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
    define('MODULE','ReceiveMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/ReceiveManager.inc.php");
    $receiveManager = ReceiveManager::getInstance();

    /////////////////////////
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' AND (io.orderNo LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR sup.supplierCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR sup.companyName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'orderNo';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $receiveManager->getTotalOrderReceived($filter);
    $receiveRecordArray = $receiveManager->getOrderReceivedList($filter,$limit,$orderBy);
    $cnt = count($receiveRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        if(strlen($receiveRecordArray[$i]['companyName'])>=40){
            $receiveRecordArray[$i]['companyName']=substr($receiveRecordArray[$i]['companyName'],0,37).'...';
        }
        if($receiveRecordArray[$i]['companyName']==''){
            $receiveRecordArray[$i]['companyName']=NOT_APPLICABLE_STRING;
        }
        $receiveRecordArray[$i]['receiveDate']=UtilityManager::formatDate($receiveRecordArray[$i]['receiveDate']);
        $receiveRecordArray[$i]['dispatchDate']=UtilityManager::formatDate($receiveRecordArray[$i]['dispatchDate']);
        
        if($receiveRecordArray[$i]['stockUpdated']==1){
          $actionStr='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteReceive('.$receiveRecordArray[$i]['orderId'].');"/></a>';
        }
        else{
        $actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$receiveRecordArray[$i]['orderId'].');return false;"></a>
                    <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteReceive('.$receiveRecordArray[$i]['orderId'].');"/></a>';            
        }
        
        $receiveRecordArray[$i]['companyName']=str_replace(' ','&nbsp;',$receiveRecordArray[$i]['companyName']);
        $receiveRecordArray[$i]['supplierCode']=str_replace(' ','&nbsp;',$receiveRecordArray[$i]['supplierCode']);

        $valueArray = array_merge(array('actionStr' => $actionStr , 'srNo' => ($records+$i+1) ),$receiveRecordArray[$i]);

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
//Updated in $/Leap/Source/Library/INVENTORY/ReceiveMaster
//Fixed bugs found during self-testing
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/09/09    Time: 15:14
//Updated in $/Leap/Source/Library/INVENTORY/ReceiveMaster
//Updated "Order Receive Master"----Added "update stock" field and added
//the code : if update stock option is yes then main item master table is
//also updated
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 5/09/09    Time: 16:53
//Created in $/Leap/Source/Library/INVENTORY/ReceiveMaster
//Created module "Order Receive Master"
?>