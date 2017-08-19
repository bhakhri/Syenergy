<?php
//-------------------------------------------------------
// Purpose: To store the records of states in array from the database, pagination and search, delete 
// functionality
//
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(MODEL_PATH . "/SubjectTopicManager.inc.php"); 
    $subjecttopicManager = SubjectTopicManager::getInstance();

    /////////////////////////             
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
         $filter = ' AND (sub.subjectCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR st.topic LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR st.topicAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';    
    }
    ////////////
    
    $totalArray = $subjecttopicManager->getTotalSubjectTopic($filter);
    $subjecttopicRecordArray = $subjecttopicManager->getSubjectTopicList($filter,$limit);

// for VSS
// $History: initList.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/30/09    Time: 10:38a
//Updated in $/LeapCC/Library/SubjectTopic
//search condition subject code update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/16/09    Time: 2:16p
//Created in $/LeapCC/Library/SubjectTopic
//subject topic file added
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 8/27/08    Time: 3:45p
//Updated in $/Leap/Source/Library/States
//optimized code and  removed trailing spaces
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:57p
//Updated in $/Leap/Source/Library/States
//removed delete code
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/13/08    Time: 4:53p
//Updated in $/Leap/Source/Library/States
//Added comments header and other action comments
?>