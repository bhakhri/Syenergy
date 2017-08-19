<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class IndentManager {
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
    
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getItemName($conditions) {
     
       $query = "SELECT 
                         i.itemId,i.itemName,i.itemCode,iis.supplierId
                  FROM
                         items_master i,item_suppliers iis
                  WHERE
                         i.itemId=iis.itemId
                         $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

   //this function is used to generate new ItemCodes 
   public function generateIndentCode(){
		global $sessionHandler;
		$year = date('Y');

		$userId = $sessionHandler->getSessionVariable('UserId');

		$makeUserId = "I".$userId."_".$year."_";
		
		 $str="SELECT IFNULL(MAX(ABS(SUBSTRING(indentNo,length('".$makeUserId."' ) +1, LENGTH(indentNo) ) ) ),0)+1 AS indentNo FROM inv_indent_master WHERE userId = $userId";
	    $uCode=SystemDatabaseManager::getInstance()->executeQuery($str,"Query: $str");
        //generate new itemCode code
		//$gCode=$makeUserId.str_pad($uCode[0]['requisitionNo'],abs(ITEM_CODE_LENGTH-strlen($makeUserId)-strlen($uCode[0]['requisitionNo']))+1,'0',STR_PAD_LEFT);
		$gCode=$makeUserId.str_pad($uCode[0]['indentNo'],abs(INDENT_CODE_LENGTH-strlen($uCode[0]['indentNo']))+1,'0',STR_PAD_LEFT);
		return $gCode;
   }

//this function is used to add indent
public function addIndent(){
	global $REQUEST_DATA;
	global $sessionHandler;
	$userId = $sessionHandler->getSessionVariable('UserId');
	$indentNo = $REQUEST_DATA['indentNo'];
	$indentDate = date('Y-m-d');
    $query = "INSERT INTO 
                  inv_indent_master(indentDate,userId,indentNo,indentStatus)
            VALUES('$indentDate',$userId,'$indentNo',0)
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR INSERTION REQUISITION TRANS
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (02 Sept 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function addIndentTrans($str) {
		global $REQUEST_DATA;
		global $sessionHandler;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
		$itemCategoryId = $REQUEST_DATA['itemCategory'];
		$itemId = $REQUEST_DATA['itemCode'];
		$quantityRequired = $REQUEST_DATA['quantityRequired'];
        
		$query = "	INSERT INTO inv_indent_trans (indentId,itemCategoryId,itemId,quantityRequired)
					VALUES $str";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//this function is used to edit order
public function editIndent($indentId,$indentDate,$requestedToUserId,$remarks=''){
    global $sessionHandler;
    $query="UPDATE 
                  inv_request_indent 
            SET
                  dated='".$indentDate."',
                  requestToUserId='".$requestedToUserId."',
                  remarks='".$remarks."'
            WHERE
                  indentId=$indentId
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


//this function is used to add indent details   
public function addIndentDetails($insStr){
 
    $query="INSERT INTO 
                  inv_request_indent_details (indentId,itemId,quantityRequested)
            VALUES $insStr
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}        
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET USER DATA AGAINST ROLE
//
// Author :Jaineesh 
// Created on : (27 July 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function getIndent($conditions) {
     
       $query = "	SELECT	iim.indentId,
							iim.indentNo,
							iit.*,
							im.itemName,
							im.itemCode,
							ic.categoryName,
							ic.categoryCode
					FROM	inv_indent_master iim,
							inv_indent_trans iit,
							items_master im,
							item_category ic
					WHERE	iim.indentId = iit.indentId
					AND		iit.itemCategoryId = ic.itemCategoryId
					AND		iit.itemId = im.itemId
					AND		iim.indentStatus = 0
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
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
    
    public function getIndentList($conditions='', $limit = '', $orderBy='indentNo') {
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');

        $query = "	SELECT	iim.*,IF(iim.indentStatus=0,'Pending',IF(iim.indentStatus=1,'Cancelled','GeneratedPo')) AS typeOf,
							COUNT(iit.indentId) AS totalCount
					FROM	inv_indent_master iim,
							inv_indent_trans iit
					WHERE	iim.indentId = iit.indentId
					AND		iim.userId = $userId
							GROUP BY iit.indentId
							$conditions
							ORDER BY $orderBy 
							$limit";
					//		echo $query;die;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

    
    public function getIndentDetails($conditions='') {
     
        $query = "SELECT 
                        ir.indentId, ir.indentNo,
                        ir.dated,ir.remarks,
                        emp1.employeeCode,
                        i.itemName,i.itemCode,
                        ird.quantityRequested
                  FROM 
                        inv_request_indent  ir, employee emp1 ,employee emp2,
                        items_master i,inv_request_indent_details ird
                  WHERE 
                        ir.requestToUserId=emp1.userId
                        AND ir.requestByUserId=emp2.userId
                        AND ir.indentId=ird.indentId
                        AND ird.itemId=i.itemId
                        $conditions 
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function checkDuplicateIndentNo($indentNo) {
     
       $query = "SELECT 
                         COUNT(*) AS found
                  FROM
                         inv_request_indent
                  WHERE
                         indentNo ='".$indentNo."'";
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
    public function getIndentData($conditions) {
     
        $query = "	SELECT	*
					FROM	inv_indent_master
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
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
    
      public function deleteIndentTrans($indentId) {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        
       $query = "	DELETE	
					FROM	inv_indent_trans
					WHERE	indentId = $indentId";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
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
    
       public function getIndentDetailList($conditions) {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        
       $query = "	SELECT	iit.itemCategoryId,
							iim.indentNo,
							iit.itemId,
							ic.categoryName,
							im.itemName,
							iit.quantityRequired
					FROM	inv_indent_trans iit,
							item_category ic,
							items_master im,
							inv_indent_master iim
					WHERE	iit.itemId = im.itemId
					AND		iit.itemCategoryId = ic.itemCategoryId
					AND		iit.indentId = iim.indentId
							$conditions";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
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
    
       public function cancelledIndentTrans($indentId) {
		global $inventoryDepartmentArr;
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        
       $query = "	Update	inv_indent_master
					SET		indentStatus = 1
					WHERE	indentId = $indentId";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

}
// $History: IndentManager.inc.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/09/09   Time: 18:22
//Created in $/Leap/Source/Model/INVENTORY
//Created  "Indent Master" module under "Inventory Management"
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/09/09    Time: 12:38
//Created in $/Leap/Source/Model/INVENTORY
//Moved Inventory Management Files to INVENTORY folder
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/09/09    Time: 18:47
//Created in $/Leap/Source/Model
//Added files for "Order Master" module
?>