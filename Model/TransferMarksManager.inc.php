<?php
//-------------------------------------------------------
//  This File contains functions for marks transfer
// Author :Parveen Sharma
// Created on : 28-Dec-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class TransferMarksManager {

	private static $instance = null;
	private $classId = 0;
	private $timeTableLabelId = 0;
	private $currentProcess = 0;
	private $transferSubjectsArray = array();
	private $transferFinalSubjectsArray = array();
	private $transferAttPerSubjectsArray = array();
	private $transferAttSlabSubjectsArray = array();
	private $transferProcessRunning = false;

	private function __construct() {
	}

	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

	public function setClassId($classId) {
		$this->classId = $classId;
	}

	public function getClassId() {
		return $this->classId;
	}

	public function setTimeTableLabelId($timeTableLabelId) {
		$this->timeTableLabelId = $timeTableLabelId;
	}

	public function getTimeTableLabelId() {
		return $this->timeTableLabelId;
	}

	public function setCurrentProcess($currentProcess) {
		$this->currentProcess = $currentProcess;
	}

	public function getCurrentProcess() {
		return $this->currentProcess;
	}

	public function setTransferSubjects($transferSubjectsArray = array()) {
		$this->transferSubjectsArray = $transferSubjectsArray;
	}

	public function getTransferSubjects() {
		return $this->transferSubjectsArray;
	}

	public function setTransferFinalSubjects($transferFinalSubjectsArray = array()) {
		$this->transferFinalSubjectsArray = $transferFinalSubjectsArray;
	}

	public function getTransferFinalSubjects() {
		return $this->transferFinalSubjectsArray;
	}

	public function setTransferAttPerSubjectsArray($transferAttPerSubjectsArray = array()) {
		$this->transferAttPerSubjectsArray = $transferAttPerSubjectsArray;
	}

	public function getTransferAttPerSubjectsArray() {
		return $this->transferAttPerSubjectsArray;
	}

	public function setTransferAttSlabSubjectsArray($transferAttSlabSubjectsArray = array()) {
		$this->transferAttSlabSubjectsArray = $transferAttSlabSubjectsArray;
	}

	public function getTransferAttSlabSubjectsArray() {
		return $this->transferAttSlabSubjectsArray;
	}

	public function setTransferProcessRunning($transferProcessRunning) {
		$this->transferProcessRunning = $transferProcessRunning;
	}

	public function getTransferProcessRunning() {
		return $this->transferProcessRunning;
	}

	public function checkTimeTableClass($labelId, $classId) {
		if (empty($labelId) or !is_numeric($labelId)) {
			echo INVALID_TIMETABLE;
			die;
		}
		$ttClassesArray = $this->getTimeTableClasses($labelId);
		if (!is_array($ttClassesArray) or $ttClassesArray[0]['classId'] == '') {
			echo INVALID_TIMETABLE;
			die;
		}
		$classesArray = explode(',', UtilityManager::makeCSList($ttClassesArray,'classId'));
		if (!in_array($classId, $classesArray)) {
			echo INVALID_CLASS;
			die;
		}
	}

	public function storeTransferMarksManager($transferMarksManager) {
		$_SESSION['transferMarksObj'] = serialize($transferMarksManager);
	}

	public function fetchTransferMarksManager() {
		if (!isset($_SESSION['transferMarksObj']) or $_SESSION['transferMarksObj'] == '') {
			return false;
		}
		return unserialize($_SESSION['transferMarksObj']);
	}


	public function resetTimeTableClass($labelId, $classId) {
		$this->setClassId($classId);
		$this->setTimeTableLabelId($labelId);
	}

	public function unsetAllValues() {
		$this->classId = 0;
		$this->timeTableLableId = 0;
		$this->currentProcess = 0;
		$this->transferSubjectsArray = array();
		$this->transferAttPerSubjectsArray = array();
		$this->transferAttSlabSubjectsArray = array();
	}

	public function validateTimeTableClass($labelId, $classId) {
		if ($labelId != $this->getTimeTableLabelId()) {
			echo INVALID_TIMETABLE;
			die;
		}
		if ($classId != $this->getClassId()) {
			echo INVALID_CLASS;
			die;
		}
	}

	public function getTimeTableClasses($timeTableLabelId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		$sepratorLen = strlen(CLASS_SEPRATOR);

		$query = "	SELECT
							distinct cvtr.classId
					FROM	classes_visible_to_role cvtr
					WHERE	cvtr.userId = $userId
					AND		cvtr.roleId = $roleId";

		$result =  $systemDatabaseManager->executeQuery($query,"Query: $query");

		$count = count($result);
		$insertValue = "";
			for($i=0;$i<$count; $i++) {
				$querySeprator = '';
			    if($insertValue!='') {
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator ('".$result[$i]['classId']."')";
			}
		if ($count > 0) {
			$query = "
				SELECT
							distinct b.classId, b.className
				FROM
							class b,
							time_table_classes a,
							classes_visible_to_role cvtr
				WHERE		a.classId = b.classId
				AND			b.classId IN ($insertValue)
				AND			cvtr.classId = b.classId
				AND			cvtr.classId = a.classId
				AND			a.timeTableLabelId = $timeTableLabelId
				AND			b.isActive IN (1,3)
				AND			b.instituteId = $instituteId
				AND			b.sessionId = $sessionId order by b.degreeId,b.branchId,b.studyPeriodId";

			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}
		else {
		$query = "
				SELECT
								b.classId, b.className
				FROM
								class b, time_table_classes a
				WHERE			a.classId = b.classId
				AND				a.timeTableLabelId = $timeTableLabelId
				AND				b.isActive IN (1,3)
				AND				b.instituteId = $instituteId
				AND				b.sessionId = $sessionId order by b.degreeId,b.branchId,b.studyPeriodId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

	}

	public function getClassSubjectsTestTypes($classId, $conditions = '') {
		$query = "
			SELECT
						a.subjectId,     
						b.subjectCode,
						b.subjectName,
						a.internalTotalMarks,
						a.externalTotalMarks,
						b.hasAttendance,
						b.hasMarks,
						a.optional,
						a.hasParentCategory,
						a.offered,
						(select c.subjectTypeName FROM subject_type c, class cls WHERE b.subjectTypeId = c.subjectTypeId AND cls.classId = a.classId AND c.universityId = cls.universityId) AS subjectType,
						(
							SELECT IFNULL(SUM(weightageAmount),0) FROM test_type tt, class cls WHERE tt.conductingAuthority = 1 AND tt.subjectId = a.subjectId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.classId = $classId AND tt.instituteId = cls.instituteId and cls.classId = a.classId
						) AS internalTestTypeSum,
						(
							SELECT IFNULL(SUM(weightageAmount),0) FROM test_type tt, class cls WHERE tt.conductingAuthority = 2 AND tt.subjectId = a.subjectId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.instituteId = cls.instituteId  and tt.classId = $classId AND cls.classId = a.classId
						) AS externalTestTypeSum,
						(
							SELECT IFNULL(SUM(weightageAmount),0) FROM test_type tt, class cls WHERE tt.conductingAuthority = 3 AND tt.subjectId = a.subjectId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.instituteId = cls.instituteId  and tt.classId = $classId AND cls.classId = a.classId
						) AS attendanceTestTypeSum

			FROM		subject_to_class a, subject b
			WHERE		a.subjectId = b.subjectId
			AND		a.classId = $classId
			AND		a.hasParentCategory = 0
			$conditions
			UNION
			SELECT
						a.subjectId,
						b.subjectCode,
						b.subjectName,
						(select internalTotalMarks from subject_to_class where classId = $classId and subjectId = a.parentOfSubjectId) as internalTotalMarks,
						(select externalTotalMarks from subject_to_class where classId = $classId and subjectId = a.parentOfSubjectId) as externalTotalMarks,
						1 as hasAttendance,
						1 as hasMarks,
						0 as optional,
						0 as hasParentCategory,
						1 as offered,
						(select c.subjectTypeName FROM subject_type c, class cls WHERE b.subjectTypeId = c.subjectTypeId AND cls.classId = a.classId AND c.universityId = cls.universityId) AS subjectType,
						(
							SELECT IFNULL(SUM(weightageAmount),0) FROM test_type tt, class cls WHERE tt.conductingAuthority = 1 AND tt.subjectId = a.subjectId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.classId = $classId AND tt.instituteId = cls.instituteId and cls.classId = a.classId
						) AS internalTestTypeSum,
						(
							SELECT IFNULL(SUM(weightageAmount),0) FROM test_type tt, class cls WHERE tt.conductingAuthority = 2 AND tt.subjectId = a.subjectId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.instituteId = cls.instituteId  and tt.classId = $classId AND cls.classId = a.classId
						) AS externalTestTypeSum,
						(
							SELECT IFNULL(SUM(weightageAmount),0) FROM test_type tt, class cls WHERE tt.conductingAuthority = 3 AND tt.subjectId = a.subjectId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.instituteId = cls.instituteId  and tt.classId = $classId AND cls.classId = a.classId
						) AS attendanceTestTypeSum

			FROM		optional_subject_to_class a, subject b
			WHERE		a.subjectId = b.subjectId
			AND		a.classId = $classId
			$conditions
			ORDER BY	subjectCode";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getClassSubjects($classId) {
		$query = "
			SELECT
						a.subjectId,
						b.subjectCode,
						b.subjectName,
						a.externalTotalMarks,
						b.hasAttendance,
						b.hasMarks,
						a.optional,
						a.hasParentCategory,
						a.offered
			FROM		subject_to_class a, subject b
			WHERE		a.subjectId = b.subjectId
			AND		a.classId = $classId
			AND		a.hasParentCategory = 0
			UNION
			SELECT
						a.subjectId,
						b.subjectCode,
						b.subjectName,
						(select externalTotalMarks from subject_to_class where classId = 42 and subjectId = a.parentOfSubjectId) as externalTotalMarks,
						1 as hasAttendance,
						1 as hasMarks,
						0 as optional,
						0 as hasParentCategory,
						1 as offered
			FROM		optional_subject_to_class a, subject b
			WHERE		a.subjectId = b.subjectId
			AND		a.classId = $classId
			ORDER BY	subjectCode";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function checkClassSubject($classId, $subjectId) {
		$classSubjectsArray = $this->getClassSubjects($classId);
		if (!is_array($classSubjectsArray) or $classSubjectsArray[0]['subjectId'] == '') {
			echo NO_SUBJECT_ASSOCIATED_TO_THIS_CLASS;
			die;
		}
		$subjectIdArray = explode(',', UtilityManager::makeCSList($classSubjectsArray, 'subjectId'));
		if (!in_array($subjectId, $subjectIdArray)) {
			echo INVALID_SUBJECT_SELECTED;
			die;
		}
	}

	public function getTestTypeDetails($classId, $subjectId, $conditions = '') {
		$query = "
				SELECT
							ttc.testTypeCategoryId,
							ttc.testTypeName,
							ttc.examType,
							ttc.isAttendanceCategory,
							(SELECT IFNULL(tt.testTypeId,0) FROM test_type tt, class cls WHERE tt.testTypeCategoryId = ttc.testTypeCategoryId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.instituteId = cls.instituteId and cls.classId = $classId and tt.classId = $classId and tt.subjectId = $subjectId) AS testTypeId,
							(SELECT IFNULL(ev.evaluationCriteriaId,0) FROM evaluation_criteria ev, test_type tt, class cls WHERE tt.testTypeCategoryId = ttc.testTypeCategoryId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.instituteId = cls.instituteId and cls.classId = $classId and tt.classId = $classId and tt.subjectId = $subjectId AND tt.evaluationCriteriaId = ev.evaluationCriteriaId) AS evaluationCriteriaId,
							(SELECT IFNULL(tt.cnt,0) FROM test_type tt, class cls WHERE tt.testTypeCategoryId = ttc.testTypeCategoryId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.instituteId = cls.instituteId and cls.classId = $classId and tt.classId = $classId and tt.subjectId = $subjectId) AS cnt,
							(SELECT IFNULL(tt.weightageAmount,0) FROM test_type tt, class cls WHERE tt.testTypeCategoryId = ttc.testTypeCategoryId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.instituteId = cls.instituteId and cls.classId = $classId and tt.classId = $classId and tt.subjectId = $subjectId) AS weightageAmount
				FROM		test_type_category ttc
				WHERE		ttc.subjectTypeId = (SELECT subjectTypeId from subject where subjectId = $subjectId)
				$conditions
				ORDER BY	ttc.testTypeName
			";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getSubjectCode($subjectId) {
		$query = "SELECT subjectId, subjectCode, subjectName, subjectTypeId from subject where subjectId IN ($subjectId)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getEvCriteriaNonAtt() {
		$query = "
				SELECT
							evaluationCriteriaId, evaluationCriteriaName FROM evaluation_criteria
				WHERE		evaluationCriteriaId NOT IN (5,6)
				ORDER BY	evaluationCriteriaId
		";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getEvCriteriaAtt() {
		$query = "
				SELECT
							evaluationCriteriaId, evaluationCriteriaName FROM evaluation_criteria
				WHERE		evaluationCriteriaId IN (5,6)
				ORDER BY	evaluationCriteriaId
		";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getClassOtherSubjects($classId, $subjectId) {
		$query = "
			SELECT
							a.subjectId,
							b.subjectCode,
							b.subjectName
			FROM			subject_to_class a, subject b
			WHERE			a.subjectId = b.subjectId
			AND			a.classId = $classId
			AND			a.subjectId != $subjectId
			AND			b.subjectTypeId = (select subjectTypeId from subject where subjectId = $subjectId)
			AND			a.hasParentCategory = 0
			UNION
			SELECT
							a.subjectId,
							b.subjectCode,
							b.subjectName
			FROM			optional_subject_to_class a, subject b
			WHERE			a.subjectId = b.subjectId
			AND			a.classId = $classId
			AND			a.subjectId != $subjectId
			AND			b.subjectTypeId = (select subjectTypeId from subject where subjectId = $subjectId)
			ORDER BY		subjectCode";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getClassDetails($classId) {
		$query = "SELECT universityId, degreeId, branchId, studyPeriodId, instituteId, className FROM class where classId = '$classId'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function checkIfInArray($subjectId, $subjectIdArray = array()) {
		if (is_array($subjectIdArray)) {
			if (in_array($subjectId, $subjectIdArray)) {
				echo INVALID_COPY_TO_SUBJECTS;
				die;
			}
		}
	}

	public function deleteTestTypesInTransaction($universityId, $degreeId, $branchId, $studyPeriodId, $labelId, $instituteId, $subjectId) {
		$query = "DELETE FROM test_type WHERE universityId = $universityId AND degreeId = $degreeId AND branchId = $branchId AND studyPeriodId = $studyPeriodId AND timeTableLabelId = $labelId AND instituteId = $instituteId AND subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function addTestTypesInTransaction($insertStr) {
		$query = "INSERT INTO test_type (testTypeName, testTypeCode, testTypeAbbr, universityId, degreeId, branchId, weightageAmount, weightagePercentage, subjectId, studyPeriodId, evaluationCriteriaId, cnt, sortOrder, subjectTypeId, conductingAuthority, timeTableLabelId, testTypeCategoryId, instituteId, classId) VALUES $insertStr";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function getAttendancePercentSlabs($evaluationCriteriaId) {
		$query = "SELECT attendanceSetId, attendanceSetName from attendance_set where evaluationCriteriaId = $evaluationCriteriaId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getSubjectsForPercentSlabs($classId, $postSubjectId, $evaluationCriteriaId) {
		$query = "
				SELECT
							tt.subjectId,
							sub.subjectCode,
							sub.subjectName,
							tt.weightageAmount,
							(
								SELECT
												b.attendanceSetName
								from			subject_to_class a, attendance_set b
								where			a.classId = $classId
								AND			a.subjectId = tt.subjectId
								AND			a.attendanceSetId = b.attendanceSetId
								UNION
								SELECT
												b.attendanceSetName
								from			optional_subject_to_class a, attendance_set b
								where			a.classId = $classId
								AND			a.subjectId = tt.subjectId
								AND			a.attendanceSetId = b.attendanceSetId
							) AS attendanceSetName
				FROM		test_type tt,
							subject sub
				WHERE		tt.subjectId = sub.subjectId
				AND			tt.subjectId IN ($postSubjectId)
				AND			tt.classId = $classId
				AND			tt.evaluationCriteriaId = $evaluationCriteriaId
		";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function checkTransferAttPerSubjects($classId, $transferAttPerSubjectsArray = array()) {
		$classSubjectsArray = $this->getClassSubjects($classId);
		if (!is_array($classSubjectsArray) or $classSubjectsArray[0]['subjectId'] == '') {
			echo NO_SUBJECT_ASSOCIATED_TO_THIS_CLASS;
			die;
		}
		if (!is_array($transferAttPerSubjectsArray)) {
			echo NO_SUBJECT_SELECTED_TO_APPLY_ATTENDANCE_SET;
			die;
		}
		$subjectIdArray = explode(',', UtilityManager::makeCSList($classSubjectsArray, 'subjectId'));
		$totalTransferSubjectsArray = $this->getTransferSubjects();
		foreach($transferAttPerSubjectsArray  as $key => $subjectId) {
			if (!in_array($subjectId, $subjectIdArray)) {
				echo INVALID_SUBJECT_SELECTED;
				die;
			}
			if (!in_array($subjectId, $totalTransferSubjectsArray)) {
				echo INVALID_SUBJECT_SELECTED;
				die;
			}
		}

		$this->setTransferAttPerSubjectsArray($transferAttPerSubjectsArray);
	}

	public function checkAttendanceSetId($attendanceSetPercentId, $evaluationCriteriaId) {
		$query = "SELECT COUNT(attendanceSetId) as cnt from  attendance_set where attendanceSetId = $attendanceSetPercentId AND evaluationCriteriaId = $evaluationCriteriaId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function updateAttendanceSetPercentSlab($classId, $attSetPerSubjectId, $attendanceSetPercentToApply) {
		$query = "UPDATE subject_to_class SET attendanceSetId = $attendanceSetPercentToApply WHERE classId = $classId AND subjectId IN ($attSetPerSubjectId)";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function updateAttendanceSetPercentSlabOptional($classId, $attSetPerSubjectId, $attendanceSetPercentToApply) {
		$query = "UPDATE optional_subject_to_class SET attendanceSetId = $attendanceSetPercentToApply WHERE classId = $classId AND subjectId IN ($attSetPerSubjectId)";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function deleteAttendanceMarksInTransaction($checkAttendanceSetId) {
		$query = "DELETE FROM attendance_marks_percent WHERE attendanceSetId = $checkAttendanceSetId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function deleteAttendanceMarksSlabInTransaction($attendanceSetSlab) {
		$query = "DELETE FROM attendance_marks_slabs WHERE attendanceSetId = $attendanceSetSlab";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function getAttendancePercentDetails($attendanceSetPercentId) {
		$query = "SELECT percentFrom, percentTo, marksScored FROM attendance_marks_percent WHERE attendanceSetId =$attendanceSetPercentId ORDER BY marksScored";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	public function checkAttendanceSetName($attendanceSetPercentName, $conditions = '') {
		$query = "SELECT COUNT(attendanceSetName) as cnt from  attendance_set where UCASE(attendanceSetName) = '".strtoupper($attendanceSetPercentName)."' $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	public function validateAttendanceSetPercentData() {
		global $REQUEST_DATA;
		if (!is_array($REQUEST_DATA['percentFrom'])) {
			echo INVALID_VALUES_FOR_PERCENT_FROM;
			die;
		}
		elseif (!is_array($REQUEST_DATA['percentTo'])) {
			echo INVALID_VALUES_FOR_PERCENT_TO;
			die;
		}
		elseif (!is_array($REQUEST_DATA['marksScored'])) {
			echo INVALID_VALUES_FOR_MARKS_SCORED;
			die;
		}
		elseif (count($REQUEST_DATA['percentFrom']) != count($REQUEST_DATA['percentTo'])) {
			echo INVALID_VALUES_ENTERED;
			die;
		}
		elseif (count($REQUEST_DATA['percentFrom']) != count($REQUEST_DATA['marksScored'])) {
			echo INVALID_VALUES_ENTERED;
			die;
		}

		$postFromArray = $REQUEST_DATA['percentFrom'];
		$percentToArray = $REQUEST_DATA['percentTo'];
		$marksScoredArray = $REQUEST_DATA['marksScored'];

		$prevTo = '';

		foreach($postFromArray as $key => $from) {
			if (!is_numeric($from) or $from < 0) {
				echo INVALID_VALUES_ENTERED_IN_FROM;
				die;
			}
			elseif (!isset($percentToArray[$key])) {
				echo INVALID_VALUES_ENTERED_IN_TO;
				die;
			}
			elseif (!is_numeric($percentToArray[$key]) or $percentToArray[$key] < 0) {
				echo INVALID_VALUES_ENTERED_IN_TO;
				die;
			}
			elseif (!isset($marksScoredArray[$key])) {
				echo INVALID_VALUES_ENTERED_IN_MARKS;
				die;
			}
			elseif (!is_numeric($marksScoredArray[$key]) or $marksScoredArray[$key] < 0) {
				echo INVALID_VALUES_ENTERED_IN_MARKS;
				die;
			}

			if ($prevTo != '') {
				if ($from < $prevTo) {
					echo INVALID_FROM_VALUES_ENTERED_FOR_MARKS_.$marksScoredArray[$key];
					die;
				}
			}

			$to = $percentToArray[$key];
			if ($to < $from) {
				echo INVALID_TO_VALUES_ENTERED_FOR_MARKS_.$marksScoredArray[$key];
				die;
			}
			$prevTo = $to;
		}
	}

	public function addAttendanceSetInTransaction($attendanceSetPercentName, $evaluationCriteriaId){
		$query = "INSERT INTO attendance_set SET attendanceSetName = '$attendanceSetPercentName', evaluationCriteriaId = $evaluationCriteriaId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function getMaxAttendanceSetId($evaluationCriteriaId) {
		$query = "SELECT MAX(attendanceSetId) as attendanceSetId from attendance_set where evaluationCriteriaId = $evaluationCriteriaId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	public function addAttendanceMarksInTransaction($insertStr) {
		$query = "INSERT INTO attendance_marks_percent(percentFrom, percentTo, marksScored, timeTableLabelId, instituteId, attendanceSetId) VALUES $insertStr";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function addAttendanceMarksSlabsInTransaction($insertStr) {
		$query = "INSERT INTO attendance_marks_slabs(lectureDelivered, lectureAttended, marksScored, timeTableLabelId, instituteId, attendanceSetId) VALUES $insertStr";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function checkAttendanceSet($attendanceSetPercentId, $evaluationCriteriaId) {
		$query = "SELECT COUNT(attendanceSetId) AS cnt FROM attendance_set WHERE attendanceSetId = '$attendanceSetPercentId' AND evaluationCriteriaId = '$evaluationCriteriaId'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getAttendanceSlabDetails($attendanceSetSlabId) {
		$query = "SELECT DISTINCT lectureDelivered, min(lectureAttended) AS lectureAttendedFrom, max(lectureAttended) AS lectureAttendedTo, marksScored from attendance_marks_slabs where attendanceSetId = $attendanceSetSlabId group by lectureDelivered, marksScored";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countMissingStudents($classId, $subjectId, $testTypeCategoryId) {
		$query = "SELECT 
                      COUNT(find) as cnt 
                  FROM 
                      (SELECT 
                          b.studentId,FIND_IN_SET('$testTypeCategoryId', CONVERT(GROUP_CONCAT(DISTINCT a.testTypeCategoryId),CHAR)) AS find 
                       FROM 
                          ".TEST_TABLE." a, ".TEST_MARKS_TABLE." b, student c, student_groups sg
                       WHERE	
                           a.testId = b.testId AND a.classId = '$classId' AND 
                           a.subjectId = '$subjectId' AND b.studentId = c.studentId AND
                           sg.studentId = c.studentId AND sg.classId = '$classId' 
                       GROUP BY b.studentId) AS abc 
                   WHERE 
                       find = 0";
                       
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getMissingStudents($classId, $subjectId, $testTypeCategoryId) {
		$query = "SELECT 
                      * 
                   FROM 
                      (SELECT 
                             b.studentId, c.rollNo, concat(c.firstName,' ',c.lastName) as studentName, 
                             FIND_IN_SET('$testTypeCategoryId', CONVERT(GROUP_CONCAT(DISTINCT a.testTypeCategoryId),CHAR)) AS find 
                       FROM 
                          ".TEST_TABLE." a, ".TEST_MARKS_TABLE." b, student c, student_groups sg
                       WHERE    
                           a.testId = b.testId AND a.classId = '$classId' AND 
                           a.subjectId = '$subjectId' AND b.studentId = c.studentId AND
                           sg.studentId = c.studentId AND sg.classId = '$classId' 
                       GROUP BY b.studentId) AS abc 
                   WHERE 
                       find = 0";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getSubjectTestTypeTestMarks($classId, $subjectId, $testTypeId, $conditions = '') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
				SELECT
							a.studentId,
							c.maxMarks,
							c.marksScored,
							IF(IFNULL(c.maxMarks,0)=0,0,(c.marksScored/c.maxMarks)*100) AS per
				FROM		student a, ".TEST_TABLE." b, ".TEST_MARKS_TABLE." c
				WHERE		b.classId = $classId
				AND			a.studentId = c.studentId
				AND			b.testId = c.testId
				AND			b.subjectId = $subjectId
				AND			b.testTypeCategoryId IN  (SELECT testTypeCategoryId FROM test_type WHERE testTypeId = $testTypeId AND instituteId = $instituteId)
							$conditions
				ORDER BY	a.studentId,per DESC";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countMissingOptionalSubjectStudents($classId, $subjectId, $testTypeCategoryId) {
		$query = "SELECT COUNT(find) as cnt FROM (SELECT b.studentId, FIND_IN_SET('$testTypeCategoryId', CONVERT(GROUP_CONCAT(DISTINCT a.testTypeCategoryId),CHAR)) AS find FROM ".TEST_TABLE." a, ".TEST_MARKS_TABLE." b, student c, student_optional_subject d WHERE a.testId = b.testId AND a.classId = $classId AND a.classId = d.classId AND a.subjectId = $subjectId AND a.subjectId = d.subjectId AND b.studentId = c.studentId AND b.studentId = d.studentId GROUP BY b.studentId) AS abc WHERE find = 0";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getMissingOptionalSubjectStudents($classId, $subjectId, $testTypeCategoryId) {
		$query = "SELECT * FROM (SELECT b.studentId, c.rollNo, concat(c.firstName,' ',c.lastName) as studentName, FIND_IN_SET('$testTypeCategoryId', CONVERT(GROUP_CONCAT(DISTINCT a.testTypeCategoryId),CHAR)) AS find FROM ".TEST_TABLE." a, ".TEST_MARKS_TABLE." b, student c, student_optional_subject d WHERE a.testId = b.testId AND a.classId = $classId AND a.classId = d.classId AND a.subjectId = $subjectId AND a.subjectId = d.subjectId AND b.studentId = c.studentId AND b.studentId = d.studentId GROUP BY b.studentId) AS abc WHERE find = 0";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countOptionalChildSubjectStudents($classId, $subjectId) {
		$query = "SELECT count(studentId) as cnt from student_optional_subject where classId = $classId and parentOfSubjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getMMSubjects($classId, $subjectId) {
		$query = "SELECT distinct(a.subjectId) as subjectId, b.subjectCode from student_optional_subject a, subject b
		where  a.classId = $classId and a.parentOfSubjectId = $subjectId and a.subjectId = b.subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function validateAttendanceSetSlabData() {
		global $REQUEST_DATA;
		if (!is_array($REQUEST_DATA['lectureDelivered'])) {
			echo INVALID_VALUES_FOR_LECTURES_DELIVERED;
			die;
		}
		elseif (!is_array($REQUEST_DATA['lectureAttendedFrom'])) {
			echo INVALID_VALUES_FOR_ATTENDED_FROM;
			die;
		}
		elseif (!is_array($REQUEST_DATA['lectureAttendedTo'])) {
			echo INVALID_VALUES_FOR_ATTENDED_TO;
			die;
		}
		elseif (!is_array($REQUEST_DATA['marksScored'])) {
			echo INVALID_VALUES_FOR_MARKS_SCORED;
			die;
		}
		elseif (count($REQUEST_DATA['lectureDelivered']) != count($REQUEST_DATA['lectureAttendedFrom'])) {
			echo INVALID_VALUES_ENTERED;
			die;
		}
		elseif (count($REQUEST_DATA['lectureDelivered']) != count($REQUEST_DATA['lectureAttendedTo'])) {
			echo INVALID_VALUES_ENTERED;
			die;
		}
		elseif (count($REQUEST_DATA['lectureDelivered']) != count($REQUEST_DATA['marksScored'])) {
			echo INVALID_VALUES_ENTERED;
			die;
		}

		$lectureDeliveredArray = $REQUEST_DATA['lectureDelivered'];
		$lectureAttendedFromArray = $REQUEST_DATA['lectureAttendedFrom'];
		$lectureAttendedToArray = $REQUEST_DATA['lectureAttendedTo'];
		$marksScoredArray = $REQUEST_DATA['marksScored'];

		$prevTo = '';
		$prevDelivered = '';

		foreach($lectureDeliveredArray as $key => $lectureDelivered) {
			if (!is_numeric($lectureDelivered) or $lectureDelivered < 0) {
				echo INVALID_VALUES_FOR_LECTURES_DELIVERED;
				die;
			}
			elseif (!isset($lectureAttendedFromArray[$key])) {
				echo INVALID_VALUES_FOR_LECTURES_ATTENDED_FROM;
				die;
			}
			elseif (!is_numeric($lectureAttendedFromArray[$key]) or $lectureAttendedFromArray[$key] < 0) {
				echo INVALID_VALUES_FOR_LECTURES_ATTENDED_FROM;
				die;
			}
			elseif (!isset($lectureAttendedToArray[$key])) {
				echo INVALID_VALUES_FOR_LECTURES_ATTENDED_TO;
				die;
			}
			elseif (!is_numeric($lectureAttendedToArray[$key]) or $lectureAttendedToArray[$key] < 0) {
				echo INVALID_VALUES_FOR_LECTURES_ATTENDED_TO;
				die;
			}
			elseif (!isset($marksScoredArray[$key])) {
				echo INVALID_VALUES_FOR_MARKS_SCORED;
				die;
			}
			elseif (!is_numeric($marksScoredArray[$key]) or $marksScoredArray[$key] < 0) {
				echo INVALID_VALUES_FOR_MARKS_SCORED;
				die;
			}

			//$lectureDelivered = $lectureAttendedFromArray[$key];
			$lectureAttendedFrom = $lectureAttendedFromArray[$key];
			$lectureAttendedTo = $lectureAttendedToArray[$key];

			if ($prevTo != '' and $lectureDelivered == $prevDelivered) {
				if ($lectureAttendedFrom <= $prevTo) {
					echo INVALID_FROM_VALUES_ENTERED_FOR_LECTURES_DELIVERED_.$lectureDelivered._AT_MARKS_.$marksScoredArray[$key];
					die;
				}
			}

			if ($lectureAttendedTo < $lectureAttendedFrom) {
				echo INVALID_FROM_VALUES_ENTERED_FOR_LECTURES_DELIVERED_.$lectureDelivered._AT_MARKS_.$marksScoredArray[$key];
				die;
			}

			if ($lectureAttendedToArray[$key] > $lectureDelivered) {
				echo LECTURES_ATTENDED_CAN_NOT_BE_MORE_THAN_LECTURES_DELIVERED;
				die;
			}

			$prevDelivered = $lectureDelivered;
			$prevTo = $lectureAttendedTo;
		}
	}


	public function checkTransferAttSlabSubjects($classId, $transferAttSlabSubjectsArray = array()) {
		$classSubjectsArray = $this->getClassSubjects($classId);
		if (!is_array($classSubjectsArray) or $classSubjectsArray[0]['subjectId'] == '') {
			echo NO_SUBJECT_ASSOCIATED_TO_THIS_CLASS;
			die;
		}
		if (!is_array($transferAttSlabSubjectsArray)) {
			echo NO_SUBJECT_SELECTED_TO_APPLY_ATTENDANCE_SET;
			die;
		}
		$subjectIdArray = explode(',', UtilityManager::makeCSList($classSubjectsArray, 'subjectId'));
		$totalTransferSubjectsArray = $this->getTransferSubjects();
		foreach($transferAttSlabSubjectsArray  as $key => $subjectId) {
			if (!in_array($subjectId, $subjectIdArray)) {
				echo INVALID_SUBJECT_SELECTED;
				die;
			}
			if (!in_array($subjectId, $totalTransferSubjectsArray)) {
				echo INVALID_SUBJECT_SELECTED;
				die;
			}
		}

		$this->setTransferAttSlabSubjectsArray($transferAttSlabSubjectsArray);
	}

	public function countClassTests($classId, $subjectId, $testTypeCategoryId) {
		$query = "select count(testId) as cnt from ".TEST_TABLE." where classId = $classId and subjectId = $subjectId and testTypeCategoryId = $testTypeCategoryId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countOptionalSubjectStudents($classId, $subjectId) {
		$query = "SELECT count(studentId) as cnt from student_optional_subject where classId = $classId and subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countPrentOptionalSubjectStudents($classId, $subjectId) {
		$query = "SELECT count(studentId) as cnt from student_optional_subject where classId = $classId and parentOfSubjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getOptionalSubjectTestTypeTestMarks($classId, $subjectId, $testTypeId, $conditions = '') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
				SELECT
							a.studentId,
							c.maxMarks,
							c.marksScored,
							ROUND((c.marksScored/c.maxMarks)*100) AS per
				FROM		student a, ".TEST_TABLE." b, ".TEST_MARKS_TABLE." c, student_optional_subject d
				WHERE		b.classId = $classId
				AND			b.classId = d.classId
				AND			a.studentId = c.studentId
				AND			a.studentId = d.studentId
				AND			b.testId = c.testId
				AND			b.subjectId = $subjectId
				AND			b.subjectId = d.subjectId
				AND			b.testTypeCategoryId IN  (SELECT testTypeCategoryId FROM test_type WHERE testTypeId = $testTypeId AND instituteId = $instituteId)
							$conditions
				ORDER BY	a.studentId,per DESC";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentAttendancePercentageMarks($classId, $subjectId, $attendanceSetId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
					SELECT grp.studentId, att.marksScored FROM (SELECT a.studentId, FORMAT(SUM(IF(a.isMemberOfClass=0,0, IF(a.attendanceType =2, (b.attendanceCodePercentage /100), a.lectureAttended ))), 1 ) AS lectureAttended, (
						SELECT COUNT(dt.dutyLeaveId)
						FROM  ".DUTY_LEAVE_TABLE."  dt WHERE (dt.studentId, dt.classId, dt.subjectId, dt.groupId, dt.dutyDate, dt.periodId)
						IN (SELECT att.studentId, att.classId, att.subjectId, att.groupId, att.fromDate, att.periodId FROM ".ATTENDANCE_TABLE." att, attendance_code ac WHERE att.classId = $classId AND att.subjectId = $subjectId and att.studentId = a.studentId and ac.attendanceCodePercentage = 0)
						and dt.studentId = a.studentId and dt.rejected = ".DUTY_LEAVE_APPROVE."
						and dt.classId = $classId and dt.subjectId = $subjectId
					) as dutyLeaves, SUM(IF(a.isMemberOfClass=0,0, a.lectureDelivered)) AS lectureDelivered FROM ".ATTENDANCE_TABLE." a LEFT JOIN attendance_code b ON (a.attendanceCodeId = b.attendanceCodeId and b.instituteId = $instituteId) WHERE a.classId = $classId AND a.subjectId = $subjectId group by a.studentId) AS grp, attendance_marks_percent AS att, student stu WHERE grp.studentId = stu.studentId AND att.attendanceSetId = $attendanceSetId AND ceil(((grp.lectureAttended + IFNULL(grp.dutyLeaves,0))*100)/grp.lectureDelivered) between att.percentFrom and att.percentTo order by grp.studentId";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    
    public function getStudentAttendanceDetial($classId, $subjectId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        a.studentId, 
                        FORMAT(SUM(IF(a.isMemberOfClass=0,0, 
                            IF(a.attendanceType =2, (b.attendanceCodePercentage /100), a.lectureAttended ))), 1 ) AS lectureAttended, 
                        IFNULL((SELECT 
                                     COUNT(DISTINCT dt.studentId, dt.classId, dt.subjectId, dt.groupId, dt.dutyDate, dt.periodId)
                                FROM 
                                      ".DUTY_LEAVE_TABLE."  dt 
                                WHERE 
                                     (dt.studentId, dt.classId, dt.subjectId, dt.groupId, dt.dutyDate, dt.periodId)
                                     IN 
                                     (SELECT 
                                         DISTINCT att.studentId, att.classId, att.subjectId, att.groupId, att.fromDate, att.periodId 
                                      FROM 
                                        ".ATTENDANCE_TABLE." att, attendance_code ac 
                                      WHERE 
                                        att.classId = $classId AND att.subjectId = $subjectId AND att.studentId = a.studentId AND 
                                        ac.attendanceCodePercentage = 0)
                                      AND 
                                        dt.studentId = a.studentId AND dt.rejected =  ".DUTY_LEAVE_APPROVE."  
                                      AND 
                                        dt.classId = $classId AND dt.subjectId = $subjectId 
                                ),'0') AS dutyLeaves, 
                        SUM(IF(a.isMemberOfClass=0,0, a.lectureDelivered)) AS lectureDelivered 
                    FROM 
                        ".ATTENDANCE_TABLE."  a 
                        LEFT JOIN attendance_code b ON (a.attendanceCodeId = b.attendanceCodeId AND b.instituteId = $instituteId) 
                    WHERE 
                        a.classId = $classId AND a.subjectId = $subjectId 
                    GROUP BY 
                        a.studentId
                    ORDER BY 
                        a.studentId ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getAttendanceSetSlabDetails($attendanceSetId) {
		$query = "SELECT lectureDelivered, lectureAttended, marksScored FROM attendance_marks_slabs WHERE attendanceSetId = $attendanceSetId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getAttendanceSetPercentDetails($attendanceSetId) {
		$query = "SELECT percentFrom, percentTo, marksScored FROM attendance_marks_percent WHERE attendanceSetId = $attendanceSetId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentAttendanceDetails($classId, $subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
					SELECT a.studentId, FORMAT(SUM(IF(a.isMemberOfClass=0,0, IF(a.attendanceType =2, (b.attendanceCodePercentage /100), a.lectureAttended ))), 1 ) AS lectureAttended, SUM(IF(a.isMemberOfClass=0,0, a.lectureDelivered)) AS lectureDelivered FROM ".ATTENDANCE_TABLE." a LEFT JOIN attendance_code b ON (a.attendanceCodeId = b.attendanceCodeId and b.instituteId = $instituteId) WHERE a.classId = $classId AND a.subjectId = $subjectId group by a.studentId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentDutyLeaveDetails($classId, $subjectId) {
		
        $query = "SELECT
                       tt.studentId, IFNULL(COUNT(tt.rejected),0) AS dutyLeaves   
                  FROM
                      (SELECT
                            DISTINCT a.studentId, a.subjectId, a.groupId, a.classId, a.periodId, a.rejected,
                            a.dutyDate, b.fromDate, b.toDate, b.isMemberOfClass      
                       FROM     
                            ".ATTENDANCE_TABLE." b,   ".DUTY_LEAVE_TABLE."  a, attendance_code c  
                       WHERE
                            a.rejected = ".DUTY_LEAVE_APPROVE."
                            and a.classId = b.classId
                            and a.subjectId = b.subjectId
                            and a.groupId = b.groupId
                            and a.dutyDate = b.fromDate
                            and a.dutyDate = b.toDate
                            and a.periodId = b.periodId
                            and a.studentId = b.studentId
                            and a.classId = $classId
                            and a.subjectId = $subjectId
                            and b.attendanceCodeId = c.attendanceCodeId
                            and c.attendanceCodePercentage = 0
                            and b.isMemberOfClass = 1 ) AS tt 
                  GROUP BY tt.studentId";
        
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getOptSubStudentAttendancePercentageMarks($classId, $subjectId, $attendanceSetId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
					SELECT grp.studentId, att.marksScored FROM (SELECT a.studentId, FORMAT(SUM(IF(a.isMemberOfClass=0,0, IF(a.attendanceType =2, (b.attendanceCodePercentage /100), a.lectureAttended ))), 1 ) AS lectureAttended, (SELECT COUNT(dutyLeaveId) from  ".DUTY_LEAVE_TABLE."  where studentId = a.studentId and classId = a.classId and subjectId = a.subjectId AND rejected = ".DUTY_LEAVE_APPROVE." and groupId = a.groupId and dutyDate = a.fromDate and periodId = a.periodId) as dutyLeaves,SUM(IF(a.isMemberOfClass=0,0, a.lectureDelivered)) AS lectureDelivered FROM ".ATTENDANCE_TABLE." a LEFT JOIN attendance_code b ON (a.attendanceCodeId = b.attendanceCodeId and b.instituteId = $instituteId) WHERE a.classId = $classId AND a.subjectId = $subjectId group by a.studentId) AS grp, attendance_marks_percent AS att, student stu WHERE grp.studentId = stu.studentId AND stu.studentId IN (SELECT studentId FROM student_optional_subject WHERE classId = $classId AND subjectId = $subjectId) AND att.attendanceSetId = $attendanceSetId AND ceil(((grp.lectureAttended + IFNULL(grp.dutyLeaves,0))*100)/grp.lectureDelivered) between att.percentFrom and att.percentTo order by grp.studentId";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getOptSubStudentAttendanceSlabsMarks($classId, $subjectId, $attendanceSetId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
						SELECT grp.studentId, grp.lectureDelivered,grp.lectureAttended,ams.marksScored FROM (SELECT a.studentId, FORMAT(SUM(IF(a.isMemberOfClass=0,0, IF(a.attendanceType =2, (b.attendanceCodePercentage /100), a.lectureAttended )) ) , 1 ) AS lectureAttended, (SELECT COUNT(dutyLeaveId) from  ".DUTY_LEAVE_TABLE."  where studentId = a.studentId and classId = a.classId and subjectId = a.subjectId AND rejected = ".DUTY_LEAVE_APPROVE." and groupId = a.groupId and dutyDate = a.fromDate and periodId = a.periodId) as dutyLeaves, SUM(IF(a.isMemberOfClass=0,0, a.lectureDelivered)) AS lectureDelivered FROM ".ATTENDANCE_TABLE." a LEFT JOIN attendance_code b ON (a.attendanceCodeId = b.attendanceCodeId and b.instituteId = $instituteId) WHERE a.classId = $classId AND a.subjectId = $subjectId group by a.studentId) as grp, attendance_marks_slabs ams, student stu WHERE grp.studentId = stu.studentId AND stu.studentId IN (SELECT studentId FROM student_optional_subject WHERE classId = $classId AND subjectId = $subjectId) AND grp.lectureDelivered = ams.lectureDelivered and (grp.lectureAttended + ifnull(dutyLeaves,0)) = ams.lectureAttended AND ams.attendanceSetId = $attendanceSetId AND ceil(((grp.lectureAttended + IFNULL(grp.dutyLeaves,0))*100)/grp.lectureDelivered) and ams.instituteId = $instituteId";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getAttendanceSetId($classId, $subjectId, $evaluationCriteriaId) {
		$query = "
					SELECT
									a.attendanceSetId
					FROM
									subject_to_class a, attendance_set b
					WHERE			a.classId = $classId
					AND			a.subjectId = $subjectId
					AND			a.attendanceSetId = b.attendanceSetId
					AND			b.evaluationCriteriaId = $evaluationCriteriaId
					UNION
					SELECT		a.attendanceSetId
					FROM
									optional_subject_to_class a, attendance_set b
					WHERE			a.classId = $classId
					AND			a.subjectId = $subjectId
					AND			a.attendanceSetId = b.attendanceSetId
					AND			b.evaluationCriteriaId = $evaluationCriteriaId ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentAttendanceSlabsMarks($classId, $subjectId, $attendanceSetId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
					SELECT grp.studentId, grp.lectureDelivered,grp.lectureAttended,ams.marksScored FROM (SELECT a.studentId, FORMAT(SUM(IF(a.isMemberOfClass=0,0, IF(a.attendanceType =2, (b.attendanceCodePercentage /100), a.lectureAttended )) ) , 1 ) AS lectureAttended, (
						SELECT COUNT(dt.dutyLeaveId)
						FROM  ".DUTY_LEAVE_TABLE."  dt WHERE (dt.studentId, dt.classId, dt.subjectId, dt.groupId, dt.dutyDate, dt.periodId)
						IN (SELECT att.studentId, att.classId, att.subjectId, att.groupId, att.fromDate, att.periodId FROM ".ATTENDANCE_TABLE." att, attendance_code ac WHERE att.classId = $classId AND att.subjectId = $subjectId and att.studentId = a.studentId and ac.attendanceCodePercentage = 0)
						and dt.studentId = a.studentId and dt.rejected = ".DUTY_LEAVE_APPROVE."
						and dt.classId = $classId and dt.subjectId = $subjectId
					) as dutyLeaves, SUM(IF(a.isMemberOfClass=0,0, a.lectureDelivered)) AS lectureDelivered FROM ".ATTENDANCE_TABLE." a LEFT JOIN attendance_code b ON (a.attendanceCodeId = b.attendanceCodeId and b.instituteId = $instituteId) WHERE a.classId = $classId AND a.subjectId = $subjectId group by a.studentId) as grp, attendance_marks_slabs ams, student stu  WHERE grp.studentId = stu.studentId AND grp.lectureDelivered = ams.lectureDelivered and (grp.lectureAttended + ifnull(dutyLeaves,0)) = ams.lectureAttended AND ams.attendanceSetId = $attendanceSetId ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getDistinctSlabsRequired($classId, $subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
						select distinct (lectureDelivered) as lectureDelivered from (SELECT a.studentId, SUM(IF(a.isMemberOfClass=0,0, a.lectureDelivered)) AS lectureDelivered FROM student s, ".ATTENDANCE_TABLE." a LEFT JOIN attendance_code b ON (a.attendanceCodeId = b.attendanceCodeId and b.instituteId = $instituteId) WHERE a.classId = $classId AND a.subjectId = $subjectId and a.studentId = s.studentId group by a.studentId) as grp";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

	}

	public function checkSlabsMade($attendanceSetId) {
		$query = "SELECT DISTINCT (lectureDelivered) as lectureDelivered FROM attendance_marks_slabs WHERE attendanceSetId = $attendanceSetId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countUnresolvedMedicalLeave($classId, $subjectId) {
		$query = "SELECT COUNT(medicalLeaveId) as cnt from  ".MEDICAL_LEAVE_TABLE."  where classId = $classId and subjectId = $subjectId AND (approvedStatus = ".MEDICAL_LEAVE_UNRESOLVED." OR approvedStatus IS NULL)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
	public function getUnresolvedMedicalLeave($classId, $subjectId) {
		$query = "SELECT m.medicalLeaveDate, m.studentId, s.rollNo, concat(s.firstName,' ',s.lastName) as studentName, sub.subjectCode, p.periodNumber from  ".MEDICAL_LEAVE_TABLE."  m, student s, subject sub, period p WHERE m.classId = $classId AND m.subjectId = $subjectId and (m.approvedStatus = ".MEDICAL_LEAVE_UNRESOLVED." OR m.approvedStatus IS NULL) and m.studentId = s.studentId and m.subjectId = sub.subjectId and m.periodId = p.periodId";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
	public function countUnresolvedDutyLeave($classId, $subjectId) {
		$query = "SELECT COUNT(dutyLeaveId) as cnt from  ".DUTY_LEAVE_TABLE."  where classId = $classId and subjectId = $subjectId AND (rejected = ".DUTY_LEAVE_UNRESOLVED." OR rejected IS NULL)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getUnresolvedDutyLeave($classId, $subjectId) {
		$query = "SELECT a.dutyDate, a.eventId, a.studentId, b.rollNo, concat(b.firstName,' ',b.lastName) as studentName, c.eventTitle, d.subjectCode, e.periodNumber from  ".DUTY_LEAVE_TABLE."  a, student b, duty_event c, subject d, period e WHERE a.classId = $classId AND a.subjectId = $subjectId and (a.rejected = ".DUTY_LEAVE_UNRESOLVED." OR a.rejected IS NULL) and a.studentId = b.studentId and a.eventId = c.eventId and a.subjectId = d.subjectId and a.periodId = e.periodId";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countConflictedDutyLeave($classId, $subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		
		$query = "
						SELECT
										COUNT(b.dutyLeaveId) AS cnt
						FROM			".ATTENDANCE_TABLE." a,  ".DUTY_LEAVE_TABLE."  b, attendance_code c, student d, duty_event e
						WHERE			a.classId = b.classId
						AND			a.subjectId = b.subjectId
						AND			a.groupId = b.groupId
						AND			a.studentId = b.studentId
						AND			a.studentId = d.studentId
						AND			a.fromDate = b.dutyDate
						AND			a.periodId = b.periodId
						AND			a.attendanceType = 2
						AND			a.attendanceCodeId = c.attendanceCodeId
						AND			c.attendanceCodePercentage = 100
						AND			b.rejected = ".DUTY_LEAVE_APPROVE."
						AND			b.eventId = e.eventId
						AND			b.classId = $classId
						AND			b.subjectId = $subjectId
					    AND			b.instituteId = $instituteId
					    AND			b.sessionId = $sessionId
						";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getConflictedDutyLeave($classId, $subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	    $sessionId = $sessionHandler->getSessionVariable('SessionId');	
		$query = "
						SELECT
										b.dutyDate, b.eventId, a.studentId, d.rollNo, concat(d.firstName,' ',d.lastName) as studentName, e.eventTitle
						FROM			".ATTENDANCE_TABLE." a,  ".DUTY_LEAVE_TABLE."  b, attendance_code c, student d, duty_event e
						WHERE			a.classId = b.classId
						AND			a.subjectId = b.subjectId
						AND			a.groupId = b.groupId
						AND			a.studentId = b.studentId
						AND			a.studentId = d.studentId
						AND			a.fromDate = b.dutyDate
						AND			a.periodId = b.periodId
						AND			a.attendanceType = 2
						AND			a.attendanceCodeId = c.attendanceCodeId
						AND			c.attendanceCodePercentage = 100
						AND			b.rejected = ".DUTY_LEAVE_APPROVE."
						AND			b.eventId = e.eventId
						AND			b.classId = $classId
						AND			b.subjectId = $subjectId
						AND			b.instituteId = $instituteId
                        AND			b.sessionId = $sessionId
						";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	public function countConflictedMedicalLeave($classId, $subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');	
		
		$query = "
						SELECT
										COUNT(b.medicalLeaveId) AS cnt
						FROM			".ATTENDANCE_TABLE." a,  ".MEDICAL_LEAVE_TABLE."  b, attendance_code c, student d
						WHERE			a.classId = b.classId
						AND			a.subjectId = b.subjectId
						AND			a.groupId = b.groupId
						AND			a.studentId = b.studentId
						AND			a.studentId = d.studentId
						AND			a.fromDate = b.medicalLeaveDate
						AND			a.periodId = b.periodId
						AND			a.attendanceType = 2
						AND			a.attendanceCodeId = c.attendanceCodeId
						AND			c.attendanceCodePercentage = 100
						AND			b.approvedStatus = ".MEDICAL_LEAVE_APPROVE."
						AND			b.classId = $classId
						AND			b.subjectId = $subjectId
						AND			b.instituteId = $instituteId
						AND			b.sessionId = $sessionId
						";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getConflictedMedicalLeave($classId, $subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');	
		
		$query = "
						SELECT
										b.medicalLeaveDate, a.studentId, d.rollNo, concat(d.firstName,' ',d.lastName) as studentName
						FROM			".ATTENDANCE_TABLE." a,  ".MEDICAL_LEAVE_TABLE."  b, attendance_code c, student d
						WHERE			a.classId = b.classId

						AND			a.subjectId = b.subjectId
						AND			a.groupId = b.groupId
						AND			a.studentId = b.studentId
						AND			a.studentId = d.studentId
						AND			a.fromDate = b.medicalLeaveDate
						AND			a.periodId = b.periodId
						AND			a.attendanceType = 2
						AND			a.attendanceCodeId = c.attendanceCodeId
						AND			c.attendanceCodePercentage = 100
						AND			b.approvedStatus = ".MEDICAL_LEAVE_APPROVE."
						AND			b.classId = $classId
						AND			b.subjectId = $subjectId
						AND			b.instituteId = $instituteId
						AND			b.sessionId = $sessionId
						";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
	public function countConflictedDutyMedicalLeave($classId, $subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');	
		
		$query = "
						SELECT
										COUNT(ml.medicalLeaveId) AS cnt
						FROM			 ".DUTY_LEAVE_TABLE."  dl,  ".MEDICAL_LEAVE_TABLE."  ml
						WHERE		dl.dutyDate = ml.medicalLeaveDate	
						AND			dl.studentId=ml.studentId
						AND			dl.classId=ml.classId
						AND			dl.subjectId=ml.subjectId
						AND			dl.groupId=ml.groupId
						AND			dl.periodId=ml.periodId
						AND			dl.classId = $classId
						AND			dl.subjectId = $subjectId
						AND			dl.instituteId = $instituteId
						AND			dl.sessionId = $sessionId
						AND			dl.rejected = ".DUTY_LEAVE_APPROVE."
						AND			ml.approvedStatus = ".MEDICAL_LEAVE_APPROVE."
						";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
	public function getConflictedDutyMedicalLeave($classId, $subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');	
		
		$query = "
						SELECT
										ml.medicalLeaveDate, stu.rollNo, concat(stu.firstName,' ',stu.lastName) as studentName
						FROM			 ".DUTY_LEAVE_TABLE."  dl,  ".MEDICAL_LEAVE_TABLE."  ml LEFT JOIN student stu ON stu.studentId=ml.studentId
						WHERE			dl.dutyDate = ml.medicalLeaveDate	
						AND			dl.studentId=ml.studentId
						AND			dl.classId=ml.classId
						AND			dl.subjectId=ml.subjectId
						AND			dl.groupId=ml.groupId
						AND			dl.periodId=ml.periodId
						AND			dl.classId = $classId
						AND			dl.subjectId = $subjectId
						AND			dl.instituteId = $instituteId
						AND			dl.sessionId = $sessionId
						AND			dl.rejected = ".DUTY_LEAVE_APPROVE."
						AND			ml.approvedStatus = ".MEDICAL_LEAVE_APPROVE."
						";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function checkAttendanceSetClassSubjects($attendanceSetSlab) {
		$query = "
						SELECT
										classId,
										subjectId
						FROM			subject_to_class
						WHERE			attendanceSetId = $attendanceSetSlab
						UNION
						SELECT
										classId,
										subjectId
						FROM			optional_subject_to_class
						WHERE			attendanceSetId = $attendanceSetSlab ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function checkAttendanceSetPercentMaxMarks($attendanceSetPercentToApply) {
		$query = "SELECT MAX(marksScored) AS marksScored FROM attendance_marks_percent WHERE attendanceSetId = $attendanceSetPercentToApply";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function checkAttendanceSetSlabMaxMarks($attendanceSetSlabToApply) {
		$query = "SELECT MAX(marksScored) AS marksScored FROM attendance_marks_slabs WHERE attendanceSetId = $attendanceSetSlabToApply";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getAttendanceTypeMarks($classId, $subjectId, $evaluationCriteriaId) {
		$query = "SELECT weightageAmount FROM test_type WHERE classId = $classId and subjectId = $subjectId and evaluationCriteriaId = $evaluationCriteriaId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function deleteTotalTransferredMarks($classId, $subjectId) {
		$query = "DELETE FROM ".TOTAL_TRANSFERRED_MARKS_TABLE." WHERE classId = $classId AND subjectId = $subjectId  AND conductingAuthority IN (1,3)";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function deleteTestTransferredMarks($classId, $subjectId) {
		$query = "DELETE FROM ".TEST_TRANSFERRED_MARKS_TABLE." WHERE classId = $classId  AND subjectId = $subjectId AND testTypeId IN (SELECT testTypeId FROM test_type WHERE conductingAuthority IN (1,3))";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function deleteTotalTransferredMarksClass($classId) {
		$query = "DELETE FROM ".TOTAL_TRANSFERRED_MARKS_TABLE." WHERE classId = $classId AND conductingAuthority IN (1,3)";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function deleteTestTransferredMarksClass($classId) {
		$query = "DELETE FROM ".TEST_TRANSFERRED_MARKS_TABLE." WHERE classId = $classId AND testTypeId IN (SELECT testTypeId FROM test_type WHERE conductingAuthority IN (1,3))";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function addTotalMarksInTransaction($queryPart2) {
		$query = "
					INSERT INTO ".TEST_TRANSFERRED_MARKS_TABLE."
								(studentId, testTypeId, classId, subjectId, maxMarks, marksScored) VALUES $queryPart2
				";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function getTransferredSubjects($classId, $transferSubjects) {
		$query = "SELECT DISTINCT a.subjectId, b.subjectCode from ".TOTAL_TRANSFERRED_MARKS_TABLE." a, subject b where a.classId = $classId and a.subjectId = b.subjectId AND a.subjectId IN ($transferSubjects)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentMarksSum($classId, $subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
				select
							a.studentId,
							a.classId,
							a.subjectId,
							b.conductingAuthority,
							sum(a.maxMarks) as maxMarks, round(sum(a.marksScored),3) as marksScored
				from		".TEST_TRANSFERRED_MARKS_TABLE." a, test_type b, class c
				where		a.testTypeId = b.testTypeId
				and			a.classId = c.classId
				and			a.classId = $classId
				and			a.subjectId IN ($subjectId)
				and			b.instituteId = $instituteId
				and			b.conductingAuthority IN  (1,3)
				group by	a.studentId, a.classId, a.subjectId, b.conductingAuthority";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countMMSubjects($classId) {
		$query = "SELECT COUNT(subjectId) as cnt FROM subject_to_class where classId = $classId and optional = 1 and hasParentCategory = 1";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getClassSubjectCount($classId) {
		$query = "SELECT COUNT(subjectId) as cnt FROM subject_to_class where classId = $classId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function addGradingRecordInTransaction($queryPart2) {
		$query = "
					INSERT INTO ".TOTAL_TRANSFERRED_MARKS_TABLE."
								(studentId, classId, subjectId, maxMarks, marksScored, holdResult, conductingAuthority, marksScoredStatus) VALUES $queryPart2
				";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function updateRecordInTransaction($table, $setCondition, $whereCondition) {
		$query = "UPDATE $table SET $setCondition WHERE $whereCondition";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


	public function getTable($getMissingStudentArray) {
		$str = '<table border="1" style="border-collapse:collapse" width="96%" align="center">';
		$str .= '<tr><th>Sr. No</th><th>Roll No</th><th>Student Name</th></tr>';
		$i = 0;

		foreach($getMissingStudentArray as $record) {
			$str .= '<tr><td>'.++$i.'<td>'.$record['rollNo'].'</td><td>'.$record['studentName'].'</td></tr>';
		}
		$str .= '</table>';
		return $str;
	}

	public function getDutyLeaveTable($dutyLeaveUnresolvedArray) {
		require_once(BL_PATH . '/UtilityManager.inc.php');
		$str = '<table border="1" width="96%" rules="all" align="center">';

		$str .= '<tr><th>Sr. No</th><th>Date</th><th>Roll No</th><th>Student Name</th><th>Event</th></tr>';

		$i = 0;

		foreach($dutyLeaveUnresolvedArray as $record) {
			$str .= '<tr><td>' . ++$i . '</td><td>' . UtilityManager::formatDate($record['dutyDate']) . '</td><td>' . $record['rollNo'] . '</td><td>' . $record['studentName'] . '</td><td>' . $record['eventTitle'] . '</td></tr>';
		}

		$str .= '</table>';
		return $str;
	}
	
	public function getMedicalLeaveTable($medicalLeaveUnresolvedArray) {
		require_once(BL_PATH . '/UtilityManager.inc.php');
		$str = '<table border="1" width="96%" rules="all" align="center">';

		$str .= '<tr><th>Sr. No</th><th>Date</th><th>Roll No</th><th>Student Name</th></tr>';

		$i = 0;

		foreach($medicalLeaveUnresolvedArray as $record) {
			$str .= '<tr><td>' . ++$i . '</td><td>' . UtilityManager::formatDate($record['medicalLeaveDate']) . '</td><td>' . $record['rollNo'] . '</td><td>' . $record['studentName'] . '</td></tr>';
		}

		$str .= '</table>';
		return $str;
	}

	public function getSingleField($table, $field, $conditions='') {
		$query = "SELECT $field FROM $table $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getInconsistencyTable($inconsistenciesArray, $className, $errorTypeDescArray, $subjectCode) {
		global $allReturnStr, $errorFound;
		if (count($inconsistenciesArray) > 0) {
			$returnStr = '';
			if ($errorFound == false) {
				$fp = fopen(TEMPLATES_PATH . '/Xml/marksTransferIssues.doc','wb');
				$returnStr .= "<table width='100%' border='0' cellspacing='5' cellpadding='0' class='contenttab_border2'><tr><td valign='top' colspan='1' class='contenttab_row1'><table border='0' cellspacing='10' cellpadding='0' width='100%'><tr><td><u><b>Marks have not been transferred because of following issues:</b></u></td></tr>";
			}
			else {
				$fp = fopen(TEMPLATES_PATH . '/Xml/marksTransferIssues.doc','ab');
				$returnStr .= "<table width='100%' border='0' cellspacing='5' cellpadding='0' class='contenttab_border2'><tr><td valign='top' colspan='1' class='contenttab_row1'><table border='0' cellspacing='10' cellpadding='0' width='100%'>";
			}
			$errorFound = true;
			foreach($inconsistenciesArray as $className => $errorArray) {
				$returnStr .= "<tr><td valign='top' colspan='1' class='marksTransferFailure'>&nbsp;&nbsp;<u><b>Following Issues were found during transferring marks for <b>$subjectCode</b> : </b></u>";
				foreach($errorArray as $errorType => $errorTypeArray) {
					$returnStr .= "<br><br>&nbsp;&nbsp;<b><u>".$errorTypeDescArray[$errorType].":</u></b>";
					$i = 0;
					foreach($errorTypeArray as $inconsistency) {
						$i++;
						$returnStr .= "<br>&nbsp; &bull;&nbsp;&nbsp;".$inconsistency;
					}
				}
				$returnStr .= "<br></td></tr>";
			}
			$returnStr .= "	</table></td></tr></table>";
			$allReturnStr .= $returnStr;

			if ($fp) {
				fwrite($fp, $returnStr);
			}
			fclose($fp);
		}
	}
}

//$History: TransferMarksManager.inc.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 1/14/10    Time: 4:50p
//Updated in $/LeapCC/Model
//applied missing check of lectures attended can not be lesser than prev.
//value of lectures attended
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 1/11/10    Time: 3:08p
//Updated in $/LeapCC/Model
//checked queries related to marks transfer de-coupling with promotion
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/28/09   Time: 4:44p
//Created in $/LeapCC/Model
//initial checkin for advanced marks transfer






?>
