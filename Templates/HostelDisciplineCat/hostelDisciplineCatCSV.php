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
	require_once(MODEL_PATH . "/HostelDisciplineCatManager.inc.php");
    $disciplineManager = DisciplineManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if (!empty($search)) {
		$conditions =' WHERE (categoryName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'categoryName';
    
    $orderBy = " $sortField $sortOrderBy";

	$disciplineCategoryArray = $disciplineManager->getDisciplineCategoryList('',$conditions,$orderBy,'');
    
	$recordCount = count($disciplineCategoryArray);

    $valueArray = array();

    $csvData ='';
	$csvData = "Search by:$search";
	$csvData .="\n";
    $csvData .="#,Category Name";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		$csvData .= ($i+1).",";
		$csvData .= $disciplineCategoryArray[$i]['categoryName'].",";
		$csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'HostelDisciplineCategory.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>