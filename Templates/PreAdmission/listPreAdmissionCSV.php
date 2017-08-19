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
    require_once(MODEL_PATH . "/SubjectManager.inc.php");
    $SubjectManager = SubjectManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    $conditionsArray = array();
    $qryString = "";

    define('MODULE','Subject');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    
    //to parse csv values    
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

    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName';
    $orderBy = " $sortField $sortOrderBy";   

    
    
    

    $record = $SubjectManager->getSubjectList($filter,'',$orderBy);
    $cnt = count($record);
    
    $search = $REQUEST_DATA['searchbox'];    
    
    $csvData = '';   
    $csvData .= "Search By:, ".parseCSVComments($search)."\n";
    $csvData .= "#,Sr No,Status,Admin Camp,School,Program,Priority 1,Priority 2,Priority 3,Priority 4";
    
    $csvData .= "Name,Father's Name,DOB,Sex,Mob No,Father Mob No, Correspondance Address,State,PQ Deg,PQ %age,Q Deg,Q %age\n";
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
       // add stateId in actionId to populate edit/delete icons in User Interface 
       $csvData .= ($i+1).",".parseCSVComments($record[$i]['srNumber']).",".parseCSVComments($record[$i]['status']);
       $csvData .= ",".parseCSVComments($record[$i]['camp']).",".parseCSVComments($record[$i]['school']);
       $csvData .= ",".parseCSVComments($record[$i]['courses']).",".parseCSVComments($record[$i]['Prefrence1']);
       $csvData .= ",".parseCSVComments($record[$i]['Prefrence2']).",".parseCSVComments($record[$i]['Prefrence3']);
       $csvData .= ",".parseCSVComments($record[$i]['Prefrence4']).",".parseCSVComments($record[$i]['studentName']);
       $csvData .= ",".parseCSVComments($record[$i]['fatherName']).",".parseCSVComments($record[$i]['dateofBirth']);
       $csvData .= ",".parseCSVComments($record[$i]['gender']).",".parseCSVComments($record[$i]['studentMobile']);
       $csvData .= ",".parseCSVComments($record[$i]['fatherMobile']).",".parseCSVComments($record[$i]['correspondeceAddress1']);
       $csvData .= ",".parseCSVComments($record[$i]['correspondenceStates']).",".parseCSVComments($record[$i]['PQDeg']);
       $csvData .= ",".parseCSVComments($record[$i]['PQ %age']);
       $csvData .=",".parseCSVComments($record[$i]['Q Deg']).",".parseCSVComments($record[$i]['Q %age'])." \n";
    }
    
    if($cnt==0){
        $csvData .=",".NO_DATA_FOUND;
    }

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a CSV
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	// It will be called testType.csv
	header('Content-Disposition: attachment;  filename="subjectList.csv"');

	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: subjectCSV.php $
//
//*****************  Version 10  *****************
//User: Parveen      Date: 4/21/10    Time: 10:37a
//Updated in $/LeapCC/Templates/Subject
//“Attendance” and “Marks” fields display
//
//*****************  Version 9  *****************
//User: Parveen      Date: 10/20/09   Time: 11:16a
//Updated in $/LeapCC/Templates/Subject
//search by condition display 
//
//*****************  Version 8  *****************
//User: Parveen      Date: 10/20/09   Time: 10:14a
//Updated in $/LeapCC/Templates/Subject
//formatting & alignment updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 10/06/09   Time: 11:26a
//Updated in $/LeapCC/Templates/Subject
//search condition checks updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/23/09    Time: 3:27p
//Updated in $/LeapCC/Templates/Subject
//hasAttendance, hasMarks filed added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/28/09    Time: 3:15p
//Updated in $/LeapCC/Templates/Subject
//condition & formating updated 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Templates/Subject
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/27/09    Time: 6:23p
//Updated in $/LeapCC/Templates/Subject
//bug fix (csvData print format bracket updated)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/20/09    Time: 1:55p
//Updated in $/LeapCC/Templates/Subject
//new enhancement categoryId (link subject_category table) new field
//added 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:58
//Created in $/LeapCC/Templates/Subject
//Added "Print" and "Export to excell" in subject and subjectType modules
?>
