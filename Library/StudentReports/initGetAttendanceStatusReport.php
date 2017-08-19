<?php
//--------------------------------------------------------
//This file returns the array of attendance missed records
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	set_time_limit(0);
	ini_set('MEMORY_LIMIT','100M');
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','AttendanceStatusReport');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$studentReportsManager = StudentReportsManager::getInstance();
	 
	
    $isTimeTableCheck=  trim($REQUEST_DATA['timeTableCheck']); 
	$classId = $REQUEST_DATA['degree'];
	$subjectId = $REQUEST_DATA['subjectId'];
	$sortOrderBy = $REQUEST_DATA['sortOrderBy'];
	$sortField = $REQUEST_DATA['sortField'];
	$labelId = $REQUEST_DATA['labelId'];
    $employeeId = $REQUEST_DATA['employeeId'];
    $showTodayAttendance = $REQUEST_DATA['showTodayAttendance'];   
    $todayDate = $REQUEST_DATA['txtDate'];  
    
    if($isTimeTableCheck=='on') {
      $isTimeTableCheck=1;  
    }
    else {
      $isTimeTableCheck=0;    
    }

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    $orderBy = " $sortField $sortOrderBy";         
    
    if($classId=='') {
      $classId=0;  
    }

	$conditions = '';
    $conditionsUser = '';
    $conditionsEmployee='';
	if ($classId != 'all') {
		$conditions = " AND g.classId = '$classId'";
	}
    if($subjectId != 'all') {
      $conditions .= " AND tt.subjectId = '$subjectId'";
    }
    if($employeeId != 'all') {
      $conditions .= " AND tt.employeeId = '$employeeId'";
    }
    
    if($showTodayAttendance=='on') {
       $totalRecordsArray =  $studentReportsManager->getLastNotAttendanceTaken($labelId, $sortField, $sortOrderBy, $conditions,'',$todayDate);  
       $recordArray = $studentReportsManager->getLastNotAttendanceTaken($labelId, $sortField, $sortOrderBy, $conditions, $limit,$todayDate);
       $cnt = count($totalRecordsArray); 
    }
    else {
	   $totalRecordsArray =  $studentReportsManager->getAttendanceStatusReport($labelId, $sortField, $sortOrderBy, $conditions, '');  
	   $recordArray = $studentReportsManager->getAttendanceStatusReport($labelId, $sortField, $sortOrderBy, $conditions, $limit);
	   $cnt = count($totalRecordsArray);
    }



    for($i=0;$i<count($recordArray);$i++) {
	   $recordArray[$i]['tillDate'] = UtilityManager::formatDate($recordArray[$i]['tillDate']);
       $valueArray = array_merge(array('srNo' => ($records+$i+1)),$recordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 

?>