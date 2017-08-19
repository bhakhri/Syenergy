<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class LoginManager {
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
//--------------------------------------------------------
// Purpose: authenticate userid and password from user table
// Author:Pushpender Kumar Chauhan
// Params: requires username and userpass
// Returns: array
// Created on : (14.06.2008)
// Modified on: (04.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
// --------------------------------------------------------

    public function authenticateUser($userName, $userPass) {
        $query = "SELECT
                        u.userId, u.userName, u.roleId,
                        u.userStatus, u.instituteId, r.roleName,
                        if(ufs.themeId is null, 1, ufs.themeId) as themeId,
                        if(ufs.grouping is null, 1, ufs.grouping) as grouping,
                        r.roleType
                  FROM
                        role r,
                        user u
                        left join user_prefs ufs on (ufs.userId=u.userId)
                  WHERE
                        u.userName='".add_slashes($userName)."'
                        AND u.userPassword = '".md5(add_slashes($userPass))."'
                        AND u.roleId=r.roleId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//Getting UserId from userName

		public function getUserId($conditions='') {
 	
		      $query="SELECT 
				      userId
			      FROM 
				      user
			      WHERE      
			       	      userName='$conditions'";
			
     		     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		 }


		//CHECKING BLOCK USER ID IN BLOCK_STU TABLE
		public function blockedUser($studentId) {

		      $query = "SELECT
    				      isStatus,message
      			         FROM
  				      block_stu
				 WHERE
  				      studentId='".$studentId."'";
		
		     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		 }

		


public function getDefaultUserRole($userId, $instituteId) {
        $query = "SELECT
                        u.userId,ur.defaultRoleId
                  FROM
                        user u,user_role ur
                  WHERE
                        u.userId=ur.userId
                        AND u.userId=$userId
                        AND ur.instituteId=$instituteId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------
// Purpose: to get institute name, id, code, abbr
// Author:Pushpender Kumar Chauhan
// Params: requires institute id
// Returns: array
// Created on : (04.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getInstituteDetail($instituteId) {

        $query = "SELECT instituteId, instituteName, instituteCode, instituteAbbr,instituteLogo FROM institute WHERE instituteId='$instituteId'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//--------------------------------------------------------
// Purpose: to get session name, id, abbr
// Author:Pushpender Kumar Chauhan
// Params: requires session id
// Returns: array
// Created on : (04.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getSessionDetail($sessionId) {

        $query = "SELECT sessionName, abbreviation, sessionId, sessionYear FROM session WHERE sessionId='$sessionId'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------
// Purpose: to get student id, name, class
// Author:Pushpender Kumar Chauhan
// Params: requires userid
// Returns: array
// Created on : (15.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getStudentDetail($userId) {
        global $sessionHandler;
        $tableName = 'student';
        if(CURRENT_PROCESS_FOR=='sc') {   //subject centric
            $tableName = 'sc_student';
        }
        $query = "SELECT 
                        s.studentId,CONCAT(IFNULL(s.firstName,''), ' ', IFNULL(s.lastName,'')) AS studentName, 
                        s.classId, s.studentPhoto, IF(IFNULL(s.rollNo,'')='','',rollNo) AS rollNo, 
                        IF(IFNULL(s.regNo,'')='','',regNo) AS regNo,
                        IF(IFNULL(s.universityRollNo,'')='','',s.universityRollNo) AS universityRollNo,
                        IF(IFNULL(s.fatherName,'')='','',s.fatherName) AS fatherName,
                        IF(IFNULL(s.motherName,'')='','',s.motherName) AS motherName,
                        IF(IFNULL(s.guardianName,'')='','',s.guardianName) AS guardianName,
                        IF(IFNULL(s.dateOfAdmission,'0000-00-00')='0000-00-00','',s.dateOfAdmission) AS dateOfAdmission, 
                        IF(IFNULL(s.dateOfBirth,'0000-00-00')='0000-00-00','',s.dateOfBirth) AS dateOfBirth,
                        c.className, s.sAllClass, c.batchId, c.degreeId, c.branchId, s.migrationStudyPeriod,
                        IFNULL(s.studentMobileNo,'') AS studentMobileNo, IFNULL(s.fatherMobileNo,'') AS fatherMobileNo, 
                        IFNULL(s.motherMobileNo,'') AS motherMobileNo, IFNULL(s.guardianMobileNo,'') AS guardianMobileNo,
                        IFNULL(s.corrPhone,'') AS corrPhone, IFNULL(s.permPhone,'') AS permPhone
                  FROM 
                        $tableName s, class c 
                  WHERE 
                        c.classId=s.classId AND 
                        (s.userId=$userId OR s.fatherUserId=$userId OR s.motherUserId=$userId OR s.guardianUserId=$userId) AND 
                        c.sessionId=".$sessionHandler->getSessionVariable('SessionId')." AND 
                        c.instituteId=".$sessionHandler->getSessionVariable('InstituteId');

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getStudentSubjectDetail() {  
        
        global $sessionHandler;  
         
        $classId = $sessionHandler->getSessionVariable('ClassId');
        $studentId = $sessionHandler->getSessionVariable('StudentId');
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
                        subjectCode";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//--------------------------------------------------------
// Purpose: to get employee id, name, code, abbr
// Author:Pushpender Kumar Chauhan
// Params: requires user id
// Returns: array
// Created on : (15.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getEmployeeDetail($userId) { //teacher detail

        $query = "SELECT 
                        employeeId,employeeName,employeeImage,employeeCode, 
                        employeeAbbreviation, emailAddress, 
                        IF(IFNULL(dateOfBirth,'0000-00-00')='0000-00-00','',dateOfBirth) AS dateOfBirth, 
                        IF(IFNULL(dateOfMarriage,'0000-00-00')='0000-00-00','',dateOfMarriage) AS dateOfMarriage, 
                        IF(IFNULL(dateOfJoining,'0000-00-00')='0000-00-00','',dateOfJoining) AS dateOfJoining 
                  FROM 
                        employee 
                  WHERE 
                        userId=$userId";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	 public function getEmployeeInstitute($userId) {
        $query = "SELECT a.instituteId, b.instituteAbbr, b.instituteCode, b.instituteName, b.instituteLogo FROM employee_can_teach_in a, institute b, employee c WHERE c.userId=$userId and a.instituteId = b.instituteId and a.employeeId = c.employeeId limit 0,1";
        //$query = "SELECT a.instituteId, b.instituteAbbr, b.instituteCode, b.instituteName, b.instituteLogo FROM user_role a, institute b, employee c WHERE c.userId=$userId and a.instituteId = b.instituteId and a.userId = c.userId limit 0,1";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }



//--------------------------------------------------------
// Purpose: to get emailAddress  based on userName
// Author:Arvind Singh Rawat
// Params: requires user Name
// Returns: array
// Created on : (23.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getEmail($username) {

        $tableName = 'student';

        $query = "SELECT userId,roleId FROM user WHERE userName='".add_slashes($username)."'";
		$role=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

		if($role[0]['roleId']=='2' || $role[0]['roleId']=='1' ){
            $query="Select emailAddress, employeeName AS name FROM employee WHERE userId='".$role[0]['userId']."'";
            $resultEmail=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
            $resultEmail[0]['roleId']=$role[0]['roleId'];
            $resultEmail[0]['userId']=$role[0]['userId'];
            return $resultEmail;
        }
        else if($role[0]['roleId']=='3'){
            // fatherUserId check
            $query="Select fatherEmail,fatherName AS name  FROM $tableName WHERE fatherUserId='".$role[0]['userId']."'";
            $resultFather=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
            if($resultFather[0]['fatherEmail']){
                $resultFather[0]['roleId']=$role[0]['roleId'];
                $resultFather[0]['userId']=$role[0]['userId'];
                return $resultFather;
                //return $resultFather[0]['fatherEmail'];
            }

            //motherUserId Check
            $query="Select motherEmail,motherName AS name  FROM $tableName WHERE motherUserId ='".$role[0]['userId']."'";
            $emailMother=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
            if($emailMother[0]['motherEmail']){
                $emailMother[0]['roleId']=$role[0]['roleId'];
                 $emailMother[0]['userId']=$role[0]['userId'];
                return $emailMother;
            }
            // Guardian Check
            $query="Select guardianEmail,guardianName AS name  FROM $tableName WHERE guardianUserId='".$role[0]['userId']."'";
            $emailGuardian=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
            if($emailGuardian[0]['guardianEmail']){
                 $emailGuardian[0]['roleId']=$role[0]['roleId'];
                 $emailGuardian[0]['userId']=$role[0]['userId'];
                return $emailGuardian;
            }
        }
        else if($role[0]['roleId']=='4'){
            $query="Select studentEmail, CONCAT(firstName, ' ', lastName) AS name FROM $tableName WHERE userId='".$role[0]['userId']."'";
            $emailStudent=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
            $emailStudent[0]['roleId']=$role[0]['roleId'];
            $emailStudent[0]['userId']=$role[0]['userId'];
            return $emailStudent;
        }
    }

//--------------------------------------------------------
// Purpose: to insert password based on $roleId ,$userId and $new Password
// Author:Arvind Singh Rawat
// Params: requires user Name
// Returns:
// Created on : (23.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function insertPassword($returnStatus) {
      return SystemDatabaseManager::getInstance()->runAutoUpdate('user', array('userPassword','verificationCode'), array($returnStatus[0]['newPassword'],NULL), "userId='".$returnStatus[0]['userId']."'" );
    }

//--------------------------------------------------------
// Purpose: to insert password based on verificationCode
// Author:Parveen Sharma
// Created on : 14-12-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function verifcationPassword($returnStatus) {
      return SystemDatabaseManager::getInstance()->runAutoUpdate('user', array('verificationCode'), array($returnStatus[0]['verifyCode']), "userId='".$returnStatus[0]['userId']."'" );
    }

//--------------------------------------------------------
// Purpose: to fetch values from config master table
// Author:Ajinder Singh
// Created on : (23.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	public function getConfigSettings() {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT param, value FROM config  WHERE instituteId=$instituteId ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//--------------------------------------------------------
// Purpose: to fetch values from role permission
// Author:Ajinder Singh
// Created on : (23.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	public function getAccessArray($roleId = 0) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		if ($roleId == 0) {
			$roleId = $sessionHandler->getSessionVariable('RoleId');
		}
		$query = "SELECT b.moduleName, a.viewPermission, a.editPermission, a.addPermission, a.deletePermission, b.isActive from role_permission a, module b where a.moduleId = b.moduleId and a.instituteId = $instituteId and a.roleId = ".$roleId;
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    
    public function getAccessInstituteArray($roleId = 0) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        if ($roleId == 0) {
            $roleId = $sessionHandler->getSessionVariable('RoleId');
        }
        $query = "SELECT 
                      DISTINCT IFNULL(allInstitute,'') AS allInstitute 
                  FROM 
                      role_permission a, module b 
                  WHERE
                      a.moduleId = b.moduleId AND a.roleId = ".$roleId;
                      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getModuleList() {
		$query = "SELECT moduleName from module ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
//--------------------------------------------------------
// Purpose: to save time of user when he logs in
// Author:Pushpender Kumar
// Created on : (18.10.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function updateUserLogTimeIn() {
        global $sessionHandler;

        $query = "INSERT INTO user_log (userId,dateTimeIn,ip) VALUES(".$sessionHandler->getSessionVariable('UserId').",NOW(),'".$_SERVER['REMOTE_ADDR']."') ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }
//--------------------------------------------------------
// Purpose: to save time of user when he logs out
// Author:Pushpender Kumar
// Created on : (18.10.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function updateUserLogTimeOut() {
        global $sessionHandler;
        $query = " SELECT MAX( userLogId ) as lastLogId FROM user_log WHERE userId =".$sessionHandler->getSessionVariable('UserId')." AND ip='".$sessionHandler->getSessionVariable('RemoteIp')."' ";
        $result = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        if($result[0]['lastLogId']>0) {
            $query = "UPDATE user_log SET dateTimeOut=NOW() WHERE userLogId=".$result[0]['lastLogId'];
            return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
        }
    }

	//--------------------------------------------------------
	// Purpose: to check if module exists
	// Author:Ajinder Singh
	// Created on : (05.10.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	public function checkModuleExists($moduleName) {
		$query = "
					SELECT
								moduleId
					FROM		module
					WHERE		moduleName = '$moduleName'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//--------------------------------------------------------
	// Purpose: to make new module
	// Author:Ajinder Singh
	// Created on : (05.10.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	public function addModule($moduleName) {
        $query = "INSERT INTO module (moduleName,isActive) VALUES('$moduleName', 1) ";
        SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
		return SystemDatabaseManager::getInstance()->lastInsertId();
	}
	//--------------------------------------------------------
	// Purpose: to make new module
	// Author:Ajinder Singh
	// Created on : (05.10.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	public function addModuleInTransaction($moduleName) {
        $query = "INSERT INTO module (moduleName,isActive) VALUES('$moduleName', 1) ";
        $return  = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
		if ($return == false) {
			return false;
		}
		return SystemDatabaseManager::getInstance()->lastInsertId();
	}

	//--------------------------------------------------------
	// Purpose: to check if module entry exists for that module or not
	// Author:Ajinder Singh
	// Created on : (05.10.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	
    public function checkModuleAccess($roleId, $moduleId, $instituteId='') {
		global $sessionHandler;
        
		$instituteId = $sessionHandler->getSessionVariable('InstituteId'); 
		$query = "
					SELECT
								permissionId
					FROM		role_permission
					WHERE		moduleId = '$moduleId'
					AND			roleId = $roleId
					AND			instituteId = $instituteId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//--------------------------------------------------------
	// Purpose: to unset all values for role
	// Author:Ajinder Singh
	// Created on : (05.10.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	public function unsetAllPermissions($roleId) {
        $query = "
					UPDATE
								role_permission
					SET			viewPermission = 0,
								editPermission = 0,
								addPermission = 0,
								deletePermission = 0
					WHERE		roleId = $roleId";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	}
	//--------------------------------------------------------
	// Purpose: to unset all values for role
	// Author:Ajinder Singh
	// Created on : (05.10.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	public function unsetAllPermissionsInTransaction($roleId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "
					UPDATE
								role_permission
					SET			viewPermission = 0,
								editPermission = 0,
								addPermission = 0,
								deletePermission = 0
					WHERE		roleId = $roleId
					AND			instituteId = $instituteId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	//--------------------------------------------------------
	// Purpose: to add permission for a role and a module
	// Author:Ajinder Singh
	// Created on : (05.10.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	public function addRolePermission($roleId,$moduleId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "
					INSERT INTO
									role_permission (moduleId, roleId, viewPermission, editPermission, addPermission, deletePermission)
					VALUES
									($moduleId, $roleId,0,0,0,0) ";
        SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
		return SystemDatabaseManager::getInstance()->lastInsertId();
	}
	//--------------------------------------------------------
	// Purpose: to add permission for a role and a module
	// Author:Ajinder Singh
	// Created on : (05.10.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	public function addRolePermissionInTransaction($roleId,$moduleId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "
					INSERT INTO
									role_permission (moduleId, roleId, viewPermission, editPermission, addPermission, deletePermission, instituteId)
					VALUES
									($moduleId, $roleId,0,0,0,0, $instituteId) ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	//--------------------------------------------------------
	// Purpose: to update permission for a role and a module
	// Author:Ajinder Singh
	// Created on : (05.10.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	public function updatePermissions($roleId,$moduleId, $modulePermission) {
		$query = "UPDATE role_permission SET $modulePermission = 1 WHERE roleId = $roleId AND moduleId = $moduleId";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	}
	//--------------------------------------------------------
	// Purpose: to update permission for a role and a module
	// Author:Ajinder Singh
	// Created on : (05.10.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	public function updatePermissionsInTransaction($roleId,$moduleId, $modulePermission) {
	
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "UPDATE role_permission SET $modulePermission = 1 WHERE roleId = $roleId AND moduleId = $moduleId AND instituteId = '$instituteId' ";
	
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}


