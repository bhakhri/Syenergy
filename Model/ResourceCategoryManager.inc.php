<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "resource_category" table
// Author :Gurkeerat Sidhu 
// Created on : (20.5.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ResourceCategoryManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ResourceManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (20.5.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ResourceManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (20.5.2009)
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
// THIS FUNCTION IS USED FOR ADDING A Resource CATEGORY
//
// Author :Gurkeerat Sidhu
// Created on : (20.5.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addResourceCategory() {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query="INSERT INTO 
                        resource_category (resourceName,instituteId) 
                VALUES('".add_slashes(trim($REQUEST_DATA['resourceName']))."',$instituteId)"; 
      
      return SystemDatabaseManager::getInstance()->executeUpdate($query);     
        
    }

    //-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING resource CATEGORY
//
//$id:resourceTypeId
// Author :Gurkeerat Sidhu
// Created on : (20.5.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editResourceCategory($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
       $query="UPDATE 
                     resource_category 
               SET 
                     resourceName ='".add_slashes(trim($REQUEST_DATA['resourceName']))."',
                     instituteId=$instituteId
              WHERE  
                     resourceTypeId=".$id;
       
       return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    } 


    //-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING resource CATEGORY LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (20.5.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getResourceCategory($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                         * 
                  FROM 
                         resource_category
                         $conditions
                         AND instituteId=$instituteId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Resource CATEGORY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu
// Created on : (20.5.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getResourceCategoryList($conditions='',$orderBy=' resourceName',$limit = '') {
     
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                       * 
                 FROM 
                       resource_category
                 WHERE
                       instituteId=$instituteId
                       $conditions
                 ORDER BY $orderBy 
                 $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

    //---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Resource  CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (20.5.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalResourceCategory($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
                  
       $query = "SELECT 
                        COUNT(*) AS totalRecords 
                 FROM 
                        resource_category
                 WHERE
                        instituteId=$instituteId 
                        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Resource CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (20.5.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function checkResourceCategory($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
      $query = "SELECT 
                      COUNT(resourceTypeId) AS totalRecords 
               FROM  
                      resource_category 
                      $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING Resource CATEGORY
//
//$d :resourceTypeId  
// Author :Gurkeerat Sidhu 
// Created on : (28.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteResourceCategory($id) {
     
        $query = "DELETE 
                  FROM 
                        resource_category
                  WHERE 
                        resourceTypeId=$id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CHECKING COURSE RESOURCE EXISTANCE
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (23.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getResourceCategoryExistance($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
      $query = "	SELECT	
                            COUNT(*) AS totalRecords 
					FROM	
                            course_resources cr 
							$conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
                          
}

// $History: ResourceCategoryManager.inc.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 1/22/10    Time: 3:40p
//Updated in $/LeapCC/Model
//added check for instituteId in getResourceCategory function
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 24/10/09   Time: 13:19
//Updated in $/LeapCC/Model
//Done bug fixing.
//Bug ids---
//00001884
?>