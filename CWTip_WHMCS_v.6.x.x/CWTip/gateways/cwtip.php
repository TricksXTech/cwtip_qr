<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

require_once(dirname(__FILE__) . '/cwtip-sdk/encdec_cwtip.php');

function cwtip_MetaData()
{
    return array(
        'DisplayName' => 'CWTip',
        'APIVersion' => '1.0.1',
        'DisableLocalCredtCardInput' => true,
        'TokenisedStorage' => false,
    );
}

function cwtip_config(){
    $configarray = array(
		"FriendlyName" => array("Type" => "System", "Value"=>"CWTip"),
		"acc_id" => array("FriendlyName" => "Account ID", "Type" => "text", "Size" => "20", ),
		"auth_token" => array("FriendlyName" => "Auth Token", "Type" => "text", "Size" => "16", ),
		"endpoint_url" => array("FriendlyName" => "Transaction Url", "Type" => "text", "Size" => "90", )
	);		
	return $configarray;
}

function cwtip_link($params) {	

	$acc_id = $params['acc_id'];
	$auth_token=$params['auth_token'];
	$order_id = $params['invoiceid'].'_'.time();
	$website= $params['website'];
	$transaction_url = $params['endpoint_url'];		
	$amount = $params['amount']; 
	$email = $params['clientdetails']['email'];
	$callBackLink=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
	$callBackLink=str_replace('cart.php', 'modules/gateways/callback/cwtip.php', $callBackLink);
	$callBackLink=str_replace('viewinvoice.php', 'modules/gateways/callback/cwtip.php', $callBackLink);
	
	$post_variables = Array(
          "MID" => $acc_id,
          "MAT" => $auth_token,
          "ORDER_ID" => $order_id ,
          "CUST_ID" => $email,
          "TXN_AMOUNT" => $amount,
          "CALLBACK_URL" => $callBackLink,
          "WEBSITE" => $website
          );
	$checksum = getChecksumFromArray($post_variables, $auth_token);
	$companyname = 'cwtip';

	$code='<form method="post" action='. $transaction_url .'>';
	foreach ($post_variables as $key => $value) {
		$code.='<input type="hidden" name="'.$key.'" value="'.$value. '"/>';
	}
	$code.='<input type="hidden" name="CHECKSUMHASH" value="'. $checksum . '"/><input type="submit" value="Pay with cwtip" /></form>';
	return $code;
}
?>
