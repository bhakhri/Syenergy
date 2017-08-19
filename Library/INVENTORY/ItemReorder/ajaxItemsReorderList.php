<?php
  //-------------------------------------------------------
// Purpose: To store the records of hostel in array from the database, pagination and search, delete 
// functionality
//
// Author : Gurkeerat Sidhu
// Created on : (15-09-2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");   
    
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ItemsReorderMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
    $itemsManager = ItemsManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
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
     
    ////////////
    
    $totalArray = $itemsManager->getTotalReorderItem($filter);
    $itemRecordArray = $itemsManager->getItemReorderList($filter,$limit,$orderBy);
    $cnt = count($itemRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $itemRecordArray[$i]['unitOfMeasure']=$UnitOfMeasurementArray[$itemRecordArray[$i]['unitOfMeasure']];
       $itemRecordArray[$i]['packaging']=$packagingArray[$itemRecordArray[$i]['packaging']];
       
       if($itemRecordArray[$i]['availableQty'] <= $itemRecordArray[$i]['minimumQty']) {
         $actionStr = '<a href="'.UI_HTTP_PATH.'/INVENTORY/orderMaster.php" title="Place an Order" ><img src="'.IMG_HTTP_PATH.'/cart.jpeg" border="0" /></a>';
       }
       else {
          $actionStr = NOT_APPLICABLE_STRING;
       }
       
       $valueArray = array_merge(array('action1' => $actionStr , 'srNo' => ($records+$i+1) ),$itemRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>
