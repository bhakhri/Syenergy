<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database, pagination and search, delete 
// functionality
//
// Author : Rajeev Aggarwal
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(MODEL_PATH . "/ClassManager.inc.php");
    $classManager = ClassManager::getInstance();

    /////////////////////////
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' (className LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" )';         
    }
    ////////////
    
    $totalArray = $classManager->getTotalClass($filter);
    $classRecordArray = $classManager->getClassList($filter,$limit);
    

// for VSS
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Class
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/02/08    Time: 10:59a
//Created in $/Leap/Source/Library/Class
//intial checkin
   
?>