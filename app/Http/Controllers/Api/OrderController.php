<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Services\SVOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseApi;
use Illuminate\Support\Facades\DB;

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

    public function changeStatus(Order $order, $status)
    {
        DB::beginTransaction();
        try {
            $data = $this->getService()->changeStatus($order, $status);
            DB::commit();
            return $this->respondSuccess($data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->respondError($e);
        }
        
    }

}
