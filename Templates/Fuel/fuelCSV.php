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
    require_once(MODEL_PATH . "/FuelManager.inc.php");
    $tranportManager = FuelManager::getInstance();

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
    $search = trim($REQUEST_DATA['searchbox']);
    $conditions = ''; 
   if (!empty($search)) {
        $conditions = ' AND  ( trs.name LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.busNo LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  f.lastMilege LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  f.currentMilege LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  f.litres LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR f.amount LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'name';

    $orderBy=" $sortField $sortOrderBy"; 

    $recordArray = $tranportManager->getFuelList($conditions,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    
    for($i=0;$i<$cnt;$i++) {
        $recordArray[$i]['dated']=UtilityManager::formatDate($recordArray[$i]['dated']);
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$csvData = '';
    $csvData .= "Sr, Staff, Registration No., Date, Last Mileage, Current Mileage, Litres, Amount \n";
    foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['name']).', '.parseCSVComments($record['busNo']).', '.parseCSVComments($record['dated']).', '.parseCSVComments($record['lastMilege']).','.parseCSVComments($record['currentMilege']).','.parseCSVComments($record['litres']).','.parseCSVComments($record['amount']);
        $csvData .= "\n";
    }
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="fuelReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: fuelCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/08/09    Time: 15:50
//Created in $/Leap/Source/Templates/Fuel
//Done bug fixing.
//bug ids---
//0000817 to 0000821
?>