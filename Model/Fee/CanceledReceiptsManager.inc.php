<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the "FEE HEAD VALUES" Module
//
//
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class CanceledReceiptsManager {
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
    
//-------------------------------------------------------
//  This function is used to fetch all branches
// Author :Nishu Bindal
// Created on : 21-Mar-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    
      public function fetchAllBranches($degreeId){
    	$query ="SELECT
    			DISTINCT	
    			b.branchId,b.branchCode 
    		FROM	`branch` b , `class` c
    		WHERE	c.branchId = b.branchId
    		AND	c.degreeId = $degreeId
    		ORDER BY b.branchCode ASC
    		";
    		
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------
//  This function is used to fetch all batches
// Author :Nishu Bindal
// Created on : 21-Mar-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function fetchAllBatches($condition){
    	$query ="SELECT		DISTINCT b.batchId,b.batchName
    			FROM	`batch` b , `class` c
    			WHERE	c.batchId = b.batchId
    			AND	b.instituteId = c.instituteId
    			$condition
    			ORDER BY b.batchName ASC
    	";
    	
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------
//  This function is used to fetch all Classes
// Author :Nishu Bindal
// Created on : 21-Mar-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function fetchClases($condition = ''){
    	$query ="SELECT		DISTINCT c.classId,c.className
    			FROM	`class` c
    				$condition
    		ORDER BY c.className Asc";
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//-------------------------------------------------------
//  This function is used to fetch Payment Details Count Of Student Fee
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	public function getPaymentHistoryCount($filter){
		$query = "
			SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName, c.className, b.branchName,sp.periodName, s.rollNo,s.regNo, SUM(frd.amount) AS amount, frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo,frd.reason
			FROM	`fee_receipt_master` frm,`fee_receipt_details` frd, `student` s, `class` c, `branch` b, `study_period` sp
				WHERE
						frm.feeReceiptId = frd.feeReceiptId
					AND	frm.feeClassId = frd.classId
					AND	frm.studentId = frd.StudentId
					AND	s.studentId = frd.studentId
					AND	s.studentId = frm.studentId
					AND	frd.classId = c.classId
					AND	frm.feeClassId = c.classId
					AND	b.branchId = c.branchId
					AND	sp.studyPeriodId = c.studyPeriodId
					AND	frm.status = 1
					AND	frd.isDelete = 1
					$filter
					GROUP BY frd.classId,frd.studentId,frd.receiptNo
			";
			
			
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
	
	
	
//-------------------------------------------------------
//  This function is used to fetch Payment Details Of Student Fee
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 public function getPaymentHistoryDetails($filter='',$limit='',$sortOrderBy='',$sortField=''){
       $query = "
            SELECT 
                    frm.feeReceiptId,CONCAT(s.firstName,' ',s.lastName) AS studentName, 
                    c.className, b.branchName,sp.periodName, s.rollNo,s.regNo, frd.reason,
                    SUM(frd.amount) AS amount, frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,
                    frm.feeClassId,s.studentId,frd.receiptNo,frd.installmentNo,fcn.cycleName,
                    IFNULL(SUM(IF(frd.paymentMode=1,frd.amount,0)),'') AS receiveCash,
                    IFNULL(SUM(IF(frd.paymentMode=3,frd.amount,IF(frd.paymentMode=2,frd.amount,0))),'') AS receiveDD,
                    IFNULL(GROUP_CONCAT(DISTINCT CONCAT(bk.bankAbbr,' (',frd.number,' ',DATE_FORMAT(frd.dated,'%d-%b-%y'),')') SEPARATOR ', '),'') AS ddDetail,
                    IFNULL(usr.userName,'') AS userName, 
                    IFNULL(CONCAT(emp.employeeName, ' (',emp.employeeCode,')'),'Admin') AS employeeCodeName
             FROM   
                    `fee_receipt_master` frm,`student` s, `class` c, `branch` b, 
                    `study_period` sp, `fee_cycle_new` fcn, 
                    `fee_receipt_details` frd LEFT JOIN `bank` bk ON frd.bankId = bk.bankId
                    LEFT JOIN `user` usr ON usr.userId = frd.userId
                    LEFT JOIN employee emp ON usr.userId = emp.userId
             WHERE
                    frm.feeReceiptId = frd.feeReceiptId
                    AND    frm.feeClassId = frd.classId
                    AND    frm.studentId = frd.StudentId
                    AND    s.studentId = frd.studentId
                    AND    s.studentId = frm.studentId
                    AND    frd.classId = c.classId
                    AND    frm.feeClassId = c.classId
                    AND    b.branchId = c.branchId
                    AND    sp.studyPeriodId = c.studyPeriodId
                    AND    frm.status = 1
                    AND    frm.feeCycleId = fcn.feeCycleId
                    AND    frd.isDelete = 1
                    $filter
             GROUP BY 
                    frd.classId,frd.studentId,frd.receiptNo
             ORDER BY 
                    $sortField $sortOrderBy $limit
             ";
            
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getPaymentHistoryCountNew($condition=''){
        $query = "
            SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName, c.className, b.branchName,sp.periodName, s.rollNo,s.regNo, SUM(frd.amount) AS amount, frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo,frd.reason
            FROM    `fee_receipt_master` frm,`fee_receipt_details` frd, `student` s, `class` c, `branch` b, `study_period` sp
                WHERE
                        frm.feeReceiptId = frd.feeReceiptId
                    AND    frm.feeClassId = frd.classId
                    AND    frm.studentId = frd.StudentId
                    AND    s.studentId = frd.studentId
                    AND    s.studentId = frm.studentId
                    AND    frd.classId = c.classId
                    AND    frm.feeClassId = c.classId
                    AND    b.branchId = c.branchId
                    AND    sp.studyPeriodId = c.studyPeriodId
                    AND    frm.status = 1
                    AND    frd.isDelete = 1
                    $condition
                    GROUP BY frd.classId,frd.studentId,frd.receiptNo
            ";
            
            
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
	 public function getPaymentHistoryDetailsNew($condition='',$limit='',$sortOrderBy='',$sortField=''){
       $query = "
            SELECT 
                    frm.feeReceiptId,CONCAT(s.firstName,' ',s.lastName) AS studentName, 
                    c.className, b.branchName,sp.periodName, s.rollNo,s.regNo, frd.reason,
                    SUM(frd.amount) AS amount, frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,
                    frm.feeClassId,s.studentId,frd.receiptNo,frd.installmentNo,fcn.cycleName,
                    IFNULL(SUM(IF(frd.paymentMode=1,frd.amount,0)),'') AS receiveCash,
                    IFNULL(SUM(IF(frd.paymentMode=3,frd.amount,IF(frd.paymentMode=2,frd.amount,0))),'') AS receiveDD,
                    IFNULL(GROUP_CONCAT(DISTINCT CONCAT(bk.bankAbbr,' (',frd.number,' ',DATE_FORMAT(frd.dated,'%d-%b-%y'),')') SEPARATOR ', '),'') AS ddDetail,
                    IFNULL(usr.userName,'') AS userName, 
                    IFNULL(CONCAT(emp.employeeName, ' (',emp.employeeCode,')'),'Admin') AS employeeCodeName
             FROM   
                    `fee_receipt_master` frm,`student` s, `class` c, `branch` b, 
                    `study_period` sp, `fee_cycle_new` fcn, 
                    `fee_receipt_details` frd LEFT JOIN `bank` bk ON frd.bankId = bk.bankId
                    LEFT JOIN `user` usr ON usr.userId = frd.userId
                    LEFT JOIN employee emp ON usr.userId = emp.userId
             WHERE
                    frm.feeReceiptId = frd.feeReceiptId
                    AND    frm.feeClassId = frd.classId
                    AND    frm.studentId = frd.StudentId
                    AND    s.studentId = frd.studentId
                    AND    s.studentId = frm.studentId
                    AND    frd.classId = c.classId
                    AND    frm.feeClassId = c.classId
                    AND    b.branchId = c.branchId
                    AND    sp.studyPeriodId = c.studyPeriodId
                    AND    frm.status = 1
                    AND    frm.feeCycleId = fcn.feeCycleId
                    AND    frd.isDelete = 1
                    $condition
             GROUP BY 
                    frd.classId,frd.studentId,frd.receiptNo
             ORDER BY 
                    $sortField $sortOrderBy $limit
             ";
            
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  This function is used to fetch Payment Details Of Student Fee Print
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function getPaymentHistoryDetailsPrint($filter,$sortOrderBy,$sortField){
        $query = "
                SELECT 
                    frm.feeReceiptId,CONCAT(s.firstName,' ',s.lastName) AS studentName, c.className, b.branchName,sp.periodName, 
                    s.rollNo,s.regNo, SUM(frd.amount) AS amount, frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,
                    frm.feeClassId,s.studentId,frd.receiptNo,frd.installmentNo,fcn.cycleName,
                    IFNULL(SUM(IF(frd.paymentMode=1,frd.amount,0)),'') AS receiveCash,frd.reason,
                    IFNULL(SUM(IF(frd.paymentMode=3,frd.amount,IF(frd.paymentMode=2,frd.amount,0))),'') AS receiveDD,
                    IFNULL(GROUP_CONCAT(DISTINCT CONCAT(bk.bankAbbr,' (',frd.number,' ',DATE_FORMAT(frd.dated,'%d-%b-%y'),')') SEPARATOR ', '),'') AS ddDetail,
                    IFNULL(usr.userName,'') AS userName, 
                    IFNULL(CONCAT(emp.employeeName, ' (',emp.employeeCode,')'),'Admin') AS employeeCodeName
            FROM    
                    `fee_receipt_master` frm, `student` s, `class` c, `branch` b, 
                    `study_period` sp, `fee_cycle_new` fcn,
                    `fee_receipt_details` frd LEFT JOIN `bank` bk ON frd.bankId = bk.bankId 
			  LEFT JOIN `user` usr ON usr.userId = frd.userId
                    LEFT JOIN employee emp ON usr.userId = emp.userId
            WHERE
                    frm.feeReceiptId = frd.feeReceiptId
                    AND    frm.feeClassId = frd.classId
                    AND    frm.studentId = frd.StudentId
                    AND    s.studentId = frd.studentId
                    AND    s.studentId = frm.studentId
                    AND    frd.classId = c.classId
                    AND    frm.feeClassId = c.classId
                    AND    b.branchId = c.branchId
                    AND    sp.studyPeriodId = c.studyPeriodId
                    AND    frm.status = 1
                    AND    frm.feeCycleId = fcn.feeCycleId
                    AND    frd.isDelete = 1
                    $filter
                    GROUP BY frd.classId,frd.studentId,frd.receiptNo
             ORDER BY 
                            $sortField $sortOrderBy
            ";
            
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------
//  This function is used to get Student Receipt Details 
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------	
	public function getReceiptDetails($receiptNo,$condition){
		$query = "SELECT 
                        DISTINCT frd.feeReceiptId,frm.bankId,frm.instituteBankAccountNo,frm.concession, frd.paidAt,
                        frm.hostelFees,frm.transportFees,bk.bankAddress, IFNULL(fri.feeReceiptInstrumentId,'') AS feeReceiptInstrumentId, 
                        IFNULL(fri.feeHeadName,'') AS feeHeadName, IFNULL(fri.amount,'') AS amount, 
                        sp.periodName AS feeStudyPeriodName, b.batchYear, br.branchName,d.degreeAbbr,
                        CONCAT(s.firstName,' ',s.lastName) AS studentName,s.regNo,d.degreeName,s.fatherName,bk.bankAbbr, br.branchCode,
                        frm.busRouteId,frm.busStopId,frm.hostelId,frm.hostelRoomId,frm.feeCycleId,s.rollNo,frm.hostelSecurity,
                        frm.receiptGeneratedDate,IFNULL(fhc.feeHeadAmount,0.00) AS paidAmount,
                        frd.receiptNo,d.degreeAbbr,frd.installmentNo, IFNULL(fhn.isSpecial,'') AS isSpecial,
                        c.className, IFNULL(s.studentGender,'M') AS studentGender, c.instituteId, s.rollNo,
                        ins.instituteName, ins.instituteCode, ins.instituteAbbr, ins.instituteLogo, frm.feeReceiptId AS ttFeeReceiptId,
                        frm.studentId, frm.feeClassId
                   FROM    
                        `study_period` sp ,`degree` d,`bank` bk,`batch` b,`branch` br,`student` s,`class` c, institute ins,
                        `fee_receipt_details` frd,
                         `fee_receipt_master` frm LEFT JOIN `fee_receipt_instrument` fri ON
                         ( fri.studentId = frm.studentId AND fri.classId = frm.feeClassId AND fri.feeReceiptId = frm.feeReceiptId)
                         LEFT JOIN `fee_head_collection_new` fhc ON 
                        (fhc.feeReceiptId = fri.feeReceiptId AND    fri.studentId = fhc.studentId AND fri.classId = fhc.classId AND 
                        fri.feeHeadId = fhc.feeHeadId AND fhc.receiptNo = '$receiptNo' AND fhc.receiptCancellation = 0)           
                        LEFT JOIN `fee_head_new` fhn ON
                        ( fri.feeHeadId = fhn.feeHeadId AND fhn.headName = fri.feeHeadName)
                    WHERE    
                        s.studentId = frm.studentId
                        AND c.classId = frm.feeClassId    
                        AND frd.feeReceiptId  = frm.feeReceiptId
                        AND frd.studentId = frm.studentId
                        AND frd.classId = frm.feeClassId
                        AND sp.studyPeriodId = c.studyPeriodId
                        AND c.batchId = b.batchId
                        AND c.degreeId = d.degreeId
                        AND c.branchId = br.branchId
                        AND bk.bankId = frm.bankId
                        AND frd.receiptno = '$receiptNo'
                        AND frm.status = 1
                        AND isDelete = 0
				        $condition ";
				
	            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");			
	}
	
//-------------------------------------------------------
//  This function is used to fetch Payment Details (CASH,DD, cheque) Of Student Fee
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------	
	public function getPaymentDetails($receiptNo,$condition){
		$query = "SELECT
                    	frd.paymentMode,frd.dated,  DATE_FORMAT(dated,'%d-%b-%y') AS dated1,
                        frd.amount,frd.number,frd.hostelSecurity,frd.installmentNo,frd.hostelFeePaid,frd.transportFeePaid,b.bankName,date_format(frd.receiptDate, '%d-%m-%Y' ) AS receiptDate,IFNULL(frd.academicFine,0) AS academicFine,IFNULL(frd.hostelFine,0) AS hostelFine,IFNULL(frd.tranportFine,0) AS tranportFine,IFNULL(frd.academicFinePaid,0) AS academicFinePaid,IFNULL(frd.hostelFinePaid,0) AS hostelFinePaid,IFNULL(frd.transportFinePaid,0) AS transportFinePaid
					FROM `fee_receipt_details` frd LEFT JOIN `bank` b ON b.bankId = frd.bankId
					WHERE	frd.receiptNo = '$receiptNo'
					AND	frd.isDelete = 0
					$condition
					";
					
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
//-------------------------------------------------------
//  This function is used for Logical Delete of feeReceiptDetails data
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function deleteFromReceiptDetails($feeReceiptId,$receiptNo,$reason){
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		
		$query ="UPDATE `fee_receipt_details` 
					SET isDelete = 1,
						reason = '$reason',
						userId = '$userId'
					WHERE	feeReceiptId = '$feeReceiptId'
					AND	receiptNo = '$receiptNo'
					AND	isDelete = 0";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
//-------------------------------------------------------
//  This function is used for Logical Delete of `fee_head_collection_new` data
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------	
	public function deleteFromHeadCollection($feeReceiptId,$receiptNo,$reason){
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$query ="UPDATE `fee_head_collection_new`
					SET	receiptCancellation = 1,
						reason = '$reason',
						userId = '$userId'
					WHERE	feeReceiptId = '$feeReceiptId'
					AND	receiptNo = '$receiptNo'
					AND	receiptCancellation = 0";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
//-------------------------------------------------------
//  This function is used To get Total Amount paid in previous installments
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function getTotalAlreadyPaid($feeReceiptId,$installmentNo){
		$query = "SELECT sum(amount) AS paidAmount
					FROM	`fee_receipt_details`
					WHERE	feeReceiptId = '$feeReceiptId'                                      
					AND	installmentNo < '$installmentNo'
					AND	isDelete = 0";
				
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
	}
	
//-------------------------------------------------------
//  This function is used To get Prev Total Fine in previous installments
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function getTotalPrevFine($feeReceiptId,$installmentNo){
		$query = "SELECT (SUM(a.tranportFine) + sum(a.hostelFine) + sum(a.academicFine)) AS previousFine
				FROM (	SELECT DISTINCT tranportFine,hostelFine,academicFine
						FROM	`fee_receipt_details`
						WHERE	feeReceiptId = '$feeReceiptId'
						AND	installmentNo < '$installmentNo'
						AND	isDelete = 0
				 	) AS a";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
	}
     
     
    public function getStudentFeeDetails($studentId,$classId,$feeReceiptId){
    $query ="SELECT 
                     DISTINCT  frm.feeReceiptId,frm.bankId,frm.instituteBankAccountNo,frm.concession,
                    frm.hostelFees,frm.transportFees,bk.bankAddress, fri.feeReceiptInstrumentId,fri.feeHeadName,
                    fri.amount, sp.periodName AS feeStudyPeriodName, b.batchYear, br.branchName,d.degreeAbbr,
                    CONCAT(s.firstName,' ',s.lastName) AS studentName,s.regNo,d.degreeName,s.fatherName,bk.bankAbbr,
                    frm.busRouteId,frm.busStopId,frm.hostelId,frm.hostelRoomId,frm.feeCycleId,s.rollNo,frm.hostelSecurity,
                    frm.receiptGeneratedDate, c.className, 
                    hs.dateOfCheckIn, hs.dateOfCheckOut, h.hostelName, hr.roomName,
                    brsm.validFrom, brsm.validTo, brn.routeName, bsn.stopName, bsc.cityName 
            FROM    
                `study_period` sp ,`degree` d,`bank` bk,`batch` b,`branch` br,`student` s,`class` c,
                `fee_receipt_master` frm 
                 LEFT JOIN `fee_receipt_instrument` fri ON 
                 (frm.feeReceiptId = fri.feeReceiptId AND frm.studentId = fri.studentId AND    frm.feeClassId = fri.classId)
                 LEFT JOIN hostel_students hs ON hs.studentId = frm.studentId AND hs.classId = frm.feeClassId 
                 LEFT JOIN hostel h ON h.hostelId = frm.hostelId
                 LEFT JOIN hostel_room hr ON hr.hostelRoomId = frm.hostelRoomId
                 LEFT JOIN bus_route_student_mapping brsm ON brsm.studentId = frm.studentId AND brsm.classId = frm.feeClassId 
                 LEFT JOIN bus_route_new brn ON frm.busRouteId = brn.busRouteId
                 LEFT JOIN bus_stop_new bsn ON bsn.busStopId = frm.busStopId
                 LEFT JOIN bus_stop_city bsc ON bsc.busStopCityId = frm.busStopCityId     
            WHERE    
                frm.studentId = '$studentId'
                AND    s.studentId = frm.studentId
                AND    s.classId = frm.currentClassId
                AND    frm.feeReceiptId = $feeReceiptId
                AND    frm.feeClassId = '$classId'
                AND    c.classId = frm.feeClassId
                AND    sp.studyPeriodId = c.studyPeriodId
                AND    c.batchId = b.batchId
                AND    c.degreeId = d.degreeId
                AND    c.branchId = br.branchId
                AND    bk.bankId = frm.bankId
                AND    frm.status = 1";
                
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   } 
    
}

//History : $

?>
