<?php
//-------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FROM "quota" TABLE AND DELETION AND PAGING
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    //Paging code goes here
    require_once(MODEL_PATH . "/QuotaManager.inc.php");
    $quotaManager = QuotaManager::getInstance();
    
    
    ///pagination    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE; 
    
    ///filter 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' WHERE (qt.quotaName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR qt.quotaAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    /////
    $totalArray = $quotaManager->getTotalQuota($filter);
    $quotaRecordArray = $quotaManager->getQuotaList($filter,$limit);
?>

<?php
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Quota
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/02/08    Time: 11:44a
//Updated in $/Leap/Source/Library/Quota
//Removed State Field from the quota master
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/25/08    Time: 12:53p
//Updated in $/Leap/Source/Library/Quota
//Added AjaxEnabled Delete Functionality
//Added Input Data Validation using Javascript
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/16/08    Time: 3:50p
//Updated in $/Leap/Source/Library/Quota
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:19p
//Updated in $/Leap/Source/Library/Quota
//Completed Comments Insertion
?>