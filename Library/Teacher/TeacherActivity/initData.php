<?php
//-------------------------------------------------------
// Purpose: To show employee data 
//
// Author : Parveen Sharma
// Created on : 24.06.2009
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	define('MODULE','EmployeeInformation');
	define('ACCESS','view');

    UtilityManager::ifTeacherNotLoggedIn(true);    

    require_once(MODEL_PATH . "/EmployeeManager.inc.php");  
    $employeeManager = EmployeeManager::getInstance();

	require_once(MODEL_PATH."/CommonQueryManager.inc.php");
	$commonAttendanceArr = CommonQueryManager::getInstance();

    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');  
    $condition = " AND emp.employeeId = '".$employeeId."'";
    $employeeArr = $employeeManager->getEmployeeList($condition);
     
// $History: initData.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//formatting, conditions, validations updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/24/09    Time: 11:50a
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin
//

?>