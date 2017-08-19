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
    define('MODULE','OrderMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/OrderManager.inc.php");
    $orderManager = OrderManager::getInstance();

    /////////////////////////
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       if(strtoupper(trim($REQUEST_DATA['searchbox']))=='YES' ){
         $dispatched=1;  
       }
       elseif(strtoupper(trim($REQUEST_DATA['searchbox']))=='NO'){
         $dispatched=0;  
       }
      else{
          $dispatched=-1;
      }
      
      $filter = ' AND (io.orderNo LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR sup.supplierCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR sup.companyName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR io.dispatched LIKE "'.$dispatched.'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'orderNo';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $orderManager->getTotalOrder($filter);
    $orderRecordArray = $orderManager->getOrderList($filter,$limit,$orderBy);
    $cnt = count($orderRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        if($orderRecordArray[$i]['dispatchDate']!='' and $orderRecordArray[$i]['dispatchDate']!='0000-00-00'){
            $orderRecordArray[$i]['dispatchDate']=UtilityManager::formatDate($orderRecordArray[$i]['dispatchDate']);
        }
        else{
            $orderRecordArray[$i]['dispatchDate']=NOT_APPLICABLE_STRING;
        }
        
        if(strlen($orderRecordArray[$i]['companyName'])>=40){
            $orderRecordArray[$i]['companyName']=substr($orderRecordArray[$i]['companyName'],0,37).'...';
        }
        if($orderRecordArray[$i]['companyName']==''){
            $orderRecordArray[$i]['companyName']=NOT_APPLICABLE_STRING;
        }
        
        $orderRecordArray[$i]['companyName']=str_replace(' ','&nbsp;',$orderRecordArray[$i]['companyName']);
        $orderRecordArray[$i]['supplierCode']=str_replace(' ','&nbsp;',$orderRecordArray[$i]['supplierCode']);
        
        $orderRecordArray[$i]['orderDate']=UtilityManager::formatDate($orderRecordArray[$i]['orderDate']);
        
        $valueArray = array_merge(array('action' => $orderRecordArray[$i]['orderId'] , 'srNo' => ($records+$i+1) ),$orderRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
  echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 19/09/09   Time: 14:24
//Updated in $/Leap/Source/Library/INVENTORY/OrderMaster
//Fixed bugs found during self-testing
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/09/09    Time: 14:32
//Updated in $/Leap/Source/Library/INVENTORY/OrderMaster
//Integrated Inventory Management with Leap
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/09/09    Time: 12:37
//Created in $/Leap/Source/Library/INVENTORY/OrderMaster
//Moved Inventory Management Files to INVENTORY folder
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/09/09    Time: 18:47
//Created in $/Leap/Source/Library/OrderMaster
//Added files for "Order Master" module
?>