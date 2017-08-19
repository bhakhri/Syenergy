<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT DESIGNATION 
//
//
// Author : Gurkeerat Sidhu
// Created on : (29.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TemporaryDesignationMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['designationName']) || trim($REQUEST_DATA['designationName']) == '')) {
        $errorMessage .= ENTER_DESIGNATION_NAME. '<br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['designationCode']) || trim($REQUEST_DATA['designationCode']) == '')) {
        $errorMessage .= ENTER_DESIGNATION_CODE. '<br/>';
    }

	if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/DesignationTempManager.inc.php");
			$foundArray = DesignationTempManager::getInstance()->getDesignation(' WHERE LCASE(designationName)=  "'.add_slashes(trim(strtolower($REQUEST_DATA['designationName']))).'" AND tempDesignationId!='.$REQUEST_DATA['tempDesignationId']);
				if(trim($foundArray[0]['designationName'])=='') {  //DUPLICATE CHECK
					$foundArray2 = DesignationTempManager::getInstance()->getDesignation(' WHERE UCASE(designationCode)="'.add_slashes(strtoupper($REQUEST_DATA['designationCode'])).'" AND tempDesignationId!='.$REQUEST_DATA['tempDesignationId']);
						if(trim($foundArray2[0]['designationCode'])=='') {  //DUPLICATE CHECK
							$returnStatus = DesignationTempManager::getInstance()->editDesignation($REQUEST_DATA['tempDesignationId']);
								if($returnStatus === false) {
									echo FAILURE;
								}
								else {
									echo SUCCESS;           
								}
						}
						else {
							echo DESIGNATION_ALREADY_EXIST;
						}
				}
				else {
					echo DESIGNATION_NAME_EXIST;
				}
	}
    else {
        echo $errorMessage;
    }


?>