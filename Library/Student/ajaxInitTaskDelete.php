<?php
//-------------------------------------------------------
// Purpose: To delete document detail
//
// Author : Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TaskMaster');
define('ACCESS','delete');
UtilityManager::ifStudentNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['taskId']) || trim($REQUEST_DATA['taskId']) == '') {
        $errorMessage = 'Invalid Task';
    }
	
	if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
		$taskManager = StudentInformationManager::getInstance();
			if($taskManager->deleteTask($REQUEST_DATA['taskId'])) {
				echo DELETE;
			}
		  else {
			  echo DEPENDENCY_CONSTRAINT;
	}
	}
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitTaskDelete.php $    
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
//User: Jaineesh     Date: 3/19/09    Time: 2:56p
//Created in $/SnS/Library/Student
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/18/09    Time: 6:23p
//Created in $/SnS/Library/Task
//new ajax files for add,edit, delete
//
//
?>

