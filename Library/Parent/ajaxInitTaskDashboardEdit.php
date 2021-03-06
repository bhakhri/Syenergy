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
    if ($errorMessage == '' && (!isset($REQUEST_DATA['title']) || trim($REQUEST_DATA['title']) == '')) {
        $errorMessage .= ENTER_TITLE."\n";    
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['shortDesc']) || trim($REQUEST_DATA['shortDesc']) == '')) {
        $errorMessage .= ENTER_SHORT_DESC."\n";    
    }
  
	$reminderOptions = $REQUEST_DATA['dashboard'].','.$REQUEST_DATA['sms'];

	  if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
		$taskManager = ParentManager::getInstance();
		
        $foundArray = $taskManager->getTask(' AND UCASE(title)="'.add_slashes(strtoupper($REQUEST_DATA['title'])).'" AND taskId!='.$REQUEST_DATA['taskId']);
        if(trim($foundArray[0]['title'])=='') {  //DUPLICATE CHECK
            $returnStatus = $taskManager->editTask($REQUEST_DATA['taskId'],$reminderOptions);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
				$m = explode('-',$REQUEST_DATA['dueDate']);
				
				$dateChange = date("Y-m-d", mktime(0, 0, 0, $m[1], $m[2]-$REQUEST_DATA['daysPrior'], $m[0]));

                echo SUCCESS.'~'.UtilityManager::formatDate($dateChange);           
            }
        }
        else {
            echo TITLE_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitTaskDashboardEdit.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/21/09    Time: 1:32p
//Created in $/LeapCC/Library/Parent
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/27/09    Time: 6:41p
//Created in $/SnS/Library/Parent
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/24/09    Time: 4:33p
//Updated in $/SnS/Library/Parent
//modified in task for parent & student
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
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/02/09    Time: 4:27p
//Created in $/SnS/Library/Document
//
//
 
?>
