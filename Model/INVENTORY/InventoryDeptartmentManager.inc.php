<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "item_category" table
// Author :Jaineesh 
// Created on : (08.05.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class InventoryDeptartmentManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "InventoryDeptartmentManager" CLASS
//
// Author :Jaineesh 
// Created on : (23.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "InventoryDeptartmentManager" CLASS
//
// Author :Jaineesh 
// Created on : (08.05.2009)
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
// THIS FUNCTION IS USED FOR ADDING A Inventory Department
//
// Author :Jaineesh
// Created on : (23.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addInventoryDepartment() {
        global $REQUEST_DATA;
        
     $query="INSERT INTO inv_dept (invDepttName,invDepttAbbr,depttType,description) 
      VALUES('".addslashes($REQUEST_DATA['inventoryDeptName'])."','".addslashes($REQUEST_DATA['abbr'])."',".$REQUEST_DATA['departmentType'].",'".addslashes($REQUEST_DATA['description'])."')"; 
      
      return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);     
        
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Inventory Department
//
// Author :Jaineesh
// Created on : (23.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addInventoryDepartmentIncharge($lastInvDepttId,$toDate) {
        global $REQUEST_DATA;
        
     $query="INSERT INTO inv_dept_incharge (inchargeId,fromDate,toDate,invDepttId) 
      VALUES(".$REQUEST_DATA['employee'].",'".$REQUEST_DATA['fromDate']."','".$toDate."',".$lastInvDepttId.")"; 
      
      return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);     
        
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING ITEM CATEGORY
//
//$id:itemCategoryId
// Author :Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editInventoryDepartment($id) {
        global $REQUEST_DATA;
        
      $query="	UPDATE inv_dept 
				SET invDepttName ='".addslashes($REQUEST_DATA['inventoryDeptName'])."',
				invDepttAbbr ='".addslashes($REQUEST_DATA['abbr'])."',
				depttType ='".addslashes($REQUEST_DATA['departmentType'])."',
				description ='".addslashes($REQUEST_DATA['description'])."'
        WHERE   invDepttId=".$id;
       
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
    } 

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING ITEM CATEGORY LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getInventoryDepartment($conditions='') {
         $query = "	SELECT	invd.*,
							idi.fromDate,
							idi.toDate,
							idi.inchargeId
					FROM	inv_dept invd,
							inv_dept_incharge idi
					WHERE	invd.invDepttId = idi.invDepttId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING ITEM CATEGORY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getInventoryDepartmentList($conditions='',  $orderBy=' invDepttName', $limit = '') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        
       $query = "	SELECT	invd.invDepttId,
							invd.invDepttName, 
							invd.invDepttAbbr,
							IF(invd.depttType=1,'".$inventoryDepartmentArr[1]."',IF(invd.depttType=2,'".$inventoryDepartmentArr[2]."','".$inventoryDepartmentArr[3]."')) AS departmentType,
							emp.employeeName
					FROM	inv_dept invd,
							inv_dept_incharge idi,
							employee emp
					WHERE	invd.invDepttId = idi.invDepttId
					AND		idi.inchargeId = emp.employeeId
						$conditions
			        ORDER BY $orderBy 
					$limit";
        
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Inventory Department
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (23.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalInventoryDepartment($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
       $query = "	SELECT COUNT(*) AS totalRecords 
					FROM	inv_dept invd,
							inv_dept_incharge idi,
							employee emp
					WHERE	invd.invDepttId = idi.invDepttId
					AND		idi.inchargeId = emp.employeeId
							$conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS itemCategoryId EXISTS IN "items_master" TABLE OR NOT(DELETE CHECK)
//
//$itemCategoryId :itemCategoryId of the item_category
// Author :Gurkeerat Sidhu 
// Created on : (18.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInItemsMaster($invDepttId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM inv_stock 
        WHERE invDepttId=$invDepttId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS itemCategoryId EXISTS IN "items_master" TABLE OR NOT(DELETE CHECK)
//
//$itemCategoryId :itemCategoryId of the item_category
// Author :Gurkeerat Sidhu 
// Created on : (18.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInIssueItemsMaster($invDepttId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM issue_items 
        WHERE issuedTo = $invDepttId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING ITEM CATEGORY
//
//$Id :itemCategoryId
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteInventoryDepartment($id) {
     
        $query = "DELETE 
        FROM inv_dept
        WHERE invDepttId=$id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING ITEM CATEGORY
//
//$Id :itemCategoryId
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteInventoryDepartmentIncharge($id) {
     
        $query = "	DELETE 
					FROM inv_dept_incharge
					WHERE invDepttId=$id";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

}

?>
