 <?php 
//This file is used as printing version for display Hostel Room Type Detail.
//
// Author :Jaineesh
// Created on : 25.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(MODEL_PATH . "/HostelRoomTypeDetailManager.inc.php");
    $hostelRoomTypeDetailManager = HostelRoomTypeDetailManager::getInstance();
    
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
       

       $filter = ' AND (h.hostelName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrt.roomType LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrtd.Capacity LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrtd.noOfBeds LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrtd.attachedBath LIKE "%'.$type.'%" OR hrtd.airConditioned LIKE "%'.$type.'%" OR hrtd.internetFacility LIKE "%'.$type.'%" OR hrtd.noOfFans LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrtd.noOfLights LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" )';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roomType';
    
    $orderBy = "$sortField $sortOrderBy";

	$hostelRoomTypeDetailArray = $hostelRoomTypeDetailManager->getHostelRoomTypeDetailList($filter,'',$orderBy);
    
	$recordCount = count($hostelRoomTypeDetailArray);

    $valueArray = array();

    $csvData  ='';
	
	$csvData  = "Search by:$search";
	$csvData .="\n";
    $csvData .="#,Hostel Name,Hostel Room Type,Capacity,No. of Beds,Attached Bathroom,Air Conditioned,Internet Facility,No. of Fans,No. of Lights";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $hostelRoomTypeDetailArray[$i]['hostelName'].",";
		  $csvData .= $hostelRoomTypeDetailArray[$i]['roomType'].",";
		  $csvData .= $hostelRoomTypeDetailArray[$i]['Capacity'].",";
		  $csvData .= $hostelRoomTypeDetailArray[$i]['noOfBeds'].",";
		  $csvData .= $hostelRoomTypeDetailArray[$i]['attachedBath'].",";
		  $csvData .= $hostelRoomTypeDetailArray[$i]['airConditioned'].",";
		  $csvData .= $hostelRoomTypeDetailArray[$i]['internetFacility'].",";
		  $csvData .= $hostelRoomTypeDetailArray[$i]['noOfFans'].",";
		  $csvData .= $hostelRoomTypeDetailArray[$i]['noOfLights'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'HostelRoomTypeDetailReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
