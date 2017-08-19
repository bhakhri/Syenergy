<?php 
//This file is used as csv output of SMS Detail Report.
//
// Author :Parveen Sharma
// Created on : 27-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
    
      // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return chr(160).$comments; 
         }
    }

    
    $classId = $REQUEST_DATA['classId'];
    $quotaId = $REQUEST_DATA['quotaId'];
    
    // Findout Class Name
    $classNameArray = $studentReportsManager->getSingleField('class', 'className', "WHERE classId  = $classId");
    $className = $classNameArray[0]['className'];
    //$className2 = str_replace("-",' ',$className);
    
    if($classId=='') {
      $classId=0;  
    }
    
    $condition = " AND s.classId IN ($classId)";
    if($quotaId!='') {
      $condition .= " AND s.quotaId IN ($quotaId)";  
    }
   
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
    if ($sortField == 'quotaName') {
        $sortField1 = 'IF(IFNULL(quotaName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, quotaName)';
    }
    else
    if ($sortField == 'managementCategory') {
        $sortField1 = 'IF(IFNULL(managementCategory,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, managementCategory)';
    }
    else {
        $sortField = 'studentName';
        $sortField1 = 'IF(IFNULL(studentName,"")="" OR studentName = "'.NOT_APPLICABLE_STRING.'",s.studentId, studentName)';   
    }
    
    $orderBy = " ORDER BY $sortField1 $sortOrderBy ";  
    
    
     
    // Student Academic Details
    $recordArray = $studentReportsManager->getAdmittedStudentList($condition,$orderBy);
    $cnt = count($recordArray);
    
    $studentIds ="0";
    for($i=0;$i<count($recordArray);$i++) {
      $studentIds .=",".$recordArray[$i]['studentId']; 
    }
    
    $condition1 =" AND studentId IN ($studentIds) ";
    $academicArray = $studentReportsManager->getStudentAcademicList($condition1);
    $academicCount = count($academicArray);

    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);      
    $csvData  = "Class,".parseCSVComments($className)."\n";
    $csvData .= "AS On ".$formattedDate."\n";
    $csvData .= "Sr. No.,Name,Father's Name,DOB,CET/AIEEE Rank,CET/AIEEE Roll No., 10th (%age), 10+2 (%age), Gender, Category, Mgmt. Quota, Perm. Address, Contact Nos.\n";
    
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
       $csvData .= parseCSVComments($i+1).",".parseCSVComments($recordArray[$i]['studentName']).",".parseCSVComments($recordArray[$i]['fatherName']);
       $csvData .= ",".parseCSVComments($recordArray[$i]['dateOfBirth']).",".parseCSVComments($recordArray[$i]['compExamRank']);
       $csvData .= ",".parseCSVComments($recordArray[$i]['compExamRollNo']).",".parseCSVComments($acd1);
       $csvData .= ",".parseCSVComments($acd2).",".parseCSVComments($recordArray[$i]['studentGender']);
       $csvData .= ",".parseCSVComments($recordArray[$i]['quotaName1']).",".parseCSVComments($recordArray[$i]['managementCategory1']);
       $csvData .= ",".parseCSVComments($recordArray[$i]['permAddress']).",".parseCSVComments($recordArray[$i]['contactNo']);
       $csvData .= "\n";
    }
   
    if($cnt==0) { 
      $csvData .="\n,,,No Data Found";  
   }
   
   UtilityManager::makeCSV($csvData,'AdmittedStudentReport.csv'); 
   die;
   
?>