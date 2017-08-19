<?php
//require_once('common.inc.php');
	/**
	 * Enter description here...
	 *
	 */
class UtilityManager {

	/**
	 * Function for no cache
	 *
	 * @param nothing
	 * @return nothin
	 */
    public static function headerNoCache() {
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
            header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
            header("Cache-Control: no-cache, must-revalidate" );
            header("Pragma: no-cache" );
    }
    /**
     * Function to formate the date
     *
     * @param $date (YYYY-MM-DD)
     * @return formatted date
     */    
     public static function formatDate($date = '',$showTime=false) {
           if(trim($date)=='') {
               return '--';
           }
           else {
              //format is yyyy-mm-dd
               
               $yy = substr($date,0,4);
               $mm = abs(substr($date,5,2));
               $dd = substr($date,8,2);
               
               if($showTime){    

                   $hr = substr($date,11,2);
                   $min = substr($date,14,2);
                   //echo "<br>";
                   return date("d-M-y H:i", mktime($hr, $min, 0,$mm,$dd,$yy));

               }
               else{
               
                   return date("d-M-y",mktime(0,0,0,$mm,$dd,$yy));
               }
          }
    }     
    
	public static function includeCSS($fileName, $externalPath='' ,$cssTitle='',$id=0) {
		$path = CSS_PATH;
		if(isset($externalPath) && ($externalPath != "")){
			//will include js file from vendor folder for any external application
			$path = $externalPath;
		}
		return '<link rel="stylesheet" type="text/css" media="screen" title="'
		 . $cssTitle .'" id="css'.$id.'" href="'. $path ."/". $fileName . '" />' . "\n"	;
	}

	/**
	 * Function for include Javascript files on the pages
	 *
	 * @param $fileName, Name of the JS file.
	 * @return HTML OutPut
	 */
     
    //Function for include autosuggest.css file on the pages
   public static function includeCSS2() {
        $path = CSS_PATH;
        return '<link rel="stylesheet" type="text/css" href="'. $path .'/autosuggest.css" />' . "\n"    ;
    }

    //Hardcoded function to include Javascript files on the pages 
    public static function javaScriptFile2() {
        $path = JS_PATH ;
         return '<script src="'. $path .'/json.js" type="text/javascript"></script>
        <script src="'. $path .'/zxml.js" type="text/javascript"></script>
        <script src="'. $path .'/autosuggest.js" type="text/javascript"></script>' . "\n"    ;
    } 

	public static function javaScriptFile($fileName,$externalPath='') {
		$path = JS_PATH ;
		if(isset($externalPath) && ($externalPath != "")){
			//will include js file from vendor folder for any external application
			$path = $externalPath;
		}
		return '<script src="'. $path ."/". $fileName . '" type="text/javascript"></script>' . "\n"	;
	}
	
	/**
	 * Function for include Javascript files on the pages
	 *
	 * @param $fileName, Name of the JS file.
 	 * @param $externalPath path of the file if located somewhere else
	 * @return HTML OutPut
	 */
	public static function includeJS($fileName,$externalPath='') {
		$path = JS_PATH ;
		if(isset($externalPath) && ($externalPath != "")){
			//will include js file from vendor folder for any external application
			$path = $externalPath;
		}
		return '<script src="'. $path ."/". $fileName . '" type="text/javascript"></script>' . "\n"	;
	}

	/**
	 * Function to create a traget url.
	 *
	 * @param $pageName, PageName
	 * @param $parametersArray, Name and Value of the parameters that appended in the URL.
	 * @param $isAdmin, True if page in admin module otherwise False
	 * @return $targetURL, target page URL.
	 */

