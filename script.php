<?php
require_once 'classes/BatchXML.php';
require_once 'classes/TransactionXML.php';
require_once 'classes/MySQLDB.php';

/*$shortopts  = "";
$shortopts .= "l:";
$shortopts .= "u:";
$shortopts .= "p:";
$shortopts .= "f:";

$longopts  = array(
	"location:",
	"user:",
	"password:",
	"filename",
);
$options = getopt($shortopts, $longopts);
var_dump($options);*/

$db_location = "localhost";
$db_user = "root";
$db_password = "87654321";
$db_name = "slav";

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

$batch = new BatchXML($accounts,$payments);
$batch->saveXML(__DIR__.'\batch.xml');

exit();
?>