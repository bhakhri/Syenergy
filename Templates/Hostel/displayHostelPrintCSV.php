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
    require_once(MODEL_PATH . "/HostelManager.inc.php");
    $hostelManager = HostelManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {

		if(strtolower(trim($REQUEST_DATA['searchbox']))=='girls') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='boys') {
           $type=2;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='mixed') {
           $type=3;
       }
	   else {
		   $type=-1;
	   }
       $filter = ' WHERE (hostelName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hostelCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR totalCapacity LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"
       OR floorTotal LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR roomTotal LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR wardenName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR wardenContactNo LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hostelType LIKE "%'.$type.'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'hostelName';
    
    $orderBy = " $sortField $sortOrderBy";

	$hostelRecordArray = $hostelManager->getHostelList($filter,'',$orderBy);
    
	$recordCount = count($hostelRecordArray);

    $valueArray = array();

    $csvData ='';
	$csvData ="Search By,".($search)."\n";
    $csvData.="#,Hostel Name,Abbr.,Type,No. of Floors,No. of Rooms,Total Capacity,Warden Name,Warden Contact No.";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $hostelRecordArray[$i]['hostelType']=$hostelTypeArr[$hostelRecordArray[$i]['hostelType']];
		  $csvData .= ($i+1).",";
		  $csvData .= $hostelRecordArray[$i]['hostelName'].",";
		  $csvData .= $hostelRecordArray[$i]['hostelCode'].",";
		  $csvData .= $hostelRecordArray[$i]['hostelType'].",";
		  $csvData .= $hostelRecordArray[$i]['floorTotal'].",";
		  $csvData .= $hostelRecordArray[$i]['roomTotal'].",";
		  $csvData .= $hostelRecordArray[$i]['totalCapacity'].",";
          $csvData .= $hostelRecordArray[$i]['wardenName'].",";
          $csvData .= $hostelRecordArray[$i]['wardenContactNo'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'HostelReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
