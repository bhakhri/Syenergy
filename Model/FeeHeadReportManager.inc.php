<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Fee Head Wise Report Manager" TABLE
//
// Author :Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeeHeadReportManager {
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

    
    public function getFeeHeadList($conditions='') {
       
        global $sessionHandler;      
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        DISTINCT IFNULL(fh.feeHeadId,'') AS feeHeadId, IFNULL(fh.headName,'') AS headName, 
                        IFNULL(fh.headAbbr,'') AS headAbbr, fc.feeHeadType    
                  FROM 
                        fee_head_collection fc LEFT JOIN fee_head fh ON (fc.feeHeadId = fh.feeHeadId)
                  WHERE     
                       fh.instituteId = $instituteId
                       $conditions
                  ORDER BY 
                        feeHeadType, sortingOrder";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    public function getStudentList($conditions='', $orderBy='', $limit='',$consolidatedId=0,$studentStatus='3') {
        
        global $sessionHandler;      
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');         
        
        if($orderBy=='') {
          $orderBy = " LENGTH(rollNo)+0, rollNo  ASC ";
        }
        
        if($studentStatus=='') {
          $studentStatus=3;  
        }
        
        $groupBy='';
        $fieldName1='';
        $fieldName2='';
        if($consolidatedId==0) {
          $fieldName1 = ',tt.receiptNo, tt.receiptDate ';
          $fieldName2 = ',fr.receiptNo, fr.receiptDate ';
          $groupBy = ', fr.receiptNo, fr.receiptDate  ';
          $orderBy1 = " tt.receiptDate, tt.receiptNo ";
          if($orderBy!='') {
            $orderBy1 .= ",".$orderBy;
          }
          $orderBy = $orderBy1;
        }
        
        $tableName='student';
        if($studentStatus==2) {
           $tableName='quarantine_student';    
        }
        
        $query = "SELECT
                             tt.studentId, tt.feeClassId, tt.rollNo, tt.universityRollNo, tt.studentName, 
                             tt.className, tt.studentFine, tt.fatherName, tt.regNo,  tt.feeReceiptId, tt.cashAmount,
                             IFNULL(tt.hostelDues,0) AS hostelDues, IFNULL(tt.hostelFine,'') AS hostelFine, 
                             IFNULL(tt.hostelConcession,'') AS hostelConcession, IFNULL(tt.hostelPaid,'') AS hostelPaid, 
                             IFNULL(tt.transportDues,'') AS transportDues, IFNULL(tt.transportFine,'') AS transportFine, 
                             IFNULL(tt.transportConcession,'') AS transportConcession, IFNULL(tt.transportPaid,'') AS transportPaid, 
                             IFNULL(tt.applHostel,'') AS applHostel, IFNULL(tt.applHostelFine,'') AS applHostelFine, 
                             IFNULL(tt.applTransport,'') AS applTransport, IFNULL(tt.applTransportFine,'') AS applTransportFine, tt.feePaid
                             $fieldName1
                      FROM      
                          (SELECT 
                                fr.studentId, fr.feeClassId, fr.feeReceiptId, fr.cashAmount,
                                IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                                IF(IFNULL(s.regNo,'')='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,     
                                IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                                IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                                CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName, c.className,  SUM(fr.fine) AS studentFine,
                                SUM(feePaid) AS feePaid,  
                                SUM(fr.hostelDues) AS hostelDues, SUM(fr.hostelFine) AS hostelFine, SUM(fr.hostelConcession) AS hostelConcession, 
                                SUM(fr.hostelPaid) AS hostelPaid, SUM(fr.transportDues) AS transportDues, 
                                SUM(fr.transportFine) AS transportFine, SUM(fr.transportConcession) AS transportConcession, 
                                SUM(fr.transportPaid) AS transportPaid, SUM(fr.applHostel) AS applHostel, SUM(fr.applHostelFine) AS applHostelFine,
                                SUM(fr.applTransport) AS applTransport, SUM(fr.applTransportFine) AS applTransportFine  
                                $fieldName2      
                           FROM 
                                fee_receipt fr, $tableName s, class c
                           WHERE
                                fr.receiptStatus NOT IN (3,4) AND
                                fr.feeClassId   = c.classId AND
                                fr.studentId = s.studentId  AND    
                                c.instituteId = $instituteId
                           $conditions      
                           GROUP BY
                                fr.feeClassId, fr.studentId $groupBy) AS tt
                      ORDER BY 
                            $orderBy
                      $limit";
       
       if($studentStatus==3) {
            $query = "SELECT
                             tt.studentId, tt.feeClassId, tt.rollNo, tt.universityRollNo, tt.studentName, 
                             tt.className, tt.studentFine, tt.fatherName, tt.regNo,  tt.feeReceiptId, tt.cashAmount,
                             IFNULL(tt.hostelDues,0) AS hostelDues, IFNULL(tt.hostelFine,'') AS hostelFine, 
                             IFNULL(tt.hostelConcession,'') AS hostelConcession, IFNULL(tt.hostelPaid,'') AS hostelPaid, 
                             IFNULL(tt.transportDues,'') AS transportDues, IFNULL(tt.transportFine,'') AS transportFine, 
                             IFNULL(tt.transportConcession,'') AS transportConcession, IFNULL(tt.transportPaid,'') AS transportPaid, 
                             IFNULL(tt.applHostel,'') AS applHostel, IFNULL(tt.applHostelFine,'') AS applHostelFine, 
                             IFNULL(tt.applTransport,'') AS applTransport, IFNULL(tt.applTransportFine,'') AS applTransportFine, tt.feePaid
                             $fieldName1
                      FROM      
                          (SELECT 
                                fr.studentId, fr.feeClassId, fr.feeReceiptId, fr.cashAmount,  'S' AS studentStatus, 
                                IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                                IF(IFNULL(s.regNo,'')='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,     
                                IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                                IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                                CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName, c.className,  SUM(fr.fine) AS studentFine,
                                SUM(feePaid) AS feePaid,  
                                SUM(fr.hostelDues) AS hostelDues, SUM(fr.hostelFine) AS hostelFine, SUM(fr.hostelConcession) AS hostelConcession, 
                                SUM(fr.hostelPaid) AS hostelPaid, SUM(fr.transportDues) AS transportDues, 
                                SUM(fr.transportFine) AS transportFine, SUM(fr.transportConcession) AS transportConcession, 
                                SUM(fr.transportPaid) AS transportPaid, SUM(fr.applHostel) AS applHostel, SUM(fr.applHostelFine) AS applHostelFine,
                                SUM(fr.applTransport) AS applTransport, SUM(fr.applTransportFine) AS applTransportFine  
                                $fieldName2      
                           FROM 
                                fee_receipt fr, student s, class c
                           WHERE
                                fr.receiptStatus NOT IN (3,4) AND
                                fr.feeClassId   = c.classId AND
                                fr.studentId = s.studentId  AND    
                                c.instituteId = $instituteId
                           $conditions      
                           GROUP BY
                                fr.feeClassId, fr.studentId $groupBy
                           UNION
                           SELECT 
                                fr.studentId, fr.feeClassId, fr.feeReceiptId, fr.cashAmount,     'D' AS studentStatus, 
                                IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                                IF(IFNULL(s.regNo,'')='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,     
                                IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                                IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                                CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName, c.className,  SUM(fr.fine) AS studentFine,
                                SUM(feePaid) AS feePaid,  
                                SUM(fr.hostelDues) AS hostelDues, SUM(fr.hostelFine) AS hostelFine, SUM(fr.hostelConcession) AS hostelConcession, 
                                SUM(fr.hostelPaid) AS hostelPaid, SUM(fr.transportDues) AS transportDues, 
                                SUM(fr.transportFine) AS transportFine, SUM(fr.transportConcession) AS transportConcession, 
                                SUM(fr.transportPaid) AS transportPaid, SUM(fr.applHostel) AS applHostel, SUM(fr.applHostelFine) AS applHostelFine,
                                SUM(fr.applTransport) AS applTransport, SUM(fr.applTransportFine) AS applTransportFine  
                                $fieldName2      
                           FROM 
                                fee_receipt fr, quarantine_student s, class c
                           WHERE
                                fr.receiptStatus NOT IN (3,4) AND
                                fr.feeClassId   = c.classId AND
                                fr.studentId = s.studentId  AND    
                                c.instituteId = $instituteId
                           $conditions      
                           GROUP BY
                                fr.feeClassId, fr.studentId $groupBy) AS tt
                      ORDER BY 
                            $orderBy
                      $limit";
        }
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
   public function getStudentWiseFeeHeadCollection($conditions='',$consolidatedId=0,$studentStatus='3') {
     
        global $sessionHandler;      
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $groupBy='';
        $fieldName1='';
        $fieldName2='';
        if($consolidatedId==0) {
          $fieldName1 = ',tt.receiptNo, tt.receiptDate ';
          $fieldName2 = ',fr.receiptNo, fr.receiptDate ';
          $groupBy = ', fr.receiptNo, fr.receiptDate  ';
        }
        
        if($studentStatus=='') {
          $studentStatus=3;  
        }
     
        global $sessionHandler;      
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        
        $tableName='student';
        if($studentStatus==2) {
           $tableName='quarantine_student';    
        }
        
        $query = "SELECT
                             tt.studentId, tt.feeClassId, tt.feeHeadId, tt.feeHeadType, tt.feeHeadAmount
                             $fieldName1
                      FROM       
                           (SELECT 
                                   fc.studentId, fc.feeClassId, IFNULL(fc.feeHeadId,'') AS feeHeadId, fc.feeHeadType,
                                   IFNULL(SUM(fc.feeHeadAmount),0) AS feeHeadAmount  $fieldName2
                            FROM 
                                   fee_receipt fr, fee_head_collection fc, fee_head fh, $tableName s
                            WHERE
                                   fr.studentId = s.studentId AND
                                   fh.instituteId = $instituteId AND 
                                   fr.feeReceiptId = fc.feeReceiptId AND
                                   fh.feeHeadId = fc.feeHeadId   AND
                                   fc.feeHeadType IN (1)
                            $conditions      
                            GROUP BY
                                   fc.feeClassId, fc.studentId $groupBy, fc.feeHeadId,fc.feeHeadType  
                            UNION
                            SELECT 
                                   fc.studentId, fc.feeClassId, IFNULL(fc.feeHeadId,'') AS feeHeadId, fc.feeHeadType,
                                   IFNULL(SUM(fc.feeHeadAmount),0) AS feeHeadAmount  $fieldName2
                            FROM 
                                   fee_receipt fr, fee_head_collection fc, $tableName s
                            WHERE
                                   fr.studentId = s.studentId AND
                                   fr.feeReceiptId = fc.feeReceiptId AND
                                   fc.feeHeadType IN (2,3)
                            $conditions      
                            GROUP BY
                                    fc.feeClassId, fc.studentId $groupBy, fc.feeHeadType) AS tt          
                      ORDER BY 
                             feeClassId, studentId, feeHeadType ";
        
        
        if($studentStatus==3) {    
            $query = "SELECT
                             tt.studentId, tt.feeClassId, tt.feeHeadId, tt.feeHeadType, tt.feeHeadAmount
                             $fieldName1
                      FROM       
                           (SELECT 
                                   fc.studentId, fc.feeClassId, IFNULL(fc.feeHeadId,'') AS feeHeadId, fc.feeHeadType, 'S' AS studentStatus,   
                                   IFNULL(SUM(fc.feeHeadAmount),0) AS feeHeadAmount  $fieldName2   
                            FROM 
                                   fee_receipt fr, fee_head_collection fc, fee_head fh, student s
                            WHERE
                                   fr.studentId = s.studentId AND
                                   fh.instituteId = $instituteId AND 
                                   fr.feeReceiptId = fc.feeReceiptId AND
                                   fh.feeHeadId = fc.feeHeadId   AND
                                   fc.feeHeadType IN (1)
                            $conditions      
                            GROUP BY
                                   fc.feeClassId, fc.studentId $groupBy, fc.feeHeadId,fc.feeHeadType  
                            UNION
                            SELECT 
                                   fc.studentId, fc.feeClassId, IFNULL(fc.feeHeadId,'') AS feeHeadId, fc.feeHeadType, 'S' AS studentStatus,   
                                   IFNULL(SUM(fc.feeHeadAmount),0) AS feeHeadAmount  $fieldName2
                            FROM 
                                   fee_receipt fr, fee_head_collection fc, student s
                            WHERE
                                   fr.studentId = s.studentId AND
                                   fr.feeReceiptId = fc.feeReceiptId AND
                                   fc.feeHeadType IN (2,3)
                            $conditions      
                            GROUP BY
                                    fc.feeClassId, fc.studentId $groupBy, fc.feeHeadType
                            UNION
                            SELECT 
                                   fc.studentId, fc.feeClassId, IFNULL(fc.feeHeadId,'') AS feeHeadId, fc.feeHeadType, 'D' AS studentStatus,   
                                   IFNULL(SUM(fc.feeHeadAmount),0) AS feeHeadAmount  $fieldName2
                            FROM 
                                   fee_receipt fr, fee_head_collection fc, fee_head fh, quarantine_student s
                            WHERE
                                   fr.studentId = s.studentId AND
                                   fh.instituteId = $instituteId AND 
                                   fr.feeReceiptId = fc.feeReceiptId AND
                                   fh.feeHeadId = fc.feeHeadId   AND
                                   fc.feeHeadType IN (1)
                            $conditions      
                            GROUP BY
                                   fc.feeClassId, fc.studentId $groupBy, fc.feeHeadId,fc.feeHeadType  
                            UNION
                            SELECT 
                                   fc.studentId, fc.feeClassId, IFNULL(fc.feeHeadId,'') AS feeHeadId, fc.feeHeadType, 'D' AS studentStatus, 
                                   IFNULL(SUM(fc.feeHeadAmount),0) AS feeHeadAmount  $fieldName2
                            FROM 
                                   fee_receipt fr, fee_head_collection fc, quarantine_student s
                            WHERE
                                   fr.studentId = s.studentId AND
                                   fr.feeReceiptId = fc.feeReceiptId AND
                                   fc.feeHeadType IN (2,3)
                            $conditions      
                            GROUP BY
                                    fc.feeClassId, fc.studentId $groupBy, fc.feeHeadType) AS tt          
                      ORDER BY 
                             feeClassId, studentId, feeHeadType ";
        }
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    
     public function getFeeHeadWiseCollection($conditions='',$studentStatus='3') {
     
        global $sessionHandler;      
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        if($studentStatus=='') {
          $studentStatus=3;  
        }
        
        $tableName='student';
        if($studentStatus==2) {
           $tableName='quarantine_student';    
        }
        
        $query = "SELECT
                        tt.feeHeadId, tt.feeHeadType, tt.feeHeadAmount
                  FROM       
                       (SELECT 
                                IFNULL(fc.feeHeadId,'') AS feeHeadId, fc.feeHeadType,
                                IFNULL(SUM(fc.feeHeadAmount),0) AS feeHeadAmount  
                        FROM 
                                fee_receipt fr, fee_head_collection fc, fee_head fh, $tableName s
                        WHERE
                                s.studentId = fr.studentId AND
                                fr.feeReceiptId = fc.feeReceiptId AND
                                fh.feeHeadId = fc.feeHeadId   AND
                                fc.feeHeadType IN (1)
                        $conditions      
                        GROUP BY
                               fc.feeHeadId,fc.feeHeadType  
                        UNION
                        SELECT 
                               IFNULL(fc.feeHeadId,'') AS feeHeadId, fc.feeHeadType,
                               IFNULL(SUM(fc.feeHeadAmount),0) AS feeHeadAmount  
                        FROM 
                               fee_receipt fr, fee_head_collection fc, $tableName s 
                        WHERE
                               s.studentId = fr.studentId AND  
                               fr.feeReceiptId = fc.feeReceiptId AND
                               fc.feeHeadType IN (2,3)
                        $conditions      
                        GROUP BY
                               fc.feeHeadId,fc.feeHeadType) AS tt          
                  ORDER BY 
                        feeHeadId, feeHeadType ";
                        
         if($studentStatus==3) {   
            $query = "SELECT
                        tt.feeHeadId, tt.feeHeadType, tt.feeHeadAmount
                  FROM       
                       (SELECT 
                                IFNULL(fc.feeHeadId,'') AS feeHeadId, fc.feeHeadType,
                                IFNULL(SUM(fc.feeHeadAmount),0) AS feeHeadAmount ,'S' AS studentStatus
                        FROM 
                                fee_receipt fr, fee_head_collection fc, fee_head fh, student s
                        WHERE
                                s.studentId = fr.studentId AND
                                fr.feeReceiptId = fc.feeReceiptId AND
                                fh.feeHeadId = fc.feeHeadId   AND
                                fc.feeHeadType IN (1)
                        $conditions      
                        GROUP BY
                               fc.feeHeadId,fc.feeHeadType  
                        UNION
                        SELECT 
                               IFNULL(fc.feeHeadId,'') AS feeHeadId, fc.feeHeadType,
                               IFNULL(SUM(fc.feeHeadAmount),0) AS feeHeadAmount  ,'S' AS studentStatus
                        FROM 
                               fee_receipt fr, fee_head_collection fc, student s 
                        WHERE
                               s.studentId = fr.studentId AND  
                               fr.feeReceiptId = fc.feeReceiptId AND
                               fc.feeHeadType IN (2,3)
                        $conditions      
                        GROUP BY
                               fc.feeHeadId,fc.feeHeadType
                        UNION
                        SELECT 
                                IFNULL(fc.feeHeadId,'') AS feeHeadId, fc.feeHeadType,
                                IFNULL(SUM(fc.feeHeadAmount),0) AS feeHeadAmount   ,'D' AS studentStatus
                        FROM 
                                fee_receipt fr, fee_head_collection fc, fee_head fh, quarantine_student s
                        WHERE
                                s.studentId = fr.studentId AND
                                fr.feeReceiptId = fc.feeReceiptId AND
                                fh.feeHeadId = fc.feeHeadId   AND
                                fc.feeHeadType IN (1)
                        $conditions      
                        GROUP BY
                               fc.feeHeadId,fc.feeHeadType  
                        UNION
                        SELECT 
                               IFNULL(fc.feeHeadId,'') AS feeHeadId, fc.feeHeadType,
                               IFNULL(SUM(fc.feeHeadAmount),0) AS feeHeadAmount    ,'D' AS studentStatus   
                        FROM 
                               fee_receipt fr, fee_head_collection fc, quarantine_student s 
                        WHERE
                               s.studentId = fr.studentId AND  
                               fr.feeReceiptId = fc.feeReceiptId AND
                               fc.feeHeadType IN (2,3)
                        $conditions      
                        GROUP BY
                               fc.feeHeadId,fc.feeHeadType) AS tt          
                  ORDER BY 
                        feeHeadId, feeHeadType ";               
                        
         }
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getFeeHeadWiseAdavanceList($conditions='', $orderBy='', $limit='',$studentStatus='3') {
        
        global $sessionHandler;      
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        
        if($studentStatus=='') {
          $studentStatus=3;  
        }
        
        $tableName='student';
        if($studentStatus==2) {
           $tableName='quarantine_student';    
        }
        
        $query = "SELECT  
                         tt.studentFine, 
                         IFNULL(tt.hostelDues,0) AS hostelDues, IFNULL(tt.hostelFine,'') AS hostelFine, 
                         IFNULL(tt.hostelConcession,'') AS hostelConcession, IFNULL(tt.hostelPaid,'') AS hostelPaid, 
                         IFNULL(tt.transportDues,'') AS transportDues, IFNULL(tt.transportFine,'') AS transportFine, 
                         IFNULL(tt.transportConcession,'') AS transportConcession, IFNULL(tt.transportPaid,'') AS transportPaid, 
                         IFNULL(tt.applHostel,'') AS applHostel, IFNULL(tt.applHostelFine,'') AS applHostelFine, 
                         IFNULL(tt.applTransport,'') AS applTransport, IFNULL(tt.applTransportFine,'') AS applTransportFine, tt.feePaid
                  FROM      
                      (SELECT 
                            SUM(fr.fine) AS studentFine, SUM(feePaid) AS feePaid,
                            SUM(fr.hostelDues) AS hostelDues, SUM(fr.hostelFine) AS hostelFine, SUM(fr.hostelConcession) AS hostelConcession, 
                            SUM(fr.hostelPaid) AS hostelPaid, SUM(fr.transportDues) AS transportDues, 
                            SUM(fr.transportFine) AS transportFine, SUM(fr.transportConcession) AS transportConcession, 
                            SUM(fr.transportPaid) AS transportPaid, SUM(fr.applHostel) AS applHostel, SUM(fr.applHostelFine) AS applHostelFine,
                            SUM(fr.applTransport) AS applTransport, SUM(fr.applTransportFine) AS applTransportFine        
                       FROM 
                            fee_receipt fr, $tableName s, class c
                       WHERE
                            fr.feeClassId   = c.classId AND
                            fr.studentId = s.studentId  
                       $conditions) AS tt
                  $orderBy $limit";
            
        
        if($studentStatus==3) {
            $query = "SELECT  
                             tt.studentFine, 
                             IFNULL(tt.hostelDues,0) AS hostelDues, IFNULL(tt.hostelFine,'') AS hostelFine, 
                             IFNULL(tt.hostelConcession,'') AS hostelConcession, IFNULL(tt.hostelPaid,'') AS hostelPaid, 
                             IFNULL(tt.transportDues,'') AS transportDues, IFNULL(tt.transportFine,'') AS transportFine, 
                             IFNULL(tt.transportConcession,'') AS transportConcession, IFNULL(tt.transportPaid,'') AS transportPaid, 
                             IFNULL(tt.applHostel,'') AS applHostel, IFNULL(tt.applHostelFine,'') AS applHostelFine, 
                             IFNULL(tt.applTransport,'') AS applTransport, IFNULL(tt.applTransportFine,'') AS applTransportFine, tt.feePaid
                      FROM      
                          (SELECT 
                                SUM(fr.fine) AS studentFine, SUM(feePaid) AS feePaid,
                                SUM(fr.hostelDues) AS hostelDues, SUM(fr.hostelFine) AS hostelFine, SUM(fr.hostelConcession) AS hostelConcession, 
                                SUM(fr.hostelPaid) AS hostelPaid, SUM(fr.transportDues) AS transportDues, 
                                SUM(fr.transportFine) AS transportFine, SUM(fr.transportConcession) AS transportConcession, 
                                SUM(fr.transportPaid) AS transportPaid, SUM(fr.applHostel) AS applHostel, SUM(fr.applHostelFine) AS applHostelFine,
                                SUM(fr.applTransport) AS applTransport, SUM(fr.applTransportFine) AS applTransportFine  ,'S' AS studentStatus      
                           FROM 
                                fee_receipt fr, student s, class c
                           WHERE
                                fr.feeClassId   = c.classId AND
                                fr.studentId = s.studentId  
                           $conditions
                           UNION
                           SELECT 
                                SUM(fr.fine) AS studentFine, SUM(feePaid) AS feePaid,
                                SUM(fr.hostelDues) AS hostelDues, SUM(fr.hostelFine) AS hostelFine, SUM(fr.hostelConcession) AS hostelConcession, 
                                SUM(fr.hostelPaid) AS hostelPaid, SUM(fr.transportDues) AS transportDues, 
                                SUM(fr.transportFine) AS transportFine, SUM(fr.transportConcession) AS transportConcession, 
                                SUM(fr.transportPaid) AS transportPaid, SUM(fr.applHostel) AS applHostel, SUM(fr.applHostelFine) AS applHostelFine,
                                SUM(fr.applTransport) AS applTransport, SUM(fr.applTransportFine) AS applTransportFine , 'D' AS studentStatus       
                           FROM 
                                fee_receipt fr, quarantine_student s, class c
                           WHERE
                                fr.feeClassId   = c.classId AND
                                fr.studentId = s.studentId  
                           $conditions) AS tt
                      $orderBy $limit";
        }
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    
    public function getFeePaymentDetail($conditions='',$studentStatus='3') { 
        
        global $sessionHandler;      
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');         
        
        if($studentStatus=='') {
          $studentStatus=3;  
        }
        
        $tableName='student';
        if($studentStatus==2) {
           $tableName='quarantine_student';    
        }
        
        $query = "SELECT 
                       SUM(fd.instrumentAmount) AS totalAmountPaid
                  FROM    
                       fee_payment_detail fd, fee_receipt fr, $tableName s 
                  WHERE     
                       s.studentId = fr.studentId AND 
                       fd.feeReceiptId = fr.feeReceiptId AND
                       fr.receiptStatus NOT IN (3,4) 
                  $conditions ";
                  
        if($studentStatus==3) {          
           $query = "SELECT
                            SUM(tt.totalAmountPaid) AS totalAmountPaid
                     FROM       
                         (SELECT 
                               SUM(fd.instrumentAmount) AS totalAmountPaid, 'S' AS studentStatus
                         FROM    
                               fee_payment_detail fd, fee_receipt fr, student s 
                         WHERE     
                               s.studentId = fr.studentId AND
                               fd.feeReceiptId = fr.feeReceiptId AND
                               fr.receiptStatus NOT IN (3,4) 
                         $conditions
                         UNION
                         SELECT 
                               SUM(fd.instrumentAmount) AS totalAmountPaid, 'D' AS studentStatus
                         FROM    
                               fee_payment_detail fd, fee_receipt fr, quarantine_student s 
                         WHERE     
                               s.studentId = fr.studentId AND
                               fd.feeReceiptId = fr.feeReceiptId AND
                               fr.receiptStatus NOT IN (3,4) 
                         $conditions) AS tt"; 
        }
          
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function getFeeCashDetail($conditions='',$studentStatus='3') { 
        
        global $sessionHandler;      
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');         
        
        if($studentStatus=='') {
          $studentStatus=3;  
        }
        
        $tableName='student';
        if($studentStatus==2) {
           $tableName='quarantine_student';    
        }
        
        $query = "SELECT 
                       SUM(fr.cashAmount) AS totalAmountPaid
                  FROM    
                       fee_receipt fr, $tableName s 
                  WHERE 
                       s.studentId = fr.studentId AND    
                       fr.receiptStatus NOT IN (3,4) 
                  $conditions ";
        
        if($studentStatus==3) {  
            $query = "SELECT
                            SUM(tt.totalAmountPaid) AS totalAmountPaid
                      FROM
                          (SELECT 
                               SUM(fr.cashAmount) AS totalAmountPaid , 'S' AS studentStatus
                          FROM    
                               fee_receipt fr, student s 
                          WHERE
                               s.studentId = fr.studentId AND     
                               fr.receiptStatus NOT IN (3,4) 
                          $conditions 
                          UNION
                          SELECT 
                               SUM(fr.cashAmount) AS totalAmountPaid , 'D' AS studentStatus
                          FROM    
                               fee_receipt fr, quarantine_student s 
                          WHERE  
                               s.studentId = fr.studentId AND    
                               fr.receiptStatus NOT IN (3,4) 
                          $conditions) AS tt ";      
        }    
          
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
      
}
?>
