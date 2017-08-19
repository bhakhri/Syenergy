<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "university" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class DriveManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "DriveManager" CLASS
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "DriveManager" CLASS
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------    
    public function addPlacementDrive() {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = 'INSERT INTO placement_drive 
         (
          `placementDriveCode`,`companyId`,`startDate`,`endDate`,`startTime`,`startTimeAmPm`,
          `endTime`,`endTimeAmPm`,`visitingPersons`,`eligibilityCriteria`,`isTest`,`individualInterview`,
          `interviewDuration`,`groupDiscussion`,`discussionDuration`,`instituteId`,`venue`,`hrInterview`,`hrInterviewDuration`
         ) 
        VALUES("'.add_slashes(trim($REQUEST_DATA['driveCode'])).'","'.add_slashes(trim($REQUEST_DATA['companyId'])).'","'.add_slashes(trim($REQUEST_DATA['startDate'])).'","'.add_slashes(trim($REQUEST_DATA['endDate'])).'","'.add_slashes(trim($REQUEST_DATA['startTime'])).'","'.add_slashes(trim($REQUEST_DATA['startAmPm'])).'", "'.add_slashes(trim($REQUEST_DATA['endTime'])).'","'.add_slashes(trim($REQUEST_DATA['endAmPm'])).'","'.add_slashes(trim($REQUEST_DATA['visitingPersons'])).'","'.add_slashes(trim($REQUEST_DATA['eligibilityCriteria'])).'","'.add_slashes(trim($REQUEST_DATA['isTest'])).'","'.add_slashes(trim($REQUEST_DATA['individualInterview'])).'","'.add_slashes(trim($REQUEST_DATA['interviewDuration'])).'","'.add_slashes(trim($REQUEST_DATA['groupDiscussion'])).'","'.add_slashes(trim($REQUEST_DATA['discussionDuration'])).'",'.$instituteId.',"'.add_slashes(trim($REQUEST_DATA['venue'])).'","'.add_slashes(trim($REQUEST_DATA['hrInterview'])).'","'.add_slashes(trim($REQUEST_DATA['hrInterviewDuration'])).'") ';
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function addPlacementDriveTests($insertString) {
        
        $query = 'INSERT INTO placement_drive_tests 
                    (`placementDriveId`,`testDuration`,`testSubjects`) 
                 VALUES '.$insertString;
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function addPlacementDriveCriteria($placementDriveId,$cutOffMarks10th,$cutOffMarks12th,$cutOffMarksLastSem,$cutOffMarksGraduation) {
        if($cutOffMarksLastSem==''){
            $cutOffMarksLastSem='Null';
        }
        if($cutOffMarksGraduation==''){
            $cutOffMarksGraduation='Null';
        }
        $query = 'INSERT INTO placement_drive_criteria
                    (`placementDriveId`,`cutOffMarks10th`,`cutOffMarks12th`,`cutOffMarksLastSem`,`cutOffMarksGraduation`) 
                 VALUES ("'.$placementDriveId.'","'.$cutOffMarks10th.'","'.$cutOffMarks12th.'",'.$cutOffMarksLastSem.','.$cutOffMarksGraduation.')';
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A UNIVERSITY 
// $id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------        
   public function editPlacementDrive($placementDriveId) {
        global $REQUEST_DATA;
        
        $query = 'UPDATE placement_drive 
                    SET 
                      `placementDriveCode`="'.add_slashes(trim($REQUEST_DATA['driveCode'])).'",
                      `companyId`="'.add_slashes(trim($REQUEST_DATA['companyId'])).'",
                      `startDate`="'.add_slashes(trim($REQUEST_DATA['startDate'])).'",
                      `endDate`="'.add_slashes(trim($REQUEST_DATA['endDate'])).'",
                      `startTime`="'.add_slashes(trim($REQUEST_DATA['startTime'])).'",
                      `startTimeAmPm`="'.add_slashes(trim($REQUEST_DATA['startAmPm'])).'",
                      `endTime`="'.add_slashes(trim($REQUEST_DATA['endTime'])).'",
                      `endTimeAmPm`="'.add_slashes(trim($REQUEST_DATA['endAmPm'])).'",
                      `visitingPersons`="'.add_slashes(trim($REQUEST_DATA['visitingPersons'])).'",
                      `eligibilityCriteria`="'.add_slashes(trim($REQUEST_DATA['eligibilityCriteria'])).'",
                      `isTest`="'.add_slashes(trim($REQUEST_DATA['isTest'])).'",
                      `individualInterview`="'.add_slashes(trim($REQUEST_DATA['individualInterview'])).'",
                      `interviewDuration`="'.add_slashes(trim($REQUEST_DATA['interviewDuration'])).'",
                      `groupDiscussion`="'.add_slashes(trim($REQUEST_DATA['groupDiscussion'])).'",
                      `discussionDuration`="'.add_slashes(trim($REQUEST_DATA['discussionDuration'])).'",
                      `venue`="'.add_slashes(trim($REQUEST_DATA['venue'])).'",
                      `hrInterview`="'.add_slashes(trim($REQUEST_DATA['hrInterview'])).'",
                      `hrInterviewDuration`="'.add_slashes(trim($REQUEST_DATA['hrInterviewDuration'])).'"
                    WHERE placementDriveId='.$placementDriveId;
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function getLastSemMarks($conditions='') {
        $query = "SELECT cutOffMarksLastSem  FROM placement_drive_criteria $conditions";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    public function deletePlacementDriveTests($placementDriveId) {
       $query = 'DELETE FROM placement_drive_tests WHERE placementDriveId='.$placementDriveId;
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function deletePlacementDriveCriteria($placementDriveId) {
        $query = 'DELETE FROM placement_drive_criteria WHERE placementDriveId='.$placementDriveId;
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
        
      
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING INSIITUTE LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------         
    public function getPlacementDrives($conditions='') {
        $query = "SELECT *  FROM placement_drive $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    public function getPlacementDrivesTest($conditions='') {
        $query = "SELECT *  FROM placement_drive_tests $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
 
	 public function getGraduationMarks($conditions='') {
        $query = "SELECT cutOffMarksGraduation  FROM placement_drive_criteria $conditions";
	
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getPlacementDrivesCriteria($conditions='') {
        $query = "SELECT *  FROM placement_drive_criteria  $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS UNIVERSITYID EXISTS IN CLASS TABLE OR NOT(DELETE CHECK)
//
//$universityId :universityId of the Company
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInPlacementResult($placementDriveId) {
        $query = "SELECT COUNT(*) AS found FROM placement_results WHERE placementDriveId=$placementDriveId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING AN UNIVERSITY
//$universityid :universityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deletePlacementDrive($placementDriveId) {
        $query = "DELETE FROM placement_drive WHERE placementDriveId=$placementDriveId";
        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//-------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS FOR DELETING DATA
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------

    public function getPlacementDriveCount($placementDriveId) {
        $query = "SELECT COUNT(*) AS cnt FROM placement_students WHERE placementDriveId = $placementDriveId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING UNIVERSITY LIST
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------       
    
    public function getPlacementDriveList($conditions='', $limit = '', $orderBy=' c.companyCode') {
     
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        d.*,
                        c.companyCode
                  FROM 
                        placement_company c,placement_drive d
                  WHERE 
                        c.companyId=d.companyId
                        AND d.instituteId=$instituteId
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalPlacementDrive($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        placement_company c,placement_drive d
                  WHERE 
                        c.companyId=d.companyId
                        AND d.instituteId=$instituteId
                        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
}
// $History: DriveManager.inc.php $
?>