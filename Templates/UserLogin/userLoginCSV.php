<?php 
//This file is used as csv version for TestType.
//
// Author :Gurkeerat Sidhu
// Created on : 20.2.2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();

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
 return $comments.chr(160); 
 }
 
}

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roleUserName';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////

    $startDate = $REQUEST_DATA['startDate'];
    $toDate = $REQUEST_DATA['toDate'];

    $filter = "DATE_FORMAT(dateTimeIn,'%Y-%m-%d') BETWEEN '$startDate' AND '$toDate'";
    if(trim($REQUEST_DATA['listView'])==1){ 
    $userLoginRecordArray = $dashboardManager->getStudentNotLoggedinList($filter,' ',$orderBy);
    $cnt = count($userLoginRecordArray);
    $reportFormat=3;
    $report= "Student not loggedIn";
    $fileName="studentNotLoggedInReport.csv";
    }
    else{
    $userLoginRecordArray = $dashboardManager->getUserLoginList($filter,' ',$orderBy);
    $cnt = count($userLoginRecordArray);
     if(trim($REQUEST_DATA['reportFormat'])==1){
         $reportFormat=1;
         $report= "Consolidated";
    }
    else if(trim($REQUEST_DATA['reportFormat'])==0){
        $reportFormat=0;
        $report= "Detailed"; 
    }
    $fileName="userLoginReport.csv";
    }
    for($i=0;$i<$cnt;$i++) {
            if($userLoginRecordArray[$i]['roleUserName']=='' AND trim($REQUEST_DATA['listView'])!=1){
            $userLoginRecordArray[$i]['roleUserName']="Administrator";
        }
        $valueArray[] = array_merge(array( 'srNo' => ($records+$i+1) ),$userLoginRecordArray[$i]); 
    }
    $csvData = '';
    if($reportFormat==3){
        $csvData .= "#, Name, Roll No., Class \n";
        foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['roleUserName']).','.parseCSVComments($record['rollNo']).','.parseCSVComments($record['className']);
        $csvData .= "\n";
        }
    }
    else{
    if($reportFormat==1){
        $csvData .= "#, Name, UserName, Role, Date&Time, Count \n";
        foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['roleUserName']).','.parseCSVComments($record['userName']).','.parseCSVComments($record['roleName']).','.parseCSVComments($record['dateTimeIn']).','.parseCSVComments($record['userCount']);
        $csvData .= "\n";
        }
    }
    else{
        $csvData .= "#, Name, UserName, Role, Date, Time, Count \n";
        foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['roleUserName']).','.parseCSVComments($record['userName']).','.parseCSVComments($record['roleName']).','.parseCSVComments($record['loggedInTime']).','.parseCSVComments($record['timeIn']).','.parseCSVComments($record['userCount']);
        $csvData .= "\n";
            }
        }
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="userLoginReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: userLoginCSV.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 2/22/10    Time: 5:49p
//Created in $/LeapCC/Templates/UserLogin
//created file under user login report
//


?>