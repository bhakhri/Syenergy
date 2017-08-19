<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the CONSOLIDATED FEE DETAILS
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class ConsolidatedFeeDetailsManager {
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
//  This function is used to fetch Study Period
// Author :Nishu Bindal
// Created on : 21-Mar-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    
      public function fetchStudyPeriod($condition=''){
    	$query ="SELECT
    			DISTINCT	
    			s.studyPeriodId,s.periodName 
    		FROM	`study_period` s , `class` c
    		WHERE	c.studyPeriodId  = s.studyPeriodId
    			$condition 
    		ORDER BY s.periodName ASC
    		";
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  This function is used to fetch Payment Details  Of Student Fee
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	public function getFeeDetails($frmCondition='',$frdCondition='',$fhcCondition='',$flCondition=''){
		$query = "
			SELECT 
				sum(a.hostelFees) AS hostelFees , sum(a.transportFees) AS transportFees ,
				 sum(a.totalAcademicFees) AS totalAcademicFees , sum(a.paidAcademicFee) AS paidAcademicFee,
				sum(a.hostelSecurity) AS hostelSecurity,sum(a.debit) AS debit, sum(a.credit) As credit,
				 sum(a.tranportFine) AS tranportFine, sum(a.hostelFine) AS hostelFine , 
				sum(a.academicFine) AS academicFine,
				a.classId,a.studentId,a.branchCode,a.className,
				a.isMigration,a.isLeet,sum(a.hostelFeePaid) AS hostelFeePaid, 
				sum(a.transportFeePaid) AS transportFeePaid, sum(a.concession) AS concession,
			        sum(a.academicFinePaid) AS academicFinePaid,sum(a.hostelFinePaid) AS hostelFinePaid,
				sum(a.transportFinePaid) AS transportFinePaid, sum(a.hostelSecurityPaid) AS hostelSecurityPaid
			FROM(
				SELECT 	
					IFNULL(frm.hostelFees,0) AS hostelFees, IFNULL(frm.transportFees,0) AS transportFees ,
					IFNULL(sum(fri.amount),0) AS totalAcademicFees,'' AS paidAcademicFee, frm.hostelSecurity,
					'' AS debit, '' AS credit,'' AS tranportFine,'' AS hostelFine,
					'' AS academicFine,c.classId,frm.studentId,
					b.branchCode,c.className,s.quotaId,s.isMigration,s.isLeet,'' AS hostelFeePaid,
					 '' AS transportFeePaid,IFNULL(frm.concession,0) AS concession,'' AS academicFinePaid,
					'' AS hostelFinePaid,'' AS transportFinePaid,'' AS hostelSecurityPaid
				FROM  
					class c,`branch` b,`student` s,
					`fee_receipt_master` frm LEFT JOIN `fee_receipt_instrument` fri ON 
					frm.feeReceiptId = fri.feeReceiptId  AND frm.studentId = fri.studentId 
					AND fri.classId = frm.feeClassId  AND frm.status = 1
				WHERE	
					frm.feeClassId = c.classId
					AND	frm.instituteId = c.instituteId
					AND	b.branchId = c.branchId						
					AND	s.studentId = frm.studentId
					AND	frm.status = 1
					$frmCondition
				GROUP BY frm.studentId,frm.feeClassId,fri.feeReceiptId
				
				UNION 
			
				SELECT 	
					'' AS hostelFees , '' AS transportFees , '' AS totalAcademicFees, 
					sum(fhc.feeHeadAmount) AS paidAcademicFee,'' AS hostelSecurity,'' AS debit,
				        '' AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine,
					c.classId,frm.studentId,b.branchCode,c.className,s.quotaId,s.isMigration,
					s.isLeet,'' AS hostelFeePaid, '' AS transportFeePaid,'' AS concession,
					'' AS academicFinePaid,'' AS hostelFinePaid,'' AS transportFinePaid,'' AS hostelSecurityPaid
				FROM  
					class c,`branch` b,`student` s, `fee_receipt_master` frm , `fee_head_collection_new` fhc
				WHERE	
					frm.feeClassId = c.classId
					AND	frm.feeClassId = fhc.classId
					AND	s.studentId = frm.studentId
					AND	frm.studentId = fhc.studentId
					AND	fhc.studentId = s.studentId
					AND	b.branchId = c.branchId						
					AND	frm.status = 1
					AND	fhc.receiptCancellation =0
					$fhcCondition
					$frmCondition
				GROUP BY fhc.feeReceiptId,fhc.studentId,frm.studentId,frm.feeClassId
					
				UNION 
				
				SELECT 
					'' AS hostelFees , '' AS transportFees , '' AS totalAcademicFees, '' AS paidAcademicFee,
					 '' AS hostelSecurity, sum(fl.debit) AS debit, SUM(fl.credit) AS credit,'' AS tranportFine,
					'' AS hostelFine,'' AS academicFine, 				
					c.classId,frm.studentId,b.branchCode,c.className,s.quotaId,s.isMigration,s.isLeet,
					'' AS hostelFeePaid, '' AS transportFeePaid,'' AS concession,'' AS academicFinePaid,
					'' AS hostelFinePaid,'' AS transportFinePaid,'' AS hostelSecurityPaid
				FROM  
					class c,`student` s , `branch` b,`fee_receipt_master` frm , `fee_ledger_debit_credit` fl
				WHERE	
					frm.studentId = s.studentId
					AND	frm.feeClassId = c.classId
					AND	fl.studentId = frm.studentId
					AND	frm.feeClassId = fl.classId
					AND	frm.instituteId  = fl.instituteId 
					AND	b.branchId = c.branchId
					AND	fl.status IN (0,1)							
					AND	frm.status = 1
					$frmCondition
				GROUP BY fl.classId,s.studentId
						
				UNION
				SELECT 
					DISTINCT '' AS hostelFees , '' AS transportFees , '' AS totalAcademicFees, 
					'' AS paidAcademicFee,'' AS hostelSecurity, '' AS debit, 
					'' AS credit, 	frd.tranportFine,frd.hostelFine, frd.academicFine,c.classId, 	
					frm.studentId,b.branchCode,c.className,s.quotaId,s.isMigration,s.isLeet,
					frd.hostelFeePaid,frd.transportFeePaid,
					'' AS concession,frd.academicFinePaid,frd.hostelFinePaid,frd.transportFinePaid,
					frd.hostelSecurity AS hostelSecurityPaid
				FROM	
					fee_receipt_details frd,`branch` b, `fee_receipt_master` frm,class c,`student` s
				WHERE	
					frd.feeReceiptId = frm.feeReceiptId
					AND	frd.studentId = frm.studentId
					AND	frd.classId = frm.feeClassId
					AND	frm.studentId = s.studentId 
					AND	frm.feeClassId = c.classId
					AND	b.branchId = c.branchId
					AND	frd.isDelete = 0
					AND	frm.status = 1						
					$frdCondition
					$frmCondition					
				) AS a
				GROUP BY a.studentId
			";
			
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

}

//History : $

?>
