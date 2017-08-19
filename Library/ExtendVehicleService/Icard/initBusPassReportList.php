<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in all details report.
//
// Author :Parveen Sharma
// Created on : 16-06-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    set_time_limit(0);  
    
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    
    define('MODULE','DisplayBusPassReport');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    $studentManager = StudentReportsManager::getInstance();
    
    $dateFormat = add_slashes($REQUEST_DATA['dateFormat']);       

    $startDate = add_slashes($REQUEST_DATA['startDate']);
    $endDate= add_slashes($REQUEST_DATA['endDate']);
    
    $busStopId = add_slashes($REQUEST_DATA['busStopId']) ;
    $busRouteId = add_slashes($REQUEST_DATA['busRouteId']);
    
    $classId = add_slashes($REQUEST_DATA['classId']) ;
    $sRollNo = add_slashes($REQUEST_DATA['sRollNo']);
    $sName = add_slashes($REQUEST_DATA['sName']);
    $sReceiptNo = add_slashes($REQUEST_DATA['sReceiptNo']);  
   
 
    $conditions ='';    
    
    if($busStopId!='') {
      $conditions .= " AND a.busStopId IN ($busStopId) ";
    }
    
    if($busRouteId!='') {
      $conditions .= " AND a.busRouteId IN ($busRouteId) ";
    }
    
    if($dateFormat==1) {
       $conditions  .= " AND ( (validUpto >= '$startDate' AND validUpto <= '$endDate') ";     
    }
    else {
       $conditions  .= " AND ( (addedOnDate >= '$startDate' AND addedOnDate <= '$endDate')";       
    }
    
    
    
    if($classId!='') {
       $conditions .= " AND a.classId = '$classId' ";   
    }
   
    if($sRollNo!='') {
       $conditions .= " AND ( a.rollNo LIKE ('$sRollNo%') OR a.regNo LIKE ('$sRollNo%') ) ";   
    }
    
    if($sName!='') {
       $conditions .= " AND CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) LIKE ('$sName%')";    
    }
    
    if($sReceiptNo!='') {
       $conditions .= " AND bpass.receiptNo LIKE ('$sReceiptNo%') ";    
    }
    
    $conditions .= " ) ";
    
    $json_val = "";       
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy";
    
    // to limit records per page    
    $page    = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records = ($page-1)* RECORDS_PER_PAGE;
    $limit   = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
 
 
    $totalArray = $studentManager->getCountAllBusPassDetails($conditions);
    $cnt = $totalArray[0]['cnt'];
    
    $studentRecordArray = $studentManager->getAllBusPassDetails($conditions, $orderBy, $limit);
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
        
        $valueArray = array_merge(array('srNo' => ($records+$i+1)),$studentRecordArray[$i]);

        if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
        }
        else {
            $json_val .= ','.json_encode($valueArray);           
        }
    }
  
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt.'","page":"'.$REQUEST_DATA['page'].'","info" : ['.$json_val.']}'; 
?>  

<?php 
//$History: initBusPassReportList.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/Icard
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/12/10    Time: 3:14p
//Updated in $/LeapCC/Library/Icard
//bus receiptNo. field added (format & validation updated)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/02/10    Time: 4:34p
//Updated in $/LeapCC/Library/Icard
//format and validation updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/02/10    Time: 4:00p
//Created in $/LeapCC/Library/Icard
//initial checkin
//


?>