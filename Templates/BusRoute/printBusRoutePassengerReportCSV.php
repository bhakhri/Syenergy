 <?php 
//This file is used as printing version for display Designation
//
// Author :Jaineesh
// Created on : 04.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php

    require_once(MODEL_PATH . "/BusRouteManager.inc.php");
    $busRouteManager = BusRouteManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);

	$busRouteId = trim($REQUEST_DATA['busRouteId']);

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (designationName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR designationCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'routeCode';

    $orderBy = " $sortField $sortOrderBy";

	$routeConditions = "WHERE br.busRouteId = ".$busRouteId;
	$busRouteArray = $busRouteManager->getBusRouteList($routeConditions,'',$orderBy);
	$routeCode = $busRouteArray[0]['routeCode'];

    $conditions = "AND bp.busRouteId = ".$busRouteId;
	$recordArray = $busRouteManager->getPassengerRouteList($conditions);

	//$employeeConditions = "AND ebp.busRouteId = ".$busRouteId;
	//$recordEmployeeArray = $busRouteManager->getEmployeeRouteList($employeeConditions);

	$cnt = count($recordArray);
	//$employeeCount = count($recordEmployeeArray);

    $valueArray = array();

    $csvData ='';
    $csvData="#,Role,Name,Roll No./Employee Code,Institute Name";
    $csvData .="\n";
    
    for($i=0;$i<$cnt;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $recordArray[$i]['roleName'].",";
		  $csvData .= $recordArray[$i]['studentName'].",";
		  $csvData .= $recordArray[$i]['rollNo'].",";
		  $csvData .= $recordArray[$i]['instituteName'].",";
		  $csvData .= "\n";
	}

	/*for($j=0;$j<$employeeCount;$j++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $recordEmployeeArray[$j]['roleName'].",";
		  $csvData .= $recordEmployeeArray[$j]['studentName'].",";
		  $csvData .= $recordEmployeeArray[$j]['rollNo'].",";
		  $csvData .= $recordEmployeeArray[$j]['instituteName'].",";
		  $csvData .= "\n";
	}*/
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'BusRoutePassengerReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>