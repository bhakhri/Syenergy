<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A TEMPORARY EMPLOYEE 
//
//
// Author : Gurkeerat Sidnhu
// Created on : (29.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TemporaryEmployee');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['tempEmployeeName']) || trim($REQUEST_DATA['tempEmployeeName']) == '') {
        $errorMessage .= ENTER_EMPLOYEE_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['address']) || trim($REQUEST_DATA['address']) == '')) {
        $errorMessage .= ENTER_EMPLOYEE_ADDRESS."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['contactNo']) || trim($REQUEST_DATA['contactNo']) == '')) {
        $errorMessage .= ENTER_CONTACT_NUMBER ."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['status']) || trim($REQUEST_DATA['status']) == '')) {
        $errorMessage .= SELECT_STATUS ."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['designationName']) || trim($REQUEST_DATA['designationName']) == '')) {
        $errorMessage .= SELECT_DESIGNATION ."\n";    
    }
    
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/EmployeeTempManager.inc.php");
        $d = $REQUEST_DATA['dateOfJoining'];
        $cdate = date('Y-m-d');
        $dArray = explode('-',$d);
        $cdateArray = explode('-',$cdate);
        $dVal = $dArray[0].$dArray[1].$dArray[2];
        $cdateVal = $cdateArray[0].$cdateArray[1].$cdateArray[2];
            if($cdateVal<$dVal){
                  echo FUTURE_DATE_VALIDATION;
                  exit();
                }
            
        $returnStatus = TempEmployeeManager::getInstance()->addTempEmployee();
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