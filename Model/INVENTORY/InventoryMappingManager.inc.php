<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "REQUISTION MAPPING" table
// Author :Jaineesh 
// Created on : (08.05.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class InventoryMappingManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "InventoryDeptartmentManager" CLASS
//
// Author :Jaineesh 
// Created on : (27 July 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "InventoryDeptartmentManager" CLASS
//
// Author :Jaineesh 
// Created on : (27 July 2010)
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
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getEmployeeList($conditions='',  $orderBy=' employeeName', $limit = '') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        
       $query = "	SELECT	emp.employeeId,
							emp.employeeName,
							emp.userId
					FROM	employee emp,
							user u,
							role r
					WHERE	emp.userId = u.userId
					AND		u.roleId = r.roleId
							$conditions
							";
        
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING ROLE MAPPING LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getRoleMappingList($roleId) {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        
       $query = "	SELECT	*
					FROM	role_incharge_mapping
					WHERE	roleId = $roleId";
        
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET USER DATA AGAINST ROLE
//
// Author :Jaineesh 
// Created on : (27 July 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function getUserData($conditions) {
     
        $query = "	SELECT  u.userId,
							u.userName
					FROM	user u
							$conditions
					ORDER BY
							u.userName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR INSERTION
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function insertRoleMappingValues($roleId,$userId,$mappingRoleId,$mappingUserId) {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        
       $query = "	INSERT INTO role_incharge_mapping (roleId, userId, mappingRoleId, mappingUserId) 
					VALUES ($roleId,$userId,$mappingRoleId,$mappingUserId)";
        
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED DELETE VALUES FROM ROLE INCHARGE MAPPING
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (28 July 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function deleteRoleMappingValues($roleId,$userId) {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        
       $query = "	DELETE 
					FROM	role_incharge_mapping 
					WHERE	roleId = $roleId
					AND		userId = $userId";
        
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

}

?>
