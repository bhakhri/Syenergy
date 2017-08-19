<?php
    set_time_limit(0);  
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    UtilityManager::ifManagementNotLoggedIn(true);   
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/Management/DashboardManager.inc.php");
    $managementManager = DashBoardManager::getInstance();

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

    $degreeId =  add_slashes($REQUEST_DATA['degreeId']);
    $teacherId =  add_slashes($REQUEST_DATA['teacherId']);
    $subjectId =  add_slashes($REQUEST_DATA['subjectId']);
    $groupId =  add_slashes($REQUEST_DATA['groupId']);
    $testTypeCategoryId =  add_slashes($REQUEST_DATA['testTypeCategoryId']);
    
    $condition = '';
    $condition1 = '';
    if($degreeId != '') {
       $condition .= " AND a.classId IN ($degreeId)" ;
       $condition1 .= " AND c.classId IN ($degreeId)" ;
    }
    
    if($teacherId != '') {
       $condition .= " AND a.employeeId  IN ($teacherId)" ;
       $condition1 .= " AND a.employeeId  IN ($teacherId)" ;
    }
    
    if($subjectId != '') {
       $condition .= " AND a.subjectId IN ($subjectId)" ;
       $condition1 .= " AND a.subjectId IN ($subjectId)" ;  
    }
    
    if($groupId != '') {
       $condition .= " AND a.groupId IN ($groupId)" ;
       $condition1 .= " AND a.groupId IN ($groupId)" ;
    }
    
    if($testTypeCategoryId != '') {
       $condition .= " AND c.testTypeCategoryId IN ($testTypeCategoryId)" ;
    }
    
    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    $orderBy = " $sortField $sortOrderBy";         

    
	$activeTimeTableLabelArray = $managementManager->getActiveTimeTable();
	$activeTimeTableLabelId = $activeTimeTableLabelArray[0]['timeTableLabelId'];
	$teacherSubjectsArray = $managementManager->getTeacherSubjects($activeTimeTableLabelId,$condition1);
	$concatStr = "'0#0'";
	foreach($teacherSubjectsArray as $teacherSubjectRecord) {
		$subjectId = $teacherSubjectRecord['subjectId'];
		$classId = $teacherSubjectRecord['classId'];
        $employeeId = $teacherSubjectRecord['employeeId']; 
		if ($concatStr != '') {
			$concatStr .= ',';
		}
		$concatStr .= "'$subjectId#$classId'";
	}

	$teacherCountTestsArray = $managementManager->getCountTeacherTests($concatStr,$condition);
    $cnt = $teacherCountTestsArray[0]['cnt'];  
    
    $teacherTestsArray = $managementManager->getTeacherTests($concatStr,$condition,$orderBy,$limit);
	$cnt1 = count($teacherTestsArray);

    $json_val = "";
	for($i=0;$i<$cnt1;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface  
		$teacherTestsArray[$i]['testDate'] = UtilityManager::formatDate($teacherTestsArray[$i]['testDate']);
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$teacherTestsArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History: ajaxGetTeacherTests.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/10/10    Time: 4:48p
//Updated in $/LeapCC/Library/Management
//validation & format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/08/10    Time: 10:55a
//Created in $/LeapCC/Library/Management
//initial added
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 1/08/10    Time: 3:07p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//file added for teacher dashboard enhancements
//




?>