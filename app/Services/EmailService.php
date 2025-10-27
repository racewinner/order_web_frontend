<?php
namespace App\Services;
use CodeIgniter\Email\Email;

class EmailService 
{
    public static function send($from, $to, $senderCompany, $subject, $message)
    {
        $email = new Email();
		$config = ['mailType' => 'html'];
		$email->initialize($config);

		$email->setFrom($from, $senderCompany);
		$email->setTo($to); // --- SWAP
		$email->setSubject($subject);
		$email->setMessage($message);
		$email->send();
		$email->clear();
    }

	 public static function sendEmail ($from, $to, $senderCompany, $subject, $message) {
		// This method was made by holla.ardy, 2025.10.26
		/* to test? use this codebase
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
		*/
	    $email = \Config\Services::email();
        $email->setFrom($from, $senderCompany);
        $email->setTo($to);
		$email->setSubject($subject);
        $email->setMessage($message);
        $email->setProtocol('mail');
		$email->setMailType('html');
        $email->send();
		$email->clear();
    }
}