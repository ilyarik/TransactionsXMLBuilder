<?php
require_once 'classes/BatchXML.php';
require_once 'classes/TransactionXML.php';
require_once 'classes/MySQLDB.php';

// read configs from .ini file
$configs_filename = "config.ini";
$configs = parse_ini_file($configs_filename);

$db_location = $configs["DB_location"];
$db_user = $configs["DB_user"];
$db_password = $configs["DB_password"];
$db_name = $configs["DB_name"];

// conn to MySQLDB and get data
$conn = new MySQLDB($db_location,$db_user,$db_password,$db_name);
$accounts = $conn->select(
		['account','name'],
		'accounts',
		'account'
	);
$payments = $conn->select(
		['account','payment_amount','payment_subj','payment_date','paymId','user_comment'],
		'payments',
		'account'
	);

// build XML file and save
$batch = new BatchXML($accounts,$payments);
$batch->saveXML(__DIR__.'\batch.xml');

exit();
?>