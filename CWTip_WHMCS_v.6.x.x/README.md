#Introduction
This integration kit is used in WHMCS PHP E-Commerce Application. This library provides support for Paytm payment gateway.

#Installation
Copy the files from this plugin into the corresponding folders on your installation, as mentioned below:

Copy the Paytm/gateways/paytm.php file into your installation's /module/gateways/ folder
Copy the Paytm/gateways/callback/paytm.php file into your installation's /module/gateways/callback folder.
Copy the Paytm/gateways/paytm-sdk folder into your /module/gateways folder
See Video : https://www.youtube.com/watch?v=CBWWYawttE4

#Configuration
Provide the values for the following in the Configuration Settings of the Admin Panel.

Merchant ID
Website
Merchant Key
Channel ID
Industry Type ID
Transaction URL
Transaction Status URL
Paytm PG URL Details
Staging	
	Transaction URL             => https://securegw-stage.paytm.in/theia/processTransaction
	Transaction Status Url      => https://securegw-stage.paytm.in/merchant-status/getTxnStatus

Production
	Transaction URL             => https://securegw.paytm.in/theia/processTransaction
	Transaction Status Url      => https://securegw.paytm.in/merchant-status/getTxnStatus
