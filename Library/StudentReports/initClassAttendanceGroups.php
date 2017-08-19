<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$reportManager = StudentReportsManager::getInstance();

	$timeTabelLabelId=trim($REQUEST_DATA['timeTable']);
    $classId=trim($REQUEST_DATA['degree']);
    $subjectId=trim($REQUEST_DATA['subjectId']);
    
    //this value is coming from "Student Attendance Performance Report"
    $employeeId=trim($REQUEST_DATA['employeeId']);
    $chkHierarchy=trim($REQUEST_DATA['chkHierarchy']);
    
    if($chkHierarchy=='') {
      $chkHierarchy=0;  
    }
	
    if($timeTabelLabelId=='' or $classId=='' or $subjectId==''){
        echo 'Required Parameters Missing';
        die;
    }
    //fetching group information
    if($subjectId!=-1 and $subjectId!=''){
     $groupRecordArray=$reportManager->getTimeTableClassSubjectGroup(' AND c.classId='.$classId.' AND t.subjectId='.$subjectId.' AND t.timeTableLabelId='.$timeTabelLabelId);
    }
    else{
     if($employeeId!='' and $employeeId!=-1){
       $groupRecordArray=$reportManager->getTimeTableClassSubjectGroup(' AND c.classId='.$classId.' AND t.timeTableLabelId='.$timeTabelLabelId.' AND t.employeeId='.$employeeId);
     }
     else{
       $groupRecordArray=$reportManager->getTimeTableClassSubjectGroup(' AND c.classId='.$classId.' AND t.timeTableLabelId='.$timeTabelLabelId);     
     }
    }
    if(is_array($groupRecordArray) and count($groupRecordArray)){
        echo json_encode($groupRecordArray);
    }
    else{
        echo 0; 
    }
// $History: initClassAttendanceSubjects.php $
?>