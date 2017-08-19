<?php
//-------------------------------------------------------
// Purpose: To store the records of Batch in array from the database, pagination and search, delete
// functionality
//
// Author : Arvind Singh Rawat
// Created on : (30.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ExtraClassAttendance');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/ExtraClassAttendanceManager.inc.php");
    $extraClassAttendanceManager = ExtraClassAttendanceManager::getInstance();

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
   
    
    $timeTableLabelId = trim($REQUEST_DATA['timeTableLabelId']); 
    $classId = trim($REQUEST_DATA['classId']); 
    $employeeId = trim($REQUEST_DATA['employeeId']); 
    $substituteEmployeeId = trim($REQUEST_DATA['substituteEmployeeId']); 
    $groupId = trim($REQUEST_DATA['groupId']); 
    $subjectId = trim($REQUEST_DATA['subjectId']); 
    $periodId = trim($REQUEST_DATA['periodId']); 
    $searchDateFilter = trim($REQUEST_DATA['searchDateFilter']);  
    $fromDate = trim($REQUEST_DATA['searchFromDate']);
    $toDate = trim($REQUEST_DATA['searchToDate']);
    
    $condition='';
    //if($timeTableLabelId!='') {
    $condition= " AND e.timeTableLabelId = '$timeTableLabelId' "; 
    //}
    if($classId!='') {
      $condition .= " AND e.classId = '$classId' "; 
    }
    if($employeeId!='') {
      $condition .= " AND e.employeeId = '$employeeId' "; 
    }
    if($substituteEmployeeId!='') {
      $condition .= " AND e.substituteEmployeeId = '$substituteEmployeeId' "; 
    }
    if($groupId!='') {
       $condition .= " AND e.groupId = '$groupId' "; 
    }
    if($subjectId!='') {
       $condition .= " AND e.subjectId = '$subjectId' "; 
    }
    if($periodId!='') {
       $condition .= " AND e.periodId = '$periodId' "; 
    }
    
    
    if($searchDateFilter!='') {
       $condition .= " AND (e.fromDate BETWEEN '$fromDate' AND '$toDate') ";   
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fromDate';
    $orderBy = " $sortField $sortOrderBy";

    $totalArray = $extraClassAttendanceManager->getExtraClassAttendanceList($condition);
    $recordArray = $extraClassAttendanceManager->getExtraClassAttendanceList($condition,$limit,$orderBy);
    $cnt = count($recordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $recordArray[$i]['fromDate']=UtilityManager::formatDate(strip_slashes($recordArray[$i]['fromDate']));
        
        if($recordArray[$i]['substituteEmployee']=='') {
          $recordArray[$i]['substituteEmployee']=NOT_APPLICABLE_STRING;  
        }
        
        if($recordArray[$i]['comments']=='') {
          $recordArray[$i]['comments']=NOT_APPLICABLE_STRING;  
        }
        
        $ids=$recordArray[$i]['extraAttendanceId'];
        
       $action1 = '<img type="image" title="Edit" alt="Edit" name="ddetails" src="'.IMG_HTTP_PATH.'/edit.gif" onClick="return editWindow(\''.$ids.'\',\'EditExtraClass\',300,500); return false;" />&nbsp;
                   <img type="image" title="Delete" alt="Delete" name="sdetails" src="'.IMG_HTTP_PATH.'/delete.gif" onClick="return deleteExtraClass(\''.$ids.'\'); return false;" />'; 
        
        // add batchId in actionId to populate edit/delete icons in User Interface
        $valueArray = array_merge(array('action1' => $action1 , 
                                        'srNo' => ($records+$i+1) ),$recordArray[$i]);
        if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
        }
        else {
          $json_val .= ','.json_encode($valueArray);
        }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}';
?>
