<?php 
//This file is used as csv version for Time Table Label.
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/LeaveSetManager.inc.php");
    $leaveSetManager = LeaveSetManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    

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

    /// Search filter /////
	$search = trim($REQUEST_DATA['searchbox']);

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       if(strtoupper(trim($REQUEST_DATA['searchbox']))=='YES' ){
           $active=1;
       }
       else if(strtoupper(trim($REQUEST_DATA['searchbox']))=='NO'){
           $active=0;
       }
       else{
           $active=-1;
       }
	   
      $filter = ' AND (leaveSetName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR isActive LIKE "'.$active.'%" )';        
       
    }
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'leaveSetName';
    
    $orderBy = " $sortField $sortOrderBy"; 


    $recordArray = $leaveSetManager->getLeaveSetList($filter,$orderBy,'');

   // $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        if($recordArray[$i]['isActive']==1){
            $recordArray[$i]['isActive']='Yes';
        }
        else if($recordArray[$i]['isActive']==0){
            $recordArray[$i]['isActive']='No';
        }
		
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }
	
	$cnt = count($valueArray);
	$csvData = '';
	$csvData = "Search By : $search";
	$csvData .="\n";
    $csvData .= "#, Leave Set Name, Active \n";
	if($cnt > 0 && is_array($valueArray)) {
		foreach($valueArray as $record) {
			$csvData .= $record['srNo'].','.parseCSVComments($record['leaveSetName']).','.$record['isActive'];
			$csvData .= "\n";
		}
	}
	else {
		$csvData .= "No Data Found";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="leaveSet.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: timeTableLabelCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/07/09   Time: 13:59
//Created in $/LeapCC/Templates/TimeTableLabel
//Added "print and export to excel" facility for time table label module
?>