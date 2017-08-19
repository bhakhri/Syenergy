<?php 
// This file is used as csv version for Company.
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Placement/CompanyManager.inc.php");
    $companyManager = CompanyManager::getInstance();

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
    $filter = ''; 
    if (!empty($search)) {
        $filter = ' AND ( companyName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR companyCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR landline LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR mobileNo LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR contactPerson LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR emailId LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'companyName';
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $companyManager->getCompanyList($filter,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$csvData = '';
    $csvData .= "#, Name, Code, Landline, Mobile No., Contact Person , Email Id \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['companyName']).','.parseCSVComments($record['companyCode']).','.parseCSVComments($record['landline']).','.parseCSVComments($record['mobileNo']).','.parseCSVComments($record['contactPerson']).','.parseCSVComments($record['emailId']);
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="company.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: testTypeCSV.php $
?>