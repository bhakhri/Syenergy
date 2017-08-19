<?php
//-------------------------------------------------------
// Purpose: To store the records of group in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','GroupMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/GroupManager.inc.php");
    $groupManager = GroupManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (c.groupName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR c.groupShort LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR gt.groupTypeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR cl.className LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'groupName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $groupManager->getTotalGroup($filter);
    $groupRecordArray = $groupManager->getGroupList($filter,$limit,$orderBy);
    $cnt = count($groupRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add groupId in actionId to populate edit/delete icons in User Interface   
        if($groupRecordArray[$i]['parentGroup']==''){
            $groupRecordArray[$i]['parentGroup']=NOT_APPLICABLE_STRING;
        }
        $valueArray = array_merge(array('action' => $groupRecordArray[$i]['groupId'] , 'srNo' => ($records+$i+1) ),$groupRecordArray[$i]);

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
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/22/09   Time: 6:37p
//Updated in $/LeapCC/Library/Group
//fixed bug Nos.0001858, 0001872, 0001870, 0001868, 0001867, 0001865,
//0001856, 0001866
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/05/09   Time: 6:31p
//Updated in $/LeapCC/Library/Group
//fixed bug nos.0001684, 0001689, 0001688, 0001687, 0001685, 0001686,
//0001683, 0001629 and report for academic head privileges
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/10/09    Time: 11:56a
//Updated in $/LeapCC/Library/Group
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/28/09    Time: 6:40p
//Updated in $/LeapCC/Library/Group
//fixed bug nos.0000574, 0000575, 0000576, 0000577, 0000578, 0000579,
//0000580, 0000581
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Group
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/28/08    Time: 1:11p
//Updated in $/Leap/Source/Library/Group
//modified in indentation
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/17/08    Time: 8:05p
//Updated in $/Leap/Source/Library/Group
//modified for add & edit
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/15/08    Time: 6:04p
//Updated in $/Leap/Source/Library/Group
//modified for edit 
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/05/08    Time: 11:06a
//Updated in $/Leap/Source/Library/Group
//modified in functions for edit, add
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/03/08    Time: 7:04p
//Created in $/Leap/Source/Library/Group
//containing functions for add, edit, delete or search
?>