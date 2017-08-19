<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Gurkeerat Sidhu
// Created on : (15-09-2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
      global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    

    require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
    $itemsManager = ItemsManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    $conditionsArray = array();

    global  $UnitOfMeasurementArray;
    global  $packagingArray;
    
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       
       $filter = '  WHERE (itemCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR  minimumQty LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR availableQty LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR itemName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" ';
       
       foreach($UnitOfMeasurementArray as $key=>$value)
       {
         if(stristr($value,add_slashes($REQUEST_DATA['searchbox']))) {  
           $filter .= " OR unitOfMeasure LIKE '%$key%' ";
           break;
         }
       }      
       
       foreach($packagingArray as $key=>$value)
       {
         if(stristr($value,add_slashes($REQUEST_DATA['searchbox']))) {  
           $filter .= " OR packaging LIKE '%$key%' ";
           break;
         }
       }      
       $filter .= ")"; 
    }

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


                   $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'itemCode';
    
     $orderBy = " $sortField $sortOrderBy";
     
     if($filter== '' ) {
       $filter = " WHERE availableQty <= minimumQty ";
     }
     else {
       $filter .= " AND availableQty <= minimumQty ";
     }
     
    $recordArray = $itemsManager->getItemReorderList($filter,$orderBy,'');
     
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $recordArray[$i]['unitOfMeasure']=$UnitOfMeasurementArray[$recordArray[$i]['unitOfMeasure']];
       $recordArray[$i]['packaging']=$packagingArray[$recordArray[$i]['packaging']];
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

    $reportManager->setReportWidth(665);
    $reportManager->setReportHeading('Items Reorder Report');
    $reportManager->setReportInformation("Search By: ".$REQUEST_DATA['searchbox']);
     
    $reportTableHead                      =    array();
    //associated key                  col.label,            col. width,      data align    
    $reportTableHead['srNo'] =    array('#','width="3%" align="left"',"align='left'");
    $reportTableHead['itemCode'] =   array('Item Code','width=15% align="left"', 'align="left"');
    $reportTableHead['itemName'] =    array('Item Name','width=15% align="left"', 'align="left"');
    $reportTableHead['unitOfMeasure'] =    array('Unit','width="15%" align="left" ', 'align="left"');
    $reportTableHead['packaging'] =    array('Packaging','width="15%" align="left" ', 'align="left"');
    $reportTableHead['availableQty'] =    array('Available Qty.','width="15%" align="right" ', 'align="right"');
    $reportTableHead['minimumQty'] =    array('Min. Qty.','width="15%" align="right" ', 'align="right"');
    $reportManager->setRecordsPerPage(30);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

?>
