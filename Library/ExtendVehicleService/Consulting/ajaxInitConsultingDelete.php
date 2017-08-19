<?php
//-------------------------------------------------------
// Purpose: To delete document detail
//
// Author : Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeInfo');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
    if (!isset($REQUEST_DATA['consultId']) || trim($REQUEST_DATA['consultId']) == '') {
        $errorMessage = 'Invalid Consult';
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ConsultingManager.inc.php");
        $consultManager = ConsultingManager::getInstance();
        
		if($consultManager->deleteConsulting($REQUEST_DATA['consultId'])) {
		   echo DELETE;
		}
		else {
		   echo DEPENDENCY_CONSTRAINT;
	    }
	}
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitConsultingDelete.php $    
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 12:13p
//Created in $/LeapCC/Library/Consulting
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:15p
//Created in $/Leap/Source/Library/Consulting
//initial checkin 
//
//
?>

