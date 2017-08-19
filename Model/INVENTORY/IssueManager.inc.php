<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class IssueManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
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
    
    

//this function is used to add issue indent
public function addIssue($indentId,$issueDate,$remarks=''){
    
    $query="INSERT INTO 
                  inv_issue_items (indentId,issueDate,remarks)
            VALUES($indentId,'$issueDate','$remarks')
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}



//this function is used to add receive order details   
public function addIssueDetails($insStr){
 
    $query="INSERT INTO 
                  inv_issue_items_detail  (indentId,itemId,quantityIssued)
            VALUES $insStr
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}        
    
//this function is used to update items's availableQty 
    public function updateAvailableQuantityOfItem($itemId,$newQuantity) {
     
        $query = "UPDATE 
                         items_master
                  SET
                         availableQty=( availableQty - $newQuantity )
                  WHERE 
                         itemId=$itemId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getIssuedIndentList($conditions='', $limit = '', $orderBy=' iri.indentNo') {
     
        $query = "SELECT 
                        iri.indentId,iri.indentNo,
                        iri.dated,
                        iii.issueDate,
                        emp.employeeCode,
                        emp1.employeeCode AS empCode
                  FROM 
                        inv_request_indent iri ,inv_issue_items iii,
                        employee emp,employee emp1
                  WHERE 
                        iri.indentId=iii.indentId
                        AND iri.requestToUserId=emp.userId
                        AND iri.requestByUserId=emp1.userId
                        $conditions 
                        ORDER BY $orderBy 
                        $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF CITIES
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalIssuedIndent($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        inv_request_indent iri ,inv_issue_items iii,
                        employee emp,employee emp1
                  WHERE 
                        iri.indentId=iii.indentId
                        AND iri.requestToUserId=emp.userId
                        AND iri.requestByUserId=emp1.userId
                        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
   //for populating during add
    public function getIndentDetails($conditions='') {
     
        $query = "SELECT 
                        iri.indentId,iri.indentNo,iri.dated,iri.dated AS indentDate,
                        emp.employeeCode,
                        i.itemCode,i.itemName,
                        iird.quantityRequested
                  FROM 
                        inv_request_indent iri,
                        inv_request_indent_details iird,
                        items_master i,employee emp
                  WHERE 
                        iri.indentId=iird.indentId
                        AND iird.itemId=i.itemId
                        AND iri.requestByUserId=emp.userId
                        $conditions
                        ORDER BY  i.itemId 
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

   //for populating during edit
    public function getIssuedIndentDetails($conditions='') {
     
       $query = "SELECT 
                        iri.indentId,iri.indentNo,iri.dated,
                        emp.employeeCode,
                        i.itemCode,i.itemName,
                        iird.quantityRequested,
                        iii.issueDate,
                        iii.remarks,
                        iiid.quantityIssued
                  FROM 
                        inv_request_indent iri,
                        inv_request_indent_details iird,
                        items_master i,employee emp,
                        inv_issue_items iii,inv_issue_items_detail iiid
                  WHERE 
                        iri.indentId=iird.indentId
                        AND iird.itemId=i.itemId
                        AND iri.requestByUserId=emp.userId
                        AND iri.indentId=iii.indentId
                        AND iii.indentId=iiid.indentId
                        AND iiid.itemId=i.itemId
                        $conditions
                        ORDER BY  i.itemId 
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //for checking during data add / edit
    public function getIndentDetailIds($conditions='') {
     
        $query = "SELECT 
                        iri.indentId,
                        iri.dated,
                        irid.itemId,
                        irid.quantityRequested
                  FROM 
                        inv_request_indent iri,  inv_request_indent_details irid
                  WHERE 
                        iri.indentId=irid.indentId
                        $conditions 
                        ORDER BY  irid.itemId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //for checking to prevent editing/duplication of records
    public function checkIndentId($indentId) {
     
        $query = "SELECT 
                        COUNT(*) AS found
                  FROM 
                        inv_issue_items 
                  WHERE 
                        indentId ='".$indentId."'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
   //for checking during data add / edit
    public function checkItemIds($itemIds) {
        $query = "SELECT 
                        i.itemId,i.availableQty ,i.itemCode
                  FROM 
                        items_master i
                  WHERE 
                        i.itemId IN ($itemIds)
                  ORDER BY i.itemId
                  ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
   //for checking userPermission
    public function checkUserPermission($indentId,$userId) {
        $query = "SELECT 
                        COUNT(*) AS found
                  FROM 
                        inv_request_indent 
                  WHERE 
                        indentId='".$indentId."'
                        AND requestToUserId=$userId
                  ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

   //-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING An Item
//
//$itemId :itemId of the items_master
// Author :Jaineesh
// Created on : (30 Aug 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function getApprovedItemsList($conditions,$limit='',$orderBy='') {
     
		$query = "	SELECT	
							irm.*,
							emp.employeeName
					FROM	
							inv_requisition_master irm,
							employee emp
					WHERE	
							irm.requisitionStatus IN (".APPROVED.",".INCOMPLETE.")
					AND		
							irm.approvedBy = emp.userId
							$conditions";

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
					AND		irm.requisitionStatus IN (".APPROVED.",".INCOMPLETE.")
					AND     irt.itemStatus = ".ITEM_PENDING."
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function updateApprovedRequisitionMaster($requisitionId,$requisitionStatus) {
		global $REQUEST_DATA;
		global $sessionHandler;
        
        $query = "	UPDATE	
							inv_requisition_master
					SET		
							requisitionStatus = $requisitionStatus
					WHERE	
							requisitionId = $requisitionId
					";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }



//-------------------------------------------------------
// THIS FUNCTION IS USED FOR UPDATION IN INVENTORY ITEM BALANCE
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function reduceItemBalance($quantityRequired,$itemId) {
		   global $sessionHandler;
		   $sessionId = $sessionHandler->getSessionVariable('SessionId');
		        
       $query = "	UPDATE	
							inv_item_balance
					SET		
							balance = (balance - $quantityRequired)
					WHERE	
							itemId = '$itemId'
					AND		sessionId=$sessionId
				";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }




//-------------------------------------------------------
// THIS FUNCTION IS USED FOR UPDATION ITEM STATUS
//
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Jaineesh
// Created on : (1 December 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
	public function updateItemStatus($itemStatus,$itemId,$requisitionId) {
		global $sessionHandler;
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

		$query = "	UPDATE	
							inv_requisition_trans irt
					SET		
							irt.itemStatus = '$itemStatus'
					WHERE	
							irt.itemId = '$itemId'
					AND
							irt.requisitionId = '$requisitionId';
				";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}



//--------------------------------------------------------
// THIS FUNCTION IS USED TO REJECT APPROVED REQUISITION
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function updateRejectedRequisition($requisitionId) {
		global $REQUEST_DATA;
		global $sessionHandler;
        
       $query = "	UPDATE	inv_requisition_master
					SET		requisitionStatus = 6
					WHERE	requisitionId = $requisitionId";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR SELECTING An Item-Quantity
//
// $itemId :itemId of the items_master
// Author :Jaineesh
// Created on : (16 Aug 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function checkItemsAvailability($requisitionId) {
     
        $query = "
 					SELECT 
							i.itemId, i.itemCode, i.itemName, ib.balance, rt.quantityRequired
					FROM 
							inv_item_balance ib, inv_requisition_trans rt, items_master i
					WHERE 
							i.itemId = ib.itemId
					AND		i.itemId = rt.itemId
					AND		rt.requisitionId =$requisitionId
					AND		rt.itemStatus = ".ITEM_PENDING."
				 ";
	
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}
// $History: IssueManager.inc.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/09/09   Time: 18:53
//Created in $/Leap/Source/Model/INVENTORY
//Created "Issue Master" under inventory management in leap
?>