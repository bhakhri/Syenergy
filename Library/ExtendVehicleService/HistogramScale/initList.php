<?php
//-------------------------------------------------------
// THIS FILE IS USED TO SEARCH FROM HISTOGRAM SCALE
//
//
// Author : Jaineesh
// Created on : (22.10.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/HistogramScaleManager.inc.php");
    $histogramScaleManager = HistogramScaleManager::getInstance();
    
    //Delete code goes here
   
   //to limit records per page     
    $page = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE; 
     /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' AND(hs.histogramRangeFrom LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hs.histogramRangeTo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" )';         
    }

    $totalArray = $histogramScaleManager->getTotalHistogramScale($filter);
    $histogramScaleRecordArray = $histogramScaleManager->getHistogramScaleList($filter,$limit);

// $History: initList.php $ 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/HistogramScale
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/24/08   Time: 4:26p
//Created in $/Leap/Source/Library/HistogramScale
//used for listing
//
?>