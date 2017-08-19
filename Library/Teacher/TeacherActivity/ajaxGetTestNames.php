<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE class drop down[subject centric]
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
global $sessionHandler;
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 2){
	  UtilityManager::ifTeacherNotLoggedIn(true); //for teachers
}
else{
	UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();
if(trim($REQUEST_DATA['conductingAuthorityId'])!= '' and trim($REQUEST_DATA['testTypeCategoryId'])!= '' and trim($REQUEST_DATA['subjectId'])!= '') {
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");

    $teacherManager = TeacherManager::getInstance();
    $employeeId     = $sessionHandler->getSessionVariable('EmployeeId');
	if($roleId == 2){
		$foundArray     = $teacherManager->getCreatedTestNamesNew(' AND tt.conductingAuthority='.trim($REQUEST_DATA['conductingAuthorityId']).' AND t.testTypeCategoryId='.trim($REQUEST_DATA['testTypeCategoryId']).' AND t.employeeId='.$employeeId.' AND t.subjectId='.trim($REQUEST_DATA['subjectId']));
    }
	else{
		$foundArray     = $teacherManager->getCreatedTestNames(' AND tt.conductingAuthority='.trim($REQUEST_DATA['conductingAuthorityId']).' AND t.testTypeCategoryId='.trim($REQUEST_DATA['testTypeCategoryId']).' AND t.subjectId='.trim($REQUEST_DATA['subjectId']));
	}
	if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
else{
    echo 0;
}
// $History: ajaxGetTestNames.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 28/10/09   Time: 10:45
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modifed "access params" to "COMMON" as these files are called from
//multiple modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 27/10/09   Time: 15:26
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Added files for "Test Wise Performance Report" module
?>