	public static function buildTargetURL($pageName='', $parametersArray = '', $isAdmin = false) {
		//global $languageDisplayInfo;
		$queryArr = Array();	
		$protocol = "";
		$parameters = '';
		$host = HTTP_PATH;

		$page = $pageName;
		
		if($pageName!=''){
			$urlArr = parse_url($pageName);
			$page = $urlArr['path'];
			$query = $urlArr['query'];
			parse_str($query,$queryArr);//parse query string to convert all the variables to array
		}

    	$parametersCount = count($parametersArray);
		if(isset($parametersArray) && is_array($parametersArray)&& $parametersCount > 0) {

			foreach($parametersArray as $key => $value) {				
                if($parameters=='') {
                    $parameters ='?'. $key . "=" . $value;
                }
                else {
                    $parameters .='&'. $key . "=" . $value;
                }
			}
		}
		
		if(isset($queryArr) && is_array($queryArr)&& count($queryArr) > 0) {
			foreach($queryArr as $key => $value) {
				//if key in the given page url is lang then skip it
				//if($key=='lang')
				//continue;
                if($parameters=='') {
                    $parameters ='?'. $key . "=" . $value;
                }
                else {
                    $parameters .='&'. $key . "=" . $value;
                }
			}
		}
		
		if($pageName==''){
			$pathUrl =  $_SERVER['PHP_SELF'];
		} else{
			if($isAdmin) {
				$pagePath = ADMIN_PATH_ROOT ."/". $page ;
			} else {
				$pagePath = UI_HTTP_PATH ."/". $page ;
			}
			$pathUrl = $protocol . $pagePath;
		}
		//$targetURL = $pathUrl . $reservedParameters . $parameters;
        $targetURL = $pathUrl . $parameters;
		return $targetURL;
	}
	
