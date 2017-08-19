<?php

//This file calls Listing Function and creates Global Array in "Display events in Parent " Module 
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $parentManager = ParentManager::getInstance();
    
          
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (eventTitle LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'")';         
    }
   
    $totalArray = $parentManager->getTotalEvents($filter); 
    $parentRecordArray = $parentManager->getEvents($filter,$limit);   
    
?>

<?php 

//$History: initDisplayEvents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Parent
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/31/08    Time: 6:30p
//Updated in $/Leap/Source/Library/Parent
//changed the path of ParentManager file
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/30/08    Time: 7:40p
//Created in $/Leap/Source/Library/Parent
//initial chekin

?>
