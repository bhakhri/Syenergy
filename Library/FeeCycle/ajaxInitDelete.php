<?php
//-------------------------------------------------------
// Purpose: To delete fee cycle detail
//
// Author : Jaineesh
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','FeeCycleMaster'); 
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
	   
global $sessionHandler;
$queryDescription =''; 

    if (!isset($REQUEST_DATA['feeCycleId']) || trim($REQUEST_DATA['feeCycleId']) == '') {
        $errorMessage = 'Invalid Fee Cycle';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeeCycleManager.inc.php");
        $feeCycleManager =  FeeCycleManager::getInstance();
		   $feeCycleArray = $feeCycleManager->checkFeeCycle($REQUEST_DATA['feeCycleId']);
			 if($feeCycleArray[0]['cnt'] > 0 ) {
				echo DEPENDENCY_CONSTRAINT;
				die;
			 }
		   $feeHeadValuesArray = $feeCycleManager->checkFeeHeadValues($REQUEST_DATA['feeCycleId']);
		     if($feeHeadValuesArray[0]['cnt'] > 0 ) {
				echo DEPENDENCY_CONSTRAINT;
				die;
			 }
		  $feeReceiptValuesArray = $feeCycleManager->checkFeeReceiptValues($REQUEST_DATA['feeCycleId']);
		     if($feeReceiptValuesArray[0]['cnt'] > 0 ) {
				echo DEPENDENCY_CONSTRAINT;
				die;
			 }
            else { 
					$condition = "WHERE feeCycleId=".$REQUEST_DATA['feeCycleId'];
			$feeCycleNameArray = $feeCycleManager->getFeeCycle($condition);
			$cycleName = $feeCycleNameArray[0]['cycleName'];
				if ($feeCycleManager->deleteFeeCycle($REQUEST_DATA['feeCycleId'])) {
					
					########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
					$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
					$auditTrialDescription = "Following Fee Cycle Has Been Deleted : ";
					$auditTrialDescription .=$cycleName;
					$type = FEE_CYCLE_DELETED; 
					$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
					if($returnStatus == false) {
						echo  "Error while saving data for audit trail";
						die;
					}
					########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
				
					echo DELETE;
				}
            }
		}
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/13/09    Time: 4:55p
//Updated in $/LeapCC/Library/FeeCycle
//fixed bug nos.0000932,0000544,0000550,0000549,0000949
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/13/09    Time: 12:54p
//Created in $/SnS/Library/FeeCycle
//fixed bug nos.0000548,0000549,0000550
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeCycle
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeCycle
//Define Module, Access  Added
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/28/08    Time: 1:44p
//Created in $/Leap/Source/Library/FeeCycle
//ajax functions used for delete, edit, update, search, add 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/27/08    Time: 12:30p
//Created in $/Leap/Source/Library/Hostel
//ajax functions for add, edit, delete and search
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//added code to delete state
//
?>

