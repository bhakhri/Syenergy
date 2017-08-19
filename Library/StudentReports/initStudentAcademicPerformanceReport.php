<?php
//This file is used for sending all data for student performance report
//
// Author :Jaineesh
// Created on : 16.06.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
    

	$studentReportsManager = StudentReportsManager::getInstance();

	$classId =$REQUEST_DATA['degree'][0];
    if($classId==''){
      echo 'Required Parameter missing';
      die;
    }
 
    
    if(trim($REQUEST_DATA['incDetained'])==1){
      $theoryLimit=trim($REQUEST_DATA['incTheory'])!=''?trim($REQUEST_DATA['incTheory']):0;
      $practicalLimit=trim($REQUEST_DATA['incPractical'])!=''?trim($REQUEST_DATA['incPractical']):0;
      $trainingLimit=trim($REQUEST_DATA['incTraining'])!=''?trim($REQUEST_DATA['incTraining']):0;
    }
    
    $dataArray = array();
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : ' rollNo';
    $orderBy = " $sortField $sortOrderBy";
	 
     $conditionStudent="";
    // get student attendance 
    if(trim($REQUEST_DATA['incDetained'])==1){ 
       $studentId=0;
       
       $attCondition = " AND att.classId= '$classId' AND st.subjectTypeId=1 ";
       $attOrderBy = " subjectCode ";
       $consolidated = "1";
       $percentage = " WHERE IF(t.lectureDelivered=0,0,(t.lectureAttended/t.lectureDelivered)*100) < $theoryLimit ";
       $studentAttendanceArray = CommonQueryManager::getInstance()->getStudentAttendanceReport($attCondition,$attOrderBy,$consolidated,'',$percentage); 
       
       for($i=0; $i<count($studentAttendanceArray); $i++) {
          $studentId .=",".$studentAttendanceArray[$i]['studentId']; 
       }
      
       $attCondition = " AND att.classId= '$classId' AND st.subjectTypeId=2 ";
       $attOrderBy = " subjectCode ";
       $consolidated = "1";
       $percentage = " WHERE IF(t.lectureDelivered=0,0,(t.lectureAttended/t.lectureDelivered)*100) < $practicalLimit ";
       $studentAttendanceArray = CommonQueryManager::getInstance()->getStudentAttendanceReport($attCondition,$attOrderBy,$consolidated,'',$percentage); 
       for($i=0; $i<count($studentAttendanceArray); $i++) {
          $studentId .=",".$studentAttendanceArray[$i]['studentId']; 
       }
       
       
       $attCondition = " AND att.classId= '$classId' AND st.subjectTypeId =3 ";
       $attOrderBy = " subjectCode ";
       $consolidated = "1";
       $percentage = " WHERE IF(t.lectureDelivered=0,0,(t.lectureAttended/t.lectureDelivered)*100) < $trainingLimit ";
       $studentAttendanceArray = CommonQueryManager::getInstance()->getStudentAttendanceReport($attCondition,$attOrderBy,$consolidated,'',$percentage); 
      
       for($i=0; $i<count($studentAttendanceArray); $i++) {
          $studentId .=",".$studentAttendanceArray[$i]['studentId']; 
       }
       
       $conditionStudent = " AND sg.studentId IN ($studentId) ";
    }
    
    $condition = $conditionStudent." AND  sg.classId IN (".$classId.")";
    $studentIdClassArray = $studentReportsManager->getStudentClassDetail($condition,$orderBy);
    $cnt = count($studentIdClassArray);
    for($i=0;$i<$cnt;$i++) {
   	   $checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($studentIdClassArray[$i]['studentId']).'">';

       // add subjectId in actionId to populate edit/delete icons in User Interface
       $valueArray = array_merge(array('checkAll' => $checkall, 'srNo' => ($records+$i+1)),$studentIdClassArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    
    echo '{"classId":"'.$classId.'","sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
?>