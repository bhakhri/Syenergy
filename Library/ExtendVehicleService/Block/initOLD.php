<?php
//duplicate check has to be coded

$errorMessage = '';
if($REQUEST_DATA['stateSubmit'] == 'Add' ) {

	if (!isset($REQUEST_DATA['stateCode']) || trim($REQUEST_DATA['stateCode']) == '') {
		$errorMessage = 'Enter state code';
	}
	if ($errorMessage == '' && (!isset($REQUEST_DATA['stateName']) || trim($REQUEST_DATA['stateName']) == '')) {
		$errorMessage = 'Enter state name';
	}
	if ($errorMessage == '' && (!isset($REQUEST_DATA['countries']) || trim($REQUEST_DATA['countries']) == '')) {
		$errorMessage = 'Enter country name';
	}

	if (trim($errorMessage) == '') {
		require_once(MODEL_PATH . "/StatesManager.inc.php");
		$returnStatus = StatesManager::getInstance()->addState();
        
        if($returnStatus === false) {
            $errorMessage = 'Data couldn\'t be saved. Please try again!';
        }
        else {
            redirectBrowser(UtilityManager::buildTargetURL("addState.php", array('status' => 1 ) ));            
        }
	}
}
if( $REQUEST_DATA['stateSubmit'] == 'Edit' ) {

    if (!isset($REQUEST_DATA['stateCode']) || trim($REQUEST_DATA['stateCode']) == '') {
        $errorMessage = 'Enter state code';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['stateName']) || trim($REQUEST_DATA['stateName']) == '')) {
        $errorMessage = 'Enter state name';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['countries']) || trim($REQUEST_DATA['countries']) == '')) {
        $errorMessage = 'Enter country name';
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/StatesManager.inc.php");
        $returnStatus = StatesManager::getInstance()->editState($REQUEST_DATA['stateId']);
        
        if($returnStatus === false) {
            $errorMessage = 'Data couldn\'t be saved. Please try again!';
        }
        else {
            redirectBrowser(UtilityManager::buildTargetURL("listState.php", array('status' => 1 )));            
        }
    }
}
if(UtilityManager::notEmpty($REQUEST_DATA['stateId']) ) {
        require_once(MODEL_PATH . "/StatesManager.inc.php");
        $stateRecordArray   = StatesManager::getInstance()->getState(' WHERE stateId='.$REQUEST_DATA['stateId']);   
} 
?>