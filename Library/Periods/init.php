<?php
//duplicate check has to be coded

$errorMessage = '';
if($REQUEST_DATA['stateSubmit'] == 'Add' ) {

	if (!isset($REQUEST_DATA['periodNumber']) || trim($REQUEST_DATA['periodNumber']) == '') {
		$errorMessage = 'Enter period number';
	}
    if (!isset($REQUEST_DATA['startTime']) || trim($REQUEST_DATA['startTime']) == '') {
        $errorMessage = 'Enter starting time';
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['endTime']) || trim($REQUEST_DATA['endTime']) == '')) {
		$errorMessage = 'Enter end time';
	}
	if ($errorMessage == '' && (!isset($REQUEST_DATA['institutes']) || trim($REQUEST_DATA['institutes']) == '')) {
		$errorMessage = 'Enter institute name';
	}

	if (trim($errorMessage) == '') {
		require_once(MODEL_PATH . "/PeriodsManager.inc.php");
		$returnStatus = PeriodsManager::getInstance()->addPeriods();
        
        if($returnStatus === false) {
            $errorMessage = 'Data couldn\'t be saved. Please try again!';
        }
        else {
            redirectBrowser(UtilityManager::buildTargetURL("addPeriod.php", array('status' => 1 ) ));            
        }
	}
}
if( $REQUEST_DATA['stateSubmit'] == 'Edit' ) {

    if (!isset($REQUEST_DATA['periodNumber']) || trim($REQUEST_DATA['periodNumber']) == '') {
        $errorMessage = 'Enter period number';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['startTime']) || trim($REQUEST_DATA['startTime']) == '')) {
        $errorMessage = 'Enter starting time';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['endTime']) || trim($REQUEST_DATA['endTime']) == '')) {
        $errorMessage = 'Enter end time';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['institutes']) || trim($REQUEST_DATA['institutes']) == '')) {
        $errorMessage = 'Enter institute name';
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/PeriodsManager.inc.php");
        $returnStatus = PeriodsManager::getInstance()->editPeriods($REQUEST_DATA['periodId']);
        
        if($returnStatus === false) {
            $errorMessage = 'Data couldn\'t be saved. Please try again!';
        }
        else {
            redirectBrowser(UtilityManager::buildTargetURL("listPeriods.php", array('status' => 1 )));            
        }
    }
}
echo $REQUEST_DATA['periodId'];
if(UtilityManager::notEmpty($REQUEST_DATA['periodId']) ) {
        require_once(MODEL_PATH . "/PeriodsManager.inc.php");
        $periodsRecordArray   = PeriodsManager::getInstance()->getPeriods(' WHERE periodId='.$REQUEST_DATA['periodId']);   
} 
?>