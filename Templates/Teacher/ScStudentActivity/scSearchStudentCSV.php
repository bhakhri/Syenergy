<?php 
//This file is used as printing version for payment history.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
        $conditionsArray[] = " a.rollNo LIKE '$rollNo%' ";
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
	
	$recordArray = $studentManager->getStudentListPrint($conditions,$orderBy,'');
	 

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => $i+1),$recordArray[$i]);
    }

	$csvData = '';
	$csvData .= "Sr, Student Name, Roll No, City, Email, Degree, Period, Mobile \n";
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].','.$record['studentName'].','.$record['rollNo'].','.$record['cityName'].','.$record['studentEmail'].','.$record['classId'].','.$record['studyPeriod'].','.$record['studentMobileNo'];
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="allStudentList.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: scSearchStudentCSV.php $
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