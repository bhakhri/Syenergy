<?php
//------------------------------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Collect Student Fee Manager" TABLE
//
// Author :Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class CollectStudentFeeManager {
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
//  THIS FUNCTION IS Add Fee Collection
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function addFeeReceiptCollection($insertValue) {
        global $sessionHandler;
        
        $userId = $sessionHandler->getSessionVariable('UserId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     
        $query = "INSERT INTO `fee_receipt`
                  (studentId, classId, feeType, feeCycleId, feeClassId, receiptNo, receiptDate, receiptStatus,
                   printRemarks, generalRemarks, totalAmountPaid, cashAmount, fine, userId, installmentCount,favouringBankBranchId, feePaid,
                   hostelDues, hostelFine, hostelConcession, hostelPaid, transportDues, transportFine, transportConcession, transportPaid,
                   duesPaid, applHostel, applHostelFine, applTransport, applTransportFine,isConessionFormat,isNew)
                  VALUES
                  $insertValue";
                   
        $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($returnStatus === true) { 
           $receiptId=SystemDatabaseManager::getInstance()->lastInsertId();
           $sessionHandler->setSessionVariable('IdToFeeReceipt',$receiptId);
           return true;
        }
        else {
           return false; 
        }
    }    
    
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Add Fee Payment Collection
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function addFeePaymentDetailCollection($insertValue) {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "INSERT INTO `fee_payment_detail`
                  (feeReceiptId,studentId,classId, feeCycleId,feeClassId, paymentInstrument,
                   instrumentNo,instrumentAmount,issuingBankId,instrumentDate,instrumentStatus)
                   VALUES
                  $insertValue";
                  
        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    

//--------------------------------------------------------------
//  THIS FUNCTION IS Add Fee Headwise Collection
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function addFeeHeadCollection($insertValue) {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "INSERT INTO `fee_head_collection`
                  (dateOfEntry,feeReceiptId,studentId,classId, feeCycleId,feeClassId,feeHeadId,feeHeadType,feeHeadAmount)
                  VALUES
                  $insertValue";
                  
        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Add Dues wise Collection
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function addFeeDuesCollection($insertValue) {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "INSERT INTO `fee_dues_collection`
                  (classId, studentId, charges, feeReceiptId)
                  VALUES
                  $insertValue";
                  
        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }    

//--------------------------------------------------------------
//  THIS FUNCTION IS Findout Receipt No.
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getReceiptNo($condition='') {
      
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();    
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                        COUNT(*) AS cnt
                  FROM 
                       `fee_receipt` f 
                  WHERE
                       $condition ";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }        
        
    
    public function getStudentDetailClass($condition,$feeClassId='',$tableName='') {
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $fieldName = '';
        if($tableName=='') {
          $tableName = ' student';  
          $fieldName = ", '0' AS isDelete ";
        }
        else { 
          $tableName = ' quarantine_student';
          $fieldName = ", '1' AS isDelete ";
        }
       
        
        $query = "SELECT
                        stu.studentId, stu.firstName, stu.lastName, stu.quotaId, stu.hostelRoomId, stu.isLeet, 
                        CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) AS studentName, 
                        IF(IFNULL(stu.regNo,'')='','".NOT_APPLICABLE_STRING."',stu.regNo) AS regNo,
                        IF(IFNULL(stu.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',stu.universityRollNo) AS universityRollNo,
                        IF(IFNULL(stu.fatherName,'')='','".NOT_APPLICABLE_STRING."',stu.fatherName) AS fatherName,
                        stu.rollNo, stu.fatherName, cls.classId, cls.instituteId, 
                        cls.className AS className, stu.studentStatus, 
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
                        $fieldName                                     
                  FROM
                        $tableName stu, class cls, study_period sp
                  WHERE
                        stu.classId = cls.classId
                        AND sp.studyPeriodId = cls.studyPeriodId
                  $condition ";
           // return $query;       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //--------------------------------------------------------------
//  THIS FUNCTION IS Findout Installment Count.
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getCountInstallment($condition='') {
      
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();    
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                        MAX(f.installmentCount) AS cnt
                  FROM 
                       `fee_receipt` f 
                  WHERE
                       $condition ";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Findout Previous Fee Payment Details
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
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
                       $condition ";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }    
    
    
    //--------------------------------------------------------------
//  THIS FUNCTION IS Findout Previous Hostel Payment Details
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getPreviousHostelPaymentDetail($condition='') {
      
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();    
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
  
        $query = "SELECT
                       IFNULL(SUM(IFNULL(f.hostelDues,0)-IFNULL(f.hostelConcession,0)),0) AS hostelPrevCharges,
                       IFNULL(SUM(IFNULL(f.hostelPaid,0)),0) AS hostelPrevPaid,
                       IFNULL(SUM(IFNULL(f.hostelFine,0)),0) AS hostelPrevFine,
                       IFNULL(SUM(IFNULL(f.applHostel,0)),0) AS hostelApplPaid 
                  FROM 
                       `fee_receipt` f 
                  WHERE
                       $condition ";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }  
    
//--------------------------------------------------------------------------------
// THIS FUNCTION fetch fee Cycle Classes  
//
// Author :Parveen Sharma
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------    
    public function getLastEntry() {
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance(); 
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT 
                            f.feeReceiptId, f.receiptDate, f.receiptNo, f.feeCycleId   
                  FROM       
                           fee_receipt f, class c 
                  WHERE 
                           f.receiptStatus NOT IN (4) AND 
                           c.classId = f.feeClassId AND
                           c.instituteId = $instituteId          
                  ORDER BY
                           f.feeReceiptId DESC    
                  LIMIT 0,1"; 
                        
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }    
//--------------------------------------------------------------------------------
// THIS FUNCTION fetch fee Cycle Classes  
//
// Author :Parveen Sharma
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------    
    public function getActiveFeeCycle() {
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance(); 
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT
						feeCycleId,
						cycleName,
						cycleAbbr,
						fromDate,
						toDate
					FROM fee_cycle
						WHERE NOW() BETWEEN fromDate AND toDate AND
								instituteId = $instituteId
						ORDER BY
                           feeCycleId DESC    
					LIMIT 0,1"; 
                        
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }     
//--------------------------------------------------------------
//  THIS FUNCTION IS Findout Previous Transport Payment Details
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getPreviousTransportPaymentDetail($condition='') {
      
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();    
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                       IFNULL(SUM(IFNULL(f.transportDues,0)-IFNULL(f.transportConcession,0)),0) AS transportPrevCharges,
                       IFNULL(SUM(IFNULL(f.transportPaid,0)),0) AS transportPrevPaid,
                       IFNULL(SUM(IFNULL(f.transportFine,0)),0) AS transportPrevFine,
                       IFNULL(SUM(IFNULL(f.applTransport,0)),0) AS transportApplPaid
                  FROM 
                       `fee_receipt` f 
                  WHERE
                       $condition ";
                  
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
    
    public function getStudentFeeHeadCollectionDetail($feeClassId='',$feeId='',$studentId='') {  
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT
                        DISTINCT tt.feeId, tt.feeHeadId, tt.feeHeadAmt, tt.classId, 
                        tt.headName, tt.headAbbr, tt.sortingOrder, tt.isVariable, tt.isConsessionable, tt.isRefundable
                  FROM
                      (SELECT
                            fhv.feeHeadValueId AS feeId, fhv.feeHeadId, fhv.feeHeadAmount AS feeHeadAmt, fhv.classId, 
                            fh.headName, fh.headAbbr, fh.sortingOrder, fh.isVariable, fh.isConsessionable, fh.isRefundable,
                            'N' AS isMisc 
                       FROM
                            fee_head fh INNER JOIN fee_head_values fhv ON fh.feeHeadId = fhv.feeHeadId
                       WHERE
                            fh.instituteId = '".$instituteId."' AND
                            fh.isVariable  = '0' AND 
                            fhv.classId = $feeClassId AND 
                            fhv.feeHeadValueId IN ($feeId)     
                       UNION
                       SELECT
                             IFNULL(smc.feeMiscId,'') AS feeId, fh.feeHeadId, IFNULL(smc.charges,'') AS feeHeadAmt, 
                             '$feeClassId' AS classId,  fh.headName, fh.headAbbr, fh.sortingOrder, fh.isVariable, 
                             fh.isConsessionable, fh.isRefundable, 
                             'Y' AS isMisc  
                       FROM
                            fee_head fh 
                            LEFT JOIN student_misc_fee_charges smc ON 
                                 (fh.feeHeadId = smc.feeHeadId AND smc.classId = $feeClassId AND smc.studentId = $studentId)
                       WHERE    
                            fh.instituteId = '".$instituteId."' AND
                            fh.isVariable  = '1'   
                       ) AS tt 
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
                            fee_head fh INNER JOIN fee_head_values fhv ON fh.feeHeadId = fhv.feeHeadId
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
    
    public function getStudentFeeHeadCollection($feeClassId='',$studentId='',$condition='') {  
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT     
                    DISTINCT 
                         fhc.feeReceiptId, fhc.studentId, fhc.classId, fhc.feeCycleId, fhc.feeClassId, 
                         fhc.feeHeadId, SUM(IFNULL(fhc.feeHeadAmount,0)) AS feeHeadAmt
                  FROM 
                         fee_head_collection fhc
                  WHERE
                         fhc.feeClassId = $feeClassId  AND 
                         fhc.studentId = $studentId AND
                         IFNULL(fhc.feeHeadAmount,0) <> 0 
                  $condition  
                  GROUP BY
                         fhc.feeClassId, fhc.studentId, fhc.feeHeadId     
                  ORDER BY
                         fhc.feeClassId, fhc.studentId, fhc.feeHeadId";
                        
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
                        f.receiptStatus NOT IN (3,4) AND
                        f.feeClassId = '$feeClassId' AND
                        f.studentId = '$studentId'
                  $condition ";
             // return $query;           
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
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
    

    
//--------------------------------------------------------------
//  THIS FUNCTION IS Findout student payment receipt Details. 
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getStudentClassDetail($condition='') {
      
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();    
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                       s.studentId, c.classId, c.batchId, c.degreeId, c.branchId,
                       IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                       IF(IFNULL(s.regNo,'')='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,
                       IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                       CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) As studentName,
                       IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName, s.isLeet,
                       sp.studyPeriodId, sp.periodValue, s.isMigration, s.migrationClassId
                  FROM 
                       student s,class c, study_period sp
                  WHERE
                       s.classId = c.classId AND
                       c.studyPeriodId = sp.studyPeriodId
                  $condition ";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }   
 
 
//--------------------------------------------------------------
//  THIS FUNCTION IS Findout student receipt Details. 
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getStudentReceiptPrintDetail($condition='') {
      
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();    
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                        ins.instituteName, ins.instituteCode, ins.instituteAbbr,  IFNULL(ins.instituteLogo,'') AS instituteLogo,
                        CONCAT(IFNULL(ins.instituteAddress1,''),' ',IFNULL(ins.instituteAddress2,'')) AS insAddress,
                        IFNULL((SELECT cityName FROM city WHERE cityId = IFNULL(ins.cityId,'')),'') AS insCityName, 
                        IFNULL((SELECT stateName FROM states WHERE stateId = IFNULL(ins.stateId,'')),'') AS insStateName, 
                        IFNULL((SELECT countryName FROM countries WHERE countryId = IFNULL(ins.countryId,'')),'') AS insCountryName, 
                        IFNULL(ins.pin,'') AS insPinCode, IFNULL(ins.employeePhone,'') AS insContactNo, 
                        IFNULL(ins.instituteEmail,'') AS insEmail, IFNULL(ins.instituteWebsite,'') AS insWebsite,
                        
                        f.feeReceiptId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(studentGender='M','Mr.','Miss') AS by1, IF(studentGender='M','S/o','D/o') AS by2, 
                        IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName, 
                        s.isLeet, IFNULL(s.quotaId,'') AS quotaId, s.rollNo, s.regNo, sp.periodName AS semester, b.branchName,
                        f.receiptNo, f.receiptDate, f.feeType, f.totalAmountPaid, f.fine, f.cashAmount, IFNULL(f.feePaid,0) AS feePaid,
                        s.studentId, f.feeClassId, f.classId, f.feeCycleId, f.installmentCount, f.favouringBankBranchId,
                        f.hostelDues, f.hostelFine, f.hostelConcession, IFNULL(f.hostelPaid,0) AS hostelPaid, IFNULL(duesPaid,0) AS duesPaid,
                        IFNULL(applHostel,'') AS applHostel, IFNULL(applHostelFine,'') AS applHostelFine,
                        f.transportDues, f.transportFine, f.transportConcession, IFNULL(f.transportPaid,0) AS transportPaid,
                        IFNULL(applTransport,'') AS applTransport, IFNULL(applTransportFine,'') AS applTransportFine,
                        IFNULL(f.isConessionFormat,'') AS isConessionFormat
                  FROM 
                       institute ins, student s, `fee_receipt` f, class c, study_period sp, branch b
                  WHERE
                       c.instituteId = ins.instituteId AND 
                       s.studentId = f.studentId AND
                       c.classId = f.feeClassId  AND
                       sp.studyPeriodId = c.studyPeriodId AND
                       b.branchId = c.branchId
                  $condition ";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }        
    

//--------------------------------------------------------------------------------
// THIS FUNCTION fetch fee Cycle Classes  
//
// Author :Parveen Sharma
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------    
    public function getFeeReceiptClasses($condition='',$orderBy=' c.branchId, c.studyPeriodId',$studentId='',$id='') {
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance(); 
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        if($orderBy=='') {
          $orderBy = " c.branchId, c.studyPeriodId";  
        }
        
        $query = "SELECT 
                        DISTINCT 
                             c.classId, c.instituteId, c.sessionId, c.className,
                             IFNULL(fsf.charges,'') AS charges, IFNULL(fsf.concession,'') AS concession,
                             IFNULL(fsf.comments,'') AS comments, IFNULL(fsf.facilityType,'') AS facilityType
                  FROM 
                        study_period sp, class c
                        LEFT JOIN fee_student_facility fsf ON fsf.classId = c.classId AND fsf.studentId = '$studentId' AND fsf.facilityType = '$id'
                  WHERE 
                        c.instituteId = '".$instituteId."' AND
                        c.studyPeriodId = sp.studyPeriodId AND
                        c.isActive IN (1,2,3)
                  $condition  
                  ORDER BY 
                        $orderBy";
                        
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }         
    
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Findout student payment receipt Details. 
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getStudentPaymentPrintDetail($condition='') {
      
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();    
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                        fpd.feePaymentDetailId, fpd.studentId, fpd.feeCycleId, 
                        fpd.installment, fpd.paymentInstrument, fpd.instrumentNo, 
                        fpd.instrumentAmount, fpd.instrumentDate, fpd.issuingBankId, 
                        fpd.receiptStatus, fpd.instrumentStatus, fpd.instrumentClearanceDate, 
                        fpd.feeReceiptId, fpd.classId, fpd.feeClassId, b.bankAbbr
                  FROM 
                       fee_payment_detail fpd, bank b
                  WHERE
                       b.bankId = fpd.issuingBankId
                  $condition ";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }       
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Findout student payment receipt Details. 
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getStudentPaymentDetail($condition='') {
      
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();    
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                        fpd.feePaymentDetailId, fpd.studentId, fpd.feeCycleId, 
                        fpd.installment, fpd.paymentInstrument, fpd.instrumentNo, 
                        fpd.instrumentAmount, fpd.instrumentDate, fpd.issuingBankId, 
                        fpd.receiptStatus, fpd.instrumentStatus, fpd.instrumentClearanceDate, 
                        fpd.feeReceiptId, fpd.classId, fpd.feeClassId
                  FROM 
                       fee_payment_detail fpd
                  WHERE
                       
                  $condition ";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }       
   
//--------------------------------------------------------------
//  THIS FUNCTION IS Add Student Facility (Hostel / Transport)
//  Author :Parveen Sharma
//  Created on : (29-May-2009)
//  Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------        
    public function addFeeFacility($insertValue,$facilityType) {
        global $sessionHandler;
     
        if($facilityType==3) {
           $query = "INSERT INTO `fee_prev_dues`
                      (classId, studentId, charges, comments)
                      VALUES
                      $insertValue"; 
        }
        else {
           $query = "INSERT INTO `fee_student_facility`
                      (classId, studentId, charges, concession, facilityType, comments)
                      VALUES
                      $insertValue";
        }
        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
    }

//--------------------------------------------------------------
//  THIS FUNCTION IS Delete Facility (Hostel / Transport)
//  Author :Parveen Sharma
//  Created on : (29-May-2009)
//  Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------        
    public function deleteFeeFacility($studentId,$facilityType) {
        global $sessionHandler;
     
        if($facilityType==3) {    
          $query = "DELETE FROM `fee_prev_dues` WHERE studentId=$studentId ";  
        }
        else {
          $query = "DELETE FROM `fee_student_facility` WHERE studentId=$studentId AND facilityType=$facilityType";
        }
                   
        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
    }
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Delete Facility (Hostel / Transport)
//  Author :Parveen Sharma
//  Created on : (29-May-2009)
//  Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------        
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
    
//--------------------------------------------------------------------------------
// THIS FUNCTION fetch All Class to set the Pending Due
// Author :Parveen Sharma
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------    
    public function getFeePendingDuesClasses($condition='',$orderBy='',$studentId='') {
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance(); 
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        if($orderBy=='') {
          $orderBy = " c.branchId, c.studyPeriodId";  
        }
        
        $query = "SELECT 
                        DISTINCT 
                             c.classId, c.instituteId, c.sessionId, c.className,
                             IFNULL(fsf.charges,'') AS charges, IFNULL(fsf.comments,'') AS comments 
                  FROM 
                        study_period sp, class c
                        LEFT JOIN fee_prev_dues  fsf ON 
                        (fsf.classId = c.classId AND fsf.studentId = '$studentId')
                  WHERE 
                        c.instituteId = '".$instituteId."' AND
                        c.studyPeriodId = sp.studyPeriodId AND
                        c.isActive IN (1,2,3)
                  $condition  
                  ORDER BY 
                        $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
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
    
     public function getFeeHeadCaption($condition='') {
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();  
        
        $query = "SELECT headCaptionId, headCaption FROM fee_head_caption ";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getMiscFeeHead($condition='') {
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();  
        
        $query = "SELECT 
                      feeMiscId, feeHeadId, classId, studentId, charges, userId, dated, reason
                  FROM 
                      student_misc_fee_charges
                  WHERE    
                      $condition ";
                     
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    
}
?>
