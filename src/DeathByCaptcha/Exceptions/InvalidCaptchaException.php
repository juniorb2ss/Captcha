<?php namespace Captcha\DeathByCaptcha\Exceptions;

use Captcha\DeathByCaptcha\Exceptions\ClientException;

/**
 * Exception to throw on invalid CAPTCHA image payload: on empty images, on images too big, on non-image payloads.
 *
 * @package DBCAPI
 * @subpackage PHP
 */
class InvalidCaptchaException extends ClientException {}