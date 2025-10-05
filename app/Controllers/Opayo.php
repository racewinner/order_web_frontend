<?php
namespace App\Controllers;

use App\Models\Employee;
use App\Models\Payment;
use App\Models\Admin;
use App\Models\Order;

class Opayo extends BaseController
{
    private $vendorName;
    private $integrationKey;
    private $integrationPasswd;
    private $encryptionPassword;
    private $form_registration_url;

    function __construct()
	{
		parent::__construct('opayo');

        $this->vendorName = env("opayo.vendorName");
        $this->integrationKey = env("opayo.integrationKey");
        $this->integrationPasswd = env("opayo.integrationPasswd");
        $this->encryptionPassword = env("opayo.encryptionPassword");
        $this->form_registration_url = env("opayo.form_registration_url");
	}

    public function initiatePayment()
    {
        $Employee = new Employee();
        $paymentModel = new Payment();

        if(!$Employee->is_logged_in()) return redirect()->to('/');			

        $user_info = $Employee->get_logged_in_employee_info();

        // To get order details(amount, order id, etc)
        $amount = request()->getPost("amount");
        $orderId = request()->getPost("order_id");
        $customerEmail = request()->getPost("customer_email");
        $description = "This is test order.";
        
        // Save payment record
        $paymentData = [
            'order_id' => $orderId,
            'amount' => $amount,
            'description' => $description,
            'person_id' => $user_info->person_id,
            'status' => 'pending',
            'customer_email' => $customerEmail,
        ];
        
        $paymentId = $paymentModel->insert($paymentData);

        // Build the crypt string for Opayo Form
        $cryptString = $this->buildCryptString($paymentData, $user_info);

        // Encrypt the crypt string
        $encryptedCrypt = $this->encryptAes($cryptString, $this->encryptionPassword);
        
        // Prepare form data for Opayo
        $data['paymentData'] = $paymentData;
        $data['formData'] = [
            'VPSProtocol' => '4.00',
            'TxType' => 'PAYMENT',
            'Vendor' => $this->vendorName,
            'Crypt' => $encryptedCrypt,
        ];
        $data['opayo_url'] = $this->form_registration_url;
        
        return view('opayo/payment_form', $data);
    }

    private function buildCryptString($paymentData, $user_info)
    {
        $cryptArray = [
            'VendorTxCode' => $paymentData['order_id'],
            'Amount' => number_format($paymentData['amount'], 2, '.', ''),
            'Currency' => 'GBP',
            'Description' => $paymentData['description'],
            'BillingSurname' => 'Tester',
            'BillingFirstnames' => 'John',
            'BillingAddress1' => '123 Test Street',
            'BillingCity' => 'Testville',
            'BillingPostCode' => 'M1 1AA',
            'BillingCountry' => 'GB',
            'DeliverySurname' => 'Tester',
            'DeliveryFirstnames' => 'John',
            'DeliveryAddress1' => '123 Test Street',
            'DeliveryCity' => 'Testville',
            'DeliveryPostCode' => 'M1 1AA',
            'DeliveryCountry' => 'GB',
            'CustomerEMail' => $paymentData['customer_email'],
            'VendorEMail' => 'merchant@example.com',
            'SendEMail' => '1', // Send confirmation emails
            'eMailMessage' => 'Thank you for your order',
            'Basket' => $this->prepareBasket($paymentData),
            'SuccessURL' => base_url('opayo/success'),
            'FailureURL' => base_url('opayo/failure')
        ];

        return http_build_query($cryptArray);
    }

    private function encryptAes($string, $key)
    {
        // Pad key to 16 bytes if shorter
        $key = str_pad($key, 16, "\0");
        
        // Encrypt using AES-128-CBC with key as IV (Opayo requirement)
        $encrypted = openssl_encrypt($string, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $key);
        
        // Convert to hex and add @ prefix
        return '@' . strtoupper(bin2hex($encrypted));
    }

    private function decryptAes($string, $key)
    {
        // Pad key to 16 bytes if shorter
        $key = str_pad($key, 16, "\0");
        
        // Remove @ prefix and convert from hex
        $binary = hex2bin(substr($string, 1));
        
        // Decrypt using AES-128-CBC with key as IV
        return openssl_decrypt($binary, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $key);
    }

    protected function prepareBasket($data)
    {
        // Example basket format - customize as needed
        $basketItems = [
            [
                'description' => $data['description'],
                'quantity' => 1,
                'unitNetAmount' => $data['amount'],
                'unitTaxAmount' => 0.00,
                'unitGrossAmount' => $data['amount'],
                'totalGrossAmount' => $data['amount']
            ]
        ];
        
        $basketString = count($basketItems) . ':';
        foreach ($basketItems as $item) {
            $basketString .= $item['description'] . ':' .
                           $item['quantity'] . ':' .
                           number_format($item['unitNetAmount'], 2) . ':' .
                           number_format($item['unitTaxAmount'], 2) . ':' .
                           number_format($item['unitGrossAmount'], 2) . ':' .
                           number_format($item['totalGrossAmount'], 2) . ':';
        }
        
        return rtrim($basketString, ':');
    }

    private function parseResponse($response)
    {
        $lines = explode("\n", $response);
        $parsedResponse = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $parsedResponse[trim($parts[0])] = trim($parts[1]);
            }
        }
        
        return $parsedResponse;
    }

    public function success()
    {
        $crypt = $this->request->getGet('crypt');
        
        if ($crypt) {
            $decryptedData = $this->decryptAes($crypt, $this->encryptionPassword);
            $responseData = $this->parseResponse($decryptedData);
            
            // Update payment record
            $paymentModel = new Payment();
            $payment = $paymentModel->where('order_id', $responseData['VendorTxCode'])->first();
            
            if ($payment) {
                $paymentModel->update($payment['id'], [
                    'status' => 'completed',
                    'vps_tx_id' => $responseData['VPSTxId'] ?? null,
                    'tx_auth_no' => $responseData['TxAuthNo'] ?? null
                ]);
            }
            
            $this->data['payment_data'] = $responseData;
        }
        
        return view('opayo/success', $this->data);
    }

    public function failure() {
        $crypt = $this->request->getGet('crypt');
        
        if ($crypt) {
            $decryptedData = $this->decryptAes($crypt, $this->encryptionPassword);
            $responseData = $this->parseResponse($decryptedData);
            
            // Update payment record
            $paymentModel = new Payment();
            $payment = $paymentModel->where('order_id', $responseData['VendorTxCode'])->first();
            
            if ($payment) {
                $paymentModel->update($payment['id'], [
                    'status' => 'failed',
                    'status_detail' => $responseData['StatusDetail'] ?? 'Payment failed'
                ]);
            }
            
            $this->data['payment_data'] = $responseData;
        }
        
        return view('opayo/failure', $this->data);
    }
}