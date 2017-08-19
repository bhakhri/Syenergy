<?php
//-------------------------------------------------------
// Purpose: To delete temporary employee detail
//
// Author : Gurkeerat Sidhu
// Created on : (29.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TemporaryEmployee');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['tempEmployeeId']) || trim($REQUEST_DATA['tempEmployeeId']) == '') {
        $errorMessage = 'Invalid Employee';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/EmployeeTempManager.inc.php");
        $tempEmployeeManager =  TempEmployeeManager::getInstance();
        
        if($recordArray[0]['found']==0) {
            if($tempEmployeeManager->deleteTempEmployee($REQUEST_DATA['tempEmployeeId']) ) {
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
        echo $errorMessage;
    }

?>

