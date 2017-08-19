<?php 
//This file is used as printing CSV version for subject topic
//
// Author :Parveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);  
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH."/EmployeeReportsManager.inc.php");   
    
    require_once(MODEL_PATH . "/SubjectTopicManager.inc.php"); 
    $subjecttopicManager = SubjectTopicManager::getInstance();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();   
    
    //--------------------------------------------------------       
    //Purpose:To escape any newline or comma present in data
    //--------------------------------------------------------   
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
            return '"'.$comments.'"'; 
         } 
         else {
            return chr(160).$comments; 
         }
    }
   
    $filter = "";
    $search = "";

    global $sessionHandler;
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId'); 
    if($employeeId=='') {
      $employeeId = 0;  
    }
    
    // Findout Time Table Name
    $employeeNameArray = $studentManager->getSingleField('employee', "employeeName,employeeCode ", "WHERE employeeId  = $employeeId");
    $employeeName = $employeeNameArray[0]['employeeName'];
    $employeeCode = $employeeNameArray[0]['employeeCode']; 
    if($employeeName=='') {
      $employeeName = NOT_APPLICABLE_STRING;  
    }
    if($employeeCode=='') {
      $employeeCode = NOT_APPLICABLE_STRING;  
    }
    
    // Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' AND (sub.subjectName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                       sub.subjectCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                       st.topic LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                       st.topicAbbr LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    
    $cond = " AND tt.employeeId = $employeeId";      
    $subjectArray = EmployeeReportsManager::getInstance()->getActiveTimeTableSubject($cond);
    
    $subjectId=0;
    for($i=0;$i<count($subjectArray);$i++) {
      $subjectId .=",".$subjectArray[$i]['subjectId']; 
    }
    $filter .= " AND sub.subjectId IN ($subjectId) ";
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName';
    
    $orderBy = " $sortField $sortOrderBy";

    $record = $subjecttopicManager->getSubjectTopicList($filter,'',$orderBy);
    $cnt = count($record);
    
    $csvData = '';
	$csvData .= "Search By:".parseCSVComments($REQUEST_DATA['searchbox']);
    $csvData .= parseCSVComments($employeeName)." (".parseCSVComments($employeeCode).")\n";
    $csvData .= "\n";
    $csvData .= "Sr. No., Subject Name, Subject Code, Topic, Abbr. \n";
    $cnt = count($record);
	if($cnt==0){
       $csvData .=",".NO_DATA_FOUND;
    }
    for($i=0;$i<$cnt;$i++) {
       $csvData .= ($i+1).",".parseCSVComments($record[$i]['subjectName']);
       $csvData .= ",".parseCSVComments($record[$i]['subjectCode']).",".parseCSVComments($record[$i]['topic']);
       $csvData .= ",".parseCSVComments($record[$i]['topicAbbr'])."\n";
    }

ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="bulkSubjectTopicReport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;    
?>
    
<?php 
// $History: listSubjectTopicPrintCSV.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/13/10    Time: 2:09p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//validation format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/13/10    Time: 1:04p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/13/10    Time: 12:30p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 5  *****************
//User: Parveen      Date: 10/20/09   Time: 10:45a
//Updated in $/LeapCC/Templates/SubjectTopic
//search condition parameter added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Templates/SubjectTopic
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/01/09    Time: 3:22p
//Updated in $/LeapCC/Templates/SubjectTopic
//formatting & spelling correct
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/30/09    Time: 10:38a
//Updated in $/LeapCC/Templates/SubjectTopic
//search condition subject code update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/20/09    Time: 2:26p
//Created in $/LeapCC/Templates/SubjectTopic
//print & csv file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/20/09    Time: 1:21p
//Created in $/Leap/Source/Templates/SubjectTopic
//print & CSV files added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/19/09    Time: 4:01p
//Created in $/Leap/Source/Templates/ScTimeTable
//inital checkin
//


?>
