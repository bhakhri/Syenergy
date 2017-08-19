<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "role" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (1.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ManageUserManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ManageUserManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (1.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ManageUserManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (1.07.2008)
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
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING AN USER
//
// Author :Dipanjan Bhattacharjee 
// Created on : (1.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addUser() {
		global $REQUEST_DATA; 

		$str = SystemDatabaseManager::getInstance()->runAutoInsert('user', array('userName','userPassword','roleId','employeeId','displayName','userStatus'), array($REQUEST_DATA['userName'],md5($REQUEST_DATA['userPwd']),$REQUEST_DATA['defaultValue'],$REQUEST_DATA['employeeId'],$REQUEST_DATA['displayName'],$REQUEST_DATA['isActive']));
        if($str) {
            $id=SystemDatabaseManager::getInstance()->lastInsertId();
			
			$query = "DELETE FROM `user_role` WHERE userId = $id";
			SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");

			$roleArr = explode(",", $REQUEST_DATA['selectedRoleId']);
			$cnt = COUNT($roleArr);

			$insertValue = "";
			for($i=0;$i<$cnt; $i++){
				
				$querySeprator = '';
			    if($insertValue!=''){

					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator ('".$id."','".$roleArr[$i]."')";
			}

			$query = "INSERT INTO `user_role`(userId,roleId) VALUES $insertValue";
			SystemDatabaseManager::getInstance()->executeUpdate($query);

            return SystemDatabaseManager::getInstance()->runAutoInsert('user_prefs', array('userId'), array($id));
        }
        else {
            return false;
        }
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING AN USER
//
//$id:userId
// Author :Dipanjan Bhattacharjee 
// Created on : (1.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editUser($id) {
        global $REQUEST_DATA;
        
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $defaultRoleId=trim($REQUEST_DATA['defaultValue']);
     
		if(($REQUEST_DATA['defaultRoleId']==3) || ($REQUEST_DATA['defaultRoleId']==4)){
			
			if($REQUEST_DATA['userPwd']!='1****1'){
			
				return SystemDatabaseManager::getInstance()->runAutoUpdate('user',array('userPassword','userStatus'),array(md5($REQUEST_DATA['userPwd']),$REQUEST_DATA['isActive']), "userId=$id" );
			}
			else{
				return SystemDatabaseManager::getInstance()->runAutoUpdate('user',array('userStatus'),array($REQUEST_DATA['isActive']), "userId=$id" );
				//return SUCCESS;
			}
		}
		else{

			 $selectedRoleArr = explode(",", $REQUEST_DATA['selectedRoleId']);
			 $query = "DELETE FROM user_role WHERE userId = $id AND instituteId=$instituteId";

			 SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");

			 $insertValue = "";
			 foreach($selectedRoleArr as $selectedRole)
			 {
				$querySeprator = '';
				if($insertValue!=''){

					$querySeprator = ",";
				}
				$insertValue .= "$querySeprator ('".$id."','".$selectedRole."',$instituteId,$defaultRoleId)";

			}
			$query = "INSERT INTO `user_role` (userId,roleId,instituteId,defaultRoleId) VALUES  $insertValue";
			SystemDatabaseManager::getInstance()->executeUpdate($query);

			$query ="UPDATE user  
					SET roleId =".$REQUEST_DATA['defaultValue'].",
                        userStatus =".$REQUEST_DATA['isActive']."
					,displayName ='".$REQUEST_DATA['displayName']."'";

			if($REQUEST_DATA['userPwd']!='1****1'){
				
				$query.=" ,userPassword='".md5($REQUEST_DATA['userPwd'])."'";
			}
			$query.=" WHERE userId = '$id'";
			//echo $query;
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
			 
		}
       
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING USER LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (1.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getUser($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        usr.userId,
                        usr.userName,
                        usr.userPassword,
                        usr.userStatus,
                        usr.roleId, 
                        usr.employeeId, 
                        usr.displayName,
                        IFNULL(ur.roleId,'') as otherRole,
                        IFNULL(ur.defaultRoleId,'') as dafaultRole
                  FROM 
                        user usr  
                        LEFT JOIN user_role ur ON (usr.userId= ur.userId AND ur.instituteId=$instituteId )
                  $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING AN USER
//
//$userId :userid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (1.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteUser($userId) {
     
        $query = "DELETE FROM user_prefs WHERE userId=$userId";
        $str = SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
        if($str==false){
            return false;
        }
        
        $ret=$query = "DELETE FROM user_role WHERE userId = $id";
        if($ret==false){
            return false;
        }
        
        $query = "DELETE   FROM user  WHERE userId=$userId";            
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
        
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING USER LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (1.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getUserList1($conditions='', $limit = '', $orderBy='userName') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
     
      /* OLD QUERY 
        $query = "SELECT ur.userId, ur.userName, ur.userPassword, rl.roleName
        FROM user ur,role rl
        WHERE ur.roleId=rl.roleId $conditions 
        ORDER BY $orderBy $limit";
      */
      /*$query = "SELECT ur.userId, ur.userName, ur.userPassword,ur.userStatus, rl.roleName,
                IF(isnull(displayName),'".NOT_APPLICABLE_STRING."',IF(displayName='','".NOT_APPLICABLE_STRING."', displayName)) AS 
                displayName, 
                IF(isnull(e.employeeName),'".NOT_APPLICABLE_STRING."',IF(e.employeeName='','".NOT_APPLICABLE_STRING."', e.employeeName)) AS 
                employeeName
                FROM role AS rl, user AS ur
                LEFT JOIN employee AS e ON e.employeeId = ur.employeeId
                WHERE ur.roleId = rl.roleId    AND ur.instituteId = $instituteId $conditions 
        ORDER BY $orderBy $limit"; */
         $query = "SELECT 
                  DISTINCT(usr.userId),userName,userPassword,userStatus,roleName,
                  IF(isnull(displayName),'".NOT_APPLICABLE_STRING."',
                  IF(displayName='','".NOT_APPLICABLE_STRING."', displayName)) AS displayName,
                  ifnull(IF(usr.roleId=4,(SELECT DISTINCT firstName from student where userId=usr.userId LIMIT 0,1),
                  (IF(usr.roleId=3,'Parent',(SELECT DISTINCT IF( e.employeeName IS NULL , us.userName, e.employeeName ) AS userName
                  FROM user us
                  LEFT JOIN employee e ON e.userId = us.userId where us.userId = usr.userId limit 0,1)))),'".NOT_APPLICABLE_STRING."') 
                  as roleUserName 
                  FROM `user` usr ,`role` ro,user_role ur 
                  WHERE 
                  usr.instituteId = $instituteId AND
                  usr.roleId = ro.roleId 
                  $conditions
                  group by usr.userId
                  ORDER BY  $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF USERS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (1.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getUserList($conditions='', $limit = '', $orderBy='userName') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
    
        /*
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM user ur,role rl
        WHERE ur.roleId=rl.roleId $conditions ";
        */
        /*$query = "SELECT COUNT(*) AS totalRecords
                FROM role AS rl, user AS ur
                LEFT JOIN employee AS e ON e.employeeId = ur.employeeId
                WHERE ur.roleId = rl.roleId  AND ur.instituteId = $instituteId $conditions ";  */
       $query = "SELECT 
                        DISTINCT(usr.userId),    
                        userName,
                        userPassword,
                        userStatus,
                        roleName,
                        IF(isnull(displayName),'".NOT_APPLICABLE_STRING."',
                        IF(displayName='','".NOT_APPLICABLE_STRING."', displayName)) AS displayName,
                        ifnull(IF(usr.roleId=4,(SELECT DISTINCT firstName from student where userId=usr.userId limit 0,1),
                        (
                             IF(usr.roleId=3,'Parent',(SELECT DISTINCT IF( e.employeeName IS NULL , us.userName, e.employeeName ) AS userName
                              FROM 
                                 `user` us
                                 LEFT JOIN employee e ON e.userId = us.userId where us.userId = usr.userId limit 0,1)))),'".NOT_APPLICABLE_STRING."'
                             ) as roleUserName 
                  FROM 
                        `role` ro,`user` usr 
                        INNER JOIN user_role ur ON (ur.userId=usr.userId AND ur.instituteId=$instituteId )
                  WHERE 
                         usr.roleId = ro.roleId AND 
                         ( (usr.userId IN (SELECT DISTINCT userId FROM student)) OR (usr.userId IN (SELECT DISTINCT fatherUserId FROM student)) OR 
                           (usr.userId IN (SELECT DISTINCT motherUserId FROM student)) OR (usr.userId IN (SELECT DISTINCT userId FROM employee)))
                  GROUP BY 
                        usr.userId
                        $conditions                  
                  ORDER BY 
                        $orderBy $limit ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
}
// $History: ManageUserManager.inc.php $
//
//*****************  Version 11  *****************
//User: Gurkeerat    Date: 12/14/09   Time: 6:04p
//Updated in $/LeapCC/Model
//updated code to add new field 'name' that shows name of user
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 8/14/09    Time: 1:58p
//Updated in $/LeapCC/Model
//applied instituteId check in queries
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 28/07/09   Time: 17:53
//Updated in $/LeapCC/Model
//Added "userStatus" field in manage user module and added the check in
//login page that if a user is in active then he/she can not login
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 7/01/09    Time: 11:17a
//Updated in $/LeapCC/Model
//Updated manage user module in which multiple role can be selected to
//single user
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/05/09    Time: 5:22p
//Updated in $/LeapCC/Model
//delete function update
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/05/09    Time: 5:00p
//Updated in $/LeapCC/Model
//add, delete condtion update
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/05/09    Time: 4:52p
//Updated in $/LeapCC/Model
//addUser, deleteUser condition update
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/01/09    Time: 7:07p
//Updated in $/LeapCC/Model
//addUser, deleteUser query update (user_prefs table add userId)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/28/09    Time: 4:40p
//Updated in $/LeapCC/Model
//New File Added in displayName
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/08/08   Time: 5:15p
//Updated in $/LeapCC/Model
//employee Id code set
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 10/01/08   Time: 2:38p
//Updated in $/Leap/Source/Model
//Corrected Problem of User Editing
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:35p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/01/08    Time: 7:34p
//Updated in $/Leap/Source/Model
//Created ManageUser Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/01/08    Time: 4:08p
//Created in $/Leap/Source/Model
//Initial Checkin
?>
