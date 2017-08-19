<?php

//This file calls Delete Function and Listing Function and creates Global Array in "FEE HEAD VALUES" Module 
//
//
// Author :Arvind Singh Rawat
// Created on : 18-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/FeeHeadValuesManager.inc.php");
    $feeHeadValuesManager = FeeHeadValuesManager::getInstance();
    
  
        
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' WHERE (fc.cycleName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR fh.headName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ffa.allocationEntity LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';          
    }
   
    $totalArray = $feeHeadValuesManager->getTotalFeeHeadValues($filter);
    $feeHeadValuesRecordArray = $feeHeadValuesManager->getFeeHeadValuesList($filter,$limit);
	//print_r($feeHeadValuesRecordArray);   
    
 

//$History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeHeadValues
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/25/08    Time: 12:19p
//Updated in $/Leap/Source/Library/FeeHeadValues
//modified the filter
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/19/08    Time: 12:45p
//Created in $/Leap/Source/Library/FeeHeadValues
//initial checkin


?>
