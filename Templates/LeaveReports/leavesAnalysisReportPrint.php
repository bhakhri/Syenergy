 <?php 
//This file is used as CSV version for display cities.
// Author :Dipanjan Bhattacharjee
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

    require_once(MODEL_PATH . "/LeaveReportsManager.inc.php");
    $leaveManager = LeaveReportsManager::getInstance();
	
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

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
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Employee Leaves Analysis Report');
    
	$reportManager->setReportInformation("Leave Session : ".trim($REQUEST_DATA['yearName'])."  &nbsp;Leave Types : ".trim($REQUEST_DATA['leaveTypeName'])."<br>Criteria : ".trim($REQUEST_DATA['criteriaName'])."  Value : ".$criteriaValue);
	

    $reportTableHead                   =    array();
    $reportTableHead['srNo']		   =    array('#','width="2%" align="left"', "align='left'");
    $reportTableHead['employeeCode']   =    array('Employee Code ',' width=10% align="left" ','align="left" ');
    $reportTableHead['employeeName']   =    array('Employee Name',' width="15%" align="left" ','align="left"');
	$reportTableHead['leaveTypeName']  =    array('Leave Type',' width="10%" align="left" ','align="left"');
    $reportTableHead['noOfDays']       =    array('Days',' width="5%" align="right" ','align="right"');

    $reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
