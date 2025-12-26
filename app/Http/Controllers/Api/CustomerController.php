<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use App\Models\Api\User;
use App\Models\Customer;
use App\Services\SVCustomer;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseApi;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CountryResource;

class CustomerController extends BaseApi
{
    public function getService()
    {
        return new SVCustomer();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        try{
            $data = $this->getService()->index($request->all());
            return $data;
        }catch(\Throwable $e)
        {
            return $this->respondError($e);
        }
    }

    public function show(Customer $customer)
    {
        try{
            $data = $this->getService()->show($customer);
            return $data;
        }catch(\Throwable $e)
        {
            return $this->respondError($e);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\Api\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        DB::beginTransaction();
        try {
            $data = $this->getService()->update($request->validated(), $customer);
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
     * @param  \App\Models\Api\Customer  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        DB::beginTransaction();
        try{
            $data = $this->getService()->destroy($customer);
            DB::commit();
            return $this->respondSuccess($data);
        }catch (\Exception $e){
            DB::rollBack();
            return $this->respondError($e);
        }
    }

     public function countries()
    {
        return CountryResource::collection(Country::query()->orderBy('name', 'asc')->get());
    }
}