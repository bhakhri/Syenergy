<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "student and teacher_comment" TABLE
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class GenerateStudentSlipManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "StudentConcessionManager" CLASS
//
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct(){
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "StudentConcessionManager" CLASS
//
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
    

    
    public function fetchClases($rollNo = '',$migrationStudyPeriod=''){

  	if($migrationStudyPeriod==''){
	      $migrationStudyPeriod = 0;
	    }
              
	if($migrationStudyPeriod != 0){
	$migrationCondition=" AND sp.periodValue >=$migrationStudyPeriod ";
	}
    	$query ="SELECT cls.classId,cls.className FROM class cls, study_period sp 
			WHERE 
				 cls.studyPeriodId = sp.studyPeriodId 
				$migrationCondition				
				AND CONCAT(degreeId,'~',batchId,'~',branchId) LIKE 
				(SELECT 
					DISTINCT CONCAT(cc.degreeId,'~',cc.batchId,'~',cc.branchId) 
				 FROM 
					student s, class cc 
				 WHERE 
					cc.classId = s.classId AND (s.rollNo LIKE '$rollNo' OR s.regNo LIKE '$rollNo'  OR s.universityRollNo LIKE '$rollNo'))

			    		ORDER BY className Asc";
    		
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    	
    }


    public function getStudentMigrationCheck($rollNo='') {
    
        $query = "SELECT 
			 st.studentId,st.classId,st.isMigration,st.migrationStudyPeriod,
                         st.migrationClassId,st.isLeet                        
                   FROM 
                       student st 
                   WHERE 
                     	 (st.rollNo LIKE '$rollNo' OR st.regNo LIKE '$rollNo'  OR st.universityRollNo LIKE '$rollNo') ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getCheckStudentId($condition='',$feeClassId='') { 
       
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                        stu.studentId, stu.classId AS currentClassId,
                        CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) AS studentName, 
                        IF(IFNULL(stu.regNo,'')='','".NOT_APPLICABLE_STRING."',stu.regNo) AS regNo,
                        IF(IFNULL(stu.rollNo,'')='','".NOT_APPLICABLE_STRING."',stu.rollNo) AS rollNo,
                        IF(IFNULL(stu.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',stu.universityRollNo) AS universityRollNo,
                        IF(IFNULL(stu.fatherName,'')='','".NOT_APPLICABLE_STRING."',stu.fatherName) AS fatherName,
                        '$feeClassId' AS feeClassId
                  FROM
                       student stu
                  WHERE    
                       $condition ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getCheckFeeHead($condition='') { 
       
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                       feeReceiptId, feeReceiptInstrumentId, studentId, classId, feeHeadId, 
                       feeHeadName AS headName, amount AS feeHeadAmt
                  FROM
                       fee_receipt_instrument
                  WHERE    
                       $condition ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    }	
// $History: StudentConcessionManager.inc.php $
?>
