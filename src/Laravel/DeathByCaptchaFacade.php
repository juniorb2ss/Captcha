<?php namespace Captcha\Laravel;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Weidner\Goutte\Goutte
 */
class DeathByCaptchaFacade extends Facade {

  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return 'dbc'; }

}