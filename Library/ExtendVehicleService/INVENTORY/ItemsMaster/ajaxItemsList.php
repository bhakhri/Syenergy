<?php
//-------------------------------------------------------
// Purpose: To store the records of hostel in array from the database, pagination and search, delete 
// functionality
//
// Author : DB
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','ItemsMaster');
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
    //////
    /// Search filter /////
    $filter ="";
    
	if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		if(strtolower(trim($REQUEST_DATA['searchbox']))=='kilogram') {
           $type=0;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='litre') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='number') {
           $type=2;
       }
	   else {
		   $type=-1;
	   }

       $filter = ' AND (ic.categoryCode LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR ic.categoryName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR im.itemName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR im.itemCode LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR im.itemName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR im.reOrderLevel LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR im.units LIKE "%'.$type.'%")';
    }   
    
    ///Search Filter Ends
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'categoryCode';
    
     $orderBy = " $sortField $sortOrderBy";

    ////////////
    
    $totalArray = $itemsManager->getTotalItem($filter);
    $itemRecordArray = $itemsManager->getItemList($filter,$limit,$orderBy);
    $cnt = count($itemRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $itemRecordArray[$i]['units']=$UnitOfMeasurementArray[$itemRecordArray[$i]['units']];
       
       $valueArray = array_merge(array('action' => $itemRecordArray[$i]['itemId'] , 'srNo' => ($records+$i+1) ),$itemRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: $
//
?>