<?php

session_start ();

if(!empty($_SESSION['STX_ID'])){
	$_SESSION['STX_ID'] = null;
}

use DD\SecuPayAddress;
use DD\SecuPayApplicationContext;
use DD\SecuPayBasket;
use DD\SecuPayContact;
use DD\SecuPayCustomer;
use DD\SecuPayPaymentData;
use DD\SecuPayProduct;
use DD\SecuPayOrder;


require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';


try {

	$orderId = '201600123';
	$referenceText = 'Ihre Bestellung Nr. ' . $orderId;

	/*
	Configuration::getDefaultConfiguration ()->setHost ('https://connect-testing.secupay-ag.de/api/v2');
	Configuration::getDefaultConfiguration ()->setAuthHost ('https://connect-testing.secupay-ag.de/');

	// enable for using the live environment
	// Configuration::getDefaultConfiguration()->setHost('https://connect.secucard.com/api/v2');
	// Configuration::getDefaultConfiguration()->setAuthHost('https://connect.secucard.com/');
    */

	#region AUTHENTICATION

	$SecuPay = new SecuPayOrder(CLIENT_ID, CLIENT_SECRET, true);

	#endregion

	#region ORDER

	$address             = new SecuPayAddress("Musterstr.", "11", "App. 302", "09123", "Musterstadt", "DE");
	$contact             = new SecuPayContact("Max", "Mustermann", "+49 555 5555555", "+49 177 5555555", $address, "max@example.org", "1965-12-31");
	$customer            = new SecuPayCustomer($contact);
	$products            = [
		new SecuPayProduct(1, "ACME ball pen Modern Line 8050", 1595, 19, 2),
		new SecuPayProduct(2, "ACME pen case Modern Line", 1795, 19, 1),
		new SecuPayProduct(3, "Standardversand", 495, 19, 1, "shipping")
	];
	$basket              = new SecuPayBasket($products);
	$basket_info         = ["currency" => "EUR", "sum" => 5480];
	$return_urls         = [
		"url_success" => SUCCESS_URL,
		"url_error"   => FAILURE_URL,
		"url_abort"   => ABORT_URL
	];
	$application_context = new SecuPayApplicationContext(TEMPLATE, "de", $return_urls);
	$payment_context     = ["auto_capture" => false];

    $data                = new SecuPayPaymentData(true, ["id" => PRODUCT_INSTANCE_ID], $customer, "sale", $basket, $basket_info, $application_context, $payment_context);

	$data = json_decode (json_encode ($data), true);

	$response = $SecuPay->AddTransaction ($data);
    /*print_r($response);
    exit;*/
	?>
    <style>
        .button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>
    <?php

    if(!empty($response['status']) && $response['status'] == 'created'){
        if (!empty($response['id'])) {
            //Save ID in SESSION
            $_SESSION['STX_ID'] = $response['id'];

        }
    }

    if(!empty($response['payment_links'])){
        $paymentLink = $response['payment_links']['general'] ?? '';
        echo "<a href=" . $paymentLink." class='button'>NU ABBO MAL SCHNELL BEZAHLN!</a>";
    }


} catch (Exception $e) {
	echo $e->getTraceAsString ();
	echo $e->getMessage ();
}
