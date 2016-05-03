<?php namespace Captcha\DeathByCaptcha;

use Captcha\DeathByCaptcha\Abstracts\Client;
use Captcha\DeathByCaptcha\Exceptions\AccessDeniedException;
use Captcha\DeathByCaptcha\Exceptions\Exception;
use Captcha\DeathByCaptcha\Exceptions\InvalidCaptchaException;
use Captcha\DeathByCaptcha\Exceptions\IOException;
use Captcha\DeathByCaptcha\Exceptions\RuntimeException;
use Captcha\DeathByCaptcha\Exceptions\ServerException;
use Captcha\DeathByCaptcha\Exceptions\ServiceOverloadException;

/**
 * Death by Captcha HTTP API Client
 *
 * @see DeathByCaptcha_Client
 * @package DBCAPI
 * @subpackage PHP
 */
class HttpClient extends Client {
	const BASE_URL = 'http://api.dbcapi.me/api';

	protected $_conn = null;
	protected $_response_type = '';
	protected $_response_parser = null;

	/**
	 * Sets up CURL connection
	 */
	protected function _connect() {
		if (!is_resource($this->_conn)) {
			if ($this->is_verbose) {
				fputs(STDERR, time() . " CONN\n");
			}

			if (!($this->_conn = curl_init())) {
				throw new RuntimeException(
					'Failed initializing a CURL connection'
				);
			}

			curl_setopt_array($this->_conn, array(
				CURLOPT_TIMEOUT => self::DEFAULT_TIMEOUT,
				CURLOPT_CONNECTTIMEOUT => (int) (self::DEFAULT_TIMEOUT / 4),
				CURLOPT_HEADER => false,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_AUTOREFERER => false,
				CURLOPT_HTTPHEADER => array(
					'Accept: ' . $this->_response_type,
					'Expect: ',
					'User-Agent: ' . self::API_VERSION,
				),
			));
		}

		return $this;
	}

	/**
	 * Makes an API call
	 *
	 * @param string $cmd     API command
	 * @param array  $payload API call payload, essentially HTTP POST fields
	 * @return array|null API response hash table on success
	 * @throws IOException On network related errors
	 * @throws AccessDeniedException On failed login attempt
	 * @throws InvalidCaptchaException On invalid CAPTCHAs rejected by the service
	 * @throws ServerException On API server errors
	 */
	protected function _call($cmd, $payload = null) {
		if (null !== $payload) {
			$payload = array_merge($payload, array(
				'username' => $this->_userpwd[0],
				'password' => $this->_userpwd[1],
			));
		}

		$this->_connect();

		$opts = array(CURLOPT_URL => self::BASE_URL . '/' . trim($cmd, '/'),
			CURLOPT_REFERER => '');
		if (null !== $payload) {
			$opts[CURLOPT_POST] = true;
			$opts[CURLOPT_POSTFIELDS] = array_key_exists('captchafile', $payload)
			? $payload
			: http_build_query($payload);
		} else {
			$opts[CURLOPT_HTTPGET] = true;
		}
		curl_setopt_array($this->_conn, $opts);

		if ($this->is_verbose) {
			fputs(STDERR, time() . " SEND: {$cmd} " . serialize($payload) . "\n");
		}

		$response = curl_exec($this->_conn);
		if (0 < ($err = curl_errno($this->_conn))) {
			throw new IOException(
				"API connection failed: [{$err}] " . curl_error($this->_conn)
			);
		}

		if ($this->is_verbose) {
			fputs(STDERR, time() . " RECV: {$response}\n");
		}

		$status_code = curl_getinfo($this->_conn, CURLINFO_HTTP_CODE);
		if (403 == $status_code) {
			throw new AccessDeniedException(
				'Access denied, check your credentials and/or balance'
			);
		} else if (400 == $status_code || 413 == $status_code) {
			throw new InvalidCaptchaException(
				"CAPTCHA was rejected by the service, check if it's a valid image"
			);
		} else if (503 == $status_code) {
			throw new ServiceOverloadException(
				"CAPTCHA was rejected due to service overload, try again later"
			);
		} else if (!($response = call_user_func($this->_response_parser, $response))) {
			throw new ServerException(
				'Invalid API response'
			);
		} else {
			return $response;
		}
	}

	/**
	 * @see DeathByCaptcha_Client::__construct()
	 */
	public function __construct($username, $password) {
		if (!extension_loaded('curl')) {
			throw new RuntimeException(
				'CURL extension not found'
			);
		}
		if (function_exists('json_decode')) {
			$this->_response_type = 'application/json';
			$this->_response_parser = array($this, 'parse_json_response');
		} else {
			$this->_response_type = 'text/plain';
			$this->_response_parser = array($this, 'parse_plain_response');
		}
		parent::__construct($username, $password);
	}

	/**
	 * @see DeathByCaptcha_Client::close()
	 */
	public function close() {
		if (is_resource($this->_conn)) {
			if ($this->is_verbose) {
				fputs(STDERR, time() . " CLOSE\n");
			}
			curl_close($this->_conn);
			$this->_conn = null;
		}
		return $this;
	}

	/**
	 * @see DeathByCaptcha_Client::get_user()
	 */
	public function get_user() {
		$user = $this->_call('user', array());
		return (0 < ($id = (int) @$user['user']))
		? array('user' => $id,
			'balance' => (float) @$user['balance'],
			'is_banned' => (bool) @$user['is_banned'])
		: null;
	}

	/**
	 * @see DeathByCaptcha_Client::upload()
	 * @throws RuntimeException When failed to save CAPTCHA image to a temporary file
	 */
	public function upload($captcha, $extra = []) {
		$img = $this->_load_captcha($captcha);
		if ($extra['banner']) {
			$banner = $this->_load_captcha($extra['banner']);
			if ($this->_is_valid_captcha($banner)) {
				$tmp_bn = tempnam(null, 'banner');
				file_put_contents($tmp_bn, $banner);
				$extra['banner'] = '@' . $tmp_bn;
			} else {
				$extra['banner'] = '';
			}
		}
		if ($this->_is_valid_captcha($img)) {
			$tmp_fn = tempnam(null, 'captcha');
			file_put_contents($tmp_fn, $img);
			try {
				$captcha = $this->_call('captcha', array_merge(
					['captchafile' => '@' . $tmp_fn],
					$extra
				));
			} catch (Exception $e) {
				@unlink($tmp_fn);
				throw $e;
			}
			@unlink($tmp_fn);
			if (0 < ($cid = (int) @$captcha['captcha'])) {
				return array(
					'captcha' => $cid,
					'text' => (!empty($captcha['text']) ? $captcha['text'] : null),
					'is_correct' => (bool) @$captcha['is_correct'],
				);
			}
		}
		return null;
	}

	/**
	 * @see DeathByCaptcha_Client::get_captcha()
	 */
	public function get_captcha($cid) {
		$captcha = $this->_call('captcha/' . (int) $cid);
		return (0 < ($cid = (int) @$captcha['captcha']))
		? array('captcha' => $cid,
			'text' => (!empty($captcha['text']) ? $captcha['text'] : null),
			'is_correct' => (bool) $captcha['is_correct'])
		: null;
	}

	/**
	 * @see DeathByCaptcha_Client::report()
	 */
	public function report($cid) {
		$captcha = $this->_call('captcha/' . (int) $cid . '/report', array());
		return !(bool) @$captcha['is_correct'];
	}
}