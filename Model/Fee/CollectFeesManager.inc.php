<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Collect Fees Manager" TABLE
// Author :NIshu Bindal
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
                        stu.rollNo, stu.fatherName, cls.classId, cls.instituteId, 
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
                        $tableName stu,
                        class cls,
                        study_period sp
                  WHERE
                        stu.classId = cls.classId
                        AND sp.studyPeriodId = cls.studyPeriodId
                  $condition ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to fetch student Fee Head wise
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    public function getStudentFeeHeadDetail($classId,$quotaId,$isLeet,$studentId,$feeReceiptId) {  
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
	$query = "SELECT
			DISTINCT tt.feeId, tt.feeHeadId, tt.feeHeadAmt, tt.classId, 
			tt.headName, tt.headAbbr, tt.sortingOrder, tt.isSpecial, tt.isConsessionable, tt.isRefundable,tt.paidAmount,tt.isLeet,tt.quotaId
				FROM
				(SELECT
					fhv.feeHeadValueId AS feeId, fhv.feeHeadId, fhv.feeHeadAmount AS feeHeadAmt, fhv.classId, fhv.isLeet, fhv.quotaId,
					fh.headName, fh.headAbbr, fh.sortingOrder, fh.isSpecial, fh.isConsessionable, fh.isRefundable, fi.amount AS paidAmount
					FROM
					fee_head_values_new fhv,fee_head_new fh LEFT JOIN `fee_receipt_instrument` fi ON fi.feeHeadId =  fh.feeHeadId AND fi.feeReceiptId = '$feeReceiptId' 
						AND fi.studentId = '$studentId' AND fi.classId = '$classId'
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
		 			    AND fhv.isLeet = CASE WHEN fhv.isLeet = '$isLeet' THEN '$isLeet' ELSE 2 END  
					UNION
					SELECT
					smc.feeMiscId AS feeId, smc.feeHeadId, smc.charges  AS feeHeadAmt, smc.classId, fh.instituteId,fh.sessionId,
					fh.headName, fh.headAbbr, fh.sortingOrder, fh.isSpecial, fh.isConsessionable, fh.isRefundable,fi.amount AS paidAmount
					FROM fee_head_new fh,student_misc_fee_charges_new smc  LEFT JOIN `fee_receipt_instrument` fi ON fi.feeHeadId =  smc.feeHeadId AND fi.feeReceiptId = '$feeReceiptId' 
						AND fi.studentId = '$studentId' AND fi.classId = '$classId'
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
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to fetch student Details
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    function getStudentFeeDetail($classId,$rollNoRegNo){        
    	$query = "SELECT fc.cycleName,fcm.feeReceiptId,fcm.studentId,fcm.feeClassId,fcm.currentClassId, s.regNo, fcm.hostelId,c.batchId,fcm.feeCycleId,
    			fcm.concession,fcm.hostelFees,fcm.transportFees,fri.feeHeadName,fri.amount, fcm.busRouteId, fcm.busStopId,
    			fcm.hostelRoomId,s.quotaId, c.className,c.branchId,s.quotaId,fri.feeStatus, fcm.hostelFeeStatus, fcm.transportFeeStatus,s.isLeet,concat(s.firstName,' ',s.lastName) AS studentName,s.fatherName,(select cls.className from `class` cls WHERE fcm.feeClassId = cls.classId) AS feeClassName ,(select clas.studyPeriodId from `class` clas WHERE fcm.feeClassId = clas.classId) AS feeStudyPeriodId,fcm.instituteBankAccountNo, b.bankName ,s.rollNo,fcm.hostelSecurity,fri.feeReceiptInstrumentId,
fri.feeHeadId,fcm.instituteId,fhn.isSpecial
    				
    			FROM	`fee_cycle_new` fc,`student` s, `class` c , `bank` b,`fee_receipt_master` fcm LEFT JOIN `fee_receipt_instrument` fri ON fcm.feeReceiptId = fri.feeReceiptId AND fcm.studentId = fri.studentId AND	fcm.feeClassId = fri.classId LEFT JOIN `fee_head_new` fhn ON fri.feeHeadId = fhn.feeHeadId AND fcm.instituteId = fhn.instituteId AND fhn.headName = fri.feeHeadName
    			WHERE	fcm.studentId = s.studentId
    			AND	c.classId = s.classId
    			AND	fcm.bankId = b.bankId
    			AND	fcm.instituteId = c.instituteId 
    			AND	fcm.feeClassId = '$classId'
    			AND	fcm.status = 1
    			AND	fc.feeCycleId = fcm.feeCycleId
    			AND	(s.rollNo = '$rollNoRegNo' OR s.regNo = '$rollNoRegNo')";
    			
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to fetch student PAID FEE Details
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------  
    
   function getStudentAlreadyPaidFeeDetail($classId,$studentId,$feeReceiptId){
    	
       $query ="SELECT 
                        SUM(feeHeadAmount) AS paidAmount, feeHeadId
    			 FROM	
                        fee_head_collection_new
    			 WHERE	
                        studentId = '$studentId'
    			        AND	classId = '$classId'
    			        AND	feeReceiptId = '$feeReceiptId'
    			        AND	receiptCancellation = 0
    			GROUP BY 
                        feeHeadId";
                        
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    function getStudentOnlineAlreadyPaidFeeDetail($classId,$studentId,$feeReceiptId){
        
       $query ="SELECT 
                     SUM(amount) AS paidAmount, isOnlinePayment, feeType
                 FROM    
                     fee_receipt_details
                 WHERE    
                     studentId = '$studentId'
                     AND  classId = '$classId'
                     AND  feeReceiptId = '$feeReceiptId'
                     AND  isDelete = 0
                     AND  isOnlinePayment=1
                GROUP BY 
                     studentId";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
  
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to fetch student LEDGER DEBIT CREDIT
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------    
    
    public function getStudentFeeLedger($studentId='',$feeCycleId='',$feeClassId='',$ledgerTypeId=''){
	  
	    if($ledgerTypeId=='') {
	      $ledgerTypeId='0';  
	    }
	
    	$query = "SELECT debit,credit,comments,status
    			FROM	`fee_ledger_debit_credit`
    			WHERE	classId = '$feeClassId'
    			AND	studentId = '$studentId'
			 AND ledgerTypeId IN ($ledgerTypeId)
    			AND	status <> 3";
    	
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
  
    
     //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to fetch student Hostel Fees
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------
    public function getStudentHostelFee($studentId,$batchId,$feeStudyPeriodId,$feeClassId,$feeReceiptId){
    	$query = "	SELECT 	
    					amount AS roomRent, frm.hostelFees AS paidAmount
    				FROM	 `hostel_fees` hf, `student` s, `hostel_room` hr LEFT JOIN `fee_receipt_master` frm ON frm.hostelId = hr.hostelId 
    					AND frm.hostelRoomId = hr.hostelRoomId AND frm.studentId = '$studentId' AND  frm.feeClassId = '$feeClassId' AND frm.status = 1 AND feeReceiptId = '$feeReceiptId' 
    				WHERE	hf.hostelId = hr.hostelId
    				AND	s.hostelId = hf.hostelId
    				AND	hr.hostelId = s.hostelId 
    				AND	s.hostelRoomId = hr.hostelRoomId
    				AND	hf.roomTypeId = hr.hostelRoomTypeId
    				AND	s.studentId = '$studentId'
    				AND	hf.studyPeriodId = '$feeStudyPeriodId'
    				AND	batchId = '$batchId'
    				";
  
    	 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
        //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to GET STUDENT TRANSPORTAION FEES
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    public function getStudentTransportFee($studentId,$batchId,$feeStudyPeriodId,$feeClassId,$feeReceiptId){
    	$query ="SELECT 	bf.amount AS transportFee,brsm.busStopId,brsm.busRouteId , frm.transportFees AS paidAmount
    			FROM 	`bus_route_student_mapping` bsm, `bus_fees` bf,`bus_stop_new` bsn,`bus_route_stop_mapping` brsm LEFT JOIN `fee_receipt_master` frm ON
    					frm.busRouteId = brsm.busRouteId AND brsm.busStopId = frm.busStopId AND frm.studentId = '$studentId' 
    					AND frm.feeClassId  = '$feeClassId' AND frm.feeReceiptId ='$feeReceiptId' AND frm.status =1	
    			WHERE	bsm.studentId = '$studentId'
    			AND	bsm.busRouteStopMappingId = brsm.busRouteStopMappingId
    			AND	brsm.busStopId = bsn.busStopId
    			AND	bsn.busStopCityId = bf.busStopCityId
    			AND	brsm.busRouteId = bf.busRouteId
    			AND	bf.batchId = '$batchId'
    			AND	bf.studyPeriodId = '$feeStudyPeriodId'
    			";
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
                //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to fetch student Fee Concession
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
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
                       AND	acm.sessionId = '$sessionId'
                  $condition
                        ";
          
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to Delete FEE HEAD INSTRUMENT
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
    public function deleteFromFeeReceiptInstrument($feeReceiptId,$studentId,$feeClassId,$feeHeadId){
    	$query ="DELETE 
    			FROM	`fee_receipt_instrument` 
    			WHERE	feeReceiptId = '$feeReceiptId'
    			AND	studentId = '$studentId'
    			AND	classId = '$feeClassId'
    			AND	feeHeadId IN ($feeHeadId)";
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to INSERT FEE HEAD INSTRUMENT
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
    public function updateFeeReceiptInstrument($feeReceiptId,$studentId,$feeClassId){
    	$query = "UPDATE `fee_receipt_instrument` 
    				SET	feeStatus  = 1 
    				WHERE	feeReceiptId = '$feeReceiptId'
    				AND	studentId = '$studentId'
    				AND	classId = '$feeClassId'
    				";
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to update Fee of Academic
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------  
    public function updateMasterTable($bankScrollNo,$feeDate,$feeReceiptId,$feeCycle,$concession){
    	global $sessionHandler;
        $userId = $sessionHandler->getSessionVariable('UserId');
        
    	$query ="UPDATE `fee_receipt_master`
    			SET	dateOfPaymentForAcademic  = '$feeDate',
    				bankScrollNoForAcademic  = '$bankScrollNo',
    				feeCycleId = '$feeCycle',
    				concession  = '$concession',
    				userId = '$userId'         
    				WHERE feeReceiptId = '$feeReceiptId'";
    				
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
     
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to update Fee of Hostel,Academic AND Transport
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
    public function updateFeeReceiptMaster($feeReceiptId,$updateData=''){
    	if($updateData !=''){
    		$updateData .=", ";
    	}
  	global $sessionHandler;
        $userId = $sessionHandler->getSessionVariable('UserId');
        
    	$query ="UPDATE `fee_receipt_master`
    			SET	
    				$updateData
    				userId = '$userId',
                    isAcademicPaid=1
    				WHERE feeReceiptId = '$feeReceiptId'";
    			
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function insertIntoFeeHeadCollection($values){
    	$query ="INSERT INTO `fee_head_collection_new` (feeHeadCollectionId,dateOfEntry,feeReceiptId,installmentNo,receiptNo,receiptDate,studentId,classId,feeHeadId,feeHeadAmount,receiptCancellation,userId)
    	VALUES $values ";
    	
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to update Fee of Hostel
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
    public function updateHostelFees($feeReceiptId){
    	global $sessionHandler;
        $userId = $sessionHandler->getSessionVariable('UserId');
        
    	$query ="UPDATE `fee_receipt_master`
    			SET	hostelFeeStatus = 1,
    				userId = '$userId',
                    isHostelPaid = 1
    				WHERE feeReceiptId = '$feeReceiptId'";
    			
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to update Fee of Fee Ledger
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------  
    public function updateFeeLedger($studentId,$classId,$feeCycleId){
    	$query = "UPDATE `fee_ledger_debit_credit`
    			SET	status = 1
    			WHERE	feeCycleId = '$feeCycleId'
    			AND	studentId = '$studentId'
    			AND	classId = '$classId'
    			AND	status <> 3";
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
    //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to update Fee of Hostel
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
    public function updateTransportFees($feeReceiptId){
    	global $sessionHandler;
        $userId = $sessionHandler->getSessionVariable('UserId');
        
    	$query ="UPDATE `fee_receipt_master`
    			SET	
    				transportFeeStatus = 1,
                    isTransportPaid = 1,
    				userId = '$userId'
    				WHERE feeReceiptId = '$feeReceiptId'";
    			
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to Delete Fee Receipt Details
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
    public function deleteFeeReceiptDetails($feeReceiptId,$studentId,$feeClassId,$feeType){
    	$query = "DELETE 
    				FROM	`fee_receipt_details` 
    				WHERE	feeReceiptId= '$feeReceiptId'
    				AND	studentId  = '$studentId'
    				AND	classId   = '$feeClassId'
    				AND	feeType  = '$feeType'";
    	
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);	
    }
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to insert into Fee Receipt Details
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
    public function insertIntoFeeReceiptDetails($values){
    	$query = "INSERT INTO `fee_receipt_details` 
        (feeReceiptDetailId,feeReceiptId,studentId,classId,paymentMode,bankId,dated,amount,number,feeType,receiptNo,receiptDate,paidAt,installmentNo,academicFeePaid,hostelFeePaid,transportFeePaid,hostelSecurity,isDelete,userId,tranportFine,hostelFine,academicFine,academicFinePaid,hostelFinePaid,transportFinePaid) 
    				VALUES $values";
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to Get Fee Receipt Details
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------  
    public function getPaymentDetails($feeReceiptId,$studentId,$classId,$feeType){
    	$query = "SELECT fr.paymentMode,fr.bankId,fr.dated,fr.amount,fr.number,fr.feeType,b.bankAbbr,fr.receiptNo,fr.receiptDate,fr.installmentNo
    				FROM	`fee_receipt_details` fr left join  `bank` b on  fr.bankId = b.bankId 
    				WHERE	fr.feeReceiptId = '$feeReceiptId'
    				AND	fr.studentId = '$studentId'
    				AND	fr.classId = '$classId'
    				AND	fr.feeType = '$feeType'
    				ORDER BY fr.paymentMode ASC
    				";
    				
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to Get ALready Paid Fees
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------  
    public function getAlreadyPaidFee($classId,$studentId,$feeReceiptId){
    	$query = "SELECT	sum(a.academicFeePaid) AS academicFeePaid,sum(a.hostelFeePaid) AS hostelFeePaid,sum(a.transportFeePaid) AS transportFeePaid, 
    				sum(a.hostelSecurity) AS hostelSecurity,max(a.installmentNo) AS installmentNo, sum(a.academicFinePaid) AS academicFinePaid, sum(a.hostelFinePaid) AS hostelFinePaid,sum(a.transportFinePaid) AS transportFinePaid,sum(a.academicFine) AS academicFine,sum(a.hostelFine) AS hostelFine,sum(a.tranportFine) AS tranportFine
    			FROM	
    				(
    					SELECT DISTINCT academicFeePaid,hostelFeePaid,transportFeePaid,hostelSecurity,installmentNo,receiptNo,academicFinePaid,hostelFinePaid,transportFinePaid,academicFine,hostelFine,tranportFine
    					FROM	`fee_receipt_details`
		    			WHERE	studentId = '$studentId'
		    			AND	classId = '$classId'
		    			AND	feeReceiptId = '$feeReceiptId'
		    			AND	isDelete = 0
		    			GROUP BY studentId,classId,installmentNo,receiptNo
	    			) AS a";
	
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to Check if Receipt No Already exists
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------  
    public function checkReceiptNo($receiptNo){
    	$query = "SELECT count(feeReceiptDetailId) AS cnt
    				FROM	`fee_receipt_details`  
    				WHERE	receiptNo = '$receiptNo'
    				AND	isDelete = 0
    				";
    				
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to Check if Installment No Already exists
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------    
    public function checkInstallmentNo($feeReceiptId,$studentId,$feeClassId,$installmentNo){
    	$query ="SELECT	count(feeReceiptDetailId) AS cnt
    			FROM	fee_receipt_details
    			WHERE	feeReceiptId = '$feeReceiptId'
    			AND	classId = '$feeClassId'
    			AND	studentId = '$studentId'
    			AND	installmentNo = '$installmentNo'
    			AND	isDelete = 0";
    
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to DELETE FEE RECEIPT LOGICALLY 
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------   
    public function changeFeeStatus($feeReceiptId,$reasonForDelete,$update =''){
    	  global $sessionHandler;
        $userId = $sessionHandler->getSessionVariable('UserId');
    
    	$query = "UPDATE	`fee_receipt_master`
    				SET	$update
    					status = '0',
    					reasonForDelete = '$reasonForDelete',
    					userId = '$userId'
    				WHERE	feeReceiptId ='$feeReceiptId '";
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to RE insert the basic columns as inserted in instrument
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------     
    
    public function insertDataInFeeReceiptInstrument($feeReceiptId,$newReceiptId){
    	$query = "INSERT INTO `fee_receipt_instrument` 
    				(feeReceiptInstrumentId,feeReceiptId,studentId,classId,feeHeadId,feeHeadName,amount,feeStatus)
    				SELECT 
    				'','$newReceiptId',studentId,classId,feeHeadId,feeHeadName,amount,0
    				FROM `fee_receipt_instrument` WHERE feeReceiptId = '$feeReceiptId'
    		";
    	
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    

    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to Update the Fee Receipt Details 
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------       
    public function updateReceiptDetails($feeReceiptId,$type,$newReceiptId){
    	$query = "UPDATE	`fee_receipt_details`
    				SET	feeReceiptId = $newReceiptId
    				WHERE	feeType NOT IN($type)
    				AND	feeReceiptId = '$feeReceiptId'";
    	
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    

 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to Change status to delete of Fee Ledger
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
    public function changeStatusOfFeeLedger($feeCycleId,$classId,$studentId){
    	global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $userId = $sessionHandler->getSessionVariable('UserId');
        
        $query = "UPDATE `fee_ledger_debit_credit`
    			SET	status = 3, userId = '$userId'
    			WHERE	feeCycleId = '$feeCycleId'
    			AND	studentId = '$studentId'
    			AND	classId = '$classId'
    			AND	status = 1";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to RE insert the Ledger Enterys  columns 
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
    public function insertIntoLedger($feeCycleId,$classId,$studentId){
    	global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $userId = $sessionHandler->getSessionVariable('UserId');
         
        $query ="INSERT INTO `fee_ledger_debit_credit` (feeLedgerDebitCreditId,classId,studentId,feeCycleId,comments,date,debit,credit,status,userId,instituteId)
        		SELECT '',classId,studentId,feeCycleId,comments,date,debit,credit,'0','$userId',instituteId
        				FROM	`fee_ledger_debit_credit`
        				WHERE	studentId ='$studentId'
        				AND	feeCycleId = '$feeCycleId'
        				AND	classId = '$classId'
        				AND	status = 1
        				AND	instituteId = '$instituteId'";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to Change the status of hostel security
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
    public function updateHostelSecurityStatus($studentId,$hostelRoomId,$update =''){
    	global $sessionHandler;
        $userId = $sessionHandler->getSessionVariable('UserId');
    	
    	$query = "UPDATE `hostel_students` 
    			SET	
    				modifyOnDate = now(),
    				userId = '$userId'
    				$update
    			WHERE	studentId = '$studentId'
    			AND	hostelRoomId = '$hostelRoomId'
    			AND	dateOfCheckOut = '0000-00-00'";
    			
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to Fetch student classes 
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------   
     public function fetchClases($rollNo = '',$migrationStudyPeriod=''){
       
       if($migrationStudyPeriod=='') {
          $migrationStudyPeriod=0; 
       }  
         
    	$query ="SELECT classId,className FROM class cls,study_period sp
			WHERE 
				cls.studyPeriodId = sp.studyPeriodId AND
				CONCAT(degreeId,'~',batchId,'~',branchId) LIKE
				(SELECT 
					DISTINCT CONCAT(cc.degreeId,'~',cc.batchId,'~',cc.branchId) 
				 FROM 
					student s, class cc 
				 WHERE 
					cc.classId = s.classId AND (s.rollNo LIKE '$rollNo' OR s.regNo LIKE '$rollNo'  OR s.universityRollNo LIKE '$rollNo'))
			AND sp.periodValue >=$migrationStudyPeriod

			    		ORDER BY className Asc";
    		
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    	
    }
    
 //--------------------------------------------------------------------------------
// THIS FUNCTION fetch last receipt No 
// Author :Nishu Bindal
// Created on : (10.05.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------    
    public function getLastEntry() {
    
        $query = "SELECT 
                           feeReceiptDetailId, receiptDate, receiptNo,paidAt,date_format(receiptDate, '%Y-%m-%d' ) AS receiptDated
                  FROM       
                           `fee_receipt_details`
                  WHERE 
                           isDelete = 0         
                  ORDER BY
                           feeReceiptDetailId 	DESC    
                  LIMIT 0,1"; 
                        
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
 //--------------------------------------------------------------------------------
// THIS FUNCTION fetch Misc Fee Head
// Author :Nishu Bindal
// Created on : (10.05.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getMiscFeeHead($instituteId){	
    	$query = "SELECT feeHeadId,headName
    			FROM	`fee_head_new`
    			WHERE	isSpecial = 1
    			AND	instituteId = '$instituteId'";
    	 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT DATA IN FEE RECEIPT MASTER TABLE IN CASE OF MISC HEAD
// Author :Nishu Bindal
// Created on : (10.05.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------  
    public function insertIntoFeeReceiptInstrument($values){
    	$query = "INSERT INTO `fee_receipt_instrument` (feeReceiptInstrumentId,feeReceiptId,studentId,classId,feeHeadId,feeHeadName,amount)
    					VALUES $values";
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
     public function getReceiptMax() {
        
        $query = "SELECT 
                      MAX(CAST(SUBSTRING(receiptNo,12) AS UNSIGNED)) AS receiptNo 
                  FROM 
                      `fee_receipt_details`  
                  WHERE
                      paidAt=2 AND SUBSTRING(receiptNo,1,11) LIKE 'CUPB/12-13/'    
                  ORDER BY 
                      receiptNo DESC ";
                      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    
    public function getSearchStudent($rollNoRegNo='') {
        
        $query = "SELECT 
                      DISTINCT studentId
                  FROM 
                      student
                  WHERE
                     (rollNo = '$rollNoRegNo' OR regNo = '$rollNoRegNo') ";
                      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

	public function getStudentMigrationCheck($rollNoRegNo='') {
    
        $query = "SELECT 
			            st.studentId,st.classId,st.isMigration,st.migrationStudyPeriod,st.migrationClassId, st.isLeet                        
                   FROM 
                       student st 
                   WHERE 
                     	(st.rollNo = '$rollNoRegNo' OR st.regNo = '$rollNoRegNo')  ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

}
?>
