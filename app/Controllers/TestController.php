<?php
namespace App\Controllers;
use App\Controllers\interfaces\iData_controller;

use App\Models\Employee;
use App\Models\Product;
use App\Models\Admin;
use App\Models\PriceList;
use App\Services\HttpService;
use App\Services\GeoLocationService;

class TestController extends BaseController
{
    public function test1 () {
        $ip = '8.8.8.8';
        $location = GeoLocationService::getLocationFromIp($ip);
        echo $location;
    }
}