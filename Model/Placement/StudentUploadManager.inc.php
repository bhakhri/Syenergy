<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "university" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class StudentUploadManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "StudentUploadManager" CLASS
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "StudentUploadManager" CLASS
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
    public function insertStudentDetails($insertString,$marksIds) {
        global $REQUEST_DATA;
        if($marksIds == 1) {
        $query = 'INSERT INTO placement_students 
                   (
                    `placementDriveId`,`studentTitle`,`studentName`,`fatherName`,`dob`,`correspondenceAddress`,`permanentAddress`,
                    `homeTown`,`landline`,`mobileNo`,`emailId`,`gender`,`courseName`,
                    `disciplineName`,`marks10th`,`marks12th`,`marksLastSem`,`college`,`university`
                   ) 
                  VALUES '.$insertString;
		}
		else {
			  $query = 'INSERT INTO placement_students 
                   (
                    `placementDriveId`,`studentTitle`,`studentName`,`fatherName`,`dob`,`correspondenceAddress`,`permanentAddress`,
                    `homeTown`,`landline`,`mobileNo`,`emailId`,`gender`,`courseName`,
                    `disciplineName`,`marks10th`,`marks12th`,`marksGraduation`,`college`,`university`
                   ) 
                  VALUES '.$insertString;
		}

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
  public function deleteUploadedStudentData($placementDriveId) {
       $query = 'DELETE FROM placement_students WHERE placementDriveId='.$placementDriveId;
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
  }
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING INSIITUTE LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------         
    public function getPlacementDrivesUsage($placementDriveId) {
        $query = "SELECT 
                        COUNT(*) AS cnt  
                  FROM placement_students
                  WHERE
                        placementDriveId=$placementDriveId
                        AND (
                             placementDriveId IN (SELECT DISTINCT placementDriveId FROM placement_eligibility_list)
                             OR 
                             placementDriveId IN (SELECT DISTINCT placementDriveId FROM placement_results)
                            )";
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



//*********************************These function are used for generating and saving student lists**************** 

public function getPlacementDriveCriteria($placementDriveId) {
    $query = "SELECT 
                    p.eligibilityCriteria,
                    p.isTest,
                    p.individualInterview,
                    p.groupDiscussion,
                    IF(pdc.cutOffMarksLastSem IS NULL,0,1) AS lastSem,
                    IF(pdc.cutOffMarksGraduation IS NULL,0,1) AS grads
              FROM 
                    placement_drive p
                    LEFT JOIN placement_drive_criteria pdc ON pdc.placementDriveId=p.placementDriveId
              WHERE 
                    p.placementDriveId=$placementDriveId
              ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


 public function getPlacementDriveStudentList($conditions='', $limit = '', $orderBy=' s.studentName') {
     
        $query = "SELECT 
                        s.studentId,
                        s.studentName,
                        s.dob,
                        s.marks10th,
                        s.marks12th,
                        s.marksLastSem,
                        s.marksGraduation,
                        s.college,
                        IF(pel.eligibilityId IS NOT NULL,'Allocated','Not Allocated') AS allocated,
                        IF(pel.eligibilityId IS NOT NULL,1,0) AS isAllocated
                  FROM
                        placement_drive pd
                        LEFT JOIN placement_students s ON s.placementDriveId=pd.placementDriveId
                        LEFT JOIN placement_drive_criteria pdc ON pdc.placementDriveId=pd.placementDriveId
                        LEFT JOIN placement_eligibility_list pel ON ( pel.placementDriveId=pd.placementDriveId AND pel.studentId=s.studentId)
                        $conditions
                  ORDER BY $orderBy
                  $limit";
				  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }    

   
 public function getTotalPlacementDriveStudent($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM
                        placement_drive pd
                        LEFT JOIN placement_students s ON s.placementDriveId=pd.placementDriveId
                        LEFT JOIN placement_drive_criteria pdc ON pdc.placementDriveId=pd.placementDriveId
                        LEFT JOIN placement_eligibility_list pel ON ( pel.placementDriveId=pd.placementDriveId AND pel.studentId=s.studentId)
                       $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }
 
 
 public function checkStudents($placementDriveId,$studentIds) {
    $query = "SELECT 
                    COUNT(*) AS found 
              FROM 
                    placement_students 
              WHERE 
                    placementDriveId=$placementDriveId
                    AND studentId in ($studentIds)
              ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


 public function deleteEligableStudents($placementDriveId,$studentIds) {
    $query = "DELETE 
              FROM 
                    placement_eligibility_list 
              WHERE 
                    placementDriveId=$placementDriveId
                    AND studentId in ($studentIds)
              ";
   return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

 public function insertEligibleStudents($insertString) {
    $query = "INSERT INTO 
                    placement_eligibility_list 
                    (placementDriveId,studentId)
              VALUES $insertString 
              ";
   return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
 }
 
 
 
 public function getPlacementDriveStudentResultList($conditions='', $limit = '', $orderBy=' s.studentName') {
     
        $query = "SELECT 
                        s.studentId,
                        s.studentName,
                        s.dob,
                        s.marks10th,
                        s.marks12th,
                        s.marksLastSem,
                        s.college,
                        pr.clearedTest,
                        pr.clearedInterview,
                        pr.clearedGroupDiscussion,
                        pr.isSelected
                  FROM
                        placement_drive pd
                        LEFT JOIN placement_students s ON s.placementDriveId=pd.placementDriveId
                        INNER JOIN placement_eligibility_list pel ON (pel.studentId=s.studentId AND pel.placementDriveId=s.placementDriveId)
                        LEFT JOIN placement_results pr ON ( pr.placementDriveId=pd.placementDriveId AND pr.studentId=s.studentId)
                        $conditions
                  ORDER BY $orderBy
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }    

   
 public function getTotalPlacementDriveStudentResult($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM
                        placement_drive pd
                        LEFT JOIN placement_students s ON s.placementDriveId=pd.placementDriveId
                        INNER JOIN placement_eligibility_list pel ON (pel.studentId=s.studentId AND pel.placementDriveId=s.placementDriveId)
                        LEFT JOIN placement_results pr ON ( pr.placementDriveId=pd.placementDriveId AND pr.studentId=s.studentId)
                        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }
 
 
  public function deleteStudentResults($placementDriveId,$studentIds) {
    $query = "DELETE 
              FROM 
                    placement_results 
              WHERE 
                    placementDriveId=$placementDriveId
                    AND studentId in ($studentIds)
              ";
   return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

 public function insertStudentResult($insertString) {
    $query = "INSERT INTO 
                    placement_results 
                    (placementDriveId,studentId,isSelected,clearedTest,clearedGroupDiscussion,clearedInterview)
              VALUES $insertString 
              ";
   return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
 }


//*********************************These function are used for generating and saving student lists****************




  
}
// $History: StudentUploadManager.inc.php $
?>