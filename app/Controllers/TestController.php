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
    
    public function sendEmail () {

        $email = \Config\Services::email();
        $email->setFrom('telesales@uniteduk.com', 'United UK Telesales');
        $email->setTo('QSfTfSilinaRoza@gmail.com');
        $email->setSubject('Test email via mail() transport');
        $email->setMessage('<p>Test email using PHP mail() transport. </p> <p>Most important is to match the fromAddr to domain name like "uniteduk".</p>');
        $email->setProtocol('mail');
        if ($email->send()) {
            echo 'Email sent'; 
        } else {
            echo $email->printDebugger(); 
        }

    }
}