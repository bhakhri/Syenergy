<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the "FEE HEAD VALUES" Module
//
//
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class ConsolidateFeeReportManager {
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
//  This function is used to fetch Payment Details Count Of Student Fee
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	public function getFeeDetailsCount($fromDate,$toDate,$condition,$formDate,$toDate){
		$query = "
			SELECT sum(a.cashAmount) as cashAmount,SUM(checkAmount) AS checkAmount,sum(DDAmount) AS DDAmount,a.feeClassId,a.paymentMode,a.feeType,a.instituteId,a.instituteAbbr,a.receiptDate
			FROM(
				SELECT SUM(frd.amount) AS cashAmount,'' AS checkAmount,'' AS DDAmount,frd.feeType,frd.paymentMode,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM	`fee_receipt_master` frm,`fee_receipt_details` frd, `institute` ins
					WHERE
							frm.feeReceiptId = frd.feeReceiptId
						AND	frm.feeClassId = frd.classId
						AND	frm.studentId = frd.StudentId
						AND	frm.status = 1
						AND	frd.paymentMode IN(1,4)
						AND	frm.instituteId = ins.instituteId
						AND	frd.isDelete = 0
						AND	date_format(frd.receiptDate, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'
						$condition
						GROUP BY frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
				UNION
				
					SELECT '' AS cashAmount,SUM(frd.amount) AS checkAmount,'' AS DDAmount,frd.feeType,frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM	`fee_receipt_master` frm,`fee_receipt_details` frd,`institute` ins
					WHERE
							frm.feeReceiptId = frd.feeReceiptId
						AND	frm.feeClassId = frd.classId
						AND	frm.studentId = frd.StudentId
						AND	frm.status = 1
						AND	frd.paymentMode = 2
						AND	frm.instituteId = ins.instituteId
						AND	frd.isDelete = 0
						AND	date_format(frd.receiptDate, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'
						$condition
						GROUP BY frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
				UNION
				
				SELECT '' AS cashAmount,'' AS checkAmount, SUM(frd.amount) AS DDAmount,frd.feeType,frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM	`fee_receipt_master` frm,`fee_receipt_details` frd, `institute` ins
					WHERE
							frm.feeReceiptId = frd.feeReceiptId
						AND	frm.feeClassId = frd.classId
						AND	frm.studentId = frd.StudentId
						AND	frm.status = 1
						AND	frd.paymentMode = 3
						AND	frm.instituteId = ins.instituteId
						AND	frd.isDelete = 0
						AND	date_format(frd.receiptDate, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'
						$condition
						GROUP BY frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
			) AS a
			where a.receiptDate between '$fromDate' AND '$toDate'
			GROUP BY a.instituteId
			";
			
			
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getFeeDetailsCountNew($condition='',$whereCondition=''){
		$query = "
			SELECT sum(a.cashAmount) as cashAmount,SUM(checkAmount) AS checkAmount,sum(DDAmount) AS DDAmount,a.feeClassId,a.paymentMode,a.feeType,a.instituteId,a.instituteAbbr,a.receiptDate
			FROM(
				SELECT 
                    SUM(frd.amount) AS cashAmount,'' AS checkAmount,'' AS DDAmount,frd.feeType,
                    frd.paymentMode,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,
                    frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM	
                    `fee_receipt_master` frm,`fee_receipt_details` frd, `institute` ins, student s, class c  
				WHERE
                     s.studentId = frm.studentId
                     AND frm.feeClassId = c.classId
					 AND frm.feeReceiptId = frd.feeReceiptId
					 AND frm.feeClassId = frd.classId
					 AND frm.studentId = frd.StudentId
					 AND frm.status = 1
					 AND frd.paymentMode IN(1,4)  
					 AND frm.instituteId = ins.instituteId
					 AND frd.isDelete = 0
					 $condition
				GROUP BY 
                     frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
				UNION
				SELECT 
                    '' AS cashAmount,SUM(frd.amount) AS checkAmount,'' AS DDAmount,
                    frd.feeType,frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,
                    frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM
                    `fee_receipt_master` frm,`fee_receipt_details` frd,`institute` ins, student s, class c
				WHERE
					s.studentId = frm.studentId
                    AND frm.feeClassId = c.classId  
                    AND frm.feeReceiptId = frd.feeReceiptId
					AND	frm.feeClassId = frd.classId
					AND	frm.studentId = frd.StudentId
					AND	frm.status = 1
					AND	frd.paymentMode = 2
					AND	frm.instituteId = ins.instituteId
					AND	frd.isDelete = 0
					$condition
				GROUP BY 
                    frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
				UNION
				SELECT 
                    '' AS cashAmount,'' AS checkAmount, SUM(frd.amount) AS DDAmount,
                    frd.feeType,frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,
                    frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM
                	`fee_receipt_master` frm,`fee_receipt_details` frd, `institute` ins, student s, class c  
				WHERE
					s.studentId = frm.studentId
                    AND frm.feeClassId = c.classId    
                    AND frm.feeReceiptId = frd.feeReceiptId
					AND	frm.feeClassId = frd.classId
					AND	frm.studentId = frd.StudentId
					AND	frm.status = 1
					AND	frd.paymentMode = 3
					AND	frm.instituteId = ins.instituteId
					AND	frd.isDelete = 0
				$condition
				GROUP BY 
                    frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
			) AS a
			$whereCondition
			GROUP BY
                a.instituteId";
			
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------
//  This function is used to fetch Payment Details Of Student Fee
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	public function getFeeDetails($fromDate,$toDate,$condition,$limit,$sortOrderBy,$sortField){
		
		$query = "
				SELECT sum(a.cashAmount) as cashAmount,SUM(checkAmount) AS checkAmount,sum(DDAmount) AS DDAmount,a.feeClassId,a.paymentMode,a.feeType,a.instituteId,a.instituteAbbr,a.receiptDate
			FROM(
				SELECT SUM(frd.amount) AS cashAmount,'' AS checkAmount,'' AS DDAmount,frd.feeType,frd.paymentMode,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM	`fee_receipt_master` frm,`fee_receipt_details` frd, `institute` ins
					WHERE
							frm.feeReceiptId = frd.feeReceiptId
						AND	frm.feeClassId = frd.classId
						AND	frm.studentId = frd.StudentId
						AND	frm.status = 1
						AND	frd.paymentMode IN(1,4)  
						AND	frm.instituteId = ins.instituteId
						AND	frd.isDelete = 0
						AND	date_format(frd.receiptDate, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'
						$condition
						GROUP BY frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
				UNION
				
					SELECT '' AS cashAmount,SUM(frd.amount) AS checkAmount,'' AS DDAmount,frd.feeType,frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM	`fee_receipt_master` frm,`fee_receipt_details` frd,`institute` ins
					WHERE
							frm.feeReceiptId = frd.feeReceiptId
						AND	frm.feeClassId = frd.classId
						AND	frm.studentId = frd.StudentId
						AND	frm.status = 1
						AND	frd.paymentMode = 2
						AND	frm.instituteId = ins.instituteId
						AND	frd.isDelete = 0
						AND	date_format(frd.receiptDate, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'
						$condition
						GROUP BY frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
				UNION
				
				SELECT '' AS cashAmount,'' AS checkAmount, SUM(frd.amount) AS DDAmount,frd.feeType,frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM	`fee_receipt_master` frm,`fee_receipt_details` frd, `institute` ins
					WHERE
							frm.feeReceiptId = frd.feeReceiptId
						AND	frm.feeClassId = frd.classId
						AND	frm.studentId = frd.StudentId
						AND	frm.status = 1
						AND	frd.paymentMode = 3
						AND	frm.instituteId = ins.instituteId
						AND	frd.isDelete = 0
						AND	date_format(frd.receiptDate, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'
						$condition
						GROUP BY frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
			) AS a
			where a.receiptDate BETWEEN '$fromDate' AND '$toDate'
			GROUP BY a.instituteId
			 ORDER BY 
                        	$sortField $sortOrderBy $limit
			";
			
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
	public function getFeeDetailsNew($condition='',$whereCondition='',$limit='',$sortOrderBy,$sortField){
		
		$query = "
				SELECT sum(a.cashAmount) as cashAmount,SUM(checkAmount) AS checkAmount,sum(DDAmount) AS DDAmount,
				a.feeClassId,a.paymentMode,a.feeType,a.instituteId,a.instituteAbbr,a.receiptDate,a.concession,
				(sum(a.academicFinePaid)+ sum(a.hostelFinePaid)+sum(a.transportFinePaid)) AS fine
			FROM(
				SELECT SUM(frd.amount) AS cashAmount,'' AS checkAmount,
				'' AS DDAmount,frm.concession,frd.feeType,frd.paymentMode,IFNULL(frd.academicFinePaid,0) AS academicFinePaid ,
				 IFNULL(frd.hostelFinePaid,0) AS hostelFinePaid , IFNULL(frd.transportFinePaid,0) AS transportFinePaid ,
				date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM	`fee_receipt_master` frm,`fee_receipt_details` frd, `institute` ins, student s, class c
					WHERE
                        s.studentId = frm.studentId
                        AND c.classId = frm.feeClassId
						AND frm.feeReceiptId = frd.feeReceiptId
						AND	frm.feeClassId = frd.classId
						AND	frm.studentId = frd.StudentId
						AND	frm.status = 1
						AND	frd.paymentMode IN(1,4)  
						AND	frm.instituteId = ins.instituteId
						AND	frd.isDelete = 0
						$condition
						GROUP BY frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
				UNION
				
					SELECT '' AS cashAmount,SUM(frd.amount) AS checkAmount,'' AS DDAmount,frm.concession,
					frd.feeType,frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,
					IFNULL(frd.academicFinePaid,0) AS academicFinePaid ,
				 IFNULL(frd.hostelFinePaid,0) AS hostelFinePaid , IFNULL(frd.transportFinePaid,0) AS transportFinePaid ,
					frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM	`fee_receipt_master` frm,`fee_receipt_details` frd,`institute` ins, student s, class c
					WHERE
                        s.studentId = frm.studentId
                        AND frm.feeClassId = c.classId
                        AND frm.feeReceiptId = frd.feeReceiptId
						AND	frm.feeClassId = frd.classId
						AND	frm.studentId = frd.StudentId
						AND	frm.status = 1
						AND	frd.paymentMode = 2
						AND	frm.instituteId = ins.instituteId
						AND	frd.isDelete = 0
						$condition
						GROUP BY frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
				UNION
				
				SELECT '' AS cashAmount,'' AS checkAmount, SUM(frd.amount) AS DDAmount,frd.feeType,
				IFNULL(frd.academicFinePaid,0) AS academicFinePaid ,
				 IFNULL(frd.hostelFinePaid,0) AS hostelFinePaid , IFNULL(frd.transportFinePaid,0) AS transportFinePaid ,frm.concession,frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM	`fee_receipt_master` frm,`fee_receipt_details` frd, `institute` ins, student s, class c
					WHERE
						s.studentId = frm.studentId
                        AND frm.feeClassId = c.classId
                        AND frm.feeReceiptId = frd.feeReceiptId
						AND	frm.feeClassId = frd.classId
						AND	frm.studentId = frd.StudentId
						AND	frm.status = 1
						AND	frd.paymentMode = 3
						AND	frm.instituteId = ins.instituteId
						AND	frd.isDelete = 0
						$condition
						GROUP BY frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
			) AS a
			$whereCondition
			GROUP BY a.instituteId
			 ORDER BY 
                        	$sortField $sortOrderBy $limit
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

	public function getFeeDetailsPrint($fromDate,$toDate,$condition,$orderBy){
		
		$query = "
				SELECT sum(a.cashAmount) as cashAmount,SUM(checkAmount) AS checkAmount,sum(DDAmount) AS DDAmount,a.feeClassId,a.paymentMode,a.feeType,a.instituteId,a.instituteAbbr,a.receiptDate
			FROM(
				SELECT SUM(frd.amount) AS cashAmount,'' AS checkAmount,'' AS DDAmount,frd.feeType,frd.paymentMode,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM	`fee_receipt_master` frm,`fee_receipt_details` frd, `institute` ins
					WHERE
							frm.feeReceiptId = frd.feeReceiptId
						AND	frm.feeClassId = frd.classId
						AND	frm.studentId = frd.StudentId
						AND	frm.status = 1
						AND	frd.paymentMode IN(1,4)  
						AND	frm.instituteId = ins.instituteId
						AND	frd.isDelete = 0
						AND	date_format(frd.receiptDate, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'
						$condition
						GROUP BY frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
				UNION
				
					SELECT '' AS cashAmount,SUM(frd.amount) AS checkAmount,'' AS DDAmount,frd.feeType,frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM	`fee_receipt_master` frm,`fee_receipt_details` frd,`institute` ins
					WHERE
							frm.feeReceiptId = frd.feeReceiptId
						AND	frm.feeClassId = frd.classId
						AND	frm.studentId = frd.StudentId
						AND	frm.status = 1
						AND	frd.paymentMode = 2
						AND	frm.instituteId = ins.instituteId
						AND	frd.isDelete = 0
						AND	date_format(frd.receiptDate, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'
						$condition
						GROUP BY frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
				UNION
				
				SELECT '' AS cashAmount,'' AS checkAmount, SUM(frd.amount) AS DDAmount,frd.feeType,frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,frm.instituteId,ins.instituteAbbr
				FROM	`fee_receipt_master` frm,`fee_receipt_details` frd, `institute` ins
					WHERE
							frm.feeReceiptId = frd.feeReceiptId
						AND	frm.feeClassId = frd.classId
						AND	frm.studentId = frd.StudentId
						AND	frm.status = 1
						AND	frd.paymentMode = 3
						AND	frm.instituteId = ins.instituteId
						AND	frd.isDelete = 0
						AND	date_format(frd.receiptDate, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'
						$condition
						GROUP BY frm.instituteId,date_format(frd.receiptDate, '%Y-%m-%d' )
			) AS a
			where a.receiptDate BETWEEN '$fromDate' AND '$toDate'
			GROUP BY a.instituteId
			 ORDER BY 
                        	$orderBy
			";
			
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
}

//History : $

?>
