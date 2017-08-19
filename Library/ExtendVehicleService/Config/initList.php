<?php
//  This File calls Edit Function used in adding Config Records
//
// Author :Ajinder Singh
// Created on : 05-Sep-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/ConfigManager.inc.php");
    $configManager = ConfigManager::getInstance();
    
    //Delete code goes here
    if(UtilityManager::notEmpty($REQUEST_DATA['configId']) && $REQUEST_DATA['act']=='del') {
            
        if($recordArray[0]['found']==0) {
            if($configManager->deleteConfig($REQUEST_DATA['configId']) ) {
                $message = DELETE;
            }
        }
        else {
            $message = DEPENDENCY_CONSTRAINT;
        }
    }
        
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (cf.param LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'% OR cf.labelName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR cf.value LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    
    $totalArray = $configManager->getTotalConfig($filter);
    $configRecordArray = $configManager->getConfigList($filter,$limit);

// $History: initList.php $
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