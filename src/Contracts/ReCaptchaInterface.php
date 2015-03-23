<?php namespace PHPSocialverse\ReCaptcha\Contracts;

use PHPSocialverse\ReCaptcha\Contracts\Utilities\AttributesInterface;
use PHPSocialverse\ReCaptcha\Contracts\Utilities\RequestInterface;

interface ReCaptchaInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set HTTP Request Client
     *
     * @param  RequestInterface $request
     *
     * @return ReCaptchaInterface
     */
    public function setRequestClient(RequestInterface $request);

    /**
     * Set noCaptcha Attributes
     *
     * @param  AttributesInterface $attributes
     *
     * @return ReCaptchaInterface
     */
    public function setAttributes(AttributesInterface $attributes);

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Display Captcha
     *
     * @param  array $attributes
     *
     * @return string
     */
    public function display($attributes = []);

    /**
     * Display image Captcha
     *
     * @param  array $attributes
     *
     * @return string
     */
    public function image($attributes = []);

    /**
     * Display audio Captcha
     *
     * @param  array $attributes
     *
     * @return string
     */
    public function audio($attributes = []);

    /**
     * Verify Response
     *
     * @param  string $response
     * @param  string $clientIp
     *
     * @return bool
     */
    public function verify($response, $clientIp = null);

    /**
     * Get script tag
     *
     * @return string
     */
    public function script();
}
