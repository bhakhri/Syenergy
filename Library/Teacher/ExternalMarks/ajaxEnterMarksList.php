<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of student marks
//
// Author : Dipanjan Bbhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','MannualExternalMarks');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    $page  = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records = ($page-1)* 1000;
    $limit = ' LIMIT '.$records.',1000';
    
    $filter="";
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';

	$sortField = $REQUEST_DATA['sortField'];

    if($sortField=="studentName"){
        $sortField2="studentName";
    }
    elseif($sortField=="rollNo"){
        $sortField2="numericRollNo";
    }
    elseif($sortField=="universityRollNo"){
        $sortField2=" isLeet,IF(universityRollNo='' OR universityRollNo IS NULL,IF(rollNo='' OR rollNo IS NULL,studentName,rollNo),universityRollNo)";
    }
    elseif($sortField=="marks"){
        $sortField2="tm.marksScored";
    }
	else {
        $sortField2=" isLeet,IF(universityRollNo='' OR universityRollNo IS NULL,IF(rollNo='' OR rollNo IS NULL,studentName,rollNo),universityRollNo)";
	}


	$orderBy = " $sortField2 $sortOrderBy";
    
    
 
    $classId = trim($REQUEST_DATA['class']);
    $subjectId = trim($REQUEST_DATA['subject']);
    $groupId = trim($REQUEST_DATA['group']);
    $testTypeId = trim($REQUEST_DATA['testType']);
    
    
    if($classId=='') {
      $classId='0';  
    }
    
    if($subjectId=='') {
      $subjectId='0';  
    }
    
    if($groupId=='') {
      $groupId='0';  
    }

    
    if($testTypeId=='') {
      $testTypeId='0';  
    }

    
    $conditions = " AND sg.classId='".$classId."'";
    $studentTotalRecordArray = $studentManager->getStudentListExternal($conditions,$orderBy,$subjectId);
    $totalRecords = count($studentTotalRecordArray);
    
    $conditions = " AND sg.classId='".$classId."'";
    $studentRecordArray = $studentManager->getStudentListExternal($conditions,$orderBy,$subjectId);
    
    //$str="<input type='hidden' id='max' name='stu[]' value='$studentRecordArray[$i]['maxMarks']' >";
    for($i=0;$i<count($studentRecordArray);$i++) {
       $id =  $studentRecordArray[$i]['studentId'];  
         
       $mks =  $studentRecordArray[$i]['marksScored'];  
       $max=$studentRecordArray[$i]['maxMarks'];       
       $str = "<input type='hidden' id='stu".$id."' name='stu[]' value='".$id."' >
 	       <input type='hidden' id='max' name='max' value='$max'>
               <input class='inputbox' type='text' id='extMks".$id."' name='extMks[]' value='$mks' >";
               
       $valueArray = array_merge(array('srNo' => ($records+$i+1),  
                                       'externalMarks' => $str,
                                      ), $studentRecordArray[$i]);   
       if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
       }
       else {
         $json_val .= ','.json_encode($valueArray);
       }
    }
   
   
 
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}';

?>
