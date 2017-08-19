<?php
//--------------------------------------------------------
//This file returns the array of of Test Time Period
// Author :Ipta Thakur
// Created on : 2-11-2011
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0); 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','GradeTranscriptReport');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
    global $sessionHandler;

    require_once(MODEL_PATH . "/TranscriptReportManager.inc.php");
    $transcriptReportManager = TranscriptReportManager::getInstance();    
    
    require_once(MODEL_PATH . "/GradeTranscriptReportManager.inc.php");
    $gradeTranscriptReportManager = GradeTranscriptReportManager::getInstance();
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance(); 
    

    $roleId = $sessionHandler->getSessionVariable('RoleId');
    if($roleId=='4') {
       UtilityManager::ifStudentNotLoggedIn(); 
    }
    else if($roleId=='3') {
        UtilityManager::ifParentNotLoggedIn();   
    }
    else {
       UtilityManager::ifNotLoggedIn(); 
    }
    UtilityManager::headerNoCache();
    
    global $sessionHandler;
    $gradeCard = $sessionHandler->getSessionVariable('ST_ALLOW_GRADE_CARD');  
    
    if($roleId==3 || $roleId==4) {
      $studentId = $sessionHandler->getSessionVariable('StudentId');  
      $condition = " WHERE studentId = '".$studentId."'";
    }
    else { 	
      $rollNo = add_slashes(trim($REQUEST_DATA['rollNo']));  
      $condition = " WHERE universityRollNo LIKE '".$rollNo."' OR rollNo LIKE '".$rollNo."'";
    }
     
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    $orderBy = " $sortField $sortOrderBy";    
     
     if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",ss.studentId, studentName)';
     }
     else if ($sortField == 'rollNo') {
        $sortField1 = 'IF(IFNULL(rollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",ss.studentId, rollNo)';
     } 
     else if ($sortField == 'universityRollNo') {
        $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",ss.studentId, universityRollNo)';
     }
    
     $orderBy = " $sortField1 $sortOrderBy";    
     
     // $recordsPerPage = RECORDS_PER_PAGE;
     $recordsPerPage = 5000; 
     // to limit records per page    
     $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
     $records    = ($page-1)* $recordsPerPage;
     $limit      = ' LIMIT '.$records.','.$recordsPerPage;
     
     //this function will format number to $decimal places
     function formatTotal($input,$decimal=2){
        return number_format($input,1,'.','');
     }

    
     $studentId='';
    $fatherName=NOT_APPLICABLE_STRING;
    $studentArray = $transcriptReportManager->getSingleField("student","CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName,studentId,IFNULL(fatherName,'') AS fatherName", $condition); 
    if(is_array($studentArray) && count($studentArray)>0 ) { 
       $studentId = $studentArray[0]['studentId'];  
       if($studentArray[0]['fatherName']!='') {
         $fatherName = $studentArray[0]['fatherName']; 
       }
       $studentName = $studentArray[0]['studentName']; 
    } 
     
    if($studentId=='') {
      echo INVALID_ROLL_NO;
      die;  
    } 


    $studentGradesArray = $transcriptReportManager->getScStudentGradeDetails($studentId,'',$orderBy1);
    $cgpa = NOT_APPLICABLE_STRING;
    $gradePointSum = 0;
    $totalCredits = 0;
    $valueArray = array();
  
    $cnt = count($studentGradesArray);
    for($i=0;$i<$cnt;$i++) {
        $updateExamType = $studentGradesArray[$i]['updatedExamType'];
        $gradePoints = $studentGradesArray[$i]['gradePoints'];
        $credits = $studentGradesArray[$i]['credits'];
        $totalCredits += $credits;
        $gradePointSum += $gradePoints * $credits;
        $subjectCode = $studentGradesArray[$i]['subjectCode'];
        if($updateExamType=='1') {
          $subjectCode .="<font color='red'>*</font>";
        }
        $studentGradesArray[$i]['subjectCode'] = $subjectCode;
        
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentGradesArray[$i]);
        
        if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
        }
        else {
          $json_val .= ','.json_encode($valueArray);     
        }
    }
    
    
    echo '{"StudentName":"'.$studentName.'","Id":"'.$studentId.'","FatherName":"'.$fatherName.'","CurrentCGPA":"'.$cgpa.'","sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info":['.$json_val.']}';
 $finalCGPAArray = $gradeTranscriptReportManager->getStudentFinalCGPAGrade($conditionCGPAGrade); 
        $valueArray[$i]['finalGrade'] = "I";  
        if(is_array($finalCGPAArray) && count($finalCGPAArray)>0 ) { 
          $valueArray[$i]['finalGrade'] = $finalCGPAArray[0]['grade'];                         
        }
        $result = "<td valign='top' class='padding_top' align='center'>".$valueArray[$i]['finalPoint']."</td>
                   <td valign='top' class='padding_top' align='center'>".$valueArray[$i]['finalGrade']."</td>";
                   
       $classWiseTotal += ($valueArray[$i]['finalPoint']*$credit);            
       return $result;

    
?> 
                         
