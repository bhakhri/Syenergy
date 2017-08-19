<?php
//-------------------------------------------------------
// Purpose: To update feecycle table data
//
// Author : Jaineesh
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','FeeCycleMaster'); 
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
	  global $sessionHandler;
	   $queryDescription =''; 
    if (!isset($REQUEST_DATA['cycleName']) || trim($REQUEST_DATA['cycleName']) == '') {
        $errorMessage .= ENTER_FEECYCLE_NAME."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['cycleAbbr']) || trim($REQUEST_DATA['cycleAbbr']) == '')) {
        $errorMessage .= ENTER_FEECYCLE_ABBR."\n";
	}
  
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeeCycleManager.inc.php");
        $foundArray = FeeCycleManager::getInstance()->getFeeCycle(' WHERE (UCASE(cycleName)= "'.add_slashes(trim(strtoupper($REQUEST_DATA['cycleName']))).'" OR UCASE(cycleAbbr)="'.add_slashes(trim(strtoupper($REQUEST_DATA['cycleAbbr']))).'") AND feeCycleId!='.$REQUEST_DATA['feeCycleId']);
            
        if(trim($foundArray[0]['cycleAbbr'])=='') {  //DUPLICATE CHECK
            $returnStatus = FeeCycleManager::getInstance()->editFeeCycle($REQUEST_DATA['feeCycleId']);
            
            if($returnStatus === false) {
                echo FAILURE;
            }
            else {
		
		########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
		$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
		$auditTrialDescription = "Following Fee Cycle Has Been Edited : ";
		$auditTrialDescription .= $REQUEST_DATA['cycleName'];
		$type = FEE_CYCLE_EDITED; 
		$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
		if($returnStatus == false) {
			echo  "Error while saving data for audit trail";
			die;
		}
		########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
                
		echo SUCCESS;           
            }
        }
		else {
		    if(strtoupper($foundArray[0]['cycleName'])==trim(strtoupper($REQUEST_DATA['cycleName']))) {
			 echo CYCLE_NAME_EXIST;
			 die;
		   }
		   else if(strtoupper($foundArray[0]['cycleAbbr'])==trim(strtoupper($REQUEST_DATA['cycleAbbr']))) {
			 echo CYCLE_ABBR_EXIST;
			 die;
		   }
		   
		}

        /*else {
            echo 'The Cycle Abbr. you entered already exists.';
        }*/
    }
    else {
        echo $errorMessage;
    }
    
    
// $History: ajaxInitEdit.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/13/09    Time: 4:55p
//Updated in $/LeapCC/Library/FeeCycle
//fixed bug nos.0000932,0000544,0000550,0000549,0000949
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/13/09    Time: 12:54p
//Created in $/SnS/Library/FeeCycle
//fixed bug nos.0000548,0000549,0000550
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/12/09    Time: 7:27p
//Updated in $/LeapCC/Library/FeeCycle
//fixed bug nos. 0000969, 0000965, 0000962, 0000963, 0000980, 0000950
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeCycle
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeCycle
//Define Module, Access  Added
//
//*****************  Version 4  *****************
//User: Arvind       Date: 9/10/08    Time: 6:02p
//Updated in $/Leap/Source/Library/FeeCycle
//modified
//
//*****************  Version 3  *****************
//User: Arvind       Date: 9/09/08    Time: 6:56p
//Updated in $/Leap/Source/Library/FeeCycle
//added common messages
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/24/08    Time: 7:36p
//Updated in $/Leap/Source/Library/FeeCycle
//edited the two date field validations
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/28/08    Time: 1:44p
//Created in $/Leap/Source/Library/FeeCycle
//ajax functions used for delete, edit, update, search, add 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/27/08    Time: 12:34p
//Created in $/Leap/Source/Library/HostelRoom
//ajax functions of add, delete, edit & search
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:57p
//Updated in $/Leap/Source/Library/States
//just added duplicate message and changed single quote to double quotes
//
//*****************  Version 2  *****************
//User: Administrator Date: 6/13/08    Time: 3:42p
//Updated in $/Leap/Source/Library/States
//added Comments and refined the code
?>

