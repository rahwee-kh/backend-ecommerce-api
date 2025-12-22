<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApi;
use Throwable;
use App\Models\Api\Product;
use App\Services\SVProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductRequest;

class ProductController extends BaseApi
{
    public function getService()
    {
        return new SVProduct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        try{
            $data = $this->getService()->getListProduct($request->all());
            return $data;
        }catch(Throwable $e)
        {
            return $this->respondError($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try{
            $params = $request->all();
            $data = $this->getService()->store($params);
            DB::commit();
            return $this->respondSuccess($data);
        }catch (\Exception $e){
            DB::rollBack();
            return $this->respondError($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        try{
            return $this->getService()->show($product);
        }catch(Throwable $e)
        {
            return $this->respondError($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $data = $this->getService()->update($request->validated(), $product);
            DB::commit();
            return $this->respondSuccess($data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->respondError($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try{
            $data = $this->getService()->delete($product);
            DB::commit();
            return $this->respondSuccess($data);
        }catch (\Exception $e){
            DB::rollBack();
            return $this->respondError($e);
        }
    }
}
