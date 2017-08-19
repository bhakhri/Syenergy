<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class StudentManager {
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
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT ROLLNUMBER
//
// Author :Gurkeerat Sidhu
// Created on : (27.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
public function getStudentRoll($groupText = '') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "
                    SELECT 
                                s.rollNo 
                    FROM        student s,class c 
                    WHERE       c.classId = s.classId AND c.instituteId = '$instituteId' AND s.rollNo LIKE '".add_slashes(trim($groupText))."%'  
                    ORDER BY s.rollNo
                     ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH CLASS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
    public function getClass($universityID,$degreeID,$branchID,$batchId,$studyperiodId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT classId,className 
        FROM class
        WHERE universityId = '$universityID' and degreeId = '$degreeID' and branchId = '$branchID' and batchId = '$batchId' and studyPeriodId = '$studyperiodId' and instituteId = '$instituteId' and sessionId = '$sessionId'";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT CLASS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getStudentClass($classId) {
        $query = "SELECT classId,className,studyPeriodId,isActive 
        FROM class
        WHERE classId = '$classId'";
        
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT MARKS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getStudentMarksClass($subjectId,$studentId) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
				  sub.subjectName,if(ttype.conductingAuthority='1','Internal','external') as type,ttype.testTypeName,sum(tm.maxMarks) as TotalMarks,SUM(tm.marksScored) as MarksObtained
				  FROM 
				  test_marks tm,test tt,test_type ttype, subject sub 
				  WHERE tt.testId=tm.testId and tm.studentId='$studentId' and tm.subjectId='$subjectId' and 
				  tt.testTypeId=ttype.testTypeId  and tm.subjectId=sub.subjectId and ttype.instituteId = $instituteId
				  GROUP BY conductingAuthority";
        
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT DETAILS BASED ON ROLL NO
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
public function getStudentDetailClass($condition) {
        $query = "SELECT 
                        stu.studentId,
                        stu.firstName, 
                        stu.lastName, 
                        stu.quotaId, 
                        stu.hostelRoomId, 
                        stu.isLeet, 
				        stu.busStopId, 
                        stu.fatherName, 
                        cls.classId, 
                        cls.instituteId, 
                        SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-3) AS className, 
				        cls.studyPeriodId,
                        cls.universityId,
                        cls.batchId,
                        cls.degreeId,
                        cls.branchId,
                        sp.periodName
				  FROM 
                        student stu, 
                        class cls, 
                        study_period sp
				  WHERE 
				        stu.classId = cls.classId 
                        AND sp.studyPeriodId = cls.studyPeriodId 
                        $condition
                  ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getStudentUserDetailClass($rollNo) {
        $query = "SELECT firstName,lastName,fatherUserId,motherUserId,guardianUserId  
				  FROM student 
				  WHERE 
				  rollNo='".$rollNo."'";
        
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getStudentEmail($conditions='') {
     
        $query = "SELECT studentEmail,alternateStudentEmail,regNo 
        FROM student 
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT QUARTINE USER DETAILS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getQuartineStudentEmail($conditions='') {
     
        $query = "SELECT studentEmail,alternateStudentEmail,regNo 
        FROM quarantine_student 
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    public function getStudentFeeReceiptNo($conditions='') {
     
        $query = "SELECT feeReceiptNo FROM student $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    public function getStudentQuartineFeeReceiptNo($conditions='') {
     
        $query = "SELECT feeReceiptNo FROM quarantine_student $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    public function getStudentRegNo($conditions='') {
     
        $query = "SELECT regNo FROM student $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    public function getQuartineStudentRegNo($conditions='') {
     
        $query = "SELECT regNo FROM quarantine_student $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
	
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT SIBLING DETAILS 
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
public function getSiblingStudents($fatherId,$motherId,$guardianId) {
        $query = "SELECT 
				  CONCAT(firstName,' ',
				  lastName) as fullName,
				  dateOfBirth 
				  FROM student 
				  WHERE ";
		if($fatherId)
			$query .= "fatherUserId = ".$fatherId;
		else
			$query .= "fatherUserId is NULL";

		if($motherId)
			$query .= " AND motherUserId = ".$motherId;
		else
			$query .= " AND motherUserId is NULL";

		if($guardianId)
			$query .= " AND guardianUserId = ".$guardianId;
		else
			$query .= " AND guardianUserId is NULL";
				  /*fatherUserId='".$fatherUser."' AND motherUserId='".$motherUser."' 
				  AND guardianUserId ='".$guardianUser."'";*/
        
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT STUDENT FEES DETAILS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
public function insertStudentFees() {
         
		 global $REQUEST_DATA;
		 global $sessionHandler;
		 
		 $instituteId = $sessionHandler->getSessionVariable('InstituteId');
		 $loggedUserId = $sessionHandler->getSessionVariable('UserId');    

		 $previousDues = $REQUEST_DATA['totalFees']+$REQUEST_DATA['studentFine']-$REQUEST_DATA['totalConcession']-$REQUEST_DATA['paidAmount']-$REQUEST_DATA['previousPayment'];
		 //$previousDues = abs($previousDues); 
		
		 $paidAmount=0;
		 if(count($REQUEST_DATA['amtId'])){
		 
			for($j=0;$j<count($REQUEST_DATA['amtId']);$j++){
			
				$paidAmount = $paidAmount + $REQUEST_DATA['amtId'][$j];
			}
		 }
		 $paidAmount = number_format(($REQUEST_DATA['cashAmount']+$paidAmount),"2",".","");
		 // number_format($previousDues,"2",".","");
		 //$paidAmount = $REQUEST_DATA['paidAmount'];
		 /*if($REQUEST_DATA['paidAmount']<0)
	     {
			$previousOverPayment = abs($REQUEST_DATA['paidAmount']);
			$paidAmount = "0.00";
		 }*/

		 if($previousDues <0)
			 $previousDues = "0.00";

		 $previousOverPayment = '';
		 if($REQUEST_DATA['paidAmount']>$REQUEST_DATA['netAmount'])
			 $previousOverPayment = $REQUEST_DATA['paidAmount'] - $REQUEST_DATA['netAmount'];
		 

		 $query     = "SELECT MAX(feeReceiptId) as recordReceipt,MAX(installmentCount) as installmentNo FROM `fee_receipt` WHERE studentId = ".$REQUEST_DATA['studentId']." AND feeCycleId =".$REQUEST_DATA['feeCycle']." AND feeStudyPeriodId =".$REQUEST_DATA['feeStudyPeriod']." AND feeType=".$REQUEST_DATA['feeType'];
		 $recordArr     = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
		 $recordReceipt = $recordArr[0]['recordReceipt'];
		 $installmentCount = $recordArr[0]['installmentNo'];
		 $installmentCount = $installmentCount + 1;

		 $previousDues = number_format($previousDues,"2",".","");
		 $previousOverPayment = number_format($previousOverPayment,"2",".","");

		 SystemDatabaseManager::getInstance()->runAutoInsert('fee_receipt', array('feeType','studentId','instituteId','installmentCount','feeCycleId','srNo','receiptNo','receiptDate','currentStudyPeriodId','feeStudyPeriodId','totalFeePayable','printRemarks','generalRemarks','totalAmountPaid','cashAmount','fine','discountedFeePayable','payableBankId','previousDues','previousOverPayment','receivedFrom','userId'), array($REQUEST_DATA['feeType'],$REQUEST_DATA['studentId'],$instituteId,$installmentCount,$REQUEST_DATA['feeCycle'],add_slashes($REQUEST_DATA['serialNumber']),$REQUEST_DATA['receiptNumber'],$REQUEST_DATA['receiptDate'],$REQUEST_DATA['currStudyPeriod'],$REQUEST_DATA['feeStudyPeriod'],$REQUEST_DATA['totalFees'],$REQUEST_DATA['printRemarks'],$REQUEST_DATA['generalRemarks'],$paidAmount,$REQUEST_DATA['cashAmount'],$REQUEST_DATA['studentFine'],$REQUEST_DATA['netAmount1'],$REQUEST_DATA['payableBank'],$previousDues,$previousOverPayment,$REQUEST_DATA['receivedFrom'],$loggedUserId) );
		 
		 $lastReceipt = SystemDatabaseManager::getInstance()->lastInsertId();
		 if($recordReceipt)
		 {
			 SystemDatabaseManager::getInstance()->runAutoUpdate('fee_receipt', array('nextReceiptId'), array($lastReceipt), " feeReceiptId = $recordReceipt" );
		 }
		 else
		 {
			$feeArr  = implode(",",$REQUEST_DATA['feeHeadId']);
			$feeHead = $REQUEST_DATA['feeHeadId'];
			for($i=0;$i<count($REQUEST_DATA['feeHeadId']); $i++)
			{
				$concessionValue = $REQUEST_DATA['chb'];
				$feeHeadAmtValue = $REQUEST_DATA['feeHeadAmt'];
							
				SystemDatabaseManager::getInstance()->runAutoInsert('fee_head_student', array('firstReceiptId','studentId','feeHeadId','feeCycleId','feeHeadAmount','discountedAmount','feeStudyPeriodId'), array($lastReceipt,$REQUEST_DATA['studentId'],$feeHead[$i],$REQUEST_DATA['feeCycle'],$feeHeadAmtValue[$i],$concessionValue[$i],$REQUEST_DATA['feeStudyPeriod']) );
			}
			//echo ($REQUEST_DATA['instId'][0]); 
			$cnt = count($REQUEST_DATA['amtId']);
			$insertValue = "";
			for($i=0;$i<$cnt; $i++)
			{
				$querySeprator = '';
				if($insertValue!=''){

					$querySeprator = ",";
				}
				$insertValue .= "$querySeprator ('".$REQUEST_DATA['studentId']."','".$REQUEST_DATA['feeCycle']."','".$installmentCount."','".$REQUEST_DATA['paymentTypeId'][$i]."','".$REQUEST_DATA['instId'][$i]."','".$REQUEST_DATA['amtId'][$i]."','".$REQUEST_DATA['leaveDate'.($i+1)]."','".$REQUEST_DATA['issuingBankId'][$i]."','".$REQUEST_DATA['receiptStatusId'][$i]."','".$REQUEST_DATA['paymentStatusId'][$i]."','".$lastReceipt."')";

			}
			if($insertValue){

				$query = "INSERT INTO `fee_payment_detail` 
					  (studentId,feeCycleId,installment,paymentInstrument,instrumentNo,instrumentAmount,instrumentDate,issuingBankId,receiptStatus,instrumentStatus,feeReceiptId)
					  VALUES 
					  $insertValue";

				SystemDatabaseManager::getInstance()->executeUpdate($query);
			}
		 }
		 return $lastReceipt;
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT SERIAL NUMBER
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getStudentFeeSerial() {

		global $REQUEST_DATA;
        $query = "SELECT MAX(srNo) as maxSerialNo FROM `fee_receipt`";
		if($REQUEST_DATA['feeTypeId']){
		
			$query .=" WHERE feeType=".$REQUEST_DATA['feeTypeId']; 
		}
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT EXTRA PAYMENT
//
// Author :Rajeev Aggarwal
// Created on : (26.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function getStudentExtraPayment($condition) {
         $query = "SELECT 
				   SUM(discountedFeePayable)-SUM(totalAmountPaid) as extraPaid  
				   FROM `fee_receipt` 
				   $condition";
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT TOTAL FEES PAID IN SELECTED STUDY PERIOD
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	 
	public function getStudentTotalFee($studentId,$feeCycleId,$feeStudyPeriodId) {

         $query = "SELECT SUM(totalAmountPaid) as totalPaid FROM `fee_receipt` WHERE studentId = ".$studentId." AND feeCycleId='".$feeCycleId."' AND feeStudyPeriodId=".$feeStudyPeriodId;
		 
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT DISCOUNTED FEES
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	 
	public function getStudentDiscountedFee($studentId,$feeCycleId,$feeStudyPeriodId) {
         $query = "SELECT discountedFeePayable FROM `fee_receipt` WHERE studentId = ".$studentId." AND feeCycleId =".$feeCycleId." AND feeStudyPeriodId=".$feeStudyPeriodId;
		 
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT TOTAL FEES HEAD DETAILS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	 
	public function getStudentHeadDetailClass($studentId,$feeCycleId,$feeType='',$studyPeriodId) {

		global $REQUEST_DATA;
            $query = "SELECT fhs.feeHeadStudentId ,fh.feeHeadId, fh.headName, fhs.feeHeadAmount,fhs.discountedAmount FROM `fee_head_student` fhs, `fee_head` fh,`fee_receipt` fr WHERE fhs.studentId = ".$studentId." AND fhs.feeCycleId = ".$feeCycleId." and fh.feeHeadId = fhs.feeHeadId AND fr.feeReceiptId=fhs.firstReceiptId AND fr.feeStudyPeriodId=".$studyPeriodId;
		
		 /*if(($feeType==1) || ($feeType==4)){
		 
			 $query .= " AND fh.transportHead != 1  AND fh.hostelHead != 1 ";
		 }
		 if($feeType==2){
		 
			 $query .= " AND fh.transportHead = 1 ";
		 }
		  if($feeType==3){
		 
			 $query .= " AND fh.hostelHead = 1 ";
		 }*/
         $query .= "  GROUP BY headName ORDER BY fhs.feeHeadStudentId ";
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT TOTAL FEES HEAD DETAILS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	 
	public function getFeeDetailClass($condition='') {

         $query = "SELECT paymentInstrument,SUM(instrumentAmount) as totalAmount FROM fee_payment_detail $condition Group by paymentInstrument";
		 
        
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
    }
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT FEES INSTALLMENT
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	 
	public function getStudentInstallment($studentId,$feeCycleId,$feeStudyPeriodId,$feeTypeId) {

         $query = "SELECT count(*) as toalInstallment FROM `fee_receipt` WHERE studentId = ".$studentId." AND feeCycleId =".$feeCycleId." AND feeStudyPeriodId=".$feeStudyPeriodId." AND feeType=".$feeTypeId;
		 
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT PREVIOUS FEES 
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	 
	public function getStudentPrevious($studentId,$feeCycleId,$feeStudyPeriodId,$feeTypeId) {

         $query = "SELECT previousDues,previousOverPayment,discountedFeePayable,feeStudyPeriodId FROM `fee_receipt` WHERE studentId = ".$studentId." AND feeType=".$feeTypeId." ORDER by feeReceiptId DESC limit 0,1";
		 
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
    }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT FEES INSTALLMENT
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	 
	public function getStudentFine($studentId,$feeCycleId,$feeStudyPeriodId) {
         $query = "SELECT fine FROM `fee_receipt` WHERE studentId = ".$studentId." AND feeCycleId =".$feeCycleId." AND feeStudyPeriodId=".$feeStudyPeriodId;
		 
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
    }
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT FEE CYCLE FINES
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
	public function getStudentCycleFineClass($todayDate) {
		 global $REQUEST_DATA;
         $query = "SELECT if(fineType = 2,DATEDIFF('".$REQUEST_DATA['receiptDate']."', fromDate) * fineAmount,fineAmount) as fineAmount
				   FROM fee_cycle_fines   
				   WHERE 
				   feeCycleId='".$REQUEST_DATA['feeCycleId']."' 
				   AND 
				   fromDate<='".$REQUEST_DATA['receiptDate']."' AND toDate>='".$REQUEST_DATA['receiptDate']."'";
		 
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
    }
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT FEE HEAD DETAILS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
	public function getStudentFeeHeadDetailClass($feeCycleId,$studyPeriodId,$studentInstitute, $universityId,$batchId,$degreeId,$branchId,$quotaId,$isLeet) {
		global $REQUEST_DATA;
         $query = "SELECT 
                         fh.feeHeadId, 
                         fh.headName, 
                         m.feeHeadAmount,
                         fh.isConsessionable,
                         fh.miscHead, 
                         m. * ,
                         CAST(CONCAT( IF( quotaId IS NULL , 0, quotaId ) , 
                         IF( universityId IS NULL , 0, universityId ), 
                         IF( batchId IS NULL, 0, batchId ), 
                         IF( degreeId IS NULL , 0, degreeId ), 
                         IF( branchId IS NULL , 0, branchId ), 
                         IF( studyPeriodId IS NULL , 0, studyPeriodId ), 
                         IF( isLeet IS NULL , 0, isLeet ) ) as SIGNED ) AS calValue
                  FROM	
                        `fee_head_values` m, fee_head fh
                  WHERE 
	                     m.feeHeadId = fh.feeHeadId 
                         AND fh.instituteId ='".$studentInstitute."' 
	                     HAVING calValue = (
                                            SELECT 
			                                      MAX(CAST(CONCAT( IF( quotaId IS NULL , 0, quotaId ) , IF( universityId IS NULL , 0, universityId ), IF( batchId IS NULL , 0, batchId ), IF( degreeId IS NULL , 0, degreeId ), IF( branchId IS NULL , 0, branchId ), IF( studyPeriodId IS NULL , 0, studyPeriodId ) , IF( isLeet IS NULL , 0, isLeet ) )  as SIGNED )) as calValue1
                                            FROM 
                                                  fee_head_values
                                            WHERE 
			                                      (quotaId ='".$quotaId."' OR quotaId IS NULL) 
                                                  AND (universityId ='".$universityId."' OR universityId IS NULL) 
                                                  AND (batchId ='".$batchId."' OR batchId IS NULL) 
                                                  AND (degreeId ='".$degreeId."' OR degreeId IS NULL) 
                                                  AND (branchId ='".$branchId."' OR branchId IS NULL) 
                                                  AND (studyPeriodId ='".$studyPeriodId."' OR studyPeriodId IS NULL) 
                                                  AND (isLeet ='".$isLeet."' OR isLeet IS NULL) 
                                                  AND feeHeadId = m.feeHeadId 
                                                  AND feeCycleId = '".$feeCycleId."'
			                                ) 
                         ORDER BY fh.sortingOrder
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
public function addStudentFeeRecipt($studentId,$instituteId,$installmentCount,$feeCycleId,$receiptNo,$receiptNo,$paidDate,$currentStudyPeriodId,$feeStudyPeriod,$totalFees,$totalFees,$dicountedFeePayable,$paymentMode,$chequeDraftNo,$paidDate,$previousDues,$previousOverPayment,$studentName,$userId,$feeType){
    
    $query="INSERT INTO 
                     fee_receipt
                     (
                      feeType,studentId,instituteId,installmentCount,feeCycleId,
                      srNo,receiptNo,receiptDate,receiptStatus,currentStudyPeriodId,
                      feeStudyPeriodId,totalFeePayable,printRemarks,generalRemarks,
                      totalAmountPaid,fine,discountedFeePayable,paymentInstrument,
                      instrumentNo,instrumentDate,payableBankId,issuingBankId,favouringBankBranchId,
                      instrumentStatus,previousDues,previousOverPayment,receivedFrom,userId
                     )
            VALUES
                    (
                      $feeType,$studentId,$instituteId,$installmentCount,$feeCycleId,
                      $receiptNo,$receiptNo,'$paidDate',2,$currentStudyPeriodId,
                      $feeStudyPeriod,$totalFees,'Uploaded from excel file','Uploaded from excel file',
                      $totalFees,0,$dicountedFeePayable,1,
                      1,$paidDate,0,0,0,0,
                      $previousDues,$previousOverPayment,'$studentName',$userId
                    )         
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function addStudentFeeHead($lastReceipt,$studentId,$feeHeadId,$feeCycleId, $studentHeadAmount,$feeStudyPeriodId){
    
    $query="INSERT INTO 
                     fee_head_student
                     (firstReceiptId,studentId,feeHeadId,feeCycleId,feeHeadAmount,discountedAmount,feeStudyPeriodId)
             VALUES
                    ($lastReceipt,$studentId,$feeHeadId,$feeCycleId, $studentHeadAmount,0,$feeStudyPeriodId)         
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT BUS FEE HEAD DETAILS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
	public function getStudentBusDetailClass($busCondition) {

		if($busCondition!=''){
		
			$query = "Select  feehead.feeHeadId, feehead.headName ,bus.transportCharges as feeHeadAmount,feehead.isConsessionable,feehead.transportHead
				  from fee_head feehead, bus_stop bus
				  where feehead.transportHead = 1 $busCondition";
		}
		else{
		
			$query = "Select  feehead.feeHeadId, feehead.headName ,feehead.isConsessionable,feehead.transportHead
				  from fee_head feehead
				  where feehead.transportHead = 1 ";
		}
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT HOSTEL FEE HEAD DETAILS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
	public function getStudentHostelDetailClass($hostelCondition) {

		if($busCondition!=''){

			$query = "Select  feehead.feeHeadId, feehead.headName ,hosroom.roomRent as feeHeadAmount,feehead.isConsessionable,feehead.hostelHead
				  from fee_head feehead, hostel_room hosroom
				  where feehead.hostelHead = 1";
		}
		else{
		
			$query = "Select  feehead.feeHeadId, feehead.headName,feehead.isConsessionable,feehead.hostelHead
				  from fee_head feehead
				  where feehead.hostelHead = 1";

		}
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT SUBJECT DETAILS BASED ON CLASS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
	public function getSubjectClass($classId) {
        $query = "SELECT sub.subjectId, sub.subjectName
        FROM subject sub, subject_to_class subcls 
        WHERE sub.subjectId = subcls.subjectId AND subcls.classId='$classId'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT ROLL NUMBER 
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	public function checkStudentRollNo($studentRoll,$studentId) {
        $query = "Select COUNT(*) as rollExists
				  from student
				  where rollNo = '$studentRoll' AND studentId!=$studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT ROLL NUMBER in QUARANTINE STUDENT
//
// Author :Rajeev Aggarwal
// Created on : (24.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	public function checkStudentQuarantineRollNo($studentRoll,$studentId) {
        $query = "Select COUNT(*) as rollExists
				  from `quarantine_student`
				  where rollNo = '$studentRoll' AND studentId!=$studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT REG NUMBER 
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	public function checkStudentRegNo($studentReg,$studentId) {
        $query = "Select COUNT(*) as regExists
				  from student
				  where regNo = '$studentReg' AND studentId!=$studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT REG NUMBER IN QUARATINE TABLE
//
// Author :Rajeev Aggarwal
// Created on : (24.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	public function checkStudentQuarantineRegNo($studentReg,$studentId) {
        $query = "Select COUNT(*) as regExists
				  from `quarantine_student`
				  where regNo = '$studentReg' AND studentId!=$studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT Univ NUMBER 
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	public function checkStudentUnivNo($studentUniv,$studentId) {
        $query = "Select COUNT(*) as univExists
				  from student
				  where universityRollNo = '$studentUniv' AND studentId!=$studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT Univ NUMBER  IN QUARATINE TABLE
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	public function checkStudentQuarantineUnivNo($studentUniv,$studentId) {
        $query = "Select COUNT(*) as univExists
				  from `quarantine_student`
				  where universityRollNo = '$studentUniv' AND studentId!=$studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT UniReg NUMBER 
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	public function checkStudentUnivRegNo($studentUnivReg,$studentId) {
        $query = "Select COUNT(*) as univRegExists
				  from student
				  where universityRegNo = '$studentUnivReg' AND studentId!=$studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT UniReg NUMBER  IN QUARATINE TABLE
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	public function checkStudentQuarantineUnivRegNo($studentUnivReg,$studentId) {
        $query = "Select COUNT(*) as univRegExists
				  from `quarantine_student`
				  where universityRegNo = '$studentUnivReg' AND studentId!=$studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT FEE RECEIPT NUMBER 
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	public function checkStudentFeeReceipt($studentFeeReceipt,$studentId) {
        $query = "Select COUNT(*) as studentFeeReceipt
				  from student
				  where feeReceiptNo = '$studentFeeReceipt' AND studentId!=$studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT FEE RECEIPT NUMBER 
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	public function checkHostelStudent($studentId) {
        $query = "Select hostelId, hostelRoomId
				  from student
				  where studentId=$studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT FEE RECEIPT NUMBER   IN QUARATINE TABLE
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	public function checkStudentQuarantineFeeReceipt($studentFeeReceipt,$studentId) {
        $query = "Select COUNT(*) as studentFeeReceipt
				  from `quarantine_student`
				  where feeReceiptNo = '$studentFeeReceipt' AND studentId!=$studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT MARKS 
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	public function getStudentMarks($studentId,$classId='',$orderBy=' studyPeriod',$limit='',$conditions='') {

		global $sessionHandler;
        /*$query = "SELECT su.subjectName, IF( tt.conductingAuthority =1, 'Internal', 'External' ) AS
				examType, CONCAT( t.testAbbr, t.testIndex ) AS testName, su.subjectCode, (
				tm.maxMarks
				) AS totalMarks, (
				tm.marksScored
				) AS obtained
				FROM test t, test_type tt, test_marks tm, student s, subject su
				WHERE t.testTypeId = tt.testTypeId
				AND t.testId = tm.testId
				AND tm.studentId = s.studentId
				AND tm.subjectId = su.subjectId
				AND tm.studentId =$studentId
				ORDER BY tm.subjectId, tt.conductingAuthority, t.testId, tm.studentId";*/
			
		  /* $query = "SELECT su.subjectName,  IF( tt.conductingAuthority =1, 'Internal', 'External' ) AS
					examType,tt.testTypeName,CONCAT( t.testAbbr, t.testIndex ) AS testName,  su.subjectCode, (
					tm.maxMarks
					) AS totalMarks, IF( tm.isMemberOfClass =0, 'Not MOC',
					IF(isPresent=1,tm.marksScored,'A'))  AS Obtained
					FROM test t, test_type tt, test_marks tm, student s, subject su
					WHERE t.testTypeId = tt.testTypeId
					AND t.testId = tm.testId
					AND tm.studentId = s.studentId
					AND tm.subjectId = su.subjectId
					AND tm.studentId =$studentId
					ORDER BY tm.subjectId, tt.conductingAuthority, t.testId, tm.studentId
					";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");*/

		$extC='';
           if($classId!=0){
               $extC=' AND cl.classId='.$classId;
           }
          $query = " SELECT
                        s.studentId,
						CONCAT(su.subjectName,' (',su.subjectCode,')') AS subjectName,
						CONCAT(IF( ttc.examType = 'PC', 'Internal', 'External' ), ' (' , ttc.testTypeName, ')' ) AS examType,
						ttc.testTypeName,t.testDate,
						emp.employeeName,
						CONCAT( t.testAbbr, t.testIndex ) AS testName, 
						su.subjectCode,
						(tm.maxMarks) AS totalMarks,
						ROUND(IF(tm.isMemberOfClass =0, 'Not MOC',IF(isPresent=1,tm.marksScored,'A')),2)  AS obtainedMarks,
						SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-1) AS studyPeriod,
						gr.groupName,
						ttc.colorCode
				FROM	test_type_category ttc,
						".TEST_MARKS_TABLE." tm,
						student s,
						subject su,
						employee emp,
						".TEST_TABLE." t,
						class cl,
						`group` gr
				WHERE	t.testTypeCategoryId = ttc.testTypeCategoryId
				AND		t.classId=cl.classId
				AND		emp.employeeId=t.employeeId
				AND		t.testId = tm.testId
				AND		t.groupId = gr.groupId
				AND		tm.studentId = s.studentId
				AND		tm.subjectId = su.subjectId
				AND		tm.studentId IN ( $studentId )
				AND		cl.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
				AND		cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
						$extC
                        $conditions
						ORDER BY $orderBy $limit ";
                

/*
           $query = "SELECT 
                          CONCAT(su.subjectName,'(',su.subjectCode,')') AS subjectName,
                          tt.testTypeName,
                          testDate,
                          SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-1) AS studyPeriod,
                          emp.employeeName,
                          CONCAT( t.testAbbr, t.testIndex ) AS  testName, 
                          (tm.maxMarks) AS totalMarks, 
                          IF( tm.isMemberOfClass =0, 'Not MOC',IF(isPresent=1,tm.marksScored,'A'))  AS obtainedMarks,
                          IF( tt.conductingAuthority =1, 'Internal', 'External' ) AS  examType
                    FROM 
                       test_type_category ttc,test t, test_type tt, test_marks tm, student s, subject su,`class` c,employee emp,
						`group` gr
                    WHERE 
						t.testTypeCategoryId = ttc.testTypeCategoryId AND		
                      t.classId=c.classId
                       AND t.employeeId=emp.employeeId
                       AND t.testId = tm.testId
                       AND tm.studentId = s.studentId
                       AND tm.subjectId = su.subjectId
					   AND		t.groupId = gr.groupId
                       AND tm.studentId =$studentId
					   AND		c.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
				AND		c.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                       $extC
                       ORDER BY $orderBy
                       $limit
                    ";
          
                   */
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT MARKS 
//
// Author :Rajeev Aggarwal
// Created on : (05.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	public function getStudentMarksCount($studentId,$classId='',$conditions='') {

		 if ($classId != "" and $classId != "0") {
			$classCond =" AND cl.classId =".add_slashes($classId);
		   }

		global $REQUEST_DATA;
		global $sessionHandler;
               
 $query = "	SELECT 
						COUNT(*) as totalRecords
				FROM	test_type_category ttc,
						test_marks tm,
						student s,
						subject su,
						employee emp,
						test t,
						class cl,
						`group` gr
				WHERE	t.testTypeCategoryId = ttc.testTypeCategoryId
				AND		t.classId=cl.classId
				AND		emp.employeeId=t.employeeId
				AND		t.testId = tm.testId
				AND		t.groupId = gr.groupId
				AND		tm.studentId = s.studentId
				AND		tm.subjectId = su.subjectId
				AND		tm.studentId IN ( $studentId )
				AND		cl.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
				AND		cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
						$classCond
                        $conditions";
                
                
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

    }
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT STUDENT from ADMIT STUDENT
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	

	public function insertStudentaToClass($classId) {

		global $REQUEST_DATA;
		
		$correspondenceCountry = $REQUEST_DATA['correspondenceCountry'];
		if($correspondenceCountry=="")
			$correspondenceCountry = 'NULL';

		$correspondenceStates = $REQUEST_DATA['correspondenceStates'];
		if($correspondenceStates=="")
			$correspondenceStates = 'NULL';

		$correspondenceCity = $REQUEST_DATA['correspondenceCity'];
		if($correspondenceCity=="")
			$correspondenceCity = 'NULL';


		$permanentCountry = $REQUEST_DATA['permanentCountry'];
		if($permanentCountry=="")
			$permanentCountry = 'NULL';

		$permanentStates = $REQUEST_DATA['permanentStates'];
		if($permanentStates=="")
			$permanentStates = 'NULL';
		
		$permanentCity = $REQUEST_DATA['permanentCity'];
		if($permanentCity=="")
			$permanentCity = 'NULL';

		$permanentAddress1 = $REQUEST_DATA['permanentAddress1'];
		$permanentAddress2 = $REQUEST_DATA['permanentAddress2'];
		$permanentPincode  = $REQUEST_DATA['permanentPincode'];
		$permanentPhone    = $REQUEST_DATA['permanentPhone'];

		if($REQUEST_DATA['sameText'])
		{
			$permanentAddress1 = $REQUEST_DATA['correspondeceAddress1'];
			$permanentAddress2 = $REQUEST_DATA['correspondeceAddress2'];
			$permanentPincode  = $REQUEST_DATA['correspondecePincode'];
			$permanentPhone    = $REQUEST_DATA['correspondecePhone'];
			$permanentCountry  = $correspondenceCountry;
			$permanentStates   = $correspondenceStates;
			$permanentCity     = $correspondenceCity;
		}

		$guardianCountry = $REQUEST_DATA['guardianCountry'];
		if($guardianCountry=="")
			$guardianCountry = 'NULL';

		$guardianStates = $REQUEST_DATA['guardianStates'];
		if($guardianStates=="")
			$guardianStates = 'NULL';

		$guardianCity = $REQUEST_DATA['guardianCity'];
		if($guardianCity=="")
			$guardianCity = 'NULL';

		$motherCountry = $REQUEST_DATA['motherCountry'];
		if($motherCountry=="")
			$motherCountry = 'NULL';

		$motherStates = $REQUEST_DATA['motherStates'];
		if($motherStates=="")
			$motherStates = 'NULL';

		$motherCity = $REQUEST_DATA['motherCity'];
		if($motherCity=="")
			$motherCity = 'NULL';

		$fatherCountry = $REQUEST_DATA['fatherCountry'];
		if($fatherCountry=="")
			$fatherCountry = 'NULL';

		$fatherStates = $REQUEST_DATA['fatherStates'];
		if($fatherStates=="")
			$fatherStates = 'NULL';

		$fatherCity = $REQUEST_DATA['fatherCity'];
		if($fatherCity=="")
			$fatherCity = 'NULL';

		//$todayDate = date('Y-m-d');
		$dateOfBirth = $REQUEST_DATA['studentYear']."-".$REQUEST_DATA['studentMonth']."-".$REQUEST_DATA['studentDate'];
		$todayDate = $REQUEST_DATA['admissionYear']."-".$REQUEST_DATA['admissionMonth']."-".$REQUEST_DATA['admissionDate'];
		
		$query = "INSERT INTO `student` SET 
		`classId`='$classId' , 
		`regNo`='".add_slashes($REQUEST_DATA[collegeRegNo])."' , 
		`rollNo`='".add_slashes($REQUEST_DATA[studentClassRole])."' ,
		`universityRollNo`='".add_slashes($REQUEST_DATA[studentUniversityRole])."' ,
		`isLeet`='".add_slashes($REQUEST_DATA[isLeet])."' , 
		`firstName`='".add_slashes($REQUEST_DATA[studentName])."' , 
		`lastName`='".add_slashes($REQUEST_DATA[studentLName])."' , 
		`studentMobileNo`='".add_slashes($REQUEST_DATA[studentMobile])."' , `studentEmail`='".add_slashes($REQUEST_DATA[studentEmail])."' ,
		`alternateStudentEmail`='".add_slashes($REQUEST_DATA[alternateEmail])."' ,
		`studentGender`='".add_slashes($REQUEST_DATA[genderRadio])."' , 
		`studentStatus`='1' , 
		`dateOfAdmission`='$todayDate' , 
		`dateOfBirth`='$dateOfBirth' , 
		`managementCategory`='".add_slashes($REQUEST_DATA[isMgmt])."' , `managementReference`='".add_slashes($REQUEST_DATA[studentReference])."' , `nationalityId`='".add_slashes($REQUEST_DATA[country])."' ,
		`domicileId`='".add_slashes($REQUEST_DATA[studentDomicile])."', 
		`quotaId`='".add_slashes($REQUEST_DATA[studentCategory])."' , 
		`compExamRank`='".add_slashes($REQUEST_DATA[studentRank])."' , 
		`compExamBy`='".add_slashes($REQUEST_DATA[entranceExam])."' , 
        `compExamRollNo`='".add_slashes($REQUEST_DATA['studentEntranceRole'])."' , 
		`studentPhone`='".add_slashes($REQUEST_DATA[studentNo])."' , 
		`fatherTitle`='".add_slashes($REQUEST_DATA[fatherTitle])."' , 
		`fatherName`='".add_slashes($REQUEST_DATA[fatherName])."' , `fatherOccupation`='".add_slashes($REQUEST_DATA[fatherOccupation])."', `fatherMobileNo`='".add_slashes($REQUEST_DATA[fatherMobile])."' , `fatherAddress1`='".add_slashes($REQUEST_DATA[fatherAddress1])."' , `fatherAddress2`='".add_slashes($REQUEST_DATA[fatherAddress2])."' , `fatherEmail`='".add_slashes($REQUEST_DATA[fatherEmail])."' , 
		`fatherPinCode`='".add_slashes($REQUEST_DATA[fatherPincode])."' , 
		`fatherCountryId`=$fatherCountry , 
		`fatherStateId`=$fatherStates , 
		`fatherCityId`=$fatherCity , 
		`motherTitle`='".add_slashes($REQUEST_DATA[motherTitle])."' , 
		`motherName`='".add_slashes($REQUEST_DATA[motherName])."', `motherOccupation`='".add_slashes($REQUEST_DATA[motherOccupation])."' , `motherMobileNo`='".add_slashes($REQUEST_DATA[motherMobile])."' , `motherAddress1`='".add_slashes($REQUEST_DATA[motherAddress1])."' , `motherAddress2`='".add_slashes($REQUEST_DATA[motherAddress2])."' , `motherEmail`='".add_slashes($REQUEST_DATA[motherEmail])."' , 
		`motherPinCode`='".add_slashes($REQUEST_DATA[motherPincode])."' , 
		`motherCountryId`=$motherCountry , 
		`motherStateId`=$motherStates , 
		`motherCityId`=$motherCity , 
		`guardianTitle`='".add_slashes($REQUEST_DATA[guardianTitle])."' , `guardianName`='".add_slashes($REQUEST_DATA[guardianName])."' , `guardianOccupation`='".add_slashes($REQUEST_DATA[guardianOccupation])."' , `guardianMobileNo`='".add_slashes($REQUEST_DATA[guardianMobile])."' , `guardianAddress1`='".add_slashes($REQUEST_DATA[guardianAddress1])."' , `guardianAddress2`='".add_slashes($REQUEST_DATA[guardianAddress2])."' , `guardianEmail`='".add_slashes($REQUEST_DATA[guardianEmail])."' ,
		`guardianPinCode`='".add_slashes($REQUEST_DATA[guardianPincode])."' , 
		`guardianCountryId`= $guardianCountry , 
		`guardianStateId`=$guardianStates , 
		`guardianCityId`=$guardianCity , 
		`corrAddress1`='".add_slashes($REQUEST_DATA[correspondeceAddress1])."' , `corrAddress2`='".add_slashes($REQUEST_DATA[correspondeceAddress2])."' , 
		`corrCountryId`=$correspondenceCountry, 
		`corrStateId`=$correspondenceStates , 
		`corrCityId`=$correspondenceCity, 
		`corrPinCode`='".add_slashes($REQUEST_DATA[correspondecePincode])."' , `corrPhone`='".add_slashes($REQUEST_DATA[correspondecePhone])."' , 
		`permAddress1`='".add_slashes($permanentAddress1)."' , 
		`permAddress2`='".add_slashes($permanentAddress2)."' , 
		`permCountryId`=$permanentCountry, 
		`permStateId`=$permanentStates, 
		`permCityId`=$permanentCity, 
		`permPinCode`='".add_slashes($permanentPincode)."' , 
		`permPhone`='".add_slashes($permanentPhone)."' , 
		`referenceName`='".add_slashes($REQUEST_DATA[referenceName])."' , 
		`feeReceiptNo`='".add_slashes($REQUEST_DATA[feeReceiptNo])."' , 
		`studentBloodGroup`='".add_slashes($REQUEST_DATA[bloodGroup])."' , 
		`studentSportsActivity`='".add_slashes($REQUEST_DATA[sportsActivity])."' ,
        `transportFacility`='".add_slashes($REQUEST_DATA['transportFacility'])."' , 
        `hostelFacility`='".add_slashes($REQUEST_DATA['hostelFacility'])."' ,  
		`studentRemarks`='".add_slashes($REQUEST_DATA[studentRemarks])."'";
		 
		 return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		 
		 //return 1;
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT STUDENT ACADEMICS
//
// Author :Rajeev Aggarwal
// Created on : (26.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  	
	public function insertStudentAcademics($rollNo,$session,$institute,$board,$marks,$maxMarks,$percentage,$educationStream,$previousClass,$studentId) {
		
		$cnt = count($rollNo);
		if($cnt)
		{
			$query = "DELETE 
			FROM `student_academic` 
			WHERE studentId = $studentId";
			$ret=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
			
			if($ret===false){
				
				return false;
			}
			$insertValue = "";
			for($i=0;$i<$cnt; $i++)
			{
				$querySeprator = '';
			    if($insertValue!=''){

					$querySeprator = ",";
			    }
				if(trim($rollNo[$i])!='' || trim($session[$i])!='' || trim($institute[$i])!='' || trim($board[$i])!='' || trim($marks[$i])!='' || trim($maxMarks[$i])!='' || trim($percentage[$i])!='' || trim($educationStream[$i])!=''){

					$insertValue .= "$querySeprator ('".add_slashes($previousClass[$i])."','".add_slashes($studentId)."','".add_slashes($rollNo[$i])."','".add_slashes($session[$i])."','".add_slashes($institute[$i])."','".add_slashes($board[$i])."','".add_slashes($marks[$i])."','".add_slashes($maxMarks[$i])."','".add_slashes($percentage[$i])."','".add_slashes(trim($educationStream[$i]))."')";
				}
				 
			}
			if($insertValue!=''){

				$query = "INSERT INTO `student_academic` 
					  (previousClassId,studentId,previousRollNo,previousSession,previousInstitute,previousBoard,previousMarks,previousMaxMarks,previousPercentage,previousEducationStream)
					  VALUES ".$insertValue;
			} 
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
			 
		}
		 
    }
  
 
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE STUDENT from STUDENT PROFILE
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	

	public function updateStudent($stUserId='NULL',$fatherId='NULL',$motherId='NULL',$guardianId='NULL') {
		global $REQUEST_DATA;
		global $sessionHandler;
        
		$loggedUserId = $sessionHandler->getSessionVariable('UserId');    
		
		$correspondenceCountry = $REQUEST_DATA['correspondenceCountry'];
		if($correspondenceCountry=="")
			$correspondenceCountry = 'NULL';

		$correspondenceStates = $REQUEST_DATA['correspondenceStates'];
		if($correspondenceStates=="")
			$correspondenceStates = 'NULL';

		$correspondenceCity = $REQUEST_DATA['correspondenceCity'];
		if($correspondenceCity=="")
			$correspondenceCity = 'NULL';


		$permanentCountry = $REQUEST_DATA['permanentCountry'];
		if($permanentCountry=="")
			$permanentCountry = 'NULL';

		$permanentStates = $REQUEST_DATA['permanentStates'];
		if($permanentStates=="")
			$permanentStates = 'NULL';

		$permanentCity = $REQUEST_DATA['permanentCity'];
		if($permanentCity=="")
			$permanentCity = 'NULL';

		$permanentAddress1 = $REQUEST_DATA['permanentAddress1'];
		$permanentAddress2 = $REQUEST_DATA['permanentAddress2'];
		$permanentPincode  = $REQUEST_DATA['permanentPincode'];
		$permanentPhone    = $REQUEST_DATA['permanentPhone'];

		if($REQUEST_DATA['sameText'])
		{
			$permanentAddress1 = $REQUEST_DATA['correspondeceAddress1'];
			$permanentAddress2 = $REQUEST_DATA['correspondeceAddress2'];
			$permanentPincode  = $REQUEST_DATA['correspondecePincode'];
			$permanentPhone    = $REQUEST_DATA['correspondecePhone'];
			$permanentCountry  = $correspondenceCountry;
			$permanentStates   = $correspondenceStates;
			$permanentCity     = $correspondenceCity;
		}

		$studentHostel = $REQUEST_DATA['studentHostel'];
		if($studentHostel=="")
			$studentHostel = 'NULL';

		$studentRoom = $REQUEST_DATA['studentRoom'];
		if($studentRoom=="")
			$studentRoom = 'NULL';

		$studentRoute = $REQUEST_DATA['studentRoute'];
		if($studentRoute=="")
			$studentRoute = 'NULL';

		$studentStop = $REQUEST_DATA['studentStop'];
		if($studentStop=="")
			$studentStop = 'NULL';

		$guardianCountry = $REQUEST_DATA['guardianCountry'];
		if($guardianCountry=="")
			$guardianCountry = 'NULL';

		$guardianStates = $REQUEST_DATA['guardianStates'];
		if($guardianStates=="")
			$guardianStates = 'NULL';

		$guardianCity = $REQUEST_DATA['guardianCity'];
		if($guardianCity=="")
			$guardianCity = 'NULL';

		$motherCountry = $REQUEST_DATA['motherCountry'];
		if($motherCountry=="")
			$motherCountry = 'NULL';

		$motherStates = $REQUEST_DATA['motherStates'];
		if($motherStates=="")
			$motherStates = 'NULL';

		$motherCity = $REQUEST_DATA['motherCity'];
		if($motherCity=="")
			$motherCity = 'NULL';

		$fatherCountry = $REQUEST_DATA['fatherCountry'];
		if($fatherCountry=="")
			$fatherCountry = 'NULL';

		$fatherStates = $REQUEST_DATA['fatherStates'];
		if($fatherStates=="")
			$fatherStates = 'NULL';

		$fatherCity = $REQUEST_DATA['fatherCity'];
		if($fatherCity=="")
			$fatherCity = 'NULL';

		$thGroup = $REQUEST_DATA['thGroup'];
		if($thGroup=="")
			$thGroup = 'NULL';

		$prGroup = $REQUEST_DATA['prGroup'];
		if($prGroup=="")
			$prGroup = 'NULL';

		$dateOfBirth = $REQUEST_DATA['studentYear']."-".$REQUEST_DATA['studentMonth']."-".$REQUEST_DATA['studentDate'];
		
		$studentRoll = add_slashes($REQUEST_DATA['studentRoll']);
		if($studentRoll=="")
			$studentRoll = 'NULL';
		else
			$studentRoll = "'$studentRoll'";

		$studentReg = add_slashes($REQUEST_DATA['studentReg']);
		if($studentReg=="")
			$studentReg = 'NULL';
		else
			$studentReg = "'$studentReg'";
		 
		$studentUniversityNo = add_slashes($REQUEST_DATA['studentUniversityNo']);
		if($studentUniversityNo=="")
			$studentUniversityNo = 'NULL';
		else
			$studentUniversityNo = "'$studentUniversityNo'";
		 
		$studentUniversityRegNo = add_slashes($REQUEST_DATA['studentUniversityRegNo']);
		if($studentUniversityRegNo=="")
			$studentUniversityRegNo = 'NULL';
		else
			$studentUniversityRegNo = "'$studentUniversityRegNo'";

		$studentDomicile = add_slashes($REQUEST_DATA['studentDomicile']);
		if($studentDomicile=="")
			$studentDomicile = 'NULL';
		else
			$studentDomicile = "'$studentDomicile'";
		 
		$query = "UPDATE `student` SET  
		`firstName`='".add_slashes($REQUEST_DATA[studentName])."' , 
		`lastName`='".add_slashes($REQUEST_DATA[studentLName])."' ,
		`rollNo`=$studentRoll,
		`regNo`=$studentReg ,
		`universityRollNo`=$studentUniversityNo,
		`universityRegNo`=$studentUniversityRegNo,
		`dateOfBirth`='$dateOfBirth' , 
		`studentGender`='".add_slashes($REQUEST_DATA[genderRadio])."' , 
		`studentEmail`='".add_slashes($REQUEST_DATA[studentEmail])."' ,
		`alternateStudentEmail`='".add_slashes($REQUEST_DATA[alternateEmail])."' ,
		`nationalityId`='".add_slashes($REQUEST_DATA[country])."' ,
		`studentPhone`='".add_slashes($REQUEST_DATA[studentNo])."' , 
		`studentMobileNo`='".add_slashes($REQUEST_DATA[studentMobile])."' , 
		`domicileId`=".$studentDomicile.",
		`quotaId`='".add_slashes($REQUEST_DATA[studentCategory])."' , 
		`isLeet`='".add_slashes($REQUEST_DATA[isLeet])."' , 	
		`studentStatus`='".add_slashes($REQUEST_DATA[isActive])."' ,
		`compExamRank`='".add_slashes($REQUEST_DATA[studentRank])."' , 
		`compExamBy`='".add_slashes($REQUEST_DATA[entranceExam])."' ,
		`compExamRollNo`='".add_slashes($REQUEST_DATA[entranceRoll])."' ,

		`corrAddress1`='".add_slashes($REQUEST_DATA[correspondeceAddress1])."' , `corrAddress2`='".add_slashes($REQUEST_DATA[correspondeceAddress2])."' , 
		`corrCountryId`=$correspondenceCountry, 
		`corrStateId`=$correspondenceStates, 
		`corrCityId`=$correspondenceCity , 
		`corrPinCode`='".add_slashes($REQUEST_DATA[correspondecePincode])."' , `corrPhone`='".add_slashes($REQUEST_DATA[correspondecePhone])."' , 
		`permAddress1`='".add_slashes($permanentAddress1)."' , 
		`permAddress2`='".add_slashes($permanentAddress2)."' , 
		`permCountryId`=$permanentCountry, 
		`permStateId`=$permanentStates, 
		`permCityId`=$permanentCity, 
		`permPinCode`='".add_slashes($permanentPincode)."' , 
		`permPhone`='".add_slashes($permanentPhone)."' , 
		`studentRemarks`='".add_slashes($REQUEST_DATA[studentRemarks])."',
		`managementCategory`='".add_slashes($REQUEST_DATA[isMgmt])."' , `managementReference`='".add_slashes($REQUEST_DATA[studentReference])."' , 
		`fatherTitle`='".add_slashes($REQUEST_DATA[fatherTitle])."' , 
		`fatherName`='".add_slashes($REQUEST_DATA[fatherName])."' , `fatherOccupation`='".add_slashes($REQUEST_DATA[fatherOccupation])."', `fatherMobileNo`='".add_slashes($REQUEST_DATA[fatherMobileNo])."' , `fatherAddress1`='".add_slashes($REQUEST_DATA[fatherAddress1])."' , `fatherAddress2`='".add_slashes($REQUEST_DATA[fatherAddress2])."' , `fatherEmail`='".add_slashes($REQUEST_DATA[fatherEmail])."' , 
		`fatherPinCode`='".add_slashes($REQUEST_DATA[fatherPinCode])."' , 
		`fatherCountryId`=$fatherCountry , 
		`fatherStateId`=$fatherStates , 
		`fatherCityId`=$fatherCity , 
		`motherTitle`='".add_slashes($REQUEST_DATA[motherTitle])."' , 
		`motherName`='".add_slashes($REQUEST_DATA[motherName])."', `motherOccupation`='".add_slashes($REQUEST_DATA[motherOccupation])."' , `motherMobileNo`='".add_slashes($REQUEST_DATA[motherMobileNo])."' , `motherAddress1`='".add_slashes($REQUEST_DATA[motherAddress1])."' , `motherAddress2`='".add_slashes($REQUEST_DATA[motherAddress2])."' , `motherEmail`='".add_slashes($REQUEST_DATA[motherEmail])."' , 
		`motherPinCode`='".add_slashes($REQUEST_DATA[motherPinCode])."' , 
		`motherCountryId`=$motherCountry , 
		`motherStateId`=$motherStates , 
		`motherCityId`=$motherCity , 
		`guardianTitle`='".add_slashes($REQUEST_DATA[guardianTitle])."' , `guardianName`='".add_slashes($REQUEST_DATA[guardianName])."' , `guardianOccupation`='".add_slashes($REQUEST_DATA[guardianOccupation])."' , `guardianMobileNo`='".add_slashes($REQUEST_DATA[guardianMobileNo])."' , `guardianAddress1`='".add_slashes($REQUEST_DATA[guardianAddress1])."' , `guardianAddress2`='".add_slashes($REQUEST_DATA[guardianAddress2])."' , `guardianEmail`='".add_slashes($REQUEST_DATA[guardianEmail])."' , 
		`guardianPinCode`='".add_slashes($REQUEST_DATA[guardianPinCode])."' , 
		`guardianCountryId`=$guardianCountry , 
		`guardianStateId`=$guardianStates , 
		`guardianCityId`=$guardianCity , ";

		if($stUserId)
			$query .="`userId`=$stUserId, ";
		
		if($fatherId)
			$query .="`fatherUserId`=$fatherId, ";

		if($motherId)
			$query .="`motherUserId`=$motherId, ";

		if($guardianId)
			$query .="`guardianUserId`=$guardianId, ";
		
		if($REQUEST_DATA[correspondeceVerified]!=$REQUEST_DATA[previousCorrespondence]){

			$query .=" `correspondenceAddressVerified`= '".$REQUEST_DATA[correspondeceVerified]."' ,correspondenceAddressVerifiedBy =".$loggedUserId.", ";
		}
		if($REQUEST_DATA[permanentVerified]!=$REQUEST_DATA[previousPermanent]){

			$query .=" permanentAddressVerified= '".$REQUEST_DATA[permanentVerified]."' ,permanentAddressVerifiedBy =".$loggedUserId.", ";
		}
		if($REQUEST_DATA[fatherVerified]!=$REQUEST_DATA[previousFatherVerified]){

			$query .=" fatherAddressVerified= '".$REQUEST_DATA[fatherVerified]."' ,fatherAddressVerifiedBy =".$loggedUserId.", ";
		}
		if($REQUEST_DATA[motherVerified]!=$REQUEST_DATA[previousMotherVerified]){

			$query .=" motherAddressVerified= '".$REQUEST_DATA[motherVerified]."' ,motherAddressVerifiedBy =".$loggedUserId.", ";
		}
		if($REQUEST_DATA[guardianVerified]!=$REQUEST_DATA[previousGuardianVerified]){

			$query .=" guardianAddressVerified= '".$REQUEST_DATA[guardianVerified]."' ,guardianAddressVerifiedBy =".$loggedUserId.", ";
		}
		$query .="`icardNumber`='".add_slashes($REQUEST_DATA[iCardNo])."' , 
		`managementCategory`='".add_slashes($REQUEST_DATA[managementCategory])."' , 
		`managementReference`='".add_slashes($REQUEST_DATA[managementReference])."' , 
		`hostelId`=$studentHostel , 
		`hostelRoomId`=$studentRoom , 
		`busRouteId`=$studentRoute , 
		`busStopId`=$studentStop ,
		`referenceName`='".add_slashes($REQUEST_DATA[referenceName])."' , 
		`feeReceiptNo`='".add_slashes($REQUEST_DATA[feeReceiptNo])."' , 
		`studentBloodGroup`='".add_slashes($REQUEST_DATA[bloodGroup])."' , 
		`hostelFacility`='".add_slashes($REQUEST_DATA[hostelFacility])."' , 
		`studentSportsActivity`='".add_slashes($REQUEST_DATA[sportsActivity])."'  

		WHERE StudentId = '".$REQUEST_DATA[studentId]."'";
		 
		SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT LIST
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	
	public function getStudentList($conditions='', $limit = '', $orderBy=' firstName') {
        global $sessionHandler;
        $sepratorLen = strlen(CLASS_SEPRATOR);
        
        $query = "SELECT 
                        DISTINCT
                          b.classId as class_id,
                          a.studentId, lastName,
                          CONCAT(IFNULL(a.firstName,''),IFNULL(a.lastName,''),'-',IFNULL(batchName,'')) AS studentNameBatch,
                          CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) as studentName,
                          CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) as firstName, 
                          IFNULL(a.firstName,'') as firstName1,
                          IF(IFNULL(rollNo,'')='','".NOT_APPLICABLE_STRING."',rollNo) AS rollNo,
                          IF(IFNULL(regNo,'')='','".NOT_APPLICABLE_STRING."',regNo) AS regNo,
                          IF(IFNULL(studentEmail,'')='','".NOT_APPLICABLE_STRING."',studentEmail) AS studentEmail, 
                          IF(IFNULL(fatherName,'')='','".NOT_APPLICABLE_STRING."',fatherName) AS fatherName,
                          IF(IFNULL(universityRollNo,'')='','".NOT_APPLICABLE_STRING."',universityRollNo) AS universityRollNo, 
                          SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-3) AS className,
                          IF(a.corrCityId Is NULL,'".NOT_APPLICABLE_STRING."',(SELECT cityName FROM city WHERE cityId = a.corrCityId)) AS corrCityId,
                          IF(a.classId Is NULL,'".NOT_APPLICABLE_STRING."',(SELECT periodName FROM study_period sp, class cls WHERE sp.studyPeriodId = cls.studyPeriodId and cls.classId = a.classId)) AS studyPeriod,
                          SUBSTRING_INDEX(substring_index(classname,'".CLASS_SEPRATOR."',4),'".CLASS_SEPRATOR."',-3) AS classId,
                          SUBSTRING_INDEX(classname,'".CLASS_SEPRATOR."',1) AS batchName,
                          IF(a.studentMobileNo='','".NOT_APPLICABLE_STRING."',a.studentMobileNo) AS studentMobileNo, e.branchName,
                          GROUP_CONCAT(DISTINCT(IFNULL(grp.groupName,'".NOT_APPLICABLE_STRING."')) ORDER BY grp.groupName SEPARATOR ', ') as groupId,
                          dateOfBirth AS DOB,
                          IF(fatherName IS NULL,'".NOT_APPLICABLE_STRING."',IF(fatherName='','".NOT_APPLICABLE_STRING."',fatherName)) AS fatherName,
                          IF(motherName IS NULL,'".NOT_APPLICABLE_STRING."',IF(motherName='','".NOT_APPLICABLE_STRING."',motherName)) AS motherName,
                          IF(guardianName IS NULL,'".NOT_APPLICABLE_STRING."',IF(guardianName='','".NOT_APPLICABLE_STRING."',guardianName)) AS guardianName,
                          IFNULL(a.userId,'') AS userId,         
                          IFNULL(fatherUserId,'') AS fatherUserId, IFNULL(motherUserId,'') AS motherUserId, 
                          IFNULL(guardianUserId,'') AS guardianUserId,
                          IF(a.userId IS NOT NULL,IF(a.userId<>'',(SELECT userName FROM `user` u WHERE u.userId=a.userId),''),'') AS userName, 
                          IF(a.fatherUserId IS NOT NULL,IF(a.fatherUserId<>'',(SELECT userName FROM `user` u WHERE u.userId=a.fatherUserId),''),'') AS fatherUserName, 
                          IF(a.motherUserId IS NOT NULL,IF(a.motherUserId<>'',(SELECT userName FROM `user` u WHERE u.userId=a.motherUserId),''),'') AS motherUserName,
                          IF(a.guardianUserId IS NOT NULL,IF(a.guardianUserId<>'',(SELECT userName FROM `user` u WHERE u.userId=a.guardianUserId),''),'') AS guardianUserName, IFNULL(u.userName,'') AS userName,
                          studentPhoto
                 FROM 
                          university c, degree d, branch e, study_period f, batch btch, student a
                          LEFT JOIN `user` u ON  u.userId = a.userId 
                          LEFT JOIN student_groups sg ON a.studentId = sg.studentId
                          LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
                          INNER JOIN class b ON (b.classId = a.classId OR sg.classId = b.classId) AND 
						  b.isActive=1
                 WHERE     
                          btch.batchId = b.batchId 
                          AND b.universityId = c.universityId
                          AND b.degreeId = d.degreeId
                          AND b.branchId = e.branchId
                          AND b.studyPeriodId = f.studyPeriodId
                          AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
                          AND b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."' 
                  $conditions
                  GROUP BY a.studentId 
                  ORDER BY $orderBy $limit";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET TOTAL NUMBER OF STUDENTS
//
// Author :Rajeev Aggarwal
// Created on : (24.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	

    public function getTotalRoleStudent($conditions='',$userId) {
		global $sessionHandler;
		$roleId = $sessionHandler->getSessionVariable('RoleId');

		$query = "SELECT 
							COUNT(DISTINCT a.studentId) AS totalRecords  
				  FROM 
                          university c, degree d, branch e, study_period f, classes_visible_to_role cvtr, student a
					      LEFT JOIN student_groups sg ON a.studentId = sg.studentId
					      LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
					      INNER JOIN class b ON b.classId = a.classId OR sg.classId = b.classId
				 WHERE     
                          b.universityId = c.universityId
					      AND b.degreeId = d.degreeId
                          AND b.branchId = e.branchId
                          AND b.studyPeriodId = f.studyPeriodId
						  AND cvtr.classId = a.classId
						  AND cvtr.classId = b.classId
						  AND cvtr.groupId = grp.groupId
						  AND cvtr.groupId = sg.groupId
				          AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
				          AND b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."' 
						  AND cvtr.userId = $userId
						  AND cvtr.roleId = $roleId
				  $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT LIST
//
// Author :Rajeev Aggarwal
// Created on : (24.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	
	public function getRoleStudentList($conditions='', $limit = '', $orderBy=' firstName',$userId) {
		global $sessionHandler;
		$roleId = $sessionHandler->getSessionVariable('RoleId');

		$sepratorLen = strlen(CLASS_SEPRATOR);
        $query = "SELECT 
				        DISTINCT
                          b.classId as class_id,
				          a.studentId,
				          firstName,
				          lastName,
				          IF(a.studentEmail='','".NOT_APPLICABLE_STRING."',a.studentEmail) AS studentEmail,
				          CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) as firstName,
				          IF(rollNo='','".NOT_APPLICABLE_STRING."',IFNULL(rollNo,'".NOT_APPLICABLE_STRING."')) AS rollNo,
				          SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-3) AS className,
				          IF(a.universityRollNo='','".NOT_APPLICABLE_STRING."',a.universityRollNo) AS universityRollNo,
				          IF(a.corrCityId Is NULL,'".NOT_APPLICABLE_STRING."',(SELECT cityName FROM city WHERE cityId = a.corrCityId)) AS corrCityId,
				          IF(a.classId Is NULL,'".NOT_APPLICABLE_STRING."',(SELECT periodName FROM study_period sp, class cls WHERE sp.studyPeriodId = cls.studyPeriodId and cls.classId = a.classId)) AS studyPeriod,
				          SUBSTRING_INDEX(substring_index(classname,'".CLASS_SEPRATOR."',4),'".CLASS_SEPRATOR."',-3) AS classId,
				          SUBSTRING_INDEX(classname,'".CLASS_SEPRATOR."',1) AS batchName,
				          IF(a.studentMobileNo='','".NOT_APPLICABLE_STRING."',a.studentMobileNo) AS studentMobileNo, e.branchName,
				          GROUP_CONCAT(DISTINCT(IFNULL(grp.groupName,'".NOT_APPLICABLE_STRING."')) ORDER BY grp.groupName SEPARATOR ', ') as groupId,
                          dateOfBirth AS DOB,
                          IF(fatherName IS NULL,'".NOT_APPLICABLE_STRING."',IF(fatherName='','".NOT_APPLICABLE_STRING."',fatherName)) AS fatherName,
                          IF(motherName IS NULL,'".NOT_APPLICABLE_STRING."',IF(motherName='','".NOT_APPLICABLE_STRING."',motherName)) AS motherName,
                          IF(guardianName IS NULL,'".NOT_APPLICABLE_STRING."',IF(guardianName='','".NOT_APPLICABLE_STRING."',guardianName)) AS guardianName,
                          IFNULL(fatherUserId,'') AS fatherUserId, IFNULL(motherUserId,'') AS motherUserId, 
                          IFNULL(guardianUserId,'') AS guardianUserId, IFNULL(a.userId,'') AS userId,
                          IF(a.fatherUserId IS NOT NULL,IF(a.fatherUserId<>'',(SELECT userName FROM `user` u WHERE u.userId=a.fatherUserId),''),'') AS fatherUserName, 
                          IF(a.motherUserId IS NOT NULL,IF(a.motherUserId<>'',(SELECT userName FROM `user` u WHERE u.userId=a.motherUserId),''),'') AS motherUserName,
                          IF(a.guardianUserId IS NOT NULL,IF(a.guardianUserId<>'',(SELECT userName FROM `user` u WHERE u.userId=a.guardianUserId),''),'') AS guardianUserName,
                          studentPhoto
				 FROM 
                          university c, degree d, branch e, study_period f, classes_visible_to_role cvtr, student a
					      LEFT JOIN student_groups sg ON a.studentId = sg.studentId
					      LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
					      INNER JOIN class b ON b.classId = a.classId OR sg.classId = b.classId
				 WHERE     
                          b.universityId = c.universityId
					      AND b.degreeId = d.degreeId
                          AND b.branchId = e.branchId
                          AND b.studyPeriodId = f.studyPeriodId
						  AND cvtr.classId = a.classId
						  AND cvtr.classId = b.classId
						  AND cvtr.groupId = grp.groupId
						  AND cvtr.groupId = sg.groupId
				          AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
				          AND b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."' 
						  AND cvtr.userId = $userId
						  AND cvtr.roleId = $roleId
				  $conditions
				  GROUP BY a.studentId 
				  ORDER BY $orderBy $limit";
                  
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET TOTAL NUMBER OF STUDENTS for payment history
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	
    public function getTotalFeesStudent($conditions='') {
    
        $query = "SELECT  COUNT(*) AS totalRecords  	 
				  FROM  fee_receipt  ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENTS FOR PAYMENT HISTORY
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	
	public function getFeesHistoryList($conditions='', $limit = '', $orderBy='') {
		
		global $sessionHandler; 
        $query = "SELECT 
				  receiptNo,srNo,feeType,
				  DATE_FORMAT(receiptDate, '%d-%b-%y') as receiptDate,
				  CONCAT('Installment','-',installmentCount) as installmentCount,	
				  FORMAT(sum(discountedAmount),2) as discountAmount, feeReceiptId,
				  stu.rollNo, 
				  stu.regNo,		
				  stu.universityRollNo, 
				  stu.universityRegNo, 
				  CONCAT(stu.firstName,' ',stu.lastName) as fullName,
				  fr.totalFeePayable as totalFeePayable, 
				  fr.fine as fine,
				  SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-3) AS className,
				  fr.totalAmountPaid as totalAmountPaid,
				  fr.discountedFeePayable as discountedFeePayable,
				  fr.previousDues  as previousDues,
				  fr.previousOverPayment as previousOverPayment,
				 
				  CAST(if(fr.previousDues>0,fr.previousDues,if(fr.previousOverPayment>0,CONCAT('-',fr.previousOverPayment),'0.00')) AS signed) as outstanding,
				  fc.cycleName
				  FROM student stu, fee_receipt fr,fee_head_student fhs,fee_cycle fc,class cls 
				  WHERE 
				  stu.studentId = fr.studentId AND 
				  stu.studentId = fhs.studentId 
				  $conditions AND 
				  fr.feeCycleId = fc.feeCycleId AND 
				  stu.classId = cls.classId AND
				  cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				  cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');

		$query .=" GROUP BY feeReceiptId  $orderBy $limit";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENTS FOR PAYMENT HISTORY
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	
	public function getFeeCollectionList($conditions='', $limit = '', $orderBy='') {
		 
        $query = "SELECT
				  fc.cycleName,
				  stu.classId,
				  fr.paymentInstrument,
				  fr.feeCycleId,
				  fr.totalAmountPaid 
				  FROM student stu, fee_receipt fr,class cls,fee_cycle fc
				  WHERE stu.studentId = fr.studentId and stu.classId = cls.classId and fc.feeCycleId = fr.feeCycleId $conditions";
		 
		 $query .=" $orderBy";
		//$query .=" GROUP BY feeReceiptId  $orderBy";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT RECEIPT
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	
	public function getFeesReceiptDetail($conditions='') {
		 
        $query = "SELECT 
				 fr.receiptDate,
				 stu.rollNo,
				 stu.studentId,
				 stu.fatherTitle,
				 stu.fatherName,
				 stu.studentGender,
				 stu.corrAddress1,
				 stu.corrAddress2,
				 stu.corrPinCode,
				 cc.countryName,
				 cs.stateName,
				 ct.cityName,
				 fr.feeCycleId,
				 fc.cycleAbbr,
				 fr.feeStudyPeriodId,
				 fr.studentId,
				 sp.periodName,
				 fr.srNo, 	
				 fr.cashAmount,
				 fr.receiptNo,
				 cls.classId,
				 cls.className,
				 stu.firstName,
				 stu.lastName,
				 (SELECT periodName FROM study_period WHERE studyPeriodId=fr.currentStudyPeriodId) as currStudyPeriod,
				 (SELECT periodName FROM study_period WHERE studyPeriodId=fr.feeStudyPeriodId) as feeStudyPeriod,
				 fr.currentStudyPeriodId,
				 fr.printRemarks,
				 fr.generalRemarks,
				 fr.receivedFrom,
				 fr.totalFeePayable,
				 fr.previousDues,
				 fr.discountedFeePayable,
				 fr.fine,
				 fr.totalAmountPaid,
				 
				 
				 fr.payableBankId, 	
				 fr.favouringBankBranchId	
				  
				 

				 FROM fee_receipt fr, class cls, fee_cycle fc, study_period sp,student stu 
				 left join countries cc on(stu.corrCountryId = cc.countryId)
				 left join states cs on(stu.corrStateId = cs.stateId)
				 left join city ct on(stu.corrCityId = ct.cityId)
				 WHERE $conditions AND 
				 fr.studentId = stu.studentId AND 
				 cls.classId = stu.classId AND 
				 fc.feeCycleId = fr.feeCycleId AND
				 fr.feeStudyPeriodId = sp.studyPeriodId
				 ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT DATA
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   		
	public function getStudentData($studentId) {
     
        $query = "SELECT * 
        FROM student
        WHERE studentId=$studentId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET USER DATA
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	
	public function getUserData($userId) {
     
        $query = "SELECT userName 
        FROM user
        WHERE userId=$userId ";
        
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET student bank detail
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	
	public function getFavBankDetail($favBankId) {
     
        $query = "SELECT branchAbbr,accountNumber from bank_branch 
		WHERE bankBranchId='$favBankId'";
        
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET student issuing bank detail
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	
	public function getIssueBankDetail($issueBankId) {
     
        $query = "SELECT bankName,bankAbbr from bank WHERE bankId='$issueBankId'";
        
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }	
 
	public function getStudentTotalFeesClass($studentId,$classId) {
     
        $query = "SELECT COUNT(*) as totalRecords
        FROM fee_receipt
        WHERE studentId=$studentId 
		";
        
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getStudentFeesClass($studentId,$classId,$orderBy,$limit) {
     
        $query = "SELECT receiptNo,DATE_FORMAT(receiptDate,'%d-%b-%Y') AS receiptDate,totalFeePayable,discountedFeePayable ,totalAmountPaid
        FROM fee_receipt
        WHERE studentId=$studentId 
		ORDER BY $orderBy
		$limit";
        
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET TOTAL NUMBER OF STUDENTS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	

    public function getTotalStudent($conditions='') {
		global $sessionHandler;

        global $sessionHandler;
		$query = "SELECT 
							COUNT(DISTINCT a.studentId) AS totalRecords  
				  FROM university c, degree d, branch e, study_period f, student a

				  LEFT JOIN student_groups sg ON a.studentId = sg.studentId
                  LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
                  INNER JOIN class b ON (b.classId = a.classId OR sg.classId = b.classId)  AND 
				  b.isActive=1
				  
				  WHERE b.universityId = c.universityId
						AND b.degreeId = d.degreeId
						AND b.branchId = e.branchId
						AND b.studyPeriodId = f.studyPeriodId
				        AND	b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
				        AND	b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."' 
				  $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO CHECK USER
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	
	public function checkUser($userId,$personUserId='') {
    
        $query = "SELECT COUNT(*) AS userExists 
        FROM user usr, student stu WHERE usr.userName = '$userId'";
		if($personUserId)
			$query .=" AND usr.userId!=$personUserId";

		 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function checkFatherUserName($userName,$studentId) {
    
        $query = "SELECT CONCAT(stu.firstName,stu.lastName) as fullName
		FROM user as us, student as stu 
		WHERE us.userName = '$userName' AND us.userId=stu.fatherUserId AND stu.studentId!='$studentId'";
		 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function checkMotherUserName($userName,$studentId) {
    
        $query = "SELECT CONCAT(stu.firstName,stu.lastName) as fullName
		FROM user as us, student as stu 
		WHERE us.userName = '$userName' AND us.userId=stu.motherUserId AND stu.studentId!='$studentId'";
		 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function checkGuardianUserName($userName,$studentId) {
    
        $query = "SELECT CONCAT(stu.firstName,stu.lastName) as fullName
		FROM user as us, student as stu 
		WHERE us.userName = '$userName' AND us.userId=stu.guardianUserId AND stu.studentId!='$studentId'";
		 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
	public function getFieldValue($tableName, $whereFieldName, $whereFieldValue, $fromFieldName) {
    
        $query = "SELECT $whereFieldName
        FROM `$tableName` 
        WHERE $fromFieldName =  $whereFieldValue";

		return SystemDatabaseManager::getInstance()->runSingleQuery($query,"Query: $query");
    }    
	
	public function insertUser($userName,$password,$rollId) {

		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$password = md5($password);

		SystemDatabaseManager::getInstance()->runAutoInsert('user', array('userName','userPassword','roleId', 'instituteId'), array($userName,$password,$rollId, $instituteId));
		return SystemDatabaseManager::getInstance()->lastInsertId();
	}
	 public function updateUser($password,$rolluserId) {
        global $REQUEST_DATA;
		$password = md5($password);
        
		return SystemDatabaseManager::getInstance()->runAutoUpdate('user', array('userPassword'), array($password), "userId='$rolluserId'" );
    } 
	 public function editStudentParentDetails($fatherId,$motherId,$guardianId,$studentId) {

		$query = "UPDATE `student` SET ";
		if($fatherId)
			$query .= "fatherUserId = ".$fatherId;
		else
			$query .= "fatherUserId = NULL";

		if($motherId)
			$query .= ", motherUserId = ".$motherId;
		else
			$query .= ", motherUserId = NULL";

		if($guardianId)
			$query .= ", guardianUserId = ".$guardianId;
		else
			$query .= ", guardianUserId = NULL";
		
		$query .=" WHERE studentId = $studentId";
		SystemDatabaseManager::getInstance()->executeUpdate($query);
		/*`classId`='$classId' , 
		`isLeet`='".add_slashes($REQUEST_DATA[isLeet])."' , 
		`firstName`='".add_slashes($REQUEST_DATA[studentName])."' , 
		`lastName`='".add_slashes($REQUEST_DATA[studentLName])."' , 
		`studentMobileNo`='".add_slashes($REQUEST_DATA[studentMobile])."' , `studentEmail`='".add_slashes($REQUEST_DATA[studentEmail])."' , 
		`studentGender`='".add_slashes($REQUEST_DATA[genderRadio])."' ,

        return SystemDatabaseManager::getInstance()->runAutoUpdate('student', array('fatherUserId', 	'motherUserId','guardianUserId'), array($fatherId,$motherId,$guardianId), "studentId=$studentId" );*/
    } 
	/* public function editStudentParentDetails($fatherId,$motherId,$guardianId,$studentId) {
        return SystemDatabaseManager::getInstance()->runAutoUpdate('student', array('fatherUserId', 	'motherUserId','guardianUserId'), array($fatherId,$motherId,$guardianId), "studentId=$studentId" );
    } */
	
	 /*
    @@ purpose: To update filename(for logo image) in 'student' table
    @@ author: Rajeev Aggarwal
    @@ Params: Id (Student ID), filename (name of the file)
    @@ created On: 23.06.2008
    @@ returns: boolean value
    */
    public function updatePhotoFilenameInStudent($id, $fileName) {
		return SystemDatabaseManager::getInstance()->runAutoUpdate('student', 
        array('studentPhoto'), 
        array($fileName), "studentId = $id");
    } 
    
     public function getStudentInformationList($studentId){
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');        
        $query = "SELECT s.*, 
        c.nationalityName, 
        st.stateName,
        q.quotaName,
        u.userName, 
        uf.userName as fatherUserName, 
        um.userName as motherUserName, 
        ug.userName as guardianUserName,
        un.universityName, 
        deg.degreeName, 
        br.branchName, 
        bt.batchName, 
        sp.periodName, 
        icardNumber, 
        if(managementCategory=1, managementReference,''), 
        hs.hostelName, 
        hr.roomName, 
        bsr.routeName, 
        bs.stopName,
        cc.countryName as corrCountry,
        cs.stateName as corrState,
        cct.cityName as corrCity, 
        permcn.countryName as permCountry, 
        permst.stateName as permState, 
        permct.cityName as permCity, 
        fatcn.countryName as fatherCountry, 
        fatst.stateName as fatherState, 
        fatct.cityName as fatherCity, 
        motcn.countryName as motherCountry, 
        motst.stateName as motherState, 
        motct.cityName as motherCity, 
        gudcn.countryName as guardianCountry, 
        gudst.stateName as guardianState, 
        gudct.CityName as guardianCity 
    FROM class cl, degree deg, 
        branch br, batch bt, study_period sp, university un, student s 
    LEFT JOIN countries c ON ( s.nationalityId = c.countryId )
    LEFT JOIN states st ON ( s.domicileId = st.stateId )            
    LEFT JOIN quota q ON ( s.quotaId = q.quotaId )
    LEFT JOIN user u ON ( s.userId = u.userId )
    LEFT JOIN countries cc ON ( s.corrCountryId=cc.countryId )
    LEFT JOIN states cs ON ( s.corrStateId=cs.stateId )
    LEFT JOIN city cct ON ( s.corrCityId = cct.cityId )
    LEFT JOIN countries permcn ON ( s.permCountryId = permcn.countryId )
    LEFT JOIN states permst ON ( s.permStateId = permst.stateId )
    LEFT JOIN city permct ON ( s.permCityId=permct.cityId )
    LEFT JOIN countries fatcn ON ( s.fatherCountryId=fatcn.countryId )
    LEFT JOIN states fatst ON ( s.fatherStateId=fatst.stateId )
    LEFT JOIN city fatct ON ( s.fatherCityId=fatct.cityId )
    LEFT JOIN countries motcn ON ( s.motherCountryId=motcn.countryId )
    LEFT JOIN states motst ON ( s.motherStateId=motst.stateId )
    LEFT JOIN city motct ON ( s.motherCityId=motct.cityId )
    LEFT JOIN countries gudcn ON ( s.guardianCountryId=gudcn.countryId )
    LEFT JOIN states gudst ON ( s.guardianStateId=gudst.stateId )
    LEFT JOIN city gudct ON ( s.guardianCityId=gudct.cityId )
    LEFT JOIN user uf ON ( s.fatherUserId=uf.userId )    
    LEFT JOIN user um ON ( s.motherUserId=um.userId )
    LEFT JOIN user ug ON ( s.guardianUserId=ug.userId )
    LEFT JOIN hostel hs ON ( s.hostelId=hs.hostelId )
    LEFT JOIN hostel_room hr ON (s.hostelRoomId = hr.hostelRoomId)
    LEFT JOIN bus_route bsr ON (s.busRouteId=bsr.busRouteId)
    LEFT JOIN bus_stop bs ON (s.busStopId=bs.busStopId)
    
    WHERE studentId=$studentId 
	AND s.classId=cl.classId 
	AND cl.universityId=un.universityId 
	AND cl.degreeId=deg.degreeId 
	AND cl.branchId=br.branchId 
	AND cl.batchId=bt.batchId 
	AND cl.studyPeriodId=sp.studyPeriodId
	AND cl.instituteId=$instituteId"  ;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
    }
	


	public function getDeletedStudentInformationList($studentId){
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');        
        $query = "SELECT s.*, 
        c.nationalityName, 
        st.stateName,
        q.quotaName,
        u.userName, 
        uf.userName as fatherUserName, 
        um.userName as motherUserName, 
        ug.userName as guardianUserName,
        un.universityName, 
        deg.degreeName, 
        br.branchName, 
        bt.batchName, 
        sp.periodName, 
        icardNumber, 
        if(managementCategory=1, managementReference,''), 
        hs.hostelName, 
        hr.roomName, 
        bsr.routeName, 
        bs.stopName,
        cc.countryName as corrCountry,
        cs.stateName as corrState,
        cct.cityName as corrCity, 
        permcn.countryName as permCountry, 
        permst.stateName as permState, 
        permct.cityName as permCity, 
        fatcn.countryName as fatherCountry, 
        fatst.stateName as fatherState, 
        fatct.cityName as fatherCity, 
        motcn.countryName as motherCountry, 
        motst.stateName as motherState, 
        motct.cityName as motherCity, 
        gudcn.countryName as guardianCountry, 
        gudst.stateName as guardianState, 
        gudct.CityName as guardianCity 
    FROM class cl, degree deg, 
        branch br, batch bt, study_period sp, university un, quarantine_student s 
    LEFT JOIN countries c ON ( s.nationalityId = c.countryId )
    LEFT JOIN states st ON ( s.domicileId = st.stateId )            
    LEFT JOIN quota q ON ( s.quotaId = q.quotaId )
    LEFT JOIN user u ON ( s.userId = u.userId )
    LEFT JOIN countries cc ON ( s.corrCountryId=cc.countryId )
    LEFT JOIN states cs ON ( s.corrStateId=cs.stateId )
    LEFT JOIN city cct ON ( s.corrCityId = cct.cityId )
    LEFT JOIN countries permcn ON ( s.permCountryId = permcn.countryId )
    LEFT JOIN states permst ON ( s.permStateId = permst.stateId )
    LEFT JOIN city permct ON ( s.permCityId=permct.cityId )
    LEFT JOIN countries fatcn ON ( s.fatherCountryId=fatcn.countryId )
    LEFT JOIN states fatst ON ( s.fatherStateId=fatst.stateId )
    LEFT JOIN city fatct ON ( s.fatherCityId=fatct.cityId )
    LEFT JOIN countries motcn ON ( s.motherCountryId=motcn.countryId )
    LEFT JOIN states motst ON ( s.motherStateId=motst.stateId )
    LEFT JOIN city motct ON ( s.motherCityId=motct.cityId )
    LEFT JOIN countries gudcn ON ( s.guardianCountryId=gudcn.countryId )
    LEFT JOIN states gudst ON ( s.guardianStateId=gudst.stateId )
    LEFT JOIN city gudct ON ( s.guardianCityId=gudct.cityId )
    LEFT JOIN user uf ON ( s.fatherUserId=uf.userId )    
    LEFT JOIN user um ON ( s.motherUserId=um.userId )
    LEFT JOIN user ug ON ( s.guardianUserId=ug.userId )
    LEFT JOIN hostel hs ON ( s.hostelId=hs.hostelId )
    LEFT JOIN hostel_room hr ON (s.hostelRoomId = hr.hostelRoomId)
    LEFT JOIN bus_route bsr ON (s.busRouteId=bsr.busRouteId)
    LEFT JOIN bus_stop bs ON (s.busStopId=bs.busStopId)
    
    WHERE s.studentId=$studentId 
	AND s.classId=cl.classId 
	AND cl.universityId=un.universityId 
	AND cl.degreeId=deg.degreeId 
	AND cl.branchId=br.branchId 
	AND cl.batchId=bt.batchId 
	AND cl.studyPeriodId=sp.studyPeriodId
	AND cl.instituteId=$instituteId"  ;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
    } 

	
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A STUDENT COUNT ON THE BASIS OF CLASS
//orderBy: on which column to sort
//
// Author :Ajinder Singh
// Created on : (24.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------   	
	public function countClassStudents($classId, $conditions='') {
			$query = "SELECT COUNT(*) AS cnt FROM student WHERE classId = $classId $conditions";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET  REGISTRATION NO., FIRST NAME AND LAST NAME BASED ON CLASS AND SORTING FIELD
//orderBy: on which column to sort
//
// Author :Ajinder Singh
// Created on : (24.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  	
	public function getStudents($classId, $orderBy, $conditions = '') {
		$query = "SELECT studentId, universityRollNo, regNo, concat(firstName, ' ', lastName) as studentName, rollNo FROM student WHERE classId = $classId  $conditions ORDER BY $orderBy";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO UPDATE STUDENT ROLL NO.
//orderBy: on which column to sort
//
// Author :Ajinder Singh
// Created on : (24.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  	
	public function updateStudentRollNo($studentId, $rollNo) {
        return SystemDatabaseManager::getInstance()->runAutoUpdate('student', array('rollNo'), array($rollNo), "studentId=$studentId" );
	}

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO CHECK THE ROLL NO.S BEFORE ISSUING
//orderBy: on which column to sort
//
// Author :Ajinder Singh
// Created on : (25.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  	
	public function checkRollNo($rollNoList, $studentIdList) {
		$query = "SELECT COUNT(*) as cnt FROM student WHERE rollNo IN ($rollNoList) AND studentId not in ($studentIdList)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function checkUserName($rollNoList, $studentUserIdList) {
		$query = "SELECT COUNT(*) as cnt FROM user WHERE userName IN ($rollNoList) AND userId not in ($studentUserIdList)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function getStudentTimeTable ($studentId,$classId,$currentClassId,$orderBy=' periodNumber, daysOfWeek',$fieldName='') {
        
        if ($classId != "" and $classId != "0") {
			$classCond =" AND cl.classId =".add_slashes($classId);
		   }
		if ($classId==0) {
			$classCond = " AND cl.classId =".add_slashes($currentClassId);
		}
        
        global $sessionHandler;
       
        if($fieldName=='') {
          $fieldName = "tt.periodId, tt.daysOfWeek, p.periodNumber,
                        CONCAT(p.startTime,p.startAmPm,' ',endTime,endAmPm) AS pTime, 
                        s.studentId, sub.subjectCode,  sub.subjectName,
                        sub.subjectAbbreviation, emp.employeeName,
                        r.roomName, r.roomAbbreviation, gr.groupName,
                        SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-1) AS periodName,
                        cl.className, ttc.timeTableLabelId, gr.groupShort, tt.fromDate, ttl.timeTableType";  
        }
        
        $query = "SELECT
				         $fieldName 
	              FROM		
                        `time_table` tt, `period` p, `student` s, `subject` sub, `employee` emp,
				        `room` r, `block` bl, `student_groups` sg, `time_table_labels` ttl, 
				        `time_table_classes` ttc, `group` gr, class cl 
	              WHERE	
                        tt.periodId = p.periodId 
	                    AND	 s.studentId=sg.studentId 
	                    AND	 tt.subjectId = sub.subjectId 
	                    AND	 sg.groupId = gr.groupId
	                    AND			tt.groupId = sg.groupId
	                    AND			tt.employeeId=emp.employeeId 
	                    AND			r.blockId = bl.blockId 
	                    AND			tt.roomId = r.roomId 
	                    AND			tt.toDate IS NULL 
	                    AND			tt.timeTableLabelId = ttl.timeTableLabelId 
	                    AND			ttl.timeTableLabelId = ttc.timeTableLabelId 
	                    AND			sg.classId = ttc.classId
	                    AND			sg.classId = cl.classId
	                    AND			sg.studentId=".$studentId." 
	                    AND			tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." 
	                    AND			tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
				      $classCond 
                  UNION    
                  SELECT
                        $fieldName 
                  FROM        
                        `time_table` tt, `period` p, `student` s, `subject` sub, `employee` emp,
                        `room` r, `block` bl, `student_optional_subject` sg, `time_table_labels` ttl, 
                        `time_table_classes` ttc, `group` gr, class cl 
                  WHERE    
                        tt.periodId = p.periodId 
                        AND     s.studentId=sg.studentId 
                        AND     tt.subjectId = sub.subjectId 
                        AND     sg.groupId = gr.groupId
                        AND            tt.groupId = sg.groupId
                        AND            tt.employeeId=emp.employeeId 
                        AND            r.blockId = bl.blockId 
                        AND            tt.roomId = r.roomId 
                        AND            tt.toDate IS NULL 
                        AND            tt.timeTableLabelId = ttl.timeTableLabelId 
                        AND            ttl.timeTableLabelId = ttc.timeTableLabelId 
                        AND            sg.classId = ttc.classId
                        AND            sg.classId = cl.classId
                        AND            sg.studentId=".$studentId." 
                        AND            tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." 
                        AND            tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                      $classCond    
                      
				      ORDER BY $orderBy";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");      
      }

public function getFieldNullValue($tableName, $whereFieldName, $whereFieldValue, $fromFieldName) {
    
        $query = "SELECT if($whereFieldName IS NULL,-1,$whereFieldName) as $whereFieldName
        FROM `$tableName` 
        WHERE $fromFieldName =  '$whereFieldValue'";

		return SystemDatabaseManager::getInstance()->runSingleQuery($query,"Query: $query");
    }  
	

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO COUNT STUDENTS FOR WHICH THE GROUP HAS NOT BEEN ISSUED YET
//
// Author :Ajinder Singh
// Created on : (25.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  	
	public function countPendingStudents($classId, $groupVal='') {
		$query = "SELECT COUNT(*) as cnt FROM student WHERE classId = $classId $groupVal";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

//-------------------------------------------------------
//  THIS FUNCTION IS USED FOR UPDATING THE ROLL NO. PREFIX IN THE CLASS
//
// Author :Ajinder Singh
// Created on : (25.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  	
	public function updateClassPrefix($classId, $prefix) {
        return SystemDatabaseManager::getInstance()->runAutoUpdate('class', array('rollNoPrefix'), array($prefix), "classId=$classId" );
	}

	public function updateClassPrefixInTransaction($classId, $prefix) {
		$query = "update class set rollNoPrefix = '$prefix' where classId=$classId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
//        return SystemDatabaseManager::getInstance()->runAutoUpdate('class', array('rollNoPrefix'), array($prefix), "classId=$classId" );
	}

//-------------------------------------------------------
//  THIS FUNCTION IS USED FOR UPDATING THE ROLL NO. SUFFIX IN THE CLASS
//
// Author :Ajinder Singh
// Created on : (04.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  	
	public function updateClassSuffixInTransaction($classId, $suffix) {
		$query = "update class set rollNoSuffix = '$suffix' where classId=$classId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        //return SystemDatabaseManager::getInstance()->runAutoUpdate('class', array('rollNoSuffix'), array($suffix), "classId=$classId" );
	}

//-------------------------------------------------------
//  THIS FUNCTION IS USED FOR FETCHING THE CLASS PREFIX
//
// Author :Ajinder Singh
// Created on : (25.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  	
	public function getClassPrefixSuffix($classId) {
		$query = "SELECT rollNoPrefix, rollNoSuffix FROM class WHERE classId = $classId ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

//-------------------------------------------------------
//  THIS FUNCTION IS USED FOR FETCHING THE MAX ROLL NO. WHOM ROLL NO. HAS BEEN ISSUED
//
// Author :Ajinder Singh
// Created on : (25.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  	
	public function getClassRollNo($classId, $prefix='', $suffix='', $conditions='', $limit, $ordering) {
		$prefixLen = strlen($prefix) + 1;
		$suffixLen = strlen($suffix) + 1;
//		$query = "SELECT SUBSTRING(rollNo,$prefixLen) as rollNo FROM student WHERE classId = $classId AND rollNo LIKE '$prefix%' $conditions ";
		//echo $query = "SELECT SUBSTRING(LEFT(rollNo, length(rollNo) - LENGTH('$suffix')), $prefixLen)  as rollNo FROM student WHERE classId = $classId AND rollNo LIKE '$prefix%$suffix' $conditions ";
		$query = "SELECT a.studentId, a.rollNo, CONVERT(SUBSTRING(LEFT( a.rollNo, length(a.rollNo) - LENGTH(b.rollNoSuffix)) , LENGTH( b.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo FROM student a, class b WHERE a.classId =$classId AND a.classId = b.classId AND a.rollNo LIKE '$prefix%$suffix' $conditions $ordering $limit";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

//-------------------------------------------------------
//  THIS FUNCTION IS USED FOR FETCHING THE MAX ROLL NO. WHOM ROLL NO. HAS BEEN ISSUED
//
// Author :Ajinder Singh
// Created on : (25.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  	
	public function getStudentClassRollNo($classId, $prefix='', $suffix='', $conditions='', $limit, $ordering) {
		$prefixLen = strlen($prefix) + 1;
		$suffixLen = strlen($suffix) + 1;
//		$query = "SELECT SUBSTRING(rollNo,$prefixLen) as rollNo FROM student WHERE classId = $classId AND rollNo LIKE '$prefix%' $conditions ";
		//echo $query = "SELECT SUBSTRING(LEFT(rollNo, length(rollNo) - LENGTH('$suffix')), $prefixLen)  as rollNo FROM student WHERE classId = $classId AND rollNo LIKE '$prefix%$suffix' $conditions ";
		$query = "SELECT a.studentId, rollNo, CONVERT(SUBSTRING(LEFT( a.rollNo, length(a.rollNo) - LENGTH(b.rollNoSuffix)) , LENGTH( b.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo, firstName, lastName, regNo FROM student a, class b WHERE a.classId =$classId AND a.classId = b.classId $conditions $ordering $limit";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

//-------------------------------------------------------
//  THIS FUNCTION IS USED FOR UPDATING THE ROLL NO. IN THE STUDENT
//
// Author :Ajinder Singh
// Created on : (25.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  	
	public function updateStudentGroup($rollNo, $groupVal, $groupId) {
		$query = "UPDATE student SET $groupVal = $groupId WHERE rollNo = '$rollNo'";
		return SystemDatabaseManager::getInstance()->executeUpdate($query);
	}

//-------------------------------------------------------
//  THIS FUNCTION IS USED FOR UN-ALLOCATING THE GROUP ASSIGNMENT TO ALL STUDENTS OF A PARTICULAR CLASS
//
// Author :Ajinder Singh
// Created on : (28.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  	
	public function removeGroupAssignment($classId, $groupType) {
		global $sessionHandler;
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "DELETE FROM student_groups WHERE classId = $classId AND groupId IN (select groupId from `group` where groupTypeId = $groupType)";
		return SystemDatabaseManager::getInstance()->executeDelete($query);
	}

	public function removeGroupStudents($classId, $groupList) {
		$query = "DELETE FROM student_groups WHERE classId = $classId AND groupId IN ($groupList)";
		return SystemDatabaseManager::getInstance()->executeDelete($query);
	}

	public function countGroupAttendance($classId,$groupList) {
		$query = "select count(studentId) as cnt from attendance where classId = $classId and groupId in ($groupList)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function countGroupTests($classId,$groupList) {
		$query = "select count(testId) as cnt from test where classId = $classId and groupId in ($groupList)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

//-------------------------------------------------------
//  THIS FUNCTION IS USED FOR CHECKING STUDENTS WHO HAVE NOT BEEN ALLOCATED ROLL NO.
//
// Author :Ajinder Singh
// Created on : (28.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  	
	public function countUnassignedRollNumbers($classId, $conditions = '') {
		$query = "SELECT COUNT(*) AS count FROM student WHERE classId = $classId AND (rollNo IS NULL OR rollNo = '') $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function assignGroup($str) {
		$query = "INSERT INTO student_groups(studentId, classId, groupId, instituteId, sessionId) values $str";
		return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");      
	}

//-----------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING  groups list regarding a specific student
//
//$conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (05.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------      
  public function getStudentGroups($studentId,$classId='',$orderBy=' studyPeriod',$limit='') {
      global $sessionHandler;
      $sessionId=$sessionHandler->getSessionVariable('SessionId');
      $instituteId=$sessionHandler->getSessionVariable('InstituteId');
      
      $extC='';
      if($classId!=0){
          $extC=' AND c.classId='.$classId;
      }
      
      $query="SELECT 
                    gr.groupName,gt.groupTypeName,gt.groupTypeCode,
                    SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                    SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-1) AS studyPeriod  
              FROM 
                   `group` gr,student s,group_type gt,student_groups sg,`class` c
              WHERE 
               s.studentId=$studentId 
               AND gr.classId=c.classId
               AND s.studentId=sg.studentId  
               AND gr.groupId=sg.groupId
               AND gr.groupTypeId=gt.groupTypeId
               AND sg.sessionId=$sessionId
               AND sg.instituteId=$instituteId
               $extC
               $conditions
			   UNION
			   SELECT 
                    gr.groupName,gt.groupTypeName,gt.groupTypeCode,
                    SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                    SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-1) AS studyPeriod  
				FROM 
					`group` gr,student s,group_type gt,`class` c,student_optional_subject sos
				WHERE 
				s.studentId=$studentId 
				AND sos.classId = c.classId
				AND sos.groupId = gr.groupId
				AND sos.studentId = s.studentId
				AND gr.groupTypeId=gt.groupTypeId
				AND c.sessionId = $sessionId
				AND c.instituteId = $instituteId
				$extC
               ORDER BY $orderBy
               $limit 
              ";
      
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");      
  }

  //-----------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING  groups COUNT regarding a specific student
//
//$conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (05.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------      
  public function getStudentGroupCount($studentId,$classId='',$orderBy=' studyPeriod') {
      global $sessionHandler;
      $sessionId=$sessionHandler->getSessionVariable('SessionId');
      $instituteId=$sessionHandler->getSessionVariable('InstituteId');
      
      $extC='';
      if($classId!=0){
          $extC=' AND c.classId='.$classId;
      }
      
      $query="SELECT 
                    gr.groupName,gt.groupTypeName,gt.groupTypeCode,
                    SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                    SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-1) AS studyPeriod  
              FROM 
                   `group` gr,student s,group_type gt,student_groups sg,`class` c
              WHERE 
               s.studentId=$studentId 
               AND gr.classId=c.classId
               AND s.studentId=sg.studentId  
               AND gr.groupId=sg.groupId
               AND gr.groupTypeId=gt.groupTypeId
               AND sg.sessionId=$sessionId
               AND sg.instituteId=$instituteId
               $extC
               $conditions
			   UNION
			   SELECT 
                    gr.groupName,gt.groupTypeName,gt.groupTypeCode,
                    SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                    SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-1) AS studyPeriod  
				FROM 
					`group` gr,student s,group_type gt,`class` c,student_optional_subject sos
				WHERE 
				s.studentId=$studentId 
				AND sos.classId = c.classId
				AND sos.groupId = gr.groupId
				AND sos.studentId = s.studentId
				AND gr.groupTypeId=gt.groupTypeId
				AND c.sessionId = $sessionId
				AND c.instituteId = $instituteId
				$extC
               ORDER BY $orderBy
              ";
      
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");      
  }

 //--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of COURSE RESOURCE for a Cource used in student tabs
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (05.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------  
    public function getStudentCourseResourceList($studentId,$classId='',$conditions='', $orderBy=' subject',$limit=''){
     global $REQUEST_DATA;
     global $sessionHandler;
     //$studentId=( trim($REQUEST_DATA['id'])=="" ? 0 : trim($REQUEST_DATA['id']) );
     
     if($classId!='' and $classId!=0){
      $classCondition=" AND  stc.classId=".add_slashes($classId); 
     }
     
     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');
        
     $query="SELECT courseResourceId,resourceName,description,subjectCode AS subject,
             IF(resourceUrl IS NULL,-1,resourceUrl) AS resourceUrl,                                              
             IF(attachmentFile IS NULL,-1,attachmentFile) AS attachmentFile,
             employeeName,
             DATE_FORMAT(postedDate,'%d-%b-%Y') AS postedDate
             
             FROM 
               course_resources,resource_category,subject,employee  
             WHERE 
                  course_resources.resourceTypeId=resource_category.resourceTypeId
                  AND
                  course_resources.subjectId=subject.subjectId
                  AND
                  course_resources.employeeId=employee.employeeId
                  AND
                  course_resources.instituteId=$instituteId 
                  AND 
                  course_resources.sessionId=$sessionId
                  AND 
                  resource_category.instituteId=$instituteId
                  AND
                  course_resources.subjectId 
                   IN 
                    (
                      SELECT DISTINCT stc.subjectId

                      FROM subject_to_class stc, student_groups sg

                      WHERE 
					  sg.classId = stc.classId AND
					  sg.instituteId=$instituteId AND 
					  sg.sessionId=$sessionId
                      $classCondition
                    )
                  $conditions   
                  ORDER BY $orderBy 
                  $limit " ;   
     //echo $query;       
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }
    

//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting total no of COURSE RESOURCE for a Cource used in student tabs
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (11.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
    public function getTotalStudentCourseResource($studentId,$classId='',$conditions=''){
     global $REQUEST_DATA;
     global $sessionHandler;
     //$studentId=( trim($REQUEST_DATA['id'])=="" ? 0 : trim($REQUEST_DATA['id']) );
     
     if($classId!='' and $classId!=0){
      $classCondition=" AND  stc.classId=".add_slashes($classId); 
     }
     
     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');
        
     $query="SELECT COUNT(*) AS totalRecords
             
             FROM 
               course_resources,resource_category,subject,employee  
             WHERE 
                  course_resources.resourceTypeId=resource_category.resourceTypeId
                  AND
                  course_resources.subjectId=subject.subjectId
                  AND
                  course_resources.employeeId=employee.employeeId
                  AND
                  course_resources.instituteId=$instituteId 
                  AND 
                  course_resources.sessionId=$sessionId
                  AND 
                  resource_category.instituteId=$instituteId
                  AND
                  course_resources.subjectId 
                   IN 
                    (
                      SELECT DISTINCT stc.subjectId

                      FROM subject_to_class stc, student_groups sg

                      WHERE 
					  sg.classId = stc.classId AND
					  sg.instituteId=$instituteId AND 
					  sg.sessionId=$sessionId
                      $classCondition
                    )
                  $conditions   
                  " ;   
     //echo $query;       
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }

	public function getTimeTableClasses($timeTableLabelId) {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

		$query = "
				SELECT 
								b.classId, b.className 
				FROM 
								class b, time_table_classes a 
				WHERE			a.classId = b.classId 
				AND				a.timeTableLabelId = $timeTableLabelId 
				AND				b.isActive in (1,2)
				AND				b.instituteId = $instituteId
				AND				b.sessionId = $sessionId
				";

	  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    

	}

	//-------------------------------------------------------
	//  THIS FUNCTION IS USED FOR MAKING CLASS AS PAST
	//
	// Author :Ajinder Singh
	// Created on : (30.10.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------  	
	public function makeClassPast($degreeId) {
		$query ="UPDATE class SET isActive = 3 WHERE classId = $degreeId";
		SystemDatabaseManager::getInstance()->executeUpdate($query);
	}
	//-------------------------------------------------------
	//  THIS FUNCTION IS USED FOR MAKING CLASS AS PAST
	//
	// Author :Ajinder Singh
	// Created on : (30.10.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------  	
	public function makeClassPastInTransaction($degreeId) {
		$query ="UPDATE class SET isActive = 3 WHERE classId = $degreeId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


	public function getPendingClassStudents($degreeId) {
		$query = "SELECT rollNo, concat(firstName,' ',lastName) as studentName FROM sc_student WHERE classId = $classId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}


	public function getActiveClasses() {
		global $sessionHandler;
		$instituteId	=	$sessionHandler->getSessionVariable('InstituteId');
		$sessionId		=	$sessionHandler->getSessionVariable('SessionId');
		$query = "select classId, className, marksTransferred from class where instituteId = $instituteId AND sessionId = $sessionId and isActive = 1 ORDER BY className";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getActiveMarksTransferredClasses() {
		global $sessionHandler;
		$instituteId	=	$sessionHandler->getSessionVariable('InstituteId');
		$query = "select classId, className, marksTransferred from class where instituteId = $instituteId AND isActive = 1 AND marksTransferred = 1";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function promoteClassStudentsInTransaction($nextClassId, $classId) {
		$query = "UPDATE student SET classId = '$nextClassId' WHERE classId = '$classId'";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


	public function getPendingStudents($classId, $groupType) {
		$query = "
				SELECT 
							count(a.studentId) as cnt
				from		student a 
				where		a.studentId 
				NOT IN		(
								SELECT 
											b.studentId 
								FROM		student_groups b, 
											`group` c 
								WHERE		b.classId = a.classId 
								AND			b.groupId = c.groupId 
								AND			c.groupTypeId = $groupType
							) 
							AND a.classId = $classId";
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting total no of Final result used in student tabs
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (21.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
    public function getTotalStudentFinalResult($studentId,$classId=''){

		global $REQUEST_DATA;
		global $sessionHandler;
     
		if($classId!='' and $classId!=0){
			
			$classCondition=" AND a.classId =".add_slashes($classId); 
		}
     
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
        
		$query="SELECT
				*

				From 
				`".TOTAL_TRANSFERRED_MARKS_TABLE."` a, `".TOTAL_TRANSFERRED_MARKS_TABLE."` b, `subject` sub,`class` cls,`student` scs

				WHERE 
				a.conductingAuthority =1 AND 
				b.conductingAuthority =2 AND
				sub.subjectId = a.subjectId AND 
				sub.subjectId = b.subjectId AND 
				a.classId = cls.classId AND
				 
				scs.studentId = a.studentId AND
				scs.studentId = b.studentId AND
				cls.sessionId = $sessionId AND
				cls.instituteId = $instituteId AND
				scs.studentId = $studentId AND
				a.holdResult = 0
				$classCondition

				GROUP BY a.subjectId
				ORDER BY a.subjectId";   
     
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }

//-------------------------------------------------------------------------------
//
//getTransferredMarks() function returns total no. of records from user
// $condition :  used to check the condition of the table  
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 


	public function checkStudentTransferredMarks($studentId,$classId) {
		global $REQUEST_DATA;
		global $sessionHandler;
               
	$query = "	SELECT 
							COUNT(*) AS totalRecords
				FROM		".TOTAL_TRANSFERRED_MARKS_TABLE." ttm
				WHERE		ttm.conductingAuthority = 2
				AND			ttm.studentId = $studentId
				AND			ttm.classId = $classId";
                
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
		
//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Final result List used in student tabs
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (21.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
   /*
	public function getStudentFinalResultList($studentId,$classId='',$orderBy='',$limit){

		global $REQUEST_DATA;
		global $sessionHandler;
     
		if($classId!='' and $classId!=0){
			
			$classCondition=" AND a.classId =".add_slashes($classId); 
		}
     
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
        
		$query="SELECT 
				CONCAT(sub.subjectName,'- (',sub.subjectCode,')') as subjectCode,
				IF(a.marksScored=-1,'A',IF(a.marksScored=-2,'UMC',CONCAT(a.marksScored,'/',a.maxMarks))) as 'preComprehensive',
				IF(b.marksScored=-2,'UMC',IF(b.marksScored=-1,'A',CONCAT(b.marksScored,'/',b.maxMarks))) as 'Comprehensive',
				IF(c.marksScored=-1,'A',IF(c.marksScored=-2,'UMC',CONCAT(c.marksScored,'/',c.maxMarks))) as 'attendance',
				SUBSTRING_INDEX(cls.className,'-',-1) AS periodName

				From 
				`".TOTAL_TRANSFERRED_MARKS_TABLE."` a, `".TOTAL_TRANSFERRED_MARKS_TABLE."` b, `".TOTAL_TRANSFERRED_MARKS_TABLE."` c, `subject` sub,`class` cls,`student` scs

				WHERE 
				a.conductingAuthority =1 AND 
				b.conductingAuthority =2 AND
				c.conductingAuthority =3 AND
				sub.subjectId = a.subjectId AND 
				sub.subjectId = b.subjectId AND
				sub.subjectId = c.subjectId AND
				a.classId = cls.classId AND
				 
				scs.studentId = a.studentId AND
				scs.studentId = b.studentId AND
				scs.studentId = c.studentId AND
				cls.sessionId = $sessionId AND
				cls.instituteId = $instituteId AND
				scs.studentId = $studentId AND
				a.holdResult = 0
				$classCondition

				GROUP BY a.subjectId, b.subjectId, c.subjectId

				ORDER BY $orderBy
				
				$limit";   
     
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }
	*/

public function getStudentFinalResultList($studentId,$classId='',$orderBy='',$limit){

		global $REQUEST_DATA;
		global $sessionHandler;
     
		if($classId!='' and $classId!=0){
			
			$classCondition=" AND a.classId =".add_slashes($classId); 
		}
     
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
        
		$query="SELECT 
				CONCAT(sub.subjectName,'- (',sub.subjectCode,')') as subjectCode,
				IF(a.marksScoredStatus='Marks',concat(a.marksScored,'/',a.maxMarks),a.marksScoredStatus) as 'preComprehensive',
				IF(b.marksScoredStatus='Marks',concat(b.marksScored,'/',b.maxMarks),b.marksScoredStatus) as 'Comprehensive',
				IF(c.marksScoredStatus='Marks',concat(c.marksScored,'/',c.maxMarks),c.marksScoredStatus) as 'attendance',
				SUBSTRING_INDEX(cls.className,'-',-1) AS periodName

				From 
				`".TOTAL_TRANSFERRED_MARKS_TABLE."` a, `".TOTAL_TRANSFERRED_MARKS_TABLE."` b, `".TOTAL_TRANSFERRED_MARKS_TABLE."` c, `subject` sub,`class` cls,`student` scs

				WHERE 
				a.conductingAuthority =1 AND 
				b.conductingAuthority =2 AND
				c.conductingAuthority =3 AND
				sub.subjectId = a.subjectId AND 
				sub.subjectId = b.subjectId AND
				sub.subjectId = c.subjectId AND
				a.classId = cls.classId AND
				 
				scs.studentId = a.studentId AND
				scs.studentId = b.studentId AND
				scs.studentId = c.studentId AND
				cls.sessionId = $sessionId AND
				cls.instituteId = $instituteId AND
				scs.studentId = $studentId AND
				a.holdResult = 0
				$classCondition

				GROUP BY a.subjectId, b.subjectId, c.subjectId

				ORDER BY $orderBy
				
				$limit";   
     
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }

	//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Final result List used in student tabs
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (21.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
   /*
	public function getStudentConductingFinalResultList($studentId,$classId='',$orderBy='',$limit){

		global $REQUEST_DATA;
		global $sessionHandler;
     
		if($classId!='' and $classId!=0){
			
			$classCondition=" AND a.classId =".add_slashes($classId); 
		}
     
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
        
		$query="SELECT 
				CONCAT(sub.subjectName,'- (',sub.subjectCode,')') as subjectCode,
				IF(a.marksScored=-1,'A',IF(a.marksScored=-2,'UMC',CONCAT(a.marksScored,'/',a.maxMarks))) as 'preComprehensive',
				IF(c.marksScored=-1,'A',IF(c.marksScored=-2,'UMC',CONCAT(c.marksScored,'/',c.maxMarks))) as 'attendance',
				'".NOT_APPLICABLE_STRING."' AS 'Comprehensive',
				SUBSTRING_INDEX(cls.className,'-',-1) AS periodName

				From 
				`".TOTAL_TRANSFERRED_MARKS_TABLE."` a, `".TOTAL_TRANSFERRED_MARKS_TABLE."` b, `".TOTAL_TRANSFERRED_MARKS_TABLE."` c, `subject` sub,`class` cls,`student` scs

				WHERE 
				a.conductingAuthority =1 AND 
				c.conductingAuthority =3 AND
				sub.subjectId = a.subjectId AND 
				sub.subjectId = c.subjectId AND
				a.classId = cls.classId AND
				 
				scs.studentId = a.studentId AND
				scs.studentId = c.studentId AND
				cls.sessionId = $sessionId AND
				cls.instituteId = $instituteId AND
				scs.studentId = $studentId AND
				a.holdResult = 0
				$classCondition

				GROUP BY a.subjectId,c.subjectId

				ORDER BY $orderBy
				
				$limit";   
     
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }*/

	public function getStudentConductingFinalResultList($studentId,$classId='',$orderBy='',$limit){

		global $REQUEST_DATA;
		global $sessionHandler;
     
		if($classId!='' and $classId!=0){
			
			$classCondition=" AND a.classId =".add_slashes($classId); 
		}
     
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
        
		$query="SELECT 
				CONCAT(sub.subjectName,'- (',sub.subjectCode,')') as subjectCode,
				IF(a.marksScoredStatus='Marks',concat(a.marksScored,'/',a.maxMarks),a.marksScoredStatus) as 'preComprehensive',
				IF(c.marksScoredStatus='Marks',concat(c.marksScored,'/',c.maxMarks),c.marksScoredStatus) as 'attendance',
				'".NOT_APPLICABLE_STRING."' AS 'Comprehensive',
				SUBSTRING_INDEX(cls.className,'-',-1) AS periodName

				From 
				`".TOTAL_TRANSFERRED_MARKS_TABLE."` a, `".TOTAL_TRANSFERRED_MARKS_TABLE."` b, `".TOTAL_TRANSFERRED_MARKS_TABLE."` c, `subject` sub,`class` cls,`student` scs

				WHERE 
				a.conductingAuthority =1 AND 
				c.conductingAuthority =3 AND
				sub.subjectId = a.subjectId AND 
				sub.subjectId = c.subjectId AND
				a.classId = cls.classId AND
				 
				scs.studentId = a.studentId AND
				scs.studentId = c.studentId AND
				cls.sessionId = $sessionId AND
				cls.instituteId = $instituteId AND
				scs.studentId = $studentId AND
				a.holdResult = 0
				$classCondition

				GROUP BY a.subjectId,c.subjectId

				ORDER BY $orderBy
				
				$limit";   
     
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }

	public function checkGrades($degreeId) {
		$query = "SELECT 
							COUNT(*) AS cnt 
				FROM		student_grades 
				WHERE		classId = $degreeId 
				AND			(gradeId = 0 or gradeId IS NULL)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countDegreeStudents($classId, $conditions='') {
		$query = "SELECT 
							COUNT(studentId) AS cnt 
				  FROM		student 
				  WHERE		classId = $classId
				  $conditions ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countGroupAssignedStudents($classId,$groupType) {
		$query = "
				SELECT 
							COUNT(a.studentId) AS cnt 
				FROM		student_groups a, `group` b 
				WHERE		a.classId = $classId 
				and			a.groupId = b.groupId 
				and			b.groupTypeId = $groupType";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getClassGroups($classId, $groupIdList, $groupTypeId) {
		$query = "
				select		
							DISTINCT a.parentGroupId as groupId, 
							b.groupName, 
							b.groupShort 
				FROM		`group` a, `group` b 
				WHERE		a.groupId in ($groupIdList) 
				AND			a.parentGroupId != 0 
				AND			a.parentGroupId = b.groupId 
				AND			b.groupTypeId = $groupTypeId 
				AND			a.classId = $classId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentDetail($rollNo) {
		global $sessionHandler;
		$systemDatabaseManager = SystemDatabaseManager::getInstance();

		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

		$query = "	SELECT 
							distinct cvtr.classId 
					FROM	classes_visible_to_role cvtr
					WHERE	cvtr.userId = $userId
					AND		cvtr.roleId = $roleId";

		$result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
		
		$count = count($result);
		$insertValue = "";
			for($i=0;$i<$count; $i++) {
				$querySeprator = '';
			    if($insertValue!='') {
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator ('".$result[$i]['classId']."')";
			}
		if ($count > 0) {
			$query = "	SELECT 
								a.classId, 
								a.studentId, 
								b.sessionId, 
								b.instituteId 
						FROM	student a,
								class b,
								classes_visible_to_role cvtr
						WHERE	a.rollNo = '$rollNo' 
						AND		a.classId = b.classId 
						AND		b.sessionId = $sessionId 
						AND		b.instituteId = $instituteId
						AND		cvtr.classId = b.classId
						AND		cvtr.classId = a.classId
						AND		b.classId IN ($insertValue)";
			}
		else {
			$query = "SELECT a.classId, a.studentId, b.sessionId, b.instituteId 
			FROM student a, class b WHERE a.rollNo = '$rollNo' AND a.classId = b.classId and b.sessionId = $sessionId and b.instituteId = $instituteId";
		}
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getClassGroupTypeGroups($classId,$groupTypeId) {
		$query = "SELECT groupId, groupShort FROM `group` WHERE groupTypeId = $groupTypeId and classId = $classId order by groupShort";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentCurrentGroups($studentId,$classId, $conditions = '') {
		$query = "SELECT a.groupId, b.groupShort FROM student_groups a, `group` b WHERE a.studentId = $studentId and a.classId = $classId and a.groupId = b.groupId $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getThisGroupAllocation($classId, $groupId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$query = "
				select 
							count(studentId) as cnt 
				from		student_groups  
				where		instituteId = $instituteId 
				and			sessionId = $sessionId 
				and			classId = $classId 
				and			groupId = $groupId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getSiblingGroupAllocation($classId, $groupId,$groupType) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$query = "
					SELECT

								COUNT(a.studentId) AS cnt

					FROM		student_groups a
					WHERE		a.classId = $classId 
					AND			groupId IN (
												SELECT 
															c.groupId 
												FROM		`group` b, `group` c
												WHERE		b.groupTypeId = $groupType
												AND			b.groupTypeId = c.groupTypeId
												AND			b.parentGroupId = c.parentGroupId
												AND			b.groupId != c.groupId
												AND			b.groupId = $groupId
												AND			b.classId = $classId
												AND			b.classId = c.classId
										)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getGroupTypeAllocatedStudents($classId, $groupType) {
		$query = "
				select 
							a.studentId 
				from		student_groups a, `group` b 
				where		a.classId = $classId 
				and			a.groupId = b.groupId 
				and			b.groupTypeId = $groupType ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getParentId($groupId) {
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = "SELECT distinct parentGroupId from `group` where groupId IN ($groupId)";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}


    
	  //-------------------------------------------------------
//  THIS FUNCTION IS USED TO FETCH STUDENT ALL CLASSES
//  orderBy: on which column to sort
//
//  Author :Rajeev Aggarwal
//  Created on : (13.11.2008)
//  Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  	
	public function getStudentAllClass($studentId)
    {
       global $sessionHandler;
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');
	   $sessionId	= $sessionHandler->getSessionVariable('SessionId');

       $query = "SELECT 
				DISTINCT(sg.classId),cls.className 
				
				FROM 
				`student_groups` sg, class cls
				
				WHERE 
				
				sg.studentId =$studentId AND 
				sg.instituteId = $instituteId AND 
				sg.sessionId = $sessionId AND 
				sg.classId=cls.classId
				
				ORDER BY sg.classId DESC";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");      
      }

	  public function getGroupPendingStudents($classId, $groupId, $groupParentId) {
		  $query = "
			SELECT 
						studentId 
			from		student_groups  
			where		classId = $classId 
			and			groupId = $groupParentId 
			and			studentId NOT IN
										(
											SELECT 
														studentId 
											FROM 
														subject_groups 
											where		classId = $classId 
											and			groupId = $groupId
										)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");      
	  }

	  public function getParentGroups($classId,$groupType) {
		  $query = "
					  SELECT 
									groupId

					  from			`group`
					  where			parentGroupId = 0
					  and			groupTypeId = $groupType";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	  }

	  public function getGroupChildren($classId = '', $condition = '') {
		  $str = "";
		  if ($classId) {
			  $str = " and classId = $classId ";
		  }
			$query = "SELECT groupId from `group` $condition $str ";
			$groupResultArray = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
			foreach($groupResultArray as $record) {
				$thisGroupId = $record['groupId'];
				$this->getGroupChildren($classId, " where parentGroupId = $thisGroupId ");
				$groupArray[$thisGroupId] = $thisGroupId;
			}
			return $groupArray;
	  }

	  public function getGroupDetails($str) {
			$query = "SELECT groupId, groupName, groupShort FROM `group` WHERE groupId in ($str)";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	  }

	  public function countPendingGroupAllocation($classId, $groupType, $groupId) {
		   $query = "
					SELECT 
								count(c.studentId) as cnt
					FROM		student a, class b, student_groups c 
					WHERE		a.classId =$classId 
					AND			a.classId = b.classId 
					AND			a.classId = c.classId 
					AND			a.studentId = c.studentId
					AND			c.groupId = (select parentGroupId from `group` where groupId = $groupId) 
					AND			c.studentId NOT IN 
										(
												SELECT b.studentId from student_groups b, `group` c 
												WHERE b.classId = $classId AND b.groupId = c.groupId AND c.parentGroupId = (select parentGroupId FROM `group` WHERE groupId = $groupId)
												AND c.groupTypeId = $groupType
										)

				";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		  }

		public function getPendingGroupAllocation($classId, $groupType, $groupId, $limit = '') {
		  $query = "
					SELECT 
								c.studentId, 
								a.rollNo, 
								CONVERT(SUBSTRING(LEFT( a.rollNo, length(a.rollNo) - LENGTH(b.rollNoSuffix)) , LENGTH( b.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo, 
								firstName, 
								lastName, 
								regNo 
					FROM		student a, class b, student_groups c 
					WHERE		a.classId =$classId 
					AND			a.classId = b.classId 
					AND			a.rollNo LIKE '$prefix%$suffix' 
					AND			a.classId = c.classId 
					AND			a.studentId = c.studentId
					AND			c.groupId = (select parentGroupId from `group` where groupId = $groupId) 
					AND			c.studentId NOT IN 
										(
												select b.studentId from student_groups b, `group` c 
												where b.classId = $classId and b.groupId = c.groupId and c.parentGroupId = (select parentGroupId from `group` where groupId = $groupId) 
												and c.groupTypeId = $groupType
										)
									
					$limit
					";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function countGroupAllocation($groupId, $classId) {
			$query = "select count(studentId) as cnt from student_groups where groupId = $groupId and classId = $classId";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getGroupSiblings($classId, $groupId, $groupType) {
			$query = "select groupId from `group` where classId = $classId and groupTypeId = $groupType and parentGroupId = (select parentGroupId from `group` where groupId = $groupId)";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function countGroupAssignedStudentsRoot($classId,$groupType) {
			$query = "select count(a.studentId) as cnt from student_groups a, `group` b where a.classId = $classId and a.classId = b.classId and a.groupId = b.groupId and b.groupTypeId = $groupType and b.parentGroupId = 0";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getSiblingGroupStudents($classId, $siblingList) {
			$query = "SELECT studentId FROM student_groups WHERE groupId IN ($siblingList)";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function updateRecordInTransaction($table, $setCondition, $whereCondition) {
			$query = "UPDATE $table SET $setCondition WHERE $whereCondition";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function makeRollNoNullInTransaction($studentIdList) {
			$query = "UPDATE student SET rollNo = null WHERE studentId IN ($studentIdList)";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function getStudentUserId($studentId) {
			$query = "SELECT userId FROM student WHERE studentId = $studentId";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function countAttendance($classId) {
			$query = "SELECT count(studentId) AS count FROM attendance WHERE classId = $classId";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function countTests($classId) {
			$query = "SELECT count(testId) AS count FROM test WHERE classId = $classId";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getStudentOldAttendance($studentId, $classId, $subjectId, $groupId) {
			global $sessionHandler;
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');
			$query = "
					  SELECT 
									SUM(IF(a.isMemberOfClass = 0,0, IF(b.attendanceCodePercentage IS NULL, a.lectureDelivered, 1))) AS lectureDelivered,
									ROUND(SUM(IF(a.isMemberOfClass = 0,0, if(b.attendanceCodePercentage IS NULL, a.lectureAttended,
									b.attendanceCodePercentage/100))),2) as lectureAttended
					  FROM			attendance a 
					  LEFT JOIN		attendance_code b 
					  ON			(a.attendanceCodeId = b.attendanceCodeId and b.instituteId = $instituteId)
					  WHERE			a.subjectId=$subjectId 
					  AND			a.studentId = $studentId 
					  AND			a.classId=$classId 
					  AND			a.groupId = $groupId
					  GROUP BY		a.studentId,a.subjectId, a.groupId";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getGroupAttendance($classId,$subjectId,$groupId) {
			$query = "
					  SELECT MAX(lectureDelivered) AS lectureDelivered FROM (
							  SELECT 
											SUM(a.lectureDelivered) AS lectureDelivered
							  FROM			attendance a
							  WHERE			a.subjectId=$subjectId 
							  AND			a.classId=$classId 
							  AND			a.groupId = $groupId
							  GROUP BY		a.studentId) as t";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getGroupEmployee($classId,$subjectId,$groupId) {
			$query = "SELECT employeeId FROM time_table WHERE subjectId = $subjectId AND groupId = $groupId ORDER BY timeTableId DESC limit 0,1";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getGroupAttDate($classId,$subjectId,$groupId) {
			$query = "SELECT min(fromDate) as fromDate, max(toDate) as toDate FROM attendance WHERE classId = $classId AND subjectId = $subjectId AND groupId = $groupId";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getGroupAttendanceDetails($classId,$subjectId,$groupId) {
			$query = "SELECT DISTINCT employeeId, attendanceType, attendanceCodeId, periodId, fromDate, toDate, lectureDelivered, topicsTaughtId FROM attendance WHERE classId = $classId AND subjectId = $subjectId AND groupId = $groupId order by fromDate desc";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getSingleField($table, $field, $conditions='') {
			$query = "SELECT $field FROM $table $conditions";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function addAttendanceInTransaction($classId,$groupId, $studentId, $subjectId, $employeeId, $fromDate, $toDate, $attendanceType, $attendanceCodeId, $periodId, $lectureDelivered, $lectureAttended, $topicsTaughtId) {
			global $sessionHandler;
			$userId = $sessionHandler->getSessionVariable('UserId');
			$query = "
						INSERT INTO			attendance 
								SET			classId = $classId, 
											groupId = $groupId, 
											studentId = $studentId, 
											subjectId = $subjectId, 
											employeeId = $employeeId,
											fromDate = '$fromDate',
											toDate = '$toDate',
											attendanceType = $attendanceType,
											attendanceCodeId = $attendanceCodeId,
											periodId = $periodId,
											lectureDelivered = $lectureDelivered,
											lectureAttended = $lectureAttended,
											topicsTaughtId = $topicsTaughtId,
											userId = $userId
											";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function quarantineAttendanceInTransaction($classId,$groupId, $studentId, $subjectId) {
			$query = "
					INSERT INTO	
								quarantine_attendance(
														classId,groupId,studentId,subjectId,employeeId,
														attendanceType,attendanceCodeId,periodId,fromDate,toDate,
														isMemberOfClass,lectureDelivered,lectureAttended,userId,deletionType
													 ) 
								SELECT
													
														classId,groupId,studentId,subjectId,employeeId,
														attendanceType,attendanceCodeId,periodId,fromDate,toDate,
														isMemberOfClass,lectureDelivered,lectureAttended,userId,2
													 
								FROM			attendance 
								WHERE			classId = $classId 
								AND				groupId = $groupId 
								AND				studentId = $studentId 
								AND				subjectId = $subjectId";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function deleteAttendanceInTransaction($classId,$groupId, $studentId, $subjectId) {
			$query = "
						DELETE FROM 
												attendance 								
								WHERE			classId = $classId 
								AND				groupId = $groupId 
								AND				studentId = $studentId 
								AND				subjectId = $subjectId";

			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function addMarksInTransaction($newTestId, $studentId, $subjectId, $newMaxMarks, $newMarksScored, $isPresent, $isMemberOfClass) {
			$query = "
						INSERT INTO test_marks set 
													testId = $newTestId,
													studentId = $studentId, 
													subjectId = $subjectId, 
													maxMarks = $newMaxMarks, 
													marksScored = $newMarksScored, 
													isPresent = $isPresent, 
													isMemberOfClass = $isMemberOfClass
						";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function quarantineMarksInTransaction($testId,$studentId, $classId, $subjectId) {
			
			$query = "insert into quarantine_test_marks (testMarksId,testId, studentId, subjectId, maxMarks, marksScored, isPresent, isMemberOfClass, deletionType) select testMarksId,testId, studentId, subjectId, maxMarks, marksScored, isPresent, isMemberOfClass, 2 from test_marks where testId = $testId and studentId = $studentId and subjectId = $subjectId";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function deleteMarksInTransaction($oldTestId,$studentId, $classId, $subjectId, $groupId) {
			$query = "delete from test_marks where testId = $oldTestId and studentId = $studentId and subjectId = $subjectId";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function updateStudentGroupInTransaction($classId,$studentId, $oldGroupId, $newGroupId) {
			$query = "update student_groups set groupId = $newGroupId where studentId = $studentId and classId = $classId and groupId = $oldGroupId";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function isGroupRelatedToTheory($theoryGroupId,$otherGroupId) {
			$query = "select if(parentGroupId = $theoryGroupId,1,0) as relation from `group` where groupId = $otherGroupId";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getStudentOldTests($studentId, $classId, $subjectId, $groupId) {
			global $sessionHandler;
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');
			$query = "
					SELECT 
								t.testId,
								concat(tt.testTypeName,'-',t.testIndex) as testName, 
								t.maxMarks, 
								tm.marksScored 
					FROM 
								test_type_category tt, 
								test t, 
								test_marks tm 
					WHERE 
								t.testTypeCategoryId = tt.testTypeCategoryId 
					AND			t.testId = tm.testId 
					AND			t.classId = $classId 
					AND			t.subjectId = $subjectId 
					AND			t.groupId = $groupId 
					AND			tm.studentId = $studentId
					ORDER BY	testName";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getGroupTests($classId,$subjectId,$groupId) {
			global $sessionHandler;
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');
			$query = "
					SELECT 
								concat(tt.testTypeName,'-',t.testIndex) as testName, 
								t.maxMarks,
								t.testId
					FROM 
								test_type_category tt, 
								test t
					WHERE 
								t.testTypeCategoryId = tt.testTypeCategoryId
					AND			t.classId = $classId 
					AND			t.subjectId = $subjectId 
					AND			t.groupId = $groupId
					ORDER BY	testName";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getClassSubjects($classId) {
			$query = "
				SELECT 
							a.subjectId, 
							b.subjectCode,
							b.subjectName, 
							a.externalTotalMarks, 
							b.hasAttendance, 
							b.hasMarks,
							a.optional,
							a.hasParentCategory,
							a.offered
				FROM		subject_to_class a, subject b 
				WHERE		a.subjectId = b.subjectId 
				AND			a.classId = $classId
				ORDER BY	b.subjectCode";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getClassTypeSubjects($classId) {
			$query = "
				SELECT 
							a.subjectId, 
							b.subjectCode,
							b.subjectName,
							c.subjectTypeName,
							a.externalTotalMarks, 
							b.hasAttendance, 
							b.hasMarks,
							a.optional,
							a.hasParentCategory,
							a.offered
				FROM		subject_to_class a, subject b, subject_type c 
				WHERE		a.subjectId = b.subjectId 
				AND			a.classId = $classId
				AND			b.subjectTypeId = c.subjectTypeId
				ORDER BY	b.subjectCode";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}


		public function runQuery($query) {
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}


//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting total no of Offence used in student tabs
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (22.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
    public function getTotalStudentOffence($studentId,$classId=''){

		global $REQUEST_DATA;
		global $sessionHandler;
     
		if($classId!='' and $classId!=0){
			
			$classCondition=" AND sd.classId =".add_slashes($classId); 
		}
     
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
        
		$query="SELECT
				COUNT(*) as totalRecords

				FROM 
				`offense` off, `student_discipline` sd, `class` cls, `study_period` sp
				
				WHERE 
				off.offenseId = sd.offenseId AND 
				sd.classId = cls.classId AND 
				sp.studyPeriodId = cls.studyPeriodId AND
				sd.studentId = $studentId 
				$classCondition";   
     
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }
		
//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Final result List used in student tabs
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (22.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------  
    public function getStudentOffenceList($studentId,$classId='',$orderBy='',$limit){

		global $REQUEST_DATA;
		global $sessionHandler;
     
		if($classId!='' and $classId!=0){
			
			$classCondition=" AND sd.classId =".add_slashes($classId); 
		}
     
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
        
		$query="SELECT 
				sp.periodName, off.offenseName, off.OffenseAbbr, DATE_FORMAT( sd.offenseDate, '%d-%b-%Y' ) AS offenseDate, sd.remarks,sd.reportedBy
				
				FROM 
				`offense` off, `student_discipline` sd, `class` cls, `study_period` sp
				
				WHERE 
				off.offenseId = sd.offenseId AND 
				sd.classId = cls.classId AND 
				sp.studyPeriodId = cls.studyPeriodId AND
				sd.studentId = $studentId 
				$classCondition

				ORDER BY $orderBy
				
				$limit";   
     
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }
//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Academic in student tabs
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (28.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------  
    public function getStudentAcademicList($condition,$orderBy=''){

		global $REQUEST_DATA;
		global $sessionHandler;
      
		$query="SELECT sa.previousClassId, sa.previousRollNo, sa.previousSession, sa.previousInstitute, sa.previousBoard, sa.previousMarks, sa.previousMaxMarks, sa.previousPercentage,sa.previousEducationStream
				
				FROM 
				`student_academic` sa 
				
				 
				$condition

				ORDER BY $orderBy ";   
     
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }

	 public function getMarksDistributionOneSubject($degree, $subjectId) {
			global $sessionHandler;
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');
			$query = "
					SELECT 
									b.testTypeId,
									b.evaluationCriteriaId,
									b.cnt,
									ROUND(b.weightagePercentage,1) AS weightagePercentage,
									b.weightageAmount,
									b.testTypeName,
									b.testTypeCategoryId,
									c.testTypeName as testTypeCategoryName 
					FROM	
									subject a, 
									test_type b, 
									test_type_category c,
									class d

					WHERE	
									a.subjectId = $subjectId 
					AND				b.testTypeCategoryId = c.testTypeCategoryId 
					AND				a.subjectId = b.subjectId 
					AND				a.subjectTypeId = b.subjectTypeId 
					AND				b.instituteId = $instituteId 
					AND				b.conductingAuthority = 1 
					AND				c.examType = 'PC'
					AND				b.timeTableLabelId IN (select timeTableLabelId from time_table_classes where classId = $degree)
					AND				b.universityId = d.universityId
					UNION 
					SELECT 
									b.testTypeId,
									b.evaluationCriteriaId,
									b.cnt,
									ROUND(b.weightagePercentage,1) AS weightagePercentage,
									b.weightageAmount,
									b.testTypeName,
									b.testTypeCategoryId,
									d.testTypeName 
					FROM		
									subject a, 
									test_type b, 
									class c, 
									test_type_category d 
					WHERE			
									c.classId = $degree 
					AND				a.subjectId = $subjectId 
					AND				b.conductingAuthority = 1 
					AND				b.instituteId = $instituteId
					AND				a.subjectTypeId = b.subjectTypeId 
					AND				d.examType = 'PC'
					AND				b.testTypeCategoryId = d.testTypeCategoryId
					AND				b.timeTableLabelId IN (select timeTableLabelId from time_table_classes where classId = $degree)
					AND				a.subjectId NOT in (SELECT IF(subjectId IS NULL,0, subjectId) FROM test_type WHERE instituteId = $instituteId) AND CASE WHEN (b.subjectId IS NULL) AND				(b.studyPeriodId = c.studyPeriodId OR b.studyPeriodId is null) AND(b.branchId = c.branchId OR b.branchId IS NULL) AND				(b.degreeId = c.degreeId or b.degreeId is null) AND b.universityId = c.universityId THEN 2 ELSE NULL END IS NOT NULL 
					ORDER BY testTypeName
					";


		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	 public function getExternalMarksDistribution($degree, $subjectId) {
			global $sessionHandler;
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');
			$query = "
					SELECT 
									b.testTypeId,
									b.evaluationCriteriaId,
									b.cnt,
									ROUND(b.weightagePercentage,1) AS weightagePercentage,
									b.weightageAmount,
									b.testTypeName,
									b.testTypeCategoryId
					FROM	
									subject a, 
									test_type b
					WHERE	
									a.subjectId = $subjectId 
					AND				a.subjectId = b.subjectId 
					AND				a.subjectTypeId = b.subjectTypeId 
					AND				b.conductingAuthority = 2
					AND				b.instituteId = $instituteId
					AND				b.timeTableLabelId IN (select timeTableLabelId from time_table_classes where classId = $degree)
					UNION 
					SELECT 
									b.testTypeId,
									b.evaluationCriteriaId,
									b.cnt,
									ROUND(b.weightagePercentage,1) AS weightagePercentage,
									b.weightageAmount,
									b.testTypeName,
									b.testTypeCategoryId
					FROM		
									subject a, 
									test_type b, 
									class c
					WHERE			
									c.classId = $degree 
					AND				a.subjectId = $subjectId 
					AND				b.conductingAuthority = 2 
					AND				a.subjectTypeId = b.subjectTypeId
					AND				b.instituteId = $instituteId
					AND				b.timeTableLabelId IN (select timeTableLabelId from time_table_classes where classId = $degree)
					AND				a.subjectId NOT in (SELECT IF(subjectId IS NULL,0, subjectId) FROM test_type where instituteId = $instituteId) AND CASE WHEN (b.subjectId IS NULL) AND				(b.studyPeriodId = c.studyPeriodId OR b.studyPeriodId is null) AND(b.branchId = c.branchId OR b.branchId IS NULL) AND				(b.degreeId = c.degreeId or b.degreeId is null) AND b.universityId = c.universityId THEN 2 ELSE NULL END IS NOT NULL ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function countMissingStudents($classId, $subjectId, $testTypeCategoryId) {
		$query = "
					SELECT 
								COUNT(find) as cnt 
					FROM		(
									SELECT 
												b.studentId,
												FIND_IN_SET('$testTypeCategoryId', CONVERT(GROUP_CONCAT(DISTINCT a.testTypeCategoryId),CHAR)) AS find
									FROM		".TEST_TABLE." a, ".TEST_MARKS_TABLE." b, student c 
									WHERE		a.testId = b.testId 
									AND			a.classId = $classId 
									AND			a.subjectId = $subjectId 
									AND			b.studentId = c.studentId
									GROUP BY	b.studentId
								) AS abc 
					WHERE		find = 0";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function getMissingStudents($classId, $subjectId, $testTypeCategoryId) {
		$query = "
					SELECT 
								*
					FROM		(
									SELECT 
												b.studentId, c.rollNo, concat(c.firstName,' ',c.lastName) as studentName,
												FIND_IN_SET('$testTypeCategoryId', CONVERT(GROUP_CONCAT(DISTINCT a.testTypeCategoryId),CHAR)) AS find
									FROM		".TEST_TABLE." a, ".TEST_MARKS_TABLE." b, student c 
									WHERE		a.testId = b.testId 
									AND			a.classId = $classId 
									AND			a.subjectId = $subjectId 
									AND			b.studentId = c.studentId
									GROUP BY	b.studentId
								) AS abc 
					WHERE		find = 0";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function countOptionalSubjectStudents($classId, $subjectId) {
		$query = "SELECT count(studentId) as cnt from student_optional_subject where classId = $classId and subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function countOptionalChildSubjectStudents($classId, $subjectId) {
		$query = "SELECT count(studentId) as cnt from student_optional_subject where classId = $classId and parentOfSubjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function getMMSubjects($classId, $subjectId) {
		$query = "SELECT distinct(a.subjectId) as subjectId, b.subjectCode from student_optional_subject a, subject b 
		where  a.classId = $classId and a.parentOfSubjectId = $subjectId and a.subjectId = b.subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function getSubjectCode($subjectId) {
		$query = "SELECT subjectId, subjectCode, subjectName, subjectTypeId from subject where subjectId IN ($subjectId)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}




	//-------------------------------------------------------
	//  THIS FUNCTION IS USED FOR FETCHING MARKS FOR A CLASS, A SUBJECT AND A TEST TYPE
	//
	// Author :Ajinder Singh
	// Created on : (16.10.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------  	
	public function getSubjectTestTypeTestMarks($classId, $subjectId, $testTypeId, $conditions = '') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
				SELECT 
							a.studentId, 
							c.maxMarks, 
							c.marksScored, 
							ROUND((c.marksScored/c.maxMarks)*100) AS per 
				FROM		student a, ".TEST_TABLE." b, ".TEST_MARKS_TABLE." c 
				WHERE		b.classId = $classId 
				AND			a.studentId = c.studentId 
				AND			b.testId = c.testId 
				AND			b.subjectId = $subjectId 
				AND			b.testTypeCategoryId IN  (SELECT testTypeCategoryId FROM test_type WHERE testTypeId = $testTypeId AND instituteId = $instituteId) 
							$conditions 
				ORDER BY	a.studentId,per DESC";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function countMissingOptionalSubjectStudents($classId, $subjectId, $testTypeCategoryId) {
		$query = "
					SELECT 
								COUNT(find) as cnt 
					FROM		(
									SELECT 
												b.studentId,
												FIND_IN_SET('$testTypeCategoryId', CONVERT(GROUP_CONCAT(DISTINCT a.testTypeCategoryId),CHAR)) AS find
									FROM		".TEST_TABLE." a, ".TEST_MARKS_TABLE." b, student c, student_optional_subject d 
									WHERE		a.testId = b.testId 
									AND			a.classId = $classId 
									AND			a.classId = c.classId
									AND			c.classId = d.classId
									AND			a.subjectId = $subjectId 
									AND			a.subjectId = d.subjectId
									AND			b.studentId = c.studentId
									AND			c.studentId = d.studentId
									GROUP BY	b.studentId
								) AS abc 
					WHERE		find = 0";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function getOptionalSubjectTestTypeTestMarks($classId, $subjectId, $testTypeId, $conditions = '') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
				SELECT 
							a.studentId, 
							c.maxMarks, 
							c.marksScored, 
							ROUND((c.marksScored/c.maxMarks)*100) AS per 
				FROM		student a, ".TEST_TABLE." b, ".TEST_MARKS_TABLE." c, student_optional_subject d 
				WHERE		a.classId = $classId
				AND			a.classId = b.classId
				AND			a.classId = d.classId
				AND			a.studentId = c.studentId 
				AND			a.studentId = d.studentId
				AND			b.testId = c.testId 
				AND			b.subjectId = $subjectId 
				AND			b.subjectId = d.subjectId
				AND			b.testTypeCategoryId IN  (SELECT testTypeCategoryId FROM test_type WHERE testTypeId = $testTypeId AND instituteId = $instituteId) 
							$conditions 
				ORDER BY	a.studentId,per DESC";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function countClassTests($classId, $subjectId, $testTypeCategoryId) {
		$query = "select count(testId) as cnt from ".TEST_TABLE." where classId = $classId and subjectId = $subjectId and testTypeCategoryId = $testTypeCategoryId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}


	public function getAttendanceTestType($degree, $subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
			$query = "SELECT
							b.testTypeId,
							b.testTypeName,
							b.evaluationCriteriaId,
							b.cnt,
							ROUND(b.weightagePercentage,1) AS weightagePercentage,
							b.weightageAmount
					FROM	subject a, test_type b
					WHERE	a.subjectId = $subjectId AND
							a.subjectId = b.subjectId AND
							a.subjectTypeId = b.subjectTypeId AND
							b.conductingAuthority = 3 AND
							b.instituteId = $instituteId AND
							b.evaluationCriteriaId IN (5,6)

					UNION
					SELECT
							b.testTypeId,
							b.testTypeName,
							b.evaluationCriteriaId,
							b.cnt,
							ROUND(b.weightagePercentage,1) AS weightagePercentage,
							b.weightageAmount
					FROM	subject a, test_type b, class c
					WHERE	c.classId = $degree AND
							a.subjectId = $subjectId AND
							b.conductingAuthority = 3 AND
							b.evaluationCriteriaId IN (5,6) AND
							a.subjectTypeId = b.subjectTypeId AND
							b.instituteId = $instituteId AND
							a.subjectId NOT in (SELECT IF(subjectId IS NULL,0, subjectId) FROM test_type where instituteId = $instituteId) AND
							CASE
								WHEN (b.subjectId IS NULL) AND
								(b.studyPeriodId = c.studyPeriodId OR b.studyPeriodId is null) AND
								(b.branchId = c.branchId OR b.branchId IS NULL) AND
								(b.degreeId = c.degreeId or b.degreeId is null) AND b.universityId = c.universityId THEN 2
							ELSE
								NULL
							END
							IS NOT NULL
							";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function getStudentAttendancePercentageMarks($labelId, $classId, $subjectId,$subjectTypeId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
						SELECT grp.studentId, att.marksScored FROM (SELECT a.studentId, FORMAT(SUM(IF(a.isMemberOfClass=0,0, IF(a.attendanceType =2, (b.attendanceCodePercentage /100), a.lectureAttended ))), 1 ) AS lectureAttended, (SELECT SUM(leavesTaken) from attendance_leave where studentId = a.studentId and classId = a.classId and subjectId = a.subjectId) as dutyLeaves,SUM(IF(a.isMemberOfClass=0,0, a.lectureDelivered)) AS lectureDelivered FROM ".ATTENDANCE_TABLE." a LEFT JOIN attendance_code b ON (a.attendanceCodeId = b.attendanceCodeId and b.instituteId = $instituteId) WHERE a.classId = $classId AND a.subjectId = $subjectId group by a.studentId) AS grp, attendance_marks_percent AS att, student stu WHERE grp.studentId = stu.studentId AND att.subjectTypeId = $subjectTypeId AND att.instituteId = $instituteId AND att.timeTableLabelId = $labelId AND att.degreeId =  (SELECT degreeId from class where classId = $classId) AND ceil(((grp.lectureAttended + IFNULL(grp.dutyLeaves,0))*100)/grp.lectureDelivered) between att.percentFrom and att.percentTo order by grp.studentId";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function getStudentAttendanceSlabsMarks($labelId, $classId, $subjectId, $subjectTypeId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
						SELECT grp.studentId, grp.lectureDelivered,grp.lectureAttended,ams.marksScored FROM (SELECT a.studentId, FORMAT(SUM(IF(a.isMemberOfClass=0,0, IF(a.attendanceType =2, (b.attendanceCodePercentage /100), a.lectureAttended )) ) , 1 ) AS lectureAttended, (SELECT SUM(leavesTaken) from attendance_leave where studentId = a.studentId and classId = a.classId and subjectId = a.subjectId) as dutyLeaves, SUM(IF(a.isMemberOfClass=0,0, a.lectureDelivered)) AS lectureDelivered FROM ".ATTENDANCE_TABLE." a LEFT JOIN attendance_code b ON (a.attendanceCodeId = b.attendanceCodeId and b.instituteId = $instituteId) WHERE a.classId = $classId AND a.subjectId = $subjectId group by a.studentId) as grp, attendance_marks_slabs ams WHERE grp.lectureDelivered = ams.lectureDelivered and (grp.lectureAttended + ifnull(dutyLeaves,0)) = ams.lectureAttended AND ceil(((grp.lectureAttended + IFNULL(grp.dutyLeaves,0))*100)/grp.lectureDelivered) and ams.instituteId = $instituteId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}


	public function getInternalTotalMarks($classId, $subjectId) {
		$query = "
					SELECT		
								internalTotalMarks
					FROM		subject_to_class 
					WHERE		classId = $classId 
					AND			subjectId = $subjectId
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function countRecords($classId, $subjectId, $studentId, $testTypeId) {
		$query = "
					SELECT 
								COUNT(*) AS cnt 
					FROM		".TEST_TRANSFERRED_MARKS_TABLE." 
					WHERE		classId = $classId 
					AND			subjectId = $subjectId 
					AND			studentId = $studentId 
					AND			testTypeId = $testTypeId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function getStudentMarksSum($classId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
				select 
							a.studentId, 
							a.classId, 
							a.subjectId, 
							b.conductingAuthority, 
							sum(a.maxMarks) as maxMarks, round(sum(a.marksScored),3) as marksScored
				from		".TEST_TRANSFERRED_MARKS_TABLE." a, test_type b, class c 
				where		a.testTypeId = b.testTypeId 
				and			a.classId = c.classId 
				and			a.classId = $classId
				and			b.instituteId = $instituteId
				group by	a.studentId, a.classId, a.subjectId, b.conductingAuthority";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function countGradingRecord($classId, $subjectId, $studentId, $conductingAuthority) {
		$query = "
					SELECT 
								COUNT(*) AS cnt 
					FROM		".TOTAL_TRANSFERRED_MARKS_TABLE." 
					WHERE		classId = $classId 
					AND			subjectId = $subjectId 
					AND			studentId = $studentId
					AND			conductingAuthority = $conductingAuthority";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function addTotalMarksInTransaction($queryPart2) {
		$query = "
					INSERT INTO ".TEST_TRANSFERRED_MARKS_TABLE."
								(studentId, testTypeId, classId, subjectId, maxMarks, marksScored) VALUES $queryPart2
				";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function addGradingRecordInTransaction($queryPart2) {
		$query = "
					INSERT INTO ".TOTAL_TRANSFERRED_MARKS_TABLE."
								(studentId, classId, subjectId, maxMarks, marksScored, holdResult, conductingAuthority, marksScoredStatus) VALUES $queryPart2
				";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO delete data from total transferred marks for a class
//orderBy: on which column to sort
//
// Author :Ajinder Singh
// Created on : (21.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------   	
	public function deleteTotalTransferredMarks($classId, $conditions = "") {
		$query = "delete from ".TOTAL_TRANSFERRED_MARKS_TABLE." where classId = $classId $conditions";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO delete data from test transferred marks for a class
//orderBy: on which column to sort
//
// Author :Ajinder Singh
// Created on : (21.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------   	
	public function deleteTestTransferredMarks($classId, $conditions = "") {
		$query = "delete from ".TEST_TRANSFERRED_MARKS_TABLE." where classId = $classId $conditions";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function getClassStudents($classId) {
		$query = "SELECT DISTINCT b.studentId, b.universityRollNo, CONCAT(b.firstName,' ',b.lastName) AS studentName FROM student_groups a, student b WHERE a.studentId = b.studentId AND b.classId = $classId ORDER BY b.universityRollNo";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function checkSubjectExists($subjectCode,$classId) {
		$query = "SELECT count(subjectId) as cnt from subject_to_class where classId = $classId and subjectId IN (select subjectId from subject where subjectCode = '$subjectCode')";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function getSubjectFinalMarks($subjectCode,$classId) {
		$query = "SELECT externalTotalMarks from subject_to_class where classId = $classId and subjectId IN (select subjectId from subject where subjectCode = '$subjectCode')";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function checkStudentId($univRollNo,$classId) {
		$query = "SELECT studentId from student where universityRollNo = '$univRollNo' AND studentId IN (select studentId from student_groups where classId = $classId)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function getSubjectId($subjectCode,$classId) {
		$query = "SELECT subjectId from subject where subjectCode = '$subjectCode' and subjectId in (select subjectId from subject_to_class where classId = $classId)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	//-------------------------------------------------------
	//  THIS FUNCTION IS USED FOR PROMOTING STUDENTS
	//
	// Author :Ajinder Singh
	// Created on : (30.10.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------  	
	public function promoteStudents($nextClassId, $classStudentList = 0) {
		$query ="UPDATE student SET classId = $nextClassId WHERE studentId IN ($classStudentList)";
		SystemDatabaseManager::getInstance()->executeUpdate($query);
	}

	//-------------------------------------------------------
	//  THIS FUNCTION IS USED FOR PROMOTING STUDENTS
	//
	// Author :Ajinder Singh
	// Created on : (30.10.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------  	
	public function promoteStudentsInTransaction($nextClassId, $classStudentList = 0) {
		$query ="UPDATE student SET classId = $nextClassId WHERE studentId IN ($classStudentList)";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


	//-------------------------------------------------------
	//  THIS FUNCTION IS USED FOR MAKING CLASS AS ACTIVE
	//
	// Author :Ajinder Singh
	// Created on : (30.10.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------  	
	public function makeClassActive($nextClassId) {
		$query ="UPDATE class SET isActive = 1, marksTransferred = 0 WHERE classId = $nextClassId";
		SystemDatabaseManager::getInstance()->executeUpdate($query);
	}
	//-------------------------------------------------------
	//  THIS FUNCTION IS USED FOR MAKING CLASS AS ACTIVE
	//
	// Author :Ajinder Singh
	// Created on : (30.10.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------  	
	public function makeClassActiveInTransaction($nextClassId) {
		$query ="UPDATE class SET isActive = 1, marksTransferred = 0 WHERE classId = $nextClassId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function checkExternalTestExist($subjectId,$classId) {
		$query = "select count(testId) as cnt from ".TEST_TABLE." where subjectId = $subjectId and classId = $classId and testTypeCategoryId IN (select testTypeCategoryId from test_type_category where examType = 'C')";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	public function getSubjectExternalTestTypeCategory($subjectId) {
		$query = "SELECT testTypeCategoryId, testTypeName FROM test_type_category WHERE examType = 'C' AND subjectTypeId = (SELECT subjectTypeId FROM subject WHERE subjectId = $subjectId)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function addNewTest($subjectId,$classId,$testTopic,$testTypeCategoryId, $maxMarks, $testDate) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

		$query = "INSERT INTO ".TEST_TABLE." SET subjectId = $subjectId, classId = $classId, testTopic = '$testTopic', testAbbr = '$testTopic', testIndex = 1, testTypeCategoryId = $testTypeCategoryId, maxMarks = $maxMarks, testDate = '$testDate', instituteId = $instituteId, sessionId = $sessionId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function getLastTest($subjectId,$classId) {
		$query = "SELECT maxMarks,testId as lastTestId FROM ".TEST_TABLE." WHERE subjectId = $subjectId AND classId = $classId AND testTypeCategoryId IN (SELECT testTypeCategoryId FROM test_type_category WHERE examType = 'C')";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function addTestMarks($queryPart) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

		$query = "INSERT INTO ".TEST_MARKS_TABLE." (testId,studentId,subjectId,maxMarks,marksScored,isPresent,isMemberOfClass) VALUES $queryPart";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function checkStudentTest($testId,$studentId) {
		$query = "SELECT COUNT(*) AS cnt FROM ".TEST_MARKS_TABLE." WHERE testId = $testId  AND studentId = $studentId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	//-------------------------------------------------------
	//  THIS FUNCTION IS USED FOR FETCHING NEXT CLASS ID
	//
	// Author :Ajinder Singh
	// Created on : (29.10.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------  	
	public function getNextClassId($degreeId) {

		$query = "
					SELECT
							  b.classId,
							  b.isActive
					FROM      class a, class b, study_period c, study_period d
					WHERE     CONCAT(a.instituteId,'#',a.universityId, '#', a.batchId, '#', a.degreeId, '#', a.branchId) =
							  CONCAT(b.instituteId,'#',b.universityId, '#', b.batchId, '#', b.degreeId, '#', b.branchId)
					AND       a.studyPeriodId = c.studyPeriodId
					AND       b.studyPeriodId = d.studyPeriodId
					AND       c.periodicityId = d.periodicityId
					AND       d.periodValue = c.periodValue + 1
					AND       a.classId = $degreeId";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO check if a roll no. exists for deleted student?
//
// Author :Ajinder Singh
// Created on : (04-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------      

	public function checkDeletedRollNo($actualRollNo) {
		$query = "SELECT COUNT(*) as cnt from quarantine_student where rollNo = '$actualRollNo'";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for class
    // Author :Rajeev Aggarwal
    // Created on : 25-05-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------    
	public function getInstituteClass($condition,$orderBy=' cls.className') {

		global $sessionHandler;
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');
		$systemDatabaseManager = SystemDatabaseManager::getInstance();

		$query = "	SELECT 
							distinct cvtr.classId 
					FROM	classes_visible_to_role cvtr
					WHERE	cvtr.userId = $userId
					AND		cvtr.roleId = $roleId";

		$result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
		
		$count = count($result);
		$insertValue = "";
			for($i=0;$i<$count; $i++) {
				$querySeprator = '';
				if($insertValue!='') {
					$querySeprator = ",";
				}
				$insertValue .= "$querySeprator ('".$result[$i]['classId']."')";
			}
		
		if ($count > 0 ) {
			$query = "
						SELECT 
										DISTINCT(ttc.classId),
										className 
						FROM			time_table_classes ttc,
										class c,
										classes_visible_to_role cvtr
						WHERE			ttc.classId = c.classId
						 
						AND				cvtr.classId = c.classId
						AND				cvtr.classId = ttc.classId
						AND				c.classId IN ($insertValue)";
		}
		else {

			$query = "SELECT cls.classId,cls.className FROM class cls 
					  
					  WHERE 
						 cls.sessionId='".$sessionId."' AND 
						 cls.isActive =1    
						 $condition
					  
					  ORDER BY $orderBy DESC";
		}

		/*global $sessionHandler;
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
		$query = "SELECT cls.classId,cls.className 
				 FROM 
				 class cls,study_period sp

				 WHERE 
				  
				 cls.sessionId='".$sessionId."' AND 
				 cls.isActive =1  AND 
				 cls.studyPeriodId = sp.studyPeriodId AND
					 sp.periodValue IN(2,4,6,8)
				$condition
				 
				 ORDER BY $orderBy DESC";*/
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}

	
	//-------------------------------------------------------
	//  THIS FUNCTION IS USED TO fetch student roll no, username
	//
	// Author :Jaineesh
	// Created on : (29-May-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------      
	public function getStudentUserDetail( $orderBy) {
	$query = "SELECT 
							scs.studentId,
							scs.rollNo,
							CONCAT(scs.firstName,'',scs.lastName) AS studentName,
							scs.firstName,
							scs.dateOfBirth
					FROM	student scs
					
					WHERE		(scs.rollNo != '' OR scs.rollNo IS NOT NULL)";
		
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//-------------------------------------------------------
	//  THIS FUNCTION IS USED TO fetch student roll no, username
	//
	// Author :Jaineesh
	// Created on : (29-May-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------      
	public function getStudentRecord($value) {
		
		 $query = "SELECT 
							scs.studentId,
							scs.firstName,
							scs.dateOfBirth,
							scs.rollNo, 
                            u.userName, 
                            u.userId
					FROM
                            user u LEFT JOIN student scs ON u.userId = scs.userId 	
					WHERE
                        	scs.studentId IN ($value)";
		
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

  
	//-------------------------------------------------------
	//  THIS FUNCTION IS USED TO fetch student roll no, username
	//
	// Author :Jaineesh
	// Created on : (29-May-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------      
	public function insertUserData($rollNo,$pass) {
		$roleId = '4';
		global $sessionHandler;
		$instituteId   = $sessionHandler->getSessionVariable('InstituteId');

		$query = "INSERT INTO `user` (userName,userPassword,roleId,instituteId) VALUES ('$rollNo','$pass',$roleId,$instituteId)";
		
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
    //-------------------------------------------------------
    //  THIS FUNCTION IS USED TO update password
    //
    // Author :Gurkeerat
    // Created on : (02-Nov-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
   public function updateUserData($pass,$userId,$userName) {

        $query = "    UPDATE `user` 
                      SET 
                            userName = '$userName',
                            userPassword = '$pass'
                      WHERE 
                            userId = '$userId'";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
	//-------------------------------------------------------
	//  THIS FUNCTION IS USED TO fetch student roll no, username
	//
	// Author :Jaineesh
	// Created on : (29-May-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------      
	public function updateStudentRecord($userId,$rollNo) {

		$query = "	UPDATE `student` 
					SET userId = $userId
					where rollNo = '$rollNo'";
		
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH PERIOD LIST
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function getStudentCurrentClass($conditions='') {
     
        global $sessionHandler;
        
      $query = "	SELECT	c.classId,
							c.className,
							scs.studentId,
							scs.rollNo,
							scs.userId
					FROM	class c,
							student scs
					WHERE	c.instituteId ='".$sessionHandler->getSessionVariable('InstituteId')."' 
					AND		c.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
					AND		scs.classId = c.classId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT GROUP
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function getSelectedStudentGroups($conditions='') {
     
        global $sessionHandler;
        
	$query = "	SELECT	COUNT(*) AS totalRecords
				FROM	student_groups
						$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT ATTENDANCE
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function getStudentAttendance($conditions='') {
     
        global $sessionHandler;
        
	$query = "	SELECT	COUNT(*) AS totalRecords
				FROM	attendance
						$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH SUBJECT
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function getStudentCurrentClassSubject($conditions='') {
     
        global $sessionHandler;
        
	$query = "	SELECT	distinct(stc.subjectId)
					FROM	subject_to_class stc,
							student s
					WHERE	s.classId = stc.classId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH SUBJECT
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function getStudentNewClassSubject($conditions='') {
     
        global $sessionHandler;
		
	 $query = "	SELECT	distinct(stc.subjectId)
					FROM	subject_to_class stc,
							student s
					WHERE	s.classId = stc.classId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO CHECK EXISTANCE ROLL NO
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function getExistanceRollNo($condition='') {
     
        global $sessionHandler;
		
		$query = "	SELECT	scs.rollNo
					FROM	student scs
							$condition";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getPrevClass() function returns previous class
// $condition :  used to check the condition of the table  
// Author : Jaineesh
// Created on : 21.05.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
	public function getPrevClass($degreeId) {
	$query = "
					SELECT
							  b.classId,
							  b.isActive, 
							  b.marksTransferred, 
							  b.className,
							  b.studyPeriodId
					FROM      class a, class b, study_period c, study_period d
					WHERE     CONCAT(a.instituteId,'#',a.universityId, '#', a.batchId, '#', a.degreeId, '#', a.branchId) =
							  CONCAT(b.instituteId,'#',b.universityId, '#', b.batchId, '#', b.degreeId, '#', b.branchId)
					AND       a.studyPeriodId = c.studyPeriodId
					AND       b.studyPeriodId = d.studyPeriodId
					AND       c.periodicityId = d.periodicityId
					AND       d.periodValue = c.periodValue - 1
					AND       a.classId = $degreeId";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 

	}

	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE STUDENT TABLE
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function studentUpdate($studentId,$newClassId) {
     
        global $sessionHandler;
		
		$query = "	UPDATE	student
					SET		classId = $newClassId
					WHERE	studentId = $studentId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE SC STUDENT SECTION SUBJECT TABLE
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function updateStudentGroups($studentId,$classId) {
     
        global $sessionHandler;
		
		$query = "	DELETE	
					FROM	student_groups
					WHERE	studentId = $studentId
					AND		classId = $classId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE SC ATTENDANCE TABLE
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function updateAttendance($studentId,$classId,$newClassId) {
     
        global $sessionHandler;
		
		$query = "	UPDATE	attendance
					SET		classId = $newClassId
					WHERE	studentId = $studentId
					AND		classId = $classId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE SC TEST MARKS
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function updateTestMarks($studentId,$classId,$newClassId) {
     
        global $sessionHandler;
		
		$query = "	UPDATE	test_marks
					SET		classId = $newClassId
					WHERE	studentId = $studentId
					AND		classId = $classId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE SC TEST MARKS
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function updateStudentOptionalSubject($studentId,$classId) {
     
        global $sessionHandler;
		
		$query = "	DELETE	
					FROM	student_optional_subject
					WHERE	studentId = $studentId
					AND		classId = $classId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE SC TEST MARKS
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function updateTestTransferredMarks($studentId,$classId,$newClassId) {
     
        global $sessionHandler;
		
		$query = "	UPDATE	".TEST_TRANSFERRED_MARKS_TABLE."
					SET		classId = $newClassId
					WHERE	studentId = $studentId
					AND		classId = $classId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE SC TEST MARKS
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function updateTotalTransferredMarks($studentId,$classId,$newClassId) {
     
        global $sessionHandler;
		
		$query = "	UPDATE	".TOTAL_TRANSFERRED_MARKS_TABLE."
					SET		classId = $newClassId
					WHERE	studentId = $studentId
					AND		classId = $classId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE SC ROLL NO
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function updateCurrentStudentRollNo($studentId,$newRollNo) {
     
        global $sessionHandler;
		
		$query = "	UPDATE	student
					SET		rollNo = '$newRollNo'
					WHERE	studentId = $studentId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE USER NAME
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function updateStudentUserName($userId,$newRollNo) {
     
        global $sessionHandler;
		
		$query = "	UPDATE	user
					SET		userName = '$newRollNo'
					WHERE	userId = $userId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT ROLLNO CLASS UPDATION LOG
//
// Author :Jaineesh
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function insertRollClassUpdationLog($studentId,$classId,$rollNo,$reason) {
     
        global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$date = date('Y-m-d h:i:s');
		
		$query = "	INSERT INTO rollno_class_updation_log (studentId,oldClassId,oldRollNo,logDateTime,reason,userId) 
					VALUES ($studentId,$classId,'$rollNo','$date','$reason',$userId)";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT RECORD FOR STUDENT FOR OPTIONAL SUBJECT
//
// Author :Ajinder Singh
// Created on : (11.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
	public function assignOptionalGroup($insertStr) {
		$query = "INSERT INTO student_optional_subject(subjectId, studentId, classId, groupId, parentOfSubjectId) VALUES $insertStr";
		return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query"); 

	}


//-------------------------------------------------------------------------------
//
// function returns class registration number
// $condition :  used to check the condition of the table  
// Author : Rajeev Aggarwal
// Created on : 12.06.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
	public function getRegistrationNumber($condition='') {
		
		$query = "SELECT regNo FROM student $condition ORDER BY studentId DESC LIMIT 0,1";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

//-------------------------------------------------------------------------------
//
// function returns last fee receipt No
// $condition :  used to check the condition of the table  
// Author : Rajeev Aggarwal
// Created on : 12.06.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
	public function getFeeReceiptNumber($condition='') {
		
		$query = "SELECT receiptNo FROM fee_receipt $condition ORDER BY feeReceiptId DESC LIMIT 0,1";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

//-------------------------------------------------------------------------------
//
// function returns class registration number
// $condition :  used to check the condition of the table  
// Author : Rajeev Aggarwal
// Created on : 12.06.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
	public function getQuartineRegistrationNumber($condition='') {
		
		$query = "SELECT regNo FROM quarantine_student $condition ORDER BY studentId DESC LIMIT 0,1";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}
	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO check whether the subject is hasCategory or not
	//
	// Author :Ajinder Singh
	// Created on : (07.07.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------  	
	public function hasParentCategory($classId, $subjectId) {
		$query = "SELECT hasParentCategory FROM subject_to_class WHERE classId = $classId AND subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO fetch category subjects
	//
	// Author :Ajinder Singh
	// Created on : (07.07.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------  	
	public function getCategorySubjects($subjectId) {
		$query = "SELECT subjectId, subjectCode FROM subject WHERE subjectCategoryId != (SELECT subjectCategoryId from subject where subjectId = $subjectId)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO count optional subjects which hasParentCategory = 1
	//
	// Author :Ajinder Singh
	// Created on : (07.07.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------  	
	public function countClassOptionalSubjects($classId) {
		$query = "SELECT COUNT(subjectId) as cnt from subject_to_class WHERE classId = $classId and optional = 1 and hasParentCategory = 1";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO fetch students who have not chosen their main subject and have not chosen this subject and have not taken total optional subjects.
	//
	// Author :Ajinder Singh
	// Created on : (07.07.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------  	
	public function getOptionalPendingStudents($classId, $subjectId, $categorySubjectId, $optionalSubjectCount, $orderBy) {
		$query = "SELECT studentId, universityRollNo, regNo, concat(firstName, ' ', lastName) as studentName, rollNo FROM student WHERE classId = $classId AND studentId NOT IN (SELECT studentId FROM student_optional_subject WHERE classId = $classId AND (parentOfSubjectId = $subjectId or subjectId = $categorySubjectId))  AND studentId NOT IN (SELECT studentId FROM student_optional_subject WHERE classId = $classId GROUP BY subjectId HAVING COUNT(subjectId) = $optionalSubjectCount) ORDER BY $orderBy";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	}

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of Subject RESOURCE for a Course
//
// Author :Parveen sharma
// Created on : (03.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------  
    public function getCourseResourceList($subjectId='',$orderBy='',$limit=''){
     global $REQUEST_DATA;
     global $sessionHandler;
     
     $conditions = '';
     if($subjectId!="All" && $subjectId!='') {
        $conditions = " AND course_resources.subjectId=$subjectId"; 
     }
     
     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');
        
     $query="SELECT courseResourceId,resourceName,description,subjectCode AS subject,
                     IF(resourceUrl IS NULL,-1,resourceUrl) AS resourceUrl,                                              
                     IF(attachmentFile IS NULL,-1,attachmentFile) AS attachmentFile,
                     employeeName,
                     postedDate
             FROM 
               course_resources,resource_category,subject,employee  
             WHERE 
                  course_resources.resourceTypeId=resource_category.resourceTypeId
                  AND
                  course_resources.subjectId=subject.subjectId
                  AND
                  course_resources.employeeId=employee.employeeId
                  AND
                  course_resources.instituteId=$instituteId 
                  AND 
                  resource_category.instituteId=$instituteId
                  AND 
                  course_resources.sessionId=$sessionId
                  $conditions
                  ORDER BY $orderBy 
                  $limit " ;   
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }
    
    //-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting total no of Subject RESOURCE for a Course 
//
// Author :Parveen Sharma
// Created on : (03.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
    public function getTotalCourseResource($subjectId){
     global $REQUEST_DATA;
     global $sessionHandler;
     
     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');

     if($subjectId!="All") {
      $conditions = " AND course_resources.subjectId=$subjectId";    
     }

     $query="SELECT COUNT(*) AS totalRecords
             
             FROM 
               course_resources,resource_category,subject,employee  
             WHERE 
                  course_resources.resourceTypeId=resource_category.resourceTypeId
                  AND
                  course_resources.subjectId=subject.subjectId
                  AND
                  course_resources.employeeId=employee.employeeId
                  AND
                  course_resources.instituteId=$instituteId 
                  AND 
                  resource_category.instituteId=$instituteId
                  AND 
                  course_resources.sessionId=$sessionId
                  $conditions
               " ;   
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }

    //-------------------------------------------------------
    //  THIS FUNCTION IS Insert the parents userName, userPassword
    //
    // Author :Parveen Sharma
    // Created on : (29-May-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
    public function insertParentUserData($userName,$userPassword) {
        
        $roleId = '3';
		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId'); 
        $query = "INSERT INTO `user` (userName,userPassword,roleId,instituteId) VALUES ('$userName',md5('$userPassword'),$roleId,$instituteId)";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
    //-------------------------------------------------------
    //  THIS FUNCTION IS UPDATE TO parent Password (father/Mother/guardian)
    //
    // Author :Parveen Sharma
    // Created on : (29-May-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
    public function updateParentPassword($condition='') {

        $query = "UPDATE ".$condition;
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    //-------------------------------------------------------
    //  THIS FUNCTION IS to create a new user
    //
    // Author :Ajinder Singh
    // Created on : (30-july-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function createUserInTransaction($userName, $password, $roleId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "INSERT INTO user SET userName = '$userName', userPassword = '$password', roleId = '$roleId', instituteId = $instituteId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

    //-------------------------------------------------------
    //  THIS FUNCTION IS to fetch the max. userId
    //
    // Author :Ajinder Singh
    // Created on : (30-july-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function getNewUserId() {
		$query = "SELECT MAX(userId) as userId from user";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

    //-------------------------------------------------------
    //  THIS FUNCTION IS to fetch the instituteId of a student
    //
    // Author :Ajinder Singh
    // Created on : (13-Aug-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function getStudentClassInstitute($studentId) {
		$query = "SELECT b.instituteId FROM class b, student a WHERE a.studentId = $studentId AND a.classId = b.classId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

    //-------------------------------------------------------
    //  THIS FUNCTION IS to fetch the instituteId of a student
    //
    // Author :Ajinder Singh
    // Created on : (14-Aug-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function getGroupTypeName($groupTypeId) {
		$query = "SELECT groupTypeName FROM group_type WHERE groupTypeId = $groupTypeId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}


    //-------------------------------------------------------
    //  THIS FUNCTION IS to fetch the groups allocated to a student for a particular group type.
    //
    // Author :Ajinder Singh
    // Created on : (15-Sep-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function countOldGroups($studentId, $classId, $groupTypeId) {
		$query = "
				SELECT 
							COUNT(sg.groupId) AS cnt 
				FROM		student_groups sg, `group` g 
				WHERE		sg.studentId = '$studentId' 
				AND			sg.classId = '$classId' 
				AND			sg.groupId = g.groupId 
				AND			g.groupTypeId = $groupTypeId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	//-------------------------------------------------------
    //  THIS FUNCTION IS to fetch the role 
    //
    // Author :Ajinder Singh
    // Created on : (15-Sep-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function getRoleUser($userId) {
		 $query = "
				SELECT 
							COUNT(*) as totalRecords 
				FROM		classes_visible_to_role cvtr,
							user_role ur
				WHERE		cvtr.userId = ur.userId
				AND			ur.userId = $userId
				AND			cvtr.userId = $userId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO GET Room Facilities Details
	// Author : Dipanjan Bhattacharjee
	// Created on : (15.07.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//-------------------------------------------------------------------------------           
    public function getRoomFacilitiesData($conditions='') {
     
       $query = "SELECT 
                         hrt.roomType,hrt.roomAbbr,
                         IF(hrtd.airConditioned=1,'Yes','No') AS airConditioned,
                         IF(hrtd.internetFacility=1,'Yes','No') AS internetFacility,
                         IF(hrtd.attachedBath=1,'Yes','No') AS attachedBath
                  FROM 
                         hostel_room_type_detail hrtd ,hostel_room hr ,hostel_room_type hrt,hostel_students hs
                  WHERE 
                         hr.hostelRoomTypeId=hrtd.hostelRoomTypeId
                         AND hrt.hostelRoomTypeId=hr.hostelRoomTypeId
                         AND hr.hostelRoomId=hs.hostelRoomId
                         ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
	//-------------------------------------------------------
    //  THIS FUNCTION IS to fetch the role 
    //
    // Author :Ajinder Singh
    // Created on : (15-Sep-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function getClassAllGroups($classId) {
		$query = "
				SELECT 
								g.groupId, g.groupShort, gt.groupTypeName, g.parentGroupId
				FROM			`group` g, group_type gt 
				WHERE			g.classId = $classId 
				AND				g.groupTypeId = gt.groupTypeId
				ORDER BY		parentGroupId, groupShort
				";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//-------------------------------------------------------
    //  THIS FUNCTION IS to fetch the students with allocated groups
    //
    // Author :Ajinder Singh
    // Created on : (15-Sep-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function getClassStudentGroupAllocation($classId, $sortBy) {
		$query = "
				SELECT 
								s.studentId, s.rollNo, concat(s.firstName,' ',s.lastName) as studentName, group_concat(sg.groupId) as groupsAllocated
				FROM			student s left join student_groups sg on (s.studentId = sg.studentId AND s.classId = sg.classId )
				WHERE			s.classId = $classId 
				group by		s.studentId
				ORDER BY		$sortBy
				";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//-------------------------------------------------------
    //  THIS FUNCTION IS to fetch students who have attendance/marks entered
    //
    // Author :Ajinder Singh
    // Created on : (15-Sep-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function getStudentWithAttendanceTest($classId) {
		$query = "select distinct b.studentId, b.rollNo, concat(b.firstName,' ',b.lastName) as studentName, a.groupId from ".ATTENDANCE_TABLE." a, student b where a.classId = $classId and a.studentId = b.studentId and a.classId = b.classId
		union 
		select distinct b.studentId, b.rollNo, concat(b.firstName,' ',b.lastName) as studentName, t.groupId  from ".TEST_TABLE." t, ".TEST_MARKS_TABLE." tm, student b where t.testId = tm.testId and t.classId = $classId and tm.studentId = b.studentId and t.classId = b.classId
		";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//-------------------------------------------------------
    //  THIS FUNCTION IS to remove current allocation for a class
    //
    // Author :Ajinder Singh
    // Created on : (15-Sep-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function removeStudentGroupAllocation($degree) {
		$query = "DELETE FROM student_groups WHERE classId = $degree";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	//-------------------------------------------------------
    //  THIS FUNCTION IS to add current allocation for a class
    //
    // Author :Ajinder Singh
    // Created on : (15-Sep-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function addStudentGroupAllocation($insertStr) {
		$query = "INSERT INTO student_groups (studentId, classId, groupId, instituteId, sessionId) VALUES $insertStr";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	//-------------------------------------------------------
    //  THIS FUNCTION IS to count current allocation for a class
    //
    // Author :Ajinder Singh
    // Created on : (15-Sep-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function countClassStudentGroupAllocation($degree) {
		$query = "select a.groupId, count(a.studentId) as groupStudentCount from student_groups a, student b where a.classId = $degree and a.classId = b.classId and a.studentId = b.studentId group by a.groupId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//-------------------------------------------------------
    //  THIS FUNCTION IS to fetch all students of a class
    //
    // Author :Ajinder Singh
    // Created on : (15-Sep-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------      
	public function getClassAllStudents($classId) {
		$query = "select studentId, rollNo, concat(firstName,' ',lastName) as studentName from student where classId = $classId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching studentId and class for a roll no
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (12.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
	public function getStudentId($rollNo,$getClassId) {
		$query = "SELECT 
								s.studentId, 
								s.classId,
								cl.className,
								cl.sessionId,
								cl.instituteId,
								s.rollNo,
								concat(s.firstName,' ', s.lastName) as studentName
				 FROM			student s, 
								class cl
				 WHERE			s.rollNo = '$rollNo'
				 AND			s.classId = cl.classId
				 AND			s.classId = $getClassId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching studentId and class for a roll no
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (12.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
	public function getStudentGroup($classId) {
		$query = "SELECT 
							gr.groupId, 
							gr.groupShort,
							gr.parentGroupId
				 FROM		`group` gr,
							class cl
				 WHERE		gr.classId = $classId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching group detail
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (12.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
	public function getStudentGroupDetail($conditions='') {
		  $query = "SELECT 
							gr.groupId, 
							gr.groupShort,
							gr.parentGroupId
				 FROM		`group` gr
				 WHERE		$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching studentId and class for a roll no
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (12.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
	public function getParentGroupId($conditions) {
		$query = "SELECT 
							gr.groupId
				 FROM		`group` gr
				 WHERE		$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

//-------------------------------------------------------------------------------
//
//addGroupInTransaction() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 13.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function addGroupInTransaction($str) {
		$query = "INSERT IGNORE INTO `student_groups` (studentId,classId,groupId,instituteId,sessionId) VALUES $str";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching group detail
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (12.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
	public function getExistStudentGroup($studentId,$classId,$groupId,$instituteId,$sessionId) {
		   $query = "	SELECT 
								*
						FROM	student_groups sg
						WHERE	studentId=$studentId
						AND		classId = $classId
						AND		groupId = $groupId
						AND		instituteId = $instituteId
						AND		sessionId = $sessionId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching student detail
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (26.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
	public function getStudentDetailInfo($conditions) {
		    $query = "	SELECT 
								distinct s.studentId, 
								s.classId,
								cl.className,
								cl.sessionId,
								cl.instituteId,
								s.fatherName,
								s.dateOfBirth,
								s.rollNo,
								s.studentMobileNo,
								s.universityRollNo,
								concat(Ifnull(s.firstName,''),' ',ifnull(s.lastName,'')) as studentName
						FROM	student s, 
								class cl
								$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching student detail
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (26.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
	public function getStudentInfo($conditions) {
		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');

		    $query = "	SELECT 
								distinct s.studentId, 
								s.classId,
								cl.className,
								cl.sessionId,
								cl.instituteId,
								s.fatherName,
								s.dateOfBirth,
								s.rollNo,
								s.studentMobileNo,
								s.universityRollNo,
								concat(Ifnull(s.firstName,''),' ',ifnull(s.lastName,'')) as studentName
						FROM	student s,
								student_groups sg,
								class cl
						WHERE	sg.classId = cl.classId 
						AND		s.studentId = sg.studentId
						AND		sg.instituteId = $instituteId
						AND		sg.sessionId = $sessionId
								$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}


//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching student detail
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (26.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
	public function getStudent($conditions) {
		    $query = "	SELECT count(*) as totalRecords
						FROM	student 
								$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}
	
	//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching student roll No.
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (26.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
	public function getStudentRollNo($conditions) {
		 $query = "	SELECT 
								rollNo,
								universityRollNo
						FROM	student
								$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//-------------------------------------------------------------------------------
//
//addStudentRollNoInTransaction() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 26.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function addStudentRollNoInTransaction($rollNo,$universityRollNo,$checkCondition) {
		$query = "	UPDATE	`student` 
					SET		rollNo = '$rollNo',
							universityRollNo = '$universityRollNo'
							$checkCondition";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


//-------------------------------------------------------------------------------
//
//getCountry() function used to SELECT COUNTY 
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 14.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function getCountry($conditions='') {
		$query = "	SELECT * FROM countries c
					$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

//-------------------------------------------------------------------------------
//
//getState() function used to SELECT States 
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 14.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function getState($conditions='') {
		$query = "	SELECT * FROM  states st
					$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

//-------------------------------------------------------------------------------
//
//getCity() function used to SELECT City 
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 14.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function getCity($conditions='') {
		$query = "	SELECT * FROM  city ct
					$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

//-------------------------------------------------------------------------------
//
//getQuota() function used to SELECT City 
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 14.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function getQuota($conditions='') {
		$query = "	SELECT * FROM  quota qt
					$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

//-------------------------------------------------------------------------------
//
//addStudentInfoInTransaction() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 13.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function addStudentInfoInTransaction($getClassId,$rollNo,$univRollNo,$isLeet,$firstName,$lastName,$fatherName,$fatherOccupation,$fatherMobile,$fatherAddress1,$fatherAddress2,$fatherCountryId,$fatherStateId,$fatherCityId,$motherName,$dateOfBirth,$corrAddress1,$corrAddress2,$corrPinCode,$corrCountryId,$corrStateId,$corrCityId,$permAddress1,$permAddress2,$permPinCode,$permCountryId,$permStateId,$permCityId,$studentMobile,$domicileId,$hostelFacility,$busFacility,$correspondencePhone,$permanentPhone,$studentStatus,$gender,$nationalityId,$quotaId,$contactNo,$emailAddress,$alternateEmailAddress,$dateOfAdmission,$registrationNo) {
		$query = "INSERT INTO `student` (classId,rollNo,universityRollNo,isLeet,firstName,lastName,fatherName,fatherOccupation,fatherMobileNo,fatherAddress1,fatherAddress2,  	fatherCountryId,fatherStateId,fatherCityId,motherName,dateOfBirth,corrAddress1,corrAddress2,corrPinCode,corrCountryId,corrStateId,corrCityId,permAddress1,permAddress2,permPinCode,permCountryId,permStateId,permCityId,studentMobileNo,domicileId,hostelFacility,transportFacility,corrPhone,permPhone,studentStatus,studentGender,nationalityId,quotaId,studentPhone,studentEmail,alternateStudentEmail,dateOfAdmission,regNo) VALUES ('$getClassId','$rollNo','$univRollNo','$isLeet','$firstName','$lastName','$fatherName','$fatherOccupation','$fatherMobile','$fatherAddress1','$fatherAddress2',$fatherCountryId,$fatherStateId,$fatherCityId,'$motherName','$dateOfBirth','$corrAddress1','$corrAddress2','$corrPinCode',$corrCountryId,$corrStateId,$corrCityId,'$permAddress1','$permAddress2','$permPinCode',$permCountryId,$permStateId,$permCityId,'$studentMobile',$domicileId,'$hostelFacility','$busFacility','$correspondencePhone','$permanentPhone','$studentStatus','$gender','$nationalityId','$quotaId','$contactNo','$emailAddress','$alternateEmailAddress','$dateOfAdmission','$registrationNo')";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


//-------------------------------------------------------------------------------
//
//addStudentInfoInTransaction() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 13.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function getClassInfo($conditions='') {
		$query = "	SELECT	cl.classId,
							cl.className
					FROM	class cl
							$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//-------------------------------------------------------------------------------
//
//updateStudentInfoInTransaction() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 13.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function updateStudentInfoInTransaction($isLeet,$lastName,$fatherName,$fatherOccupation,$fatherMobile,$fatherAddress1,$fatherAddress2,$fatherCountryId,$fatherStateId,$fatherCityId,$motherName,$dateOfBirth,$corrAddress1,$corrAddress2,$corrPinCode,$corrCountryId,$corrStateId,$corrCityId,$permAddress1,$permAddress2,$permPinCode,$permCountryId,$permStateId,$permCityId,$studentMobile,$domicileId,$hostelFacility,$busFacility,$correspondencePhone,$permanentPhone,$studentStatus,$gender,$nationalityId,$quotaId,$contactNo,$emailAddress,$alternateEmailAddress,$dateOfAdmission,$registrationNo,$studentRollNo) {
		  $query = "	UPDATE `student` 
					SET		isLeet = '$isLeet',
							lastName = '$lastName',
							fatherName = '$fatherName',
							fatherOccupation = '$fatherOccupation',
							fatherMobileNo = '$fatherMobile',
							fatherAddress1 = '$fatherAddress1',
							fatherAddress2 = '$fatherAddress2',
							fatherCountryId = $fatherCountryId,
							fatherStateId = $fatherStateId,
							fatherCityId = $fatherCityId,
							motherName = '$motherName',
							dateOfBirth = '$dateOfBirth',
							corrAddress1 = '$corrAddress1',
							corrAddress2 = '$corrAddress2',
							corrPinCode = '$corrPinCode',
							corrCountryId = $corrCountryId,
							corrStateId = $corrStateId,
							corrCityId = $corrCityId,
							permAddress1 = '$permAddress1',
							permAddress2 = '$permAddress2',
							permPinCode = '$permPinCode',
							permCountryId = $permCountryId,
							permStateId = $permStateId,
							permCityId = $permCityId,
							studentMobileNo = '$studentMobile',
							domicileId = $domicileId,
							hostelFacility = '$hostelFacility',
							transportFacility = '$busFacility',
							corrPhone = '$correspondencePhone',
							permPhone = '$permanentPhone',
							studentStatus = '$studentStatus',
							studentGender = '$gender',
							nationalityId = '$nationalityId',
							quotaId = '$quotaId',
							studentPhone = '$contactNo',
							studentEmail = '$emailAddress',
							alternateStudentEmail = '$alternateEmailAddress',
							dateOfAdmission = '$dateOfAdmission',
							regNo = '$registrationNo'
					WHERE	rollNo = '$studentRollNo'
					";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


//-------------------------------------------------------------------------------
//
//updateStudentInfoInTransaction() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 13.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function updateStudentWithoutRollNoInfoInTransaction($univRollNo,$firstName,$isLeet,$lastName,$fatherName,$fatherOccupation,$fatherMobile,$fatherAddress1,$fatherAddress2,$fatherCountryId,$fatherStateId,$fatherCityId,$motherName,$dateOfBirth,$corrAddress1,$corrAddress2,$corrPinCode,$corrCountryId,$corrStateId,$corrCityId,$permAddress1,$permAddress2,$permPinCode,$permCountryId,$permStateId,$permCityId,$studentMobile,$domicileId,$hostelFacility,$busFacility,$correspondencePhone,$permanentPhone,$studentStatus,$gender,$nationalityId,$quotaId,$contactNo,$emailAddress,$alternateEmailAddress,$dateOfAdmission,$registrationNo,$studentRollNo) {
		  $query = "	UPDATE `student` 
					SET		universityRollNo = '$univRollNo',
							isLeet = '$isLeet',
							lastName = '$lastName',
							fatherName = '$fatherName',
							fatherOccupation = '$fatherOccupation',
							fatherMobileNo = '$fatherMobile',
							fatherAddress1 = '$fatherAddress1',
							fatherAddress2 = '$fatherAddress2',
							fatherCountryId = $fatherCountryId,
							fatherStateId = $fatherStateId,
							fatherCityId = $fatherCityId,
							motherName = '$motherName',
							corrAddress1 = '$corrAddress1',
							corrAddress2 = '$corrAddress2',
							corrPinCode = '$corrPinCode',
							corrCountryId = $corrCountryId,
							corrStateId = $corrStateId,
							corrCityId = $corrCityId,
							permAddress1 = '$permAddress1',
							permAddress2 = '$permAddress2',
							permPinCode = '$permPinCode',
							permCountryId = $permCountryId,
							permStateId = $permStateId,
							permCityId = $permCityId,
							studentMobileNo = '$studentMobile',
							domicileId = $domicileId,
							hostelFacility = '$hostelFacility',
							transportFacility = '$busFacility',
							corrPhone = '$correspondencePhone',
							permPhone = '$permanentPhone',
							studentStatus = '$studentStatus',
							studentGender = '$gender',
							nationalityId = '$nationalityId',
							quotaId = '$quotaId',
							studentPhone = '$contactNo',
							studentEmail = '$emailAddress',
							alternateStudentEmail = '$alternateEmailAddress',
							dateOfAdmission = '$dateOfAdmission',
							regNo = '$registrationNo'
					WHERE	firstName = '$firstName'
					AND		dateOfBirth = '$dateOfBirth'
					";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


	//-------------------------------------------------------------------------------
//
//updateStudentInfoInTransaction() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 13.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function updateStudentRollNoInfoInTransaction($univRollNo,$firstName,$rollNo,$isLeet,$lastName,$fatherName,$fatherOccupation,$fatherMobile,$fatherAddress1,$fatherAddress2,$fatherCountryId,$fatherStateId,$fatherCityId,$motherName,$dateOfBirth,$corrAddress1,$corrAddress2,$corrPinCode,$corrCountryId,$corrStateId,$corrCityId,$permAddress1,$permAddress2,$permPinCode,$permCountryId,$permStateId,$permCityId,$studentMobile,$domicileId,$hostelFacility,$busFacility,$correspondencePhone,$permanentPhone,$studentStatus,$gender,$nationalityId,$quotaId,$contactNo,$emailAddress,$alternateEmailAddress,$dateOfAdmission,$registrationNo,$studentRollNo) {
		   $query = "	UPDATE `student` 
					SET		universityRollNo = '$univRollNo',
							rollNo = '$rollNo',
							isLeet = '$isLeet',
							lastName = '$lastName',
							fatherName = '$fatherName',
							fatherOccupation = '$fatherOccupation',
							fatherMobileNo = '$fatherMobile',
							fatherAddress1 = '$fatherAddress1',
							fatherAddress2 = '$fatherAddress2',
							fatherCountryId = $fatherCountryId,
							fatherStateId = $fatherStateId,
							fatherCityId = $fatherCityId,
							motherName = '$motherName',
							corrAddress1 = '$corrAddress1',
							corrAddress2 = '$corrAddress2',
							corrPinCode = '$corrPinCode',
							corrCountryId = $corrCountryId,
							corrStateId = $corrStateId,
							corrCityId = $corrCityId,
							permAddress1 = '$permAddress1',
							permAddress2 = '$permAddress2',
							permPinCode = '$permPinCode',
							permCountryId = $permCountryId,
							permStateId = $permStateId,
							permCityId = $permCityId,
							studentMobileNo = '$studentMobile',
							domicileId = $domicileId,
							hostelFacility = '$hostelFacility',
							transportFacility = '$busFacility',
							corrPhone = '$correspondencePhone',
							permPhone = '$permanentPhone',
							studentStatus = '$studentStatus',
							studentGender = '$gender',
							nationalityId = '$nationalityId',
							quotaId = '$quotaId',
							studentPhone = '$contactNo',
							studentEmail = '$emailAddress',
							alternateStudentEmail = 'alternateEmailAddress',
							dateOfAdmission = '$dateOfAdmission',
							regNo = '$registrationNo'
					WHERE	firstName = '$firstName'
					AND		dateOfBirth = '$dateOfBirth'
					";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Student Image
//
// Author :Parveen Sharma                       
// Created on : (20.08.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------- 
    public function deleteStudentImage($id) {
        
        $query="UPDATE student SET studentPhoto=NULL WHERE studentId='".$id."'";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Tests of a particular class subject and group
//
// Author :Ajinder Singh
// Created on : (30-dec-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------- 
	public function getTests($classId, $subjectId, $groupId, $conditions = '') {
		$query = "
				SELECT 
							a.testId, 
							a.testTopic, 
							a.testAbbr, 
							c.testTypeName, 
							a.testIndex, 
							a.testTypeCategoryId,
							COUNT(b.studentId) AS studentCount 
				FROM		test a, test_marks b, test_type_category c 
				WHERE		a.testId = b.testId 
				AND			a.testTypeCategoryId = c.testTypeCategoryId 
				AND			a.classId = $classId 
				AND			a.subjectId = $subjectId 
				AND			a.groupId = $groupId 
				$conditions
				GROUP BY	a.testId 
				ORDER BY	c.testTypeName, a.testIndex
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}


	//---------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR checking current category of a test
	//
	// Author :Ajinder Singh
	// Created on : (30-dec-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------- 
	public function getTestCurrentCategory($testId) {
		$query = "SELECT a.testTypeCategoryId, b.testTypeName from test a, test_type_category b where a.testTypeCategoryId = b.testTypeCategoryId and a.testId = $testId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//---------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR fetching subject type of a subject
	//
	// Author :Ajinder Singh
	// Created on : (30-dec-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------- 
	public function getSubjectTypeId($subjectId) {
		$query = "SELECT subjectTypeId FROM subject where subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//---------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR fetching other test type categories
	//
	// Author :Ajinder Singh
	// Created on : (30-dec-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------- 
	public function getOtherTestCategory($currentTestTypeCategoryId, $subjectTypeId) {
		$query = "SELECT testTypeCategoryId, testTypeName from test_type_category where subjectTypeId = $subjectTypeId and examType = 'PC' AND showCategory = 1 AND isAttendanceCategory = 0 AND testTypeCategoryId != $currentTestTypeCategoryId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//---------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR fetching max test index
	//
	// Author :Ajinder Singh
	// Created on : (30-dec-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------- 
	public function getNewTestIndex($classId, $subjectId, $groupId, $newTestTypeCategoryId) {
		$query = "SELECT IFNULL(MAX(testIndex),0) as testIndex from test where classId = $classId and subjectId = $subjectId and groupId = $groupId and testTypeCategoryId = $newTestTypeCategoryId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//---------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR updating test index
	//
	// Author :Ajinder Singh
	// Created on : (30-dec-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------- 
	public function upNewTestIndexInTransaction($classId, $subjectId, $groupId, $testId, $newTestTypeCategoryId, $newTestIndex) {
		$query = "UPDATE test set testTypeCategoryId = $newTestTypeCategoryId, testIndex = $newTestIndex where classId = $classId and subjectId = $subjectId and groupId = $groupId and testId = $testId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


    
    
        //-------------------------------------------------------
        //  THIS FUNCTION IS USED TO GET A LIST OF check student reappear subject details
        // Author :PArveen Sharma
        // Created on : (29.12.2009)
        // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
        //-------------------------------------------------------- 
          public function getStudentReappearDetails($condition='', $orderBy='ORDER BY className',$reapperClassId=''){
               global $sessionHandler;
               
               $systemDatabaseManager = SystemDatabaseManager::getInstance();
               $instituteId=$sessionHandler->getSessionVariable('InstituteId');
               $studentId = $sessionHandler->getSessionVariable('StudentId'); 
               $classId = $sessionHandler->getSessionVariable('ClassId'); 
               
               $query="SELECT 
                            DISTINCT sub.subjectName, sub.subjectCode, sc.subjectId, st.subjectTypeName, 
                                    sc.classId, sr.currentClassId, IFNULL(sr.reappearId,'') AS reappearId, 
                            IF(IFNULL(reppearStatus,'')='','Not Submitted',IFNULL(reppearStatus,'')) AS reppearStatus,
                            IFNULL(sr.assignmentStatus,0) AS assignmentStatus, IFNULL(sr.midSemesterStatus,0) AS midSemesterStatus, 
                            IFNULL(sr.attendanceStatus,0) AS attendanceStatus, sub.subjectId, IFNULL(detained,'".NOT_APPLICABLE_STRING."') AS detained
                       FROM
                            subject_type st, `subject` sub , 
                            `subject_to_class` sc  LEFT JOIN `student_reappear` sr  ON sr.subjectId = sc.subjectId AND 
                             sr.studentId = $studentId AND sr.currentClassId = $classId AND 
                             sr.reapperClassId= $reapperClassId AND sr.instituteId = $instituteId 
                       WHERE
                            st.subjectTypeId = sub.subjectTypeId AND
                            sc.subjectId = sub.subjectId  AND
                            sub.hasAttendance = 1 AND
                            sub.hasMarks = 1
                       $condition
                       $orderBy";
             
            return $systemDatabaseManager->executeQuery($query,"Query: $query");
         }
         
        //-------------------------------------------------------
        //  THIS FUNCTION TO student reappear subject details
        // Author :PArveen Sharma
        // Created on : (29.12.2009)
        // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
        //--------------------------------------------------------  
            public function addReapperSubject($str) {
                global $REQUEST_DATA;
                
                $fieldName = "studentId, reapperClassId, subjectId, dateOfEntry, reppearStatus, 
                              currentClassId, assignmentStatus, midSemesterStatus, attendanceStatus, instituteId"; 
                $query = "INSERT INTO student_reappear ($fieldName) values $str";
                
                return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);        
            }

        //-------------------------------------------------------
        // THIS FUNCTION TO Delete student reappear subject details
        // Author :PArveen Sharma
        // Created on : (29.12.2009)
        // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
        //--------------------------------------------------------  
            public function deleteReapperSubject($condition='') {
              
                global $REQUEST_DATA;
                
                $query = "DELETE FROM student_reappear $condition";
                
                return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);       
            }
            
        //-------------------------------------------------------
        //  THIS FUNCTION TO student reappear subject details
        // Author :PArveen Sharma
        // Created on : (29.12.2009)
        // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
        //--------------------------------------------------------  
            public function editReapperSubject($fieldName_values='',$condition='') {
                
                global $REQUEST_DATA;
              
                
                $query = "UPDATE student_reappear SET $fieldName_values $condition";
                
                return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);             
            }    
            
        //-------------------------------------------------------
        // THIS FUNCTION IS USED TO GET A LIST OF classwise student internal re-appear details
        // Author :PArveen Sharma
        // Created on : (29.12.2009)
        // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.                                    
        //-------------------------------------------------------- 
          public function getClasswiseReappearDetails($condition='', $orderBy='ORDER BY studentName', $limit='') {
               global $sessionHandler;
               
               $systemDatabaseManager = SystemDatabaseManager::getInstance();
               $instituteId=$sessionHandler->getSessionVariable('InstituteId');
               global $reppearStatusArr;     
               
               $query="SELECT
                              DISTINCT CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName, 
                              IFNULL(rollNo,'NOT_APPLICABLE_STRING') AS rollNo,
                              IFNULL(universityRollNo,'NOT_APPLICABLE_STRING') AS universityRollNo,
                              IF(IFNULL(sr.currentClassId,'')='','NOT_APPLICABLE_STRING',
                                  (SELECT className FROM class WHERE classId = sr.currentClassId)) AS currentClassName,
                              IF(IFNULL(sr.reapperClassId,'')='','NOT_APPLICABLE_STRING',
                                  (SELECT className FROM class WHERE classId = sr.reapperClassId)) AS reappearClassName,
                              IFNULL(sr.currentClassId,'') AS currentClassId, IFNULL(sr.reapperClassId,'') AS reapperClassId, sr.studentId,
                              GROUP_CONCAT(DISTINCT CONCAT(sub.subjectCode,'<br>(',
                                              IF(reppearStatus=1,'".$reppearStatusArr[1]."',
                                              IF(reppearStatus=2,'".$reppearStatusArr[2]."',
                                              IF(reppearStatus=3,'".$reppearStatusArr[3]."',''))),' / ',
                                              IF(IFNULL(detained,'N')='N','No','Yes')
                                              ,')') 
                              ORDER BY reppearStatus, subjectCode SEPARATOR '<br> ') AS subjects,
                              IFNULL(sr.assignmentStatus,0) AS assignmentStatus, IFNULL(sr.midSemesterStatus,0) AS midSemesterStatus, 
                              IFNULL(sr.attendanceStatus,0) AS attendanceStatus
                        FROM 
                              student_reappear sr, student s, subject_to_class stc, `subject` sub
                        WHERE
                              sr.studentId = s.studentId AND
                              stc.subjectId = sub.subjectId AND
                              stc.subjectId = sr.subjectId AND  
                              sr.reapperClassId = stc.classId AND
                              sr.instituteId = $instituteId  
                        $condition        
                        GROUP BY
                              sr.currentClassId, sr.reapperClassId, sr.studentId    
                       $orderBy $limit";
             
            return $systemDatabaseManager->executeQuery($query,"Query: $query");
         }    
         
        //-------------------------------------------------------
        //  THIS FUNCTION IS USED TO GET A LIST OF check student reappear subject details
        // Author :PArveen Sharma
        // Created on : (29.12.2009)
        // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
        //-------------------------------------------------------- 
          public function getStudentReappear($condition='', $orderBy='ORDER BY subjectName'){
               global $sessionHandler;
               
               $systemDatabaseManager = SystemDatabaseManager::getInstance();
               
               $query="SELECT 
                            DISTINCT sub.subjectName, sub.subjectCode, sub.subjectId, st.subjectTypeName, sr.reappearId,
                            sr.reapperClassId, sr.currentClassId, sr.reappearId, sr.reppearStatus, sr.studentId, detained,
                            IFNULL(sr.assignmentStatus,0) AS assignmentStatus, IFNULL(sr.midSemesterStatus,0) AS midSemesterStatus, 
                            IFNULL(sr.attendanceStatus,0) AS attendanceStatus
                       FROM
                            subject_type st, `student_reappear` sr, `subject` sub
                       WHERE
                            sr.subjectId = sub.subjectId AND
                            st.subjectTypeId = sub.subjectTypeId AND
                            sub.hasAttendance = 1 AND
                            sub.hasMarks = 1
                       $condition
                       $orderBy";
             
            return $systemDatabaseManager->executeQuery($query,"Query: $query");
         }
         

        //-------------------------------------------------------
        // THIS FUNCTION IS USED TO GET A LIST OF classwise student internal re-appear details
        // Author :PArveen Sharma
        // Created on : (29.12.2009)
        // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
        //-------------------------------------------------------- 
          public function getClasswiseReappearCount($condition=''){
               global $sessionHandler;
               
               $systemDatabaseManager = SystemDatabaseManager::getInstance();
               $instituteId=$sessionHandler->getSessionVariable('InstituteId');
               
               $query="SELECT 
                            COUNT(t.studentId) AS cnt 
                       FROM     
                            (SELECT
                                  DISTINCT CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName, 
                                  IFNULL(rollNo,'NOT_APPLICABLE_STRING') AS rollNo,
                                  IFNULL(universityRollNo,'NOT_APPLICABLE_STRING') AS universityRollNo,
                                  IF(IFNULL(sr.currentClassId,'')='','NOT_APPLICABLE_STRING',
                                      (SELECT className FROM class WHERE classId = sr.currentClassId)) AS currentClassName,
                                  IF(IFNULL(sr.reapperClassId,'')='','NOT_APPLICABLE_STRING',
                                      (SELECT className FROM class WHERE classId = sr.reapperClassId)) AS reappearClassName,
                                  IFNULL(sr.currentClassId,'') AS currentClassId, IFNULL(sr.reapperClassId,'') AS reapperClassId, sr.studentId,
                                  GROUP_CONCAT(DISTINCT sub.subjectCode ORDER BY subjectCode SEPARATOR ', ') AS subjects,
                                  IFNULL(sr.assignmentStatus,0) AS assignmentStatus, IFNULL(sr.midSemesterStatus,0) AS midSemesterStatus, 
                                  IFNULL(sr.attendanceStatus,0) AS attendanceStatus
                            FROM 
                                  student_reappear sr, student s, subject_to_class stc, `subject` sub
                            WHERE
                                  sr.studentId = s.studentId AND
                                  stc.subjectId = sub.subjectId AND
                                  stc.subjectId = sr.subjectId AND  
                                  sr.reapperClassId = stc.classId AND
                                  sr.instituteId = $instituteId    
                            $condition      
                            GROUP BY
                                  sr.currentClassId, sr.reapperClassId, sr.studentId ) AS t ";
             
               return $systemDatabaseManager->executeQuery($query,"Query: $query");
          }     
    
  
	 
	//---------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR fetching maxExternalMarks
	//
	// Author :Jaineesh
	// Created on : (30-dec-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------- 
	public function checkSubjectExistance($subjectId,$classId) {
		$query = "	SELECT 
							externalTotalMarks 
					FROM	subject_to_class 
					where	subjectId = $subjectId 
					AND		classId = $classId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching studentId and class for a roll no
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (12.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
	public function getUnivStudentId($universityRollNo,$getClassId) {
		$query = "SELECT 
								s.studentId, 
								s.classId,
								cl.className,
								cl.sessionId,
								cl.instituteId,
								s.rollNo,
								concat(s.firstName,' ', s.lastName) as studentName
				 FROM			student s,
								student_groups sg,
								class cl
				 WHERE			s.universityRollNo = '$universityRollNo'
				 AND			sg.studentId = s.studentId
				 AND			sg.classId = cl.classId
				 AND			sg.classId = $getClassId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//---------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR fetching maxExternalMarks
	//
	// Author :Jaineesh
	// Created on : (30-dec-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------- 
	public function findStudentClass($studentId,$classId) {
		$query = "	SELECT 
							sg.classId
					FROM	student_groups sg,
							class c
					WHERE	sg.studentId = $studentId 
					AND		sg.classId = c.classId
					AND		c.classId = $classId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//---------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR fetching maxExternalMarks
	//
	// Author :Jaineesh
	// Created on : (30-dec-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------- 
	public function checkTestExist($studentId,$classId,$subjectId,$testTypeId) {
		 $query = "	SELECT 
							count(testTypeId) as cnt
					FROM	".TEST_TRANSFERRED_MARKS_TABLE."
					WHERE	studentId = $studentId 
					AND		classId = $classId
					AND		subjectId = $subjectId
					AND		testTypeId = $testTypeId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//-------------------------------------------------------------------------------
//
//addStudentInfoInTransaction() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 13.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function addTestTransferredMarks($testTypeId,$studentId,$classId,$subjectId,$maxMarks,$maxValue) {

		$query = "INSERT INTO ".TEST_TRANSFERRED_MARKS_TABLE." (testTypeId,studentId,classId,subjectId,maxMarks,marksScored) VALUES ($testTypeId,$studentId,$classId,$subjectId,$maxMarks,$maxValue)";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	//-------------------------------------------------------------------------------
//
//addStudentInfoInTransaction() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 13.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function updateTestTransferMarks($studentId,$classId,$subjectId,$maxMarks,$maxValue) {
		 $query = "	UPDATE ".TEST_TRANSFERRED_MARKS_TABLE." 
					SET		maxMarks = $maxMarks,
							marksScored = $maxValue
					WHERE	studentId = $studentId
					AND		classId = $classId
					AND		subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	//---------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR fetching maxExternalMarks
	//
	// Author :Jaineesh
	// Created on : (30-dec-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------- 
	public function checkTransferredMarksExist($studentId,$classId,$subjectId) {
		$query = "	SELECT 
							count(*) as cnt
					FROM	".TOTAL_TRANSFERRED_MARKS_TABLE."
					WHERE	studentId = $studentId 
					AND		classId = $classId
					AND		subjectId = $subjectId
					AND		conductingAuthority = 2";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//-------------------------------------------------------------------------------
//
//addStudentInfoInTransaction() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 13.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function addTransferredMarks($conductingAuthority,$studentId,$classId,$subjectId,$maxMarks,$maxValue,$marksScoredStatus) {
		 $query = "INSERT INTO ".TOTAL_TRANSFERRED_MARKS_TABLE." (conductingAuthority,studentId,classId,subjectId,maxMarks,marksScored,marksScoredStatus) VALUES ($conductingAuthority,$studentId,$classId,$subjectId,$maxMarks,$maxValue,'$marksScoredStatus')";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	//-------------------------------------------------------------------------------
//
//updateTransferredMarks() function used to Update Total Transferred Marks
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 13.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function updateTransferredMarks($conductingAuthority,$studentId,$classId,$subjectId,$maxMarks,$maxValue,$marksScoredStatus) {
		 $query = "	UPDATE ".TOTAL_TRANSFERRED_MARKS_TABLE." 
					SET		maxMarks = $maxMarks,
							marksScored = $maxValue,
							marksScoredStatus = '$marksScoredStatus'
					WHERE	studentId = $studentId
					AND		classId = $classId
					AND		subjectId = $subjectId
					AND		conductingAuthority = 2";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Jaineesh
    // Created on : 17-04-2010
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------    
	public function getLabelClass($labelId,$orderBy=' ttc.timeTableLabelId') {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		$sepratorLen = strlen(CLASS_SEPRATOR);

		$query = "	SELECT 
							distinct cvtr.classId 
					FROM	classes_visible_to_role cvtr
					WHERE	cvtr.userId = $userId
					AND		cvtr.roleId = $roleId";

		$result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
		
		$count = count($result);
		$insertValue = "";
			for($i=0;$i<$count; $i++) {
				$querySeprator = '';
			    if($insertValue!='') {
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator ('".$result[$i]['classId']."')";
			}
		if ($count > 0) {
			$query = "	SELECT	distinct cls.classId,
								cls.className 
						FROM 	class cls,
								time_table_classes ttc, 
								time_table_labels ttl,
								classes_visible_to_role cvtr
						WHERE 
								 cls.instituteId='".$instituteId."' AND 
								 cls.sessionId='".$sessionId."' AND 
								 cls.classId = ttc.classId AND 
								 ttc.timeTableLabelId = ttl.timeTableLabelId AND
								 cls.classId IN ($insertValue) AND
								 cvtr.classId = cls.classId AND
								 cvtr.classId = ttc.classId AND
								 ttl.timeTableLabelId =$labelId
					 			
								 ORDER BY $orderBy DESC";
						
						return $systemDatabaseManager->executeQuery($query,"Query: $query");
		}
		else {
			$query = "SELECT cls.classId,cls.className 
					 FROM 
					 class cls,time_table_classes ttc, time_table_labels ttl 
					 WHERE 
					 cls.instituteId='".$instituteId."' AND 
					 cls.sessionId='".$sessionId."' AND 
					 cls.classId = ttc.classId AND 
					 ttc.timeTableLabelId = ttl.timeTableLabelId AND
					 ttl.timeTableLabelId =$labelId
					 
					 ORDER BY $orderBy DESC";
			return $systemDatabaseManager->executeQuery($query,"Query: $query");
		}
	}
	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Jaineesh
    // Created on : 17-04-2010
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------    
	public function getConfigLabelValue($condition) {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		 

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT `value` FROM config WHERE instituteId='".$instituteId."' $condition";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
	//
	// Author :Rajeev Aggarwal
	// Created on : (05.08.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------      
    public function getFeeReceiptNo($conditions='') {
     
        $query = "SELECT receiptNo FROM fee_receipt $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
	//
	// Author :Rajeev Aggarwal
	// Created on : (05.08.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------      
    public function getOptionalGroupList($rollNo) {
     
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

       $query = "	SELECT	sub.subjectCode,
							sub.subjectId,
							gr.groupName,
							gr.groupId
					FROM	`group` gr,
							subject sub,
							student_optional_subject sos,
							student st,
							class cl
					WHERE	sos.studentId = st.studentId
					AND		sos.subjectId = sub.subjectId
					AND		sos.groupId = gr.groupId
					AND		sos.classId = cl.classId
					AND		cl.isActive = 1
					AND		cl.sessionId = ".$sessionId."
					AND		cl.instituteId = ".$instituteId."
					AND		st.rollNo = '".$rollNo."' ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
	//
	// Author :Jaineesh
	// Created on : (17.04.2010)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------- 
	public function getOptionalSubject($classId,$limit='',$orderBy) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

		$query = "	SELECT	CONCAT(st.firstName,' ',st.lastName) AS studentName,
							st.rollNo,
							gr.groupName,
							concat(sub.subjectCode,' (',sub.subjectName,')') AS subjectCode,
							sos.parentOfSubjectId
					FROM	student st,
							student_optional_subject sos,
							`group` gr,
							class cl,
							subject sub
					WHERE	sos.subjectId = sub.subjectId
					AND		st.studentId = sos.studentId
					AND		sos.groupId = gr.groupId
					AND		sos.classId = cl.classId
					AND		sos.classId = $classId
					AND		cl.sessionId = $sessionId
					AND		cl.instituteId = $instituteId
							ORDER BY $orderBy
							$limit
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		
	}

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH STUDENT WITHOUT OPTIONAL DETAILS
	//
	// Author :Jaineesh
	// Created on : (17.04.2010)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------- 
	public function getWithoutOptionalSubject($classId,$limit='',$orderBy) {
	
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

		$query = "	SELECT	CONCAT(st.firstName,' ',st.lastName) AS studentName,
							st.rollNo,
							GROUP_CONCAT(gr.groupName SEPARATOR ' , ') AS groupName
					FROM	student st,
							student_groups sg,
							`group` gr,
							class cl
					WHERE	st.studentId = sg.studentId
					AND		gr.groupId = sg.groupId
					AND		sg.classId = cl.classId
					AND		sg.classId = $classId
					AND		cl.sessionId = $sessionId
					AND		cl.instituteId = $instituteId
							GROUP BY st.studentId
							ORDER BY $orderBy
							$limit
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		
	}

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH STUDENT WITHOUT OPTIONAL DETAILS
	//
	// Author :Jaineesh
	// Created on : (17.04.2010)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------- 
	public function getCountWithoutOptionalSubject($classId) {
	
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

		$query = "	SELECT	CONCAT(st.firstName,' ',st.lastName) AS studentName,
							st.rollNo,
							GROUP_CONCAT(gr.groupName SEPARATOR ' , ') AS groupName
					FROM	student st,
							student_groups sg,
							`group` gr,
							class cl
					WHERE	st.studentId = sg.studentId
					AND		gr.groupId = sg.groupId
					AND		sg.classId = cl.classId
					AND		sg.classId = $classId
					AND		cl.sessionId = $sessionId
					AND		cl.instituteId = $instituteId
							GROUP BY st.studentId
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		
	}

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
	//
	// Author :Jaineesh
	// Created on : (17.04.2010)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------- 
	public function getOptionalParentSubject($optionalSubjectId) {
	
		$query = "	SELECT	distinct sub.subjectId,
							concat(sub.subjectCode,' (',sub.subjectName,')') AS subjectCode,
							sos.parentOfSubjectId
					FROM	subject sub,
							student_optional_subject sos
					WHERE	sos.parentOfSubjectId = sub.subjectId
					AND		sos.parentOfSubjectId = $optionalSubjectId					
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		
	}

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
	//
	// Author :Jaineesh
	// Created on : (17.04.2010)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------- 
	public function getCountOptionalSubject($classId) {
	
		$query = "	SELECT	COUNT(*) AS totalRecords
					FROM	student st,
							student_optional_subject sos,
							`group` gr,
							class cl,
							subject sub
					WHERE	sos.subjectId = sub.subjectId
					AND		st.studentId = sos.studentId
					AND		sos.groupId = gr.groupId
					AND		sos.classId = cl.classId
					AND		sos.classId = $classId					
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		
	}


	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
	//
	// Author :Jaineesh
	// Created on : (17.04.2010)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------- 
	public function getDeletedStudentDetail($limit='',$orderBy) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

		$query = "	SELECT	qs.studentId,
							CONCAT(qs.firstName,' ',qs.lastName) AS studentName,
							qs.rollNo,
							cl.className
					FROM	quarantine_student qs,
							class cl
					WHERE	qs.classId = cl.classId
					AND		cl.instituteId = $instituteId
					AND		cl.sessionId = $sessionId
							ORDER BY $orderBy
							$limit
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		
	}


	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
	//
	// Author :Jaineesh
	// Created on : (17.04.2010)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------- 
	public function getCountDeletedStudentDetail() {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

		$query = "	SELECT	qs.studentId,
							CONCAT(qs.firstName,' ',qs.lastName) AS studentName,
							qs.rollNo,
							cl.className
					FROM	quarantine_student qs,
							class cl
					WHERE	qs.classId = cl.classId
					AND		cl.instituteId = $instituteId
					AND		cl.sessionId = $sessionId
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		
	}

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
	//
	// Author :Jaineesh
	// Created on : (17.04.2010)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------- 
	public function getDeleteStudentAttendanceReportDetail($studentId,$orderBy) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

	 $query = "	SELECT 
                        s.studentId,
                        CONCAT( s.firstName , ' ' , s.lastName ) AS studentName ,
						s.rollNo,
                        su.subjectName, su.subjectCode,
                        ROUND( SUM( IF( att.isMemberOfClass = 0 , 0 , IF( att.attendanceType = 2 , ( ac.attendanceCodePercentage / 100 ) , att.lectureAttended ) ) ) , 2 ) AS attended ,
                        SUM( IF( att.isMemberOfClass = 0 , 0 , att.lectureDelivered ) ) AS delivered ,
                        SUBSTRING_INDEX(c.className,'-',-3) AS className
            FROM        class c,
                        quarantine_student s
            INNER JOIN    ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
            LEFT JOIN    attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
			INNER JOIN	subject su ON su.subjectId = att.subjectId
            WHERE       s.studentId = $studentId
            AND         att.classId = c.classId
			AND         c.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." 
            AND         c.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
					   GROUP BY att.subjectId 
					   ORDER BY $orderBy
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		
	}


	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
	//
	// Author :Jaineesh
	// Created on : (17.04.2010)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------- 
	public function getDeleteStudentTestMarksReportDetail($studentId,$orderBy) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

	$query = "	SELECT 
                        s.studentId,
                        su.subjectName, su.subjectCode,t.testDate,
                        CONCAT(ttc.testTypeName,'-',t.testIndex) as testType,
                        (tm.maxMarks) AS totalMarks,
						IF(tm.isMemberOfClass =0, 'Not MOC',IF(isPresent=1,tm.marksScored,'A')) AS obtained,
                        SUBSTRING_INDEX(cl.className,'-',-3) AS className
            FROM		test_type_category ttc,
						 ".TEST_MARKS_TABLE."  tm,
						quarantine_student s,
						subject su,
						".TEST_TABLE." t,
						class cl
				WHERE	t.testTypeCategoryId = ttc.testTypeCategoryId
				AND		t.classId=cl.classId
				AND		t.testId = tm.testId
				AND		tm.studentId = s.studentId
				AND		tm.subjectId = su.subjectId
				AND		tm.studentId =$studentId
				AND		cl.sessionId = $sessionId
				AND		cl.instituteId = $instituteId
						ORDER BY $orderBy
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		
	}

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
	//
	// Author :Jaineesh
	// Created on : (17.04.2010)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------- 
	public function getDeleteStudentFinalReportDetail($studentId,$orderBy) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

	$query = "	SELECT 
                        s.studentId,
                        CONCAT( s.firstName , ' ' , s.lastName ) AS studentName ,
						s.rollNo,
                        su.subjectName, su.subjectCode,
						ttm.maxMarks,
						ttm.marksScored,
						if(ttm.conductingAuthority=1,'Internal',if(ttm.conductingAuthority=2,'External','Attendance')) as conductingAuthority,
                        SUBSTRING_INDEX(cl.className,'-',-3) AS className
            FROM		quarantine_student s,
						subject su,
						".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,
						class cl
				WHERE	ttm.classId = cl.classId
				AND		ttm.studentId = s.studentId
				AND		ttm.subjectId = su.subjectId
				AND		ttm.studentId =$studentId
				AND		cl.sessionId = $sessionId
				AND		cl.instituteId = $instituteId
						ORDER BY $orderBy
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		
	}

}//end of class


// for VSS
// $History: StudentManager.inc.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 4/22/10    Time: 12:59p
//Updated in $/LeapCC/Model
//put new function getOptionalGroupList()
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/21/10    Time: 12:17p
//Updated in $/LeapCC/Model
//getStudentTimeTable query update (optional subject basis)
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/21/10    Time: 12:05p
//Updated in $/LeapCC/Model
//put new function getOptionalGroupList()
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/20/10    Time: 2:38p
//Updated in $/LeapCC/Model
//daily & weekly base query format udpated (getStudentTimeTable)  
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 4/20/10    Time: 12:23p
//Created in $/LeapCC/Model
//file added, old file corrupted
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 4/15/10    Time: 1:00p
//Created in $/LeapCC/Model
//file added
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10-04-13   Time: 5:54p
//Created in $/LeapCC/Model
//intial checkin after files are corrupted
//
//*****************  Version 163  *****************
//User: Rajeev       Date: 10-04-12   Time: 4:00p
//Updated in $/LeapCC/Model
//updated VSS file from local file
//
//*****************  Version 162  *****************
//User: Rajeev       Date: 10-04-08   Time: 5:55p
//Updated in $/LeapCC/Model
//Added functions for Import Fees data
//
//*****************  Version 161  *****************
//User: Rajeev       Date: 10-03-31   Time: 4:53p
//Updated in $/LeapCC/Model
//updated studyperiodId query in fee receipt table
//
//*****************  Version 160  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Model
//updated with all the fees enhancements
//
//*****************  Version 159  *****************
//User: Dipanjan     Date: 20/03/10   Time: 17:34
//Updated in $/LeapCC/Model
//Created "Sent Student Information Message To Parents" module
//
//*****************  Version 158  *****************
//User: Jaineesh     Date: 3/18/10    Time: 5:16p
//Updated in $/LeapCC/Model
//changes done according to show status Absent, Detained
//
//*****************  Version 157  *****************
//User: Rajeev       Date: 10-03-04   Time: 12:41p
//Updated in $/LeapCC/Model
//updated the getStudentFeeHeadDetailClass functions 
//
//*****************  Version 156  *****************
//User: Rajeev       Date: 10-02-23   Time: 3:46p
//Updated in $/LeapCC/Model
//updated admit student with config setting for registration number
//
//*****************  Version 155  *****************
//User: Jaineesh     Date: 2/17/10    Time: 7:24p
//Updated in $/LeapCC/Model
//fixed bug nos. 0002885, 0002887, 0002886, 0002888, 0002889 and add time
//table filter also
//
//*****************  Version 154  *****************
//User: Rajeev       Date: 10-02-17   Time: 3:58p
//Updated in $/LeapCC/Model
//working on fees enhancements
//
//*****************  Version 153  *****************
//User: Jaineesh     Date: 2/12/10    Time: 4:23p
//Updated in $/LeapCC/Model
//fixed bug nos.  0002857, 0002861, 0002860, 0002863, 0002862
//
//*****************  Version 152  *****************
//User: Ajinder      Date: 2/11/10    Time: 4:49p
//Updated in $/LeapCC/Model
//done changes as per FCNS No.1292
//
//*****************  Version 151  *****************
//User: Parveen      Date: 2/11/10    Time: 2:50p
//Updated in $/LeapCC/Model
//re-apper class  function (review)
//
//*****************  Version 150  *****************
//User: Jaineesh     Date: 2/09/10    Time: 5:59p
//Updated in $/LeapCC/Model
//put link & make new functions for upload student external marks
//
//*****************  Version 149  *****************
//User: Jaineesh     Date: 2/09/10    Time: 2:34p
//Updated in $/LeapCC/Model
//put new functions checkTestExist(), addTestTransferredMarks() ,
//updateTestTransferMarks(), checkTransferredMarksExist(),
//addTransferredMarks(), updateTransferredMarks() for upload student
//external marks
//
//*****************  Version 148  *****************
//User: Ajinder      Date: 2/08/10    Time: 4:39p
//Updated in $/LeapCC/Model
//undone changes made for bug no. 2186. this bug is fixed in
//autosuggest.js 
//
//*****************  Version 145  *****************
//User: Jaineesh     Date: 2/08/10    Time: 4:21p
//Updated in $/LeapCC/Model
//put new function checkSubjectExistance()
//
//*****************  Version 144  *****************
//User: Jaineesh     Date: 2/04/10    Time: 11:07a
//Updated in $/LeapCC/Model
//changes in code to show final result tab in find student & parent 
//
//*****************  Version 143  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:31p
//Updated in $/LeapCC/Model
//done changes for new Session End Activities
//
//*****************  Version 142  *****************
//User: Parveen      Date: 1/28/10    Time: 6:09p
//Updated in $/LeapCC/Model
//function getClasswiseReappearDetails updated
//
//*****************  Version 141  *****************
//User: Rajeev       Date: 10-01-28   Time: 1:46p
//Updated in $/LeapCC/Model
//earlier it was showing all classes. not it will show classes based on
//academic head previleges 
//
//*****************  Version 140  *****************
//User: Ajinder      Date: 1/28/10    Time: 1:41p
//Updated in $/LeapCC/Model
//released file for Rajeev for time-being.
//
//*****************  Version 139  *****************
//User: Ajinder      Date: 1/20/10    Time: 5:08p
//Updated in $/LeapCC/Model
//done changes to Assign Colour scheme to test type and refect this
//colour in student tab. FCNS No. 1102
//
//*****************  Version 138  *****************
//User: Parveen      Date: 1/19/10    Time: 6:26p
//Updated in $/LeapCC/Model
//getClasswiseReappearDetails function updated  
//
//*****************  Version 137  *****************
//User: Parveen      Date: 1/19/10    Time: 12:08p
//Updated in $/LeapCC/Model
//function updated getClasswiseReappearDetails 
//
//*****************  Version 136  *****************
//User: Jaineesh     Date: 1/18/10    Time: 11:28a
//Updated in $/LeapCC/Model
//put new field university Roll No.
//
//*****************  Version 135  *****************
//User: Parveen      Date: 1/15/10    Time: 10:03a
//Updated in $/LeapCC/Model
//function updated getClasswiseReappearDetails
//
//*****************  Version 134  *****************
//User: Parveen      Date: 1/14/10    Time: 5:14p
//Updated in $/LeapCC/Model
//getClasswiseReappearDetails function updated
//
//*****************  Version 133  *****************
//User: Parveen      Date: 1/14/10    Time: 2:44p
//Updated in $/LeapCC/Model
//query format updated getStudentReappearDetails
//
//*****************  Version 132  *****************
//User: Parveen      Date: 1/14/10    Time: 2:15p
//Updated in $/LeapCC/Model
//getClasswiseReappearCount query udpated
//
//*****************  Version 131  *****************
//User: Parveen      Date: 1/13/10    Time: 2:13p
//Updated in $/LeapCC/Model
//getClasswiseReappearDetails function subjectId base check updated
//
//*****************  Version 130  *****************
//User: Parveen      Date: 1/12/10    Time: 6:16p
//Updated in $/LeapCC/Model
//format validation updated
//
//*****************  Version 129  *****************
//User: Parveen      Date: 1/09/10    Time: 5:48p
//Updated in $/LeapCC/Model
//getClasswiseReappearDetails function udpated (approvestatus added)
//
//*****************  Version 127  *****************
//User: Dipanjan     Date: 9/01/10    Time: 17:10
//Updated in $/LeapCC/Model
//Updated getPrevClass() function and added check for sessionId
//
//*****************  Version 126  *****************
//User: Parveen      Date: 1/09/10    Time: 5:09p
//Updated in $/LeapCC/Model
//getClasswiseReappearDetails, getClasswiseReappearCount function added
//
//*****************  Version 125  *****************
//User: Parveen      Date: 1/09/10    Time: 1:54p
//Updated in $/LeapCC/Model
//function updated getStudentReappearDetails (added instituteId)
//
//*****************  Version 124  *****************
//User: Parveen      Date: 1/09/10    Time: 1:08p
//Updated in $/LeapCC/Model
//editReapperSubject function updated
//
//*****************  Version 123  *****************
//User: Rajeev       Date: 10-01-09   Time: 11:38a
//Updated in $/LeapCC/Model
//updated with class isActive check on student list in find student
//
//*****************  Version 122  *****************
//User: Parveen      Date: 1/08/10    Time: 6:35p
//Updated in $/LeapCC/Model
//getStudentReappearDetails, deleteReapperSubject, editReapperSubject
//function added
//
//*****************  Version 121  *****************
//User: Ajinder      Date: 12/30/09   Time: 4:05p
//Updated in $/LeapCC/Model
//done changes for new module creation 'change test category'
//
//*****************  Version 120  *****************
//User: Jaineesh     Date: 12/28/09   Time: 4:38p
//Updated in $/LeapCC/Model
//put new field university roll no.
//
//*****************  Version 119  *****************
//User: Ajinder      Date: 12/28/09   Time: 1:21p
//Updated in $/LeapCC/Model
//transfer marks functions removed [added in
//transferMarksManager.inc.php]
//
//*****************  Version 118  *****************
//User: Rajeev       Date: 09-12-17   Time: 5:25p
//Updated in $/LeapCC/Model
//Updated with fees function for transport and hostel
//
//*****************  Version 117  *****************
//User: Ajinder      Date: 12/17/09   Time: 1:40p
//Updated in $/LeapCC/Model
//released for rajeev for time being.
//
//*****************  Version 116  *****************
//User: Rajeev       Date: 09-12-16   Time: 4:05p
//Updated in $/LeapCC/Model
//Updated with student fees functions
//
//*****************  Version 115  *****************
//User: Ajinder      Date: 12/16/09   Time: 3:05p
//Updated in $/LeapCC/Model
//released for Rajeev for time being.
//
//*****************  Version 114  *****************
//User: Jaineesh     Date: 12/08/09   Time: 5:09p
//Updated in $/LeapCC/Model
//put 14 new fields during student uploading and modification in checks
//
//*****************  Version 113  *****************
//User: Ajinder      Date: 12/07/09   Time: 10:11a
//Updated in $/LeapCC/Model
//fixed minor flaw left in query in countOptionalSubjectStudents()
//
//*****************  Version 112  *****************
//User: Ajinder      Date: 12/06/09   Time: 2:36p
//Updated in $/LeapCC/Model
//done changes in files for marks transfer
//
//*****************  Version 111  *****************
//User: Parveen      Date: 12/05/09   Time: 4:49p
//Updated in $/LeapCC/Model
//function added deleteStudentImage
//
//*****************  Version 110  *****************
//User: Parveen      Date: 12/05/09   Time: 11:44a
//Updated in $/LeapCC/Model
//function getstudentList, getRoleStudentList column added studentPhoto
//
//*****************  Version 109  *****************
//User: Jaineesh     Date: 12/01/09   Time: 3:32p
//Updated in $/LeapCC/Model
//put addslashes during corrAddress, PermAddress
//
//*****************  Version 108  *****************
//User: Jaineesh     Date: 12/01/09   Time: 1:35p
//Updated in $/LeapCC/Model
//put new field registration No. during uploading student detail
//
//*****************  Version 107  *****************
//User: Jaineesh     Date: 12/01/09   Time: 12:44p
//Updated in $/LeapCC/Model
//modified in query to check state, city, country for NULL
//
//*****************  Version 106  *****************
//User: Rajeev       Date: 09-11-25   Time: 4:17p
//Updated in $/LeapCC/Model
//Fixed 2126,2127,2128,2129,2130,
//2131,2132,2133,2134,2135
//
//*****************  Version 105  *****************
//User: Rajeev       Date: 09-11-25   Time: 3:15p
//Updated in $/LeapCC/Model
//resolved bug no 0002124 
//
//*****************  Version 104  *****************
//User: Ajinder      Date: 11/20/09   Time: 4:15p
//Updated in $/LeapCC/Model
//done changes related to transfer marks - initial checkin
//
//*****************  Version 103  *****************
//User: Rajeev       Date: 09-11-20   Time: 12:28p
//Updated in $/LeapCC/Model
//updated fee type field in fee receipt
//
//*****************  Version 102  *****************
//User: Jaineesh     Date: 11/18/09   Time: 6:58p
//Updated in $/LeapCC/Model
//student uploading and change in time table to show group short
//
//*****************  Version 101  *****************
//User: Ajinder      Date: 11/17/09   Time: 6:53p
//Updated in $/LeapCC/Model
//done changes for marks transfer
//
//*****************  Version 96  *****************
//User: Jaineesh     Date: 11/17/09   Time: 11:56a
//Updated in $/LeapCC/Model
//put new functions for upload student information
//
//*****************  Version 95  *****************
//User: Rajeev       Date: 09-11-17   Time: 9:51a
//Updated in $/LeapCC/Model
//updated with study period "5" in admit student functionality
//
//*****************  Version 94  *****************
//User: Jaineesh     Date: 11/17/09   Time: 9:49a
//Updated in $/LeapCC/Model
//put new functions for upload student information 
//
//*****************  Version 93  *****************
//User: Jaineesh     Date: 11/16/09   Time: 5:59p
//Updated in $/LeapCC/Model
//update function getAttendanceTestType() by Ajinder
//
//*****************  Version 92  *****************
//User: Rajeev       Date: 09-11-11   Time: 6:27p
//Updated in $/LeapCC/Model
//Added issue and payable bank id as per new requirement
//
//*****************  Version 91  *****************
//User: Ajinder      Date: 11/11/09   Time: 5:19p
//Updated in $/LeapCC/Model
//added ordering clause in getClassSubjects()
//
//*****************  Version 90  *****************
//User: Jaineesh     Date: 11/09/09   Time: 10:53a
//Updated in $/LeapCC/Model
//Change in function getStudentDetail() as per HOD role
//
//*****************  Version 89  *****************
//User: Gurkeerat    Date: 11/06/09   Time: 1:48p
//Updated in $/LeapCC/Model
//updated function 'getStudentList()' and 'updateUserData()'
//
//*****************  Version 88  *****************
//User: Ajinder      Date: 11/06/09   Time: 1:24p
//Updated in $/LeapCC/Model
//modified tables as per defines for multiple institutes.
//
//*****************  Version 87  *****************
//User: Jaineesh     Date: 11/04/09   Time: 12:06p
//Updated in $/LeapCC/Model
//put new functions for student roll no. uploading and make link for
//student roll no. under student setup
//
//*****************  Version 86  *****************
//User: Gurkeerat    Date: 11/03/09   Time: 11:31a
//Updated in $/LeapCC/Model
//added function 'updateUserData' and updated function 'getStudentRecord'
//
//*****************  Version 84  *****************
//User: Gurkeerat    Date: 10/27/09   Time: 3:15p
//Updated in $/LeapCC/Model
//added function 'getStudentRoll' 
//
//*****************  Version 83  *****************
//User: Ajinder      Date: 10/27/09   Time: 3:11p
//Updated in $/LeapCC/Model
//released for gurkeerat for time being.
//
//*****************  Version 82  *****************
//User: Jaineesh     Date: 10/27/09   Time: 1:07p
//Updated in $/LeapCC/Model
//put new functions() for insert student rollno.
//
//*****************  Version 81  *****************
//User: Ajinder      Date: 10/26/09   Time: 3:41p
//Updated in $/LeapCC/Model
//file released for time being for Jaineesh
//
//*****************  Version 80  *****************
//User: Jaineesh     Date: 10/26/09   Time: 1:07p
//Updated in $/LeapCC/Model
//put new function getStudentDetailInfo()
//
//*****************  Version 79  *****************
//User: Ajinder      Date: 10/26/09   Time: 12:31p
//Updated in $/LeapCC/Model
//file released for time being for Jaineesh
//
//*****************  Version 78  *****************
//User: Jaineesh     Date: 10/14/09   Time: 6:53p
//Updated in $/LeapCC/Model
//put new functions for group uploading
//
//*****************  Version 77  *****************
//User: Jaineesh     Date: 10/13/09   Time: 11:46a
//Updated in $/LeapCC/Model
//put new functions for groupuploading 
//
//*****************  Version 76  *****************
//User: Ajinder      Date: 10/08/09   Time: 3:12p
//Updated in $/LeapCC/Model
//done changes for group assignment (advanced)
//
//*****************  Version 75  *****************
//User: Jaineesh     Date: 10/01/09   Time: 6:51p
//Updated in $/LeapCC/Model
//changed queries and flow in send message to student, student report
//list according to HOD role and make new role advisory, modified in
//queries according to this role
//
//*****************  Version 74  *****************
//User: Rajeev       Date: 09-10-01   Time: 10:23a
//Updated in $/LeapCC/Model
//- Updated administrative tab with hostel details
//
//*****************  Version 73  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:47p
//Updated in $/LeapCC/Model
//worked on role to class
//
//*****************  Version 72  *****************
//User: Parveen      Date: 9/21/09    Time: 11:38a
//Updated in $/LeapCC/Model
//getStudentList function  rollno blank condition updated
//
//*****************  Version 71  *****************
//User: Ajinder      Date: 9/21/09    Time: 11:26a
//Updated in $/LeapCC/Model
//file changed to correct attendance problem.
//
//*****************  Version 70  *****************
//User: Ajinder      Date: 9/15/09    Time: 3:41p
//Updated in $/LeapCC/Model
//done changes to allow user to save data without selecting tutorial
//group/practical group
//
//*****************  Version 69  *****************
//User: Ajinder      Date: 9/08/09    Time: 5:10p
//Updated in $/LeapCC/Model
//changed queries from test type to test type categories.
//
//*****************  Version 68  *****************
//User: Rajeev       Date: 09-09-07   Time: 1:40p
//Updated in $/LeapCC/Model
//Updated marks table to be dynamic as per institute logged in
//
//*****************  Version 67  *****************
//User: Ajinder      Date: 9/02/09    Time: 10:58a
//Updated in $/LeapCC/Model
//added code for assigning groups to pending students only.
//
//*****************  Version 66  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:35p
//Updated in $/LeapCC/Model
//added multiple table defines.
//
//*****************  Version 64  *****************
//User: Ajinder      Date: 8/14/09    Time: 6:03p
//Updated in $/LeapCC/Model
//added function getGroupTypeName() to fetch group type name
//
//*****************  Version 63  *****************
//User: Ajinder      Date: 8/13/09    Time: 5:27p
//Updated in $/LeapCC/Model
//added function getStudentClassInstitute() to get current institute of
//student.
//
//*****************  Version 62  *****************
//User: Jaineesh     Date: 8/13/09    Time: 4:36p
//Updated in $/LeapCC/Model
//put institute Id in insertUserData(), insertParentUserData()
//
//*****************  Version 61  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 60  *****************
//User: Rajeev       Date: 8/07/09    Time: 7:06p
//Updated in $/LeapCC/Model
//Updated with "Admit Student" class dropdown. Now it will show classes
//with 1st and 3rd sememster only
//
//*****************  Version 59  *****************
//User: Ajinder      Date: 7/30/09    Time: 1:51p
//Updated in $/LeapCC/Model
//fixed bug no.0000755
//
//*****************  Version 58  *****************
//User: Rajeev       Date: 7/29/09    Time: 10:21a
//Updated in $/LeapCC/Model
//Updated with quartine student registration number increment
//
//*****************  Version 57  *****************
//User: Parveen      Date: 7/28/09    Time: 4:13p
//Updated in $/LeapCC/Model
//getStudentList function updated, (updateParentPassword,
//insertParentUserData function added)
//
//*****************  Version 56  *****************
//User: Rajeev       Date: 7/28/09    Time: 2:28p
//Updated in $/LeapCC/Model
//Updated final result query as per conducting authority 2
//
//*****************  Version 55  *****************
//User: Ajinder      Date: 7/25/09    Time: 12:54p
//Updated in $/LeapCC/Model
//added promotion functions, for transaction.
//
//*****************  Version 54  *****************
//User: Jaineesh     Date: 7/25/09    Time: 12:25p
//Updated in $/LeapCC/Model
//put new functions for update student class
//
//*****************  Version 53  *****************
//User: Rajeev       Date: 7/24/09    Time: 11:41a
//Updated in $/LeapCC/Model
//Added roll no, univ roll no, reg no and univ reg no validation on
//student quaratine table
//
//*****************  Version 52  *****************
//User: Rajeev       Date: 7/20/09    Time: 4:02p
//Updated in $/LeapCC/Model
//Fixed bugs and enhancements 0000616-0000620
//
//*****************  Version 51  *****************
//User: Parveen      Date: 7/17/09    Time: 12:30p
//Updated in $/LeapCC/Model
//getCourseResourceList, getTotalCourseResource function added
//
//*****************  Version 50  *****************
//User: Rajeev       Date: 7/15/09    Time: 1:29p
//Updated in $/LeapCC/Model
//Updated code with transaction in admit student if there is an error in
//query
//
//*****************  Version 49  *****************
//User: Jaineesh     Date: 7/14/09    Time: 6:37p
//Updated in $/LeapCC/Model
//modified in queries, delete record student_groups,
//student_optional_subject
//
//*****************  Version 48  *****************
//User: Rajeev       Date: 7/13/09    Time: 6:48p
//Updated in $/LeapCC/Model
//Updated with transactions in admit student
//
//*****************  Version 47  *****************
//User: Rajeev       Date: 7/13/09    Time: 12:36p
//Updated in $/LeapCC/Model
//Updated class name on sachin sir request "Class should be printed as
//BTech CSE-1 Sem, not as 2009-PTU-BTech-CSE-1 SEM"
//
//*****************  Version 46  *****************
//User: Rajeev       Date: 7/13/09    Time: 12:25p
//Updated in $/LeapCC/Model
//Removed Last name validation check as per Sachin sir dated 13thjuly
//
//*****************  Version 45  *****************
//User: Rajeev       Date: 7/09/09    Time: 7:06p
//Updated in $/LeapCC/Model
//Updated student list query in find student
//
//*****************  Version 44  *****************
//User: Ajinder      Date: 7/07/09    Time: 11:18a
//Updated in $/LeapCC/Model
//added following functions for taking care of MBA concept:
//1. hasParentCategory()
//2. getCategorySubjects()
//3. countClassOptionalSubjects()
//4. getOptionalPendingStudents()
//
//*****************  Version 43  *****************
//User: Rajeev       Date: 6/15/09    Time: 7:22p
//Updated in $/LeapCC/Model
//Enhanced "Admin Student" module as mailed by Puspender Sir.
//
//*****************  Version 42  *****************
//User: Ajinder      Date: 6/11/09    Time: 10:51a
//Updated in $/LeapCC/Model
//added function assignOptionalGroup() to save record for students in
//optional subjects.
//
//*****************  Version 41  *****************
//User: Rajeev       Date: 6/09/09    Time: 5:14p
//Updated in $/LeapCC/Model
//Enhanced student list with university roll no, group and removed email
//address from search student
//
//*****************  Version 39  *****************
//User: Jaineesh     Date: 6/09/09    Time: 2:07p
//Updated in $/LeapCC/Model
//replicate of bug Nos.3,4 in cc
//
//*****************  Version 38  *****************
//User: Jaineesh     Date: 6/04/09    Time: 3:32p
//Updated in $/LeapCC/Model
//put new functions for student class/roll no
//
//*****************  Version 37  *****************
//User: Jaineesh     Date: 6/01/09    Time: 3:27p
//Updated in $/LeapCC/Model
//put new functions for student update user name & password
//
//*****************  Version 36  *****************
//User: Ajinder      Date: 5/29/09    Time: 3:03p
//Updated in $/LeapCC/Model
//updated function getActiveMarksTransferredClasses()
//to fetch only current institute classes.
//
//*****************  Version 35  *****************
//User: Ajinder      Date: 5/29/09    Time: 11:37a
//Updated in $/LeapCC/Model
//added functions() for promoting student, making new class active, make
//old class past.
//
//*****************  Version 34  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Model
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 33  *****************
//User: Rajeev       Date: 5/27/09    Time: 6:59p
//Updated in $/LeapCC/Model
//added reference name,blood group, fee receipt no,institute wise search
//for class student previous academic in admit student
//
//*****************  Version 32  *****************
//User: Rajeev       Date: 5/27/09    Time: 4:27p
//Updated in $/LeapCC/Model
//Updated student list query to show all students before assigning group
//
//*****************  Version 31  *****************
//User: Rajeev       Date: 5/18/09    Time: 9:53a
//Updated in $/LeapCC/Model
//Updated Time table format as per the parameter set from Config Paramter
//"TIMETABLE_FORMAT"
//
//*****************  Version 30  *****************
//User: Rajeev       Date: 5/07/09    Time: 7:04p
//Updated in $/LeapCC/Model
//updated marks query
//
//*****************  Version 29  *****************
//User: Ajinder      Date: 5/04/09    Time: 11:23a
//Updated in $/LeapCC/Model
//updated functions for marks transfer.
//added function checkDeletedRollNo() for fixing bug no. 955
//
//*****************  Version 28  *****************
//User: Parveen      Date: 5/02/09    Time: 12:21p
//Updated in $/LeapCC/Model
//getStudentList query update
//
//*****************  Version 27  *****************
//User: Ajinder      Date: 5/02/09    Time: 12:00p
//Updated in $/LeapCC/Model
//updated sc queries for cc.
//
//*****************  Version 26  *****************
//User: Rajeev       Date: 4/09/09    Time: 3:18p
//Updated in $/LeapCC/Model
//added print reports
//
//*****************  Version 25  *****************
//User: Dipanjan     Date: 30/03/09   Time: 16:00
//Updated in $/LeapCC/Model
//Bug Fixed
//
//*****************  Version 24  *****************
//User: Dipanjan     Date: 24/03/09   Time: 11:46
//Updated in $/LeapCC/Model
//Modifed query
//
//*****************  Version 23  *****************
//User: Ajinder      Date: 3/18/09    Time: 12:50p
//Updated in $/LeapCC/Model
//added functions for internal marks transfer
//
//*****************  Version 22  *****************
//User: Ajinder      Date: 3/07/09    Time: 4:43p
//Updated in $/LeapCC/Model
//added functions for group change
//
//*****************  Version 21  *****************
//User: Rajeev       Date: 2/26/09    Time: 4:18p
//Updated in $/LeapCC/Model
//Implemented address verification
//
//*****************  Version 20  *****************
//User: Rajeev       Date: 2/17/09    Time: 4:26p
//Updated in $/LeapCC/Model
//Updated fee head query to fetch single fee head
//
//*****************  Version 19  *****************
//User: Rajeev       Date: 1/05/09    Time: 11:40a
//Updated in $/LeapCC/Model
//added reported by in offense tab
//
//*****************  Version 18  *****************
//User: Rajeev       Date: 12/25/08   Time: 11:25a
//Updated in $/LeapCC/Model
//Updated "student_discipline" table name in offense tab
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 12/24/08   Time: 3:47p
//Updated in $/LeapCC/Model
//Updated Student time table query as per CC functionality
//
//*****************  Version 16  *****************
//User: Rajeev       Date: 12/23/08   Time: 12:57p
//Updated in $/LeapCC/Model
//updated as per CC functionality
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 12/22/08   Time: 5:52p
//Updated in $/LeapCC/Model
//added Offense tab
//
//*****************  Version 14  *****************
//User: Ajinder      Date: 12/22/08   Time: 1:20p
//Updated in $/LeapCC/Model
//added functions for checking if attendance/tests already taken
//
//*****************  Version 13  *****************
//User: Ajinder      Date: 12/18/08   Time: 12:01p
//Updated in $/LeapCC/Model
//added functions for roll no. assignment.
//
//*****************  Version 12  *****************
//User: Parveen      Date: 12/17/08   Time: 2:21p
//Updated in $/LeapCC/Model
//getStudentTime query update
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 12/17/08   Time: 2:14p
//Updated in $/LeapCC/Model
//working...left for praveen
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 12/13/08   Time: 4:32p
//Updated in $/LeapCC/Model
//added functions for student group assignment.
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 12/10/08   Time: 6:34p
//Updated in $/LeapCC/Model
//working on student group allocation
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 12/10/08   Time: 5:47p
//Updated in $/LeapCC/Model
//Updated with Student timetable query
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 12/10/08   Time: 5:46p
//Updated in $/LeapCC/Model
//working on functions for group assignment
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 12/10/08   Time: 3:49p
//Updated in $/LeapCC/Model
//Updated Student Update query
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 12/10/08   Time: 3:48p
//Updated in $/LeapCC/Model
//working on functions for student group assignment.
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 12/10/08   Time: 12:42p
//Updated in $/LeapCC/Model
//Updated with time table and student information query
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/10/08   Time: 12:36p
//Updated in $/LeapCC/Model
//added functions for group assignment
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 10:19a
//Updated in $/LeapCC/Model
//modified as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 57  *****************
//User: Ajinder      Date: 10/17/08   Time: 1:48p
//Updated in $/Leap/Source/Model
//removed following functions, for adding the same into scStudentManager:
//
//1. getClassSubjects()
//2. getMarksDistributionOneSubject()
//3. getSubjectTestTypeTestMarks()
//4. updateMarksTransfer()
//5. countRecords()
//6. addMarks()
//
//*****************  Version 56  *****************
//User: Ajinder      Date: 10/17/08   Time: 1:23p
//Updated in $/Leap/Source/Model
//Added following functions for "marks transfer"
//1. getClassSubjects()
//2. getMarksDistributionOneSubject()
//3. getSubjectTestTypeTestMarks()
//4. updateMarksTransfer()
//5. countRecords()
//6. addMarks()
//
//*****************  Version 55  *****************
//User: Rajeev       Date: 9/16/08    Time: 11:27a
//Updated in $/Leap/Source/Model
//updated functions for fee collection report
//
//*****************  Version 54  *****************
//User: Rajeev       Date: 9/09/08    Time: 3:24p
//Updated in $/Leap/Source/Model
//updated student profile print module
//
//*****************  Version 53  *****************
//User: Rajeev       Date: 9/08/08    Time: 4:51p
//Updated in $/Leap/Source/Model
//updated with duplicate student email address
//
//*****************  Version 52  *****************
//User: Rajeev       Date: 9/08/08    Time: 3:36p
//Updated in $/Leap/Source/Model
//updated formatting
//
//*****************  Version 51  *****************
//User: Ajinder      Date: 9/08/08    Time: 1:48p
//Updated in $/Leap/Source/Model
//fixed coding mistake.
//
//*****************  Version 50  *****************
//User: Rajeev       Date: 9/04/08    Time: 6:30p
//Updated in $/Leap/Source/Model
//updated fixes for student fees receipt
//
//*****************  Version 49  *****************
//User: Rajeev       Date: 9/03/08    Time: 3:10p
//Updated in $/Leap/Source/Model
//updated formatting and spacing
//
//*****************  Version 48  *****************
//User: Rajeev       Date: 9/02/08    Time: 8:06p
//Updated in $/Leap/Source/Model
//updated fee receipt query
//
//*****************  Version 47  *****************
//User: Rajeev       Date: 9/01/08    Time: 4:02p
//Updated in $/Leap/Source/Model
//updated with default display of student attendance, student print
//report
//
//*****************  Version 46  *****************
//User: Rajeev       Date: 8/28/08    Time: 1:09p
//Updated in $/Leap/Source/Model
//updated fee receipt payment report
//
//*****************  Version 45  *****************
//User: Rajeev       Date: 8/27/08    Time: 2:13p
//Updated in $/Leap/Source/Model
//updated fee module
//
//*****************  Version 44  *****************
//User: Rajeev       Date: 8/25/08    Time: 5:31p
//Updated in $/Leap/Source/Model
//updated student detail functions
//
//*****************  Version 41  *****************
//User: Rajeev       Date: 8/22/08    Time: 6:37p
//Updated in $/Leap/Source/Model
//updates fees head query
//
//*****************  Version 40  *****************
//User: Rajeev       Date: 8/22/08    Time: 5:48p
//Updated in $/Leap/Source/Model
//updated print reports
//
//*****************  Version 39  *****************
//User: Rajeev       Date: 8/21/08    Time: 4:10p
//Updated in $/Leap/Source/Model
//updated time table query
//
//*****************  Version 38  *****************
//User: Rajeev       Date: 8/21/08    Time: 2:03p
//Updated in $/Leap/Source/Model
//updated formatting and print reports
//
//*****************  Version 37  *****************
//User: Administrator Date: 8/13/08    Time: 12:06p
//Updated in $/Leap/Source/Model
//
//*****************  Version 36  *****************
//User: Rajeev       Date: 8/11/08    Time: 10:59a
//Updated in $/Leap/Source/Model
//updated the formatting and other issues
//
//*****************  Version 34  *****************
//User: Rajeev       Date: 8/08/08    Time: 11:56a
//Updated in $/Leap/Source/Model
//updated student list query with session and institute id from session
//
//*****************  Version 33  *****************
//User: Ajinder      Date: 8/08/08    Time: 11:46a
//Updated in $/Leap/Source/Model
//added function countUnassignedRollNumbers() for checking if all
//students have been assigned roll numbers or not
//
//*****************  Version 32  *****************
//User: Rajeev       Date: 8/07/08    Time: 2:30p
//Updated in $/Leap/Source/Model
//updated the constraints in admit student module
//
//*****************  Version 31  *****************
//User: Rajeev       Date: 8/06/08    Time: 3:51p
//Updated in $/Leap/Source/Model
//updated query and formatting
//
//*****************  Version 30  *****************
//User: Rajeev       Date: 8/05/08    Time: 6:30p
//Updated in $/Leap/Source/Model
//remove all the demo issues
//
//*****************  Version 29  *****************
//User: Admin        Date: 8/05/08    Time: 11:28a
//Updated in $/Leap/Source/Model
//working on functions made for assigning roll no.s and assigning groups
//
//*****************  Version 28  *****************
//User: Rajeev       Date: 8/02/08    Time: 6:20p
//Updated in $/Leap/Source/Model
//updated schedule query
//
//*****************  Version 27  *****************
//User: Ajinder      Date: 8/01/08    Time: 7:30p
//Updated in $/Leap/Source/Model
//changed prefix to rollNoPrefix in getClassPrefix()
//
//*****************  Version 26  *****************
//User: Rajeev       Date: 7/31/08    Time: 11:37a
//Updated in $/Leap/Source/Model
//reviewed the file
//
//*****************  Version 25  *****************
//User: Ajinder      Date: 7/28/08    Time: 4:14p
//Updated in $/Leap/Source/Model
//added function removeGroupAssignment() for removing all group
//assignment to a particular class.
//
//*****************  Version 24  *****************
//User: Rajeev       Date: 7/28/08    Time: 3:46p
//Updated in $/Leap/Source/Model
//added student sibling function
//
//*****************  Version 23  *****************
//User: Ajinder      Date: 7/28/08    Time: 3:20p
//Updated in $/Leap/Source/Model
//added functions:
//1. updateClassPrefix()  [for updating class prefix]
//2. getClassPrefix() [for fetching class prefix]
//3. getClassRollNo() [for students which have not yet been assigned to
//any group]
//4. updateStudentGroup() [for updating student group]
//
//*****************  Version 22  *****************
//User: Rajeev       Date: 7/25/08    Time: 6:43p
//Updated in $/Leap/Source/Model
//
//*****************  Version 21  *****************
//User: Ajinder      Date: 7/25/08    Time: 6:10p
//Updated in $/Leap/Source/Model
//added countPendingStudents() for checking students for whom groups have
//not been issued yet.
//
//*****************  Version 20  *****************
//User: Rajeev       Date: 7/25/08    Time: 5:46p
//Updated in $/Leap/Source/Model
//worked on attendance module
//
//*****************  Version 19  *****************
//User: Ajinder      Date: 7/25/08    Time: 4:10p
//Updated in $/Leap/Source/Model
//added function checkRollNo()
//
//*****************  Version 18  *****************
//User: Rajeev       Date: 7/25/08    Time: 3:40p
//Updated in $/Leap/Source/Model
//worked on sibling module
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 7/24/08    Time: 6:37p
//Updated in $/Leap/Source/Model
//updated the validations
//
//*****************  Version 16  *****************
//User: Ajinder      Date: 7/24/08    Time: 4:16p
//Updated in $/Leap/Source/Model
//added functions:
//1. countClassStudents()  for assigning roll nos.
//2. getStudents()  for assigning roll nos.
//3. updateStudentRollNo()  for assigning roll nos.
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 7/24/08    Time: 1:06p
//Updated in $/Leap/Source/Model
//added fees receipt functions
//
//*****************  Version 14  *****************
//User: Ajinder      Date: 7/24/08    Time: 12:42p
//Updated in $/Leap/Source/Model
//added function getStudents() for fetching students based on class
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 7/23/08    Time: 10:22a
//Updated in $/Leap/Source/Model
//make new StudentInformationManager in student folder
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 7/21/08    Time: 6:53p
//Updated in $/Leap/Source/Model
//modified in database query
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 7/19/08    Time: 7:10p
//Updated in $/Leap/Source/Model
//updated fees function
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 7/19/08    Time: 7:07p
//Updated in $/Leap/Source/Model
//make new function getStudentInformationList
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 7/18/08    Time: 4:34p
//Updated in $/Leap/Source/Model
//working on student fee receipt module
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 7/17/08    Time: 3:01p
//Updated in $/Leap/Source/Model
//updated with user validations
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/17/08    Time: 12:21p
//Updated in $/Leap/Source/Model
//Add new function to get student information through userid
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 7/16/08    Time: 1:41p
//Updated in $/Leap/Source/Model
//updated student profile with student marks 
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/14/08    Time: 4:55p
//Updated in $/Leap/Source/Model
//updated the attendance functions
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/12/08    Time: 5:25p
//Updated in $/Leap/Source/Model
//made ajax profile ajax based
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/12/08    Time: 2:28p
//Updated in $/Leap/Source/Model
//added add_slahes function during insert
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/11/08    Time: 6:45p
//Updated in $/Leap/Source/Model
//updated the photo upload function
?>

  