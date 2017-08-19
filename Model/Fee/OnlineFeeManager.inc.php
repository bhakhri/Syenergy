<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "Online Fee Manange" TABLE
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class OnlineFeeManager {
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
    
                          
    public function insertOnlineTransaction($strQuery='') {
    
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $userId = $sessionHandler->getSessionVariable('UserId');
        
        $query = "INSERT INTO online_fee
                  (depositoryId,studentId,feeType,feeClassId,payableAmount,paidAmount,ipAddress)
                  VALUES
                  $strQuery";
		// return $query;
        $ret = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);     
        if($ret===false) {
          return false;  
        }
        $lastInsertId=SystemDatabaseManager::getInstance()->lastInsertId();   
        return $lastInsertId; 
    } 
    
    public function updateOnlineTransaction($fieldName='',$onlineFeePaymentId='',$studentId='') {
    
        global $sessionHandler;
        
        $query = "UPDATE online_fee SET $fieldName
                  WHERE orderId = '$onlineFeePaymentId' AND studentId = '$studentId'";
        // return $query;
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);     
    }
    
    
    public function insertFeeMaster($strQuery='') {
    
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $userId = $sessionHandler->getSessionVariable('UserId');
        
        $query = "INSERT INTO onlineFeePaymentMaster
                  (studentId,totalAmount,holderName,contactNo,securityCode,feeHistory)
                  VALUES
                  $strQuery";
        $ret = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);     
        if($ret===false) {
          return false;  
        }
        $lastInsertId=SystemDatabaseManager::getInstance()->lastInsertId();   
        return $lastInsertId; 
    } 
    
    public function insertFeeDetail($strQuery='') {
	
        global $sessionHandler;
	    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    	$userId = $sessionHandler->getSessionVariable('UserId');
	    
    	$query = "INSERT INTO onlineFeePaymentDetail
		          (onlineFeePaymentId,studentId,feeClassId,feeType,feeAmount)
                  VALUES
                  $strQuery";
   
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);     
    } 
    
    public function updateFeeStatus($isStatus='',$onlineId='',$classId='',$feeType='',$mystring='') {
    
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $userId = $sessionHandler->getSessionVariable('UserId');
        
        if($isStatus=='') {
          $isStatus=0;  
        }
        
        $query = "UPDATE onlineTransaction  
                  SET
                    isStatus = '$isStatus',
                    classId ='$classId',
                    feeType = '$feeType',
                    textURL ='$mystring'
                  WHERE
                    onlineId = '$onlineId' ";
   			
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);     
    } 
    
    public function getFeePaymentList($condition='',$orderBy='',$limit='') {
    
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $userId = $sessionHandler->getSessionVariable('UserId');
        
        if($isStatus=='') {
          $isStatus=0;  
        }
        
        $query = "SELECT 
                   studentId, feeClassId, feeType, feeAmount, isStatus, holderName, 
                     securityCode, insertDate, isDelete
                  FROM
                     onlineTransaction 
                  WHERE
                      $condition
                  $orderBy $limit";
   
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");       
    } 
   public function insertMerchantFeeDetail($strQuery='') {
    
        global $sessionHandler;   
        
    	$query  = "INSERT INTO online_fee_detail 
    						(`TxnReferenceNo`,`portalResponse`,`status`)
					 VALUES
              				$strQuery";
		
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
   } 
   
   public function getStudentAllFeeClasses($studentId='',$classId='',$condition='') {   
     
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $classId = $sessionHandler->getSessionVariable('ClassId');
        $studentId = $sessionHandler->getSessionVariable('StudentId');
        
        $batchId = $sessionHandler->getSessionVariable('ClassBatchId'); 
        $degreeId = $sessionHandler->getSessionVariable('ClassDegreeId'); 
        $branchId = $sessionHandler->getSessionVariable('ClassBranchId'); 
        $migrationStudyPeriod = $sessionHandler->getSessionVariable('StudentMigrationStudyPeriod');
        
        $studentAllClass = trim($sessionHandler->getSessionVariable('StudentAllClass')); 
        
        
        if($studentId==''){
          $studentId = 0;
        }
        
	    if($migrationStudyPeriod==''){
	      $migrationStudyPeriod = 0;
	    }
        
        if($batchId==''){
          $batchId = 0;
        }
        if($degreeId==''){
          $degreeId = 0;
        }
        if($branchId==''){
          $branchId = 0;
        }
        
        if($studentAllClass=='') {
          $studentAllClass=0;  
        }
        
        // Fetch Academic Fee == $query1
        $query ="SELECT    
                        DISTINCT frm.classId AS feeClassId,
                        TRIM(SUBSTRING_INDEX(cls.className,'-',-1)) AS cycleName
                    FROM     
                        `fee_cycle_new` fcn , `fee_head_values_new` frm, class cls, study_period sp   
                    WHERE 
                        cls.classId = frm.classId   
                        AND fcn.feeCycleId = frm.feeCycleId
			            AND cls.studyPeriodId = sp.studyPeriodId                       
			            AND (INSTR('$studentAllClass',CONCAT('~',frm.classId,'~'))>0)
			            AND sp.periodValue >= '$migrationStudyPeriod'
                    ORDER BY 
                       frm.classId";
		 $query1 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                                 
        
        
         // Fetch Hostel Fee  == $query2
         $query ="SELECT    
                        DISTINCT hs.classId AS feeClassId,
                        TRIM(SUBSTRING_INDEX(hc.className,'-',-1)) AS cycleName
                  FROM     
                        `fee_cycle_new` f , `hostel_students` hs, class hc
                  WHERE   
                        f.feeCycleId = hs.feeCycleId 
                        AND hc.classId = hs.classId
			           
                        AND hs.studentId = '$studentId'
                  ORDER BY
                       hs.classId";
          $query2 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                                                   
          
          
          // Fetch Transport Fee == $query3
          $query ="SELECT    
                        DISTINCT brsm.classId AS feeClassId,
                        TRIM(SUBSTRING_INDEX(cc.className,'-',-1)) AS cycleName
                    FROM     
                        `fee_cycle_new` ff , `bus_route_student_mapping` brsm, class cc
                    WHERE   
                        ff.feeCycleId = brsm.feeCycleId 
                        AND brsm.classId = cc.classId
			           			
                        AND brsm.studentId = '$studentId'      
                  ORDER BY 
                      brsm.classId";
          $query3 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");              
                        
          
          // Academic 
          $valueArray = array();
          for($i=0;$i<count($query1);$i++) {
            $valueArray[]= array("feeClassId"=>$query1[$i]['feeClassId'],
                                 "cycleName"=>$query1[$i]['cycleName'],
                                 "academic"=>1,
                                 "hostel"=>0,
                                 "transport"=>0
                                 );
          }                      
           
          // Hostel
          for($i=0;$i<count($query2);$i++) {
            $feeClassId = $query2[$i]['feeClassId']; 
            $findId='';
            for($j=0;$j<count($valueArray);$j++) {
              if($valueArray[$j]['feeClassId']==$feeClassId) {
                $findId='1';
                $valueArray[$j]['hostel']=1; 
                break;  
              }
            }
            if($findId=='') {
               $valueArray[]= array("feeClassId"=>$query2[$i]['feeClassId'],
                                    "cycleName"=>$query2[$i]['cycleName'],
                                    "academic"=>0,
                                    "hostel"=>1,
                                    "transport"=>0
                                    );
            }
          }
          
          // Transport
          for($i=0;$i<count($query3);$i++) {
            $feeClassId = $query3[$i]['feeClassId'];  
            $findId='';
            for($j=0;$j<count($valueArray);$j++) {
              if($valueArray[$j]['feeClassId']==$feeClassId) {
                $findId='1';
                $valueArray[$j]['transport']=1; 
                break;  
              }
            }
            if($findId=='') {
               $valueArray[]= array("feeClassId"=>$query3[$i]['feeClassId'],
                                    "cycleName"=>$query3[$i]['cycleName'],
                                    "academic"=>0,
                                    "hostel"=>0,
                                    "transport"=>1
                                    );
            }
          }
          return $valueArray;            
     }
     
     public function getPreviousTotalAmount($ttStudentId='',$ttClassId=''){
   
        global $sessionHandler;
        $query ="SELECT
                    fri.studentId, fri.classId,frm.concession,SUM(IFNULL(fl.debit,0)) AS debit, SUM(IFNULL(fl.credit,0)) AS credit,  
                    (SUM(fri.amount) - IFNULL(frm.concession,0) +  SUM(DISTINCT IF(fl.ledgerTypeId =1,IF(fl.isFine=0,fl.debit,0),0)) - SUM(DISTINCT IF(fl.ledgerTypeId =1,IF(fl.isFine=0,fl.credit,0),0)) + SUM(DISTINCT IF(fl.ledgerTypeId =1,IF(fl.isFine=1,fl.debit,0),0)) - SUM(DISTINCT IF(fl.ledgerTypeId =1,IF(fl.isFine=1,fl.credit,0),0))) AS acdemicFees,
                    (IFNULL(hs.hostelCharges,0) + IFNULL(hs.securityAmount,0) + SUM(DISTINCT IF(fl.ledgerTypeId =3,IF(fl.isFine=0,fl.debit,0),0)) - SUM(DISTINCT IF(fl.ledgerTypeId =3,IF(fl.isFine=0,fl.credit,0),0)) + SUM(DISTINCT IF(fl.ledgerTypeId =3,IF(fl.isFine=1,fl.debit,0),0)) - SUM(DISTINCT IF(fl.ledgerTypeId =1,IF(fl.isFine=1,fl.credit,0),0)) ) AS hostelFees,
                    (frm.transportFees + SUM(DISTINCT IF(fl.ledgerTypeId =2,IF(fl.isFine=0,fl.debit,0),0)) - SUM(DISTINCT IF(fl.ledgerTypeId =2,IF(fl.isFine=0,fl.credit,0),0)) + SUM(DISTINCT IF(fl.ledgerTypeId =2,IF(fl.isFine=1,fl.debit,0),0)) - SUM(DISTINCT IF(fl.ledgerTypeId =2,IF(fl.isFine=1,fl.credit,0),0)) ) AS transportFees,
                     (SUM(fri.amount) + (IFNULL(hs.hostelCharges,0) + IFNULL(hs.securityAmount,0)) + frm.transportFees - IFNULL(frm.concession,0) + SUM(IFNULL(fl.debit,0)) - SUM(IFNULL(fl.credit,0))) AS totalFees
                 FROM
                    fee_receipt_instrument fri LEFT JOIN fee_receipt_master frm ON 
                        frm.studentId = fri.studentId AND frm.feeClassId = fri.classId AND frm.status = 1
                    LEFT JOIN hostel_students hs ON hs.studentId = fri.studentId AND hs.classId = fri.classId 
                    LEFT JOIN fee_ledger_debit_credit fl ON 
                        fl.studentId = fri.studentId AND fl.classId = fri.classId AND ledgerTypeId IN(1,2,3) 
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId'                        
                 GROUP BY
                     fri.studentId, fri.classId ";   
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     } 
     
     public function getTotalAcademicPaidAmount($ttStudentId='',$ttClassId=''){
   
        global $sessionHandler;
        $query ="SELECT
                    SUM(IFNULL(fri.amount,0)) AS paidAcademicAmount 
                 FROM
                    fee_receipt_details fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.isDelete = 0
                    AND fri.feeType ='1'   
                  
                 GROUP BY
                     fri.studentId, fri.classId";
      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
      public function getTotalHostelPaidAmount($ttStudentId='',$ttClassId=''){
   
        global $sessionHandler;
        $query ="SELECT
                    SUM(IFNULL(fri.amount,0)) AS paidHostelAmount 
                 FROM
                    fee_receipt_details fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.isDelete = 0
                    AND fri.feeType ='3'   
                  
                 GROUP BY
                     fri.studentId, fri.classId";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
      public function getTotalTransportPaidAmount($ttStudentId='',$ttClassId=''){
   
        global $sessionHandler;
        $query ="SELECT
                    SUM(IFNULL(fri.amount,0)) AS paidTransportAmount 
                 FROM
                    fee_receipt_details fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.isDelete = 0
                    AND fri.feeType ='2'                    
                 GROUP BY
                     fri.studentId, fri.classId";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    public function getTotalAllPaidAmount($ttStudentId='',$ttClassId=''){
   
        global $sessionHandler;
        $query ="SELECT
                    SUM(fri.amount) AS paidAmount 
                 FROM
                    fee_receipt_details fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.isDelete = 0
                    AND fri.feeType ='4'                    
                 GROUP BY
                     fri.studentId, fri.classId";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    public function checkFeeReceiptMasterDetail($studentId='',$classId=''){
   
        global $sessionHandler;
        
        $query ="SELECT
                    feeReceiptId, studentId, feeClassId
                 FROM
                    fee_receipt_master frm  
                 WHERE
                    studentId = '$studentId'                        
                    AND feeClassId= '$classId' 
                 GROUP BY
                    studentId, feeClassId";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    public function insertOnlineFeeReceiptDetails($strQuery='') {
    
        global $sessionHandler;   
        $query  = "INSERT INTO `fee_receipt_details` 
                    (`feeReceiptId`,`studentId`,`classId`,`paymentMode`,`dated`,`amount`,`feeType`,`receiptNo`,
                     `receiptDate`,`paidAt`,`installmentNo`,`isOnlinePayment`, onlineId)
                   VALUES
                      $strQuery";
   
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
   }  
   
    public function updatePaidFeeReceipt($studentId='',$classId='',$updateCondition='') {
    
        global $sessionHandler;   
        $query  = "UPDATE `fee_receipt_master` SET  $updateCondition WHERE studentId ='$studentId' AND feeClassId ='$classId'";
   
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
   } 
   
    public function checkInstallmentFeeDetails($studentId='',$classId=''){
   
        global $sessionHandler;
        $query ="SELECT
                    COUNT(frm.installmentNo) AS installmentNo, frm.studentId,frm.classId
                 FROM
                    `fee_receipt_details` frm  
                 WHERE
                    frm.studentId = '$studentId'                        
                    AND frm.classId= '$classId' 
                 GROUP BY
                    frm.studentId, frm.classId";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    
    public function getTransactionList($condition='') {        
        
        global $sessionHandler;        
        $query = "SELECT * FROM online_fee of WHERE $condition AND isStatus = '0' LIMIT 0,1";  
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
	
	public function getStudentDetails($studentId='') {        
        
        global $sessionHandler;        
        $query = "SELECT 
        			 CONCAT(s.firstName,' ',s.lastName) AS studentName,s.rollNo,s.fatherName
				 FROM 
				 	student s
		 		WHERE 
		 			 s.studentId = '$studentId' LIMIT 0,1";  
    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    public function getClassPaymentDetails($classId='') {        
        
        global $sessionHandler;        
        $query = "SELECT 
        				 TRIM(SUBSTRING_INDEX(cc.className,'-',-1)) AS className
				 FROM 
				 		class cc 
		 		WHERE 
		 			 cc.classId =  '$classId' LIMIT 0,1";  
      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
     public function addOnlineUrl($vpcURL='',$onlineFeePaymentId='') {
    
        global $sessionHandler;
		   
        $query  = "UPDATE onlineTransaction  
                  SET
                    vpcURL = '$vpcURL'
                  WHERE
                    onlineId = '$onlineFeePaymentId' ";
   
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
   }  
   public function checkDuplicateReceipt($studentId='',$classId='',$receiptNo=''){
   
        global $sessionHandler;
        $query ="SELECT
                    frm.receiptNo ,frm.isOnlinePayment
                 FROM
                    `fee_receipt_details` frm  
                 WHERE
                    frm.studentId = '$studentId'                        
                    AND frm.classId= '$classId' 
                    AND frm.isOnlinePayment = 1
                    AND receiptNo = '$receiptNo'
                 GROUP BY
                    frm.studentId, frm.classId";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    public function getOnlinePaymentHistoryCountNew($condition='',$onlineFeeCondition='') {        
        
        global $sessionHandler;        
        $query = "SELECT 
        				 DISTINCT frd.feeReceiptId,TRIM(SUBSTRING_INDEX(cc.className,'-',-1)) AS classPeriodName,
        				 CONCAT(s.firstName,' ',s.lastName) AS studentName,
        				 cc.className, s.rollNo, s.regNo, IFNULL(frd.amount,0) AS amount,
        				 DATE_FORMAT(frd.receiptDate, '%Y-%m-%d') AS receiptDate,
                         DATE_FORMAT(ot.insertDate, '%Y-%m-%d') AS insertDate,frd.isDelete, 
	                     s.studentId, IFNULL(frd.receiptNo,'--') AS receiptNo,frd.installmentNo,ot.holderName,
	                     ot.payableAmount AS totalAmount,ot.feePaymentHistory,ot.isStatus,ot.taxAmount,ot.totalFee,
                             IFNULL(usr.userName,'') AS userName, 
                           IFNULL(CONCAT(emp.employeeName, ' (',emp.employeeCode,')'),'Admin') AS employeeCodeName,
                           IF(frd.isDelete=1,'Receipt Cancelled','---')  AS userStatus,frd.reason
				 FROM 
                        `class` cc,`student` s,
                        `onlineTransaction` ot LEFT JOIN  `fee_receipt_details` frd ON 
                        ot.studentId = frd.studentId AND frd.isOnlinePayment = 1 AND 
                        ot.classId = frd.classId AND frd.feeType = ot.feeType AND 
                        ot.onlineId = frd.onlineId
                        $onlineFeeCondition
                         LEFT JOIN `user` usr ON usr.userId = frd.userId
                         LEFT JOIN employee emp ON usr.userId = emp.userId
                 WHERE 
                        s.studentId = ot.studentId AND
                        cc.classId = ot.classId                       
                        $condition  
                   GROUP BY 
                        frd.feeReceiptDetailId, ot.onlineId,ot.insertDate ";  
      		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
     public function getOnlinePaymentHistoryDetailsNew($condition='',$onlineFeeCondition ='',$limit='',$sortOrderBy='',$sortField='') {        
        
        global $sessionHandler;        
        $query = "SELECT 
        				 DISTINCT frd.feeReceiptId,TRIM(SUBSTRING_INDEX(cc.className,'-',-1)) AS classPeriodName,
        				 CONCAT(s.firstName,' ',s.lastName) AS studentName,
        				 cc.className, s.rollNo, s.regNo, IFNULL(frd.amount,0) AS amount,
        				 DATE_FORMAT(frd.receiptDate, '%Y-%m-%d') AS receiptDate,
                         DATE_FORMAT(ot.insertDate, '%Y-%m-%d') AS insertDate,IFNULL(frd.isDelete,0) AS isDelete, 
	                     s.studentId, IFNULL(frd.receiptNo,'--') AS receiptNo,frd.installmentNo,ot.holderName,
	                     ot.payableAmount AS totalAmount,ot.feePaymentHistory,ot.isStatus,ot.taxAmount,ot.totalFee ,
                          IFNULL(usr.userName,'') AS userName, 
                           IFNULL(CONCAT(emp.employeeName, ' (',emp.employeeCode,')'),'Admin') AS employeeCodeName,
                           IF(frd.isDelete=1,'Receipt Cancelled','--')  AS userStatus ,
                           IF(frd.isDelete=1,frd.reason,'--') AS reason
				 FROM 
                        `class` cc,`student` s,
                        `onlineTransaction` ot LEFT JOIN  `fee_receipt_details` frd ON 
                        ot.studentId = frd.studentId AND frd.isOnlinePayment = 1 AND 
                        ot.classId = frd.classId AND frd.feeType = ot.feeType AND                         
                        ot.onlineId = frd.onlineId   $onlineFeeCondition
                          LEFT JOIN `user` usr ON usr.userId = frd.userId
                          LEFT JOIN employee emp ON usr.userId = emp.userId
                        
                 WHERE 
                        s.studentId = ot.studentId AND
                        cc.classId = ot.classId                       
                        $condition  
                GROUP BY 
                        frd.feeReceiptDetailId, ot.onlineId,ot.insertDate 
                ORDER BY 
                    $sortField $sortOrderBy $limit";  
      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
       public function getAllFeeClassData($condition='',$orderBy='cls.degreeId,cls.branchId,cls.studyPeriodId') {

        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                         DISTINCT
                                cls.classId, cls.className
                  FROM
                        class cls
                  WHERE
                        cls.instituteId='".$instituteId."' AND
                        cls.isActive IN (1,2,3)
                  ORDER BY
                        $orderBy DESC";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
     public function getStudentClassesData($studentId='',$classId='',$condition='') {   
     
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $classId = $sessionHandler->getSessionVariable('ClassId');
        $studentId = $sessionHandler->getSessionVariable('StudentId');
        
        $batchId = $sessionHandler->getSessionVariable('ClassBatchId'); 
        $degreeId = $sessionHandler->getSessionVariable('ClassDegreeId'); 
        $branchId = $sessionHandler->getSessionVariable('ClassBranchId'); 
        $migrationStudyPeriod = $sessionHandler->getSessionVariable('StudentMigrationStudyPeriod');
        
        $studentAllClass = trim($sessionHandler->getSessionVariable('StudentAllClass')); 
        
        
        if($studentId==''){
          $studentId = 0;
        }
        
	    if($migrationStudyPeriod==''){
	      $migrationStudyPeriod = 0;
	    }
        
        if($batchId==''){
          $batchId = 0;
        }
        if($degreeId==''){
          $degreeId = 0;
        }
        if($branchId==''){
          $branchId = 0;
        }
        
        if($studentAllClass=='') {
          $studentAllClass=0;  
        }
        
        // Fetch Academic Fee == $query1
        $query ="SELECT    
                        DISTINCT frm.classId AS classId,cls.className AS className
                     
                    FROM     
                        `fee_cycle` fcn , `fee_head_values` frm, class cls, study_period sp   
                    WHERE 
                        cls.classId = frm.classId   
                        AND fcn.feeCycleId = frm.feeCycleId
			            AND cls.studyPeriodId = sp.studyPeriodId                       
			            AND (INSTR('$studentAllClass',CONCAT('~',frm.classId,'~'))>0)
			            AND sp.periodValue >= '$migrationStudyPeriod'
                    ORDER BY 
                       frm.classId";
		 $query1 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                                 
        return $query1;
        
         // Fetch Hostel Fee  == $query2
         $query ="SELECT 
         				 DISTINCT hs.classId AS classId,hc.className AS className   
                       
                  FROM     
                        `fee_cycle_new` f , `hostel_students` hs, class hc
                  WHERE   
                        f.feeCycleId = hs.feeCycleId 
                        AND hc.classId = hs.classId
			           
                        AND hs.studentId = '$studentId'
                  ORDER BY
                       hs.classId";
          $query2 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                                                   
          
          
          // Fetch Transport Fee == $query3
          $query ="SELECT  
          				 DISTINCT brsm.classId AS classId,cc.className AS className  
                        
                    FROM     
                        `fee_cycle_new` ff , `bus_route_student_mapping` brsm, class cc
                    WHERE   
                        ff.feeCycleId = brsm.feeCycleId 
                        AND brsm.classId = cc.classId
			           			
                        AND brsm.studentId = '$studentId'      
                  ORDER BY 
                      brsm.classId";
          $query3 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");              
                        
          
          // Academic 
          $valueArray = array();
          for($i=0;$i<count($query1);$i++) {
            $valueArray[]= array("classId"=>$query1[$i]['classId'],
                                 "className"=>$query1[$i]['className'],
                                 "academic"=>1,
                                 "hostel"=>0,
                                 "transport"=>0
                                 );
          }                      
           
          // Hostel
          for($i=0;$i<count($query2);$i++) {
            $classId = $query2[$i]['classId']; 
            $findId='';
            for($j=0;$j<count($valueArray);$j++) {
              if($valueArray[$j]['classId']==$classId) {
                $findId='1';
                $valueArray[$j]['hostel']=1; 
                break;  
              }
            }
            if($findId=='') {
               $valueArray[]= array("classId"=>$query2[$i]['classId'],
                                    "className"=>$query2[$i]['className'],
                                    "academic"=>0,
                                    "hostel"=>1,
                                    "transport"=>0
                                    );
            }
          }
          
          // Transport
          for($i=0;$i<count($query3);$i++) {
            $classId = $query3[$i]['classId'];  
            $findId='';
            for($j=0;$j<count($valueArray);$j++) {
              if($valueArray[$j]['classId']==$classId) {
                $findId='1';
                $valueArray[$j]['transport']=1; 
                break;  
              }
            }
            if($findId=='') {
               $valueArray[]= array("classId"=>$query3[$i]['classId'],
                                    "className"=>$query3[$i]['className'],
                                    "academic"=>0,
                                    "hostel"=>0,
                                    "transport"=>1
                                    );
            }
          }
          return $valueArray;            
     }


 public function getTotalAcademicHeadFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                    fri.studentId, fri.classId, SUM(fri.amount) AS academicFees
                 FROM
                    fee_receipt_instrument fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId'                        
                 $condition
                 GROUP BY
                     fri.studentId, fri.classId ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
 
 public function getTotalAcademicConcessionFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                    fri.studentId, fri.feeClassId, SUM(IFNULL(fri.concession,0)) AS concession
                 FROM
                    fee_receipt_master fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.feeClassId= '$ttClassId'                        
                 $condition 
                 GROUP BY
                     fri.studentId, fri.feeClassId";
        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function getTotalAcademicLedgerFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                     SUM( IF(fri.isFine=0,IFNULL(fri.debit,0),0)) AS acdDebit,  SUM( IF(fri.isFine=0,IFNULL(fri.credit,0),0)) AS acdCredit,
                    SUM( IF(fri.isFine>0,IFNULL(fri.debit,0),0)) AS fineDebit,  SUM( IF(fri.isFine>0,IFNULL(fri.credit,0),0)) AS fineCredit
                 FROM
                    fee_ledger_debit_credit fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.ledgerTypeId = 1                       
                 $condition ";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
     //Hostel Fees
    public function getTotalHostelHeadFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                    DISTINCT fri.studentId, fri.classId, IFNULL(fri.hostelCharges,0) AS hostelFees,
                    fri.securityAmount AS hostelSecurity
                 FROM
                    hostel_students fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId'                        
                 $condition
                 GROUP BY
                     fri.studentId, fri.classId ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    public function getTotalHostelLedgerFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                     SUM( IF(fri.isFine=0,IFNULL(fri.debit,0),0)) AS hostDebit,
                     SUM( IF(fri.isFine=0,IFNULL(fri.credit,0),0)) AS hostCredit,
                   	 SUM( IF(fri.isFine>0,IFNULL(fri.debit,0),0)) AS finehostDebit,
                      SUM( IF(fri.isFine>0,IFNULL(fri.credit,0),0)) AS finehostCredit
                 FROM
                    fee_ledger_debit_credit fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.ledgerTypeId = 3                       
                 $condition ";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    public function getTotalTransportHeadFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                    DISTINCT fri.studentId, fri.classId, IFNULL(fri.routeCharges,0) AS transportFees
                 FROM
                    bus_route_student_mapping fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId'                        
                 $condition
                 GROUP BY
                     fri.studentId, fri.classId ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
     public function getTotalTransportLedgerFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                     SUM( IF(fri.isFine=0,IFNULL(fri.debit,0),0)) AS transDebit,
                     SUM( IF(fri.isFine=0,IFNULL(fri.credit,0),0)) AS transCredit,
                   	 SUM( IF(fri.isFine>0,IFNULL(fri.debit,0),0)) AS finetransDebit,
                      SUM( IF(fri.isFine>0,IFNULL(fri.credit,0),0)) AS finetransCredit
                 FROM
                    fee_ledger_debit_credit fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.ledgerTypeId = 2                       
                 $condition ";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
   public function getStudentOnlineStatusDetails($condition='') {
    
        $query = "SELECT 
                     onlineId,  studentId, holderName, ipAddress, feePaymentHistory, 
                     payableAmount, taxAmount, totalFee, isStatus, insertDate, vpcSecureHash
                  FROM
                     onlineTransaction 
                  WHERE
                     $condition
                  LIMIT 0,1";

         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");       
    }   
    
      public function getOnlineFeeDetails($condition='') {        
        
        global $sessionHandler;        
        $query = "SELECT 
        			 IFNULL(frd.amount,0) AS amount, CONCAT(s.firstName,' ',s.lastName) AS studentName,
        			 s.rollNo,s.fatherName,
        			 frd.receiptDate AS receiptDate,frd.feeReceiptId,ot.isPrint,ot.onlineId,ot.studentId,
                     cc.className AS className, TRIM(SUBSTRING_INDEX(cc.className,'-',-1)) AS shortClassName,
	                 IFNULL(frd.receiptNo,'--') AS receiptNo,frd.installmentNo,ot.holderName,ot.isStatus,frd.feeType,
	                 ot.payableAmount AS totalAmount,ot.feePaymentHistory,ot.isStatus,ot.taxAmount,ot.totalFee
				  FROM 
				 	  class cc, student s, onlineTransaction ot, fee_receipt_details frd 
                  WHERE
                      s.studentId = frd.studentId AND
                      cc.classId = frd.classId AND		 		 
                      ot.studentId = frd.studentId AND 
                      ot.classId = frd.classId AND 
                      frd.isOnlinePayment = 1 AND 
                      frd.feeType = ot.feeType AND 
                      frd.isDelete=0 AND 
                      ot.onlineId= frd.onlineId
		 		      $condition
	 			  LIMIT 0,1";  
  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
     public function getUpdateFeePrint($condition) {
    
        global $sessionHandler;
        
        $query = "UPDATE onlineTransaction SET isPrint = '1'
                  WHERE $condition";
    
        return SystemDatabaseManager::getInstance()->executeUpdate($query);     
    }
    
    
}
// $History: StudentConcessionManager.inc.php $
?>
