<?php
//-------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FROM "department" TABLE AND DELETION AND PAGING
//
//
// Author : Jaineesh
// Created on : (20.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/DepartmentManager.inc.php");
    $departmentManager = DepartmentManager::getInstance();
    
 
    /////pagination code    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
    
    ////filter code
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' WHERE (departmentName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR abbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    ////
    $totalArray = $departmentManager->getTotalDepartment($filter);
    $departmentRecordArray = $departmentManager->getDepartmentList($filter,$limit);
    
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Department
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/20/08   Time: 5:50p
//Created in $/Leap/Source/Library/Department
//used for paging & sorting
//

?>