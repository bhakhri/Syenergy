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
    require_once(MODEL_PATH . "/BusManager.inc.php");
    $busManager = BusManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    $conditionsArray = array();
    $qryString = "";
    $searchCrieria = "";
    
    
    if(isset($REQUEST_DATA['busId']) and $REQUEST_DATA['busId']!=''){
     $filter = ' AND ( bs.busId IN ('.$REQUEST_DATA['busId'].') AND bi.insuranceDueDate BETWEEN "'.$REQUEST_DATA['fromDate'].'" AND "'.$REQUEST_DATA['toDate'].'" )';
      $searchCrieria=' Bus : '.$REQUEST_DATA['busNameStr'].' From: '.UtilityManager::formatDate($REQUEST_DATA['fromDate']).' To : '.UtilityManager::formatDate($REQUEST_DATA['toDate']);
    }
    else{
      $filter = ' WHERE ( insuranceDueDate BETWEEN "'.$REQUEST_DATA['fromDate'].'" AND "'.$REQUEST_DATA['toDate'].'" )';
      $searchCrieria=' All Bus From: '.UtilityManager::formatDate($REQUEST_DATA['fromDate']).' To : '.UtilityManager::formatDate($REQUEST_DATA['toDate']);
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busName';
    
    $orderBy = " $sortField $sortOrderBy";
    
    $busRecordArray = $busManager->getBusList($filter,' ',$orderBy);  

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    
    

    $cnt = count($busRecordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        if($busRecordArray[$i]['insuranceDueDate']!='0000-00-00'){
         $busRecordArray[$i]['insuranceDueDate']=UtilityManager::formatDate($busRecordArray[$i]['insuranceDueDate']);
        }
        else{
            $busRecordArray[$i]['insuranceDueDate']=NOT_APPLICABLE_STRING;
        }
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$busRecordArray[$i]);
    }

    $reportManager->setReportWidth(665);
    $reportManager->setReportInformation("For ".$searchCrieria." As On $formattedDate ");
    $reportManager->setReportHeading("Insurance Due  Report");
     

    $reportTableHead                        =    array();
    
    $reportTableHead['srNo']                =    array('#','width="1%"', "align='center' ");
    $reportTableHead['busName']                =    array('Name','width=10% align="left"', 'align="left"');
    $reportTableHead['busNo']                =    array('Registration No.','width="10%" align="left" ', 'align="left"');
    $reportTableHead['isActive']            =    array('In Service','width="5%" align="left"', 'align="left"');
    $reportTableHead['insuringCompanyName']        =    array('Insuring Company','width="10%" align="left"','align="left"');
	$reportTableHead['policyNo']        =    array('Policy No.','width="10%" align="left"','align="left"');
    $reportTableHead['insuranceDueDate']    =    array('Due Date','width="10%" align="center"', 'align="center"');
    
    $reportManager->setRecordsPerPage(30);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

    // $History: insuranceDueReportPrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:00
//Updated in $/LeapCC/Templates/Bus
//Copied bus master enhancements from leap to leapcc
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Templates/Bus
//Updated fleet mgmt file in Leap 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/09    Time: 13:26
//Created in $/SnS/Templates/Bus
//Added "InsuranceDue Report" module
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 27/03/09   Time: 14:45
//Updated in $/SnS/Templates/Student
//Corrected logic
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 2/14/09    Time: 2:25p
//Updated in $/SnS/Templates/Student
//Corrected Individual Student Report Print
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/01/09   Time: 12:12
//Created in $/SnS/Templates/Student
//Added Sns System to VSS(Leap for Chitkara International School)
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/14/09    Time: 12:25p
//Updated in $/LeapCC/Templates/Student
//Updated search filter with permanent cityid, stateId and countryid
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/23/08   Time: 11:16a
//Updated in $/LeapCC/Templates/Student
//Added group filter in student search
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 5:50p
//Updated in $/LeapCC/Templates/Student
//updated functionality as per CC
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 9/17/08    Time: 10:48a
//Updated in $/Leap/Source/Templates/Student
//updated as respect to subject centric
?>