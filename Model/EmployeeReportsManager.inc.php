<?php
//-------------------------------------------------------------------------------
//
//EmployeeReportsManager .
// Author : Jaineesh
// Created on : 07.07.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class EmployeeReportsManager {
	private static $instance = null;

	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

//-------------------------------------------------------------------------------
//
//addHostel() is used to add new record in database.
// Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getSingleEmployeeList($conditions='') {
		global $sessionHandler;                            
        //        AND c.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."'
        $query = "SELECT e.EmployeeCode, e.employeeName,e.isTeaching,e.designationId,e.gender,e.branchId,
        e.instituteId,e.qualification,e.isMarried,e.fatherName,e.motherName,e.contactNumber,e.mobileNumber,
        if(upper(c.cityName)=upper(s.stateName),(concat(e.address1,' ',c.cityName,' ',cn.countryName)), (concat(e.address1,' ',c.cityName,' ',s.stateName,' ',cn.countryName))) AS `Address`, 
        dateOfBirth, dateOfMarriage,
        dateOfJoining, dateOfLeaving, e.designationId
        FROM Employee e,  branch b, institute i, designation d, city c, countries cn, states s  
        WHERE  e.branchId = b.branchId AND e.cityId = c.cityId AND e.countryId = cn.countryId AND e.stateId = s.stateId and e.designationId=d.designationId and e.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getAllEmployeeList($conditions='') {
		global $sessionHandler;
            $query = "SELECT e.EmployeeCode, e.employeeName,e.isTeaching,e.designationId,e.gender,e.branchId,
            e.instituteId,e.qualification,e.isMarried,e.fatherName,e.motherName,e.contactNumber,e.mobileNumber,
            if(upper(c.cityName)=upper(s.stateName),(concat(e.address1,' ',c.cityName,' ',cn.countryName)), (concat(e.address1,' ',c.cityName,' ',s.stateName,' ',cn.countryName))) AS `Address`, 
            dateOfBirth, dateOfMarriage,
            dateOfJoining, dateOfLeaving, e.designationId
            FROM Employee e,  branch b, institute i, designation d, city c, countries cn, states s  
            WHERE  e.branchId = b.branchId AND e.cityId = c.cityId AND e.countryId = cn.countryId AND e.stateId = s.stateId and e.designationId=d.designationId and e.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
            $conditions ";
        //$query = "SELECT s.EmployeeId, s.rollNo,s.firstName,s.lastName,s.EmployeeMobileNo,s.fatherName,s.dateOfLeaving,s.corrAddress1,s.corrAddress2,s.permAddress1,s.permAddress2,s.regNo,s.universityRollNo,s.universityRegNo,s.dateOfBirth,s.EmployeePhoto, c.classId, d.degreeName, p.periodicityName, sp.periodName
        //FROM Employee s, class c, branch b, periodicity p, study_period sp, degree d
        //WHERE s.classId = c.classId AND c.degreeId = d.degreeId AND c.studyPeriodId=sp.studyPeriodId AND sp.periodicityId=p.periodicityId AND c.branchId=b.branchId AND c.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
		//AND c.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."' $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------------------------------------
//// Function gets classId from the using sessionId,instituteId,degreeId,universityId,batchId,branchId for Sc modules
//
// Author :Arvind Singh Rawat
// Created on : 18-sept-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------------------------------

	public function getScClassId($classFilter)
	{
		global $REQUEST_DATA;
		global $sessionHandler;
		$query="SELECT classId FROM class $classFilter AND sessionId='".$sessionHandler->getSessionVariable('SessionId')."' AND instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
//----------------------------------------------------------------------------------------------------
//funciton return the array of EmployeeListReport based on th selected fields for Sc Modules

// Author :Arvind Singh Rawat
// Created on : 18-Sept-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------

	public function getScEmployeeListReportList($filter,$conditions)
	{   global $sessionHandler;     
		$query="SELECT $filter  $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


//----------------------------------------------------------------------------------------------------
//// Function gets classId from the using sessionId,instituteId,degreeId,universityId,batchId,branchId
//
// Author :Arvind Singh Rawat
// Created on : 08-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------



	public function getClassId($classFilter)
	{
		global $REQUEST_DATA;
		global $sessionHandler;
		$query="SELECT classId FROM class $classFilter AND sessionId='".$sessionHandler->getSessionVariable('SessionId')."' AND instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//----------------------------------------------------------------------------------------------------
//funciton return the array of EmployeeListReport based on th selected fields

// Author :Arvind Singh Rawat
// Created on : 08-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------

	public function getEmployeeListReportList($filter)
	{
		$query="SELECT $filter ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//----------------------------------------------------------------------------------------------------
//funciton return the array of Subjects based on the class

// Author :Ajinder Singh
// Created on : 16-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getSubjectList($classesList) {
		$query = "SELECT a.subjectId, a.subjectName FROM subject a, subject_to_class b WHERE a.subjectId = b.subjectId AND b.classId IN ($classesList)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


//----------------------------------------------------------------------------------------------------
//general function created for one or more fields from one or more table with option of conditions

// Author :Ajinder Singh
// Created on : 29-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getSingleField($table, $field, $conditions='') {
		$query = "SELECT $field FROM $table $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//----------------------------------------------------------------------------------------------------
//function created for fetching all subjects of one type

// Author :Ajinder Singh
// Created on : 09-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getSubjectListByType($degree, $subjectTypeId) {
		$query = "SELECT a.subjectId, a.subjectName FROM subject a, subject_to_class b WHERE a.subjectId = b.subjectId AND b.classId = $degree AND a.subjectTypeId = $subjectTypeId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

// Author :Ajinder Singh
// Created on : 09-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function countClassSubjectEmployees($classId, $subjectId) {
		$query = "SELECT
							COUNT(DISTINCT(CONCAT(EmployeeId,classId,subjectId))) AS cnt
				  FROM		".TEST_TRANSFERRED_MARKS_TABLE."
				  WHERE		classId = $classId AND
							subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}



	//----------------------------------------------------------------------------------------------------
	//function created for fetching rangewise classwise subjectwise marks.

	// Author :Ajinder Singh
	// Created on : 22-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	/*	Please note that same $conditions part is made in
		1. Library/EmployeeReports/initSubjectwiseConsolidatedReport.php
		2. Templates/listSubjectWiseConsolidatedGraphPrint.php
		3. Templates/listSubjectWiseConsolidatedReportPrint.php
	*/
	public function getAllSubjectConsolidatedMarks($conditions) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
		$query = "SELECT
							a.rangeLabel
							$conditions
				  FROM		range_levels a";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching data for Employee label reports

	// Author :Ajinder Singh
	// Created on : 22-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getEmployeeLabelData($universityId,$branchId,$instituteId) {
		global $REQUEST_DATA;
		$query = "	SELECT
								e.employeeCode,
								e.employeeName,
                                e.gender,
								e.Address1,
								e.Address2,
								e.contactNumber,
								s.mobileNumber,
								s.fatherName,
								st.stateCode,
								ct.cityName
					FROM		Employee e
					LEFT JOIN	states st ON ( e.stateId = st.stateId )
					LEFT JOIN	city ct ON ( e.cityId = ct.cityId )
					WHERE		e.universityId = '".$universityId."' AND
								e.branchId='".$branchId."' AND
								e.instituteId='".$instituteId."' 
					ORDER BY	e.employeeName ASC";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching data of employees who teach to a particular group

	// Author :Ajinder Singh
	// Created on : 27-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getEmployeeList($employeeCode, $conditions = '') {
			global $sessionHandler;
			$query = "
					SELECT
							DISTINCT(a.employeeCode) AS employeeCode,
							b.employeeName
					FROM	employee e
							$conditions
					ORDER BY e.employeeName
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");

	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching data of groups which are taught by a particular employee

	// Author :Ajinder Singh
	// Created on : 27-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getEmployeeGroupList($classId, $employeeId, $conditions='') {
			global $sessionHandler;
			$query = "
					SELECT
							DISTINCT(a.groupId) AS groupId,
							b.groupShort
					FROM	test a, `group` b
					WHERE	a.groupId = b.groupId 
					AND		a.classId = $classId 
					AND		a.employeeId = $employeeId 
							$conditions 
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");

	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching data of tests of a particular class and particular subject

	// Author :Ajinder Singh
	// Created on : 27-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getTestList($classId, $conditions = '') {
			$query = "
					SELECT
								testId,
								CONCAT(testAbbr,'-', testIndex) as testName
					FROM		test 
					WHERE		classId = $classId 
								$conditions
					ORDER BY	testName";

		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching data of total Employees of a class for a particular test

	// Author :Ajinder Singh
	// Created on : 27-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getTestTotalEmployees($testId, $conditions='') {
		$query = "SELECT
							count(a.EmployeeId) as cnt
				 FROM		sc_test_marks a, sc_Employee b
				 WHERE		a.testId = $testId AND
							a.isMemberOfClass = 1 AND
							a.EmployeeId = b.EmployeeId 
							$conditions
			";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching data of present Employees of a class for a particular test

	// Author :Ajinder Singh
	// Created on : 27-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getTestPresentEmployees($testId, $conditions = '') {
		$query = "SELECT
							COUNT(a.EmployeeId) as cnt
				  FROM		sc_test_marks a, sc_Employee b
				  WHERE		a.testId = $testId AND
							a.isMemberOfClass = 1 AND
							a.isPresent = 1 AND
							a.EmployeeId = b.EmployeeId 
							$conditions
			";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching  range wise marks

	// Author :Ajinder Singh
	// Created on : 27-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getClassWiseConsolidatedMarks($classId, $testId) {
		$query = "SELECT
						a.rangeLabel, (
										SELECT
												COUNT( * ) FROM sc_Employee
										WHERE	classId IN ($classId)
										AND		EmployeeId IN (
																SELECT		EmployeeId
																FROM		sc_test_marks 
																WHERE		testId = $testId AND
																			classId IN ($classId) AND
																			isMemberOfClass = 1 AND
																			isPresent = 1
																GROUP BY	EmployeeId
																HAVING (
																			SUM( marksScored ) / SUM( maxMarks ) *100 BETWEEN a.rangeFrom AND a.rangeTo
																		)
																)
									 ) AS cnt
					FROM range_levels a ";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}


	public function getClassWiseConsolidatedMarksSep($testId, $queryPart='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		 $query = "SELECT
						a.rangeLabel
						$queryPart
					FROM range_levels a where a.instituteId = $instituteId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}


	//----------------------------------------------------------------------------------------------------
	//function created for fetching  range wise marks

	// Author :Ajinder Singh
	// Created on : 27-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getClassWiseConsolidatedMarksCon($testId) {
		echo $query = "SELECT
						a.rangeLabel, (
										SELECT
												COUNT( * ) FROM sc_Employee
										WHERE	EmployeeId IN (
																SELECT		c.EmployeeId
																FROM		sc_test_marks c, sc_Employee d
																WHERE		c.testId = $testId AND
																			c.EmployeeId = d.EmployeeId AND
																			d.classId = c.classId AND
																			c.isMemberOfClass = 1 AND
																			c.isPresent = 1
																GROUP BY	EmployeeId
																HAVING (
																			SUM( marksScored ) / SUM( maxMarks ) *100 BETWEEN a.rangeFrom AND a.rangeTo
																		)
																)
									 ) AS cnt
					FROM range_levels a ";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}
	
	
	
	//----------------------------------------------------------------------------------------------------
	//function created for fetching  Employee and class details on base of roll number

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------	
	public function getEmployeeIdClass($rollNo) {
		$query = "SELECT
							a.classId,
							a.EmployeeId,
							a.firstName,
							a.lastName,
							b.className
				  FROM		Employee a, class b
				  WHERE		a.rollNo = '".$rollNo."' AND
							a.classId = b.classId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for subject types for which subjects have been used in tests in test transferred marks table

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------	
	public function getTestSubjectTypes($classId) {
		$query = "SELECT
							DISTINCT(b.subjectTypeId) AS subjectTypeId,
							c.subjectTypeName
				  FROM      ".TEST_TRANSFERRED_MARKS_TABLE." a, subject  b, subject_type c
				  WHERE     a.classId = $classId AND
							a.subjectId = b.subjectId AND
							b.subjectTypeId = c.subjectTypeId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for subject types for which subjects have been used in tests in test transferred marks table

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------	
	public function getTestTypes($classId, $subjectList) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT
							DISTINCT(a.testTypeId) AS testTypeId,
							c.testTypeName,
							c.evaluationCriteriaId
				  FROM      ".TEST_TRANSFERRED_MARKS_TABLE." a, subject b, test_type c
				  WHERE     a.subjectId = b.subjectId AND
							a.subjectId IN ($subjectList) AND
							a.classId = $classId AND
							c.instituteId = $instituteId and 
							a.testTypeId = c.testTypeId
				  ORDER BY  testTypeId, b.subjectName";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for subjects under subject type for which marks have been 
	//transferred and subject belong to specified class

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------	
	public function getSubjectTypeSubjects($subjectTypeId, $classId) {
		$query = "SELECT
							DISTINCT(a.subjectId) AS subjectId,
							b.subjectCode
				  FROM		".TEST_TRANSFERRED_MARKS_TABLE." a, subject b
				  WHERE		a.classId = $classId AND
							a.subjectId = b.subjectId AND
							b.subjectTypeId = $subjectTypeId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching attendance marks for a Employee and for a subject and for that particular class

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------	
	public function getAttendanceMarks($classId, $EmployeeId, $subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "	SELECT
							FORMAT(SUM(IF(a.isMemberOfClass=0,0, IF(a.attendanceType =2, (b.attendanceCodePercentage /100), a.lectureAttended )) ) , 1 ) AS lectureAttended,
							SUM(IF(a.isMemberOfClass=0,0, a.lectureDelivered)) AS lectureDelivered
					FROM	attendance a, attendance_code b
					WHERE	a.classId = $classId AND
							a.EmployeeId = $EmployeeId AND
							a.subjectId = $subjectId AND
							b.instituteId = $instituteId AND
							a.attendanceCodeId = b.attendanceCodeId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching marks for a Employee and for a subject and for that particular class and that particualr test

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------	
	public function getMarks($classId, $EmployeeId, $subjectId, $testId) {
		$query = "SELECT
							a.marksScored
				 FROM		test_marks a, test b
				 WHERE		a.subjectId = $subjectId AND
							a.EmployeeId = $EmployeeId AND
							b.classId = $classId AND
							b.testId = $testId AND
							a.subjectId = b.subjectId AND
							a.testId = b.testId";

		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching marks transferred for a Employee and for a subject and for that particular class and that particualr test type

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------	
	public function getTotalMarks($classId, $subjectId, $EmployeeId, $testTypeId) {
		$query = "SELECT
							marksScored
				  FROM		".TEST_TRANSFERRED_MARKS_TABLE."
				  WHERE		classId = $classId AND
							subjectId =  $subjectId AND
							EmployeeId = $EmployeeId AND
							testTypeId = $testTypeId";

		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching tests for specific test types and specific subjects for that particular class and particular Employee

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------	
	public function getTests($testTypeList = 0, $subjectList = 0, $classId, $EmployeeId) {
		$query = "SELECT
								DISTINCT (a.testId) as testId,
								CONCAT(a.testAbbr,'-',testIndex) AS testName
				  FROM			test a 
				  LEFT JOIN		test_marks b 
				  ON			(
									a.testId = b.testId AND 
									a.subjectId = b.subjectId
								)
				  WHERE			a.classId = $classId AND
								a.testTypeId in ($testTypeList) AND
								a.subjectId in ($subjectList) ";

		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for counting tests for a particular test type and for a list of subjects

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------	
	public function countTestTypeSubjectListTest($testTypeId, $subjectList, $classId) {
		$query = "SELECT
							COUNT(*) AS cnt
				  FROM		test
				  WHERE		classId = $classId AND
							subjectId IN($subjectList) AND
							testTypeId = $testTypeId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching different classes from test

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------	
	public function getTestClasses() {
		$query = "SELECT 
							DISTINCT(a.classId) AS classId 
				  FROM		test a, class b  
				  WHERE		a.classId = b.classId 
				  AND		b.isActive = 1";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching tests taken for a particular class, subject and group

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------	
	public function getClassSubjectGroupTests($classId, $subjectId, $groupId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT 
							a.testId,
							CONCAT(b.testTypeName,'-',a.testIndex) AS testName,
							SUBSTRING_INDEX(c.className,'-',-3) AS className, 
							d.subjectCode,
							e.employeeCode,
							e.employeeName
				  FROM		test a, test_type b, class c, subject d, employee e
				  WHERE		a.classId = $classId AND 
							a.subjectId = $subjectId AND 
							a.groupId = $groupId AND 
							a.testTypeId = b.testTypeId AND
							b.instituteId = $instituteId AND
							a.classId = c.classId AND
							a.employeeId = e.employeeId AND
							a.subjectId = d.subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching subjects which are taught to a particular class

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------	
	public function getClassTeachingSubjectList($classId) {
		global $sessionHandler;
		$query = "SELECT 
							DISTINCT(a.subjectId) AS subjectId, 
							a.subjectName 
				  FROM		subject a, subject_to_class b
				  WHERE		a.subjectId = b.subjectId 
				  AND		b.classId = $classId";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching Employees matching conditions

	// Author :Ajinder Singh
	// Created on : 13-Sep-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------	
	public function getAllDetails($conditions='', $order, $limit) {
		global $sessionHandler;
		$query = "SELECT 
							CONCAT(a.firstName,' ', a.lastName) as EmployeeName, 
							a.rollNo, 
							CONCAT(c.universityCode,'-',d.degreeCode,'-',e.branchCode) as programme, 
							f.periodName 
				  FROM		sc_Employee a, class b, university c, degree d,  branch e, study_period f 
				  WHERE		a.classId = b.classId 
				  AND		b.universityId = c.universityId 
				  AND		b.degreeId = d.degreeId
				  AND		b.branchId = e.branchId 
				  AND		b.studyPeriodId = f.studyPeriodId
				  AND		b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
				  AND		b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."' 
							$conditions
				  ORDER BY	$order $limit";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

    //----------------------------------------------------------------------------------------------------
    //function created for fetching Employees matching conditions

    // Author :Arvind Singh Rawat
    // Created on : 23-Sep-2008
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------    
    public function getAllDetailsEmployeeList($conditions='', $order, $limit) {
        global $sessionHandler;
        $query = "SELECT 
                            CONCAT(a.firstName,' ', a.lastName) as EmployeeName, 
                            a.rollNo, 
                            CONCAT(c.universityCode,'-',d.degreeCode,'-',e.branchCode) as programme, 
                            f.periodName 
                  FROM        sc_Employee a, class b, university c, degree d,  branch e, study_period f 
                  WHERE        a.classId = b.classId 
                  AND        b.universityId = c.universityId 
                  AND        b.degreeId = d.degreeId
                  AND        b.branchId = e.branchId 
                  AND        b.studyPeriodId = f.studyPeriodId
                  AND        b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
                  AND        b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."' 
                            $conditions
                  ORDER BY    $order $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
	//----------------------------------------------------------------------------------------------------
	//function created for counting Employees matching conditions

	// Author :Ajinder Singh
	// Created on : 13-Sep-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------	
	public function countRecords($conditions='') {
		global $sessionHandler;
		$query = "SELECT 
							COUNT(*) AS cnt  
				  FROM		employee a, university c, branch e
				  WHERE		a.universityId = c.universityId 
				  AND		a.branchId = e.branchId 
				  AND		a.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
							$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getSubjectSections($subjectId) {
		$query = "SELECT 
							DISTINCT(a.sectionId),
							CONCAT(a.abbr,' ', a.sectionType) AS sectionName  
				  FROM		sc_section a, sc_Employee_section_subject b
				  WHERE		b.subjectId = $subjectId
				  AND		a.sectionId = b.sectionId
				  ORDER BY	sectionType,abbr";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	public function getSubjectClasses($subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
	
		$query = "
					SELECT 
								DISTINCT(a.classId), substring_index( substring_index( a.className, '-', 5 ) , '-', -4 ) AS className FROM class a, subject_to_class b 
					WHERE		a.classId		= b.classId
					AND			a.instituteId	= '$instituteId' 
					AND			a.sessionId		= '$sessionId'
					AND			b.subjectId		= '$subjectId'
					AND			a.isActive		= 1
				 ";

		return SystemDatabaseManager::getInstance()->executeQuery($query);

	}

	public function getSubjectAttendanceClasses($subjectId, $conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
	
		 $query = "
					SELECT 
								DISTINCT(a.classId), substring_index( substring_index( b.className, '-', 5 ) , '-', -4 ) AS className FROM sc_attendance a, class b
					WHERE		a.classId		= b.classId
					AND			b.instituteId	= '$instituteId' 
					AND			b.sessionId		= '$sessionId'
					AND			a.subjectId		= '$subjectId'
					$conditions
					AND			b.isActive		= 1
				 ";

		return SystemDatabaseManager::getInstance()->executeQuery($query);

	}

	public function getSubjectSectionTests($subjectId, $sectionId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

		$query = "
					SELECT 
								CONCAT(a.testTypeName,'-',b.testIndex) AS testName
					FROM		test_type a, sc_test b 
					WHERE		a.testTypeId = b.testTypeId 
					AND			b.subjectId = $subjectId 
					AND			b.sectionId = $sectionId 
					AND			b.instituteId = $instituteId 
					AND			a.instituteId = $instituteId 
					AND			b.sessionId = $sessionId";

		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	public function getSubjectSectionTestTypes($subjectId, $sectionId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$query = "	
					SELECT	
								distinct(a.testTypeId) AS testTypeId, 
								b.testTypeCode,
								b.testTypeName
					FROM		sc_test a, test_type b 
					WHERE		a.subjectId = $subjectId 
					AND			a.sectionId = $sectionId 
					AND			a.instituteId = $instituteId 
					AND			b.instituteId = $instituteId 
					AND			a.sessionId = $sessionId 
					AND			a.testTypeId = b.testTypeId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	public function getSubjectSectionTestClasses($subjectId, $testId) {
		$query = "
					SELECT 
								DISTINCT(a.classId) AS classId, 
								SUBSTRING_INDEX(SUBSTRING_INDEX(b.className, '-', 5) , '-', -4) AS className 
					FROM		sc_test_marks a, class b 
					WHERE		a.classId = b.classId 
					AND			a.subjectId = $subjectId
					AND			a.testId = $testId
					AND			b.isActive=1";

		return SystemDatabaseManager::getInstance()->executeQuery($query);

	}

	public function getSubjectSectionTestNames($subjectId, $sectionId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		 $query = "
					SELECT 
								testId, 
								CONCAT(testAbbr,'-',testIndex) AS testName
					FROM		sc_test  
					WHERE		subjectId = $subjectId
					AND			sectionId = $sectionId
					AND			instituteId = $instituteId 
					AND			sessionId = $sessionId 
					";

		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	public function getSubjectTestSections($subjectId) {
		$query = "SELECT 
							DISTINCT(a.sectionId),
							CONCAT(a.abbr,' ', a.sectionType) AS sectionName  
				  FROM		sc_section a, sc_test b
				  WHERE		b.subjectId = $subjectId
				  AND		a.sectionId = b.sectionId
				  ORDER BY	sectionType,abbr";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}


    //----------------------------------------------------------------------------------------------------
    //function created for fetching Employees topic Count
    // Author :Parveen Sharma
    // Created on : 02-06-09
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------    
    
    public function getCountSubjectTopic($conditions='') {
        
        $query = "SELECT 
                        COUNT(*)
                  FROM 
                       (SELECT 
                                    cls.className, 
                                    att.topicsTaughtId,
                                    sub.subjectName,
                                    sub.subjectCode, 
                                    gr.groupName,  
                                    emp.employeeName,att.fromDate,att.toDate,
                                    (SELECT 
                                          GROUP_CONCAT(DISTINCT topic SEPARATOR ' ---' )
                                     FROM 
                                          subject_topic sst, topics_taught ttt
                                     WHERE 
                                          ttt.topicsTaughtId = att.topicsTaughtId  AND 
                                          sst.subjectId = st.subjectId AND
                                          INSTR(ttt.subjectTopicId, CONCAT('~',sst.subjectTopicId,'~'))>0 
                                     ) AS topic
                        FROM 
                                    ".ATTENDANCE_TABLE." att, topics_taught tt, subject_topic st,
                                    `subject` sub,`class` cls,`group` gr,`employee` emp
                        WHERE
                                    st.subjectId = sub.subjectId AND
                                    att.subjectId = st.subjectId AND        
                                    att.classId = cls.classId AND 
                                    att.groupId = gr.groupId AND 
                                    att.employeeId = emp.employeeId AND 
                                    att.topicsTaughtId = tt.topicsTaughtId AND
                                    INSTR(tt.subjectTopicId, CONCAT('~',st.subjectTopicId,'~'))>0  
                        $conditions          
                        GROUP BY
                                    att.fromDate, att.topicsTaughtId) AS t ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    //----------------------------------------------------------------------------------------------------
    //function created for fetching Employees topic Count
    //
    // Author :Parveen Sharma
    // Created on : 02-06-09
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------    
    
    public function getAllSubjectTopic($conditions='',$order='', $limit='') {
        
         $query ="SELECT 
                        cls.className, 
                        att.topicsTaughtId,
                        sub.subjectName,
                        sub.subjectCode, 
                        gr.groupName,  
                        emp.employeeName,att.fromDate,att.toDate,
                        (SELECT 
                              GROUP_CONCAT(DISTINCT topic SEPARATOR ' ---' )
                         FROM 
                              subject_topic sst, topics_taught ttt
                         WHERE 
                              ttt.topicsTaughtId = att.topicsTaughtId  AND 
                              sst.subjectId = st.subjectId AND
                              INSTR(ttt.subjectTopicId, CONCAT('~',sst.subjectTopicId,'~'))>0 
                         ) AS topic
              FROM 
                        ".ATTENDANCE_TABLE." att, topics_taught tt, subject_topic st,
                        `subject` sub,`class` cls,`group` gr,`employee` emp
              WHERE
                        st.subjectId = sub.subjectId AND
                        att.subjectId = st.subjectId AND        
                        att.classId = cls.classId AND 
                        att.groupId = gr.groupId AND 
                        att.employeeId = emp.employeeId AND 
                        att.topicsTaughtId = tt.topicsTaughtId AND
                        INSTR(tt.subjectTopicId, CONCAT('~',st.subjectTopicId,'~'))>0  
              $conditions          
              GROUP BY
                        att.fromDate, att.topicsTaughtId 
              $order $limit ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //----------------------------------------------------------------------------------------------------
    //function fetching Lecture Delivered Details 
    // Author :Parveen Sharma
    // Created on : 15-05-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //----------------------------------------------------------------------------------------------------    
    public function getLectureDelivered($conditions='', $orderBy='', $limit='',$filter1='',$filter2='') {
        
        $query = "SELECT        
                        t.employeeId, t.employeeName,
                        $filter2
                        SUM(t.totalLecture) AS total,
                        t.subjectId, t.subjectCode, t.subjectName
                  FROM 
                        (SELECT 
                            e.employeeId, e.employeeName, 
                            $filter1
                            MAX(att.lectureDelivered) AS totalLecture,    
                            att.subjectId, su.subjectCode, su.subjectName
                         FROM 
                            ".ATTENDANCE_TABLE." att, time_table_classes ttc, employee e, `group` grp,
                            group_type gt, `subject` su
                         WHERE 
                            su.hasAttendance = 1 AND
                            att.employeeId = e.employeeId       AND 
                            gt.groupTypeId = grp.groupTypeId    AND    
                            att.subjectId = su.subjectId        AND 
                            att.classId = ttc.classId           AND 
                            grp.groupId = att.groupId
                            $conditions
                            GROUP BY att.periodId, att.fromDate, att.subjectId, att.groupId, att.employeeId
                        ) AS t
                  GROUP BY t.employeeName, t.subjectId
                  $orderBy $limit";
                      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //----------------------------------------------------------------------------------------------------
    //function fetching Lecture Delivered Details 
    // Author :Parveen Sharma
    // Created on : 15-05-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //----------------------------------------------------------------------------------------------------    
    public function getLectureDeliveredCount($conditions='',$filter1='',$filter2='') {
        
       $query = "SELECT 
                         COUNT(*) AS cnt
                 FROM        
                         (SELECT 
                                t.employeeId, t.employeeName,
                                $filter2    
                                SUM(t.totalLecture) AS Total,
                                t.subjectId, t.subjectCode, t.subjectName
                          FROM 
                                (SELECT 
                                    e.employeeId, e.employeeName, 
                                    $filter1  
                                    MAX(att.lectureDelivered) AS totalLecture, att.subjectId, su.subjectCode, 
                                    su.subjectName
                                 FROM 
                                     ".ATTENDANCE_TABLE." att, time_table_classes ttc, employee e, `group` grp,
                                     group_type gt, `subject` su
                                 WHERE 
                                    su.hasAttendance = 1 AND
                                    att.employeeId = e.employeeId       AND 
                                    gt.groupTypeId = grp.groupTypeId     AND 
                                    att.subjectId = su.subjectId        AND 
                                    att.classId = ttc.classId           AND 
                                    grp.groupId = att.groupId
                                    $conditions
                                    GROUP BY  att.periodId, att.fromDate, att.subjectId, att.groupId, att.employeeId
                                ) AS t
                          GROUP BY t.employeeName, t.subjectId) AS tt ";
                      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    //----------------------------------------------------------------------------------------------------
    //function created for fetching topics & pending Topic Details 
    // Author :Parveen Sharma
    // Created on : 05-05-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------    
    
    public function getEmployeeTopicsPendingDetails($conditions='',  $orderBy='') {
        
       /* $query = "SELECT 
                        DISTINCT
                        (SELECT 
                              GROUP_CONCAT(DISTINCT topic SEPARATOR ' ---' )
                         FROM 
                              subject_topic sst, topics_taught ttt
                         WHERE 
                              ttt.topicsTaughtId = tt.topicsTaughtId  AND 
                              sst.subjectId = st.subjectId AND
                              INSTR(ttt.subjectTopicId, CONCAT('~',sst.subjectTopicId,'~'))>0 
                         ) AS topicAbbr,
                        sub.subjectName, sub.subjectCode, IFNULL(tt.topicsTaughtId,'') AS topicsTaughtId 
                  FROM
                        `subject` sub, 
                        subject_topic st LEFT JOIN topics_taught tt ON INSTR(tt.subjectTopicId, CONCAT('~',st.subjectTopicId,'~'))>0 
                                         LEFT JOIN `employee` emp ON emp.employeeId = tt.employeeId 
                  WHERE      
                        sub.subjectId = st.subjectId $conditions 
                  $orderBy ";
        */
        $query = "SELECT 
                          st.subjectTopicId AS subjectTopicId, st.subjectId AS subjectId, 
                          st.topic AS topic, st.topicAbbr AS topicAbbr,
                          sub.subjectName, sub.subjectCode, sub.hasAttendance, sub.hasMarks
                  FROM
                          subject_topic st, `subject` sub
                  WHERE 
                          st.subjectId = sub.subjectId
                  $conditions ";                  
                                           
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    
    //----------------------------------------------------------------------------------------------------
    //function created for fetching topics & pending Topic Details 
    // Author :Parveen Sharma
    // Created on : 05-05-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------    
    
    public function getEmployeeTopicsDetails($conditions='') {
        
        /*$query = "SELECT   
                        DISTINCT att.fromDate, att.groupId, IFNULL(att.topicsTaughtId,'') AS topicsTaughtId 
                  FROM 
                        ".ATTENDANCE_TABLE." att, time_table_classes ttc
                  WHERE 
                        att.classId = ttc.classId 
                  $conditions
                  GROUP BY att.employeeId, att.subjectId, att.groupId, att.fromDate ";
        */
        $query = "SELECT 
                        cls.className, 
                        att.topicsTaughtId,
                        sub.subjectName,
                        sub.subjectCode, 
                        gr.groupName,  
                        emp.employeeName,att.fromDate,att.toDate,
                        tt.subjectTopicId
              FROM 
                        ".ATTENDANCE_TABLE." att, topics_taught tt, subject_topic st,
                        `subject` sub,`class` cls,`group` gr,`employee` emp, time_table_classes ttc
              WHERE
                        att.classId = ttc.classId   AND
                        st.subjectId = sub.subjectId AND
                        att.subjectId = st.subjectId AND        
                        att.classId = cls.classId AND 
                        att.groupId = gr.groupId AND 
                        att.employeeId = emp.employeeId AND 
                        att.topicsTaughtId = tt.topicsTaughtId AND
                        INSTR(tt.subjectTopicId, CONCAT('~',st.subjectTopicId,'~'))>0  
              $conditions          
              GROUP BY
                        att.topicsTaughtId 
              $order ";
                   
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    
    //----------------------------------------------------------------------------------------------------
    //function created for fetching Employee Time Tables wise Subject Details findout
    // Author :Parveen Sharma
    // Created on : 05-05-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //----------------------------------------------------------------------------------------------------    
    
    public function getEmployeeSubjectDetails($conditions='',$orderBy='' ) {
        
        $query = "SELECT
                          DISTINCT sub.subjectId, sub.subjectName, sub.subjectCode, emp.employeeId, emp.employeeName, 
                                   tt.groupId, grp.groupName, grp.groupShort, sub.hasAttendance, sub.hasMarks,
                                   SUBSTRING_INDEX(c.className,'-',-3) AS className
                  FROM 
                           ".TIME_TABLE_TABLE."  tt,  `subject` sub, `employee` emp, `group` grp,
                          class c
                  WHERE         
                          tt.subjectId = sub.subjectId AND
                          grp.classId = c.classId AND
                          tt.employeeId = emp.employeeId
						  AND tt.groupId = grp.groupId
						  AND tt.toDate IS NULL
                  $conditions $orderBy ";
                   
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
    //  THIS FUNCTION IS to fetch the role 
    //
    // Author :Jaineesh
    // Created on : (01-10-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function getRoleUser($userId) {
		 $query = "
				SELECT 
							COUNT(*) as totalRecords 
				FROM		classes_visible_to_role cvtr,
							user_role ur
				WHERE		cvtr.userId = ur.userId
				AND			ur.userId = $userId
				AND			cvtr.userId = $userId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    
    
    //----------------------------------------------------------------------------------------------------
    //function created for fetching Employees period wise topic findout
    //
    // Author :Parveen Sharma
    // Created on : 02-06-09
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------    
    
    public function getTeacherSubjectTopic($conditions='',$order='', $limit='') {
        
         $query ="SELECT 
                        cls.className, 
                        att.topicsTaughtId,
                        sub.subjectName,
                        sub.subjectCode, 
                        gr.groupName,  
                        emp.employeeName,att.fromDate,att.toDate,
                        (SELECT 
                              GROUP_CONCAT(DISTINCT topic SEPARATOR ' ---' )
                         FROM 
                              subject_topic sst, topics_taught ttt
                         WHERE 
                              ttt.topicsTaughtId = att.topicsTaughtId  AND 
                              sst.subjectId = st.subjectId AND
                              INSTR(ttt.subjectTopicId, CONCAT('~',sst.subjectTopicId,'~'))>0 
                         ) AS topic, 
                         IF(IFNULL(p.periodNumber,'')='','".NOT_APPLICABLE_STRING."',p.periodNumber) AS periodNumber, 
                         IFNULL(p.periodId,'') AS periodId
              FROM 
                         topics_taught tt, subject_topic st,
                        `subject` sub,`class` cls,`group` gr,`employee` emp, 
                        ".ATTENDANCE_TABLE." att LEFT JOIN period p ON att.periodId = p.periodId 
              WHERE
                        st.subjectId = sub.subjectId AND
                        att.subjectId = st.subjectId AND        
                        att.classId = cls.classId AND 
                        att.groupId = gr.groupId AND 
                        att.employeeId = emp.employeeId AND 
                        att.topicsTaughtId = tt.topicsTaughtId AND
                        INSTR(tt.subjectTopicId, CONCAT('~',st.subjectTopicId,'~'))>0  
              $conditions          
              GROUP BY
                        att.fromDate, att.topicsTaughtId, p.periodId 
              $order $limit ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
     //----------------------------------------------------------------------------------------------------
    // function created for teacher in active time table
    // Author :Parveen Sharma
    // Created on : 12-01-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------    
    public function getTimeTableTeacher($condition='') {
        
        global $sessionHandler;   
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');      
        
        $query = "SELECT 
                        DISTINCT 
                                emp.employeeId, employeeName, employeeAbbreviation, 
                                IF(IFNULL(employeeAbbreviation,'')='',employeeName,
                                CONCAT(employeeName,' (',employeeAbbreviation,')')) AS employeeName1
                        FROM 
                                time_table_labels ttl,  ".TIME_TABLE_TABLE."  tt, employee emp
                        WHERE
                                tt.toDate IS NULL
                                AND ttl.timeTableLabelId=tt.timeTableLabelId
                                AND tt.sessionId = $sessionId
                                AND tt.instituteId = $instituteId
                                AND tt.employeeId = emp.employeeId
                 $condition               
                 ORDER BY employeeName1 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }  
    
    //-----------------------------------------------------------------------------------------------
    // function created for Classes in active time table       
    // Author :Parveen Sharma
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------    
    public function getTimeTableClass($condition, $orderBy=' c.className') {

        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
      
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');

        $query = "SELECT 
                          DISTINCT 
                                    cvtr.classId 
                  FROM    
                          classes_visible_to_role cvtr
                  WHERE   cvtr.userId = $userId
                          AND cvtr.roleId = $roleId ";

        $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
        $count = count($result);
        
        $classId = '';
        if($count>0) {
           $classId = " AND c.classId IN (0";
           for($i=0; $i<count($result); $i++) {
              $classId .= ",".$result[$i]['classId']; 
           }          
           $classId .= ")";
        }

        
        $query = "SELECT 
                         DISTINCT c.classId, c.className 
                  FROM 
                         ".TIME_TABLE_TABLE."  tt,`group` g,`class` c
                  WHERE 
                         tt.groupId=g.groupId 
                         AND g.classId=c.classId  
                         AND c.instituteId=".$instituteId." 
                         AND tt.sessionId=".$sessionId."
                         AND tt.toDate IS NULL 
                         AND c.isActive IN (1,3)
                 $condition $classId 
                 ORDER BY $orderBy ASC";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
	
	//-----------------------------------------------------------------------------------------------
    // function created for Classes in active time table  type      
    // Author :Prashant
    // Created on : 21-05-2010
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------  
	
	 public function getTimeTableTypeClass($condition, $orderBy=' c.className') {

        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
      
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');

        $query = "SELECT 
                          DISTINCT 
                                    cvtr.classId 
                  FROM    
                          classes_visible_to_role cvtr
                  WHERE   cvtr.userId = $userId
                          AND cvtr.roleId = $roleId ";

        $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
        $count = count($result);
        
        $classId = '';
        if($count>0) {
           $classId = " AND c.classId IN (0";
           for($i=0; $i<count($result); $i++) {
              $classId .= ",".$result[$i]['classId']; 
           }          
           $classId .= ")";
        }

        
        $query = "SELECT 
                         DISTINCT c.classId, SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className 
                  FROM 
                         ".TIME_TABLE_TABLE."  tt,`group` g,`class` c
                  WHERE 
                         tt.groupId=g.groupId 
                         AND g.classId=c.classId  
                         AND c.instituteId=".$instituteId." 
                         AND tt.sessionId=".$sessionId."
                         AND tt.toDate IS NULL 
                         AND c.isActive IN (1,3)
                 $condition $classId 
                 ORDER BY $orderBy ASC";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
	
	public function getTimeTableType($timeTableLabelId){
	$systemDatabaseManager = SystemDatabaseManager::getInstance();
	$query= "SELECT timeTableType 
	
			FROM time_table_labels ttt
			
		WHERE  
				ttt.timeTableType =$timeTableLabelId
	
	";
	return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}
    
    //-----------------------------------------------------------------------------------------------
    // function created for Subject in active time table       
    // Author :Parveen Sharma
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------    
    public function getTimeTableSubject($condition, $orderBy=' s.subjectTypeId, s.subjectCode', $groupBy='') {

        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();    
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');

        $query ="SELECT 
                         DISTINCT s.subjectId,s.subjectName,s.subjectCode, s.hasAttendance, s.hasMarks  
                 FROM
                        subject_type st,    
                        subject_to_class sc LEFT JOIN `subject` s ON s.subjectId=sc.subjectId
                        LEFT JOIN `class` c ON sc.classId = c.classId
                        LEFT JOIN  ".TIME_TABLE_TABLE."  tt ON s.subjectId = tt.subjectId AND tt.toDate IS NULL AND tt.sessionId=".$sessionId."
                        LEFT JOIN `group` g ON g.classId=c.classId AND tt.groupId=g.groupId 
                 WHERE    
                        st.subjectTypeId = s.subjectTypeId
                        AND c.instituteId=".$instituteId." 
                        AND c.sessionId=".$sessionId."  
                        AND c.isActive IN (1,3)
                 $condition
                 $groupBy
                 ORDER BY $orderBy ";         
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    
    //-----------------------------------------------------------------------------------------------
    // function created for Subject in active time table       
    // Author :Parveen Sharma
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------    
    public function getTimeTableGroup($condition='', $orderBy=' g.groupName') {

        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        $roleName = $sessionHandler->getSessionVariable('RoleName');    
        
        $tableName = "";
        $hodCondition = ""; 
        $query = "SELECT 
                          DISTINCT 
                                    cvtr.classId 
                  FROM    
                          classes_visible_to_role cvtr
                  WHERE   cvtr.userId = $userId
                          AND cvtr.roleId = $roleId ";

        $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
        $count = count($result);
        
        $insertValue = "(0";
        for($i=0;$i<$count; $i++) {
          $insertValue .= ",".$result[$i]['classId'];
        }
        $insertValue .= ")";
        
          
        if ($count > 0) {
            $tableName = ", classes_visible_to_role cvtr";
            $hodCondition = " AND  cvtr.groupId = g.groupId
                              AND  cvtr.classId = g.classId
                              AND  cvtr.groupId = tt.groupId
                              AND  cvtr.classId = c.classId
                              AND  cvtr.userId = $userId
                              AND  cvtr.roleId = $roleId 
                              AND  c.classId IN $insertValue ";
        }
        
        $query = "SELECT 
                         DISTINCT g.groupId, g.groupName, g.groupShort
                  FROM 
                         ".TIME_TABLE_TABLE."  tt,`group` g,`class` c, `subject` s $tableName
                  WHERE 
                         s.subjectId = tt.subjectId
                         AND tt.groupId=g.groupId 
                         AND g.classId=c.classId  
                         AND c.instituteId=".$instituteId." 
                         AND tt.sessionId=".$sessionId."
                         AND tt.toDate IS NULL 
                         AND c.isActive IN (1,3)
                 $condition
                 $hodCondition
                 ORDER BY $orderBy ASC";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    
    //-----------------------------------------------------------------------------------------------
    // function created for Subject in active time table       
    // Author :Parveen Sharma
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------    
    public function getClasswiseGroup($condition='', $orderBy=' groupName') {

        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');

        $query = "SELECT 
                          DISTINCT 
                                    cvtr.classId 
                  FROM    
                          classes_visible_to_role cvtr
                  WHERE   cvtr.userId = $userId
                          AND cvtr.roleId = $roleId ";

        $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
        $count = count($result);
        
        $insertValue = "(0";
        for($i=0;$i<$count; $i++) {
          $insertValue .= ",".$result[$i]['classId'];
        }
        $insertValue .= ")";
        
        $tableName = "";
        $hodCondition = "";    
        if ($count > 0) {
            $tableName = ", classes_visible_to_role cvtr";
            $hodCondition = " AND  cvtr.groupId = g.groupId
                              AND  cvtr.classId = g.classId
                              AND  cvtr.classId = c.classId
                              AND  cvtr.userId = $userId
                              AND  cvtr.roleId = $roleId 
                              AND  c.classId IN $insertValue ";
        }
        
        $query ="SELECT 
                         DISTINCT g.groupId, g.groupName, g.groupShort
                 FROM 
                        `group` g,`class` c $tableName
                 WHERE 
                         g.classId=c.classId  
                         AND c.instituteId=".$instituteId." 
                         AND c.sessionId=".$sessionId."
                         AND c.isActive IN (1,3)
                 $condition
                 $hodCondition
                 ORDER BY $orderBy ASC";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    //-----------------------------------------------------------------------------------------------
    // function created for Subject in active time table       
    // Author :Parveen Sharma
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------    
    public function getActiveTimeTableSubject($condition='', $orderBy=' s.subjectCode') {

        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();    
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT 
                         DISTINCT s.subjectId,s.subjectName,s.subjectCode, s.hasAttendance, s.hasMarks    
                  FROM 
                         ".TIME_TABLE_TABLE."  tt,`group` g,`class` c, `subject` s  
                  WHERE 
                         s.subjectId = tt.subjectId
                         AND tt.groupId=g.groupId 
                         AND g.classId=c.classId  
                         AND c.instituteId= ".$instituteId." 
                         AND tt.sessionId= ".$sessionId."
                         AND tt.toDate IS NULL 
                         AND tt.timeTableLabelId IN
                        (SELECT 
                                timeTableLabelId 
                         FROM 
                                time_table_labels ttl
                         WHERE 
                                ttl.isActive = 1  AND
                                ttl.instituteId= ".$instituteId." AND
                                ttl.sessionId= ".$sessionId.")
                  $condition
                  ORDER BY $orderBy ASC ";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getEmployeeRoleId($roleId='') {
     
       $query = "SELECT 
                      DISTINCT emp.employeeId 
                 FROM 
                      employee emp 
                      LEFT JOIN `user` u ON emp.userId = u.userId 
                      LEFT JOIN `role` r ON r.roleId = u.roleId                
                 WHERE
                      r.roleId = '$roleId' ";
       
       return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
    
}

//$History: EmployeeReportsManager.inc.php $
//
//*****************  Version 32  *****************
//User: Parveen      Date: 4/13/10    Time: 1:29p
//Updated in $/LeapCC/Model
//getActiveTimeTableSubject function updated
//
//*****************  Version 31  *****************
//User: Parveen      Date: 4/13/10    Time: 12:13p
//Updated in $/LeapCC/Model
//getActiveTimeTableSubject function added
//
//*****************  Version 29  *****************
//User: Parveen      Date: 3/17/10    Time: 10:44a
//Updated in $/LeapCC/Model
//getTimeTableClass function update (hod roles classes  fetch)
//
//*****************  Version 28  *****************
//User: Parveen      Date: 2/22/10    Time: 12:34p
//Updated in $/LeapCC/Model
//getTimeTableGroup function updated  (hod roles updated)
//
//*****************  Version 27  *****************
//User: Parveen      Date: 2/17/10    Time: 11:30a
//Updated in $/LeapCC/Model
//getTimeTableGroup function added
//
//*****************  Version 26  *****************
//User: Parveen      Date: 2/11/10    Time: 6:31p
//Updated in $/LeapCC/Model
//query added getTimeTableSubject, getTimeTableClass, getTimeTableTeacher
//
//*****************  Version 25  *****************
//User: Parveen      Date: 2/11/10    Time: 4:45p
//Updated in $/LeapCC/Model
//getTimeTableTeacher function added
//
//*****************  Version 24  *****************
//User: Parveen      Date: 12/22/09   Time: 5:26p
//Updated in $/LeapCC/Model
//getTeacherSubjectTopic function updated
//
//*****************  Version 23  *****************
//User: Parveen      Date: 12/03/09   Time: 3:25p
//Updated in $/LeapCC/Model
//getTeacherSubjectTopic function added
//
//*****************  Version 22  *****************
//User: Parveen      Date: 11/23/09   Time: 2:14p
//Updated in $/LeapCC/Model
//getEmployeeSubjectDetails function updated (className added)
//
//*****************  Version 21  *****************
//User: Parveen      Date: 11/23/09   Time: 1:52p
//Updated in $/LeapCC/Model
//getEmployeeTopicsPendingDetails function updated
//
//*****************  Version 20  *****************
//User: Parveen      Date: 11/20/09   Time: 6:04p
//Updated in $/LeapCC/Model
//subjectTopic checks updated
//
//*****************  Version 19  *****************
//User: Parveen      Date: 11/20/09   Time: 12:49p
//Updated in $/LeapCC/Model
//getAllSubjectTopic, getCountSubjectTopic function updated
//
//*****************  Version 18  *****************
//User: Parveen      Date: 11/20/09   Time: 12:45p
//Updated in $/LeapCC/Model
//getCountSubjectTopic function updated 
//
//*****************  Version 17  *****************
//User: Parveen      Date: 11/19/09   Time: 6:14p
//Updated in $/LeapCC/Model
//getCountSubjectTopic, getAllSubjectTopic function updated
//
//*****************  Version 16  *****************
//User: Parveen      Date: 10/24/09   Time: 12:02p
//Updated in $/LeapCC/Model
//getLectureDelivered condition updated (periodId check added)
//
//*****************  Version 15  *****************
//User: Parveen      Date: 10/23/09   Time: 4:04p
//Updated in $/LeapCC/Model
//getLectureDeliveredCount, getLectureDelivered function updated
//(dynamically column created)
//
//*****************  Version 14  *****************
//User: Parveen      Date: 10/06/09   Time: 2:51p
//Updated in $/LeapCC/Model
//class added, look & feel formating 
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 10/06/09   Time: 11:54a
//Updated in $/LeapCC/Model
//modified in code for HOD role
//
//*****************  Version 12  *****************
//User: Parveen      Date: 10/01/09   Time: 10:46a
//Updated in $/LeapCC/Model
//getEmployeeSubjectDetails (hasAttendance, hasMarks) added
//
//*****************  Version 11  *****************
//User: Parveen      Date: 9/17/09    Time: 1:11p
//Updated in $/LeapCC/Model
//getAllSubjectTopic, getCountSubjectTopic function (instr query updated)
//
//*****************  Version 10  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Model
//search & conditions updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 9/11/09    Time: 3:48p
//Updated in $/LeapCC/Model
//getLectureDelivered, getLectureDeliveredCount function 
//table name setting common "ATTENDANCE_TABLE" 
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:22p
//Updated in $/LeapCC/Model
//added multiple table defines.
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/26/09    Time: 5:11p
//Updated in $/LeapCC/Model
//getEmployeeSubjectDetails, getEmployeeTopicsPendingDetails function
//added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Model
//formatting, conditions, validations updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/17/09    Time: 11:04a
//Updated in $/LeapCC/Model
//validation, formatting, themes base css templates changes
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/03/09    Time: 12:25p
//Updated in $/LeapCC/Model
//getAllSubjectTopic function update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/03/09    Time: 10:35a
//Updated in $/LeapCC/Model
//query update employeeList
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/23/08   Time: 4:09p
//Created in $/LeapCC/Model
//inital checkin
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/03/08   Time: 12:47p
//Updated in $/Leap/Source/Model
//code review
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/03/08   Time: 10:17a
//Updated in $/Leap/Source/Model
//employee list query check
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/31/08   Time: 1:55p
//Updated in $/Leap/Source/Model
//Query update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/31/08   Time: 12:15p
//Created in $/Leap/Source/Model
//employee list added
//
//*****************  Version 15  *****************
//User: Ajinder      Date: 10/01/08   Time: 10:33a
//Updated in $/Leap/Source/Model
//fixed minor bug relating to attendance %
//
//*****************  Version 14  *****************
//User: Ajinder      Date: 9/30/08    Time: 4:54p
//Updated in $/Leap/Source/Model
//added code for showing class name and percentage
//
//*****************  Version 13  *****************
//User: Ajinder      Date: 9/30/08    Time: 10:42a
//Updated in $/Leap/Source/Model
//1. updated getTestTotalEmployees()
//2. updated getTestPresentEmployees()
//3. updated getClassWiseConsolidatedMarks()
//4. added getSubjectSectionTestClasses()
//
//for course performance graph report.
//
//*****************  Version 12  *****************
//User: Arvind       Date: 9/23/08    Time: 6:09p
//Updated in $/Leap/Source/Model
//added common filter
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 9/22/08    Time: 3:20p
//Updated in $/Leap/Source/Model
//modified functions to fetch data from sc_Employee table.
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 9/20/08    Time: 8:16p
//Updated in $/Leap/Source/Model
//1. updated getTestDetails()
//2. updated getTestWiseMarksResult()
//3. added getSubjectSectionTestTypes()
//
//*****************  Version 8  *****************
//User: Arvind       Date: 9/19/08    Time: 4:49p
//Updated in $/Leap/Source/Model
//removed sessionId and institute ID from getScEmployeeListReportList()
//function
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 9/19/08    Time: 4:45p
//Updated in $/Leap/Source/Model
//updated functions
//
//*****************  Version 6  *****************
//User: Arvind       Date: 9/19/08    Time: 12:30p
//Updated in $/Leap/Source/Model
//added sessionId and instituteId in getScEmployeeListReportList()
//functions query
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 9/19/08    Time: 12:04p
//Updated in $/Leap/Source/Model
//updated functions
//
//*****************  Version 3  *****************
//User: Arvind       Date: 9/18/08    Time: 9:03p
//Updated in $/Leap/Source/Model
//added function for sc Employee lists
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/18/08    Time: 8:52p
//Updated in $/Leap/Source/Model
//updated functions
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/18/08    Time: 5:27p
//Created in $/Leap/Source/Model
//file added for Employee reports - sc
//
//*****************  Version 32  *****************
//User: Ajinder      Date: 9/13/08    Time: 12:10p
//Updated in $/Leap/Source/Model
//added current session and current institute code.
//
//*****************  Version 30  *****************
//User: Ajinder      Date: 9/09/08    Time: 4:06p
//Updated in $/Leap/Source/Model
//fixed mistake in "getClassTeachingSubjectList" function
//
//*****************  Version 29  *****************
//User: Ajinder      Date: 9/09/08    Time: 1:49p
//Updated in $/Leap/Source/Model
//1. removed function getTimeTableClasses()
//2. added function getTestClasses()
//3. modified function getClassTeachingSubjectList()
//
//to remove the relation from time table, and make it with test table
//
//*****************  Version 27  *****************
//User: Ajinder      Date: 9/08/08    Time: 12:27p
//Updated in $/Leap/Source/Model
//removed echo
//
//*****************  Version 25  *****************
//User: Ajinder      Date: 9/06/08    Time: 3:45p
//Updated in $/Leap/Source/Model
//added:
//getClassTeachingSubjectList()
//modified:
//1. getTestList()
//2. getEmployeeGroupList()
//3. getAllSubjectMissedAttendanceReport()
//
//*****************  Version 24  *****************
//User: Ajinder      Date: 9/05/08    Time: 1:21p
//Updated in $/Leap/Source/Model
//modified getClassSubjectGroupTests() function.
//fetched part of class name.
//
//*****************  Version 22  *****************
//User: Ajinder      Date: 9/04/08    Time: 11:00a
//Updated in $/Leap/Source/Model
//file modified and fixed bugs found during self testing
//
//*****************  Version 20  *****************
//User: Ajinder      Date: 8/28/08    Time: 3:45p
//Updated in $/Leap/Source/Model
//updated functions:
//1. getEmployeeGroupList()
//2. getTestList()
//
//*****************  Version 19  *****************
//User: Ajinder      Date: 8/28/08    Time: 12:36p
//Updated in $/Leap/Source/Model
//added following functions for class performance graph:
//1. getEmployeeList()
//2. getEmployeeGroupList()
//3. getTestList()
//4. getTestTotalEmployees()
//5. getTestPresentEmployees()
//6. getClassWiseConsolidatedMarks()
//
//*****************  Version 18  *****************
//User: Arvind       Date: 8/26/08    Time: 2:59p
//Updated in $/Leap/Source/Model
//removed echoing
//
//*****************  Version 17  *****************
//User: Ajinder      Date: 8/25/08    Time: 6:19p
//Updated in $/Leap/Source/Model
//added function getEmployeeLabelData() for Employee labels report
//
//*****************  Version 16  *****************
//User: Ajinder      Date: 8/25/08    Time: 1:27p
//Updated in $/Leap/Source/Model
//modified functions:
//1. getClassTestTypes()
//2. getTestDetails()
//
//added 'test_marks' table in the queries to make queries better, and
//include only those tests for which marks have been entered.
//
//*****************  Version 14  *****************
//User: Ajinder      Date: 8/22/08    Time: 3:35p
//Updated in $/Leap/Source/Model
//added following functions for subjectWiseConsolidatedReport report :
//
//1.getClassConsolidatedSubjects()
//2. getAllSubjectConsolidatedMarks()
//
//*****************  Version 13  *****************
//User: Ajinder      Date: 8/21/08    Time: 11:28a
//Updated in $/Leap/Source/Model
//removed following functions:
//1. getRangeValues()
//2. getEmployeesInRange()
//and added following function:
//1. getSubjectWiseConsolidatedMarks()
//for subjectwise consolidated report
//
//*****************  Version 12  *****************
//User: Ajinder      Date: 8/20/08    Time: 6:41p
//Updated in $/Leap/Source/Model
//added following functions:
//1. getRangeValues()
//2. getEmployeesInRange()
//3. countClassSubjectEmployees()
//
//for subjectwise consolidated report
//


?>