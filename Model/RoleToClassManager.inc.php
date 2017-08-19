<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "classes_visible_to_role" TABLE
//
//
// Author :Jaineesh
// Created on : (14.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class RoleToClassManager {
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
	 
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH SUBJECT TO CLASS DATA
//
// Author :Jaineesh
// Created on : (23.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getClassData($conditions='') {
     global $sessionHandler;
       /* $query = "	SELECT 
									DISTINCT(ttc.classId),
									className 
					FROM			time_table_classes ttc,
									class c,
									time_table_labels ttl
					WHERE			ttc.classId = c.classId 
					AND				ttl.timeTableLabelId = ttc.timeTableLabelId
					AND				ttl.isActive = 1";*/

	  $query = "SELECT 
						DISTINCT ttc.classId,
						c.className,
						GROUP_CONCAT(DISTINCT gt.groupTypeName) AS groupTypeName,
						GROUP_CONCAT(DISTINCT gr.groupName) AS groupName,
						GROUP_CONCAT(DISTINCT gr.groupId) AS groupId
				FROM	
						time_table_classes ttc,
						time_table_labels ttl, 
						class c LEFT JOIN classes_visible_to_role cr ON c.classId = cr.classId
						LEFT JOIN `group` gr ON gr.groupId = cr.groupId
						LEFT JOIN group_type gt ON gt.groupTypeId = gr.groupTypeId 
				WHERE	
						ttc.classId = c.classId AND
						ttl.timeTableLabelId = ttc.timeTableLabelId AND
						ttl.isActive = 1 AND
						c.isActive = 1 AND
						c.instituteId = '".$sessionHandler->getSessionVariable('InstituteId')."' AND
						c.sessionId = '".$sessionHandler->getSessionVariable('SessionId')."'
						
				GROUP BY
						ttc.classId ";

       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH SUBJECT TO CLASS DATA
//
// Author :Jaineesh
// Created on : (23.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getVisibleClassRole($conditions='') {
     
           $query = "	SELECT 
									DISTINCT(cvtr.classId),
									gt.groupTypeId,
									gr.groupId,
									gr.groupName,
									cl.className,
									gt.groupTypeName
						FROM		classes_visible_to_role cvtr,
									class cl,
									`group` gr,
									group_type gt
						WHERE		cl.classId = cvtr.classId
						AND			cvtr.groupId = gr.groupId
						AND			gr.groupTypeId = gt.groupTypeId
									$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH SUBJECT TO CLASS DATA
//
// Author :Jaineesh
// Created on : (23.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getPrintVisibleClassRole($conditions='') {
     
           $query = "	SELECT 
									DISTINCT(cvtr.classId),
									gt.groupTypeName,
									GROUP_CONCAT(DISTINCT gr.groupName) AS groupName,
									cl.className
					FROM			classes_visible_to_role cvtr,
									class cl,
									`group` gr,
									group_type gt
					WHERE			cl.classId = cvtr.classId
					AND				cvtr.groupId = gr.groupId
					AND				gr.groupTypeId = gt.groupTypeId
									$conditions
									GROUP BY cl.classId, gt.groupTypeId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH SUBJECT TO CLASS DATA
//
// Author :Jaineesh
// Created on : (23.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getVisibleGroup($userId,$roleId,$conditions='') {
     
			$query = "	SELECT 
							   gr.groupId,
							   gr.groupName,
							   ifNULL(cvtr.groupId,'') as egroupId,
							   cvtr.userId
						FROM  `group` gr
						LEFT JOIN classes_visible_to_role cvtr ON (gr.groupId = cvtr.groupId AND cvtr.userId = $userId AND cvtr.roleId = $roleId)
						WHERE  $conditions GROUP BY gr.groupId";

			/*$query = "	SELECT 
							   gr.groupId,
							   gr.groupName,
							   ifNULL(cvtr.groupId,'') as egroupId,
							   cvtr.userId
						FROM  `group` gr, classes_visible_to_role cvtr
						WHERE  $conditions ";*/
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH GROUP TYPE
//
// Author :Jaineesh
// Created on : (23.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getGroupType($conditions='') {
     
		global $sessionHandler;

        $query = "	SELECT 
							groupTypeId,
							groupTypeName
					FROM	`group_type` ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH GROUP TYPE
//
// Author :Jaineesh
// Created on : (23.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getGroupTypeRole($conditions='') {
     
		global $sessionHandler;

        $query = "	SELECT 
							DISTINCT gt.groupTypeId, 
							gt.groupTypeName, 
							IFNULL(cvtr.userId,'') AS userId
					FROM 
							group_type gt 
					LEFT JOIN `group` gr ON gt.groupTypeId = gr.groupTypeId
					LEFT JOIN classes_visible_to_role cvtr ON cvtr.groupId = gr.groupId 
					WHERE
							$conditions
					GROUP BY 
								gt.groupTypeId, cvtr.userId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH EMPLOYEE USERID
