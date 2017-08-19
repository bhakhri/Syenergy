 <?php 
//This file is used as printing version for display period slot.
//
// Author :Jaineesh
// Created on : 03.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
	require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/PeriodSlotManager.inc.php");
    $periodSlotManager = PeriodSlotManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		if(strtolower(trim($REQUEST_DATA['searchbox']))=='yes') {
           $type=1;
        }
        elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='no') {
           $type=0;
        }
	    else {
		   $type=-1;
	    }

        $filter = ' WHERE ( slotName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR slotName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR isActive LIKE "'.$type.'")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'slotName';
    
    $orderBy = " $sortField $sortOrderBy";

	$periodSlotRecordArray = $periodSlotManager->getPeriodSlotDetail($filter,'',$orderBy);
    
	$recordCount = count($periodSlotRecordArray);

    $valueArray = array();

    $csvData ='';
    $csvData="Sr No.,Slot Name,Abbr.,Active";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $periodSlotRecordArray[$i]['slotName'].",";
		  $csvData .= $periodSlotRecordArray[$i]['slotAbbr'].",";
		  $csvData .= $periodSlotRecordArray[$i]['isActive'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'PeriodSlotReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>