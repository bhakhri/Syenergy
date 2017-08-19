<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeMDP');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';

    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpname']) || trim($REQUEST_DATA['mdpname']) == '')) {
        $errorMessage .= ENTER_MDP_NAME."\n";    
    }

	if ($errorMessage == '' && (!isset($REQUEST_DATA['Mdp startDate']) || trim($REQUEST_DATA['Mdp startDate']) == '')) {
        $errorMessage .= ENTER_MDP_START_DATE."\n";    
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['Mdp endDate']) || trim($REQUEST_DATA['Mdp endDate']) == '')) {
        $errorMessage .= ENTER_MDP_END_DATE ."\n";    
    }

	if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpSelectId']) || trim($REQUEST_DATA['mdpSelectId']) == '')) {
        $errorMessage .= SELECT_MDP."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpSessionattended']) || trim($REQUEST_DATA['mdpSessionattended']) == '')) {
        $errorMessage .= ENTER_MDP_SESSION_ATTENDED."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpHours']) || trim($REQUEST_DATA['mdpHours']) == '')) {
        $errorMessage .= ENTER_MDP_HOURS."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpVenue']) || trim($REQUEST_DATA['mdpVenue']) == '')) {
        $errorMessage .= ENTER_MDP_VENUE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpTypeId']) || trim($REQUEST_DATA['mdpTypeId']) == '')) {
        $errorMessage .= SELECT_MDP_TYPE_ID."\n";    
    }
     if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpdescription']) || trim($REQUEST_DATA['mdpdescription']) == '')) {
        $errorMessage .= ENTER_DESCRIPTION ."\n";    
    }
    
    if (trim($REQUEST_DATA['Mdp startDate']) == '0000-00-00') {
        $errorMessage .= ENTER_SEMINAR_START_DATE."\n";    
    }
    
    if (trim($REQUEST_DATA['Mdp endDate']) == '0000-00-00') {
        $errorMessage .= ENTER_SEMINAR_END_DATE."\n";    
    }

    
	 
    $employeeId =  $REQUEST_DATA['employeeId'];
     
    if (trim($errorMessage) == '') {
         require_once(MODEL_PATH . "/mdpmanager.inc.php");
         $mdpManager = mdpmanager::getInstance();
		
           $returnStatus = $mdpManager->addMdp($employeeId);
            if($returnStatus === false) {
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