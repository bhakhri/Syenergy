<?php
//-------------------------------------------------------
// Purpose: To add state detail
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentProgramFee');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['programFeeName']) || trim($REQUEST_DATA['programFeeName']) == '') {
        $errorMessage .= ENTER_PROGRAM_FEE_NAME."\n";
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/StudentProgramFeeManager.inc.php");
        $foundArray = StudentProgramFeeManager::getInstance()->getProgramFee(' WHERE ( UCASE(programFeeName)="'.add_slashes(strtoupper($REQUEST_DATA['programFeeName'])).'" ) ');

        if(trim($foundArray[0]['programFeeName'])=='') {  //DUPLICATE CHECK
            $returnStatus = StudentProgramFeeManager::getInstance()->addProgramFee();
            if($returnStatus === false) {
                echo FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else{
            echo PROGRAM_FEE_NAME_EXISTS;
		}
    }
    else {
        echo $errorMessage;
    }
?>