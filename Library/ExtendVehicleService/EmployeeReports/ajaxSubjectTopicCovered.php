<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in all details report.
//
// Author :Rajeev Aggarwal
// Created on : 20-01-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    
    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");
    $employeeManager = EmployeeReportsManager::getInstance();
	
    global $sessionHandler;   
	 
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fromDate';
    
    
    $orderBy = " ORDER by $sortField $sortOrderBy";  
	
    $conditions = "";
    
    $timeTableLabelId   = add_slashes($REQUEST_DATA['labelId']);
    $employeeId   = add_slashes($REQUEST_DATA['employeeId']);
    $classId      = add_slashes($REQUEST_DATA['classId']);
    $subjectId    = add_slashes($REQUEST_DATA['subject']);
    $groupId      = add_slashes($REQUEST_DATA['group']);
    $subjectTopic = add_slashes($REQUEST_DATA['subjectTopic']);

    if($employeeId=='' || $classId == '' || $subjectId == '' || $subjectTopic == '' || $timeTableLabelId =='') {
       echo 0;
       die;
    }

    $conditions .= " AND cls.classId = ".$classId." AND (st.subjectTopicId IN (".$subjectTopic.")) ";  
    if(trim(strtolower($groupId))!='all') {
      $conditions .= " AND gr.groupId = $groupId";
    }
     
    $conditions .= " AND st.subjectId = $subjectId  AND tt.employeeId = ".$employeeId;  
    
	$startDate = $REQUEST_DATA['startDate'];
	$endDate = $REQUEST_DATA['endDate'];
	
	
	if($startDate!='' && $endDate =='')
		$conditions .= " AND fromDate >='$startDate' ";

	if($startDate=='' && $endDate!='')
		$conditions .= " AND toDate <='$endDate'";

	if($startDate!='' && $endDate!=''){
		$conditions .= " AND ((fromDate BETWEEN '$startDate' AND '$endDate') OR (toDate BETWEEN '$startDate' AND '$endDate'))";
	}
    
    $conditions .= " AND sub.hasAttendance = 1 ";
    
	$employeeRecordCount = $employeeManager->getTeacherSubjectTopic($conditions);
	$cnt1 = count($employeeRecordCount);

	$employeeRecordArray = $employeeManager->getTeacherSubjectTopic($conditions, $orderBy, $limit);
    $cnt = count($employeeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $employeeRecordArray[$i]['fromDate'] = UtilityManager::formatDate($employeeRecordArray[$i]['fromDate']);    
        $employeeRecordArray[$i]['toDate'] = UtilityManager::formatDate($employeeRecordArray[$i]['toDate']);    
        
        if($employeeRecordArray[$i]['topic'] != '') {
          $topic1 = $employeeRecordArray[$i]['topic'];  
          $employeeRecordArray[$i]['topic'] = $topic1; 
        }
        
		$valueArray = array_merge(array('srNo' => ($records+$i+1) ),$employeeRecordArray[$i]);
		if(trim($json_val)=='') {
			$json_val = json_encode($valueArray);
		}
		else {
			$json_val .= ','.json_encode($valueArray);           
		}
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt1.'","page":"'.$page.'","info" : ['.$json_val.']}'; 

//$History: ajaxSubjectTopicCovered.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/EmployeeReports
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/11/10    Time: 4:45p
//Updated in $/LeapCC/Library/EmployeeReports
//timetable label check added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/03/09   Time: 3:22p
//Created in $/LeapCC/Library/EmployeeReports
//initial checkin
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/25/09   Time: 10:53a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//paging function updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 10/24/09   Time: 4:17p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//sorting order updated (dates)
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 5:15p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//added access defines
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/15/09   Time: 11:49a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//date format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/06/09   Time: 2:51p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//class added, look & feel formating 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/03/09    Time: 12:26p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin
//

?>
