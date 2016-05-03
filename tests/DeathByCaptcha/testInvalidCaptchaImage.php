<?php namespace tests\DeathByCaptcha;

use Captcha\DeathByCaptcha\Service;

class testInvalidCaptchaImage extends \PHPUnit_Framework_TestCase {

	/**
	 * @group dbc-image
	 */
	public function testFileExist() {
		$file = dirname(__FILE__) . '/../invalid.png';
		$this->assertFileExists($file, 'Captcha image file not present to test');

		return 'data:image/png;base64,' . base64_encode(file_get_contents($file));
	}

	/**
	 * Testa retorno de captcha inválido
	 * @group dbc-image
	 * @expectedException \Captcha\DeathByCaptcha\Exceptions\InvalidCaptchaException
	 * @depends testFileExist
	 */
	public function testWithoutImage($image) {
		// load env
		if (file_exists(__DIR__ . '/../../.env')) {
			$dotenv = new \Dotenv\Dotenv(__DIR__ . '/../../');
			$dotenv->load();
		}

		$service = new Service;

		$service->credentials(getenv('DBC_USER'), getenv('DBC_PASSWORD'));

		$text = $service->upload($image);
	}
}