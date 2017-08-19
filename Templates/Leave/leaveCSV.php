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
    require_once(MODEL_PATH . "/LeaveManager.inc.php");
    $leaveManager = LeaveManager::getInstance();
    define('MODULE','LeaveMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
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
    if(UtilityManager::notEmpty(trim($REQUEST_DATA['searchbox']))) {

        if(strtolower(trim($REQUEST_DATA['searchbox']))=='yes') {
           $type=1;
        }
        elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='no') {
           $type=0;
        }
       else {
           $type=-1;                                    
       }
       
       $filter = ' AND (TRIM(leaveTypeName) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" 
                        OR isActive LIKE "'.$type.'" OR carryForward LIKE "'.$type.'" OR reimbursed LIKE "'.$type.'" )';
    }
    
    
    //$conditions = '';
    //if (count($conditionsArray) > 0) {
        //$conditions = ' AND '.implode(' AND ',$conditionsArray);
    //}

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'leaveTypeName';

    //$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $leaveManager->getLeaveList($filter,'',$orderBy);
    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
	if($recordArray[$i]['isActive']==1){
            $recordArray[$i]['isActive']='Yes';
        }
        else if($recordArray[$i]['isActive']==0){
            $recordArray[$i]['isActive']='No';
        }
        if($recordArray[$i]['carryForward']==1){
            $recordArray[$i]['carryForward']='Yes';
        }
        else if($recordArray[$i]['carryForward']==0){
            $recordArray[$i]['carryForward']='No';
        }
        
        if($recordArray[$i]['reimbursed']==1){
            $recordArray[$i]['reimbursed']='Yes';
        }
        else if($recordArray[$i]['reimbursed']==0){
            $recordArray[$i]['reimbursed']='No';
        }
		
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$csvData = '';
    $csvData .= "Search By,".parseCSVComments($REQUEST_DATA['searchbox']);   
    $csvData .= "\n";
    $csvData .= "#,Leave Type Name,Reimbursement,Carry Forward,Active \n";
    $find=0;
	foreach($valueArray as $record) {
        $find=1;
        $csvData .= $record['srNo'].",".parseCSVComments($record['leaveTypeName']);
        $csvData .= ",".parseCSVComments($record['carryForward']).",".parseCSVComments($record['reimbursed']).",".parseCSVComments($record['isActive']); 
		$csvData .= "\n";
	}
	
    if($find==0) {
      $csvData .= ",,No Data Found";   
    }
    
    ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="leaveCsv.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

    // $History: LeaveCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/08/09    Time: 16:10
//Created in $/LeapCC/Templates/Leave
//Done bug fixing.
//bug ids--
//0000861 to 0000877
?>