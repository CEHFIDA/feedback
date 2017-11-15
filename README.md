# Laravel 5 Admin Amazing feedback
feedback - a package that allows you to control letters that have been sent through the contact form

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

Connect javascript
```html
<script src="{{ asset('js/core.js') }}"></script>
```

## Usage

```
	Transimt data to url (/contacts or url from config feedback) - method POST:
		- name (required)
		- email (required)
		- subject (required)
		- msg (required)
		- phone
		- captcha (if you want to tie the CAPTCHA to the form)
```

## Connect captcha in your blade
```php
@if(config('feedback.captcha') == true)
{!! \Recaptcha::render() !!}
@endif
```

##

With the package there is another package of Laravel imap, for convenient parsing

[read this](https://github.com/Webklex/laravel-imap/blob/master/README.md)

##

## Call the parser manually
```php
$messages = EmailParser::getInbox(); // get all messages from mail
EmailParser::parseMessages($messages, false); // parse messages, false (EnableQuotes)
unset($messages); // unset all messages
```