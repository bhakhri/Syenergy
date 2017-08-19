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
    require_once(MODEL_PATH . "/DegreeManager.inc.php");
    $degreeManager = DegreeManager::getInstance();

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
 return $comments; 
 }
 
}

    //search filter
    $search = $REQUEST_DATA['searchbox'];
       if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (dg.degreeName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR dg.degreeCode LIKE 
	   "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR studentId LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR dg.degreeAbbr LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';  
	 // $filter = ' AND (dg.degreeName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR dg.degreeCode LIKE 
	 // "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR dg.degreeAbbr LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';  
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'degreeName';
    
     
    if ($sortField == "studentId") {
		 $orderBy = " $sortField $sortOrderBy";
    }
	 else {
		 $orderBy = " dg.$sortField $sortOrderBy";
	 }

    ////////////
    $recordArray = $degreeManager->getDegreeList($filter,'',$orderBy);
    $cnt = count($recordArray);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$csvData = "Search By : ".$search."\n";
    $csvData .= "#, Degree Name, Degree Code, Abbr.,Student Count \n";
	foreach($valueArray as $record) {
    $csvData .= $record['srNo'].', '.parseCSVComments($record['degreeName']).', '.parseCSVComments($record['degreeCode']).', '.parseCSVComments($record['degreeAbbr']).','.parseCSVComments($record['studentId']);
		$csvData .= "\n";
	}
    
    if($cnt==0){
       $csvData .=",".NO_DATA_FOUND;
    }
	
    ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="degree.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: degreeCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 31/07/09   Time: 14:38
//Created in $/LeapCC/Templates/Degree
//Done bug fixing.
//bug ids---0000803,0000804
?>