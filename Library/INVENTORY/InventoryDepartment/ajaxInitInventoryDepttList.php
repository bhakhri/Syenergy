<?php
//-------------------------------------------------------
// Purpose: To store the records of item category in array from the database, pagination and search, delete 
// functionality
//
// Author : Gurkeerat Sidhu
// Created on : (08.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;   
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','InventoryDeptartment');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/InventoryDeptartmentManager.inc.php");
    $inventoryDeptartmentManager = InventoryDeptartmentManager::getInstance();

    /////////////////////////
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  

	if(strtolower(trim($REQUEST_DATA['searchbox']))=='issue/transfer') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='issuing authority') {
           $type=2;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='end user') {
           $type=3;
       }
	   else {
		   $type=-1;
	   }

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (invd.invDepttName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR invd.invDepttAbbr LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR invd.depttType LIKE "'.$type.'" OR emp.employeeName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'invDepttName';
    
     $orderBy = " $sortField $sortOrderBy";

    ////////////
    
    $totalArray = $inventoryDeptartmentManager->getTotalInventoryDepartment($filter);

    $inventoryDepartmentArray = $inventoryDeptartmentManager->getInventoryDepartmentList( $filter, $orderBy, $limit);
	   
    $cnt = count($inventoryDepartmentArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $inventoryDepartmentArray[$i]['invDepttId'] , 'srNo' => ($records+$i+1) ),$inventoryDepartmentArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
?>