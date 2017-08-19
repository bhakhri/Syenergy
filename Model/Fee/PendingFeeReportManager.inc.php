<?php 
//-------------------------------------------------------
// This File contains Bussiness Logic of the "FEE HEAD VALUES" Module/
// Created on : 16-April-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class PendingFeeReportManager {
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
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    
      public function fetchAllBranches($degreeId){
      	 global $sessionHandler;
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
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function fetchAllBatches($condition){
    	 global $sessionHandler;
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
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	public function getPendingFeeCount($classId){
		$query = "
			SELECT (sum(a.totalFees) + sum(a.hostelSecurity) + sum(a.debit) + sum(a.tranportFine) + sum(a.hostelFine) + sum(a.academicFine)) AS totalFees, (sum(a.paidAmount) + sum(a.credit)) AS paidAmount , a.studentName, a.rollNo, a.regNo,a.branchName,a.periodName
			FROM (
				SELECT 	((IFNULL(frm.hostelFees,0) + IFNULL(frm.transportFees,0) + IFNULL(sum(fri.amount),0)) - IFNULL(frm.concession,0)) AS totalFees, '' AS paidAmount , CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity,'' AS debit, '' AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
						FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm LEFT JOIN `fee_receipt_instrument` fri ON frm.feeReceiptId = fri.feeReceiptId 
							AND frm.studentId = fri.studentId AND	fri.classId = frm.feeClassId AND fri.classId = '$classId' AND	frm.status = 1
						WHERE	frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId
						AND	frm.feeClassId = '$classId'
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
					GROUP BY frm.studentId,frm.feeClassId,fri.feeReceiptId
				
				UNION 
			
				SELECT 	'' AS totalFees, sum(frd.amount) AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity,'' AS debit, '' AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
						FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm ,`fee_receipt_details` frd 
						WHERE	frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId
						AND	frm.feeClassId = frd.classId 
						AND	frd.classId ='$classId' 
						AND	frm.feeReceiptId = frd.feeReceiptId 
						AND	frd.studentId = frm.studentId
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
						AND	frd.isDelete = 0
					GROUP BY frm.studentId,frm.feeClassId,frd.feeReceiptId
					
					UNION 
				
					SELECT 	'' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo ,b.branchName,sp.periodName, hostelSecurity ,'' AS debit, '' AS credit ,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
						FROM  class c,`branch` b, `study_period` sp,`student` s,`fee_receipt_master` frm
						WHERE		frm.studentId = s.studentId 
							AND	frm.feeClassId = c.classId
							AND	frm.feeClassId = '$classId'
							AND	b.branchId = c.branchId
							AND	sp.studyPeriodId = c.studyPeriodId
							AND	frm.status = 1
				
				UNION 
				
					SELECT 	'' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity, sum(fl.debit) AS debit, SUM(fl.credit) AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
							FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm , `fee_ledger_debit_credit` fl
							WHERE	frm.studentId = s.studentId
							AND	frm.feeClassId = c.classId
							AND	frm.feeClassId = '$classId'
							AND	fl.studentId = frm.studentId
							AND	frm.feeClassId = fl.classId
							AND	frm.instituteId  = fl.instituteId 
							AND	b.branchId = c.branchId
							AND	fl.status IN (0,1)
							AND	sp.studyPeriodId = c.studyPeriodId
							AND	frm.status = 1
						GROUP BY fl.studentId,fl.classId,frm.feeReceiptId
						
				UNION
					SELECT 
							DISTINCT '' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity, '' AS debit, '' AS credit,frd.tranportFine,frd.hostelFine,frd.academicFine
						FROM	fee_receipt_details frd, `fee_receipt_master` frm,class c,`branch` b, `study_period` sp,`student` s
						WHERE	frd.feeReceiptId = frm.feeReceiptId
						AND	frd.studentId = frm.studentId
						AND	frd.classId = frm.feeClassId
						AND	frm.studentId = s.studentId 
						AND	frm.feeClassId = c.classId
						AND	frm.feeClassId = '$classId'
						AND	frd.isDelete = 0
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
						
							
				) AS a
				GROUP BY a.periodName,a.branchName,a.regNo,a.rollNo
				HAVING (sum(a.totalFees) + sum(a.hostelSecurity) + sum(a.debit)  + sum(a.tranportFine) + sum(a.hostelFine) + sum(a.academicFine)) > (sum(a.paidAmount) + sum(a.credit))
				
				
			";
			
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	


//-------------------------------------------------------
//  This function is used to fetch Payment Details Of Student Fee
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	public function getPendingFeeDetails($classId,$limit,$sortOrderBy,$sortField){
		$query = "
		SELECT (sum(a.totalFees) + sum(a.hostelSecurity) + sum(a.debit) + sum(a.tranportFine) + sum(a.hostelFine) + sum(a.academicFine)) AS totalFees, (sum(a.paidAmount) + sum(a.credit)) AS paidAmount , a.studentName, a.rollNo, a.regNo,a.branchName,a.periodName
			FROM (
				SELECT 	((IFNULL(frm.hostelFees,0) + IFNULL(frm.transportFees,0) + IFNULL(sum(fri.amount),0)) - IFNULL(frm.concession,0)) AS totalFees, '' AS paidAmount , CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity,'' AS debit, '' AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
						FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm LEFT JOIN `fee_receipt_instrument` fri ON frm.feeReceiptId = fri.feeReceiptId 
							AND frm.studentId = fri.studentId AND	fri.classId = frm.feeClassId AND fri.classId = '$classId' AND	frm.status = 1
						WHERE	frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId
						AND	frm.feeClassId = '$classId'
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
					GROUP BY frm.studentId,frm.feeClassId,fri.feeReceiptId
				
				UNION 
			
				SELECT 	'' AS totalFees, sum(frd.amount) AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity,'' AS debit, '' AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
						FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm ,`fee_receipt_details` frd 
						WHERE	frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId
						AND	frm.feeClassId = frd.classId 
						AND	frd.classId ='$classId' 
						AND	frm.feeReceiptId = frd.feeReceiptId 
						AND	frd.studentId = frm.studentId
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
						AND	frd.isDelete = 0
					GROUP BY frm.studentId,frm.feeClassId,frd.feeReceiptId
					
					UNION 
				
					SELECT 	'' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo ,b.branchName,sp.periodName, hostelSecurity ,'' AS debit, '' AS credit ,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
						FROM  class c,`branch` b, `study_period` sp,`student` s,`fee_receipt_master` frm
						WHERE		frm.studentId = s.studentId 
							AND	frm.feeClassId = c.classId
							AND	frm.feeClassId = '$classId'
							AND	b.branchId = c.branchId
							AND	sp.studyPeriodId = c.studyPeriodId
							AND	frm.status = 1
				
				UNION 
				
					SELECT 	'' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity, sum(fl.debit) AS debit, SUM(fl.credit) AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
							FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm , `fee_ledger_debit_credit` fl
							WHERE	frm.studentId = s.studentId
							AND	frm.feeClassId = c.classId
							AND	frm.feeClassId = '$classId'
							AND	fl.studentId = frm.studentId
							AND	frm.feeClassId = fl.classId
							AND	frm.instituteId  = fl.instituteId 
							AND	b.branchId = c.branchId
							AND	fl.status IN (0,1)
							AND	sp.studyPeriodId = c.studyPeriodId
							AND	frm.status = 1
						GROUP BY fl.studentId,fl.classId,frm.feeReceiptId
						
				UNION
					SELECT 
							DISTINCT '' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity, '' AS debit, '' AS credit,frd.tranportFine,frd.hostelFine,frd.academicFine
						FROM	fee_receipt_details frd, `fee_receipt_master` frm,class c,`branch` b, `study_period` sp,`student` s
						WHERE	frd.feeReceiptId = frm.feeReceiptId
						AND	frd.studentId = frm.studentId
						AND	frd.classId = frm.feeClassId
						AND	frm.studentId = s.studentId 
						AND	frm.feeClassId = c.classId
						AND	frm.feeClassId = '$classId'
						AND	frd.isDelete = 0
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
						
							
				) AS a
				GROUP BY a.periodName,a.branchName,a.regNo,a.rollNo
				HAVING (sum(a.totalFees) + sum(a.hostelSecurity) + sum(a.debit)  + sum(a.tranportFine) + sum(a.hostelFine) + sum(a.academicFine)) > (sum(a.paidAmount) + sum(a.credit))
				ORDER BY  $sortField $sortOrderBy $limit
			";
			
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    
    public function getPendingFeeCountNew($condition='', $classId=''){
        $query = "
            SELECT (sum(a.totalFees) + sum(a.hostelSecurity) + sum(a.debit) + sum(a.tranportFine) + sum(a.hostelFine) + sum(a.academicFine)) AS totalFees, (sum(a.paidAmount) + sum(a.credit)) AS paidAmount , a.studentName, a.rollNo, a.regNo,a.branchName,a.periodName
            FROM (
                SELECT     ((IFNULL(frm.hostelFees,0) + IFNULL(frm.transportFees,0) + IFNULL(sum(fri.amount),0)) - IFNULL(frm.concession,0)) AS totalFees, '' AS paidAmount , CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity,'' AS debit, '' AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
                        FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm LEFT JOIN `fee_receipt_instrument` fri ON frm.feeReceiptId = fri.feeReceiptId 
                            AND frm.studentId = fri.studentId AND    fri.classId = frm.feeClassId AND fri.classId = '$classId' AND    frm.status = 1
                        WHERE    frm.studentId = s.studentId
                        AND    frm.feeClassId = c.classId
                        AND    b.branchId = c.branchId
                        AND    sp.studyPeriodId = c.studyPeriodId
                        AND    frm.status = 1
                        $condition
                    GROUP BY frm.studentId,frm.feeClassId,fri.feeReceiptId
                
                UNION 
            
                SELECT     '' AS totalFees, sum(frd.amount) AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity,'' AS debit, '' AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
                        FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm ,`fee_receipt_details` frd 
                        WHERE    frm.studentId = s.studentId
                        AND    frm.feeClassId = c.classId
                        AND    frm.feeClassId = frd.classId 
                        AND    frm.feeReceiptId = frd.feeReceiptId 
                        AND    frd.studentId = frm.studentId
                        AND    b.branchId = c.branchId
                        AND    sp.studyPeriodId = c.studyPeriodId
                        AND    frm.status = 1
                        AND    frd.isDelete = 0
                    GROUP BY frm.studentId,frm.feeClassId,frd.feeReceiptId
                    $condition
                    UNION 
                
                    SELECT     '' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo ,b.branchName,sp.periodName, hostelSecurity ,'' AS debit, '' AS credit ,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
                        FROM  class c,`branch` b, `study_period` sp,`student` s,`fee_receipt_master` frm
                        WHERE        frm.studentId = s.studentId 
                            AND    frm.feeClassId = c.classId
                            AND    b.branchId = c.branchId
                            AND    sp.studyPeriodId = c.studyPeriodId
                            AND    frm.status = 1
                            $condition
                UNION 
                
                    SELECT     '' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity, sum(fl.debit) AS debit, SUM(fl.credit) AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
                            FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm , `fee_ledger_debit_credit` fl
                            WHERE    frm.studentId = s.studentId
                            AND    frm.feeClassId = c.classId
                            AND    fl.studentId = frm.studentId
                            AND    frm.feeClassId = fl.classId
                            AND    frm.instituteId  = fl.instituteId 
                            AND    b.branchId = c.branchId
                            AND    fl.status IN (0,1)
                            AND    sp.studyPeriodId = c.studyPeriodId
                            AND    frm.status = 1
                            $condition
                        GROUP BY fl.studentId,fl.classId,frm.feeReceiptId
                        
                UNION
                    SELECT 
                            DISTINCT '' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity, '' AS debit, '' AS credit,frd.tranportFine,frd.hostelFine,frd.academicFine
                        FROM    fee_receipt_details frd, `fee_receipt_master` frm,class c,`branch` b, `study_period` sp,`student` s
                        WHERE    frd.feeReceiptId = frm.feeReceiptId
                        AND    frd.studentId = frm.studentId
                        AND    frd.classId = frm.feeClassId
                        AND    frm.studentId = s.studentId 
                        AND    frm.feeClassId = c.classId
                        AND    frd.isDelete = 0
                        AND    b.branchId = c.branchId
                        AND    sp.studyPeriodId = c.studyPeriodId
                        AND    frm.status = 1
                        $condition
                        
                            
                ) AS a
                GROUP BY a.periodName,a.branchName,a.regNo,a.rollNo
                HAVING (sum(a.totalFees) + sum(a.hostelSecurity) + sum(a.debit)  + sum(a.tranportFine) + sum(a.hostelFine) + sum(a.academicFine)) > (sum(a.paidAmount) + sum(a.credit))
                
                
            ";
            
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
	public function getPendingFeeDetailsNew($condition='', $classId='',$limit='',$sortOrderBy,$sortField){
		$query = "
		SELECT (sum(a.totalFees) + sum(a.hostelSecurity) + sum(a.debit) + sum(a.tranportFine) + sum(a.hostelFine) + sum(a.academicFine)) AS totalFees, (sum(a.paidAmount) + sum(a.credit)) AS paidAmount , a.studentName, a.rollNo, a.regNo,a.branchName,a.periodName
			FROM (
				SELECT 	((IFNULL(frm.hostelFees,0) + IFNULL(frm.transportFees,0) + IFNULL(sum(fri.amount),0)) - IFNULL(frm.concession,0)) AS totalFees, '' AS paidAmount , CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity,'' AS debit, '' AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
						FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm LEFT JOIN `fee_receipt_instrument` fri ON frm.feeReceiptId = fri.feeReceiptId 
							AND frm.studentId = fri.studentId AND	fri.classId = frm.feeClassId AND fri.classId = '$classId' AND	frm.status = 1
						WHERE	frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
						$condition
					GROUP BY frm.studentId,frm.feeClassId,fri.feeReceiptId
				
				UNION 
			
				SELECT 	'' AS totalFees, sum(frd.amount) AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity,'' AS debit, '' AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
						FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm ,`fee_receipt_details` frd 
						WHERE	frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId
						AND	frm.feeClassId = frd.classId 
						AND	frm.feeReceiptId = frd.feeReceiptId 
						AND	frd.studentId = frm.studentId
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
						AND	frd.isDelete = 0
						$condition
					GROUP BY frm.studentId,frm.feeClassId,frd.feeReceiptId
						
					UNION 
				
					SELECT 	'' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo ,b.branchName,sp.periodName, hostelSecurity ,'' AS debit, '' AS credit ,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
						FROM  class c,`branch` b, `study_period` sp,`student` s,`fee_receipt_master` frm
						WHERE		frm.studentId = s.studentId 
							AND	frm.feeClassId = c.classId
							AND	b.branchId = c.branchId
							AND	sp.studyPeriodId = c.studyPeriodId
							AND	frm.status = 1
							$condition
				UNION 
				
					SELECT 	'' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity, sum(fl.debit) AS debit, SUM(fl.credit) AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
							FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm , `fee_ledger_debit_credit` fl
							WHERE	frm.studentId = s.studentId
							AND	frm.feeClassId = c.classId
							AND	fl.studentId = frm.studentId
							AND	frm.feeClassId = fl.classId
							AND	frm.instituteId  = fl.instituteId 
							AND	b.branchId = c.branchId
							AND	fl.status IN (0,1)
							AND	sp.studyPeriodId = c.studyPeriodId
							AND	frm.status = 1
							$condition
						GROUP BY fl.studentId,fl.classId,frm.feeReceiptId
						
				UNION
					SELECT 
							DISTINCT '' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity, '' AS debit, '' AS credit,frd.tranportFine,frd.hostelFine,frd.academicFine
						FROM	fee_receipt_details frd, `fee_receipt_master` frm,class c,`branch` b, `study_period` sp,`student` s
						WHERE	frd.feeReceiptId = frm.feeReceiptId
						AND	frd.studentId = frm.studentId
						AND	frd.classId = frm.feeClassId
						AND	frm.studentId = s.studentId 
						AND	frm.feeClassId = c.classId
						AND	frd.isDelete = 0
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
						$condition
						
							
				) AS a
				GROUP BY a.periodName,a.branchName,a.regNo,a.rollNo
				HAVING (sum(a.totalFees) + sum(a.hostelSecurity) + sum(a.debit)  + sum(a.tranportFine) + sum(a.hostelFine) + sum(a.academicFine)) > (sum(a.paidAmount) + sum(a.credit))
				ORDER BY  $sortField $sortOrderBy $limit
			";
			
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

public function getPendingFeeDetailsLedgerNew($condition='', $classId='',$limit='',$sortOrderBy,$sortField){
		$query = "SELECT 
				(sum(a.totalFees) + sum(a.hostelSecurity) + sum(a.debit) + sum(a.tranportFine) + sum(a.hostelFine) + sum(a.academicFine)) AS totalFees,
				sum(a.hostelFees + hostelSecurity) AS hostelFees,a.transportFees AS transportFees,a.academicFees AS academicFees, a.concession,a.className,
		(sum(a.paidAmount) + sum(a.credit)) AS paidAmount , a.studentName, a.rollNo, a.regNo,a.branchName,a.periodName,
		SUM(IF(a.ledgerTypeId =1,a.debit,0)) AS ledgerAcademicDebit,
        SUM(IF(a.ledgerTypeId =1,a.credit,0)) AS ledgerAcademicCredit,
        SUM(IF(a.ledgerTypeId =2,a.debit,0)) AS ledgerTransportDebit,
        SUM(IF(a.ledgerTypeId =2,a.credit,0)) AS ledgerTransportCredit,                
        SUM(IF(a.ledgerTypeId =3,a.debit,0)) AS ledgerHostelDebit, 
        SUM(IF(a.ledgerTypeId =3,a.credit,0)) AS ledgerHostelCredit

			FROM (
				SELECT 	((IFNULL(frm.hostelFees,0) + IFNULL(frm.transportFees,0) + IFNULL(sum(fri.amount),0)) - IFNULL(frm.concession,0)) AS totalFees,
                IFNULL(frm.hostelFees,0) AS hostelFees,IFNULL(frm.transportFees,0) AS transportFees,IFNULL(sum(fri.amount),0) AS academicFees,  '' AS paidAmount , CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity,'' AS debit, '' AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine,'' AS ledgerTypeId,frm.concession,c.className
						FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm LEFT JOIN `fee_receipt_instrument` fri ON frm.feeReceiptId = fri.feeReceiptId 
							AND frm.studentId = fri.studentId AND	fri.classId = frm.feeClassId AND fri.classId = '$classId' AND	frm.status = 1
						WHERE	frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
						$condition
					GROUP BY frm.studentId,frm.feeClassId,fri.feeReceiptId
				
				UNION 
			
				SELECT 	'' AS totalFees,'' AS hostelFees,'' AS transportFees,'' AS academicFees, sum(frd.amount) AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity,'' AS debit, '' AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine,'' AS ledgerTypeId,frm.concession,c.className
						FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm ,`fee_receipt_details` frd 
						WHERE	frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId
						AND	frm.feeClassId = frd.classId 
						AND	frm.feeReceiptId = frd.feeReceiptId 
						AND	frd.studentId = frm.studentId
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
						AND	frd.isDelete = 0
						$condition
					GROUP BY frm.studentId,frm.feeClassId,frd.feeReceiptId
						
					UNION 
				
					SELECT 	'' AS totalFees,'' AS hostelFees,'' AS transportFees,'' AS academicFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo ,b.branchName,sp.periodName, hostelSecurity ,'' AS debit, '' AS credit ,'' AS tranportFine,'' AS hostelFine,'' AS academicFine,'' AS ledgerTypeId,frm.concession,c.className
						FROM  class c,`branch` b, `study_period` sp,`student` s,`fee_receipt_master` frm
						WHERE		frm.studentId = s.studentId 
							AND	frm.feeClassId = c.classId
							AND	b.branchId = c.branchId
							AND	sp.studyPeriodId = c.studyPeriodId
							AND	frm.status = 1
							$condition
				UNION 
				
					SELECT 	'' AS totalFees,'' AS hostelFees,'' AS transportFees,'' AS academicFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity, sum(fl.debit) AS debit, SUM(fl.credit) AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine,fl.ledgerTypeId,frm.concession,c.className
							FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm , `fee_ledger_debit_credit` fl
							WHERE	frm.studentId = s.studentId
							AND	frm.feeClassId = c.classId
							AND	fl.studentId = frm.studentId
							AND	frm.feeClassId = fl.classId
							AND	frm.instituteId  = fl.instituteId 
							AND	b.branchId = c.branchId
							AND	fl.status IN (0,1)
							AND	sp.studyPeriodId = c.studyPeriodId
							AND	frm.status = 1
							$condition
						GROUP BY fl.studentId,fl.classId,frm.feeReceiptId,fl.ledgerTypeId
						
				UNION
					SELECT 
							DISTINCT '' AS totalFees,'' AS hostelFees,'' AS transportFees,'' AS academicFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity, '' AS debit, '' AS credit,frd.tranportFine,frd.hostelFine,frd.academicFine,'' AS ledgerTypeId,frm.concession,c.className
						FROM	fee_receipt_details frd, `fee_receipt_master` frm,class c,`branch` b, `study_period` sp,`student` s
						WHERE	frd.feeReceiptId = frm.feeReceiptId
						AND	frd.studentId = frm.studentId
						AND	frd.classId = frm.feeClassId
						AND	frm.studentId = s.studentId 
						AND	frm.feeClassId = c.classId
						AND	frd.isDelete = 0
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
						$condition
						
							
				) AS a
				GROUP BY a.periodName,a.branchName,a.regNo,a.rollNo
				HAVING (sum(a.totalFees) + sum(a.hostelSecurity) + sum(a.debit)  + sum(a.tranportFine) + sum(a.hostelFine) + sum(a.academicFine)) > (sum(a.paidAmount) + sum(a.credit))
				ORDER BY  $sortField $sortOrderBy $limit
			";
			
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	//-------------------------------------------------------
//  This function is used to fetch Payment Details Of Student Fee
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	public function getPendingFeeDetailsPrint($classId,$orderBy){
		$query = "
		SELECT (sum(a.totalFees) + sum(a.hostelSecurity) + sum(a.debit) + sum(a.tranportFine) + sum(a.hostelFine) + sum(a.academicFine)) AS totalFees, (sum(a.paidAmount) + sum(a.credit)) AS paidAmount , a.studentName, a.rollNo, a.regNo,a.branchName,a.periodName
			FROM (
				SELECT 	((IFNULL(frm.hostelFees,0) + IFNULL(frm.transportFees,0) + IFNULL(sum(fri.amount),0)) - IFNULL(frm.concession,0)) AS totalFees, 
				'' AS paidAmount , CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo , b.branchName,
				sp.periodName,'' AS hostelSecurity,'' AS debit, '' AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
						FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm LEFT JOIN `fee_receipt_instrument` fri ON frm.feeReceiptId = fri.feeReceiptId 
							AND frm.studentId = fri.studentId AND	fri.classId = frm.feeClassId AND fri.classId = '$classId' AND	frm.status = 1
						WHERE	frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId
						AND	frm.feeClassId = '$classId'
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
					GROUP BY frm.studentId,frm.feeClassId,fri.feeReceiptId
				
				UNION 
			
				SELECT 	'' AS totalFees, sum(frd.amount) AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, 
				s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity,'' AS debit, '' AS credit,'' AS tranportFine,
				'' AS hostelFine,'' AS academicFine
						FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm ,`fee_receipt_details` frd 
						WHERE	frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId
						AND	frm.feeClassId = frd.classId 
						AND	frd.classId ='$classId' 
						AND	frm.feeReceiptId = frd.feeReceiptId 
						AND	frd.studentId = frm.studentId
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
						AND	frd.isDelete = 0
					GROUP BY frm.studentId,frm.feeClassId,frd.feeReceiptId
					
					UNION 
				
					SELECT 	'' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName, 
					s.rollNo,s.regNo ,b.branchName,sp.periodName, hostelSecurity ,'' AS debit, '' AS credit ,'' AS tranportFine,
					'' AS hostelFine,'' AS academicFine
						FROM  class c,`branch` b, `study_period` sp,`student` s,`fee_receipt_master` frm
						WHERE		frm.studentId = s.studentId 
							AND	frm.feeClassId = c.classId
							AND	frm.feeClassId = '$classId'
							AND	b.branchId = c.branchId
							AND	sp.studyPeriodId = c.studyPeriodId
							AND	frm.status = 1
				
				UNION 
				
					SELECT 	'' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName,
					 s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity, sum(fl.debit) AS debit, 
					 SUM(fl.credit) AS credit,'' AS tranportFine,'' AS hostelFine,'' AS academicFine
							FROM  class c,`branch` b, `study_period` sp,`student` s , `fee_receipt_master` frm , `fee_ledger_debit_credit` fl
							WHERE	frm.studentId = s.studentId
							AND	frm.feeClassId = c.classId
							AND	frm.feeClassId = '$classId'
							AND	fl.studentId = frm.studentId
							AND	frm.feeClassId = fl.classId
							AND	frm.instituteId  = fl.instituteId 
							AND	b.branchId = c.branchId
							AND	fl.status IN (0,1)
							AND	sp.studyPeriodId = c.studyPeriodId
							AND	frm.status = 1
						GROUP BY fl.studentId,fl.classId,frm.feeReceiptId
						
				UNION
					SELECT 
							DISTINCT '' AS totalFees, '' AS paidAmount, CONCAT(s.firstName,' ',s.lastName) AS studentName,
							 s.rollNo,s.regNo , b.branchName,sp.periodName,'' AS hostelSecurity, '' AS debit, '' AS credit,
							 frd.tranportFine,frd.hostelFine,frd.academicFine
						FROM	fee_receipt_details frd, `fee_receipt_master` frm,class c,`branch` b, `study_period` sp,`student` s
						WHERE	frd.feeReceiptId = frm.feeReceiptId
						AND	frd.studentId = frm.studentId
						AND	frd.classId = frm.feeClassId
						AND	frm.studentId = s.studentId 
						AND	frm.feeClassId = c.classId
						AND	frm.feeClassId = '$classId'
						AND	frd.isDelete = 0
						AND	b.branchId = c.branchId
						AND	sp.studyPeriodId = c.studyPeriodId
						AND	frm.status = 1
						
							
				) AS a
				GROUP BY a.periodName,a.branchName,a.regNo,a.rollNo
				HAVING (sum(a.totalFees) + sum(a.hostelSecurity) + sum(a.debit)  + sum(a.tranportFine) + sum(a.hostelFine) + sum(a.academicFine)) > (sum(a.paidAmount) + sum(a.credit))
				ORDER BY  $orderBy
			";
			
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
	  public function getPendingAcademicFeeCount($condition='',$limit='',$sortOrderBy,$sortField){
      	 global $sessionHandler;
    	$query ="SELECT 	
    					(IFNULL(sum(fri.amount),0) + IFNULL(fl.debit,0) - IFNULL(frm.concession,0)- IFNULL(fl.credit,0)) AS totalAcademicFees, 
    					IFNULL(sum(fri.amount),0) AS academicFees,IFNULL(frd.amount,0) AS paidAmount ,
                		 CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo ,
                		 fl.debit AS academicLedgerDebit, fl.credit AS academicLedgerCredit,
                		 frd.academicFine,fl.ledgerTypeId,frm.concession,c.className
				FROM  
						class c,`student` s , `fee_ledger_debit_credit` fl,fee_receipt_details frd,
						`fee_receipt_master` frm LEFT JOIN `fee_receipt_instrument` fri ON frm.feeReceiptId = fri.feeReceiptId 
						AND frm.studentId = fri.studentId AND	fri.classId = frm.feeClassId  AND	frm.status = 1						
				WHERE	
						frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId						
						AND	frm.status = 1
						AND	frm.studentId = fl.studentId
						AND	frm.feeClassId = fl.classId
						AND	frm.instituteId  = fl.instituteId
						AND	frm.feeClassId = frd.classId 
						AND	frm.feeReceiptId = frd.feeReceiptId 
						AND	frd.studentId = frm.studentId
						AND frd.isDelete = 0 
						AND frd.feeType IN(1,4)
						AND fl.ledgerTypeId =1
						$condition
				GROUP BY frm.studentId,frm.feeClassId,fri.feeReceiptId ";
    		
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     public function getPendingAcademicFee($condition='',$limit='',$sortOrderBy,$sortField){
      	 global $sessionHandler;
    	$query ="SELECT 	
    					(IFNULL(sum(fri.amount),0) + IFNULL(fl.debit,0) - IFNULL(frm.concession,0)- IFNULL(fl.credit,0)) AS totalAcademicFees, 
    					IFNULL(sum(fri.amount),0) AS academicFees,IFNULL(frd.amount,0) AS paidAmount ,
                		 CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo ,
                		 fl.debit AS academicLedgerDebit, fl.credit AS academicLedgerCredit,
                		 frd.academicFine,fl.ledgerTypeId,frm.concession,c.className
				FROM  
						class c,`student` s , 
						`fee_receipt_master` frm LEFT JOIN `fee_receipt_instrument` fri ON frm.feeReceiptId = fri.feeReceiptId 
						AND frm.studentId = fri.studentId AND	fri.classId = frm.feeClassId  AND	frm.status = 1
						LEFT JOIN `fee_ledger_debit_credit` fl	ON	frm.studentId = fl.studentId AND	frm.feeClassId = fl.classId
						AND	frm.instituteId  = fl.instituteId	AND fl.ledgerTypeId =1
						LEFT JOIN fee_receipt_details frd	ON 	frm.feeClassId = frd.classId 
						AND	frm.feeReceiptId = frd.feeReceiptId AND	frd.studentId = frm.studentId
						AND frd.isDelete = 0 AND frd.feeType IN(1,4)
				WHERE	
						frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId						
						AND	frm.status = 1
						$condition
				GROUP BY frm.studentId,frm.feeClassId,fri.feeReceiptId ";   			
			
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     public function getPendingTransportFee($condition='',$limit='',$sortOrderBy,$sortField){
      	 global $sessionHandler;
    	$query ="SELECT 	
    					(IFNULL(frm.transportFees,0)  + IFNULL(fl.debit,0)- IFNULL(fl.credit,0)) AS totalFees,
            			IFNULL(frm.transportFees,0) AS transportFees,IFNULL(frd.amount,0) AS paidAmount , 
                		CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo ,
               			 IFNULL(fl.debit,0) AS transportLedgerDebit, IFNULL(fl.credit,0) AS transportLedgerCredit,
                		 fl.ledgerTypeId,frm.concession,c.className                	
				FROM  
						class c,`student` s ,
						`fee_receipt_master` frm LEFT JOIN `fee_ledger_debit_credit` fl	ON	frm.studentId = fl.studentId AND	frm.feeClassId = fl.classId
						AND	frm.instituteId  = fl.instituteId	AND fl.ledgerTypeId =2
						LEFT JOIN fee_receipt_details frd	ON 	frm.feeClassId = frd.classId 
						AND	frm.feeReceiptId = frd.feeReceiptId AND	frd.studentId = frm.studentId
						AND frd.isDelete = 0 AND frd.feeType IN(2,4)						
				WHERE	
						frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId						
						AND	frm.status = 1						
						$condition
				GROUP BY frm.studentId,frm.feeClassId ";
    			
		
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     public function getPendingHostelFee($condition='',$limit='',$sortOrderBy,$sortField){
      	 global $sessionHandler;
    	$query ="SELECT 
    	
					(IFNULL(frm.hostelFees,0)+ IFNULL(fl.debit,0) - IFNULL(fl.credit,0)) AS totalFees, 
					IFNULL(frd.amount,0) AS paidAmount , 
					CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo ,
                	IFNULL(fl.debit,0) AS hostelLedgerDebit, IFNULL(fl.credit,0)  AS hostelLedgerCredit,
                	frd.hostelFine,fl.ledgerTypeId,c.className,frd.hostelSecurity			
				FROM  
						class c,`student` s , 
						`fee_receipt_master` frm LEFT JOIN `fee_ledger_debit_credit` fl	ON	frm.studentId = fl.studentId AND	frm.feeClassId = fl.classId
						AND	frm.instituteId  = fl.instituteId	AND fl.ledgerTypeId =3
						LEFT JOIN fee_receipt_details frd	ON 	frm.feeClassId = frd.classId 
						AND	frm.feeReceiptId = frd.feeReceiptId AND	frd.studentId = frm.studentId
						AND frd.isDelete = 0 AND frd.feeType IN(3,4)					
				WHERE	
						frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId						
						AND	frm.status = 1						
						$condition
				GROUP BY frm.studentId,frm.feeClassId ";
    			
			
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
}

//History : $

?>
