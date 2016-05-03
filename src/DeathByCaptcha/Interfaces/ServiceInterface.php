<?php namespace Captcha\DeathByCaptcha\Interfaces;

/**
* 
*/
interface ServiceInterface
{
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
	 * Set credentials
	 * @param  string $username 
	 * @param  string $password
	 * @return Service
	 */
	public function credentials($username, $password);

	/**
	 * request decode captcha in service
	 * @param  string $captcha base64 or path image
	 * @param  array $extra            
	 * @return Service
	 */
	public function upload($captcha, $extra);

	/**
	 * Return captcha text
	 * @return string $captcha_id
	 */
	public function text($captcha);
}