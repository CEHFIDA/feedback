# Laravel 5 Admin Amazing feedback
feedback - a package that allows you to control letters that have been sent through the contact form

## Documentation

- [Contact form](#contact-form)
	- [Connect script](#connect-script-in-your-blade)
	- [Create form](#create-form)
	- [Settings captcha](#settings-captcha)
	- [Connect captcha](#connect-captcha-in-your-blade)
- [Parser messages](#parser-messages)
	- [Settings](#settings)
	- [Call manually](#call-manually)
	- [Artisan command](#artisan-command)

## Require

- [adminamazing](https://github.com/selfrelianceme/adminamazing)
- [recaptcha](https://github.com/greggilbert/recaptcha)
- [laravel-imap](https://github.com/Webklex/laravel-imap)
- configured and connected mail server

## How to install

Install via composer
```
composer require selfreliance/feedback
```

Publish config, javascript
```php
php artisan vendor:publish --provider=Selfreliance\\feedback\\FeedbackServiceProvider --force
```

### Also you can connect the information block
Edit value blocks in config (config/adminamazing.php)
```
'blocks' => [
    //
    'countFeedback' => 'Selfreliance\Feedback\FeedbackController@registerBlock',
]
```

## Contact form

### Connect script in your blade

```html
<script src="{{ asset('js/core.js') }}"></script>
```

### Create form

Transmit data to url (/contacts or url from config feedback) - method POST:
```
- name (required),
- email (required),
- subject (required),
- msg (required),
- phone
```

### Settings captcha

Add the service provider to the providers array in config/app.php
```php
'providers' => [
	'Greggilbert\Recaptcha\RecaptchaServiceProvider::class,
];
```

Add the aliases to the aliases array
```php
'aliases' => [
	'Recaptcha' => Greggilbert\Recaptcha\Facades\Recaptcha::class,
];
```

Publish config
```php
php artisan vendor:publish --provider=Greggilbert\\Recaptcha\\RecaptchaServiceProvider
```

## In /config/recaptcha.php, enter your reCAPTCHA public and private keys

### Connect captcha in your blade
```php
@if(config('feedback.captcha') == true)
{!! \Recaptcha::render() !!}
@endif
```

## Parser messages

### Settings

Add the service provider to the providers array in config/app.php
```php
'providers' => [
    Webklex\IMAP\Providers\LaravelServiceProvider::class,
];
```

Add the aliases to the aliases array
```php
'aliases' => [
    'Client' => Webklex\IMAP\Facades\Client::class
];
```

Publish
```php
php artisan vendor:publish --provider=Webklex\\IMAP\Providers\\LaravelServiceProvider
```

### Call manually
```php
$messages = EmailParser::getInbox(); // get all messages from mail
EmailParser::parseMessages($messages, false); // parse messages, false (EnableQuotes)
unset($messages); // unset all messages
```

### Artisan command
```php
php artisan email:parser // 'Parse email successfuly'
```