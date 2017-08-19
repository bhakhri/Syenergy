<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "hostel_complaint_category" table
// Author :Gurkeerat Sidhu 
// Created on : (23.4.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ComplaintManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ComplaintManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (23.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ComplaintManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (23.4.2009)
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
// THIS FUNCTION IS USED FOR ADDING A COMPLAINTCATEGORY
//
// Author :Gurkeerat Sidhu
// Created on : (23.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addComplaintCategory() {
        global $REQUEST_DATA;
        
     $query="INSERT INTO hostel_complaint_category (categoryName) 
      VALUES('".addslashes($REQUEST_DATA['categoryName'])."')"; 
      
      return SystemDatabaseManager::getInstance()->executeUpdate($query);     
        
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING COMPLAINT CATEGORY
//
//$id:cityId
// Author :Gurkeerat Sidhu
// Created on : (23.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editComplaintCategory($id) {
        global $REQUEST_DATA;
        
     $query="UPDATE hostel_complaint_category SET categoryName ='".addslashes($REQUEST_DATA['categoryName'])."'
        WHERE   complaintCategoryId=".$id;
       
       return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    } 


	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING COMPLAINT CATEGORY LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (23.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getComplaintCategory($conditions='') {
        $query = "SELECT * 
        FROM hostel_complaint_category
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING COMPLAINT CATEGORY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu
// Created on : (23.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getComplaintCategoryList($conditions='', $filter, $orderBy=' categoryName', $limit = '') {
     
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        
       $query = "	SELECT * 
					FROM hostel_complaint_category  
					$filter
			        ORDER BY $orderBy 
					$limit";
        //echo $query;
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF COMPLAINT  CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (23.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalComplaintCategory($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
       $query = "SELECT COUNT(*) AS totalRecords 
        FROM hostel_complaint_category 
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF COMPLAINT CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (23.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function checkComplaintCategory($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
      $query = "SELECT count(complaintCategoryId) AS foundRecord 
        FROM  hostel_complaint_category 
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING COMPLAINTCATEGORY
//
//$testtypeId :testTypeCategoryId  of testtypecategory
// Author :Gurkeerat Sidhu 
// Created on : (23.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteComplaintCategory($id) {
     
        $query = "DELETE 
        FROM hostel_complaint_category
        WHERE complaintCategoryId=$id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF COMPLAINT CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (23.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function checkExistanceComplaintCategory($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
      $query = "SELECT count(complaintCategoryId) AS foundRecord 
        FROM  complaints
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


                          
}

?>
