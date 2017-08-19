<?php
//-------------------------------------------------------
// Purpose: To add fee cycle detail
//
// Author : Nishu Bindal
// Created on : (3.feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
require_once(MODEL_PATH . "/Fee/FeeCycleManager.inc.php");
$feeCycleManager = FeeCycleManager::getInstance();
define('MODULE','FeeCycleMasterNew'); 
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

	global $sessionHandler;
	$errorMessage ='';
 

    if (!isset($REQUEST_DATA['cycleName']) || trim($REQUEST_DATA['cycleName']) == '') {
        $errorMessage .= ENTER_FEECYCLE_NAME."\n";
    }
    if (!isset($REQUEST_DATA['cycleAbbr']) || trim($REQUEST_DATA['cycleAbbr']) == '') {
        $errorMessage .= ENTER_FEECYCLE_ABBR."\n";
	}
    if (!isset($REQUEST_DATA['fromDate']) || trim($REQUEST_DATA['fromDate']) == '') {
        $errorMessage .= ENTER_FEECYCLE_FROM_DATE."\n";
    }
    if (!isset($REQUEST_DATA['toDate']) || trim($REQUEST_DATA['toDate']) == '') {
        $errorMessage .= ENTER_FEECYCLE_TODATE."\n";
    }
	 if (!isset($REQUEST_DATA['academicFromDate']) || trim($REQUEST_DATA['academicFromDate']) == '') {
        $errorMessage .= "Enter Academic From Date";
    }
    if (!isset($REQUEST_DATA['academicToDate']) || trim($REQUEST_DATA['academicToDate']) == '') {
        $errorMessage .= "Enter Academic To Date";
    }
	 if (!isset($REQUEST_DATA['hostelFromDate']) || trim($REQUEST_DATA['hostelFromDate']) == '') {
        $errorMessage .= "Enter Hostel From Date";
    }
    if (!isset($REQUEST_DATA['hostelToDate']) || trim($REQUEST_DATA['hostelToDate']) == '') {
        $errorMessage .= "Enter Hostel To Date";
    }
 if (!isset($REQUEST_DATA['transportFromDate']) || trim($REQUEST_DATA['transportFromDate']) == '') {
        $errorMessage .= "Enter Transport From Date";
    }
    if (!isset($REQUEST_DATA['transportToDate']) || trim($REQUEST_DATA['transportToDate']) == '') {
        $errorMessage .= "Enter Transport To Date";
    }

     if (!isset($REQUEST_DATA['active']) || trim($REQUEST_DATA['active']) == '') {
        $errorMessage .= "Required Parameter is missing"."\n";
    }
    
	
       if($REQUEST_DATA['active'] == 1){
		$foundArray = $feeCycleManager->getFeeCycle(' AND status =1');
		if(count($foundArray) > 0) {  
	      		if(count($foundArray) > 0) {
				$errorMessage .= "Only One Fee Cycle Can be Active at a time.\n";
			   }
		}
        }
       
        $foundArray = $feeCycleManager->getFeeCycle(' AND UCASE(cycleName)= "'.add_slashes(trim(strtoupper($REQUEST_DATA['cycleName']))).'"');
        if(count($foundArray) > 0) {  //DUPLICATE CHECK
      		if(count($foundArray) > 0) {
			$errorMessage .= CYCLE_NAME_EXIST."\n";
		   }
        }
     
        $foundArray = $feeCycleManager->getFeeCycle(' AND UCASE(cycleAbbr)="'.add_slashes(trim(strtoupper($REQUEST_DATA['cycleAbbr']))).'"');
        if(count($foundArray) > 0) {  //DUPLICATE CHECK
      		if(count($foundArray) > 0) {
			 $errorMessage .=CYCLE_ABBR_EXIST."\n";
		   }     
        }
        
	if (trim($errorMessage) == '') {
		$returnStatus = $feeCycleManager->addFeeCycle();
		if($returnStatus === false) {
			echo FAILURE;
		}
		else {
			echo SUCCESS;
		}
	}
	else {
		echo $errorMessage;
	}
   
 
?>
