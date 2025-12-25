<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApi;
use App\Models\Api\User;
use App\Services\SVUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends BaseApi
{
    public function getService()
    {
        return new SVUser();
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        DB::beginTransaction();
        try{
            $data = $this->getService()->store($request->all());
            DB::commit();
            return $this->respondSuccess($data);
        }catch (\Exception $e){
            DB::rollBack();
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
    public function update(UpdateUserRequest $request, User $user)
    {
        DB::beginTransaction();
        try {
            $data = $this->getService()->update($request->validated(), $user);
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
     * @param  \App\Models\Api\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try{
            $data = $this->getService()->destroy($user);
            DB::commit();
            return $this->respondSuccess($data);
        }catch (\Exception $e){
            DB::rollBack();
            return $this->respondError($e);
        }
    }
}