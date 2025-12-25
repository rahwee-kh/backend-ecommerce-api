<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Services\SVOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseApi;

class OrderController extends BaseApi
{
    public function getService()
    {
        return new SVOrder();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        try{
            $data = $this->getService()->getListOrder($request->all());
            return $data;
        }catch(\Throwable $e)
        {
            return $this->respondError($e);
        }
    }

    public function show(Order $order)
    {
        try{
            $data = $this->getService()->viewOrder($order);
            return $data;
        }catch(\Throwable $e)
        {
            return $this->respondError($e);
        }
    }

    public function getStatuses()
    {

        try{
            $data = $this->getService()->getStatuses();
            return $data;
        }catch(\Throwable $e)
        {
            return $this->respondError($e);
        }
        
    }

}
