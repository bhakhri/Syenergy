<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A PUBLISHING
//
//
// Author : Jaineesh
// Created on : (05.03.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TaskMaster');
define('ACCESS','add');
UtilityManager::ifStudentNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';

    if ($errorMessage == '' && (!isset($REQUEST_DATA['title']) || trim($REQUEST_DATA['title']) == '')) {
        $errorMessage .= ENTER_TITLE."\n";    
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['shortDesc']) || trim($REQUEST_DATA['shortDesc']) == '')) {
        $errorMessage .= ENTER_SHORT_DESC."\n";    
    }
		
	if($sessionHandler->getSessionVariable('SuperUserId')!=''){
		echo ACCESS_DENIED;
		die;
	}

	$reminderOptions = $REQUEST_DATA['dashboard'].','.$REQUEST_DATA['sms'];
		
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
		$taskManager = StudentInformationManager::getInstance();
		$taskArray = $taskManager -> getTask('AND UCASE(title)="'.add_slashes(strtoupper($REQUEST_DATA['title'])).'"');
		
        if(trim($taskArray[0]['title'])=='') {  //DUPLICATE CHECK
            $returnStatus = $taskManager->addTask($reminderOptions);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo TITLE_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitTaskAdd.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/20/09    Time: 6:42p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/19/09    Time: 4:41p
//Updated in $/SnS/Library/Student
//add new room if hostel room is different
//new task module in student
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/18/09    Time: 6:23p
//Created in $/SnS/Library/Task
//new ajax files for add,edit, delete
//
//

?>