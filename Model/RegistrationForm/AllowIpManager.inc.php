<?php 
//-------------------------------------------------------
//  This File contains Bussiness Logic of the blockstudent Module
//
// Author :Abhay Kant
// Created on : 22-June-2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');


class AllowIpManager {
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

	public function addIp($str) {
	
	   global $sessionHandler;	
	
  	   $query="INSERT INTO allow_ip_address
		       (allowIPNo) 
		       VALUES 
		       $str "; 
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

    public function getCheckIPList($str) {
    
       global $sessionHandler;    
       
       if($str=='') {
         $str='0';  
       }
    
       $query="SELECT allowIPNo FROM allow_ip_address WHERE allowIPNo IN ($str) "; 
         
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
    }
    
	public function getStudent($conditions='') {
 	
		  $query="SELECT 
				        DISTINCT 
                            a.studentId, 
                            IFNULL(a.fatherName,'')  AS fatherName, 
                            CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) AS studentName,
                            IFNULL(a.fatherEmail,'') AS fatherEmail, 
                            IFNULL(a.studentEmail,'') AS studentEmail   	
			      FROM 
				       sc_student a
			      $conditions";

     		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getIpList($conditions='', $limit = '', $orderBy='rollNo') {
 	
		  $query="SELECT 
				        allowIPId,allowIPNo    
			      FROM 
				        allow_ip_address
			            $conditions 
			      ORDER BY 
                        $orderBy $limit ";
	
     		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
		 

	public function getTotalIp($conditions='') {
 	
		  $query="SELECT 
				        COUNT(*) AS totalRecords
                  FROM      	
			          allow_ip_address $conditions";
			
     		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	 
    public function deleteIp($AllowIpId) {
        
        $query = "DELETE FROM allow_ip_address WHERE allowIPId IN ($AllowIpId) ";
        
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
}
?>
