<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
     $searchCrieria .="<b>studentName</b>:$studentName&nbsp;";    
    }
    
    $degsText = "ALL";
    if (!empty($degreeId)) {
        $degsText = $REQUEST_DATA['degsText'];
        $conditionsArray[] = " cl.degreeId in ($degreeId) ";
    }
    $searchCrieria .="&nbsp;<b>Degree:</b>$degsText";
    
    $bransText="ALL";
    if (!empty($branchId)) {
        $bransText = $REQUEST_DATA['branText'];
        $conditionsArray[] = " cl.branchId in ($branchId) ";
    }
    $searchCrieria .="&nbsp;<b>Branches:</b>$bransText";
    
    if (!empty($periodicityId)) {
        $periodsText = $REQUEST_DATA['periodsText'];
        $conditionsArray[] = " cl.studyPeriodId IN ($periodicityId) ";
    }
    $searchCrieria .="&nbsp;<b>StudyPeriod:</b>$periodsText";
    
    $course = $REQUEST_DATA['courseId'];
    if (!empty($course)) {
     $courseText = $REQUEST_DATA['courseText'];   
     $conditionsArray[] = " s.studentId IN (SELECT DISTINCT(studentId) FROM quarantine_student WHERE classId IN (SELECT DISTINCT(classId) FROM subject_to_class WHERE subjectId IN($course))) ";
    }
    $searchCrieria .="&nbsp;<b>Subject:</b>$courseText";
    
    if (!empty($groupId)) {
        $groupText = $REQUEST_DATA['groupText'];
        $conditionsArray[] = " s.studentId IN (SELECT studentId from student_groups WHERE groupId IN ($groupId)) ";
    }
    $searchCrieria .="&nbsp;<b>Group:</b>$groupText";
    
    if (!empty($fromDateA) and $fromDateA != '--') {
        $fromDateArr = explode('-',$fromDateA);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " s.dateOfAdmission >= '$thisDate' ";
            $searchCrieria .="<b>Admission Date</b>:$thisDate";
        }
    }

    if (!empty($toDateA) and $toDateA != '--') {
        $toDateArr = explode('-',$toDateA);
        $toDateAM = intval($toDateArr[0]);
        $toDateAD = intval($toDateArr[1]);
        $toDateAY = intval($toDateArr[2]);
        if (false !== checkdate($toDateAM, $toDateAD, $toDateAY)) {
            if($thisDate!=''){
                $toDate = " To: ";
            }
            else{
               $toDate ="";
            }
            $thisDate = $toDateAY.'-'.$toDateAM.'-'.$toDateAD;
            $conditionsArray[] = " s.dateOfAdmission <= '$thisDate' ";
            $searchCrieria .="<b>$toDate</b>$thisDate";
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
            $searchCrieria .="<b>Date Of Birth</b>:$thisDate";
        }
    }

    if (!empty($toDateD) and $toDateD != '--') {
        $toDateArr = explode('-',$toDateD);
        $toDateDM = intval($toDateArr[0]);
        $toDateDD = intval($toDateArr[1]);
        $toDateDY = intval($toDateArr[2]);
        if (false !== checkdate($toDateDM, $toDateDD, $toDateDY)) {
            if($thisDate2!=''){
                $toDate = " To: ";
            }
            else{
               $toDate ="";
            }
            $thisDate = $toDateDY.'-'.$toDateDM.'-'.$toDateDD;
            $conditionsArray[] = " s.dateOfBirth <= '$thisDate' ";
            $searchCrieria .="<b>$toDate</b>$thisDate";
        }
    }

    if (!empty($gender)) {
       if($gender=='M' or $gender=='F'){ 
        $conditionsArray[] = " s.studentGender = '$gender' ";
        $gender1 = $gender=='M' ? "Male" : "Female";
        $searchCrieria .="<b>Gender</b>:$gender1&nbsp;";
       }
    }
    if ($categoryId!='') {
        $conditionsArray[] = " s.managementCategory = $categoryId ";
    }
    $quotaText ="ALL";
    if (!empty($quotaId)) {
        $conditionsArray[] = " s.quotaId IN ($quotaId) ";
        $quotaText = $REQUEST_DATA['quotaText'];
    }
    $searchCrieria .="&nbsp;<b>Category:</b>$quotaText";
    
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

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Restore Students Report');
    $reportManager->setReportInformation("For ".$searchCrieria." As On $formattedDate ");
	 
	$reportTableHead                        =    array();
    $reportTableHead['srNo']                =   array('#','width="2%" align="left"', "align='left' ");
    $reportTableHead['studentName']         =   array('Name','width=15% align="left"', 'align="left"');
    $reportTableHead['rollNo']              =   array('Roll No.','width=10% align="left"', 'align="left"');
    $reportTableHead['regNo']               =   array('Regn. No.','width=10% align="left"', 'align="left"');
    $reportTableHead['universityRollNo']    =   array('Univ. Roll No.','width=12% align="left"', 'align="left"');
    $reportTableHead['className']           =   array('Class','width=15% align="left"', 'align="left"');
    $reportTableHead['dateOfBirth']         =   array('DOB','width=15% align="center"', 'align="center"');
    //$reportTableHead['fatherName']          =   array('Father','width=15% align="left"', 'align="left"');
    //$reportTableHead['degreeAbbr']            =   array('Degree','width="8%" align="left" ', 'align="left"');
    //$reportTableHead['branchCode']          =   array('Branch','width="8%" align="left" ', 'align="left"');
    //$reportTableHead['periodName']          =   array('Study Period','width="8%" align="left" ', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: restoreStudentPrint.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 27/08/09   Time: 11:34
//Updated in $/LeapCC/Templates/RestoreStudent
//Done bug fixing.
//bug ids---
//00001283,00001294,00001297
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/08/09    Time: 12:16
//Updated in $/LeapCC/Templates/RestoreStudent
//corrected look and feel issues
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 24/07/09   Time: 14:59
//Created in $/LeapCC/Templates/RestoreStudent
//Done bug fixing.
//Bug ids----0000648,0000650,0000667,0000651,0000676,0000649,0000652
?>