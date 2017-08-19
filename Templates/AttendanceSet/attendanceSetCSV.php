<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/AttendanceSetManager.inc.php");
    $attendanceSetManager = AttendanceSetManager::getInstance();
    define('MODULE','AttendanceSetMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();


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

    /// Search filter /////  
    if(UtilityManager::notEmpty(trim($REQUEST_DATA['searchbox']))) {
       $search = trim($REQUEST_DATA['searchbox']);
       if(strtoupper($search)=='PERCENTAGES'){
           $filter =' WHERE at.evaluationCriteriaId='.PERCENTAGES;
       }
       else if(strtoupper($search)=='SLABS'){
           $filter =' WHERE at.evaluationCriteriaId='.SLABS;
       }
       if($filter!=''){
          $filter .= ' OR ( at.attendanceSetName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
       }
       else{
          $filter = ' WHERE ( at.attendanceSetName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
       }

    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'attendanceSetName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray      = $attendanceSetManager->getTotalAttendanceSet($filter);
    $record  = $attendanceSetManager->getAttendanceList($filter,'',$orderBy);
    $cnt = count($record);
    
    $csvData = '';
    $csvData .= "Search By,".parseCSVComments($REQUEST_DATA['searchbox']);   
    $csvData .= "\n";
    $csvData .= "#,Set Name,Criteria \n";
    for($i=0;$i<$cnt;$i++) {
       if($record[$i]['evaluationCriteriaId']==PERCENTAGES){
           $record[$i]['evaluationCriteriaId']='Percentages';
       }
       else if($record[$i]['evaluationCriteriaId']==SLABS){
           $record[$i]['evaluationCriteriaId']='Slabs';
       }
       else{
           $record[$i]['evaluationCriteriaId']=NOT_APPLICABLE_STRING;
       } 
       $csvData .= ($i+1).",".parseCSVComments($record[$i]['attendanceSetName']).",".parseCSVComments($record[$i]['evaluationCriteriaId']); 
       $csvData .= "\n";
    }       

    if($i==0) {
      $csvData .= ",No Data Found";   
    }
    
    ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="AttendanceSetReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
    
?>