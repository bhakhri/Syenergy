<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A TESTTYPECATEGORY
//
//
// Author : Gurkeerat Sidhu
// Created on : (23.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ComplaintCategory');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['categoryName']) || trim($REQUEST_DATA['categoryName']) == '')) {
        $errorMessage .= ENTER_COMPLAINTCATEGORY_NAME."\n";    
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HostelComplaintCatManager.inc.php");
        $foundArray = ComplaintManager::getInstance()->getComplaintCategory(' WHERE UCASE(categoryName)="'.addslashes(strtoupper($REQUEST_DATA['categoryName'])).'"');
        if(trim($foundArray[0]['categoryName'])=='') {  //DUPLICATE CHECK
            $returnStatus = ComplaintManager::getInstance()->addComplaintCategory();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo NAME_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }

 
?>