<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Collect Fees Manager" TABLE
//
// Author :Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class CollectFeesManager {
	private static $instance = null;
	
//----------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "QuotaManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
	private function __construct() {
	}

//----------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "QuotaManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------    
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

    
//--------------------------------------------------------------
//  THIS FUNCTION IS Add Student Facility (Hostel / Transport)
//  Author :Parveen Sharma
//  Created on : (29-May-2009)
//  Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------        
    public function addFeeFacility($insertValue) {
        global $sessionHandler;
     
        $query = "INSERT INTO `fee_student_facility`
                  (classId, studentId, charges, concession, facilityType, comments)
                  VALUES
                  $insertValue";
                   
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
     
        $query = "DELETE FROM `fee_student_facility` WHERE studentId=$studentId AND facilityType=$facilityType";
                   
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
                   applHostel, applHostelFine, applTransport, applTransportFine, isNew)
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
//  THIS FUNCTION IS Delete Fee Receipt
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function deleteReceipt($id) {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "UPDATE `fee_receipt` SET receiptStatus = '4' WHERE feeReceiptId = '$id' ";
                  
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
                        COUNT(*) AS cnt
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
                       
                       IFNULL(SUM(hostelFine),0) AS prevHostelFine,
                       IFNULL(SUM(transportFine),0) AS prevTransportFine,
                       IFNULL(SUM(fine),0) AS prevFeeFine
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
    public function getFeeCycleClasses($condition='',$orderBy=' c.branchId, c.studyPeriodId') {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        if($orderBy=='') {
          $orderBy = " c.branchId, c.studyPeriodId";  
        }
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT 
                        DISTINCT 
                            fc.feeCycleClassId, fc.feeCycleId, fc.classId, fc.instituteId, fc.sessionId, c.className, f.cycleName, f.cycleAbbr      
                  FROM 
                        fee_cycle f, fee_cycle_class fc, class c    
                  WHERE 
                        f.feeCycleId = fc.feeCycleId AND
                        fc.classId = c.classId AND
                        fc.instituteId = f.instituteId AND
                        fc.instituteId = c.instituteId AND
                        fc.sessionId   = c.sessionId AND 
                        fc.instituteId = '".$instituteId."' 
                        $condition  
                        $studentRNoCondition
                  ORDER BY 
                        $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }  

    
//--------------------------------------------------------------------------------
// THIS FUNCTION fetch student Pervious Class Detail   
//
// Author :Parveen Sharma
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------    
    public function getStudentPreviousClassDetail($condition) {
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                         DISTINCT c.classId, c.className
                  FROM 
                         class c, fee_head_values fh, study_period sp
                  WHERE 
                         c.instituteId = $instituteId 
                         AND c.studyPeriodId = sp.studyPeriodId
                         AND fh.classId = c.classId  
                  $condition ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Student Detail
//
// Author :Parveen Sharma
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
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
                        stu.studentId, stu.firstName, stu.lastName, stu.quotaId, stu.hostelRoomId, stu.isLeet, stu.universityRollNo,
                        CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) AS studentName,
                        IF(IFNULL(stu.fatherName,'')='','".NOT_APPLICABLE_STRING."',stu.fatherName) AS fatherName,
                        stu.rollNo, stu.fatherName, cls.classId, cls.instituteId, 
                        cls.className AS className, studentStatus,  
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
                        $tableName stu,
                        class cls,
                        study_period sp
                  WHERE
                        stu.classId = cls.classId
                        AND sp.studyPeriodId = cls.studyPeriodId
                  $condition ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED for Student Fee Head Detail
//
// Author :Parveen Sharma
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    public function getStudentFeeHeadDetail($studentId,$feeClassId='',$quotaId='',$isLeet='',$feeReceiptId='',$feeId='') {
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        if($feeReceiptId=='') {
          $feeReceiptId = -1;
        }
        $condition = "AND fhc.feeReceiptId = $feeReceiptId"; 
        
        $query = "SELECT            
                        DISTINCT  s.studentId, s.classId AS currentClassId, s.rollNo,            
                        CONCAT(s.firstName,' ',s.lastName) AS studentName, 
                        s.isLeet, IFNULL(s.quotaId,'') AS quotaId,
                        tt.feeId, tt.feeHeadId, tt.classId AS feeClassId, 
                        tt.headName, tt.headAbbr, tt.sortingOrder, tt.isVariable , tt.feeHeadAmt, tt.feeHeadAmt1, 
                        IF(tt.isVariable=0,IF(tt.concession=-1,'',tt.feeHeadAmt-tt.concession),'') AS concession,
                        IF(tt.headCollectionAmount=-1,'',tt.headCollectionAmount) AS headCollectionAmount,
                        IF(tt.feeHeadCollectionId=-1,'',tt.feeHeadCollectionId) AS feeHeadCollectionId,
                        tt.concessionType, tt.concessionValue, tt.discountValue 
                  FROM
                        student s, 
                        (SELECT     
                            DISTINCT fhv.feeHeadValueId AS feeId, fhv.feeHeadId, fhv.classId, 
                            fh.headName, fh.headAbbr, fh.sortingOrder, fh.isVariable,
                            fhv.feeHeadAmount AS feeHeadAmt1,
                            IF(IFNULL(fhv.quotaId,'')='".$quotaId."',
                                (IF(fhv.isLeet=3,fhv.feeHeadAmount, IF(fhv.isLeet=1,IF('".$isLeet."'=1,fhv.feeHeadAmount,-1), 
                                    IF(fhv.isLeet=2,IF('".$isLeet."'=0,fhv.feeHeadAmount,-1),-1)))),
                                (IF(IFNULL(fhv.quotaId,'')='',
                                (IF(fhv.isLeet=3,fhv.feeHeadAmount, IF(fhv.isLeet=1,IF('".$isLeet."'=1,fhv.feeHeadAmount,-1), 
                                     IF(fhv.isLeet=2,IF('".$isLeet."'=0,fhv.feeHeadAmount,-1),-1)))),-1))) AS feeHeadAmt,
                            IFNULL(discountValue,-1) AS concession,
                            IFNULL(fhc.feeHeadAmount,-1) AS headCollectionAmount, IFNULL(fhc.feeHeadType,-1) AS feeHeadCollectionId,
                            sc.concessionType, sc.concessionValue, sc.discountValue 
                        FROM 
                            fee_head fh,
                            fee_head_values fhv 
                            LEFT JOIN student_concession sc   ON (sc.studentId=$studentId AND sc.classId=fhv.classId AND sc.feeHeadId=fhv.feeHeadId)
                            LEFT JOIN fee_head_collection fhc ON (fhc.studentId=$studentId AND fhc.feeClassId=fhv.classId AND 
                                                                   fhc.feeHeadId=fhv.feeHeadId AND fhc.feeHeadType=1 $condition) 
                        WHERE
                            fhv.feeHeadId  = fh.feeHeadId AND
                            fh.instituteId = '".$instituteId."' AND
                            fhv.classId    = '".$feeClassId."'  AND
                            fh.isVariable  = 0  AND
                            fhv.feeHeadValueId IN ($feeId)
                        UNION
                        SELECT     
                               DISTINCT sm.feeMiscId AS feeId, sm.feeHeadId, sm.classId, 
                               fh.headName, fh.headAbbr, fh.sortingOrder, fh.isVariable, 
                               '' AS feeHeadAmt1, 
                               IFNULL(sm.charges,'-1') AS feeHeadAmt, '' AS concession,
                               IFNULL(fhc.feeHeadAmount,0) AS headCollectionAmount, IFNULL(fhc.feeHeadType,-1) AS feeHeadCollectionId,
                               '','',''
                         FROM 
                               fee_head fh, student_misc_fee_charges sm
                               LEFT JOIN fee_head_collection fhc  ON (fhc.studentId=sm.studentId AND fhc.feeClassId=sm.classId AND 
                                                                      fhc.feeHeadId=sm.feeHeadId AND fhc.feeHeadType=1 $condition)     
                         WHERE
                               fh.feeHeadId  = sm.feeHeadId AND     
                               fh.instituteId = '".$instituteId."' AND
                               sm.classId    = '".$feeClassId."'   AND
                               fh.isVariable = 1 AND
                               sm.studentId = $studentId 
                         UNION
                         SELECT     
                               DISTINCT '', '', fhcc.classId, '',
                               '', '', '', '', 
                               '', '',
                               IFNULL(fhcc.feeHeadAmount,0) AS headCollectionAmount, IFNULL(fhcc.feeHeadType,-1) AS feeHeadCollectionId,
                               '','',''
                         FROM 
                               fee_head_collection fhcc
                         WHERE
                               fhcc.studentId = $studentId AND
                               fhcc.feeClassId    = '".$feeClassId."' AND
                               fhcc.feeHeadType IN (2,3) AND
                               fhcc.feeReceiptId = $feeReceiptId   
                        ) AS tt
                    WHERE
                        s.studentId = $studentId AND
                        tt.feeHeadAmt != -1     
                    ORDER BY
                        tt.sortingOrder ASC ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED for Student Previous Fee Detail
//
// Author :Parveen Sharma
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    public function getStudentPreviousFeeDetail($studentId,$feeClassId='',$quotaId='',$isLeet='') {
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT     
                        s.studentId, 
                        SUM(tt.feeHeadAmt) AS feeHeadAmt, 
                        SUM(IF(tt.isVariable=0,IF(tt.concession=-1,'',tt.feeHeadAmt-tt.concession),'')) AS concession  
                  FROM
                        student s, 
                       (SELECT     
                            fhv.feeHeadValueId AS feeId, fhv.feeHeadId, fhv.classId, 
                            fh.headName, fh.headAbbr, fh.sortingOrder, fh.isVariable,
                            IF(IFNULL(fhv.quotaId,'')='".$quotaId."',
                                (IF(fhv.isLeet=3,fhv.feeHeadAmount, IF(fhv.isLeet=1,IF('".$isLeet."'=1,fhv.feeHeadAmount,-1), 
                                    IF(fhv.isLeet=2,IF('".$isLeet."'=0,fhv.feeHeadAmount,-1),-1)))),
                                (IF(IFNULL(fhv.quotaId,'')='',
                                (IF(fhv.isLeet=3,fhv.feeHeadAmount, IF(fhv.isLeet=1,IF('".$isLeet."'=1,fhv.feeHeadAmount,-1), 
                                     IF(fhv.isLeet=2,IF('".$isLeet."'=0,fhv.feeHeadAmount,-1),-1)))),-1))) AS feeHeadAmt,
                            IFNULL(discountValue,-1) AS concession
                        FROM 
                            fee_head fh,
                            fee_head_values fhv 
                            LEFT JOIN student_concession sc ON (sc.studentId=$studentId AND sc.classId=fhv.classId AND sc.feeHeadId=fhv.feeHeadId )
                        WHERE
                            fhv.feeHeadId  = fh.feeHeadId AND
                            fh.instituteId = '".$instituteId."' AND
                            fhv.classId    IN (".$feeClassId.")  AND
                            fh.isVariable  = 0
                        UNION
                        SELECT     
                               sm.feeMiscId AS feeId, sm.feeHeadId, sm.classId, 
                               fh.headName, fh.headAbbr, fh.sortingOrder, fh.isVariable, 
                               IFNULL(sm.charges,'-1') AS feeHeadAmt, '' AS concession
                         FROM 
                                fee_head fh, student_misc_fee_charges sm
                         WHERE
                                fh.feeHeadId  = sm.feeHeadId AND     
                                fh.instituteId = '".$instituteId."' AND
                                sm.classId    IN (".$feeClassId.")   AND
                                fh.isVariable = 1 AND
                                sm.studentId = $studentId     
                       ) AS tt
                    WHERE
                        s.studentId = $studentId AND
                        tt.feeHeadAmt != -1     
                    GROUP BY
                        s.studentId ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
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
                        f.hostelDues, f.hostelFine, f.hostelConcession, IFNULL(f.hostelPaid,0) AS hostelPaid, 
                        IFNULL(applHostel,'') AS applHostel, IFNULL(applHostelFine,'') AS applHostelFine,
                        f.transportDues, f.transportFine, f.transportConcession, IFNULL(f.transportPaid,0) AS transportPaid,
                        IFNULL(applTransport,'') AS applTransport, IFNULL(applTransportFine,'') AS applTransportFine
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
                       IFNULL(SUM(IFNULL(f.hostelFine,0)),0) AS hostelPrevFine
                  FROM 
                       `fee_receipt` f 
                  WHERE
                       $condition ";
                  
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
                       IFNULL(SUM(IFNULL(f.transportFine,0)),0) AS transportPrevFine
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
    
    public function getCountFeeHead($studentId,$feeClassId='',$quotaId='',$isLeet='',$feeReceiptId='',$havingConditon='') {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        if($feeReceiptId=='') {
          $feeReceiptId = -1;
        }
        $condition = "AND fhc.feeReceiptId = $feeReceiptId"; 
        
        $query = "SELECT             
                        DISTINCT  s.studentId, s.classId AS currentClassId, s.rollNo,            
                        CONCAT(s.firstName,' ',s.lastName) AS studentName, 
                        s.isLeet, IFNULL(s.quotaId,'') AS quotaId,
                        tt.feeId, tt.feeHeadId, tt.classId AS feeClassId, 
                        tt.headName, tt.headAbbr, tt.sortingOrder, tt.isVariable , tt.feeHeadAmt, 
                        IF(tt.isVariable=0,IF(tt.concession=-1,'',tt.feeHeadAmt-tt.concession),'') AS concession,
                        IF(tt.headCollectionAmount=-1,'',tt.headCollectionAmount) AS headCollectionAmount,
                        IF(tt.feeHeadCollectionId=-1,'',tt.feeHeadCollectionId) AS feeHeadCollectionId,
                        tt.concessionType, tt.concessionValue, tt.discountValue 
                  FROM
                        student s, 
                        (SELECT     
                            DISTINCT fhv.feeHeadValueId AS feeId, fhv.feeHeadId, fhv.classId, 
                            fh.headName, fh.headAbbr, fh.sortingOrder, fh.isVariable,
                            IF(IFNULL(fhv.quotaId,'')='".$quotaId."',
                                (IF(fhv.isLeet=3,fhv.feeHeadAmount, IF(fhv.isLeet=1,IF('".$isLeet."'=1,fhv.feeHeadAmount,-1), 
                                    IF(fhv.isLeet=2,IF('".$isLeet."'=0,fhv.feeHeadAmount,-1),-1)))),
                                (IF(IFNULL(fhv.quotaId,'')='',
                                (IF(fhv.isLeet=3,fhv.feeHeadAmount, IF(fhv.isLeet=1,IF('".$isLeet."'=1,fhv.feeHeadAmount,-1), 
                                     IF(fhv.isLeet=2,IF('".$isLeet."'=0,fhv.feeHeadAmount,-1),-1)))),-1))) AS feeHeadAmt,
                            IFNULL(discountValue,-1) AS concession,
                            IFNULL(fhc.feeHeadAmount,-1) AS headCollectionAmount, IFNULL(fhc.feeHeadType,-1) AS feeHeadCollectionId,
                            sc.concessionType, sc.concessionValue, sc.discountValue
                        FROM 
                            fee_head fh,
                            fee_head_values fhv 
                            LEFT JOIN student_concession sc   ON (sc.studentId=$studentId AND sc.classId=fhv.classId AND sc.feeHeadId=fhv.feeHeadId)
                            LEFT JOIN fee_head_collection fhc ON (fhc.studentId=$studentId AND fhc.feeClassId=fhv.classId AND 
                                                                   fhc.feeHeadId=fhv.feeHeadId AND fhc.feeHeadType=1 $condition) 
                        WHERE
                            fhv.feeHeadId  = fh.feeHeadId AND
                            fh.instituteId = '".$instituteId."' AND
                            fhv.classId    = '".$feeClassId."'  AND
                            fh.isVariable  = 0  
                         GROUP BY
                            fhv.feeHeadId 
                         HAVING 
                            $havingConditon 
                        ) AS tt
                    WHERE
                        s.studentId = $studentId 
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
    
    public function getQuotaFeeHead($studentId,$feeClassId='',$quotaId='',$isLeet='',$feeReceiptId='',$feeHeadCondition='') {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        if($feeReceiptId=='') {
          $feeReceiptId = -1;
        }
        $condition = "AND fhc.feeReceiptId = $feeReceiptId"; 
        
        $query = "SELECT             
                        DISTINCT  s.studentId, s.classId AS currentClassId, s.rollNo,            
                        CONCAT(s.firstName,' ',s.lastName) AS studentName, 
                        s.isLeet, IFNULL(s.quotaId,'') AS quotaId,
                        tt.feeId, tt.feeHeadId, tt.classId AS feeClassId, 
                        tt.headName, tt.headAbbr, tt.sortingOrder, tt.isVariable , tt.feeHeadAmt, 
                        IF(tt.isVariable=0,IF(tt.concession=-1,'',tt.feeHeadAmt-tt.concession),'') AS concession,
                        IF(tt.headCollectionAmount=-1,'',tt.headCollectionAmount) AS headCollectionAmount,
                        IF(tt.feeHeadCollectionId=-1,'',tt.feeHeadCollectionId) AS feeHeadCollectionId
                  FROM
                        student s, 
                        (SELECT     
                            DISTINCT fhv.feeHeadValueId AS feeId, fhv.feeHeadId, fhv.classId, 
                            fh.headName, fh.headAbbr, fh.sortingOrder, fh.isVariable,
                            IF(IFNULL(fhv.quotaId,'')='".$quotaId."',
                                (IF(fhv.isLeet=3,fhv.feeHeadAmount, IF(fhv.isLeet=1,IF('".$isLeet."'=1,fhv.feeHeadAmount,-1), 
                                    IF(fhv.isLeet=2,IF('".$isLeet."'=0,fhv.feeHeadAmount,-1),-1)))),
                                (IF(IFNULL(fhv.quotaId,'')='',
                                (IF(fhv.isLeet=3,fhv.feeHeadAmount, IF(fhv.isLeet=1,IF('".$isLeet."'=1,fhv.feeHeadAmount,-1), 
                                     IF(fhv.isLeet=2,IF('".$isLeet."'=0,fhv.feeHeadAmount,-1),-1)))),-1))) AS feeHeadAmt,
                            IFNULL(discountValue,-1) AS concession,
                            IFNULL(fhc.feeHeadAmount,-1) AS headCollectionAmount, IFNULL(fhc.feeHeadType,-1) AS feeHeadCollectionId
                        FROM 
                            fee_head fh,
                            fee_head_values fhv 
                            LEFT JOIN student_concession sc   ON (sc.studentId=$studentId AND sc.classId=fhv.classId AND sc.feeHeadId=fhv.feeHeadId)
                            LEFT JOIN fee_head_collection fhc ON (fhc.studentId=$studentId AND fhc.feeClassId=fhv.classId AND 
                                                                   fhc.feeHeadId=fhv.feeHeadId AND fhc.feeHeadType=1 $condition) 
                        WHERE
                            fhv.feeHeadId  = fh.feeHeadId AND
                            fh.instituteId = '".$instituteId."' AND
                            fhv.classId    = '".$feeClassId."'  AND
                            fh.isVariable  = 0  
                            $feeHeadCondition
                        ) AS tt
                    WHERE
                        s.studentId = $studentId AND
                        tt.feeHeadAmt != -1     
                    ORDER BY
                        tt.sortingOrder ASC ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getConcessionFeeHead($condition='') {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        
        $query = "SELECT             
                        feeHeadValueId, feeHeadId, feeHeadAmount, quotaId, isLeet, classId
                  FROM
                        fee_head_values
                  WHERE
                        $condition";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
        
}
?>
