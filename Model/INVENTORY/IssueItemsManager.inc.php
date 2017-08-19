<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class IssueItemsManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
    
	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   //for populating during edit
    public function getItemsList($conditions='',$store,$condition='',$orderBy='') {
     
    $query = "	SELECT 
							isi.subItemCode,
							isi.subItemId,
							if(ins.status=1,'Available',if(ins.status=2,'Issued','Transferred')) AS status,
							1 AS mode
					FROM 
							inv_sub_items isi,
							item_category ic,
							items_master im,
							inv_stock ins
					WHERE 
							ins.itemId = isi.itemId
					AND 	ins.subItemId = isi.subItemId
					AND		im.itemCategoryId = ic.itemCategoryId
					AND		ins.stockOutDate IS NULL
					AND		status != 3
					        $conditions
					UNION
					SELECT	isi.subItemCode,
							isi.subItemId,
							IF(iit.status=1,'Issued','Returned') AS STATUS,
							2 AS mode
					FROM	inv_sub_items isi,
							issue_items iit
					WHERE 	iit.subItemId = isi.subItemId
					AND		returnDate IS NULL
					AND		issuedTo = ".$store."
					AND		status = 1
							$condition
							ORDER BY $orderBy
					
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   //for populating during edit
    public function checkEndUserItems($issuedTo) {
     
	 global $REQUEST_DATA;
		global $sessionHandler;
		$chb  = $REQUEST_DATA['chb'];
				
		$insertValue = "";
		foreach($chb as $subItemId){
		
				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator('".$subItemId."')";
		}

      $query = "	SELECT 
							COUNT(*) AS totalRecords
					FROM 
							issue_items
					WHERE 	invDepttId = ".$issuedTo."
					AND		subItemId IN (".$insertValue.")
					AND		stockOutDate IS NULL
					        $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   //for populating during edit
    public function checkReturnIssueItems($issuedTo) {
     
	 global $REQUEST_DATA;
		global $sessionHandler;
		$chb  = $REQUEST_DATA['chb'];
				
		$insertValue = "";
		foreach($chb as $subItemId){
		
				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator('".$subItemId."')";
		}

      $query = "	SELECT 
							COUNT(*) AS totalRecords
					FROM	inv_stock
					WHERE 	invDepttId = ".$issuedTo."
					AND		subItemId IN (".$insertValue.")
					AND		stockOutDate IS NULL
					        $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING ISSUE ITEMS
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   //for populating during edit
    public function checkItemsIssueStatus($store) {
     
      $query = "	SELECT 
							depttType
					FROM	inv_dept
					WHERE 	invDepttId = ".$store."
							$conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   //for populating during edit
    public function checkReturnItems($store) {
     
	 global $REQUEST_DATA;
		global $sessionHandler;
		$chb  = $REQUEST_DATA['chb'];
				
		$insertValue = "";
		foreach($chb as $subItemId){
		
				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator('".$subItemId."')";
		}

      $query = "	SELECT 
							COUNT(*) AS totalRecords
					FROM 
							inv_stock
					WHERE 	invDepttId = ".$store."
					AND		subItemId IN (".$insertValue.")
					AND		stockOutDate IS NULL
					        $conditions
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

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh 
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   public function getItemDetail($itemCategoryId) {
     
       $query = "SELECT 
                         itemId,
						 itemName
                  FROM
                         items_master
                  WHERE
                         itemCategoryId = ".$itemCategoryId."
						 AND itemType = 2 
						 ORDER BY itemName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh 
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   public function getConsumableItemDetail($itemCategoryId,$invDepttId) {
     
         $query = "	SELECT 
							 im.itemId,
							 concat(im.itemName,'(',im.availableQty,')') AS itemName
					FROM
							items_master im,
							inv_stock ins
					WHERE	im.itemCategoryId =".$itemCategoryId."
					AND		im.itemType = 1 
					AND		ins.subItemId = 0
					AND		ins.invDepttId = ".$invDepttId."
					AND		im.itemId = ins.itemId
							ORDER BY itemName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Update class to frozen
//
// Author :Jaineesh
// Created on : 01-07-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function insertStockItems($itemCategory,$itemId,$issuedTo,$issuedDate) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$chb  = $REQUEST_DATA['chb'];
				
		$insertValue = "";
		foreach($chb as $subItemId){
		
				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator(".$itemId.",'".$subItemId."',".$issuedTo.",1,'".$issuedDate."',NULL)";
		}
		
		$query = "INSERT INTO inv_stock(itemId,subItemId,invDepttId,status,stockInDate,stockOutDate) VALUES ".$insertValue."";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Update class to frozen
//
// Author :Jaineesh
// Created on : 01-07-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function updateStockItems($issuedDate) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$chb  = $REQUEST_DATA['chb'];
				
		$insertValue = "";
		foreach($chb as $subItemId){
		
				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator('".$subItemId."')";
		}
		
		$query = "	UPDATE inv_stock 
					SET	status = 3,
						stockOutDate = '".$issuedDate."'
					WHERE subItemId IN (".$insertValue.")";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Update class to frozen
//
// Author :Jaineesh
// Created on : 01-07-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function insertIssueItems($issuedTo,$store,$issuedDate) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$chb  = $REQUEST_DATA['chb'];
				
		$insertValue = "";
		foreach($chb as $subItemId){
		
				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator('".$subItemId."',".$issuedTo.",".$store.",'".$issuedDate."',NULL)";
		}
		
		$query = "INSERT INTO issue_items (subItemId,issuedTo,issuedFrom,issueDate,returnDate) VALUES ".$insertValue."";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Update class to frozen
//
// Author :Jaineesh
// Created on : 01-07-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function updateStockItemsStatus($issuedItemStatus,$store) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$chb  = $REQUEST_DATA['chb'];
				
		$insertValue = "";
		foreach($chb as $subItemId){
		
				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator('".$subItemId."')";
		}
		
		 $query = "	UPDATE	inv_stock 
					SET		status = ".$issuedItemStatus."
					WHERE	subItemId IN (".$insertValue.")
					AND		invDepttId = ".$store."";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Update class to frozen
//
// Author :Jaineesh
// Created on : 01-07-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function updateReturnedStockItemsStatus($issuedTo) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$chb  = $REQUEST_DATA['chb'];
				
		$insertValue = "";
		foreach($chb as $subItemId){
		
				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator('".$subItemId."')";
		}
		
		  $query = "	UPDATE	inv_stock 
					SET		status = 1
					WHERE	subItemId IN (".$insertValue.")
					AND		invDepttId = ".$issuedTo." ";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Update Issue Items
//
// Author :Jaineesh
// Created on : 01-07-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function updateIssueItemsStatus($issuedDate,$store,$issuedTo) {
		global $REQUEST_DATA;
		global $sessionHandler;	
		$chb  = $REQUEST_DATA['chb'];
				
		$insertValue = "";
		foreach($chb as $subItemId){
		
				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator('".$subItemId."')";
		}
		
		 $query = "	UPDATE	issue_items 
					SET		returnDate = '".$issuedDate."',
							status = 2
					WHERE	subItemId IN (".$insertValue.")
					AND		issuedTo = ".$store."
					AND		issuedFrom = ".$issuedTo."";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Update class to frozen
//
// Author :Jaineesh
// Created on : 01-07-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  

	public function getInvNonIssueDepttData($invDepttId) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
         $query = "	SELECT	invDepttId,
							invDepttAbbr
					FROM	inv_dept 
					WHERE	invDepttId NOT IN ($invDepttId)
							ORDER BY invDepttAbbr";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET CONSUMBALE LIST
//
// Author :Jaineesh
// Created on : 09-03-2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  

	public function getCosumableList($filter='',$limit='',$orderBy=' issuedFrom') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
    $query = "	SELECT	icii.invConsumableIssuedId,
							im.itemName,
							(select invDepttAbbr from inv_dept where invDepttId = icii.issuedTo) AS issuedTo,
							(select invDepttAbbr from inv_dept where invDepttId = icii.invDepttId) AS issuedFrom,
							icii.itemQuantity,
							icii.issuedDate,
							ic.categoryName
					FROM	items_master im,
							inv_consumable_issue_items icii,
							inv_dept invd,
							item_category ic
					WHERE	im.itemId = icii.itemId
					AND		icii.issuedTo = invd.invDepttId
					AND		ic.itemCategoryId = im.itemCategoryId
					AND		icii.status = 0
							$filter
							ORDER BY $orderBy $limit";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO ADD CONSUMABLE ITEMS
//
// Author :Jaineesh
// Created on : 09-03-2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
	public function addConsumableItems($str){
	 
		 $query="INSERT INTO 
					  inv_consumable_issue_items (itemId,invDepttId,issuedTo,itemQuantity,issuedDate,comments)
				VALUES $str
				";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Update CONSUMABLE ITEMS Quantity
//
// Author :Jaineesh
// Created on : 09-03-2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
	public function updateConsumableAvailableQuantity($itemId,$quantity,$itemCategory){
	 
		$query="	UPDATE	items_master 
					SET		availableQty = ".$quantity."
					WHERE	itemId = ".$itemId."
					AND		itemCategoryId = ".$itemCategory."

				";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
    
	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET CONSUMBALE LIST
//
// Author :Jaineesh
// Created on : 09-03-2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  

	public function getConsumableItemsDetails($conditions="") {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
   $query = "		SELECT	icii.invConsumableIssuedId,
							im.itemName,
							invd.invDepttAbbr,
							icii.itemQuantity,
							icii.issuedDate,
							ic.categoryName,
							icii.comments,
							icii.invDepttId,
							icii.issuedTo,
							im.itemCategoryId,
							icii.itemId
					FROM	items_master im,
							inv_consumable_issue_items icii,
							inv_dept invd,
							item_category ic,
							inv_stock ins
					WHERE	im.itemId = icii.itemId
					AND		icii.issuedTo = invd.invDepttId
					AND		ic.itemCategoryId = im.itemCategoryId
					AND		icii.invDepttId = ins.invDepttId
					AND		icii.itemId = ins.itemId
							$conditions";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET CONSUMBALE LIST
//
// Author :Jaineesh
// Created on : 09-03-2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  

	public function getInventoryItemsDetail($itemCategory,$itemId,$store) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
  $query = "		SELECT	im.availableQty
					FROM	items_master im,
							inv_stock ins
					WHERE	ins.itemId = im.itemId
					AND		im.itemCategoryId = $itemCategory
					AND		im.itemId = $itemId
					AND		im.itemType = 1
					AND		ins.invDepttId = $store
							$conditions";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Update CONSUMABLE ITEMS Quantity
//
// Author :Jaineesh
// Created on : 09-03-2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
	public function editConsumableIssueItems($invConsumableIssuedId,$editIssuedTo,$issueDate,$commentsTxt){
	 
		$query="	UPDATE	inv_consumable_issue_items 
					SET		issuedTo = ".$editIssuedTo.",
							issuedDate = '".$issueDate."',
							comments = '".$commentsTxt."'
					WHERE	invConsumableIssuedId = ".$invConsumableIssuedId."

				";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   //for populating during edit
    public function getIssueFromItemsList($conditions='',$store) {
     
     $query = "	SELECT 
							iit.subItemId,
							iit.issuedTo
					FROM	issue_items iit
					WHERE 	returnDate IS NULL
					AND		issuedFrom = ".$store."
					AND		status = 1
				
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

		//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   //for populating during edit
    public function getLatestDepttList() {
     
   $query = "	SELECT a.subItemId, 
					(SELECT c.invDepttAbbr from issue_items b, inv_dept c where b.returnDate IS NULL and b.subItemId = a.subItemId AND b.issuedTo = c.invDepttId order by issueItemId desc limit 0,1) AS latestUser 
					FROM inv_stock a" ;

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   //for populating during edit
   // 1 -> is using for Available
    public function checkAvailableIssueItems($store) {
		global $REQUEST_DATA;
		global $sessionHandler;	
		$chb  = $REQUEST_DATA['chb'];
				
		$insertValue = "";
		foreach($chb as $subItemId){
		
				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator('".$subItemId."')";
		}

    $query = "	SELECT 
							COUNT(*) AS totalRecords
					FROM	inv_stock
					WHERE 	invDepttId = ".$store."
					AND		stockOutDate IS NULL
					AND		status = 1
					AND		subItemId IN (".$insertValue.")
					        $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   //for populating during edit
   // 1 -> is using for Available
    public function checkReturnedStatus($store,$issuedTo) {
     global $REQUEST_DATA;
	 global $sessionHandler;
		$chb  = $REQUEST_DATA['chb'];
				
		$insertValue = "";
		foreach($chb as $subItemId){
		
				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator('".$subItemId."')";

      $query = "	SELECT 
							COUNT(*) AS totalRecords
					FROM	issue_items
					WHERE 	issuedTo = ".$store."
					AND		subItemId IN (".$insertValue.")
					AND		issuedFrom = ".$issuedTo."
					AND		returnDate IS NULL
					        $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}
	}

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR UPDATE TABLE inv_consumable_issue_items
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   //for populating during edit
    public function deleteConsumableItems($invConsumableIssuedId) {
     
      $query = "	UPDATE	inv_consumable_issue_items
					SET		status = 1 
					WHERE	invConsumableIssuedId = ".$invConsumableIssuedId."
					
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING QUANTITY OF AN ITEMS
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (12.03.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   //for populating during edit
    public function getQuantity($invConsumableIssuedId) {
     
      $query = "	SELECT 
							icit.itemQuantity,
							icit.itemId
					FROM	inv_consumable_issue_items icit
					WHERE 	icit.invConsumableIssuedId = ".$invConsumableIssuedId."					
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING QUANTITY OF AN ITEMS
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (12.03.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   //for populating during edit
    public function getAvailableQuantity($itemId) {
     
      $query = "	SELECT 
							im.availableQty
					FROM	items_master im
					WHERE 	im.itemId = ".$itemId."					
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR UPDATE CONSUMABLE ITEMS STATUS
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (12.03.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

    public function updateConsumableItemsStatus($invConsumableIssuedId) {
     
     $query = "	UPDATE	inv_consumable_issue_items
					SET		STATUS = 2
					WHERE 	invConsumableIssuedId = ".$invConsumableIssuedId."					
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR UPDATE CONSUMABLE ITEMS STATUS
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (12.03.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

    public function updateConsumableItemsQuantity($gettingQuantity,$itemId) {
     
      $query = "	UPDATE	items_master
					SET		availableQty = ".$gettingQuantity."
					WHERE 	itemId = ".$itemId."	
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING ISSUE DATE OF AN ITEMS
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (29.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   //for populating during edit
    public function getIssueDate($store,$insertValues) {
     
    $query = "	SELECT 
							distinct(stockInDate)
					FROM	inv_stock
					WHERE 	invDepttId = ".$store."
					AND		status = 1
					AND		stockOutDate IS NULL
					AND		subItemId IN ($insertValues)";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING ISSUE DATE OF AN ITEMS
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (29.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 

   //for populating during edit
    public function getIssuedItemsDate($store,$insertValues) {
     
      $query = "	SELECT 
							min(issueDate) AS issueMinDate,
							max(issueDate) AS issueMaxDate
					FROM	issue_items
					WHERE 	issuedTo = ".$store."
					AND		status = 1
					AND		returnDate IS NULL
					AND		subItemId IN ($insertValues)";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

}
// $History: IssueItemsManager.inc.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/24/10    Time: 10:09a
//Created in $/Leap/Source/Model/INVENTORY
//new files for items master
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/09/09   Time: 18:53
//Created in $/Leap/Source/Model/INVENTORY
//Created "Issue Master" under inventory management in leap
?>