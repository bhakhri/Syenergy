<?php
//--------------------------------------------------------
//This file returns the array of of Test Time Period
// Author :Ipta Thakur
// Created on : 07-11-2011
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    set_time_limit(0); 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FinalTranscriptReportManager');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
    global $sessionHandler;

    require_once(MODEL_PATH . "/FinalTranscriptReportManager.inc.php");
    $finalTranscriptReportManager = FinalTranscriptReportManager::getInstance();    
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance(); 
    
    global $sessionHandler;
    $gradeCard = $sessionHandler->getSessionVariable('ST_ALLOW_GRADE_CARD');  
    
    $rollNo = add_slashes(trim($REQUEST_DATA['rollNo']));  
    $condition = " WHERE universityRollNo LIKE '".$rollNo."' OR rollNo LIKE '".$rollNo."'";
    
     
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
    

     // Fetch Subject Detail
     $subjectCondition = "AND d.classId IN 
                         (SELECT 
                              DISTINCT cc.classId 
                          FROM 
                              class cc 
                          WHERE 
                              cc.branchId = '$branchId' AND cc.batchId = '$batchId' AND cc.degreeId = '$degreeId')"; 
    $subjectArray=$finalTranscriptReportManager->getClassSubjectsWithOtherSubjects($subjectCondition);  



   // Fetch Student Detail
    $conditions = " AND e.branchId = '$branchId' AND b.batchId = '$batchId' AND d.degreeId = '$degreeId'";
    if($rollNo!='') {
       $conditions .= " AND a.rollNo LIKE '$rollNo%' "; 
    }
    $conditions .=" GROUP BY ss.studentId";
    $studentArray=  $finalTranscriptReportManager->getAllDetailsStudentList($conditions, $orderBy, $limit);
    $cnt = count($studentArray);
    
    $rowspan='5';
    
    $tableClass='';
    for($i=0;$i<count($subjectCountArray);$i++) {
      $className = $subjectCountArray[$i]['className'];  
      $subjectCount = $subjectCountArray[$i]['subjectCount'];
      $colspan= $subjectCount*2;    
      $tableClass .="<td colspan='$colspan' class='searchhead_text' align='center'>
                        <strong><nobr>$className</nobr></strong>
                     </td>
                     <td rowspan='$rowspan' class='searchhead_text' align='center'>
                        <strong><nobr>Total</nobr></strong>
                     </td> ";                     
    }
    

    
     $studentId='';
    $fatherName=NOT_APPLICABLE_STRING;
    $studentArray = $finalTranscriptReportManager->getSingleField("student","CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName,studentId,IFNULL(fatherName,'') AS fatherName", $condition); 
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


    $studentGradesArray = $finalTranscriptReportManager->getScStudentGradeDetails($studentId,'',$orderBy1);
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
    
        $finalCGPAArray = $finalTranscriptReportManager->getStudentFinalCGPAGrade($conditionCGPAGrade); 
        $valueArray[$i]['finalGrade'] = "I";  
        if(is_array($finalCGPAArray) && count($finalCGPAArray)>0 ) { 
          $valueArray[$i]['finalGrade'] = $finalCGPAArray[0]['grade'];                         
        }
        $result = "<td valign='top' class='padding_top' align='center'>".$valueArray[$i]['finalPoint']."</td>
                   <td valign='top' class='padding_top' align='center'>".$valueArray[$i]['finalGrade']."</td>";
                   
       $classWiseTotal += ($valueArray[$i]['finalPoint']*$credit);            
       return $result;

    
    echo '{"StudentName":"'.$studentName.'","Id":"'.$studentId.'","FatherName":"'.$fatherName.'","CurrentCGPA":"'.$cgpa.'","result":"'.$result.'""sortOrderBy":"'.$sortOrderBy.'","sortField":"'.    $sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info":['.$json_val.']}';
 

    
?> 
                         
