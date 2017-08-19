<?php
//-------------------------------------------------------
// Purpose: To store the records of lecture type in array from the database, pagination and search, delete 
// functionality
//
// Author : Rajeev Aggarwal
// Created on : (11.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','LectureTypeMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/LectureManager.inc.php");
    $lectureManager = LectureTypeManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' (lectureName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" )';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'lectureName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $lectureManager->getTotalLectureType($filter);
    $lectureRecordArray = $lectureManager->getLectureTypeList($filter,$limit,$orderBy);
    $cnt = count($lectureRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $lectureRecordArray[$i]['lecturetypeId'] , 'srNo' => ($records+$i+1) ),$lectureRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 3:15p
//Updated in $/LeapCC/Library/Lecture
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Lecture
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/27/08    Time: 2:59p
//Updated in $/Leap/Source/Library/Lecture
//updated formatting
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/17/08    Time: 11:33a
//Updated in $/Leap/Source/Library/Lecture
//updated issue no 0000062,0000061,0000070
 
?>