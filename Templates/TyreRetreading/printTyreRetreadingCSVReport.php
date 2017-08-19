 <?php 
//This file is used as printing version for display Tyre Retreading
//
// Author :Jaineesh
// Created on : 05-Jan-10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php

    require_once(MODEL_PATH . "/TyreRetreadingManager.inc.php");
    $tyreRetreadingManager = TyreRetreadingManager::getInstance();
    
	$search = $REQUEST_DATA['searchbox'];
	/// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (tm.tyreNumber LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR b.busNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR tr.retreadingDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR tr.totalRun LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'tyreNumber';
    
    $orderBy = " $sortField $sortOrderBy";

	$tyreRetreadingRecordArray = $tyreRetreadingManager->getTyreRetreadingList($filter,$limit,$orderBy);
    $cnt = count($tyreRetreadingRecordArray);

	$valueArray = array();

    $csvData ='';
    $csvData="Sr No.,Tyre No.,Registration No.,Retreading Date,Reading";
    $csvData .="\n";
    
    for($i=0;$i<$cnt;$i++) {
		  $tyreRetreadingRecordArray[$i]['retreadingDate'] = UtilityManager::formatDate($tyreRetreadingRecordArray[$i]['retreadingDate']);
		  $csvData .= ($i+1).",";
		  $csvData .= $tyreRetreadingRecordArray[$i]['tyreNumber'].",";
		  $csvData .= $tyreRetreadingRecordArray[$i]['busNo'].",";
		  $csvData .= $tyreRetreadingRecordArray[$i]['retreadingDate'].",";
		  $csvData .= $tyreRetreadingRecordArray[$i]['totalRun'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'TyreRetreadingReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>