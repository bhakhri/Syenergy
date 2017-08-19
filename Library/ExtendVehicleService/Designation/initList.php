<?php
//-------------------------------------------------------
// THIS FILE IS USED TO SEARCH FROM DESIGNATION
//
//
// Author : Jaineesh
// Created on : (13.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/DesignationManager.inc.php");
    $designationManager = DesignationManager::getInstance();
    
    //Delete code goes here
   
   //to limit records per page     
    $page = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE; 
     /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' WHERE (designationName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR designationCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }

    $totalArray = $designationManager->getTotalDesignation($filter);
    $designationRecordArray = $designationManager->getDesignationList($filter,$limit);

// $History: initList.php $ 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Designation
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:17p
//Updated in $/Leap/Source/Library/Designation
//modified in indentation
?>