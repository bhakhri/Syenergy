<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
       
    $studentReportManager = StudentReportsManager::getInstance();
    $studentManager = StudentManager::getInstance();  
    
    $dateFormat = add_slashes($REQUEST_DATA['dateFormat']);       

    $startDate = add_slashes($REQUEST_DATA['startDate']);
    $endDate= add_slashes($REQUEST_DATA['endDate']);
    
    $busStopId = add_slashes($REQUEST_DATA['busStopId']) ;
    $busRouteId = add_slashes($REQUEST_DATA['busRouteId']);
    
    $classId = add_slashes($REQUEST_DATA['classId']) ;
    $sRollNo = add_slashes($REQUEST_DATA['sRollNo']);
    $sName = add_slashes($REQUEST_DATA['sName']);
    $sReceiptNo = add_slashes($REQUEST_DATA['sReceiptNo']); 
    
    
     //to parse csv values    
    function parseCSVComments($comments) {
       $comments = str_replace('"', '""', $comments);
       $comments = str_ireplace('<br/>', "\n", $comments);
       if(eregi(",", $comments) or eregi("\n", $comments)) {
          return '"'.$comments.'"'; 
       } 
       else {
          return $comments.chr(160); 
       }
    }

 
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
       $search .= "\nBus Stoppage,".parseCSVComments($findValue); 
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
       $search .= "\nBus Route,".parseCSVComments($findValue); 
    }
    
    if($dateFormat==1) {
       $conditions  .= " AND ( (validUpto >= '$startDate' AND validUpto <= '$endDate') ";     
       if($startDate!='') {
         $search1 .= "Bus Pass Expiry Date From,".UtilityManager::formatDate($startDate);
       }
       if($endDate!='') {
         $search1 .= ",To,".UtilityManager::formatDate($endDate);
       }
    }
    else {
       $conditions  .= " AND ( (addedOnDate >= '$startDate' AND addedOnDate <= '$endDate') ";       
       if($startDate!='') {
         $search1 .= "Bus Pass Issue Date From,".UtilityManager::formatDate($startDate);
       }
       if($endDate!='') {
         $search1 .= ", To,".UtilityManager::formatDate($endDate);
       }
    }
    
    if($classId!='') {
       $conditions .= " AND a.classId = '$classId' ";  
       
      // Findout Class Name
      $classNameArray = $studentManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "WHERE classId = $classId ");
      $className = $classNameArray[0]['className'];
      $className2 = str_replace("-",' ',$className);
      
      $search .= "\nClass,".$className2;    
      $chk = '1';   
    }
   
     if($sRollNo!='') {
       $chk = '1';   
       $conditions .= " AND ( a.rollNo LIKE ('$sRollNo%') OR a.regNo LIKE ('$sRollNo%') ) ";   
       $search .= "\nRoll No.,".parseCSVComments($sRollNo);  
    }
    
    if($sName!='') {
       $chk = '1';    
       $conditions .= " AND CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) LIKE ('$sName%')";    
       $search .= "\nStudent Name,".parseCSVComments($sName);  
    }
    
    if($sReceiptNo!='') {
       $chk = '1';    
       $conditions .= " AND bpass.receiptNo LIKE ('$sReceiptNo%')";  
       $search .= "\nReceipt No.,".parseCSVComments($sReceiptNo);     
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
  
    
    $record = $studentReportManager->getAllBusPassDetails($conditions, $orderBy);
    $cnt = count($record);
    
    $csvData = '';   
    $csvData .= "Search By\n".$search2."\n";
    $csvData .= "Sr. No.,Name,Current Class,Roll No.,Bus Route,Bus Stoppage,Receipt No.,Issue Date,Expiry Date \n";
    
    for($i=0;$i<$cnt;$i++) {
        if($record[$i]['validUpto']=='0000-00-00') {
           $record[$i]['validUpto'] = NOT_APPLICABLE_STRING;
        }
        else{
           $record[$i]['validUpto'] = UtilityManager::formatDate($record[$i]['validUpto']);
        } 
        
        if($record[$i]['addedOnDate']=='0000-00-00') {
           $record[$i]['addedOnDate'] = NOT_APPLICABLE_STRING; 
        }
        else{
           $record[$i]['addedOnDate'] = UtilityManager::formatDate($record[$i]['addedOnDate']);  
        }
        
        if($record[$i]['cancelOnDate']=='0000-00-00') {
           $record[$i]['cancelOnDate'] = NOT_APPLICABLE_STRING;     
        }
        else{
           $record[$i]['cancelOnDate'] = UtilityManager::formatDate($record[$i]['cancelOnDate']);   
        } 
        
       // add stateId in actionId to populate edit/delete icons in User Interface 
       $csvData .= ($i+1).",".parseCSVComments($record[$i]['studentName']).",".parseCSVComments($record[$i]['className']);
       $csvData .= ",".parseCSVComments($record[$i]['rollNo']).",".parseCSVComments($record[$i]['routeCode']);
       $csvData .= ",".parseCSVComments($record[$i]['stopName']).",".parseCSVComments($record[$i]['receiptNo']);
       $csvData .= ",".parseCSVComments($record[$i]['addedOnDate']).",".parseCSVComments($record[$i]['validUpto'])."\n";
    }

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a CSV
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	// It will be called testType.csv
	header('Content-Disposition: attachment;  filename="BusPassReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 
// $History: listBusPassReportCSV.php $
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