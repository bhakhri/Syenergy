<?php

//The file contains functions to get student attendance
//
// Author :Rajeev Aggarwal
// Created on : 04.12.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0); 
    global $FE;  
    require_once($FE . "/Library/common.inc.php"); //for studentId 
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler;           
     
    if($sessionHandler->getSessionVariable('RoleId')==3) {
      UtilityManager::ifParentNotLoggedIn(true);  
      $studentId= $sessionHandler->getSessionVariable('StudentId');          
    }
    else if($sessionHandler->getSessionVariable('RoleId')==4) {
      UtilityManager::ifStudentNotLoggedIn(true);   
      $studentId= $sessionHandler->getSessionVariable('StudentId');          
    }
    else {
       UtilityManager::ifNotLoggedIn(true);  
       $studentId= $REQUEST_DATA['studentId'];          
    }
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    $commonQueryManager = CommonQueryManager::getInstance(); 
  
	require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();
  
    require_once(MODEL_PATH."/PercentageWiseReportManager.inc.php");    
    $percentageWiseReportManager = PercentageWiseReportManager::getInstance();             
    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
        
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName1';
    
    $sortField1= $sortField;
    if($sortField1 =='subjectName1') {
      $sortField1 = "subjectName";
    }
    
    $orderBy = " $sortField1 $sortOrderBy";  
    if($sessionHandler->getSessionVariable('RoleId')==3 || $sessionHandler->getSessionVariable('RoleId')==4 ) {
		$studentId = $sessionHandler->getSessionVariable('StudentId');
	} // this if condition added because if any role other than student or parent has logged on this will have give valkue of student id as empty string
    if($studentId=='') {
      $studentId=0;  
    }        
   
    /*  $studentAttendanceCount = CommonQueryManager::getInstance()->getFinalAttendance($where,$orderBy,$consolidated);
        $totalRecord = count($studentAttendanceCount);
        $studentAttendanceArray = CommonQueryManager::getInstance()->getFinalAttendance($where,$orderBy,$consolidated,$limit);
    */
    
    $attendance = trim($sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD'));
    $toDate = $REQUEST_DATA['startDate2'];
    $classId = $REQUEST_DATA['rClassId'];
  
    $consolidatedView = $REQUEST_DATA['consolidatedView'];
    
    $where = " AND att.studentId = '$studentId' ";
    if($toDate!='') {
       $where .= " AND att.toDate <= '$toDate' ";
    }
    if($classId!=0) {
      $where .= " AND att.classId = '$classId' "; 
    }

    $consolidated='1';
    if($REQUEST_DATA['consolidatedView']=='1') {
      $consolidated='';
    }    
    
$holdStudentArray = $studentInformationManager->getHoldStudentsData($studentId);
    $holdStudentClassId='';
    for($i=0;$i<count($holdStudentArray);$i++) {
       if($holdStudentArray[$i]['holdAttendance']=='1') { 
          if($holdStudentClassId!='') {
            $holdStudentClassId .=",";  
          }  
          $holdStudentClassId .= $holdStudentArray[$i]['classId']; 
       }
    }

    $studentAttendanceCount = $percentageWiseReportManager->getFinalAttendanceCount($where,$consolidated,$holdStudentClassId);
    $totalRecord = count($studentAttendanceCount);
    $studentAttendanceArray = $percentageWiseReportManager->getFinalAttendance($where,$orderBy,$limit,$consolidated,'',$holdStudentClassId);
    
    for($i=0; $i<count($studentAttendanceArray); $i++) {
        
       $fontStart = '<b><u><font color="blue">';
       $fontEnd   = '</u></font></b>';
       
       $per = number_format($studentAttendanceArray[$i]['per'],2,'.','');
       
       if($studentAttendanceArray[$i]['fromDate']!=NOT_APPLICABLE_STRING) {
         $studentAttendanceArray[$i]['fromDate'] = UtilityManager::formatDate($studentAttendanceArray[$i]['fromDate']);    
       }
       if($studentAttendanceArray[$i]['toDate']!=NOT_APPLICABLE_STRING) {
         $studentAttendanceArray[$i]['toDate'] = UtilityManager::formatDate($studentAttendanceArray[$i]['toDate']);
       }
        
       $studentAttendanceArray[$i]['per'] = $per;
	
	   $studentId=$studentAttendanceArray[$i]['studentId'];
	   $classId=$studentAttendanceArray[$i]['classId'];
	   $subjectId=$studentAttendanceArray[$i]['subjectId'];
	   $groupId=$studentAttendanceArray[$i]['groupId'];
	   
       $str = $studentId."~".$classId."~".$subjectId."~".$groupId;
	   
       if($attendance!='') {
          if($studentAttendanceArray[$i]['per'] < $attendance) {
             $studentAttendanceArray[$i]['per'] = "<span style='color:#FF0000;text-decoration:underline;'>".$per."</span>";         
          }
       }
       $leaveTaken=$studentAttendanceArray[$i]['leaveTaken'];
       /*
       if($leaveTaken>0) {
          $str .= "~".$studentAttendanceArray[$i]['leaveTaken']; 
          $leave = $fontStart.$studentAttendanceArray[$i]['leaveTaken'].$fontEnd;  
		  $leaveTaken = '<a name="bubble1" onclick="showDutyLeaveDetails(\''.$str.'\',\'divDutyLeave\',600,400);return false;" >'.$leave.'</a>';
	   }
       */
	   $studentAttendanceArray[$i]['leaveTaken'] = $leaveTaken;
        
       $medicalLeaveTaken=$studentAttendanceArray[$i]['medicalLeaveTaken'];
       /*
       if($medicalLeaveTaken>0){
         $str = $studentId."~".$classId."~".$subjectId."~".$studentAttendanceArray[$i]['medicalLeaveTaken']; 
         
         $leave = $fontStart.$studentAttendanceArray[$i]['medicalLeaveTaken'].$fontEnd;  
		 $medicalLeaveTaken = '<a name="bubble22" onclick="showMedicalLeaveDetails(\''.$str.'\',\'divMedicalLeave\',600,400);return
		    false;">'.$leave.'</a>';
	    }
        */
	    $studentAttendanceArray[$i]['medicalLeaveTaken'] = $medicalLeaveTaken;
       
        $valueArray = array_merge(array('srNo' => ($records+$i+1)),$studentAttendanceArray[$i]);
        
        if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
        }
        else {
          $json_val .= ','.json_encode($valueArray);           
        }
   }
    
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecord.'","page":"'.$page.'","info" : ['.$json_val.']}';    
?>
