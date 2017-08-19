 <?php 
//This file is used as export to excel version for display batch.
//
// Author :Jaineesh
// Created on : 04.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
	require_once(MODEL_PATH . "/BranchManager.inc.php");
    $branchManager =BranchManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    
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
   //    $filter = ' WHERE (branchName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR branchCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';   
    $filter = ' AND (branchName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR branchCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR miscReceiptPrefix LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR                            studentCount LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';  
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'branchName';
    $orderBy = " $sortField $sortOrderBy";
    
	$branchRecordArray = $branchManager->getBranchList($filter,'',$orderBy);
    
	$recordCount = count($branchRecordArray);

    $valueArray = array();

    $csvData ='';
    $csvData ='SearchBy,'.parseCSVComments($REQUEST_DATA['searchbox']);
    $csvData .="\n";
    $csvData .="#,Name,Abbr.,Misc.Receipt Prefix,Student";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
	  $csvData .= ($i+1).",";
	  $csvData .= parseCSVComments($branchRecordArray[$i]['branchName']).",";
	  $csvData .= parseCSVComments($branchRecordArray[$i]['branchCode']).",";
	   $csvData .= parseCSVComments($branchRecordArray[$i]['miscReceiptPrefix']).",";
      $csvData .= parseCSVComments($branchRecordArray[$i]['studentCount']).","; 
      
	  $csvData .= "\n";
    }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'BranchReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
