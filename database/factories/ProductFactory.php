<?php
// http://zetcode.com/php/faker/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'brand' => $faker->city,
        'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 500),
        'quantity' => $faker->numberBetween(1, 300)
    ];
});
