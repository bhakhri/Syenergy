<?php 
//This file is used as csv version for bus.
//
// Author : Jaineesh
// Created on : 22-10-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/BusManager.inc.php");
    $busManager = BusManager::getInstance();

	
    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       if(strtoupper(trim($REQUEST_DATA['searchbox']))=='YES' ){
         $inService=1;  
       }
       elseif(strtoupper(trim($REQUEST_DATA['searchbox']))=='NO'){
         $inService=0;  
       }
      else{
          $inService=-1;
      }
      
       $filter = ' WHERE (bs.busName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.busNo LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.modelNumber LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.seatingCapacity LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.yearOfManufacturing LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.isActive LIKE "'.$inService.'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busName';
    
     $orderBy = " bs.$sortField $sortOrderBy"; 
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'stopName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $busManager->getBusList($filter,$orderBy,'');

	$recordCount = count($recordArray);
	
	$valueArray = array();
    $csvData ='';
    $csvData="Sr No.,Name,Registration No.,Model,Purchase Date,Capacity,Mfd. Year,In Service";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		if($recordArray[$i]['purchaseDate']!='0000-00-00'){
         $recordArray[$i]['purchaseDate']=UtilityManager::formatDate($recordArray[$i]['purchaseDate']);
        }
        else{
            $recordArray[$i]['purchaseDate']=NOT_APPLICABLE_STRING;
        }
		  $csvData .= ($i+1).",";
		  $csvData .= $recordArray[$i]['busName'].",";
		  $csvData .= $recordArray[$i]['busNo'].",";
		  $csvData .= $recordArray[$i]['modelNumber'].",";
		  $csvData .= $recordArray[$i]['purchaseDate'].",";
		  $csvData .= $recordArray[$i]['seatingCapacity'].",";
		  $csvData .= $recordArray[$i]['yearOfManufacturing'].",";
		  $csvData .= $recordArray[$i]['isActive'].",";	
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'BusReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $

?>

