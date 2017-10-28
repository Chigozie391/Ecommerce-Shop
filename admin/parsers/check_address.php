<?php 	require_once $_SERVER['DOCUMENT_ROOT'].'/shop/core/init.php';

$name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$address = sanitize($_POST['address']);
$phone1 = sanitize($_POST['phone1']);
$phone2 = sanitize($_POST['phone2']);
$state = sanitize($_POST['state']);

$errors = array();
$required = array(
	'full_name' => 'Full Name',
	'email' =>'Email',
	'address' => 'Address',
	'phone1' => 'Phone Number',
	'state' => 'State',
);

//check if all field are filled out
foreach ($required as $f => $d) {
	if(empty($_POST[$f]) || $_POST[$f] == ''){
		$errors[] = $d.' is Required.';
	}
}
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
	$errors[] = 'Please Enter a Valid Email.';
}

if(!empty($errors)){
	echo display_errors($errors);
}else{
	echo 'passed';
}

 ?>