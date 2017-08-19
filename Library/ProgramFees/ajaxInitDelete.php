<?php
//-------------------------------------------------------
// Purpose: To delete state detail
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentProgramFee');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['programFeeId']) || trim($REQUEST_DATA['programFeeId']) == '') {
        $errorMessage = PROGRAM_FEE_NOT_EXIST;
    }
if (trim($errorMessage) == ''){
    require_once(MODEL_PATH . "/StudentProgramFeeManager.inc.php");
    $programFeeManager =  StudentProgramFeeManager::getInstance();
    
    $recordArray = $programFeeManager->checkInStudent($REQUEST_DATA['programFeeId']);
    if($recordArray[0]['found']==0) {
        if($programFeeManager->deleteProgramFee($REQUEST_DATA['programFeeId']) ) {
            echo DELETE;
        }
        else {
            echo DEPENDENCY_CONSTRAINT;
        }
    }
    else {
        echo "This program fee could not be deleted as as some students opt for this program fee already";
    }
}
else {
    echo $errorMessage;
}
?>