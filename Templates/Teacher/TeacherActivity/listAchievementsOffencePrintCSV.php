<?php 
//  This File contains student Achievements/Offence Details CSV  
//
// Author :Parveen Sharma
// Created on : 29-05-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentManager = StudentReportsManager::getInstance();
    
    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();
    
    
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
    
    
    $condition = '';
    
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId'); //as teacherId is EmployeeId      
    
    $reportHead = '';
    
    if(trim($REQUEST_DATA['classId'])!=''){
       $condition .=" AND cls.classId=".$REQUEST_DATA['classId'];
       $reportHead .= 'Class,'.parseCSVComments($REQUEST_DATA['className']);
    }    
    
    if(trim($REQUEST_DATA['subjectId'])!=''){
        $condition .=" AND tt.subjectId=".$REQUEST_DATA['subjectId'];
        $reportHead .= ',Subject,'.parseCSVComments($REQUEST_DATA['subjectName']);
    }    
    
    if(trim($REQUEST_DATA['groupId'])!=''){
        $condition .=" AND gr.groupId=".$REQUEST_DATA['groupId'];  
        $reportHead .= ',Group,'.parseCSVComments($REQUEST_DATA['groupName']);
    }    
    
    if(trim($REQUEST_DATA['studentRollNo'])!=''){
        $condition .=' AND s.rollNo="'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'"';
        $reportHead .= ',Roll No'.parseCSVComments($REQUEST_DATA['studentRollNo']);   
    }    
    
    $condition .=" AND tt.employeeId=".$employeeId;
     
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'offenseName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
   
       
    $resourceRecordArray = $studentManager->getStudentOffenceList($condition,$orderBy,'');
    $cnt = count($resourceRecordArray);
    for($i=0;$i<$cnt;$i++) {
       // add stateId in actionId to populate edit/delete icons in User Interface
       if($i==0) {
         $csvData = 'Teacher Code,'.$resourceRecordArray[$i]['employeeCode'].',Teacher Name,'.$resourceRecordArray[$i]['employeeName']."\n"; 
         $csvData .= "Sr, Roll No, Name, Class, Group, Offense, Date, Reported By, Remarks \n";
       }
       $resourceRecordArray[$i]['offenseDate']=UtilityManager::formatDate($resourceRecordArray[$i]['offenseDate']); 
       $csvData .= ($i+1).','.parseCSVComments($resourceRecordArray[$i]['rollNo']).','.parseCSVComments($resourceRecordArray[$i]['studentName']).','.parseCSVComments($resourceRecordArray[$i]['className']);
       $csvData .= ','.parseCSVComments($resourceRecordArray[$i]['groupName']).','.parseCSVComments($resourceRecordArray[$i]['offenseName']).','.parseCSVComments($resourceRecordArray[$i]['offenseDate']);
       $csvData .= ','.parseCSVComments($resourceRecordArray[$i]['reportedBy']).','.parseCSVComments($resourceRecordArray[$i]['remarks']);  
       $csvData .= "\n";  
    }
   
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="StudentAchOffReport.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: listAchievementsOffencePrintCSV.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/29/09    Time: 4:42p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//employeeName, employee Code added in Report
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/29/09    Time: 4:15p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin 
//
 

?>