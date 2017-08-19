<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE salary head
//
// Author : Rajeev Aggarwal
// Created on : (24.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AllocateAssignment');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['assignmentId'] ) != '') {


	require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

	 // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_TEACHER;
    //////

    /////search functionility not needed
    $filter="";
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';

     $orderBy = " $sortField $sortOrderBy";

	/* START: function to fetch teacher assignment detail*/
    $teacherAssignmentArray = $teacherManager->getTeacherDetailAssignment($REQUEST_DATA['assignmentId']);
	if(is_array($teacherAssignmentArray) && count($teacherAssignmentArray)>0 ) {

		$teacherAssignmentArray[0]['topicTitle']=stripslashes($teacherAssignmentArray[0]['topicTitle']);
		$teacherAssignmentArray[0]['topicDescription']=stripslashes($teacherAssignmentArray[0]['topicDescription']);
        $json_assignment =  json_encode($teacherAssignmentArray[0]);
    }
    else {
        $json_assignment =  "exit"; // no record found
		exit;
    }

	$subjectId = $teacherAssignmentArray[0]['subjectId'];
	$groupId = $teacherAssignmentArray[0]['groupId'];
    $classId = $teacherAssignmentArray[0]['classId'];

	$condition = " AND c.classId=$classId AND sc.subjectId=$subjectId AND g.groupId=$groupId";

	$studentRecordArray = $teacherManager->getStudentDetailAssignment($condition,trim($REQUEST_DATA['assignmentId']),$groupId);
	$cnt = count($studentRecordArray);
	$studentSubmit = 0;
    for($i=0;$i<$cnt;$i++) {

		$checked = '';
		if($studentRecordArray[$i]['assignmentId'])
		   $checked = "CHECKED";
		   $userCheck = "<input $checked $disabled type=\"checkbox\" name=\"students\" id=\"students\" value=\"".$studentRecordArray[$i]['studentId']."\">";
		   $disabled = '';

		if($studentRecordArray[$i]['submitDate']!='-1'){
			//$userCheck = "--red_button.gif";
			//$userCheck='<img src="'.IMG_HTTP_PATH.'/green_button.gif" border="0" alt="Task Uploaded">';
		     $userCheck = "<input disabled type=\"checkbox\" name=\"students\" id=\"students\" value=\"".$studentRecordArray[$i]['studentId']."\">";
			$studentSubmit=$studentSubmit+1;
		}
		$valueArray = array_merge(array('srNo' => ($records+$i+1),"students" => $userCheck)
        , $studentRecordArray[$i]);


		//$valueArray = array_merge(array('srNo' => ($records+$i+1),"students" => "<input type=\"checkbox\" name=\"students\" id=\"students\" value=\"".$studentRecordArray[$i]['studentId'].'~'.$studentRecordArray[$i]['classId']."\">") ),$studentAssignmentArray[$i]);

        if(trim($json_val)=='') {

			$json_val = json_encode($valueArray);
		}
       else {

			$json_val .= ','.json_encode($valueArray);
       }
    }

	echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","page":"'.$page.'","studentSubmit":"'.$studentSubmit.'","assignmentinfo" : ['.$json_assignment.'],"info" : ['.$json_val.']}';
	/* END: function to function to fetch teacher assignment detail */
}
// $History: ajaxGetTeacherAssignmentValues.php $
?>