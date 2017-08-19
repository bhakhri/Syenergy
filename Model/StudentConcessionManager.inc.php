<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "student and teacher_comment" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class StudentConcessionManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "StudentConcessionManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "StudentConcessionManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
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
// THIS FUNCTION IS USED FOR GETTING student LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getStudentList($conditions='', $limit = '', $orderBy=' studentName',$feeHeadId,$feeCondition='',$feeClassId='') {

       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');  
       
       if($orderBy=='') {
         $orderBy='studentName';  
       }
       
       $query = "SELECT 
                            DISTINCT s.studentId,       
							s.transportFacility,
							s.hostelFacility,
							IF(IFNULL(busStopId,'')='','0',busStopId) AS busStopId,
							IF(IFNULL(hostelRoomId,'')='','0',hostelRoomId) AS hostelRoomId,
                            cl.classId, cl.className AS className,
                            IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',rollNo) AS rollNo, 
                            IF(IFNULL(universityRollNo,'') ='','".NOT_APPLICABLE_STRING."',universityRollNo) AS universityRollNo, 
                            CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName,
                            sc.concessionType,
                            IFNULL(s.quotaId,'') AS quotaId,
                            IFNULL(s.isLeet,'') AS isLeet,
                            IFNULL(sc.concessionValue,0) AS concessionValue,
							IFNULL(sc.discountValue,0) AS discountValue,
                            IFNULL(sc.reason,'') AS reason,  
                            IF(IFNULL(fh.quotaId,'')=s.quotaId,
                                (IF(fh.isLeet=3,fh.feeHeadAmount, IF(fh.isLeet=1,IF(s.isLeet=1,fh.feeHeadAmount,0), 
                                    IF(fh.isLeet=2,IF(s.isLeet=0,fh.feeHeadAmount,0),0)))),
                            (IF(IFNULL(fh.quotaId,'')='',
                                (IF(fh.isLeet=3,fh.feeHeadAmount, IF(fh.isLeet=1,IF(s.isLeet=1,fh.feeHeadAmount,0), 
                                     IF(fh.isLeet=2,IF(s.isLeet=0,fh.feeHeadAmount,0),0)))),0))) AS feeHeadAmount,
                            IFNULL(sc.concessionId,-1) AS concessionId, fh.classId AS feeClassId
                  FROM 
                            class cl, student s
                            LEFT JOIN student_concession sc ON (sc.studentId=s.studentId AND sc.classId='$feeClassId' AND 
                            sc.feeHeadId=$feeHeadId)
                            LEFT JOIN fee_head_values fh ON fh.classId='$feeClassId' AND fh.feeHeadId='$feeHeadId' 
                            LEFT JOIN class c2 ON c2.classId='$feeClassId'
                  WHERE                   
                            s.classId=cl.classId
                            AND cl.instituteId=".$instituteId." 
                            AND cl.sessionId=".$sessionId." 
                            AND cl.batchId = c2.batchId AND cl.universityId = c2.universityId 
                            AND cl.branchId = c2.branchId AND cl.degreeId = c2.degreeId
                            $conditions                      
                  GROUP BY 
                            s.studentId
                  ORDER BY 
                            $orderBy 
                  $limit ";
        
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF students
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalStudent($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
        $query = "SELECT 
                        studentId 
                  FROM 
                            student s,class cl 
                  WHERE 
                            s.classId=cl.classId
                            
                            AND cl.instituteId=".$instituteId." 
                            AND cl.sessionId=".$sessionId." 
                            $conditions
                  GROUP BY s.studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
   
   //-------------------------------------------------------
    //  THIS FUNCTION IS to fetch the role 
    //
    // Author :Jaineesh
    // Created on : (01-10-2009)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function getRoleUser($userId) {
		 $query = "
				SELECT 
				        cvtr.classId,
                        cvtr.groupId
				FROM	
                        classes_visible_to_role cvtr,
						user_role ur
				WHERE	
                       cvtr.userId = ur.userId
				AND	 ur.userId = $userId
				AND	cvtr.userId = $userId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to delete student concession data
// Author :Dipanjan Bhattacharjee
// Created on : (07.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    public function deleteStudentConcession($deleteString) {
         
		 $query = "DELETE FROM student_concession WHERE CONCAT_WS('_',studentId,classId,feeHeadId) IN (".$deleteString.")";
         return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to insert student concession data
// Author :Dipanjan Bhattacharjee
// Created on : (07.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    public function insertStudentConcession($insertString) {
         
        $query = "INSERT INTO student_concession    
                   (feeHeadId,studentId,classId,headValue,concessionType,concessionValue,discountValue,reason,userId,dated)
                   VALUES  
                   $insertString ";
                   
         return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }    

  
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to total student concession data
// Author :Dipanjan Bhattacharjee
// Created on : (07.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    public function getTotalConcession($condition='') {
         
         $query = "SELECT 
                        IF(discountValue='0.00','0.00',IFNULL(SUM(
                                headValue-discountValue),'0.00')) as totalConcession 
                   FROM 
                        student_concession 
                   $condition";
                   
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    public function getStudentConcessionList($conditions='', $limit = '', $orderBy=' studentName',$feeClassId='') {

       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');  
       
       $query = "SELECT 
                          DISTINCT 
                                s.studentId, s.classId AS currentClassId, s.transportFacility, s.hostelFacility,
                                c2.classId AS feeClassId, c2.className, 
                                IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',rollNo) AS rollNo, 
                                IF(IFNULL(universityRollNo,'') ='','".NOT_APPLICABLE_STRING."',universityRollNo) AS universityRollNo, 
                                CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName,
                                IFNULL(s.quotaId,'') AS quotaId, IFNULL(s.isLeet,'') AS isLeet
                  FROM 
                          class c1, student s LEFT JOIN class c2 ON c2.classId='$feeClassId' AND c2.instituteId=".$instituteId." 
                  WHERE             
                          c1.classId = s.classId       
                          AND c1.instituteId=".$instituteId." 
                          AND c1.batchId = c2.batchId AND c1.universityId = c2.universityId 
                          AND c1.branchId = c2.branchId AND c1.degreeId = c2.degreeId
                  $conditions                      
                  ORDER BY 
                          $orderBy 
                  $limit ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
      
}

// $History: StudentConcessionManager.inc.php $
?>