 <?php 
//This file is used to export indent to excel sheet.
//
// Created on : 19.Nov.10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php

  require_once(INVENTORY_MODEL_PATH . "/GRNManager.inc.php");
    $grnManager = GRNManager::getInstance();
	
    $conditions = ''; 

     if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' HAVING (ip.partyCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR igm.billNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR igm.billDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" )';
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'requisitionNo';
    
    $orderBy = " $sortField $sortOrderBy";
    $grnRecordArray  = $grnManager->getGRNList($filter,'',$orderBy);
	$recordCount = count($grnRecordArray);

    $valueArray = array();

    $csvData  ='';

	if(isset($REQUEST_DATA['searchbox'])){
  	   $csvData .= "Search By : ";
	   $csvData .=$REQUEST_DATA['searchbox'];
	}
	$csvData .="\n";
    $csvData .="#,Party Code,Bill No.,Date";
    $csvData .="\n";

    for($i=0;$i<$recordCount;$i++) {
		$csvData .= ($i+1).",";
		$csvData .= $grnRecordArray[$i]['partyCode'].",";
		$csvData .= $grnRecordArray[$i]['billNo'].",";
		$csvData .= UtilityManager::formatDate($grnRecordArray[$i]['billDate']).",";
		$csvData .= "\n";
	}
    if($recordCount == 0){
		$csvData .=" No Data Found ";
	}
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'GRNReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
?>