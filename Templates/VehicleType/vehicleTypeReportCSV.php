 <?php
//This file is used as printing version for display Insurance.
//
// Author :Jaineesh
// Created on : 24.12.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
    require_once(MODEL_PATH . "/VehicleTypeManager.inc.php");
    $vehicleTypeManager = VehicleTypeManager::getInstance();

	function parseCSVComments($comments) {

		$comments = str_replace('"', '""', $comments);
		$comments = str_ireplace('<br/>', "\n", $comments);
		if(eregi(",", $comments) or eregi("\n", $comments)) {

			return '"'.$comments.'"';
		}
		else {

			return $comments;
		}
	}

    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (vehicleType LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR mainTyres LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR spareTyres LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'vehicleType';

     $orderBy = " $sortField $sortOrderBy";

	$vehicleTypeRecordArray = $vehicleTypeManager->getVehicleTypeList($filter,$limit,$orderBy);
    $cnt = count($vehicleTypeRecordArray);

    $csvData = "Search By, $search \n";
    $csvData .= "#, Vehicle Type, Main Tyres, Spare Tyres";
    $csvData .="\n";

	if($cnt == 0 ) {
		$csvData .="No Data Found";
	}
    for($i=0;$i<$cnt;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= parseCSVComments($vehicleTypeRecordArray[$i]['vehicleType']).",";
		  $csvData .= parseCSVComments($vehicleTypeRecordArray[$i]['mainTyres']).",";
		  $csvData .= parseCSVComments($vehicleTypeRecordArray[$i]['spareTyres']).",";
		  $csvData .= "\n";
  }

 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'VehicleTypeReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;
//$History : $
?>