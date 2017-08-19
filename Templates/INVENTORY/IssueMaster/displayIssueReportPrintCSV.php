 <?php 
//This file is used as printing version for display Hostel.
//
// Created on : 19.Nov.10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
   
    require_once(INVENTORY_MODEL_PATH . "/IssueManager.inc.php");
    $issueManager = IssueManager::getInstance();
	
	if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' HAVING (irm.requisitionNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR emp.employeeName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR irm.requisitionDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" OR irm.approvedOn LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" )';
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'requisitionNo';
    
    $orderBy = " $sortField $sortOrderBy";

	$approvedItemsRecordArray  = $issueManager->getApprovedItemsList($filter,$limit,$orderBy);
	$recordCount = count($approvedItemsRecordArray);

    $valueArray = array();

    $csvData  ='';

	if(isset($REQUEST_DATA['searchbox'])){
  	   $csvData .= "Search By : ";
	   $csvData .=$REQUEST_DATA['searchbox'];
	}
	$csvData .="\n";
    $csvData .="#,Requisition No.,Requisition Date,Approved By,Approved On";
    $csvData .="\n";

    for($i=0;$i<$recordCount;$i++) {
		$csvData .= ($i+1).",";
		$csvData .= $approvedItemsRecordArray[$i]['requisitionNo'].",";
		$csvData .= UtilityManager::formatDate($approvedItemsRecordArray[$i]['requisitionDate']).",";
		$csvData .= $approvedItemsRecordArray[$i]['employeeName'].",";
		$csvData .= $approvedItemsRecordArray[$i]['approvedOn'].",";
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
header('Content-Disposition: attachment;  filename="'.'IssueDescription.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
?>
