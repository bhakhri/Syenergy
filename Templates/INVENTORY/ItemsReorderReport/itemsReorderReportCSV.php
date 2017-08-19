<?php
  //This file is used as csv version for TestType.
//
// Author :Gurkeerat Sidhu
// Created on : 15-09-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
    $itemsManager = ItemsManager::getInstance();

    $conditionsArray = array();
    $qryString = "";
                          

//to parse csv values    
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

  global  $UnitOfMeasurementArray;
    global  $packagingArray;
    
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       
       $filter = '  WHERE (itemCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR  minimumQty LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR availableQty LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR itemName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" ';
       
       foreach($UnitOfMeasurementArray as $key=>$value)
       {
         if(stristr($value,add_slashes($REQUEST_DATA['searchbox']))) {  
           $filter .= " OR unitOfMeasure LIKE '%$key%' ";
           break;
         }
       }      
       
       foreach($packagingArray as $key=>$value)
       {
         if(stristr($value,add_slashes($REQUEST_DATA['searchbox']))) {  
           $filter .= " OR packaging LIKE '%$key%' ";
           break;
         }
       }      
       $filter .= ")"; 
    }
     $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'itemCode';
    
     $orderBy = " $sortField $sortOrderBy";
     if($filter== '' ) {
       $filter = " WHERE availableQty <= minimumQty ";
     }
     else {
       $filter .= " AND availableQty <= minimumQty ";
     }
     

    $recordArray = $itemsManager->getItemReorderList($filter,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
         $recordArray[$i]['unitOfMeasure']=$UnitOfMeasurementArray[$recordArray[$i]['unitOfMeasure']];
       $recordArray[$i]['packaging']=$packagingArray[$recordArray[$i]['packaging']];
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

    $csvData = '';
    $csvData .= "#, Item Code, Item Name, Unit, Packaging, Available Qty., Min. Qty. \n";
    foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['itemCode']).', '.parseCSVComments($record['itemName']).', '.parseCSVComments($record['unitOfMeasure']).', '.parseCSVComments($record['packaging']).', '.parseCSVComments($record['availableQty']).', '.parseCSVComments($record['minimumQty']);
        $csvData .= "\n";
    }
    ob_end_clean();
    header("Cache-Control: public, must-revalidate");
    header('Content-type: application/octet-stream; charset=utf-8');
    header("Content-Length: " .strlen($csvData) );
    header('Content-Disposition: attachment;  filename="itemsReorder.csv"');
    header("Content-Transfer-Encoding: binary\n");
    echo $csvData;
    die; 
?>
