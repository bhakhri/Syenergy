 <?php 
//This file is used as printing version for display Designation
//
// Author :Jaineesh
// Created on : 04.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php

    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();

	function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
            return '"'.$comments.'"'; 
         } 
         else {
            return chr(160).$comments; 
         }
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
    $sorderBy = " $sortField $sortOrderBy";

	$timeTabelLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $classId=trim($REQUEST_DATA['classId']);
	$subjectId=trim($REQUEST_DATA['subjectId']);
	$employeeId=trim($REQUEST_DATA['employeeId']);

	if($timeTabelLabelId != 0) {
		$getTimeTableLabelArray = $timeTableManager->getTimeTableLabel($timeTabelLabelId);
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

	$recordCount = count($foundArray);

    $valueArray = array();

    $csvData ='';
	$csvData .= "Search By : Time Table Label Name - ".$timeTableLabelName."  Class - ".$className."  Subject - ".$subjectCode."  Teacher - ".$employeeName;
	$csvData .= "\n";
    $csvData .="#,Teacher,Class,Time Table Label Name,Subject";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $foundArray[$i]['employeeName'].",";
		  $csvData .= $foundArray[$i]['className'].",";
		  $csvData .= $foundArray[$i]['labelName'].",";
		  $csvData .= parseCSVComments($foundArray[$i]['subjectCode']).",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'TimeTableTeacherReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>