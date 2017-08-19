 <?php 
// This file is used as printing version for display cities.
// Author :Dipanjan Bhattacharjee
// Created on : 03.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeCode';
    
    $orderBy = " $sortField $sortOrderBy";
    
    $criteria=trim($REQUEST_DATA['criteriaType']);
    $criteriaValue=trim(add_slashes($REQUEST_DATA['criteriaText']));
    
    if(!is_numeric($criteriaValue)){
        echo ENTER_CRITERIA_VALUE_IN_INTERGER;
        die;
    }
    
    if($criteriaValue<0){
        echo ENTER_CRITERIA_VALUE_POSITIVE;
        die;
    }

    if(trim($REQUEST_DATA['leaveSessionId'])!="-1"){
        $filter1 .=' AND l.leaveSessionId='.trim($REQUEST_DATA['leaveSessionId']);
    }
    
    if($criteria==1){
        $filter2 =' HAVING noOfDays > '.$criteriaValue;
    }
    else if($criteria==2){
        $filter2 =' HAVING noOfDays < '.$criteriaValue;
    }
    else{
        $filter2 =' HAVING noOfDays = '.$criteriaValue; 
    }
    
    if(trim($REQUEST_DATA['leaveType'])!="-1"){
      $filter1 .=' AND l.leaveTypeId='.trim($REQUEST_DATA['leaveType']);  
    }
    
        
    $leaveRecordArray = $leaveManager->getLeavesAnalysisList($filter1,$filter2,' ',$orderBy);
    $cnt = count($leaveRecordArray);

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$leaveRecordArray[$i]);
    }
    
    $csvData = '';
    
    $csvData .= "Leave Session,".parseCSVComments(trim($REQUEST_DATA['yearName'])).",Leave Types,".parseCSVComments(trim($REQUEST_DATA['leaveTypeName']));
    $csvData .= "\nCriteria,".parseCSVComments(trim($REQUEST_DATA['criteriaName']))."  Value, ".$criteriaValue;
    $csvData .= "\n";
    $csvData .= "#, Employee Code, Employee Name, Leave Type, Days \n";
    $find=0;
    foreach($valueArray as $record) {
       $csvData .= $record['srNo'].', '.parseCSVComments($record['employeeCode']).', '.parseCSVComments($record['employeeName']).', '.parseCSVComments($record['leaveTypeName']).','.$record['noOfDays'];
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
header('Content-Disposition: attachment; filename="employeeLeavesAnalysisReport.csv"');
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>