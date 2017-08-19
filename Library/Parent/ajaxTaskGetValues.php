<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DOCUMENT LIST
//
// Author : Jaineesh
// Created on : (28.02.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TaskMaster');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['taskId'] ) != '') {
    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
	$taskManager = ParentManager::getInstance();
    $taskArray = $taskManager->getTask('AND taskId="'.$REQUEST_DATA['taskId'].'"');
    if(is_array($taskArray) && count($taskArray)>0 ) {  
        echo json_encode($taskArray[0]);
		//die();
    }
    else {
        echo 0;
    }
}
// $History: ajaxTaskGetValues.php $
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
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/19/09    Time: 4:41p
//Updated in $/SnS/Library/Task
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