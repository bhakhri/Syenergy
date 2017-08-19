<?php
//-------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FROM "role" TABLE AND DELETION AND PAGING
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/RoleManager.inc.php");
    $roleManager = UserRoleManager::getInstance();
    
    
        
    /////////////////////////
    // to limit records per page 
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
    
       //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' WHERE (rl.roleName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
     ////////////   
    $totalArray = $roleManager->getTotalRole($filter);
    $roleRecordArray = $roleManager->getRoleList($filter,$limit);

// $History: initList.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/20/09    Time: 2:00p
//Updated in $/LeapCC/Library/Role
//added role permission module for user other than admin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Role
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/10/08    Time: 5:22p
//Updated in $/Leap/Source/Library/Role
//Created Role(Role Master) Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 2:58p
//Created in $/Leap/Source/Library/Role
//Initial checkin
?>