 <?php 
//This file is used as printing version for display Hostel.
//
// Author :Jaineesh
// Created on : 06.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
   
    require_once(INVENTORY_MODEL_PATH . "/RequisitionManager.inc.php");
	$requisitionManager = RequisitionManager::getInstance();
	
	 if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		if(strtolower(trim($REQUEST_DATA['searchbox']))=='pending') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='approved') {
           $type=2;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='cancelled') {
           $type=3;
       }
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='issued') {
           $type=4;
       }
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='cancelledbyhod') {
           $type=5;
       }
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='cancelledbystore') {
           $type=6;
       }
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='incomplete') {
           $type=7;
       }
	   else {
		   $type=-1;
	   }

      $filter = ' HAVING (irm.requisitionNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR irm.requisitionStatus LIKE "'.$type.'%"  OR irm.requisitionDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" OR totalCount = "'.$REQUEST_DATA['searchbox'].'" )';
    }



    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'requisitionNo';
    
    $orderBy = " $sortField $sortOrderBy";

	$requisitionRecordArray  = $requisitionManager->getRequisitionList($filter,'',$orderBy);

	$recordCount = count($requisitionRecordArray);

    $valueArray = array();

    $csvData  ='';

	if(ISSET($REQUEST_DATA['searchbox'])){
  	   $csvData .= "Search By : ";
	   $csvData .=$REQUEST_DATA['searchbox'];
	}
	$csvData .="\n";
    $csvData .="Sr No.,Requisition No.,Requisition Date,Status,Item Count";
    $csvData .="\n";

    for($i=0;$i<$recordCount;$i++) {
		$requisitionRecordArray[$i]['requisitionStatus']=$requisitionStatusArray[$requisitionRecordArray[$i]['requisitionStatus']];
		$csvData .= ($i+1).",";
		$csvData .= $requisitionRecordArray[$i]['requisitionNo'].",";
		$csvData .= UtilityManager::formatDate($requisitionRecordArray[$i]['requisitionDate']).",";
		$csvData .= $requisitionRecordArray[$i]['requisitionStatus'].",";
		$csvData .= $requisitionRecordArray[$i]['totalCount'].",";
		$csvData .= "\n";
  }
    if($recordCount == 0){
		$csvData .=" No Data Found ";
	}
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'requisitionReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>