<?php
//-------------------------------------------------------
// Purpose: To store the records of student grades from the database, pagination
// functionality
//
// Author : Parveen Sharma
// Created on : 15-12-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    ini_set('MEMORY_LIMIT','5000M'); 
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler; 
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==3){
      UtilityManager::ifParentNotLoggedIn(true);  
    }
    else if($roleId==4){
      UtilityManager::ifStudentNotLoggedIn(true);
    }
    else {
      UtilityManager::ifNotLoggedIn(true);  
    }
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");     
    $studentManager =  StudentManager::getInstance();   
    
    if($roleId==3 || $roleId==4) {
      $studentId = $sessionHandler->getSessionVariable('StudentId');
    }
    else {
      $studentId = $REQUEST_DATA['studentId'];
    }
	$classId  = $REQUEST_DATA['rClassId'];

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 500;
    $limit      = ' LIMIT '.$records.',500';
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : ' classId';
    
    $sortField = " periodName, subjectCode";
    $orderBy = " $sortField $sortOrderBy";

    //$cgpaArray = $studentManager->getScStudentCGPA($studentId);
  
    $gradeIDeclareResult = $sessionHandler->getSessionVariable('GRADE_I_FORMAT_ALLOW'); 
    if($gradeIDeclareResult=='') {
      $gradeIDeclareResult='0';  
    }
    if($gradeIDeclareResult=='0') { 
      $studentExternalArray = $studentManager->getStudentExternalMarksCheck($studentId); 
    }
    
    $holdStudentArray = $studentInformationManager->getHoldStudentsData($studentId);
    $holdStudentClassId='';
    for($i=0;$i<count($holdStudentArray);$i++) {
       if($holdStudentArray[$i]['holdGrades']=='1') { 
          if($holdStudentClassId!='') {
            $holdStudentClassId .=",";  
          }  
          $holdStudentClassId .= $holdStudentArray[$i]['classId']; 
       }
    }
    
    
    $where ='';
	$resourceRecordArray = $studentManager->getScStudentGradeDetailsNew($studentId,$classId,$orderBy,'',$where,$holdStudentClassId);
    $cntTotal = count($resourceRecordArray);
    $find='';
    $srNo=0;
    $ttStudyPeriodId='';
    for($i=0;$i<$cntTotal;$i++) {
       $updateExamType = $resourceRecordArray[$i]['updatedExamType'];
       $subjectCode = $resourceRecordArray[$i]['subjectCode'];
       $studyPeriodId = $resourceRecordArray[$i]['studyPeriodId'];
       $testType  = $resourceRecordArray[$i]['testType'];
    
       if($find=='') {   
         $ttStudentId = $resourceRecordArray[$i]['studentId'];  
         $ttClassId = $resourceRecordArray[$i]['classId'];  
         if($ttStudyPeriodId!='') {
           $ttStudyPeriodId .=",";  
         }
         $ttStudyPeriodId .= $studyPeriodId;
         
         $gpa=NOT_APPLICABLE_STRING;
         $cgpa=NOT_APPLICABLE_STRING;
         /*  for($j=0;$j<count($cgpaArray);$j++) {
               if($cgpaArray[$j]['studentId'] == $ttStudentId && $cgpaArray[$j]['classId'] == $ttClassId) {
                 $gpa= UtilityManager::decimalRoundUp($cgpaArray[$j]['gpa']);  
                 $cgpa= UtilityManager::decimalRoundUp($cgpaArray[$j]['cgpa']);  
                 break; 
               }            
             }
         */
         $condition=" s.studentId = '".$ttStudentId."' AND c.studyPeriodId = '".$studyPeriodId."' ";
         $recordArray = $studentManager->getStudentGPA($condition);
         $gpa = UtilityManager::decimalRoundUp($recordArray[0]['gpa1']);
          
         $condition=" s.studentId = '".$ttStudentId."' AND c.studyPeriodId IN (".$ttStudyPeriodId.") ";
         $recordArray = $studentManager->getStudentCGPA($condition);
         $cgpa = UtilityManager::decimalRoundUp($recordArray[0]['CGPA']);
         
         $find=='1';
       }
      
       if($updateExamType=='1') {
         $subjectCode .="<font color='red'>*</font>";
       }
       $resourceRecordArray[$i]['subjectCode']=$subjectCode;
       /*
       if($testType=='0') { 
          for($ee=0;$ee<count($studentExternalArray);$ee++) {
             if($studentExternalArray[$ee]['classId']==$resourceRecordArray[$i]['classId'] && 
                $studentExternalArray[$ee]['studentId']==$resourceRecordArray[$i]['studentId'] && 
                $studentExternalArray[$ee]['subjectId']==$resourceRecordArray[$i]['subjectId']) {
               $resourceRecordArray[$i]['gradeLabel'] = 'I'; 
               break;  
             } 
          }
       }
       */
       
       $valueArray = array_merge(array('srNo' => ($srNo+1),
                                       'subjectCode' => $resourceRecordArray[$i]['subjectCode'],
                                       'subjectName' => $resourceRecordArray[$i]['subjectName'],  
                                       'credits' => $resourceRecordArray[$i]['credits'],  
                                       'gradeLabel' => $resourceRecordArray[$i]['gradeLabel'],   
                                       'periodName' => $resourceRecordArray[$i]['periodName']   
                                       ));

      $srNo=$srNo+1;                                
      if(trim($json_val)=='') {
        $json_val = json_encode($valueArray);
      }
      else {
        $json_val .= ','.json_encode($valueArray);
      }                   
      if(($resourceRecordArray[$i+1]['classId'] != $ttClassId) || $i == ($cntTotal-1) ){
          $valueArray = array_merge(array('srNo' => '',
                                           'subjectCode' => '',
                                           'subjectName' => '',  
                                           'credits' => "",  
                                           'gradeLabel' => "<b>SGPA&nbsp;:&nbsp;&nbsp;$gpa&nbsp;&nbsp;</b>",   
                                           'periodName' => "<b>CGPA&nbsp;:&nbsp;&nbsp;$cgpa&nbsp;&nbsp;</b>"   
                                           ));
                                         
           if(trim($json_val)=='') {            
             $json_val = json_encode($valueArray);
           }
           else {
             $json_val .= ','.json_encode($valueArray);
           } 
           $find='';
           $srNo=0;  
      }            
   } 

    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cntTotal.'","page":"'.$page.'","info" : ['.$json_val.']}';

?>
