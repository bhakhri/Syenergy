<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "REQUISTION MAPPING" table
// Author :Jaineesh
// Created on : (08.05.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class POManager {
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

       public function getPendingIndentList($conditions='',$limit='',$orderBy) {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

       $query = "	SELECT	iim.*,
							u.userName
					FROM	inv_indent_master iim,
							user u
					WHERE	indentStatus = 0
					AND		iim.userId = u.userId
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getPOList($conditions='',$limit='',$orderBy) {
		global $inventoryDepartmentArr;
		global $sessionHandler;

		$userId = $sessionHandler->getSessionVariable('UserId');

        $query = "	SELECT	ipm.*,
							u.userName
					FROM	inv_po_master ipm,
							user u
					WHERE	ipm.userId = u.userId
					AND		ipm.userId = $userId
							$conditions
							ORDER BY $orderBy
							";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING GENERATED PO LIST
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
//In this query MIN(ipt.status) is used to select the min PO status from inv_po_trans because min PO status is the current satus of PO

       public function getGeneratedPo($conditions='',$limit='',$orderBy) {
		   $query = "
					SELECT
								ipm.poNo,
								ipm.poDate,
								ipm.poId,
								MIN(ipt.status) AS status,
								u.userName
					FROM		`inv_po_master` ipm,
								`inv_po_trans` ipt,
								`user` u
					WHERE		ipm.userId = u.userId
					AND			ipm.poId=ipt.poId
					GROUP BY poNo
					$conditions
					ORDER BY $orderBy $limit
			";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
// this function is used to get the status of generated PO
      public function poStatus($poId) {
		   global $inventoryDepartmentArr;
		   global $sessionHandler;
		   $userId = $sessionHandler->getSessionVariable('UserId');
		   $query = "
					SELECT	MIN(status) AS status
					FROM	`inv_po_trans`
					WHERE	poId=$poId
					GROUP BY poId
			";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING UNAPPROVED PO  COUNT
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (29 Nov 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
  public function countUnapprovedPOList($conditions='',$limit='',$orderBy) {

       $query = "	SELECT	
							DISTINCT ipm.poNo,u.userName,ipm.poDate
					FROM	inv_po_master ipm, inv_po_trans ipt, user u
					WHERE	ipm.userId = u.userId
					AND     ipt.status = 0
					AND		ipt.poId = ipm.poId
							$conditions
							ORDER BY $orderBy
							$limit
							";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING UNAPPROVED PO  LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (29 Nov 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
  public function getUnapprovedPOList($conditions='',$limit='',$orderBy) {

       $query = "	SELECT	DISTINCT ipm.poNo,
							ipm.poDate,
							ipm.userId,
							ipm.poId,
							u.userName
					FROM	inv_po_master ipm,
							inv_po_trans ipt,
							user u
					WHERE	ipm.userId = u.userId
					AND     ipt.status = 0
					AND		ipt.poId = ipm.poId
							$conditions
							ORDER BY $orderBy
							$limit
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
// Created on : (03 Sept 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


	//this function is used to generate new ItemCodes
   public function generatePOCode(){
		global $sessionHandler;
		$year = date('Y');

		$userId = $sessionHandler->getSessionVariable('UserId');

		$makeUserId = "P".$userId."_".$year."_";

		 $str="SELECT IFNULL(MAX(ABS(SUBSTRING(poNo,length('".$makeUserId."' ) +1, LENGTH(poNo) ) ) ),0)+1 AS poNo FROM inv_po_master WHERE userId = $userId";
	    $uCode=SystemDatabaseManager::getInstance()->executeQuery($str,"Query: $str");
        //generate new itemCode code
		//$gCode=$makeUserId.str_pad($uCode[0]['requisitionNo'],abs(ITEM_CODE_LENGTH-strlen($makeUserId)-strlen($uCode[0]['requisitionNo']))+1,'0',STR_PAD_LEFT);
		$gCode=$makeUserId.str_pad($uCode[0]['poNo'],abs(PO_CODE_LENGTH-strlen($uCode[0]['poNo']))+1,'0',STR_PAD_LEFT);
		return $gCode;
   }



   //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF INDENT NO
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (03 Sep 10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getIndentValues($orderBy=' indentId') {
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

       public function getParty($conditions='') {

       $query = "	SELECT	partyName
					FROM	inv_party
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

	public function getIndentDetail($conditions='') {
		global $inventoryDepartmentArr;
		//no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

		$query = "	SELECT
								iit.*,
								iim.indentNo,
								iim.indentDate,
								ic.categoryName,
								im.itemName
					FROM
								inv_indent_master iim,
								inv_indent_trans iit,
								item_category ic,
								items_master im
					WHERE
								iim.indentStatus = 0
					AND			iit.indentId = iim.indentId
					AND			iit.itemCategoryId = ic.itemCategoryId
					AND			iit.itemId = im.itemId
					AND			iit.indentId = iim.indentId
					AND			iit.poId = 0
								$conditions
					ORDER BY	iim.indentNo
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getItemCategory($conditions='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

       $query = "	SELECT	distinct ic.itemCategoryId,
							ic.categoryCode,ic.categoryName
					FROM	inv_indent_trans iit,
							item_category ic
					WHERE	iit.itemCategoryId = ic.itemCategoryId
					AND		iit.poId = 0
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getItem($conditions='') {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

       $query = "	SELECT	distinct im.itemId,
							im.itemCode,im.itemName
					FROM	inv_indent_trans iit,
							items_master im
					WHERE	iit.itemId = im.itemId
					AND		iit.poId = 0
							$conditions
							";


        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET USER DATA AGAINST ROLE
//
// Author :Jaineesh
// Created on : (02 Sep 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function getPOData($conditions) {

        $query = "	SELECT	*
					FROM	inv_po_master
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GENERATE PO
//
// Author :Jaineesh
// Created on : (06 Sep 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------

public function addPO(){
	global $REQUEST_DATA;
	global $sessionHandler;
	$userId = $sessionHandler->getSessionVariable('UserId');
	$poNo = $REQUEST_DATA['poNo'];
	$partyId = $REQUEST_DATA['partyCode'];
	$poDate = date('Y-m-d');
	$totalAmount = $REQUEST_DATA['totalAmount'];
	$vat =$REQUEST_DATA['vat'];
	$discount=$REQUEST_DATA['discount'];
	$grandTotal=$REQUEST_DATA['grandTotal'];
	$aditionalCharges =$REQUEST_DATA['aditionalCharges'];
    $query = "	INSERT INTO inv_po_master(poNo,partyId,userId,poDate,grandTotal,discount,vat,aditionalCharges,netAmount)
				VALUES('$poNo',$partyId,$userId,'$poDate',$totalAmount,$discount,$vat,$aditionalCharges,$grandTotal)
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function addPOTrans($str) {
		global $REQUEST_DATA;
		global $sessionHandler;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

		$query = "	INSERT INTO inv_po_trans (poId,indentId,itemId,itemCategoryId,quantityRequired,rate,amount)
					VALUES $str";

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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function updateIndentTrans($indentId,$poItemCategory,$poItem,$poId) {
		global $REQUEST_DATA;
		global $sessionHandler;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

		$query = "	UPDATE	inv_indent_trans
					SET		poId = $poId
					WHERE	indentId = $indentId
					AND		itemCategoryId = $poItemCategory
					AND		itemId = $poItem";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED TO COUNT poid FROM TRANS
//
//$conditions :db clauses
// Author :Nishu
// Created on : (16 nov 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function countPendingPO($indentId) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$query = "	SELECT COUNT(poId) as cnt
					FROM inv_indent_trans
					WHERE indentId = $indentId
					AND   poId = 0";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD PO TRANS
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (06 Sept 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function updateIndentMaster($indentId) {
		global $REQUEST_DATA;
		global $sessionHandler;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

		$query = "	UPDATE	inv_indent_master
					SET		indentStatus = 2
					WHERE	indentId = $indentId";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED TO CANCELLED PO
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (06 Sept 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function cancelledPO($poId) {
		global $REQUEST_DATA;
		global $sessionHandler;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

		$query = "	UPDATE	inv_indent_trans
					SET		poId = 0
					WHERE	poId = $poId";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED TO CANCELLED GENERATED PO
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Created on : (9 dec 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function cancelledGeneratedPO($poId) {
		global $REQUEST_DATA;
		global $sessionHandler;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

		$query = "	UPDATE	inv_po_master
					SET		poId = 0
					WHERE	poId = $poId";
      // echo $query;
	    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


	//-------------------------------------------------------
// THIS FUNCTION IS USED TO CANCELLED PO
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (06 Sept 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

      public function updateIndent($indentIds) {
		global $REQUEST_DATA;
		global $sessionHandler;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

		$query = "	UPDATE	inv_indent_master
					SET		indentStatus = 0
					WHERE	indentId IN($indentIds)";
					//echo $query; die;

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED TO reject GENERATED PO
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Created on : (9 dec 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
  public function rejectPOTrans($poId, $conditions = '') {
		global $REQUEST_DATA;
		global $sessionHandler;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        $userId = $sessionHandler->getSessionVariable('UserId');
		$query = "	UPDATE	`inv_po_trans`
					SET	     status = 2
					WHERE	 poId = $poId
					$conditions
					";
					//echo $query;

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

	   public function rejectPOMaster($poId) {
		global $sessionHandler;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        $userId = $sessionHandler->getSessionVariable('UserId');
		$query = "	UPDATE	`inv_po_master`
					SET	     approvedOn = now(),
						     approvedBy = $userId
					WHERE	 poId = $poId
					";
					//echo $query;

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }
	//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET USER DATA AGAINST ROLE
//
// Author :Jaineesh
// Created on : (02 Sep 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function getIndent($poId) {

        $query = "	SELECT	distinct(indentId)
					FROM	inv_indent_trans
					WHERE	poId = $poId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------
// THIS FUNCTION IS USED TO get GENERATED PO
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Created on : (9 dec 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
  public function getPO($poId) {

        $query = "	SELECT	distinct(indentId)
					FROM	inv_po_trans
					WHERE	poId = $poId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------
// THIS FUNCTION IS USED TO get DETAIL OF GENERATED PO
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Created on : (9 dec 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getPODetail($poId) {


		$query="	SELECT	ipm.partyId,
							ipm.poNo,
							ipm.discount,
							ipm.poId,
							ipm.vat,
							ipm.aditionalCharges,
							ipm.netAmount,
							ipm.grandTotal,
							ipt.quantityRequired,
							ipt.rate,
							ipt.itemId,
							ipt.amount,
							im.itemCode,
							iim.indentNo,
							ic.categoryCode,
							ip.partyName
					FROM	inv_po_trans ipt ,
					        inv_po_master ipm,
							items_master im,
							item_category ic,
							inv_party ip,
							inv_indent_master iim
					WHERE	ipt.poId = $poId
					AND     ipt.poId = ipm.poId
					AND		ipt.itemCategoryId = im.itemCategoryId
					AND     ipt.itemCategoryId = ic.itemCategoryId
					AND		ipt.itemId = im.itemId
					AND     ipt.indentId = iim.indentId
					AND		ipt.status=0
					AND     ipm.partyId = ip.partyId
					AND		ic.itemCategoryId = im.itemCategoryId";



        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	//-------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE GENERATED PO IN PO MASTER TABLE
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Created on : (9 dec 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function updatePOMaster($poId,$partyId,$discount,$aditonalCharges,$vat,$grandtotal,$netAmount){
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$query="	UPDATE	inv_po_master
					SET		partyId = $partyId,
							approvedOn = now(),
							approvedBy = $userId,
							discount = $discount,
							vat = $vat,
							aditionalCharges = $aditonalCharges,
							grandTotal = $grandtotal,
							netAmount = $netAmount
					WHERE	poId = $poId";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}
	//-------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE GENERATED PO IN PO TRANS TABLE
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Created on : (9 dec 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function UpdatePOTrans($poId,$itemId,$rate,$quantityRequired,$amount){
		$query="	UPDATE	inv_po_trans
					SET		quantityRequired = $quantityRequired,
							rate = $rate,
							amount = $amount,
							status = 1
					WHERE   poId = $poId
					AND		itemId = $itemId";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}







}

// $History: $
//
?>
