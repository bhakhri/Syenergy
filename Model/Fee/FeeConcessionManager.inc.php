<?php 
//-------------------------------------------------------
//  This File contains Bussiness Logic of the Fee Concession Master
// Author :Nishu Bindal
// Created on : 4-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
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
		global $sessionHandler;
        	$instituteId= $sessionHandler->getSessionVariable('InstituteId');
        	$sessionId= $sessionHandler->getSessionVariable('SessionId');
		$categoryName = addslashes(strtoupper(trim($REQUEST_DATA['categoryName'])));
		$categoryOrder = trim($REQUEST_DATA['categoryOrder']);
		$categoryDesc = addslashes(trim($REQUEST_DATA['categoryDescription']));
		
		$query ="INSERT INTO `fee_concession_category_new` (categoryId,categoryName,categoryOrder,categoryDescription,institueId,sessionId) 
					VALUES('','$categoryName','$categoryOrder','$categoryDesc','$instituteId','$sessionId')";
					

		return SystemDatabaseManager::getInstance()->executeUpdate($query);
	}

	public function editFeeConcession($id) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$categoryName = add_slashes(strtoupper(trim($REQUEST_DATA['categoryName'])));
		$categoryOrder = add_slashes(trim($REQUEST_DATA['categoryOrder']));
		$categoryDescription = add_slashes(trim($REQUEST_DATA['categoryDescription']));

		$query ="UPDATE `fee_concession_category_new` 
				SET	categoryName	= '$categoryName',
					categoryOrder	= '$categoryOrder',
					categoryDescription = '$categoryDescription'
				WHERE	categoryId = $id";
				
		return SystemDatabaseManager::getInstance()->executeUpdate($query);
	}    

    public function getFeeConcession($conditions='') {
    	global $sessionHandler;
        $instituteId= $sessionHandler->getSessionVariable('InstituteId');
        $sessionId= $sessionHandler->getSessionVariable('SessionId');
     
         $query = "SELECT 
                        categoryId, categoryName,categoryOrder, IFNULL(categoryDescription,'') AS categoryDescription 
                   FROM 
                        `fee_concession_category_new`
                   WHERE	institueId = '$instituteId'
                   AND		sessionId ='$sessionId'
                   $conditions";
	
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    //Gets the country table fields
      
    public function getFeeConcessionList($conditions='', $limit = '', $orderBy=' categoryName') {
	global $sessionHandler;
	$instituteId= $sessionHandler->getSessionVariable('InstituteId');
	$sessionId= $sessionHandler->getSessionVariable('SessionId');
     
        $query = "SELECT 
                        categoryId, categoryName, categoryOrder, 
                        IF(IFNULL(categoryDescription,'')='','".NOT_APPLICABLE_STRING."',categoryDescription) AS categoryDescription  
                  FROM 
                        fee_concession_category_new  
                  WHERE	institueId = '$instituteId'
                  AND	sessionId = '$sessionId'
                  $conditions                   
                  ORDER BY 
                        $orderBy $limit";
          
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
 
   // checks dependency constraint

    public function getTotalFeeConcession($conditions='') {
    	global $sessionHandler;
	$instituteId= $sessionHandler->getSessionVariable('InstituteId');
	$sessionId= $sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT	COUNT(categoryId) AS totalRecords 
        		FROM	`fee_concession_category_new`
        		WHERE	institueId = '$instituteId'
                  	AND	sessionId = '$sessionId'
        	$conditions ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    // deletes the country
    public function deleteFeeConcession($categoryId) {
     
        $query = "DELETE FROM  fee_concession_category WHERE categoryId=$categoryId";
        
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }   
}
?>
