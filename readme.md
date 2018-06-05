# Entitize

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require wcr/entitize
```

## Usage
#### 1. Publish components ####
``` bash
$ php artisan vendor:publish --tag:entitize
```

#### 2. Create Model whith Migration ####
``` bash
$ php artisan make:model Book -m
```
edit migration `/database/migrations/0000_00_00_000000_create_books_table.php`
``` php
<?php
/** more code **/
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('autor');
            $table->date('published_at')->nullable();
            $table->boolean('deleted')->default(0); // This field is REQUIRED
            $table->timestamps();
        });
    }
/** more code **/
?>
```
Launch migration
``` bash
$ php artisan migrate
```

#### 3. Create controller ####
``` bash
$ php artisan make:controller BookController
```

#### 4. use entitize in controlle ####
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Wcr\Entitize\Controllers\Entitize;

use App\Book;

class BookController extends Controller
{
    use Entitize;

    public $tableParams = ['Id'=>'id', 'Title'=>'title', 'Author'=>'author', 'Created at'=>'created_at'];

    public $fields = array(
        [
            'name' => 'title',
            'label' => 'Title',
            'validation' => 'required'
        ],
        [
            'name' => 'author',
            'label' => 'Author',
            'validation' => 'required'
        ],
        [
            'name' => 'published_at',
            'label' => 'Published at',
            'type' => 'date'
        ],
    );
}
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [Francesco Monti][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/francescomonti/entitize.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/francescomonti/entitize.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/francescomonti/entitize/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/wcr/entitize
[link-downloads]: https://packagist.org/packages/wcr/entitize
[link-travis]: https://travis-ci.org/francescomonti/entitize
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/francescomonti
[link-contributors]: ../../contributors]
