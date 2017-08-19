<?php
//-------------------------------------------------------
// Purpose: To delete document detail
//
// Author : Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeInfo');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['publishId']) || trim($REQUEST_DATA['publishId']) == '') {
        $errorMessage = 'Invalid Publishing';
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/PublishingManager.inc.php");
		$publishingManager = PublishingManager::getInstance();
			if($publishingManager->deletePublishing($REQUEST_DATA['publishId'])) {
				echo DELETE;
			}
		  else {
			  echo DEPENDENCY_CONSTRAINT;
	}
	}
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitPublishingDelete.php $    
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

