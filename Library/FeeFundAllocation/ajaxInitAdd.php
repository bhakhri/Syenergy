<?php 
//
//  THIS FILE CALLS ADD FUNCTION USED IN ADDING "FeeFundAllocation" RECORDS  
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
 
define('MODULE','FundAllocationMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);           
UtilityManager::headerNoCache();


global $sessionHandler;
$queryDescription =''; 

    $errorMessage ='';
    if (!isset($REQUEST_DATA['allocationEntity']) || trim($REQUEST_DATA['allocationEntity']) == '') {
        $errorMessage .= ENTER_FEEFUNDALLOCATION_ENTITY."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['entityType']) || trim($REQUEST_DATA['entityType']) == '')) {
        $errorMessage .= ENTER_FEEFUNDALLOCATION_TYPE."\n";
    }
    
    
   if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeeFundAllocationManager.inc.php");
        $foundArray = FeeFundAllocationManager::getInstance()->getFeeFundAllocation(' AND UCASE(allocationEntity)="'.add_slashes(strtoupper($REQUEST_DATA['allocationEntity'])).'"');
        if(trim($foundArray[0]['allocationEntity'])=='') {  //DUPLICATE CHECK
          $foundArray = FeeFundAllocationManager::getInstance()->getFeeFundAllocation(' AND UCASE(entityType)="'.add_slashes(strtoupper($REQUEST_DATA['entityType'])).'"');
          if(trim($foundArray[0]['allocationEntity'])=='') {  //DUPLICATE CHECK
                $returnStatus = FeeFundAllocationManager::getInstance()->addFeeFundAllocation();
                if($returnStatus === false) {
                    $errorMessage = FAILURE;
                }
                else {
			########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
			$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
			$auditTrialDescription = "Following Fund Allocation Entity Has Been Added : ";
			$auditTrialDescription .= $REQUEST_DATA['allocationEntity'];
			$type = FUND_ALLOCATION_ENTITY_ADDED; 
			$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDesription);
			if($returnStatus == false) {
				echo  "Error while saving data for audit trail";
				die;
			}
			########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
                    echo SUCCESS;           
                }
          }
          else {
             echo FEEFUNDALLOCATION_TYPE_EXIST;
        } 
      }
      else {
          echo FEEFUNDALLOCATION_ENTITY_EXIST;       
      }
    }
    else {
        echo $errorMessage;
    }
 
 

//$History: ajaxInitAdd.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/22/09    Time: 3:52p
//Updated in $/LeapCC/Library/FeeFundAllocation
//condition & formatting, required parameter checks updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/22/08   Time: 5:13p
//Updated in $/LeapCC/Library/FeeFundAllocation
//print sorting order set
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeFundAllocation
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeFundAllocation
//Define Module, Access  Added
//
//*****************  Version 5  *****************
//User: Arvind       Date: 9/09/08    Time: 7:14p
//Updated in $/Leap/Source/Library/FeeFundAllocation
//added common messages
//
//*****************  Version 4  *****************
//User: Arvind       Date: 8/05/08    Time: 1:35p
//Updated in $/Leap/Source/Library/FeeFundAllocation
//modified the query
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/01/08    Time: 6:39p
//Updated in $/Leap/Source/Library/FeeFundAllocation
//added check for duplicacy
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/11/08    Time: 6:30p
//Updated in $/Leap/Source/Library/FeeFundAllocation
//removed valiadation for InstituteId
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:18a
//Created in $/Leap/Source/Library/FeeFundAllocation
//Added files for new module

?>
