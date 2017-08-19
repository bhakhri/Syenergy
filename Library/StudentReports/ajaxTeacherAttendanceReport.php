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
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TeacherAttendanceReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $labelId=trim($REQUEST_DATA['labelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $employeeId=trim($REQUEST_DATA['employeeId']);
    $fromDate=trim($REQUEST_DATA['fromDate']);
    $toDate=trim($REQUEST_DATA['toDate']);
    $groupId=trim($REQUEST_DATA['groupId']);
    $chkHierarchy=trim($REQUEST_DATA['chkHierarchy']);  
    
    if($chkHierarchy=='') {
      $chkHierarchy=0;  
    }
    
    if($labelId=='' or $classId=='' or $employeeId=='' or $fromDate=='' or $toDate=='' or $groupId==''){
        echo 'Required Parametes Missing';
        die;
    }
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    
    $orderBy = " ORDER BY $sortField $sortOrderBy";         

    ////////////
    
    $filter =' AND ttc.timeTableLabelId='.$labelId.' AND c.classId='.$classId.' AND ( att.fromDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND att.toDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" )';
    
    if($employeeId!=-1 and $employeeId!=''){
        $filter .=' AND e.employeeId='.$employeeId;
    }
    
    $groupConditions='';
    if($groupId!=-1){
       //find group hierarchy
       if($chkHierarchy==1) {
         $groupHierarchyString=$studentReportsManager->getGroupHierarchy($classId,$groupId);
         $groupConditions=' AND att.groupId IN ('.$groupHierarchyString.')';
         $filter .=$groupConditions;
       }
       else {
         $filter .=' AND att.groupId IN ('.$groupId.')';   
       }
    }
    
    
    $totalArray = $studentReportsManager->getTeacherAttendanceSummeryList($filter);
    $teacherAttendanceRecordArray = $studentReportsManager->getTeacherAttendanceSummeryList($filter,$limit,$orderBy);
    $cnt = count($teacherAttendanceRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$teacherAttendanceRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxTeacherAttendanceReport.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 16/04/10   Time: 10:22
//Created in $/LeapCC/Library/StudentReports
//Created "Teacher Attendance Report".This report is used to see total
//lectured delivered by a teacher for a subject within a specified date
//interval.
?>