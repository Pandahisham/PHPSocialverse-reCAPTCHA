<?php

return [
    'secret'  => getenv('RECAPTCHA_SECRET')  ?: 're-captcha-secret',
    'sitekey' => getenv('RECAPTCHA_SITEKEY') ?: 're-captcha-sitekey',
    'lang'    => app()->getLocale(),
];
