<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A PUBLISHING
//
//
// Author : Jaineesh
// Created on : (05.03.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeInfo');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';

    if ($errorMessage == '' && (!isset($REQUEST_DATA['type']) || trim($REQUEST_DATA['type']) == '')) {
        $errorMessage .= ENTER_TYPE."\n";    
    }

	if ($errorMessage == '' && (!isset($REQUEST_DATA['publishedBy']) || trim($REQUEST_DATA['publishedBy']) == '')) {
        $errorMessage .= ENTER_PUBLISHER."\n";    
    }

	if ($errorMessage == '' && (!isset($REQUEST_DATA['description']) || trim($REQUEST_DATA['description']) == '')) {
        $errorMessage .= ENTER_DESCRIPTION."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['publishOn']) || trim($REQUEST_DATA['publishOn']) == '')) {
        $errorMessage .= ENTER_PUBLISHER_DATE."\n";    
    }
    
    if (trim($REQUEST_DATA['publishOn']) == '0000-00-00') {
        $errorMessage .= ENTER_PUBLISHER_DATE."\n";    
    }
	 
    $employeeId =  $REQUEST_DATA['employeeId'];
     
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/PublishingManager.inc.php");
		$publishingManager = PublishingManager::getInstance();
		
             $returnStatus = $publishingManager->addPublishing($employeeId);
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
// $History: ajaxInitPublishingAdd.php $ajaxInitPublishingAdd.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 12:14p
//Created in $/LeapCC/Library/Publishing
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:14p
//Created in $/Leap/Source/Library/Publishing
//initial checkin 
//
//
 
?>