<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/shop/core/init.php';

$full_name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$address = sanitize($_POST['address']);
$state = sanitize($_POST['state']);
$phone1 = sanitize($_POST['phone1']);
$phone2 = sanitize($_POST['phone2']);
$grand_total = sanitize($_POST['grand_total']);
$description = sanitize($_POST['description']);
$cart_id = sanitize($_POST['cart_id']);
$reference = $_POST['reference'];
$webmaster = 'thankyou@dominiquestores.com';
$emailsubject = 'Your Order Details From Dominique Store';


$location = $address.' '.$state;
$number = $phone1.' '.phone2;
$amount = money($grand_total);
$body = <<<EOD
<div style ="text-align:center">
<h1 style="text-align:center;">Order Information</h1><hr style="border-color:#3F729B;border-width:2px;">
<p><b>Name: </b>$full_name</p><hr style="border-color:#3F729B;">
<p><b>Email: </b>$email</p><hr style="border-color:#3F729B;">
<p><b>Shipping Address: </b>$location</p><hr style="border-color:#3F729B;">
<p><b>Phone Number: </b>$number</p><hr style="border-color:#3F729B">
<p><b>Phone Number: </b>$description</p><hr style="border-color:#3F729B">
<p><b>Your Receipt Number: </b>$cart_id</p><hr style="border-color:#3F729B;">
<p><b>Your Reference Number: </b>$reference</p><hr style="border-color:#3F729B;">
<p><b>Grand Total: $amount</b></p>
</div>
EOD;
		$headers = "From: $webmaster"."\r\n";
		$headers .= "MIME-Version:1.0"."\r\n";
		$headers .= "Content-type: text/html;charset=UTF-8"."\r\n";
		mail($email, $emailsubject, $body, $headers);



 ?>