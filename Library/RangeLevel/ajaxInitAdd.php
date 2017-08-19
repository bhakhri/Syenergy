<?php
/*
  This File calls addFunction used in adding Range level Records

 Author :Ajinder Singh
 Created on : 20-Aug-2008
 Copyright 2008-2009: syenergy Technologies Pvt. Ltd.

--------------------------------------------------------
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RangeLevelMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['rangeFrom']) || trim($REQUEST_DATA['rangeFrom']) == '') {
        $errorMessage .= 'Enter Range From';
    }
    if ($errorMessage == '' && !isset($REQUEST_DATA['rangeTo']) || trim($REQUEST_DATA['rangeTo']) == '') {
        $errorMessage .= 'Enter Range To';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['rangeLabel']) || trim($REQUEST_DATA['rangeLabel']) == '')) {
        $errorMessage .= 'Enter Range Label';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/RangeLevelManager.inc.php");
        $foundArray = RangeLevelManager::getInstance()->getRange(' WHERE '.add_slashes(strtoupper($REQUEST_DATA['rangeFrom'])).' BETWEEN rangeFrom AND rangeTo');  

        if(trim($foundArray[0]['rangeId'])=='') {  //DUPLICATE CHECK
			//check for range to duplicacy
			$foundArray2 = RangeLevelManager::getInstance()->getRange(' WHERE '.add_slashes(strtoupper($REQUEST_DATA['rangeTo'])).' BETWEEN rangeFrom AND rangeTo');
			if (trim($foundArray2[0]['rangeId'] == '')) {
				$returnStatus = RangeLevelManager::getInstance()->addRange();

				if($returnStatus === false) {
					$errorMessage = FAILURE;
				}
				else {
					echo SUCCESS;           
				}
			}
			else {
				echo 'To Range already exists.';
			}
        }
        else {
            echo 'From Range already exists.';
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/26/09    Time: 1:32p
//Updated in $/LeapCC/Library/RangeLevel
//fixed issues: 9 & 10 ---SGI
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/RangeLevel
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/10/08   Time: 11:54a
//Updated in $/Leap/Source/Library/RangeLevel
//add define access in module
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/20/08    Time: 3:07p
//Created in $/Leap/Source/Library/RangeLevel
//file added for range level masters
//
?>

