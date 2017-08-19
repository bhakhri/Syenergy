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
	define('MODULE','OpeningStock');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
    $itemsManager = ItemsManager::getInstance();

    /////////////////////////
	$itemCategoryId = $REQUEST_DATA['categoryCode'];

    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
    $filter ="";
    
    
    ///Search Filter Ends
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'categoryCode';
    
     $orderBy = " $sortField $sortOrderBy";

    ////////////
    $filter = "WHERE im.itemCategoryId = $itemCategoryId";
    $itemRecordArray = $itemsManager->getParticularItem($filter);
    $cnt = count($itemRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		
	   if($itemRecordArray[$i]['qty'] == '') {
			$quantityAction = '<input type=text maxlength="6" name="qty_'.$itemRecordArray[$i]['itemId'].'" id="qty_'.$itemRecordArray[$i]['itemId'].'" class="inputbox"/>';
	   }
	   else {
			$quantityAction = '<input type=text maxlength="6" name="qty_'.$itemRecordArray[$i]['itemId'].'" id="qty_'.$itemRecordArray[$i]['itemId'].'" class="inputbox" value = "'.$itemRecordArray[$i]['qty'].'"/>';
	   }
       $valueArray = array_merge(array('quantityAction' => $quantityAction, 'srNo' => ($records+$i+1) ),$itemRecordArray[$i]);
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