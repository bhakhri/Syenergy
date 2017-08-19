<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Document" table
// Author :Jaineesh 
// Created on : (28.02.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class PublishingManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "TestTypeManager" CLASS
//
// Author :Jaineesh
// Created on : (28.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "DocumentManager" CLASS
//
// Author :Jaineesh 
// Created on : (28.02.2008)
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
// THIS FUNCTION IS USED FOR ADDING A DOCUMENT
//
// Author :Jaineesh 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addPublishing($employeeId) {
        global $REQUEST_DATA;
        
      $query="INSERT INTO publishing (type,publishOn,publishedBy,description,employeeId) 
      VALUES('".$REQUEST_DATA['type']."','".$REQUEST_DATA['publishOn']."','".$REQUEST_DATA['publishedBy']."','".$REQUEST_DATA['description']."','".$employeeId."')";
      
      return SystemDatabaseManager::getInstance()->executeUpdate($query);     
        
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A DOCUMENT
//
//$id:documentId
// Author :Jaineesh 
// Created on : (28.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editPublishing($id) {
        global $REQUEST_DATA;
        
        $query="UPDATE publishing SET	type ='".$REQUEST_DATA['type']."',
									publishOn ='".$REQUEST_DATA['publishOn']."',
									publishedBy = '".$REQUEST_DATA['publishedBy']."',
									description = '".$REQUEST_DATA['description']."'
		        WHERE publishId=".$id;
       
       return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    } 

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Document
//
//$publishId :publishId   of document
// Author :Jaineesh 
// Created on : (05.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deletePublishing($Id) {
     
        $query = "DELETE 
        FROM publishing
        WHERE publishId='$Id'";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

 
	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING DOCUMENT
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (05.3.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getPublishing($conditions='') {
        
      $query = "	SELECT
                        	p.publishId,  p.type,  p.publishOn,  p.publishedBy, p.description,
                            e.employeeName,  e.employeeCode, d.designationName, e.employeeId
					FROM	
                            publishing p, 
							employee e LEFT JOIN designation d ON e.designationId = d.designationId
					WHERE	
                            p.employeeId = e.employeeId 
                    $conditions ";
                    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TESTTYPE CATEGORY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (19.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getPublishingList($filter='', $orderBy='',$limit = '') {
     
        $query = "	SELECT 
							p.publishId,
							p.type,
							e.employeeName,
                            e.employeeCode,
							p.publishOn,
							p.publishedBy,
                            p.description
					FROM	publishing p,
							employee e
					WHERE	p.employeeId = e.employeeId
							$filter
							ORDER BY $orderBy 
							$limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF DOCUMENT
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalPublishing($filter='') {
         
       $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	publishing p,
							employee e
					WHERE	p.employeeId = e.employeeId
							$filter  "; 
                            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EXISTANCE OF DOCUMENT IN ANOTHER MODULE
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getEmployee($code='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
      $query = "SELECT	employeeId,
						employeeCode,
						employeeName
				FROM	employee
				WHERE	employee.employeeCode = '$code'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE DETAIL 
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (04.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getEmployeeDetail($conditions='') {
        
        global $sessionHandler;
        
        $query = " SELECT	emp.employeeId,
							emp.employeeCode,
							emp.employeeName,
							desg.designationName
					FROM	employee emp LEFT JOIN designation desg ON emp.designationId = desg.designationId
					$conditions ";
                            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }       
}
// $History: PublishingManager.inc.php $      
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 12:15p
//Created in $/LeapCC/Model
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:14p
//Created in $/Leap/Source/Model
//initial checkin 
//

?>
