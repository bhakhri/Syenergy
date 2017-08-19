<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
class ContactImport {
	  private static $isDebug = true;
      protected $userName;
      protected $password;
      function __construct() {
        $this->userName = $userName;
        $this->password = $password;
      }
      public function logError($message){
      	if(self::$isDebug == true){
      		logError($message);
      	}
      	return ;
      }
    }
    class YahooContacts extends ContactImport {
    	function __construct($userName = '', $password = ''){
    		$this->loginStatus = self::userAuthenticate($userName, $password);
    		if($this->loginStatus == true){
    			self::getContacts();
			}else{
				parent::logError("Someone tried to Import Contacts from YAHOO as $userName .");
			}
    		
    	}
    	function userAuthenticate($userName, $password){
    		require_once(BL_PATH."/Extras/yahoo.php");
    		return Yahoo::userAuthenticate($userName, $password);
    	}
    	function getContacts(){
    		require_once(BL_PATH."/Extras/yahoo.php");
    		$arrContact = Yahoo::get_contacts();
    		if(is_array($arrContact)){
    			return $arrContact;    		
    		}else {
    			return false;
    		}
    		
    	}
    	function getErrorMessage(){
    		require_once(BL_PATH."/Extras/yahoo.php");
    		return Yahoo::getErrorMessage();
    	}
    }
     class GmailContacts extends ContactImport {
     	var $loginStatus;
    	function __construct($userName = '', $password = ''){
    		$this->loginStatus = self::userAuthenticate($userName, $password);
    		if($this->loginStatus == true){
    			self::getContacts();
			}else{
				parent::logError("Someone tried to Import Contacts from GMAIL as $userName .");
			}
    		
    	}
    	function userAuthenticate($userName, $password){
    		require_once(BL_PATH."/Extras/gmail.php");
    		return Gmail::userAuthenticate($userName, $password);
    	}
    	function getContacts(){
    		require_once(BL_PATH."/Extras/gmail.php");
    		$arrContact = Gmail::get_contacts();
    		if(is_array($arrContact)){
    			return $arrContact;    		
    		}else {
    			return false;
    		}
    	}
    	function getErrorMessage(){
    		require_once(BL_PATH."/Extras/gmail.php");
    		return Gmail::getErrorMessage();
    	}
    }
    class MsnContacts extends ContactImport {
     	var $loginStatus;
    	function __construct($userName = '', $password = ''){
    		$this->loginStatus = self::userAuthenticate($userName, $password);
    		if($this->loginStatus == true){
    			self::getContacts();
			}else{
				parent::logError("Someone tried to Import Contacts from GMAIL as $userName .");
			}
    		
    	}
    	function userAuthenticate($userName, $password){
    		require_once(BL_PATH.'/Extras/msn_contact_grab.class.php');
    		return msn::connect($userName, $password);
    	}
    	function getContacts(){
    		require_once(BL_PATH.'/Extras/msn_contact_grab.class.php');
    			msn::rx_data();
				msn::process_emails();
//				msn::email_output;
				$returned_emails = msn::$email_output;
//    			$arrContact = msn::get_contacts();
    			if(is_array($returned_emails)){
	    			return $returned_emails;    		
	    		}else {
	    			return false;
	    		}
    	}
    	function getErrorMessage(){
    		require_once(BL_PATH.'/Extras/msn_contact_grab.class.php');
    		return msn::getErrorMessage();
    	}
    }

    class ContactImportFactory {
      public static function checkServiceProvider($data) {
      	 switch ($data['serviceProvider']) {
          case YAHOO :
            $factoryContactImport = new YahooContacts($data['userName'], $data['password']);
            return $factoryContactImport;
          case GMAIL :
            $factoryContactImport = new GmailContacts($data['userName'], $data['password']);
            return $factoryContactImport;
          case HOTMAIL :
             $factoryContactImport = new MsnContacts($data['userName'], $data['password']);
            return $factoryContactImport;
          default:
          	echo "NO Provider found";  
          }
      }
    }

   
//user below lines in calling module
    $_POST['userName'] = "varuncoomar@hotmail.com";
    $_POST['password'] = "480233";
    $_POST['serviceProvider'] = "HOTMAIL";
    $data = $_POST;
    $factoryContactImport = new ContactImportFactory();
    $objContacts = $factoryContactImport->checkServiceProvider($data);
    
    $flag = $objContacts->loginStatus;
    if($flag == true){
    	print "<pre>d";
    	print_r($objContacts->getContacts());
    }else{
    	$objContacts->getErrorMessage();
    } 
//require_once(BL_PATH.'/Extras/msn_contact_grab.class.php');
//msn::connect('varuncoomar@hotmail.com', '480233');
//msn::rx_data();//die;
//msn::process_emails();
//$returned_emails = msn::$email_output;
//echo "<pre>";
//print_r($returned_emails);
    ?>