<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApi;
use Throwable;
use App\Services\jwt\SvAuth;
use Illuminate\Http\Request;


class AuthController extends BaseApi
{
    public function getService()
    {
        return new SvAuth();
    }
    
    public function login(Request $request)
    {
        try{
            $params = $request->all();
            $data = $this->getService()->login($params);
            return $this->respondSuccess($data);
        }catch(Throwable $e){
            return $this->respondError($e);
        }
    }

    public function logout(Request $request)
    {
        try{
            $params = $request->all();
            $data = $this->getService()->logout($params);
            return $this->respondSuccess($data);
        }catch(Throwable $e){
            return $this->respondError($e);
        }
    }

    public function me(Request $request){
        try{
            $data = $this->getService()->getUser($request->all());
            return $this->respondSuccess($data);
        }catch(Throwable $e){
            return $this->respondError($e);
        }
    }

}
