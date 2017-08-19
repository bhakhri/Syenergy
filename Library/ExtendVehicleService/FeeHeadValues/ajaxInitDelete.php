<?php
//-------------------------------------------------------
// Purpose: To delete FEE HEAD VALUES detail
//
//
// Author :Arvind Singh Rawat
// Created on : 18-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeHeadValues');  
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['feeHeadValueId']) || trim($REQUEST_DATA['feeHeadValueId']) == '') {
        $errorMessage = 'Invalid Fee Head Value';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeeHeadValuesManager.inc.php");
        $feeHeadValuesManager =  FeeHeadValuesManager::getInstance();
        
        // Checks dependency constraint
        
//        $recordArray = $feeHeadValuesManager->checkInState($REQUEST_DATA['countryId']);
//        if($recordArray[0]['found']==0) {
            if($feeHeadValuesManager->deleteFeeHeadValues($REQUEST_DATA['feeHeadValueId']) ) {
                echo DELETE;
            }
 //       }
 //       else {
//            echo DEPENDENCY_CONSTRAINT;
//        }
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeHeadValues
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeHeadValues
//Define Module, Access  Added
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/19/08    Time: 12:45p
//Created in $/Leap/Source/Library/FeeHeadValues
//initial checkin

?>

