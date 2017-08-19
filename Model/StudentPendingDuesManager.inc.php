<?php
//Edited-Madhav Bhasin--------------------
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "student and teacher_comment" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class StudentPendingDuesManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "StudentConcessionManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "StudentConcessionManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
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
    
   
    public function getStudentList($condition='', $limit = '', $orderBy=' studentName') {          
        
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
                  FROM 
                       `class` c, student s
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
        
        $queryStr = "SELECT
                        DISTINCT stu.studentId, stu.firstName, stu.lastName, stu.quotaId, stu.hostelRoomId, stu.isLeet, 
                        CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) AS studentName, 
                        IF(IFNULL(stu.regNo,'')='','".NOT_APPLICABLE_STRING."',stu.regNo) AS regNo,
                        IF(IFNULL(stu.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',stu.universityRollNo) AS universityRollNo,
                        IF(IFNULL(stu.fatherName,'')='','".NOT_APPLICABLE_STRING."',stu.fatherName) AS fatherName,
                        stu.rollNo, cls.classId, cls.instituteId,  cls.className AS className,
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
                                      className
                                FROM 
                                      class c 
                                WHERE 
                                      c.instituteId = cls.instituteId AND c.universityId = cls.universityId AND
                                      c.batchId = cls.batchId AND c.degreeId = cls.degreeId AND 
                                      c.branchId = cls.branchId AND c.classId='$feeClassId'),-1) AS feeClassName,              
                        IFNULL((SELECT 
                                      c.studyPeriodId
                                FROM 
                                      class c 
                                WHERE 
                                      c.instituteId = cls.instituteId AND c.universityId = cls.universityId AND
                                      c.batchId = cls.batchId AND c.degreeId = cls.degreeId AND 
                                      c.branchId = cls.branchId AND c.classId='$feeClassId'),-1) AS feeStudyPeriodId                                      
                  FROM
                        <tableName> stu, class cls, study_period sp
                  WHERE
                        stu.classId = cls.classId
                        AND sp.studyPeriodId = cls.studyPeriodId   
                        $condition";
        
        $query = $queryStr;
        $query = str_replace("<tableName>","`student`",$query);
        $query .= " UNION ".$queryStr;
        $query = str_replace("<tableName>","`quarantine_student`",$query);
        $query .= " ORDER BY studentId DESC ";
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
                       DISTINCT studentId, feeClassId, feeHeadId, isVariable, concessionAmount
                  FROM
                       adhoc_concession_detail
                  WHERE      
                       $condition     
                  ORDER BY    
                       feeClassId, studentId ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function getClassList($classId='',$condition='') { 
       
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                       DISTINCT cc.classId
                  FROM
                       class cc
                  WHERE
                       cc.instituteId = $instituteId AND    
                       CONCAT_WS('~',cc.degreeId,cc.branchId,cc.batchId) IN  
                       (SELECT
                               DISTINCT  CONCAT_WS('~',c.degreeId,c.branchId,c.batchId) 
                          FROM
                               class c
                          WHERE    
                               c.instituteId = $instituteId AND
                               c.classId IN ($classId))
                  $condition";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getClassStudentList($condition='',$orderBy='rollNo',$condition1='0') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
	    // Left out
        if($condition1=='0') {   
	      if(trim($condition)=='') {
            $condition .= " stu.studentStatus = 1 ";
	      }
	      else {
            $condition .= " AND stu.studentStatus = 1 "; 	
	      }
	    }
        
        $queryStr =  "SELECT 
		                   DISTINCT  stu.studentId, stu.classId, stu.quotaId, stu.isLeet, stu.isMigration, stu.migrationClassId,
                           CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) AS studentName, stu.studentStatus,
                           IF(IFNULL(stu.regNo,'')='','".NOT_APPLICABLE_STRING."',stu.regNo) AS regNo,
                           IF(IFNULL(stu.rollNo,'')='','".NOT_APPLICABLE_STRING."',stu.rollNo) AS rollNo,
                           IF(IFNULL(stu.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',stu.universityRollNo) AS universityRollNo,
                           IF(IFNULL(stu.fatherName,'')='','".NOT_APPLICABLE_STRING."',stu.fatherName) AS fatherName, 
                           IF(IFNULL(stu.studentMobileNo,'')='','".NOT_APPLICABLE_STRING."',stu.studentMobileNo) AS studentMobileNo,
                           IFNULL(stu.studentPhoto,'') AS studentPhoto <DeleteType>
		              FROM 
                           <tableName> stu
                      WHERE
                          $condition";
                          
         $query = $queryStr;
         $query = str_replace("<DeleteType>",", '0' AS deleteType",$query);
         $query = str_replace("<tableName>","`student`",$query);
         if($condition1=='1') {   
           $query .= " UNION ".$queryStr;
           $query = str_replace("<DeleteType>",", '1' AS deleteType",$query);
           $query = str_replace("<tableName>","`quarantine_student`",$query);
         }
         $query .= " ORDER BY $orderBy ";
         
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getFacility($condition='') {
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();     
     
        $query = "SELECT 
                        fsf.classId, fsf.studentId, fsf.charges, fsf.concession, fsf.facilityType, fsf.comments
                  FROM      
                        `fee_student_facility` fsf 
                  WHERE 
                        $condition ";
                   
         return $systemDatabaseManager->executeQuery($query,"Query: $query");    
    }
    
    public function getStudentConcession($feeClassId='',$studentId='',$feeHeadId='',$isLeet='',$condition) {  
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT
                         fcc.categoryId, fcc.categoryName, fcc.categoryOrder,
                         fcv.classId, fcv.feeHeadId, fcv.isLeet, fcv.concessionType, fcv.concessionAmount 
                   FROM 
                         fee_concession_category fcc, fee_student_concession_mapping fscm, 
                         fee_classwise_concession_values fcv 
                   WHERE       
                         fcc.categoryId = fscm.categoryId  AND
                         fcv.classId    = fscm.classId     AND
                         fcv.categoryId = fscm.categoryId  AND
                         fscm.classId   = $feeClassId      AND
                         fscm.studentId = $studentId       AND
                         fcv.feeHeadId IN ($feeHeadId)     AND
                         fcv.isLeet = $isLeet
                   $condition      
                   ORDER BY 
                         fcv.feeHeadId, fcc.categoryOrder"; 
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getStudentFinalConcession($feeClassId='',$studentId='',$feeHeadId='',$isLeet='',$condition) {  
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT
                        tt.categoryId, tt.categoryName, tt.categoryOrder,
                        tt.classId, tt.feeHeadId, tt.isLeet, tt.concessionType, tt.concessionAmount
                  FROM
                      (SELECT
                             fcc.categoryId, fcc.categoryName, fcc.categoryOrder,
                             fcv.classId, fcv.feeHeadId, fcv.isLeet, fcv.concessionType, fcv.concessionAmount
                       FROM 
                             fee_concession_category fcc, fee_student_concession_mapping fscm, 
                             fee_classwise_concession_values fcv 
                       WHERE       
                             fcc.categoryId = fscm.categoryId  AND
                             fcv.classId    = fscm.classId     AND
                             fcv.categoryId = fscm.categoryId  AND
                             fscm.classId   = $feeClassId      AND
                             fscm.studentId = $studentId       AND
                             fcv.feeHeadId IN ($feeHeadId)     AND
                             fcv.isLeet = $isLeet
                       UNION
                       SELECT
                             fcc.categoryId, fcc.categoryName, fcc.categoryOrder,
                             fcv.classId, fcv.feeHeadId, fcv.isLeet, fcv.concessionType, fcv.concessionAmount 
                       FROM 
                             fee_concession_category fcc, fee_student_concession_mapping fscm, 
                             fee_classwise_concession_values fcv 
                       WHERE       
                             fcc.categoryId = fscm.categoryId  AND
                             fcv.classId    = fscm.classId     AND
                             fcv.categoryId = fscm.categoryId  AND
                             fscm.classId   = $feeClassId      AND
                             fscm.studentId = $studentId       AND
                             fcv.feeHeadId IN ($feeHeadId)     AND
                             fcv.isLeet = 3
                             $condition
                       ) AS tt      
                   ORDER BY 
                       tt.feeHeadId, tt.categoryOrder"; 
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //--------------------------------------------------------------------------------
// THIS FUNCTION fetch Pending Due Classes
//
// Author :Parveen Sharma
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------    
    public function getPendingDuesList($condition='',$orderBy='') {
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance(); 
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        if($orderBy=='') {
          $orderBy = " branchId, studyPeriodId";  
        }
        
        $query = "SELECT
                        tt.classId, tt.studentId, tt.className, tt.periodName, tt.studyPeriodId, tt.branchId,
                        IFNULL(SUM(tt.dues),'') AS dues, IFNULL(SUM(tt.paid),'') AS paid
                  FROM    
                     (SELECT 
                                 fsf.studentId, c.classId, c.instituteId, c.sessionId, c.className, 
                                 c.branchId, sp.periodName, sp.studyPeriodId,
                                 IFNULL(fsf.charges,'') AS dues, IFNULL(fdc.charges,'') AS paid
                      FROM 
                            study_period sp, class c, 
                            fee_prev_dues fsf 
                            LEFT JOIN fee_dues_collection fdc ON (fsf.studentId = fdc.studentId AND fsf.classId = fdc.classId)
                            LEFT JOIN fee_receipt f ON (f.feeReceiptId = fdc.feeReceiptId AND f.receiptStatus NOT IN (3,4) )
                      WHERE 
                            c.instituteId = '".$instituteId."' AND
                            c.studyPeriodId = sp.studyPeriodId AND
                            fsf.classId = c.classId
                      $condition ) AS tt
                  GROUP BY
                      tt.studentId, tt.classId       
                  ORDER BY 
                        $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
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
    
    
    public function getStudentPaidReceipt($feeClassId='',$studentId='') {  
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT     
                        DISTINCT f.feeReceiptId, f.studentId, f.classId, f.feeClassId
                  FROM 
                        `fee_receipt` f  
                  WHERE
                        f.receiptStatus NOT IN (3,4)  AND
                        f.feeClassId = '$feeClassId' AND
                        f.studentId = '$studentId'
                  $condition ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getPreviousFeePaymentDetail($condition='') {
      
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();    
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                       IFNULL(SUM(hostelPaid),0) AS prevHostelPaid,
                       IFNULL(SUM(transportPaid),0) AS prevTransportPaid,
                       IFNULL(SUM(feePaid),0) AS prevFeePaid,
                       IFNULL(SUM(duesPaid),0) AS prevDuesPaid, 
                       
                       IFNULL(SUM(hostelFine),0) AS prevHostelFine,
                       IFNULL(SUM(transportFine),0) AS prevTransportFine,
                       IFNULL(SUM(fine),0) AS prevFeeFine     
                  FROM 
                       `fee_receipt` f 
                  WHERE
                       f.receiptStatus NOT IN (3,4)  
                       $condition ";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }    
    
}

// $History: StudentConcessionManager.inc.php $
?>
