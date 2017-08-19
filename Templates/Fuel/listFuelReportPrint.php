<?php 
//This file is used as printing version for payment history.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FuelMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/FuelManager.inc.php");
    $tranportManager = FuelManager::getInstance();

    $busId = $REQUEST_DATA['busId'];
	$filter = " AND f.busId = $busId AND f.dated BETWEEN '".$REQUEST_DATA['fromDate']."' AND '".$REQUEST_DATA['toDate']."'";
    $busRecordArray = $tranportManager->getFuelList($filter,' ','f.addedOnDate DESC');  
	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    
	$cnt = count($busRecordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
         
        $busRecordArray[$i]['dated']=UtilityManager::formatDate($busRecordArray[$i]['dated']);
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$busRecordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportInformation("For ".$searchCrieria." As On $formattedDate ");
	$reportManager->setReportHeading("Fuel Usage Report for: ".$busRecordArray[0]['busNo']);
	 

	$reportTableHead						=	array();
	
	$reportTableHead['srNo']				=	array('#','width="1%"', "align='center' ");
	$reportTableHead['name']			    =	array('Staff','width=30% align="left"', 'align="left"');
	$reportTableHead['busNo']			    =	array('Bus','width="10%" align="left" ', 'align="left"');
	$reportTableHead['dated']			=	array('Date','width="5%" align="left"', 'align="left"');
	$reportTableHead['lastMilege']		=	array('Last Mileage','width="12%" align="right"','align="right"');
	$reportTableHead['currentMilege']	=	array('Current Mileage','width="14%" align="right"', 'align="right"');
	$reportTableHead['litres']	=	array('Litres','width="10%" align="right"', 'align="right"');
	$reportTableHead['amount']	=	array('Amount','width="8%" align="right"', 'align="right"');
	
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: listFuelReportPrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 5/08/09    Time: 17:27
//Updated in $/Leap/Source/Templates/Fuel
//Done bug fixing.
//bug ids--
//0000878 to 0000883
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/15/09    Time: 4:58p
//Created in $/Leap/Source/Templates/Fuel
//Intial checkin of print report of fuel usage of bus
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/15/09    Time: 11:04a
//Created in $/SnS/Templates/Fuel
//Intial checkin

?>