<?php namespace tests\DeathByCaptcha;

use Captcha\DeathByCaptcha\Service;

class testInvalidCredentials extends \PHPUnit_Framework_TestCase {
	/**
	 * Testa retorno de captcha inválido
	 * @expectedException \Captcha\DeathByCaptcha\Exceptions\AccessDeniedException
	 * @group dbc-credentials
	 */
	public function testCredentials() {
		$dbc = new Service;

		$dbc->credentials('111111', '11111');

		$dbc->balance();
	}

	/**
	 * Testa retorno de captcha inválido
	 * @expectedException \Captcha\DeathByCaptcha\Exceptions\RuntimeException
	 * @group dbc-credentials
	 */
	public function testWithoutCredentials() {
		$dbc = new Service;

		$dbc->credentials('', '');

		$dbc->balance();
	}
}