# Laravel 5 Admin Amazing feedback
feedback - a package that allows you to control letters that have been sent through the contact form

## Require

- [adminamazing](https://github.com/selfrelianceme/adminamazing)
- configured and connected mail server

## How to install

Install via composer
```
composer require selfreliance/feedback
```

Migrations
```php
php artisan vendor:publish --provider="Selfreliance\Feedback\FeedbackServiceProvider" --tag="migrations" --force
```

Javascript
```php
php artisan vendor:publish --provider="Selfreliance\feedback\FeedbackServiceProvider" --tag="javascript" --force
```

Config
```php
php artisan vendor:publish --provider="Selfreliance\feedback\FeedbackServiceProvider" --tag="config" --force
```

Connect javascript
```html
<script src="{{ asset('js/core.js') }}"></script>
```

Migrate
```php
php artisan migrate
```

## Usage

```
	Transimt data to url (/contacts or from config feedback) - method POST:
		- name (required)
		- email (required)
		- subject (required)
		- msg (required)
		- phone
		- captcha (if you want to tie the CAPTCHA to the form)
```
