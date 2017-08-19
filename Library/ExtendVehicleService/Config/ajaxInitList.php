<?php
//-------------------------------------------------------
// Purpose: To store the records of Config in array from the database, pagination and search, delete 
// functionality
//
// Author : Ajinder Singh
// Created on : 05-Sep-2008
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ConfigManager.inc.php");
    $configManager = ConfigManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  WHERE param LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR labelName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR value LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'param';
    
     $orderBy = " $sortField $sortOrderBy";         

    
    $totalArray = $configManager->getTotalConfig($filter);
    $configRecordArray = $configManager->getConfigList($filter,$limit,$orderBy);
	
    $cnt = count($configRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add configId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $configRecordArray[$i]['configId'] , 'srNo' => ($records+$i+1) ),$configRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History: ajaxInitList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Config
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/05/08    Time: 5:41p
//Created in $/Leap/Source/Library/Config
//file added for config masters
//


?>
