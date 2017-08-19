<?php 
//This file is used as printing version for payment history.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	//require_once(MODEL_PATH . "/ScStudentManager.inc.php");
    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $studentManager = ScTeacherManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    
//-----------------------------------------------------------------------------------
//Purpose: To parse the user submitted value in a space seperated string
//Author:Dipanjan Bhattacharjee
//Date:19.09.2008
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
      $genName=" OR CONCAT(TRIM(a.firstName),' ',TRIM(a.lastName)) LIKE '".$genName."%'";
  }  
  
  return $genName;
}

    //subject and section
    $subject = $REQUEST_DATA['subject'];
    $secs = $REQUEST_DATA['section'];
    if (!empty($subject) and !empty($secs)) {
        $conditionsArray[] = " a.studentId IN 
                                (
                                 SELECT DISTINCT(studentId) FROM sc_student_section_subject WHERE subjectId IN ($subject)
                                 AND sectionId IN ($secs) 
                                ) ";
        //$qryString.= "&subjectId=$secs";
    }
    
    
   
    //class
    $classId = $REQUEST_DATA['classes'];
    if (!empty($classId)) {
        $conditionsArray[] = " a.classId = $classId ";
        //$qryString .= "&classId=$classId";
    }
    else{
        $conditionsArray[] = " a.classId IN (
                                              SELECT DISTINCT c.classId
                                              FROM sc_student_section_subject ssc,class c
                                              WHERE 
                                              c.classId=ssc.classId  AND c.isActive=1
                                              AND ssc.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." 
                                              AND ssc.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                              AND ssc.sectionId=$secs
                                              AND ssc.subjectId=$subject
                                            )";
    }
    
    //Roll Number
    $rollNo = $REQUEST_DATA['studentRollNo'];
    if (!empty($rollNo)) {
        $conditionsArray[] = " a.rollNo LIKE  '$rollNo%' ";
        //$qryString.= "&rollNo=$rollNo";
    }

    //Student Name
    $studentName = $REQUEST_DATA['studentNameFilter'];
    if (!empty($studentName)) {
        $parsedName=parseName(trim($studentName)); 
        $conditionsArray[] = " (
                                  TRIM(a.firstName) LIKE '".add_slashes(trim($studentName))."%' 
                                  OR 
                                  TRIM(a.lastName) LIKE '".add_slashes(trim($studentName))."%'
                                  $parsedName
                               )";
                               
        //$qryString.= "&studentName=$studentName";
    }
    
	$conditions = '';
	if (count($conditionsArray) > 0) {
		$conditions = ' AND '.implode(' AND ',$conditionsArray);
	}

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'firstName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy="$sortField $sortOrderBy"; 

	if($sortField=="studyPeriod")
		$orderBy= "b.studyPeriodId $sortOrderBy"; 

	/* END: search filter */
	$totalArray = $studentManager->getTotalStudentPrint($conditions);
    $recordArray = $studentManager->getStudentListPrint($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Student List Report');
    $reportManager->setReportInformation("Subject: $REQUEST_DATA[subjectName] Section: $REQUEST_DATA[sectionName] Class: $REQUEST_DATA[className] Name:$REQUEST_DATA[studentNameFilter] Roll No:$REQUEST_DATA[studentRollNo]");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width="3%"', "align='center' ");
	$reportTableHead['studentName']			=	array('Name','width=14% align="left"', 'align="left"');
	$reportTableHead['rollNo']			    =	array('Roll No','width="10%" align="left" ', 'align="left"');
	$reportTableHead['cityName']			=	array('City','width="9%" align="left"', 'align="left"');
	$reportTableHead['studentEmail']		=	array('Email','width="7%" align="left"','align="left"');
	$reportTableHead['classId']				=	array('Degree','width="17%" align="left"', 'align="left"');
	$reportTableHead['studyPeriod']		    =	array('Period','width="4%" align="right"', 'align="right"');
	$reportTableHead['studentMobileNo']		=	array('Mobile','width="9%" align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: scSearchStudentPrint.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/ScStudentActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/12/08   Time: 4:29p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//Corrected  "Class" condition
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 10/21/08   Time: 5:11p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//Added code for partial roll no searching and displaying search criteria
//in reports
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/20/08   Time: 4:12p
//Created in $/Leap/Source/Templates/Teacher/ScStudentActivity
?>