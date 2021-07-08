# Laravel / Lumen HTTP/2 Server Push Middleware

[![Build Status](https://secure.travis-ci.org/tomschlick/laravel-http2-server-push.png)](http://travis-ci.org/tomschlick/laravel-http2-server-push)
[![StyleCI](https://styleci.io/repos/64423074/shield)](https://styleci.io/repos/64423074)
[![Latest Stable Version](https://poser.pugx.org/tomschlick/laravel-http2-server-push/v/stable)](https://packagist.org/packages/tomschlick/laravel-http2-server-push)
[![Total Downloads](https://poser.pugx.org/tomschlick/laravel-http2-server-push/downloads)](https://packagist.org/packages/tomschlick/laravel-http2-server-push)
[![Latest Unstable Version](https://poser.pugx.org/tomschlick/laravel-http2-server-push/v/unstable)](https://packagist.org/packages/tomschlick/laravel-http2-server-push)
[![License](https://poser.pugx.org/tomschlick/laravel-http2-server-push/license)](https://packagist.org/packages/tomschlick/laravel-http2-server-push)


A middleware package for Laravel 5 / Lumen to enable server push for your script, style, and image assets.

## Installation

First start by adding the package to your composer.json file
```bash
composer require tomschlick/laravel-http2-server-push
```

Next add the service provider to your `config/app.php` file:
```
\TomSchlick\ServerPush\ServiceProvider::class,
```


Then add the middleware to your Http Kernel (`app/Http/Kernel.php`). Do so towards the end of the list.
```php
protected $middleware = [
    \TomSchlick\ServerPush\Http2ServerPushMiddleware::class,
];
protected $routeMiddleware = [
    'http2'             => \TomSchlick\ServerPush\Http2ServerPushMiddleware::class,
];
```

## Usage
Now when you enable it on a route it will automatically include the resources in your elixir `/build/rev-manifest.json` file. 
To add a resource manually you may use `pushStyle($pathOfCssFile)`, `pushScript($pathOfJsFile)`, `pushFont($pathOfFontFile)` or `pushImage($pathOfImageFile)` from anywhere in your project.

```
Route::group(['middleware' => ['http2:frontend']], function () {
    //...
});
```
