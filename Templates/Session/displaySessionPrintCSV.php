 <?php 
//This file is used as printing version for display session.
//
// Author :Jaineesh
// Created on : 03.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(MODEL_PATH . "/SessionsManager.inc.php");   
    $sessionsManager = SessionsManager::getInstance();
    
    define('MODULE','COMMNO');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    
   
    // CSV data field Comments added 
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
         $filter = '  WHERE sessionName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sessionYear LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR abbreviation LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"';  
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'sessionName';
    
    $orderBy = " $sortField $sortOrderBy";

	$sessionRecordArray = $sessionsManager->getSessionList($filter,'',$orderBy);
    
	$recordCount = count($sessionRecordArray);

    $valueArray = array();

    $csvData ='';
    $csvData .="SearchBy,".parseCSVComments($search);
    $csvData .="\n";
    $csvData .="#,Session Name,Session Year,Abbreviation,Start Date,End Date,Active";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $sessionRecordArray[$i]['startDate'] =strip_slashes($sessionRecordArray[$i]['startDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :        UtilityManager::formatDate($sessionRecordArray[$i]['startDate']);

			$sessionRecordArray[$i]['endDate'] = strip_slashes($sessionRecordArray[$i]['endDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :UtilityManager::formatDate($sessionRecordArray[$i]['endDate']);

		  $csvData .= ($i+1).",";
		  $csvData .= $sessionRecordArray[$i]['sessionName'].",";
		  $csvData .= $sessionRecordArray[$i]['sessionYear'].",";
		  $csvData .= $sessionRecordArray[$i]['abbreviation'].",";
		  $csvData .= $sessionRecordArray[$i]['startDate'].",";
		  $csvData .= $sessionRecordArray[$i]['endDate'].",";
		  $csvData .= $sessionRecordArray[$i]['active'].",";
		  $csvData .= "\n";
  }
    
  
  if($i==0) {
    $csvData .= ",,,No Data Found";  
  }  
    
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'SessionReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>