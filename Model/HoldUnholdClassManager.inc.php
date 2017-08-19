<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Reappear / Re-Exam" Module
//
// Author :Parveen Sharma   
// Created on : 19-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class HoldUnholdClassManager {
    private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "SessionsManager" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "SessionsManager" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
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
    
  
    public function addHoldClasses($id='0',$chk1='0',$chk2='0',$chk3='0',$chk4='0') {
       
       if($id=='') {
         $id='0';  
       } 
       $query = "UPDATE 
                    `class`
                 SET
                    holdAttendance = '$chk1' ,
                    holdTestMarks = '$chk2' ,
                    holdFinalResult = '$chk3',
                    holdGrades = '$chk4'
                 WHERE         
                    classId = '$id' ";

       return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
    }
    
    public function unHoldClasses($classId='0') {
        
       if($classId=='') {
         $classId='0';  
       } 
       $query = "UPDATE `class`  
                 SET
                    holdAttendance = '0' ,
                    holdTestMarks = '0',
                    holdFinalResult = '0',
                    holdGrades = '0'   
                 WHERE
                    classId IN ($classId) ";

       return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
    }
    
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Batch Year
//
// Author :Abhay Kant
// Created on : 26-July-2011
// Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
   public function getBatchYear($conditions='') {
	
       global $sessionHandler;
        
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');
       $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        

	$query="SELECT 
		    DISTINCT b.batchId, b.batchName 
		FROM
		   class c, batch b
	        WHERE
		 c.batchId = b.batchId AND c.isActive IN (1,2,3) AND
		 c.instituteId = $instituteId
		 $conditions ";
	
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }    
     


    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING DEGREE 
//
// Author :Abhay Kant
// Created on : 26-July-2011
// Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
   public function getDegree($conditions) {
	
 	 global $sessionHandler; 

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

	$query="SELECT 
			DISTINCT d.degreeId, d.degreeAbbr
	       FROM 
			class c, degree d 
	       WHERE     
			c.degreeId = d.degreeId AND c.isActive IN (1,2,3)  AND
	    	        c.instituteId = $instituteId AND c.batchId = $conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }    
     
         
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Branch
//
// Author :Abhay Kant
// Created on : 26-July-2011
// Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
   public function getBranch($batchId,$degreeId) {
     
	global $sessionHandler; 

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        

	$query="SELECT 
			DISTINCT b.branchId, b.branchCode 
		FROM 
			class c, branch b 
		WHERE 
			c.branchId = b.branchId AND c.isActive IN (1,2,3) AND
			c.instituteId = $instituteId AND			 
			c.batchId = $batchId AND 
			c.degreeId = $degreeId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }    
     
//--------------------------------------------------------------
//  THIS FUNCTION IS Fetch all selected class
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getSessionClasses($batchId='',$branchId='',$degreeId='',$condition='', $orderBy='className', $limits='') {
       
        global $sessionHandler; 

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');  
        
        if($orderBy=='') {
          $orderBy=" classStatus, SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3), studyPeriodId";  
        }
                                     
        $query ="SELECT 
			         c.classId,c.className,c.studyPeriodId,c.sessionTitleName,c.displayOrder, c.isActive, 
                     c.holdAttendance,c.holdTestMarks,c.holdFinalResult,c.holdGrades
		         FROM 
			         class c 
		         WHERE 
			         c.batchId=$batchId   AND 
			         c.degreeId=$degreeId AND 
			         c.branchId=$branchId AND
			         c.instituteId = $instituteId AND
			         c.isActive IN (1,2,3)
                 ORDER BY 
	  	             $orderBy $limits";
		
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   
    }
    
    
    public function getSingleField($table, $conditions='') {
    
        $query = "SELECT 
			 classId,className,sessionTitleName,displayOrder  
                  FROM 
                        $table 
                  $conditions";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getIndividualStudents($classId='', $hiddenClassId='', $conditions='') {
    
        $query = "SELECT 
			           DISTINCT s.studentId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                       IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                       IF(IFNULL(s.regNo,'')='','---',s.regNo)  AS regNo,
                       IF(s.isLeet=0,'Not Leet','Leet') AS studentLeet,
                       IF(s.isMigration=0,'Not Migrated','Migrated') AS studentMigration,
                       IFNULL(shr.classId,0) AS hiddenClassId,
                       IFNULL(shr.holdAttendance,0) AS holdAttendance, IFNULL(shr.holdTestMarks,0) AS holdTestMarks, 
                       IFNULL(shr.holdFinalResult,0) AS holdFinalResult, IFNULL(shr.holdGrades,0) AS holdGrades
                  FROM 
                       class c, student s LEFT JOIN student_hold_result shr ON s.studentId = shr.studentId AND shr.classId = '$hiddenClassId' 
                 WHERE 
                       c.classId = s.classId  AND 
		    	       s.classId IN ($classId) 
                 $conditions 
                 ORDER BY 
                       s.rollNo ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

  public function getHoldStudentsData($studentId) {
    
        $query = "SELECT 
			           shr.studentId,shr.classId,shr.studentId as holdStudentId,shr.holdAttendance,
			           shr.holdTestMarks,shr.holdFinalResult,shr.holdGrades                        
                  FROM 
                       student_hold_result shr 
                   WHERE 
                     	shr.studentId = '$studentId'
                       $conditions ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//,

	public function getActiveClasses($condition='') {
    	

        $query = "SELECT 
			DISTINCT c.classId,c.className
                  FROM 
                        class c
	
                 WHERE  
			 c.isActive =1 AND
                       $condition ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	public function getClass($batchId,$degreeId,$branchId='') {
    	
	 global $sessionHandler; 

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');  
        $query = "SELECT 
			DISTINCT c.classId,c.className
                  FROM 
                        class c
	
                 WHERE  
			c.batchId=$batchId   AND 
			c.degreeId=$degreeId AND 
			c.branchId=$branchId AND
			c.instituteId = $instituteId AND
			c.isActive IN (1,2,3)			 
                       $condition ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function addHoldStudentDetails($id='0',$chk1='0',$chk2='0',$chk3='0',$chk4='0',$classId='',$isClass='') {

	   global $sessionHandler;   
       $userId = $sessionHandler->getSessionVariable('UserId');  
       if($chk1=='') {
         $chk1=0;  
       }     
       if($chk2=='') {
         $chk2=0;  
       }     
       if($chk3=='') {
         $chk3=0;  
       }     
       if($chk4=='') {
         $chk4=0;  
       }     
       if($isClass=='') {
         $isClass=0;  
       }     
	   
	   $query = "INSERT INTO  `student_hold_result`
		         SET
			        studentId = '$id',
		            holdAttendance = '$chk1' ,
		            holdTestMarks = '$chk2' ,
		            holdFinalResult = '$chk3',
		            holdGrades = '$chk4',		           
		            classId = '$classId',
			        isClass = '$isClass',
			        holdDate = now(),
		            userId ='$userId'";

        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
    }

    public function checkStudentDetails($studentId) {

	       $query = "SELECT  
           				 studentId ,holdAttendance,holdTestMarks ,holdFinalResult,
		                  holdGrades,classId,isClass,holdDate,userId
		              FROM
			              `student_hold_result`
		              WHERE
				          studentId = '$studentId'";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getUnHoldHoldDelete($condition='') {
      
       global $sessionHandler; 
       $userId = $sessionHandler->getSessionVariable('UserId');  
        
       $query = "DELETE FROM `student_hold_result` WHERE $condition ";
        
       return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
    }
    
}
?>
