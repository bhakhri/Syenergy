<?php 
//
//----------------------------------------------------------------------------------------------
//  THIS FILE IS USED FOR ADDING A RECORD IN THE DATABASE THROUGH AJAX IN "FeeCycleFine" MODULE
//
//  Author :Arvind Singh Rawat
//  Created on : 1st-July-2008
//  Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
//
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','FeeCycleFines');    
define('ACCESS','add');
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
	
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeeCycleFineManager.inc.php");
  //      $foundArray = FeeCycleFineManager::getInstance()->getFeeCycleFine(' WHERE UCASE(feeCycleFineId)="'.add_slashes(strtoupper($REQUEST_DATA['feeCycleFineId'])).'"');
 //       if(trim($foundArray[0]['feeCycleFineId'])=='') {  //DUPLICATE CHECK
			$returnStatus = FeeCycleFineManager::getInstance()->addFeeCycleFine();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
			 $cycleNameArray = FeeCycleFineManager::getInstance()->geatFeeCycle($REQUEST_DATA['feeCycleId']);
			$cycleName=$cycleNameArray[0]['cycleName'];
			/*########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
			$auditTrialDescription = "Following fee cycle fine has been created: ";
			$auditTrialDescription .= $cycleName;
			$type = FEE_CYCLE_FINE_ADDED; //Fee Head is created
			$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription);
			if($returnStatus == false) {
				echo  "Error while saving data for audit trail";
				die;
			}
			########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
                	*/echo SUCCESS;           
            }
        }
  //      else {
   //         echo 'The Fee Cycle Fine Id you entered already exists.';
 //       }
 //   }
    else {
        echo $errorMessage;
    }
 

//$History: ajaxInitAdd.php $
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
//*****************  Version 5  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeCycleFine
//Define Module, Access  Added
//
//*****************  Version 4  *****************
//User: Arvind       Date: 9/09/08    Time: 7:13p
//Updated in $/Leap/Source/Library/FeeCycleFine
//added common messages
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/01/08    Time: 4:56p
//Updated in $/Leap/Source/Library/FeeCycleFine
//modified the validation message for dates
//
//*****************  Version 2  *****************
//User: Arvind       Date: 8/01/08    Time: 4:55p
//Updated in $/Leap/Source/Library/FeeCycleFine
//reomoved <br/> from validations
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:17a
//Created in $/Leap/Source/Library/FeeCycleFine
//Added library files of" feecyclefine" module

?>
