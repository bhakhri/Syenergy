<?php
//
//----------------------------------------------------------------------------------------
// THIS FILE IS USED TO DELETE DATA THROUGH AJAX FROM "FeeCycleFine" Module
//
// Author : Arvind Singh Rawat
// Created on : (1.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
//
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
 
define('MODULE','FeeCycleFines');    
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['feeCycleFineId']) || trim($REQUEST_DATA['feeCycleFineId']) == '') {
        $errorMessage = 'Invalid fee Cycle Fine';
    }
	
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeeCycleFineManager.inc.php");
        $feeCycleFineManager =  FeeCycleFineManager::getInstance();
        
        $cycleNameArray = $feeCycleFineManager->geatFeeCycleName($REQUEST_DATA['feeCycleFineId']);
	$cycleName=$cycleNameArray[0]['cycleName'];
            if($feeCycleFineManager->deleteFeeCycleFine($REQUEST_DATA['feeCycleFineId']) ) {
		/*
		########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
		$auditTrialDescription = "Following fee cycle fine has been deleted: ";
		$auditTrialDescription .= $cycleName;
		$type = FEE_CYCLE_FINE_DELETED; //Fee Head is created
		$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription);
		if($returnStatus == false) {
			echo  "Error while saving data for audit trail";
			die;
		}
		########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
                */echo DELETE;
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
//Created in $/LeapCC/Library/FeeCycleFine
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeCycleFine
//Define Module, Access  Added
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/15/08    Time: 10:39a
//Updated in $/Leap/Source/Library/FeeCycleFine
//Added a condition of Dependency constraint
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:17a
//Created in $/Leap/Source/Library/FeeCycleFine
//Added library files of" feecyclefine" module
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/25/08    Time: 11:54a
//Created in $/Leap/Source/Library/Country
//added new file which is used for deleting a record through ajax
?>

