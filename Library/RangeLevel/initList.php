<?php

//  This File calls Edit Function used in adding Range Level Records
//
// Author :Ajinder Singh
// Created on : 20-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/RangeLevelManager.inc.php");
    $rangeLevelManager = RangeLevelManager::getInstance();
    
    //Delete code goes here
    if(UtilityManager::notEmpty($REQUEST_DATA['rangeId']) && $REQUEST_DATA['act']=='del') {
            
        if($recordArray[0]['found']==0) {
            if($rangeLevelManager->deleteRange($REQUEST_DATA['rangeId']) ) {
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
       $filter = '  WHERE rangeLevel LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR rangeFrom LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR rangeTo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"';
    }
    
    $totalArray = $rangeLevelManager->getTotalRangeLevel($filter);
    $rangeLevelRecordArray = $rangeLevelManager->getRangeLevelList($filter,$limit);

// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/RangeLevel
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/20/08    Time: 3:07p
//Created in $/Leap/Source/Library/RangeLevel
//file added for range level masters
//

?>