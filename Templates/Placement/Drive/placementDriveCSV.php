<?php 
// This file is used as csv version for Company.
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Placement/DriveManager.inc.php");
    $driveManager = DriveManager::getInstance();

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

    //search filter
    $search = trim($REQUEST_DATA['searchbox']);
    $filter = ''; 
    if (!empty($search)) {
     $filter = ' AND ( c.companyCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR d.placementDriveCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'placementDriveCode';
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $driveManager->getPlacementDriveList($filter,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
       $time=explode(':',$recordArray[$i]['startTime']);
       $recordArray[$i]['startTime']=$time[0].':'.$time[1].' '.$recordArray[$i]['startTimeAmPm'];

       $time=explode(':',$recordArray[$i]['endTime']);
       $recordArray[$i]['endTime']=$time[0].':'.$time[1].' '.$recordArray[$i]['endTimeAmPm'];

       $recordArray[$i]['startDate']=UtilityManager::formatDate($recordArray[$i]['startDate']);
       $recordArray[$i]['endDate']=UtilityManager::formatDate($recordArray[$i]['endDate']);
       
       $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$csvData = "Search By : ".$search."\n";
    $csvData .= "#, Placement Drive Code, Company, From, Time, To, Time \n";
	foreach($valueArray as $record) {
      $csvData .= $record['srNo'].','.parseCSVComments($record['placementDriveCode']).','.parseCSVComments($record['companyCode']).','.parseCSVComments($record['startDate']).','.parseCSVComments($record['startTime']).','.parseCSVComments($record['endDate']).','.parseCSVComments($record['endTime']);
	  $csvData .= "\n";
	}
    if($cnt==0){
      $csvData .= ",".NO_DATA_FOUND; 
    }
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="placementDrive.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: testTypeCSV.php $
?>