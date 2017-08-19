<?php 
//This file is used as fee Cycle Fine CSV Report
//
// Author :Parveen Sharma
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/FeeCycleFineManager.inc.php");
    $feeCycleFineManager =FeeCycleFineManager::getInstance();
    /////////////////////////
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);  

     //used to parse csv data
    function parseCSVComments($comments) {
     $comments = str_replace('"', '""', $comments);
     $comments = str_ireplace('<br/>', "\n", $comments);
      if(eregi(",", $comments) or eregi("\n", $comments)) {
         return '"'.$comments.'"'; 
      } 
      else {
         return chr(160).$comments; 
      }
    }

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (fc.cycleName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                    DATE_FORMAT(fcf.fromDate,"%d-%b-%y") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                    DATE_FORMAT(fcf.toDate,"%d-%b-%y")  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                    fcf.fineAmount LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                    fcf.fineType LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';    
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'cycleName';
    
    $orderBy = "$sortField $sortOrderBy";         

    
    $record = $feeCycleFineManager->getFeeCycleFineListPrint($filter,'',$orderBy);
    $cnt = count($record);
    
    
    $search = $REQUEST_DATA['searchbox']; 
    
    $csvData = ""; 
    $csvData .= "SearchBy, ".parseCSVComments($search);
    $csvData .= "\n";
    $csvData .= "#,Fee Cycle,From,To,Fine Amount,Fine Type, \n ";
   
    for($i=0;$i<$cnt;$i++) {
       $record[$i]['fromDate']=UtilityManager::formatDate($record[$i]['fromDate']);
       $record[$i]['toDate']=UtilityManager::formatDate($record[$i]['toDate']);
       $csvData .= ($i+1).",".parseCSVComments($record[$i]['cycleName']);
       $csvData .= ",".parseCSVComments($record[$i]['fromDate']).",".parseCSVComments($record[$i]['toDate']);
       $csvData .= ",".parseCSVComments($record[$i]['fineAmount']).",".parseCSVComments($record[$i]['fineType']);
       $csvData .= "\n";
    }
    
    if($i==0) {
        $csvData .= ",,,No Data Found";   
    }
    
    ob_end_clean();
    header("Cache-Control: public, must-revalidate");
    // We'll be outputting a CSV
    header('Content-type: application/octet-stream; charset=utf-8');
    header("Content-Length: " .strlen($csvData) );
    // It will be called testType.csv
    header('Content-Disposition: attachment;  filename="FeeCycleFineReport.csv"');
    header("Content-Transfer-Encoding: binary\n");
    echo $csvData;
    die;    
// $History: feeCycleFineCSV.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Templates/FeeCycleFine
//updated with all the fees enhancements
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/08/09    Time: 4:30p
//Created in $/LeapCC/Templates/FeeCycleFine
//initial checkin
//

?>