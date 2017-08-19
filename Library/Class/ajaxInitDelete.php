<?php
//-------------------------------------------------------
// Purpose: To delete class detail
//
// Author : Rajeev Aggarwal
// Created on : (01.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['classId']) || trim($REQUEST_DATA['classId']) == '') {
        $errorMessage = 'Invalid Class';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ClassManager.inc.php");
        $classManager =  ClassManager::getInstance();
        $recordArray = $classManager->checkInStudent($REQUEST_DATA['classId']);
        if($recordArray[0]['found']==0) {
            if($classManager->deleteClass($REQUEST_DATA['classId']) ) {
                echo DELETE;
            }
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
//Created in $/LeapCC/Library/Class
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/11/08    Time: 5:16p
//Updated in $/Leap/Source/Library/Class
//file updated with dependency constraint
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/02/08    Time: 10:59a
//Created in $/Leap/Source/Library/Class
//intial checkin
?>

