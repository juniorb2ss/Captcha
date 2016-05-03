<?php namespace Captcha\DeathByCaptcha;

use Captcha\DeathByCaptcha\Interfaces\ServiceInterface;

/**
 *
 */
class Service extends ServiceInterface {

	/**
	 * [$username description]
	 * @var [type]
	 */
	private $username;

	/**
	 * [$password description]
	 * @var [type]
	 */
	private $password;

	/**
	 * [credentials description]
	 * @param  [type] $username [description]
	 * @param  [type] $password [description]
	 * @return [type]           [description]
	 */
	public function credentials($username, $password) {
		# code...
	}

	/**
	 * [decode description]
	 * @param  [type] $captcha_filename [description]
	 * @param  [type] $extra            [description]
	 * @return [type]                   [description]
	 */
	public function decode($captcha_filename, $extra) {
		# code...
	}

	/**
	 * [text description]
	 * @return [type] [description]
	 */
	public function text($captcha) {
		# code...
	}
}