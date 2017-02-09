<?php
require_once 'classes/BatchXML.php';
require_once 'classes/TransactionXML.php';
require_once 'classes/MySQLDB.php';

date_default_timezone_set('Asia/Vladivostok');

try {
	
	// open log file
	$log_filename = "log.log";
	$log = fopen(__DIR__.'\\'.$log_filename,'a');

	// read configs from .ini file
	$configs_filename = "configs.ini";
	$configs = parse_ini_file($configs_filename);

	if(!$configs) {
		throw new Exception("Ошибка открытия файла конфигурации. Работа остановлена.");	
	}

	$db_location = $configs["DB_location"];
	$db_user = $configs["DB_user"];
	$db_password = $configs["DB_password"];
	$db_name = $configs["DB_name"];
	$interval = $configs["interval"];

	// conn to MySQLDB and get data
	$conn = new MySQLDB($db_location,$db_user,$db_password,$db_name);

	$payments = $conn->select(
		"SELECT accounts.name as name,
		payments.account as account,
		payment_amount,
		payment_subj,
		payment_date,
		paymId,
		user_comment 
		FROM payments 
		INNER JOIN accounts ON accounts.account = payments.account 
		WHERE payment_date > DATE_SUB(now(), INTERVAL $interval)
		ORDER BY payment_date ASC;"
		);

	// build XML file and save
	$batch = new BatchXML($payments);

	$batch_filename = sprintf('Batch %s.xml', date('y.m.d h-i'));
	$batch->saveXML(__DIR__."\\Results\\$batch_filename");

	// write log
	fwrite($log, sprintf("[%s] %s\n",date('y.m.d h:i:s'), "'$batch_filename' создан успешно."));

} catch (Exception $e) {
	// write error in log
	fwrite($log, sprintf("[%s] %s\n",date('y.m.d h:i:s'), $e->getMessage() ));
}

fclose($log);
exit();
?>