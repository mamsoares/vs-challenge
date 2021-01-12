<?php

//php artisan make:controller ProductController --api

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\ProductCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{
	private $perPage = 10;
	private $orderBy = '';

    public function __construct() {
        // $this->middleware('jwt.auth', ['except' => ['index', 'show', 'store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $products = (new Product);

        if( $request->has('filter') ) {
        	$filter = explode(';', $request->get('filter') );

        	foreach($filter as $f){
        		$cond = explode(':', $f);

        		$products = $products->where($cond[0], $cond[1], $cond[2]);
        	}

        	//Debug
        	// return response()->json( $filter );
        }

        if ( $request->has('fields') ) {
        	$fields = $request->get('fields');

        	$products = $products->selectRaw($fields);
        }

        if ( $request->has('perpage') ) {
        	$this->perPage = $request->get('perpage');
        }
       
        if ( $request->has('orderby') ) {
        	$order = explode(':', $request->query('orderby'));

        	$products = $products->orderBy($order[0], $order[1]);
        }

        if ( $request->has('page') ) {

        	$pageNumber = $request->get('page');
        }

		return $products->paginate($this->perPage, ['*'], 'page', $pageNumber = null);
        // return new ProductCollection($products->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product();
        $product->fill($request->all());
        $product->save();

        return response()->json( new ProductResource( $product ), 201 );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
		$product = Product::findOrfail($id);

        return response()->json( new ProductResource( $product ), 200);	    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {

		$product = Product::findOrfail($id);

        $product->fill( $request->all() );
        $product->save();

        return response()->json(new ProductResource( $product ), 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(int $id)
    {
        $product = Product::findOrfail($id);

        $product->delete();

        return response()->json(null, 204);

    }
}
