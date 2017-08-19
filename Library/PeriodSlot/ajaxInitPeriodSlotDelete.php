<?php
//-------------------------------------------------------
// Purpose: To delete a Period Slot
//
// Author : Jaineesh
// Created on : (15.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PeriodSlotMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true); 
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['periodSlotId']) || trim($REQUEST_DATA['periodSlotId']) == '') {
        $errorMessage = 'Invalid Period Slot';
    }
    if (trim($errorMessage) == '') {
		require_once(MODEL_PATH . "/PeriodSlotManager.inc.php");
		$periodSlotManager =  PeriodSlotManager::getInstance();
		$foundArray = $periodSlotManager->getPeriodSlot(' WHERE periodSlotId='.$REQUEST_DATA['periodSlotId']);
		
		if($foundArray[0]['isActive']==0) {
			
			$foundArray1 = $periodSlotManager->getPeriodSlotId(' WHERE period.periodSlotId='.$REQUEST_DATA['periodSlotId']);
			
			if ($foundArray1[0]['periodSlotId']==0) {
			 if($periodSlotManager->deletePeriodSlot($REQUEST_DATA['periodSlotId'])) {
             echo DELETE;
			 }
			}
           else {
                echo DEPENDENCY_CONSTRAINT;
            }
        }
       else{
           echo ACTIVE_PERIODSLOT_DELETE;
       }  
    }
   else {
        echo $errorMessage;
    }


	 
// $History: ajaxInitPeriodSlotDelete.php $    
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:33p
//Created in $/LeapCC/Library/PeriodSlot
//to get the file add, delete & edit records
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:15p
//Created in $/Leap/Source/Library/PeriodSlot
//use the file during delete
//
?>