 <?php 
//This file is used to download salary deduction accounts as csv.
//
// Author :Abhiraj
// Created on : 16-April-2010
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    $payrollManager = PayrollManager::getInstance();
    
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  WHERE accountName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR accountNumber LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'accountName';
    
     $orderBy = " $sortField $sortOrderBy";

	$salaryRecordArray = $payrollManager->getAccountList($filter,'',$orderBy);
    
	$recordCount = count($salaryRecordArray);

    $valueArray = array();

    $csvData ='';
    if(trim($search)!="" && $recordCount>0)
    {
        logError("xxxxxxxxxx".trim($search)."yyyyyyyyyyyy".$recordCount);
        $csvData .= "Search By : ".$REQUEST_DATA['searchbox'];
        $csvData .="\n"; 
    }
    $csvData .="Sr No.,Account Name,Account Number";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
          $csvData .= $salaryRecordArray[$i]['accountName'].",";
		  $csvData .= $salaryRecordArray[$i]['accountNumber'].","; 
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called DeductionAccountsReport.csv
header('Content-Disposition: attachment;  filename="'.'DeductionAccountsReport.csv'.'"');
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>