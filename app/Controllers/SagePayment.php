<?php
namespace App\Controllers;

class SagePayment extends BaseController {

    private $vendor_name; // Your Opayo vendor name
    private $integration_key; // Your integration key
    private $integration_password; // Your integration password
    private $opayo_url; // Test URL

    public function __construct() {
        parent::__construct();
        // $this->load->helper('url');
        // $this->load->library('session');
        $this->vendor_name = env("opayo.vendorName");
        $this->integration_key = env("opayo.integrationKey");
        $this->integration_password = env("opayo.integrationPasswd");
        $this->encryption_password = env("opayo.encryptionPassword");
        $this->opayo_url = 'https://test.opayo.eu.elavon.com/api/v1/transactions';
        // For live: https://live.opayo.eu.elavon.com/api/v1/transactions

    }

    /**
     * Initiate payment - Create transaction and redirect to Opayo
     */
    public function initiate() {
        // Your order details
        $order_id = uniqid('ORD-');
        $amount = 1000; // Amount in smallest currency unit (e.g., cents/pence)
        $currency = 'GBP';
        $description = 'Order Payment';
        
        // Customer details
        $customer_email = 'customer@example.com';
        $customer_firstname = 'John';
        $customer_lastname = 'Doe';
        
        // Billing address
        $billing_address = array(
            'address1' => '123 Test Street',
            'city' => 'London',
            'postalCode' => 'SW1A 1AA',
            'country' => 'GB'
        );

        // Build the transaction request
        $transaction_data = array(
            'transactionType' => 'Payment',
            'paymentMethod' => array(
                'card' => array()
            ),
            'vendorTxCode' => $order_id,
            'amount' => $amount,
            'currency' => $currency,
            'description' => $description,
            'customerFirstName' => $customer_firstname,
            'customerLastName' => $customer_lastname,
            'customerEmail' => $customer_email,
            'billingAddress' => $billing_address,
            'entryMethod' => 'Ecommerce',
            'redirectUrl' => base_url('payment/callback')
        );

        // Make API request
        $response = $this->send_opayo_request($transaction_data);

        if ($response && isset($response['status']) && $response['status'] === 'Ok') {
            // Store transaction ID in session
            $this->session->set_userdata('opayo_transaction_id', $response['transactionId']);
            $this->session->set_userdata('order_id', $order_id);
            
            // Redirect to Opayo hosted payment page
            if (isset($response['redirectUrl'])) {
                redirect($response['redirectUrl']);
            } else {
                echo "Error: No redirect URL received";
            }
        } else {
            // Handle error
            $error_message = isset($response['description']) ? $response['description'] : 'Unknown error occurred';
            echo "Payment initiation failed: " . $error_message;
        }
    }

    /**
     * Callback handler after payment
     */
    public function callback() {
        // Opayo will redirect back here with transaction details
        $transaction_id = $this->session->userdata('opayo_transaction_id');
        $order_id = $this->session->userdata('order_id');
        
        if (!$transaction_id) {
            echo "Invalid transaction";
            return;
        }

        // Retrieve transaction details from Opayo
        $transaction_details = $this->get_transaction_details($transaction_id);
        
        if ($transaction_details && isset($transaction_details['status'])) {
            $status = $transaction_details['status'];
            
            switch ($status) {
                case 'Ok':
                    // Payment successful
                    $this->handle_successful_payment($order_id, $transaction_details);
                    break;
                    
                case 'Authenticated':
                case 'Registered':
                    // 3D Secure authentication successful, awaiting final authorization
                    $this->handle_pending_payment($order_id, $transaction_details);
                    break;
                    
                case 'Rejected':
                case 'Notauthed':
                case 'Abort':
                case 'Error':
                    // Payment failed
                    $this->handle_failed_payment($order_id, $transaction_details);
                    break;
                    
                default:
                    echo "Unknown payment status: " . $status;
            }
        } else {
            echo "Unable to retrieve transaction details";
        }
    }

    /**
     * Send request to Opayo API
     */
    private function send_opayo_request($data) {
        // $ch = curl_init($this->opayo_url);https://www.w3schools.com/
        $ch = curl_init("https://sandbox.opayo.eu.elavon.com/hosted-payment-pages/vendor/v1/payment-pages");
        
        $auth = base64_encode($this->integration_key . ':' . $this->integration_password);
        
        $headers = array(
            'Authorization: Basic ' . $auth,
            'Content-Type: application/json',
            'Cache-Control: no-cache'
        );
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            error_log('Opayo cURL Error: ' . curl_error($ch));
            curl_close($ch);
            return false;
        }
        
        curl_close($ch);
        
        return json_decode($response, true);
    }

    /**
     * Get transaction details from Opayo
     */
    private function get_transaction_details($transaction_id) {
        $url = $this->opayo_url . '/' . $transaction_id;
        
        $ch = curl_init($url);
        
        $auth = base64_encode($this->integration_key . ':' . $this->integration_password);
        
        $headers = array(
            'Authorization: Basic ' . $auth,
            'Cache-Control: no-cache'
        );
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }

    /**
     * Handle successful payment
     */
    private function handle_successful_payment($order_id, $transaction_details) {
        // Update your database, send confirmation email, etc.
        
        // Clear session data
        $this->session->unset_userdata('opayo_transaction_id');
        $this->session->unset_userdata('order_id');
        
        // Redirect to success page
        $data['order_id'] = $order_id;
        $data['transaction_id'] = $transaction_details['transactionId'];
        $data['amount'] = $transaction_details['amount']['totalAmount'] / 100; // Convert back to main currency unit
        
        $this->load->view('payment/success', $data);
    }

    /**
     * Handle pending payment (awaiting authentication)
     */
    private function handle_pending_payment($order_id, $transaction_details) {
        $data['order_id'] = $order_id;
        $data['status'] = 'pending';
        
        $this->load->view('payment/pending', $data);
    }

    /**
     * Handle failed payment
     */
    private function handle_failed_payment($order_id, $transaction_details) {
        // Clear session data
        $this->session->unset_userdata('opayo_transaction_id');
        $this->session->unset_userdata('order_id');
        
        $data['order_id'] = $order_id;
        $data['error'] = isset($transaction_details['statusDetail']) ? $transaction_details['statusDetail'] : 'Payment failed';
        
        $this->load->view('payment/failed', $data);
    }
}