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
    require_once(MODEL_PATH . "/SubjectTypeManager.inc.php");
    $SubjectTypeManager = SubjectTypeManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    $conditionsArray = array();
    $qryString = "";
       
    define('MODULE','SubjectTypesMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    //used to parse csv data
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
           return chr(160).$comments; 
         }
    } 

    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
       $filter = ' AND (sub.subjectTypeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sub.subjectTypeCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR uni.universityName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    
    //$conditions = '';
    //if (count($conditionsArray) > 0) {
        //$conditions = ' AND '.implode(' AND ',$conditionsArray);
    //}
             

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectTypeName';

    //$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy = " $sortField $sortOrderBy";       


    
    $recordArray = $SubjectTypeManager->getSubjectTypeList($filter,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$csvData = "SearchBy :".$search."\n";
	$csvData .= "#, Subject Type, Abbr., University \n";
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].','.parseCSVComments($record['subjectTypeName']).','.parseCSVComments($record['subjectTypeCode']).','.parseCSVComments($record['universityName']);
		$csvData .= "\n";
	}
    if(count($valueArray)==0){
       $csvData .= ",".NO_DATA_FOUND; 
    }
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a CSV
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	// It will be called testType.csv
	header('Content-Disposition: attachment;  filename="subjectTypeList.csv"');

	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: subjectTypeCSV.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 10/15/09   Time: 12:46p
//Updated in $/LeapCC/Templates/SubjectType
//sorting condition updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/24/09    Time: 11:34a
//Updated in $/LeapCC/Templates/SubjectType
//search in all fields any format type
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/21/09    Time: 5:40p
//Updated in $/LeapCC/Templates/SubjectType
//formatting & role permission added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Templates/SubjectType
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/01/09    Time: 12:56p
//Updated in $/LeapCC/Templates/SubjectType
//list formatting & required field validation added
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:58
//Created in $/LeapCC/Templates/SubjectType
//Added "Print" and "Export to excell" in subject and subjectType modules
?>