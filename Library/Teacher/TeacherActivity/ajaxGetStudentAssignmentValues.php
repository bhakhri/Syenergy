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
    $teacherAssignmentArray = $teacherManager->getTeacherDetailAssignment(trim($REQUEST_DATA['assignmentId']));
	//print_r($teacherAssignmentArray ); die;
	if(is_array($teacherAssignmentArray) && count($teacherAssignmentArray)>0 ) {

		$teacherAssignmentArray[0]['topicTitle']=stripslashes($teacherAssignmentArray[0]['topicTitle']);
		$teacherAssignmentArray[0]['topicDescription']=trim(chunk_split($teacherAssignmentArray[0]['topicDescription'],145,"<br>&nbsp;&nbsp;&nbsp;"));
		$teacherAssignmentArray[0]['topicTitle']=wordwrap($teacherAssignmentArray[0]['topicTitle'], 100, "<br>\n", true);
		$teacherAssignmentArray[0]['assignedOn'] = UtilityManager::formatDate($teacherAssignmentArray[0]['assignedOn']);
		$teacherAssignmentArray[0]['tobeSubmittedOn'] = UtilityManager::formatDate($teacherAssignmentArray[0]['tobeSubmittedOn']);
		$teacherAssignmentArray[0]['addedOn'] = UtilityManager::formatDate($teacherAssignmentArray[0]['addedOn']);
        $teacherAssignmentArray[0]['isVisible'] =$teacherAssignmentArray[0]['isVisible']==1?'Yes':'No';
        $json_assignment =  json_encode($teacherAssignmentArray[0]);
    }
    else {
        $json_assignment =  "exit"; // no record found
		exit;
    }

	$subjectId = $teacherAssignmentArray[0]['subjectId'];
	$groupId   = $teacherAssignmentArray[0]['groupId'];
    $classId   = $teacherAssignmentArray[0]['classId'];

	$condition = " AND c.classId=$classId AND sc.subjectId=$subjectId AND g.groupId=$groupId";

	$studentRecordArray = $teacherManager->getStudentAssignmentStatus($condition,trim($REQUEST_DATA['assignmentId']),$groupId);
	//print_r($studentRecordArray); die;
	$cnt = count($studentRecordArray);

    for($i=0;$i<$cnt;$i++) {
		if($studentRecordArray[$i]['submitDate']!='--')
			$studentRecordArray[$i]['submitDate'] = UtilityManager::formatDate($studentRecordArray[$i]['submitDate']);
		else
			$studentRecordArray[$i]['submitDate'] = '--';

        if($studentRecordArray[$i]['attachmentFile']){
            $studentRecordArray[$i]['attachmentFile'] ='<img src="'.IMG_HTTP_PATH.'/download.gif" title="Download File" onclick="download(\''.$studentRecordArray[$i]['attachmentFile'].'\')" />';
        }
        else{
            $studentRecordArray[$i]['attachmentFile'] = '--';
        }            

		if($studentRecordArray[$i]['replyAttachmentFile']){
			$studentRecordArray[$i]['replyAttachmentFile'] ='<img src="'.IMG_HTTP_PATH.'/download.gif" title="Download File" onclick="download(\''.$studentRecordArray[$i]['replyAttachmentFile'].'\')" />';
		}
		else{
			$studentRecordArray[$i]['replyAttachmentFile'] = '--';
		}
		$valueArray = array_merge(array('srNo' => ($records+$i+1)), $studentRecordArray[$i]);


        if(trim($json_val)=='') {

			$json_val = json_encode($valueArray);
		}
       else {

			$json_val .= ','.json_encode($valueArray);
       }
    }

	echo '{"assignmentinfo" : ['.$json_assignment.'],"info" : ['.$json_val.']}';
	/* END: function to function to fetch teacher assignment detail */
}
// $History: ajaxGetStudentAssignmentValues.php $
?>
