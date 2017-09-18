Laravel 5 Admin Amazing feedback
======================
after install this packages, you need install base admin
[adminamazing](https://github.com/selfrelianceme/adminamazing)

-----------------
Install via composer
```
composer require selfreliance/feedback
```

Add Service Provider to `config/app.php` in `providers` section
```php
Selfreliance\feedback\FeedbackServiceProvider::class,
```

Go to `http://myapp/admin/feedback` to view admin amazing
