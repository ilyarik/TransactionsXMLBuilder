<?php
class TransactionXML {

	private $parentXMLObj;
	private $payment;

	function __construct(SimpleXMLElement $XMLObj, $payment) {
		$this->parentXMLObj = $XMLObj;
		$this->payment = $payment;
	}

	public function getXML() {

		$transaction = $this->parentXMLObj->addChild('Transaction',null,BatchXML::$xmlns['ti']);

		$transactionId = $transaction->addChild('TransactionId',null,BatchXML::$xmlns['ti']);

		$localIDSet = $transactionId->addChild('LocalIDSet',null,BatchXML::$xmlns['bt']);
		$localIDSet->addChild('LocalID',null,BatchXML::$xmlns['bt']);
		$localIDSet->addChild('ComponentID',null,BatchXML::$xmlns['bt']);

		$transactionInfo = $transaction->addChild('TransactionInfo',null,BatchXML::$xmlns['ti']);
		$transactionInfo->addChild('Branch',null,BatchXML::$xmlns['ti']);
		$transactionInfo->addChild('TransactionTime',null,BatchXML::$xmlns['ti']);
		$transactionInfo->addChild('SenderPartCreateTime',null,BatchXML::$xmlns['ti']);

		$notificationOfOrderExecution = $transactionInfo->addChild('NotificationOfOrderExecution',null,BatchXML::$xmlns['ti']);

		$supplierInfo = $notificationOfOrderExecution->addChild('SupplierInfo',null,BatchXML::$xmlns['tns']);
		$supplierInfo->addChild('SupplierID',null,BatchXML::$xmlns['tns']);
		$supplierInfo->addChild('SupplierName',null,BatchXML::$xmlns['tns']);

		$recipientInfo = $notificationOfOrderExecution->addChild('RecipientInfo',null,BatchXML::$xmlns['tns']);
		$recipientInfo->addChild('INN',null,BatchXML::$xmlns['tns']);

		$legal = $recipientInfo->addChild('Legal',null,BatchXML::$xmlns['tns']);
		$legal->addChild('KPP',null,BatchXML::$xmlns['tns']);
		$legal->addChild('Name',null,BatchXML::$xmlns['tns']);

		$paymentInformation = $recipientInfo->addChild('PaymentInformation',null,BatchXML::$xmlns['tns']);
		$paymentInformation->addChild('RecipientINN',null,BatchXML::$xmlns['tns']);
		$paymentInformation->addChild('RecipientKPP',null,BatchXML::$xmlns['tns']);
		$paymentInformation->addChild('BankName',null,BatchXML::$xmlns['tns']);
		$paymentInformation->addChild('PaymentRecipient',$this->payment['name'],BatchXML::$xmlns['tns']);
		$paymentInformation->addChild('BankBIK',null,BatchXML::$xmlns['tns']);
		$paymentInformation->addChild('operatingAccountNumber',$this->payment['account'],BatchXML::$xmlns['tns']);	// here is account
		$paymentInformation->addChild('CorrespondentBankAccount',null,BatchXML::$xmlns['tns']);

		$orderInfo = $notificationOfOrderExecution->addChild('OrderInfo',null,BatchXML::$xmlns['tns']);
		$orderInfo->addChild('OrderID',null,BatchXML::$xmlns['tns']);
		$orderInfo->addChild('OrderDate',$this->payment['payment_date'],BatchXML::$xmlns['tns']);	// here is payment_date
		$orderInfo->addChild('OrderNum',null,BatchXML::$xmlns['tns']);
		$orderInfo->addChild('Amount',$this->payment['payment_amount'],BatchXML::$xmlns['tns']);	// here is payment_amount
		$orderInfo->addChild('PaymentPurpose',$this->payment['payment_subj'],BatchXML::$xmlns['tns']);	// here is payment_subj
		$orderInfo->addChild('Comment',$this->payment['user_comment'],BatchXML::$xmlns['tns']);		//here is user_comment
		$orderInfo->addChild('PaymentDocumentID',$this->payment['paymId'],BatchXML::$xmlns['tns']);	// here is paymId

	}
}
?>