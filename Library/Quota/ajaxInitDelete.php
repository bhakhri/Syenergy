<?php
//-------------------------------------------------------
// Purpose: To delete quota detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','QuotaMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
$errorMessage = '';
    if (!isset($REQUEST_DATA['quotaId']) || trim($REQUEST_DATA['quotaId']) == '') {
        echo 'Invalid Quota';
        die;
    }
    
	require_once(MODEL_PATH . "/QuotaManager.inc.php");
	$quotaManager =  QuotaManager::getInstance();
	$recordArray = $quotaManager->checkInquota($REQUEST_DATA['quotaId']);
	if($recordArray[0]['found'] >0) {
		$errorMessage .= DEPENDENCY_CONSTRAINT;
	}
	
	if(trim($errorMessage) == ''){
		$recordArray1 = $quotaManager->checkInFeeHeadValues($REQUEST_DATA['quotaId']);
		if($recordArray1[0]['found'] >0) {
			$errorMessage .= DEPENDENCY_CONSTRAINT."\n";
		}
       }
       
    if(trim($errorMessage) == ''){
       if($quotaManager->deleteQuota($REQUEST_DATA['quotaId'])){
               echo DELETE;
       }
       else{
               echo DEPENDENCY_CONSTRAINT;
       }
    }
    else{
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Quota
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:49p
//Updated in $/Leap/Source/Library/Quota
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/17/08    Time: 4:57p
//Updated in $/Leap/Source/Library/Quota
//Added parent quota field
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:29p
//Updated in $/Leap/Source/Library/Quota
//Added dependency constraint check
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 12:53p
//Updated in $/Leap/Source/Library/Quota
//Added AjaxEnabled Delete Functionality
//Added Input Data Validation using Javascript
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/25/08    Time: 12:09p
//Created in $/Leap/Source/Library/Quota
//Initial checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 11:36a
//Updated in $/Leap/Source/Library/City
//Added AjaxEnabled Delete Functionality
?>

