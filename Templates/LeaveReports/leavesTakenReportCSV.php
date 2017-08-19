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
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    
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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeCode';
    
    $orderBy = " $sortField $sortOrderBy";
    
    global $sessionHandler;  
    $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');    
    if($leaveAuthorizersId=='') {
       $leaveAuthorizersId=1;  
    }
    
    
    if(trim($REQUEST_DATA['leaveSessionId'])!="-1"){
        $filter =' AND l.leaveSessionId ='.trim($REQUEST_DATA['leaveSessionId']);
    }
    
    if(trim($REQUEST_DATA['employeeDD'])!="-1"){
        $filter .=' AND l.employeeId='.trim($REQUEST_DATA['employeeDD']);
    }
    if(trim($REQUEST_DATA['leaveStatus'])=="1"){
        $filter .=" AND l.leaveStatus=$leaveAuthorizersId";
    }
    
    if($leaveAuthorizersId==2) {
        if(trim($REQUEST_DATA['leaveStatus'])=="0"){
            if(trim($REQUEST_DATA['pendingStatus'])=="0"){
                $filter .=' AND l.leaveStatus=0';
            }
            else if(trim($REQUEST_DATA['pendingStatus'])=="1"){
                $filter .=' AND l.leaveStatus=1';
            }
            else{
                $filter .=' AND l.leaveStatus IN (0,1)';
            }
        }
    }
    else {
       if(trim($REQUEST_DATA['leaveStatus'])=="0") {
         $filter .=' AND l.leaveStatus=0';
       }
    }
    
    if(trim($REQUEST_DATA['fromDate'])!='' and trim($REQUEST_DATA['toDate'])!=''){
        $filter .=' AND ( ( l.leaveFromDate BETWEEN "'.trim($REQUEST_DATA['fromDate']).'" AND "'.trim($REQUEST_DATA['toDate']).'" ) OR ( l.leaveToDate BETWEEN "'.trim($REQUEST_DATA['fromDate']).'" AND "'.trim($REQUEST_DATA['toDate']).'" ) )';
        $dateSearch=' From,'.parseCSVComments(UtilityManager::formatDate(trim($REQUEST_DATA['fromDate']))).',To,'.parseCSVComments(UtilityManager::formatDate(trim($REQUEST_DATA['toDate'])));
    }
     
    $leaveRecordArray = $leaveManager->getLeavesTakenList($filter,' ',$orderBy);
    $cnt = count($leaveRecordArray);

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $leaveRecordArray[$i]['leaveFromDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveFromDate']); 
        $leaveRecordArray[$i]['leaveToDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveToDate']);
        $leaveRecordArray[$i]['leaveStatus']=$leaveStatusArray[$leaveRecordArray[$i]['leaveStatus']];
        
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$leaveRecordArray[$i]);
    }
    
    if(trim($REQUEST_DATA['leaveStatus'])==1){
        $leaveStatus='Approved';
    }
    else{
       if($leaveAuthorizersId==2) { 
          $leaveStatus='Pending ( '. trim($REQUEST_DATA['pendingStatusName']) .' )'; 
       }
       else {
          $leaveStatus='Pending';   
       }
    }
    
    $search="Leave Session,".parseCSVComments(trim($REQUEST_DATA['yearName']));
    if(trim($REQUEST_DATA['employeeName'])!='All') {
      $search .=",Employee,".parseCSVComments(trim($REQUEST_DATA['employeeName']));
    }
    $search .="\nLeave Status,".parseCSVComments($leaveStatus);
    
    if($dateSearch!='') {
      $search .="\n".$dateSearch;
    }
    
    $csvData = '';
    $csvData .= $search;
    $csvData .= "\n";
    $csvData .= "#, Employee Code, Employee Name, Leave Type, From, To , Days, Status \n";
    $find=0;
    foreach($valueArray as $record) {
       $csvData .= $record['srNo'].', '.parseCSVComments($record['employeeCode']).', '.parseCSVComments($record['employeeName']).', '.parseCSVComments($record['leaveTypeName']).','.$record['leaveFromDate'].','.$record['leaveToDate'].','.$record['noOfDays'].','.$record['leaveStatus'];   $find=1;
       $csvData .= "\n";
    } 
    
    if($find==0) {
      $csvData .= ",,,No Data Found";  
    }
    
ob_end_clean();
header("Cache-Control: public, must-revalidate");
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
header('Content-Disposition: attachment; filename="employeeLeavesTakenReport.csv"');
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>