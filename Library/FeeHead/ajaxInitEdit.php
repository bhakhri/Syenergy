<?php 

//  This File calls Edit Function used in adding "FeeHead" Records
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;

require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','FeeHeads');     
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache(); 

    require_once(MODEL_PATH . "/FeeHeadManager.inc.php");  

    global $sessionHandler;
    $queryDescription =''; 
    $errorMessage ='';
     if (!isset($REQUEST_DATA['headName']) || trim($REQUEST_DATA['headName']) == '') {
        $errorMessage .= ENTER_FEEHEAD_NAME."\n";
    }
    
	
    $feeHeadId   = trim($REQUEST_DATA['feeHeadId']); 
    $headName    = trim($REQUEST_DATA['headName']); 
    $headAbbr    = trim($REQUEST_DATA['headAbbr']); 
    $sortOrder   = trim($REQUEST_DATA['sortOrder']); 
    
    $id = $REQUEST_DATA['feeHeadId'];
    if($id=='') {
      $id=0;  
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['headAbbr']) || trim($REQUEST_DATA['headAbbr']) == '')) {
      $errorMessage .= ENTER_FEEHEAD_ABBR."\n";
    }
    $foundArray = FeeHeadManager::getInstance()->getFeeHead(' AND UCASE(sortingOrder)="'.$sortOrder.'" AND feeHeadId!='.$REQUEST_DATA['feeHeadId']);
    if(trim($foundArray[0]['headAbbr'])=='') {  //DUPLICATE CHECK 
        $foundArray = FeeHeadManager::getInstance()->getFeeHead(' AND UCASE(headName)="'.add_slashes(strtoupper($REQUEST_DATA['headName'])).'" AND feeHeadId!='.$REQUEST_DATA['feeHeadId']);
        if(trim($foundArray[0]['headAbbr'])=='') {  //DUPLICATE CHECK
            $foundArray = FeeHeadManager::getInstance()->getFeeHead(' AND UCASE(headAbbr)="'.add_slashes(strtoupper($REQUEST_DATA['headAbbr'])).'" AND feeHeadId!='.$REQUEST_DATA['feeHeadId']);
            if(trim($foundArray[0]['headAbbr'])=='') {  //DUPLICATE CHECK 
                $returnStatus = FeeHeadManager::getInstance()->editFeeHead($REQUEST_DATA['feeHeadId']);            
                if($returnStatus === false) {
                    echo FAILURE;
                }
                else {
		    	########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
			$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
			$auditTrialDescription = "Following fee head has been edited: ";
			$auditTrialDescription .=  $headName ;
			$type = FEE_HEAD_EDITED; //Fee Head is created
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

?>