//--------------------------------------------------------
// Purpose: to get emailAddress  based on userName
// Author:Arvind Singh Rawat
// Params: requires user Name
// Returns: array
// Created on : (23.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getVerifyEmail($CC) {

        $tableName = 'student';

        $query = "SELECT userId,roleId,verificationCode FROM user WHERE verificationCode='".$CC."'";
        $role=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

        if($role[0]['roleId']=='2' || $role[0]['roleId']=='1' ){
        $query="Select emailAddress FROM employee WHERE userId='".$role[0]['userId']."'";
        $resultEmail=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        $resultEmail[0]['roleId']=$role[0]['roleId'];
        $resultEmail[0]['userId']=$role[0]['userId'];
        return $resultEmail;
        }
        else if($role[0]['roleId']=='3'){
            // fatherUserId check
            $query="Select fatherEmail FROM $tableName WHERE fatherUserId='".$role[0]['userId']."'";
            $resultFather=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
            if($resultFather[0]['fatherEmail']){
                $resultFather[0]['roleId']=$role[0]['roleId'];
                $resultFather[0]['userId']=$role[0]['userId'];
                return $resultFather;
                //return $resultFather[0]['fatherEmail'];
            }

            //motherUserId Check
            $query="Select motherEmail FROM $tableName WHERE motherUserId ='".$role[0]['userId']."'";
            $emailMother=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
            if($emailMother[0]['motherEmail']){
                $emailMother[0]['roleId']=$role[0]['roleId'];
                 $emailMother[0]['userId']=$role[0]['userId'];
                return $emailMother;
            }
            // Guardian Check
            $query="Select guardianEmail FROM $tableName WHERE guardianUserId='".$role[0]['userId']."'";
            $emailGuardian=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
            if($emailGuardian[0]['guardianEmail']){
                 $emailGuardian[0]['roleId']=$role[0]['roleId'];
                 $emailGuardian[0]['userId']=$role[0]['userId'];
                return $emailGuardian;
            }
        }
        else if($role[0]['roleId']=='4'){
        $query="Select studentEmail FROM $tableName WHERE userId='".$role[0]['userId']."'";
        $emailStudent=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        $emailStudent[0]['roleId']=$role[0]['roleId'];
        $emailStudent[0]['userId']=$role[0]['userId'];
        return $emailStudent;
        }
    }

	//--------------------------------------------------------
	// Purpose: function to fetch dashboard module list
	// Author:Rajeev Aggarwal
	// Created on : (29.12.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	public function getDashboardFrameList($condition='', $orderBy='', $limit='') {
		global $sessionHandler;
		$InstituteId = $sessionHandler->getSessionVariable('InstituteId');
		$RoleId = $sessionHandler->getSessionVariable('RoleId');
		if($RoleId == 1){
			$query = "SELECT
						df.frameId,df.frameName AS  frameName1,
						REPLACE(df.frameName,' ','_') AS frameName
						, df.titleName,
						df.viewPermission, df.editPermission, df.addPermission, df.deletePermission
						FROM
						dashboard_frame df
						$condition
						$orderBy $limit";

			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}
		else{
			$query = "SELECT
						df.frameId,df.frameName AS  frameName1,
						REPLACE(df.frameName,' ','_') AS frameName
						, df.titleName,
						df.viewPermission, df.editPermission, df.addPermission, df.deletePermission

				 FROM
				 dashboard_frame df,dashboard_permissions dp

				 WHERE df.frameId = dp.frameId and dp.instituteId = $InstituteId AND dp.dashboardPermission='view' AND dp.roleId = ".$RoleId;

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}
	}
	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO INSERT Permissions to
	//
	// Author :Rajeev Aggarwal
	// Created on : (29.12.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------
	public function insertRolePermission($roleId) {

		global $REQUEST_DATA;

		$cnt  = $REQUEST_DATA['recordCount'];

		//$cnt = count($chb);
		if($roleId)
		{
			$query = "DELETE FROM `dashboard_permissions` WHERE roleId = $roleId";
			
			SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
			$insertValue = "";

			for($i=1;$i<=$cnt; $i++){

				$querySeprator = '';
				if($insertValue!=''){

					$querySeprator = ",";
				}
				$valName = "chb".$i;
				$chb1 = $REQUEST_DATA[$valName];
				if($chb1)
					$insertValue .= "$querySeprator ($chb1,$roleId)";

				//echo $valName;
			}

			if($insertValue)
				$query = "INSERT INTO `dashboard_permissions` (frameId,roleId) VALUES ".$insertValue;
			SystemDatabaseManager::getInstance()->executeUpdate($query);
			return true;
		}
		else {
			return false;
		}
	}

	public function getFrameId($frameName) {
			//to remove _ from frame name as in database there is space b/w 2 words		
			$frameName = str_replace("_", " ", $frameName);
				
				$query = "SELECT
     						frameId, REPLACE(frameName,' ','_') AS frameName 
					 FROM
							dashboard_frame 
					WHERE 
							frameName IN ($frameName) ";
			
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

	}
	
	
	
	public function insertNewRolePermissionInTransaction($str) {
			
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		/*$query = "DELETE FROM `dashboard_permissions` WHERE roleId = $roleId and instituteId = $instituteId";
		$return = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
		if ($return == false) {
			return false;
		}*/
		$query = "INSERT INTO `dashboard_permissions` (frameId,roleId,instituteId) VALUES $str ";
	
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
	//this is new function for role permission which add edit,view,delete permission in role permission 
	public function insertNewRolePermissionInTransactionNew($str) {
			
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		/*$query = "DELETE FROM `dashboard_permissions` WHERE roleId = $roleId and instituteId = $instituteId";
		$return = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
		if ($return == false) {
			return false;
		}*/
		$query = "INSERT INTO `dashboard_permissions` (frameId,roleId,instituteId,dashboardPermission) VALUES $str ";
	
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
	
	
	public function deleteNewRolePermissionInTransaction($roleId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "DELETE FROM `dashboard_permissions` WHERE roleId = $roleId and instituteId = $instituteId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO INSERT Permissions to
	//
	// Author :Rajeev Aggarwal
	// Created on : (29.12.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------
	public function insertRolePermissionInTransaction($roleId) {

		global $REQUEST_DATA;
		$cnt  = $REQUEST_DATA['recordCount'];

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		//$cnt = count($chb);
		if($roleId)
		{
			$query = "DELETE FROM `dashboard_permissions` WHERE roleId = $roleId and instituteId = $instituteId";
			$return = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
			if ($return == false) {
				return false;
			}
			$insertValue = "";

			for($i=1;$i<=$cnt; $i++){

				$querySeprator = '';
				if($insertValue!=''){

					$querySeprator = ",";
				}
				$valName = "chb".$i;
				$chb1 = $REQUEST_DATA[$valName];
				if($chb1)
					$insertValue .= "$querySeprator ($chb1,$roleId, $instituteId)";

				//echo $valName;
			}

			if($insertValue)
				$query = "INSERT INTO `dashboard_permissions` (frameId,roleId, instituteId) VALUES ".$insertValue;
				
				$return = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
				if ($return == false) {
					return false;
				}
			return true;
		}
		else {
			return false;
		}
	}



	//--------------------------------------------------------
	// Purpose: to fetch values from dashboard role permission
	// Author:Rajeev Aggarwal
	// Created on : (29.12.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	public function getDashboardAccessArray($roleId = 0) {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		if ($sessionHandler->getSessionVariable('RoleId') == 1) {

			$query = "SELECT
				 df.frameId,df.frameName

				 FROM
				 dashboard_frame df";

			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		if ($roleId == 0) {
			$roleId = $sessionHandler->getSessionVariable('RoleId');
		}
		$query = "SELECT
				 df.frameId,df.frameName

				 FROM
				 dashboard_frame df,dashboard_permissions dp

				 WHERE df.frameId = dp.frameId and dp.instituteId = $instituteId AND dp.dashboardPermission='view' AND dp.roleId = ".$roleId;

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//--------------------------------------------------------
	// Purpose: to fetch values from dashboard frame
	// Author:Rajeev Aggarwal
	// Created on : (29.12.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	public function getDashboardFrame() {


		$query = "SELECT
				 df.frameId,df.frameName

				 FROM
				 dashboard_frame df

				 WHERE isActive=1";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//--------------------------------------------------------
	// Purpose: to fetch values from dashboard role permission
	// Author:Rajeev Aggarwal
	// Created on : (29.12.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------
	public function getDashboardAccessArray1($roleId = 0,$condition='') {
	
		global $sessionHandler;
		 $instituteId = $sessionHandler->getSessionVariable('InstituteId');
		if ($roleId == 0) {
			$roleId = $sessionHandler->getSessionVariable('RoleId');
		}
		$query = "SELECT 
				 df.frameId,df.frameName, df.titleName ,dp.dashboardPermission,REPLACE(df.frameName,' ','_') AS frameName1
			 FROM
				 dashboard_frame df, dashboard_permissions dp
			 WHERE 
				 df.frameId = dp.frameId AND dp.instituteId = $instituteId AND 
				 dp.roleId = ".$roleId." 
				$condition
			 ORDER BY
				df.titleName, df.frameId";
		
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    

//viewPermission 	editPermission 	addPermission 	deletePermission

    
    	//--------------------------------------------------------  
	// Purpose: to fetch values from dashboard role permission
	// Author:Abhay Kant
	// Created on : (19.07.2011)
	// Copyright 2010-2011 - syenergy Technologies Pvt. Ltd.
	//--------------------------------------------------------  
	public function getDashboardAccessArray2($roleId = 0) {
		
		global $sessionHandler;
		 $instituteId = $sessionHandler->getSessionVariable('InstituteId');
		if ($roleId == 0) {
			$roleId = $sessionHandler->getSessionVariable('RoleId');
		}
		$query = "SELECT 
			       DISTINCT titleName 
			 FROM
			       dashboard_frame df, dashboard_permissions dp
			 WHERE 
			       df.frameId = dp.frameId AND dp.instituteId = $instituteId AND
			       dp.roleId = ".$roleId ;

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    
    //--------------------------------------------------------
    // Purpose: to fetch parent login name
    // Author:Parveen Sharma
    // Created on : (09.01.2009)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //--------------------------------------------------------
    public function getParentName($userId) {

            // fatherUserId check
            $tableName = 'student';

            $query="Select  fatherName AS name, 'Father' AS parentType FROM $tableName WHERE fatherUserId='".$userId."'";
            $resultFather=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
            if($resultFather[0]['name']){
                return $resultFather;
            }

            //motherUserId Check
            $query="Select  motherName AS name, 'Mother' AS parentType FROM $tableName WHERE motherUserId ='".$userId."'";
            $resultMother=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
            if($resultMother[0]['name']){
                return $resultMother;
            }
            // Guardian Check
            $query="Select  guardianName AS name, 'Guardian' AS parentType FROM $tableName WHERE guardianUserId='".$userId."'";
            $resultGuardian=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
            if($resultGuardian[0]['name']){
                return $resultGuardian;
            }
    }


//--------------------------------------------------------
// Purpose: to get active session's name, id, abbr
// Author:Dipanjan Bhattacharjee
// Params: optional
// Returns: array
// Created on : (12.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getActiveSessionDetail($conditions='') {
        $query = "SELECT sessionId, sessionYear FROM session WHERE active=1 $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------
// Purpose: to fetch values from user_role table
// Author:Rajeev Aggarwal
// Created on : (30.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	public function getAllUserRole() {

		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

		$query = "SELECT
                         DISTINCT usr.roleId,
                                  rol.roleName,
                                  rol.roleType
                  FROM
                         user_role usr,
                         role rol
                  WHERE
                         rol.roleId = usr.roleId
                         AND usr.userId=$userId
                         AND usr.instituteId=$instituteId ";
                         
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getAllUserRoleForAllInstitutes() {

		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
    	$query = "SELECT
                         DISTINCT usr.roleId,
                         rol.roleName,
                         rol.roleType
                  FROM
                         user_role usr,
                         role rol
                  WHERE
                         rol.roleId = usr.roleId
                         AND usr.userId=$userId
                         ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


//--------------------------------------------------------
// Purpose: to save values for usage_log
// Author:Ajinder Singh
// Created on : (12-oct-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	public function saveCurrentAccessedDetails() {
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "insert into usage_log set instituteId = $instituteId, userId = $userId, roleId = $roleId, dateTime = NOW(), moduleName = '".MODULE."',  url = '".'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']."'";
		return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	}


//-------------------------------------------------------------------
// Purpose: to get time table label type of logged in teacher
// Author:Dipanjan Bhattacharjee
// Params: requires employee id
// Returns: array
// Created on : (17.04.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------
    public function getTimeTableLabelType($employeeId='') {
        
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT 
                      DISTINCT timeTableLabelId
                  FROM
                      ".TIME_TABLE_TABLE." 
                  WHERE
                      sessionId='$sessionId'
                      AND instituteId='$instituteId'
                      AND toDate IS NULL
                      AND employeeId='$employeeId' ";
        $ret = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
        
        $timeTableLabelId='0';
        for($i=0;$i<count($ret);$i++) {
          $timeTableLabelId .= ",".$ret[$i]['timeTableLabelId'];  
        }
        $query = "SELECT
                        timeTableType
                  FROM
                        time_table_labels
                  WHERE
                        timeTableLabelId IN ($timeTableLabelId) AND isActive=1";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//--------------------------------------------------------
// Purpose: get user credentials from user table
// Author:Dipanjan Bhattacharjee
// Params: requires userId
// Returns: array
// Created on : (07.04.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
// --------------------------------------------------------

    public function getUserCredentials($userId) {

        $query = "SELECT
                        u.userId, u.userName, u.roleId,u.userStatus, r.roleName, u.instituteId,
                        if(ufs.themeId is null, 3, ufs.themeId) as themeId,
                        if(ufs.grouping is null, 1, ufs.grouping) as grouping
                  FROM
                        role r,user u left join user_prefs ufs on (ufs.userId=u.userId)
                  WHERE
                        u.userId=$userId
                        AND u.roleId=r.roleId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getAllInstitue($condition='') {

        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                       instituteId, instituteName, instituteAbbr, instituteCode
                  FROM
                       institute
                   WHERE
                       instituteId <> '$instituteId'
                       $condition  
                   ORDER BY 
                       instituteId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function checkInstituteModuleAccess($roleId='', $moduleId='', $instituteId='') {
       
        global $sessionHandler;
       
        $query = "SELECT
                       permissionId
                  FROM 
                        role_permission
                  WHERE 
                        moduleId = '$moduleId'
                        AND  roleId = '$roleId'
                        AND  instituteId = '$instituteId' ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function addRoleInstituePermissionInTransaction($roleId,$moduleId,$instituteId) {
        global $sessionHandler;
       
        $query = "
                    INSERT INTO
                                    role_permission (moduleId, roleId, viewPermission, editPermission, addPermission, deletePermission, instituteId)
                    VALUES
                                    ($moduleId, $roleId,0,0,0,0, $instituteId) ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

    public function updateInstitutePermissionsInTransaction($roleId='',$moduleId='', $modulePermission='',$instituteId='' ) {
    
        global $sessionHandler;
       
        $query = "UPDATE 
                        role_permission SET $modulePermission = 1 
                  WHERE 
                        roleId = $roleId AND moduleId = $moduleId AND instituteId = '$instituteId' ";
    
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }
    
    public function checkInstituteDashboard($frameId='', $roleId='', $instituteId='',$dashboardPermission='') {
       
        global $sessionHandler;
       
        $query = "SELECT
                       permissionId, frameId, roleId, instituteId, dashboardPermission
                  FROM 
                       dashboard_permissions
                  WHERE 
                       frameId ='$frameId' AND 
                       roleId = '$roleId' AND  
                       instituteId ='$instituteId' AND  
                       dashboardPermission ='$dashboardPermission' ";
                       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
   public function deleteAllNewRolePermissionInTransaction($roleId='',$allValue='', $instituteIds='') {
        
        global $sessionHandler;
        
        if($allValue=='') {
          $allValue=0;  
        }
        if($instituteIds=='') {
          $instituteIds=0;  
        }
        
        $query = "DELETE FROM `dashboard_permissions` 
                  WHERE 
                    roleId = $roleId AND 
                    CONCAT_WS('!~~!',frameId,dashboardPermission) IN ($allValue) AND
                    instituteId IN ($instituteIds) ";
                    
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }
 
}


?>
