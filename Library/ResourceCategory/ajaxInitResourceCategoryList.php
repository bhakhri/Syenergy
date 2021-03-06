<?php
//-------------------------------------------------------
// Purpose: To store the records of Resource category in array from the database, pagination and search, delete 
// functionality
//
// Author : Gurkeerat Sidhu
// Created on : (20.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;   
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ResourceCategory');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ResourceCategoryManager.inc.php");
    $resourceCategoryManager = ResourceCategoryManager::getInstance();

    /////////////////////////
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (resourceName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'resourceName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $resourceCategoryManager->getTotalResourceCategory($filter);

    $resourceCategoryRecordArray = $resourceCategoryManager->getResourceCategoryList($filter, $orderBy, $limit);
       
    $cnt = count($resourceCategoryRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $resourceCategoryRecordArray[$i]['resourceTypeId'] , 'srNo' => ($records+$i+1) ),$resourceCategoryRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    


  
?>
