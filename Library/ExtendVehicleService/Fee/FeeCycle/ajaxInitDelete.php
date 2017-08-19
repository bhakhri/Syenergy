<?php
//-------------------------------------------------------
// Purpose: To delete fee cycle detail
// Author : Nishu Bindal
// Created on : (14.03.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','FeeCycleMasterNew'); 
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

global $sessionHandler;
$queryDescription =''; 
 require_once(MODEL_PATH . "/Fee/FeeCycleManager.inc.php");
 $feeCycleManager = FeeCycleManager::getInstance();
        
	if (!isset($REQUEST_DATA['feeCycleId']) || trim($REQUEST_DATA['feeCycleId']) == '') {
		$errorMessage = 'Invalid Fee Cycle';
	}
	else{
		$feeReceiptValuesArray = $feeCycleManager->checkFeeReceiptValues($REQUEST_DATA['feeCycleId']);
		if($feeReceiptValuesArray[0]['cnt'] > 0 ) {
			$errorMessage = DEPENDENCY_CONSTRAINT;
		}
	}

	if(trim($errorMessage) == ''){
		if ($feeCycleManager->deleteFeeCycle($REQUEST_DATA['feeCycleId'])) {
			echo DELETE;
		}
	}
	else {
		echo $errorMessage;
	}
   
    

?>

