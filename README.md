# Laravel Eloquent UUID

[![Packagist](https://img.shields.io/packagist/v/jamesmills/eloquent-uuid.svg?style=for-the-badge)](https://packagist.org/packages/jamesmills/eloquent-uuid)
![Packagist](https://img.shields.io/packagist/dt/jamesmills/eloquent-uuid.svg?style=for-the-badge)
![Packagist](https://img.shields.io/packagist/l/jamesmills/eloquent-uuid?style=for-the-badge)
[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen?style=for-the-badge)](https://plant.treeware.earth/jamesmills/eloquent-uuid)

A Laravel Eloquent Model trait for adding and using a uuid with models.

The trait listens to the `creating` event. It generates a new UUID and saves it in the uuid column on the model. 

Featured in [Laravel News](https://laravel-news.com/eloquent-uuid-package-for-laravel)

## Installation

```
composer require jamesmills/eloquent-uuid
```

## Use

In order to use this in your models, just put `use HasUuidTrait;`

```php
<?php

namespace App;
use JamesMills\Uuid\HasUuidTrait;

class User extends Eloquent
{
	use HasUuidTrait;
}
```

## Schema requirements

In order to use this trait, your **schema** must be something like:

```php
<?php
	// ...
	Schema::create('users', function (Blueprint $table) {
		$table->primary('id');
		$table->uuid('uuid')->unique(); // this will create a CHAR(36) field
		$table->string('username', 32);
		$table->string('password', 50);
		// ...
	});
```

## Querying your models

You may use the `findByUuidOrFail` method to try and fetch a model directly:

```php
<?php

Route::get('/user/{uuid}', function($uuid) {
    try {
        return App\User::findByUuidOrFail($uuid);
    } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        abort(404);
    }
});
```
Since `uuid` gets registered as `Route Key` using implicit binding[https://laravel.com/docs/5.8/routing#implicit-binding], your resource controllers will use `uuid` instead of default `id` column.

```php
<?php

    php artisan make:controller UserController --resource
```
/users/{user} route uses `uuid` i.e. /users/bff37872-1450-47c7-b9f7-9a6d917796cf

You may also use the `withUuid` and `withUuids` local query scopes with the query builder.

```php
<?php

Route::get('/user/{uuid}', function($uuid) {
    $user = App\User::withUuid($uuid)->first();
    if (! $user) {
        // Do something else...
    }
});
```
```php
<?php

Route::delete('/users', function(Request $request) {
    // Receive an array of UUIDs
    $uuids = $request->input('uuids');

    // Try to get the Users
    $users = App\User::withUuids($uuids)->all();
    
    // Handle the delete and return
    $users->delete();
});
```

## Licence

This package is 100% free and open-source, under the MIT license. Use it however you want.

This package is [Treeware](https://treeware.earth). If you use it in production, then we ask that you [**buy the world a tree**](https://plant.treeware.earth/jamesmills/eloquent-uuid) to thank us for our work. By contributing to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.
