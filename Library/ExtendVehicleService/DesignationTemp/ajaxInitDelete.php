<?php
//-------------------------------------------------------
// Purpose: To delete designation detail
//
// Author : Gurkeerat Sidhu
// Created on : (29.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TemporaryDesignationMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['tempDesignationId']) || trim($REQUEST_DATA['tempDesignationId']) == '') {
        $errorMessage = INVALID_DESIGNATION;
    }

	if (trim($errorMessage) == '') {
		require_once(MODEL_PATH . "/DesignationTempManager.inc.php");
			$designationTempManager =  DesignationTempManager::getInstance();
				if($designationTempManager->deleteDesignation($REQUEST_DATA['tempDesignationId']) ) {
					echo DELETE;
				}
				else {
					echo DEPENDENCY_CONSTRAINT;
				}
    }
    else {
        echo $errorMessage;
    }
    

?>