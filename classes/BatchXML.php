<?php
class BatchXML {

	// define namespaces
	public static $xmlns = array(
			'msg' => 'http://message.smev.gpb.ru',
			'bt' => 'http://basetypes.smev.gpb.ru',
			'tns' => 'http://smev.gosuslugi.ru/rev120315',
			'xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
			'ti' => 'http://transactioninfo.smev.gpb.ru',
			'schemaLocation' => 'http://message.smev.gpb.ru SMEV-GPB_Messages_v0.0.3.xsd',
		);
	private $payments;

	function __construct($payments) {
		$this->payments = $payments;
	}

	public function saveXML($filename) {

		// create new xml document with utf-8 encoding
		$header = '<?xml version="1.0" encoding="UTF-8"?>';
		$batch = sprintf(
			'<msg:Batch xmlns:msg="%s" xmlns:bt="%s" xmlns:tns="%s" xmlns:xsi="%s" xmlns:ti="%s" xsi:schemaLocation="%s"></msg:Batch>', 
			self::$xmlns['msg'],
			self::$xmlns['bt'],
			self::$xmlns['tns'],
			self::$xmlns['xsi'],
			self::$xmlns['ti'],
			self::$xmlns['schemaLocation']);
		$page = new SimpleXMLElement(mb_convert_encoding($header.$batch,"UTF-8"));

		$batchHeader = $page->addChild('BatchHeader',null,self::$xmlns['msg']);
		$batchHeader->addChild('MessageId',null,self::$xmlns['msg']);
		$batchHeader->addChild('Source',null,self::$xmlns['msg']);

		$batchBody = $page->addChild('BatchBody',null,self::$xmlns['msg']);

		// add transactions
		foreach($this->payments as $payment) {
			$transactionObj = new TransactionXML($batchBody, $payment);
			$transactionObj->getXML();
		}

		// format and save document
		$dom = dom_import_simplexml($page)->ownerDocument;
		$dom->formatOutput = true;
		$dom->save($filename);
	}
	
}
?>