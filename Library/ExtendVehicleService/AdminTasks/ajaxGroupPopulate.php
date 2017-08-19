<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE class drop down[subject centric]
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (10.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['subjectId'])!= '' and trim($REQUEST_DATA['classId'])!= '') {
	
	require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
	$teacherManager = AdminTasksManager::getInstance();
	
	$foundArray = $teacherManager->getSubjectGroup($REQUEST_DATA['subjectId'],$REQUEST_DATA['classId']);
	
	if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGroupPopulate.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/AdminTasks
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Administrator Date: 20/05/09   Time: 11:54
//Created in $/LeapCC/Library/AdminTasks
//Created "Duty Leave" Module
?>