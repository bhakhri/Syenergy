<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "student and teacher_comment" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class StudentMiscChargesManager {
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
    
    
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to delete student concession data
// Author :Dipanjan Bhattacharjee
// Created on : (07.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    public function deleteStudentMiscCharges($condition) {
         
         $query = "DELETE FROM student_misc_fee_charges WHERE $condition ";
         return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to insert student concession data
// Author :Dipanjan Bhattacharjee
// Created on : (07.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    public function insertStudentMiscCharges($insertString) {
         
        $query = "INSERT INTO student_misc_fee_charges 
                   (feeHeadId, classId, studentId, charges, userId, dated)
                   VALUES  
                   $insertString ";
                   
         return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET TOTAL NUMBER OF STUDENTS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getTotalStudent($conditions='') {
       
       global $sessionHandler;

       $query = "SELECT
                      COUNT(DISTINCT a.studentId) AS totalRecords
                  FROM 
                      university c, degree d, branch e, study_period f, student a
                      LEFT JOIN student_groups sg ON a.studentId = sg.studentId
                      LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
                      INNER JOIN class b ON (b.classId = a.classId OR sg.classId = b.classId)
                  WHERE 
                      b.universityId = c.universityId
                      AND b.degreeId = d.degreeId
                      AND b.branchId = e.branchId
                      AND b.studyPeriodId = f.studyPeriodId
                      AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                  $conditions";
                  
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getStudentList($condition='', $limit = '', $orderBy=' studentName',$feeHeadId,$feeCondition='',$feeClassId='') {          
        
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();  
        if($orderBy=='') {
          $orderBy =' studentName';
        }  
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                       s.studentId, c.classId, c.batchId, c.degreeId, c.branchId, c.className AS activeClassName, 
                       IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                       IF(IFNULL(s.regNo,'')='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,
                       IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                       CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) As studentName, studentPhoto,
                       '$feeClassId' AS feeClassId,
                       (SELECT className FROM class cc WHERE cc.classId = '$feeClassId') AS feeClassName,
                        IFNULL(sm.charges,-1) AS charges 
                  FROM 
                       `class` c,
                        student s LEFT JOIN student_misc_fee_charges sm ON 
                        sm.studentId = s.studentId AND sm.classId = '$feeClassId' AND sm.feeHeadId = '$feeHeadId'  
                  WHERE
                        s.classId = c.classId AND
                        CONCAT_WS(',',c.universityId, c.batchId, c.degreeId, c.branchId) IN
                        (SELECT 
                              CONCAT_WS(',',cc.universityId, cc.batchId, cc.degreeId, cc.branchId)
                        FROM 
                              `class` cc
                        WHERE 
                              cc.classId = '$feeClassId' AND cc.isActive IN (1,2,3) AND cc.instituteId = $instituteId )
                  $condition 
                  ORDER BY 
                        $orderBy
                  $limit ";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
}

// $History: StudentConcessionManager.inc.php $
?>