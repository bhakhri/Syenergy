<?php
/*
Email helper class for sending email from Limehard
*/

define("CAXIS_URL","http://beta.caribbeanaxis.com/");
define("SENDER_NAME", "Lime Hard");
define("SENDER_ADDRESS", "dehard@limehard.com");
require_once(BL_PATH . "/Helpers/Email.Base.php");
class EmailManager extends EmailBase {
	private static $instance = NULL;
	private static $debug;
//	public  $senderAddress = "dehard@caribbeanwindow.com";
//	public  $senderName = "Lime Hard";
	
	public function __construct(){
		self::$debug = true;
	}
	
	public static function getInstance(){
		if(self::$instance == NULL){
			self::$instance = new EmailManager();
			parent::logError("EmailManager::getInstance() instance created.");
//			parent::$instance = new EmailBase();
		}
		return self::$instance;
	}
	
	
	
	
	/*
	function sendRegistrationMail
	@param $recipientAddress reciever's email address
	@param $recipientName reciever's name
	@param $activationURL Activation URL
	*/
	
	public static function sendRegistrationMail($recipientAddress, $recipientName, $activationURL){
		if(UtilityManager::isEmpty($recipientAddress)){
			parent::logError("EmailManager::sendRegistrationMail()  Recipient Address blank");
			return false;
		}
		if(UtilityManager::isEmpty($recipientName)){
			parent::logError("EmailManager::sendRegistrationMail()  Recipient Name blank");
			return false;
		}
		if(UtilityManager::isEmpty($activationUrl)){
			parent::logError("EmailManager::sendRegistrationMail()  Recipient Name blank");
			return false;
		}
		
//		parent::$instance->senderAddress = 
//		require_once()
			$sender = SENDER_NAME;
			$senderAdd = SENDER_ADDRESS;
			
			$file=file_get_contents(TEMPLATES_PATH."/Email/userRegistration.html") or die(parent::logError("EmailManager::sendRegistrationMail() Couldn't read file userRegistration.html"));
			$email=str_replace("|||Recipent|||",$recipientName,$file);
			$email=str_replace("|||RegURL|||",$activationURL,$email);
			//this link is for display purpose only as it can broke in two lines and causing problems on clicking
			$email=str_replace("|||RegURL_BROKEN|||",$activationUrl,$email);
			$email=str_replace("|||Date|||",date("d F,Y"), $email);
			$email=str_replace("|||Spacer|||",IMG_PATH."/spacer.gif", $email);
			$email=str_replace("|||Graphic|||",IMG_PATH ."/EmailTemplate/", $email);
			$email=str_replace("|||Home|||",UI_HTTP_PATH, $email);
			$email=str_replace("|||cAxisHome|||",CAXIS_URL, $email);
			$email=str_replace("|||teamEmail|||",$senderAdd, $email);

			$recipientAddress = $recipientName . " <".$recipientAddress.">";
			$subject = "Confirm your registration.";
			$emailBase = new EmailBase();
			$emailBase->message = $message;		
			$emailBase->addHeaders($sender, $senderAdd);

			$emailBase->sendMail($recipientAddress, $subject, $email);
		
	}
		
	

}

?>