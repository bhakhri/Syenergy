<?php

//-------------------------------------------------------
// THIS FILE IS USED TO SEARCH THE EMPLOYEE AND GIVE THE LIST
//
//
// Author : Jaineesh
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $employeeManager = EmployeeManager::getInstance();
    
    //Delete code goes here
    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (emp.employeeName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR emp.employeeCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")'; }
    $totalArray = $employeeManager->getTotalEmployee($filter);
   // print_r($totalArray);
    $employeeRecordArray = $employeeManager->getEmployeeList($filter,$limit);
   // print_r($employeeRecordArray);
    
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Employee
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:00p
//Updated in $/Leap/Source/Library/Employee
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/22/08    Time: 11:48a
//Updated in $/Leap/Source/Library/Employee
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:55p
//Created in $/Leap/Source/Library/Employee
//checkin
?>