<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeInformation');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';

    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpName']) || trim($REQUEST_DATA['mdpName']) == '')) {
        $errorMessage .= ENTER_MDP_NAME."\n";    
    }

	if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpstartDate']) || trim($REQUEST_DATA['mdpstartDate']) == '')) {
        $errorMessage .= ENTER_MDP_START_DATE."\n";    
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpendDate']) || trim($REQUEST_DATA['mdpendDate']) == '')) {
        $errorMessage .= ENTER_MDP_END_DATE ."\n";    
    }

	if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpSelectId']) || trim($REQUEST_DATA['mdpSelectId']) == '')) {
        $errorMessage .= SELECT_MDP."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpSessionAttended']) || trim($REQUEST_DATA['mdpSessionAttended']) == '')) {
        $errorMessage .= ENTER_MDP_SESSION_ATTENDED."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpHours']) || trim($REQUEST_DATA['mdpHours']) == '')) {
        $errorMessage .= ENTER_MDP_HOURS."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpVenue']) || trim($REQUEST_DATA['mdpVenue']) == '')) {
        $errorMessage .= ENTER_MDP_VENUE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpType']) || trim($REQUEST_DATA['mdpType']) == '')) {
        $errorMessage .= SELECT_MDP_TYPE_ID."\n";    
    }
     if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpDescription']) || trim($REQUEST_DATA['mdpDescription']) == '')) {
        $errorMessage .= ENTER_DESCRIPTION ."\n";    
    }
    
    if (trim($REQUEST_DATA['mdpstartDate']) == '0000-00-00') {
        $errorMessage .= ENTER_MDP_START_DATE."\n";    
    }
    
    if (trim($REQUEST_DATA['mdpendDate']) == '0000-00-00') {
        $errorMessage .= ENTER_MDP_END_DATE."\n";    
    }
   
	 
$employeeId =  $REQUEST_DATA['employeeId'];
if (trim($errorMessage) == '') {
	require_once(MODEL_PATH . "/MdpManager.inc.php");
	$mdpManager = MdpManager::getInstance();
	$returnStatus = $mdpManager->addMdp($employeeId);
	if($returnStatus == false) {
		echo FAILURE;
	}
	else {
		echo SUCCESS;
	}
}
else {
	echo $errorMessage;
}
?>