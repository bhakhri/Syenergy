<?php 
//-------------------------------------------------------
//  This File contains Bussiness Logic of the Fee Concession Master
// Author :Parveen Sharma
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeeConcessionManager {
	private static $instance = null;
	
	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
	public function addFeeConcession() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('fee_concession_category', 
                    array('categoryName','categoryOrder','categoryDescription'), 
                    array(strtoupper($REQUEST_DATA['categoryName']),$REQUEST_DATA['categoryOrder'],$REQUEST_DATA['categoryDescription']));
	}

    public function editFeeConcession($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('fee_concession_category', 
                    array('categoryName','categoryOrder','categoryDescription'),
                    array(strtoupper($REQUEST_DATA['categoryName']),$REQUEST_DATA['categoryOrder'],$REQUEST_DATA['categoryDescription']), "categoryId=$id" );  
    }    

    public function getFeeConcession($conditions='') {
     
         $query = "SELECT 
                        categoryId, categoryName,categoryOrder, IFNULL(categoryDescription,'') AS categoryDescription 
                   FROM 
                        fee_concession_category
                   $conditions";
	
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    //Gets the country table fields
      
    public function getFeeConcessionList($conditions='', $limit = '', $orderBy=' categoryName') {
     
        $query = "SELECT 
                        categoryId, categoryName, categoryOrder, 
                        IF(IFNULL(categoryDescription,'')='','".NOT_APPLICABLE_STRING."',categoryDescription) AS categoryDescription  
                  FROM 
                        fee_concession_category  
                  $conditions                   
                  ORDER BY 
                        $orderBy $limit";
             
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
 
   // checks dependency constraint
  /*  public function checkInState($countryId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM states 
        WHERE countryId=$countryId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }*/
    public function getTotalFeeConcession($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords FROM fee_concession_category  $conditions ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    // deletes the country
    public function deleteFeeConcession($categoryId) {
     
        $query = "DELETE FROM  fee_concession_category WHERE categoryId=$categoryId";
        
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }   
}
?>