	/**
	 * Function detemines the whether the given value is numeric or not.
	 *
	 * @param $value
	 * @return boolean true or false
	 */
	public static function isNumeric($value) {
		if (is_numeric($value)) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * Detaermines whether the value is empty or not
	 *
	 * @param $value
	 * @return return boolean true or false
	 */

	public static function isEmpty($value) {
		if (isset($value) && (trim($value) == "")) {
			return true;
		}
		else {
			return false;
		}
	}
	
    /**
     * Detaermines whether the value is empty or not
     *
     * @param $value
     * @return return boolean true or false
     */

	public static function notEmpty($value) {
		if (isset($value) && (trim($value) != "")) {
			return true;
		}
		else {
			return false;
		}
	}
	
	public static function generatePassword($str){
		//return $str;
//		print_r($str);die;
        return substr(md5($str.date('YmdHis').microtime()),0,8);	
	}
	
	public static function generateErrorBlock($arrErrors){
		if(isset($arrErrors) && (count($arrErrors) > 0)){
			$total = count($arrErrors);
			echo self::includeJS("spryEffects.js");
			$str = "<div id='errorBlock'  style='position:relative;'>
					<table width='65%' class='green_row' cellpadding='2' cellspacing='0' border='0'>
					<tr><td width='30px' valign='top'><img src='" .IMG_PATH. "/warning.gif'></td><td class='errHeading'>Oops something went wrong, Here is what you can do.</td><td width='5px' style='cursor:pointer;' valign='middle' onclick=\"Spry.Effect.DoFade('errorBlock', {duration: 1000, from:  100, to:  0, toggle: true}); setTimeout('Spry.Effect.DoBlind(\'errorBlock\', {duration: 1000, from:  \'100%\', to:  \'0%\', toggle: true})',1000);\">&nbsp;X&nbsp;</td></tr>
					<tr><td colspan='3' style='height:10px;'></td></tr>";
			for($i=0; $i < $total; $i++){
				$str .= "<tr><td colspan='3' class='red_txt'>" . $arrErrors[$i] . "</td></tr>";
			}
			$str .= "</table></div>";
			return $str;
		}
		return ;
	}
	
	public static function generateSuccessBlock($arrSuccess){
		if(isset($arrSuccess) && (count($arrSuccess) > 0)){
			$total = count($arrSuccess);
			echo self::includeJS("spryEffects.js");
			$str = "<div id='successBlock'  style='position:relative;'>
					<table width='65%' class='green_row' cellpadding='2' cellspacing='0' border='0'>";
			for($i=0; $i < $total; $i++){
				$str .= "<tr><td colspan='3' class='red_txt'>" . $arrSuccess[$i] . "</td></tr>";
			}
			$str .= "</table></div>";
			return $str;
		} 
		return ;
	}

     /**
     * Function to protect different client institute access 
     *
     * @return redirect to login page if not logged in
     */
	public static function checkDomainLogin() {
        global $sessionHandler;
		$uniqueKey = LIB_PATH;
		if ($sessionHandler->getSessionVariable('UniqueKey') != '') {
			if ($sessionHandler->getSessionVariable('UniqueKey') != $uniqueKey) {
				$sessionHandler->destroySession();
				redirectBrowser(UI_HTTP_PATH.'/index.php');
				die;
			}
		}
		else {
			$sessionHandler->setSessionVariable('UniqueKey',$uniqueKey);
		}
	}
    
     /**
     * Function to check if administrator not logged in 
     *
     * @return redirect to login page if not logged in
     */
    public static function ifNotLoggedIn($internalFile = false) {
        global $sessionHandler, $checkAccessArray;
		UtilityManager::checkDomainLogin();
        // we will change this after once role permissions screen is completed
        // $sessionHandler->getSessionVariable('RoleId') ==1 to  be added later
		/*
        if(UtilityManager::isEmpty($sessionHandler->getSessionVariable('AdminId') ) ) {
                redirectBrowser(UI_HTTP_PATH.'/index.php');
        }
		*/
		
		//the user is other than administrator, teacher, student and parent
		
        if(!in_array($sessionHandler->getSessionVariable('RoleId'), $checkAccessArray) && UtilityManager::notEmpty($sessionHandler->getSessionVariable('RoleId'))) {
			require_once(BL_PATH . '/RoleManager.inc.php');
			$roleManager = RoleManager::getInstance();
			if (!$roleManager->hasRoleAccess()) {
				redirectBrowser(UI_HTTP_PATH.'/indexHome.php?z=1');
			}
			if (!$roleManager->hasFileAccess($internalFile)) {
				if ($internalFile === true) {
					echo ACCESS_DENIED;
					die;
				}
				redirectBrowser(UI_HTTP_PATH.'/indexHome.php?z=1');
			}
		}
        // check if user wants to access Live application from Demo login 
        else if($sessionHandler->getSessionVariable('ApplicationPath')!=HTTP_PATH) {
                $sessionHandler->destroySession();
				if ($internalFile) {
					echo SESSION_TIMEOUT;
					die;
				}
                redirectBrowser(UI_HTTP_PATH.'/sessionError.php');
        }
        else if(UtilityManager::isEmpty($sessionHandler->getSessionVariable('AdminId')) && !defined('MANAGEMENT_ACCESS') ) { 
            // to access admin reports in management, we have defined MANAGEMENT_ACCESS variable in the files
				if ($internalFile) {
					echo SESSION_TIMEOUT;
					die;
				}
                redirectBrowser(UI_HTTP_PATH.'/sessionError.php');
        }
    }
     /**
     * Function to check if logged in 
     *
     * @return redirect to corresponding role home page if logged in
     */
    public static function ifLoggedIn() {
        global $sessionHandler;
        if( $sessionHandler->getSessionVariable('RoleId') ==1  ) {
            if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('AdminId') ) ) {
                redirectBrowser(UI_HTTP_PATH.'/indexHome.php');
            }
        }
        else if( $sessionHandler->getSessionVariable('RoleId') ==2  ) {
            if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('EmployeeId') ) ) {
              if(CURRENT_PROCESS_FOR=='sc') {   //subject centric
                redirectBrowser(UI_HTTP_PATH.'/Teacher/scIndex.php');
              }
              else {
                redirectBrowser(UI_HTTP_PATH.'/Teacher/index.php');
              }
            }
        }
        else if( $sessionHandler->getSessionVariable('RoleId') ==3  ) {
            if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('ParentId') ) ) {
              if(CURRENT_PROCESS_FOR=='sc') {   //subject centric
                redirectBrowser(UI_HTTP_PATH.'/Parent/scIndex.php');
              }
              else {
                redirectBrowser(UI_HTTP_PATH.'/Parent/index.php');
              }
            }
        }
        else if( $sessionHandler->getSessionVariable('RoleId') ==4  ) {
            if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('StudentId') ) ) {
              if(CURRENT_PROCESS_FOR=='sc') {   //subject centric
                redirectBrowser(UI_HTTP_PATH.'/Student/scIndex.php');
              }
              else {
                redirectBrowser(UI_HTTP_PATH.'/Student/index.php');
              }
            }
        }
		else if( $sessionHandler->getSessionVariable('RoleId') ==5  ) {
            if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('EmployeeId') ) ) {
              if(CURRENT_PROCESS_FOR=='sc') {   //subject centric
                redirectBrowser(UI_HTTP_PATH.'/Management/scIndex.php');
              }
              else {
                redirectBrowser(UI_HTTP_PATH.'/Management/index.php');
              }
            }
        }
		else if( $sessionHandler->getSessionVariable('RoleId') !=0 && UtilityManager::notEmpty($sessionHandler->getSessionVariable('RoleId')) ) {
             redirectBrowser(UI_HTTP_PATH.'/indexHome.php');
		}
    }
    /**
    * Function to check if teacher not logged in 
    *
    * @return redirect to login page if not logged in
    */
    public static function ifTeacherNotLoggedIn($internalFile = false) {
		UtilityManager::checkDomainLogin();
        global $sessionHandler;
        if(UtilityManager::isEmpty($sessionHandler->getSessionVariable('EmployeeId') ) || $sessionHandler->getSessionVariable('RoleId') !=2 ) {
			$callerFile = $_SERVER['SCRIPT_FILENAME'];
			if (stristr($callerFile, '/Library/') !== false or $internalFile) {
				echo SESSION_TIMEOUT;
				die;
			}
            redirectBrowser(UI_HTTP_PATH.'/index.php');
        }
    }
    /**
    * Function to check if parent not logged in 
    *
    * @return redirect to login page if not logged in
    */
    public static function ifParentNotLoggedIn($internalFile = false) {
		UtilityManager::checkDomainLogin();
        global $sessionHandler;
        if(UtilityManager::isEmpty($sessionHandler->getSessionVariable('ParentId') ) || $sessionHandler->getSessionVariable('RoleId') !=3 ) {
			$callerFile = $_SERVER['SCRIPT_FILENAME'];
			if (stristr($callerFile, '/Library/') !== false or $internalFile) {
				echo SESSION_TIMEOUT;
				die;
			}
            redirectBrowser(UI_HTTP_PATH.'/index.php');
        }
    }
    /**
    * Function to check if student not logged in 
    *
    * @return redirect to login page if not logged in
    */
    public static function ifStudentNotLoggedIn($internalFile = false) {
		UtilityManager::checkDomainLogin();
        global $sessionHandler;
        if(UtilityManager::isEmpty($sessionHandler->getSessionVariable('StudentId') ) || $sessionHandler->getSessionVariable('RoleId') !=4 ) {
			$callerFile = $_SERVER['SCRIPT_FILENAME'];
			if (stristr($callerFile, '/Library/') !== false or $internalFile) {
				echo SESSION_TIMEOUT;
				die;
			}
            redirectBrowser(UI_HTTP_PATH.'/index.php');
        }
     }
	 
	/**
    * Function to check if management not logged in 
    *
    * @return redirect to login page if not logged in
    */
    public static function ifManagementNotLoggedIn($internalFile = false) {
		UtilityManager::checkDomainLogin();
        global $sessionHandler;
        if(UtilityManager::isEmpty($sessionHandler->getSessionVariable('EmployeeId') ) || $sessionHandler->getSessionVariable('RoleId') !=5 ) {
			$callerFile = $_SERVER['SCRIPT_FILENAME'];
			if (stristr($callerFile, '/Library/') !== false or $internalFile) {
				echo SESSION_TIMEOUT;
				die;
			}
            redirectBrowser(UI_HTTP_PATH.'/index.php');
        }
    }
     /**
     * Function to send email 
     *
     * @return boolean
     */    
    public static function sendMail($to, $subject, $body, $from) {
        return @mail($to, $subject, $body, $from);
    }    
     /**
     * Function to send SMS 
     *
     * @return boolean
     */    
    public static function sendSMS($mobileNo, $message, $sender='CHALKPAD') {
        $message =str_ireplace('&amp;','&',$message);
        $message =urlencode(preg_replace("/&#?[a-z0-9]+;/i","",$message));
        
        if(defined('CLIENT_NAME') && CLIENT_NAME=='SGI') {                
            $postVars = 'data=<?xml version="1.0" encoding="ISO-8859-1"?><!DOCTYPE MESSAGE SYSTEM "http://127.0.0.1/psms/dtd/messagev12.dtd" ><MESSAGE VER="1.2"><USER USERNAME="'.SMS_GATEWAY_USERNAME.'" PASSWORD="'.SMS_GATEWAY_PASSWORD.'"/><SMS  UDH="0" CODING="1" TEXT="'.$message.'sgiw" PROPERTY="0" ID="1"><ADDRESS FROM="'.SMS_GATEWAY_SNDR_VALUE.'" TO="'.$mobileNo.'" SEQ="1" TAG="" /></SMS></MESSAGE>&action=send';
        }
        else {    
	// $mobileNo = '9878425461,9855094422';
            $postVars = SMS_GATEWAY_USER_VARIABLE.'='.SMS_GATEWAY_USERNAME.'&'.SMS_GATEWAY_PASS_VARIABLE.'='.SMS_GATEWAY_PASSWORD.'&'.SMS_GATEWAY_NUMBER_VARIABLE.'='.$mobileNo.'&'.SMS_GATEWAY_SNDR_VARIABLE.'='.$sender.'&'.SMS_GATEWAY_MESSAGE_VARIABLE.'='.$message;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, SMS_GATEWAY_URL); //set the url
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
        curl_setopt($ch, CURLOPT_POST, 1); //set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars); //set the POST variables
        $response = curl_exec($ch); //run the whole process and return the response
        curl_close($ch); //close the curl handle
        if(preg_match("/failure/i",$response)) {
            logError('SMS Response: '.$response);
            return false;
        }
        else if(preg_match("/ERROR/i",$response)) {
            logError('SMS Response: '.$response);
            return false;
        }
        else {
            return true;
        }
    }
     /**
     * Function to make CSV
     *
     * @return boolean
     */    
	public static function makeCSV($csvData, $fileName) {
		ob_end_clean();
		header("Cache-Control: public, must-revalidate");
		// We'll be outputting a PDF
		header('Content-type: application/octet-stream');
		header("Content-Length: " .strlen($csvData) );
		// It will be called downloaded.pdf
		header('Content-Disposition: attachment;  filename="'.$fileName.'"');
		// The PDF source is in original.pdf
		header("Content-Transfer-Encoding: binary\n");
		echo $csvData;
	}

     /**
     * Function to write XML
     *
     * @return boolean
     */    
	public static function writeXML($xmlData, $fileName) {
		if(is_writable($fileName)) {
			$handle = @fopen($fileName, 'w');
			if (@fwrite($handle, $xmlData) === FALSE){
				die("unable to write");
			}
		}
		else{
			logError("unable to open file");
		}
	}

	public static function makeCSList($array, $field, $delimiter = ',', $makeValueString = false) {
		$str = '';
		foreach($array as $record) {
			$value = $record[$field];
			if (!empty($str)) {
				$str .= $delimiter;
			}
			if ($makeValueString == true) {
				$str .= "'$value'";

			}
			else {
				$str .= $value;
			}
		}
		return $str;
	}
	public static function getRGB($color) {
		$rColor = intval("0x".substr($color, 0,2),16);
		$gColor = intval("0x".substr($color, 2,2),16);
		$bColor = intval("0x".substr($color, 4,2),16);
		return array($rColor, $gColor, $bColor);
	}
    
    // Function is change the text is Title Case i.e. 'PATiala' Convert to title case is 'Patiala'
    public static function getTitleCase($string) {
        $len=strlen($string);
        $i=0;
        $last= "";
        $new= "";
        $string=strtoupper($string);
        while ($i<$len):
            $char=substr($string,$i,1);
            if (ereg( "[A-Z]",$last)):
                $new.=strtolower($char);
            else:
                $new.=strtoupper($char);
            endif;
            
            $last=$char;
            $i++;
        endwhile;
        return($new);
    }

     /**
     * Function to check if administrator not logged in 
     *
     * @return redirect to login page if not logged in
     */
    public static function ifCompanyNotSelected() {
        global $sessionHandler;
		if(UtilityManager::isEmpty($sessionHandler->getSessionVariable('CompanyId') ) ) {
            redirectBrowser(UI_HTTP_ACCOUNTS_PATH.'/listCompanySelect.php');
        }
    }
	
	//Function for include autosuggest files on the pages
		public static function includeAutosuggest() {
        $cssPath = CSS_PATH;
		$jsPath = JS_PATH ;
		$str='<link rel="stylesheet" type="text/css" href="'. $cssPath .'/autosuggest/searchfield.css" />' . "\n";
		$str=$str.'<script src="'.$jsPath.'/autosuggest/searchfield.js" type="text/javascript"  /></script>' . "\n";
        return $str;
    }

}
?>