<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "student and teacher_comment" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class StudentAdhocConcessionManager {
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
    public function deleteAdhocConcessionMaster($condition) {
         
         $query = "DELETE FROM adhoc_concession_master WHERE $condition ";
         return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function deleteAdhocConcessionDetail($condition) {
         
         $query = "DELETE FROM adhoc_concession_detail WHERE $condition ";
         return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to insert student concession data
// Author :Dipanjan Bhattacharjee
// Created on : (07.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    public function insertAdhocConcessionDetail($insertString) {
         
        $query = "INSERT INTO adhoc_concession_detail 
                  (studentId,feeClassId,feeHeadId,isVariable,concessionAmount)
                  VALUES  
                  $insertString ";
                   
         return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }  
    
    
    public function insertAdhocConcessionMaster($table,$date,$studentId,$classId,$userId,$comment,$amount,$condition='') {
         
        $query = "$table 
                      `adhoc_concession_master`
                  SET
                      dateOfEntry = '$date', 
                      studentId ='$studentId', 
                      feeClassId ='$classId', 
                      userId='$userId', 
                      description='$comment', 
                      adhocAmount='$amount'
                  $condition";
                   
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
    
    public function getCountFeeHead($feeClassId='',$quotaId='',$isLeet='',$havingConditon='',$feeHeadCondition='',$isVariable=0,$isLeetChk='') {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $hConditon = " fhv.feeHeadId ";
        if($isLeet=='1') {
          $hConditon=" fhv.feeHeadValueId HAVING feeHeadAmt > 0";
        }
        if($havingConditon!='') {
          $hConditon = " fhv.feeHeadId HAVING ".$havingConditon;
        }
          
        if($isVariable=='') {
          $isVariable=0;  
        }
        
        if($isLeetChk=='') {
          $isLeetChk="3,$isLeet";  
        }
        
        $query = "SELECT
                        tt.feeId, tt.feeHeadId, tt.classId, tt.headName, tt.headAbbr, 
                        tt.sortingOrder, tt.isVariable, tt.feeHeadAmt, tt.isLeet
                  FROM
                      (SELECT     
                            DISTINCT fhv.feeHeadValueId AS feeId, fhv.feeHeadId, fhv.classId, 
                            fh.headName, fh.headAbbr, fh.sortingOrder, fh.isVariable, fhv.isLeet,
                            IF(IFNULL(fhv.quotaId,'')='".$quotaId."',
                                (IF(fhv.isLeet=3,fhv.feeHeadAmount, IF(fhv.isLeet=1,IF('".$isLeet."'=1,fhv.feeHeadAmount,0), 
                                    IF(fhv.isLeet=2,IF('".$isLeet."'=0,fhv.feeHeadAmount,0),0)))),
                                (IF(IFNULL(fhv.quotaId,'')='',
                                (IF(fhv.isLeet=3,fhv.feeHeadAmount, IF(fhv.isLeet=1,IF('".$isLeet."'=1,fhv.feeHeadAmount,0), 
                                     IF(fhv.isLeet=2,IF('".$isLeet."'=0,fhv.feeHeadAmount,0),0)))),0))) AS feeHeadAmt
                      FROM 
                            fee_head fh, fee_head_values fhv 
                      WHERE
                            fhv.feeHeadId  = fh.feeHeadId AND
                            fh.instituteId = '".$instituteId."' AND
                            fhv.classId    = '".$feeClassId."'  AND
                            fhv.feeHeadAmount <> 0 AND
                            fh.isVariable  = '$isVariable' 
                            $feeHeadCondition
                      GROUP BY
                           $hConditon) AS tt 
                  WHERE 
                        tt.isLeet IN ($isLeetChk)         
                  ORDER BY
                        tt.sortingOrder ASC";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getStudentFeeHeadDetail($feeClassId='',$feeId='',$studentId='') {  
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT
                        DISTINCT tt.feeId, tt.feeHeadId, tt.feeHeadAmt, tt.classId, 
                        tt.headName, tt.headAbbr, tt.sortingOrder, tt.isVariable, tt.isConsessionable, tt.isRefundable
                  FROM
                      (SELECT
                            fhv.feeHeadValueId AS feeId, fhv.feeHeadId, fhv.feeHeadAmount AS feeHeadAmt, fhv.classId, 
                            fh.headName, fh.headAbbr, fh.sortingOrder, fh.isVariable, fh.isConsessionable, fh.isRefundable
                       FROM
                            fee_head fh 
                            INNER JOIN fee_head_values fhv ON fh.feeHeadId = fhv.feeHeadId
                       WHERE
                            fh.instituteId = '".$instituteId."' AND
                            fh.isVariable  = '0' AND 
                            fhv.classId = $feeClassId AND 
                            fhv.feeHeadValueId IN ($feeId)     
                       UNION
                       SELECT
                             smc.feeMiscId AS feeId, smc.feeHeadId, smc.charges  AS feeHeadAmt, smc.classId, 
                             fh.headName, fh.headAbbr, fh.sortingOrder, fh.isVariable, fh.isConsessionable, fh.isRefundable
                       FROM
                            fee_head fh INNER JOIN student_misc_fee_charges smc ON fh.feeHeadId = smc.feeHeadId
                       WHERE    
                            fh.instituteId = '".$instituteId."' AND
                            fh.isVariable  = '1'  AND 
                            smc.classId = $feeClassId AND 
                            smc.studentId = $studentId
                       ) AS tt 
                  ORDER BY
                        tt.sortingOrder ASC";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     public function getCountFeeHeadNew($feeClassId='',$quotaId='',$isLeet='',$havingConditon='',$feeHeadCondition='',$isVariable=0,$isLeetChk='') {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $hConditon = " fhv.feeHeadId ";
        if($isLeet=='1') {
          $hConditon=" fhv.feeHeadValueId HAVING feeHeadAmt > 0";
        }
        if($havingConditon!='') {
          $hConditon = " fhv.feeHeadId HAVING ".$havingConditon;
        }
          
        if($isVariable=='') {
          $isVariable=0;  
        }
        
        if($isLeetChk=='') {
          $isLeetChk="3,$isLeet";  
        }
        
        $query = "SELECT
                        tt.feeId, tt.feeHeadId, tt.classId, tt.headName, tt.headAbbr, 
                        tt.sortingOrder, tt.isVariable, tt.feeHeadAmt, tt.isLeet
                  FROM
                      (SELECT     
                            DISTINCT fhv.feeHeadValueId AS feeId, fhv.feeHeadId, fhv.classId, 
                            fh.headName, fh.headAbbr, fh.sortingOrder, fh.isVariable, fhv.isLeet,
                            fhv.feeHeadAmount AS feeHeadAmt 
                      FROM 
                            fee_head fh, fee_head_values fhv 
                      WHERE
                            fhv.feeHeadId  = fh.feeHeadId AND
                            fh.instituteId = '".$instituteId."' AND
                            fhv.classId    = '".$feeClassId."'  AND
                            fhv.feeHeadAmount <> 0 AND
                            fh.isVariable  = '$isVariable' AND
                            fhv.isLeet IN ($isLeetChk) 
                      $feeHeadCondition
                      GROUP BY
                           $hConditon) AS tt 
                  WHERE 
                        tt.isLeet IN ($isLeetChk)         
                  ORDER BY
                        tt.sortingOrder ASC";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     public function getStudentAdhocConcession($condition='') {  
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT
                       acm.adhocId, acm.dateOfEntry, acm.studentId, acm.feeClassId, acm.userId, acm.description, acm.adhocAmount,
                       acd.adhocDetailId, acd.feeHeadId, acd.isVariable, acd.concessionAmount
                  FROM
                       adhoc_concession_master acm, adhoc_concession_detail acd
                  WHERE    
                       acm.studentId = acd.studentId AND
                       acm.feeClassId = acd.feeClassId 
                  $condition     
                  ORDER BY
                       acd.adhocDetailId ";
                        
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
    
}

// $History: StudentConcessionManager.inc.php $
?>
