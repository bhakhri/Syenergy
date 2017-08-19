<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "item_category" table
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ItemCategoryManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ItemCategoryManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ItemCategoryManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
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
// THIS FUNCTION IS USED FOR ADDING A ITEMCATEGORY
//
// Author :Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addItemCategory() {
        global $REQUEST_DATA;
        
     $query="INSERT INTO item_category (categoryName,categoryCode,categoryType) 
      VALUES('".addslashes($REQUEST_DATA['categoryName'])."','".addslashes($REQUEST_DATA['categoryCode'])."', '".addslashes($REQUEST_DATA['categoryType'])."')"; 
      return SystemDatabaseManager::getInstance()->executeUpdate($query);     
        
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING ITEM CATEGORY
//
//$id:itemCategoryId
// Author :Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editItemCategory($id) {
        global $REQUEST_DATA;
        
     $query="	UPDATE	item_category 
				SET		categoryName ='".addslashes($REQUEST_DATA['categoryName'])."',
				categoryCode ='".addslashes($REQUEST_DATA['categoryCode'])."',
				categoryType ='".addslashes($REQUEST_DATA['categoryType'])."'
        WHERE   itemCategoryId=".$id;
       
       return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    } 


	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING ITEM CATEGORY LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getItemCategory($conditions='') {
        $query = "	SELECT	* 
					FROM	item_category
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING ITEM CATEGORY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getItemCategoryList($conditions='',  $orderBy=' categoryName', $limit = '') {
     
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        
       $query = "	SELECT		*
					FROM		item_category
								$conditions
								ORDER BY $orderBy
								$limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING ITEM CATEGORY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (16.03.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getItemList($itemCategory) {
     
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        
       $query = "	SELECT	itemName,
							availableQty
					FROM	items_master
					WHERE	itemCategoryId = ".$itemCategory."
							";
        
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF ITEM  CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalItemCategory($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
       $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	item_category
							$conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF ITEM CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function checkItemCategory($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
      $query = "SELECT count(itemCategoryId) AS foundRecord 
        FROM  item_category 
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS itemCategoryId EXISTS IN "items_master" TABLE OR NOT(DELETE CHECK)
//
//$itemCategoryId :itemCategoryId of the item_category
// Author :Gurkeerat Sidhu 
// Created on : (18.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInItemsMaster($itemCategoryId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM items_master 
        WHERE itemCategoryId=$itemCategoryId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING ITEM CATEGORY
//
//$Id :itemCategoryId
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteItemCategory($id) {
     
        $query = "DELETE 
        FROM item_category
        WHERE itemCategoryId=$id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }



}

?>
