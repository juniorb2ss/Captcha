<?php namespace Captcha\Laravel;

use Captcha\DeathByCaptcha\Service;
use Illuminate\Support\ServiceProvider;

class ServicesProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerService();
		$this->app->alias('dbc', 'Captcha\DeathByCaptcha\Service');
	}

	/**
	 * Register the Sintegra instance.
	 *
	 * @return void
	 */
	protected function registerService() {
		$this->app->bind('dbc', function () {
			return new Service;
		});
	}
}