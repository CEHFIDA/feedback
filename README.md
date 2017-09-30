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

Moving files
```
Vendor/Selfreliance/Feedback/Notifications/SupportNotification in App/Notifications
```

## Functions

```php
/*
  @ param $id (integer)
  @ request type (get)
*/
function show($id) // get all about feedback and show blade 'show'
$this->show($id) // usage

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
```
