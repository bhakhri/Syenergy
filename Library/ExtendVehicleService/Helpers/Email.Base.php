<?php
require_once( BL_PATH . "/UtilityManager.inc.php");
/*
Email helper class for sending email from Limehard
*/
class EmailBase{
	private static $instance = NULL;
	private static $debug;
	public   $recipientEmail;
	public  $subject;
	public  $message;
	public  static  $senderAddress ;
	public  static $senderName ;
	private $headers;

	
	public function __construct(){

		self::$debug = true;
	}
	
	public static function logError($error){
		if(self::$debug == true){
			logError($error);
		}
		return;
	}
	
	
	public  static function getInstance(){
		if(self::$instance == NULL){
			self::$instance = new EmailBase();
			self::logError("EmailBase::getInstance() instance created");
		}
		return self::$instance;
	}
	/*
	 function sendMail 
	 @param NULL
	 returns true if mail was sent successfully otherwise false;
	*/
	
	public  function sendMail($recipientAddress, $subject, $message){
		
		if(UtilityManager::isEmpty($recipientAddress)){
			self::logError("UtilityManager::isEmpty() Recipient Email address is blank.");
			return false;
		}
		if(UtilityManager::isEmpty($subject)){
			self::logError("UtilityManager::isEmpty() Subject of mail is blank.");
			return false;
		}
		if(UtilityManager::isEmpty($message)){
			self::logError("UtilityManager::isEmpty() Message body of email is blank.");
			return false;
		}
		//sending mail
		
		if(!(mail($recipientAddress,$subject,$message,$this->headers))){
			self::logError("EmailBase::sendMail() Couldn't send mail to " . $recipientEmail);
			return false;
		}
		
		self::logError("EmailBase::sendMail()  Sending mail to ". $recipientAddress);
		return true;
	}
	/*
	function addHeaders
	@param $arrHeaders array containing any extra headers
	ret
	*/
	
	
	public   function addHeaders($senderName='', $senderAddress){
		//In some cases we might not have the sender name
		if($senderName == ""){
			$senderName = "Lime Hard";
			
		}
		/*
		some common extra header types
		To :	A comma seperated list of recipient emails.
		From :	The senders email address.
		Reply-To :	The email address where replies should be sent to.
		Return-Path :	Kinda the same thing as the Reply-To. Some email clients require this, others create a default.
		Subject :	Subject of the email.
		CC :	Carbon Copy. A comma seperated list of more recipients that will be seen by all other recipients.
		BCC :	Blind Carbon Copy. A comma seperated list of more recipients that will not be seen by any other recipients.
		"X-Mailer: PHP/" . phpversion());
		*/
//			$headers = "";
			$headers = "From: " . $senderName ." <" . $senderAddress . ">\r\n";
			$headers .= "Content-type: text/html\r\n";
			$headers .= "Reply-To: noreply@limehard.com\r\n";
			//$headers .= "Return-Path: noreply@limehard.com\r\n";
			//$headers .= "X-Mailer: PHP/" . phpversion()."\r\n";
			$this->headers = $headers;
			

		return;
	}
	
	public function setSenderAddress($senderAddress){
		self::$senderAddress = $senderAddress;
	}
	
	public static  function getSenderAddress(){
		return self::$senderAddress;
	}
	
	public static function getSenderName(){
		return self::$senderName;
	}
	
	public function setSenderName($senderName){
		 self::$senderName = $senderName;
	}
}

?>