<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
//
//
// Author :Jaineesh
// Created on : (27.07.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ItemsManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ItemsManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ItemsManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
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
// THIS FUNCTION IS USED FOR ADDING A CITY
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addItem() {
		global $REQUEST_DATA;

        $query="	INSERT INTO 
					items_master ( itemCategoryId,itemName,itemCode,reOrderLevel,units )
                VALUES
                      (
                      '".trim($REQUEST_DATA['itemCategoryId'])."',
                      '".add_slashes($REQUEST_DATA['itemName'])."',
                      '".add_slashes(trim($REQUEST_DATA['itemCode']))."',
					  '".add_slashes(trim($REQUEST_DATA['reorderLevel']))."',
                      '".trim($REQUEST_DATA['unit'])."'
                      )";
        return SystemDatabaseManager::getInstance()->executeUpdateinTransaction($query);
	}




//function to update itemdescription
public function updateItemDescription($filter='',$itemCategoryId='',$itemName='',$itemCode='',$reOrderLevel='',$units='',$condition='') {
		global $REQUEST_DATA;

        $query="$filter items_master  
		SET 	
                    itemCategoryId = '$itemCategoryId',
                    itemName = '$itemName' ,
                    itemCode = '$itemCode' ,
                    reOrderLevel = '$reOrderLevel' ,
                     units= '$units'
		$condition"; 
                                 
                 
        return SystemDatabaseManager::getInstance()->executeUpdateinTransaction($query);
	}



