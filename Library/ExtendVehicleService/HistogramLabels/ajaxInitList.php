<?php
//-------------------------------------------------------
// Purpose: To store the records of histogram label in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (22.10.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','HistogramLabelMaster');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/HistogramLabelManager.inc.php");
    $histogramLabelManager = HistogramLabelManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (histogramLabel LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'histogramLabel';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $histogramLabelManager->getTotalHistogramLabel($filter);
    $histogramLabelRecordArray = $histogramLabelManager->getHistogramLabelList($filter,$limit,$orderBy);
    $cnt = count($histogramLabelRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add designationId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $histogramLabelRecordArray[$i]['histogramId'] , 'srNo' => ($records+$i+1) ),$histogramLabelRecordArray[$i]);

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
//Created in $/LeapCC/Library/HistogramLabels
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:40p
//Updated in $/Leap/Source/Library/HistogramLabels
//add define access in module
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/24/08   Time: 4:23p
//Created in $/Leap/Source/Library/HistogramLabels
//used for listing
//
?>