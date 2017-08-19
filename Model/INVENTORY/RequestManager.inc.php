<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class RequestManager {
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

   //this function is used to generate new ORDER NO 
   public function generateIndentNo(){
       global $sessionHandler;
       $indentNoPrefix=$sessionHandler->getSessionVariable('INDENT_NO_PREFIX');
       $indentNoLength=$sessionHandler->getSessionVariable('INDENT_NO_LENGTH');
       
       $str="SELECT IFNULL(MAX(ABS(SUBSTRING(indentNo,length('".$indentNoPrefix."' ) +1, LENGTH(indentNo) ) ) ),0)+1 AS indentNo FROM  inv_request_indent ";
       $iCode=SystemDatabaseManager::getInstance()->executeQuery($str,"Query: $str");
        
        //generate new order no
       $gCode=$indentNoPrefix.str_pad($iCode[0]['indentNo'],abs($indentNoLength-strlen($indentNoPrefix)-strlen($iCode[0]['indentNo']))+1,'0',STR_PAD_LEFT);
       return $gCode;
   }

//this function is used to add indent
public function addIndent($indentNo,$indentDate,$requestedToUserId,$requestId,$remarks=''){
   $query="INSERT INTO 
                  inv_request_indent(indentNo,dated,requestToUserId,requestByUserId,remarks)
            VALUES('$indentNo','$indentDate',$requestedToUserId,$requestId,'$remarks');
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
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
public function addIndentDetails($str){
 
    $query="INSERT INTO 
                  inv_request_indent_details (indentId,itemId,quantityRequested)
            VALUES $str
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}        
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A CITY
//$cityId :cityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteIndentDetails($indentId) {
     
        $query = "DELETE 
                  FROM 
                      inv_request_indent_details 
                  WHERE 
                      indentId ='".$indentId."'";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function deleteIndent($indentId) {
     
        $query = "DELETE 
                  FROM 
                      inv_request_indent
                  WHERE 
                      indentId ='".$indentId."'";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function checkIssueedIndent($indentId) {
    
        $query = "SELECT 
                        COUNT(*) AS found 
                  FROM 
                        inv_issue_items
                  WHERE 
                      indentId ='".$indentId."'";
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
    
    public function getIndentList($conditions='', $limit = '', $orderBy=' ir.indentNo') {
     
        $query = "SELECT 
                        ir.indentId, ir.indentNo,
                        ir.dated,
                        emp1.employeeCode,
                        emp2.employeeCode AS empCode
                  FROM 
                        inv_request_indent  ir, employee emp1 ,employee emp2
                  WHERE 
                        ir.requestToUserId=emp1.employeeId
                        AND ir.requestByUserId=emp2.employeeId
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
    public function getTotalIndent($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        inv_request_indent  iri, employee emp1 ,employee emp2
                  WHERE 
                        iri.requestToUserId=emp1.employeeId
                        AND iri.requestByUserId=emp2.employeeId
                        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

    public function getEmployeeList($conditions='', $limit = '', $orderBy=' emp.employeeName') {
     
        $query = "SELECT 
                        emp.employeeId,emp.employeeName,
                        emp.employeeCode,deg.designationCode
                  FROM 
                        employee emp,designation deg,user u
                  WHERE 
                        emp.designationId=deg.designationId
                        AND emp.userId=u.userId
                        AND emp.isActive=1
                        AND ( emp.employeeCode    IS NOT NULL AND TRIM(emp.employeeCode)<>'')
                        AND ( deg.designationCode IS NOT NULL AND TRIM(deg.designationCode)<>'')
                        $conditions 
                        ORDER BY $orderBy 
                        $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getTotalEmployees($conditions='') {
     
        $query = "SELECT 
                        COUNT(*) AS totalRecords
                  FROM 
                        employee emp,designation deg,user u
                  WHERE 
                        emp.designationId=deg.designationId
                        AND emp.userId=u.userId
                        AND emp.isActive=1
                        AND ( emp.employeeCode    IS NOT NULL AND TRIM(emp.employeeCode)<>'')
                        AND ( deg.designationCode IS NOT NULL AND TRIM(deg.designationCode)<>'')
                        $conditions 
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    

    
   //check for existense of employee in employee table
    public function checkEmployee($conditions) {
     
       $query = "SELECT 
                         e.employeeId,u.userId
                  FROM
                         employee e,user u
                  WHERE
                         e.userId=u.userId
                         AND e.isActive=1
                         $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getIndentDetails($conditions='') {
     
        $query = "SELECT 
                        ir.indentId, ir.indentNo,
                        ir.dated,ir.remarks,
                        emp1.employeeId,
						i.itemId,
                        i.itemName,i.itemCode,
                        ird.quantityRequested,
						ic.itemCategoryId,
						ic.categoryName
                  FROM 
                        inv_request_indent  ir, 
						employee emp1 ,
						employee emp2,
                        items_master i,
						inv_request_indent_details ird,
						item_category ic
                  WHERE 
                        ir.requestToUserId=emp1.employeeId
                        AND ir.requestByUserId=emp2.employeeId
                        AND ir.indentId=ird.indentId
                        AND ird.itemId=i.itemId
						AND	i.itemCategoryId = ic.itemCategoryId
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
	
	public function getItemDetail($itemCategoryId) {
     
       $query = "SELECT 
                         itemId,
						 itemName
                  FROM
                         items_master
                  WHERE
                         itemCategoryId ='".$itemCategoryId."'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getItemQuantity($itemId) {
     
       $query = "SELECT 
                         availableQty
                  FROM
                         items_master
                  WHERE
                         itemId ='".$itemId."'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getEmployeeDetail($requestedByUserId) {
     
       $query = "SELECT 
                         employeeId
                  FROM
                         employee
                  WHERE
                         userId ='".$requestedByUserId."'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
  
}
// $History: RequestManager.inc.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/24/10    Time: 10:09a
//Created in $/Leap/Source/Model/INVENTORY
//new files for items master
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