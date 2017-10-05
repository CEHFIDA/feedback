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

Notification
```
php artisan vendor:publish --provider="Selfreliance\Feedback\FeedbackServiceProvider" --tag="notification" --force
```

Javascript
```
php artisan vendor:publish --provider="Selfreliance\Feedback\FeedbackServiceProvider" --tag="javascript" --force
```

Connect javascript
```html
<script src="{{ asset('vendor/contactform/contact.js') }}"></script>
```

Migrate
```php
php artisan migrate
```

## Functions

```php
/*
  @ param $id (integer)
  @ request type (get)
*/
function show($id) // get all about feedback and show blade 'show'
$this->show(1) // usage

/*
  @ param $id (integer)
  @ param $request (post)
*/
function send($id, Request $request) // sends a message on id feedback (email), transmit data: subject, message (required)

/*
  @ param $id (integer)
  @ request type (delete)
*/
function destroy($id) // delete feedback with $id
$this->destroy(1) // usage
```

## Usage

```
	Transimt data to url (/contacts) - method POST:
		- name (required)
		- email (required)
		- subject (required)
		- msg (required)
		- phone
```

Do not forget about result
```php 
<div id = "result"></div>
```