 <?php 
//This file is used as printing version for display Resource Category.
//
// Author :Jaineesh
// Created on : 03.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	
	require_once(MODEL_PATH . "/ResourceCategoryManager.inc.php");
    $resourceCategoryManager = ResourceCategoryManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (resourceName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'resourceName';
    
    $orderBy = " $sortField $sortOrderBy";
    
	$resouceCategoryRecordArray = $resourceCategoryManager->getResourceCategoryList($filter,'',$orderBy);
    
	$recordCount = count($resouceCategoryRecordArray);

    $valueArray = array();

    $csvData ="Search By : ".$search."\n";
    $csvData .="#, Category Name";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $resouceCategoryRecordArray[$i]['resourceName'].",";
		  $csvData .= "\n";
    }
    
    if($recordCount==0){
        $csvData .=",".NO_DATA_FOUND;
    }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'ResourceCategoryReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>