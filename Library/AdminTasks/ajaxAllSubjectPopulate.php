<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE subject drop down[class centric]
// Author : Dipanjan Bhattacharjee
// Created on : (10.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['classId'])!= '') {
	
	require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
	$teacherManager = AdminTasksManager::getInstance();
	
	$foundArray = $teacherManager->getAllTeacherSubject(' AND c.classId='.$REQUEST_DATA['classId']);

	if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxAllSubjectPopulate.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/AdminTasks
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Administrator Date: 4/06/09    Time: 10:47
//Created in $/LeapCC/Library/AdminTasks
//Created grace marks module in admin end
?>