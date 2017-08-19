 <?php 
//This file is used as printing version for display countries.
//
// Author :Parveen Sharma
// Created on : 03.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentFeeConcessionMapping');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
    
    require_once(MODEL_PATH . "/StudentFeeConcessionMappingManager.inc.php");
    $studentFeeConcessionManager = StudentFeeConcessionMappingManager::getInstance();
    
     // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return $comments.chr(160); 
         }
    }
    
    $feeClassId = add_slashes($REQUEST_DATA['feeClassId']);
    $studentName = add_slashes($REQUEST_DATA['studentName']);
    $rollNo = add_slashes($REQUEST_DATA['rollNo']);
   
    $condition = '';
    if($rollNo!='') {
      $condition .= " AND (s.rollNo LIKE '$rollNo%' OR s.regNo LIKE '$rollNo%'  OR s.universityRollNo LIKE '$rollNo%') ";   
    }
    
    if($studentName!='') {
      $condition .= " AND (CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%') ";   
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = "$sortField $sortOrderBy";
  
  
    $csvData = '';
    $studentRecordArray = $studentFeeConcessionManager->getClassName($feeClassId);
    $csvData  .= "Class,".parseCSVComments($studentRecordArray[0]['className']);
    if($studentName!='') {
      $csvData  .= "\nName,".parseCSVComments($studentName);
    }
    if($rollNo!='') {
      $csvData  .= "\nRollNo.,".parseCSVComments($rollNo);
    }
    $csvData  .= "\n";
    $csvData  .= "#,Student Name,Roll No.,Univ. No.,Reg. No.,Fee Concession Category"; 
    $csvData  .= "\n";
    $studentRecordArray = $studentFeeConcessionManager->getStudentList($condition,'',$orderBy,$feeClassId);
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $studentId = $studentRecordArray[$i]['studentId'];
        $studentName = $studentRecordArray[$i]['studentName']; 
        $rollNo = $studentRecordArray[$i]['rollNo']; 
        $universityRollNo = $studentRecordArray[$i]['universityRollNo']; 
        $regNo = $studentRecordArray[$i]['regNo']; 
                  
        $condition = " AND fscm.studentId = $studentId AND fscm.classId = $feeClassId";
        $concessionCondition = " AND fh.isConsessionable = 1 ";
        $concessionArray = $studentFeeConcessionManager->getStudentConcessionCategoryList($condition,$concessionCondition);
        $concessionCategory = '';
        for($j=0;$j<count($concessionArray);$j++) {
           $name = $concessionArray[$j]['categoryName'];
           $chkClassId = $concessionArray[$j]['classId'];
           if($chkClassId!='') {
             if($concessionCategory != '') {  
               $concessionCategory .=", ";
             } 
             $concessionCategory .= $name; 
           }
        }
        if($concessionCategory == '') {  
          $concessionCategory = NOT_APPLICABLE_STRING;
        } 
        $csvData .= ($i+1).",".parseCSVComments($studentName).",".parseCSVComments($rollNo);
        $csvData .= ",".parseCSVComments($universityRollNo).",".parseCSVComments($regNo).",".parseCSVComments($concessionCategory);
        $csvData .= "\n";
    }

    if($i==0) {
      $csvData .=",,No Data Found";  
    }
    
UtilityManager::makeCSV($csvData,'studentConcessionMapping.csv');             
die;         
?>