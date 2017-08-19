<?php

//This file calls Delete Function and Listing Function and creates Global Array in Periodicity Module 
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/PeriodicityManager.inc.php");
    $periodicityManager = PeriodicityManager::getInstance();
    
    //Delete code goes here
    if(UtilityManager::notEmpty($REQUEST_DATA['periodicityId']) && $REQUEST_DATA['act']=='del') {
            
      //  $recordArray = $countryManager->checkInCity($REQUEST_DATA['countryId']);
      //  if($recordArray[0]['found']==0) {
            if($periodicityManager->deletePeriodicity($REQUEST_DATA['periodicityId']) ) {
                $message = DELETE;
        //    }
        }
        else {
            $message = DEPENDENCY_CONSTRAINT;
        }
    }
        
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (periodicityName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR periodicityCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    $totalArray = $periodicityManager->getTotalPeriodicity($filter);  
    $periodicityRecordArray = $periodicityManager->getPeriodicityList($filter,$limit); 
    
    //print_r($periodicityRecordArray);      
?>

<?php 

////$History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Periodicity
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/16/08    Time: 11:22a
//Updated in $/Leap/Source/Library/Periodicity
//modifified
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:04p
//Updated in $/Leap/Source/Library/Periodicity
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:19p
//Created in $/Leap/Source/Library/Periodicity
//NEw Files Added in Periodicity Folder

?>
