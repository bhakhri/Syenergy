<?php

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentAttendance');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$reportManager = StudentReportsManager::getInstance();

$subjectId = $REQUEST_DATA['subjectId'];
$sortField = $REQUEST_DATA['sortField'];
if ($sortField == 'rollNo') {
	$sortField = 'numericRollNo';
}
//$classId = $REQUEST_DATA['class'];

//fetch classId which match the criteria
/*
$classFilter = " WHERE universityId ='".$arr[0]."' AND degreeId='".$arr[1]."' AND branchId='".$arr[2]."' AND studyPeriodId='".$studyPeriodId."'";   
$classIdArray = $reportManager->getClassId($classFilter);
*/
$classId = $REQUEST_DATA['degree'];

//fetch all students for this class and for this subject

// to limit records per page    
$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
$records    = ($page-1)* RECORDS_PER_PAGE;
$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

$totalRecordsArray = $reportManager->getAttendanceData($classId, $subjectId, $sortField, $REQUEST_DATA['sortOrderBy'], '');

$studentsArray = $reportManager->getAttendanceData($classId, $subjectId, $sortField, $REQUEST_DATA['sortOrderBy'],$limit);

$cnt1=count($totalRecordsArray);
$cnt = count($studentsArray);
//echo '<pre>';
//print_r($studentsArray);
//die;

    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
		$studentsArray[$i]['Percentage'] = "0.00";
		if(intval($studentsArray[$i]['lectureAttended']) > 0 && intval($studentsArray[$i]['lectureDelivered']) > 0 ) {
			$studentsArray[$i]['Percentage'] = "".round($studentsArray[$i]['lectureAttended'] / $studentsArray[$i]['lectureDelivered']*100,1)."";
		}
		else {
			$studentsArray[$i]['Percentage'] = $studentsArray[$i]['Percentage'];
		}

        $valueArray = array(	'srNo' => ($records+$i+1) , 
								'studentId' => $studentsArray[$i]['studentId'],
								'universityRollNo' => $studentsArray[$i]['universityRollNo'],
								'rollNo' => $studentsArray[$i]['rollNo'],
								'studentName' => $studentsArray[$i]['studentName'],
								'lectureAttended' => $studentsArray[$i]['lectureAttended'],
								'lectureDelivered' => $studentsArray[$i]['lectureDelivered'],
								'percentage' => $studentsArray[$i]['Percentage']
								
							);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt1.'","page":"'.$page.'","info" : ['.$json_val.']}'; 


?>