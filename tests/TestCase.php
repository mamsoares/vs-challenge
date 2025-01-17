<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;

    protected function setUp(): void {
    	parent::setUp();

    	$this->artisan('migrate:reset');
    	$this->artisan('migrate');
    	$this->artisan('db:seed');

    	// $this->withoutExecptionHandling();
    }

    public function create(string $model, array $attributes = [], $resource = true)
    {

        $resourceModel = factory("App\\$model")->create($attributes);
        $resourceClass = "App\\Http\Resources\\$model";

        if(!$resource)
            return $resourceModel;

        return new $resourceClass($resourceModel);
    }

}
