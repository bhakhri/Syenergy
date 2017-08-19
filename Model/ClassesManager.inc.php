<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Class" Module
//
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ClassesManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ClassManager" CLASS
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


	private function __construct() {
	}


//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ClassManager" CLASS
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Class
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


	public function addClass($studyPeriodId, $className, $isActive, $nextSessionId) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$classDescription = add_slashes($REQUEST_DATA['description']);

		return SystemDatabaseManager::getInstance()->runAutoInsert('class', array('instituteId','universityId', 'batchId', 'degreeId', 'sessionId', 'branchId', 'studyPeriodId', 'degreeDuration', 'classDescription', 'className', 'isActive'), array($instituteId,$REQUEST_DATA['universityId'],$REQUEST_DATA['batchId'],$REQUEST_DATA['degreeId'],$nextSessionId,$REQUEST_DATA['branchId'],$studyPeriodId,$REQUEST_DATA['degreeDurationId']." year", $classDescription, $className,$isActive));
	}

	public function addClassInTransaction($studyPeriodId, $className, $isActive, $nextSessionId) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$classDescription = add_slashes($REQUEST_DATA['description']);

		$query = "INSERT INTO class set instituteId = $instituteId, universityId = ".$REQUEST_DATA['universityId'].", batchId = ".$REQUEST_DATA['batchId'].", degreeId = ".$REQUEST_DATA['degreeId'].", sessionId = $nextSessionId, branchId = ".$REQUEST_DATA['branchId'].", studyPeriodId = $studyPeriodId, degreeDuration = ".$REQUEST_DATA['degreeDurationId'].", classDescription = '$classDescription', className = '$className', isActive = $isActive";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}



