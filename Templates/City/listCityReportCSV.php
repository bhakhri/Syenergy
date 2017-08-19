 <?php 
//This file is used as printing version for display cities.
//
// Author :Parveen Sharma
// Created on : 03.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
	
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CityMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/CityManager.inc.php");
    $cityManager = CityManager::getInstance();
    
     // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return $comments.chr(160); 
         }
    }
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (ct.cityName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ct.cityCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR st.stateName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'cityName';
    $orderBy = " $sortField $sortOrderBy";  
     
    $cityRecordArray = $cityManager->getCityList($filter,'',$orderBy);
    $cnt = count($cityRecordArray);
    
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));    
    
    $csvData ='';
    $csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData.="Sr No.,City Name,City Code,State Name";
    $csvData .="\n";
    
    for($i=0;$i<$cnt;$i++) {  
		  $csvData .= ($i+1).",";
		  $csvData .= parseCSVComments($cityRecordArray[$i]['cityName']).",";
		  $csvData .= parseCSVComments($cityRecordArray[$i]['cityCode']).",";
		  $csvData .= parseCSVComments($cityRecordArray[$i]['stateName'])."\n";
    }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="cityreport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>