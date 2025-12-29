<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApi;
use App\Services\Report\SVReport;


class ReportController extends BaseApi
{
    public function getService()
    {
        return new SVReport();
    }
    
    public function orders()
    {
        return $this->getService()->orders();
    }

    public function customers()
    {
        return $this->getService()->customers();
    }

}