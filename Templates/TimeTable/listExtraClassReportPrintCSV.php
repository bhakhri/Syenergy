 <?php 
//This file is used as printing version for display cities.
//
// Author :Parveen Sharma
// Created on : 03.08.09
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

    // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return $comments.chr(160); 
         }
    }
    
    
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
       $search1="Time Table,".parseCSVComments($recordArray[0]['labelName']); 
    }
   
    if($employeeId!='') {
      $condition .= " AND e.employeeId = '$employeeId' "; 
      
      $fieldName = " DISTINCT e.employeeId, CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName";  
      $tableName=" employee e" ;
      $ttCondition= " employeeId = '$employeeId' ";  
      $recordArray = $extraClassAttendanceManager->getFetchName($fieldName,$tableName,$ttCondition);
      $search2="Teacher,".parseCSVComments($recordArray[0]['employeeName']); 
    }
    
    if($substituteEmployeeId!='') {
      $condition .= " AND e.substituteEmployeeId = '$substituteEmployeeId' "; 
      
      $fieldName = " DISTINCT e.employeeId, CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName";  
      $tableName=" employee e" ;
      $ttCondition= " employeeId = '$substituteEmployeeId' ";  
      $recordArray = $extraClassAttendanceManager->getFetchName($fieldName,$tableName,$ttCondition);
      $search3="Substitute For,".parseCSVComments($recordArray[0]['employeeName']); 
    }
    
    
    if($classId!='') {
      $condition .= " AND e.classId = '$classId' "; 
      
      $fieldName=" className";
      $tableName=" class " ;
      $ttCondition= " classId = '$classId' ";  
      $recordArray = $extraClassAttendanceManager->getFetchName($fieldName,$tableName,$ttCondition);
      $search4="Class,".parseCSVComments($recordArray[0]['className']); 
    }
    
    if($subjectId!='') {
       $condition .= " AND e.subjectId = '$subjectId' "; 
       
       $fieldName = " DISTINCT sub.subjectId, CONCAT(sub.subjectName,' (',sub.subjectCode,')' AS subjectCodeName";   
       $tableName=" `subject` sub" ;
       $ttCondition= " subjectId = '$subjectId' ";  
       $recordArray = $extraClassAttendanceManager->getFetchName($fieldName,$tableName,$ttCondition);
       $search5="Subject,".parseCSVComments($recordArray[0]['subjectCodeName']);   
       
    }
    if($groupId!='') {
       $condition .= " AND e.groupId = '$groupId' "; 
      
       $fieldName = " DISTINCT groupName";  
       $tableName=" `group` " ;
       $ttCondition= " groupId = '$groupId' ";  
       $recordArray = $extraClassAttendanceManager->getFetchName($fieldName,$tableName,$ttCondition);
       $search6="Group,".parseCSVComments($recordArray[0]['groupName']); 
       
    }
    if($periodId!='') {
       
       $condition .= " AND e.periodId = '$periodId' "; 
       
       $fieldName = " DISTINCT p.periodId, p.periodNumber, CONCAT(p.startTime,p.startAmPm,' to ',p.endTime,p.endAmPm) AS periodTime";  
       $tableName=" `period` p" ;
       $ttCondition= " periodId = '$periodId' ";  
       $recordArray = $extraClassAttendanceManager->getFetchName($fieldName,$tableName,$ttCondition);
       $search7="Period,".parseCSVComments($recordArray[0]['periodNumber']." (".$recordArray[0]['periodNumber'].")");   
    }
    
    
    if($searchDateFilter!='') {
       $condition .= " AND (e.fromDate BETWEEN '$fromDate' AND '$toDate') ";   
       $search8="Date,".parseCSVComments(UtilityManager::formatDate($fromDate)." to ".UtilityManager::formatDate($toDate));  
    }
    
    $search='';
    $count='0';
    if($search1!='') {
      $search .= $search1;
      $count++;  
    }
    if($search2!='') {
      $search .=",";  
      if($count%2==0) {
        $search .="\n";     
      }  
      $search .= $search2;
      $count++;  
    }
    if($search3!='') {
      $search .=",";
      if($count%2==0) {
        $search .="\n";     
      }    
      $search .= $search3;
      $count++;  
    }
    if($search4!='') {
      $search .=",";
      if($count%2==0) {
        $search .="\n";     
      }    
      $search .= $search4;
      $count++;  
    }
    if($search5!='') {
      $search .=",";  
      if($count%2==0) {
        $search .="\n";   
      }    
      $search .= $search5;
      $count++;  
    }
    if($search6!='') {
      $search .=",";  
      if($count%2==0) {
        $search .="\n";     
      }    
      $search .= $search6;
      $count++;  
    }
    if($search7!='') {
      $search .=",";  
      if($count%2==0) {
        $search .="\n";     
      }    
      $search .= $search7;
      $count++;  
    }
    if($search8!='') {
      $search .=",";  
      if($count%2==0) {
        $search .="\n";     
      }    
      $search .= $search8;
      $count++;  
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fromDate';
    $orderBy = " $sortField $sortOrderBy";

    $recordArray = $extraClassAttendanceManager->getExtraClassAttendanceList($condition,'',$orderBy);
    $cnt = count($recordArray);
    
    $csvData ='';
    if($search!='') {
      $csvData .= $search;
      $csvData .="\n";
    }
    $csvData .="#,Date,Class,Subject,Group,Period,Employee,Substitute For,Comments";
    $csvData .="\n";
    
    if($cnt>0) {
      for($i=0;$i<$cnt;$i++) {
          if($recordArray[$i]['substituteEmployee']=='') {
            $recordArray[$i]['substituteEmployee']=NOT_APPLICABLE_STRING;  
          }
          if($recordArray[$i]['comments']=='') {
            $recordArray[$i]['comments']=NOT_APPLICABLE_STRING;  
          }  
          $recordArray[$i]['fromDate']=UtilityManager::formatDate(strip_slashes($recordArray[$i]['fromDate']));
           // add batchId in actionId to populate edit/delete icons in User Interface
          $csvData .= ($i+1).",";
          $csvData .= parseCSVComments($recordArray[$i]['fromDate']).",";
          $csvData .= parseCSVComments($recordArray[$i]['className']).",";
          $csvData .= parseCSVComments($recordArray[$i]['subjectCode']).",";
          $csvData .= parseCSVComments($recordArray[$i]['groupName']).",";
          $csvData .= parseCSVComments($recordArray[$i]['periodTime']).",";
          $csvData .= parseCSVComments($recordArray[$i]['employeeName']).",";
          $csvData .= parseCSVComments($recordArray[$i]['substituteEmployee']).",";
          $csvData .= parseCSVComments($recordArray[$i]['comments'])."\n";
      }
    }
    else {
       $csvData .=",,,, No Data Found";  
    }
    
    UtilityManager::makeCSV($csvData,'ExtraClassConductedReport.csv');
die;         
//$History : $
?>