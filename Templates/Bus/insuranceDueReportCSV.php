<?php 
//This file is used as csv output of student list.
//
// Author :Rajeev Aggarwal
// Created on : 10-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
    
    //to parse csv values    
    function parseCSVComments($comments) {
      $comments = str_replace('"', '""', $comments);
      $comments = str_ireplace('<br/>', "\n", $comments);
      if(eregi(",", $comments) or eregi("\n", $comments)) {
         return '"'.$comments.'"'; 
      } 
      else {
        return $comments; 
      }
    }
    
    
    if(isset($REQUEST_DATA['busId']) and $REQUEST_DATA['busId']!=''){
      $filter = ' AND ( bs.busId IN ('.$REQUEST_DATA['busId'].') AND bi.insuranceDueDate BETWEEN "'.$REQUEST_DATA['fromDate'].'" AND "'.$REQUEST_DATA['toDate'].'" )';
    }
    else{
      $filter = ' WHERE ( insuranceDueDate BETWEEN "'.$REQUEST_DATA['fromDate'].'" AND "'.$REQUEST_DATA['toDate'].'" )';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busName';
    
    $orderBy = " $sortField $sortOrderBy";
    
    $busRecordArray = $busManager->getBusList($filter,' ',$orderBy);
     

    $cnt = count($busRecordArray);  
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        
        if($busRecordArray[$i]['insuranceDueDate']!='0000-00-00'){
         $busRecordArray[$i]['insuranceDueDate']=UtilityManager::formatDate($busRecordArray[$i]['insuranceDueDate']);
        }
        else{
            $busRecordArray[$i]['insuranceDueDate']=NOT_APPLICABLE_STRING;
        }
        
        $valueArray[] = array_merge(array('srNo' => $i+1),$busRecordArray[$i]);
    }

    $csvData = '';
    $csvData .= "Sr, Name, Registration No., In Service, Insuring Company, Policy No.,Due Date \n";
    foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['busName']).', '.parseCSVComments($record['busNo']).', '.parseCSVComments($record['isActive']).', '.parseCSVComments($record['insuringCompanyName']).','.parseCSVComments($record['policyNo']).', '.parseCSVComments($record['insuranceDueDate']);
        $csvData .= "\n";
    }
    
    ob_end_clean();
    header("Cache-Control: public, must-revalidate");
    header('Content-type: application/octet-stream');
    header("Content-Length: " .strlen($csvData) );
    header('Content-Disposition: attachment;  filename="insuranceDue.csv"');
    header("Content-Transfer-Encoding: binary\n");
    echo $csvData;
    die;    
// $History: insuranceDueReportCSV.php $
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
?>