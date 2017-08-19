<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "hostel_discipline_category" table
// Author :Gurkeerat Sidhu 
// Created on : (28.4.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class DisciplineManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "DisciplineManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (28.4.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "DisciplineManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (28.4.2009)
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
// THIS FUNCTION IS USED FOR ADDING A DISCIPLINECATEGORY
//
// Author :Gurkeerat Sidhu
// Created on : (28.4.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addDisciplineCategory() {
        global $REQUEST_DATA;
        
     $query="INSERT INTO hostel_discipline_category (categoryName) 
      VALUES('".addslashes($REQUEST_DATA['categoryName'])."')"; 
      
      return SystemDatabaseManager::getInstance()->executeUpdate($query);     
        
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING DISCIPLINE CATEGORY
//
//$id:cityId
// Author :Gurkeerat Sidhu
// Created on : (28.4.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editDisciplineCategory($id) {
        global $REQUEST_DATA;
        
     $query="UPDATE hostel_discipline_category SET categoryName ='".addslashes($REQUEST_DATA['categoryName'])."'
        WHERE   disciplineCategoryId=".$id;
       
       return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    } 


	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING DISCIPLINE CATEGORY LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (28.4.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getDisciplineCategory($conditions='') {
        $query = "SELECT * 
        FROM hostel_discipline_category
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING DISCIPLINE CATEGORY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu
// Created on : (28.4.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getDisciplineCategoryList($conditions='', $filter, $orderBy=' categoryName', $limit = '') {
     
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        
       $query = "	SELECT * 
					FROM hostel_discipline_category  
					$filter
			        ORDER BY $orderBy 
					$limit";
        //echo $query;
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF DISCIPLINE  CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (28.4.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalDisciplineCategory($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
       $query = "SELECT COUNT(*) AS totalRecords 
        FROM hostel_discipline_category 
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF DESCIPLINE CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (28.4.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function checkDisciplineCategory($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
      $query = "SELECT count(disciplineCategoryId) AS foundRecord 
        FROM  hostel_discipline_category 
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING DISCIPLINE CATEGORY
//
//$testtypeId :testTypeCategoryId  of testtypecategory
// Author :Gurkeerat Sidhu 
// Created on : (28.4.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteDisciplineCategory($id) {
     
        $query = "DELETE 
        FROM hostel_discipline_category
        WHERE disciplineCategoryId=$id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }


                          
}

?>
