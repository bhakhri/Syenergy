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
define('MODULE','StudentAssignment');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['assignmentDetailId'] ) != '') {

	require_once(MODEL_PATH . "/Student/StudentAssignmentManager.inc.php");
	
	//echo $REQUEST_DATA['assignmentDetailId']."---".$REQUEST_DATA['assignmentType'];
    $foundArray = StudentAssignmentManager::getInstance()->getStudentAssignment(' AND assignmentDetailId="'.trim($REQUEST_DATA['assignmentDetailId']).'"');
	
	if(is_array($foundArray) && count($foundArray)>0 ) { 

		$foundArray[0]['topicDescription']=stripslashes($foundArray[0]['topicDescription']);
		$foundArray[0]['assignedOn'] = UtilityManager::formatDate($foundArray[0]['assignedOn']);
		$foundArray[0]['tobeSubmittedOn'] = UtilityManager::formatDate($foundArray[0]['tobeSubmittedOn']);
		$foundArray[0]['submittedOn'] = UtilityManager::formatDate($foundArray[0]['submittedOn']);
		echo json_encode($foundArray[0]);
	}
	else {
		echo 0;
	}
	 
}
// $History: ajaxGetStudentAssignmentValues.php $
?>