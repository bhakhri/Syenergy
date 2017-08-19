<?php
//
//  This File calls Edit Function used in adding Range Level Records
//
// Author :Ajinder Singh
// Created on : 20-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RangeLevelMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['rangeFrom']) || trim($REQUEST_DATA['rangeFrom']) == '') {
        $errorMessage .= 'Enter Range From<br/>';
    }
    if ($errorMessage == '' && !isset($REQUEST_DATA['rangeTo']) || trim($REQUEST_DATA['rangeTo']) == '') {
        $errorMessage .= 'Enter Range To<br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['rangeLabel']) || trim($REQUEST_DATA['rangeLabel']) == '')) {
        $errorMessage .= 'Enter Range Label<br/>';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/RangeLevelManager.inc.php");
        $foundArray = RangeLevelManager::getInstance()->getRange(' WHERE ('.add_slashes(strtoupper($REQUEST_DATA['rangeFrom'])).' BETWEEN rangeFrom AND rangeTo) AND rangeId!='.$REQUEST_DATA['rangeId']);

		if(trim($foundArray[0]['rangeId'])=='') {  //DUPLICATE from range NAME CHECK
        	$foundArray2 = RangeLevelManager::getInstance()->getRange(' WHERE ('.add_slashes(strtoupper($REQUEST_DATA['rangeTo'])).' BETWEEN rangeFrom AND rangeTo) AND rangeId!='.$REQUEST_DATA['rangeId']);
			if(trim($foundArray2[0]['rangeId'])=='') {  //DUPLICATE to range YEAR CHECK
				$returnStatus = RangeLevelManager::getInstance()->editRange($REQUEST_DATA['rangeId']);
				if($returnStatus === false) {
					$errorMessage = FAILURE;
				}
				else {
					echo SUCCESS;
				}
			}
			else {
				echo 'Range To already exists.';
			}
        }
        else {
            echo 'Range From already exists.';
        }
    }
    else {
        echo $errorMessage;
    }
//$History: ajaxInitEdit.php $	
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