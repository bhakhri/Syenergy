<?php
//-------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FROM "degree" TABLE AND DELETION AND PAGING
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (13.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/DegreeManager.inc.php");
    $degreeManager = DegreeManager::getInstance();
    
 
    /////pagination code    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
    
    ////filter code
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' WHERE (dg.degreeName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR dg.degreeCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR dg.degreeAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    
    ////
    $totalArray = $degreeManager->getTotalDegree($filter);
    $degreeRecordArray = $degreeManager->getDegreeList($filter,$limit);
    
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Degree
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/25/08    Time: 2:22p
//Updated in $/Leap/Source/Library/Degree
//Adding AjaxEnabled Delete functionality
//*********Solved the problem :**********
//Open 2 browsers opening Degree Masters page. On one page, delete a
//Degree. On the second page, the deleted degree is still visible since
//editing was done on first page. Now, click on the Edit button
//corresponding to the deleted Degree in the second page which was left
//untouched. Provide the new Degree Code and click Submit button.A blank
//popup is displayed. It should rather display "The Degree you are trying
//to edit no longer exists".
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/16/08    Time: 7:24p
//Updated in $/Leap/Source/Library/Degree
//Removing degreeDuratioin Done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/13/08    Time: 11:34a
//Updated in $/Leap/Source/Library/Degree
//Complete
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/13/08    Time: 10:05a
//Created in $/Leap/Source/Library/Degree
//Initial checkin
?>