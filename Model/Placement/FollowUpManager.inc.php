<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "university" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FollowUpManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "FollowUpManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "FollowUpManager" CLASS
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
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Modified on: 7.7.2008
// Modified By: Dipanjan Bhattacharjee
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------    
    public function addFollowUp() {
        global $REQUEST_DATA;
        if(trim($REQUEST_DATA['followUp'])==1){
           $followUpDate=trim($REQUEST_DATA['followUpDate']);
        } 
        else{
           $followUpDate=''; 
        }
        $query = 'INSERT INTO placement_followups 
         (
          `companyId`,`contactedOn`,`newCall`,`contactedVia`,`contactedPerson`,`designation`,
          `comments`,`followUp`,`followUpDate`,`followUpBy`,`followUpMethod`
         ) 
        VALUES("'.add_slashes(trim($REQUEST_DATA['companyId'])).'","'.add_slashes(trim($REQUEST_DATA['contactedOn'])).'","'.add_slashes(trim($REQUEST_DATA['newCall'])).'","'.add_slashes(trim($REQUEST_DATA['contactedVia'])).'","'.add_slashes(trim($REQUEST_DATA['contactedPerson'])).'","'.add_slashes(trim($REQUEST_DATA['designation'])).'", "'.add_slashes(trim($REQUEST_DATA['comments'])).'","'.add_slashes(trim($REQUEST_DATA['followUp'])).'","'.$followUpDate.'","'.add_slashes(trim($REQUEST_DATA['followUpBy'])).'","'.add_slashes(trim($REQUEST_DATA['followUpMethod'])).'"); ';
        
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
        
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A UNIVERSITY 
// $id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------        
    public function editFollowUp($id) {
        global $REQUEST_DATA;
        
        if(trim($REQUEST_DATA['followUp'])==1){
           $followUpDate=trim($REQUEST_DATA['followUpDate']);
        } 
        else{
           $followUpDate=''; 
        }
        
        $query = 'UPDATE placement_followups 
                  SET 
                    `companyId`="'.add_slashes(trim($REQUEST_DATA['companyId'])).'",
                    `contactedOn`="'.add_slashes(trim($REQUEST_DATA['contactedOn'])).'",
                    `newCall`="'.add_slashes(trim($REQUEST_DATA['newCall'])).'",
                    `contactedVia`="'.add_slashes(trim($REQUEST_DATA['contactedVia'])).'",
                    `contactedPerson`="'.add_slashes(trim($REQUEST_DATA['contactedPerson'])).'",
                    `designation`="'.add_slashes(trim($REQUEST_DATA['designation'])).'", 
                    `comments`="'.add_slashes(trim($REQUEST_DATA['comments'])).'",
                    `followUpDate`="'.$followUpDate.'",
                    `followUpBy`="'.add_slashes(trim($REQUEST_DATA['followUpBy'])).'",
                    `followUpMethod`="'.add_slashes(trim($REQUEST_DATA['followUpMethod'])).'"
                  WHERE followUpId='.$id;
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
        
    }
        
      
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING INSIITUTE LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------         
    public function getFollowUps($conditions='') {
        $query = "SELECT *  FROM placement_followups $conditions";
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
    public function deleteFollowUp($followUpId) {
     
        $query = "DELETE FROM placement_followups WHERE followUpId=$followUpId";
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
    
    public function getFollowUpList($conditions='', $limit = '', $orderBy=' c.companyName') {
     
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        f.followUpId,
                        f.contactedOn,
                        f.contactedVia,
                        f.contactedPerson,
                        f.designation,
                        c.companyId,
                        c.companyName,
                        c.companyCode 
                  FROM 
                        placement_company c,placement_followups f
                  WHERE 
                        c.companyId=f.companyId
                        AND c.instituteId=$instituteId
                        AND c.isActive=1
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
    public function getTotalFollowUp($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        placement_company c,placement_followups f
                  WHERE 
                        c.companyId=f.companyId
                        AND c.instituteId=$instituteId
                        AND c.isActive=1
                        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}
// $History: FollowUpManager.inc.php $
?>