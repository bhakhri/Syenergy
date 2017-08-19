<?php
//-------------------------------------------------------
// Purpose: To store the records of student in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (15.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       //$filter = ' AND (ct.cityName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ct.cityCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
       $filter ='';
    }
    
    
    $totalArray = $teacherManager->getTotalStudent($filter);
    $studentRecordArray = $teacherManager->getStudentList($filter,$limit);
    $cnt = count($studentRecordArray);
    
// for VSS
// $History: ajaxInitData.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/StudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/17/08    Time: 5:17p
//Updated in $/Leap/Source/Library/Teacher/StudentActivity
//ifTeacherNotLoggedIn
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/15/08    Time: 7:21p
//Created in $/Leap/Source/Library/Teacher/StudentActivity
//Initial Checkin
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:19p
//Created in $/Leap/Source/Library/Teacher/StudentActivity
//Initial Checkin
?>
