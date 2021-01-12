<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Product extends Model
{
	use SoftDeletes;

	protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $orderable = [
        'id',
        'name',
        'brand',
        'quantity',
        'price',
    ];

    protected $filterable = [
        'id',
        'name',
        'brand',
        'quantity',
        'price',
    ];

    protected $fillable = [
        'name',
        'brand',
        'price',
        'quantity',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $guarded = ['id'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
