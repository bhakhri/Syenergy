<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "role" TABLE
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (10.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class UserRoleManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "RoleManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "RoleManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A role
//
// Author :Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function addRole() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('role', array('roleName'), array(strtoupper($REQUEST_DATA['roleName'])) );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A role
//
//$id:busRouteId
// Author :Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function editRole($id) {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoUpdate('role', array('roleName'), array(strtoupper($REQUEST_DATA['roleName'])), "roleId=$id" );
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING role LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getRole($conditions='') {

        $query = "SELECT roleId,roleName
        FROM role
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    //**AS BUSROUTE TABLE IS INDEPENDENT NO NEED TO CHECK FOR INTEGRITY CONSTRAINTS**//

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Role
//
//$busRouteId :busRouteId of the BusStop
// Author :Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function deleteRole($roleId) {

        $query = "DELETE
        FROM role
        WHERE roleId=$roleId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING role LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getRoleList($conditions='', $limit = '', $orderBy=' rl.roleName') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT
                        rl.roleId,
                        rl.roleName,
                        COUNT(u.userId) AS userCount
                 FROM
                        role rl
                        LEFT JOIN `user` u ON ( u.roleId = rl.roleId AND u.instituteId=$instituteId)
                        $conditions
                        GROUP BY rl.roleId
                 ORDER BY $orderBy
                 $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF roles
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getTotalRole($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
                        role rl
                        LEFT JOIN `user` u ON ( u.roleId = rl.roleId AND u.instituteId=$instituteId)
                        $conditions
                        GROUP BY rl.roleId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //these function are used to fetch user info corresponding to different role
    public function getEmployeeRoleList($conditions='', $limit = '', $orderBy=' rl.roleName') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        u.userName,
                        u.displayName,
                        e.employeeName,
                        e.isTeaching,
                        e.dateOfBirth,
                        e.dateOfJoining,
                        d.designationName
                 FROM
                        role r
                        LEFT JOIN `user` u ON ( u.roleId = r.roleId AND u.instituteId=$instituteId)
                        LEFT JOIN  employee e ON e.userId=u.userId
                        LEFT join  designation d ON d.designationId=e.designationId
                 WHERE
                        r.roleId NOT IN (3,4)
                        $conditions
                 GROUP BY u.userId
                 ORDER BY $orderBy
                 $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getTotalEmployeeRole($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        COUNT(*) AS totalRecords
                 FROM
                        role r
                        LEFT JOIN `user` u ON ( u.roleId = r.roleId AND u.instituteId=$instituteId)
                        LEFT JOIN  employee e ON e.userId=u.userId
                        LEFT join  designation d ON d.designationId=e.designationId
                 WHERE
                        r.roleId NOT IN (3,4)
                        $conditions
                 GROUP BY u.userId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getParentRoleList($conditions='', $limit = '', $orderBy=' rl.roleName') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        u.userName,
                        u.displayName,
                        IF(s1.fatherUserId IS NULL,IF(s2.motherUserId IS NULL,'Guardian','Mother'),'Father') AS parent,
                        IF(s1.fatherUserId IS NULL,IF(s2.motherUserId IS NULL,s3.guardianName,s2.motherName),s1.fatherName) AS parentName
                 FROM
                        role r
                        LEFT JOIN `user` u ON ( u.roleId = r.roleId AND u.instituteId=$instituteId)
                        LEFT JOIN  student s1 ON (s1.fatherUserId=u.userId)
                        LEFT JOIN  student s2 ON (s2.motherUserId=u.userId)
                        LEFT JOIN  student s3 ON (s3.guardianUserId=u.userId)
                 WHERE
                        r.roleId IN (3)
                        $conditions
                 GROUP BY u.userId
                 ORDER BY $orderBy
                 $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getTotalParentRole($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        COUNT(*) AS totalRecords
                 FROM
                        role r
                        LEFT JOIN `user` u ON ( u.roleId = r.roleId AND u.instituteId=$instituteId )
                        LEFT JOIN  student s1 ON (s1.fatherUserId=u.userId)
                        LEFT JOIN  student s2 ON (s2.motherUserId=u.userId)
                        LEFT JOIN  student s3 ON (s3.guardianUserId=u.userId)
                 WHERE
                        r.roleId IN (3)
                        $conditions
                 GROUP BY u.userId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function getStudentRoleList($conditions='', $limit = '', $orderBy=' rl.roleName') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        u.userName,
                        u.displayName,
                        CONCAT(IF(s.firstName IS NULL OR s.firstName='','',s.firstName),' ',IF(s.lastName IS NULL OR s.lastName='','',s.lastName)) AS studentName,
                        s.dateOfBirth,
                        s.dateOfAdmission
                 FROM
                        role r
                        LEFT JOIN `user` u ON ( u.roleId = r.roleId AND u.instituteId=$instituteId)
                        LEFT JOIN  student s ON s.userId=u.userId
                 WHERE
                        r.roleId IN (4)
                        $conditions
                 GROUP BY u.userId
                 ORDER BY $orderBy
                 $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getTotalStudentRole($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        COUNT(*) AS totalRecords
                 FROM
                        role r
                        LEFT JOIN `user` u ON ( u.roleId = r.roleId AND u.instituteId=$instituteId)
                        LEFT JOIN  student s ON s.userId=u.userId
                 WHERE
                        r.roleId IN (4)
                        $conditions
                 GROUP BY u.userId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

 public function getRolePermissionList($conditions='', $limit = '', $orderBy=' m.moduleName') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        m.moduleName,
                        rp.viewPermission,
                        rp.editPermission,
                        rp.addPermission,
                        rp.deletePermission,
                        rp.roleId
                 FROM
                        role r,module m,role_permission rp
                 WHERE
                        r.roleId=rp.roleId
                        AND rp.moduleId=m.moduleId
                        AND rp.instituteId=$instituteId
                        $conditions
                 ORDER BY $orderBy
                 $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

 public function getTotalRolePermission($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        COUNT(*) AS totalRecord
                 FROM
                        role r,module m,role_permission rp
                 WHERE
                        r.roleId=rp.roleId
                        AND rp.moduleId=m.moduleId
                        AND rp.instituteId=$instituteId
                        $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

 public function checkInRolePermission($roleId, $conditions = '') {
        $query = "SELECT
                        COUNT(*) AS cnt
                 FROM
                        role_permission
                 WHERE
                        roleId=$roleId
                        AND
                         (
                           viewPermission!=0
                           OR
                           editPermission!=0
                           OR
                           addPermission!=0
                           OR
                           deletePermission!=0
                         )
								 $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function deleteOldPermission($roleId, $newInstitute) {
		 $query = "DELETE FROM role_permission where roleId = $roleId and instituteId = $newInstitute";
		 return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function copyNewPermission($roleId, $newInstitute, $copyFrom) {
		 $query = "INSERT INTO role_permission(moduleId, roleId, viewPermission, editPermission, addPermission, deletePermission, instituteId) select moduleId, roleId, viewPermission, editPermission, addPermission, deletePermission, $newInstitute as instituteId from role_permission where roleId = $roleId and instituteId = $copyFrom";
		 return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

public function checkInDashBoardPermission($roleId) {
        $query = "SELECT
                        COUNT(*) AS cnt
                 FROM
                        dashboard_permissions
                 WHERE
                        roleId=$roleId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

}

// $History: RoleManager.inc.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 22/01/10   Time: 14:45
//Updated in $/LeapCC/Model
//Done bug fixing.
//Bug ids---
//0002683,0002682,0002268,0001960,
//0002619,0002623
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 5/01/10    Time: 12:45
//Updated in $/LeapCC/Model
//Corrected query error
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 23/12/09   Time: 11:20
//Updated in $/LeapCC/Model
//Corrected query and added institute wise check
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 15/12/09   Time: 18:46
//Updated in $/LeapCC/Model
//Made UI changes in Role Master module
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/20/09    Time: 2:00p
//Updated in $/LeapCC/Model
//added role permission module for user other than admin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:40p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/10/08    Time: 5:22p
//Updated in $/Leap/Source/Model
//Created Role(Role Master) Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 2:59p
//Created in $/Leap/Source/Model
//Initial checkin
?>
