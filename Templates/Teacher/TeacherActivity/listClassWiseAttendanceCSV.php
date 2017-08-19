<?php 
//This file is used as csv version for display attendance
//
// Author :Rajeev Aggarwal
// Created on : 08-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $studentManager = TeacherManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
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
      $genName=" OR CONCAT(TRIM(s.firstName),' ',TRIM(s.lastName)) LIKE '".add_slashes($genName)."%'";
  }  
  
  return $genName;
}
    
	//used to parse csv data
	function parseCSVComments($comments) {
		$comments = str_replace('"', '""', $comments);
		$comments = str_ireplace('<br/>', "\n", $comments);
		if(eregi(",", $comments) or eregi("\n", $comments)) {
		
			return '"'.$comments.'"'; 
		} 
		else{
			
			return $comments; 
		}
	}

    /////////////////////////
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy"; 

    //$conditions=($REQUEST_DATA['studentRollNo']!="" ? " AND s.rollNo='".$REQUEST_DATA['studentRollNo']."'" : " AND c.classId=".$REQUEST_DATA['class']." AND su.subjectId=".$REQUEST_DATA['subject']." AND g.groupId=".$REQUEST_DATA['group']);
    $conditions= " AND c.classId=".$REQUEST_DATA['class']." AND su.subjectId=".$REQUEST_DATA['subject'];
    if(trim($REQUEST_DATA['group'])!='' and trim($REQUEST_DATA['group'])!=-1){
        $conditions .=" AND g.groupId=".$REQUEST_DATA['group'];
    }
    if(trim($REQUEST_DATA['studentRollNo'])!=''){
        $conditions .=' AND ( s.rollNo LIKE "'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'%" OR s.universityRollNo LIKE "'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'%" )';
    }
    
    if(trim($REQUEST_DATA['studentName'])!=""){
        $parsedName=parseName(trim($REQUEST_DATA['studentName']));    //parse the name for compatibality
        $conditions .=" AND (
                                  TRIM(s.firstName) LIKE '".add_slashes(trim($REQUEST_DATA['studentName']))."%' 
                                  OR 
                                  TRIM(s.lastName) LIKE '".add_slashes(trim($REQUEST_DATA['studentName']))."%'
                                  $parsedName
                               )"; 
    }
    
    $conditions .=" AND att.fromDate >='".$REQUEST_DATA['fromDate']."' AND att.toDate <='".$REQUEST_DATA['toDate']."'";
    
    if(trim($REQUEST_DATA['reportType'])==1){
        $conditions .=' AND att.employeeId='.$sessionHandler->getSessionVariable('EmployeeId');
    }

	 
    $recordArray = $studentManager->getClassWiseAttendanceList($conditions,$orderBy);
    $cnt = count($recordArray);

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
       if($recordArray[$i]['percentage']==''){
         $recordArray[$i]['percentage']='0.00'; 
       }
       if($recordArray[$i]['shortAttendance']==-1){
           $recordArray[$i]['shortAttendance']='Yes';
       } 
       else{
           $recordArray[$i]['shortAttendance']='No';
       }
       $valueArray[] = array_merge(array('srNo' => ($i+1) ),$recordArray[$i]);
    }

	$csvData = '';
	$csvData .= "Sr, Name,Roll No.,Univ Roll No.,Subject,Delivered,Attended,Percentage,Att. Short ?  \n";
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].',  '.parseCSVComments($record['studentName']).',  '.parseCSVComments($record['rollNo']).',  '.parseCSVComments($record['universityRollNo']).',  '.parseCSVComments($record['subjectCode']).',  '.parseCSVComments($record['delivered']).','.parseCSVComments($record['attended']).','.parseCSVComments($record['percentage']).','.parseCSVComments($record['shortAttendance']);
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="displayAttendanceList.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: listClassWiseAttendanceCSV.php $
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 12/04/10   Time: 18:58
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Updated "Display Attendance" report
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 28/01/10   Time: 11:31
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added "Univ. Roll No." column in student list display
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 5/09/09    Time: 18:09
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done bug fixing.
//bug ids---
//00001449,00001445
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 29/06/09   Time: 11:32
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added name & roll no wise search in display attendance and marks
//display in teacher login
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 26/06/09   Time: 10:37
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done GNIMT enhancements as on 26.06.2009
//
//*****************  Version 2  *****************
//User: Administrator Date: 30/05/09   Time: 12:55
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected bugs -----issues.doc.
//Bug ids-1,2,3
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/08/09    Time: 1:04p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//added print and export to csv functionality
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/08/09    Time: 12:53p
//Created in $/SnS/Templates/Teacher/TeacherActivity
//added print and export to CSV functionality
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/08/09    Time: 12:14p
//Created in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Intial checkin
?>