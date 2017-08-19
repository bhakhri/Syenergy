<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE group drop down based upon selection of class
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (10.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['classId'])!= '') {
	
	require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
	$teacherManager = TeacherManager::getInstance();
	
	$foundArray = $teacherManager->getTeacherGroup(' AND c.classId='.$REQUEST_DATA['classId']);
	if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxClassGroupPopulate.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/04/09   Time: 16:02
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Created "Grace Marks Master"
?>