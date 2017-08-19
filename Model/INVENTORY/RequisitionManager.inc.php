<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "REQUISTION MAPPING" table
// Author :Jaineesh
// Created on : (08.05.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class RequisitionManager {
    private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "InventoryDeptartmentManager" CLASS
//
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "InventoryDeptartmentManager" CLASS
//
// Author :Jaineesh
// Created on : (27 July 2010)
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
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
							$conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getItemCategory($conditions='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

       $query = "	SELECT	itemId,itemCode,itemName
					FROM	items_master
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getRequisitionList($conditions='',$limit='',$orderBy='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
		global $sessionHandler;

		$userId = $sessionHandler->getSessionVariable('UserId');

      $query = "	SELECT	irm.*,
							COUNT(irt.requisitionId) AS totalCount
					FROM	inv_requisition_master irm,
							inv_requisition_trans irt
					WHERE	irm.requisitionId = irt.requisitionId
					AND		irm.userId = $userId
							GROUP BY irt.requisitionId
							$conditions
							ORDER BY $orderBy
							$limit";

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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getRequisitionDetailList($conditions) {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

       $query = "	SELECT	irt.itemCategoryId,
							irm.requisitionNo,
							irt.itemId,
							ic.categoryName,
							im.itemName,
							irt.quantityRequired
					FROM	inv_requisition_trans irt,
							item_category ic,
							items_master im,
							inv_requisition_master irm
					WHERE	irt.itemId = im.itemId
					AND		irt.itemCategoryId = ic.itemCategoryId
					AND		irt.requisitionId = irm.requisitionId
							$conditions";

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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getTotalRequisitionList($conditions='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

      $query = "	SELECT	COUNT(*) AS totalRecords
					FROM	inv_requisition_master irm,
							inv_requisition_trans irt
					WHERE	irm.requisitionId = irt.requisitionId
							GROUP BY irt.requisitionId
							$conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET USER DATA AGAINST ROLE
//
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function getUserData($conditions) {

        $query = "	SELECT  u.userId,
							u.userName
					FROM	user u
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET USER DATA AGAINST ROLE
//
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function getRequisition($conditions) {

       $query = "	SELECT	irt.requisitionId,
							irm.requisitionNo,
							irt.*,
							im.itemName,
							im.itemCode,
							ic.categoryName,
							ic.categoryCode,
							IFNULL((SELECT 
											balance 
									FROM 
											inv_item_balance 
									WHERE 
											itemCategoryId = irt.itemCategoryId AND
											itemId  = irt.itemId
											),'".NOT_APPLICABLE_STRING."') AS balance
					FROM	inv_requisition_master irm,
							inv_requisition_trans irt,
							items_master im,
							item_category ic
					WHERE	irm.requisitionId = irt.requisitionId
					AND		irt.itemCategoryId = ic.itemCategoryId
					AND		irt.itemId = im.itemId
					AND		irm.requisitionStatus = 1
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET USER DATA AGAINST ROLE
//
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function getRequisitionData($conditions) {

        $query = "	SELECT	*
					FROM	inv_requisition_master
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET USER DATA AGAINST ROLE
//
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function getRequisitionEditData($conditions) {

        $query = "	SELECT	requisitionId
					FROM	inv_requisition_master
							$conditions";
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function addRequisition() {
		global $REQUEST_DATA;
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
		$requisitionNo = $REQUEST_DATA['requisitionNo'];
		$requisitionDate = date('Y-m-d');

       $query = "	INSERT INTO inv_requisition_master (requisitionDate,userId,requisitionNo,requisitionStatus)
					VALUES ('$requisitionDate',$userId,'$requisitionNo',1)";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }



	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR INSERTION REQUISITION TRANS
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (02 August 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function addRequisitionTrans($str) {
		global $REQUEST_DATA;
		global $sessionHandler;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
		$itemCategoryId = $REQUEST_DATA['itemCategory'];
		$itemId = $REQUEST_DATA['itemCode'];
		$quantityRequired = $REQUEST_DATA['quantityRequired'];

		$query = "	INSERT INTO inv_requisition_trans (requisitionId,itemCategoryId,itemId,quantityRequired)
					VALUES $str";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR INSERTION
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function updateRequisitionTrans() {
		global $REQUEST_DATA;
		global $sessionHandler;
		$itemCategoryId = $REQUEST_DATA['itemCategory'];
		$itemId = $REQUEST_DATA['itemCode'];
		$quantityRequired = $REQUEST_DATA['quantityRequired'];
		$requisitionTransId = $REQUEST_DATA['requisitionTransId'];

       $query = "	UPDATE	inv_requisition_trans
					SET		itemCategoryId = $itemCategoryId,
							itemId = $itemId,
							quantityRequired = $quantityRequired
					WHERE	requisitionTransId = $requisitionTransId";

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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function cancelRequisitionTrans($requisitionId) {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

       $query = "	Update	inv_requisition_master
					SET		requisitionStatus = 3
					WHERE	requisitionId = $requisitionId";

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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	public function deleteRequisitionTrans($requisitionId) {
		global $inventoryDepartmentArr;
		//no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

		$query = "	DELETE
					FROM
							inv_requisition_trans
					WHERE
							requisitionId = $requisitionId
				";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}


//this function is used to generate new ItemCodes
	public function generateRequisitionCode() {
		global $sessionHandler;
		$year = date('Y');

		$userId = $sessionHandler->getSessionVariable('UserId');

		$makeUserId = "U".$userId."_".$year."_";

		$str="SELECT IFNULL(MAX(ABS(SUBSTRING(requisitionNo,length('".$makeUserId."' ) +1, LENGTH(requisitionNo) ) ) ),0)+1 AS requisitionNo FROM inv_requisition_master WHERE userId = $userId";
		$uCode=SystemDatabaseManager::getInstance()->executeQuery($str,"Query: $str");
		//generate new itemCode code
		//$gCode=$makeUserId.str_pad($uCode[0]['requisitionNo'],abs(ITEM_CODE_LENGTH-strlen($makeUserId)-strlen($uCode[0]['requisitionNo']))+1,'0',STR_PAD_LEFT);
		$gCode=$makeUserId.str_pad($uCode[0]['requisitionNo'],abs(ITEM_CODE_LENGTH),'0',STR_PAD_LEFT);
		return $gCode;
	}



//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET USER DATA AGAINST ROLE
//
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------
    public function getInventoryRequisition($conditions) {

        $query = "	SELECT	*
					FROM	inv_requisition_master
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING REQUISITION Count
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (04 Aug 10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getApprovedRequisitionCount($conditions='',$orderBy='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
		global $sessionHandler;

		$userId = $sessionHandler->getSessionVariable('UserId');

      $query = "	SELECT	irm.requisitionNo,
							u.userName,irm.requisitionDate
					FROM	inv_requisition_master irm,
							role_incharge_mapping rim,
							user u
					WHERE	irm.userId = rim.userId
					AND		u.userId = rim.userId
					AND		rim.mappingUserId = $userId
					AND		irm.requisitionStatus = 1
							$conditions
							ORDER BY $orderBy
				";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING REQUISITION LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (04 Aug 10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getApprovedRequisitionList($conditions='',$limit='',$orderBy='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
		global $sessionHandler;

		$userId = $sessionHandler->getSessionVariable('UserId');

      $query = "	SELECT	irm.*,
							u.userName
					FROM	inv_requisition_master irm,
							role_incharge_mapping rim,
							user u
					WHERE	irm.userId = rim.userId
					AND		u.userId = rim.userId
					AND		rim.mappingUserId = $userId
					AND		irm.requisitionStatus = 1
							$conditions
							ORDER BY $orderBy
							$limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING REQUISITION LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (04 Aug 10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

     public function getApprovedRequisitionDetail($conditions='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
		global $sessionHandler;

		$userId = $sessionHandler->getSessionVariable('UserId');

      $query = "	SELECT	irm.*,
							u.userName
					FROM	inv_requisition_master irm,
							user u
					WHERE	irm.userId = u.userId
					AND		irm.requisitionStatus = 1
							$conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING REQUISITION LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (04 Aug 10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

     public function getRequisitionDetail($conditions='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
		global $sessionHandler;

		$userId = $sessionHandler->getSessionVariable('UserId');

      $query = "	SELECT	irt.*
					FROM	inv_requisition_trans irt,
							inv_requisition_master irm
					WHERE	irt.requisitionId = irm.requisitionId
							$conditions";

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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function updateApprovedRequisitionTrans($requisitionTransId,$itemQuantityRequired) {
		global $REQUEST_DATA;
		global $sessionHandler;

       $query = "	UPDATE	inv_requisition_trans
					SET		quantityRequired = $itemQuantityRequired
					WHERE	requisitionTransId = $requisitionTransId";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR INSERTION
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function updateApprovedRequisitionMaster($requisitionId) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$currentDate = date('Y-m-d');

       $query = "	UPDATE	inv_requisition_master
					SET		requisitionStatus = 2,
							approvedBy = $userId,
							approvedOn = '$currentDate'
					WHERE	requisitionId = $requisitionId";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR INSERTION
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (5 Aug 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function updateRejectedRequisition($requisitionId) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$currentDate = date('Y-m-d');

       $query = "	UPDATE	inv_requisition_master
					SET		requisitionStatus = 5,
							approvedBy = $userId,
							approvedOn = '$currentDate'
					WHERE	requisitionId = $requisitionId";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


}

// $History: $
//
?>
