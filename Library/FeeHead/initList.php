<?php

//This file calls Delete Function and Listing Function and creates Global Array in Country Module 
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/FeeHeadManager.inc.php");
    $feeHeadManager = FeeHeadManager::getInstance();
    
   
      
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (c.headName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.headAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ins.instituteName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';             
    }
   
    $totalArray = $feeHeadManager->getTotalFeeHead($filter);
    $feeHeadRecordArray = $feeHeadManager->getFeeHeadList($filter,$limit); 
    
//$History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeHead
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/15/08    Time: 6:22p
//Updated in $/Leap/Source/Library/FeeHead
//modifed the field names
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:19a
//Created in $/Leap/Source/Library/FeeHead
//Added new library files for "FeeHead" Module
?>
