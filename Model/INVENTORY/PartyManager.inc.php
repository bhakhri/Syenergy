<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "item_category" table
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class PartyManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ItemCategoryManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ItemCategoryManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
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
// THIS FUNCTION IS USED FOR ADDING A ITEMCATEGORY
//
// Author :Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addParty() {
        global $REQUEST_DATA;
        
     $query="INSERT INTO inv_party (partyName,partyCode,partyAddress,partyPhones,partyFax) 
      VALUES('".addslashes($REQUEST_DATA['partyName'])."','".addslashes($REQUEST_DATA['partyCode'])."','".addslashes($REQUEST_DATA['partyAddress'])."','".addslashes($REQUEST_DATA['partyPhones'])."','".addslashes($REQUEST_DATA['partyFax'])."')"; 
      
      return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);     
        
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING ITEM CATEGORY
//
//$id:itemCategoryId
// Author :Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editParty($id) {
        global $REQUEST_DATA;
        
     $query="	UPDATE	inv_party 
				SET		partyName ='".addslashes($REQUEST_DATA['partyName'])."',
						partyCode ='".addslashes($REQUEST_DATA['partyCode'])."',
						partyAddress ='".addslashes($REQUEST_DATA['partyAddress'])."',
						partyPhones ='".addslashes($REQUEST_DATA['partyPhones'])."',
						partyFax ='".addslashes($REQUEST_DATA['partyFax'])."'
				WHERE	partyId=".$id;
       
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
    } 


	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING ITEM CATEGORY LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getParty($conditions='') {
        $query = "	SELECT	* 
					FROM	inv_party
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getPartyList($conditions='',  $orderBy=' partyName', $limit = '') {
     
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        
       $query = "	SELECT		*
					FROM		inv_party
								$conditions
								ORDER BY $orderBy
								$limit";
        
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF ITEM  CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalParty($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
       $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	inv_party
							$conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING ITEM CATEGORY
//
//$Id :itemCategoryId
// Author :Gurkeerat Sidhu 
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteParty($id) {
     
        $query = "	DELETE 
					FROM	inv_party
					WHERE	partyId=$id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }



}

?>
