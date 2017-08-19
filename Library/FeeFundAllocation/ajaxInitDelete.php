<?php
//-------------------------------------------------------
// Purpose: To delete FeeFundAllocation detail
//
// Author : Arvind Singh Rawat
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
 
define('MODULE','FundAllocationMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

global $sessionHandler;
$queryDescription =''; 

    if (!isset($REQUEST_DATA['feeFundAllocationId']) || trim($REQUEST_DATA['feeFundAllocationId']) == '') {
        $errorMessage = 'Invalid feeFundAllocation';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeeFundAllocationManager.inc.php");
        $feeFundAllocationManager =  FeeFundAllocationManager::getInstance();
       
 	    $foundArray = $feeFundAllocationManager->getFeeHeadValue($REQUEST_DATA['feeFundAllocationId']);
        if($foundArray[0]['cnt']==0) {  //DUPLICATE CHECK  
	    $condition="AND feeFundAllocationId =".$REQUEST_DATA['feeFundAllocationId'];
	
	    $feeFundAllocationArray = $feeFundAllocationManager->getFeeFundAllocation($condition);
	    $allocationEntity = $feeFundAllocationArray[0]['allocationEntity'];
            if($feeFundAllocationManager->deleteFeeFundAllocation($REQUEST_DATA['feeFundAllocationId']) ) {
		########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
		$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
		$auditTrialDescription = "Following Fund Allocation Entity Has Been Deleted : ";
		$auditTrialDescription .=  $allocationEntity;
		$type =FUND_ALLOCATION_ENTITY_DELETED; 
		$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
		if($returnStatus == false) {
			echo  "Error while saving data for audit trail";
			die;
		}
		########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
                echo DELETE;
           }
           else {
               echo DEPENDENCY_CONSTRAINT;
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
//*****************  Version 2  *****************
//User: Parveen      Date: 7/22/09    Time: 3:52p
//Updated in $/LeapCC/Library/FeeFundAllocation
//condition & formatting, required parameter checks updated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeFundAllocation
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeFundAllocation
//Define Module, Access  Added
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/15/08    Time: 10:40a
//Updated in $/Leap/Source/Library/FeeFundAllocation
//Added a condition of Dependency constraint
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:18a
//Created in $/Leap/Source/Library/FeeFundAllocation
//Added files for new module

?>

