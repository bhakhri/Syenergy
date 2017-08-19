<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (01.09.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ReceiveManager {
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
    
    

//this function is used to add receive order
public function addReceiveOrder($orderId,$receiveDate,$userId,$totalAmount,$taxAmount,$updateStore,$remarks=''){
    
    $query="INSERT INTO 
                  inv_receive_orders(orderId,stockUpdated,receiveDate,receivedByUserId,totalAmount,taxAmount,remarks)
            VALUES($orderId,$updateStore,'$receiveDate',$userId,$totalAmount,$taxAmount,'$remarks')
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}



//this function is used to add receive order details   
public function addReceiveOrderDetails($insStr){
 
    $query="INSERT INTO 
                  inv_receive_order_details (orderId,itemId,itemPrice,quantityOrdered,quantityReceived)
            VALUES $insStr
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}        
    

//this function is used to delete receive order details       
    public function deleteReceiveOrderDetails($orderId) {
     
        $query = "DELETE 
                 FROM 
                      inv_receive_order_details 
                 WHERE 
                      orderId=$orderId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
//this function is used to delete receive orders     
    public function deleteReceiveOrder($orderId) {
     
        $query = "DELETE 
                  FROM 
                      inv_receive_orders 
                  WHERE 
                      orderId=$orderId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
//this function is used to update items's availableQty 
    public function updateAvailableQuantityOfItem($itemId,$newQuantity) {
     
        $query = "UPDATE 
                         items_master
                  SET
                         availableQty=availableQty+$newQuantity
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
    
    public function getOrderReceivedList($conditions='', $limit = '', $orderBy=' io.orderNo') {
     
        $query = "SELECT 
                        io.orderId, io.orderNo,
                        io.dispatchDate,io.orderDate,
                        sup.supplierCode,
                        sup.companyName,
                        ior.receiveDate,
                        ior.stockUpdated
                  FROM 
                        inv_orders io, supplier sup,inv_receive_orders ior
                  WHERE 
                        io.supplierId=sup.supplierId
                        AND io.orderId=ior.orderId
                        AND io.dispatched=1
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
    public function getTotalOrderReceived($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        inv_orders io, supplier sup,inv_receive_orders ior
                  WHERE 
                        io.supplierId=sup.supplierId
                        AND io.orderId=ior.orderId
                        AND io.dispatched=1
                        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
   //for populating during add
    public function getOrderDetails($conditions='') {
     
        $query = "SELECT 
                        io.orderId, io.orderNo, io.supplierId,io.orderDate,
                        io.dispatchDate,
                        io.dispatchDate AS dispatchDate1,
                        sup.supplierCode,
                        im.itemCode,im.itemName,
                        iod.quantity
                  FROM 
                        inv_orders io,inv_order_details iod,items_master im,supplier sup
                  WHERE 
                        io.orderId=iod.orderId
                        AND iod.itemId=im.itemId
                        AND io.dispatched=1
                        AND io.supplierId=sup.supplierId
                        AND io.orderId NOT IN (SELECT DISTINCT orderId FROM inv_receive_orders)
                        $conditions
                        ORDER BY  iod.itemId 
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

   //for populating during edit
    public function getReceiveDetails($conditions='') {
     
       $query = "SELECT 
                        io.orderId, io.orderNo, io.orderDate,
                        io.dispatchDate,
                        io.dispatchDate AS dispatchDate1,
                        sup.supplierCode,
                        im.itemCode,
                        ird.receiveDate,
                        ird.remarks,
                        ird.taxAmount,ird.totalAmount,
                        ior.quantityOrdered,ior.quantityReceived,ior.itemPrice
                      FROM 
                        inv_orders io, items_master im,supplier sup,
                        inv_receive_orders ird,
                        inv_receive_order_details ior
                      WHERE 
                        ior.itemId=im.itemId
                        AND io.dispatched=1
                        AND io.supplierId=sup.supplierId
                        AND io.orderId=ird.orderId
                        AND io.orderId=ior.orderId
                        $conditions
                        ORDER BY ior.itemId 
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //for checking during data add / edit
    public function getOrderDetailIds($conditions='') {
     
        $query = "SELECT 
                        io.orderId,
                        io.dispatchDate,
                        iod.itemId,iod.quantity
                  FROM 
                        inv_orders io,  inv_order_details iod
                  WHERE 
                        io.orderId=iod.orderId
                        AND io.dispatched=1
                        $conditions 
                        ORDER BY  iod.itemId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //for checking during data add / edit
    public function checkItemIds($itemIds) {
     
        $query = "SELECT 
                        i.itemId
                  FROM 
                        items_master i
                  WHERE 
                        i.itemId IN ($itemIds)
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //for checking during data add / edit
    public function checkStockUpdated($orderId) {
     
        $query = "SELECT 
                        stockUpdated
                  FROM 
                        inv_receive_orders 
                  WHERE 
                        orderId =$orderId 
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }         
   
  
}
// $History: ReceiveManager.inc.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/09/09    Time: 15:14
//Updated in $/Leap/Source/Model/INVENTORY
//Updated "Order Receive Master"----Added "update stock" field and added
//the code : if update stock option is yes then main item master table is
//also updated
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/09/09    Time: 15:31
//Updated in $/Leap/Source/Model/INVENTORY
//Updated "Receive Order Master" : Added tax ,total amount and item price
//amount fields
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 5/09/09    Time: 16:53
//Created in $/Leap/Source/Model/INVENTORY
//Created module "Order Receive Master"
?>