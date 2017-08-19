<?php
//-------------------------------------------------------
// Purpose: To store the records of training in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','EmployeeInfo');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/PublishingManager.inc.php");
    $publishingManager = PublishingManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $condition = '  AND (p.type LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR p.publishedBy LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR p.description LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%") ';             
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'description';
    
    $orderBy = " $sortField $sortOrderBy";         

    $condition .= ' AND p.employeeId = '.$REQUEST_DATA['employeeId'];
     
    ////////////
    $totalArray = $publishingManager->getTotalPublishing($condition);
    $publishingRecordArray = $publishingManager->getPublishingList($condition,$orderBy,$limit);
    $cnt = count($publishingRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        if($publishingRecordArray[$i]['publishOn']=='0000-00-00') {
           $publishingRecordArray[$i]['publishOn'] = NOT_APPLICABLE_STRING;
        }
        else {
           $publishingRecordArray[$i]['publishOn'] = UtilityManager::formatDate($publishingRecordArray[$i]['publishOn']);
        }
        
        $valueArray = array_merge(array('action' => $publishingRecordArray[$i]['publishId'] , 
                                        'srNo' => ($records+$i+1) ),$publishingRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitPublishingList.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 12:14p
//Created in $/LeapCC/Library/Publishing
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:14p
//Created in $/Leap/Source/Library/Publishing
//initial checkin 
//
?>
