<?php
//-------------------------------------------------------------------------------------------------------------- 
// Purpose: To store the records of block in array from the database, pagination and search, delete 
// functionality
//
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------- 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','Common');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SMSDetailManagers.inc.php");
    $smsDetailManagers = SMSDetailManagers::getInstance();
   
    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();

    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'dated';
    
    $orderBy = " $sortField $sortOrderBy";         

    $filter="";

    $totalArray = $smsDetailManagers->getMessageDetailTotal($filter);
    $smsRecordArray = $smsDetailManagers->getMessageDetail($filter,$limit,$orderBy);
    $cnt = count($smsRecordArray);


    for($i=0;$i<$cnt;$i++) {
        $msg = $htmlManager->removePHPJS($smsRecordArray[$i]['message']); 
        
        $action1 = '';
        if(strlen($msg) > 80) {
          $message1 = substr($msg,0,80)."...";  
        }
        else {
          $message1 = $msg;
        }
        
        $action1 = '<a href="" name="bubble" onclick="showMessageDetails('.$smsRecordArray[$i]['messageId'].',\'divMessage\',400,200);return false;" ><img src="'.IMG_HTTP_PATH.'/mer.gif" border="0" alt="Brief Description" title="Brief Description"></a></a>';
        
        $smsRecordArray[$i]['dated'] = UtilityManager::formatDate($smsRecordArray[$i]['dated']);
        $smsRecordArray[$i]['message'] = $message1;  
        
        $valueArray = array_merge(array('action1'=>$action1,
					'srNo' => ($records+$i+1)),
					$smsRecordArray[$i]);
        if(trim($json_val)=='') {                      
            $json_val = json_encode($valueArray);
        }                                                                          
        else{
            $json_val .= ','.json_encode($valueArray);           
        }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

?>
