<?php
//--------------------------------------------------------------------------
// Purpose: This file returns the array of SMS Details based on Message Type
// Author : Kavish Manjkhola
// Created on : (28.03.2011 )
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','SMSDeliveryReport');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/SMSDetailManager.inc.php");
    $smsdetailManager  = SMSDetailManager::getInstance();

    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();
     
    
    // to limit records per page    
   $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
   $records    = ($page-1)* RECORDS_PER_PAGE;
   $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'dated';
    $orderBy = " ORDER BY $sortField $sortOrderBy";

    if(UtilityManager::notEmpty($REQUEST_DATA['fromDate'])) {
       $filter .= "where (sentDate >='".add_slashes($REQUEST_DATA['fromDate'])."')";
    }

    if(UtilityManager::notEmpty($REQUEST_DATA['toDate'])) {
       $filter .= " AND (sentDate <='".add_slashes($REQUEST_DATA['toDate'])."')";
    }

     
    if(UtilityManager::notEmpty($REQUEST_DATA['messageType'])) {
       if($REQUEST_DATA['messageType']!='All') {
            $filter .= " AND (smsStatus ='".$REQUEST_DATA['messageType']."')";
       }
    }
    $smsdetailManager->updateDeliveryStatus(); 
    $totalArray = $smsdetailManager->getTotalSMSStatusDetailList($filter);  
    $SMSDetailRecordArray = $smsdetailManager->getSMSStatusDetailList($filter,$orderBy,$limit);
    $cnt = count($SMSDetailRecordArray);

    for($i=0;$i<$cnt;$i++) {
       
        //$message1 = '<a href="" name="bubble" onclick="showMessageDetails('.$SMSDetailRecordArray[$i]['messageId'].',\'divMessage\',400,200);return false;" title="'.$title.'" >'.$msg.'</a>';
        
        $SMSDetailRecordArray[$i]['sentDate'] = UtilityManager::formatDate($SMSDetailRecordArray[$i]['sentDate']);
        $SMSDetailRecordArray[$i]['smsStatus'] = $SMSDetailRecordArray[$i]['smsStatus'];
        $SMSDetailRecordArray[$i]['smsFrom'] = $SMSDetailRecordArray[$i]['smsFrom'];
        $SMSDetailRecordArray[$i]['smsTo'] = $SMSDetailRecordArray[$i]['smsTo'];
        $SMSDetailRecordArray[$i]['smsText'] = $htmlManager->removePHPJS(str_replace("+"," ",$SMSDetailRecordArray[$i]['smsText']));
        $valueArray = array_merge(array('srNo' => ($records+$i+1)),$SMSDetailRecordArray[$i]);
        if(trim($json_val)=='') {                      
            $json_val = json_encode($valueArray);
        }                                                                          
        else{
            $json_val .= ','.json_encode($valueArray);           
        }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>