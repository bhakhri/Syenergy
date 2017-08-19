<?php 
//This file is used as csv version for Time Table Label.
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/TimeTableLabelManager.inc.php");
    $timeTableLabelManager = TimeTableLabelManager::getInstance();

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
	   
  /*     if(strtoupper(trim($REQUEST_DATA['searchbox']))=='WEEKLY' ){
           $timeTableType=1;
       }
       else if(strtoupper(trim($REQUEST_DATA['searchbox']))=='DAILY'){
           $timeTableType=2;
       } */
    //   $filter = ' AND (ttl.labelName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ttl.isActive LIKE "'.$active.'%" OR ttl.timeTableType LIKE "'.$timeTableType.'%")';    
     	  $filter .=' AND (labelName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ttl.isActive LIKE "'.$active.'" OR 
		 IF(ttl.timeTableType=1,"WEEKLY","DAILY")  LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
		 DATE_FORMAT(ttl.startDate,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
		 DATE_FORMAT(ttl.endDate,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';  
       
    }
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'labelName';
    
    $orderBy = " ttl.$sortField $sortOrderBy"; 


    $recordArray = $timeTableLabelManager->getTimeTableLabelList($filter,'',$orderBy);

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        if($recordArray[$i]['isActive']==1){
            $recordArray[$i]['isActive']='Yes';
        }
        else if($recordArray[$i]['isActive']==0){
            $recordArray[$i]['isActive']='No';
        }
	/*	if($recordArray[$i]['timeTableType']==WEEKLY_TIMETABLE){
            $recordArray[$i]['timeTableType']='Weekly';
        }
        else if($recordArray[$i]['timeTableType']==DAILY_TIMETABLE){
            $recordArray[$i]['timeTableType']='Daily';
        }  */
        $recordArray[$i]['startDate'] =strip_slashes($recordArray[$i]['startDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :        UtilityManager::formatDate($recordArray[$i]['startDate']);
        
        $recordArray[$i]['endDate'] = strip_slashes($recordArray[$i]['endDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :UtilityManager::formatDate($recordArray[$i]['endDate']);
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }
	$search = add_slashes(trim($REQUEST_DATA['searchbox']));
	$csvData = '';
	 $csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData .= "#, Name, From Date, To Date, Active, Type \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['labelName']).', '.parseCSVComments($record['startDate']).', '.parseCSVComments($record['endDate']).','.$record['isActive'].','.$record['typeOf'];
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="timeTableLabel.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: timeTableLabelCSV.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 4/21/10    Time: 4:34p
//Updated in $/LeapCC/Templates/TimeTableLabel
//done changes as per FCNS No. 1625
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/07/09   Time: 13:59
//Created in $/LeapCC/Templates/TimeTableLabel
//Added "print and export to excel" facility for time table label module
?>