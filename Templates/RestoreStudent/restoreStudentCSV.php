<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/RestoreStudentManager.inc.php");
    $restoreStudentManager = RestoreStudentManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    $conditionsArray = array();
    $qryString = "";
    

//to parse csv values    
function parseCSVComments($comments) {
 $comments = str_replace('"', '""', $comments);
 $comments = str_ireplace('<br/>', "\n", $comments);
 if(eregi(",", $comments) or eregi("\n", $comments)) {
   return '"'.$comments.'"'; 
 } 
 else {
 return $comments; 
 }
 
}

    //-----------------------------------------------------------------------------------
//Purpose: To parse the user submitted value in a space seperated string
//Author:Dipanjan Bhattacharjee
//Date:06.11.2008
//-----------------------------------------------------------------------------------
function parseName($value){
    $name=explode(' ',$value);
    $genName="";
    $len= count($name);
    if($len > 0){
      for($i=0;$i<$len;$i++){
          if(trim($name[$i])!=""){
              if($genName!=""){
                  $genName =$genName ." ".$name[$i];
              }
             else{
                 $genName =$name[$i];
             } 
          }
      }
    }
  if($genName!=""){
      $genName=" OR CONCAT(TRIM(s.firstName),' ',TRIM(s.lastName)) LIKE '".$genName."%'";
  }  
  
  return $genName;
}


    foreach($REQUEST_DATA as $key => $values) {
        $$key = $values;
    }
    $conditionsArray = array();
    
    if (!empty($rollNo)) {
        $conditionsArray[] = " s.rollNo LIKE '$rollNo%' ";
        $searchCrieria .="<b>Roll No</b>:$rollNo&nbsp;";
    }
    if (!empty($studentName)) {
         $parsedName=parseName(trim($studentName));    //parse the name for compatibality
        //$conditionsArray[] = " CONCAT(s.firstName, ' ', s.lastName) like '%$studentName%' ";
        $conditionsArray[] = " (
                                  TRIM(s.firstName) LIKE '".add_slashes(trim($studentName))."%' 
                                  OR 
                                  TRIM(s.lastName) LIKE '".add_slashes(trim($studentName))."%'
                                  $parsedName
                               )";
    }
    
    if (!empty($degreeId)) {
        $conditionsArray[] = " cl.degreeId in ($degreeId) ";
    }
    
    if (!empty($branchId)) {
        $conditionsArray[] = " cl.branchId in ($branchId) ";
    }
    
    if (!empty($periodicityId)) {
        $conditionsArray[] = " cl.studyPeriodId IN ($periodicityId) ";
    }
    
    if (!empty($course)) {
     $conditionsArray[] = " s.studentId IN (SELECT DISTINCT(studentId) FROM quarantine_student WHERE classId IN (SELECT DISTINCT(classId) FROM subject_to_class WHERE subjectId IN($course))) ";
    }
    
    if (!empty($groupId)) {
        $conditionsArray[] = " s.studentId IN (SELECT studentId from student_groups WHERE groupId IN ($groupId)) ";
    }
    
    if (!empty($fromDateA) and $fromDateA != '--') {
        $fromDateArr = explode('-',$fromDateA);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " s.dateOfAdmission >= '$thisDate' ";
        }
    }

    if (!empty($toDateA) and $toDateA != '--') {
        $toDateArr = explode('-',$toDateA);
        $toDateAM = intval($toDateArr[0]);
        $toDateAD = intval($toDateArr[1]);
        $toDateAY = intval($toDateArr[2]);
        if (false !== checkdate($toDateAM, $toDateAD, $toDateAY)) {
            $thisDate = $toDateAY.'-'.$toDateAM.'-'.$toDateAD;
            $conditionsArray[] = " s.dateOfAdmission <= '$thisDate' ";
        }
    }

    if (!empty($fromDateD) and $fromDateD != '--') {
        $fromDateArr = explode('-',$fromDateD);
        $fromDateDM = intval($fromDateArr[0]);
        $fromDateDD = intval($fromDateArr[1]);
        $fromDateDY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateDM, $fromDateDD, $fromDateDY)) {
            $thisDate = $fromDateDY.'-'.$fromDateDM.'-'.$fromDateDD;
            $thisDate2=$thisDate;
            $conditionsArray[] = " s.dateOfBirth >= '$thisDate' ";
        }
    }

    if (!empty($toDateD) and $toDateD != '--') {
        $toDateArr = explode('-',$toDateD);
        $toDateDM = intval($toDateArr[0]);
        $toDateDD = intval($toDateArr[1]);
        $toDateDY = intval($toDateArr[2]);
        if (false !== checkdate($toDateDM, $toDateDD, $toDateDY)) {
            $thisDate = $toDateDY.'-'.$toDateDM.'-'.$toDateDD;
            $conditionsArray[] = " s.dateOfBirth <= '$thisDate' ";
        }
    }

    if (!empty($gender)) {
       if($gender=='M' or $gender=='F'){ 
        $conditionsArray[] = " s.studentGender = '$gender' ";
        $gender1 = $gender=='M' ? "Male" : "Female";
       }
    }
    if ($categoryId!='') {
        $conditionsArray[] = " s.managementCategory = $categoryId ";
    }

    if (!empty($quotaId)) {
        $conditionsArray[] = " s.quotaId IN ($quotaId) ";
        $quotaText = $REQUEST_DATA['quotaText'];
    }
    
    if (!empty($hostelId)) {
        $conditionsArray[] = " s.hostelId IN ('$hostelId') ";
    }
    if (!empty($busStopId)) {
        $conditionsArray[] = " s.busStopId IN ('$busStopId') ";
    }
    if (!empty($busRouteId)) {
        $conditionsArray[] = " s.busRouteId IN ($busRouteId) ";
    }
    if (!empty($cityId)) {
        $conditionsArray[] = " s.permCityId IN ($cityId) ";
    }
    if (!empty($stateId)) {
        $conditionsArray[] = " s.permStateId IN ($stateId) ";
    }
    if (!empty($countryId)) {
        $conditionsArray[] = " s.permCountryId IN ($countryId) ";
    }
    if (!empty($universityId)) {
        $conditionsArray[] = " cl.universityId IN ($universityId) ";
    }
    if (!empty($instituteId)) {
        $conditionsArray[] = " cl.instituteId IN ($instituteId) ";
    }
    if (!empty($bloodGroup)) {
        $conditionsArray[] = " s.studentBloodGroup = $bloodGroup";
    }

    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy";


    $recordArray = $restoreStudentManager->getStudentList($conditions,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        if($recordArray[$i]['dateOfBirth']!=NOT_APPLICABLE_STRING){
         $recordArray[$i]['dateOfBirth']=UtilityManager::formatDate($recordArray[$i]['dateOfBirth']); 
        }
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$csvData = '';
    //$csvData .= "Sr, Name, Roll No., Regn. No., Univ. Roll No., Class, DOB, Father \n";
    $csvData .= "Sr, Name, Roll No., Regn. No., Univ. Roll No., Class, DOB \n";
    foreach($valueArray as $record) {
        //$csvData .= $record['srNo'].', '.parseCSVComments($record['studentName']).', '.parseCSVComments($record['rollNo']).', '.parseCSVComments($record['regNo']).', '.parseCSVComments($record['universityRollNo']).', '.parseCSVComments($record['className']).', '.$record['dateOfBirth'].','.$record['fatherName'];
        $csvData .= $record['srNo'].', '.parseCSVComments($record['studentName']).', '.parseCSVComments($record['rollNo']).', '.parseCSVComments($record['regNo']).', '.parseCSVComments($record['universityRollNo']).', '.parseCSVComments($record['className']).', '.$record['dateOfBirth'];
        $csvData .= "\n";
    }
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="restoreStudentReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: restoreStudentCSV.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 27/08/09   Time: 11:34
//Updated in $/LeapCC/Templates/RestoreStudent
//Done bug fixing.
//bug ids---
//00001283,00001294,00001297
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 24/07/09   Time: 14:59
//Created in $/LeapCC/Templates/RestoreStudent
//Done bug fixing.
//Bug ids----0000648,0000650,0000667,0000651,0000676,0000649,0000652
?>