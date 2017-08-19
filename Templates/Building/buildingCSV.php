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
    require_once(MODEL_PATH . "/BuildingManager.inc.php");
    $buildingManager = BuildingManager::getInstance();

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
        $conditions =' WHERE (bu.buildingName LIKE "%'.add_slashes($search).'%" OR bu.abbreviation LIKE "%'.add_slashes($search).'%")';         
    }
    
    
    //$conditions = '';
    //if (count($conditionsArray) > 0) {
        //$conditions = ' AND '.implode(' AND ',$conditionsArray);
    //}

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'buildingName';

    //$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy="bu.$sortField $sortOrderBy"; 


    $recordArray = $buildingManager->getBuildingList($conditions,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$csvData = "SearchBy : ".$search."\n";
    $csvData .= "#, Building Name, Abbr. \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['buildingName']).','.parseCSVComments($record['abbreviation']);
		$csvData .= "\n";
	}
    if($cnt==0){
       $csvData .= ",".NO_DATA_FOUND; 
    }
	
    ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="building.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

    // $History: buildingCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/08/09    Time: 16:10
//Created in $/LeapCC/Templates/Building
//Done bug fixing.
//bug ids--
//0000861 to 0000877
?>