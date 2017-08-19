<?php
//--------------------------------------------------------
// This file is used as printing version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler;   
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2) {
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/AssignmentReportManager.inc.php");
    $teacherManager = AssignmentReportManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
     
    $timeTableLabelId    = $REQUEST_DATA['timeTableLabelId'];
    $employeeId = $REQUEST_DATA['employeeId'];   
    $classId  = $REQUEST_DATA['classId'];  
    $subjectId   = $REQUEST_DATA['subjectId']; 
    $groupId = $REQUEST_DATA['groupId'];   
    
    $searchDateFilter = $REQUEST_DATA['searchDateFilter'];    
    $searchFromDate = $REQUEST_DATA['searchFromDate'];   
    $searchToDate = $REQUEST_DATA['searchToDate'];   

	
	$conditionsArray = array();
	$qryString = "";
    
    
    if($timeTableLabelId=='') {
      $timeTableLabelId='0';  
    }
    
    $filter ="";
    
    $search ="";
    $search1='';
    $search2='';
    $search3='';
    $search4='';
    $search5='';
    $search6='';
    
    if($timeTableLabelId!='') {
      $filter .=" AND timeTableLabelId = '$timeTableLabelId' ";
    
      $fieldName=" labelName";
      $tableName=" time_table_labels " ;
      $ttCondition= " timeTableLabelId = '$timeTableLabelId' ";  
      $recordArray = $teacherManager->getFetchName($fieldName,$tableName,$ttCondition);
      $search1="<b>Time Table&nbsp;:</b>&nbsp;".$recordArray[0]['labelName']; 
    }
    
    if($employeeId!='') {
      $filter .=" AND aa.employeeId = '$employeeId' ";
      
      $fieldName = " DISTINCT e.employeeId, CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName";  
      $tableName=" employee e" ;
      $ttCondition= " employeeId = '$employeeId' ";  
      $recordArray = $teacherManager->getFetchName($fieldName,$tableName,$ttCondition);
      $search2="&nbsp;<b>Teacher&nbsp;:</b>&nbsp;".$recordArray[0]['employeeName']; 
    }
    
    if($classId!='') {
      $filter .=" AND aa.classId = '$classId' ";
      
      $fieldName=" className";
      $tableName=" class " ;
      $ttCondition= " classId = '$classId' ";  
      $recordArray = $teacherManager->getFetchName($fieldName,$tableName,$ttCondition);
      $search3="&nbsp;<b>Class&nbsp;:</b>&nbsp;".$recordArray[0]['className']; 
    }
    
    if($subjectId!='') {
      $filter .=" AND aa.subjectId = '$subjectId' ";
      
      $fieldName = " DISTINCT sub.subjectId, CONCAT(sub.subjectName,' (',sub.subjectCode,')') AS subjectCodeName";   
       $tableName=" `subject` sub" ;
       $ttCondition= " subjectId = '$subjectId' ";  
       $recordArray = $teacherManager->getFetchName($fieldName,$tableName,$ttCondition);
       $search4="&nbsp;<b>Subject&nbsp;:</b>&nbsp;".$recordArray[0]['subjectCodeName']; 
    }
    if($groupId!='') {
      $filter .=" AND aa.groupId = '$groupId' ";
      
      $fieldName = " DISTINCT groupName";  
      $tableName=" `group` " ;
      $ttCondition= " groupId = '$groupId' ";  
      $recordArray = $teacherManager->getFetchName($fieldName,$tableName,$ttCondition);
      $search5="&nbsp;<b>Group&nbsp;:</b>&nbsp;".$recordArray[0]['groupName']; 
    }
    
    
   if($searchDateFilter!='') {
      if($searchDateFilter=='1') {
        $filter .=" AND (aa.assignedOn BETWEEN '$searchFromDate' AND '$searchToDate') ";    
        $search6="&nbsp;<b>Assigned Date</b>:&nbsp;".UtilityManager::formatDate($searchFromDate)."&nbsp;&nbsp;to&nbsp;&nbsp;".UtilityManager::formatDate($searchToDate);  
      }  
      else if($searchDateFilter=='2') {
        $filter .=" AND (aa.tobeSubmittedOn BETWEEN '$searchFromDate' AND '$searchToDate') ";    
        $search6="&nbsp;<b>Due Date</b>:&nbsp;".UtilityManager::formatDate($searchFromDate)."&nbsp;&nbsp;to&nbsp;&nbsp;".UtilityManager::formatDate($searchToDate);  
      }  
      else if($searchDateFilter=='3') {
        $filter .=" AND (aa.addedOn BETWEEN '$searchFromDate' AND '$searchToDate') ";    
        $search6="&nbsp;<b>Added Date</b>:&nbsp;".UtilityManager::formatDate($searchFromDate)."&nbsp;&nbsp;to&nbsp;&nbsp;".UtilityManager::formatDate($searchToDate);  
      }  
      else if($searchDateFilter=='4') {
        $filter .=" AND ( (aa.assignedOn BETWEEN '$searchFromDate' AND '$searchToDate') OR 
                          (aa.tobeSubmittedOn BETWEEN '$searchFromDate' AND '$searchToDate') OR
                          (aa.addedOn BETWEEN '$searchFromDate' AND '$searchToDate')
                        ) ";    
        $search6="&nbsp;<b>Date</b>:&nbsp;".UtilityManager::formatDate($searchFromDate)."&nbsp;&nbsp;to&nbsp;&nbsp;".UtilityManager::formatDate($searchToDate);               }  
    }
    
    
    $search='';
    $count='1';
    if($search1!='') {
      $search .= $search1;
      $count++;  
    }
    if($search2!='') {
      if($count%2==0) {
        $search .="<br>";   
      }  
      $search .= $search2;
      $count++;  
    }
    if($search3!='') {
      if($count%2==0) {
        $search .="<br>";   
      }    
      $search .= $search3;
      $count++;  
    }
    if($search4!='') {
      if($count%2==0) {
        $search .="<br>";   
      }    
      $search .= $search4;
      $count++;  
    }
    if($search5!='') {
      if($count%2==0) {
        $search .="<br>";   
      }    
      $search .= $search5;
      $count++;  
    }
    if($search6!='') {
      if($count%2==0) {
        $search .="<br>";   
      }    
      $search .= $search6;
      $count++;  
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'assignedOn';
    $orderBy = " $sortField $sortOrderBy";  

    
    $recordArray =$teacherManager->getTeacherAssignmentList($filter,'');
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $recordArray[$i]['assignedOn'] = UtilityManager::formatDate($recordArray[$i]['assignedOn']);
        $recordArray[$i]['tobeSubmittedOn'] = UtilityManager::formatDate($recordArray[$i]['tobeSubmittedOn']);
        $recordArray[$i]['addedOn'] = UtilityManager::formatDate($recordArray[$i]['addedOn']);
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Student Teacher Assignment Detail Report');
    $reportManager->setReportInformation("$search");
	 
	$reportTableHead					 =	array();
	$reportTableHead['srNo']			 =   array('#','width="2%"', "align='center' ");
    $reportTableHead['assignedOn']       =   array('Assigned','width="10%" align="center" ', 'align="center"');
    $reportTableHead['employeeName']     =   array('Teacher','width="12%" align="left" ', 'align="left"');
    $reportTableHead['topicTitle']       =   array('Topic','width=15% align="left"', 'align="left"');
	$reportTableHead['topicDescription'] =   array('Description','width=20% align="left"', 'align="left"');
    $reportTableHead['tobeSubmittedOn']  =   array('Due Date','width="10%" align="center" ', 'align="center"');
    $reportTableHead['addedOn']          =   array('Added','width="6%" align="center" ', 'align="center"');
    $reportTableHead['totalAssignment']  =   array('Total','width="6%" align="right" ', 'align="right"');
    $reportTableHead['isVisible']        =   array('Visible','width="7%" align="center" ', 'align="center"');
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: testTypePrint.php $
?>