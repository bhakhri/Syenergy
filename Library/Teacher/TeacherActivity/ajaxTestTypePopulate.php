<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE test type [subject centric]
//
//
// Author : Jaineesh 
// Created on : (04.04.09)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['subjectId'] ) != '') {
	
	require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
	$teacherManager = TeacherManager::getInstance();
	$foundArray = $teacherManager->getTestTypeCategory($REQUEST_DATA['subjectId'],"AND examType='PC' AND showCategory=1");

	if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxTestTypePopulate.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/04/09    Time: 3:26p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//new ajax file to get the test type of particular theory or practical
//subject
//
?>