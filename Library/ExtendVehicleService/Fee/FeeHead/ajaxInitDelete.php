<?php
//-------------------------------------------------------
// Purpose: To delete Fee Head detail
//
// Author : Arvind Singh Rawat
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;

require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','FeeHeadsNew');     
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


global $sessionHandler;
$queryDescription =''; 


    if (!isset($REQUEST_DATA['feeHeadId']) || trim($REQUEST_DATA['feeHeadId']) == '') {
        $errorMessage = 'Invalid fee Head';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
        $feeHeadManager =  FeeHeadManager::getInstance();
        
	$recordArray1 = $feeHeadManager->checkHeadInFee($REQUEST_DATA['feeHeadId']);
	if($recordArray[0]['totalRecords']==0) {
		$recordArray = $feeHeadManager->getFeeHeadValueCheck($REQUEST_DATA['feeHeadId']);
		if($recordArray[0]['totalRecords']==0) {
			if($feeHeadManager->deleteFeeHead($REQUEST_DATA['feeHeadId']) ) {
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
   
    

?>

