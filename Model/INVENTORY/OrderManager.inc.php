<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class OrderManager {
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
    
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
   public function generateOrderNo(){
       global $sessionHandler;
       $orderNoPrefix=$sessionHandler->getSessionVariable('ORDER_NO_PREFIX');
       $orderNoLength=$sessionHandler->getSessionVariable('ORDER_NO_LENGTH');
       
       $str="SELECT IFNULL(MAX(ABS(SUBSTRING(orderNo,length('".$orderNoPrefix."' ) +1, LENGTH(orderNo) ) ) ),0)+1 AS orderNo FROM  inv_orders ";
       $iCode=SystemDatabaseManager::getInstance()->executeQuery($str,"Query: $str");
        
        //generate new order no
       $gCode=$orderNoPrefix.str_pad($iCode[0]['orderNo'],abs($orderNoLength-strlen($orderNoPrefix)-strlen($iCode[0]['orderNo']))+1,'0',STR_PAD_LEFT);
       return $gCode;
   }

//this function is used to add order
public function addOrder($orderNo,$orderDate,$supplierId,$dispatched,$dispatchedDate){
    global $sessionHandler;
    $userId=$sessionHandler->getSessionVariable('UserId');
    $date=date('Y-m-d');
    $query="INSERT INTO 
                  inv_orders(orderNo,orderDate,supplierId,dispatched,dispatchDate,addedByUserId,addedOnDate)
            VALUES('$orderNo','$orderDate',$supplierId,$dispatched,'$dispatchedDate',$userId,'$date')
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

//this function is used to edit order
public function editOrder($orderId,$orderDate,$dispatched,$dispatchedDate){
    global $sessionHandler;
    $query="UPDATE 
                  inv_orders
            SET
                  orderDate='".$orderDate."',
                  dispatched='".$dispatched."',
                  dispatchDate='".$dispatchedDate."'
            WHERE
                  orderId=$orderId
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


//this function is used to add order details   
public function addOrderDetails($insStr){
 
    $query="INSERT INTO 
                  inv_order_details (orderId,itemId,quantity)
            VALUES $insStr
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}        
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A CITY
//$cityId :cityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteOrderDetails($orderId) {
     
        $query = "DELETE 
                 FROM 
                      inv_order_details 
                 WHERE 
                      orderId=$orderId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function deleteOrder($orderId) {
     
        $query = "DELETE 
                 FROM 
                      inv_orders 
                 WHERE 
                      orderId=$orderId";
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
    
    public function getOrderList($conditions='', $limit = '', $orderBy=' io.orderNo') {
     
        $query = "SELECT 
                        io.orderId, io.orderNo, io.supplierId,
                        IF(io.dispatched=1,'Yes','No') AS dispatched,
                        io.dispatchDate,io.orderDate,
                        sup.supplierCode,
                        sup.companyName
                  FROM 
                        inv_orders io, supplier sup
                  WHERE 
                        io.supplierId=sup.supplierId
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
    public function getTotalOrder($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        inv_orders io, supplier sup
                  WHERE 
                        io.supplierId=sup.supplierId
                        $conditions ";
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getOrder($conditions='') {
     
        $query = "SELECT 
                        *
                  FROM 
                        inv_orders 
                        $conditions 
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
   //for populating during edit 
    public function getOrderDetails($conditions='') {
     
        $query = "SELECT 
                        io.orderId, io.orderNo, io.supplierId,io.orderDate,
                        io.dispatched,io.dispatchDate,
                        io.supplierId,
                        im.itemCode,im.itemName,
                        iod.quantity
                  FROM 
                        inv_orders io,  inv_order_details iod,items_master im
                  WHERE 
                        io.orderId=iod.orderId
                        AND iod.itemId=im.itemId
                        $conditions 
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function checkDuplicateOrderNo($orderNo) {
     
        $query = "SELECT 
                        COUNT(*) AS found
                  FROM 
                        inv_orders 
                  WHERE
                        orderNo='".$orderNo."' 
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }          
   
  
}
// $History: OrderManager.inc.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/09/09   Time: 10:56
//Updated in $/Leap/Source/Model/INVENTORY
//Corrected add/edit code during order no entry and corrected interface
//path in print file
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 10/09/09   Time: 15:24
//Updated in $/Leap/Source/Model/INVENTORY
//Corrected add query
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