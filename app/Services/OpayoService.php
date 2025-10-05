<?php
namespace App\Services;

class ElavonPaymentService 
{
    private $_hostedPaymentUrl = "";
    private $_vendorName = "";
    private $_integrationKey = "";
    private $_integrationPasswd = "";
    
    function __construct() {
        $this->_hostedPaymentUrl = env("opayo.hosted_payment_url", "https://api.demo.convergepay.com/hosted-payments");
        $this->_vendorName = env("opayo.vendorName");
        $this->_integrationKey = env("opayo.integrationKey");
        $this->_integrationPasswd = env("elavon_integrationPasswd");
    }
}