//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CLASSES
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getClasses($conditions='') {

        $query = "SELECT a.sessionId, a.batchId, a.universityId, a.degreeId, a.branchId, a.degreeDuration, b.periodicityId, a.classDescription
        FROM class a, study_period b WHERE a.studyPeriodId = b.studyPeriodId
        $conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CLASSES LIST
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


    public function getClassesList($conditions='', $limit = '', $orderBy=' className') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		if ($conditions !='') {
			$conditions = " AND $conditions";
		}
    /*   $query = "
					SELECT
								a.classId,
								a.className,
								b.sessionName,
								a.degreeDuration,
								d.periodicityName,
								e.degreeCode,
								f.batchName,
								g.branchName,
								c.periodName as Active
					FROM
								class a,
								session b,
								study_period c,
								periodicity d,
								degree e,
								batch f,
								branch g
					WHERE		a.isActive = 1
					AND			a.sessionId = b.sessionId
					and			a.studyPeriodId = c.studyPeriodId
					and			c.periodicityId = d.periodicityId
					and			a.degreeId = e.degreeId
					and			a.batchId = f.batchId
					and			a.branchId = g.branchId
					and			a.instituteId = f.instituteId
					$conditions
					AND			a.instituteId = $instituteId
					ORDER BY	$orderBy $limit ";               
                           
   */                $query = "
                    SELECT
                                a.classId,
                                a.className,
                                b.sessionName,
                                a.degreeDuration,
                                d.periodicityName,
                                e.degreeCode,
                                f.batchName,
                                g.branchName,
                                c.periodName as Active,
                                (
                                SELECT 
                                COUNT(stu.studentId)    
                                FROM  student stu
                                WHERE a.branchId = g.branchId 
                                AND stu.classId = a.classId
                                
                                )  
                    AS studentCount 
                    FROM
                                class a,
                                session b,
                                study_period c,
                                periodicity d,
                                degree e,
                                batch f,
                                branch g                    
                  WHERE            a.isActive = 1
                    AND            a.sessionId = b.sessionId
                    and            a.studyPeriodId = c.studyPeriodId
                    and            c.periodicityId = d.periodicityId
                    and            a.degreeId = e.degreeId
                    and            a.batchId = f.batchId
                    and            a.branchId = g.branchId
                    and            a.instituteId = f.instituteId
                    AND            a.instituteId = $instituteId
                    HAVING         1=1
                    $conditions
                    ORDER BY    $orderBy $limit ";                  
                                                                       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "ALL CLASSES" TABLE
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getTotalClasses($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions !='') {
			$conditions = " WHERE $conditions AND isActive = 1 AND instituteId = $instituteId";
		}
		else {
			$conditions = " WHERE  isActive = 1 AND instituteId = $instituteId";
		}
        $query = "SELECT COUNT(*) AS totalRecords
        FROM class $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING PERIODICITY BASED ON CONDITIONS
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getPeriodicityFrequency($conditions='') {
		$query = "SELECT periodicityFrequency FROM periodicity $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING PERIODICITY BASED ON ID
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getPeriodicityFrequencyById($periodicityId) {
		$query = "SELECT periodicityFrequency FROM periodicity WHERE periodicityId = $periodicityId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING PERIODICITES BASED ON PERIODICITYID
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function countStudyPeriods($periodicityId) {
		$query = "SELECT COUNT(*) AS cnt FROM study_period WHERE periodicityId = $periodicityId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING PERIODICITES BASED ON PERIODICITYID
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getStudyPeriods($periodicityId, $records) {
		$query = "SELECT studyPeriodId, periodName, periodValue FROM study_period WHERE periodicityId = $periodicityId ORDER BY periodValue LIMIT 0, $records";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING CLASS NAME BASED ON THE BATCH YEAR, UNIV. CODE, DEGREE CODE, BRANCH CODE AND PERIOD NAME
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getClassName($periodName) {
		global $REQUEST_DATA;

		$query = "SELECT CONCAT(a.batchYear, ' - ',b.universityCode, ' - ',c.degreeCode, ' - ', d.branchCode, ' -  $periodName') AS className FROM batch a, university b, degree c, branch d, study_period e WHERE a.batchId = ".$REQUEST_DATA['batchId']." AND b.universityId = ".$REQUEST_DATA['universityId']." AND c.degreeId = ".$REQUEST_DATA['degreeId']." AND d.branchId = ".$REQUEST_DATA['branchId'];
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING CLASSID BASED ON THE CONDITIONS PASSED TO IT
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getClass($conditions='') {
		$query = "SELECT classId FROM class $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING BATCH YEAR AND SESSION YEAR
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getBatchAndSessionYear($batchId, $sessionId) {
		$query = "SELECT a.batchYear, b.sessionYear FROM batch a, session b WHERE a.batchId = $batchId AND b.sessionId = $sessionId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING NEXT YEAR SESSION ID
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getNextSessionYearId($sessionId) {
		$query = "SELECT sessionId FROM session WHERE sessionYear = (SELECT sessionYear + 1 FROM session WHERE sessionId = $sessionId)";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING SESSION ID BASED ON YEAR
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getSessionId($year) {
		$query = "SELECT sessionId FROM session WHERE sessionYear = '$year'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING SESSION YEARS
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function countSessionYear($year) {
		$query = "SELECT COUNT(*) as count FROM session WHERE sessionYear = '$year'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING SESSION YEAR BASED ON SESSION ID
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getSessionYear($sessionId) {
		$query = "SELECT sessionYear FROM session WHERE sessionId = $sessionId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING BATCH YEAR BASED ON BATCH ID
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getBatchYear($batchId) {
		$query = "SELECT batchYear FROM batch WHERE batchId = $batchId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING CLASS STUDENTS
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function countClassStudents($classId) {
		$query = "SELECT COUNT(*) as count FROM students WHERE classId = $classId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING STUDENTS IN RELATED CLASSES OF A CLASS
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function countStudentsInRelatedClasses($classId) {
		$query = "SELECT COUNT(*) AS count FROM student WHERE classId IN (SELECT classId FROM class WHERE concat(degreeId,'-',branchId,'-',batchId) = (SELECT concat(degreeId,'-',branchId,'-',batchId) FROM class WHERE classId =$classId))";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING STUDENTS IN RELATED CLASSES OF A CLASS
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function countTimeTableMappingInRelatedClasses($classId) {
		$query = "SELECT COUNT(*) AS count FROM time_table_classes WHERE classId IN (SELECT classId FROM class WHERE concat(degreeId,'-',branchId,'-',batchId) = (SELECT concat(degreeId,'-',branchId,'-',batchId) FROM class WHERE classId =$classId))";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING STUDENTS IN RELATED CLASSES OF A CLASS
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getAllRelatedClasses($classId) {
		$query = "SELECT classId FROM class WHERE concat(degreeId,'-',branchId,'-',batchId,'-',instituteId) = (SELECT concat(degreeId,'-',branchId,'-',batchId,'-',instituteId) FROM class WHERE classId =$classId)";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A CLASS
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function deleteClassInTransaction($classId) {
		$query = "DELETE FROM class WHERE classId IN ($classId)";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING STUDY PERIODS BASED ON A CLASS
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getClassStudyPeriods($classId) {
		$query = "SELECT a.studyPeriodId, b.periodName, b.periodValue, a.isActive FROM class a, study_period b WHERE a.studyPeriodId = b.studyPeriodId AND concat(a.instituteId,'-',a.universityId,'-',a.degreeId, '-', a.branchId, '-', a.batchId) = (SELECT concat(instituteId,'-',universityId,'-',degreeId, '-', branchId, '-', batchId) FROM class WHERE classId = $classId ) ORDER BY a.studyPeriodId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING CLASS SUBJECTS
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function deleteClassSubjectsInTransaction($classId) {
		$query = "DELETE FROM subject_to_class WHERE classId IN ($classId)";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING CLASS Groups
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function deleteClassGroupsInTransaction($classId) {
		$query = "DELETE FROM `group` WHERE classId IN ($classId)";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Fetching active session year
//
// Author :Ajinder Singh
// Created on : 29-July-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getActiveSessionYear() {
		$query = "select sessionYear from `session` where active = 1";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Fetching study period
//
// Author :Ajinder Singh
// Created on : 29-July-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getThisStudyPeriod($classId, $nextPeriod) {
		$query = "SELECT studyPeriodId FROM `study_period` WHERE periodicityId=(select periodicityId from study_period
		where studyPeriodId = (select studyPeriodId from class where classId = '$classId')) ORDER BY periodValue limit $nextPeriod,1";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Fetching class with a study period
//
// Author :Ajinder Singh
// Created on : 29-July-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getRelatedClassStudyPeriod($classId, $studyPeriodId) {
		$query = "select classId from class where studyPeriodId = $studyPeriodId and concat(instituteId,'-',universityId,'-',degreeId, '-', branchId, '-', batchId) = (SELECT concat(instituteId,'-',universityId,'-',degreeId, '-', branchId, '-', batchId) FROM class WHERE classId = $classId )";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR updating class details
//
// Author :Ajinder Singh
// Created on : 29-July-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function updateClass($thisClassId, $isActive) {
		global $REQUEST_DATA;
		$classDescription = add_slashes($REQUEST_DATA['description']);
		$query = "update class set isActive = $isActive, classDescription = '$classDescription' where classId = $thisClassId";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR updating class details in transaction
//
// Author :Ajinder Singh
// Created on : 29-July-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function updateClassInTransaction($thisClassId, $isActive) {
		global $REQUEST_DATA;
		$classDescription = add_slashes($REQUEST_DATA['description']);
		$query = "update class set isActive = $isActive, classDescription = '$classDescription' where classId = $thisClassId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR checking universityId
//
// Author :Ajinder Singh
// Created on : 29-July-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getUniversityId($universityId) {
		$query = "select universityId from university where universityId = $universityId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR checking degree Id
//
// Author :Ajinder Singh
// Created on : 29-July-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getDegreeId($degreeId) {
		$query = "select degreeId from degree where degreeId = $degreeId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR checking branchId
//
// Author :Ajinder Singh
// Created on : 29-July-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getBranchId($branchId) {
		$query = "select branchId from branch where branchId = $branchId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR checking if a class with different periodicity already exists or not.
//
// Author :Ajinder Singh
// Created on : 29-July-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function countMultiplePeriodicity($batchId, $degreeId, $universityId, $branchId, $instituteId) {
		$query = "SELECT COUNT(DISTINCT(c.periodicityId)) AS cnt FROM periodicity c, study_period b, class a WHERE a.batchId = $batchId AND a.degreeId = $degreeId AND a.universityId = $universityId AND a.branchId = $branchId AND a.instituteId = $instituteId AND a.studyPeriodId = b.studyPeriodId AND b.periodicityId = c.periodicityId GROUP BY a.batchId, a.degreeId, a.universityId, a.branchId, a.instituteId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

	}

/*THESE FUNCTIONS ARE NEEDED FOR COPING PERIODICITY*/
    public function getPeriodicity($conditions='') {
        $query = "SELECT * FROM periodicity $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function checkAlumniPeriodicity($periodicityId) {
        $query = "SELECT
                        COUNT(*) AS cnt
                  FROM
                        study_period
                  WHERE
                        periodicityId=$periodicityId
                        AND periodValue=99999
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

        public function copyAlumniPeriodicity($periodicityId) {

         $query = "INSERT INTO
                             study_period
                             (periodName,periodValue,periodicityId)
                  VALUES
                        ('Alumni',99999,$periodicityId)
        ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


	public function checkCopyPeriodicity() {
		$periodicityArray=$this->getPeriodicity();
		$cnt = count($periodicityArray);
		for($i = 0; $i < $cnt; $i++) {
			$chkArray = $this->checkAlumniPeriodicity($periodicityArray[$i]['periodicityId']);
			if($chkArray[0]['cnt'] != 0) {
				continue;
			}
			$ret = $this->copyAlumniPeriodicity($periodicityArray[$i]['periodicityId']);
			if($ret == false) {
				return false;
			}
		}
		return true;
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching alumni classid of a class
//
// Author :Ajinder Singh
// Created on : 29-01-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function getAlumniClassId($classId) {
		$query = "
					SELECT
										classId
					from				class
					where				studyPeriodId = (select studyPeriodId from study_period where periodValue = 99999
					and				instituteId = (select instituteId from class where classId = '$classId')
					and				universityId = (select universityId from class where classId = '$classId')
					and				batchId = (select batchId from class where classId = '$classId')
					and				degreeId = (select degreeId from class where classId = '$classId')
					and				branchId = (select branchId from class where classId = '$classId')
					and				periodicityId = (select periodicityId from study_period where studyPeriodId = (select studyPeriodId from class where classId = '$classId'))) ";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR creating alumni record
//
// Author :Ajinder Singh
// Created on : 29-01-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function createAlumniClassInTransaction($alumniRecord) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		$universityId = $alumniRecord['universityId'];
		$batchId = $alumniRecord['batchId'];
		$degreeId = $alumniRecord['degreeId'];
		$sessionId = $alumniRecord['sessionId'];
		$branchId = $alumniRecord['branchId'];
		$studyPeriodId = $alumniRecord['studyPeriodId'];
		$degreeDuration = $alumniRecord['degreeDuration'];
		$className = $alumniRecord['className'];
		$rollNoPrefix = $alumniRecord['rollNoPrefix'];
		$rollNoSuffix = $alumniRecord['rollNoSuffix'];

		$query = "insert into class set instituteId = $instituteId, universityId = $universityId, batchId = $batchId, degreeId = $degreeId, sessionId = $sessionId, branchId = $branchId, studyPeriodId = $studyPeriodId, degreeDuration = $degreeDuration, classDescription = 'Alumni', className = '$className', rollNoPrefix = '$rollNoPrefix', rollNoSuffix = '$rollNoSuffix', isActive = 5, marksTransferred = 1, isFrozen = 1";
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting details of class
//
// Author :Ajinder Singh
// Created on : 29-01-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getLastClassDetails($classId) {
		$query = "select (select universityId from class where classId = $classId) as universityId, (select batchId from class where classId = $classId) as batchId, (select degreeId from class where classId = $classId) as degreeId, (select sessionId from session where sessionYear = (select sessionYear+1 from session where sessionId = (select sessionId from class where classId = $classId))) as sessionId, (select branchId from class where classId = $classId) as branchId, (select studyPeriodId from study_period where periodValue = 99999 and periodicityId = (select periodicityId from study_period where studyPeriodId = (select studyPeriodId from class where classId = '$classId'))) as studyPeriodId, (select degreeDuration from class where classId = $classId) as degreeDuration, (select concat(substring_index(className,'-',4),' - Alumni') from class where classId = $classId) as className, (select rollNoPrefix from class where classId = '$classId') as rollNoPrefix, (select rollNoSuffix from class where classId = $classId) as rollNoSuffix";
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

	}
}

// $History: ClassesManager.inc.php $
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 2/01/10    Time: 2:28p
//Updated in $/LeapCC/Model
//done changes for code of making Alumni Class.
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 29/01/10   Time: 17:28
//Updated in $/LeapCC/Model
//Created Script for copying "Periodicity"
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 7/29/09    Time: 3:43p
//Updated in $/LeapCC/Model
//done the changes to fix bug no.s 754, 751
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 7/23/09    Time: 3:46p
//Updated in $/LeapCC/Model
//done the changes to fix following bug no.s:
//1. 642
//2. 625
//3. 601
//4. 573
//5. 572
//6. 570
//7. 569
//8. 301
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 5/29/09    Time: 1:35p
//Updated in $/LeapCC/Model
//changed queries to show lists as per current institute only.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/15/08   Time: 6:09p
//Updated in $/LeapCC/Model
//added functions to change the functionality at the time of class
//creation/updation.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/08/08    Time: 6:38p
//Updated in $/Leap/Source/Model
//added function deleteClassGroups to delete all groups related to class

?>