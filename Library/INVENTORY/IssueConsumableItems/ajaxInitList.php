<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','IssueConsumableItems');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/IssueItemsManager.inc.php");
    $itemsManager = IssueItemsManager::getInstance();

    /////////////////////////
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' having (issuedFrom LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR issuedTo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ic.categoryName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR im.itemName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR icii.itemQuantity LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'issuedFrom';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $itemTotalConsumableRecordArray = $itemsManager->getCosumableList($filter,'',$orderBy);
	$count = count($itemTotalConsumableRecordArray);

    $itemConsumableRecordArray = $itemsManager->getCosumableList($filter,$limit,$orderBy);
	//print_r($itemConsumableRecordArray);
    $cnt = count($itemConsumableRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
		$actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$itemConsumableRecordArray[$i]['invConsumableIssuedId'].');return false;"></a>
					<a href="#" title="Consumed"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteConsumableItems('.$itemConsumableRecordArray[$i]['invConsumableIssuedId'].');"/></a>
					<a href="#" title="Returned"><img src="'.IMG_HTTP_PATH.'/red_button.gif" border="0" onClick="cancelConsumableItem('.$itemConsumableRecordArray[$i]['invConsumableIssuedId'].');"/></a>';

        $valueArray = array_merge(	array('action1' => $actionStr, 
									'srNo' => ($records+$i+1) ),
									$itemConsumableRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
  echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}';
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/24/10    Time: 10:08a
//Created in $/Leap/Source/Library/INVENTORY/IssueConsumableItems
//new files for issue consumable items
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 19/09/09   Time: 14:24
//Updated in $/Leap/Source/Library/INVENTORY/IndentMaster
//Fixed bugs found during self-testing
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/09/09   Time: 18:22
//Created in $/Leap/Source/Library/INVENTORY/IndentMaster
//Created  "Indent Master" module under "Inventory Management"
?>