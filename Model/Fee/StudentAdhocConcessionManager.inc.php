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

class StudentAdhocConcessionManager {
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
    
    
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to delete student concession data
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    public function deleteAdhocConcessionMaster($condition) {
         
         $query = "DELETE FROM adhoc_concession_master_new  $condition ";
         return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function deleteAdhocConcessionDetail($condition) {
         
         $query = "DELETE FROM adhoc_concession_detail_new $condition ";
         return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to insert student concession data
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    public function insertAdhocConcessionDetail($insertString) {
         
        $query = "INSERT INTO adhoc_concession_detail_new 
                  (adhocDetailId,studentId,feeClassId,feeHeadId,isVariable,concessionAmount,sessionId,instituteId)
                  VALUES  
                  $insertString ";
                   
         return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }  
    
    
    public function insertAdhocConcessionMaster($studentId,$classId,$userId,$comments,$netCharges) {
    	 global $sessionHandler;
    
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
         
        $query = "INSERT into  `adhoc_concession_master_new` (adhocId,dateOfEntry,studentId,feeClassId,userId,description,adhocAmount,sessionId,instituteId)
        						VALUES('',now(),'$studentId','$classId','$userId','$comments','$netCharges','$sessionId','$instituteId')";
       
         return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET TOTAL NUMBER OF STUDENTS
//
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
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
    
    
     public function getStudentDetailClass($condition,$feeClassId='',$tableName='') {
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        if($tableName=='') {
          $tableName = ' student';  
        }
        else { 
          $tableName = ' quarantine_student';
        }
       
        
        $query = "SELECT
                        stu.studentId, stu.firstName, stu.lastName, stu.quotaId, stu.hostelRoomId, stu.isLeet, 
                        CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) AS studentName, 
                        IF(IFNULL(stu.regNo,'')='','".NOT_APPLICABLE_STRING."',stu.regNo) AS regNo,
                        IF(IFNULL(stu.rollNo,'')='','".NOT_APPLICABLE_STRING."',stu.rollNo) AS rollNo,
                        IF(IFNULL(stu.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',stu.universityRollNo) AS universityRollNo,
                        IF(IFNULL(stu.fatherName,'')='','".NOT_APPLICABLE_STRING."',stu.fatherName) AS fatherName,
                        stu.fatherName, cls.classId, cls.instituteId, 
                        cls.className AS className,
                        SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-3) AS className1,
                        cls.studyPeriodId, cls.universityId, cls.batchId, cls.degreeId, cls.branchId, sp.periodName,
                        IF(IFNULL(stu.transportFacility,'')='',0,stu.transportFacility)  AS transportFacility,    
                        IF(IFNULL(stu.hostelFacility,'')='',0,stu.hostelFacility) AS hostelFacility, 
                        IF(IFNULL(stu.busStopId,'')='','0',stu.busStopId) AS busStopId,
                        IF(IFNULL(stu.hostelRoomId,'')='','0',stu.hostelRoomId) AS hostelRoomId,
                        stu.isMigration, stu.migrationClassId,
                        IFNULL((SELECT 
                                      classId
                                FROM 
                                      class c 
                                WHERE 
                                      c.instituteId = cls.instituteId AND c.universityId = cls.universityId AND
                                      c.batchId = cls.batchId AND c.degreeId = cls.degreeId AND 
                                      c.branchId = cls.branchId AND c.classId='$feeClassId'),-1) AS feeClassId,
                        IFNULL((SELECT 
                                      c.studyPeriodId
                                FROM 
                                      class c 
                                WHERE 
                                      c.instituteId = cls.instituteId AND c.universityId = cls.universityId AND
                                      c.batchId = cls.batchId AND c.degreeId = cls.degreeId AND 
                                      c.branchId = cls.branchId AND c.classId='$feeClassId'),-1) AS feeStudyPeriodId                                      
                  FROM
                        $tableName stu, class cls, study_period sp
                  WHERE
                        stu.classId = cls.classId
                        AND sp.studyPeriodId = cls.studyPeriodId
                  $condition ";
                
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    	// This Function is used to check if student has paid Fees
    	public function checkIfAlreadyPaid($studentId,$classId,$currentClassId){
    		 global $sessionHandler;
        	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        	
    		$query = "SELECT COUNT(frm.feeReceiptId) AS cnt
    				FROM	`fee_receipt_master` frm,`fee_receipt_details` frd
    				WHERE	frm.feeReceiptId = frd.feeReceiptId
    				AND	frm.studentId = frd.studentId
    				AND	frm.feeClassId = frd.classId
    				AND	frd.feeType IN (4,1)
    				AND	frm.studentId = '$studentId'
    				AND	frm.feeClassId = '$classId'
				AND	frm.currentClassId = '$currentClassId'
				AND	frm.status = 1
				AND	frd.isDelete = 0
				AND	frm.instituteId = '$instituteId'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    		
    	}
    
	// This Function is used to update fee Receipt master table concession
	public function updateFeeReceiptMaster($studentId,$classId,$userId,$discount,$currentClassId){
		 global $sessionHandler;
        	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		
		$query ="UPDATE `fee_receipt_master` 
					SET	concession = '$discount',
						userId = '$userId'
					WHERE	studentId = '$studentId'
					AND	feeClassId = '$classId'
					AND	currentClassId = '$currentClassId'
					AND	status = 1
					AND	instituteId = '$instituteId'";
		 return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
    
    public function getStudentFeeHeadDetail($classId,$quotaId,$isLeet,$studentId,$isMigrated) {  
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
	$query = "SELECT
			DISTINCT tt.feeId, tt.feeHeadId, tt.feeHeadAmt, tt.classId, 
			tt.headName, tt.headAbbr, tt.sortingOrder, tt.isSpecial, tt.isConsessionable, tt.isRefundable,tt.quotaId,tt.isLeet
				FROM
				(SELECT
					fhv.feeHeadValueId AS feeId, fhv.feeHeadId, fhv.feeHeadAmount AS feeHeadAmt, fhv.classId, 
					fh.headName, fh.headAbbr, fh.sortingOrder, fh.isSpecial, fh.isConsessionable, fh.isRefundable,fhv.quotaId,fhv.isLeet
					FROM
					fee_head_new fh,fee_head_values_new fhv  
					WHERE
				            fhv.feeHeadId  = fh.feeHeadId AND
				            fhv.instituteId  = fh.instituteId AND
				            fhv.sessionId  = fh.sessionId AND
				            fh.instituteId = '$instituteId' AND
				            fh.sessionId = '$sessionId' AND
				            fhv.classId    = '$classId'  AND
				            fhv.feeHeadAmount <> 0 AND
				            fh.isSpecial  = 0
				            AND fhv.quotaId = CASE when fhv.quotaId = '$quotaId' THEN '$quotaId' ELSE 0 END 
		 			    AND if(fhv.isLeet < 3 ,fhv.isLeet = CASE WHEN fhv.isLeet = '$isLeet' THEN '$isLeet' ELSE 2 END,fhv.isLeet = case 
		 			    	when fhv.isLeet = '$isMigrated' THEN '$isMigrated' ELSE -2 END) 
					UNION
					SELECT
					smc.feeMiscId AS feeId, smc.feeHeadId, smc.charges  AS feeHeadAmt, smc.classId, 
					fh.headName, fh.headAbbr, fh.sortingOrder, fh.isSpecial, fh.isConsessionable, fh.isRefundable,fh.instituteId,fh.sessionId
					FROM fee_head_new fh,student_misc_fee_charges_new smc  
						WHERE
						fh.feeHeadId = smc.feeHeadId 
						AND smc.classId = '$classId' 
						AND smc.studentId = '$studentId'
						AND fh.instituteId = smc.instituteId	    
						AND fh.instituteId = '$instituteId'
						AND fh.sessionId = smc.sessionId
						AND fh.sessionId = '$sessionId' 
						AND fh.isSpecial  = '1'
							
				) AS tt 
			ORDER BY
			tt.sortingOrder ASC";
     
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getStudentAdhocConcession($condition='') {  
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT
                       acm.adhocId, acm.dateOfEntry, acm.studentId, acm.feeClassId, 
                       acm.userId, acm.description, acm.adhocAmount
                  FROM
                       adhoc_concession_master_new acm
                  WHERE 
                 		acm.instituteId = '$instituteId'
                        $condition ";
          
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getCheckStudentConcession($condition) { 
       
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                       COUNT(*) AS cnt
                  FROM
                       fee_student_concession_mapping
                  WHERE    
                       $condition ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
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
    
	public function getStudentConcessionId($condition='') { 
       
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                        st.studentId
                  FROM
                       student st
                  WHERE    
                       $condition ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
	 public function deleteStudentConcession($studentId='',$classId='') { 
       
        global $sessionHandler;     
       
        $query = "DELETE 
                  FROM
                       adhoc_concession_master_new 
                  WHERE    
                       studentId='$studentId' AND feeClassId = '$classId' ";
                    
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }
	 
	  public function updateStudentFeeConcession($studentId='',$classId='') { 
       
        global $sessionHandler;     
      
        $query = "UPDATE  `fee_receipt_master` frm
                   SET frm.concession =0
                  WHERE    
                       frm.studentId='$studentId' AND frm.feeClassId ='$classId' AND frm.status = 1 ";
                      
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }
    
     public function getGenerateStudentFeeValue($ttStudentId='',$ttClassId=''){
		  $query ="SELECT
               		*
                 FROM
                    `generate_student_fee` fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                   ";
  
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		
	}
	public function updateGenerateStudentFeeValue($ttStudentId='',$ttClassId='',$strQuery=''){
			$query = "UPDATE `generate_student_fee` 
    				SET	$strQuery 
    				WHERE	
    					studentId = '$ttStudentId'
    				AND	classId = '$ttClassId'
    				";
    		
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);		
			
		
	}
	public function insertGenerateStudentFeeValue($strQuery=''){
			$query = "INSERT INTO
							 `generate_student_fee` 
    				SET	
    						$strQuery ";
						  
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);		
			
		
	} 
}

// $History: StudentConcessionManager.inc.php $
?>
