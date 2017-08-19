<?php 
//This file is used as printing version for fee head CSV.
//
// Author :Parveen Sharma
// Created on : 21.10.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<?php
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');     
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeeHeadManager.inc.php");
    $feeHeadManager =FeeHeadManager::getInstance();
    
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
    
    /// Search filter /////  
     if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $str = "";
       if(add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'yes' || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'y' || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'ye') 
          $str = ' OR c.isRefundable = "1" OR c.isVariable = "1" OR c.isConsessionable = "1"';
       else if(add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'no'  || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'n') 
          $str = ' OR c.isRefundable = "0" OR c.isVariable = "0" OR c.isConsessionable = "0"';   
          
       $filter = ' AND (c.headName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.headAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.sortingOrder LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" '.$str.')';         
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'headName';
    
    $orderBy = " $sortField $sortOrderBy";         

    $record = $feeHeadManager->getFeeHeadList($filter,'',$orderBy);
    $cnt = count($record);
    
    $search = $REQUEST_DATA['searchbox'];         
    
    $csvData  = '';
    $csvData .= "SearchBy,".$search;
    $csvData .= "\n";
    $csvData .= "#, Name, Abbr.,Refundable Security,Concessionable,Miscellaneous,Display Order";
    $csvData .= "\n";
    
    for($i=0;$i<$cnt;$i++) {
       $record[$i]['srNo'] =  ($i+1);
       $record[$i]['isRefundable'] = $record[$i]['isRefundable'] == 1 ? 'Yes' : 'No' ;
       $record[$i]['isVariable']   = $record[$i]['isVariable'] == 1 ? 'Yes' : 'No' ;
       $record[$i]['isConsessionable']   = $record[$i]['isConsessionable'] == 1 ? 'Yes' : 'No' ;
	   $record[$i]['sortingOrder'] = $record[$i]['sortingOrder'] != ''? $record[$i]['sortingOrder'] : "0" ;
       $csvData .= ($i+1).",";       
       $csvData .= parseCSVComments($record[$i]['headName']).",";
       $csvData .= parseCSVComments($record[$i]['headAbbr']).",";
       $csvData .= parseCSVComments($record[$i]['isRefundable']).",";
       $csvData .= parseCSVComments($record[$i]['isConsessionable']).",";
       $csvData .= parseCSVComments($record[$i]['isVariable']).",";
       $csvData .= parseCSVComments($record[$i]['sortingOrder']).",\n";
       /*
       $csvData .= parseCSVComments($record[$i]['parentHead']).",";
       $csvData .= parseCSVComments($record[$i]['applicableToAll']).",";
       $csvData .= parseCSVComments($record[$i]['transportHead']).",";
       $csvData .= parseCSVComments($record[$i]['hostelHead']).",";
       $csvData .= parseCSVComments($record[$i]['miscHead']).",";
       $csvData .= parseCSVComments($record[$i]['isConsessionable']).",";
       */
    }
     
    if($i==0) {
      $csvData .= ",,No Data Found";  
    }    
        
    UtilityManager::makeCSV($csvData,'FeeHeadReport.csv');
    die;                  
?>