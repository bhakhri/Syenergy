<?php
//-------------------------------------------------------
// Purpose: To delete document detail
//
// Author : Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TaskMaster');
define('ACCESS','delete');
UtilityManager::ifParentNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['taskId']) || trim($REQUEST_DATA['taskId']) == '') {
        $errorMessage = 'Invalid Task';
    }
	
	if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
		$taskManager = ParentManager::getInstance();
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
//User: Jaineesh     Date: 4/21/09    Time: 1:32p
//Created in $/LeapCC/Library/Parent
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/20/09    Time: 6:10p
//Updated in $/SnS/Library/Parent
//modified for task
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/19/09    Time: 4:50p
//Created in $/SnS/Library/Parent
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/18/09    Time: 6:23p
//Created in $/SnS/Library/Task
//new ajax files for add,edit, delete
//
//
?>

