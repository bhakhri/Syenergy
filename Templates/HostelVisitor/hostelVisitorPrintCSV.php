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
	require_once(MODEL_PATH . "/HostelVisitorManager.inc.php");
    $hostelVisitorManager = HostelVisitorManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if (!empty($search)) {
       if(strtolower(trim($search))=='father'){
           $rel=1;
       }
       elseif(strtolower(trim($search))=='mother'){
           $rel=2;
       }
       elseif(strtolower(trim($search))=='sister'){
           $rel=3;
       }
       elseif(strtolower(trim($search))=='brother'){
           $rel=4;
       }
       elseif(strtolower(trim($search))=='others'){
           $rel=5;
       }
       else{
           $rel=-1;
       }
		$conditions =' WHERE (visitorName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR toVisit LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR address LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"
       OR purpose LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR contactNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR relation LIKE "'.$rel.'%")';
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'visitorName';
    
    $orderBy = " $sortField $sortOrderBy";

	$recordArray = $hostelVisitorManager->getHostelVisitorList($conditions,'',$orderBy);
    
	$recordCount = count($recordArray);

    $valueArray = array();

    $csvData ='';
	$csvData = "Search by:$search";
	$csvData .="\n";
    $csvData .="#,Visitor Name,To Visit,Address,Date of Visit,Time,Purpose,Contact No.,Relation";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		$recordArray[$i]['relation']=$hostelVisitorRelArr[$recordArray[$i]['relation']];
		$recordArray[$i]['dateOfVisit']=UtilityManager::formatDate($recordArray[$i]['dateOfVisit']);  
		$csvData .= ($i+1).",";
		$csvData .= $recordArray[$i]['visitorName'].",";
		$csvData .= $recordArray[$i]['toVisit'].",";
		$csvData .= $recordArray[$i]['address'].",";
		$csvData .= $recordArray[$i]['dateOfVisit'].",";
		$csvData .= $recordArray[$i]['timeOfVisit'].",";
		$csvData .= $recordArray[$i]['purpose'].",";
		$csvData .= $recordArray[$i]['contactNo'].",";
		$csvData .= $recordArray[$i]['relation'].",";
		$csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'HostelVisitorReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>