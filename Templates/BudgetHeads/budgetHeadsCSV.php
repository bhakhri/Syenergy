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
    require_once(MODEL_PATH . "/BudgetHeadsManager.inc.php");
    $budgetManager = BudgetHeadsManager::getInstance();

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
			return $comments.chr(160); 
		}
	}
    $search = $REQUEST_DATA['searchbox'];
    $filter = ''; 
    if (!empty($search)) {
        $filter = ' WHERE (headName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR headAmount LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'headName';

    //$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" $sortField $sortOrderBy"; 
	 	if($REQUEST_DATA['searchbox'] == "Guest House"){
		$REQUEST_DATA['searchbox']=1;
	}
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (headName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR headAmount LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR headTypeId Like"%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }

    $recordArray = $budgetManager->getBudgetHeadsList($filter,$orderBy,'');
	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
	$cnt = count($recordArray);
	$valueArray = array();
    $csvData ='';
	$csvData = "Search by:$search";
	$csvData .="\n";
    $csvData .="#, Head Name, Head Amount, Head Type \n";
	/*foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['headName']).', '.parseCSVComments($record['headAmount']).','.parseCSVComments($record['headTypeId']);
    $csvData .="\n";

	}*/
	for($i=0;$i<$cnt;$i++){
		$csvData .= ($i+1).",";
		$csvData .= $recordArray[$i]['headName'].",";
		$csvData .= parseCSVComments($recordArray[$i]['headAmount']).",";
		$csvData .= $globalBudgetHeadTypeArray[$recordArray[$i]['headTypeId']].",";
		$csvData .="\n";
	}
	if($cnt == 0){
		$csvData .=" No Data Found ";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="budgetHeads.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: testTypeCSV.php $
?>