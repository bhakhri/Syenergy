 <?php
//This file is used as printing version for display Insurance.
//
// Author :Jaineesh
// Created on : 24.12.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
    require_once(MODEL_PATH . "/VehicleInsuranceManager.inc.php");
    $vehicleInsuranceManager = VehicleInsuranceManager::getInstance();

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
        $filter = ' WHERE (insuringCompanyName  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR insuringCompanyDetails LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'insuringCompanyName';

     $orderBy = " $sortField $sortOrderBy";

	$vehicleInsuranceRecordArray = $vehicleInsuranceManager->getVehicleInsuranceList($filter,'',$orderBy);
	$cnt = count($vehicleInsuranceRecordArray);

    $csvData ="Search By,".parseCSVComments($search);
    $csvData .="\n";
    $csvData .="#,Company Name,Detail";
    $csvData .="\n";
    
    for($i=0;$i<$cnt;$i++) {
		if($vehicleInsuranceRecordArray[0]['insuringCompanyDetails'] == '') {
				$vehicleInsuranceRecordArray[0]['insuringCompanyDetails'] = NOT_APPLICABLE_STRING;
			}
		  $csvData .= ($i+1).",";
		  $csvData .= parseCSVComments($vehicleInsuranceRecordArray[$i]['insuringCompanyName']).",";
          if($vehicleInsuranceRecordArray[$i]['insuringCompanyDetails']=='') {
		    $csvData .= parseCSVComments(NOT_APPLICABLE_STRING).",";
          }
          else {
            $csvData .= parseCSVComments($vehicleInsuranceRecordArray[$i]['insuringCompanyDetails']).",";  
          }
		  $csvData .= "\n";
    }

    if($i==0) {
       $csvData .= ",No Data Found";  
    }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'InsuranceReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;
//$History : $
?>