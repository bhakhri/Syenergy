<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "university" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class CompanyManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CompanyManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CompanyManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
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
// THIS FUNCTION IS USED FOR ADDING AN UNIVERSITY
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Modified on: 7.7.2008
// Modified By: Dipanjan Bhattacharjee
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addCompany() {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = 'INSERT INTO placement_company 
         (
          `companyName`,`companyCode`,`contactAddress`,`contactPerson`,`designation`,`landline`,
          `mobileNo`,`emailId`,`industryType`,`remarks`,`isActive`,`instituteId`
         ) 
        VALUES("'.add_slashes(trim($REQUEST_DATA['companyName'])).'","'.add_slashes(trim($REQUEST_DATA['companyCode'])).'","'.add_slashes(trim($REQUEST_DATA['contactAddress'])).'","'.add_slashes(trim($REQUEST_DATA['contactPerson'])).'","'.add_slashes(trim($REQUEST_DATA['designation'])).'","'.add_slashes(trim($REQUEST_DATA['landline'])).'", "'.add_slashes(trim($REQUEST_DATA['mobileNo'])).'","'.add_slashes(trim($REQUEST_DATA['emailId'])).'","'.add_slashes(trim($REQUEST_DATA['industryType'])).'","'.add_slashes($REQUEST_DATA['remarks']).'","'.add_slashes(trim($REQUEST_DATA['isActive'])).'","'.$instituteId.'"); ';
        
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
        
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A UNIVERSITY 
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editCompany($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = 'UPDATE placement_company 
                 SET 
                    `companyName`="'.add_slashes(trim($REQUEST_DATA['companyName'])).'",
                    `companyCode`="'.add_slashes(trim($REQUEST_DATA['companyCode'])).'",
                    `contactAddress`="'.add_slashes(trim($REQUEST_DATA['contactAddress'])).'",
                    `contactPerson`="'.add_slashes(trim($REQUEST_DATA['contactPerson'])).'",
                    `designation`="'.add_slashes(trim($REQUEST_DATA['designation'])).'",
                    `landline`="'.add_slashes(trim($REQUEST_DATA['landline'])).'", 
                    `mobileNo`="'.add_slashes(trim($REQUEST_DATA['mobileNo'])).'",
                    `emailId`="'.add_slashes(trim($REQUEST_DATA['emailId'])).'",
                    `industryType`="'.add_slashes(trim($REQUEST_DATA['industryType'])).'",
                    `remarks`="'.add_slashes(trim($REQUEST_DATA['remarks'])).'",
                    `isActive`="'.add_slashes(trim($REQUEST_DATA['isActive'])).
                    '" WHERE companyId='.$id.' AND instituteId='.$instituteId;
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
        
    }
        
      
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING INSIITUTE LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------         
    public function getCompany($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT *  FROM placement_company WHERE instituteId=$instituteId $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS UNIVERSITYID EXISTS IN CLASS TABLE OR NOT(DELETE CHECK)
//
//$universityId :universityId of the Company
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInPlacementDrive($companyId) {
        $query = "SELECT COUNT(*) AS found FROM placement_drive WHERE companyId=$companyId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING AN UNIVERSITY
//
//$universityid :universityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteCompany($companyId) {
     
        $query = "DELETE FROM placement_company WHERE companyId=$companyId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING UNIVERSITY LIST
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------       
    
    public function getCompanyList($conditions='', $limit = '', $orderBy=' companyName') {
     
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        * 
                  FROM 
                        placement_company 
                  WHERE 
                        instituteId=$instituteId
                        $conditions 
                  ORDER BY $orderBy 
                  $limit" ;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF UNIVERSITYS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalCompany($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        placement_company 
                  WHERE 
                        instituteId=$instituteId
                        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}
// $History: CompanyManager.inc.php $
?>