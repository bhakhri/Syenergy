<?php
//-------------------------------------------------------
// Purpose: To delete UNIBERSITY detail
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PlacementComapanyMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


    if (!isset($REQUEST_DATA['companyId']) || trim($REQUEST_DATA['companyId']) == '') {
        $errorMessage = PLACEMENT_COMPANY_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Placement/CompanyManager.inc.php");
        $companyManager =  CompanyManager::getInstance();
        
        $recordArray = $companyManager->checkInPlacementDrive(trim($REQUEST_DATA['companyId']));
        if($recordArray[0]['found']!=0){
            die(DEPENDENCY_CONSTRAINT);
        }
        // delete record
        if($companyManager->deleteCompany(trim($REQUEST_DATA['companyId']))) {
            echo DELETE;
        }
        else {
            echo DEPENDENCY_CONSTRAINT;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitDelete.php $    
?>