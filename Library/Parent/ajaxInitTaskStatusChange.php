<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A TESTTYPE
//
//
// Author : Jaineesh
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TaskMaster');
define('ACCESS','edit');
UtilityManager::ifParentNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
        require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
		$taskManager = ParentManager::getInstance();
        $returnStatus = $taskManager->editTaskStatus($REQUEST_DATA['taskId'],$REQUEST_DATA['status']);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
				echo SUCCESS;           
            }
        
        
// $History: ajaxInitTaskStatusChange.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/21/09    Time: 1:32p
//Created in $/LeapCC/Library/Parent
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/01/09    Time: 3:45p
//Updated in $/SnS/Library/Parent
//modified for change status click on dashboard in teacher & parent
//
//
?>
