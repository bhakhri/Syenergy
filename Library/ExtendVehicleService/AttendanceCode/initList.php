<?php  
  //This file calls Delete Function and Listing Function and creates Global Array in AttendanceCode Module 
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
     
    //Paging code goes here
    require_once(MODEL_PATH . "/AttendanceCodeManager.inc.php");
    $attendanceCodeManager = AttendanceCodeManager::getInstance();
    define('MODULE','AttendanceCodesMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
  
        
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' WHERE (attendanceCodeName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR attendanceCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR attendanceCodePercentage LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';        
              
    }
   
    $totalArray = $attendanceCodeManager->getTotalAttendanceCode($filter);
    //echo $totalArray;
    $attendanceCodeRecordArray = $attendanceCodeManager->getAttendanceCodeList($filter,$limit);   
   
//$History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/AttendanceCode
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/21/08    Time: 4:04p
//Updated in $/Leap/Source/Library/AttendanceCode
//Added a attendanceCodePercentage in attendance table
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/26/08    Time: 4:51p
//Updated in $/Leap/Source/Library/AttendanceCode
//deletes the code for delete function
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/17/08    Time: 4:14p
//Created in $/Leap/Source/Library/AttendanceCode
//added new files


?>
