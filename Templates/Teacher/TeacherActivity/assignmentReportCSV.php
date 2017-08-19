<?php 
// This file is used as csv version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    
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

    $roleId=$sessionHandler->getSessionVariable('RoleId');
    
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $subjectId=trim($REQUEST_DATA['subjectId']);
    $groupId=trim($REQUEST_DATA['groupId']);
    
    if($timeTableLabelId=='' or $classId==''){
        die('Required Parameters Missing');
    }
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    if($roleId==2){
      $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectCode';
    }
    else{
      $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    }

    $orderBy=" $sortField $sortOrderBy"; 

    $filter =' AND asg.classId='.$classId.' AND ttc.timeTableLabelId='.$timeTableLabelId;
    
    if($subjectId!='' and $subjectId!=-1){
        $filter .=' AND asg.subjectId='.$subjectId;
    }
    
    if($groupId!='' and $groupId!=-1){
        $filter .=' AND asg.groupId='.$groupId;
    }
    
    if($roleId==2){
       $filter .=' AND asg.employeeId='.$sessionHandler->getSessionVariable('EmployeeId');    
    }

    $recordArray = $teacherManager->getAssignmentList($filter,' ',$orderBy);

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$csvData = '';
    $csvData .= "#, ";
    if($roleId!=2){
       $csvData .= "Teacher, "; 
    }
    $csvData .= "Subject, Group, Topic, Description, Assigned , Total \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', ';
        if($roleId!=2){
            $csvData .= parseCSVComments($record['employeeName']).', ';
        }
        $csvData .= parseCSVComments($record['subjectCode']).', '.parseCSVComments($record['groupName']).', '.parseCSVComments($record['topicTitle']).','.parseCSVComments($record['topicDescription']).','.$record['assignedOn'].','.$record['totalAssigned'];
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="assignmentReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: testTypeCSV.php $
?>