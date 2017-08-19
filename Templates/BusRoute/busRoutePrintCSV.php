<?php 
//This file is used as csv version for bus route.
//
// Author : Jaineesh
// Created on : 22-10-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/BusRouteManager.inc.php");
    $busRouteManager = BusRouteManager::getInstance();
    //to parse csv values    
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
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
        $filter = ' WHERE (br.routeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                          br.routeCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                          b.busNo  LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'routeName';
    
    $orderBy = " br.$sortField $sortOrderBy"; 
    
    
    $recordArray = $busRouteManager->getBusRouteList($filter,$orderBy,'');

	$recordCount = count($recordArray);
	
	$valueArray = array();
    $csvData ='';
    $csvData="Sr No.,Vehicle Route Name,No. of students,Vehicle Route Code,Bus No.";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
	$csvData .= ($i+1).",";
	$csvData .= parseCSVComments($recordArray[$i]['routeName']).",";
	$csvData .= parseCSVComments($recordArray[$i]['studentCount']).","; 
	$csvData .= parseCSVComments($recordArray[$i]['routeCode']).",";
	$csvData .= parseCSVComments($recordArray[$i]['busNo']).",";
	$csvData .= "\n";
  }
  if($recordCount==0){
        $csvData .=",".NO_DATA_FOUND;
    }  
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'BusRouteReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $

?>

