 <?php 
//This file is used as printing version for display Bank Branch.
//
// Author :Jaineesh
// Created on : 20.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(MODEL_PATH . "/BankBranchManager.inc.php");
    $bankBranchManager = BankBranchManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  AND (a.branchName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR a.branchAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR a.accountType LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR a.accountNumber LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR b.bankName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bankName';
    
     $orderBy = " $sortField $sortOrderBy";

	$bankBranchArray = $bankBranchManager->getBankBranchList($filter,'',$orderBy);
    
	$recordCount = count($bankBranchArray);

    $valueArray = array();

    $csvData ='';
    $csvData="Sr No.,Bank,Branch,Abbr.,Account Type,Account Number";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $bankBranchArray[$i]['bankName'].",";
		  $csvData .= $bankBranchArray[$i]['branchName'].",";
		  $csvData .= $bankBranchArray[$i]['branchAbbr'].",";
		  $csvData .= $bankBranchArray[$i]['accountType'].",";
		  $csvData .= $bankBranchArray[$i]['accountNumber'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'BankBranchReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>