 <?php 
//This file is used as CSV version for display cities.
//
// Author :Parveen Sharma
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ExtraClassAttendance');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/ExtraClassAttendanceManager.inc.php");
    $extraClassAttendanceManager = ExtraClassAttendanceManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  
    
    
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
    
    
    $search1='';
    $search2='';
    $search3='';
    $search4='';
    $search5='';
    $search6=''; 
    $search7=''; 
    $search8=''; 
    
    $condition='';
    $condition= " AND e.timeTableLabelId = '$timeTableLabelId' ";
    if($timeTableLabelId!='') {
       //$condition= " AND e.timeTableLabelId = '$timeTableLabelId' ";  
       $fieldName=" labelName";
       $tableName=" time_table_labels " ;
       $ttCondition= " timeTableLabelId = '$timeTableLabelId' ";  
       $recordArray = $extraClassAttendanceManager->getFetchName($fieldName,$tableName,$ttCondition);
       $search1="<b>Time Table&nbsp;:</b>&nbsp;".$recordArray[0]['labelName']; 
    }
   
    if($employeeId!='') {
      $condition .= " AND e.employeeId = '$employeeId' "; 
      
      $fieldName = " DISTINCT e.employeeId, CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName";  
      $tableName=" employee e" ;
      $ttCondition= " employeeId = '$employeeId' ";  
      $recordArray = $extraClassAttendanceManager->getFetchName($fieldName,$tableName,$ttCondition);
      $search2="<b>Teacher&nbsp;:</b>&nbsp;".$recordArray[0]['employeeName']; 
    }
    
    if($substituteEmployeeId!='') {
      $condition .= " AND e.substituteEmployeeId = '$substituteEmployeeId' "; 
      
      $fieldName = " DISTINCT e.employeeId, CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName";  
      $tableName=" employee e" ;
      $ttCondition= " employeeId = '$substituteEmployeeId' ";  
      $recordArray = $extraClassAttendanceManager->getFetchName($fieldName,$tableName,$ttCondition);
      $search3="<b>Substitute For&nbsp;:</b>&nbsp;".$recordArray[0]['employeeName']; 
    }
    
    
    if($classId!='') {
      $condition .= " AND e.classId = '$classId' "; 
      
      $fieldName=" className";
      $tableName=" class " ;
      $ttCondition= " classId = '$classId' ";  
      $recordArray = $extraClassAttendanceManager->getFetchName($fieldName,$tableName,$ttCondition);
      $search4="<b>Class&nbsp;:</b>&nbsp;".$recordArray[0]['className']; 
    }
    if($subjectId!='') {
       $condition .= " AND e.subjectId = '$subjectId' "; 
       
       $fieldName = " DISTINCT sub.subjectId, CONCAT(sub.subjectName,' (',sub.subjectCode,')' AS subjectCodeName";   
       $tableName=" `subject` sub" ;
       $ttCondition= " subjectId = '$subjectId' ";  
       $recordArray = $extraClassAttendanceManager->getFetchName($fieldName,$tableName,$ttCondition);
       $search5="<b>Subject&nbsp;:</b>&nbsp;".$recordArray[0]['subjectCodeName'];   
       
    }
    if($groupId!='') {
       $condition .= " AND e.groupId = '$groupId' "; 
      
       $fieldName = " DISTINCT groupName";  
       $tableName=" `group` " ;
       $ttCondition= " groupId = '$groupId' ";  
       $recordArray = $extraClassAttendanceManager->getFetchName($fieldName,$tableName,$ttCondition);
       $search6="<b>Group&nbsp;:</b>&nbsp;".$recordArray[0]['groupName']; 
       
    }
    if($periodId!='') {
       
       $condition .= " AND e.periodId = '$periodId' "; 
       
       $fieldName = " DISTINCT p.periodId, p.periodNumber, CONCAT(p.startTime,p.startAmPm,' to ',p.endTime,p.endAmPm) AS periodTime";  
       $tableName=" `period` p" ;
       $ttCondition= " periodId = '$periodId' ";  
       $recordArray = $extraClassAttendanceManager->getFetchName($fieldName,$tableName,$ttCondition);
       $search7="<b>Period&nbsp;:</b>&nbsp;".$recordArray[0]['periodNumber']." (".$recordArray[0]['periodNumber'].")";   
    }
    
    
    if($searchDateFilter!='') {
       $condition .= " AND (e.fromDate BETWEEN '$fromDate' AND '$toDate') ";   
       $search8="<b>Date</b>:&nbsp;".UtilityManager::formatDate($fromDate)."&nbsp;&nbsp;to&nbsp;&nbsp;".UtilityManager::formatDate($toDate);  
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
    if($search7!='') {
      if($count%2==0) {
        $search .="<br>";   
      }    
      $search .= $search7;
      $count++;  
    }
    if($search8!='') {
      if($count%2==0) {
        $search .="<br>";   
      }    
      $search .= $search8;
      $count++;  
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fromDate';
    $orderBy = " $sortField $sortOrderBy";

    $recordArray = $extraClassAttendanceManager->getExtraClassAttendanceList($condition,'',$orderBy);
    $cnt = count($recordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $recordArray[$i]['fromDate']=UtilityManager::formatDate(strip_slashes($recordArray[$i]['fromDate']));
        
        if($recordArray[$i]['substituteEmployee']=='') {
          $recordArray[$i]['substituteEmployee']=NOT_APPLICABLE_STRING;  
        }
        
        if($recordArray[$i]['comments']=='') {
          $recordArray[$i]['comments']=NOT_APPLICABLE_STRING;  
        }
        
        // add batchId in actionId to populate edit/delete icons in User Interface
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }
  
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Extra Class Conducted by Faculty Report');
    $reportManager->setReportInformation($search);


    $reportTableHead                        =    array();
                    //associated key                  col.label,         col. width,      data align        
    $reportTableHead['srNo']				=    array('#',              ' width="2%" align="left"', "align='left'");
    $reportTableHead['fromDate']			=    array('Date',           ' width=8% align="center" ','align="center" ');
    $reportTableHead['className']	        =    array('Class',          ' width="12%" align="left" ','align="left"');
	$reportTableHead['subjectCode']			=    array('Subject',        ' width="10%" align="left" ','align="left"');
    $reportTableHead['groupName']           =    array('Group',          ' width="8%" align="left" ','align="left"');
    $reportTableHead['periodTime']        =    array('Period',         ' width="12%" align="left" ','align="left"');
    $reportTableHead['employeeName']        =    array('Employee',       ' width="10%" align="left" ','align="left"');
    $reportTableHead['substituteEmployee']  =    array('Substitute For', ' width="12%" align="left" ','align="left"');
    $reportTableHead['comments']            =    array('Comments',       ' width="15%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 


?>
