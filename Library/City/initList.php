<?php
//-------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FROM "city" TABLE AND DELETION AND PAGING
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    //Paging code goes here
    require_once(MODEL_PATH . "/CityManager.inc.php");
    $cityManager = CityManager::getInstance();
    
    
        
    /////////////////////////
    // to limit records per page 
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
    
       //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND (ct.cityName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ct.cityCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR st.stateName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    
     ////////////   
    $totalArray = $cityManager->getTotalCity($filter);
    $cityRecordArray = $cityManager->getCityList($filter,$limit);
    
?>

<?php
// $History: initList.php $
//
//*****************  Version 2  *****************
//User: Administrator Date: 4/06/09    Time: 15:22
//Updated in $/LeapCC/Library/City
//Corrected bugs
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/City
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/25/08    Time: 11:36a
//Updated in $/Leap/Source/Library/City
//Added AjaxEnabled Delete Functionality
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:04p
//Updated in $/Leap/Source/Library/City
//Completed Comment Insertion
?>