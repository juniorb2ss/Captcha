<?php namespace Captcha\DeathByCaptcha\Exceptions;

use Captcha\DeathByCaptcha\Exceptions\ClientException;

/**
 * Exception to throw on rejected login attemts due to invalid DBC credentials, low balance, or when account being banned.
 *
 * @package DBCAPI
 * @subpackage PHP
 */
class AccessDeniedException extends ClientException {}