<?php

//This file calls Delete Function and Listing Function and creates Global Array in Country Module 
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/FeeCycleFineManager.inc.php");
    $feeCycleFineManager = FeeCycleFineManager::getInstance();
    
   
        
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
	 if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (fc.cycleName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR fh.headName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR fcf.fromDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR fcf.toDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR fcf.fineAmount LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR fcf.fineType LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $totalArray = $feeCycleFineManager->getTotalFeeCycleFine($filter);
    $feeCycleFineRecordArray = $feeCycleFineManager->getFeeCycleFineList($filter,$limit);   
    
?>

<?php 

//$History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeCycleFine
//
//*****************  Version 2  *****************
//User: Arvind       Date: 8/02/08    Time: 11:04a
//Updated in $/Leap/Source/Library/FeeCycleFine
//modified the filter
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:17a
//Created in $/Leap/Source/Library/FeeCycleFine
//Added library files of" feecyclefine" module


?>
