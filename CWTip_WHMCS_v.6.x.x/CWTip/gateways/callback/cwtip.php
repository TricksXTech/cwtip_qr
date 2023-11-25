<?php	

include("../../../init.php");
include("../../../includes/functions.php");
include("../../../includes/gatewayfunctions.php");
include("../../../includes/invoicefunctions.php");

require_once(dirname(__FILE__) . '/../cwtip-sdk/encdec_cwtip.php');
$gatewaymodule = "cwtip"; 
$GATEWAY = getGatewayVariables($gatewaymodule);

// Die if module is not active.
if (!$GATEWAY['type']) {
    die("Module Not Activated");
}

if(isset($_POST['ORDERID']) && isset($_POST['STATUS']) && isset($_POST['RESPCODE']) && $_POST['RESPCODE'] != 325){
   
    $txnid_arr  = explode('_',$_POST['ORDERID']);
	$tnxid = $txnid_arr[0]; 
	$txnid  = checkCbInvoiceID($_POST['ORDERID'],'cwtip');	
	$status = $_POST['STATUS'];
	$paytm_trans_id = $_POST['TXNID'];
	$amount = $_POST['TXNAMOUNT'];
	
	$ORDER_ID = $_POST['ORDER_ID'];
	$acc_id = $_POST['MID'];
	$auth_token = $_POST['MAT'];
	
	if($status== 'TXN_SUCCESS'){

$send_data = file_get_contents("https://ca.cwtip.co/api/?api=$acc_id&auth=$auth_token&orderid=$ORDER_ID"); 
$json_data = json_decode($send_data,true);

if($json_data["status"]=="failed"){
    logTransaction($GATEWAY["name"], $_POST, "It seems some issue in server to server communication. Kindly connect with administrator.");
}
else if($json_data["STATUS"]=="TXN_FAILURE" || $json_data["RESPMSG"]=="Invalid Order Id."){
    logTransaction($GATEWAY["name"], $_POST, "It seems some issue in server to server communication. Kindly connect with administrator.");
}
else{
 
	 $gatewayresult = "success";
			addInvoicePayment($txnid, $paytm_trans_id, $amount,"0.0", $gatewaymodule); 
			logTransaction($GATEWAY["name"], $_POST, $_POST['RESPMSG']);
		}
	}
		else{
			logTransaction($GATEWAY["name"], $_POST, "It seems some issue in server to server communication. Kindly connect with administrator.");
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
