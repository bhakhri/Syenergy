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
	define('MODULE','ItemsSupplierMapping');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
    $itemsManager = ItemsManager::getInstance();

    /////////////////////////
    
    /// Search filter /////
    $filter ="";
    
    ///Search Filter Ends
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'companyName';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////
    
    $supplierRecordArray = $itemsManager->getSupplierList($filter,$orderBy);
    $cnt = count($supplierRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $val=$supplierRecordArray[$i]['supplierId'];
       $alt=strip_slashes($supplierRecordArray[$i]['supplierCode']);
       $priceString='<input type="text" name="priceTxt" id="priceTxt'.$val.'" value="0"  class="inputbox" style="width:50px;" />';        
       $valueArray = array_merge(
                                  array(
                                        'srNo' => ($records+$i+1),
                                        "sups" => '<input type="checkbox" name="sups" id="sups'.$val.'" alt="'.$alt.'"  value="'.$val .'" />' ,
                                        "price"=>$priceString
                                     ),
                                 $supplierRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxSupplierList.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 15/09/09   Time: 11:07
//Created in $/Leap/Source/Library/INVENTORY/ItemsMaster
//Created Item & Supplier Mapping module
?>