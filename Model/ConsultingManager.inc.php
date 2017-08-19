<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Document" table
// Author :Jaineesh 
// Created on : (28.02.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ConsultingManager {
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
    public function addConsulting($employeeId) {
      global $REQUEST_DATA;

      $query="INSERT INTO consulting (projectName,sponsorName,startDate,endDate,amountFunding,remarks,employeeId) 
      VALUES('".$REQUEST_DATA['projectName']."','".$REQUEST_DATA['sponsorName']."','".$REQUEST_DATA['startDate']."','".$REQUEST_DATA['endDate']."','".$REQUEST_DATA['amountFunding']."','".$REQUEST_DATA['remarks']."','".$employeeId."')";
      
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
    public function editConsulting($id) {
        global $REQUEST_DATA;
        
        $query="UPDATE consulting SET	projectName ='".$REQUEST_DATA['projectName']."',
									    sponsorName ='".$REQUEST_DATA['sponsorName']."',
									    startDate = '".$REQUEST_DATA['startDate']."',
                                        endDate = '".$REQUEST_DATA['endDate']."',
                                        amountFunding = '".$REQUEST_DATA['amountFunding']."',
                                        remarks = '".$REQUEST_DATA['remarks']."',
                                        employeeId = '".$REQUEST_DATA['employeeId']."'
                WHERE consultId=".$id;
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
    public function deleteConsulting($Id) {
     
        $query = "DELETE FROM consulting
                  WHERE consultId=$Id ";
        
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
    public function getConsulting($conditions='') {
        
      $query = "SELECT	c.consultId, c.projectName, c.sponsorName, c.startDate,c.endDate, c.amountFunding, c.remarks, c.employeeId,
						e.employeeName, e.employeeCode,	d.designationName 
				FROM	consulting c, 
						employee e LEFT JOIN designation d ON e.designationId = d.designationId
				WHERE	c.employeeId = e.employeeId
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
    
       public function getConsultingList($filter='', $orderBy='',$limit = '') {
     
        $query = "	SELECT 
							c.consultId, c.projectName, c.sponsorName, c.startDate,c.endDate, c.amountFunding, c.remarks, c.employeeId,
                            e.employeeName, e.employeeCode
					FROM	consulting c, employee e
					WHERE	c.employeeId = e.employeeId
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
    public function getTotalConsulting($filter='') {
         
       $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	consulting c,
							employee e
					WHERE	c.employeeId = e.employeeId
							$filter  "; 
                            
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

// $History: ConsultingManager.inc.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 12:14p
//Created in $/LeapCC/Model
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:14p
//Created in $/Leap/Source/Model
//initial checkin 
//

?>
