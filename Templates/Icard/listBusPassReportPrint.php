<?php 
//This file is used as printing version for Bus Pass Report.
//
// Author :Parveen Sharma
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	set_time_limit(0);  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    require_once(MODEL_PATH . "/StudentManager.inc.php");     
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    define('MODULE','DisplayBusPassReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();    
    
       
    $studentManager = StudentManager::getInstance();       
    $studentReportManager = StudentReportsManager::getInstance();
    
    $dateFormat = add_slashes($REQUEST_DATA['dateFormat']);       

    $startDate = add_slashes($REQUEST_DATA['startDate']);
    $endDate= add_slashes($REQUEST_DATA['endDate']);
    
    $busStopId = add_slashes($REQUEST_DATA['busStopId']) ;
    $busRouteId = add_slashes($REQUEST_DATA['busRouteId']);
    
    $classId = add_slashes($REQUEST_DATA['classId']) ;
    $sRollNo = add_slashes($REQUEST_DATA['sRollNo']);
    $sName = add_slashes($REQUEST_DATA['sName']);
    $sReceiptNo = add_slashes($REQUEST_DATA['sReceiptNo']); 
 
    $search = '';
    $search1 = '';
    $search2 = '';
    $conditions ='';  
    $chk = '';   
    
    if($busStopId!='') {
      $conditions .= " AND a.busStopId IN ($busStopId) ";
  
       // Findout Bus Stoppage
       $findValue="";
       $foundArray = $studentManager->getSingleField('bus_stop', 'stopName', "WHERE busStopId IN ($busStopId) ");
       for($i=0;$i<count($foundArray);$i++) {
         if($findValue=='') { 
           $findValue = $foundArray[$i]['stopName'];
         }
         else {
           $findValue .= ", ".$foundArray[$i]['stopName'];
         }
       }
       $search .= "<br>Bus Stoppage&nbsp;:&nbsp;".$findValue; 
    }
    
    if($busRouteId!='') {
       $conditions .= " AND a.busRouteId IN ($busRouteId) ";

       // Findout Bus Route
       $foundArray = $studentManager->getSingleField('bus_route', 'routeCode', "WHERE busRouteId IN ($busRouteId) ");
       for($i=0;$i<count($foundArray);$i++) {
         if($findValue=='') { 
           $findValue = $foundArray[$i]['routeCode'];
         }
         else {
           $findValue .= ", ".$foundArray[$i]['routeCode'];
         }
       }
       $search .= "<br>Bus Route&nbsp;:&nbsp;".$findValue; 
    }
    
    
    if($dateFormat==1) {
       $conditions  .= " AND ( (validUpto >= '$startDate' AND validUpto <= '$endDate') ";     
       if($startDate!='') {
         $search1 .= "Bus Pass Expiry Date From ".UtilityManager::formatDate($startDate);
       }
       if($endDate!='') {
         $search1 .= " To ".UtilityManager::formatDate($endDate);
       }
    }
    else {
       $conditions  .= " AND ( (addedOnDate >= '$startDate' AND addedOnDate <= '$endDate') ";       
       if($startDate!='') {
         $search1 .= "Bus Pass Issue Date From ".UtilityManager::formatDate($startDate);
       }
       if($endDate!='') {
         $search1 .= " To ".UtilityManager::formatDate($endDate);
       }
    }
    
    
    
    if($classId!='') {
       $chk = '1';
       $conditions .= " AND a.classId = '$classId' ";   
     
      // Findout Class Name
      $classNameArray = $studentManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "WHERE classId = $classId ");
      $className = $classNameArray[0]['className'];
      $className2 = str_replace("-",' ',$className);
      
      $search .= "<br>Class&nbsp;:&nbsp;".$className2; 
      
    }
   
    if($sRollNo!='') {
       $chk = '1';
       $conditions .= " AND ( a.rollNo LIKE ('$sRollNo%') OR a.regNo LIKE ('$sRollNo%') ) ";   
       $search .= "<br>Roll No.&nbsp;:&nbsp;".$sRollNo;  
    }
    
    if($sName!='') {   
       $chk = '1'; 
       $conditions .= " AND CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) LIKE ('$sName%')";    
       $search .= "<br>Student Name&nbsp;:&nbsp;".$sName;  
    }
    
    if($sReceiptNo!='') {
       $chk = '1';
       $conditions .= " AND bpass.receiptNo LIKE ('$sReceiptNo%')";  
       $search .= "<br>Receipt No.&nbsp;:&nbsp;".$sReceiptNo;   
    }
    
    $conditions .= " ) ";
    
    if($chk==1) {
      $search2 = $search;
    }
    else {
      $search2 = $search1.$search;
    }
     
    $json_val = "";       
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy";
  
    
    $studentRecordArray = $studentReportManager->getAllBusPassDetails($conditions, $orderBy);
    $cnt1 = count($studentRecordArray);
 
    
    for($i=0;$i<$cnt1;$i++) {
        if($studentRecordArray[$i]['validUpto']=='0000-00-00') {
           $studentRecordArray[$i]['validUpto'] = NOT_APPLICABLE_STRING;
        }
        else{
           $studentRecordArray[$i]['validUpto'] = UtilityManager::formatDate($studentRecordArray[$i]['validUpto']);
        }
        
        if($studentRecordArray[$i]['addedOnDate']=='0000-00-00') {
           $studentRecordArray[$i]['addedOnDate'] = NOT_APPLICABLE_STRING; 
        }
        else{
           $studentRecordArray[$i]['addedOnDate'] = UtilityManager::formatDate($studentRecordArray[$i]['addedOnDate']);  
        }
        
        if($studentRecordArray[$i]['cancelOnDate']=='0000-00-00') {
           $studentRecordArray[$i]['cancelOnDate'] = NOT_APPLICABLE_STRING;     
        }
        else{
           $studentRecordArray[$i]['cancelOnDate'] = UtilityManager::formatDate($studentRecordArray[$i]['cancelOnDate']);   
        }
        
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1)),$studentRecordArray[$i]);
    }   
    
	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Display Bus Pass Report');
    $reportManager->setReportInformation("Search By: $search2");
	 
	$reportTableHead						   =  array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']		 =  array('#','width="3%" align="left"', "align='left' ");
    $reportTableHead['studentName']  =  array('Name','width=15% align="left"', 'align="left"');
	$reportTableHead['className']	 =  array('Current Class','width=14% align="left"', 'align="left"');
	$reportTableHead['rollNo']       =  array('Roll No.','width="10%" align="left" ', 'align="left"');
    $reportTableHead['routeCode']    =  array('Bus Route','width="12%" align="left" ', 'align="left"');
    $reportTableHead['stopName']     =  array('Bus Stoppage','width=12% align="left"', 'align="left"');
    $reportTableHead['receiptNo']    =  array('Receipt No.','width=11% align="left"', 'align="left"');
    $reportTableHead['addedOnDate']  =  array('Issue Date','width=11% align="center"', 'align="center"');
    $reportTableHead['validUpto']    =  array('Expiry Date','width=11% align="center"', 'align="center"');
    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: listBusPassReportPrint.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/12/10    Time: 3:14p
//Updated in $/LeapCC/Templates/Icard
//bus receiptNo. field added (format & validation updated)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/02/10    Time: 4:41p
//Updated in $/LeapCC/Templates/Icard
//report fieldHeading Name updated (Current Class)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/02/10    Time: 4:34p
//Updated in $/LeapCC/Templates/Icard
//format and validation updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/02/10    Time: 3:56p
//Created in $/LeapCC/Templates/Icard
//inital checkin
//

?>