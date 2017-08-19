<?php
//-------------------------------------------------------
// Purpose: To store the records of users in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (1.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ManageUsers');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ManageUserManager.inc.php");
    $manageUserManager = ManageUserManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');  
    
    /// Search filter /////  
    $filter = '';
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = 'HAVING (userName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR
                          roleUserName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                          roleName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR
                          displayName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'userName';
    $orderBy = " $sortField $sortOrderBy";         


    //$totalArray = $manageUserManager->getTotalUser($filter);
    $totalArray = $manageUserManager->getUserList($filter,' ',$orderBy);
    $manageUserRecordArray = $manageUserManager->getUserList($filter,$limit,$orderBy);
    $cnt = count($manageUserRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        if ($manageUserRecordArray[$i]['userStatus']==1) {
            $manageUserRecordArray[$i]['userStatus'] =  '<img src='.IMG_HTTP_PATH.'/active.gif border="0" alt="Active" title="Active" width="10" height="10" style="cursor:default">';    
        }
        else {
            $manageUserRecordArray[$i]['userStatus']= '<img src='.IMG_HTTP_PATH.'/deactive.gif border="0" alt="Deactive" title="Deactive" width="10" height="10" style="cursor:default">';    
        }
        
        $valueArray = array_merge(array('action' => $manageUserRecordArray[$i]['userId'] , 'srNo' => ($records+$i+1) ),$manageUserRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    //echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 12/14/09   Time: 6:04p
//Updated in $/LeapCC/Library/ManageUser
//updated code to add new field 'name' that shows name of user
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 28/07/09   Time: 17:53
//Updated in $/LeapCC/Library/ManageUser
//Added "userStatus" field in manage user module and added the check in
//login page that if a user is in active then he/she can not login
//
//*****************  Version 3  *****************
//User: Administrator Date: 13/06/09   Time: 18:59
//Updated in $/LeapCC/Library/ManageUser
//Corredted issues which are detected during user documentation
//preparation
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/28/09    Time: 4:40p
//Updated in $/LeapCC/Library/ManageUser
//New File Added in displayName
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/ManageUser
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:45p
//Updated in $/Leap/Source/Library/ManageUser
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/25/08    Time: 4:34p
//Updated in $/Leap/Source/Library/ManageUser
//Corrected javascript validations
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/01/08    Time: 7:34p
//Updated in $/Leap/Source/Library/ManageUser
//Created ManageUser Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/01/08    Time: 4:08p
//Created in $/Leap/Source/Library/ManageUser
//Initial Checkin
?>
