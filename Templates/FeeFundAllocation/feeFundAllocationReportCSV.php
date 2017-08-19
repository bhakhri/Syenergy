<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/FeeFundAllocationManager.inc.php");
    $feeFundAllocationManager = FeeFundAllocationManager::getInstance();
    
    define('MODULE','FundAllocationMaster');
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
    
    
    
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (allocationEntity LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR entityType LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'allocationEntity';
    $orderBy = " $sortField $sortOrderBy";   

    $csvData = "";
    $csvData = "SearchBy:, ".parseCSVComments($REQUEST_DATA['searchbox'])."\n";
    $csvData .= "#, Allocation Entity, Abbr. \n";
    
    $record = $feeFundAllocationManager->getFeeFundAllocationList($filter,'',$orderBy);    
    $recordCount = count($record); 
    for($i=0; $i<$recordCount; $i++ ){
      $csvData .= ($i+1).','.parseCSVComments($record[$i]['allocationEntity']).','.parseCSVComments($record[$i]['entityType']);
      $csvData .= "\n";
    }
    ob_end_clean();
    header("Cache-Control: public, must-revalidate");
    // We'll be outputting a CSV
    header('Content-type: application/octet-stream; charset=utf-8');
    header("Content-Length: " .strlen($csvData) );
    // It will be called testType.csv
    header('Content-Disposition: attachment;  filename="FundAllocationReport.csv"');
    header("Content-Transfer-Encoding: binary\n");
    echo $csvData;
    die;    
// $History: feeFundAllocationReportCSV.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/13/09    Time: 10:49a
//Updated in $/LeapCC/Templates/FeeFundAllocation
//search condition & CSV format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/13/09    Time: 10:30a
//Updated in $/SnS/Templates/FeeFundAllocation
//issue fix 1057 search & CSV format setting
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/06/09    Time: 5:31p
//Updated in $/SnS/Templates/FeeFundAllocation
//role permission added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/06/09    Time: 5:00p
//Created in $/SnS/Templates/FeeFundAllocation
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/30/09    Time: 5:04p
//Created in $/LeapCC/Templates/FeeFundAllocation
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/01/09    Time: 12:56p
//Updated in $/LeapCC/Templates/SubjectType
//list formatting & required field validation added
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:58
//Created in $/LeapCC/Templates/SubjectType
//Added "Print" and "Export to excell" in subject and subjectType modules
?>