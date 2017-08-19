<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
class CommonQueryManager {
	private static $instance = null;

	private function __construct() {
	}

	public static function getInstance() {
		if (self::$instance === null) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}
	public function getCountries($orderBy=' countryId',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = "SELECT * FROM countries $condition ORDER BY $orderBy";
		return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}
	public function getRanges() {
		$query = "SELECT lowMarksValue, highMarksValue from division_class_detail order by lowMarksValue";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	public function getClassName($classId) {
		 $systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = "	SELECT
							className
					FROM	class
					WHERE
							classId = $classId";

		return $systemDatabaseManager->executeQuery($query,"Query: $query");
		//return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	public function getStudentName($studentIds) {
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = "SELECT concat(firstName,' ',lastName) as studentName FROM student WHERE studentId IN ($studentIds)";
		return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}
	public function addAuditTrialRecord($type, $auditTrialDescription,$queryDescription='') {
		global $REQUEST_DATA, $sessionHandler;

		$systemDatabaseManager = SystemDatabaseManager::getInstance();

		$userIp = $_SERVER['REMOTE_ADDR'];
		$userId = $sessionHandler->getSessionVariable('UserId');
		
        $aType = str_replace("'","",$aType);
        $aDescription = str_replace("'","",$aDescription);
        $qDescription = str_replace("'","",$qDescription);
        
        $aType = str_replace('"',"",$aType);
        $aDescription = str_replace('"',"",$aDescription);
        $qDescription = str_replace('"',"",$qDescription);
        
        $aType = htmlentities(add_slashes($type)); 
        $aDescription = htmlentities(add_slashes($auditTrialDescription));
        $qDescription = htmlentities(add_slashes($queryDescription));
        
        $query = 'INSERT INTO `audit_trail`
	  	 	      (`auditType` ,`userId` ,`auditDateTime` ,`description` ,`userIp`,`queryDescription`)
	  		      VALUES	
                  ("'.$aType.'", "'.$userId.'", now(), "'.$aDescription.'", "'.$userIp.'","'.$qDescription.'")';
        
		$sessionHandler->setSessionVariable('IdToQueryDescription',''); 

		return $systemDatabaseManager->executeUpdateInTransaction($query,"Query: $query");
	}
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STATES
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getStates($orderBy=' stateId',$condition) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM states $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

// getStudentMobileNumber() is used to get the student mobile number
public function getStudentMobileNumber($studentIds){
	$query= "SELECT
						studentMobileNo
				FROM	`student`
				WHERE	studentId IN($studentIds)";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STATES CORRESPONDING TO A COUNTRY
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (16.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getStatesCountry($condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT stateId,stateName FROM states $condition ORDER BY stateName";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF PARTY
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (03 Sep 10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getIndent($orderBy=' indentId',$condition) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query ="	SELECT
								*
					FROM
								inv_indent_master
					WHERE
								indentStatus = 0
					ORDER BY	$orderBy
				";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF PARTY
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (03 Sep 10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getParty($orderBy=' partyId',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM inv_party $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

	//----------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF PURCHASE ORDERS
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (03 Sep 10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function getPO($orderBy='poId',$condition) {
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = "	SELECT
								ipm. *
					FROM
								inv_po_master ipm,
								inv_po_trans ipt
					WHERE
								ipt.poId = ipm.poId
					AND			ipt.grnId = 0
				";
		return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Section
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (17.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getSection($conditions='') {
       global $REQUEST_DATA;
        global $sessionHandler;
       $query = "SELECT DISTINCT sec.sectionId,sec.sectionName
        FROM sc_time_table t,sc_section sec
        WHERE sec.sectionId=t.sectionId
        AND t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
        AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
        AND t.employeeId=$teacherId
        AND t.toDate IS NULL
        $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Bank branch
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (22.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
  public function getBankBranch($condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT bankBranchId,branchAbbr FROM bank_branch $condition ORDER BY branchAbbr";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Banks
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (18.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getBank($condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT bankId,bankName,bankAbbr FROM bank $condition ORDER BY bankName";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STATES CORRESPONDING TO A COUNTRY
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (09.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getHostelRoom($orderBy=' roomName',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT hostelRoomId ,roomName FROM hostel_room $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//---------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET Expected Date of Checkout
// Author :Dipanjan Bhattacharjee
// Created on : (09.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------
    public function getExpectedCheckOutDate($condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT studentId , possibleDateOfCheckOut FROM hostel_students $condition ";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF INSTITUTE ROOM
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.01.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getInstituteRoom($orderBy=' roomName',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT roomId ,roomAbbreviation FROM room $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF INSTITUTE ROOM
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.01.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getInstituteRoom2($orderBy=' roomName',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT r.roomId , concat(c.abbreviation, '-',b.abbreviation,'-',r.roomAbbreviation) as roomAbbreviation FROM room r, block b, building c WHERE r.blockId = b.blockId AND b.buildingId = c.buildingId $condition ORDER BY roomAbbreviation";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STUDY PERIOD
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (09.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getClassStudyPeriod($orderBy=' 	studyPeriodId',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT cls.studyPeriodId as studyPeriodId,sp.periodName as periodName
		FROM class cls,study_period sp $condition
		ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF SUBJECT CORRESPONDING TO A CLASS
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (30.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getClassSubject($orderBy=' sub.subjectName',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT sub.subjectId ,sub.subjectName,sub.subjectCode FROM subject sub, subject_to_class subTocls $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Batches CORRESPONDING TO A CLASS FOr "Sc" Approach
//
//orderBy: on which column to sort
//
// Author :Arvind Singh Rawat
// Created on : (30.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getScClassBatch($orderBy=' bat.batchName',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT DISTINCT(bat.batchName),bat.batchId FROM batch bat, class cls WHERE bat.batchId=cls.batchId $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Grades
//
//orderBy: on which column to sort
// Author :Ajinder Singh
// Created on : (04.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------
 	public function getGradeLabels($gradeSetId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT gradeId, gradeLabel FROM grades  WHERE gradeSetId = '$gradeSetId' AND instituteId = $instituteId ORDER BY gradeLabel";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Batches CORRESPONDING TO A CLASS FOr "Sc" Approach
//
//orderBy: on which column to sort
//
// Author :Arvind Singh Rawat
// Created on : (30.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getScClassStudyPeriod($orderBy=' std.periodName',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT DISTINCT(std.periodName),std.studyPeriodId FROM study_period std, class cls WHERE std.studyPeriodId=cls.studyPeriodId  $condition ORDER BY $orderBy";
        //echo $query;
		return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
/*
//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A CLASS For "Sc" Approach
//
//orderBy: on which column to sort
//
// Author :Arvind Singh Rawat
// Created on : (30.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getScClassBy($orderBy=' classId',$condition='') {
        global $REQUEST_DATA;
        global $sessionHandler;
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT classId FROM class WHERE $condition
		AND instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
        AND sessionId=".$sessionHandler->getSessionVariable('SessionId')."  ORDER BY $orderBy";
        echo $query;
		return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

*/
//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF TEACHING EMPLOYEE
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (30.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getTeacherData($isTeachingCondition='') {
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

        if($isTeachingCondition==''){
           $isTeaching1='AND emp.isTeaching = 1';
           $isTeaching2='AND e.isTeaching = 1';
        }
        else{
           $isTeaching1='';
           $isTeaching2='';
        }

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
			 $query = "	SELECT
							DISTINCT
                                emp.employeeId, employeeName AS employeeName1, employeeAbbreviation,
                                IF(IFNULL(employeeAbbreviation,'')='',employeeName,
                                CONCAT(employeeName,' (',employeeAbbreviation,')')) AS employeeName
					FROM	classes_visible_to_role cvtr,
							 ".TIME_TABLE_TABLE." tt,
							`group` gr,
							`employee` emp
					LEFT JOIN employee_can_teach_in ec ON emp.employeeId = ec.employeeId
					WHERE	cvtr.groupId = tt.groupId
					AND		tt.employeeId = emp.employeeId
					AND		(emp.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
					OR		ec.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
					AND		gr.classId = cvtr.classId
					AND		gr.groupId = cvtr.groupId
					AND		tt.toDate IS NULL
					AND		gr.classId IN ($insertValue)
					$isTeaching1
					AND		emp.isActive=1
					AND		cvtr.userId = $userId
					AND		cvtr.roleId = $roleId
							ORDER BY employeeName";
		}
		else {
			$query =
						"	SELECT
									DISTINCT
                                            e.employeeId, employeeName AS employeeName1, employeeAbbreviation,
                                            IF(IFNULL(employeeAbbreviation,'')='',employeeName,
                                            CONCAT(employeeName,' (',employeeAbbreviation,')')) AS employeeName
							FROM	`employee` e
							LEFT JOIN employee_can_teach_in ec ON e.employeeId = ec.employeeId
							WHERE (e.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
							OR ec.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
							$isTeaching2
							AND  e.isActive=1
							ORDER BY employeeName";
		}
		return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO CHECK VALID CLASS
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (09.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getValidClass($degree,$batchId,$studyperiodId) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$degreeArr = explode("-",$degree);

		$universityID	=	$degreeArr[0];
		$degreeID		=	$degreeArr[1];
		$branchID		=	$degreeArr[2];

        $query = "SELECT classId,className
        FROM class
        WHERE universityId = '$universityID' and degreeId = '$degreeID' and branchId = '$branchID' and batchId = '$batchId' and studyPeriodId = '$studyperiodId' and instituteId = '$instituteId' and sessionId = '$sessionId'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

    }
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF CITIES
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (13.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getCity($orderBy=' cityId',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM city $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF CITIES CORRESPONDING TO A STATE
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (16.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getCityState($condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT cityId,cityName FROM city $condition ORDER BY cityName";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF DESIGNATIONS
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (13.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getDesignation($orderBy=' designationId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM designation ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF UNIVERSITIES
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
//ModifiedBy: on which column to sort
//
// Author :Arvind
// Created on : (13.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
public function getUniversity($orderBy=' universityName') {
		global $sessionHandler;
		$instituteId = $employeeId = $sessionHandler->getSessionVariable('InstituteId');
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT distinct u.* FROM university u left join class cl on (cl.universityId = u.universityId AND cl.instituteId = $instituteId) ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
 //----------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF SubjectType
// Author :Arvind
// Created on : (17.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getSubjectTypeUniversity($condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT subjectTypeId,subjectTypeName FROM subject_type $condition ORDER BY subjectTypeName";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF SUBJECT TYPES
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
//ModifiedBy: on which column to sort
//
// Author :Arvind
// Created on : (13.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function getSubjectType($orderBy=' subjectTypeId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;
        $query = "SELECT
                        *
                  FROM
                        subject_type
                  WHERE
                       universityId = '1'
                  ORDER BY
                        $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

   public function getSubjectType2($orderBy=' subjectTypeId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;
        $query = "SELECT
                        a.subjectTypeId, concat(b.universityCode,'-',a.subjectTypeName) as subjectTypeName
                  FROM
                        subject_type a, university b
                  WHERE
                       a.universityId = b.universityId
                  ORDER BY
                        $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF EMPLOYEES
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
public function getEmployee($orderBy=' employeeName',$conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
         $query = "SELECT * FROM employee WHERE isActive=1 $conditions ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
}
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A FullName of employee
//orderBy: on which column to sort
// Created on : 4/19/2011
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
public function getEmployeeFullName($orderBy=' employeeName',$conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
         $query = "SELECT employeeId,employeeName,CONCAT('(',employeeCode,') ',employeeName,' ',lastName) AS employeeFullName
				FROM employee WHERE isActive=1 $conditions ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
}
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Institutes
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (13.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getInstitute($orderBy=' instituteCode') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM institute ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


    public function getEmployeeInstitutes($orderBy=' instituteCode') {
		global $sessionHandler;
		$employeeId = $sessionHandler->getSessionVariable('EmployeeId');
        $userId = $sessionHandler->getSessionVariable('UserId');
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        /*$query = "SELECT b.instituteId, b.instituteCode FROM institute b, employee_can_teach_in a where a.employeeId = '$employeeId' AND a.instituteId = b.instituteId ORDER BY $orderBy";*/
        $query = "SELECT
                        DISTINCT b.instituteId,
                        b.instituteCode
                  FROM
                        institute b, user_role a
                  WHERE
                        a.userId = '$userId'
                        AND a.instituteId = b.instituteId
                  ORDER BY $orderBy";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Institutes
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (13.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getEmployeeInstitute($instituteId) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        //$query = "SELECT * FROM institute WHERE instituteId NOT IN ($instituteId)";
		$query = "SELECT * FROM institute ORDER BY instituteId";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Institutes
// orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (13.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getEmployeeInstituteForCommonResources($conditions='',$orderBy=' instituteCode') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM institute $conditions ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Degree
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getDegree($orderBy=' degreeId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

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
			$query = "	SELECT
								distinct deg.degreeAbbr,
								deg.degreeId
						FROM	degree deg,
								class cl,
								classes_visible_to_role cvtr
						WHERE	cl.degreeId = deg.degreeId
						AND		cvtr.classId = cl.classId
						AND		cvtr.userId = $userId
						AND		cvtr.roleId = $roleId
						AND		cl.classId IN ($insertValue)
								ORDER BY $orderBy";
		}
		else {
			$query = "SELECT * FROM degree ORDER BY $orderBy";
		}
		//die;
        return $systemDatabaseManager->executeQuery($query,"Query: $query");


    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Degree
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getInstituteDegree($orderBy=' degreeId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

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
			$query = "	SELECT
								distinct deg.degreeAbbr,
								deg.degreeId
						FROM	degree deg,
								class cl,
								classes_visible_to_role cvtr
						WHERE	cl.degreeId = deg.degreeId
						AND		cvtr.classId = cl.classId
						AND		cvtr.userId = $userId
						AND		cvtr.roleId = $roleId
						AND		cl.classId IN ($insertValue)
						AND		cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
								ORDER BY $orderBy";
		}
		else {
			$query = "SELECT distinct deg.* FROM degree deg, class cl WHERE	cl.degreeId = deg.degreeId AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')." ORDER BY $orderBy";
		}
		//die;
        return $systemDatabaseManager->executeQuery($query,"Query: $query");


    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Quota
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (05.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getQuota($orderBy=' quotaId',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM quota $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Batch
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (27.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getBatch($orderBy=' batchName') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');


        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT
				 ba.batchId,
				 ba.batchName

                 FROM
                 `batch` ba
                 LEFT JOIN
                 `class` cls
                 ON
				 ba.batchId = cls.batchId AND
				 cls.isActive = 1 AND
				 cls.sessionId = '".$sessionId ."' WHERE
				 ba.instituteId = '".$instituteId."'
				 GROUP BY ba.batchId
				 ORDER BY

				 $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Study Period
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (27.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getStudyPeriod($orderBy=' studyPeriodId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		global $sessionHandler;
        //$query = "SELECT a.*, b.* FROM study_period a, periodicity b where a.periodicityId=b.periodicityId  ORDER BY $orderBy";
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

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
			$query = "	SELECT
									DISTINCT(SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-1)) as periodName, studyPeriodId
							FROM	`class` cl,
									classes_visible_to_role cvtr
							WHERE	cl.classId IN ($insertValue)
							AND		cvtr.classId = cl.classId
							AND		cvtr.userId = $userId
							AND		cvtr.roleId = $roleId
							AND		(isActive =1 OR isActive =3)
									GROUP BY studyPeriodId
									ORDER BY $orderBy";
		}
		else {
			$query = "SELECT

					 DISTINCT(SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-1)) as periodName, studyPeriodId
					 FROM
					 `class`
					 WHERE
					 (isActive =1 OR isActive =3)
					 GROUP BY studyPeriodId
					 ORDER BY studyPeriodId";
		}
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Periodicity
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (30.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getPeriodicity($orderBy=' periodicityCode') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM  periodicity ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Subject
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getSubject($orderBy=' subjectCode') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		global $sessionHandler;
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

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
			$query = "	SELECT
									distinct sub.subjectId,
									sub.subjectCode
							FROM	subject_to_class stc,
									subject sub,
									classes_visible_to_role cvtr,
									`group` g
							WHERE	stc.subjectId = sub.subjectId
							AND		cvtr.groupId = g.groupId
							AND		g.classId = cvtr.classId
							AND		stc.classId = cvtr.classId
							AND		cvtr.userId = $userId
							AND		cvtr.roleId = $roleId
							AND		g.classId IN ($insertValue)
									ORDER BY $orderBy";
		}
		else {
			$query = "SELECT * FROM subject ORDER BY $orderBy";
		}


        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }



//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function geClass($orderBy=' cls.degreeId,cls.branchId,cls.studyPeriodId') {
		global $sessionHandler;
		$systemDatabaseManager = SystemDatabaseManager::getInstance();

		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');




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
		$query = "	SELECT	distinct cls.classId,
							cls.className
					FROM	class cls,
							time_table_classes ttc,
							time_table_labels ttl,
							classes_visible_to_role cvtr
					WHERE 	cls.instituteId='".$instituteId."'
					AND 	cls.sessionId='".$sessionId."'
					AND 	cls.isActive IN(1,3)
					AND 	cls.classId = ttc.classId
					AND 	ttc.timeTableLabelId = ttl.timeTableLabelId

					AND		cvtr.classId = cls.classId
					AND		cvtr.classId = ttc.classId
					AND		cls.classId IN ($insertValue)
							ORDER BY $orderBy DESC";
		}
		else {

        $query = "	SELECT	*
					FROM	class cls,
							time_table_classes ttc,
							time_table_labels ttl
					WHERE 	cls.instituteId='".$instituteId."'
					AND		cls.sessionId='".$sessionId."'
					AND		cls.isActive IN(1,3)
					AND 	cls.classId = ttc.classId
					AND 	ttc.timeTableLabelId = ttl.timeTableLabelId

				 			ORDER BY $orderBy DESC";
		}
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class which are active and future
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function geAdmitClass($orderBy=' cls.className') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

		$systemDatabaseManager = SystemDatabaseManager::getInstance();

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
			$query = "SELECT cls.classId,cls.className
					 FROM
					 class cls,study_period sp, classes_visible_to_role cvtr
					 WHERE
					 cls.instituteId='".$instituteId."' AND
					 cls.sessionId='".$sessionId."' AND
					 cls.isActive =1  AND
					 cls.studyPeriodId = sp.studyPeriodId AND
					 cvtr.classId = cls.classId AND
					 cls.classId IN ($insertValue) AND
						 sp.periodValue IN(1,3,5)


					 ORDER BY $orderBy DESC";
			return $systemDatabaseManager->executeQuery($query,"Query: $query");
		}
		else {

		   $query = "SELECT cls.classId,cls.className
					 FROM
					 class cls,study_period sp

					 WHERE
					 cls.instituteId='".$instituteId."' AND
					 cls.sessionId='".$sessionId."' AND
					 cls.isActive =1  AND
					 cls.studyPeriodId = sp.studyPeriodId AND
						 sp.periodValue IN(1,3,5)


					 ORDER BY $orderBy DESC";
			return $systemDatabaseManager->executeQuery($query,"Query: $query");
		}
    }


 //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE CYCLE FINES
//
//orderBy: on which column to sort
//modified by: Rajeev Aggarwal
// Author :Arvind Singh Rawat
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getFeeCycleClasses($condition='',$orderBy=' c.branchId, c.studyPeriodId',$option='') {
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        if($orderBy=='') {
          $orderBy = " classId";
        }

        if($option=='') {
          $filedName = ' fc.feeCycleClassId, fc.feeCycleId, fc.classId, fc.instituteId, fc.sessionId, c.className, f.cycleName, f.cycleAbbr';
        }
        else {
          $filedName = ' f.feeCycleId, f.cycleName, f.cycleAbbr';
        }


        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT
                        DISTINCT $filedName
                  FROM
                        fee_cycle f, fee_cycle_class fc, class c
                  WHERE
                        f.feeCycleId = fc.feeCycleId AND
                        fc.classId = c.classId AND
                        fc.instituteId = f.instituteId AND
                        fc.instituteId = c.instituteId AND
                        fc.sessionId   = c.sessionId AND
                        fc.instituteId = '".$instituteId."' AND
                        fc.sessionId   = '".$sessionId."'
                  $condition
                  ORDER BY
                        $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE CYCLE FINES
//
//orderBy: on which column to sort
//modified by: Rajeev Aggarwal
// Author :Arvind Singh Rawat
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getFeeCycle($orderBy=' cycleName',$condition='') {
	global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM fee_cycle WHERE instituteid= '".$sessionHandler->getSessionVariable('InstituteId')."' $condition  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE CYCLE 
//orderBy: on which column to sort
// Author :Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.

//
//--------------------------------------------------------
    public function getFeeCycleNew($orderBy=' cycleName',$condition='') {
	    
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $query = "SELECT	
                        feeCycleId, cycleName, cycleAbbr, fromDate, toDate, status
                  FROM 	
                        `fee_cycle_new`
        		  WHERE	
                        instituteid= '".$sessionHandler->getSessionVariable('InstituteId')."'
        		        AND	sessionId = '".$sessionHandler->getSessionVariable('SessionId')."'
        		  $condition  
        		  ORDER BY 
                        $orderBy";
                        
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE HEAD
//
//orderBy: on which column to sort
//
// Author :Arvind Singh Rawat
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getFeeHead($orderBy=' headName',$condition='') {
        global $sessionHandler;
	    $systemDatabaseManager = SystemDatabaseManager::getInstance();

        if($orderBy=='') {
          $orderBy = 'headName';
        }

        $query = "SELECT
                        *
                  FROM
                        fee_head
                  WHERE
                        instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                  $condition
                  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE HEAD
//
//orderBy: on which column to sort
//
// Author :Arvind Singh Rawat
















// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getFeeConcession($orderBy=' categoryName',$condition='') {

        global $sessionHandler;

        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        if($orderBy=='') {
          $orderBy = 'categoryName';
        }

        $query = "SELECT
                       categoryId, categoryName, categoryOrder
                  FROM
                       fee_concession_category
                  $condition
                  ORDER BY
                       $orderBy";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE HEAD
//
//orderBy: on which column to sort
//
// Author :Arvind Singh Rawat
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getFeeHeadList($conditions='', $limit = '', $orderBy=' headName') {

        global $sessionHandler;

        //  IF(p.headName IS NULL,'".NOT_APPLICABLE_STRING."', p.headName) AS parentHead,
        //  c.headAbbr, c.isConsessionable, c.transportHead, c.hostelHead, c.miscHead,
        $query = "SELECT
                        c.feeHeadId, c.headName, c.headAbbr, c.sortingOrder, c.isRefundable, c.isVariable
                  FROM
                        `fee_head` c
                  $conditions
                  ORDER BY $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF ALL ALLOCATED FEE HEAD
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (26.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getAllocatedFeeHead($orderBy=' fh.headName') {
        global $sessionHandler;
	    $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT DISTINCT(headName), fh.feeHeadId
				 FROM fee_head fh, fee_head_student fhs
				 WHERE fh.feeHeadId = fhs.feeHeadId AND instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF evaluation criteria
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function geEvaluationCritieria($orderBy=' evaluationCriteriaId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM evaluation_criteria ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }



    //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Branch
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getBranch($orderBy=' branchCode') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		global $sessionHandler;
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

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
			 $query = "	SELECT
									distinct br.branchCode,
									br.branchId
							FROM	branch br,
									class cl,
									classes_visible_to_role cvtr
							WHERE	cl.branchId = br.branchId
							AND		cvtr.classId = cl.classId
							AND		cvtr.userId = $userId
							AND		cvtr.roleId = $roleId
							AND		cl.classId IN ($insertValue)
									ORDER BY $orderBy";
		}
		else {
			$query = "SELECT * FROM branch ORDER BY $orderBy";
		}
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

    //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Hostel Name
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function getHostelName($orderBy=' hostelId',$condition='') {
      $systemDatabaseManager = SystemDatabaseManager::getInstance();
      $query = "SELECT * FROM hostel $condition ORDER BY $orderBy";
      return $systemDatabaseManager->executeQuery($query,"Query: $query");
   }




//----------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF USER TYPES FROM ROLE TABLE
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (13.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getRole($orderBy=' roleId',$conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM role  $conditions ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }




//----------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF concatenated batch,university and branch from class table
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (02.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getConcatenateClass($orderBy=' classId') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		$sepratorLen = strlen(CLASS_SEPRATOR);
        $query = "SELECT
				  DISTINCT(substring_index(substring_index(classname,'".CLASS_SEPRATOR."',4),'".CLASS_SEPRATOR."',-3))  AS className ,
				  universityId,degreeId,branchId from class
				  WHERE
				  instituteId ='".$instituteId."' AND
				  sessionId='".$sessionId."' AND
				  isActive = 1
				  ORDER BY $orderBy";
		/*$query = "SELECT
				  DISTINCT(SUBSTRING((SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',4)),7+".$sepratorLen.")) AS className ,
				  universityId,degreeId,branchId from class
				  WHERE instituteId ='".$instituteId."' AND sessionId='".$sessionId."'
				  ORDER BY $orderBy";*/
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
//----------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF concatenated batch,university and branch from class table
//
//orderBy: on which column to sort
//
// Author :Ajinder Singh
// Created on : (09.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getSessionClasses($orderBy=' degreeId,branchId,studyPeriodId') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		$sepratorLen = strlen(CLASS_SEPRATOR);
        
        $classCondition = "";
        $classCondition1 = "";
        if($roleId>=2 && $roleId<=4) {
          $classCondition = " AND cl.isActive IN (1) ";
          $classCondition1 = " AND isActive IN (1) ";
        }
        else {
          $classCondition = " AND cl.isActive IN (1,3) ";  
          $classCondition1 = " AND isActive IN (1,3) ";
        }

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
		    $query = "	SELECT
								    DISTINCT cl.classId, cl.className,
								    substring_index( substring_index( cl.className, '-', 5 ) , '-', -5 ) AS className1
						    FROM	class cl, classes_visible_to_role cvtr
						    WHERE	cl.instituteId ='".$instituteId."'
						    AND		cl.sessionId='".$sessionId."'
						    AND		cvtr.classId = cl.classId
						    AND		cl.classId IN ($insertValue)
						    $classCondition
							ORDER BY $orderBy";
		    return $systemDatabaseManager->executeQuery($query,"Query: $query");
		}
		else {
			$query = "	SELECT
								classId, className,
								substring_index( substring_index( className, '-', 5 ) , '-', -5 ) AS className1
						FROM	class
						WHERE	instituteId ='".$instituteId."'
						AND		sessionId='".$sessionId."'
						$classCondition1
						ORDER BY $orderBy";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
		}
    }

//----------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF GROUP TYPE NAME FROM GROUP TYPE TABLE
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (02.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getGroupTypeName($orderBy=' groupTypeId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM group_type ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//----------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST BUS ROUTE
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (09.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getBusRoute($orderBy=' routeCode',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM bus_route $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//----------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST BUS STOP
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (09.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getBusStop($orderBy=' stopName',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM bus_stop $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Study Period
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (02.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getStudyPeriodName($orderBy=' studyPeriodId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
       $query = "SELECT * FROM  study_period ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Group
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getGroupParent($orderBy,$condition='') {
		if($orderBy == ''){
			$orderBy = 'groupId';
		}
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM `group` $condition  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


    public function getGroupParentClassName($orderBy=' groupId',$condition) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT g.*, c.className FROM `group` g, class c $condition  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------
// Purpose: to get the list of sessions
// Author:Pushpender Kumar Chauhan
// Params: nothing
// Returns: array
// Created on : (04.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
//--------------------------------------------------------
// Purpose: to make the list ordered by session year
// Modified By :Ajinder Singh
// Params: $mode=1 : Used to show all sessions | $mode=2 : used to show only active and past sessions
// Returns: array
// Created on : (01.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getSessionDetail($mode='1') {
        $query = "SELECT sessionName, abbreviation, sessionId,sessionYear FROM session ORDER BY $mode ASC";

        if($mode=='2'){
           $query = "SELECT
                           s.sessionId, s.sessionName,s.abbreviation,s.sessionYear
                     FROM
                           `session` s
                     WHERE
                           s.sessionId
                            IN (
                                SELECT
                                      DISTINCT sessionId
                                FROM
                                      class
                                WHERE
                                      isActive IN ( 1, 3 )
                                )
                     UNION
                           SELECT
                                s.sessionId, s.sessionName,s.abbreviation,s.sessionYear
                                FROM
                                    `session` s
                                WHERE
                                   s.sessionYear IN (
                                                     SELECT
                                                           sessionYear +1
                                                     FROM
                                                          `session`
                                                     WHERE
                                                          sessionId
                                                          IN (
                                                            SELECT
                                                                  DISTINCT sessionId
                                                            FROM
                                                                  class
                                                            WHERE
                                                                  isActive IN ( 1, 3 )
                                                            )
                                                       )
                     ORDER BY $mode ASC";
        }
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//--------------------------------------------------------
	// Purpose: to fetch active session
	// Modified By :Ajinder Singh
	// Params: --
	// Returns: array
	// Created on : (01.08.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	public function getActiveSession() {
		$query = "SELECT sessionName, abbreviation, sessionId FROM session WHERE active = 1 ORDER BY sessionId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//----------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET THE ROLE OF AN USER
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getUserRole() {
        require_once(LIB_PATH . "/Library/common.inc.php"); //for UserId
        global $sessionHandler;

        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $query = "SELECT roleName
                  FROM role,user
                  WHERE role.roleId=user.roleId AND user.userId=".$sessionHandler->getSessionVariable('UserId');
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Buildings
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getBuilding($orderBy=' buildingId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM building  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Block
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getBlock($orderBy=' blockId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM block  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }



//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET the userName of a user
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getUserName($orderBy=' userId',$condition) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM user $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET the userName of a user
//orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (01.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------
    public function getUserNameDetailed($orderBy='u.userId',$condition) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT
                         u.userId,u.userName,
                         IF(u.displayName IS NULL OR u.displayName='','Admin',u.displayName) AS displayName ,
                         r.roleId,r.roleName,r.roleType
                  FROM
                         user u,role r
                         WHERE u.roleId=r.roleId
                         $condition
                         ORDER BY $orderBy";

		return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF attendance codes
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (18.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getAttendanceCode($conditions='',$orderBy=' attendanceCodeId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        $query = "SELECT * FROM attendance_code $conditions  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FeeFundAllocation
//
//orderBy: on which column to sort
//
// Author :Arvind Singh Rawat
// Created on : (17.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getFeeFundAllocation($orderBy=' allocationEntity',$condition) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM fee_fund_allocation $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF All Classes in CURRENT SESSION
//
//orderBy: on which column to sort
//
// Author :Ajinder Singh
// Created on : (22.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getClassWithStudyPeriod($orderBy=' className') {
		global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
	
	$classCondition = "";
        if($roleId>=2 && $roleId<=4) {
          $classCondition = " AND c.isActive IN (1) ";
        }
        else {
          $classCondition = " AND c.isActive IN (1,3) ";  
        }
        
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
            $hodCondition = " AND  cvtr.classId = c.classId
                              AND  cvtr.userId = $userId
                              AND  cvtr.roleId = $roleId
                              AND  cvtr.classId IN $insertValue ";
        }

    $query = "SELECT
                        distinct c.classId, c.degreeId, c.branchId, c.studyPeriodId, c.className
                  FROM
                        class c $tableName
                  WHERE
                        c.sessionId='".$sessionHandler->getSessionVariable('SessionId')."' AND
                        c.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                     $classCondition
                  $hodCondition
                  ORDER BY c.degreeId,c.branchId,c.studyPeriodId";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF All Classes in CURRENT SESSION
//
//orderBy: on which column to sort
//
// Author :Ajinder Singh
// Created on : (22.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getPromotedClassWithStudyPeriod($orderBy=' className') {
		global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "
				SELECT			DISTINCT(a.classId), a.className
				FROM			class a, test_transferred_marks b
				WHERE			a.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
				AND				a.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
				AND				a.isActive = 1
				AND				a.classId = b.classId
				ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A RETURN CURRENT SESSION CLASSES WITH CLASS NAMES
//
//orderBy: on which column to sort
//
// Author :Ajinder Singh
// Created on : (24.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function getCurrentSessionClasses($orderBy = ' class.degreeId,class.branchId,class.studyPeriodId') {
		global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT classId, className FROM class WHERE sessionId='".$sessionHandler->getSessionVariable('SessionId')."' AND instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' AND isActive = 1 ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF BOTTOM LEVEL GROUPS
//
//selected: which element in the select element to be selected
//
// Author :Ajinder Singh
// Created on : (25.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
	public function getLastLevelGroups($orderBy = '',$condition='') {

		global $sessionHandler;
		$systemDatabaseManager = SystemDatabaseManager::getInstance();

		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

		$systemDatabaseManager = SystemDatabaseManager::getInstance();

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
			$query = "	SELECT
								a.groupId,
								a.groupName,
								a.groupShort,
								COUNT(b.groupId) AS children
						FROM	classes_visible_to_role cvtr, `group` a
								LEFT JOIN `group` b ON (a.groupId =  b.parentGroupId)
						WHERE	cvtr.groupId = a.groupId
								$condition GROUP BY a.groupId HAVING children=0 ORDER BY $orderBy";
			return $systemDatabaseManager->executeQuery($query,"Query: $query");

		}
		else {
			$query = "SELECT a.groupId, a.groupName, a.groupShort, COUNT(b.groupId) AS children FROM `group` a LEFT JOIN `group` b ON (a.groupId =  b.parentGroupId) WHERE 1 $condition GROUP BY a.groupId HAVING children=0 ORDER BY $orderBy";
			return $systemDatabaseManager->executeQuery($query,"Query: $query");
		}
	}

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF BOTTOM LEVEL GROUPS
//
//selected: which element in the select element to be selected
//
// Author :Ajinder Singh
// Created on : (25.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
	public function getLastLevelTypeGroups($orderBy = '',$groupTypeId, $condition='') {
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = "SELECT a.groupId, a.groupName, a.groupShort, COUNT(b.groupId) AS children FROM `group` a LEFT JOIN `group` b ON (a.groupId =  b.parentGroupId and b.groupTypeId = $groupTypeId) $condition GROUP BY a.groupId HAVING children=0 ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}


//-------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF ALL LEVEL GROUPS
//
//selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (09.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------
	public function getAllCurrentGroups($orderBy = '',$condition='') {

        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        $roleName = $sessionHandler->getSessionVariable('RoleName'); 
        
        
        $classCondition1 = " AND c.isActive IN (1,3) ";
        
        $tableName = "";
        $insertValue = '';
        $classCondition1 ='';
        $cnt = 0;
        if($roleId!=1) { 
            $query = "SELECT
                            DISTINCT cvtr.classId
                      FROM
                            classes_visible_to_role cvtr, class cc
                      WHERE  
                            cc.classId = cvtr.classId AND
                            cvtr.userId = $userId AND
                            cvtr.roleId = $roleId AND
                            cc.instituteId = $instituteId AND
                            cc.sessionId = $sessionId  ";

            $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
            $cnt = count($result);
            
            $insertValue = "0";
            for($i=0;$i<$cnt; $i++) {
              $insertValue .= ",".$result[$i]['classId'];
            }
        }
        
        if($cnt > 0) {
          $classCondition1 = " AND cls.classId IN  ($insertValue) ";  
        }
        
        $query = "SELECT
                        DISTINCT 
                           cls.className, grp.groupId, grp.parentGroupId,
                           IF(grp.parentGroupId=0,grp.groupName,CONCAT('".NOT_APPLICABLE_STRING."',grp.groupName)) AS groupName 
                  FROM    
                        class cls, `group` grp 
                  WHERE    
                        grp.classId = cls.classId AND
                        cls.instituteId = '$instituteId' AND
                        cls.sessionId = '$sessionId'   
                        $classCondition1
                        $condition
                  ORDER BY 
                        cls.className, grp.groupId";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

	function traverseCat($id,$level) {
		global $sessionHandler;
		$level++;
		$idArr = array();
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

		$categoryArr = array();
		$tempArr = array();
		$systemDatabaseManager = SystemDatabaseManager::getInstance();

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
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
		if ($insertValue) {
			$conditions = "AND classId NOT IN ($insertValue)";
		}

		$query = "SELECT groupId,groupName,parentGroupId FROM `group` WHERE parentGroupId=$id $conditions";
		$result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
		$cnt = count($result);

		for($i=0;$i<$cnt;$i++) {

			   $groupId = $result[$i]['groupId'];
			   $parentID =  $result[$i]['parentGroupId'];
			   $groupName = $result[$i]['groupName'];

			 $spacer = "";

			 for($cntr=0;$cntr<$level;$cntr++)
				// $spacer .= "&nbsp;&nbsp;";
			 $tempArr[$groupId] = $spacer . "--$groupName";
			 $idArr = $this->mergeArray($idArr,$tempArr);
			 $tempArr = $this->traverseCat($groupId,$level);
			 $idArr = $this->mergeArray($idArr,$tempArr);
		}
		return $idArr;

	}

	function mergeArray($arr1,$arr2) {
		$arr3 = array();
		foreach($arr1 as $key => $val)
			$arr3[$key] = $val;
		foreach($arr2 as $key => $val)
			$arr3[$key] = $val;
		return $arr3;
	}


//-------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF ALL LEVEL Categories
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (15.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------
    public function getAllCurrentCategories($orderBy = ' quotaName',$condition='', $showParentCat='') {

        global $sessionHandler;
        $categoryArr = array();
        $tempArr = array();
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $query = "SELECT quotaId,quotaName FROM `quota` $condition ORDER BY $orderBy";
        $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
        $cnt = count($result);

        for($i=0;$i<$cnt;$i++) {

            $quotaId   = $result[$i]['quotaId'];
            $quotaName = $result[$i]['quotaName'];

            $parentQuery = "SELECT quotaId,quotaName,parentQuotaId FROM `quota` WHERE parentQuotaId=$quotaId ORDER BY quotaName";
            $parentResult =  $systemDatabaseManager->executeQuery($parentQuery,"Query: $parentQuery");
            if(count($parentResult)==0) {
              $categoryArr[$quotaId] = "$quotaName";
            }

            $tempArr = "";
            $tempArr = $this->traverseQuotaCat($quotaId,0,$showParentCat,$quotaName);
            $categoryArr = $this->mergeArray($categoryArr, $tempArr);
        }

        return $categoryArr;
    }

    function traverseQuotaCat($id,$level,$showParentCat,$tquotaName)
    {
        $level++;
        $idArr = array();
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $query = "SELECT quotaId,quotaName,parentQuotaId FROM `quota` WHERE parentQuotaId=$id ORDER BY quotaName";
        $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
        $cnt = count($result);

        for($i=0;$i<$cnt;$i++) {
             $quotaId = $result[$i]['quotaId'];
             $parentID =  $result[$i]['parentQuotaId'];
             $quotaName = $result[$i]['quotaName'];
             $spacer = "";
             for($cntr=0;$cntr<$level;$cntr++)
                $tempArr[$quotaId] = $tquotaName . "--&nbsp;$quotaName";
              //if($showParentCat=='') {
              //   $spacer .= "&nbsp;&nbsp;";
              //   $tempArr[$quotaId] = $spacer . "--&nbsp;$quotaName";
              //}
             $idArr = $this->mergeArray($idArr,$tempArr);
             $tempArr = $this->traverseQuotaCat($quotaId,$level,$showParentCat,$tquotaName);
             $idArr = $this->mergeArray($idArr,$tempArr);
        }
        return $idArr;
    }

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STUDENT ATTENDANCE
//
//
// Author :Jaineesh
// Created on : (25.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------

public function getAttendance($condition='',$orderBy=' s.firstName'){

    global $sessionHandler;
	$query="SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName,su.subjectName, su.subjectCode,
    SUM(IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ) ) )  AS attended,
    SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) ) AS delivered, att.toDate, att.fromDate
    FROM student s
    INNER JOIN attendance att ON att.studentId = s.studentId
    LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." )
    INNER JOIN subject su ON su.subjectId = att.subjectId
    INNER JOIN class c ON c.classId = s.classId AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')." AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." $condition
    GROUP BY att.subjectId, att.studentId
    ORDER BY $orderBy ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


//--------------------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (11.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------------------------------------------------------
    public function getFormattedClass($orderBy=' classId') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $sepratorLen = strlen(CLASS_SEPRATOR);
        $query = "SELECT
                  classId,
                  (substring_index(classname,'".CLASS_SEPRATOR."',-4))  AS className
                  FROM
                  class
                  WHERE instituteId ='".$instituteId."' AND sessionId='".$sessionId."'
                  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STUDENT ATTENDANCE FOR SC Modules
//
//
// Author :Arvind Singh Rawat
// Created on : (13-08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------

public function getScAttendance($condition='',$orderBy=' scs.firstName'){

    global $sessionHandler;
    $query="SELECT CONCAT(scs.firstName,' ',scs.lastName) AS studentName,su.subjectName, su.subjectCode,
    FORMAT( SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ) ) ) , 1 ) AS attended,
    SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) ) AS delivered, att.toDate, att.fromDate
    FROM sc_student scs
    INNER JOIN sc_attendance att ON att.studentId = scs.studentId
    LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
    INNER JOIN subject su ON su.subjectId = att.subjectId
    INNER JOIN class c ON c.classId = scs.classId AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')." AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." $condition
    GROUP BY att.subjectId, att.studentId
    ORDER BY $orderBy ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Subject codes from active classes
//
//
// Author :Arvind Singh Rawat
// Created on : (13-09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------

 public function getSubjectsWithCode() {
      global $sessionHandler;
      $instituteId = $sessionHandler->getSessionVariable('InstituteId');
      $sessionId = $sessionHandler->getSessionVariable('SessionId');

	  $query = "
				SELECT
							DISTINCT(a.subjectId),
							b.subjectCode AS subjectName
				FROM		subject_to_class a, subject b, class c
				WHERE		a.subjectId = b.subjectId
				AND			a.classId = c.classId
				AND			c.isActive=1
                AND         c.instituteId = $instituteId
                AND         c.sessionId = $sessionId
				ORDER BY	subjectName";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Subject codes for which tests have been taken
//
//
// Author :Ajinder Singh
// Created on : (13-10-2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
 public function getTestSubjectsWithCode() {
	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId = $sessionHandler->getSessionVariable('SessionId');
    $query = "
				SELECT
							DISTINCT(a.subjectId),
							b.subjectCode AS subjectName
				FROM		sc_test a, subject b
				WHERE		a.subjectId = b.subjectId
				AND			a.instituteId = $instituteId
				AND			a.sessionId = $sessionId
				ORDER BY	subjectName";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF sections
//
//
// Author :Arvind Singh Rawat
// Created on : (13-09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
  public function getSectionList() {
		$query = "
					SELECT
								sectionId, sectionName, sectionType
					FROM		sc_section
					ORDER BY	sectionType, sectionName";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF sections
//
//
// Author :Ajinder Singh
// Created on : (22-09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
  public function getSectionAbbr() {
	  $query = "SELECT
							sectionId,
							CONCAT(abbr,' ',sectionType) AS sectionName
				FROM		sc_section
				ORDER BY	sectionType, abbr";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

//----------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF time table labels
//
// orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getTimeTableLabel($condition='', $orderBy='timeTableLabelId') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$userId= $sessionHandler->getSessionVariable('UserId');

		$systemDatabaseManager = SystemDatabaseManager::getInstance();

		$query = "	SELECT
							distinct cvtr.classId
					FROM	classes_visible_to_role cvtr
					WHERE	cvtr.userId = $userId";

		$result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
		$count = count($result);

		if ($count >0 ) {
			$query = "SELECT
						    timeTableLabelId, labelName, startDate, endDate, isActive, timeTableType
				      FROM
                            time_table_labels
				      WHERE
                            instituteId ='".$instituteId."'
				            AND	sessionId='".$sessionId."'

			          $condition
                      ORDER BY $orderBy";
		}
		else {
		    $query = "SELECT
						    timeTableLabelId, labelName, startDate, endDate, isActive, timeTableType
				      FROM
                            time_table_labels
				      WHERE
                            instituteId ='".$instituteId."'
						    AND	sessionId='".$sessionId."'
				      $condition
                      ORDER BY $orderBy";
		}

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF time table labels for teachers as they can see only active and past
// classes time table
// orderBy: on which column to sort
// Author :Dipanjan Bhattacharje
// Created on : (31.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------------------
    public function getTimeTableLabelForTeachers($condition='', $orderBy=' timeTableLabelId') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

            $query = "SELECT
                            DISTINCT t.timeTableLabelId,
                            t.labelName,
                            t.isActive
                    FROM
                            time_table_labels t,time_table_classes  tc,class c
                    WHERE
                            t.timeTableLabelId=tc.timeTableLabelId
                            AND tc.classId=c.classId
                            AND c.isActive IN (1,3)
                            AND t.instituteId ='".$instituteId."'
                            AND t.sessionId='".$sessionId."'
                            $condition
                    ORDER BY $orderBy
                    ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }




public function countStudentAttendance($studentId,$classId='',$where) {
     if ($classId != "" and $classId != "0") {
			$classCond =" and sg.classId = $classId ";
		   }
    global $sessionHandler;
    $query="
			SELECT
						COUNT(*) as totalRecords
			FROM		class c,
						study_period sp,
						periodicity p,
						student s
			INNER JOIN	".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
			LEFT JOIN	attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
			INNER JOIN	`group` gr ON gr.groupId = att.groupId
			INNER JOIN	subject su ON su.subjectId = att.subjectId
			INNER JOIN	employee emp ON emp.employeeId = att.employeeId
			AND			s.studentId = $studentId
			where
						c.classId in (
						  select distinct sg.classId
						  from student_groups sg,
						  `group` gr
						  where sg.studentId=$studentId
						  AND sg.groupId=gr.groupId
						  $classCond
						  )
			AND			sp.studyPeriodId = c.studyPeriodId
			AND			p.periodicityId = sp.periodicityId
			AND			c.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
			AND			c.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
			AND			att.classId = c.classId
			$where
            GROUP BY att.subjectId, att.groupId, emp.employeeId ";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STUDENT ATTENDANCE FOR SC Modules
//
//
// Author :Jaineesh
// Created on : (04-12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------

  public function getStudentAttendance($studentId,$classId='',$limit='',$where='', $orderBy = ''){
           if ($classId != "" and $classId != "0") {
			$classCond =" and sg.classId IN ($classId) ";
		   }
		    if ($classId != "" and $classId != "0") {
			$classCond1 =" and classId IN ($classId) ";
		   }
    global $sessionHandler;
    /*
    $query="
			SELECT
						CONCAT( s.firstName , ' ' , s.lastName ) AS studentName ,
						CONCAT( su.subjectName , ' (' , su.subjectCode , ')' ) as subject ,
						gr.groupName,
						emp.employeeName ,
						sp.periodName ,
						ROUND( SUM( IF( att.isMemberOfClass = 0 , 0 , IF( att.attendanceType = 2 , ( ac.attendanceCodePercentage / 100 ) , att.lectureAttended ) ) ) , 2 ) AS attended ,
						SUM( IF( att.isMemberOfClass = 0 , 0 , att.lectureDelivered ) ) AS delivered ,
						MIN(fromDate) AS fromDate ,
						MAX(toDate) AS toDate,
						leavesTaken AS dutyLeave,
                        SUBSTRING_INDEX(c.className,'-',-3) AS className
			FROM		class c,
						study_period sp,
						periodicity p,
						attendance_leave al,
						student s
			INNER JOIN	".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
			LEFT JOIN	attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
			INNER JOIN	`group` gr ON gr.groupId = att.groupId
			INNER JOIN	subject su ON su.subjectId = att.subjectId
			INNER JOIN	employee emp ON emp.employeeId = att.employeeId
			AND			s.studentId = $studentId
			where
						c.classId in (
						  select distinct sg.classId
						  from student_groups sg,
						  `group` gr
						  where sg.studentId=$studentId
						  AND sg.groupId=gr.groupId
						  $classCond
						  )
			AND			sp.studyPeriodId = c.studyPeriodId
			AND			p.periodicityId = sp.periodicityId
			AND			c.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
			AND			c.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
			AND			att.classId = c.classId
			AND			al.studentId = s.studentId
			AND			al.classId = c.classId
			AND			al.subjectId=su.subjectId
			AND			al.groupId=gr.groupId
						$where
						GROUP BY att.subjectId, att.groupId, emp.employeeId
						ORDER BY $orderBy
						$limit
						 ";
     */

     $query="SELECT
                    t.employeeId, t.subjectId, t.classId, t.studentId, t.groupId, t.subjectName, t.subjectCode,
                    t.studentName, t.subject, t.groupName, t.employeeName, t.periodName,
                    t.attended, t.delivered, t.fromDate, t.toDate, t.className, t.dutyLeave
             FROM(
                    SELECT
                        tt.employeeId, tt.subjectId, tt.classId, tt.studentId, tt.groupId,   tt.subjectName, tt.subjectCode,
                        tt.studentName, tt.subject, tt.groupName, tt.employeeName, tt.periodName,
                        SUM(tt.attended) AS attended, SUM(tt.delivered) AS delivered, MIN(tt.fromDate) AS fromDate, MAX(tt.toDate) AS toDate,
                        tt.className, SUM(tt.dutyLeave) AS dutyLeave
                    FROM
                        (
                            SELECT
                                        att.employeeId, att.subjectId, att.classId, att.studentId, att.groupId,
                                        CONCAT( s.firstName , ' ' , s.lastName ) AS studentName ,
                                        CONCAT( su.subjectName , ' (' , su.subjectCode , ')' ) as subject ,
                                        su.subjectName AS subjectName,
                                        su.subjectCode AS subjectCode,
                                        gr.groupName,
                                        emp.employeeName ,
                                        sp.periodName ,
                                        ROUND( SUM( IF( att.isMemberOfClass = 0 , 0 , IF( att.attendanceType = 2 , ( ac.attendanceCodePercentage / 100 ) , att.lectureAttended ) ) ) , 2 ) AS attended ,
                                        SUM( IF( att.isMemberOfClass = 0 , 0 , att.lectureDelivered ) ) AS delivered ,
                                        MIN(fromDate) AS fromDate ,
                                        MAX(toDate) AS toDate,
                                        ( SELECT
                                                COUNT(dl.dutyLeaveId)
                                          FROM
                                               ".ATTENDANCE_TABLE." att1,   ".DUTY_LEAVE_TABLE."  dl
                                          WHERE
                                               dl.studentId = att.studentId AND
                                               dl.subjectId = att.subjectId AND

                                               dl.groupId = att.groupId AND
                                               dl.classId = att1.classId  AND dl.studentId = att1.studentId
                                               AND dl.subjectId = att1.subjectId  AND dl.groupId = att1.groupId
                                               AND dl.periodId = att1.periodId AND dl.rejected = ".DUTY_LEAVE_APPROVE."
                                               AND dl.dutyDate = att1.fromDate AND dl.dutyDate = att.toDate
                                        ) AS dutyLeave,
                                        SUBSTRING_INDEX(c.className,'-',-3) AS className
                            FROM        class c,
                                        study_period sp,
                                        periodicity p,
                                        student s
                            INNER JOIN    ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                            LEFT JOIN    attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
                            INNER JOIN    `group` gr ON gr.groupId = att.groupId
                            INNER JOIN    subject su ON su.subjectId = att.subjectId
                            INNER JOIN    employee emp ON emp.employeeId = att.employeeId
                            AND            s.studentId = $studentId
                            where
                                        c.classId in (
                                          select distinct sg.classId
                                          from student_groups sg,
                                          `group` gr
                                          where sg.studentId=$studentId
                                          AND sg.groupId=gr.groupId
                                          $classCond
                                          )
                            AND            sp.studyPeriodId = c.studyPeriodId
                            AND            p.periodicityId = sp.periodicityId
                            AND            c.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
                            AND            c.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                            AND            att.classId = c.classId
                                        $where
                            GROUP BY
                                    att.subjectId, att.groupId, emp.employeeId, att.fromDate, att.toDate ) AS tt
             GROUP BY
                    tt.subjectId, tt.groupId, tt.employeeId) AS t
             ORDER BY
                    $orderBy
             $limit";

	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }




//--------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF STUDENT ATTENDANCE Irrespective of groups
// Author :Dipanjan Bhattacharjee
// Created on : (06-10.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------

public function countConsolidatedStudentAttendance ($studentId,$classId='',$where) {
     if ($classId != "" and $classId != "0") {
            $classCond =" and sg.classId = $classId ";
           }
    global $sessionHandler;
    $query="
            SELECT
                        COUNT(*) as totalRecords
            FROM        class c,
                        study_period sp,
                        periodicity p,
                        student s
            INNER JOIN    ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
            LEFT JOIN    attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
            INNER JOIN    `group` gr ON gr.groupId = att.groupId
            INNER JOIN    subject su ON su.subjectId = att.subjectId
            INNER JOIN    employee emp ON emp.employeeId = att.employeeId
            AND            s.studentId IN ( $studentId )
            where
                        c.classId in (
                          select distinct sg.classId
                          from student_groups sg,
                          `group` gr
                          where sg.studentId IN ( $studentId )
                          AND sg.groupId=gr.groupId
                          $classCond
                          )
            AND            sp.studyPeriodId = c.studyPeriodId
            AND            p.periodicityId = sp.periodicityId
            AND            c.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
            AND            c.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."

            AND            att.classId = c.classId
                           $where
                           GROUP BY att.subjectId
            ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//--------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF STUDENT ATTENDANCE Irrespective of groups
// Author :Dipanjan Bhattacharjee
// Created on : (06-10.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------

public function getConsolidatedStudentAttendance($studentId,$classId='',$limit='',$where='', $orderBy = ''){

  /*
          if ($classId != "" and $classId != "0") {
            $classCond =" and sg.classId = $classId ";
            $dutyLeavesCond=" AND al.classId = $classId ";
           }
    global $sessionHandler;
 $query="
            SELECT
                        s.studentId,
                        CONCAT( s.firstName , ' ' , s.lastName ) AS studentName ,
                        CONCAT( su.subjectName , ' (' , su.subjectCode , ')' ) as subject ,
                        su.subjectCode,
                        sp.periodName ,
                        ROUND( SUM( IF( att.isMemberOfClass = 0 , 0 , IF( att.attendanceType = 2 , ( ac.attendanceCodePercentage / 100 ) , att.lectureAttended ) ) ) , 2 ) AS attended ,
                        SUM( IF( att.isMemberOfClass = 0 , 0 , att.lectureDelivered ) ) AS delivered ,
                        MIN(fromDate) AS fromDate ,
                        MAX(toDate) AS toDate,
                        gr.groupName,
                        emp.employeeName,
						(
							  SELECT
									SUM(leavesTaken)
							  FROM
									attendance_leave al
							  WHERE
								   al.studentId = s.studentId
								   AND al.classId=c.classId
								   AND al.subjectId=su.subjectId
								   AND al.studentId IN ( $studentId )
								   $dutyLeavesCond
						) AS leavesTaken,
                        SUBSTRING_INDEX(c.className,'-',-3) AS className
            FROM        class c,
                        study_period sp,
                        periodicity p,
                        student s
            INNER JOIN    ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
            LEFT JOIN    attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
            INNER JOIN    `group` gr ON gr.groupId = att.groupId
            INNER JOIN    subject su ON su.subjectId = att.subjectId
            INNER JOIN    employee emp ON emp.employeeId = att.employeeId
            AND            s.studentId IN ( $studentId )
            where
                        c.classId in (
                          select distinct sg.classId
                          from student_groups sg,
                          `group` gr
                          where sg.studentId IN ( $studentId )
                          AND sg.groupId=gr.groupId
                          $classCond




                          )
            AND            sp.studyPeriodId = c.studyPeriodId
            AND            p.periodicityId = sp.periodicityId
            AND            c.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
            AND            c.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
            AND            att.classId = c.classId
                           $where
                           GROUP BY att.subjectId
                           $orderBy
                           $limit
            ";
      */

      if ($classId != "" and $classId != "0") {
            $classCond =" and sg.classId = $classId ";
            //$dutyLeavesCond=" AND al.classId = $classId ";
      }
    global $sessionHandler;

    $query="SELECT
                    t.subjectId, t.classId, t.studentId,
                    t.studentName, t.subject,  t.periodName,  t.subjectName, t.subjectCode,
                    t.attended, t.delivered, t.fromDate, t.toDate, t.className, t.dutyLeave
             FROM(
                    SELECT
                        tt.subjectId, tt.classId, tt.studentId, tt.subjectName, tt.subjectCode,
                        tt.studentName, tt.subject, tt.periodName,
                        SUM(tt.attended) AS attended, SUM(tt.delivered) AS delivered, MIN(tt.fromDate) AS fromDate, MAX(tt.toDate) AS toDate,
                        tt.className, SUM(tt.dutyLeave) AS dutyLeave
                    FROM
                        (
                            SELECT
                                        att.subjectId, att.classId, att.studentId, att.groupId,
                                        CONCAT( s.firstName , ' ' , s.lastName ) AS studentName ,
                                        CONCAT( su.subjectName , ' (' , su.subjectCode , ')' ) as subject ,
                                        su.subjectName AS subjectName,
                                        su.subjectCode AS subjectCode,
                                        gr.groupName, sp.periodName ,
                                        ROUND( SUM( IF( att.isMemberOfClass = 0 , 0 , IF( att.attendanceType = 2 , ( ac.attendanceCodePercentage / 100 ) , att.lectureAttended ) ) ) , 2 ) AS attended ,
                                        SUM( IF( att.isMemberOfClass = 0 , 0 , att.lectureDelivered ) ) AS delivered ,
                                        MIN(fromDate) AS fromDate ,
                                        MAX(toDate) AS toDate,
                                        (SELECT
                                                SUM(IF(att1.isMemberOfClass=0,0,IF(att1.attendanceType=2,1,0))) AS dutyLeave
                                         FROM
                                                 ".DUTY_LEAVE_TABLE."  dl, ".ATTENDANCE_TABLE." att1
                                                LEFT JOIN  attendance_code ac1 ON
                                                (ac1.attendanceCodeId = att1.attendanceCodeId AND ac1.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
                                         WHERE
                                               dl.studentId = att.studentId AND
                                               dl.subjectId = att.subjectId AND
                                               dl.groupId = att.groupId AND
                                               dl.classId = att1.classId  AND dl.studentId = att1.studentId
                                               AND dl.subjectId = att1.subjectId  AND dl.groupId = att1.groupId
                                               AND dl.periodId = att1.periodId AND dl.rejected = ".DUTY_LEAVE_APPROVE."
                                               AND dl.dutyDate = att1.fromDate AND dl.dutyDate = att.toDate
                                        )AS dutyLeave,
                                        SUBSTRING_INDEX(c.className,'-',-3) AS className
                            FROM        class c,
                                        study_period sp,
                                        periodicity p,
                                        student s
                            INNER JOIN    ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                            LEFT JOIN    attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
                            INNER JOIN    `group` gr ON gr.groupId = att.groupId
                            INNER JOIN    subject su ON su.subjectId = att.subjectId
                            INNER JOIN    employee emp ON emp.employeeId = att.employeeId
                            AND            s.studentId IN($studentId)
                            where
                                        c.classId in (
                                          select distinct sg.classId
                                          from student_groups sg,
                                          `group` gr
                                          where sg.studentId IN($studentId)
                                          AND sg.groupId=gr.groupId
                                          $classCond
                                          )
                            AND            sp.studyPeriodId = c.studyPeriodId
                            AND            p.periodicityId = sp.periodicityId
                            AND            c.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
                            AND            c.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                            AND            att.classId = c.classId
                                        $where
                            GROUP BY
                                    att.classId, att.studentId, att.subjectId, att.groupId, att.fromDate, att.toDate ) AS tt
             GROUP BY
                    tt.classId, tt.studentId, tt.subjectId) AS t
             $orderBy
             $limit";


   /*
    $query="
            SELECT
                        s.studentId,
                        CONCAT( s.firstName , ' ' , s.lastName ) AS studentName ,
                        CONCAT( su.subjectName , ' (' , su.subjectCode , ')' ) as subject ,
                        su.subjectCode,
                        sp.periodName ,
                        ROUND( SUM( IF( att.isMemberOfClass = 0 , 0 , IF( att.attendanceType = 2 , ( ac.attendanceCodePercentage / 100 ) , att.lectureAttended ) ) ) , 2 ) AS attended ,
                        SUM( IF( att.isMemberOfClass = 0 , 0 , att.lectureDelivered ) ) AS delivered ,
                        MIN(fromDate) AS fromDate ,
                        MAX(toDate) AS toDate,
                        gr.groupName,
                        emp.employeeName,
                        (
                          SELECT
                                COUNT(dl.dutyLeaveId)
                          FROM
                                 ".DUTY_LEAVE_TABLE."  dl
                          WHERE
                                dl.classId = att.classId
                                AND dl.studentId = att.studentId
                                AND dl.subjectId = att.subjectId
                                AND dl.rejected = ".DUTY_LEAVE_APPROVE."
                                AND dl.groupId = att.groupId
                        ) AS dutyLeave,
                        SUBSTRING_INDEX(c.className,'-',-3) AS className
            FROM        class c,
                        study_period sp,
                        periodicity p,
                        student s
            INNER JOIN    ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
            LEFT JOIN    attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
            INNER JOIN    `group` gr ON gr.groupId = att.groupId
            INNER JOIN    subject su ON su.subjectId = att.subjectId
            INNER JOIN    employee emp ON emp.employeeId = att.employeeId
            AND            s.studentId IN ( $studentId )
            where
                        c.classId in (
                          select distinct sg.classId
                          from student_groups sg,
                          `group` gr
                          where sg.studentId IN ( $studentId )
                          AND sg.groupId=gr.groupId
                          $classCond
                          )
            AND            sp.studyPeriodId = c.studyPeriodId
            AND            p.periodicityId = sp.periodicityId
            AND            c.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
            AND            c.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
            AND            att.classId = c.classId
                           $where
                           GROUP BY att.subjectId
                           $orderBy
                           $limit
            ";
     */
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF testType
//
//
// Author :Arvind Singh Rawat
// Created on : (22-10.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
  public function getTestType($selected='',$conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
       $query = "
                    SELECT
							tt.testTypeId,
							ttc.testTypeName,
							tt.testTypeCode,
							tt.testTypeAbbr,
							tt.subjectId
                    FROM    test_type tt,
							test_type_category ttc
					WHERE	instituteId = $instituteId
					AND		tt.testTypeCategoryId = ttc.testTypeCategoryId
							$conditions
							ORDER BY testTypeName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Subject codes from active classes for which marks have been transferred
//
//
// Author :Ajinder Singh
// Created on : 21-oct-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
 public function getMarksTransferredSubjectsWithCode() {
    global $sessionHandler;
	  $query = "
				SELECT
							DISTINCT(a.subjectId),
							b.subjectCode AS subjectName
				FROM		test_transferred_marks a, subject b, class c
				WHERE		a.subjectId = b.subjectId
				AND			a.classId = c.classId
				AND			c.isActive=1
				AND			c.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
				AND			c.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
				ORDER BY	subjectName";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Subject codes from active classes for which marks have been transferred
//
//
// Author :Ajinder Singh
// Created on : 21-oct-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
 public function getHistogramLabels() {
    global $sessionHandler;
	  $query = "
				SELECT
							DISTINCT(a.histogramId),
							a.histogramLabel
				FROM		sc_histogram_labels a, sc_histogram_scales b
				WHERE		a.histogramId = b.histogramId
				AND			a.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
				AND			a.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
				ORDER BY	a.histogramLabel";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF HISTOGRAM LABEL
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (22.10.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getHistogramLabel($orderBy='histogramId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "	SELECT
							histogramId,
							histogramLabel
					FROM	sc_histogram_labels
							ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Subject codes from active classes for which marks have been transferred
//
//
// Author :Ajinder Singh
// Created on : 21-oct-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
 public function getGradingLabels() {
    global $sessionHandler;
	  $query = "
				SELECT
							DISTINCT(a.gradingLabelId),
							a.gradingLabel
				FROM		grading_labels a, grading_scales  b
				WHERE		a.gradingLabelId = b.gradingLabelId
				AND			a.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
				AND			a.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
				ORDER BY	a.gradingLabel";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


 //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF grades
//
//--------------------------------------------------------
    public function getGrade($orderBy=' gradeLabel') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM grades ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF sc_grading_labels
//
//--------------------------------------------------------
    public function getGradingLabel($orderBy=' gradingLabel') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT *
                  FROM sc_grading_labels
                  WHERE instituteId=$instituteId AND sessionId=$sessionId
         ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF PERIOD NAME
// orderBy: on which column to sort
// Author :Jaineesh
// Created on : (31.10.2008)
// Modified By :Dipanjan Bhattacharjee
// Modified on : (14.10.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getStudyPeriodData($studentId, $orderBy=' classId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		global $sessionHandler;
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

        if($studentId=='') {
          $studentId=0;
        }

        $query = "	SELECT
							distinct(sg.classId),
							substring_index(className,'-',-1) as periodName,
                            className AS className1, c.isActive, 
                            c.holdAttendance, c.holdTestMarks, c.holdFinalResult, c.holdGrades
					FROM	`student_groups` sg,
							class c
					WHERE
                            sg.classId= c.classId
					        AND	c.sessionId = $sessionId
					        AND	c.instituteId = $instituteId
					        AND	sg.studentId= $studentId
							$condition
                            GROUP BY c.classId
                 UNION
                   SELECT
                            distinct(sg.classId),
                            substring_index(className,'-',-1) as periodName,
                            className AS className1, c.isActive,
                            c.holdAttendance, c.holdTestMarks, c.holdFinalResult, c.holdGrades
                    FROM    `student_optional_subject` sg,
                            class c
                    WHERE
                            sg.classId= c.classId
                            AND c.sessionId = $sessionId
                            AND c.instituteId = $instituteId
                            AND sg.studentId= $studentId
                            $condition
                            GROUP BY c.classId
							ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF RESOURCE CATEGORIES
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (04.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getResourceCategory($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT resourceTypeId,resourceName FROM resource_category $conditions ORDER BY resourceName";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

	public function getRoles($condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT roleId, roleName FROM role WHERE roleId > 5 $condition ORDER BY roleName";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}


	public function getRoleNames($condition='') {
     $systemDatabaseManager = SystemDatabaseManager::getInstance();
	  $query = "SELECT DISTINCT r.roleName, r.roleId
FROM `admin_messages` a, `user` u, `role` r
WHERE a.senderId = u.userId
AND receiverType = 'student'
AND u.roleId = r.roleId
AND receiverType = 'student' $condition " ;
return $systemDatabaseManager->executeQuery($query,"Query: $query");
}


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FeedBack Labels
//
//orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getFeedBackLabel($orderBy=' feedbackSurveyId',$condition) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "	SELECT *
					FROM feedback_survey
					$condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FeedBack Categories
//
//orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------
    public function getFeedBackCategory($orderBy=' feedbackCategoryId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT * FROM feedback_category WHERE instituteId = $instituteId ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }



//-------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FeedBack Advanced AnswerSet
//
//orderBy: on which column to sort
// Author :Gurkeerat Sidhu
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------
    public function getFeedbackAdvAnswerSet($orderBy=' answerSetId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT * FROM feedbackadv_answer_set WHERE instituteId = $instituteId ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FeedBack Grades
//
//orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (17.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------
    public function getFeedBackGrade($orderBy=' feedbackGradeId',$conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT feedback_grade.* FROM feedback_grade,feedback_survey
		          WHERE  feedback_grade.feedbackSurveyId=feedback_survey.feedbackSurveyId
		          $conditions
				  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Financial Years
//
//orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (20.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------
    public function getFinancialYear($orderBy=' financialYearId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM emp_financial_year  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Leave Types
//
//orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (20.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------
    public function getLeaveType($orderBy=' leaveTypeId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM emp_leave_type  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Departments
//
//orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (21.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------
    public function getDepartment($orderBy=' departmentId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM department  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }





   //------------------------------------------------------------------------------------------------
// This Function  gets the employee name
//
// Author : Jaineesh
// Created on : 18.11.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

      public function getEmployeeName ($orderBy=' emp.employeeId')
      {

		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
   	    $studentId=$sessionHandler->getSessionVariable('StudentId');
        $classId=$sessionHandler->getSessionVariable('ClassId');

$query = "
   SELECT
				distinct(emp.employeeName),
				emp.employeeId
	FROM		 ".TIME_TABLE_TABLE."  tt,
				`period` p,
				`student` s,
				`subject` sub,
				`employee` emp,
				`room` r,
				`block` bl,
				`student_groups` sg,
				`time_table_labels` ttl,
				`time_table_classes` ttc,
				`group` gr,
				 class cl
	WHERE		tt.periodId = p.periodId
	AND			s.studentId=sg.studentId
	AND			tt.subjectId = sub.subjectId
	AND			sg.groupId = gr.groupId
	AND			tt.groupId = sg.groupId
	AND			tt.employeeId=emp.employeeId
	AND			r.blockId = bl.blockId
	AND			tt.roomId = r.roomId
	AND			tt.toDate IS NULL
	AND			tt.timeTableLabelId = ttl.timeTableLabelId
	AND			ttl.timeTableLabelId = ttc.timeTableLabelId
	AND			sg.classId = ttc.classId
	AND			sg.classId = cl.classId
	AND			sg.studentId=".$sessionHandler->getSessionVariable('StudentId')."
	AND			tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
	AND			tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
	AND			cl.classId=".$sessionHandler->getSessionVariable('ClassId')." ";

return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }





 //------------------------------------------------------------------------------------------------
// This Function  gets the employee name entery
//
// Author : Parveen Sharma
// Created on : 01.12.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

      public function getEmployeeNames()
      {

        global $sessionHandler;
               $instituteId=$sessionHandler->getSessionVariable('InstituteId');
               $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT distinct e.employeeId, e.employeeName
                  FROM
                        sc_feedback_teacher ft, feedback_questions fq, feedback_survey fs, employee e
                  WHERE
                        ft.feedbackQuestionId=fq.feedbackQuestionId AND
                        fq.feedbackSurveyId=fs.feedbackSurveyId AND
                        fs.instituteId=$instituteId AND fs.sessionId=$sessionId AND
                        fq.feedbackSurveyId=$feedbackSurveyId
                  ";
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF feedback survey
//
//
// Author :Parveen Sharma
// Created on : (01.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getFeedbackSurveyData() {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;

        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT feedbackSurveyId, feedbackSurveyLabel FROM feedback_survey
                  WHERE  instituteId=$instituteId AND sessionId=$sessionId
                  ORDER BY isActive DESC, feedbackSurveyLabel";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FeedBack Grades
//
//orderBy: on which column to sort
// Author :Parveen Sharma
// Created on : (01.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------
    public function getFeedBackGradeDESC($condition='', $orderBy=' feedbackGradeId DESC') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM feedback_grade $condition   ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------------------------------------
//function created for fetching degree to a student attendance
//
//
// Author :Parveen Sharma
// Created on : 05-12-08
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
 public function getDegreeWithCode() {
      $systemDatabaseManager = SystemDatabaseManager::getInstance();
      global $sessionHandler;

      $instituteId=$sessionHandler->getSessionVariable('InstituteId');
      $sessionId=$sessionHandler->getSessionVariable('SessionId');

      $query = "    SELECT
                              DISTINCT(a.classId), substring_index(substring_index( b.className, '-', 5 ) , '-', -4 ) AS className
                    FROM
                               class b, time_table_classes c,
                               ".ATTENDANCE_TABLE." a LEFT JOIN subject sub ON a.subjectId = sub.subjectId
                    WHERE
                              sub.hasAttendance = 1 AND
                              a.classId      =  b.classId        AND
                              b.classId      =  c.classId        AND
                              b.isActive     =  1                AND
                              b.instituteId  =  '$instituteId'   AND
                              b.sessionId    =  '$sessionId'     ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

  public function getCourses($condition='',$orderBy = 'subjectCode') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		global $sessionHandler;
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

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
			 $query = "	SELECT
									distinct sub.subjectId,
									sub.subjectCode
							FROM	classes_visible_to_role cvtr,
									`group` g,
									subject sub,
									subject_to_class stc
							WHERE	stc.subjectId = sub.subjectId
							AND		cvtr.groupId = g.groupId
							AND		g.classId = cvtr.classId
							AND		stc.classId = cvtr.classId
							AND		cvtr.userId = $userId
							AND		cvtr.roleId = $roleId
							AND		g.classId IN ($insertValue)
									ORDER BY $orderBy";
		}
		else {
        $query = "	SELECT
							b.subjectId,
							b.subjectCode
					FROM	subject b
							$condition
							ORDER BY $orderBy";
		}

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}

	public function getGroupTypes() {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT groupTypeId, groupTypeName FROM group_type  ORDER BY groupTypeName";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}
    //--------------------------------------------------------------------------------
    // THIS FUNCTION IS USED TO FETCH PERIOD SLOT LIST
    // Author :Pushpender Kumar Chauhan
    // Created on : (16.12.2008)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //-------------------------------------------------------------------------------
    public function getPeriodSlot($conditions='',$orderBy =' slotAbbr') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        $query = "SELECT periodSlotId,slotAbbr, slotName, isActive FROM period_slot $conditions ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Offences
//
//orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (22.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------
    public function getOffence($orderBy=' offenseId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM offense  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Groups based on class
//
//orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (06.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------
	public function getClassGroups($orderBy = '',$condition='') {
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = "SELECT groupId, groupName, groupShort FROM `group`   $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}


//-------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Groups based on manage Time Table
//
// orderBy: on which column to sort
// Author :PArveen Sharma
// Created on : (07.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------
   public function getTimeTableClassGroups($condition='',$orderBy = ' gr.groupId') {

        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $query = "SELECT
                        gr.groupId, gr.groupName, gr.groupShort
                  FROM
                        `subject_type` subType, `group_type` grType, `subject` sub, `group` gr
                  WHERE
                        subType.subjectTypeCode = grType.groupTypeCode AND
                        subType.subjectTypeId = sub.subjectTypeId AND
                        grType.groupTypeId = gr.groupTypeId
                  $condition ORDER BY $orderBy ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
   }

//-------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF TEST TYPE CATEGORY
//
// orderBy: on which column to sort
// Author :Jaineesh
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------
   public function getTestTypeCategoryData($condition='',$orderBy=' testTypeName') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "	SELECT	*
					FROM	test_type_category
					$condition
					ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Subject ACCORDING TO TIME TABLE
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (27.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getSubjectTimeTable($orderBy=' s.subjectId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
   $query = "	SELECT	distinct(s.subjectCode),
							s.subjectId
					FROM	subject s,
							 ".TIME_TABLE_TABLE." sctt,
							time_table_labels ttl
					WHERE	s.subjectId = sctt.subjectId
					AND		sctt.timeTableLabelId = ttl.timeTableLabelId
					AND		ttl.isActive = 1
					ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

   //------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF BUS NAMES
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (21.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------
   public function getBus($orderBy=' busName',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM bus $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
//------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF BUS NAMES
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (21.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------
   public function getRouteBusMapping($orderBy=' busName',$condition='') {
        
       $systemDatabaseManager = SystemDatabaseManager::getInstance();
       
        if($orderBy=='') {
          $orderBy = "busNo";  
        }
        
        
        $query = "SELECT 
                        DISTINCT b.busId, b.busNo, brm.busRouteId
                  FROM 
                        busRouteMapping brm, bus b 
                  WHERE
                        b.busId = brm.busId
                  $condition 
                  ORDER BY $orderBy";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }     
    
    
    public function getRouteBusTotal($condition='',$groupBy='') {
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $filedName='';
        $filedName1='bp.busRouteId'; 
        $filedName2='';
        $groupBy1='';
        if(strtolower(trim($groupBy))=='bus') {
          $filedName1='bp.busId'; 
          $filedName=',seatingCapacity,b.busId';  
          $filedName2=',t.seatingCapacity,t.busId';   
          $groupBy='GROUP BY b.busId';  
          $groupBy1='GROUP BY t.busId';
        }
        else {
          $groupBy='';   
        }
        
        $dt = date('Y-m-d');
        $query = "SELECT
                       SUM(t.cnt) AS cnt $filedName2
                  FROM
                      (SELECT 
                            IFNULL(COUNT($filedName1),0) AS cnt $filedName
                       FROM 
                            bus b 
                            LEFT JOIN bus_pass bp ON b.busId = bp.busId AND bp.validUpto > '".$dt."' AND bp.busPassStatus=1  
                       $condition 
                       $groupBy

                       UNION
                       SELECT 
                            IFNULL(COUNT($filedName1),0) AS cnt $filedName
                       FROM 
                            bus b 
                            LEFT JOIN employee_bus_pass bp ON b.busId = bp.busId AND bp.validUpto > '".$dt."' AND bp.status=1  
                       $condition 
                       $groupBy) AS t
                       $groupBy1";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
                        
//------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF TRANSPORT STUFF
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (21.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------
   public function getTransportStuff($orderBy=' name',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM transport_stuff $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF TRANSPORT STUFF
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (21.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------
   public function getSubjectTypeClass($orderBy=' name',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT subjectTypeId,subjectTypeName FROM subject_type st,class cls WHERE st.universityId = cls.universityId $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Hostel Room Type
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (22.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------
   public function getHostelRoomType($orderBy=' roomType',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT hostelRoomTypeId,roomType FROM hostel_room_type $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Hostel Room Type
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (22.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------
   public function getComplaintCategory($orderBy=' roomType',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM hostel_complaint_category $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Hostel Student
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (22.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------
   public function getStudentHostel($orderBy=' studentName',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
   $query = "	SELECT	DISTINCT(hs.studentId),
							CONCAT(s.firstName,' ',s.lastName) AS studentName
					FROM	hostel_students hs,
							student s
					WHERE	hs.studentId = s.studentId
							$condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------------------------------------
// This Function  gets the employee name
//
// Author : Jaineesh
// Created on : 18.11.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

      public function getPreviousEmployeeName ($orderBy=' emp.employeeId',$classId) {

		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
   	    $studentId=$sessionHandler->getSessionVariable('StudentId');

        //$classId=$sessionHandler->getSessionVariable('ClassId');
		if ($classId == "" ) {
			$classId = -1;
		}
$query = "
  SELECT
				distinct emp.employeeId,
				emp.employeeName
	FROM		 ".TIME_TABLE_TABLE."  tt,
				`period` p,
				`student` s,
				`subject` sub,
				`employee` emp,
				`room` r,
				`block` bl,
				`student_groups` sg,
				`time_table_labels` ttl,
				`time_table_classes` ttc,
				`group` gr,
				 class cl
	WHERE		tt.periodId = p.periodId
	AND			s.studentId=sg.studentId
	AND			tt.subjectId = sub.subjectId
	AND			sg.groupId = gr.groupId
	AND			tt.groupId = sg.groupId
	AND			tt.employeeId=emp.employeeId
	AND			r.blockId = bl.blockId
	AND			tt.roomId = r.roomId
	AND			tt.toDate IS NULL
	AND			tt.timeTableLabelId = ttl.timeTableLabelId
	AND			ttl.timeTableLabelId = ttc.timeTableLabelId
	AND			sg.classId = ttc.classId
	AND			sg.classId = cl.classId
	AND			sg.studentId=".$sessionHandler->getSessionVariable('StudentId')."
	AND			tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
	AND			tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
	AND			cl.classId = $classId
				ORDER BY $orderBy";

return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }

//------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Temp employee Name
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (30.04.09)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------
   public function getTempEmployee($orderBy=' tempEmployeeName',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
   $query = "	SELECT	tempEmployeeId,
						tempEmployeeName
				FROM	employee_temp
						$condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//----------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST temporary employee designation
//
//orderBy: on which column to sort
//
// Author :Gurkeerat Sidhu
// Created on : (29.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getStatus($orderBy=' designationName') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM designation_temp ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF General Feed Back Label Names
//
//orderBy: on which column to sort
// Author :Jaineesh
// Created on : (30.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getGeneralFeedBackLabel($orderBy=' feedbackSurveyId',$condition) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "	SELECT	fs.*,
							svu.targetIds
					FROM	feedback_survey fs,
							survey_visible_to_users svu
					WHERE	svu.feedbackSurveyId=fs.feedbackSurveyId
					AND		(CURRENT_DATE() BETWEEN fs.visibleFrom AND fs.visibleTo)
					$condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }



//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Total No. of Students
//
// Author :Parveen Sharma
// Created on : 27-05-09
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
   public function getTotalStudents($condition='') {

        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

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

        $insertValue = "";
        for($i=0;$i<$count; $i++) {
          $insertValue .= ",".$result[$i]['classId'];
        }

        $tableName = "";
        $hodCondition = "";
        if ($count > 0) {
            $tableName = ", classes_visible_to_role cvtr";
            $hodCondition = " AND  cvtr.groupId = group.groupId
                              AND  cvtr.classId = group.classId
                              AND  cvtr.classId = b.classId
                              AND  cvtr.userId = $userId
                              AND  cvtr.roleId = $roleId
                              AND  b.classId IN (0 $insertValue) ";
        }

        $query = "SELECT
                        COUNT(*) AS cnt,
                        SUM(IF(studentStatus=0,1,0)) AS unactive,
                        SUM(IF(studentStatus=1,1,0)) AS active
                  FROM
                        (SELECT
                                DISTINCT a.studentId, a.studentStatus
                         FROM
                              student a
                              LEFT JOIN class b ON a.classId = b.classId
                              LEFT JOIN `group` ON group.classId = a.classId
                              LEFT JOIN student_groups scs ON a.studentId = scs.studentId AND
                                        a.classId = scs.classId AND scs.groupId = group.groupId
                              $tableName
                         WHERE
                              b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' AND
                              b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."'
                         $condition
                         $hodCondition
                         GROUP BY
                              a.studentId) as t ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Total No. of Students without session check 
//
// Author :Nishu Bindal
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

 public function getTotalStudentsList($condition='') {

        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

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

        $insertValue = "";
        for($i=0;$i<$count; $i++) {
          $insertValue .= ",".$result[$i]['classId'];
        }

        $tableName = "";
        $hodCondition = "";
        if ($count > 0) {
            $tableName = ", classes_visible_to_role cvtr";
            $hodCondition = " AND  cvtr.groupId = group.groupId

                              AND  cvtr.classId = group.classId
                              AND  cvtr.classId = b.classId
                              AND  cvtr.userId = $userId
                              AND  cvtr.roleId = $roleId
                              AND  b.classId IN (0 $insertValue) ";
        }

        $query = "SELECT
                        COUNT(*) AS cnt,
                        SUM(IF(studentStatus=0,1,0)) AS unactive,

                        SUM(IF(studentStatus=1,1,0)) AS active,
                        SUM(IF(isActive>4,1,0)) AS alumni

                  FROM
                        (SELECT
                                DISTINCT a.studentId, a.studentStatus,b.isActive
                         FROM

                              student a
                              LEFT JOIN class b ON a.classId = b.classId
                              LEFT JOIN `group` ON group.classId = a.classId
                              LEFT JOIN student_groups scs ON a.studentId = scs.studentId AND
                                        a.classId = scs.classId AND scs.groupId = group.groupId

                              $tableName
                         WHERE
                              b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                         $condition
                         $hodCondition
                         GROUP BY
                              a.studentId) as t ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Last Login
//
// Author :Parveen Sharma
// Created on : 28-05-09
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getUserLastLogin($condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $query = " SELECT dateTimeIn FROM `user_log` $condition ORDER BY userLogId DESC LIMIT 1,1 ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

	//----------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF time table labels
//
// orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getDegreeLabel() {
		$query = "
					SELECT
								DISTINCT(c.classId),
								SUBSTRING_INDEX(c.className,'-',-3) AS className
					FROM		time_table_classes ttc,
								time_table_labels ttl,
								class c
					WHERE		ttc.timeTableLabelId = ttl.timeTableLabelId
					AND			ttc.classId = c.classId
					AND			ttl.isActive = 1";

		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}


//----------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST SUBJECT TOPIC
//
// Author :Parveen Sharma
// Created on : 01.06.09
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
     public function getSubjectTopic($orderBy=' topic',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

      /* if($condition=='') {
             $condition = " WHERE st.subjectId = sub.subjectId ";
         }
         else {
            $condition .= " AND st.subjectId = sub.subjectId ";
         }
         IF(IFNULL(tt.topicsTaughtId,'')='','-1',tt.topicsTaughtId) AS topicsTaughtId

         $query = "SELECT
                        st.subjectTopicId, st.topic, st.topicAbbr, st.subjectId,
                        sub.subjectName, sub.subjectCode, sub.hasAttendance, sub.hasMarks

                  FROM
                        subject_topic st, subject sub
                  $condition
                  ORDER BY $orderBy"; */

        $query="SELECT
                        DISTINCT   st.subjectTopicId,st.topicAbbr, st.topic,
                        sub.hasAttendance, st.subjectId, sub.subjectName,
                        sub.subjectCode, sub.hasAttendance, sub.hasMarks
                FROM
                        subject_topic st
                        LEFT JOIN topics_taught tt ON INSTR(tt.subjectTopicId, CONCAT('~',st.subjectTopicId,'~'))>0
                        LEFT JOIN `subject` sub ON sub.subjectId = st.subjectId
                WHERE
                        $condition
                ORDER BY
                        $orderBy ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-----------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Groups
//
// Author :Parveen Sharma
// Created on : (07.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------
   public function getTimeTableSubjectGroups($condition='',$orderBy = ' gr.groupId') {

       $systemDatabaseManager = SystemDatabaseManager::getInstance();

       $query = "SELECT
                        DISTINCT gr.groupId, gr.groupName, gr.groupShort, gt.groupTypeName, gt.groupTypeCode
                 FROM
                         ".TIME_TABLE_TABLE." tt, `group` gr, `group_type` gt, subject sub
                 WHERE
                        gr.groupTypeId = gt.groupTypeId AND
                        gr.groupId = tt.groupId AND
                        tt.subjectId = sub.subjectId
                 $condition
                 ORDER BY $orderBy ";

       /* $query = "SELECT
                            DISTINCT gr.groupId, gr.groupName, gr.groupShort
                  FROM
                            `subject_type` subType, `group_type` grType, `subject` sub, `group` gr,  ".TIME_TABLE_TABLE." tt
                  WHERE
                            subType.subjectTypeCode = grType.groupTypeCode AND
                            subType.subjectTypeId = sub.subjectTypeId AND
                            grType.groupTypeId = gr.groupTypeId AND
                            tt.subjectId = sub.subjectId
                            $condition
                  ORDER BY $orderBy ";
        */
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
   }


  //------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Offense Category
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (15.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------
   public function getOffenseCategoryData($orderBy=' offenseName') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
      $query = "	SELECT
							offenseId,
							offenseName
					FROM	offense
							$condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Fine Category
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (03.07.09)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------
   public function getFineCategoryData($orderBy=' fineCategoryAbbr') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
      $query = "	SELECT
							fineCategoryId,
							fineCategoryAbbr
					FROM	fine_category
							$condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Fine Category assigned to role
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (06.07.09)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------
   public function getRoleFineCategoryData($orderBy=' fc.fineCategoryAbbr') {

	  global $sessionHandler;
	  $instituteId=$sessionHandler->getSessionVariable('InstituteId');
	  $roleId=$sessionHandler->getSessionVariable('RoleId');

	  $systemDatabaseManager = SystemDatabaseManager::getInstance();
      $query = "	SELECT
							fc.fineCategoryId,
							fc.fineCategoryAbbr
					FROM	`fine_category` fc,`role_fine` rf,`role_fine_category` rfc,`role_fine_institute` rfi

					WHERE
							rfc.fineCategoryId = fc.fineCategoryId
					AND		rfc.roleFineId = rf.roleFineId
					
					AND		rfi.roleFineId = rf.roleFineId
					AND		rfi.instituteId = $instituteId
							$condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-----------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Parent Subject Category Information
// Author :Parveen Sharma
// Created on : (06.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------
    public function getParentSubjectCategory($orderBy=' categoryName') {

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT subjectCategoryId, categoryName FROM `subject_category` ORDER BY $orderBy";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


 //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Batch
//orderBy: on which column to sort
// Author :Ajinder Singh
// Created on : (29.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getBatches($orderBy=' batchId') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');


        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT
				 ba.batchId,
				 ba.batchName
                 FROM
                 `batch` ba
				 WHERE
				 ba.instituteId = '".$instituteId."'
				 ORDER BY
				 $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Institutes
// orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (13.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function checkInstitutes($conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT instituteId FROM institute $conditions";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Employees based on time table records
// orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (07.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------
    public function getEmployeesFromTimeTable($orderBy=' e.employeeName',$conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

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

		if ($count > 0 ) {
			$query = "
					  SELECT
							 DISTINCT e.employeeId,e.employeeName
					  FROM
							  ".TIME_TABLE_TABLE." t,time_table_labels ttl,
							 subject s,period p,`group` g,employee e,
							 class c, classes_visible_to_role cvtr
					  WHERE
							 e.employeeId=t.employeeId
							 AND t.subjectId=s.subjectId
							 AND t.groupId=g.groupId
							 AND g.classId=c.classId
							 AND t.periodId=p.periodId
							 AND t.timeTableLabelId=ttl.timeTableLabelId
							 AND cvtr.classId = c.classId
							 AND c.classId IN ($insertValue)
							 AND t.toDate IS NULL
							 AND t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
							 AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
							 AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
							 AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
							 $conditions
							 ORDER BY $orderBy
					 ";
			}
		else {
			$query = "
                  SELECT
                         DISTINCT e.employeeId,e.employeeName
                  FROM
                          ".TIME_TABLE_TABLE." t,time_table_labels ttl,
                         subject s,period p,`group` g,employee e,
                         class c
                  WHERE
                         e.employeeId=t.employeeId
                         AND t.subjectId=s.subjectId
                         AND t.groupId=g.groupId
                         AND g.classId=c.classId
                         AND t.periodId=p.periodId
                         AND t.timeTableLabelId=ttl.timeTableLabelId
                         AND t.toDate IS NULL
                         AND t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                         AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                         $conditions
                         ORDER BY $orderBy
                 ";

		}



        /*
         $query = "
          SELECT
                 DISTINCT e.employeeId,e.employeeName
          FROM
                employee e
          WHERE e.isActive=1
                AND e.isTeaching=1";
        */
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-----------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Employees based on adjusted time table records
// orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (22.10.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------
    public function getAdjustedEmployeesFromTimeTable($orderBy=' e.employeeName',$conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;

        $query = "
                  SELECT
                         DISTINCT e.employeeId,e.employeeName
                  FROM
                         time_table_adjustment t,time_table_labels ttl,
                         subject s,period p,`group` g,employee e,
                         class c
                  WHERE
                         e.employeeId=t.employeeId
                         AND t.subjectId=s.subjectId
                         AND t.groupId=g.groupId
                         AND g.classId=c.classId
                         AND t.periodId=p.periodId
                         ANd t.timeTableLabelId=ttl.timeTableLabelId
                         AND t.isActive=1
                         AND t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                         AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                         AND t.adjustmentType=3
                         $conditions
                         ORDER BY $orderBy
                 ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Conduncting Authorities based on test_type table records
// orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (24.10.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------------
    public function getTestConductingAuthority($orderBy=' conductingAuthority',$conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;

        $query = "
                  SELECT
                         DISTINCT conductingAuthority
                  FROM
                         test_type
                  WHERE
                         instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         $conditions
                         ORDER BY $orderBy
                 ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Test Type Categories based on test_type table records
// orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (24.10.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------------
   
public function getUsedTestTypeCategory($conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;

        $query = "
                  SELECT
                         DISTINCT ttc.testTypeCategoryId,ttc.testTypeName,ttc.testTypeAbbr
                  FROM
                         test_type tt,test_type_category ttc
                  WHERE
                         tt.testTypeCategoryId=ttc.testTypeCategoryId
                         AND tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         $conditions
                         ORDER BY ttc.testTypeName
                 ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }




/*public function getUsedTestTypeCategory($conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;

        $query = "
                  SELECT
                         DISTINCT ttc.testTypeCategoryId,ttc.testTypeName,ttc.testTypeAbbr
                  FROM
                         test_type tt,test_type_category ttc
                  WHERE
                         tt.testTypeCategoryId=ttc.testTypeCategoryId
                         AND tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         $conditions
                         ORDER BY ttc.testTypeName
                 ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");

    }

In the teacher login , in the report "Testwise performance report" the test type category shows all the possible categories that have been created till date, making it near impossible or very difficult for the teacher to remember which were the ones for which this teacher actually took the tests.Now with the new getUsedTestTypeCAtegory
the  drop down only contains those items which match the tests which teacher has taken.*/

public function getUsedTestTypeCategoryNew($conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;
        $employeeId= $sessionHandler->getSessionVariable('EmployeeId');
        


$query =
			"SELECT 
					DISTINCT ttc.testTypeCategoryId, ttc.testTypeName,ttc.testTypeAbbr
				FROM 
					test_type_category ttc, ".TEST_TABLE." ttt, test_type tt
				WHERE 
					ttc.testTypeCategoryId = ttt.testTypeCategoryId
				AND	tt.testTypeCategoryId = ttc.testTypeCategoryId
				AND	ttt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
				AND	ttt.employeeId=$employeeId
				$conditions
				ORDER BY ttc.testTypeName
			";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-----------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Class FROM TIME TABLE
// orderBy: on which column to sort
// Author :Jaineesh
// Created on : (28.10.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------
    public function getClassFromTimeTable($orderBy=' t.className',$conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

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

       if ($count > 0 ) {
	   $query = "
					SELECT
							DISTINCT cl.classId,
							cl.className
					FROM
							 ".TIME_TABLE_TABLE." t,
							time_table_labels ttl,
							time_table_classes ttc,
							class cl,
							classes_visible_to_role cvtr
					WHERE
							ttc.classId = cl.classId
					AND		ttc.timeTableLabelId = t.timeTableLabelId
					AND		ttl.isActive = 1
					AND		t.toDate IS NULL
					AND		t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
					AND		t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
					AND		cl.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
					AND		cl.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
					AND		cvtr.classId = cl.classId
					AND		cvtr.classId = ttc.classId
					AND		cl.classId IN ($insertValue)
							$conditions
							 ORDER BY $orderBy
                 ";
	   }
	   else {
	   $query = "
					SELECT
							DISTINCT cl.classId,
							cl.className
					FROM
							 ".TIME_TABLE_TABLE." t,
							time_table_labels ttl,
							time_table_classes ttc,
							class cl
					WHERE
							ttc.classId = cl.classId
					AND		ttc.timeTableLabelId = t.timeTableLabelId
					AND		ttl.isActive = 1
					AND		t.toDate IS NULL
					AND		t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
					AND		t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
					AND		cl.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
					AND		cl.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
							$conditions
							 ORDER BY $orderBy
                 ";
	   }

        /*
         $query = "
          SELECT
                 DISTINCT e.employeeId,e.employeeName
          FROM
                employee e
          WHERE e.isActive=1
                AND e.isTeaching=1";
        */
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF TEACHING EMPLOYEE
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (30.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getAllTeacherData() {
      global $sessionHandler;
		$systemDatabaseManager = SystemDatabaseManager::getInstance();

		$query =
				"	SELECT
							DISTINCT
                                e.employeeId, e.employeeName AS employeeName1, e.employeeAbbreviation,
                                IF(IFNULL(e.employeeAbbreviation,'')='',e.employeeName,CONCAT(e.employeeName,' (',e.employeeAbbreviation,')')) AS employeeName
					FROM	`employee` e
					LEFT JOIN employee_can_teach_in ec ON e.employeeId = ec.employeeId
					WHERE (e.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
					OR ec.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
					AND		e.isTeaching = 1
					AND		e.isActive=1
					ORDER BY employeeName";

		return $systemDatabaseManager->executeQuery($query,"Query: $query");
	  }


    public function getAllTeachers() {
      global $sessionHandler;
		$systemDatabaseManager = SystemDatabaseManager::getInstance();

		$query =
				"	SELECT
							DISTINCT
                                e.employeeId, e.employeeName AS employeeName1, e.employeeAbbreviation,
                                IF(IFNULL(e.employeeAbbreviation,'')='',e.employeeName,CONCAT(e.employeeName,' (',e.employeeAbbreviation,')')) AS employeeName, e.isActive
					FROM	`employee` e
					LEFT JOIN employee_can_teach_in ec ON e.employeeId = ec.employeeId
					WHERE (e.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
					OR ec.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
					AND		e.isTeaching = 1
					ORDER BY employeeName";

		return $systemDatabaseManager->executeQuery($query,"Query: $query");
	  }
      
      
      public function getAllEmployees($condition='') {
          
          global $sessionHandler;
          $systemDatabaseManager = SystemDatabaseManager::getInstance();

          $query ="    SELECT
                            DISTINCT e.employeeId, e.employeeName, e.employeeCode,     
                            CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName1, e.isActive
                        FROM    

                            `employee` e
                        $condition
                        ORDER BY 
                             e.isActive DESC, TRIM(e.employeeName) ASC ";

            return $systemDatabaseManager->executeQuery($query,"Query: $query");
      }
 public function getAllEmployeesNew($condition='') {
          
          global $sessionHandler;
          $systemDatabaseManager = SystemDatabaseManager::getInstance();

          $query = "SELECT
                        DISTINCT e.employeeId, e.employeeName, e.employeeCode, e.isTeaching,    
                        CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName1, e.isActive,
                        e.userId, i.instituteName, i.instituteCode
                    FROM    
                        `employee` e, `user` u, `institute` i
                    WHERE
                        e.userId = u.userId AND
                        u.instituteId = i.instituteId
                        $condition
                    ORDER BY 
                        e.isActive DESC, TRIM(e.employeeName) ASC ";

            return $systemDatabaseManager->executeQuery($query,"Query: $query");
   }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getAllClass($orderBy=' ttc.timeTableLabelId') {
		global $sessionHandler;
		$systemDatabaseManager = SystemDatabaseManager::getInstance();

		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

		$query = "	SELECT	*
					FROM	class cls,
							time_table_classes ttc,
							time_table_labels ttl
					WHERE 	cls.instituteId='".$instituteId."'
					AND		cls.sessionId='".$sessionId."'
					AND		cls.isActive IN(1,3)
					AND 	cls.classId = ttc.classId
					AND 	ttc.timeTableLabelId = ttl.timeTableLabelId
					AND		ttl.isActive =1
				 			ORDER BY $orderBy DESC";

		return $systemDatabaseManager->executeQuery($query,"Query: $query");

		}


		//-----------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Employees based on adjusted time table records
// orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (22.10.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------

    public function getAdjustedTeacherTimeTable($orderBy=' e.employeeName',$conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;

       $query = "
                  SELECT
                         DISTINCT e.employeeId,e.employeeName
                  FROM
                         time_table_adjustment t,time_table_labels ttl,
                         subject s,period p,`group` g,employee e,
                         class c
                  WHERE
                         e.employeeId=t.employeeId
                         AND t.subjectId=s.subjectId
                         AND t.groupId=g.groupId
                         AND g.classId=c.classId
                         AND t.periodId=p.periodId
                         ANd t.timeTableLabelId=ttl.timeTableLabelId
                         AND t.isActive=1
                         AND t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                         AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                         AND (t.adjustmentType = 1 OR t.adjustmentType = 2)
                         $conditions
                         ORDER BY $orderBy
                 ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Degree
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getTimeTableLabelDegreeData($orderBy=' degreeId',$conditions) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

		$query = "	SELECT	distinct deg.*
					FROM	degree deg,
							time_table_classes ttc,
							time_table_labels ttl,
							class cl
					WHERE	ttl.timeTableLabelId = ttc.timeTableLabelId
					AND		ttc.classId = cl.classId
					AND		cl.degreeId = deg.degreeId
					AND		ttl.instituteId = $instituteId
					AND		ttl.sessionId = $sessionId
							$conditions
							ORDER BY $orderBy";

		return $systemDatabaseManager->executeQuery($query,"Query: $query");
		}



/***********THIS FUNCTION IS USED TO CHECK FOR "FREEZE" FUNCTIONALITY*************/
//-------------------------------------------------------
// Author :Dipanjan Bhattacharjee
// Created on : (17.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
  public function checkFrozenClass($classId){
    $systemDatabaseManager = SystemDatabaseManager::getInstance();
     $query="
             SELECT
                    isFrozen,
                    SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-3) AS className
             FROM
                   `class`
             WHERE
                    classId IN ($classId)
           ";

    return $systemDatabaseManager->executeQuery($query,"Query: $query");
 }


 //-------------------------------------------------------
// Author :Dipanjan Bhattacharjee
// Created on : (17.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
  public function getTestClass($testId){
    $systemDatabaseManager = SystemDatabaseManager::getInstance();
     $query="
             SELECT
                    classId
             FROM
                   ".TEST_TABLE."
             WHERE
                    testId=$testId
           ";

    return $systemDatabaseManager->executeQuery($query,"Query: $query");
 }

 /***********THIS FUNCTION IS USED TO CHECK FOR "FREEZE" FUNCTIONALITY*************/



 //-------------------------------------------------------
// Author :Dipanjan Bhattacharjee
// Created on : (17.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
  public function getActiveClassesWithNoGroupsData(){
    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');

    $systemDatabaseManager = SystemDatabaseManager::getInstance();
    $query="
             SELECT
                    DISTINCT c.classId,c.className
             FROM
                    class c , study_period s
             WHERE
                    c.studyPeriodId = s.studyPeriodId
                    AND s.periodValue != 99999

                    AND c.instituteId=$instituteId
                    AND c.sessionId=$sessionId
                    AND c.classId NOT IN

                                         (
                                           SELECT
                                                  DISTINCT g.classId
                                           FROM
                                                  `group` g
                                         )
             ORDER BY c.studyPeriodId
           ";

    return $systemDatabaseManager->executeQuery($query,"Query: $query");
 }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Attendance Set
// Author :PArveen Sharma
// Created on : (29.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
  public function getAttendanceSet($condition='', $orderBy='attendanceSetName'){
    $systemDatabaseManager = SystemDatabaseManager::getInstance();
     $query="SELECT
                    attendanceSetId, attendanceSetName, evaluationCriteriaId
             FROM
                   `attendance_set`
             $condition
             ORDER BY $orderBy";

    return $systemDatabaseManager->executeQuery($query,"Query: $query");
 }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Past Classes
// Author :PArveen Sharma
// Created on : (29.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
  public function getPastClasses($condition='', $orderBy='className'){
       global $sessionHandler;

       $systemDatabaseManager = SystemDatabaseManager::getInstance();

       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $studentId = $sessionHandler->getSessionVariable('StudentId');

       $query="SELECT
                    DISTINCT c.classId, c.classname  AS className
               FROM
                   student_groups sg, class c
               WHERE
                    sg.classId = c.classId AND
                    sg.studentId = $studentId AND
                    sg.instituteId = $instituteId AND
                    c.instituteId = $instituteId AND
                    c.isActive = 3
               $condition
               ORDER BY $orderBy";

    return $systemDatabaseManager->executeQuery($query,"Query: $query");
 }



//-----------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Subject Types for logged in Institute
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------
  public function getSubjectTypes($condition='', $orderBy=' s.subjectTypeName'){
    $systemDatabaseManager = SystemDatabaseManager::getInstance();
    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');


     $query="SELECT
                   s.subjectTypeId,    
                   s.subjectTypeCode,
                   s.subjectTypeName
             FROM
                   subject_type s,institute i,class c
             WHERE
                   i.instituteId=c.instituteId
                   AND c.universityId=s.universityId
                   AND i.instituteId=$instituteId
                   $condition
             GROUP BY s.subjectTypeId
             ORDER BY $orderBy";

    return $systemDatabaseManager->executeQuery($query,"Query: $query");
 }


 //-----------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Adv. Feedback Label
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------
  public function getAdvFeedBackLabel($condition='', $orderBy=' f.feedbackSurveyLabel'){
    $systemDatabaseManager = SystemDatabaseManager::getInstance();
    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');

    $query ="SELECT
                   f.feedbackSurveyId,
                   f.feedbackSurveyLabel
             FROM
                   feedbackadv_survey f,time_table_labels t
             WHERE
                   f.timeTableLabelId=t.timeTableLabelId
                   AND t.instituteId=$instituteId
                   AND t.sessionId=$sessionId
                   $condition
             ORDER BY $orderBy";

    return $systemDatabaseManager->executeQuery($query,"Query: $query");
 }


//-----------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Adv. Feedback Categories
// Author : Dipanjan Bhattacharjee
// Created on : (11.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------
  public function getAdvFeedBackCategory($condition='', $orderBy=' f.feedbackCategoryName'){
    $systemDatabaseManager = SystemDatabaseManager::getInstance();
    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');

    /*$query ="SELECT
                   f.feedbackCategoryId,
                   f.feedbackCategoryName
             FROM
                   feedbackadv_category f
                   $condition
             ORDER BY $orderBy"; */
    $query ="SELECT
                    f.feedbackCategoryName,
                    f.feedbackCategoryId
             FROM
                    feedbackadv_category f
             LEFT JOIN
                    feedbackadv_category fc
             ON
                    f.feedbackCategoryId = fc.parentFeedbackCategoryId
             WHERE
                    fc.parentFeedbackCategoryId IS NULL
             $condition
             ORDER BY $orderBy";

    return $systemDatabaseManager->executeQuery($query,"Query: $query");
 }

 //-----------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Adv. Feedback Question Sets
// Author : Dipanjan Bhattacharjee
// Created on : (11.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------
  public function getAdvFeedBackQuestionSet($condition='', $orderBy=' q.feedbackQuestionSetName'){
    $systemDatabaseManager = SystemDatabaseManager::getInstance();
    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');

    $query ="SELECT
                   q.feedbackQuestionSetId,
                   q.feedbackQuestionSetName
             FROM
                   feedbackadv_question_set q
             WHERE
                   q.instituteId=$instituteId
                   $condition
             ORDER BY $orderBy";

    return $systemDatabaseManager->executeQuery($query,"Query: $query");
 }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF  Student Interneal Re-appear class
// Author :PArveen Sharma
// Created on : (29.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
  public function getReappearClasses($condition='', $orderBy='className'){
       global $sessionHandler;

       $systemDatabaseManager = SystemDatabaseManager::getInstance();

       $instituteId=$sessionHandler->getSessionVariable('InstituteId');

       $query="SELECT
                    DISTINCT c.classId, c.classname  AS className
               FROM
                   class c, student_reappear sr
               WHERE
                    sr.reapperClassId = c.classId AND
                    sr.instituteId = $instituteId
               $condition
               ORDER BY $orderBy";

    return $systemDatabaseManager->executeQuery($query,"Query: $query");
 }


 //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF  Student Interneal Re-appear subjects
// Author :PArveen Sharma
// Created on : (29.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
  public function getReappearSubject($condition='', $orderBy='subjectName'){
       global $sessionHandler;

       $systemDatabaseManager = SystemDatabaseManager::getInstance();

       $instituteId=$sessionHandler->getSessionVariable('InstituteId');



       $query="SELECT
                    DISTINCT sub.subjectId, sub.subjectName, sub.subjectCode
               FROM
                    `student_reappear` sr, `subject` sub
               WHERE
                    sub.subjectId = sr.subjectId AND
                    sr.instituteId = $instituteId
               $condition
               ORDER BY $orderBy";

    return $systemDatabaseManager->executeQuery($query,"Query: $query");
 }

 //------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Adv. Feedback Lable Mapped Labels corresponding to
// an user of a particular role
// Author : Dipanjan Bhattacharjee
// Created on : (19.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------
  public function fetchMappedFeedbackLabelAdvForUsers($roleId=-1,$userId=-1,$ignoreDate=0){
    $systemDatabaseManager = SystemDatabaseManager::getInstance();
    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');
    $dateCondition='';
    if($ignoreDate==0){
     $logInDate=date('Y-m-d');
     if($sessionHandler->getSessionVariable('UserIdDisabledForInCompleteFeedback')==2){
      $dateCondition="AND ( '$logInDate' BETWEEN fs.visibleFrom AND fs.extendTo )";
     }
     else{
         $dateCondition="AND ( '$logInDate' BETWEEN fs.visibleFrom AND fs.visibleTo )";
     }
    }

    $query ="SELECT
                   fs.feedbackSurveyId,
                   fs.feedbackSurveyLabel
             FROM
                   feedbackadv_survey fs,
                   feedbackadv_survey_mapping fsm,
                   feedbackadv_survey_visible_to_users fsvu,
                   `user` u,
                   `role` r,
                   time_table_labels ttl
             WHERE
                   u.roleId=r.roleId
                   AND u.userId=fsvu.userId
                   AND r.roleId=fsvu.roleId
                   AND fsvu.feedbackMappingId=fsm.feedbackMappingId
                   AND fsm.feedbackSurveyId=fs.feedbackSurveyId
                   AND fs.isActive=1
                   AND fs.timeTableLabelId=ttl.timeTableLabelId
                   AND ttl.instituteId=$instituteId
                   AND ttl.sessionId=$sessionId
                   $dateCondition
                   AND u.userId=$userId
                   AND r.roleId=$roleId
                   AND u.instituteId=$instituteId
             GROUP BY  fs.feedbackSurveyId
             ORDER BY fs.feedbackSurveyLabel
             ";
    return $systemDatabaseManager->executeQuery($query,"Query: $query");
 }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF ALL Institue Past and Present CLASS
// Author :PArveen Sharma
// Created on : (29.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
   public function getInstitueClass($orderBy=' className') {

        global $sessionHandler;

        $systemDatabaseManager = SystemDatabaseManager::getInstance();

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
            $hodCondition = " AND  cvtr.classId = c.classId
                              AND  cvtr.userId = $userId
                              AND  cvtr.roleId = $roleId
                              AND  c.classId IN $insertValue ";
        }

        $query = "SELECT
                        DISTINCT c.classId, c.className
                  FROM
                        class c $tableName
                  WHERE
                        c.isActive IN (1)
                  $hodCondition
                  ORDER BY $orderBy";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Subjects associated with a class and time table
// Author : Parveen Sharma
// Created on : (17.03.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function getClassSubjectsList($condition='',$orderBy='subjectName',$limit='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                        DISTINCT
                                s.subjectId,s.subjectCode,s.subjectName,
                                s.subjectAbbreviation, st.subjectTypeName, st.subjectTypeCode, c.className
                  FROM
                        `subject_type` st, `subject` s,subject_to_class stc,
                        `class` c,time_table_classes ttc
                  WHERE
                         st.subjectTypeId = s.subjectTypeId
                         AND ttc.classId=c.classId
                         AND c.classId=stc.classId
                         AND stc.subjectId=s.subjectId
                         AND c.instituteId='$instituteId'
                         AND c.sessionId='$sessionId'
                  $condition
                  UNION
                  SELECT
                        DISTINCT
                                s.subjectId,s.subjectCode,s.subjectName,
                                s.subjectAbbreviation, st.subjectTypeName, st.subjectTypeCode, c.className
                  FROM
                        `subject_type` st, `subject` s,student_optional_subject stc,
                        `class` c,time_table_classes ttc
                  WHERE
                         st.subjectTypeId = s.subjectTypeId
                         AND ttc.classId=c.classId
                         AND c.classId=stc.classId
                         AND stc.subjectId=s.subjectId
                         AND c.instituteId='$instituteId'
                         AND c.sessionId='$sessionId'
                  $condition
                  ORDER BY
                        $orderBy
                  $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class
//orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (14.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
   public function getActiveTimeTableClasses($condition='',$orderBy=' ttc.timeTableLabelId') {

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
           $classId = " AND cls.classId IN (0";
           for($i=0; $i<count($result); $i++) {
              $classId .= ",".$result[$i]['classId'];
           }
           $classId .= ")";
        }

        $query = "SELECT
                         DISTINCT cls.classId,cls.className
                 FROM
                         class cls,time_table_classes ttc, time_table_labels ttl
                 WHERE
                         cls.instituteId='".$instituteId."' AND
                         cls.sessionId='".$sessionId."' AND
                         cls.isActive IN(1,3)  AND
                         cls.classId = ttc.classId AND
                         ttc.timeTableLabelId = ttl.timeTableLabelId
                         $condition $classId
                 ORDER BY degreeId,branchId,studyPeriodId ASC";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


    //-----------------------------------------------------------------------------------------------
    // function created for Subject in active time table
    // Author :Parveen Sharma
    // Created on : 21-04-2009
    // Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
    public function getTeacherTimeTableSubject($condition, $orderBy=' degreeId,branchId,studyPeriodId') {

        global $sessionHandler;

        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');


        $query = "SELECT
                         DISTINCT s.subjectId,s.subjectCode,s.subjectName,
                                  s.subjectAbbreviation, st.subjectTypeName, st.subjectTypeCode, c.className
                  FROM
                         ".TIME_TABLE_TABLE."  tt,`group` g, `class` c, `subject` s, `subject_type` st
                  WHERE
                         st.subjectTypeId = s.subjectTypeId
                         AND s.subjectId = tt.subjectId
                         AND tt.groupId=g.groupId
                         AND g.classId=c.classId
                         AND c.instituteId=".$instituteId."
                         AND tt.sessionId=".$sessionId."
                         AND tt.toDate IS NULL
                         AND c.isActive IN (1,3)
                 $condition
                 ORDER BY $orderBy ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-----------------------------------------------------------------------------------------------
// function created for counting total no. of institutes
// Author :Dipanjan Bhattacharjee
// Created on : 20.04.2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------
    public function countInstitutes() {

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT
                         COUNT(*) AS instituteCount
                  FROM
                        institute";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-----------------------------------------------------------------------------------------------
// function created for counting total no. of institutes
// Author :Dipanjan Bhattacharjee
// Created on : 20.04.2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------
    public function getOptionalSubjects($subjectId,$groupId) {

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "	SELECT
							gr.groupId,
							gr.groupName
					FROM
							`group` gr
					WHERE	gr.optionalSubjectId = ".$subjectId."
					AND		gr.groupId NOT IN ($groupId)";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

	//------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Hostel Room Type
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (22.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------
   public function getRoomTypeList($orderBy=' roomType',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM room_type $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Bank LIST
//
// Author :Abhiraj malhotra
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


    public function getDedAccountList() {
        $query = "SELECT dedAccountId, accountName, accountNumber
        FROM deduction_account ORDER BY accountName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
 //-------------------------------------------------------------------------------



//------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Hostel Room Type
// orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (01.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------
   public function getSubjectInformation($condition='',$orderBy=' subjectCode') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM `subject` $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF PERIOD NAME
// orderBy: on which column to sort
// Author :Prashant
// Created on : 12-May-10
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getStudentAllocatedSubject($studentId,$classId, $orderBy=' subjectCode') {

	    $systemDatabaseManager = SystemDatabaseManager::getInstance();
		global $sessionHandler;
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

        if($studentId=='') {
          $studentId=0;
        }
		$classCondition='';
		if($classId!=0){
		  $classCondition=' AND c.classId='.$classId;
		}

        $query = "	SELECT
							DISTINCT s.subjectId,s.subjectName,s.subjectCode
					FROM	`student_groups` sg,
							class c ,`subject` s,subject_to_class stc
					WHERE
                            sg.classId= c.classId
					        AND	c.sessionId = $sessionId
					        AND	c.instituteId = $instituteId
					        AND	sg.studentId= $studentId
							AND c.classId=stc.classId
							AND stc.subjectId=s.subjectId
							$classCondition
							$condition
                            GROUP BY s.subjectId
                 UNION
                   SELECT
                            DISTINCT s.subjectId,s.subjectName,s.subjectCode
                    FROM    `student_optional_subject` sg,
                            class c,`subject` s
                    WHERE
                            sg.classId= c.classId
                            AND c.sessionId = $sessionId
                            AND c.instituteId = $instituteId
                            AND sg.studentId= $studentId
							AND sg.subjectId=s.subjectId
							$classCondition
                            $condition
                            GROUP BY s.subjectId
							ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


 	   //------------------------------------------------------------------------------------------------
// This Function  gets the employee name Allocated to particular Class
//
// Author : Prashant
// Created on : 11-May-10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

    /*  public function getEmployeeNameAllocatedToClass($classId,$orderBy='emp.employeeId')
      {
		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
   	    $studentId=$sessionHandler->getSessionVariable('StudentId');

	$classCondition='';
		if($classId!=0){
		  $classCondition=' AND cl.classId='.$classId;
		}
 $query = "
   SELECT
				distinct(emp.employeeName),
				emp.employeeId
	FROM		 ".TIME_TABLE_TABLE."  tt,
				`period` p,
				`student` s,
				`subject` sub,
				`employee` emp,
				`room` r,
				`block` bl,
				`student_groups` sg,
				`time_table_labels` ttl,
				`time_table_classes` ttc,
				`group` gr,
				`class` cl

	WHERE		tt.periodId = p.periodId
	AND			s.studentId=sg.studentId
	AND			tt.subjectId = sub.subjectId
	AND			sg.groupId = gr.groupId
	AND			tt.groupId = sg.groupId
	AND			tt.employeeId=emp.employeeId
	AND			r.blockId = bl.blockId
	AND			tt.roomId = r.roomId
	AND			tt.toDate IS NULL
	AND			tt.timeTableLabelId = ttl.timeTableLabelId
	AND			ttl.timeTableLabelId = ttc.timeTableLabelId
	AND			sg.classId = ttc.classId
	AND			sg.classId = cl.classId
	AND			sg.groupId = tt.groupId
	AND			tt.employeeId = emp.employeeId
				$classCondition
	AND			sg.studentId=".$sessionHandler->getSessionVariable('StudentId')."

	AND			tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
	AND			tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."";

return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }*/


	   public function getEmployeeNameAllocatedToClass($classId,$orderBy='employee.employeeId')
      {
		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
   	    $studentId=$sessionHandler->getSessionVariable('StudentId');

		$classCondition='';
		if ($classId != "" and $classId != "0") {
			$classCond =" AND stc.classId =".add_slashes($classId);
		}
$query = "
   SELECT
						distinct employee.employeeId,
						employee.employeeName

				FROM
						course_resources,
						resource_category,
						subject,
						employee

				WHERE	course_resources.resourceTypeId=resource_category.resourceTypeId
				AND		course_resources.subjectId=subject.subjectId
				AND		course_resources.employeeId=employee.employeeId
				AND		course_resources.instituteId=$instituteId
				AND		resource_category.instituteId=$instituteId
				AND		course_resources.sessionId=$sessionId
				AND		course_resources.subjectId
				IN
						(
							SELECT DISTINCT stc.subjectId
							FROM subject_to_class stc, `student_groups` sg
							WHERE stc.classId=sg.classId
							AND sg.instituteId = $instituteId
							AND sg.sessionId = $sessionId
							$classCond
						)

						ORDER BY employee.employeeName";

return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }




//--------------------------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF BUDGET HEADS
// selected: which element in the select element to be selected
// Author :Dipanjan Bhattacharjee
// Created on : (17.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------------------------------------
    public function getBudgetHeadsData($condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM budget_heads $condition ORDER BY headName";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF LEAVE SETS
// selected: which element in the select element to be selected
// Author :Dipanjan Bhattacharjee
// Created on : (17.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------------------------------------
    public function getLeaveSessionSetAdvData($condition='') {
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        DISTINCT ls.leaveSetName, ls.leaveSetId, ls.isActive, ls.instituteId
                  FROM
                        leave_session s,leave_set_mapping lsm, leave_set ls
                  WHERE
                        s.leaveSessionId = lsm.leaveSessionId AND
                        lsm.leaveSetId  = ls.leaveSetId AND
                        lsm.instituteId = ls.instituteId AND
                        ls.instituteId=".$instituteId."
                  $condition
                  ORDER BY
                        LENGTH(ls.leaveSetName)+0,ls.leaveSetName";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }



//--------------------------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF LEAVE SETS
// selected: which element in the select element to be selected
// Author :Dipanjan Bhattacharjee
// Created on : (17.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------------------------------------
    public function getLeaveSetAdvData($condition='') {
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $query = "SELECT *
                  FROM
                        leave_set
                  WHERE
                        instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                  $condition
                  ORDER BY
                        LENGTH(leaveSetName)+0,leaveSetName";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF LEAVE SETS
// selected: which element in the select element to be selected
// Author :Dipanjan Bhattacharjee
// Created on : (17.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------------------------------------
    public function getLeaveTypeAdvData($condition='') {
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM leave_type WHERE instituteId=".$sessionHandler->getSessionVariable('InstituteId')."  $condition ORDER BY LENGTH(leaveTypeName)+0,leaveTypeName";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


    //-------------------------------------------------------
    //  THIS FUNCTION IS USED TO GET A LIST OF Sujbect
    //
    //orderBy: on which column to sort
    //
    // Author :Arvind Singh Rawat
    // Created on : (2.07.2008)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getCourseData($condition='',$orderBy='') {

        global $sessionHandler;

        if($orderBy=='') {
          $orderBy = 'sub.subjectCode';
        }

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                        DISTINCT
                            sub.subjectId, sub.subjectCode, sub.subjectName,
                            CONCAT(sub.subjectCode,' (',sub.subjectName,')') AS subjectName1
                  FROM
                        `subject` sub
                  $condition
                  ORDER BY $orderBy";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

    //-------------------------------------------------------
    //  THIS FUNCTION IS USED TO GET A LIST OF Sujbect
    //
    //orderBy: on which column to sort
    //
    // Author :Arvind Singh Rawat
    // Created on : (2.07.2008)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getRegistrationDegreeList($condition='',$orderBy='') {

        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        if($orderBy=='') {
           $orderBy = 'sp.periodValue ASC';
        }

        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $degreeCode = $sessionHandler->getSessionVariable('REGISTRATION_DEGREE');


        $query = "SELECT
                       DISTINCT
                          c.classId, c.degreeId, sp.studyPeriodId, d.degreeCode,
                          p.periodicityId, p.periodicityName, sp.periodValue, c.className
                  FROM
                       `class` c, study_period sp, periodicity p, degree d
                  WHERE
                        c.degreeId = d.degreeId AND
                        c.studyPeriodId = sp.studyPeriodId AND
                        p.periodicityId = sp.periodicityId AND
                        c.instituteId = $instituteId AND
                        d.degreeCode = '".$degreeCode."' AND
                        sp.periodValue IN (4,5,6)
                  $condition
                  ORDER BY $orderBy";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A Degree
// selected: which element in the select element to be selected
// Author :Parveen Sharma
// Created on : (17.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------------------------------------
    public function getDegreeName($condition='') {

        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $degreeCode = $sessionHandler->getSessionVariable('REGISTRATION_DEGREE');

        $query = "SELECT
                        DISTINCT d.degreeId, d.degreeCode, c.classId, c.batchId, c.branchId
                  FROM
                        class c, degree d, study_period sp
                  WHERE
                        d.degreeId = c.degreeId AND
                        c.instituteId = $instituteId AND
                        d.degreeCode = '".$degreeCode."' AND
                        c.studyPeriodId =  sp.studyPeriodId
                  $condition";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A Degree
// selected: which element in the select element to be selected
// Author :Parveen Sharma
// Created on : (17.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------------------------------------
    public function getRegistrationClass($condition='') {

        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $degreeCode = $sessionHandler->getSessionVariable('REGISTRATION_DEGREE');

        $query = "SELECT
                        DISTINCT c.classId, c.className
                  FROM
                        class c, student_registration_master m, degree d
                  WHERE
                        c.classId = m.currentClassId AND
                        c.instituteId = m.instituteId AND
                        c.instituteId = $instituteId AND
                        d.degreeCode = '".$degreeCode."'
                  $condition";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF DUTY LEAVE EVENTS
// selected: which element in the select element to be selected
// Author :Dipanjan Bhattacharjee
// Created on : (14.06.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------------------------------------
public function getDutyLeaveEventData($condition='') {
    global $sessionHandler;
    $systemDatabaseManager = SystemDatabaseManager::getInstance();
    $query ="SELECT
                    *
             FROM
                   duty_event
             WHERE
                   instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                   AND sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                   $condition
             ORDER BY eventTitle";
    return $systemDatabaseManager->executeQuery($query,"Query: $query");
}


  //--------------------------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Counselling Rounds
// selected: which element in the select element to be selected
// Author :Parveen Sharma
// Created on : (14.06.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------------------------------------
public function getCounsellingRounds($orderBy=' roundId',$condition='') {
    global $sessionHandler;
    $systemDatabaseManager = SystemDatabaseManager::getInstance();

    $query ="SELECT
                   `roundId`, `roundName`
             FROM
                   `counselling_rounds`
             $condition
             ORDER BY $orderBy";

    return $systemDatabaseManager->executeQuery($query,"Query: $query");
}

 //--------------------------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Counselling Class
// selected: which element in the select element to be selected
// Author :Parveen Sharma
// Created on : (14.06.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------------------------------------
public function getCounsellingClass($orderBy=' className',$condition='') {

    global $sessionHandler;
    $systemDatabaseManager = SystemDatabaseManager::getInstance();

    $sessionId = $sessionHandler->getSessionVariable('SessionId');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');

    $query ="SELECT
                   DISTINCT c.classId, c.className
             FROM
                   class_quota_seats cs, class c
             WHERE
                   cs.classId     = c.classId  AND
                   cs.instituteId = c.instituteId AND
                   cs.sessionId   = c.sessionId AND
                   cs.instituteId = $instituteId AND
                   cs.sessionId   = $sessionId
             $condition
             ORDER BY $orderBy";

    return $systemDatabaseManager->executeQuery($query,"Query: $query");
}


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Vehicle types
//
// Author :Ajinder Singh
// Created on : 01-Dec-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function getExtraTyres() {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "	SELECT
							tyreId,
							tyreNumber
					FROM	`tyre_master`
					WHERE	tyre_master.isActive = 2
							ORDER BY tyreNumber";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}

	//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Vehicle types
//
// Author :Ajinder Singh
// Created on : 01-Dec-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function getVehicleTypes() {


        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM `vehicle_type` ORDER BY vehicleType";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}

	//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Vehicle types
//
// Author :Ajinder Singh
// Created on : 01-Dec-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function getInsuringCompany() {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT insuringCompanyId, insuringCompanyName FROM `insurance_company` ORDER BY insuringCompanyName";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}

	//------------------------------------------------------------------
	//  THIS FUNCTION IS USED TO GET A LIST OF TRANSPORT STUFF
	//
	//orderBy: on which column to sort
	//
	// Author :Dipanjan Bhattacharjee
	// Created on : (21.01.2009)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//-----------------------------------------------------------------

   public function getTransportStaff($orderBy=' name',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM transport_staff $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


	//-------------------------------------------------------
    //  THIS FUNCTION IS USED TO GET A LIST OF Institutes
    // orderBy: on which column to sort
    // Author :Jaineesh
    // Created on : (24.02.2010)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //--------------------------------------------------------
    public function getDepartmentIncharge($conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "	SELECT	emp.employeeId,
							emp.employeeName
					FROM	employee emp, inv_dept_incharge idi
					WHERE	idi.inchargeId = emp.employeeId
					$conditions";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
    //  THIS FUNCTION IS USED TO GET A LIST OF Institutes
    // orderBy: on which column to sort
    // Author :Jaineesh
    // Created on : (13.08.2009)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //--------------------------------------------------------
    public function getInvDepttData($conditions='',$orderBy=' invDepttAbbr') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "	SELECT	invDepttId,
							invDepttAbbr
					FROM	inv_dept $conditions ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

	//------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Item Category lists
// Author :Dipanjan Bhattacharjee
// Created on : (11.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------
   public function getItemConsumableCategory($orderBy=' abbr',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "	SELECT	distinct(ic.itemCategoryId),
							ic.abbr
					FROM	item_category ic,
							items_master im
					WHERE	ic.itemCategoryId = im.itemCategoryId
					$condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

	//------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Item Category lists
// Author :Dipanjan Bhattacharjee
// Created on : (11.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------
   public function getItemCategory($orderBy=' abbr',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM item_category $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Student's Teacher
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (28.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
    public function getStudentTeacher($orderBy='employeeName',$condition='') {

       global $REQUEST_DATA;
       global $sessionHandler;

       $studentId = $sessionHandler->getSessionVariable('StudentId');
       $classId = $sessionHandler->getSessionVariable('ClassId');
       $instituteId  = $sessionHandler->getSessionVariable('InstituteId');
       $sessionId = $sessionHandler->getSessionVariable('SessionId');

       if($orderBy=='') {
         $orderBy='employeeName';
       }

       $query = "SELECT
                        DISTINCT emp.employeeId, emp.userId, employeeName
                 FROM
                        `employee` emp LEFT JOIN `user` ur ON emp.userId = ur.userId
                        LEFT JOIN role r  ON r.roleId = ur.roleId
                 WHERE
                        emp.isActive = 1 AND
                        ur.instituteId = $instituteId
                        $condition
                 ORDER BY $orderBy";


        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //---------------------------------------------------------------------------
    //  THIS FUNCTION IS USED TO GET A LIST OF Student's Teacher
    //
    //orderBy: on which column to sort
    //
    // Author :Rajeev Aggarwal
    // Created on : (28.01.2009)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------
    public function getTeacherStudent($orderBy='firstName') {

       global $REQUEST_DATA;
       global $sessionHandler;


        $query ="SELECT
                         DISTINCT(ssub.studentId),scs.userId, CONCAT(scs.firstName,' ',scs.lastName) as firstName
                 FROM
                        `sc_student` scs, `student_groups` ssub
                 WHERE
                        ssub.studentId = scs.studentId AND
                        ssub.groupId IN (SELECT
                                                DISTINCT(stt.groupId)
                                         FROM
                                                 ".TIME_TABLE_TABLE."  stt,`time_table_labels` ttl
                                         WHERE
                                                stt.employeeId=".$sessionHandler->getSessionVariable('EmployeeId')." AND
                                                stt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." AND
                                                stt.sessionId=".$sessionHandler->getSessionVariable('SessionId')." AND
                                                stt.timeTableLabelId = ttl.timeTableLabelId AND
                                                stt.toDate IS NULL )
                 ORDER BY $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH SUBJECT TO CLASS DATA
//
// Author :Rajeev Aggarwal
// Created on : (14.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getClassList($conditions='', $orderBy=' c.degreeId,c.branchId,c.studyPeriodId') {

        global $sessionHandler;

        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');

        $query = "SELECT
                        distinct cvtr.classId
                  FROM
                        classes_visible_to_role cvtr
                  WHERE
                        cvtr.userId = $userId
                        AND cvtr.roleId = $roleId";

        $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");

        $count = count($result);
        $insertValue = "";
        for($i=0;$i<$count; $i++) {
            $querySeprator = '';
            if($insertValue!='') {
                $querySeprator = ",";
            }
            $insertValue .= "$querySeprator '".$result[$i]['classId']."'";
        }

        $hodConditions = "";
        if($insertValue!='') {
          $hodConditions = " AND c.classId IN ($insertValue)";
        }

        $query = "SELECT
                      DISTINCT c.classId, className
                  FROM
                      class c,`subject` sub, `subject_to_class` subtocls,`subject_type` st
                  WHERE
                      c.classId = subtocls.classId AND
                      sub.subjectId = subtocls.subjectId AND
                      sub.subjectTypeId = st.subjectTypeId AND
                      c.instituteId='".$instituteId."' AND
                      c.sessionId='".$sessionId."' AND
                      c.isActive IN(1,3)
                      $conditions
                      $hodConditions
                  ORDER BY $orderBy DESC";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Grade Set
//
// Author :Parveen Sharma
// Created on : (22.10.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getGradeSet($condition='',$orderBy=' isActive DESC, gradeSetName ASC') {
			global $sessionHandler;
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM `grades_set` WHERE instituteId = $instituteId $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }



//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Appraisal Tabs
// Author :Dipanjan Bhattacharjee
// Created on : (15.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getAppraisalTabData($conditions='') {

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM employee_appraisal_tab $conditions ORDER BY appraisalTabName";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Appraisal Titles
// Author :Dipanjan Bhattacharjee
// Created on : (15.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------
    public function getAppraisalTitleData($conditions='') {

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM employee_appraisal_title $conditions ORDER BY appraisalTitle";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Appraisal Titles
// Author :Dipanjan Bhattacharjee
// Created on : (15.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------
    public function getAppraisalProofData($conditions='') {

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM employee_appraisal_proof $conditions";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------
// Purpose: to fetch active leave session
// Modified By :Parveen Sharma
// Params: --
// Returns: array
// Created on : (01.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
   public function getLeaveSessionList($conditions='',$orderBy='') {

       if($orderBy=='') {
         $orderBy = " active DESC,sessionName ASC";
       }
       $query = "SELECT
                      leaveSessionId, sessionName,  sessionStartDate, sessionEndDate, active
              FROM
                     leave_session
              $conditions
              ORDER BY $orderBy";

       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }


//------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Placement Companies
// Author :Dipanjan Bhattacharjee
// Created on : (29.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------
    public function getPlacementCompaniesData($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT
                        companyId,companyName,companyCode
                  FROM
                        placement_company
                  WHERE
                        instituteId=$instituteId
                        AND isActive=1
                        $conditions
                  ORDER BY companyCode";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Placement Drives
// Author :Dipanjan Bhattacharjee
// Created on : (03.08.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------
    public function getPlacementDrivesData($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT
                        placementDriveId,placementDriveCode
                  FROM
                        placement_drive
                  WHERE
                        instituteId=$instituteId
                        $conditions
                  ORDER BY placementDriveCode";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


/*THIS FUNCTION FETCHES CLASSES FROM admappl_student_information table*/
    public function getAdmApplClassData($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT
                        c.classId,c.className
                  FROM
                        class c,admappl_student_information a
                  WHERE
                        c.classId=a.classId
                        AND c.instituteId=$instituteId
                        AND c.sessionId=$sessionId
                        $conditions
                  GROUP BY c.classId
                  ORDER BY c.degreeId,c.branchId,c.studyPeriodId";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-----------------------------------------------------------------------------------------------
// function created for fetching list of student program fees
// Author :Dipanjan Bhattacharjee
// Created on : 28.09.2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------------
    public function getStudentProgramFeeData() {

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT
                         programFeeId,
                         programFeeName
                  FROM
                         student_program_fee
                  ORDER BY programFeeName";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Collect fee Class
//
//selected: which element in the select element to be selected
//
// Author :Arvind Singh Rawat
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
    public function getCollectFeeClass($condition='',$orderBy='') {

        global $sessionHandler;

        $systemDatabaseManager = SystemDatabaseManager::getInstance();


        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        if($orderBy=='') {
          $orderBy = " c.branchId, c.studyPeriodId";
        }

        $query = "SELECT
                       DISTINCT c.classId,c.className
                  FROM
                       class c, fee_receipt fr
                  WHERE
                       fr.feeClassId = c.classId AND
                       c.instituteId = $instituteId
                  ORDER BY
                       $orderBy";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-----------------------------------------------------------------------------------------------
// function created to fetch list of users who has requested allocation for guest house
// Author :Dipanjan Bhattacharjee
// Created on : 28.09.2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------------
    public function getGuestHouseRequesterData($conditions='') {
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "
                  SELECT
                        DISTINCT u.userId,
                        IFNULL((SELECT e.employeeName FROM employee e WHERE e.userId=u.userId),u.userName) AS userName
                  FROM
                        `user` u,
                        guest_house_allocation gh
                  WHERE
                         u.userId=gh.requesterUsedId
                         AND u.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         $conditions
                  ORDER BY userName
                 ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-----------------------------------------------------------------------------------------------
// function created to fetch list of users data
// Created on : 24-Nov-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------------
    public function getUserData($orderBy=' userName',$conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT userId,userName,roleId FROM `user`  $conditions ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//---------------------------------------------------------------------------------------------------------------------
// Function gets records for student Attendance wise report
//
// Author :Parveen Sharma
// Created on : 26-Nov-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------------------------------
     public function getStudentPercentageAttendanceReport($condition='',$groupBy='',$orderBy='',$limit='',$dateFields='')  {

        global $REQUEST_DATA;
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');



        if($dateFields=='') {
           $fieldName = "tt.classId, tt.subjectId, tt.groupId, tt.studentId, tt.subjectName, tt.subjectCode,
                         tt.periodId, tt.groupTypeId, tt.fromDate, tt.toDate, tt.isMemberOfClass,
                         tt.periodNumber, tt.lectureAttended, tt.lectureDelivered,
                         tt.leaveTaken";
           if($orderBy=='') {
              $orderBy = " tt.studentId, tt.classId, tt.subjectId, tt.fromDate, tt.toDate, tt.periodNumber, tt.periodId, tt.groupId  ";
           }
        }
        else {
           $fieldName = " tt.classId, tt.subjectId, tt.groupId, tt.groupTypeId, tt.fromDate, tt.toDate,
                          tt.periodNumber, tt.periodId, tt.subjectName, tt.subjectCode ";
           if($orderBy=='') {
              $orderBy = " tt.classId, tt.subjectId, tt.fromDate, tt.toDate, tt.periodNumber, tt.periodId, tt.groupId,
                           tt.subjectName, tt.subjectCode ";
           }
        }

        $query = "SELECT
                         DISTINCT $fieldName
                  FROM
                     (SELECT

                            su.subjectName, su.subjectCode,
                            att.classId, att.subjectId, att.groupId, att.studentId,
                            IF(IFNULL(att.periodId,'')='','-1',att.periodId) AS periodId, gt.groupTypeId,
                            att.fromDate, att.toDate, IF(IFNULL(p.periodNumber,'')='','',p.periodNumber) AS periodNumber,
                            IF(att.isMemberOfClass=0, -1, 1) AS isMemberOfClass,
                            IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2,(ac.attendanceCodePercentage/100),att.lectureAttended)) AS lectureAttended,
                            IF(att.isMemberOfClass=0, '', att.lectureDelivered) AS lectureDelivered,
                            IFNULL(IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2, IF((ac.attendanceCodePercentage/100)=0,
                                    (SELECT
                                               DISTINCT IF(IFNULL(dl.dutyLeaveId,'')='','',1)
                                        FROM
                                                ".DUTY_LEAVE_TABLE."  dl
                                        WHERE
                                               dl.studentId = att.studentId AND
                                               dl.classId   = att.classId   AND








                                               dl.subjectId = att.subjectId AND
                                               dl.groupId   = att.groupId   AND
                                               dl.periodId  = att.periodId  AND
                                               att.fromDate = dl.dutyDate   AND
                                               att.toDate   = dl.dutyDate   AND
                                               dl.rejected  = ".DUTY_LEAVE_APPROVE."),''),'')),'') AS leaveTaken
                      FROM
                            group_type gt, `group` grp, class c, `subject` su,
                            student s INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                            LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId = $instituteId)
                            LEFT JOIN period p ON att.periodId = p.periodId
                      WHERE
                            gt.groupTypeId = grp.groupTypeId  AND
                            att.groupId   = grp.groupId       AND
                            att.subjectId = su.subjectId      AND
                            att.classId   = c.classId
                      $condition
                      $groupBy) AS tt
                  ORDER BY
                        $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getStudentOldAttendanceReport($condition='',$group='',$perCondition='',$orderBy='')  {

        global $REQUEST_DATA;
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $fieldName='';
        $groupBy='';
        if($group!='') {
          $fieldName=" ,t.groupTypeId, t.groupId";
          $groupBy=" ,tt.groupTypeId, tt.groupId";

        }

        if($orderBy=='') {
          $orderBy='studentId';
        }

       $query = "SELECT
                        t.studentId, t.classId, t.subjectTypeId, t.subjectId, t.subjectCode, t.subjectName,
                        t.studentName, t.rollNo, t.universityRollNo,
                        t.lectureAttended, t.lectureDelivered, t.leaveTaken $fieldName
                 FROM
                     (SELECT
                             tt.studentId, tt.classId, tt.subjectTypeId,  tt.subjectId, tt.subjectCode, tt.subjectName,
                             tt.studentName, tt.rollNo, tt.universityRollNo,
                             IFNULL(SUM(tt.lectureAttended),0) AS lectureAttended, IFNULL(SUM(tt.lectureDelivered),0) AS lectureDelivered,
                             IFNULL(SUM(tt.leaveTaken),0) AS leaveTaken $groupBy
                      FROM
                         (SELECT
                                att.classId, att.subjectId, att.groupId, att.studentId, su.subjectTypeId, su.subjectCode, su.subjectName,
                                IF(IFNULL(att.periodId,'')='','-1',att.periodId) AS periodId, gt.groupTypeId,
                                CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                                IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                                att.fromDate, att.toDate, IF(IFNULL(p.periodNumber,'')='','',p.periodNumber) AS periodNumber,
                                IF(att.isMemberOfClass=0, -1, 1) AS isMemberOfClass,
                                IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2,(ac.attendanceCodePercentage/100),att.lectureAttended)) AS lectureAttended,
                                IF(att.isMemberOfClass=0, '', att.lectureDelivered) AS lectureDelivered,
                                IFNULL(IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2, IF((ac.attendanceCodePercentage/100)=0,
                                        (SELECT
                                                   DISTINCT IF(IFNULL(dl.dutyLeaveId,'')='','',1)
                                            FROM
                                                    ".DUTY_LEAVE_TABLE."  dl
                                            WHERE
                                                   dl.studentId = att.studentId AND
                                                   dl.classId   = att.classId   AND
                                                   dl.subjectId = att.subjectId AND
                                                   dl.groupId   = att.groupId   AND
                                                   dl.periodId  = att.periodId  AND
                                                   att.fromDate = dl.dutyDate   AND
                                                   att.toDate   = dl.dutyDate   AND
                                                   dl.rejected  = ".DUTY_LEAVE_APPROVE."),''),'')),'') AS leaveTaken
                          FROM
                                group_type gt, `group` grp, class c, `subject` su,
                                student s INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                                LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId = $instituteId)
                                LEFT JOIN period p ON att.periodId = p.periodId
                          WHERE
                                gt.groupTypeId = grp.groupTypeId  AND
                                att.groupId   = grp.groupId       AND
                                att.subjectId = su.subjectId      AND
                                att.classId   = c.classId
                          $condition) AS tt
                       GROUP BY
                          tt.studentId, tt.classId, tt.subjectId $groupBy) AS t
                  $perCondition
                  ORDER BY
                        $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getStudentAttendanceReport($condition='',$orderBy='',$consolidated='',$limit='',$percentCondition='')  {

        global $REQUEST_DATA;
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
		
		$lowerMedicalLimit=$sessionHandler->getSessionVariable('MEDICAL_LEAVE_CALCULATION_LIMIT');
		$higherMedicalLimit=$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
		
        if($orderBy=='') {
          $orderBy = "subjectName";
        }

        $groupByField1 ='';
        $groupByField2 ='';
        $groupBy='';
        if($consolidated=='') {
          $groupByField1 = " ,tt.groupId, tt.groupName";
          $groupByField2 = " ,t.groupId, t.groupName";
          $groupBy = " ,tt.groupId";
        }

        $query = "SELECT
                        t.studentId, t.classId, t.subjectId, t.subjectCode, t.subjectName, t.className, t.studentName,
                        IFNULL(t.employeeName,'".NOT_APPLICABLE_STRING."') AS employeeName,
                        t.subjectTypeId, t.subjectTypeName, t.rollNo, t.universityRollNo,
                        CONCAT(t.subjectName,' (',t.subjectCode,')') AS subjectName1, t.periodName,
                        t.fromDate, t.toDate, t.lectureAttended AS attended, t.lectureDelivered AS delivered,
                        t.leaveTaken, t.medicalLeaveTaken,
                        IF(t.lectureDelivered=0,0,((t.lectureAttended+t.leaveTaken)/t.lectureDelivered)*100) AS per,
                        IF(t.lectureDelivered=0,0,(t.lectureAttended/t.lectureDelivered)*100) AS per1
                        $groupByField2
                  FROM
                     (SELECT
                             tt.studentId, tt.classId, tt.subjectId, tt.subjectCode, tt.subjectName, tt.className, tt.studentName,
                             tt.rollNo, tt.universityRollNo, MIN(tt.fromDate) AS fromDate, MAX(tt.toDate) AS toDate,  tt.periodName,
                             GROUP_CONCAT(DISTINCT tt.employeeName SEPARATOR ', ')  AS employeeName,
                             tt.subjectTypeId, tt.subjectTypeName,
                             IFNULL(SUM(tt.lectureAttended),0) AS lectureAttended, IFNULL(SUM(tt.lectureDelivered),0) AS lectureDelivered,
                             IFNULL(SUM(tt.leaveTaken),0) AS leaveTaken ,
                             IFNULL(SUM(tt.medicalleaveTaken),0) AS medicalleaveTaken 
                             $groupByField1
                      FROM
                         (SELECT
                                att.classId, att.subjectId, att.groupId, att.studentId, su.subjectCode, su.subjectName, c.className,
                                CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                                IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                                st.subjectTypeId, st.subjectTypeName,
                                IF(IFNULL(att.periodId,'')='','-1',att.periodId) AS periodId, gt.groupTypeId, grp.groupName,
                                att.fromDate, att.toDate, IF(IFNULL(p.periodNumber,'')='','',p.periodNumber) AS periodNumber,
                                IF(att.isMemberOfClass=0, -1, 1) AS isMemberOfClass,
                                IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2,(ac.attendanceCodePercentage/100),att.lectureAttended)) AS lectureAttended,
                                IF(att.isMemberOfClass=0, '', att.lectureDelivered) AS lectureDelivered, sp.periodName,
                                (SELECT
                                      GROUP_CONCAT(DISTINCT e.employeeName,' (',e.employeeCode,')' SEPARATOR ', ')
                                 FROM
                                      employee e,  ".TIME_TABLE_TABLE." tt
                                 WHERE
                                      e.employeeId = tt.employeeId AND tt.employeeId = att.employeeId AND
                                      tt.classId=att.classId  AND tt.subjectId = att.subjectId AND
                                      tt.groupId=att.groupId AND tt.toDate IS NULL) AS employeeName,
                                IFNULL(IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2, IF((ac.attendanceCodePercentage/100)=0,
                                        (SELECT
                                                   DISTINCT IF(IFNULL(CONCAT(ml.studentId, ml.classId, ml.subjectId, ml.groupId,
                                        		ml.periodId, ml.medicalLeaveDate, ml.approvedStatus),'')='',IF(IFNULL(CONCAT(dl.studentId,
                                        		dl.classId,dl.subjectId,dl.groupId,dl.periodId,dl.dutyDate,dl.rejected),'')='','',1),'')
                                            FROM
                                                    ".DUTY_LEAVE_TABLE."  dl LEFT JOIN  ".MEDICAL_LEAVE_TABLE."  ml ON 
								                                   dl.studentId = ml.studentId AND
								                                   dl.classId   = ml.classId   AND
								                                   dl.subjectId = ml.subjectId AND
								                                   dl.groupId   = ml.groupId   AND
								                                   dl.periodId  = ml.periodId  AND
								                                   dl.dutyDate = ml.medicalLeaveDate AND
								                                   ml.approvedStatus  = ".MEDICAL_LEAVE_APPROVE."  
                                            WHERE
                                                   dl.studentId = att.studentId AND
                                                   dl.classId   = att.classId   AND
                                                   dl.subjectId = att.subjectId AND
                                                   dl.groupId   = att.groupId   AND
                                                   dl.periodId  = att.periodId  AND
                                                   att.fromDate = dl.dutyDate   AND
                                                   att.toDate   = dl.dutyDate   AND
                                                   dl.rejected  = ".DUTY_LEAVE_APPROVE."),''),'')),'') AS leaveTaken,
                                                   
                         		IFNULL(IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2, IF((ac.attendanceCodePercentage/100)=0,
                                        (SELECT
                                                   DISTINCT IF(IFNULL(CONCAT(dl.studentId,dl.classId,dl.subjectId,dl.groupId,dl.periodId,
                                        		dl.dutyDate,dl.rejected),'')='',IF(IFNULL(CONCAT(ml.studentId, ml.classId,ml.subjectId,
                                        		ml.groupId, ml.periodId,ml.medicalLeaveDate,ml.approvedStatus),'')='','',1),'')
                                            FROM

                                                    ".MEDICAL_LEAVE_TABLE."  ml LEFT JOIN  ".DUTY_LEAVE_TABLE."  dl ON 
								                                   dl.studentId = ml.studentId AND
								                                   dl.classId   = ml.classId   AND
								                                   dl.subjectId = ml.subjectId AND
								                                   dl.groupId   = ml.groupId   AND
								                                   dl.periodId  = ml.periodId  AND
								                                   dl.dutyDate = ml.medicalLeaveDate AND
								                                   dl.rejected  = ".DUTY_LEAVE_APPROVE."   
                                            WHERE
                                                   ml.studentId = att.studentId AND
                                                   ml.classId   = att.classId   AND
                                                   ml.subjectId = att.subjectId AND
                                                   ml.groupId   = att.groupId   AND
                                                   ml.periodId  = att.periodId  AND
                                                   att.fromDate = ml.medicalLeaveDate  AND
                                                   att.toDate   = ml.medicalLeaveDate  AND
                                                   ml.approvedStatus  = ".MEDICAL_LEAVE_APPROVE."),''),'')),'') AS medicalLeaveTaken                         
                                                   
                          FROM
                                group_type gt, `group` grp, class c, study_period sp, subject_type st, `subject` su,
                                student s INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                                LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId = $instituteId)
                                LEFT JOIN period p ON att.periodId = p.periodId
                          WHERE
                                sp.studyPeriodId = c.studyPeriodId AND
                                gt.groupTypeId = grp.groupTypeId  AND
                                att.groupId   = grp.groupId       AND
                                att.subjectId = su.subjectId      AND
                                st.subjectTypeId = su.subjectTypeId AND
                                att.classId   = c.classId
                          $condition) AS tt
                       GROUP BY
                          tt.studentId, tt.classId, tt.subjectId $groupBy) AS t
                  $percentCondition
                  ORDER BY
                        $orderBy $limit";
	
        $resultArray= SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        if($resultArray===false) {
  			return false;
		}
		else{
			if($consolidated!='') { 
				for($i=0;$i<count($resultArray);$i++) {
					$resultArray[$i]['transferMarksMedicalLeaveTaken'] = 0;
          			if($resultArray[$i]['per'] >= $lowerMedicalLimit && $resultArray[$i]['per'] <= $higherMedicalLimit) { 
          				$medicalLeaveTaken = $resultArray[$i]['medicalLeaveTaken'];
          				
          				for($j=1;$j<=$medicalLeaveTaken;$j++){
          				    $attend = $resultArray[$i]['attended'];
          				    $leaveTaken = $resultArray[$i]['leaveTaken'];
          				    $delivered = $resultArray[$i]['delivered'];
          				    if($delivered>0) {	
							  $resultArray[$i]['per']=(($attend+$leaveTaken+$j)/$delivered)*100;
							  $resultArray[$i]['transferMarksMedicalLeaveTaken'] = $j;
							  if($resultArray[$i]['per']>=$higherMedicalLimit){
                                $resultArray[$i]['per']=$higherMedicalLimit;  
								break;
							  }
							}
          				}
          			}
          		}
          		return $resultArray; 
			}
			else{
				return $resultArray;
			}
		}
    }
    
//display the duty leave event , date and period in a div
    public function getDutyLeaveValue($condition='',$orderBy='dutyDate')  {
    	global $REQUEST_DATA;
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        if($orderBy=='') {
          $orderBy='dutyDate'; 
        }
        
        $query = "SELECT
      				   DISTINCT   
                       dl.dutyDate, dl.periodId, de.eventTitle, su.subjectName, su.subjectCode,
                       IFNULL(IF((ac.attendanceCodePercentage/100)=0,
                        IF(IFNULL(ml.medicalLeaveId,'')='',IF(IFNULL(dl.dutyLeaveId,'')='',0,1),0),0),0) AS nonConflictedApproved
				  FROM
       				   `subject` su,duty_event de, 
                       ".ATTENDANCE_TABLE." att
                       LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId = $instituteId),  
                        ".DUTY_LEAVE_TABLE."  dl LEFT JOIN  ".MEDICAL_LEAVE_TABLE."  ml ON 
                       (dl.studentId = ml.studentId AND
                        dl.classId   = ml.classId   AND
                        dl.subjectId = ml.subjectId AND
                        dl.groupId   = ml.groupId   AND
                        dl.periodId  = ml.periodId  AND
                        dl.dutyDate = ml.medicalLeaveDate AND
                        ml.approvedStatus = ".MEDICAL_LEAVE_APPROVE.") 
				  WHERE
                       att.attendanceType= '2' AND 
                       att.isMemberOfClass != '0' AND
				  	   su.subjectId=dl.subjectId AND 
				  	   dl.eventId=de.eventId AND
					   dl.studentId = att.studentId AND
					   dl.classId   = att.classId   AND
					   dl.subjectId = att.subjectId AND
					   dl.groupId   = att.groupId   AND
					   dl.periodId  = att.periodId  AND
					   att.fromDate = dl.dutyDate   AND
					   att.toDate   = dl.dutyDate   AND
					   dl.rejected  = ".DUTY_LEAVE_APPROVE." AND
                       IFNULL(IF((ac.attendanceCodePercentage/100)=0,
                        IF(IFNULL(ml.medicalLeaveId,'')='',IF(IFNULL(dl.dutyLeaveId,'')='',0,1),0),0),0) > '0'
				  $condition	
				  GROUP BY
				  		dl.dutyDate, dl.periodId, su.subjectName, su.subjectCode   
			  	  ORDER BY
			  	  	   $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        
    }

//display medical leave date and period in a div
    public function getMedicalLeaveValue($condition='',$orderBy='medicalLeaveDate')  {   
    	global $REQUEST_DATA;
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        if($orderBy=='') {
          $orderBy='medicalLeaveDate'; 
        }
        
        $query = "SELECT
                       DISTINCT   
                       ml.medicalLeaveDate, ml.periodId, su.subjectName, su.subjectCode,
                       IFNULL(IF((ac.attendanceCodePercentage/100)=0,
                        IF(IFNULL(dl.dutyLeaveId,'')='',IF(IFNULL(ml.medicalLeaveId,'')='',0,1),0),0),0) AS nonConflictedApproved
                  FROM
                       `subject` su,duty_event de, 
                       ".ATTENDANCE_TABLE." att
                       LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId = $instituteId),  
                        ".MEDICAL_LEAVE_TABLE."  ml LEFT JOIN  ".DUTY_LEAVE_TABLE."  dl ON 
                       (dl.studentId = ml.studentId AND
                       dl.classId   = ml.classId   AND
                       dl.subjectId = ml.subjectId AND
                       dl.periodId  = ml.periodId  AND
                       dl.dutyDate = ml.medicalLeaveDate AND
                       dl.rejected  = ".DUTY_LEAVE_APPROVE."  ) 
                  WHERE
                       att.attendanceType= '2' AND 
                       att.isMemberOfClass != '0' AND
                       su.subjectId=ml.subjectId AND
                       ml.studentId = att.studentId AND
                       ml.classId   = att.classId   AND
                       ml.subjectId = att.subjectId AND
                       ml.periodId  = att.periodId  AND
                       att.fromDate = ml.medicalLeaveDate  AND
                       att.toDate   = ml.medicalLeaveDate  AND
                       ml.approvedStatus  = ".MEDICAL_LEAVE_APPROVE." AND
                       IFNULL(IF((ac.attendanceCodePercentage/100)=0,
                        IF(IFNULL(dl.dutyLeaveId,'')='',IF(IFNULL(ml.medicalLeaveId,'')='',0,1),0),0),0) > '0'
                  $condition       
                    ORDER BY
                           $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        
        
    }

    public function getEmployeeTeachSubjectList($condition='',$orderBy='')  {

        global $REQUEST_DATA;
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                       tt.subjectId, tt.subjectName, tt.subjectCode, IFNULL(tt.employeeName,'".NOT_APPLICABLE_STRING."') AS employeeName
                  FROM
                     (SELECT
                            su.subjectId, su.subjectName, su.subjectCode,
                            GROUP_CONCAT(DISTINCT CONCAT(emp.employeeName,' (',emp.employeeCode,')') ORDER BY emp.employeeName SEPARATOR ', ') AS employeeName
                      FROM
                            employee emp, subject su,  ".TIME_TABLE_TABLE." tt, `group` g
                      WHERE
                            tt.instituteId = $instituteId AND

                            tt.toDate IS NULL AND
                            g.groupId = tt.groupId AND
                            tt.subjectId = su.subjectId AND
                            emp.employeeId = tt.employeeId
                            $condition
                      GROUP BY
                            su.subjectId
                      ORDER BY
                            subjectTypeId, subjectCode) AS tt ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getAllFeeClassData($condition='',$orderBy='cls.degreeId,cls.branchId,cls.studyPeriodId') {

        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                         DISTINCT
                                cls.classId, cls.className
                  FROM
                        class cls
                  WHERE
                        cls.instituteId='".$instituteId."' AND
                        cls.isActive IN (1,2,3)
                  ORDER BY
                        $orderBy DESC";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
     public function getStudentAllFeeClasses($studentId='',$classId='',$condition='') {   
     
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $classId = $sessionHandler->getSessionVariable('ClassId');
        $studentId = $sessionHandler->getSessionVariable('StudentId');
        
        $batchId = $sessionHandler->getSessionVariable('ClassBatchId'); 
        $degreeId = $sessionHandler->getSessionVariable('ClassDegreeId'); 
        $branchId = $sessionHandler->getSessionVariable('ClassBranchId'); 
        $migrationStudyPeriod = $sessionHandler->getSessionVariable('StudentMigrationStudyPeriod');
        
        $studentAllClass = trim($sessionHandler->getSessionVariable('StudentAllClass')); 
        
        
        if($studentId==''){
          $studentId = 0;
        }
        
	    if($migrationStudyPeriod==''){
	      $migrationStudyPeriod = 0;
	    }
        
        if($batchId==''){
          $batchId = 0;
        }
        if($degreeId==''){
          $degreeId = 0;
        }
        if($branchId==''){
          $branchId = 0;
        }
        
        if($studentAllClass=='') {
          $studentAllClass=0;  
        }
        
        // Fetch Academic Fee == $query1
        $query ="SELECT    
                        DISTINCT frm.classId AS classId,cls.className AS className
                     
                    FROM     
                        `fee_cycle_new` fcn ,  class cls, study_period sp ,
                        `fee_head_values_new` frm LEFT JOIN fee_receipt_details frd ON frm.classId = frd.classId AND frd.feeType IN(1,4) 
                          AND frd.isDelete = 0 
                    WHERE 
                        cls.classId = frm.classId   
                        AND fcn.feeCycleId = frm.feeCycleId
			            AND cls.studyPeriodId = sp.studyPeriodId                       
			            AND (INSTR('$studentAllClass',CONCAT('~',frm.classId,'~'))>0)
			            AND sp.periodValue >= '$migrationStudyPeriod'
			            AND IFNULL(frd.feeReceiptId,0) = 0
			        	
                    ORDER BY 
                       frm.classId";
		 $query1 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                                 
        
        
         // Fetch Hostel Fee  == $query2
         $query ="SELECT 
         				 DISTINCT hs.classId AS classId,hc.className AS className   
                       
                  FROM     
                        `fee_cycle_new` f , class hc,
                         `hostel_students` hs LEFT JOIN fee_receipt_details frd ON hs.classId = frd.classId AND frd.feeType IN(3,4) 
                         AND frd.isDelete = 0 
                  WHERE   
                        f.feeCycleId = hs.feeCycleId 
                        AND hc.classId = hs.classId
			            AND IFNULL(frd.feeReceiptId,0) = 0
                        AND hs.studentId = '$studentId'
                  ORDER BY
                       hs.classId";
          $query2 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                                                   
          
          
          // Fetch Transport Fee == $query3
          $query ="SELECT  
          				 DISTINCT brsm.classId AS classId,cc.className AS className                          
                    FROM     
                        `fee_cycle_new` ff ,class cc,
                         `bus_route_student_mapping` brsm LEFT JOIN fee_receipt_details frd ON brsm.classId = frd.classId AND frd.feeType IN(2,4) 
                         AND frd.isDelete = 0 
                    WHERE   
                        ff.feeCycleId = brsm.feeCycleId 
                        AND brsm.classId = cc.classId
			           	 AND IFNULL(frd.feeReceiptId,0) = 0		
                        AND brsm.studentId = '$studentId'      
                  ORDER BY 
                      brsm.classId";
          $query3 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");              
                        
          
          // Academic 
          $valueArray = array();
          for($i=0;$i<count($query1);$i++) {
            $valueArray[]= array("classId"=>$query1[$i]['classId'],
                                 "className"=>$query1[$i]['className'],
                                 "academic"=>1,
                                 "hostel"=>0,
                                 "transport"=>0
                                 );
          }                      
           
          // Hostel
          for($i=0;$i<count($query2);$i++) {
            $classId = $query2[$i]['classId']; 
            $findId='';
            for($j=0;$j<count($valueArray);$j++) {
              if($valueArray[$j]['classId']==$classId) {
                $findId='1';
                $valueArray[$j]['hostel']=1; 
                break;  
              }
            }
            if($findId=='') {
               $valueArray[]= array("classId"=>$query2[$i]['classId'],
                                    "className"=>$query2[$i]['className'],
                                    "academic"=>0,
                                    "hostel"=>1,
                                    "transport"=>0
                                    );
            }
          }
          
          // Transport
          for($i=0;$i<count($query3);$i++) {
            $classId = $query3[$i]['classId'];  
            $findId='';
            for($j=0;$j<count($valueArray);$j++) {
              if($valueArray[$j]['classId']==$classId) {
                $findId='1';
                $valueArray[$j]['transport']=1; 
                break;  
              }
            }
            if($findId=='') {
               $valueArray[]= array("classId"=>$query3[$i]['classId'],
                                    "className"=>$query3[$i]['className'],
                                    "academic"=>0,
                                    "hostel"=>0,
                                    "transport"=>1
                                    );
            }
          }
          return $valueArray;            
     }
    
    public function getShowSlotPeriods($periodSlotId = '') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 

                        DISTINCT a.periodId, a.periodNumber, a.startTime, a.startAmPm, a.endTime, a.endAmPm 
                  FROM 
                        period a, period_slot b 
                  WHERE 
                        a.periodSlotId = b.periodSlotId 
                        AND b.instituteId  = '$instituteId' 
                        AND b.periodSlotId = '$periodSlotId' 
                  ORDER BY
                        a.periodId ASC";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getCheckAttendanceStatus($condition = '') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        DISTINCT fromDate
                  FROM 
                        ".ATTENDANCE_TABLE." att
                  $condition";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
////---------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO VIEW IN DASHBOARD
//CLASS NAME STUDENT NAME COURSES.........
//USER : Aarti   DATE: 20/01/12***********
//---------------------------------------------------------------------

public function getStudentSubjectDetails($classId='',$studentId='',$orderBy='subjectCode') {  
        
        global $sessionHandler;  
         
       
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                         DISTINCT s.subjectId,s.subjectName,s.subjectCode
                  FROM   
                        `student` sg, class c ,`subject` s,subject_to_class stc
                  WHERE
                         sg.classId= c.classId
                         AND c.sessionId = '$sessionId'
                         AND c.instituteId = '$instituteId'
                         AND sg.studentId= '$studentId'
                         AND c.classId=stc.classId
                         AND stc.subjectId=s.subjectId
                         AND c.classId='$classId'
                  GROUP BY 
                         s.subjectId
                  UNION
                  SELECT
                        DISTINCT s.subjectId,s.subjectName,s.subjectCode
                  FROM    
                        `student_optional_subject` sg, class c,`subject` s
                  WHERE
                        sg.classId = c.classId
                        AND c.sessionId = '$sessionId'  
                        AND c.instituteId = '$instituteId' 
                        AND sg.studentId= '$studentId'   
                        AND sg.subjectId = s.subjectId
                       AND c.classId = '$classId'  
                  GROUP BY 
                        s.subjectId
                  ORDER BY  
                        $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function fetchAllDegree($condition='',$orderBy='degreeAbbr'){
    	$query ="SELECT
    			        DISTINCT degreeId,degreeAbbr 
    	         FROM	
                        `degree`
    		     $condition
    		     ORDER BY $orderBy ASC";
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function fetchAllClassDegree($condition='',$orderBy='degreeAbbr'){
       
        global $sessionHandler;  
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query ="SELECT
                      DISTINCT d.degreeId, d.degreeAbbr 
                 FROM    
                     `degree` d, class c
                 WHERE
                      d.degreeId = c.degreeId  AND    
                      c.instituteId  = '".$instituteId."'
                      $condition
                 ORDER BY 
                      $orderBy ASC";
                      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getBusRoutes($condition='',$orderBy='route'){
    	$query ="SELECT
    			DISTINCT	
    			busRouteId , routeName AS route
    		FROM	`bus_route_new`
    		$condition
    		ORDER BY $orderBy ASC
    		";
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function fetchBusStopHead($conditions='',$orderBy='headName'){
    	$query ="SELECT
    			DISTINCT	
    			busStopHeadId , headName
    		FROM	`bus_stop_head`
    		$conditions
    		ORDER BY $orderBy ASC
    		";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE HEAD
//
//orderBy: on which column to sort
//
// Author :Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getFeeHeadNew($orderBy='headName',$condition='') {
        global $sessionHandler;
	    $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT

                        feeHeadId,headName
                  FROM
                        `fee_head_new`
                  WHERE
                        instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                   AND	sessionId = '".$sessionHandler->getSessionVariable('SessionId')."'
                  $condition
                  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Active Batch
//orderBy: on which column to sort
//
// Author :Nishu Bindal
// Created on : (17.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getActiveBatch($orderBy=' batchName') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');


        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT
				 ba.batchId,
				 ba.batchName

                 FROM
                 	`batch` ba,`class` cls
                 WHERE
			 ba.batchId = cls.batchId 
			 AND	cls.isActive = 1
			 AND	cls.sessionId = '".$sessionId ."' 
			 AND	cls.instituteId = ba.instituteId
			 AND	ba.instituteId = '".$instituteId."'
			 GROUP BY ba.batchId

			 ORDER BY

			 $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
       //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF HOSTELS
//
//orderBy: on which column to sort
//
// Author :Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getHostelNames($orderBy='hostelName',$condition='') {
        global $sessionHandler;
	    $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT

                        hostelId,hostelName

                  FROM
                        `hostel`
           
                  $condition
                  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
 //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF HOSTEL TYPES
//
//orderBy: on which column to sort
//
// Author :Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getRoomTypes($orderBy='roomType',$condition='') {
        global $sessionHandler;
	    $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT

                        hostelRoomTypeId ,roomType
                  FROM
                        `hostel_room_type`
                  $condition
                  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
 //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF BUS STOP HEAD NAME
//orderBy: on which column to sort
// Author :Nishu Bindal
// Created on : (12.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getbusStopCityName($orderBy='cityName') {
        global $sessionHandler;
	    $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT

                        busStopCityId ,cityName
                  FROM
                       `bus_stop_city`
                  ORDER BY $orderBy";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    
    public function getFinalAttendance($condition='',$orderBy='',$consolidated='',$limit='',$percentCondition='',$classId='',$subjectId='',$studentId='') {

        global $REQUEST_DATA;
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $lowerMedicalLimit=$sessionHandler->getSessionVariable('MEDICAL_LEAVE_CALCULATION_LIMIT');
        $higherMedicalLimit=$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
        
        
        $groupByField1 ='';
        $groupBy='';
        if($consolidated=='') {
          $groupByField1 = " ,grp.groupId, grp.groupName";
          $groupBy = " ,att.groupId";
        }
        
        if($orderBy=='') {
          $orderBy = "studentName";
        }
        
        if($classId=='') {
          $classId='0';
        }
        
        if($subjectId=='') {
          $subjectId='0';
        }
        
        $studentCondition = " AND tt.classId = '$classId' ";
        if($subjectId!='') {
          $studentCondition .= " AND tt.subjectId = '$subjectId' ";  
        }
        if($studentId!='') {
          $studentCondition .= " AND s.studentId = '$studentId' ";   
        }

        $query = "SELECT
                        DISTINCT sg.studentId, sg.classId, 
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo
                  FROM
                        `group` grp, class c,  ".TIME_TABLE_TABLE." tt, student_groups sg, student s, subject_to_class stc
                  WHERE
                        grp.classId = c.classId AND
                        grp.groupId = tt.groupId AND
                        tt.toDate IS NULL AND
                        tt.groupId = sg.groupId        AND
                        tt.subjectId = stc.subjectId   AND
                        tt.instituteId = c.instituteId AND
                        tt.sessionId = c.sessionId     AND
                        sg.studentId = s.studentId     AND
                        sg.classId =  c.classId        AND
                        stc.classId = c.classId        AND
                        c.instituteId = $instituteId   AND
                        c.sessionId = $sessionId 
                        $studentCondition     
                  UNION
                  SELECT
                        DISTINCT s.studentId, ss.classId, 
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                        IF(IFNULL(s.universityRollNo,'')='','---',s.universityRollNo) AS universityRollNo
                  FROM
                        `group` grp, class c,  ".TIME_TABLE_TABLE." tt, student s, student_optional_subject ss
                  WHERE
                        grp.classId = c.classId AND
                        grp.groupId = tt.groupId AND
                        tt.toDate IS NULL AND
                        ss.groupId = tt.groupId        AND
                        ss.subjectId = tt.subjectId    AND
                        tt.instituteId = c.instituteId AND
                        tt.sessionId = c.sessionId     AND
                        ss.studentId = s.studentId     AND
                        ss.classId =  c.classId        AND
                        c.instituteId = $instituteId   AND
                        c.sessionId = $sessionId 
                        $studentCondition
                  ORDER BY
                        $orderBy
                  $limit ";

        $studentArray = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
       
        $studentIdList = "0";
        for($i=0;$i<count($studentArray);$i++)  {
          $studentIdList .=",".$studentArray[$i]['studentId'];  
        }
        
      
        $query = "SELECT 
                        att.classId, att.studentId, att.subjectId,
                        ROUND(SUM(IF( att.isMemberOfClass =0, 0,
                        IF(att.attendanceType =2,(ac.attendanceCodePercentage /100), att.lectureAttended ) ) ),0) AS lectureAttended ,
                        SUM(IF(isMemberOfClass=0,0, lectureDelivered))  as lectureDelivered
                        $groupByField1
                 FROM  
                        `group` grp,  ".ATTENDANCE_TABLE." att  
                        LEFT JOIN attendance_code ac ON ac.attendanceCodeId = att.attendanceCodeId 
                 WHERE 
                        grp.classId = att.classId AND
                        grp.groupId = att.groupId AND
                        att.studentId IN ($studentIdList) 
                        $condition       
                 GROUP BY 
                        att.studentId $groupBy
                 ORDER BY
                        att.studentId";
                        
        $attendanceResultArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        
        $attendanceArray = array();
        foreach($attendanceResultArray as $record) {
            $classId = $record['classId'];
            $studentId = $record['studentId'];
            $subjectId = $record['subjectId'];
            $lectureAttended = $record['lectureAttended'];
            $lectureDelivered = $record['lectureDelivered'];
            $attendanceArray[$studentId][$subjectId]['lectureAttended']= $lectureAttended;
            $attendanceArray[$studentId][$subjectId]['lectureDelivered']= $lectureDelivered;
        }
        
        
        $query = "SELECT
                       SUM(t.leavesTaken) AS leavesTaken, t.studentId, t.classId, t.subjectId     
                  FROM  
                      (SELECT
                          DISTINCT a.classId, a.studentId, a.subjectId, a.groupId, a.periodId,  a.dutyDate, 
                          IF(IFNULL(ml.medicalLeaveId,'')='',IF(att.isMemberOfClass=0,0,IF(att.attendanceType=2,1,0)),0) leavesTaken
                       FROM
                           ".ATTENDANCE_TABLE." att LEFT JOIN  attendance_code ac1 ON ac1.attendanceCodeId = att.attendanceCodeId, 
                           ".DUTY_LEAVE_TABLE."  a LEFT JOIN  ".MEDICAL_LEAVE_TABLE."  ml ON a.studentId = ml.studentId AND
                                                        a.classId   = ml.classId   AND
                                                        a.subjectId = ml.subjectId AND
                                                        a.groupId   = ml.groupId   AND
                                                        a.periodId  = ml.periodId  AND
                                                        a.dutyDate = ml.medicalLeaveDate AND
                                                        ml.approvedStatus  = ".MEDICAL_LEAVE_APPROVE."  
                       WHERE
                          att.classId = a.classId AND att.studentId = a.studentId AND att.subjectId = a.subjectId AND
                          att.periodId = a.periodId   AND att.groupId=a.groupId AND
                          (a.dutyDate = att.fromDate  AND a.dutyDate = att.toDate) AND
                          att.studentId IN ($studentIdList) AND
                          a.rejected= ".DUTY_LEAVE_APPROVE."
                          $condition) AS t
                   GROUP BY 
                        t.studentId, t.classId, t.subjectId 
                   ORDER BY
                        t.studentId, t.classId, t.subjectId ";
        $dutyLeaveResultArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                        

        $dutyLeaveArray = array();
        foreach($dutyLeaveResultArray as $record) {
          $classId = $record['classId'];
          $studentId = $record['studentId'];
          $subjectId = $record['subjectId'];
          $leavesTaken = $record['leavesTaken'];
          $dutyLeaveArray[$studentId][$subjectId]['leavesTaken']= $leavesTaken;
        }
        
        
        $medicalLeaveArray = array(); 
        if($consolidated!='') {  
            $query = "SELECT
                           SUM(t.leavesTaken) AS leavesTaken, t.studentId, t.classId, t.subjectId     
                      FROM 
                          (SELECT
                              DISTINCT a.classId, a.studentId, a.subjectId,  
                              IF(IFNULL(dl.dutyLeaveId,'')='',IF(att.isMemberOfClass=0,0,IF(att.attendanceType=2,1,0)),0) leavesTaken
                           FROM
                              ".ATTENDANCE_TABLE." att LEFT JOIN  attendance_code ac1 ON ac1.attendanceCodeId = att.attendanceCodeId ,
                               ".MEDICAL_LEAVE_TABLE."  a LEFT JOIN  ".DUTY_LEAVE_TABLE."  dl ON dl.studentId = a.studentId AND
                                                         dl.classId   = a.classId   AND
                                                         dl.subjectId = a.subjectId AND
                                                         dl.groupId   = a.groupId   AND
                                                         dl.periodId  = a.periodId  AND
                                                         dl.dutyDate = a.medicalLeaveDate AND
                                                         dl.rejected  = ".DUTY_LEAVE_APPROVE."   
                           WHERE
                              att.classId = a.classId AND att.studentId = a.studentId AND att.subjectId = a.subjectId AND
                              att.periodId = a.periodId   AND att.groupId=a.groupId AND
                              (a.medicalLeaveDate = att.fromDate AND a.medicalLeaveDate = att.toDate) AND
                              att.studentId IN ($studentIdList) AND
                              a.approvedStatus  = ".MEDICAL_LEAVE_APPROVE." 
                              $condition) AS t
                      GROUP BY 
                            t.studentId, t.classId, t.subjectId 
                      ORDER BY
                            t.studentId, t.classId, t.subjectId";
           $medicalLeaveResultArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                        
        
          
           foreach($medicalLeaveResultArray as $record) {
              $classId = $record['classId'];
              $studentId = $record['studentId'];
              $subjectId = $record['subjectId'];
              $leavesTaken = $record['leavesTaken'];
              $medicalLeaveArray[$studentId][$subjectId]['leavesTaken']= $leavesTaken;
           }
        }
        
      
       // Student List
       $resultArray = array();
       for($i=0;$i<count($studentArray);$i++) {
          $ttStudentId = $studentArray[$i]['studentId'];
          $resultArray[$i]['studentId'] = $studentArray[$i]['studentId'];
          $resultArray[$i]['classId'] = $studentArray[$i]['classId'];
          $resultArray[$i]['studentName'] = $studentArray[$i]['studentName'];
          $resultArray[$i]['rollNo'] = $studentArray[$i]['rollNo'];
          $resultArray[$i]['universityRollNo'] = $studentArray[$i]['universityRollNo'];
          $resultArray[$i]['subjectId']  = $subjectId;  
          $resultArray[$i]['lectureAttended']  = 0;
          $resultArray[$i]['lectureDelivered'] = 0;
          $resultArray[$i]['dutyLeave']  = 0;
          $resultArray[$i]['medicalLeave'] = 0;
          $resultArray[$i]['percentage'] = 0;
       
          // Fetch Stduent Attendance List
          if(count($attendanceArray[$ttStudentId][$subjectId]) > 0 ) {
             $attended = $attendanceArray[$ttStudentId][$subjectId]['lectureAttended'];   
             $delivered = $attendanceArray[$ttStudentId][$subjectId]['lectureDelivered'];
             
             if($delivered > 0) {   
               // Fetch Stduent Duty Leave List
               $dutyLeaveTaken='0';  
               if(count($dutyLeaveArray[$ttStudentId][$subjectId]['leavesTaken']) >0)  {
                 $dutyLeaveTaken = $dutyLeaveArray[$ttStudentId][$subjectId]['leavesTaken'];  
               }
               if($dutyLeaveTaken=='') {
                 $dutyLeaveTaken='0';  
               }
               $attended = $attended + $dutyLeaveTaken; 
               $per = "0";
               if($delivered>0) {
                 $per = ceil($attended / $delivered * 100); 
               }
               
               // Fetch Stduent Medical Leave List 
               $medicalLeaveTaken=0; 
               if($consolidated!='') { 
                  if($per >= $lowerMedicalLimit && $per <= $higherMedicalLimit) { 
                      if(count($medicalLeaveArray[$ttStudentId][$subjectId]['leavesTaken']) >0) {
                         $medicalLeaveTaken = $medicalLeaveArray[$ttStudentId][$subjectId]['leavesTaken'];  
                      }
                      if($medicalLeaveTaken=='') {
                        $medicalLeaveTaken=0; 
                      }
                      if($medicalLeaveTaken > 0 ) {
                          $dif = $higherMedicalLimit - $per;    
                          $medicalCnt = intval($dif*$delivered/100);  
                          if($medicalLeaveTaken > $medicalCnt) {
                            $per = 75;   
                            $medicalLeaveTaken = $medicalCnt;
                          }
                          else {
                             $attended = $attended + $medicalLeaveTaken;
                             $per = ceil($attended / $delivered * 100); 
                          }
                      }
                  }
               }
               $attended = $attendanceArray[$ttStudentId][$subjectId]['lectureAttended']; 
             }      
             else {
                $attended=0; 
                $delivered=0;
                $dutyLeaveTaken=0;
                $medicalLeaveTaken=0; 
                $per=0; 
             }
             
             $resultArray[$i]['lectureAttended'] = $attended;
             $resultArray[$i]['lectureDelivered'] = $delivered;
             $resultArray[$i]['dutyLeave']  = $dutyLeaveTaken;
             $resultArray[$i]['medicalLeave'] = $medicalLeaveTaken;
             $resultArray[$i]['percentage'] = $per; 
             /*
             echo "<pre>";
              print_r($resultArray);
             die;
             */
          }
       }
       
       return $resultArray;
    }
//---------------------------------------------------------------------------------------------

    public function getAllPeriods($condition='') {
        
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT  
                        p.periodSlotId, p.periodId, p.periodNumber, CONCAT(p.startTime,p.startAmPm,' to ',p.endTime,p.endAmPm) AS periodTime 
                  FROM 
                        `period` p, period_slot ps
                  WHERE      
                        ps.periodSlotId = p.periodSlotId AND
                        ps.instituteId = '$instituteId'    
                  $condition
                  ORDER BY
                        p.periodSlotId, p.periodId ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getSmsTemplate($condition='') {
        
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT  
			id, templateName, templateText, `value` AS noCols                  
                  FROM 
                       smsTemplate
                  ORDER BY
                        id ASC ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getTeacherDataList($condition='',$orderBy='') {
      
       global $sessionHandler;
       $systemDatabaseManager = SystemDatabaseManager::getInstance();

       $query ="SELECT
                      DISTINCT e.employeeId, e.employeeName, e.employeeCode,
                      CONCAT(e.employeeName,' (',e.employeeCode,')' ) AS employeeNameCode
                FROM    
                      `employee` e LEFT JOIN employee_can_teach_in ec ON e.employeeId = ec.employeeId
                WHERE 
                      e.isTeaching = 1 AND e.isActive=1
                ORDER BY 
                      e.employeeName";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
      }
      
      public function getClassDataForAlumniList($condition='',$orderBy='') {
      
       global $sessionHandler;
       $systemDatabaseManager = SystemDatabaseManager::getInstance();
       
       if($orderBy=='') {
         $orderBy = 'cls.degreeId,cls.branchId,cls.studyPeriodId';  
       }

       $query ="SELECT
                      DISTINCT cls.classId, cls.className, cls.isActive
                FROM    
                      student s, class cls
                WHERE 
                      s.classId = cls.classId
                $condition      
                ORDER BY 
                      $orderBy";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
      }
      
      public function getFineClassList($condition='',$orderBy='') {
      
           global $sessionHandler;
           $systemDatabaseManager = SystemDatabaseManager::getInstance();
           
           $query ="SELECT
                        DISTINCT cls.classId, cls.className, cls.isActive,
                        CONCAT(cls.className,' (',i.instituteCode,')') AS instituteClassName
                    FROM
                        fine_student fs,    
                        student s, class cls, institute i
                    WHERE 
                        fs.studentId = s.studentId
                        AND s.classId = cls.classId
                        AND i.instituteId = cls.instituteId
                        $condition      
                    ORDER BY 
                        cls.instituteId, cls.classId ";

            return $systemDatabaseManager->executeQuery($query,"Query: $query");
      }
      
       public function getLoginClassList($condition='') {
      
           global $sessionHandler;
           $systemDatabaseManager = SystemDatabaseManager::getInstance();
           $instituteId = $sessionHandler->getSessionVariable('InstituteId');   
           $sessionId = $sessionHandler->getSessionVariable('SessionId');   
           
           $query ="SELECT
                          i.instituteId, c.classId, c.degreeId, c.batchId, c.branchId,  
                          i.instituteCode, c.className, d.degreeCode, b.batchName, br.branchCode, 
                          CONCAT_WS('!!~!!~!!',i.instituteId, c.classId, c.degreeId, c.batchId, c.branchId) AS combineId,
                          CONCAT_WS('!!~!!~!!',i.instituteCode, c.className, d.degreeCode, b.batchName, br.branchCode) AS combineName
                    FROM    
                          institute i, class c, degree d, batch b, branch br ,time_table_classes ttc ,time_table_labels ttl
                    WHERE 
                          c.isActive = 1 AND
                          c.instituteId = i.instituteId AND
                          c.instituteId  = '$instituteId' AND
                          c.degreeId = d.degreeId AND
                          c.batchId = b.batchId AND
                          c.branchId = br.branchId 
						   AND ttc.`classId` = c.`classId` AND
                          ttc.`timeTableLabelId` = ttl.`timeTableLabelId` AND
                         ttl.`sessionId` = '$sessionId'
                    $condition 
                    ORDER BY
                          i.instituteCode, b.batchName, d.degreeCode, br.branchCode, c.className ";

            return $systemDatabaseManager->executeQuery($query,"Query: $query");
      }

       public function getLoginInstituteList($condition='') {
      
           global $sessionHandler;
           $systemDatabaseManager = SystemDatabaseManager::getInstance();
           //$instituteId = $sessionHandler->getSessionVariable('InstituteId');   
           
           $query ="SELECT
                          DISTINCT i.instituteId, c.classId, c.degreeId, c.batchId, c.branchId, c.isActive, 
                          i.instituteCode, c.className, d.degreeCode, b.batchName, br.branchCode, 
                          CONCAT_WS('!!~!!~!!',i.instituteId, c.classId, c.degreeId, c.batchId, c.branchId,c.isActive) AS combineId,
                          CONCAT_WS('!!~!!~!!',i.instituteCode, c.className, d.degreeCode, b.batchName, br.branchCode,IF(c.isActive=1,'Active',IF(c.isActive=2,'Future','Past'))) AS combineName
                    FROM    
                          institute i,degree d, batch b, branch br,  
                          fee_head_values_new fn LEFT JOIN class c ON fn.classId = c.classId
                    WHERE 
                          c.isActive IN (1,2,3) AND
                          c.instituteId = i.instituteId AND
                          c.degreeId = d.degreeId AND
                          c.batchId = b.batchId AND
                          c.branchId = br.branchId
                          $condition 
                    UNION
                    SELECT
                          DISTINCT i.instituteId, c.classId, c.degreeId, c.batchId, c.branchId,  c.isActive, 
                          i.instituteCode, c.className, d.degreeCode, b.batchName, br.branchCode, 
                          CONCAT_WS('!!~!!~!!',i.instituteId, c.classId, c.degreeId, c.batchId, c.branchId,c.isActive) AS combineId,
                          CONCAT_WS('!!~!!~!!',i.instituteCode, c.className, d.degreeCode, b.batchName, br.branchCode,IF(c.isActive=1,'Active',IF(c.isActive=2,'Future','Past'))) AS combineName
                    FROM    
                          institute i,degree d, batch b, branch br,  
                          bus_route_student_mapping brs LEFT JOIN class c ON brs.classId = c.classId
                    WHERE 
                          c.isActive IN (1,2,3) AND
                          c.instituteId = i.instituteId AND
                          c.degreeId = d.degreeId AND
                          c.batchId = b.batchId AND
                          c.branchId = br.branchId
                          $condition 
                    UNION
                    SELECT
                          DISTINCT i.instituteId, c.classId, c.degreeId, c.batchId, c.branchId,  c.isActive, 
                          i.instituteCode, c.className, d.degreeCode, b.batchName, br.branchCode, 
                          CONCAT_WS('!!~!!~!!',i.instituteId, c.classId, c.degreeId, c.batchId, c.branchId,c.isActive) AS combineId,
                          CONCAT_WS('!!~!!~!!',i.instituteCode, c.className, d.degreeCode, b.batchName, br.branchCode,IF(c.isActive=1,'Active',IF(c.isActive=2,'Future','Past'))) AS combineName
                    FROM    
                          institute i,degree d, batch b, branch br,  
                          hostel_students hs LEFT JOIN class c ON hs.classId = c.classId
                    WHERE 
                          c.isActive IN (1,2,3) AND
                          c.instituteId = i.instituteId AND
                          c.degreeId = d.degreeId AND
                          c.batchId = b.batchId AND
                          c.branchId = br.branchId
                          $condition             
                    ORDER BY
                          instituteCode, batchName, degreeCode, branchCode, className, isActive ";

            return $systemDatabaseManager->executeQuery($query,"Query: $query");
      }
      
      public function fetchHostelRoomTypes($condition=''){
        
        $query ="SELECT 
                     DISTINCT hrt.hostelRoomTypeId, CONCAT(hrt.roomType,' (',h.hostelName,')') AS hostelRoomType
                 FROM 
                     hostel_room_type hrt, hostel_room_type_detail hrtd, hostel h
                  WHERE 
                      h.hostelId = hrtd.hostelId
                      AND hrtd.hostelRoomTypeId = hrt.hostelRoomTypeId
                      $condition
                 ORDER BY 
                      h.hostelName, hrt.roomType ASC";
                    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
      public function getRouteStopRegistration($orderBy=' br.routeName, bs.stopName ASC',$condition='') {
       
       $systemDatabaseManager = SystemDatabaseManager::getInstance();
       
       if($orderBy=='') {
         $orderBy = " br.routeName, bs.stopName ASC";   
       }
       
       $query = "SELECT 
                    bs.busStopId,bsc.cityName,bs.stopName,bs.stopAbbr, br.routeName,bsm.scheduledTime, bsm.busRouteStopMappingId 
                 FROM 
                    `bus_stop_new` bs , `bus_stop_city` bsc, `bus_route_new` br,`bus_route_stop_mapping` bsm
                 WHERE    
                     bs.busStopCityId = bsc.busStopCityId
                     AND bsm.busRouteId = br.busRouteId
                     AND bs.busStopId = bsm.busStopId 
                 ORDER BY
                    br.routeName, bs.stopName ASC";
       
       return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }   
	   public function getHostelRegistration($orderBy=' hostelName',$condition='') {
       
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
       
       if($orderBy=='') {
         $orderBy = " hostelName ASC";   
       }
       
       $query = "SELECT 
                     DISTINCT hostelId, hostelName, hostelCode, roomTotal, hostelType, 
                     floorTotal, totalCapacity, IFNULL(wardenName,'') AS wardenName, 
                     IFNULL(wardenContactNo,'') AS  wardenContactNo  
                 FROM 
                     hostel 
                 $condition 
                 ORDER BY 
                    $orderBy";
      
       return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }  
    
    public function getResultClass($orderBy=' cls.degreeId,cls.branchId,cls.studyPeriodId') {
		global $sessionHandler;
		$systemDatabaseManager = SystemDatabaseManager::getInstance();

		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

		$query = "	SELECT	
                        DISTINCT cls.classId, cls.className
					FROM
					    ".TOTAL_TRANSFERRED_MARKS_TABLE." att , class cls
					WHERE 	
                        att.classId = cls.classId  AND 
                        cls.instituteId = '".$instituteId."' AND
                        (IFNULL(cls.internalPassMarks,0) > 0 OR IFNULL(externalPassMarks,0) >0) AND
                        cls.isActive IN(1,3)
					ORDER BY 
                        $orderBy DESC";
							
       return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
}
?>
