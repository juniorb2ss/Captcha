<?php namespace Captcha\DeathByCaptcha;

use Captcha\DeathByCaptcha\Interfaces\ServiceInterface;
use Captcha\DeathByCaptcha\Abstracts\Client;

/**
 *
 */
class Service extends ServiceInterface {

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
	 * timeout request
	 * @var integer
	 */
	private $timeout = Client::DEFAULT_TIMEOUT;

	/**
	 * Set credentials
	 * @param  string $username 
	 * @param  string $password
	 * @return Service
	 */
	public function credentials($username, $password) {
		# code...
	}

	/**
	 * request decode captcha in service
	 * @param  string $captcha base64 or path image
	 * @param  array $extra            
	 * @return Service
	 */
	public function upload($captcha, $extra) {
		# code...
	}

	/**
	 * Return captcha text
	 * @return string $captcha_id
	 */
	public function text($captcha_id) {
		# code...
	}

	/**
	 * set verbose request
	 * @param  boolean $verbose 
	 * @return Service
	 */
	public function verbose($verbose = true) {
		$this->is_verbose = $verbose

		return $this;
	}

	/**
	 * Set timeout request
	 * @param  integer $timeout
	 * @return Service
	 */
	public function timeout($timeout)
	{
		$this->timeout = $timeout;

		return $this;
	}
}