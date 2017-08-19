 <?php 
//This file is used as printing version for display Hostel.
//
// Created on : 18.Nov.10
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
   
    require_once(INVENTORY_MODEL_PATH . "/RequisitionManager.inc.php");
	$requisitionManager = RequisitionManager::getInstance();
	
	   if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' HAVING (irm.requisitionNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR u.userName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR irm.requisitionDate LIKE "%'.date("Y-m-d", strtotime(trim($REQUEST_DATA['searchbox']))).'%")';
    }


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'requisitionNo';
    
    $orderBy = " $sortField $sortOrderBy";

	$requisitionApprovedRecordArray  = $requisitionManager->getApprovedRequisitionList($filter,'',$orderBy);

    $cnt = count($requisitionApprovedRecordArray);

    $valueArray = array();

    $csvData  ='';
    $csvData .= "Search By : ";
	if(trim($REQUEST_DATA['searchbox'])!=''){
  	   
	   $csvData .=$REQUEST_DATA['searchbox'];
	}
	$csvData .="\n";
    $csvData .="#,Requisition No.,Requisition Date,User";
    $csvData .="\n";

    for($i=0;$i<$cnt;$i++) {
		$requisitionApprovedRecordArray[$i]['requisitionStatus']=$requisitionStatusArray[$requisitionApprovedRecordArray[$i]['requisitionStatus']];
		$csvData .= ($i+1).",";
		$csvData .= $requisitionApprovedRecordArray[$i]['requisitionNo'].",";
		$csvData .= UtilityManager::formatDate($requisitionApprovedRecordArray[$i]['requisitionDate']).",";
		$csvData .= $requisitionApprovedRecordArray[$i]['userName'].",";
		$csvData .= "\n";
  }
    if($cnt == 0){
		$csvData .=" No Data Found ";
	}
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'approveRequisitionReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
