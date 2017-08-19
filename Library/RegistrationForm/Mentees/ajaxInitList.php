<?php
//-------------------------------------------------------
// Purpose: To store the records of time table report in array from the database for subject centric
//
// Author : Rajeev Aggarwal
// Created on : (31.10.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    ini_set('MEMORY_LIMIT','5000M'); 
    set_time_limit(0); 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentRegistrationReport');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/RegistrationForm/MenteesManager.inc.php");
    $menteesManager  = MenteesManager::getInstance();

	
    $userId = trim($REQUEST_DATA['mentorName']);
    $rollNo  = trim($REQUEST_DATA['rollNo']);
    $studentName  = trim($REQUEST_DATA['studentName']);
    $registered  = trim($REQUEST_DATA['registered']);

    /*
    $classArray = $menteesManager->getConfigClass();     
    $classIds='0';
    for($i=0;$i<count($classArray);$i++) {
      $classIds .=','.$classArray[$i]['value'];  
    }
    */
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    if($page=='undefined') {
      $page='1';
    }
    $records    = ($page-1)* 1000;
    $limit      = ' LIMIT '.$records.',1000';

    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    if($sortOrderBy=='undefined') {
      $sortOrderBy='ASC';
    }
    if($sortField=='undefined') {
      $sortField='className';
    }
    $orderBy = " $sortField $sortOrderBy";
    
    $having = "";
    $filter = "";
    if($rollNo!='') {
      $filter .= " AND s.rollNo LIKE '$rollNo%'" ;  
    }
    if($studentName!='') {
      $filter .= " AND CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%'" ;  
    }
    if($userId!='') {
      $filter .= " AND sm.userId = '$userId' ";
    }
    
    if($registered=='1') {
      $filter .= " AND IFNULL(sr.registrationDate,'0000-00-00') != '0000-00-00' ";  
    }
    else  if($registered=='2') {
      $filter .= " AND IFNULL(sr.registrationDate,'0000-00-00') = '0000-00-00' ";  
    }
    

    $totalArray = $menteesManager->getstudentRegistrationCount($filter);  
    $registrationRecordArray = $menteesManager->getstudentRegistrationDetails($filter,$orderBy,$limit);
  $feeStatus ="Unpaid";	
    $cnt = count($registrationRecordArray);
    for($i=0;$i<$cnt;$i++) {
      $studentId = $registrationRecordArray[$i]['studentId'];
      $classId = $registrationRecordArray[$i]['classId']; 
      $id = $registrationRecordArray[$i]['mentorshipId'];     
      $feeStatus ="Unpaid";	
	    $feeRecordArray = $menteesManager->getStudentFeeDetails($studentId,$classId);
	   
		if(count($feeRecordArray)>0){
		 $feeStatus ="Paid";			
		}
      $checked = "";  
      $lblId = "lbl".$id;
      $approve="<span id='$lblId'>Unapprove</span>";
      if($registrationRecordArray[$i]['isAllowRegistration']=='1') {
        $checked = "checked='checked'";  
        $approve="<span id='$lblId'><b>Approve</b></span>";
      }
      
      $chbId = "chb_".$id;
      $checkall = '<input type="checkbox" onclick="getUpdateStatus('.$id.');" id="'.$chbId.'" name="chb[]" '.$checked.' value="'.$id.'">'.$approve;      
      $span1='';
      $span2='';
      $regDate = UtilityManager::formatDate($registrationRecordArray[$i]['registrationDate']);
      if($regDate=='--') {
        $span1='<span style="color: red;">'; 
        $span2='</span>';  
      }
      else {
        $checkall = 'Done';  
      }
	  
	  $regMobileNo = $registrationRecordArray[$i]['studentMobileNo'];
	   
	   if(trim($regMobileNo)=='') {
          $regMobileNo= '--';
       }
	  
      if($regMobileNo=='--') {
        $span1='<span style="color: red;">'; 
        $span2='</span>';  
      }
      else {
        $checkall = 'Done';  
      }

      

      //$showlink = "<a href=scStudentDetail.php?id=".$studentId."&classId=".$classId."><img src='".IMG_HTTP_PATH."/zoom.gif' alt='Student Detail' title='Student Detail' border='0' /></a>";
      $showlink = "<img src=\"".IMG_HTTP_PATH."/edit.gif\" border=\"0\" title=\"Comments\" alt=\"Comments\" onClick=\"return showMentorshipDetails('".$id."','".$studentId."','".$classId."');\"/>&nbsp;&nbsp;
                   <img src=\"".IMG_HTTP_PATH."/zoom.gif\" border=\"0\" title=\"Detail View\" alt=\"Details View\" onclick=\"openUrl('".$studentId."')\" />"; 
      
      $registrationRecordArray[$i]['registrationDate'] = $span1.$regDate.$span2;
      $registrationRecordArray[$i]['className'] = $span1.$registrationRecordArray[$i]['className'].$span2;
      $registrationRecordArray[$i]['universityRollNo'] = $span1.$registrationRecordArray[$i]['universityRollNo'].$span2;
      $registrationRecordArray[$i]['studentName'] = $span1.$registrationRecordArray[$i]['studentName'].$span2;
      $registrationRecordArray[$i]['employeeName1'] = $span1.$registrationRecordArray[$i]['employeeName1'].$span2;
      $registrationRecordArray[$i]['fatherName'] = $span1.$registrationRecordArray[$i]['fatherName'].$span2;
      $registrationRecordArray[$i]['studentMobileNo'] = $span1.$regMobileNo.$span2;
      
      $valueArray = array_merge(array('srNo' => $span1.($records+$i+1).$span2,
                                      'checkAll' => $checkall, 
                                      'feeStatus' => $feeStatus,    
                                      'action1' => $showlink),
                                      $registrationRecordArray[$i]);
      if(trim($json_val)=='') {                      
        $json_val = json_encode($valueArray);
      }                                                                          
      else{
        $json_val .= ','.json_encode($valueArray);           
      }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
