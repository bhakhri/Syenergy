<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/EmployeeLeaveSetMappingManager.inc.php");
    $employeeLeaveSetMappingManager = EmployeeLeaveSetMappingManager::getInstance();
     
    define('MODULE','EmployeeEmployeeLeaveSetMapping');  
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

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (ls.leaveSetName  LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                        emp.employeeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                        emp.employeeCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeCode';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    $filter .=" AND s.active=1";
    $totalArray = $employeeLeaveSetMappingManager->getTotalEmployeeLeaveSetMapping($filter);
    $empLeaveSetMappingRecordArray = $employeeLeaveSetMappingManager->getEmployeeLeaveSetMappingList($filter,'',$orderBy);
    $cnt = count($empLeaveSetMappingRecordArray);
    
    $csvData = '';
    $csvData .= "Search By,".parseCSVComments($REQUEST_DATA['searchbox']);   
    $csvData .= "\n";
    $csvData .= "#,Employee Code,Employee Name,Leave Set \n";
    $find=0;
    
    for($i=0;$i<$cnt;$i++) {
        if(trim($empLeaveSetMappingRecordArray[$i]['employeeCode'])==''){
            $empLeaveSetMappingRecordArray[$i]['employeeCode']=NOT_APPLICABLE_STRING;
        }
        if(trim($empLeaveSetMappingRecordArray[$i]['employeeName'])==''){
            $empLeaveSetMappingRecordArray[$i]['employeeName']=NOT_APPLICABLE_STRING;
        }
        $csvData .= ($i+1).",".parseCSVComments($empLeaveSetMappingRecordArray[$i]['employeeCode']).",";   
        $csvData .= parseCSVComments($empLeaveSetMappingRecordArray[$i]['employeeName']).",";   
        $csvData .= parseCSVComments($empLeaveSetMappingRecordArray[$i]['leaveSetName'])."\n";   
    }

    if($i==0) {
      $csvData .= ",No Data Found";   
    }
    
    ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="EmployeeLeaveSetMappingCsv.csv"');
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