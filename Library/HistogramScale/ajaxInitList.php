<?php
//-------------------------------------------------------
// Purpose: To store the records of histogram scale in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (22.10.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','HistogramScaleMaster');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/HistogramScaleManager.inc.php");
    $histogramScaleManager = HistogramScaleManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND(hs.histogramRangeFrom LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hs.histogramRangeTo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" )';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'hs.histogramRangeFrom';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $histogramScaleManager->getTotalHistogramScale($filter);
    $histogramScaleRecordArray = $histogramScaleManager->getHistogramScaleList($filter,$limit,$orderBy);
    $cnt = count($histogramScaleRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add designationId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $histogramScaleRecordArray[$i]['histogramScaleId'] , 'srNo' => ($records+$i+1) ),$histogramScaleRecordArray[$i]);

		if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
		}
		else {
            $json_val .= ','.json_encode($valueArray);           
		}
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/HistogramScale
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:39p
//Updated in $/Leap/Source/Library/HistogramScale
//add define access in module
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/25/08   Time: 11:09a
//Updated in $/Leap/Source/Library/HistogramScale
//modified for sorting
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/24/08   Time: 4:26p
//Created in $/Leap/Source/Library/HistogramScale
//used for listing
//
?>