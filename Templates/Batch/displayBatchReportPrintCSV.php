 <?php 
//This file is used as export to excel version for display batch.
//
// Author :Jaineesh
// Created on : 04.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    define('MODULE','BatchMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();

	require_once(MODEL_PATH . "/BatchManager.inc.php");
    $batchManager = BatchManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (bat.batchName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR studentId LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bat.startDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bat.endDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bat.batchYear LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'batchName';
    
    $orderBy = " $sortField $sortOrderBy";

	$batchRecordArray = $batchManager->getBatchList($filter,'',$orderBy);
	$recordCount = count($batchRecordArray);
    $search = $search==''? "All":$search;
    $valueArray = array();
    $csvData ='';
    $csvData ='Search By,'.$search."\n";
    $csvData .="\n";               
    $csvData .="Sr. No.,Name,Student,Start Date,End Date,Batch Year";
    $csvData .="\n";
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $batchRecordArray[$i]['startDate']=UtilityManager::formatDate(strip_slashes($batchRecordArray[$i]['startDate']));
		  $batchRecordArray[$i]['endDate']=UtilityManager::formatDate(strip_slashes($batchRecordArray[$i]['endDate']));
		  $csvData .= $batchRecordArray[$i]['batchName'].",";
          $csvData .= $batchRecordArray[$i]['studentId'].",";          
		  $csvData .= $batchRecordArray[$i]['startDate'].",";
		  $csvData .= $batchRecordArray[$i]['endDate'].",";
		  $csvData .= $batchRecordArray[$i]['batchYear'].",";
		  $csvData .= "\n";
  }          
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="batchReport.csv"'); 
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>