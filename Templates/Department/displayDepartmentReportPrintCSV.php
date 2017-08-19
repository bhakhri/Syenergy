 <?php 
//This file is used as printing version for display Designation
//
// Author :Jaineesh
// Created on : 04.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php

    require_once(MODEL_PATH . "/DepartmentManager.inc.php");
    $departmentManager = DepartmentManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (departmentName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR abbr LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR description LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR employeeCount LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'departmentName';
    
    $orderBy = " $sortField $sortOrderBy";

	$departmentRecordArray = $departmentManager->getDepartmentList($filter,'',$orderBy);
    
	$recordCount = count($departmentRecordArray);

    $valueArray = array();

    $csvData ='';
    $csvData="Sr No.,Department Name,Employees,Abbr.,Description";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $departmentRecordArray[$i]['departmentName'].",";
          $csvData .= $departmentRecordArray[$i]['employeeCount'].",";          
		  $csvData .= $departmentRecordArray[$i]['abbr'].",";
          $csvData .= $departmentRecordArray[$i]['description'].",";   
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'DepartmentReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>