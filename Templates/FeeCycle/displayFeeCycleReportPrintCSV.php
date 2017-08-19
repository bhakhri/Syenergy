 <?php 
//This file is used as printing version for display fee cycle.
//
// Author :Jaineesh
// Created on : 13.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
	require_once(MODEL_PATH . "/FeeCycleManager.inc.php");
	$feeCycleManager = FeeCycleManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
     /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (cycleName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                        DATE_FORMAT(fromDate,"%d-%b-%y") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                        DATE_FORMAT(toDate,"%d-%b-%y")  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                        cycleAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" )';          
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'cycleName';
    
    $orderBy = " $sortField $sortOrderBy";

	$feeCycleRecordArray = $feeCycleManager->getFeeCycleList($filter,'',$orderBy);
    
	$recordCount = count($feeCycleRecordArray);

    $valueArray = array();
    
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
    

    $csvData ='';
    $csvData.="Search By,".parseCSVComments($search);
    $csvData .="\n";
    $csvData .="#,Name,Abbr.,From,To";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
	  $csvData .= ($i+1).",";
	  $feeCycleRecordArray[$i]['fromDate']=UtilityManager::formatDate($feeCycleRecordArray[$i]['fromDate']);
	  $feeCycleRecordArray[$i]['toDate']=UtilityManager::formatDate($feeCycleRecordArray[$i]['toDate']);
	  $csvData .= parseCSVComments($feeCycleRecordArray[$i]['cycleName']).",";
	  $csvData .= parseCSVComments($feeCycleRecordArray[$i]['cycleAbbr']).",";
	  $csvData .= parseCSVComments($feeCycleRecordArray[$i]['fromDate']).",";
	  $csvData .= parseCSVComments($feeCycleRecordArray[$i]['toDate']);
	  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'FeeCycleReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>