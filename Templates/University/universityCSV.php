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
    require_once(MODEL_PATH . "/UniversityManager.inc.php");
    $universityManager = UniversityManager::getInstance();

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
    $conditions = ''; 
    if (!empty($search)) {
        /// Search filter /////  
       $conditions = ' AND (un.universityName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR un.universityCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR un.universityAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR un.universityWebsite LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR un.contactPerson LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR un.contactNumber LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR un.universityEmail LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'universityName';

    //$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $universityManager->getUniversityList($conditions,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$csvData = '';
	//$csvData .= "Sr, Name, Code, Abbr, Weightage.Amt, Weightage.Per, Eva.Criteria , University, Degree \n";
    $csvData .= "Sr, Name, Code, Abbr., Website, Contact Person, Ph. No., Email \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['universityName']).', '.parseCSVComments($record['universityCode']).', '.parseCSVComments($record['universityAbbr']).', '.parseCSVComments($record['universityWebsite']).', '.parseCSVComments($record['contactPerson']).', '.parseCSVComments($record['contactNumber']).', '.parseCSVComments($record['universityEmail']);
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a CSV
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	// It will be called testType.csv
	header('Content-Disposition: attachment;  filename="universityReport.csv"');

	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: universityCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/10/09   Time: 11:41
//Created in $/LeapCC/Templates/University
//Done bug fixing.
//bug ids---
//00001796,00001794,00001786,00001630
?>