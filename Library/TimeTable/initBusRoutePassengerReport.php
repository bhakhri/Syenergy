<?php
//-------------------------------------------------------
// Purpose: To store the records of designation in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','BusRoutePassengerReport');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();

    /////////////////////////
    
	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
    $sorderBy = " $sortField $sortOrderBy";
	
	$timeTabelLabelId=trim($REQUEST_DATA['labelId']);
    $classId=trim($REQUEST_DATA['degree']);
	$subjectId=trim($REQUEST_DATA['subjectId']);
	$employeeId=trim($REQUEST_DATA['employeeId']);
	
	if($timeTabelLabelId == 0) {
		$timeTableArray = $timeTableManager->getAllTimeTable($conditions,$orderBy= 'timeTableLabelId');
		$timeTabelLabelIds = UtilityManager::makeCSList($timeTableArray,'timeTableLabelId');
		$conditions = " AND ttc.timeTableLabelId IN ($timeTabelLabelIds)";
		$classArray = $timeTableManager->getTimeTableAllClasses($conditions);
		$timeTableClassList = UtilityManager::makeCSList($classArray,'classId');
		if($classId == 0) {
			$subjectConditions = " AND tt.classId IN ($timeTableClassList)";
			$timeTableSubjectArray = $timeTableManager->getTimeTableClassSubjects($subjectConditions,$orderBy='s.subjectCode');
			$timeTableSubjectList = UtilityManager::makeCSList($timeTableSubjectArray,'subjectId');

			$employeeConditions = " AND tt.classId IN ($timeTableClassList)";
			$timeTableTeacherArray = $timeTableManager->getTimeTableClassTeacher($employeeConditions,$orderBy='employeeName');
			$timeTableEmployeeList = UtilityManager::makeCSList($timeTableTeacherArray,'employeeId');
			if($subjectId == 0) {
				if($employeeId == 0) {
					$timeTableConditions = "AND tt.timeTableLabelId IN ($timeTabelLabelIds) AND tt.classId IN ($timeTableClassList) AND tt.subjectId IN ($timeTableSubjectList) AND tt.employeeId IN ($timeTableEmployeeList)";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
				}
			}
		}
	}

	if($timeTabelLabelId != 0) {
		if($classId != 0) {
			if($subjectId != 0) {
				if($employeeId != 0) {
					$timeTableConditions = "AND tt.timeTableLabelId = $timeTabelLabelId AND tt.classId = $classId AND tt.subjectId = $subjectId AND tt.employeeId = $employeeId";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
				}
			}
		}
	}

	if($timeTabelLabelId != 0) {
		$conditions = " AND ttc.timeTableLabelId = $timeTabelLabelId";
		$classArray = $timeTableManager->getTimeTableAllClasses($conditions);
		$timeTableClassList = UtilityManager::makeCSList($classArray,'classId');
		if($classId == 0) {
			$subjectConditions = " AND tt.classId IN ($timeTableClassList)";
			$timeTableSubjectArray = $timeTableManager->getTimeTableClassSubjects($subjectConditions,$orderBy='s.subjectCode');
			$timeTableSubjectList = UtilityManager::makeCSList($timeTableSubjectArray,'subjectId');

			$employeeConditions = " AND tt.classId IN ($timeTableClassList)";
			$timeTableTeacherArray = $timeTableManager->getTimeTableClassTeacher($employeeConditions,$orderBy='employeeName');
			$timeTableEmployeeList = UtilityManager::makeCSList($timeTableTeacherArray,'employeeId');
			if($subjectId == 0) {
				if($employeeId == 0) {
					$timeTableConditions = "AND tt.timeTableLabelId = $timeTabelLabelId AND tt.classId IN ($timeTableClassList) AND tt.subjectId IN ($timeTableSubjectList) AND tt.employeeId IN ($timeTableEmployeeList)";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
				}
			}
		}
	}

	if($timeTabelLabelId != 0) {
		$conditions = " AND ttc.timeTableLabelId = $timeTabelLabelId";
		$classArray = $timeTableManager->getTimeTableAllClasses($conditions);
		$timeTableClassList = UtilityManager::makeCSList($classArray,'classId');
		if($classId != 0) {
			$subjectConditions = " AND tt.classId = $classId";
			$timeTableSubjectArray = $timeTableManager->getTimeTableClassSubjects($subjectConditions,$orderBy='s.subjectCode');
			$timeTableSubjectList = UtilityManager::makeCSList($timeTableSubjectArray,'subjectId');

			$employeeConditions = " AND tt.classId = $classId";
			$timeTableTeacherArray = $timeTableManager->getTimeTableClassTeacher($employeeConditions,$orderBy='employeeName');
			$timeTableEmployeeList = UtilityManager::makeCSList($timeTableTeacherArray,'employeeId');
			if($subjectId == 0) {
				if($employeeId == 0) {
					$timeTableConditions = "AND tt.timeTableLabelId = $timeTabelLabelId AND tt.classId = $classId AND tt.subjectId IN ($timeTableSubjectList) AND tt.employeeId IN ($timeTableEmployeeList)";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
				}
			}
		}
	}

	if($timeTabelLabelId != 0) {
		$conditions = " AND ttc.timeTableLabelId = $timeTabelLabelId";
		$classArray = $timeTableManager->getTimeTableAllClasses($conditions);
		$timeTableClassList = UtilityManager::makeCSList($classArray,'classId');
		if($classId != 0) {
			$subjectConditions = " AND tt.classId = $classId";
			$timeTableSubjectArray = $timeTableManager->getTimeTableClassSubjects($subjectConditions,$orderBy='s.subjectCode');
			$timeTableSubjectList = UtilityManager::makeCSList($timeTableSubjectArray,'subjectId');

			$employeeConditions = " AND tt.classId = $classId";
			$timeTableTeacherArray = $timeTableManager->getTimeTableClassTeacher($employeeConditions,$orderBy='employeeName');
			$timeTableEmployeeList = UtilityManager::makeCSList($timeTableTeacherArray,'employeeId');
			if($subjectId != 0) {
				if($employeeId == 0) {
					$timeTableConditions = "AND tt.timeTableLabelId = $timeTabelLabelId AND tt.classId = $classId AND tt.subjectId = $subjectId AND tt.employeeId IN ($timeTableEmployeeList)";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
				}
			}
		}
	}

	if($timeTabelLabelId == 0) {
		$timeTableArray = $timeTableManager->getAllTimeTable($newConditions,$orderBy= 'timeTableLabelId');
		$timeTabelLabelIds = UtilityManager::makeCSList($timeTableArray,'timeTableLabelId');
		$timeTableLabelConditions = " AND ttc.timeTableLabelId IN ($timeTabelLabelIds)";
		$classArray = $timeTableManager->getTimeTableAllClasses($timeTableLabelConditions);
		$timeTableClassList = UtilityManager::makeCSList($classArray,'classId');
		if($classId != 0) {
			$subjectConditions = " AND tt.classId = $classId";
			$timeTableSubjectArray = $timeTableManager->getTimeTableClassSubjects($subjectConditions,$orderBy='s.subjectCode');
			$timeTableSubjectList = UtilityManager::makeCSList($timeTableSubjectArray,'subjectId');

			$employeeConditions = " AND tt.classId = $classId";
			$timeTableTeacherArray = $timeTableManager->getTimeTableClassTeacher($employeeConditions,$orderBy='employeeName');
			$timeTableEmployeeList = UtilityManager::makeCSList($timeTableTeacherArray,'employeeId');
			if($subjectId != 0) {
				if($employeeId != 0) {
					$timeTableConditions = "AND tt.timeTableLabelId IN ($timeTabelLabelIds) AND tt.classId = $classId AND tt.subjectId = $subjectId AND tt.employeeId = $employeeId";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
				}
			}
		}
	}

	if($timeTabelLabelId == 0) {
		$timeTableArray = $timeTableManager->getAllTimeTable($newConditions,$orderBy= 'timeTableLabelId');
		$timeTabelLabelIds = UtilityManager::makeCSList($timeTableArray,'timeTableLabelId');
		$timeTableLabelConditions = " AND ttc.timeTableLabelId IN ($timeTabelLabelIds)";
		$classArray = $timeTableManager->getTimeTableAllClasses($timeTableLabelConditions);
		$timeTableClassList = UtilityManager::makeCSList($classArray,'classId');
		if($classId == 0) {
			$subjectConditions = " AND tt.classId IN ($timeTableClassList)";
			$timeTableSubjectArray = $timeTableManager->getTimeTableClassSubjects($subjectConditions,$orderBy='s.subjectCode');
			$timeTableSubjectList = UtilityManager::makeCSList($timeTableSubjectArray,'subjectId');

			$employeeConditions = " AND tt.classId IN ($timeTableClassList)";
			$timeTableTeacherArray = $timeTableManager->getTimeTableClassTeacher($employeeConditions,$orderBy='employeeName');
			$timeTableEmployeeList = UtilityManager::makeCSList($timeTableTeacherArray,'employeeId');
			if($subjectId != 0) {
				if($employeeId != 0) {
					$timeTableConditions = "AND tt.timeTableLabelId IN ($timeTabelLabelIds) AND tt.classId IN ($timeTableClassList) AND tt.subjectId = $subjectId AND tt.employeeId = $employeeId";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
				}
			}
		}
	}

	if($timeTabelLabelId == 0) {
		$timeTableArray = $timeTableManager->getAllTimeTable($newConditions,$orderBy= 'timeTableLabelId');
		$timeTabelLabelIds = UtilityManager::makeCSList($timeTableArray,'timeTableLabelId');
		$timeTableLabelConditions = " AND ttc.timeTableLabelId IN ($timeTabelLabelIds)";
		$classArray = $timeTableManager->getTimeTableAllClasses($timeTableLabelConditions);
		$timeTableClassList = UtilityManager::makeCSList($classArray,'classId');
		if($classId != 0) {
			$subjectConditions = " AND tt.classId = $classId";
			$timeTableSubjectArray = $timeTableManager->getTimeTableClassSubjects($subjectConditions,$orderBy='s.subjectCode');
			$timeTableSubjectList = UtilityManager::makeCSList($timeTableSubjectArray,'subjectId');

			$employeeConditions = " AND tt.classId = $classId";
			$timeTableTeacherArray = $timeTableManager->getTimeTableClassTeacher($employeeConditions,$orderBy='employeeName');
			$timeTableEmployeeList = UtilityManager::makeCSList($timeTableTeacherArray,'employeeId');
			if($subjectId == 0) {
				if($employeeId == 0) {
					$timeTableConditions = "AND tt.timeTableLabelId IN ($timeTabelLabelIds) AND tt.classId = $classId AND tt.subjectId IN ($timeTableSubjectList) AND tt.employeeId IN ($timeTableEmployeeList)";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
				}
			}
		}
	}


	if($timeTabelLabelId == 0) {
		$timeTableArray = $timeTableManager->getAllTimeTable($newConditions,$orderBy= 'timeTableLabelId');
		$timeTabelLabelIds = UtilityManager::makeCSList($timeTableArray,'timeTableLabelId');
		$timeTableLabelConditions = " AND ttc.timeTableLabelId IN ($timeTabelLabelIds)";
		$classArray = $timeTableManager->getTimeTableAllClasses($timeTableLabelConditions);
		$timeTableClassList = UtilityManager::makeCSList($classArray,'classId');
		if($classId != 0) {
			$subjectConditions = " AND tt.classId = $classId";
			$timeTableSubjectArray = $timeTableManager->getTimeTableClassSubjects($subjectConditions,$orderBy='s.subjectCode');
			$timeTableSubjectList = UtilityManager::makeCSList($timeTableSubjectArray,'subjectId');

			$employeeConditions = " AND tt.classId = $classId";
			$timeTableTeacherArray = $timeTableManager->getTimeTableClassTeacher($employeeConditions,$orderBy='employeeName');
			$timeTableEmployeeList = UtilityManager::makeCSList($timeTableTeacherArray,'employeeId');
			if($subjectId != 0) {
				if($employeeId == 0) {
					$timeTableConditions = "AND tt.timeTableLabelId IN ($timeTabelLabelIds) AND tt.classId IN ($timeTableClassList) AND tt.subjectId = $subjectId AND tt.employeeId IN ($timeTableEmployeeList)";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
				}
			}
		}
	}

	//die;
    ////////////
    $count = count($foundArray); 
    $cnt = count($limitFoundArray);

    $prevEmployeeName = '';
    for($i=0;$i<$cnt;$i++) {
		$thisEmployeeName = $foundArray[$i]['employeeName'];
		if($prevEmployeeName == $thisEmployeeName) {
			$foundArray[$i]['employeeName'] = '';
		}
        // add designationId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$foundArray[$i]);

		if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
		}
		else {
            $json_val .= ','.json_encode($valueArray);
		}
		$prevEmployeeName = $thisEmployeeName;

    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: $
//
?>