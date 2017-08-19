<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Collect Fees Manager" TABLE
//
// Author :Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeeReportManager {
	private static $instance = null;
	
//----------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "QuotaManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
	private function __construct() {
	}

//----------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "QuotaManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
//  THIS FUNCTION IS Findout Receipt No.
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getStudentFeeReceipt($condition='',$orderBy=' fr.receiptDate', $limit = '') {
     
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();    
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        if($orderBy=='') {
          $orderBy = " fr.receiptDate";  
        }
        
        $query = "SELECT
                        CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) AS studentName, stu.isLeet, IFNULL(stu.quotaId,'') AS quotaId,
                        IF(IFNULL(stu.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',stu.universityRollNo) AS universityRollNo,
                        IF(IFNULL(stu.regNo,'')='','".NOT_APPLICABLE_STRING."',stu.regNo) AS regNo,
                        IF(IFNULL(stu.rollNo,'')='','".NOT_APPLICABLE_STRING."',stu.rollNo) AS rollNo,
                        IF(IFNULL(stu.fatherName,'')='','".NOT_APPLICABLE_STRING."',stu.fatherName) AS fatherName,
                        cls.className, fc.cycleName, fc.cycleAbbr,
                        fr.feeReceiptId, fr.feeClassId, fr.classId, fr.studentId, fr.feeType, fr.installmentCount, 
                        fr.feeCycleId, fr.receiptNo, fr.receiptDate, fr.receiptStatus, fr.instrumentStatus, 
                        fr.reasonOfCancellation, fr.totalAmountPaid, fr.cashAmount,
                        fr.fine, fr.feeDue, fr.feePaid, fr.isNew, fr.isConessionFormat,   fr.duesPaid,
                        fr.hostelDues, fr.hostelConcession, fr.hostelPaid, fr.hostelFine, fr.applHostel, fr.applHostelFine,
                        fr.transportDues, fr.transportConcession, fr.transportPaid, fr.transportFine, fr.applTransport, fr.applTransportFine
                  FROM 
                       student stu, `fee_receipt` fr, class cls, fee_cycle fc  
                  WHERE
                       cls.instituteId = $instituteId AND  
                       fr.studentId  = stu.studentId AND
                       fr.feeClassId = cls.classId  AND
                       fc.feeCycleId = fr.feeCycleId 
                  $condition
                  ORDER BY 
                        $orderBy $limit";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------
//  THIS FUNCTION IS Findout Previous Fee Payment Details
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
                      $condition";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }    
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Student Detail
//
// Author :Parveen Sharma
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
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
                        stu.studentId, stu.firstName, stu.lastName, stu.quotaId, stu.hostelRoomId, stu.isLeet, stu.universityRollNo,
                        CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) AS studentName,
                        IF(IFNULL(stu.fatherName,'')='','".NOT_APPLICABLE_STRING."',stu.fatherName) AS fatherName,
                        stu.rollNo, stu.fatherName, cls.classId, cls.instituteId, SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-3) AS className,
                        cls.studyPeriodId, cls.universityId, cls.batchId, cls.degreeId, cls.branchId, sp.periodName,
                        IF(IFNULL(stu.transportFacility,'')='',0,stu.transportFacility)  AS transportFacility,    
                        IF(IFNULL(stu.hostelFacility,'')='',0,stu.hostelFacility) AS hostelFacility, 
                        IF(IFNULL(stu.busStopId,'')='','0',stu.busStopId) AS busStopId,
                        IF(IFNULL(stu.hostelRoomId,'')='','0',stu.hostelRoomId) AS hostelRoomId,
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    public function getStudentFeeHeadDetail($studentId,$feeClassId='',$quotaId='',$isLeet='',$feeReceiptId='') {
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        if($feeReceiptId=='') {
          $feeReceiptId = -1;
        }
        $condition = "AND fhc.feeReceiptId = $feeReceiptId"; 
        
        $inValue = 3;
        if($isLeet!='') {
          $inValue .= ",".$isLeet;  
        }
        
        $query = "SELECT
                        ff.studentId, IFNULL(SUM(ff.feeHeadAmt),0) AS feeHeadAmt, IFNULL(SUM(ff.concession),0) AS  concession
                  FROM      
                         (SELECT             
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
                                    LEFT JOIN student_concession sc ON (sc.studentId=$studentId AND sc.classId=fhv.classId AND 
                                                                        sc.feeHeadId=fhv.feeHeadId)
                                    LEFT JOIN fee_head_collection fhc  ON (fhc.studentId=$studentId AND fhc.feeClassId=fhv.classId AND 
                                                                           fhc.feeHeadId=fhv.feeHeadId AND fhc.feeHeadType=1 
                                                                           $condition) 
                                WHERE
                                    fhv.feeHeadId  = fh.feeHeadId AND
                                    fh.instituteId = '".$instituteId."' AND
                                    fhv.classId    = '".$feeClassId."'  AND
                                    fh.isVariable  = 0  AND
                                    fhv.isLeet IN ($inValue)    
                                UNION
                                SELECT     
                                       DISTINCT sm.feeMiscId AS feeId,  sm.feeHeadId, sm.classId, 
                                       fh.headName, fh.headAbbr, fh.sortingOrder, fh.isVariable, 
                                       IFNULL(sm.charges,'-1') AS feeHeadAmt, '' AS concession,
                                       IFNULL(fhc.feeHeadAmount,0) AS headCollectionAmount, IFNULL(fhc.feeHeadType,-1) AS feeHeadCollectionId
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
                                       DISTINCT '', '', fhcc.classId, 
                                       '', '', '', '', 
                                       '', '',
                                       IFNULL(fhcc.feeHeadAmount,0) AS headCollectionAmount, IFNULL(fhcc.feeHeadType,-1) AS feeHeadCollectionId
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
                                tt.sortingOrder ASC) AS ff 
                         GROUP BY
                                ff.studentId ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED for Student Previous Fee Detail
//
// Author :Parveen Sharma
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    public function getStudentPreviousFeeDetail($studentId,$feeCycleId='',$feeClassId='',$quotaId='',$isLeet='') {
        
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
                            fhv.feeHeadValueId AS feeId, fhv.feeCycleId, fhv.feeHeadId, fhv.classId, 
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
                            fee_head_values fhv LEFT JOIN student_concession sc ON (sc.studentId=$studentId AND 
                               sc.feeCycleId=fhv.feeCycleId  AND sc.classId=fhv.classId AND sc.feeHeadId=fhv.feeHeadId )
                        WHERE
                            fhv.feeHeadId  = fh.feeHeadId AND
                            fh.instituteId = '".$instituteId."' AND
                            fhv.feeCycleId IN (".$feeCycleId.")  AND
                            fhv.classId    IN (".$feeClassId.")  AND
                            fh.isVariable  = 0
                        UNION
                        SELECT     
                               sm.feeMiscId AS feeId, sm.feeCycleId, sm.feeHeadId, sm.classId, 
                               fh.headName, fh.headAbbr, fh.sortingOrder, fh.isVariable, 
                               IFNULL(sm.charges,'-1') AS feeHeadAmt, '' AS concession
                         FROM 
                                fee_head fh, student_misc_fee_charges sm
                         WHERE
                                fh.feeHeadId  = sm.feeHeadId AND     
                                fh.instituteId = '".$instituteId."' AND
                                sm.feeCycleId IN (".$feeCycleId.")   AND
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
                        
                        IFNULL(s.firstName,'') AS firstName, IFNULL(s.lastName,'') AS lastName,
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getStudentClassDetail($condition='') {
      
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();    
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                       s.studentId, c.classId, c.batchId, c.degreeId, c.branchId
                  FROM 
                       student s,class c
                  WHERE
                       s.classId = c.classId
                  $condition ";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }   

//--------------------------------------------------------------
//  THIS FUNCTION IS Findout Previous Hostel Payment Details
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
}
?>
