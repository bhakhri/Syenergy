<?php
//-------------------------------------------------------
// Purpose: To store the records of item category in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;   
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ItemCategoryMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/ItemCategoryManager.inc.php");
    $itemCategoryManager = ItemCategoryManager::getInstance();

    /////////////////////////

	function trim_output($str,$maxlength,$mode=1,$rep='...'){
		$ret=($mode==2?chunk_split($str,12):$str);

	if(strlen($ret) > $maxlength){
		$ret=substr($ret,0,$maxlength).$rep; 
	}
	return $ret;
	}
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
	   if(strtolower(trim($REQUEST_DATA['searchbox']))=='consumable') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='non-consumable') {
           $type=2;
       }
	   else {
		   $type=-1;
	   }
       $filter = ' WHERE (categoryName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR categoryCode LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR categoryType LIKE "'.$type.'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'categoryName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $itemCategoryManager->getTotalItemCategory($filter);

    $itemCategoryRecordArray = $itemCategoryManager->getItemCategoryList($filter, $orderBy, $limit);
	//print_r($itemCategoryRecordArray);
       
    $cnt = count($itemCategoryRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		//$itemCategoryRecordArray[$i]['cnt'] = "<a href='#' title='Show Detail' onclick='printReport(".$itemCategoryRecordArray[$i]['itemCategoryId'].")'>".$itemCategoryRecordArray[$i]['cnt']."</a>";
		//$itemCategoryRecordArray[$i]['description'] = trim_output($itemCategoryRecordArray[$i]['description'],50,1);
        // add stateId in actionId to populate edit/delete icons in User Interface
		$itemCategoryRecordArray[$i]['categoryType'] = $categoryTypeStatus[$itemCategoryRecordArray[$i]['categoryType']];
        $valueArray = array_merge(array('action' => $itemCategoryRecordArray[$i]['itemCategoryId'] , 'srNo' => ($records+$i+1) ),$itemCategoryRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
?>