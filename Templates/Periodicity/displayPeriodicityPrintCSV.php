 <?php 
//This file is used as printing version for display classes.
//
// Author :Jaineesh
// Created on : 03.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(MODEL_PATH . "/PeriodicityManager.inc.php");
	$periodicityManager = PeriodicityManager::getInstance();
    
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
  
    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = 'WHERE  (periodicityName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR periodicityCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR periodicityFrequency LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'periodicityName';
    
    $orderBy = " $sortField $sortOrderBy";

	$periodicityRecordArray = $periodicityManager->getPeriodicityList($filter,'',$orderBy);
    
	$recordCount = count($periodicityRecordArray);

    $valueArray = array();

    $search = $REQUEST_DATA['searchbox'];    
    
    $csvData  = '';   
    $csvData .= "Search By:, ".parseCSVComments($search)."\n";
    $csvData .="Sr No.,Name,Abbr.,Annual Frequency";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= parseCSVComments($periodicityRecordArray[$i]['periodicityName']).",";
		  $csvData .= parseCSVComments($periodicityRecordArray[$i]['periodicityCode']).",";
		  $csvData .= parseCSVComments($periodicityRecordArray[$i]['periodicityFrequency']).",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'PeriodicityReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>