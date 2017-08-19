 <?php 
//This file is used to export indent to excel sheet.
//
// Created on : 19.Nov.10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
   
  require_once(INVENTORY_MODEL_PATH . "/IndentManager.inc.php");
    $indentManager = IndentManager::getInstance();

	
    $conditions = ''; 

   if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
	   if(strtolower(trim($REQUEST_DATA['searchbox']))=='pending') {
           $type=0;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='cancelled') {
           $type=1;
       }
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='generatedpo') {
           $type=2;
       }
	   else {
		   $type=-1;
	   }

      $filter = ' HAVING (iim.indentNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR iim.indentStatus  LIKE "'.$type.'%" OR iim.indentDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" OR totalCount = "'.$REQUEST_DATA['searchbox'].'")';
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'indentNo';
    
   $sortField1 = $sortField;
	if($sortField=='indentNo') {
	   //$sortField1 = "LENGTH(indentNo)+0,indentNo";
	   $orderBy = " LENGTH(indentNo)+0 $sortOrderBy, indentNo $sortOrderBy";
	}
	else {
		$orderBy = " $sortField1 $sortOrderBy";
	}
	$indentRecordArray  = $indentManager->getIndentList($filter,$limit,$orderBy);
	$recordCount = count($indentRecordArray);

    $valueArray = array();

    $csvData  ='';

	if(isset($REQUEST_DATA['searchbox'])){
  	   $csvData .= "Search By : ";
	   $csvData .=$REQUEST_DATA['searchbox'];
	}
	$csvData .="\n";
    $csvData .="#,Indent No.,Indent Date,Status,Items Count";
    $csvData .="\n";

    for($i=0;$i<$recordCount;$i++) {
		$csvData .= ($i+1).",";
		$csvData .= $indentRecordArray[$i]['indentNo'].",";
		$csvData .= UtilityManager::formatDate($indentRecordArray[$i]['indentDate']).",";
		$csvData .= $indentStatusArray[$indentRecordArray[$i]['indentStatus']].",";
		$csvData .= $indentRecordArray[$i]['totalCount'].",";
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
header('Content-Disposition: attachment;  filename="'.'IndentReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
?>