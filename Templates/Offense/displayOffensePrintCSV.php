 <?php 
//This file is used as printing version for display offense.
//
// Author :Jaineesh
// Created on : 05.10.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(MODEL_PATH . "/OffenseManager.inc.php");
    $offenseManager = OffenseManager::getInstance();
    
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
	
	$search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE ( offenseName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR offenseAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'offenseName';
    
    $orderBy = " $sortField $sortOrderBy";
	 
	$offenseArray = $offenseManager->getOffenseDetail($filter,'',$orderBy);
    
	$recordCount = count($offenseArray);

    $valueArray = array();

    $csvData ='';
    $csvData="Sr No.,Name,Abbr.,Desc.,Student Count";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $offenseArray[$i]['offenseName'].",";
		  $csvData .= parseCSVComments($offenseArray[$i]['offenseAbbr']).",";
		  $csvData .= parseCSVComments($offenseArray[$i]['offenseDesc']).",";
		  $csvData .= parseCSVComments($offenseArray[$i]['studentCount']).",";

		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'OffenseReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//Modify By satinder
?>