<?php
//-------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FROM "testtype" TABLE AND DELETION AND PAGING
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/TestTypeManager.inc.php");
    $testtypeManager = TestTypeManager::getInstance();
    
 
        
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' WHERE (tt.testtypeName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR tt.testtypeCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    $totalArray = $testtypeManager->getTotalTestType($filter);
    $testtypeRecordArray = $testtypeManager->getTestTypeList($filter,$limit);
    
?>

<?php
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/TestType
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/12/08    Time: 1:21p
//Updated in $/Leap/Source/Library/TestType
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/30/08    Time: 11:30a
//Updated in $/Leap/Source/Library/TestType
//Added AjaxList & AjaxSearch Functionality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/16/08    Time: 10:26a
//Updated in $/Leap/Source/Library/TestType
//Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:41p
//Created in $/Leap/Source/Library/TestType
//Initial CheckIn
?>