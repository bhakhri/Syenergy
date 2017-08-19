<?php
//This file calls Delete Function and Listing Function and creates Global Array in Subject Module 
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	//Paging code goes here
    require_once(MODEL_PATH . "/SubjectManager.inc.php");
    $subjectManager = SubjectManager::getInstance();
    
    //Delete code goes here
    if(UtilityManager::notEmpty($REQUEST_DATA['subjectId']) && $REQUEST_DATA['act']=='del') {
            
      //  $recordArray = $subjectManager->checkInCity($REQUEST_DATA['stateId']);
        if($recordArray[0]['found']==0) {
            if($subjectManager->deleteSubject($REQUEST_DATA['subjectId']) ) {
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
       $filter = ' AND (sub.subjectName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sub.subjectCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    $totalArray = $subjectManager->getTotalSubject($filter);
    $subjectRecordArray = $subjectManager->getSubjectList($filter,$limit);

//$History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Subject
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:24p
//Created in $/Leap/Source/Library/Subject
//Added new files
?>