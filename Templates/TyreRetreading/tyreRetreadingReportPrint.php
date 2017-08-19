<?php 
//This file is used as printing version for payment history.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/TyreRetreadingManager.inc.php");
    $tyreRetreadingManager = TyreRetreadingManager::getInstance();
    $conditionsArray = array();
    $qryString = "";
    $searchCrieria = "";
    
    
	require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();


    if(isset($REQUEST_DATA['tyreNo']) and $REQUEST_DATA['tyreNo']!=''){
		$filter = ' AND tm.tyreNumber = "'.$REQUEST_DATA['tyreNo'].'"';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busNo';
    
    $orderBy = " $sortField $sortOrderBy";
    
    $tyreRecordArray = $tyreRetreadingManager->getTyreRetreadingReport($filter,$limit,$orderBy);
	$cnt = count($tyreRecordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        if($tyreRecordArray[$i]['retreadingDate']!='0000-00-00'){
         $tyreRecordArray[$i]['retreadingDate']=UtilityManager::formatDate($tyreRecordArray[$i]['retreadingDate']);
        }
        else{
            $tyreRecordArray[$i]['insuranceDueDate']=NOT_APPLICABLE_STRING;
        }
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$tyreRecordArray[$i]);
    }

    $reportManager->setReportWidth(665);
    $reportManager->setReportInformation("For ".$REQUEST_DATA['tyreNo']);
    $reportManager->setReportHeading("Tyre Retreading Report");
     

    $reportTableHead                        =    array();
    
    $reportTableHead['srNo']                =    array('#','width="1%", align="left"', "align='left' ");
    $reportTableHead['busNo']               =    array('Registration No.','width="10%" align="left" ', 'align="left"');
    $reportTableHead['totalRun']            =    array('KM Reading','width="5%" align="right"', 'align="right"');
    $reportTableHead['retreadingDate'] =    array('Retreading Date','width="10%" align="center"','align="center"');
    $reportTableHead['reason']    =    array('Reason','width="10%" align="left"', 'align="left"');
    
    $reportManager->setRecordsPerPage(30);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

    // $History: tyreRetreadingReportPrint.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/02/10    Time: 5:18p
//Created in $/Leap/Source/Templates/TyreRetreading
//new templates for tyre retreading
//
//
?>