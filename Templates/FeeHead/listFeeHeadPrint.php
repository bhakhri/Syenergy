 <?php 
//This file is used as printing version for fee head.
//
// Author :Parveen Sharma
// Created on : 21.10.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');     
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeeHeadManager.inc.php");
    $feeHeadManager =FeeHeadManager::getInstance();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  
    
    /// Search filter /////  
     if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $str = "";
       if(add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'yes' || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'y' || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'ye') 
          $str = ' OR c.isRefundable = "1" OR c.isVariable = "1" OR c.isConsessionable = "1"';
       else if(add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'no'  || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'n') 
          $str = ' OR c.isRefundable = "0" OR c.isVariable = "0" OR c.isConsessionable = "0"';  
          
       $filter = ' AND (c.headName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.headAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.sortingOrder LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" '.$str.')';         
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'headName';
    
    $orderBy = " $sortField $sortOrderBy";         

    $feeHeadRecordArray = $feeHeadManager->getFeeHeadList($filter,'',$orderBy);
   
    $cnt = count($feeHeadRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $feeHeadRecordArray[$i]['srNo'] =  ($i+1);
       $feeHeadRecordArray[$i]['isRefundable'] = $feeHeadRecordArray[$i]['isRefundable'] == 1 ? 'Yes' : 'No' ;
       $feeHeadRecordArray[$i]['isVariable']   = $feeHeadRecordArray[$i]['isVariable'] == 1 ? 'Yes' : 'No' ;
       $feeHeadRecordArray[$i]['isConsessionable']   = $feeHeadRecordArray[$i]['isConsessionable'] == 1 ? 'Yes' : 'No' ;    
       $feeHeadRecordArray[$i]['sortingOrder'] = $feeHeadRecordArray[$i]['sortingOrder'] != ''? $feeHeadRecordArray[$i]['sortingOrder'] : "0" ;
	}
                           
                           
    $search = $REQUEST_DATA['searchbox'];
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Fee Head Report');
    $reportManager->setReportInformation("SearchBy: $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                    ' width="2%" align="left"', "align='left'");
    $reportTableHead['headName']			=    array('Name',                 ' width=30% align="left" ','align="left" ');
    $reportTableHead['headAbbr']			=    array('Abbr.',                ' width="12%" align="left" ','align="left"');
    $reportTableHead['isRefundable']        =    array('Refundable Security',  ' width="18%" align="center" ','align="center"');
    $reportTableHead['isConsessionable']    =    array('Concessionable',       ' width="14%" align="center" ','align="center"');
    $reportTableHead['isVariable']          =    array('Miscellaneous',           ' width="12%" align="center" ','align="center"');
    $reportTableHead['sortingOrder']        =    array('Display Order',        ' width="12%" align="right" ','align="right"');
    /*
    $reportTableHead['parentHead']            =    array('Parent Head',  ' width="14%" align="left" ','align="left"');
    $reportTableHead['applicableToAll']        =    array('Applicable to all<br><span style="font-size:9px">(Categories i.e. Gen/SC/ST)</span>', 'width="15%" align="center" ','align="center"');
    $reportTableHead['transportHead']       =    array('Transport Head',  ' width="13%" align="center" ','align="center"');
    $reportTableHead['hostelHead']          =    array('Hostel Head',  ' width="10%" align="center" ','align="center"');
    $reportTableHead['miscHead']          =    array('Misc. Head',  ' width="10%" align="center" ','align="center"');
    $reportTableHead['isConsessionable']    =    array('Concessionable',  ' width="13%" align="center" ','align="center"');
    */
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $feeHeadRecordArray);
    $reportManager->showReport(); 

//$History : listFeeHeadPrint.php $
//
?>
