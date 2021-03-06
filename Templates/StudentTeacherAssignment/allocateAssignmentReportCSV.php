<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
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
    

//to parse csv values    
function parseCSVComments($comments) {
 $comments = str_replace('"', '""', $comments);
 $comments = str_ireplace('<br/>', "\n", $comments);
 if(eregi(",", $comments) or eregi("\n", $comments)) {
   return '"'.$comments.'"'; 
 } 
 else {
 return $comments; 
 }
 
}

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
      $search1="Time Table,".parseCSVComments($recordArray[0]['labelName']); 
    }
    
    if($employeeId!='') {
      $filter .=" AND aa.employeeId = '$employeeId' ";
      
      $fieldName = " DISTINCT e.employeeId, CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName";  
      $tableName=" employee e" ;
      $ttCondition= " employeeId = '$employeeId' ";  
      $recordArray = $teacherManager->getFetchName($fieldName,$tableName,$ttCondition);
      $search2="Teacher,".parseCSVComments($recordArray[0]['employeeName']); 
    }
    
    if($classId!='') {
      $filter .=" AND aa.classId = '$classId' ";
      
      $fieldName=" className";
      $tableName=" class " ;
      $ttCondition= " classId = '$classId' ";  
      $recordArray = $teacherManager->getFetchName($fieldName,$tableName,$ttCondition);
      $search3="Class,".parseCSVComments($recordArray[0]['className']);    
    }
    
    if($subjectId!='') {
      $filter .=" AND aa.subjectId = '$subjectId' ";
      
      $fieldName = " DISTINCT sub.subjectId, CONCAT(sub.subjectName,' (',sub.subjectCode,')') AS subjectCodeName";   
       $tableName=" `subject` sub" ;
       $ttCondition= " subjectId = '$subjectId' ";  
       $recordArray = $teacherManager->getFetchName($fieldName,$tableName,$ttCondition);
       $search4="Subject,".parseCSVComments($recordArray[0]['subjectCodeName']);  
    }
    if($groupId!='') {
      $filter .=" AND aa.groupId = '$groupId' ";
      
      $fieldName = " DISTINCT groupName";  
      $tableName=" `group` " ;
      $ttCondition= " groupId = '$groupId' ";  
      $recordArray = $teacherManager->getFetchName($fieldName,$tableName,$ttCondition);
      $search5="Group,".parseCSVComments($recordArray[0]['groupName']);   
    }
    
    
   if($searchDateFilter!='') {
      $dt = parseCSVComments(UtilityManager::formatDate($searchFromDate)." to ".UtilityManager::formatDate($searchToDate));  
      if($searchDateFilter=='1') {
        $filter .=" AND (aa.assignedOn BETWEEN '$searchFromDate' AND '$searchToDate') ";    
        $search6="Assigned Date,".$dt; 
      }  
      else if($searchDateFilter=='2') {
        $filter .=" AND (aa.tobeSubmittedOn BETWEEN '$searchFromDate' AND '$searchToDate') ";    
        $search6="Due Date,".$dt;  
      }  
      else if($searchDateFilter=='3') {
        $filter .=" AND (aa.addedOn BETWEEN '$searchFromDate' AND '$searchToDate') ";    
        $search6="Added Date,".$dt;  
      }  
      else if($searchDateFilter=='4') {
        $filter .=" AND ( (aa.assignedOn BETWEEN '$searchFromDate' AND '$searchToDate') OR 
                          (aa.tobeSubmittedOn BETWEEN '$searchFromDate' AND '$searchToDate') OR
                          (aa.addedOn BETWEEN '$searchFromDate' AND '$searchToDate')
                        ) ";    
        $search6="Date,".$dt; 
      }  
    }
    
    $search='';
    $count='1';
    if($search1!='') {
      $search .= $search1;
      $count++;  
    }
    if($search2!='') {
      if($count%2==0) {
        $search .="\n";   
      }  
      $search .= $search2;
      $count++;  
    }
    if($search3!='') {
      if($count%2==0) {
        $search .="\n";   
      }    
      $search .= $search3;
      $count++;  
    }
    if($search4!='') {
      if($count%2==0) {
        $search .="\n";   
      }    
      $search .= $search4;
      $count++;  
    }
    if($search5!='') {
      if($count%2==0) {
        $search .="\n";   
      }    
      $search .= $search5;
      $count++;  
    }
    if($search6!='') {
      if($count%2==0) {
        $search .="\n";   
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

	$csvData = $search."\n";
    
    $csvData .= "#, Assigned, Teacher, Topic, Description, Due Date, Added , Total, Visible \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['assignedOn']).','.parseCSVComments($record['employeeName']).','.
                    parseCSVComments($record['topicTitle']).','.parseCSVComments($record['topicDescription']).','.
                    parseCSVComments($record['tobeSubmittedOn']).','.$record['addedOn'].','.$record['totalAssignment'].','.$record['isVisible'];
		$csvData .= "\n";
	}
    if($cnt==0){
        $csvData .=",".NO_DATA_FOUND;
    }
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="allocateAssignmentReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: testTypeCSV.php $
?>