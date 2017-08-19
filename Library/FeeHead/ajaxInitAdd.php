<?php 
//  This File calls addFunction used in adding FeeHead Records
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
define('MODULE','FeeHeads');     
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);           
UtilityManager::headerNoCache(); 
require_once(MODEL_PATH . "/FeeHeadManager.inc.php");

global $sessionHandler;
$queryDescription =''; 

    $errorMessage ='';

  
    if (!isset($REQUEST_DATA['headName']) || trim($REQUEST_DATA['headName']) == '') {
        $errorMessage .= ENTER_FEEHEAD_NAME."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['headAbbr']) || trim($REQUEST_DATA['headAbbr']) == '')) {
        $errorMessage .= ENTER_FEEHEAD_ABBR."\n";
    }
	
    $headName       = trim($REQUEST_DATA['headName']); 
    $headAbbr       = trim($REQUEST_DATA['headAbbr']); 
    $sortOrder   = trim($REQUEST_DATA['sortOrder']); 
    


	
     if (trim($errorMessage) == '') {
        $foundArray = FeeHeadManager::getInstance()->getFeeHead(' AND UCASE(sortingOrder)="'.$sortOrder.'"');
        if(trim($foundArray[0]['headAbbr'])=='') {  //DUPLICATE CHECK 
            $foundArray = FeeHeadManager::getInstance()->getFeeHead(' AND UCASE(headName)="'.add_slashes(strtoupper($headName)).'"');
            if(trim($foundArray[0]['headAbbr'])=='') {  //DUPLICATE CHECK
                $foundArray = FeeHeadManager::getInstance()->getFeeHead(' AND UCASE(c.headAbbr)="'.add_slashes(strtoupper($headAbbr)).'"');
                if(trim($foundArray[0]['headAbbr'])=='') {  //DUPLICATE CHECK
                    $returnStatus = FeeHeadManager::getInstance()->addFeeHead();
		 if($returnStatus === false) {
                        $errorMessage = FAILURE;
                    }
                    else {
			########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
			$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
			$auditTrialDescription = "Following fee head has been created: ";
			$auditTrialDescription .= $headName;
			$type = FEE_HEAD_CREATED; //Fee Head is created
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
                    echo FEEHEAD_ABBR_EXIST;
                }
            }
            else {
                echo FEEHEAD_NAME_EXIST;
            }
        }
        else {
            echo FEEHEAD_DISPLAY_ORDER_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
 

//$History: ajaxInitAdd.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/20/09    Time: 7:17p
//Updated in $/LeapCC/Library/FeeHead
//issue fix 13, 15, 10, 4 1129, 1118, 1134, 555, 224, 1177, 1176, 1175
//formating conditions updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/30/09    Time: 4:08p
//Updated in $/LeapCC/Library/FeeHead
//parent checks updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/24/09    Time: 1:50p
//Updated in $/LeapCC/Library/FeeHead
//duplicate checks validation added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeHead
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeHead
//Define Module, Access  Added
//
//*****************  Version 5  *****************
//User: Arvind       Date: 9/09/08    Time: 7:15p
//Updated in $/Leap/Source/Library/FeeHead
//added common messages
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/29/08    Time: 4:38p
//Updated in $/Leap/Source/Library/FeeHead
//added duplicate check for feehead
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/15/08    Time: 6:20p
//Updated in $/Leap/Source/Library/FeeHead
//removed validation for parent head
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/11/08    Time: 3:59p
//Updated in $/Leap/Source/Library/FeeHead
//added a condition for parentHeadId
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:19a
//Created in $/Leap/Source/Library/FeeHead
//Added new library files for "FeeHead" Module
?>
