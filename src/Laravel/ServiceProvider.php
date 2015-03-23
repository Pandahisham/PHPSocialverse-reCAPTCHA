<?php namespace PHPSocialverse\ReCaptcha\Laravel;

use PHPSocialverse\ReCaptcha\ReCaptcha;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package(
            'phpsocialverse/re-captcha',
            're-captcha',
            realpath(dirname(__FILE__) . '/..')
        );

        $this->registerServices();

        $this->registerValidatorRules();

        $this->registerFormMacros();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {}

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'phpsocialverse.re-captcha'
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Package Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register Services
     */
    private function registerServices()
    {
        $this->app->bind('phpsocialverse.re-captcha', function($app) {
            $config = $app['config']->get('re-captcha::config');

            return new ReCaptcha($config['secret'], $config['sitekey'], $config['lang']);
        });
    }

    /**
     * Register Validator rules
     */
    private function registerValidatorRules()
    {
        $this->app['validator']->extend('captcha', function($attribute, $value) {
            $ip = $this->app['request']->getClientIp();

            return $this->app['phpsocialverse.re-captcha']->verify($value, $ip);
        });
    }

    /**
     * Register Form Macros
     */
    private function registerFormMacros()
    {
        if ($this->app->bound('form')) {
            $this->app['form']->macro('captcha', function($attributes = []) {
                return $this->app['phpsocialverse.re-captcha']->display($attributes);
            });
        }
    }
}
