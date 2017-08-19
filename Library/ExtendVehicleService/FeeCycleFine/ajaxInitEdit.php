<?php 

//  This File calls Edit Function used in adding FeeCycleFine Records
//
// Author :Arvind Singh Rawat
// Created on : 1-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
 
define('MODULE','FeeCycleFines');    
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache(); 
    $errorMessage ='';
   if (!isset($REQUEST_DATA['feeCycleId']) || trim($REQUEST_DATA['feeCycleId']) == '') {
        $errorMessage .= SELECT_FEECYCLE."\n";
    }
   /*if (!isset($REQUEST_DATA['feeHeadId']) || trim($REQUEST_DATA['feeHeadId']) == '') {
        $errorMessage .= SELECT_FEEHEAD."\n";
    }*/
	if (!isset($REQUEST_DATA['fromDate']) || trim($REQUEST_DATA['fromDate']) == '') {
        $errorMessage .= EMPTY_FROM_DATE."\n";
    }
	if (!isset($REQUEST_DATA['toDate']) || trim($REQUEST_DATA['toDate']) == '') {
        $errorMessage .= EMPTY_TO_DATE."\n";
    }
	if (!isset($REQUEST_DATA['fineAmount']) || trim($REQUEST_DATA['fineAmount']) == '') {
        $errorMessage .= ENTER_FEECYCLEFINE_AMOUNT."\n";
    }
	if (!isset($REQUEST_DATA['fineType']) || trim($REQUEST_DATA['fineType']) == '') {
        $errorMessage .= ENTER_FEECYCLEFINE_TYPE."\n";
    }
    if (trim($errorMessage) == '') 
	{
        require_once(MODEL_PATH . "/FeeCycleFineManager.inc.php");
 //       $foundArray = FeeCycleFineManager::getInstance()->getFeeCycleFine(' WHERE feeCycleFineId!='.$REQUEST_DATA['feeCycleFineId']);
//        if(trim($foundArray[0]['feeCycleFineId'])=='') {  //DUPLICATE CHECK
            $returnStatus = FeeCycleFineManager::getInstance()->editFeeCycleFine($REQUEST_DATA['feeCycleFineId']);            
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else{
		$cycleNameArray = FeeCycleFineManager::getInstance()->geatFeeCycleName($REQUEST_DATA['feeCycleFineId']);
		$cycleName=$cycleNameArray[0]['cycleName'];
		########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
		$auditTrialDescription = "Following fee cycle fine has been edited: ";
		$auditTrialDescription .= $cycleName;
		$type = FEE_CYCLE_FINE_EDITED; //Fee Head is created
		$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription);
		if($returnStatus == false) {
			echo  "Error while saving data for audit trail";
			die;
		}
		########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
                echo SUCCESS;           
            }
 //       }
 //       else {
 //           echo 'The fee Cycle Fine Id you entered already exists.';
  //      }
    }
    else {
        echo $errorMessage;
    }      
//$History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Library/FeeCycleFine
//updated with all the fees enhancements
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeCycleFine
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeCycleFine
//Define Module, Access  Added
//
//*****************  Version 3  *****************
//User: Arvind       Date: 9/09/08    Time: 7:13p
//Updated in $/Leap/Source/Library/FeeCycleFine
//added common messages
//
//*****************  Version 2  *****************
//User: Arvind       Date: 8/01/08    Time: 4:59p
//Updated in $/Leap/Source/Library/FeeCycleFine
//removed <br> from validation of date
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:17a
//Created in $/Leap/Source/Library/FeeCycleFine
//Added library files of" feecyclefine" module


?>


