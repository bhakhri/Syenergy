<?php
/*
  This File calls addFunction used in adding Config Records

 Author :Ajinder Singh
 Created on : 05-Sep-2008
 Copyright 2008-2009: syenergy Technologies Pvt. Ltd.

--------------------------------------------------------
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['param']) || trim($REQUEST_DATA['param']) == '') {
        $errorMessage = ENTER_PARAM_NAME;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['label']) || trim($REQUEST_DATA['label']) == '')) {
        $errorMessage .= ENTER_LABEL;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['val']) || trim($REQUEST_DATA['val']) == '')) {
        $errorMessage .= ENTER_VALUE;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ConfigManager.inc.php");
        $foundArray = ConfigManager::getInstance()->getParam(' WHERE UCASE(param)="'.add_slashes(strtoupper($REQUEST_DATA['param'])).'"');  

        if(trim($foundArray[0]['param'])=='') {  //DUPLICATE CHECK
			$returnStatus = ConfigManager::getInstance()->addConfig();
			if($returnStatus === false) {
				$errorMessage = FAILURE;
			}
			else {
				echo SUCCESS;           
			}
		}
		else {
			echo PARAM_ALREADY_EXIST;
		}
	}
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Config
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/05/08    Time: 5:41p
//Created in $/Leap/Source/Library/Config
//file added for config masters
//

?>

