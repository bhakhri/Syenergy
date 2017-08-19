 <?php 
// This file is used as printing version for display cities.
// Author :Dipanjan Bhattacharjee
// Created on : 03.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

    require_once(MODEL_PATH . "/LeaveReportsManager.inc.php");
    $leaveManager = LeaveReportsManager::getInstance();
    
    
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
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'leaveTypeName';
    $orderBy = " $sortField $sortOrderBy";         

    if(trim($REQUEST_DATA['leaveSessionId'])!="-1"){
        $filter =' AND l.leaveSessionId='.trim($REQUEST_DATA['leaveSessionId']);
    }
    
    
    if(trim($REQUEST_DATA['employeeDD'])==""){
      echo 'Employee Information Missing';
      die;  
    }
    
    $filter .=' AND l.employeeId='.trim($REQUEST_DATA['employeeDD']);
     
    $leaveRecordArray = $leaveManager->getLeavesHistoryList($filter,' ',$orderBy);
    $cnt = count($leaveRecordArray);


    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $leaveRecordArray[$i]['leaveFromDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveFromDate']); 
        $leaveRecordArray[$i]['leaveToDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveToDate']);
        $leaveRecordArray[$i]['leaveStatus']=$leaveStatusArray[$leaveRecordArray[$i]['leaveStatus']];
        
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$leaveRecordArray[$i]);
    }
    
   $csvData = '';
   $csvData .= "Leave Session,".parseCSVComments(trim($REQUEST_DATA['yearName'])).",Employee,".parseCSVComments(trim($REQUEST_DATA['employeeName']));
   $csvData .= "\n";
   $csvData .= "#, Leave Type, From, To , Days, Status \n";
   $find=0;
   foreach($valueArray as $record) {
       $csvData .= $record['srNo'].','.parseCSVComments($record['leaveTypeName']).', '.$record['leaveFromDate'].', '.$record['leaveToDate'].','.$record['noOfDays'].','.$record['leaveStatus'];
       $csvData .= "\n";
       $find=1;
   } 
    
   if($find==0) {
      $csvData .= ",,No Data Found";  
    }
    
ob_end_clean();
header("Cache-Control: public, must-revalidate");
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
header('Content-Disposition: attachment; filename="leavesHistoryReport.csv"');
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>