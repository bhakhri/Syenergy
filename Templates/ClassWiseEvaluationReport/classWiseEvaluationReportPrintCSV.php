<?php 
//  This File contains Class Wise Evaluation Report's Print
//
// Author : Aditi Miglani
// Created on : 09 Aug 2011
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ClassWiseEvaluationReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
require_once(MODEL_PATH . "/ClassWiseEvaluationReportManager.inc.php");
$classWiseEvaluationReportManager = ClassWiseEvaluationReportManager::getInstance();
   
    //--------------------------------------------------------       
    //Purpose:To escape any newline or comma present in data
    //Author: Dipanjan Bhattacharee
    //Date: 31.10.2008
    // Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------   
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
   
   
    $timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
    $classId= $REQUEST_DATA['classId'];
    
    
    if($timeTableLabelId=='') {
      $timeTableLabelId=0;  
    }
    
    if($classId=='') {
      $classId=0;  
    }

    
    
    $csvData='';
    $csvHead='';   
    
    // Findout Time Table Name
    $timeNameArray = $classWiseEvaluationReportManager->getSingleField('time_table_labels', 'labelName', "WHERE timeTableLabelId  = $timeTableLabelId");
    $timeTableName = $timeNameArray[0]['labelName'];
    if($timeTableName=='') {
      $timeTableName = NOT_APPLICABLE_STRING;  
    }
   
    // Findout Class Name
    if($classId != '') {   
      $classNameArray = $classWiseEvaluationReportManager->getSingleField('class', 'className', "WHERE classId  = $classId");
      $className = $classNameArray[0]['className'];
    }
    else {
      $className = NOT_APPLICABLE_STRING;      
    }
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    $csvData .= parseCSVComments($className)."\nTime Table,".parseCSVComments($timeTableName)."\nAs On,".parseCSVComments($formattedDate);
    $csvData .= "\n";
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'universityRollNo';

        
    if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",sg.studentId, studentName)';
    }
    else if ($sortField == 'rollNo') {
        $sortField1 = 'IF(IFNULL(rollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",sg.studentId, rollNo)';
    }
    else if ($sortField == 'universityRollNo') {
        $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",sg.studentId, universityRollNo)';
    }
    else {
       $sortField == 'studentName';
       $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",sg.studentId, studentName)';
    }
    
    $orderBy = " $sortField1 $sortOrderBy";    
    
    
    //$condition = " AND stc.classId = '$classId' AND sub.hasMarks = 1 AND sub.hasAttendance =1 ";
    //$orderSubject = " sub.subjectTypeId, sub.subjectCode, sub.subjectId";
    //$recordArray = $classWiseEvaluationReportManager->classwiseSubjects($condition,$orderSubject);
    //$recordCount = count($recordArray);
    
    // Fetch Subject Type Wise List
    $filter= " DISTINCT c.classId,su.subjectTypeId,st.subjectTypeName AS subjectTypeName, COUNT(DISTINCT st.subjectTypeName) AS cnt, COUNT(DISTINCT su.subjectName) AS cnt1 ";
    $groupBy = "GROUP BY c.classId, su.subjectTypeId ";  
    $orderSubjectBy = " classId, subjectTypeId ";
    $cond  = " AND su.hasMarks = 1 AND su.hasAttendance =1 AND tt.timeTableLabelId = $timeTableLabelId AND c.classId = $classId";
    $recordArray1 =  $classWiseEvaluationReportManager->getAllSubjectAndSubjectTypes($cond, $filter, $groupBy, $orderSubjectBy);  
    $recordCount1 = count($recordArray1);

    if($recordCount1 >0) {   
       $csvData .= "Sr. No.,Univ. Roll No.,Roll No.,Student Name"; 
                    
        for($i=0; $i<$recordCount1; $i++) {    
           $csvData .=",".parseCSVComments($recordArray1[$i]['subjectTypeName']);  
           if($recordArray1[$i]['cnt1']>=2) {
             $colspanval = $recordArray1[$i]['cnt1'];  
           }
           else {
             $colspanval = 0;     
           }
           for($j=0;$j<($colspanval)-1;$j++) {
             $csvData .=",";  
           }
        } 
        $csvData .=",Total";
    }
    $csvData .="\n";
        
    $cnt = 0;
    $colSpanCount = 4; 
    $csvData .=",,,";    
    // Fetch Subject Name
    $filter1 = "";
    $filter= " DISTINCT su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, st.subjectTypeName, c.classId ";
    $groupBy = "";
    $orderSubjectBy = "classId, subjectTypeId, subjectCode ";
    $recordArray =  $classWiseEvaluationReportManager->getAllSubjectAndSubjectTypes($cond, $filter, $groupBy,  $orderSubjectBy );   
    $recordCount = count($recordArray); 
    if($recordCount > 0 ) {
        $tableHead .= "<tr>";
          for($i=0; $i<$recordCount; $i++) {  
              $subjectId = $recordArray[$i]['subjectId']; 
              $csvData .=",".parseCSVComments($recordArray[$i]['subjectCode']);
              $colSpanCount = $colSpanCount + 1;
           }
        $csvData .="\n";  
        
        $condition = " AND sg.classId = '$classId' ";
        $studentArray2 = $classWiseEvaluationReportManager->getStudentExternalMarks($condition,$orderBy);  
        $cnt = count($studentArray2);       
        
        $condition = " AND ttm.classId = '$classId' ";      
        $findMarksArray = $classWiseEvaluationReportManager->getStudentTotalExternalMarks($condition);  
        $findMarksTotal = count($findMarksArray);       
    }
    
    if($cnt > 0) {
      $scount=0;
      for($s=0; $s<$cnt; $s++) {      
           $studentId  = $studentArray2[$s]['studentId'];
           $csvData .= parseCSVComments($s+1).",".parseCSVComments($studentArray2[$s]['universityRollNo']);
           $csvData .= ",".parseCSVComments($studentArray2[$s]['rollNo']).",".parseCSVComments($studentArray2[$s]['studentName']);    
                             
              $find=0;               
             // Fetch Subject Information     
             for($k=0;$k<$findMarksTotal; $k++) {
                if($findMarksArray[$k]['studentId']==$studentId) {
                  $find=1;
                  break; 
                }  
             }
             if($find==1) {
                 $total=0; 
                 for($i=0; $i<$recordCount; $i++) {
                    $subjectId = $recordArray[$i]['subjectId'];
                    $tstudentId = $findMarksArray[$k]['studentId'];  
                    $tsubjectId = $findMarksArray[$k]['subjectId'];
                    if($tstudentId==$studentId) {  
                       if($subjectId==$tsubjectId) {
                         $csvData .=",".parseCSVComments($findMarksArray[$k]['marksScored']);    
                         $total=$total+$findMarksArray[$k]['marksScored']; 
                         $k++;   
                       }
                       else {
                         $csvData .=",".parseCSVComments(NOT_APPLICABLE_STRING);    
                       }
                    }
                    else {
                       $csvData .=",".parseCSVComments(NOT_APPLICABLE_STRING);    
                    }
                 }
                 if($find==1) {
                    $csvData .=",".parseCSVComments($total);      
                 }
                 else {
                    $csvData .=",".parseCSVComments(NOT_APPLICABLE_STRING);        
                 }
             }
             else {
               for($i=0; $i<$recordCount; $i++) {  
                   $csvData .=",".parseCSVComments(NOT_APPLICABLE_STRING);      
               }  
               if($recordCount>0) {
                  $csvData .=",".parseCSVComments(NOT_APPLICABLE_STRING);     
               }
             }
          $scount = $scount +1;
          $csvData .= "\n";
       }
       
       UtilityManager::makeCSV($csvData,'StudentExternalMarksReport.csv');      
       die;
   }
   else {
     $csvData .= "\n";  
     $csvData .= "No Data Found";  
   }
   
   UtilityManager::makeCSV($csvData,'StudentExternalMarksReport.csv');   
   die;
 ?>
