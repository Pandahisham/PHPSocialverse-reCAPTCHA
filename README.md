## What is reCAPTCHA?
> reCAPTCHA is a free service that protects your site from spam and abuse. It uses advanced risk analysis engine to tell humans and bots apart.
With the new API, a significant number of your valid human users will pass the reCAPTCHA challenge without having to solve a CAPTCHA.
reCAPTCHA comes in the form of a widget that you can easily add to your blog, forum, registration form, etc.
> - [Google RECAPTCHA](https://developers.google.com/recaptcha/)

![New Google reCAPTCHA](https://developers.google.com/recaptcha/images/newCaptchaAnchor.gif)

### Requirements
    
    - PHP >= 5.4.0
    - ext-curl: *
    - ext-json: *

To use reCAPTCHA, you need to have a `site key` and a `secret key`. [Click here](https://www.google.com/recaptcha/admin) to setup a domain and get your keys.

The `site key` is using for the widget and the `secret key` is used to validate the response we get from Google.

For more details, check the [official documentation](https://developers.google.com/recaptcha/).

# INSTALLATION

## Composer
You can install this package via [Composer](http://getcomposer.org/). Add this to your `composer.json` :

```json
{
    "require": {
        "phpsocialverse/recaptcha": "dev-master"
    }
}
```    

Then install it via `composer install` or `composer update`.

## Laravel

### Setup
Once the package is installed, you can register the service provider in `app/config/app.php` in the `providers` array:

```php
'providers' => [
    ...
    'PHPSocialverse\ReCaptcha\Laravel\ServiceProvider',
],
```

And the facade in the `aliases` array:

```php
'aliases' => [
    ...
    'Captcha' => 'PHPSocialverse\ReCaptcha\Laravel\Facade',
],
```

### Configuration
There is not really a need to publish the configuration file. Both the `secret` and `sitekey` should be set in your environment file so it won't be available in your versioning system.

##### Option 1:
See [Protecting Sensitive Configuration](http://laravel.com/docs/4.2/configuration#protecting-sensitive-configuration) if you don't know how to setup environment variables in Laravel `>= v4.1`.

````php
<?php
/**
 * Edit your .env.php or .env.*.php (If you know what i mean)
 * By adding this two lines and fill it with your keys.
 */
return [
    'RECAPTCHA_SECRET'  => 'your-secret-key',
    'RECAPTCHA_SITEKEY' => 'your-site-key'
];

````

##### Option 2:
Run `php artisan config:publish phpsocialverse/recaptcha` to publish the config file.
    
Edit the `secret` and `sitekey` values in `app/config/packages/phpsocialverse/re-captcha/config.php` file:

```php
<?php

return [
    'secret'  => getenv('RECAPTCHA_SECRET')  ?: 're-captcha-secret',
    'sitekey' => getenv('RECAPTCHA_SITEKEY') ?: 're-captcha-sitekey',
    'lang'    => app()->getLocale(),
];
```

###### To :

```php
<?php

return [
    'secret'  => 'your-secret-key',
    'sitekey' => 'your-site-key',
    'lang'    => 'en', // You can remove this line, it take the lang from config/app.php => 'locale'
];
```

# USAGE

## Hard Coded
Checkout example below:
```php
<?php

require_once "vendor/autoload.php";

use PHPSocialverse\ReCaptcha\ReCaptcha;

$secret  = 'your-secret-key';
$sitekey = 'your-site-key';
$captcha = new ReCaptcha($secret, $sitekey);

if ( ! empty($_POST)) {
    $response = $_POST['g-recaptcha-response'];
    $result   = $captcha->verify($response);

    echo $result === true
        ? 'Yay ! You are a human.'
        : 'No ! You are a robot.';

    exit();
}
?>

<form action="?" method="POST">
    <?php echo $captcha->display(); ?>
    <button type="submit">Submit</button>
</form>


<?php
// At the bottom, before the </body> (If you're a good boy and you listen to your mother)
echo $captcha->script();
?>
```

## Laravel

### Views
Insert reCAPTCHA inside your form using one of this examples:

##### By using Blade syntax
```php
{{ Form::open([...]) }}
    // Other inputs... 
    {{ Form::captcha() }}
    {{ Form::submit('Submit') }}
{{ Form::close() }}

// Remember what your mother told you
{{ Captcha::script() }}
```

###### OR

```php
{{ Form::open([...]) }}
    // Other inputs... 
    {{ Captcha::display() }}
    {{ Form::submit('Submit') }}
{{ Form::close() }}

// Same thing
{{ Captcha::script() }}
```

##### Without using Blade syntax
```php
<?php
echo Form::open([...]);
    // Other inputs... 
    echo Form::captcha();
    echo Form::submit('Submit');
echo Form::close();
?>

<?php echo Captcha::script(); ?>
```

### Back-end (Controller or somewhere in your project ...)
To validate the response we get from Google, your can use the `captcha` rule in your validator:

```php
$inputs   = Input::all();
$rules    = [
    // Other validation rules...
    'g-recaptcha-response' => 'captcha',
];
$messages = [
    'g-recaptcha-response.captcha' => 'Your custom validation message.',
];

$validator = Validator::make($inputs, $rules, $messages);

if ($validator->fails()) {
    $errors = $validator->messages();
    
    var_dump($errors->first('g-recaptcha-response'));
    
    // Redirect back or throw an error
}
```

If you want to manage the localized messages, edit the `validation.php` files inside your lang directory.

For example:
```php
// app/lang/en/validation.php
return [
    ...
    // Add this line with your custom message
    "captcha" => "If you read this message, then you're a robot.",
];
```
```php
// app/lang/fr/validation.php
return [
    ...
    // Ajoutez cette ligne avec votre message personnalisé
    "captcha" => "Si vous lisez ce message, alors vous êtes un robot.",
];
```

```php
$validator = Validator::make(Input::all(), [
    // Other validation rules...
    'g-recaptcha-response' => 'captcha',
]);

if ($validator->fails()) {
    $errors = $validator->messages();
    
    var_dump($errors->first('g-recaptcha-response'));
    
    // Redirect back or throw an error
}
```

## Contribution

Any ideas are welcome. Feel free the submit any issues or pull requests.

  