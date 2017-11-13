# Laravel 5 Admin Amazing feedback
feedback - a package that allows you to control letters that have been sent through the contact form

## Require

- [adminamazing](https://github.com/selfrelianceme/adminamazing)
- [laravel-imap](https://github.com/Webklex/laravel-imap)
- configured and connected mail server

## How to install

Install via composer
```
composer require selfreliance/feedback
```

Migrations, javascript, command
```php
php artisan vendor:publish --provider="Selfreliance\feedback\FeedbackServiceProvider" --force
```

## Laravel imap (required)

With the package there is another package of Laravel imap, for convenient parsing

[read this](https://github.com/Webklex/laravel-imap/blob/master/README.md)

##

Connect javascript
```html
<script src="{{ asset('js/core.js') }}"></script>
```

Edit model Kernel (App/Console/Kernel.php)

Add to $commands
```
Commands\EmailParser::class
```

Add to protected function schedule and [setup cron](https://scotch.io/@Kidalikevin/how-to-set-up-cron-job-in-laravel)
```
$schedule->command('email:parse')->everyMinute()->withoutOverlapping(); // p.s Mail parsing takes place every minute
```

And do not forget about
```php
php artisan migrate
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

## Call the parser manually
```php
use Selfreliance/Feedback/FeedbackController;

$parse = FeedbackController::parse_email();
if($parse) $this->info('Parse email successfuly.');
else $this->error('Parse email error.');
```