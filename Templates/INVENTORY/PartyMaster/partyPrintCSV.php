 <?php 
//This file is used as printing version for display Hostel.
//
// Author :Jaineesh
// Created on : 06.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(INVENTORY_MODEL_PATH . "/PartyManager.inc.php");
    $partyManager = PartyManager::getInstance();
    
	function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
            return '"'.$comments.'"'; 
         } 
         else {
            return chr(160).$comments; 
         }
    }

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (partyName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR partyCode LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR partyAddress LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR partyPhones LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR partyFax LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'partyName';
    
    $orderBy = " $sortField $sortOrderBy";

	$partyRecordArray = $partyManager->getPartyList($filter, $orderBy, '');
    $recordCount = count($partyRecordArray);

    $valueArray = array();

    $csvData ='';
	$csvData.="Search by : ".$REQUEST_DATA['searchbox']."\n";
    $csvData .="#,Name,Code,Address,Phones,Fax";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		$csvData .= ($i+1).",";
		$csvData .= $partyRecordArray[$i]['partyName'].",";
		$csvData .= $partyRecordArray[$i]['partyCode'].",";
		$csvData .= parseCSVComments($partyRecordArray[$i]['partyAddress']).",";
		$csvData .= parseCSVComments($partyRecordArray[$i]['partyPhones']).",";
		$csvData .= parseCSVComments($partyRecordArray[$i]['partyFax']).",";
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
header('Content-Disposition: attachment;  filename="'.'PartyReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>