//
// Author :Jaineesh
// Created on : (23.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getEmployeeUserId($conditions) {
     
		global $sessionHandler;

        $query = "	SELECT 
							userId,
							employeeName
					FROM	employee
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT EMPLOYEE USERID & ROLEID
//
// Author :Jaineesh
// Created on : (23.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function insertUserRole($conditions,$userId,$roleId) {
     
		global $sessionHandler;

        $query = "	DELETE  
					FROM	user_role
							$conditions";
        $return = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
		/*if ($return == false) {
			return false;
		}*/
		$query = "INSERT INTO user_role (userId,roleId) VALUES (".$userId.",".$roleId.")";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");

    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO DELETE ROLE TO CLASS
//
// Author :Jaineesh
// Created on : (24.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function deleteRoleToClass($userId,$roleId) {
		
        global $REQUEST_DATA;
        global $sessionHandler;
        
		$chb  = $REQUEST_DATA['chb'];
		$group = $REQUEST_DATA['group'];
		//echo(count($group));
		//print_r($group);
		$cnt = count($chb);
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId'); 				
        $sessionId = $sessionHandler->getSessionVariable('SessionId');                         
		
        if($userId) {
			$query = "DELETE FROM 
                            classes_visible_to_role 
                      WHERE 
                            userId = $userId AND roleId = $roleId AND
                            classId IN (SELECT 
                                              DISTINCT classId 
                                        FROM 
                                              class 
                                        WHERE 
                                              instituteId = '$instituteId' AND sessionId ='$sessionId')";
                                              
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
		}
		else {
			return false;
		}
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT SUBJECT TO CLASS DATA
//
// Author :Jaineesh
// Created on : (24.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function insertRoleToClass($userId,$classId,$classGroup,$roleId) {
		global $REQUEST_DATA;
		$chb  = $REQUEST_DATA['chb'];
		$group = $REQUEST_DATA['group'];
		//echo(count($group));
		//print_r($group);
		$cnt = count($chb);
				
		if($userId) {
			/*
			$insertValue = "";
			for($i=0;$i<$cnt; $i++) {
				$querySeprator = '';
			    if($insertValue!='') {
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator ('".$userId."','".$chb[$i]."','".$REQUEST_DATA['group'.$chb[$i]]."')";
			}
			echo($insertValue);*/
			$query = "INSERT INTO classes_visible_to_role
					  (userId,roleId,classId,groupId)
					  VALUES 
					  ($userId,$roleId,$classId,$classGroup)";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
		}
		else {
			return false;
		}
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT SUBJECT TO CLASS DATA
//
// Author :Jaineesh
// Created on : (24.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	

public function getTeacherData($conditions='', $orderBy='emp.employeeName') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
        $query = "	SELECT	distinct(emp.employeeId),
							emp.employeeName
					FROM	employee emp,
							user_role ur
					WHERE	emp.isActive=1 
					AND		(emp.userId in (select userId from user $conditions AND instituteId = $instituteId )
					OR		emp.userId in (select userId from user_role  $conditions AND instituteId = $instituteId))
					ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
}

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH EMPLOYEE USERID
//
// Author :Jaineesh
// Created on : (23.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getUserRole($conditions) {
     
		global $sessionHandler;

       $query = "	SELECT 
							r.roleId,
							r.roleName
					FROM	user_role ur,
							role r
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}
// $History: RoleToClassManager.inc.php $
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 11/02/09   Time: 1:33p
//Updated in $/LeapCC/Model
//show teacher current institute
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 10/20/09   Time: 6:18p
//Updated in $/LeapCC/Model
//fixed bug if employee  has not managed the user
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/15/09   Time: 2:35p
//Updated in $/LeapCC/Model
//fixed bug nos. 0001790, 0001789, 0001768, 0001767, 0001769, 0001761,
//0001758, 0001759, 0001757, 0001791
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/07/09   Time: 4:58p
//Updated in $/LeapCC/Model
//fixed bug nos.0001727, 0001725, 0001724, 0001723, 0001721, 0001720,
//0001719, 0001718, 0001729
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 10/05/09   Time: 6:31p
//Updated in $/LeapCC/Model
//fixed bug nos.0001684, 0001689, 0001688, 0001687, 0001685, 0001686,
//0001683, 0001629 and report for academic head privileges
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/01/09   Time: 6:51p
//Updated in $/LeapCC/Model
//changed queries and flow in send message to student, student report
//list according to HOD role and make new role advisory, modified in
//queries according to this role
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:04p
//Created in $/LeapCC/Model
//new file to operate database
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/10/09    Time: 11:13a
//Updated in $/LeapCC/Model
//Fixed bug no 984,982
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 7/20/09    Time: 12:56p
//Updated in $/LeapCC/Model
//Added "hasParentCategory" in subject to class module
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 5/07/09    Time: 2:11p
//Updated in $/LeapCC/Model
//Updated subject list function to show subjectype also
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 4/06/09    Time: 12:14p
//Updated in $/LeapCC/Model
//Updated with subject type
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/05/09    Time: 6:38p
//Updated in $/LeapCC/Model
//added internaltotalmarks and externaltotalmarks field
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/11/08   Time: 3:00p
//Updated in $/LeapCC/Model
//Updated module as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 9/11/08    Time: 5:38p
//Updated in $/Leap/Source/Model
//updated formatting and added comments
?>