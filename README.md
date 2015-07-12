# Cropp

Laravel 5 package for image manipulations on-the-fly.

## Installation 

You can install the package through Composer.
```bash
composer require yaro/cropp
```
You must install this service provider.
```php
// Laravel 5: config/app.php
'providers' => [
    //...
    Yaro\Cropp\ServiceProvider::class,
    //...
];
```

Then publish the config file of the package using artisan.
```bash
php artisan vendor:publish --provider="Yaro\Cropp\ServiceProvider"
```
This package comes with [Intervention Image](http://image.intervention.io/) package, so publish its configs too.
```bash
php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5"
```
> There is no need to add Intervention Image service provider in 'providers' array.

## Usage
Simple wrap ypur image source on `cropp` function and call api methods.
```php
<img src="{{ cropp('image.jpg')->invert()->fit(320, 200) }}" />
// <img src="http://example.com/storage/cropp/61bb83eae21cb5559fe0c583f14b0374.jpg">
```
Or not to wrap result on `asset`:
```php
<img src="{{ cropp('image.jpg', false)->greyscale()->rotate(-45)->resize(500, null) }}" />
// <img src="/storage/cropp/44aead54d338966bca06535d34edc3ae.jpg">
```
To get generated source path use `src` method:
```php
$thumbPath = cropp('image.jpg')->invert()->fit(320, 200)->src();
```
You can also initialise Cropp without helper function:
```php
$cropp = new Cropp('image.jpg', false);
$src = $cropp->fit(320, 200)->src(); 
echo $src; // /storage/cropp/44aead54d338966bca06535d34edc3ae.jpg
// or
$src = Cropp::make('image.jpg')->fit(320, 200)->src();
echo $src; // http://example.com/storage/cropp/61bb83eae21cb5559fe0c583f14b0374.jpg
```


## API
All manipulation methods is provided by [Intervention Image](http://image.intervention.io/) package.


## License
The MIT License (MIT). Please see [LICENSE](https://github.com/Cherry-Pie/Cropp/blob/master/LICENSE) for more information.
