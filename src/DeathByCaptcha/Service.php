<?php namespace Captcha\DeathByCaptcha;

use Captcha\DeathByCaptcha\Abstracts\Client;
use Captcha\DeathByCaptcha\SocketClient;
use Captcha\Interfaces\ServiceInterface;

/**
 *
 */
class Service implements ServiceInterface {

	/**
	 * Username
	 * @var string
	 */
	private $username;

	/**
	 * Password
	 * @var string
	 */
	private $password;

	/**
	 * verbose request
	 * @var boolean
	 */
	private $is_verbose = false;

	/**
	 * SocketClient instance
	 * @var Captcha\DeathByCaptcha\SocketClient
	 */
	private $SocketClient;

	/**
	 * timeout request
	 * @var integer
	 */
	const DEFAULT_TIMEOUT = Client::DEFAULT_TIMEOUT;

	/**
	 * captcha id
	 * @var string
	 */
	private $captcha_id;

	/**
	 * Set credentials
	 * @param  string $username
	 * @param  string $password
	 * @return Service
	 */
	public function credentials($username, $password) {
		$this->SocketClient = new SocketClient($username, $password);

		return $this;
	}

	/**
	 * request decode captcha in service
	 * @param  string $captcha base64 or path image
	 * @param  null|array $extra
	 * @return Array
	 */
	public function upload($captcha, $extra = []) {
		$captcha = $this->SocketClient->upload($captcha, $extra);

		$this->captcha_id = $captcha['captcha'];

		/**
		 * overload service to get response
		 */
		if ($captcha) {
			sleep(2);

			while (5 <= 5) {
				if ($text = $this->text($captcha['captcha'])) {
					break;
				}
				sleep(3);
			}
		}

		return $text;
	}

	/**
	 * Report captcha not resolved
	 * @param  string $captcha captcha id
	 * @return Service
	 */
	public function report($captcha) {
		$this->SocketClient->upload($captcha);

		return $this;
	}

	/**
	 * Return captcha text
	 * @return string
	 */
	public function text($captcha_id) {
		$text = $this->SocketClient->get_text($captcha_id);

		return $text;
	}

	/**
	 * set verbose request
	 * @param  boolean $verbose
	 * @return Service
	 */
	public function verbose($verbose = true) {
		$this->is_verbose = $verbose;

		$this->SocketClient->is_verbose = $this->is_verbose;

		return $this;
	}

	/**
	 * Set timeout request
	 * @param  integer $timeout
	 * @return Service
	 */
	public function timeout($timeout) {
		$this->timeout = $timeout;

		return $this;
	}

	/**
	 * Return account balance
	 * @return string|integer
	 */
	public function balance() {
		return $this->SocketClient->balance;
	}
}