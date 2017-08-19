<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "REQUISTION MAPPING" table
// Author :Jaineesh
// Created on : (08.05.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class GRNManager {
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

       public function getPOItems($partyId) {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

	$query = "	SELECT	poNo
					FROM	inv_po_master
					WHERE	partyId = $partyId
							$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF PARTY
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (03 Sep 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function getPO($orderBy='poId') {
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = "	SELECT
								ipm. *
					FROM
								inv_po_master ipm,
								inv_po_trans ipt
					WHERE
								ipt.poId = ipm.poId
					AND			ipt.grnId = 0
					AND			ipt.status=1
					ORDER BY $orderBy
				";
		return $systemDatabaseManager->executeQuery($query,"Query: $query");
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

       public function getItemCategory($conditions='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

       $query = "	SELECT	distinct ic.itemCategoryId,
							ic.categoryCode,ic.categoryName
					FROM	inv_po_trans ipt,
							item_category ic
					WHERE	ipt.itemCategoryId = ic.itemCategoryId
					AND		ipt.grnId = 0
							$conditions
							";


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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getEditItemCategory($conditions='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

       $query = "	SELECT	distinct ic.itemCategoryId,
							ic.categoryCode
					FROM	inv_grn_trans igt,
							item_category ic
					WHERE	igt.itemCategoryId = ic.itemCategoryId
							$conditions
							";


        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (07 Sep 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getItem($conditions='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

       $query = "	SELECT	distinct im.itemId,
							im.itemCode,im.itemName
					FROM	inv_po_trans ipt,
							items_master im
					WHERE	ipt.itemId = im.itemId
					AND		ipt.grnId = 0
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
// Created on : (07 Sep 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getEditItem($conditions='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

       $query = "	SELECT	distinct im.itemId,
							im.itemCode
					FROM	inv_grn_trans igt,
							items_master im
					WHERE	igt.itemId = im.itemId
							$conditions";


        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL GRN COUNT
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getGRNCount($conditions='',$orderBy) {
		global $inventoryDepartmentArr;
		global $sessionHandler;

		$userId = $sessionHandler->getSessionVariable('UserId');

       $query = "	SELECT	igm.grnId
					FROM	inv_grn_master igm,
							inv_party ip
					WHERE	igm.partyId = ip.partyId
							$conditions
							ORDER BY $orderBy
				";


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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getGRNList($conditions='',$limit='',$orderBy) {
		global $inventoryDepartmentArr;
		global $sessionHandler;

		$userId = $sessionHandler->getSessionVariable('UserId');

       $query = "	SELECT	igm.*,
							ip.partyCode
					FROM	inv_grn_master igm,
							inv_party ip
					WHERE	igm.partyId = ip.partyId
							$conditions
							ORDER BY $orderBy
							$limit";



        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (07 Sep 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getQtyRate($conditions='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

       $query = "	SELECT	ipt.quantityRequired,
							ipt.rate
					FROM	inv_po_trans ipt
					WHERE	ipt.grnId = 0
							$conditions";


        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET USER DATA AGAINST ROLE
//
// Author :Jaineesh
// Created on : (02 Sep 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function getGRNData($conditions) {

        $query = "	SELECT	*
					FROM	inv_grn_master
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GENERATE PO
//
// Author :Jaineesh
// Created on : (06 Sep 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------

public function addGRN(){
	global $REQUEST_DATA;
	global $sessionHandler;
	$partyId = $REQUEST_DATA['partyCode'];
	$billNo = $REQUEST_DATA['billNo'];
	$billDate = $REQUEST_DATA['billDate'];
	$totalAmount = $REQUEST_DATA['totalAmount'];

    $query = "	INSERT INTO inv_grn_master(billNo,billDate,partyId,totalAmount)
				VALUES('$billNo','$billDate',$partyId,$totalAmount)
            ";

    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD PO TRANS
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (06 Sept 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function updatePOTrans($poId,$poItemCategory,$poItem,$grnId) {
		global $REQUEST_DATA;
		global $sessionHandler;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

		$query = "	UPDATE	inv_po_trans
					SET		grnId = $grnId
					WHERE	poId = $poId
					AND		itemCategoryId = $poItemCategory
					AND		itemId = $poItem";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD PO TRANS
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (06 Sept 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function addGRNTrans($str) {
		global $REQUEST_DATA;
		global $sessionHandler;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

		$query = "	INSERT INTO
								inv_grn_trans (grnId,poId,itemCategoryId,itemId,quantityReceived,rate,amount)
					VALUES
								$str
				 ";

	    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }



//--------------------------------------------------------
// THIS FUNCTION IS USED TO ADD GRN to ITEM STOCK
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (06 Sept 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function addItemStock($val) {
		global $REQUEST_DATA;
		global $sessionHandler;
		//no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

		$query = "	INSERT INTO
								item_stock (itemCategoryId,itemId,date,quantity,type)
				    VALUES
								$val
				 ";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }



//--------------------------------------------------------
// THIS FUNCTION IS USED TO ADD GRN to inv_item_balance
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (06 Sept 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function updateTotalStock($grnQuantity,$poItem) {

		$query = "	UPDATE
							inv_item_balance
					SET
							balance=(balance+$grnQuantity)
					WHERE
							itemId = $poItem
				 ";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }






	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (07 Sep 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getGRN($conditions='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

       $query = "	SELECT	igm.*,
							igt.*,
							im.itemName,
							im.itemCode,
							ic.categoryName,
							ic.categoryCode
					FROM	inv_grn_master igm,
							inv_grn_trans igt,
							items_master im,
							item_category ic
					WHERE	igm.grnId = igt.grnId
					AND		igt.itemCategoryId = ic.itemCategoryId
					AND		igt.itemId = im.itemId
							$conditions";


        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED TO CANCELLED PO
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (06 Sept 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------

       public function cancelledGRN($grnId) {
		global $REQUEST_DATA;
		global $sessionHandler;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

		$query = "	UPDATE	inv_po_trans
					SET		grnId = 0
					WHERE	grnId = $grnId";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


}

// $History: $
//
?>
