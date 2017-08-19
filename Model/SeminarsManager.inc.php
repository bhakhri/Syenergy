<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Document" table
// Author :Jaineesh 
// Created on : (28.02.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class SeminarsManager {
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
    public function addSeminars($employeeId) {
        global $REQUEST_DATA;
        
      $query="INSERT INTO seminar (organisedBy,topic,description,startDate,endDate,seminarPlace,employeeId) 
      VALUES('".$REQUEST_DATA['seminarOrganisedBy']."','".$REQUEST_DATA['seminarTopic']."','".$REQUEST_DATA['seminarDescription']."','".$REQUEST_DATA['startDate']."','".$REQUEST_DATA['endDate']."','".$REQUEST_DATA['seminarPlace']."','".$employeeId."')";
      
      return SystemDatabaseManager::getInstance()->executeUpdate($query);     
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A DOCUMENT
//
// $id:documentId
// Author :Jaineesh 
// Created on : (28.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editSeminars($id) {
        global $REQUEST_DATA;
        
        $query="UPDATE seminar SET	organisedBy ='".$REQUEST_DATA['seminarOrganisedBy']."',
									topic ='".$REQUEST_DATA['seminarTopic']."',
									description = '".$REQUEST_DATA['seminarDescription']."',
									startDate = '".$REQUEST_DATA['startDate']."',
                                    endDate = '".$REQUEST_DATA['endDate']."',
                                    seminarPlace = '".$REQUEST_DATA['seminarPlace']."',
                                    employeeId = '".$REQUEST_DATA['employeeId']."'
                WHERE seminarId=".$id;
       
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
    public function deleteSeminars($Id) {
     
        $query = "DELETE FROM seminar
                  WHERE seminarId=$Id ";
        
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
    public function getSeminars($conditions='') {
        
      $query = "SELECT	s.seminarId, s.organisedBy,s.topic,s.description,s.startDate,s.endDate,s.seminarPlace,s.employeeId,
						e.employeeName, e.employeeCode,	d.designationName 
				FROM	seminar s, 
						employee e LEFT JOIN designation d ON e.designationId = d.designationId
				WHERE	s.employeeId = e.employeeId
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
    
       public function getSeminarsList($filter='', $orderBy='',$limit = '') {
     
        $query = "	SELECT 
							s.seminarId, s.organisedBy, s.topic, s.description,
                            s.startDate, s.endDate, s.seminarPlace, s.employeeId, e.employeeName, e.employeeCode
					FROM	seminar s, employee e
					WHERE	s.employeeId = e.employeeId
					    $filter
                    ORDER BY $orderBy $limit ";
                    
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
    public function getTotalSeminars($filter='') {
         
       $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	seminar s,
							employee e
					WHERE	s.employeeId = e.employeeId
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

// $History: SeminarsManager.inc.php $
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
