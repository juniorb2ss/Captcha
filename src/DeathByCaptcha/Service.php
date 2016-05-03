<?php namespace Captcha\DeathByCaptcha;

use Captcha\DeathByCaptcha\Abstracts\Client;
use Captcha\DeathByCaptcha\Interfaces\ServiceInterface;
use Captcha\DeathByCaptcha\SocketClient;

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

		return $captcha;
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