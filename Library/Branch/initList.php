<?php
//This file calls Delete Function and Listing Function and creates Global Array in Branch Module 
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/BranchManager.inc.php");
    $branchManager = BranchManager::getInstance();
    //Delete code goes here
    if(UtilityManager::notEmpty($REQUEST_DATA['branchId']) && $REQUEST_DATA['act']=='del') {
            if($branchManager->deleteBranch($REQUEST_DATA['branchId']) ) {
                $message = DELETE;
	        }
		    else {
	            $message = DEPENDENCY_CONSTRAINT;
	        }
    }
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (branchName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR branchCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $totalArray = $branchManager->getTotalBranch($filter);
    $branchRecordArray = $branchManager->getBranchList($filter,$limit);
//$History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Branch
//
//*****************  Version 4  *****************
//User: Arvind       Date: 8/27/08    Time: 4:40p
//Updated in $/Leap/Source/Library/Branch
//removed spaces
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/16/08    Time: 11:21a
//Updated in $/Leap/Source/Library/Branch
//modified
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:03p
//Updated in $/Leap/Source/Library/Branch
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:19p
//Created in $/Leap/Source/Library/Branch
//NEw Files Added in Branch Folder
?>