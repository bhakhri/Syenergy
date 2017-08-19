<?php
//-------------------------------------------------------
// Purpose: To show student data for subject centric
//
// Author : Rajeev Aggarwal
// Created on : (06.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	define('MODULE','EmployeeInfo');
	define('ACCESS','edit');
    require_once(MODEL_PATH . "/EmployeeManager.inc.php");  
    $employeeManager = EmployeeManager::getInstance();

	require_once(MODEL_PATH."/CommonQueryManager.inc.php");
	$commonAttendanceArr = CommonQueryManager::getInstance();

    if(UtilityManager::notEmpty(add_slashes($REQUEST_DATA['Id']))) 
	{
        $condition = " AND emp.employeeId = '".add_slashes($REQUEST_DATA['Id'])."'";
        $employeeArr = $employeeManager->getEmployeeList($condition);
        
    }
// $History: initData.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Library/Employee
//role permission,alignment, new enhancements added 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/16/09    Time: 3:23p
//Created in $/LeapCC/Library/Employee
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:58p
//Created in $/Leap/Source/Library/Employee
//initial checkin
//

?>