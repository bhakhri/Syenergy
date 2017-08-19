<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the Student Fee Concession Mapping
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------


require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class StudentFeeConcessionMappingManager {
	private static $instance = null;
	
	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
	
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Add Student Fee Concession Categories
//  Author :Parveen Sharma
//  Created on : (29-May-2009)
//  Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------        
    public function addFeeStudentCategory($insertValue) {
        global $sessionHandler;
     
        $query = "INSERT INTO `fee_student_concession_mapping`
                  (classId, studentId, categoryId)
                  VALUES
                  $insertValue";
                   
        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
    }
    
    public function deleteFeeStudentCategory($classId='',$studentId='') {
        global $sessionHandler;
     
        $query = "DELETE FROM `fee_student_concession_mapping`
                  WHERE classId = $classId AND studentId = $studentId ";
                   
        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
    }
    
    public function getStudentList($condition='', $limit = '', $orderBy=' studentName',$feeClassId='') {         
        
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();  
        if($orderBy=='') {
          $orderBy =' studentName';
        }  
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                       s.studentId, c.classId, c.batchId, c.degreeId, c.branchId,
                       IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                       IF(IFNULL(s.regNo,'')='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,
                       IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                       CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) As studentName, studentPhoto,
                       '$feeClassId' AS feeClassId
                  FROM 
                       student s, `class` c
                  WHERE
                       s.classId = c.classId AND
                       CONCAT_WS(',',c.universityId, c.batchId, c.degreeId, c.branchId) IN
                       (SELECT 
                              CONCAT_WS(',',universityId, batchId, degreeId, branchId) 
                        FROM 
                              `class` 
                        WHERE 
                              classId = '$feeClassId' AND isActive IN (1,2,3) )
                  $condition 
                  ORDER BY 
                        $orderBy
                  $limit ";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getStudentConcessionCategoryList($conditions='') {         
        
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();  
        if($orderBy=='') {
          $orderBy =' categoryOrder';
        }  
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                       fcc.categoryId, fcc.categoryName, fcc.categoryOrder,
                       IFNULL(fscm.studentId,'') AS studentId, IFNULL(fscm.classId,'') AS classId 
                  FROM 
                       fee_concession_category fcc LEFT JOIN fee_student_concession_mapping fscm ON 
                       fcc.categoryId = fscm.categoryId $conditions
                        
                  ORDER BY 
                       fscm.studentId DESC, fcc.categoryOrder ";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    
    public function getClassWiseConcessionList($classId='')   {
       global $sessionHandler;
        
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
       $sessionId = $sessionHandler->getSessionVariable('SessionId');
         
       $query = "SELECT 
                       DISTINCT fcv.classId, fcv.categoryId, fcc.categoryName
                  FROM 
                        `fee_head` fh, fee_concession_category fcc, `fee_classwise_concession_values` fcv 
                  WHERE 
                       fh.feeHeadId =  fcv.feeHeadId    AND
                       fcc.categoryId = fcv.categoryId  AND
                       fh.isConsessionable = 1          AND
                       fcv.classId = '$classId'
                  ORDER BY
                       fcc.categoryOrder";
                
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
    public function getClassName($classId='')   {
       global $sessionHandler;
        
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
       $sessionId = $sessionHandler->getSessionVariable('SessionId');
         
       $query = "SELECT 
                       DISTINCT c.classId, c.className, 
                       c.universityId, c.batchId, c.degreeId, c.branchId
                  FROM 
                        `class` c
                  WHERE 
                       c.classId = '$classId' ";
                
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
    public function getCheckStudentConcession($condition) { 
       
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                       DISTINCT studentId, feeClassId
                  FROM
                       adhoc_concession_detail
                  WHERE      
                       $condition     
                  ORDER BY    
                       feeClassId, studentId ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
}
?> 