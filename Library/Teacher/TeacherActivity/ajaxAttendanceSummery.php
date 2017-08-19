<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
    define('MODULE','AttendanceSummary');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    
    $teacherManager      = TeacherManager::getInstance();
    $commonAttendanceArr = CommonQueryManager::getInstance();     

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    /////////////////////////////////
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    
     $orderBy = "  ORDER BY $sortField $sortOrderBy";         

    ////////////
    
    $totalArray         = $teacherManager->getTotalTeacherAttendanceSummery($REQUEST_DATA['classId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['timeTableLabelId']);
    $studentRecordArray = $teacherManager->getTeacherAttendanceSummeryList($REQUEST_DATA['classId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['timeTableLabelId'],$orderBy,$limit);
    
    
    
    
    $cnt = count($studentRecordArray);
    $TD=0;
    
    for($i=0;$i<$cnt;$i++) {
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentRecordArray[$i]);
        $TD += $studentRecordArray[$i]['totalDelivered'];
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","TD":"'.$TD.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxAttendanceSummery.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 19:24
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Created "Attendance Summary" module in teacher login
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 18/04/09   Time: 18:46
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Completed Attendance Summery Report
?>
