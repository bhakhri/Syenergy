<?php
//-------------------------------------------------------
// Purpose: To delete attendance Code detail
//
// Author : Ajinder Singh
// Created on : 20-Aug-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RangeLevelMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['rangeId']) || trim($REQUEST_DATA['rangeId']) == '') {
        $errorMessage = 'Invalid range ';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/RangeLevelManager.inc.php");
        $rangeLevelManager =  RangeLevelManager::getInstance();
  
            if($rangeLevelManager->deleteRange($REQUEST_DATA['rangeId']) ) {
                echo DELETE;
            }
       
       		else {
         	 echo DEPENDENCY_CONSTRAINT;
       		}
	}
 
    else {
        echo $errorMessage;
    }
   
// $History: ajaxInitDelete.php $
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