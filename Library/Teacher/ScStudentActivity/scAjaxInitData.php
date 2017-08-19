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

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $teacherManager = ScTeacherManager::getInstance();

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
// $History: scAjaxInitData.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScStudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
?>
