<?php
require_once 'classes/BatchXML.php';
require_once 'classes/TransactionXML.php';
require_once 'classes/MySQLDB.php';

date_default_timezone_set('Asia/Vladivostok');
$log = fopen(__DIR__."\log.log",'a');

	// read configs from .ini file
	$configs_filename = "config.ini";
	$configs = parse_ini_file($configs_filename);

	$db_location = $configs["DB_location"];
	$db_user = $configs["DB_user"];
	$db_password = $configs["DB_password"];
	$db_name = $configs["DB_name"];
	$interval = $configs["interval"];

try {
	// throw new Exception('Деление на ноль.');
	// conn to MySQLDB and get data
	$conn = new MySQLDB($db_location,$db_user,$db_password,$db_name);
	$payments = $conn->select(
			['accounts.name as name','payments.account as account','payment_amount','payment_subj','payment_date','paymId','user_comment'],
			'payments',
			'accounts ON accounts.account = payments.account',
			"payment_date > DATE_SUB(now(), INTERVAL $interval)",
			'payment_date ASC'
		);

	/*// convert encoding to UTF-8
	foreach ($payments as &$payment) {
		$payment["name"] = mb_convert_encoding($payment["name"], "UTF-8");
	}*/

	// var_dump($payments);

	// build XML file and save
	$batch = new BatchXML($payments);
	$batch->saveXML(__DIR__.'\batch.xml');
	fwrite($log, "[".date('l jS \of F Y h:i:s A')."] batch.xml создан успешно.\n");
} catch (Exception $e) {
	fwrite($log, "[".date('l jS \of F Y h:i:s A')."] Выброшено исключение: ". $e->getMessage(). "\n");
}

fclose($log);
exit();
?>