<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the Country Module
//
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

//Author : Arvind Singh Rawat
//updated on 25-06-2008 
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class publicationManager {
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
	public function addPublication() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('publication_type', array('publicationName'), array($REQUEST_DATA['publicationName']));
	}
    public function editPublication($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('publication_type', array('publicationName'), array($REQUEST_DATA['publicationName']),"publicationId=$id" );
    }    
    public function getPublication($conditions='') {
     
        $query = "SELECT  
	     				publicationId,
    	                publicationName
                    FROM
							publication_type

        $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    //Gets the country table fields
      
    public function getPublicationList($conditions='', $limit = '', $orderBy=' PublicationName') {
     
        $query = "SELECT 
							publicationId, 
							publicationName
		          FROM  
			                publication_type  
        $conditions                   
        ORDER BY $orderBy $limit";
             
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
      
    public function getTotalPublication($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM publication_type
        $conditions ";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    // deletes the country
     public function deletePublication($publicationId) {
     
        $query = "DELETE 
        FROM publication_type 
        WHERE publicationId=$publicationId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }   
}
?>

<?php 