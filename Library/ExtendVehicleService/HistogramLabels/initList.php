<?php
//-------------------------------------------------------
// THIS FILE IS USED TO SEARCH FROM HISTOGRAM LABEL
//
//
// Author : Jaineesh
// Created on : (22.10.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/HistogramLabelManager.inc.php");
    $histogramLabelManager = HistogramLabelManager::getInstance();
    
    //Delete code goes here
   
   //to limit records per page     
    $page = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE; 
     /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' WHERE (histogramLabel LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }

    $totalArray = $histogramLabelManager->getTotalHistogramLabel($filter);
    $histogramLabelRecordArray = $histogramLabelManager->getHistogramLabelList($filter,$limit);

// $History: initList.php $ 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/HistogramLabels
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/24/08   Time: 4:23p
//Created in $/Leap/Source/Library/HistogramLabels
//used for paging
//
?>