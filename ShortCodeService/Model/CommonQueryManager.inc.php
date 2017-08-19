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
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STATES
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function getStates($orderBy=' stateId',$condition) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM states $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STATES CORRESPONDING TO A COUNTRY
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (16.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------     
    public function getStatesCountry($condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT stateId,stateName FROM states $condition ORDER BY stateName";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }    



//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Section
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (17.09.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------     
    public function getScClassBatch($orderBy=' bat.batchName',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT DISTINCT(bat.batchName),bat.batchId FROM batch bat, class cls WHERE bat.batchId=cls.batchId $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Batches CORRESPONDING TO A CLASS FOr "Sc" Approach
//
//orderBy: on which column to sort
//
// Author :Arvind Singh Rawat
// Created on : (30.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------     
    public function getTeacherData() {
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
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
							DISTINCT           
                                emp.employeeId, employeeName AS employeeName1, employeeAbbreviation, 
                                IF(IFNULL(employeeAbbreviation,'')='',employeeName,
                                CONCAT(employeeName,' (',employeeAbbreviation,')')) AS employeeName
					FROM	classes_visible_to_role cvtr,
							time_table tt,
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
					AND		emp.isTeaching = 1
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
							AND		e.isTeaching = 1 
							AND		e.isActive=1 
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------   
//ModifiedBy: on which column to sort
//
// Author :Arvind
// Created on : (13.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
public function getUniversity($orderBy=' universityId') {
		global $sessionHandler;
		$instituteId = $employeeId = $sessionHandler->getSessionVariable('InstituteId');
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT distinct u.* FROM university u, class cl WHERE cl.universityId = u.universityId AND cl.instituteId = $instituteId ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
 //   
//  THIS FUNCTION IS USED TO GET A LIST OF SubjectType 
// Author :Arvind
// Created on : (17.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 
//ModifiedBy: on which column to sort
//
// Author :Arvind
// Created on : (13.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
public function getEmployee($orderBy=' employeeName',$conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
         $query = "SELECT * FROM employee WHERE isActive=1 $conditions ORDER BY $orderBy";
         //logError("xxxxxxx".$query);
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
}                 
    
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Institutes
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (13.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT b.instituteId, b.instituteCode FROM institute b, employee_can_teach_in a where a.employeeId = '$employeeId' AND a.instituteId = b.instituteId ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Institutes
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (13.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function getBatch($orderBy=' batchId') {
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function getPeriodicity($orderBy=' periodicityId') {
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function geClass($orderBy=' ttc.timeTableLabelId') {
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function getFeeCycle($orderBy=' feeCycleId',$condition='') {
	global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM fee_cycle WHERE instituteid= '".$sessionHandler->getSessionVariable('InstituteId')."' $condition  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
	
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE HEAD
//
//orderBy: on which column to sort
//
// Author :Arvind Singh Rawat
// Created on : (2.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function getFeeHead($orderBy=' feeHeadId') {
        global $sessionHandler;
	    $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM fee_head where instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF ALL ALLOCATED FEE HEAD
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (26.09.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function getBranch($orderBy=' branchId') {
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
			$query = "SELECT distinct br.* FROM branch br, class cl WHERE cl.branchId = br.branchId AND cl.instituteId = $instituteId ORDER BY $orderBy";
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
								distinct cl.classId, 
								substring_index( substring_index( cl.className, '-', 5 ) , '-', -5 ) AS className 
						FROM	class cl, classes_visible_to_role cvtr
						WHERE	cl.instituteId ='".$instituteId."' 
						AND		cl.sessionId='".$sessionId."'
						AND		cvtr.classId = cl.classId
						AND		cl.classId IN ($insertValue)
						AND		cl.isActive = 1 
								ORDER BY $orderBy";
		return $systemDatabaseManager->executeQuery($query,"Query: $query");
		}
		else {

			$query = "	SELECT 
								classId, 
								substring_index( substring_index( className, '-', 5 ) , '-', -5 ) AS className 
						FROM	class  
						WHERE	instituteId ='".$instituteId."' 
						AND		sessionId='".$sessionId."'
						AND		isActive = 1 
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function getGroupParent($orderBy=' groupId',$condition) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM `group` $condition  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    } 

//--------------------------------------------------------  
// Purpose: to get the list of sessions
// Author:Pushpender Kumar Chauhan
// Params: nothing
// Returns: array
// Created on : (04.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------      
//--------------------------------------------------------  
// Purpose: to make the list ordered by session year
// Modified By :Ajinder Singh
// Params: $mode=1 : Used to show all sessions | $mode=2 : used to show only active and past sessions
// Returns: array
// Created on : (01.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------      
    public function getSessionDetail($mode='1') {
     
        $query = "SELECT sessionName, abbreviation, sessionId FROM session ORDER BY sessionId";
        
        if($mode=='2'){
           $query = "SELECT 
                           s.sessionId, s.sessionName,s.abbreviation
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
                                s.sessionId, s.sessionName,s.abbreviation
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
                     ORDER BY sessionId"; 
        }
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
	//--------------------------------------------------------  
	// Purpose: to fetch active session
	// Modified By :Ajinder Singh
	// Params: --
	// Returns: array
	// Created on : (01.08.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------     
    public function getUserNameDetailed($orderBy=' u.userId',$condition) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT 
                         u.userId,u.userName,
                         IF(u.displayName IS NULL OR u.displayName='','".NOT_APPLICABLE_STRING."',u.displayName) AS displayName ,
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function getClassWithStudyPeriod($orderBy=' className') {
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
                              AND  cvtr.classId IN $insertValue ";
        }
        
    $query = "SELECT 
                        distinct c.classId, c.degreeId, c.branchId, c.studyPeriodId, c.className 
                  FROM 
                        class c $tableName
                  WHERE 
                        c.sessionId='".$sessionHandler->getSessionVariable('SessionId')."' AND 
                        c.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' AND 
                        c.isActive = 1 
                  $hodCondition
                  ORDER BY $orderBy";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF All Classes in CURRENT SESSION
//
//orderBy: on which column to sort
//
// Author :Ajinder Singh
// Created on : (22.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
	public function getCurrentSessionClasses($orderBy = ' className') {
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------
	public function getAllCurrentGroups($orderBy = '',$condition='') {

		global $sessionHandler;
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
		if ($count > 0) {
			 $query = "	SELECT 
									gr.groupId,
									gr.groupName
							FROM	`group` gr, 
									classes_visible_to_role cvtr
							WHERE	cvtr.groupId = gr.groupId
							AND		cvtr.classId = gr.classId
							AND		cvtr.userId = $userId
							AND		cvtr.roleId = $roleId
							AND		gr.classId IN ($insertValue)
									ORDER BY gr.groupId
									";
		}
		else {

		 //$query = "SELECT groupId,groupName FROM `group` $condition";

		$query = "SELECT 
				 grp.groupId,grp.groupName,grp.groupShort 
				 FROM 
				 
				 `group` grp, `class` cls
				 $condition AND 
				 grp.classId = cls.classId AND 
				 (cls.isActive=1 or cls.isActive=3) AND 
				 cls.sessionId='".$sessionHandler->getSessionVariable('SessionId')."' AND 
				 cls.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
				 
				 ORDER BY 
				 $orderBy"; 
		}
		$result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
		$cnt = count($result);

		for($i=0;$i<$cnt;$i++) {

			$groupId = $result[$i]['groupId'];
			$groupName = $result[$i]['groupName'];
			 
			//$query1 = "SELECT COUNT(*) as totalRecord FROM  `group` WHERE parentGroupId  = $groupId ";
		    //$result1 =  $systemDatabaseManager->executeQuery($query1,"Query: $query");
			//if($result1[0]['totalRecord']){
			  $categoryArr[$groupId] = "$groupName";
			  //echo ($categoryArr[$groupId]);
			
			$tempArr = "";
			$tempArr = $this->traverseCat($groupId,0);
			$categoryArr = $this->mergeArray($categoryArr, $tempArr);
			//}
		}
		 
		return $categoryArr;
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------
    public function getAllCurrentCategories($orderBy = ' quotaName',$condition='') {

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
            $categoryArr[$quotaId] = "$quotaName";
            
            $tempArr = "";
            $tempArr = $this->traverseQuotaCat($quotaId,0);
            $categoryArr = $this->mergeArray($categoryArr, $tempArr);

        }
         
        return $categoryArr;
    }

    function traverseQuotaCat($id,$level)
    {
        $level++;
        $idArr = array();
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $query = "SELECT quotaId,quotaName,parentQuotaId FROM `quota` WHERE parentQuotaId=$id  ORDER BY quotaName";
        $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
        $cnt = count($result); 

        for($i=0;$i<$cnt;$i++) {
        
    
               $quotaId = $result[$i]['quotaId'];
               $parentID =  $result[$i]['parentQuotaId'];
               $quotaName = $result[$i]['quotaName'];
             
             $spacer = "";
             for($cntr=0;$cntr<$level;$cntr++)
                 $spacer .= "&nbsp;&nbsp;";
             $tempArr[$quotaId] = $spacer . "--&nbsp;$quotaName";
             $idArr = $this->mergeArray($idArr,$tempArr);
             $tempArr = $this->traverseQuotaCat($quotaId,$level);
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------     
    public function getTimeTableLabel($condition='', $orderBy=' timeTableLabelId') {
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
        
		if ($count > 0 ) {
			$query = "SELECT 
						    timeTableLabelId, labelName, startDate, endDate, isActive, timeTableType 
				      FROM	
                            time_table_labels  
				      WHERE	
                            instituteId ='".$instituteId."' 
				            AND	sessionId='".$sessionId."'
                            AND isActive = 1
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
						$where GROUP BY att.subjectId, att.groupId, emp.employeeId ";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
}

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STUDENT ATTENDANCE FOR SC Modules
//
//
// Author :Jaineesh
// Created on : (04-12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
  
  public function getStudentAttendance($studentId,$classId='',$limit='',$where='', $orderBy = ''){      
           if ($classId != "" and $classId != "0") {
			$classCond =" and sg.classId = $classId ";
		   }
		    if ($classId != "" and $classId != "0") {
			$classCond1 =" and classId = $classId ";
		   }
    global $sessionHandler;
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
                        SUBSTRING_INDEX(c.className,'-',-3) AS className 
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
						GROUP BY att.subjectId, att.groupId, emp.employeeId
			UNION 
						SELECT 
							-1 AS studentName ,
					CONCAT( s.subjectName , ' (' , s.subjectCode , ')' ) AS subject ,
					g.groupName,
					'".NOT_APPLICABLE_STRING."' AS employeeName,
					'".NOT_APPLICABLE_STRING."' AS periodName ,
					leavesTaken AS attended ,
					'DL' AS delivered ,
					'".NOT_APPLICABLE_STRING."' AS fromDate ,
					'".NOT_APPLICABLE_STRING."' AS toDate,
					'".NOT_APPLICABLE_STRING."' AS className 
					  FROM 
							attendance_leave al,
							class c,
							subject s,
							`group` g,
							student st
							
					  WHERE
						   al.studentId = st.studentId
						   AND concat(al.studentId,'-',al.classId,'-',g.groupId) IN (select concat(studentId,'-',classId,'-',groupId) from student_groups where studentId = $studentId $classCond1)
						   AND al.classId=c.classId
						   AND al.subjectId=s.subjectId
						   AND al.groupId=g.groupId
						   AND al.studentId = $studentId
							ORDER BY subject, groupName, className		
							$limit 
						 ";


	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
  }
  
  
  

//--------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF STUDENT ATTENDANCE Irrespective of groups
// Author :Dipanjan Bhattacharjee
// Created on : (06-10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------
  
public function getConsolidatedStudentAttendance($studentId,$classId='',$limit='',$where='', $orderBy = ''){      
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
            logError($query);
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
  }
  
  
//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF testType
//
//
// Author :Arvind Singh Rawat
// Created on : (22-10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
  public function getTestType($selected='',$conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
       $query = "
                    SELECT 
							testTypeId, testTypeName, testTypeCode,testTypeAbbr,subjectId
                    FROM    test_type 
					WHERE	instituteId = $instituteId
							$conditions
							ORDER BY    testTypeName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
  }

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Subject codes from active classes for which marks have been transferred
//
//
// Author :Ajinder Singh
// Created on : 21-oct-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
 public function getGradingLabels() {
    global $sessionHandler;
	  $query = "
				SELECT 
							DISTINCT(a.gradingLabelId), 
							a.gradingLabel 
				FROM		sc_grading_labels a, sc_grading_scales  b 
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
                            className AS className1, c.isActive  
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
                            className AS className1, c.isActive 
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
    
    
    
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FeedBack Labels
//
//orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (15.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
	FROM		`time_table` tt, 
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
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function getSubjectTimeTable($orderBy=' s.subjectId') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
   $query = "	SELECT	distinct(s.subjectCode), 
							s.subjectId
					FROM	subject s, 
							time_table sctt,
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------
   public function getBus($orderBy=' busName',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM bus $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    } 
    
//------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF TRANSPORT STUFF
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (21.01.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
	FROM		`time_table` tt, 
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
//  THIS FUNCTION IS USED TO GET A LIST OF Last Login
//
// Author :Parveen Sharma
// Created on : 28-05-09
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------------     
   public function getTimeTableSubjectGroups($condition='',$orderBy = ' gr.groupId') {
        
       $systemDatabaseManager = SystemDatabaseManager::getInstance();    

       $query = "SELECT 
                        DISTINCT gr.groupId, gr.groupName, gr.groupShort, gt.groupTypeName, gt.groupTypeCode
                 FROM 
                        time_table tt, `group` gr, `group_type` gt, subject sub
                 WHERE 
                        gr.groupTypeId = gt.groupTypeId AND
                        gr.groupId = tt.groupId AND
                        tt.subjectId = sub.subjectId 
                 $condition
                 ORDER BY $orderBy ";

       /* $query = "SELECT 
                            DISTINCT gr.groupId, gr.groupName, gr.groupShort 
                  FROM
                            `subject_type` subType, `group_type` grType, `subject` sub, `group` gr, time_table tt
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
					FROM	`fine_category` fc,`role_fine` rf,`role_fine_category` rfc
					
					WHERE
							rfc.fineCategoryId = fc.fineCategoryId
					AND		rfc.roleFineId = rf.roleFineId
					AND		rf.roleId= $roleId
					AND		rf.instituteId = $instituteId
							$condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
       

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Parent Subject Category Information     
//
// Author :Parveen Sharma
// Created on : (06.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function getParentSubjectCategory($orderBy=' categoryName') {
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();  
        $query = "SELECT subjectCategoryId, categoryName FROM `subject_category` ORDER BY $orderBy";
        
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
       

 //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Batch
//
//orderBy: on which column to sort
//
// Author :Ajinder Singh
// Created on : (29.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------   
    public function checkInstitutes($conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT instituteId FROM institute $conditions";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    

//-----------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Employees based on time table records
// orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (07.08.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
							 time_table t,time_table_labels ttl,
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
                         time_table t,time_table_labels ttl,
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
	

//-----------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Class FROM TIME TABLE
// orderBy: on which column to sort
// Author :Jaineesh
// Created on : (28.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
							time_table t,
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
							time_table t,
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
                    classId=$classId
           ";
     
    return $systemDatabaseManager->executeQuery($query,"Query: $query");
 }
 
 
 //-------------------------------------------------------
// Author :Dipanjan Bhattacharjee
// Created on : (17.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
                    class c
             WHERE
                    c.instituteId=$instituteId
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
                         AND c.instituteId=$instituteId
                         AND c.sessionId=$sessionId
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
                         AND c.instituteId=$instituteId
                         AND c.sessionId=$sessionId
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
                        `time_table` tt,`group` g, `class` c, `subject` s, `subject_type` st
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
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-----------------------------------------------------------------
   public function getSubjectInformation($condition='',$orderBy=' subjectCode') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM `subject` $condition ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
 
 
 
 }

// $History: CommonQueryManager.inc.php $
//
//*****************  Version 152  *****************
//User: Jaineesh     Date: 4/21/10    Time: 3:24p
//Updated in $/LeapCC/Model
//put new functions for student optional group
//
//*****************  Version 151  *****************
//User: Dipanjan     Date: 20/04/10   Time: 18:30
//Updated in $/LeapCC/Model
//Added check for CLIENT_INSTITUTES
//
//*****************  Version 150  *****************
//User: Ajinder      Date: 4/19/10    Time: 1:35p
//Updated in $/LeapCC/Model
//bug fixed. FCNS No.1610
//
//*****************  Version 149  *****************
//User: Parveen      Date: 4/19/10    Time: 1:23p
//Updated in $/LeapCC/Model
//getStudyPeriodData function studentId check added 
//
//*****************  Version 148  *****************
//User: Parveen      Date: 4/19/10    Time: 12:40p
//Updated in $/LeapCC/Model
//function updated getTimeTableLabel 
//
//*****************  Version 147  *****************
//User: Parveen      Date: 4/16/10    Time: 10:16a
//Updated in $/LeapCC/Model
//getTeacherTimeTableSubject function updated
//
//*****************  Version 146  *****************
//User: Parveen      Date: 4/15/10    Time: 5:23p
//Updated in $/LeapCC/Model
//getClassSubjectsList, getActiveTimeTableClasses,
//getTeacherTimeTableSubject function added
//
//*****************  Version 145  *****************
//User: Ajinder      Date: 3/30/10    Time: 1:33p
//Updated in $/LeapCC/Model
//bugs fixed. FCNS No.1490
//
//*****************  Version 144  *****************
//User: Jaineesh     Date: 3/29/10    Time: 12:15p
//Updated in $/LeapCC/Model
//add new functions for employee experience & qualification
//
//*****************  Version 143  *****************
//User: Dipanjan     Date: 20/03/10   Time: 17:34
//Updated in $/LeapCC/Model
//Created "Sent Student Information Message To Parents" module
//
//*****************  Version 142  *****************
//User: Dipanjan     Date: 3/04/10    Time: 5:43p
//Updated in $/LeapCC/Model
//made modifications
//
//*****************  Version 141  *****************
//User: Jaineesh     Date: 3/03/10    Time: 12:33p
//Updated in $/LeapCC/Model
//show classes order by degreeId,branchId,studyPeriodId
//
//*****************  Version 140  *****************
//User: Parveen      Date: 2/18/10    Time: 1:27p
//Updated in $/LeapCC/Model
//getTimeTableLabel function updated (startDate, endDate added)
//
//*****************  Version 139  *****************
//User: Jaineesh     Date: 2/15/10    Time: 7:19p
//Updated in $/LeapCC/Model
//fixed bug nos. 0002869, 0002870, 0002868, 0002867, 0002865, 0002864,
//0002866, 0002871
//
//*****************  Version 138  *****************
//User: Parveen      Date: 2/15/10    Time: 10:41a
//Updated in $/LeapCC/Model
//getTeacherData, getAllTeacherData function updated
//concat(employeeName,employeeAbbreiviation) 
//
//*****************  Version 137  *****************
//User: Jaineesh     Date: 2/09/10    Time: 5:59p
//Updated in $/LeapCC/Model
//put link & make new functions for upload student external marks
//
//*****************  Version 136  *****************
//User: Dipanjan     Date: 8/02/10    Time: 13:17
//Updated in $/LeapCC/Model
//Modified  fetchMappedFeedbackLabelAdvForUsers() function.
//
//*****************  Version 135  *****************
//User: Jaineesh     Date: 2/08/10    Time: 1:13p
//Updated in $/LeapCC/Model
//change in function getTestType(), get subjectId also
//
//*****************  Version 134  *****************
//User: Parveen      Date: 2/02/10    Time: 5:38p
//Updated in $/LeapCC/Model
//function added getInstitueClass
//
//*****************  Version 133  *****************
//User: Dipanjan     Date: 1/02/10    Time: 19:30
//Updated in $/LeapCC/Model
//Done bug fixing.
//Bug ids---
//0002703,0002702
//
//*****************  Version 132  *****************
//User: Parveen      Date: 2/01/10    Time: 3:09p
//Updated in $/LeapCC/Model
//function added getReappearSubject
//
//*****************  Version 131  *****************
//User: Dipanjan     Date: 27/01/10   Time: 16:24
//Updated in $/LeapCC/Model
//Corrected getAdvFeedBackQuestionSet() function and added institute wise
//check.
//
//*****************  Version 130  *****************
//User: Dipanjan     Date: 22/01/10   Time: 12:01
//Updated in $/LeapCC/Model
//Updated fetchMappedFeedbackLabelAdvForUsers() function and added
//session and institute check
//
//*****************  Version 129  *****************
//User: Ajinder      Date: 1/22/10    Time: 11:56a
//Updated in $/LeapCC/Model
//done coding for showing multi institutes for teachers
//
//*****************  Version 128  *****************
//User: Dipanjan     Date: 21/01/10   Time: 18:47
//Updated in $/LeapCC/Model
//Added functions for Feedback Modules
//
//*****************  Version 127  *****************
//User: Gurkeerat    Date: 1/18/10    Time: 2:42p
//Updated in $/LeapCC/Model
//made updations under feedback module
//
//*****************  Version 126  *****************
//User: Ajinder      Date: 1/14/10    Time: 12:55p
//Updated in $/LeapCC/Model
//modified function getInstituteRoom2, corrected ordering
//
//*****************  Version 125  *****************
//User: Gurkeerat    Date: 1/13/10    Time: 11:24a
//Updated in $/LeapCC/Model
//Fixed issue as list was not populating
//
//*****************  Version 124  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 5:28p
//Updated in $/LeapCC/Model
//added function getFeedbackAdvAnswerSet
//
//*****************  Version 123  *****************
//User: Rajeev       Date: 10-01-11   Time: 3:47p
//Updated in $/LeapCC/Model
//updated to show previous classes also
//
//*****************  Version 122  *****************
//User: Dipanjan     Date: 11/01/10   Time: 15:35
//Updated in $/LeapCC/Model
//Added getAdvFeedBackQuestionSet() function for adv. feedback modules
//
//*****************  Version 121  *****************
//User: Parveen      Date: 1/09/10    Time: 5:49p
//Updated in $/LeapCC/Model
//getReappearClasses function added
//
//*****************  Version 120  *****************
//User: Dipanjan     Date: 9/01/10    Time: 13:49
//Updated in $/LeapCC/Model
//Added getSubjectTypes() and getAdvFeedBackLabel() function for advanced
//feedback modules
//
//*****************  Version 119  *****************
//User: Parveen      Date: 1/08/10    Time: 3:07p
//Updated in $/LeapCC/Model
//getPastClasses function updated
//
//*****************  Version 118  *****************
//User: Parveen      Date: 1/07/10    Time: 3:51p
//Updated in $/LeapCC/Model
//function added getPastClasses 
//
//*****************  Version 117  *****************
//User: Parveen      Date: 12/29/09   Time: 1:15p
//Updated in $/LeapCC/Model
//function added getAttendanceSet
//
//*****************  Version 116  *****************
//User: Dipanjan     Date: 23/12/09   Time: 19:15
//Updated in $/LeapCC/Model
//Done group coping module
//
//*****************  Version 115  *****************
//User: Parveen      Date: 12/23/09   Time: 6:39p
//Updated in $/LeapCC/Model
//role permission check & format updated
//
//*****************  Version 114  *****************
//User: Dipanjan     Date: 23/12/09   Time: 17:54
//Updated in $/LeapCC/Model
//Added getActiveClassesWithNoGroupsData() function
//
//*****************  Version 113  *****************
//User: Jaineesh     Date: 12/18/09   Time: 4:07p
//Updated in $/LeapCC/Model
//show selected default institute of employee
//
//*****************  Version 112  *****************
//User: Dipanjan     Date: 17/12/09   Time: 15:47
//Updated in $/LeapCC/Model
//Added the code for "Freezed" class
//
//*****************  Version 111  *****************
//User: Parveen      Date: 12/03/09   Time: 3:23p
//Updated in $/LeapCC/Model
//getSubjectTopic function udpated
//
//*****************  Version 110  *****************
//User: Parveen      Date: 12/02/09   Time: 11:03a
//Updated in $/LeapCC/Model
//getTotalStudents function updated
//
//*****************  Version 109  *****************
//User: Parveen      Date: 12/01/09   Time: 5:42p
//Updated in $/LeapCC/Model
//condition format updated
//
//*****************  Version 107  *****************
//User: Parveen      Date: 11/26/09   Time: 11:27a
//Updated in $/LeapCC/Model
//getClassWithStudyPeriod function updated (Hod Permission base class
//fetch)
//
//*****************  Version 106  *****************
//User: Jaineesh     Date: 11/23/09   Time: 6:49p
//Updated in $/LeapCC/Model
//fixed bug nos. 0002099, 0002105, 0002096, 0002080
//
//*****************  Version 105  *****************
//User: Jaineesh     Date: 11/23/09   Time: 3:24p
//Updated in $/LeapCC/Model
//Show duty in attendance during student login
//
//*****************  Version 104  *****************
//User: Parveen      Date: 11/19/09   Time: 6:17p
//Updated in $/LeapCC/Model
//getSubjectTopic function updated
//
//*****************  Version 103  *****************
//User: Jaineesh     Date: 11/19/09   Time: 6:08p
//Updated in $/LeapCC/Model
//put new function getTimeTableLabelDegreeData() to fetch degree against
//timetablelabel
//
//*****************  Version 102  *****************
//User: Rajeev       Date: 09-11-17   Time: 9:39a
//Updated in $/LeapCC/Model
//Updated "geAdmitClass" with study period 5
//
//*****************  Version 101  *****************
//User: Jaineesh     Date: 11/13/09   Time: 6:25p
//Updated in $/LeapCC/Model
//Modification in code for move/copy timetable
//
//*****************  Version 100  *****************
//User: Jaineesh     Date: 11/10/09   Time: 11:26a
//Updated in $/LeapCC/Model
//modification in function getEmployeesFromTimeTable() for HOD role 
//
//*****************  Version 99  *****************
//User: Jaineesh     Date: 11/09/09   Time: 12:28p
//Updated in $/LeapCC/Model
//Modified in manage table table according to HOD role
//
//*****************  Version 98  *****************
//User: Dipanjan     Date: 5/11/09    Time: 12:13
//Updated in $/LeapCC/Model
//Modified Swap/Substitution module as one new field "adjustmentType" is
//added in "time_table_adjustment" table
//
//*****************  Version 97  *****************
//User: Jaineesh     Date: 11/02/09   Time: 10:34a
//Updated in $/LeapCC/Model
//put new functions and messages for move copy time table
//
//*****************  Version 96  *****************
//User: Dipanjan     Date: 26/10/09   Time: 11:12
//Updated in $/LeapCC/Model
//1.Added getTestConductingAuthority() function to get used conducting
//authority.
//2.Added getUsedTestTypeCategoryfunction to get used test type
//categories .
//
//*****************  Version 95  *****************
//User: Parveen      Date: 10/23/09   Time: 5:45p
//Updated in $/LeapCC/Model
//function remove getAttendanceSubjectType
//
//*****************  Version 94  *****************
//User: Parveen      Date: 10/23/09   Time: 3:56p
//Updated in $/LeapCC/Model
//function getAttendanceSubjectType updated
//
//*****************  Version 93  *****************
//User: Parveen      Date: 10/23/09   Time: 2:44p
//Updated in $/LeapCC/Model
//getAttendanceSubjectType function added
//
//*****************  Version 92  *****************
//User: Jaineesh     Date: 10/23/09   Time: 11:36a
//Updated in $/LeapCC/Model
//modified in function getBusRouteName() for showing bus route code in
//asc order
//
//*****************  Version 91  *****************
//User: Parveen      Date: 10/22/09   Time: 5:03p
//Updated in $/LeapCC/Model
//getConsolidatedStudentAttendance, getStudentAttendance (className,
//employeeName, groupName) fields name added 
//
//*****************  Version 90  *****************
//User: Dipanjan     Date: 22/10/09   Time: 13:19
//Updated in $/LeapCC/Model
//Added code "time table adjustment cancellation"
//
//*****************  Version 89  *****************
//User: Rajeev       Date: 09-10-20   Time: 4:52p
//Updated in $/LeapCC/Model
//Updated group fetch query
//
//*****************  Version 88  *****************
//User: Jaineesh     Date: 10/15/09   Time: 2:35p
//Updated in $/LeapCC/Model
//fixed bug nos. 0001790, 0001789, 0001768, 0001767, 0001769, 0001761,
//0001758, 0001759, 0001757, 0001791
//
//*****************  Version 87  *****************
//User: Dipanjan     Date: 14/10/09   Time: 13:13
//Updated in $/LeapCC/Model
//Updated getStudyPeriodData() function so that it can fetch study period
//date both from student_groups and student_optional_subject tables
//
//*****************  Version 86  *****************
//User: Jaineesh     Date: 10/14/09   Time: 12:51p
//Updated in $/LeapCC/Model
//modified in function getCourses() to show subject related to group for
//HOD role
//
//*****************  Version 85  *****************
//User: Dipanjan     Date: 12/10/09   Time: 16:06
//Updated in $/LeapCC/Model
//modified getEmployeesFromTimeTable() function
//
//*****************  Version 84  *****************
//User: Dipanjan     Date: 12/10/09   Time: 10:50
//Updated in $/LeapCC/Model
//Modified getEmployeesFromTimeTable() function
//
//*****************  Version 83  *****************
//User: Parveen      Date: 10/09/09   Time: 4:37p
//Updated in $/LeapCC/Model
//getTimeTableSubjectGroups function updated
//
//*****************  Version 82  *****************
//User: Jaineesh     Date: 10/09/09   Time: 3:35p
//Updated in $/LeapCC/Model
//updated function roomAbbreviation() by Ajinder
//
//*****************  Version 81  *****************
//User: Jaineesh     Date: 10/09/09   Time: 3:30p
//Updated in $/LeapCC/Model
//added getInstituteRoom2() BY AJINDER
//
//*****************  Version 80  *****************
//User: Parveen      Date: 10/09/09   Time: 9:58a
//Updated in $/LeapCC/Model
//getDegreeWithCode fucntion updated
//
//*****************  Version 79  *****************
//User: Dipanjan     Date: 6/10/09    Time: 17:00
//Updated in $/LeapCC/Model
//Added Detailed(group wise) and Consolidated view(irrespective of groups
//of a subject) of attendance in admin section
//
//*****************  Version 78  *****************
//User: Ajinder      Date: 10/06/09   Time: 12:47p
//Updated in $/LeapCC/Model
//modified function getSessionClasses() to fetch complete class name
//
//*****************  Version 77  *****************
//User: Parveen      Date: 10/06/09   Time: 11:50a
//Updated in $/LeapCC/Model
//getSubjectTopic function updated
//
//*****************  Version 76  *****************
//User: Jaineesh     Date: 10/01/09   Time: 6:51p
//Updated in $/LeapCC/Model
//changed queries and flow in send message to student, student report
//list according to HOD role and make new role advisory, modified in
//queries according to this role
//
//*****************  Version 75  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:47p
//Updated in $/LeapCC/Model
//worked on role to class
//
//*****************  Version 74  *****************
//User: Parveen      Date: 9/30/09    Time: 12:48p
//Updated in $/LeapCC/Model
//getSubjectTopic function (hasAttedance,hasMarks checks updatd)
//
//*****************  Version 73  *****************
//User: Jaineesh     Date: 9/30/09    Time: 10:15a
//Updated in $/LeapCC/Model
//modified in functions getDegree(), getStudyPeriod() for different role
//
//*****************  Version 72  *****************
//User: Parveen      Date: 9/23/09    Time: 1:35p
//Updated in $/LeapCC/Model
//getHostelName Query Condition updated
//
//*****************  Version 71  *****************
//User: Jaineesh     Date: 9/23/09    Time: 1:32p
//Updated in $/LeapCC/Model
//modification in getEmployee()
//
//*****************  Version 70  *****************
//User: Jaineesh     Date: 9/17/09    Time: 10:40a
//Updated in $/LeapCC/Model
//change in query getPreviousEmployeeName() if classId will be null
//
//*****************  Version 69  *****************
//User: Jaineesh     Date: 9/07/09    Time: 12:26p
//Updated in $/LeapCC/Model
//fixed bug no.0001446
//
//*****************  Version 68  *****************
//User: Parveen      Date: 9/02/09    Time: 4:40p
//Updated in $/LeapCC/Model
//getBusRoute function condition added
//
//*****************  Version 67  *****************
//User: Rajeev       Date: 09-08-31   Time: 4:27p
//Updated in $/LeapCC/Model
//Updated with insituteId validation in "getBatch" Query
//
//*****************  Version 66  *****************
//User: Jaineesh     Date: 8/28/09    Time: 10:37a
//Updated in $/LeapCC/Model
//fixed bugs 
//
//*****************  Version 65  *****************
//User: Jaineesh     Date: 8/26/09    Time: 10:59a
//Updated in $/LeapCC/Model
//put attendance with instituteId
//
//*****************  Version 64  *****************
//User: Jaineesh     Date: 8/26/09    Time: 10:22a
//Updated in $/LeapCC/Model
//fixed bug nos.0001235, 0001233, 0001230, 0001234 and put time table in
//reports
//
//*****************  Version 62  *****************
//User: Jaineesh     Date: 8/21/09    Time: 10:39a
//Updated in $/LeapCC/Model
//fixed issues nos.0000511,  0001157, 0001154 , 0001153, 0001150
//
//*****************  Version 61  *****************
//User: Rajeev       Date: 09-08-18   Time: 3:42p
//Updated in $/LeapCC/Model
//Fixed bug no 638
//
//*****************  Version 60  *****************
//User: Dipanjan     Date: 14/08/09   Time: 16:43
//Updated in $/LeapCC/Model
//Done enhancement in "Room" module---added room and institute mapping so
//that one room can be shared across institutes
//
//*****************  Version 59  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 58  *****************
//User: Dipanjan     Date: 31/07/09   Time: 18:05
//Updated in $/LeapCC/Model
//Added the check for teacher timetable in teacher login:
//Teachers can see only active and past classes timetable
//
//*****************  Version 57  *****************
//User: Ajinder      Date: 7/31/09    Time: 5:06p
//Updated in $/LeapCC/Model
//made the coding, if tutorial groups are not mandatory
//
//*****************  Version 56  *****************
//User: Ajinder      Date: 7/29/09    Time: 3:43p
//Updated in $/LeapCC/Model
//done the changes to fix bug no.s 754, 751
//
//*****************  Version 55  *****************
//User: Rajeev       Date: 7/29/09    Time: 2:47p
//Updated in $/LeapCC/Model
//Updated "geAdmitClass" to show classes for 1st and 3rd semester only
//
//*****************  Version 54  *****************
//User: Parveen      Date: 7/20/09    Time: 12:46p
//Updated in $/LeapCC/Model
//getSubject function sorting order updated
//
//*****************  Version 53  *****************
//User: Jaineesh     Date: 7/14/09    Time: 6:37p
//Updated in $/LeapCC/Model
//modified in queries, delete record student_groups,
//student_optional_subject
//
//*****************  Version 52  *****************
//User: Rajeev       Date: 7/10/09    Time: 12:41p
//Updated in $/LeapCC/Model
//Updated study period query to show study period with "Group By"
//parameter
//
//*****************  Version 51  *****************
//User: Ajinder      Date: 7/09/09    Time: 1:15p
//Updated in $/LeapCC/Model
//added function getActiveSession() to fetch active session.
//
//*****************  Version 50  *****************
//User: Parveen      Date: 7/07/09    Time: 4:38p
//Updated in $/LeapCC/Model
//getParentSubjectCategory function added
//
//*****************  Version 49  *****************
//User: Rajeev       Date: 7/06/09    Time: 5:38p
//Updated in $/LeapCC/Model
//added category select box based on role permission for fine category
//
//*****************  Version 48  *****************
//User: Jaineesh     Date: 7/03/09    Time: 11:31a
//Updated in $/LeapCC/Model
//put new functions getFineCategoryData()
//
//*****************  Version 47  *****************
//User: Jaineesh     Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Model
//put new function for student performance report
//
//*****************  Version 46  *****************
//User: Ajinder      Date: 6/17/09    Time: 11:01a
//Updated in $/LeapCC/Model
//updated getSessionDetail() function. corrected query
//
//*****************  Version 45  *****************
//User: Dipanjan     Date: 16/06/09   Time: 18:14
//Updated in $/LeapCC/Model
//Modified getSessionDetail() function to show past(n number),active
//(only one) and future (only one) session in login page's session
//dropdown
//
//*****************  Version 44  *****************
//User: Rajeev       Date: 6/15/09    Time: 7:22p
//Updated in $/LeapCC/Model
//Enhanced "Admin Student" module as mailed by Puspender Sir.
//
//*****************  Version 43  *****************
//User: Jaineesh     Date: 6/02/09    Time: 6:06p
//Updated in $/LeapCC/Model
//put new function getOffenseCategoryData() for offense report
//
//*****************  Version 42  *****************
//User: Parveen      Date: 6/02/09    Time: 5:46p
//Updated in $/LeapCC/Model
//getTimeTableSubjectGroups function added
//
//*****************  Version 41  *****************
//User: Administrator Date: 1/06/09    Time: 17:17
//Updated in $/LeapCC/Model
//Added getUserNameDetailed() function
//
//*****************  Version 40  *****************
//User: Parveen      Date: 6/01/09    Time: 3:50p
//Updated in $/LeapCC/Model
//getSubjectTopic function added
//
//*****************  Version 39  *****************
//User: Jaineesh     Date: 6/01/09    Time: 3:26p
//Updated in $/LeapCC/Model
//put new function getDegreeLabel()
//
//*****************  Version 38  *****************
//User: Parveen      Date: 5/28/09    Time: 5:17p
//Updated in $/LeapCC/Model
//added getUserLastLogin function
//
//*****************  Version 37  *****************
//User: Parveen      Date: 5/28/09    Time: 2:49p
//Updated in $/LeapCC/Model
//add new function getTotalStudents
//
//*****************  Version 36  *****************
//User: Jaineesh     Date: 5/27/09    Time: 11:02a
//Updated in $/LeapCC/Model
//copy from sc and modifications in the files as per requirement of CC
//
//*****************  Version 35  *****************
//User: Jaineesh     Date: 5/26/09    Time: 7:02p
//Updated in $/LeapCC/Model
//remove echo from getPreviousEmployeeName()
//
//*****************  Version 34  *****************
//User: Jaineesh     Date: 5/26/09    Time: 5:45p
//Updated in $/LeapCC/Model
//put new functions getFeedBackLabel() & getFeedBackLabel()
//
//*****************  Version 33  *****************
//User: Jaineesh     Date: 5/25/09    Time: 3:57p
//Updated in $/LeapCC/Model
//modified in getBatch()
//
//*****************  Version 32  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:33p
//Updated in $/LeapCC/Model
//Added Feedback Survey reports
//
//*****************  Version 31  *****************
//User: Jaineesh     Date: 5/05/09    Time: 12:01p
//Updated in $/LeapCC/Model
//added functions made by Gurkeerat and added in VSS by Jaineesh
//
//*****************  Version 30  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:30p
//Updated in $/LeapCC/Model
//modified for test type category
//
//*****************  Version 29  *****************
//User: Jaineesh     Date: 4/23/09    Time: 12:45p
//Updated in $/LeapCC/Model
//put new message for hostel room type detail and message in add or edit
//
//*****************  Version 28  *****************
//User: Rajeev       Date: 4/22/09    Time: 10:21a
//Updated in $/LeapCC/Model
//Updated for conslidated student report
//
//*****************  Version 27  *****************
//User: Rajeev       Date: 4/09/09    Time: 3:18p
//Updated in $/LeapCC/Model
//added print reports
//
//*****************  Version 26  *****************
//User: Parveen      Date: 4/07/09    Time: 12:46p
//Updated in $/LeapCC/Model
//getTimeTableClassGroups function added
//
//*****************  Version 25  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:34
//Updated in $/LeapCC/Model
//Added functions for bus masters
//
//*****************  Version 24  *****************
//User: Jaineesh     Date: 3/27/09    Time: 2:34p
//Updated in $/LeapCC/Model
//modified in test type category
//
//*****************  Version 23  *****************
//User: Jaineesh     Date: 3/16/09    Time: 6:24p
//Updated in $/LeapCC/Model
//modified for test type & put test type category
//
//*****************  Version 22  *****************
//User: Parveen      Date: 3/16/09    Time: 12:20p
//Updated in $/LeapCC/Model
//query update
//
//*****************  Version 21  *****************
//User: Ajinder      Date: 3/06/09    Time: 12:23p
//Updated in $/LeapCC/Model
//called different function for subject and group population
//
//*****************  Version 20  *****************
//User: Rajeev       Date: 1/14/09    Time: 6:17p
//Updated in $/LeapCC/Model
//added fetch room select box
//
//*****************  Version 19  *****************
//User: Rajeev       Date: 1/05/09    Time: 1:30p
//Updated in $/LeapCC/Model
//Updated class query with active label parameter
//
//*****************  Version 18  *****************
//User: Rajeev       Date: 12/24/08   Time: 3:47p
//Updated in $/LeapCC/Model
//Changed class query to show only active class at admit student
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 12/23/08   Time: 2:16p
//Updated in $/LeapCC/Model
//Updated with single class selection dropdown and REQUIRED_FIELD
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 23/12/08   Time: 12:05
//Updated in $/LeapCC/Model
//Added function for offence
//
//*****************  Version 15  *****************
//User: Pushpender   Date: 12/18/08   Time: 12:09p
//Updated in $/LeapCC/Model
//modified getTeacherData function
//
//*****************  Version 14  *****************
//User: Pushpender   Date: 12/17/08   Time: 6:49p
//Updated in $/LeapCC/Model
//appended instituteId condition in query of getTeacherData function
//
//*****************  Version 12  *****************
//User: Pushpender   Date: 12/16/08   Time: 6:33p
//Updated in $/LeapCC/Model
//added periodSlot function
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 12/13/08   Time: 4:30p
//Updated in $/LeapCC/Model
//changed getAllCurrentGroups() function
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 12/09/08   Time: 6:41p
//Updated in $/LeapCC/Model
//added function getGroupTypes()
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 12/09/08   Time: 6:38p
//Updated in $/LeapCC/Model
//Updated HTML functions with student filter
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 12/09/08   Time: 12:09p
//Updated in $/LeapCC/Model
//modification in query for student feed back
//
//*****************  Version 7  *****************
//User: Parveen      Date: 12/08/08   Time: 5:15p
//Updated in $/LeapCC/Model
//employee Id code set
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 12/05/08   Time: 5:13p
//Updated in $/LeapCC/Model
//modified in student attendance query
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/05/08   Time: 1:27p
//Updated in $/LeapCC/Model
//QUERY CHECK
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/05/08   Time: 11:54a
//Updated in $/LeapCC/Model
//getDegreeWithCode function added
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/05/08   Time: 11:34a
//Updated in $/LeapCC/Model
//make new function getStudentAttendance() as study period
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/04/08   Time: 6:13p
//Updated in $/LeapCC/Model
//modified getStudyPeriodData() for study period
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 152  *****************
//User: Parveen      Date: 12/01/08   Time: 5:53p
//Updated in $/Leap/Source/Model
//
//*****************  Version 151  *****************
//User: Parveen      Date: 12/01/08   Time: 11:56a
//Updated in $/Leap/Source/Model
//Adding feedback survey Functions
//
//*****************  Version 150  *****************
//User: Rajeev       Date: 11/21/08   Time: 3:09p
//Updated in $/Leap/Source/Model
//added Ajax functionality on hostel and bus route
//
//*****************  Version 149  *****************
//User: Dipanjan     Date: 11/21/08   Time: 11:18a
//Updated in $/Leap/Source/Model
//Added functions for department dropdown
//
//*****************  Version 148  *****************
//User: Dipanjan     Date: 11/20/08   Time: 1:23p
//Updated in $/Leap/Source/Model
//Added getFinancialYear() and getLeaveType()  functions for getting
//list of financial years and leave types
//
//*****************  Version 147  *****************
//User: Jaineesh     Date: 11/20/08   Time: 10:25a
//Updated in $/Leap/Source/Model
//make new function getEmployeeName() get the teacher name for student
//feedback
//
//*****************  Version 146  *****************
//User: Rajeev       Date: 11/18/08   Time: 12:20p
//Updated in $/Leap/Source/Model
//changed getclass data function to fetch classes in sorted order
//
//*****************  Version 145  *****************
//User: Ajinder      Date: 11/17/08   Time: 4:58p
//Updated in $/Leap/Source/Model
//added code for showing course in student filter
//
//*****************  Version 144  *****************
//User: Dipanjan     Date: 11/17/08   Time: 4:44p
//Updated in $/Leap/Source/Model
//Added getFeedBackCategory() function
//
//*****************  Version 143  *****************
//User: Jaineesh     Date: 11/17/08   Time: 1:52p
//Updated in $/Leap/Source/Model
//modified function getScAttendance1() for group by att.subjectId,
//att.sectionId 
//
//*****************  Version 142  *****************
//User: Parveen      Date: 11/15/08   Time: 4:02p
//Updated in $/Leap/Source/Model
//
//*****************  Version 141  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:22p
//Updated in $/Leap/Source/Model
//Added getFeedBackCategory() and getFeedBackLabel() functions
//to get data about FeedBack Categories and Labels
//
//*****************  Version 140  *****************
//User: Rajeev       Date: 11/12/08   Time: 10:52a
//Updated in $/Leap/Source/Model
//updated getattendance1 function with "Round" parameter
//
//*****************  Version 139  *****************
//User: Jaineesh     Date: 11/12/08   Time: 10:20a
//Updated in $/Leap/Source/Model
//modified in countgetattendance for date
//
//*****************  Version 138  *****************
//User: Jaineesh     Date: 11/11/08   Time: 6:29p
//Updated in $/Leap/Source/Model
//get new function for paging countScAttendance1()
//
//*****************  Version 137  *****************
//User: Jaineesh     Date: 11/11/08   Time: 12:31p
//Updated in $/Leap/Source/Model
//modified in getScAttendance1()
//
//*****************  Version 136  *****************
//User: Jaineesh     Date: 11/11/08   Time: 11:13a
//Updated in $/Leap/Source/Model
//modified in function getScAttendance1()
//
//*****************  Version 135  *****************
//User: Jaineesh     Date: 11/10/08   Time: 2:51p
//Updated in $/Leap/Source/Model
//modified in getStudyPeriodData()
//
//*****************  Version 134  *****************
//User: Dipanjan     Date: 11/10/08   Time: 10:15a
//Updated in $/Leap/Source/Model
//Modified getSessionData() and getSessionDetail() functions for
//genarating "session" drop down
//
//*****************  Version 133  *****************
//User: Ajinder      Date: 11/05/08   Time: 6:59p
//Updated in $/Leap/Source/Model
//added function:
//1. getRoles()
//
//for role permissions
//
//*****************  Version 132  *****************
//User: Dipanjan     Date: 11/05/08   Time: 9:39a
//Updated in $/Leap/Source/Model
//Added getResourceCategory() function to get a list resource categories
//
//*****************  Version 131  *****************
//User: Jaineesh     Date: 11/03/08   Time: 12:49p
//Updated in $/Leap/Source/Model
//modified in getStudyPeriodData() by sending student Id
//
//*****************  Version 130  *****************
//User: Jaineesh     Date: 10/31/08   Time: 5:01p
//Updated in $/Leap/Source/Model
//make new function for getting period name
//
//*****************  Version 129  *****************
//User: Ajinder      Date: 10/31/08   Time: 10:38a
//Updated in $/Leap/Source/Model
//added function getPromotedClassWithStudyPeriod()
//
//*****************  Version 128  *****************
//User: Jaineesh     Date: 10/25/08   Time: 11:58a
//Updated in $/Leap/Source/Model
//modified in getscattendance1 for section abbr or section type
//
//*****************  Version 127  *****************
//User: Parveen      Date: 10/24/08   Time: 4:55p
//Updated in $/Leap/Source/Model
//code review
//
//*****************  Version 126  *****************
//User: Jaineesh     Date: 10/24/08   Time: 1:45p
//Updated in $/Leap/Source/Model
//modified
//
//*****************  Version 125  *****************
//User: Ajinder      Date: 10/23/08   Time: 12:47p
//Updated in $/Leap/Source/Model
//added function getGradingLabels()
//
//*****************  Version 124  *****************
//User: Jaineesh     Date: 10/23/08   Time: 11:52a
//Updated in $/Leap/Source/Model
//add new function getHistogramLabel
//
//*****************  Version 123  *****************
//User: Ajinder      Date: 10/22/08   Time: 5:27p
//Updated in $/Leap/Source/Model
//added following functions for marks histogram:
//
//1. getMarksTransferredSubjectsWithCode()
//2. getHistogramLabels()
//
//*****************  Version 122  *****************
//User: Arvind       Date: 10/22/08   Time: 2:49p
//Updated in $/Leap/Source/Model
//added a new function getTestType()
//
//*****************  Version 121  *****************
//User: Jaineesh     Date: 10/22/08   Time: 10:56a
//Updated in $/Leap/Source/Model
//modified in getscattendance1 function
//
//*****************  Version 120  *****************
//User: Rajeev       Date: 10/21/08   Time: 4:24p
//Updated in $/Leap/Source/Model
//updated "getStudyPeriod" function for showling active and past
//
//*****************  Version 119  *****************
//User: Jaineesh     Date: 10/21/08   Time: 4:14p
//Updated in $/Leap/Source/Model
//get new function for attendance paging
//
//*****************  Version 118  *****************
//User: Jaineesh     Date: 10/20/08   Time: 6:11p
//Updated in $/Leap/Source/Model
//modified 
//
//*****************  Version 117  *****************
//User: Jaineesh     Date: 10/20/08   Time: 6:05p
//Updated in $/Leap/Source/Model
//modified in function for format date
//
//*****************  Version 116  *****************
//User: Jaineesh     Date: 10/20/08   Time: 4:40p
//Updated in $/Leap/Source/Model
//modified
//
//*****************  Version 115  *****************
//User: Pushpender   Date: 10/14/08   Time: 1:05p
//Updated in $/Leap/Source/Model
//Modified getBatch function, replaced Straight join with Left Join, to
//correct the issue " the batch was not being populated in dropdown if
//not associated with class"
//
//*****************  Version 114  *****************
//User: Ajinder      Date: 10/13/08   Time: 12:04p
//Updated in $/Leap/Source/Model
//added function getTestSubjectsWithCode()
//
//*****************  Version 113  *****************
//User: Pushpender   Date: 10/06/08   Time: 12:17p
//Updated in $/Leap/Source/Model
//modified getTeacherData, removed arguments
//
//*****************  Version 112  *****************
//User: Pushpender   Date: 9/30/08    Time: 5:28p
//Updated in $/Leap/Source/Model
//added isActive field in query of getTimeTableLabel function
//
//*****************  Version 111  *****************
//User: Rajeev       Date: 9/30/08    Time: 3:55p
//Updated in $/Leap/Source/Model
//updated getTimeTableLabel function
//
//*****************  Version 110  *****************
//User: Rajeev       Date: 9/30/08    Time: 3:43p
//Updated in $/Leap/Source/Model
//added "getTimeTableLabel" function
//
//*****************  Version 109  *****************
//User: Rajeev       Date: 9/29/08    Time: 5:45p
//Updated in $/Leap/Source/Model
//updated the file
//
//*****************  Version 108  *****************
//User: Ajinder      Date: 9/22/08    Time: 3:20p
//Updated in $/Leap/Source/Model
//added function getSectionAbbr() to fetch section abbr and section type.
//
//*****************  Version 107  *****************
//User: Pushpender   Date: 9/20/08    Time: 7:44p
//Updated in $/Leap/Source/Model
//removed CONCAT in getSubjectsWithCode()
//
//*****************  Version 106  *****************
//User: Pushpender   Date: 9/19/08    Time: 8:18p
//Updated in $/Leap/Source/Model
//added field name groupShort in getLastLevelGroups function and
//subjectCode in getClassSubject
//
//*****************  Version 105  *****************
//User: Jaineesh     Date: 9/19/08    Time: 5:17p
//Updated in $/Leap/Source/Model
//modified in getAttendance & getScAttendance to show field attendance
//from date
//
//*****************  Version 104  *****************
//User: Arvind       Date: 9/19/08    Time: 3:05p
//Updated in $/Leap/Source/Model
//added two functions for autopopulate i.e getScClassBatch( )and
//getScClassStudyPeriod()  for batch and studty period by selecting class
//
//*****************  Version 103  *****************
//User: Rajeev       Date: 9/18/08    Time: 1:58p
//Updated in $/Leap/Source/Model
//updated batch query
//
//*****************  Version 102  *****************
//User: Ajinder      Date: 9/17/08    Time: 6:36p
//Updated in $/Leap/Source/Model
//updated function getSubjectsWithCode(), removed subjectName
//
//*****************  Version 101  *****************
//User: Rajeev       Date: 9/17/08    Time: 5:52p
//Updated in $/Leap/Source/Model
//added section function
//
//*****************  Version 100  *****************
//User: Jaineesh     Date: 9/17/08    Time: 12:03p
//Updated in $/Leap/Source/Model
//modification in getScAttendance function
//
//*****************  Version 99  *****************
//User: Rajeev       Date: 9/16/08    Time: 4:55p
//Updated in $/Leap/Source/Model
//updated files according to subject centric
//
//*****************  Version 98  *****************
//User: Jaineesh     Date: 9/16/08    Time: 3:46p
//Updated in $/Leap/Source/Model
//modified in getAttendance
//
//*****************  Version 97  *****************
//User: Ajinder      Date: 9/15/08    Time: 3:01p
//Updated in $/Leap/Source/Model
//added following functions:
//1. getSubjectsWithCode()
//2. getSectionList()
//
//*****************  Version 96  *****************
//User: Arvind       Date: 9/13/08    Time: 4:11p
//Updated in $/Leap/Source/Model
//added  getScAttendance function for SC Module
//
//*****************  Version 95  *****************
//User: Jaineesh     Date: 9/13/08    Time: 12:15p
//Updated in $/Leap/Source/Model
//modification in getAttendance function for date
//
//*****************  Version 94  *****************
//User: Ajinder      Date: 9/10/08    Time: 2:54p
//Updated in $/Leap/Source/Model
//applied isActive for class
//
//*****************  Version 93  *****************
//User: Rajeev       Date: 9/09/08    Time: 1:42p
//Updated in $/Leap/Source/Model
//added subject code in getattendance function
//
//*****************  Version 92  *****************
//User: Ajinder      Date: 9/09/08    Time: 1:22p
//Updated in $/Leap/Source/Model
//modified following functions, applied isActive check for classes:
//1. getSessionClasses()
//2. getClassWithStudyPeriod()
//
//*****************  Version 91  *****************
//User: Rajeev       Date: 9/08/08    Time: 5:13p
//Updated in $/Leap/Source/Model
//updated group query
//
//*****************  Version 90  *****************
//User: Rajeev       Date: 9/08/08    Time: 4:29p
//Updated in $/Leap/Source/Model
//updated class function with "isActive = 1" to show all active classes
//
//*****************  Version 89  *****************
//User: Rajeev       Date: 8/25/08    Time: 7:27p
//Updated in $/Leap/Source/Model
//fees cycle function modified
//
//*****************  Version 88  *****************
//User: Rajeev       Date: 8/25/08    Time: 5:29p
//Updated in $/Leap/Source/Model
//reviewed last level group function
//
//*****************  Version 86  *****************
//User: Dipanjan     Date: 8/11/08    Time: 12:38p
//Updated in $/Leap/Source/Model
//Added getFormattedClass() function
//
//*****************  Version 84  *****************
//User: Ajinder      Date: 8/09/08    Time: 4:49p
//Updated in $/Leap/Source/Model
//updated comment
//
//*****************  Version 82  *****************
//User: Rajeev       Date: 8/09/08    Time: 2:00p
//Updated in $/Leap/Source/Model
//added function to fetch studyperiod based on class
//
//*****************  Version 81  *****************
//User: Rajeev       Date: 8/08/08    Time: 6:06p
//Updated in $/Leap/Source/Model
//updated get class function
//
//*****************  Version 80  *****************
//User: Pushpender   Date: 8/07/08    Time: 7:40p
//Updated in $/Leap/Source/Model
//global $sessionHandler; written in getAttendance function
//
//*****************  Version 79  *****************
//User: Pushpender   Date: 8/07/08    Time: 6:49p
//Updated in $/Leap/Source/Model
//added SessionId and InstituteId from php session in attendance query of
//getAttenance function
//
//*****************  Version 78  *****************
//User: Dipanjan     Date: 8/07/08    Time: 6:35p
//Updated in $/Leap/Source/Model
//
//*****************  Version 77  *****************
//User: Pushpender   Date: 8/07/08    Time: 5:31p
//Updated in $/Leap/Source/Model
//Changed getAttendance function, added check for Member of class in
//query
//
//*****************  Version 76  *****************
//User: Dipanjan     Date: 8/05/08    Time: 7:59p
//Updated in $/Leap/Source/Model
//
//*****************  Version 75  *****************
//User: Arvind       Date: 8/02/08    Time: 11:10a
//Updated in $/Leap/Source/Model
//added instituteid in query of getfee cycle
//
//*****************  Version 74  *****************
//User: Rajeev       Date: 8/02/08    Time: 11:00a
//Updated in $/Leap/Source/Model
//updated getclass function
//
//*****************  Version 72  *****************
//User: Ajinder      Date: 8/01/08    Time: 3:26p
//Updated in $/Leap/Source/Model
//modified function getSessionDetail(), set ordering to sessionId
//
//*****************  Version 71  *****************
//User: Jaineesh     Date: 8/01/08    Time: 12:29p
//Updated in $/Leap/Source/Model
//modified in getAttendance Function
//
//*****************  Version 70  *****************
//User: Jaineesh     Date: 7/31/08    Time: 7:46p
//Updated in $/Leap/Source/Model
//modification in getAttendance function to calculate percentage
//
//*****************  Version 69  *****************
//User: Pushpender   Date: 7/31/08    Time: 5:12p
//Updated in $/Leap/Source/Model
//corrected subjectwise attendance query
//
//*****************  Version 68  *****************
//User: Ajinder      Date: 7/31/08    Time: 12:07p
//Updated in $/Leap/Source/Model
//modified function getSessionDetail(), set the ordering on session year
//
//*****************  Version 67  *****************
//User: Rajeev       Date: 7/31/08    Time: 12:05p
//Updated in $/Leap/Source/Model
//added SUBJECT CORRESPONDING TO A CLASS function
//
//*****************  Version 65  *****************
//User: Arvind       Date: 7/29/08    Time: 3:48p
//Updated in $/Leap/Source/Model
//modified the query of getFeeData()
//
//*****************  Version 64  *****************
//User: Jaineesh     Date: 7/28/08    Time: 6:36p
//Updated in $/Leap/Source/Model
//modified in getAttendance
//
//*****************  Version 62  *****************
//User: Rajeev       Date: 7/25/08    Time: 7:21p
//Updated in $/Leap/Source/Model
//updated getattendance function
//
//*****************  Version 61  *****************
//User: Jaineesh     Date: 7/25/08    Time: 6:59p
//Updated in $/Leap/Source/Model
//change in function getAttendance
//
//*****************  Version 60  *****************
//User: Administrator Date: 7/25/08    Time: 6:33p
//Updated in $/Leap/Source/Model
//put the function getAttendance
//
//*****************  Version 59  *****************
//User: Ajinder      Date: 7/25/08    Time: 5:21p
//Updated in $/Leap/Source/Model
//added function getLastLevelGroups()
//
//*****************  Version 58  *****************
//User: Ajinder      Date: 7/24/08    Time: 4:14p
//Updated in $/Leap/Source/Model
//added function getCurrentSessionClasses()
//
//*****************  Version 56  *****************
//User: Rajeev       Date: 7/22/08    Time: 10:44a
//Updated in $/Leap/Source/Model
//added bank branch function
//
//*****************  Version 55  *****************
//User: Ajinder      Date: 7/22/08    Time: 10:39a
//Updated in $/Leap/Source/Model
//added function getClassWithStudyPeriod() for attendanceMissedReport
//
//*****************  Version 54  *****************
//User: Arvind       Date: 7/19/08    Time: 12:09p
//Updated in $/Leap/Source/Model
//add a new file for feehead
//
//*****************  Version 53  *****************
//User: Dipanjan     Date: 7/18/08    Time: 4:32p
//Updated in $/Leap/Source/Model
//
//*****************  Version 52  *****************
//User: Dipanjan     Date: 7/18/08    Time: 2:55p
//Updated in $/Leap/Source/Model
//Added getAttendanceCode() function for getting all the attendance codes
//from attendance_code table
//
//*****************  Version 51  *****************
//User: Jaineesh     Date: 7/18/08    Time: 1:36p
//Updated in $/Leap/Source/Model
//change in getRole Query
//
//*****************  Version 50  *****************
//User: Dipanjan     Date: 7/18/08    Time: 12:20p
//Updated in $/Leap/Source/Model
//Added $condions parameter  in getRole() function
//
//*****************  Version 49  *****************
//User: Rajeev       Date: 7/18/08    Time: 11:39a
//Updated in $/Leap/Source/Model
//add function to get list of banks
//
//*****************  Version 48  *****************
//User: Dipanjan     Date: 7/17/08    Time: 11:11a
//Updated in $/Leap/Source/Model
//Modified getRole() function for not displaying teacher,student,parent
//in add/edit user dropdown("ManageUser" module)
//
//*****************  Version 47  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:16p
//Updated in $/Leap/Source/Model
//Added getUserName() function to get the userName corresponding to a
//userId
//
//*****************  Version 46  *****************
//User: Dipanjan     Date: 7/14/08    Time: 2:59p
//Updated in $/Leap/Source/Model
//Modified getCountries() function to have $condition clause
//
//*****************  Version 45  *****************
//User: Rajeev       Date: 7/12/08    Time: 1:08p
//Updated in $/Leap/Source/Model
//added "Class seprator" constant
//
//*****************  Version 44  *****************
//User: Jaineesh     Date: 7/12/08    Time: 11:34a
//Updated in $/Leap/Source/Model
//add function for block name
//
//*****************  Version 43  *****************
//User: Arvind       Date: 7/11/08    Time: 6:50p
//Updated in $/Leap/Source/Model
//modified the parameter feeCycleId passed in getFeeCycle() function
//
//*****************  Version 42  *****************
//User: Rajeev       Date: 7/11/08    Time: 3:34p
//Updated in $/Leap/Source/Model
//updated study period dropdown query 
//
//*****************  Version 41  *****************
//User: Rajeev       Date: 7/11/08    Time: 2:44p
//Updated in $/Leap/Source/Model
//added condition parameter in state and country functions while fetching
//data
//
//*****************  Version 40  *****************
//User: Dipanjan     Date: 7/10/08    Time: 7:01p
//Updated in $/Leap/Source/Model
//Added getBuilding() function for getting list of all buildings
//
//*****************  Version 39  *****************
//User: Rajeev       Date: 7/10/08    Time: 5:16p
//Updated in $/Leap/Source/Model
//added a common function to fetch valid class from given parameters
//
//*****************  Version 38  *****************
//User: Dipanjan     Date: 7/10/08    Time: 12:06p
//Updated in $/Leap/Source/Model
//Add  getUserRole() function to get the rolename of a logged in user
//
//*****************  Version 37  *****************
//User: Rajeev       Date: 7/09/08    Time: 1:17p
//Updated in $/Leap/Source/Model
//added bus route and bus stop functions
//
//*****************  Version 36  *****************
//User: Rajeev       Date: 7/09/08    Time: 12:59p
//Updated in $/Leap/Source/Model
//added ajax function for hostel room
//
//*****************  Version 35  *****************
//User: Rajeev       Date: 7/07/08    Time: 4:14p
//Updated in $/Leap/Source/Model
//added year, month, date dropdown functions
//
//*****************  Version 34  *****************
//User: Arvind       Date: 7/05/08    Time: 3:43p
//Updated in $/Leap/Source/Model
//change the table names feehead to fee_head and feecycle to fee_cycle
//
//*****************  Version 33  *****************
//User: Rajeev       Date: 7/05/08    Time: 3:40p
//Updated in $/Leap/Source/Model
//added quota master function
//
//*****************  Version 32  *****************
//User: Pushpender   Date: 7/04/08    Time: 5:04p
//Updated in $/Leap/Source/Model
//added getSessionDetail Function
//
//*****************  Version 31  *****************
//User: Jaineesh     Date: 7/04/08    Time: 11:30a
//Updated in $/Leap/Source/Model
//modified in role function
//
//*****************  Version 30  *****************
//User: Jaineesh     Date: 7/04/08    Time: 11:07a
//Updated in $/Leap/Source/Model
//make new query for role 
//
//*****************  Version 29  *****************
//User: Dipanjan     Date: 7/03/08    Time: 8:26p
//Updated in $/Leap/Source/Model
//Modify table names to have underscore
//
//*****************  Version 28  *****************
//User: Jaineesh     Date: 7/03/08    Time: 6:27p
//Updated in $/Leap/Source/Model
//modified in function
//
//*****************  Version 26  *****************
//User: Jaineesh     Date: 7/03/08    Time: 11:37a
//Updated in $/Leap/Source/Model
//
//*****************  Version 25  *****************
//User: Rajeev       Date: 7/03/08    Time: 9:52a
//Updated in $/Leap/Source/Model
//updated study period table name
//
//*****************  Version 24  *****************
//User: Jaineesh     Date: 7/03/08    Time: 9:42a
//Updated in $/Leap/Source/Model
//making function for grouptypename
//
//*****************  Version 23  *****************
//User: Rajeev       Date: 7/02/08    Time: 5:23p
//Updated in $/Leap/Source/Model
//added selectbox in which university,degree and branch 
//
//*****************  Version 22  *****************
//User: Arvind       Date: 7/02/08    Time: 11:26a
//Updated in $/Leap/Source/Model
//Added two new functions as follows :
//
//1) getFeeHead is used to get the values of feehead table
//2) getFeeCycle  is used to get the values of feecycle table
//
//*****************  Version 21  *****************
//User: Rajeev       Date: 7/02/08    Time: 10:38a
//Updated in $/Leap/Source/Model
//concatinated study period function with periodicity in studyperiod
//function
//
//*****************  Version 20  *****************
//User: Dipanjan     Date: 7/01/08    Time: 5:10p
//Updated in $/Leap/Source/Model
//Added getRole() function to fetch all roles from "role" table
//
//*****************  Version 19  *****************
//User: Rajeev       Date: 6/30/08    Time: 3:41p
//Updated in $/Leap/Source/Model
//add periodicity common query code to fetch data
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 6/27/08    Time: 12:25p
//Updated in $/Leap/Source/Model
//Add new function
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 6/19/08    Time: 9:55a
//Updated in $/Leap/Source/Model
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 6/18/08    Time: 8:19p
//Updated in $/Leap/Source/Model
//
//*****************  Version 15  *****************
//User: Arvind       Date: 6/17/08    Time: 2:57p
//Updated in $/Leap/Source/Model
//modified
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 6/16/08    Time: 4:04p
//Updated in $/Leap/Source/Model
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 6/16/08    Time: 3:22p
//Updated in $/Leap/Source/Model
//getStates function not modified ,
//getStateCountry() and getCityState() functions added
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 6/16/08    Time: 9:46a
//Updated in $/Leap/Source/Model
//
//*****************  Version 11  *****************
//User: Administrator Date: 6/14/08    Time: 7:42p
//Updated in $/Leap/Source/Model
//modification
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 6/14/08    Time: 7:35p
//Updated in $/Leap/Source/Model
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 6/14/08    Time: 6:30p
//Updated in $/Leap/Source/Model
//Adding function for university list Done
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 6/14/08    Time: 12:36p
//Updated in $/Leap/Source/Model
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/14/08    Time: 12:19p
//Updated in $/Leap/Source/Model
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/13/08    Time: 3:11p
//Updated in $/Leap/Source/Model
//Adding City and Designation Functions Complete
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/13/08    Time: 1:50p
//Updated in $/Leap/Source/Model
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/12/08    Time: 6:53p
//Updated in $/Leap/Source/Model
//Updation Complete

?>