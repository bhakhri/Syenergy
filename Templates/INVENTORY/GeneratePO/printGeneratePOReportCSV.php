 <?php 
//This file is used to export generatedPO to excel sheet.
//
// Created on : 19.Nov.10
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
   
    require_once(INVENTORY_MODEL_PATH . "/POManager.inc.php");
    $poManager = POManager::getInstance();

	
    $conditions = ''; 

     if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		if(strtolower(trim($REQUEST_DATA['searchbox']))=='pending'){
			$type = 0;
		}
		else if(strtolower(trim($REQUEST_DATA['searchbox']))=='approved'){
			$type = 1;
		}
		else if(strtolower(trim($REQUEST_DATA['searchbox']))=='cancelled'){
			$type = 2;
		}
		else{
			$type= -1;
		}

		$filter = ' HAVING (ipm.poNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR u.userName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ipm.poDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%"
		OR status LIKE "%'.add_slashes(trim($type)).'%")';
    }

   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'requisitionNo';
    
    $orderBy = " $sortField $sortOrderBy";

	$poRecordArray  = $poManager->getGeneratedPo($filter,'',$orderBy);
	$recordCount = count($poRecordArray);

    $valueArray = array();

    $csvData  ='';

	if(isset($REQUEST_DATA['searchbox'])){
  	   $csvData .= "Search By : ";
	   $csvData .=$REQUEST_DATA['searchbox'];
	}
	$csvData .="\n";
    $csvData .="#,PO No.,Date,Status,User Name";
    $csvData .="\n";

    for($i=0;$i<$recordCount;$i++) {
		$csvData .= ($i+1).",";
		$csvData .= $poRecordArray[$i]['poNo'].",";
		$csvData .= UtilityManager::formatDate($poRecordArray[$i]['poDate']).",";
		$csvData .= $poStatusArray[$poRecordArray[$i]['status']].",";
		$csvData .= $poRecordArray[$i]['userName'].",";
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
header('Content-Disposition: attachment;  filename="'.'GeneratePOReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
?>