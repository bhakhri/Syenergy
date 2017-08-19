<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/BlockManager.inc.php");
    $blockManager = BlockManager::getInstance();

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
        $conditions =' AND (bl.blockName LIKE "%'.add_slashes($search).'%" OR bl.abbreviation LIKE "%'.add_slashes($search).'%" OR bi.buildingName LIKE "%'.add_slashes($search).'%")';         
    }

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'blockName';

    //$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $blockManager->getBlockList($conditions,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$csvData = "SearchBy : ".$search."\n";
    $csvData .= "#, Block Name, Abbr., Building \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['blockName']).','.parseCSVComments($record['abbreviation']).','.parseCSVComments($record['buildingName']);
		$csvData .= "\n";
	}
    if($cnt==0){
       $csvData .= " ,".NO_DATA_FOUND."\n"; 
    }
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="block.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: blockCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 5/08/09    Time: 12:47
//Created in $/LeapCC/Templates/Block
//Done bug fixing.
//bug ids---
//0000887 to 0000895,
//0000906 to 0000909
?>