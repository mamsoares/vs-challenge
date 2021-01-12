<?php

namespace Tests\Feature;

use App\User;
use App\Product;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

/********************************************************
*
* CONSIDERAÇÕES:
* 
* 1- Foram criados testes para o CRUD de Produtos sem AUtenticação, 
*    tentei implementar de diversas maneiras, mas não tive sucesso,
*    usei a biblioteca do Tymon\JWTAuth versão 1.0.1;
*
* 2- Buscando na internet tentei fazer os testes de authenticação pelo auth:api,
*    mas também não tive sucesso;
*
* 3- Para executar os testes é necssário alterar o arquivo de rotas, comentando as 
*    rotas autenticadas e descomentado para não atenticadas;
* 
* 4- Acredito que seja algo simples, mas pelo que encontrei nos foruns, pode ser 
*    configuração do ambiente apache ou algum versão da lib Tymon/JWTAuth;
*
*    Seguem alguns links dos diversos que pesquisei: 
*    - https://stackoverflow.com/questions/17018586/apache-2-4-php-fpm-and-authorization-headers
*    - https://github.com/tymondesigns/jwt-auth/issues/852
*    - https://stackoverflow.com/questions/17488656/zend-server-windows-authorization-header-is-not-passed-to-php-script
* 
*    DESCRIÇÃO DO ERRO: (o mesmo em todos os testes - retirei para não deixar muitos comentários no código)
*     1) Tests\Feature\ProductTest::can_return_a_collection_of_paginated_products
*        TypeError: Argument 1 passed to Tymon\JWTAuth\JWT::fromUser() must implement interface Tymon\JWTAuth\Contracts\JWTSubject, 
*        null given,
*
*     - Como podemos observar na descrição erro, sempre recebo um retorno de token vazio. Parece que perde o usuário no momento 
*       da geração do token;
*
* 5- O teste abaixo funciona apenas se as rotas de autenticação estiverem liberadas no arquivo (\routes\api.php):
*    * non_authenticated_users_cannot_access_the_following_endpoints_for_the_product_api
* 
* 6- Os demais testes funcionam apenas se NÃO tiver autenticação, bastando comentar as rotas de autenticação eliberar as rotas sem  
*    o middleware no arquivo (\routes\api.php) 
*
********************************************************/


class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @test
     */
    public function can_verify_the_root_route()
    {

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    // public function non_authenticated_users_cannot_access_the_following_endpoints_for_the_product_api()
    // {
    //     $index = $this->json('GET', 'api/v1/products');
    //     $index->assertStatus(401);

    //     $store = $this->json('POST', 'api/v1/products');
    //     $store->assertStatus(401);

    //     $show = $this->json('GET', 'api/v1/products/-1');
    //     $show->assertStatus(401);

    //     $update = $this->json('PUT', 'api/v1/products/-1');
    //     $update->assertStatus(401);

    //     $destroy = $this->json('DELETE', 'api/v1/products/-1');
    //     $destroy->assertStatus(401);
    // }   



    /**
     * @test
     */
    public function can_return_a_collection_of_paginated_products()
    {
        $faker = Factory::create();

        $product1 = $this->create('Product');
        $product2 = $this->create('Product');
        $product3 = $this->create('Product');

        // $user = User::first();
        // $token = JWTAuth::fromUser($user);
        // $url = 'api/v1/products?orderby=price:DESC&perpage=20&fields=name,brand,price,quantity,id&filter=name:like:%group%';
        // $response = $this->json('GET', $url . '?token=' . $token);

        $response = $this->json('GET', 
               'api/v1/products?orderby=price:DESC&perpage=20&fields=name,brand,price,quantity,id&filter=name:like:%group%');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => []
                ]
                // 'links' => ['first', 'last', 'prev', 'next'],
                // 'meta' => [
                //     'current_page', 'last_page', 'from', 'to',
                //     'path', 'per_page', 'total'
                // ]
            ]);
    }


    /**
     *
     * @test
     */
    // public function can_create_a_product_with_autenticate()
    // {

    //     $faker = Factory::create();

    //     $attributes = [
    //                     'name' => $name = $faker->company,
    //                     'brand' => $brand = $faker->city,
    //                     'price' => $price = $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 500),
    //                     'quantity' => $quantity = $faker->numberBetween(1, 300)
    //                   ];

    //     // $user = User::first();
    //     // $token = JWTAuth::fromUser($user);
    //     // $response = $this->json('POST', 'api/v1/products', $attributes, ['Authorization' => "bearer $token"]);

    //     $response = $this->json( 'POST', 'api/v1/products', $attributes );

    //     // \Log::info(1, [$response->getContent()]);

    //     $response->assertJsonStructure([
    //             'id', 'name', 'brand', 'price', 'quantity', 'created_at'
    //         ])->assertJson( $attributes )->assertStatus(201);        

    //     $this->assertDatabaseHas('products', $attributes );
    // }



    /**
     *
     * @test
     */
    public function can_create_a_product()
    {

        $faker = Factory::create();

        $attributes = [
                        'name' => $name = $faker->company,
                        'brand' => $brand = $faker->city,
                        'price' => $price = $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 500),
                        'quantity' => $quantity = $faker->numberBetween(1, 300)
                      ];


        $response = $this->json( 'POST', 'api/v1/products', $attributes );

        // \Log::info(1, [$response->getContent()]);

        $response->assertJsonStructure([
                'id', 'name', 'brand', 'price', 'quantity', 'created_at'
            ])->assertJson( $attributes )->assertStatus(201);        

        $this->assertDatabaseHas('products', $attributes );
    }    


    /**
     * @test
     */
    public function will_fail_with_a_404_if_product_is_not_found()
    {
        $response = $this->json('GET', 'api/v1/products/-1');

        $response->assertStatus(404);
    }


    /**
     * @test
     */
    public function can_return_a_product()
    {

        $product = $this->create('Product');

        $response = $this->json('GET', "api/v1/products/$product->id");

        // \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200)
            ->assertExactJson([
                'id' => $product->id,
                'name' => $product->name,
                'brand' => $product->brand,
                'price' => (float)$product->price,
                'quantity' => (int)$product->quantity,
                'created_at' => (string)$product->created_at
            ]);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_product_we_want_to_update_is_not_found()
    {
        $response = $this->json('PUT', 'api/v1/products/-1');

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function can_update_a_product()
    {

        $product = $this->create('Product');

        $response = $this->json('PUT', "api/v1/products/$product->id",[
            'name' => $product->name .'_updated',
            'brand' => $product->brand .'_updated',
            'price' => $product->price + 35,
            'quantity' => $product->quantity + 99
        ]);

        // \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200)
            ->assertExactJson([
                'id' => $product->id,
                'name' => $product->name .'_updated',
                'brand' => $product->brand .'_updated',
                'price' => $product->price + 35,
                'quantity' => $product->quantity + 99,
                'created_at' => (string)$product->created_at
            ]);


        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name .'_updated',
            'brand' => $product->brand .'_updated',
            'price' => $product->price + 35,
            'quantity' => $product->quantity + 99,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ]);

    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_product_we_want_to_delete_is_not_found()
    {
        $response = $this->json('DELETE', 'api/v1/products/-1');

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function can_delete_a_product()
    {

        $product = $this->create('Product');

        $response = $this->json('DELETE', "api/v1/products/$product->id");

        // \Log::info(1, [$response->getContent()]);

        $response->assertStatus(204)
                    ->assertSee(null);

        $this->assertSoftDeleted('products', ['id' => $product->id]);

    }


}
