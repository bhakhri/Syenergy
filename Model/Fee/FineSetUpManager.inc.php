<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Collect Fees Manager" TABLE
// Author :NIshu Bindal
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FineSetUpManager {
	private static $instance = null;
	
//----------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "QuotaManager" CLASS
//------------------------------------------------------------------------
	private function __construct() {
	}

//----------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "QuotaManager" CLASS

//------------------------------------------------------------------------    
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}


   public function deletePreviousFineDetail($classId='',$fineTypeId=''){
       
 	   $query = "DELETE  FROM `fee_fine_new`WHERE classId IN ($classId) AND feeFineTypeId = '$fineTypeId'";

       return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
	}

	public function addFineSetUpDetail($strValue=''){
	
        $query = "INSERT INTO `fee_fine_new`
                  (classId, feeFineTypeId, fromDate,toDate,chargesFormat,charges)
		          VALUES
                  $strValue";
	
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
	}

	public function getFineSetUpList($condition='',$orderBy='',$limit=''){
	
        $query = "SELECT 
                        fn.classId,fn.fromDate,fn.toDate,fn.charges,fn.feeFineId,
                        IF(fn.feeFineTypeId=1,'Academic',IF(fn.feeFineTypeId=2,'Transport','Hostel')) As feeFineTypeId,
                        IF(fn.chargesFormat=1,'Daily','Fixed') As chargesFormat,c.className
		           FROM 
			           `fee_fine_new` fn,class c
		           WHERE 
                        c.classId=fn.classId		
	               $condition
		           ORDER BY
		                fn.fromDate ASC";
		
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

    public function fetchClases($condition = '') {
    	
        $query ="SELECT
                    DISTINCT c.classId,c.className
    	    	 FROM
                 	`class` c
    		      $condition
    		      ORDER BY c.className Asc";
    		
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function deleteFineDetail($feeFineId = ''){
    	
        $query ="DELETE FROM  `fee_fine_new` WHERE  feeFineId ='$feeFineId'";
    		
    	return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
}
?>
