 <?php 
//This file is used as printing version for designations.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();
	
	/// Search filter /////  
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
    $sorderBy = " $sortField $sortOrderBy";

	$timeTabelLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $classId=trim($REQUEST_DATA['classId']);
	$subjectId=trim($REQUEST_DATA['subjectId']);
	$employeeId=trim($REQUEST_DATA['employeeId']);
	
	if($timeTableLabelId != 0) {
		$getTimeTableLabelArray = $timeTableManager->getTimeTableLabel($timeTableLabelId);
		$timeTableLabelName =	$getTimeTableLabelArray[0]['labelName'];
	}
	else {
		$timeTableLabelName = 'ALL';
	}
	if($classId != 0) {
		$getClassNameArray = $timeTableManager->getClassName($classId);
		$className =	$getClassNameArray[0]['className'];
	}
	else {
		$className = 'ALL';
	}

	if($subjectId != 0) {
		$getSubjectArray = $timeTableManager->getSubjectName($subjectId);
		$subjectCode =	$getSubjectArray[0]['subjectCode'];
	}
	else {
		$subjectCode = 'ALL';
	}

	if($employeeId != 0) {
		$getEmployeeNameArray = $timeTableManager->getEmployeeName($employeeId);
		$employeeName =	$getEmployeeNameArray[0]['employeeName'];
	}
	else {
		$employeeName = 'ALL';
	}

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
					//$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
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
					//$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
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
					//$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
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
			if($subjectId != 0) {
				if($employeeId != 0) {
					$timeTableConditions = "AND tt.timeTableLabelId = $timeTabelLabelId AND tt.classId IN ($timeTableClassList) AND tt.subjectId = $subjectId AND tt.employeeId = $employeeId";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					//$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
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
					//$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
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
					//$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
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
					//$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
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
					//$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
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
				if($employeeId == 0) {
					$timeTableConditions = "AND tt.timeTableLabelId IN ($timeTabelLabelIds) AND tt.classId IN ($timeTableClassList) AND tt.subjectId = $subjectId AND tt.employeeId IN ($timeTableEmployeeList)";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					//$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
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
			if($subjectId == 0) {
				if($employeeId != 0) {
					$timeTableConditions = "AND tt.timeTableLabelId IN ($timeTabelLabelIds) AND tt.classId IN ($timeTableClassList) AND tt.subjectId IN ($timeTableSubjectList) AND tt.employeeId = $employeeId";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					//$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
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
					//$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
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
					//$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
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
				if($employeeId != 0) {
					$timeTableConditions = "AND tt.timeTableLabelId IN ($timeTabelLabelIds) AND tt.classId IN ($timeTableClassList) AND tt.subjectId IN ($timeTableSubjectList) AND tt.employeeId = $employeeId";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					//$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
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
				if($employeeId != 0) {
					$timeTableConditions = "AND tt.timeTableLabelId = $timeTabelLabelId AND tt.classId = $classId AND tt.subjectId IN ($timeTableSubjectList) AND tt.employeeId = $employeeId";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					//$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
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
			if($subjectId != 0) {
				if($employeeId == 0) {
					$timeTableConditions = "AND tt.timeTableLabelId = $timeTabelLabelId AND tt.classId IN ($timeTableClassList) AND tt.subjectId = $subjectId AND tt.employeeId IN ($timeTableEmployeeList)";
					$foundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,'',$sorderBy);
					$limitFoundArray = $timeTableManager->getTimeTableClassSubjectTeacher($timeTableConditions,$limit,$sorderBy);
				}
			}
		}
	}
    
                           
	$count = count($foundArray); 
    //$cnt = count($limitFoundArray);
	//echo($count);
	//echo($cnt);
	//die('line'.__LINE__);
	//$recordCount = count($foundArray);
	
	$designationPrintArray[] =  Array();
	//if($cnt >0 && is_array($foundArray) ) { 
		
		for($i=0; $i<$count; $i++ ) {
			
			$bg = $bg =='row0' ? 'row1' : 'row0';

			$thisEmployeeName = $foundArray[$i]['employeeName'];
				if($prevEmployeeName == $thisEmployeeName) {
					$foundArray[$i]['employeeName'] = '';
				}
				// add designationId in actionId to populate edit/delete icons in User Interface   
				$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$foundArray[$i]);

				/*if(trim($json_val)=='') {
					$json_val = json_encode($valueArray);
				}
				else {
					$json_val .= ','.json_encode($valueArray);
				}*/

				$prevEmployeeName = $thisEmployeeName;
		   
			//$valueArray[] = array_merge(array('srNo' => ($i+1) ),$designationArray[$i]);
		
		}
	//}
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Subject Taught By Teacher Report');
	$reportManager->setReportInformation("Search By: Time Table Label Name - ".$timeTableLabelName.", Class - ".$className.", Subject - ".$subjectCode.", Teacher - ".$employeeName );

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="center"', "align='center'");
    $reportTableHead['employeeName']		=    array('Teacher ',         ' width=10% align="left" ','align="left" ');
    $reportTableHead['className']			=    array('Class',        ' width="12%" align="left" ','align="left"');
	$reportTableHead['labelName']			=    array('Time Table Label Name',        ' width="12%" align="left" ','align="left"');
	$reportTableHead['subjectCode']			=    array('Subject',        ' width="18%" align="left" ','align="left"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
