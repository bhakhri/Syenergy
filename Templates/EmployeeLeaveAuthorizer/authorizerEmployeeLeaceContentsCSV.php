<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==1){
      UtilityManager::ifNotLoggedIn(true);
    }
    else if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else if($roleId==5){
      UtilityManager::ifManagementNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);  
    }
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AuthorizeLeaveManager.inc.php");
    $authorizeLeaveManager = AuthorizeLeaveManager::getInstance();

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    
     /// get Active session
    $leaveSessionArray = $commonQueryManager->getLeaveSessionList(' WHERE active=1 ');   
    $leaveSessionId='';
    if($leaveSessionArray[0]['leaveSessionId']!='') {
      $leaveSessionId=$leaveSessionArray[0]['leaveSessionId']; 
    }                                                                                                         

    if($leaveSessionId=='') {
      $leaveSessionId=0;  
    }
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
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $search=strtoupper(trim($REQUEST_DATA['searchbox']));
       $leaveStatus=-1;
       if($search=='APPLIED'){
           $leaveStatus=0;
       }
       else if($search=='FIRST APPROVAL'){
           $leaveStatus=1;
       }
       else if($search=='SECOND APPROVAL'){
           $leaveStatus=2;
       }
       else if($search=='REJECTED'){
           $leaveStatus=3;
       }
       else if($search=='CANCELLED'){
           $leaveStatus=4;
       }
       else{
         $leaveStatus=-1;   
       }
       $filter = ' AND (lt.leaveTypeName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR e.employeeName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR e.employeeCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR l.leaveStatus LIKE "'.$leaveStatus.'%")';
    }
    
   $filter .= " AND l.leaveSessionId = $leaveSessionId";      
    
   //fetch employeeId of logged in user
   $empArray=$authorizeLeaveManager->getEmployeeInformation($sessionHandler->getSessionVariable('UserId'));
   $employeeId=$empArray[0]['employeeId'];
   
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeCode';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $authorizeLeaveManager->getLeavesForAuthorizeList($employeeId,$filter);
    $leaveRecordArray = $authorizeLeaveManager->getLeavesForAuthorizeList($employeeId,$filter,$limit,$orderBy);
    $cnt = count($leaveRecordArray);
    $search = $REQUEST_DATA['searchbox'];
    //$csvData = parseCSVComments($search);
	$csvData .= "\n";
	$csvData = "#,Employee Code,Employee Name,Leave Type,From,To,Leave Status\n";

    for($i=0;$i<$cnt;$i++) {
		if(trim($leaveRecordArray[$i]['employeeCode']) == ''){
			$leaveRecordArray[$i]['employeeCode'] = NOT_APPLICABLE_STRING;
		}
		if(trim($leaveRecordArray[$i]['employeeName']) == ''){
			$leaveRecordArray[$i]['employeeName'] = NOT_APPLICABLE_STRING;
		}
		$leaveStatus=$leaveRecordArray[$i]['leaveStatus'];
		$actionString='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$leaveRecordArray[$i]['leaveId'].');return false;"></a>';
		$leaveRecordArray[$i]['leaveFromDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveFromDate']);
		$leaveRecordArray[$i]['leaveToDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveToDate']);
		$leaveRecordArray[$i]['leaveStatus']=$leaveStatusArray[$leaveStatus];
		$csvData .= parseCSVComments(($i+1)).",". parseCSVComments($empLeaveAuthRecordArray[$i]['employeeCode']);
		$csvData .= ",".parseCSVComments($empLeaveAuthRecordArray[$i]['employeeName']); 
		$csvData .= ",".parseCSVComments($empLeaveAuthRecordArray[$i]['leaveType']);
		$csvData .= ",".parseCSVComments($empLeaveAuthRecordArray[$i]['leaveFromDate']); 

		$csvData .= ",".parseCSVComments($empLeaveAuthRecordArray[$i]['leaveToDate']);
		$csvData .= ",".parseCSVComments($empLeaveAuthRecordArray[$i]['leaveStatus']);
		$csvData .= "\n";
    }
	UtilityManager::makeCSV($csvData,'AuthorizeEmployeeLeave.csv');
	die;
?>  