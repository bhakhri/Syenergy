<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "supplier" table
// Author :Gurkeerat Sidhu 
// Created on : (06.05.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class SupplierManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "SupplierManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (06.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "SupplierManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (06.05.2009)
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
// THIS FUNCTION IS USED FOR ADDING A SUPPLIER
//
// Author :Gurkeerat Sidhu
// Created on : (06.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addSupplier() {
        global $REQUEST_DATA;
        
     $query="INSERT INTO supplier (companyName,supplierCode,address,countryId,stateId,cityId,contactPerson,contactPersonPhone,companyPhone) 
      VALUES('".addslashes($REQUEST_DATA['companyName'])."','".addslashes(strtoupper($REQUEST_DATA['supplierCode']))."','".addslashes($REQUEST_DATA['address'])."',
      '".$REQUEST_DATA['countryId']."','".$REQUEST_DATA['stateId']."',
      '".$REQUEST_DATA['cityId']."','".addslashes($REQUEST_DATA['contactPerson'])."',
      '".addslashes($REQUEST_DATA['contactPersonPhone'])."','".addslashes($REQUEST_DATA['companyPhone'])."')"; 
      
      return SystemDatabaseManager::getInstance()->executeUpdate($query);     
        
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING SUPPLIER
//
//$id:supplierId
// Author :Gurkeerat Sidhu
// Created on : (06.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editSupplier($id) {
        global $REQUEST_DATA;
        
     $query="UPDATE supplier SET companyName ='".addslashes($REQUEST_DATA['companyName'])."',supplierCode ='".addslashes(strtoupper($REQUEST_DATA['supplierCode']))."',address ='".addslashes($REQUEST_DATA['address'])."'
     ,countryId ='".$REQUEST_DATA['countryId']."',stateId ='".$REQUEST_DATA['stateId']."',cityId ='".$REQUEST_DATA['cityId']."'
     ,contactPerson ='".addslashes($REQUEST_DATA['contactPerson'])."',contactPersonPhone ='".addslashes($REQUEST_DATA['contactPersonPhone'])."',
     companyPhone ='".addslashes($REQUEST_DATA['companyPhone'])."'
        WHERE   supplierId=".$id;
       
       return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    } 


	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING SUPPLIER LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (06.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getSupplier($conditions='') {
        $query = "SELECT 
                          s.supplierId,s.companyName,s.supplierCode,s.address,cn.countryId,st.stateId,ct.cityId,s.contactPerson,s.contactPersonPhone,s.companyPhone,st.stateName,ct.cityName,cn.countryName 
                    FROM supplier s ,states st ,city ct ,countries cn
                    WHERE
                          s.stateId = st.stateId 
                    AND   s.cityId  = ct.cityId 
                    AND   s.countryId = cn.countryId
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING SUPPLIER LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu
// Created on : (06.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getSupplierList($conditions='', $orderBy=' companyName', $limit = '') {
     
        
        
       $query = "	SELECT 
                          s.supplierId,
                          s.companyName,s.supplierCode,s.address,cn.countryId,st.stateId,ct.cityId,s.contactPerson,s.contactPersonPhone,s.companyPhone,st.stateName,ct.cityName,cn.countryName 
					FROM supplier s ,states st ,city ct ,countries cn
                    WHERE
                          s.stateId = st.stateId 
                    AND   s.cityId  = ct.cityId 
                    AND   s.countryId = cn.countryId  
					$conditions
			        ORDER BY $orderBy 
					$limit";
        
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    //-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING SUPPLIER LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu
// Created on : (06.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getTotalSupplier($conditions='') {
     
        
        
       $query = "    SELECT 
                          COUNT(*) AS totalRecords
                    FROM supplier s ,states st ,city ct ,countries cn
                    WHERE
                          s.stateId = st.stateId 
                    AND   s.cityId  = ct.cityId 
                    AND   s.countryId = cn.countryId  
                    $conditions
                    ";
        
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF SUPPLIERS
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (06.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getSupplierCategory($conditions='') {
         
        
                  
       $query = "SELECT COUNT(*) AS totalRecords 
        FROM supplier 
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF SUPPLIERS
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (06.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function checkSupplier($conditions='') {
         
        
                  
      $query = "SELECT count(supplierId) AS foundRecord 
        FROM  supplier 
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS supplierId EXISTS IN "item_suppliers" TABLE OR NOT(DELETE CHECK)
//
//$supplierId :supplierId of the supplier
// Author :Gurkeerat Sidhu 
// Created on : (18.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInItemSupplier($supplierId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM item_suppliers 
        WHERE supplierId=$supplierId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    

	//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING SUPPLIER
//
//$supplierId :supplierId  of supplier
// Author :Gurkeerat Sidhu 
// Created on : (06.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteSupplier($id) {
     
        $query = "DELETE 
        FROM supplier
        WHERE supplierId=$id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

 


}

?>
