<?php
//-------------------------------------------------------
// Purpose: To delete hostel visitor detail
//
// Author : Gurkeerat Sidhu
// Created on : (20.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelVisitor');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['visitorId']) || trim($REQUEST_DATA['visitorId']) == '') {
        $errorMessage = 'Invalid Visitor';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HostelVisitorManager.inc.php");
        $hostelVisitorManager =  HostelVisitorManager::getInstance();
        
        if($recordArray[0]['found']==0) {
            if($hostelVisitorManager->deleteHostelVisitor($REQUEST_DATA['visitorId']) ) {
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

