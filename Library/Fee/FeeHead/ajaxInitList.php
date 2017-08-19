<?php
//-------------------------------------------------------
// Purpose: To store the records of Fee Head in array from the database, pagination and search, delete 
// functionality
// Author : Nishu Bindal
// Created on : (02.feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeeHeadsNew');     
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
    $feeHeadManager =FeeHeadManager::getInstance();


    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty(trim($REQUEST_DATA['searchbox']))) {
       
       $str = "";
       if(add_slashes(strtolower(trim($REQUEST_DATA['searchbox']))) == 'yes' || add_slashes(strtolower(trim($REQUEST_DATA['searchbox']))) == 'y' || add_slashes(strtolower(trim($REQUEST_DATA['searchbox']))) == 'ye') 
          $str = ' OR isRefundable = "1" OR isSpecial = "1" OR isConsessionable = "1"';
       else if(add_slashes(strtolower(trim($REQUEST_DATA['searchbox']))) == 'no'  || add_slashes(strtolower(trim($REQUEST_DATA['searchbox']))) == 'n') 
          $str = ' OR isRefundable = "0" OR isSpecial = "0" OR isConsessionable = "0"';  
          
       $filter = ' AND (headName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR headAbbr LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR sortingOrder LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" '.$str.')';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'headName';
    
     $orderBy = " $sortField $sortOrderBy";         

    
    $totalArray = $feeHeadManager->getTotalFeeHead($filter);
    $feeHeadRecordArray = $feeHeadManager->getFeeHeadList($filter,$limit,$orderBy);
    $cnt = count($feeHeadRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $id = $feeHeadRecordArray[$i]['feeHeadId']; 
       $feeHeadRecordArray[$i]['isRefundable'] = $feeHeadRecordArray[$i]['isRefundable'] == 1 ? 'Yes' : 'No' ;
       $feeHeadRecordArray[$i]['isSpecial']   = $feeHeadRecordArray[$i]['isSpecial'] == 1 ? 'Yes' : 'No' ;
       $feeHeadRecordArray[$i]['isConsessionable']   = $feeHeadRecordArray[$i]['isConsessionable'] == 1 ? 'Yes' : 'No' ;  
       $feeHeadRecordArray[$i]['sortingOrder'] = $feeHeadRecordArray[$i]['sortingOrder'] != ''? $feeHeadRecordArray[$i]['sortingOrder'] : "0" ;         
     
       // add feeHeadId in actionId to populate edit/delete icons in User Interface   
       $valueArray = array_merge(array('action' => $id, 
                                       'srNo' => ($records+$i+1) ),
                                       $feeHeadRecordArray[$i]);

       if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
       }
       else {
          $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
?>
