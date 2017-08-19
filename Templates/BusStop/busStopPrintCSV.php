<?php 
//This file is used as csv version for bus stop.
//
// Author : Jaineesh
// Created on : 22-10-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/BusStopManager.inc.php");
    $busStopManager = BusStopManager::getInstance();

	
    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        $conditions =' AND (bs.stopName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.stopAbbr LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.transportCharges LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bsr.routeCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'stopName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $busStopManager->getBusStopList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$recordCount = count($recordArray);
	
	$valueArray = array();
    $csvData ='';
    $csvData="Sr No.,Vehicle Stop,Abbr.,No. of students,Vehicle Route,Time,Transport Charges";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $recordArray[$i]['stopName'].",";
		  $csvData .= $recordArray[$i]['stopAbbr'].",";
          $csvData .= $recordArray[$i]['studentCount'].","; 
		  $csvData .= $recordArray[$i]['routeCode'].",";
		  $csvData .= $recordArray[$i]['scheduleTime'].",";
		  $csvData .= $recordArray[$i]['transportCharges'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'BusStopReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $

?>