//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A CITY
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editItem($id,$conditions='') {
        global $REQUEST_DATA;
     
        //itemCode='".add_slashes(trim($REQUEST_DATA['itemCode']))."',
        $query	=	"	UPDATE	items_master 
						SET  
								itemCategoryId='".trim($REQUEST_DATA['itemCategoryId'])."',
								itemName='".add_slashes(trim($REQUEST_DATA['itemName']))."',
								itemCode='".add_slashes(trim($REQUEST_DATA['itemCode']))."',
								reorderLevel='".add_slashes(trim($REQUEST_DATA['reorderLevel']))."',
								units='".add_slashes(trim($REQUEST_DATA['unit']))."'
						WHERE
								itemId=$id";
        return SystemDatabaseManager::getInstance()->executeUpdateinTransaction($query);
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getItem($conditions='') {
     
     $query = "	SELECT 
                        im.itemId ,im.itemCategoryId ,im.itemName , im.itemCode,im.reOrderLevel ,im.units ,
						ic.categoryName,
						ic.categoryCode
				FROM 
                        items_master im,
						item_category ic
				WHERE	im.itemCategoryId = ic.itemCategoryId
				        $conditions";
	
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    


    public function getItemNew($conditions='') {
     
     $query = "	SELECT 
                        im.itemId ,im.itemCategoryId ,im.itemName , im.itemCode,im.reOrderLevel,im.units,ic.categoryName,ic.categoryCode,
              IFNULL(its.itemCategoryId,'-1') AS   stockCategoryId
	        FROM 
                     item_category ic,items_master im 
                LEFT JOIN item_stock its ON im.itemId = its.itemId AND im.itemCategoryId = its.itemCategoryId
	        WHERE	   im.itemCategoryId = ic.itemCategoryId
				        $conditions";
	
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING An Item
//
//$itemId :itemId of the items_master
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteItem($itemId) {
     
        $query = "DELETE 
                  FROM 
                        items_master
                  WHERE 
                        itemId=$itemId";
        return SystemDatabaseManager::getInstance()->executeUpdateinTransaction($query,"Query: $query");
    }
    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getItemList($conditions='', $limit = '', $orderBy=' itemCode') {
     
        $query = "	SELECT 
								im.*,
								ic.categoryName,
								ic.categoryCode
						FROM 
								items_master im,
								item_category ic
						WHERE	im.itemCategoryId = ic.itemCategoryId
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
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalItem($conditions='') {
    
        $query = "		SELECT 
								COUNT(im.itemId) AS totalRecords 
						FROM 
								items_master im,
								item_category ic
						WHERE	im.itemCategoryId = ic.itemCategoryId 
								$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
   //this function is used to generate new ItemCodes 
   public function generateItemCode(){
       
       $str="SELECT IFNULL(MAX(ABS(SUBSTRING(itemCode,length('".ITEM_CODE_PREFIX."' ) +1, LENGTH(itemCode) ) ) ),0)+1 AS itemCode FROM items_master ";
       $iCode=SystemDatabaseManager::getInstance()->executeQuery($str,"Query: $str");
        
        //generate new itemCode code
       $gCode=ITEM_CODE_PREFIX.str_pad($iCode[0]['itemCode'],abs(ITEM_CODE_LENGTH-strlen(ITEM_CODE_PREFIX)-strlen($iCode[0]['itemCode']))+1,'0',STR_PAD_LEFT);
       return $gCode;
   }
   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu 
// Created on : (10.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getItemReorderList($conditions='WHERE availableQty <= minimumQty', $limit = '', $orderBy=' itemCode') {
     
        $query = "SELECT 
                        itemId,itemName,itemCode,unitOfMeasure,availableQty,minimumQty,itemDesc
                  FROM 
                        items_master 
                   $conditions
                  ORDER BY $orderBy 
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    
  //-------------------------------------------------------------------------------------------------------
// THIS FUNCTION will fetch list of suppliers
// Author :Jaineesh
// Created on : (17.03.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------             
    public function getItemDetail($conditions='') {
     
        $query = "	SELECT
							count(*) AS totalRecords
					FROM
							issue_items
							$conditions
                        ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
  }

  //-----------------------------------------------------------------------------------------------
// THIS FUNCTION will fetch list of suppliers
// Author :Jaineesh
// Created on : (17.03.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------------             
   public function getCategory($conditions='') {
     
        $query = "	SELECT
							*
					FROM
							item_category
							$conditions
                        ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
  }


  //-----------------------------------------------------------------------------------------------
// THIS FUNCTION will fetch list of suppliers
// Author :Jaineesh
// Created on : (17.03.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------------             
    public function getParticularItem($conditions='') {
     
        $query = "	SELECT 
							im.itemId,
							im.itemName,
							im.itemCode,
							(SELECT itst.quantity FROM item_stock itst WHERE im.itemId = itst.itemId AND itst.type=0) AS qty
					FROM	items_master im	
							$conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-----------------------------------------------------------------------------------------------
// THIS FUNCTION will fetch list of suppliers
// Author :Jaineesh
// Created on : (17.03.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------------             
    public function getParticularItemData($conditions='') {
     
        $query = "	SELECT 
							im.itemId,
							im.itemName,
							im.itemCode
					FROM	items_master im	
							$conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A CITY
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addOpeningStock($str) {
		global $REQUEST_DATA;

      $query=	"	INSERT INTO item_stock (itemCategoryId,itemId,date,quantity,type)
					VALUES $str";

        return SystemDatabaseManager::getInstance()->executeUpdateinTransaction($query);
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING AN ITEM IN STOCK
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addTotalItemStock($val) {
	
      $query =	"	INSERT INTO
									inv_item_balance(itemCategoryId,itemId,balance,sessionId)
					VALUES 
									$val
				";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING An Item
//
//$itemId :itemId of the items_master
// Author :Jaineesh
// Created on : (16 Aug 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteStock($itemCategoryId) {
     
        $query = "	DELETE 
					FROM 
							item_stock
					WHERE 
							itemCategoryId = $itemCategoryId
					AND		type = 0;
				";
		/*echo $query;
		die;*/
        return SystemDatabaseManager::getInstance()->executeUpdateinTransaction($query,"Query: $query");
    }

}
// $History: $
//
?>
