<?php
//  This File calls Edit Function used in adding Batch Records
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/BatchManager.inc.php");
    $batchManager = batchManager::getInstance();
    //Delete code goes here
    if(UtilityManager::notEmpty($REQUEST_DATA['batchId']) && $REQUEST_DATA['act']=='del') {
       // $recordArray = $batchManager->checkInCity($REQUEST_DATA['batchId']);
        if($recordArray[0]['found']==0) {
            if($batchManager->deleteBatch($REQUEST_DATA['batchId']) ) {
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
        $filter = ' AND (bat.batchName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bat.startDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bat.endDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bat.batchYear LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';   
    }
    $totalArray = $batchManager->getTotalBatch($filter);
    $batchRecordArray = $batchManager->getBatchList($filter,$limit);
	//$History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Batch
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/27/08    Time: 3:21p
//Updated in $/Leap/Source/Library/Batch
//removed spaces
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/21/08    Time: 5:02p
//Updated in $/Leap/Source/Library/Batch
//added a new fields batchYear in queriy
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:25p
//Created in $/Leap/Source/Library/Batch
//new files added
?>