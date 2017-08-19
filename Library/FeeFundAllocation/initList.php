<?php

//This file calls Delete Function and Listing Function and creates Global Array in "FeeFundAllocation" Module 
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/FeeFundAllocationManager.inc.php");
    $feeFundAllocationManager = FeeFundAllocationManager::getInstance();
    
    //Delete code goes here
 /*   if(UtilityManager::notEmpty($REQUEST_DATA['countryId']) && $REQUEST_DATA['act']=='del') {
            
      //  $recordArray = $countryManager->checkInCity($REQUEST_DATA['countryId']);
      //  if($recordArray[0]['found']==0) {
            if($countryManager->deleteCountry($REQUEST_DATA['countryId']) ) {
                $message = DELETE;
        //    }
        }
        else {
            $message = DEPENDENCY_CONSTRAINT;
        }
    }*/
        
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (allocationEntity LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR entityType LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
   
    $totalArray = $feeFundAllocationManager->getTotalFeeFundAllocation($filter);
    $feeFundAllocationRecordArray = $feeFundAllocationManager->getFeeFundAllocationList($filter,$limit);   
    
?>

<?php 

//$History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeFundAllocation
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/02/08    Time: 10:43a
//Updated in $/Leap/Source/Library/FeeFundAllocation
//modified the filter
//
//*****************  Version 2  *****************
//User: Arvind       Date: 8/01/08    Time: 8:06p
//Updated in $/Leap/Source/Library/FeeFundAllocation
//modified
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:18a
//Created in $/Leap/Source/Library/FeeFundAllocation
//Added files for new module


?>
