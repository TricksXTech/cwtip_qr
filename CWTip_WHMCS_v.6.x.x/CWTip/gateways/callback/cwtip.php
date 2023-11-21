<?php	

include("../../../init.php");
include("../../../includes/functions.php");
include("../../../includes/gatewayfunctions.php");
include("../../../includes/invoicefunctions.php");

require_once(dirname(__FILE__) . '/../cwtip-sdk/encdec_cwtip.php');
$gatewaymodule = "cwtip"; 
$GATEWAY = getGatewayVariables($gatewaymodule);

$response = array();
$response = $_POST;   
  
if(isset($response['ORDERID']) && isset($response['STATUS']) && isset($response['RESPCODE']) && $response['RESPCODE'] != 325){

	$txnid_arr  = explode('_',$response['ORDERID']);
	$tnxid = $txnid_arr[0];
	$txnid  = $txnid_arr[0];
	$status =$response['STATUS'];
	$paytm_trans_id = $response['TXNID'];
	$checksum_recv='';	
	$amount=$response['TXNAMOUNT'];
	if(isset($response['CHECKSUMHASH'])){
		$checksum_recv=$response['CHECKSUMHASH'];
	}
	
	if($status== 'TXN_SUCCESS'){	
	
		if($response['STATUS']=='TXN_SUCCESS')
		{
			$gatewayresult = "success";
			addInvoicePayment($txnid, $paytm_trans_id, $amount,"0.0", $gatewaymodule); 
			logTransaction($GATEWAY["name"], $response, $response['RESPMSG']);
		}
		else{
			logTransaction($GATEWAY["name"], $response, "It seems some issue in server to server communication. Kindly connect with administrator.");
		}
	} elseif ($status != "TXN_SUCCESS") {
		logTransaction($GATEWAY["name"], $response, "Checksum Mismatch");
	}else {
		logTransaction($GATEWAY["name"], $response, $response['RESPMSG']); 
	}
	
	$returnResponse=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
	$filename=str_replace('modules/gateways/callback/cwtip.php','viewinvoice.php?id='.$txnid, $returnResponse);
    header("Location: $filename");
}
else{

 
	$returnResponse=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
	$location=str_replace('modules/gateways/callback/cwtip.php','', $returnResponse);
	
	 
	header("Location: $location");
}
?>
