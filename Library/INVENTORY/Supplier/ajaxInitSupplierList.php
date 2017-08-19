<?php
//-------------------------------------------------------
// Purpose: To store the records of supplier in array from the database, pagination and search, delete 
// functionality
//
// Author : Gurkeerat Sidhu
// Created on : (06.05.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;   
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','SupplierMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/SupplierManager.inc.php");
    $supplierManager = SupplierManager::getInstance();

    /////////////////////////
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
        $search=add_slashes(trim($REQUEST_DATA['searchbox']));
       $filter = ' AND (s.companyName LIKE "'.$search.'%" OR s.supplierCode LIKE "'.$search.'%" OR
        s.address LIKE "'.$search.'%" OR cn.countryName LIKE "'.$search.'%" OR 
        st.stateName LIKE "'.$search.'%" OR ct.cityName LIKE "'.$search.'%" OR s.contactPerson LIKE "'.$search.'%" OR s.contactPersonPhone LIKE "'.$search.'%" OR s.contactPerson LIKE "'.$search.'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'companyName';
    
     $orderBy = " $sortField  $sortOrderBy";         

    ////////////
    
    $totalArray = $supplierManager->getTotalSupplier($filter);

    $supplierRecordArray = $supplierManager->getSupplierList( $filter, $orderBy, $limit);
       
    $cnt = count($supplierRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        if(strlen($supplierRecordArray[$i]['address'])>20){
          $supplierRecordArray[$i]['address']=substr($supplierRecordArray[$i]['address'],0,20).'...';
        }  
        if(strlen($supplierRecordArray[$i]['companyName'])>20){
          $supplierRecordArray[$i]['companyName']=substr($supplierRecordArray[$i]['companyName'],0,20).'...';
        }
        $valueArray = array_merge(array('action' => $supplierRecordArray[$i]['supplierId'] , 'srNo' => ($records+$i+1) ),$supplierRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>