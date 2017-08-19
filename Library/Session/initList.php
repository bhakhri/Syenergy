<?php

//  This File calls Edit Function used in adding Session Records
//
// Author :Ajinder Singh
// Created on : 14-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/SessionsManager.inc.php");   
    $sessionsManager = SessionsManager::getInstance();
    
    //Delete code goes here
    if(UtilityManager::notEmpty($REQUEST_DATA['sessionId']) && $REQUEST_DATA['act']=='del') {
            
        if($recordArray[0]['found']==0) {
            if($sessionsManager->deleteSession($REQUEST_DATA['sessionId']) ) {
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
       $filter = ' AND (sess.sessionName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    $totalArray = $sessionsManager->getTotalSession($filter);
    $sessionRecordArray = $sessionsManager->getSessionList($filter,$limit);

// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Session
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/15/08   Time: 10:50a
//Updated in $/Leap/Source/Library/Session
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/21/08    Time: 10:20a
//Updated in $/Leap/Source/Library/Session
//Added last line comment for VSS

?>