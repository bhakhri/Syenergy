<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/GroupTypeManager.inc.php");
    $groupTypeManager = GroupTypeManager::getInstance();
    define('MODULE','GroupTypeMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    $conditionsArray = array();
    $qryString = "";
    

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

    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (groupTypeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR groupTypeCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'groupTypeName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $groupTypeManager->getTotalGroupType($filter);
    $record = $groupTypeManager->getGroupTypeList($filter,'',$orderBy);
    $cnt = count($record);
    
    $csvData = '';
    $csvData .= "Search By,".parseCSVComments($REQUEST_DATA['searchbox']);   
    $csvData .= "\n";
    $csvData .= "#,Group Type Name,Abbr. \n";
    for($i=0;$i<$cnt;$i++) {
       $csvData .= ($i+1).",".parseCSVComments($record[$i]['groupTypeName']).",".parseCSVComments($record[$i]['groupTypeCode']); 
       $csvData .= "\n";
    }

    if($i==0) {
      $csvData .= ",No Data Found";   
    }
    
    ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="GroupTypeCsv.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
    
?>