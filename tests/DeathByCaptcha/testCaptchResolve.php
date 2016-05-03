<?php namespace tests\DeathByCaptcha;

use Captcha\DeathByCaptcha\Service;

class testCaptchResolve extends \PHPUnit_Framework_TestCase {
	/**
	 * @group captcha-resolve
	 */
	public function testFileExist() {
		$file = dirname(__FILE__) . '/../captcha.png';
		$this->assertFileExists($file, 'Captcha image file not present to test');

		return 'data:image/png;base64,' . base64_encode(file_get_contents($file));
	}

	/**
	 * @group captcha-resolve
	 * @depends testFileExist
	 */
	public function testResolve($basee64Image) {

		// load env
		if (file_exists(__DIR__ . '/../../.env')) {
			$dotenv = new \Dotenv\Dotenv(__DIR__ . '/../../');
			$dotenv->load();
		}

		$service = new Service;

		$service->credentials(getenv('DBC_USER'), getenv('DBC_PASSWORD'));
		$service->verbose(true);
		$text = $service->upload($basee64Image);

		$this->assertEquals('vWC5YT', $text, 'Captcha resolve fail');
	}
}