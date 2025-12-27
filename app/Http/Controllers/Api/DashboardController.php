<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\BaseApi;
use App\Services\Report\SVDashboard;

class DashboardController extends BaseApi
{
   
    public function getService()
    {
        return new SVDashboard();
    }

    public function activeCustomers()
    {
        return $this->getService()->activeCustomers();
    }

    public function activeProducts()
    {
        return $this->getService()->activeProducts();
    }

    public function paidOrders()
    {
        return $this->getService()->paidOrders();
    }

    public function totalIncome()
    {
        return $this->getService()->totalIncome();
    }

    public function ordersByCountry()
    {
        return $this->getService()->ordersByCountry();
    }

    public function latestCustomers()
    {
        return $this->getService()->latestCustomers();
    }

    public function latestOrders()
    {
        return $this->getService()->latestOrders();
    }
}