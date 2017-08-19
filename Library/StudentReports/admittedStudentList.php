<?php
//-------------------------------------------------------
// Purpose: To store the records of time table report in array from the database for subject centric
//
// Author : Rajeev Aggarwal
// Created on : (31.10.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','MessagesList');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager  = StudentReportsManager::getInstance();

   
    $classId = $REQUEST_DATA['classId'];
    $quotaId = $REQUEST_DATA['quotaId'];
    
    if($classId=='') {
      $classId=0;  
    }
    
    $condition = " AND s.classId IN ($classId)";
    if($quotaId!='') {
      $condition .= " AND s.quotaId IN ($quotaId)";  
    }
   
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"")="" OR studentName = "'.NOT_APPLICABLE_STRING.'",s.studentId, studentName)';
    }
    else 
    if ($sortField == 'fatherName') {
        $sortField1 = 'IF(IFNULL(fatherName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, fatherName)';
    }
    else
    if ($sortField == 'dateOfBirth') {
        $sortField1 = 'IF(IFNULL(dateOfBirth,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, dateOfBirth)';
    }
    else
    if ($sortField == 'compExamRollNo') {
        $sortField1 = 'IF(IFNULL(compExamRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, CAST(compExamRollNo AS UNSIGNED) )';
    }
    else
    if ($sortField == 'compExamRank') {
        $sortField1 = 'IF(IFNULL(compExamRank,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, CAST(compExamRank AS UNSIGNED))';
    }
    else
    if ($sortField == 'studentGender') {
        $sortField1 = 'IF(IFNULL(studentGender,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, studentGender)';
    }
    else
    if ($sortField == 'quotaName1') {
        $sortField1 = 'IF(IFNULL(quotaName1,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, quotaName1)';
    }
    else
    if ($sortField == 'managementCategory1') {
        $sortField1 = 'IF(IFNULL(managementCategory1,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, managementCategory1)';
    }
    else {
        $sortField = 'studentName';
        $sortField1 = 'IF(IFNULL(studentName,"")="" OR studentName = "'.NOT_APPLICABLE_STRING.'",s.studentId, studentName)';   
    }
    
    $orderBy = " ORDER BY $sortField1 $sortOrderBy ";  
    
    
     
    // Student Academic Details
    $totalArray = $studentReportsManager->getAdmittedStudentCount($condition);  
    $recordArray = $studentReportsManager->getAdmittedStudentList($condition,$orderBy,$limit);
    $cnt = count($recordArray);
    
    $studentIds ="0";
    for($i=0;$i<count($recordArray);$i++) {
      $studentIds .=",".$recordArray[$i]['studentId']; 
    }
    
    $condition1 =" AND studentId IN ($studentIds) ";
    $academicArray = $studentReportsManager->getStudentAcademicList($condition1);
    $academicCount = count($academicArray);

    for($i=0;$i<$cnt;$i++) {
       $studentId = $recordArray[$i]['studentId'];        
       
       if($recordArray[$i]['dateOfBirth']!=NOT_APPLICABLE_STRING) {
          $recordArray[$i]['dateOfBirth'] = UtilityManager::formatDate($recordArray[$i]['dateOfBirth']);
       }
       
       if($recordArray[$i]['compExamRollNo']=='') {
          $recordArray[$i]['compExamRollNo'] = NOT_APPLICABLE_STRING;
       }
       
       if($recordArray[$i]['compExamRank']=='') {
          $recordArray[$i]['compExamRank'] = NOT_APPLICABLE_STRING;
       }
       
       if($recordArray[$i]['permAddress']=='') {
          $recordArray[$i]['permAddress'] = NOT_APPLICABLE_STRING;
       }
       
       $contact1='';
       $contact2=''; 
       if($recordArray[$i]['studentPhone'] != NOT_APPLICABLE_STRING) {
          $contact1 = $recordArray[$i]['studentPhone'];      
       }
        
       if($recordArray[$i]['studentMobileNo'] != NOT_APPLICABLE_STRING) {
          $contact2 = $recordArray[$i]['studentMobileNo'];      
       }
       
       if(trim($contact1)==trim($contact2)) {
          $contact = $contact1;   
       }
       else {
          $contact = $contact1;   
          if($contact!='') { 
            if($contact2!='') { 
              $contact .=", ".$contact2;
            }
            else {
              $contact .=$contact2;  
            }
          }
          else {
              $contact =$contact2;  
          }
       }
       
       if(trim($contact)==null || trim($contact)=='') {
         $contact = NOT_APPLICABLE_STRING;  
       }
       
       $recordArray[$i]['contactNo'] = $contact;
       
       $find=0;       
       $acd1= NOT_APPLICABLE_STRING; 
       $acd2= NOT_APPLICABLE_STRING;
       for($k=0; $k<$academicCount; $k++) {
          $aStudentId = $academicArray[$k]['studentId'];
          $per = $academicArray[$k]['previousPercentage'];  
          $examClass = $academicArray[$k]['previousClassId'];
          $per = $academicArray[$j]['previousPercentage'];    
          
          if($aStudentId == $studentId) {
            $find=1;
            if($examClass==1) { 
              $acd1 = $per;
            } 
            else
            if($examClass==2) { 
              $acd2 = $per;
            } 
            break;
          }  
          else if($find==1) {
            break;  
          }
       }
       $valueArray = array_merge(array('srNo' => ($records+$i+1), 'acd1' => $acd1,'acd2' => $acd1),$recordArray[$i]);
       
       
       if(trim($json_val)=='') {                      
         $json_val = json_encode($valueArray);
       }                                                                          
       else{
         $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['cnt'].'","page":"'.$page.'","info" : ['.$json_val.']}';     
