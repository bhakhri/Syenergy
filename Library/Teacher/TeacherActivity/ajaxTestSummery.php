<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn(true);
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
   /*
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (ct.cityName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ct.cityCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
   */ 
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////

    $totalArray      = $teacherManager->getTeacherTotalTests($REQUEST_DATA['classId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['timeTableLabelId']);
    $testRecordArray = $teacherManager->getTeacherTestsSummery($REQUEST_DATA['classId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['timeTableLabelId'],$orderBy,$limit);
    
    $cnt = count($testRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $testRecordArray[$i]['testDate']= UtilityManager::formatDate($testRecordArray[$i]['testDate']);
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$testRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxTestSummery.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 16:13
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Created "Test Summary" module in teacher login
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 17/04/09   Time: 15:43
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Created "Display Test Summery"
?>
