<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A TESTTYPE
//
//
// Author : Jaineesh
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TaskMaster');
define('ACCESS','edit');
UtilityManager::ifStudentNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
        require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
		$taskManager = StudentInformationManager::getInstance();
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
//User: Jaineesh     Date: 4/20/09    Time: 6:42p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/01/09    Time: 12:04p
//Created in $/SnS/Library/Student
//
//
 
?>