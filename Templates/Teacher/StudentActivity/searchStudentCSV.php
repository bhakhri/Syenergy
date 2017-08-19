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
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $studentManager = TeacherManager::getInstance();

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
      $genName=" OR CONCAT(TRIM(s.firstName),' ',TRIM(s.lastName)) LIKE '".$genName."%'";
  }  
  
  return $genName;
}

    /////////////////////////
    
    $filter="";

    if(trim($REQUEST_DATA['subject'])!=""){
        $filter =$filter." AND sc.subjectId=".trim($REQUEST_DATA['subject']); 
    }
    if(trim($REQUEST_DATA['group'])!=""){
        $filter =$filter." AND g.groupId=".trim($REQUEST_DATA['group']); 
    }
    if(trim($REQUEST_DATA['class'])!=""){
        $filter =$filter." AND c.classId=".trim($REQUEST_DATA['class']); 
    }
    if(trim($REQUEST_DATA['studentNameFilter'])!=""){
        $parsedName=parseName(trim($REQUEST_DATA['studentNameFilter']));    //parse the name for compatibality
        $filter =$filter." AND (
                                  TRIM(s.firstName) LIKE '".add_slashes(trim($REQUEST_DATA['studentNameFilter']))."%' 
                                  OR 
                                  TRIM(s.lastName) LIKE '".add_slashes(trim($REQUEST_DATA['studentNameFilter']))."%'
                                  $parsedName
                               )"; 
    }
    if(trim($REQUEST_DATA['studentRollNo'])!=""){
        $filter =$filter." AND s.rollNo LIKE '".add_slashes(trim($REQUEST_DATA['studentRollNo']))."%'"; 
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $studentRecordArray = $studentManager->getSearchStudentList($filter,' ',$orderBy);
    $cnt = count($studentRecordArray);
    
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
        $valueArray[] = array_merge(array('srNo' => ($studentRecordArrays+$i+1) ),$studentRecordArray[$i]);
   }

	$csvData = '';
	$csvData .= "Sr, Name, Roll No.,Univ. Roll No., City,Class, Degree, Branch, Batch \n";
	foreach($valueArray as $studentRecordArray) {
		$csvData .= $studentRecordArray['srNo'].','.$studentRecordArray['studentName'].','.$studentRecordArray['rollNo'].','.$studentRecordArray['universityRollNo'].','.$studentRecordArray['cityName'].','.$studentRecordArray['className'].','.$studentRecordArray['degreeAbbr'].','.$studentRecordArray['branchCode'].','.$studentRecordArray['batchName'];
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
 

// $History: searchStudentCSV.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 28/01/10   Time: 11:31
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Added "Univ. Roll No." column in student list display
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 13/07/09   Time: 11:59
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Added "Class" column in student display and corrected session changing
//problem
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/12/08   Time: 12:00
//Created in $/LeapCC/Templates/Teacher/StudentActivity
//Added print & csv functionality
?>