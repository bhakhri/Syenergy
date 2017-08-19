<?php
//-------------------------------------------------------
// Purpose: To delete Fee Head detail
//
// Author : Arvind Singh Rawat
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;

require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','FeeHeads');     
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


global $sessionHandler;
$queryDescription =''; 


    if (!isset($REQUEST_DATA['feeHeadId']) || trim($REQUEST_DATA['feeHeadId']) == '') {
        $errorMessage = 'Invalid fee Head';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeeHeadManager.inc.php");
        $feeHeadManager =  FeeHeadManager::getInstance();
        
        // Checks dependency constraint
        $feeHeadId = trim($REQUEST_DATA['feeHeadId']); 

	
	    /*
	    $childCountArray = $feeHeadManager->checkChildCount($feeHeadId);
	    $cnt = $childCountArray[0]['cnt'];
	    if($cnt > 0) {
		    echo FEEHEAD_PARENT_RELATION;
		    die;
	    }
        */
        
	$recordArray = $feeHeadManager->getFeeCycleFinesCheck($REQUEST_DATA['feeHeadId']);
        if($recordArray[0]['totalRecords']==0) {
            $recordArray = $feeHeadManager->getFeeHeadValueCheck($REQUEST_DATA['feeHeadId']);
            if($recordArray[0]['totalRecords']==0) {
		$condition = "AND feeHeadId =".$REQUEST_DATA['feeHeadId'];
		$headNameArray = $feeHeadManager->getFeeHead($condition);
		$headName = $headNameArray[0]['headName'];
                    if($feeHeadManager->deleteFeeHead($REQUEST_DATA['feeHeadId']) ) {
			########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
			$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
			$auditTrialDescription = "Following fee head has been deleted: ";
			$auditTrialDescription .= $headName ;
			$type =FEE_HEAD_DELETED; //Fee Head is created
			$returnStatus = $commonQueryManager->addAuditTrialRecord($type,$auditTrialDescription,$queryDescription);
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
          echo DEPENDENCY_CONSTRAINT;  
        }
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/21/09    Time: 12:28p
//Updated in $/LeapCC/Library/FeeHead
//sorting order check updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/20/09    Time: 7:17p
//Updated in $/LeapCC/Library/FeeHead
//issue fix 13, 15, 10, 4 1129, 1118, 1134, 555, 224, 1177, 1176, 1175
//formating conditions updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/30/09    Time: 10:23a
//Updated in $/LeapCC/Library/FeeHead
//validation updated (edit/delete relation checks updated)
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeHead
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeHead
//Define Module, Access  Added
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/27/08    Time: 1:47p
//Updated in $/Leap/Source/Library/FeeHead
//added dependency constarint
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/11/08    Time: 4:11p
//Updated in $/Leap/Source/Library/FeeHead
//added a dependency check for HEadId and ParentHEadId
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:19a
//Created in $/Leap/Source/Library/FeeHead
//Added new library files for "FeeHead" Module

?>

