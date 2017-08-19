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



class FeeCollectionReportManager {
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

	public function getFeeDetailsCount($classId,$fromDate,$toDate){
		$query = "
			SELECT a.studentName, a.branchName, a.periodName, a.rollNo, a.regNo, sum(a.cashAmount) as cashAmount, sum(a.checkAmount) As checkAmount, sum(a.DDAmount) As DDAmount,a.feeClassId,a.studentId,a.receiptDate,a.receiptNo
			FROM(
				SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName, c.className, b.branchName,sp.periodName, s.rollNo,s.regNo, SUM(frd.amount) AS cashAmount, '' AS checkAmount, '' AS DDAmount,frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo
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
						AND	frd.paymentMode = 1
						AND	frm.status = 1
						AND	frd.isDelete = 0
						AND	frm.feeClassId = '$classId'
						GROUP BY frd.classId,frd.studentId,frd.receiptNo,frd.paymentMode 
						
			 UNION 
			 
			 SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName,c.className, b.branchName,sp.periodName, s.rollNo,s.regNo ,'' AS cashAmount,sum(frd.amount) AS checkAmount, '' AS DDAmount,frd.paymentMode,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo
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
						AND	frd.paymentMode = 2
						AND	frm.status = 1
						AND	frd.isDelete = 0
						AND	frm.feeClassId = '$classId'
						GROUP BY frd.classId,frd.studentId,frd.receiptNo,frd.paymentMode 
						
			UNION 
			
			SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName,c.className, b.branchName,sp.periodName, s.rollNo,s.regNo, '' cashAmount,'' AS checkAmount,sum(frd.amount) AS DDAmount,frd.paymentMode,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo
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
						AND	frd.paymentMode = 3
						AND	frm.status = 1
						AND	frd.isDelete = 0
						AND	frm.feeClassId = '$classId'
						GROUP BY frd.classId,frd.studentId,frd.receiptNo,frd.paymentMode
			) AS a
			WHERE a.receiptDate between '$fromDate' and '$toDate'
			Group By a.StudentId, a.feeClassId
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

	public function getFeeDetails($classId,$fromDate,$toDate,$limit,$sortOrderBy,$sortField){
		$query = "
			SELECT a.studentName, a.branchName, a.periodName, a.rollNo, a.regNo, sum(a.cashAmount) as cashAmount, sum(a.checkAmount) As checkAmount, sum(a.DDAmount) As DDAmount,a.feeClassId,a.studentId,a.receiptDate,a.receiptNo
			FROM(
				SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName, c.className, b.branchName,sp.periodName, s.rollNo,s.regNo, SUM(frd.amount) AS cashAmount, '' AS checkAmount, '' AS DDAmount,frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo
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
						AND	frd.paymentMode = 1
						AND	frm.status = 1
						AND	frd.isDelete = 0
						AND	frm.feeClassId = '$classId'
						GROUP BY frd.receiptNo,frd.classId,frd.studentId,frd.paymentMode 
						
			 UNION 
			 
			 SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName,c.className, b.branchName,sp.periodName, s.rollNo,s.regNo ,'' AS cashAmount,sum(frd.amount) AS checkAmount, '' AS DDAmount,frd.paymentMode,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo
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
						AND	frd.paymentMode = 2
						AND	frm.status = 1
						AND	frd.isDelete = 0
						AND	frm.feeClassId = '$classId'
						GROUP BY frd.receiptNo,frd.classId,frd.studentId,frd.paymentMode 
						
			UNION 
			
			SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName,c.className, b.branchName,sp.periodName, s.rollNo,s.regNo, '' cashAmount,'' AS checkAmount,sum(frd.amount) AS DDAmount,frd.paymentMode,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo
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
						AND	frd.paymentMode = 3
						AND	frm.status = 1
						AND	frd.isDelete = 0
						AND	frm.feeClassId = '$classId'
						GROUP BY frd.receiptNo,frd.classId,frd.studentId,frd.paymentMode
			) AS a
			WHERE a.receiptDate between '$fromDate' and '$toDate'
			Group By a.receiptNo,a.StudentId, a.feeClassId
			 ORDER BY 
                        	$sortField $sortOrderBy $limit
			";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
	
		
//-------------------------------------------------------
//  This function is used to fetch Payment Details Of Student Fee for print
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function getFeeDetailsPrint($classId,$fromDate,$toDate,$orderBy){	
		$query = "
			SELECT a.studentName, a.branchName, a.periodName, a.rollNo, a.regNo, sum(a.cashAmount) as cashAmount, sum(a.checkAmount) As checkAmount, sum(a.DDAmount) As DDAmount,a.feeClassId,a.studentId,a.receiptDate,a.receiptNo
			FROM(
				SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName, c.className, b.branchName,sp.periodName, s.rollNo,s.regNo, SUM(frd.amount) AS cashAmount, '' AS checkAmount, '' AS DDAmount,frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo
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
						AND	frd.paymentMode = 1
						AND	frm.status = 1
						AND	frd.isDelete = 0
						AND	frm.feeClassId = '$classId'
						GROUP BY frd.receiptNo,frd.classId,frd.studentId,frd.paymentMode 
						
			 UNION 
			 
			 SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName,c.className, b.branchName,sp.periodName, s.rollNo,s.regNo ,'' AS cashAmount,sum(frd.amount) AS checkAmount, '' AS DDAmount,frd.paymentMode,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo
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
						AND	frd.paymentMode = 2
						AND	frm.status = 1
						AND	frd.isDelete = 0
						AND	frm.feeClassId = '$classId'
						GROUP BY frd.receiptNo,frd.classId,frd.studentId,frd.paymentMode 
						
			UNION 
			
			SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName,c.className, b.branchName,sp.periodName, s.rollNo,s.regNo, '' cashAmount,'' AS checkAmount,sum(frd.amount) AS DDAmount,frd.paymentMode,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo
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
						AND	frd.paymentMode = 3
						AND	frm.status = 1
						AND	frd.isDelete = 0
						AND	frm.feeClassId = '$classId'
						GROUP BY frd.receiptNo,frd.classId,frd.studentId,frd.paymentMode
			) AS a
			WHERE a.receiptDate between '$fromDate' and '$toDate'
			Group By a.receiptNo,a.StudentId, a.feeClassId
			 ORDER BY 
                        	$orderBy
			";
			
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    
    public function getFeeDetailsCountNew($condition='',$whereCondition=''){
        $query = "
            SELECT a.studentName, a.branchName, a.periodName, a.rollNo, a.regNo, sum(a.cashAmount) as cashAmount, sum(a.checkAmount) As checkAmount, sum(a.DDAmount) As DDAmount,a.feeClassId,a.studentId,a.receiptDate,a.receiptNo
            FROM(
                SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName, c.className, b.branchName,sp.periodName, s.rollNo,s.regNo, SUM(frd.amount) AS cashAmount, '' AS checkAmount, '' AS DDAmount,frd.paymentMode ,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo
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
                        AND    frd.paymentMode = 1
                        AND    frm.status = 1
                        AND    frd.isDelete = 0
                        $condition
                        GROUP BY frd.classId,frd.studentId,frd.receiptNo,frd.paymentMode 
                        
             UNION 
             
             SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName,c.className, b.branchName,sp.periodName, s.rollNo,s.regNo ,'' AS cashAmount,sum(frd.amount) AS checkAmount, '' AS DDAmount,frd.paymentMode,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo
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
                        AND    frd.paymentMode = 2
                        AND    frm.status = 1
                        AND    frd.isDelete = 0
                        $condition
                        GROUP BY frd.classId,frd.studentId,frd.receiptNo,frd.paymentMode 
                        
            UNION 
            
            SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName,c.className, b.branchName,sp.periodName, s.rollNo,s.regNo, '' cashAmount,'' AS checkAmount,sum(frd.amount) AS DDAmount,frd.paymentMode,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo
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
                        AND    frd.paymentMode = 3
                        AND    frm.status = 1
                        AND    frd.isDelete = 0
                        $condition
                        GROUP BY frd.classId,frd.studentId,frd.receiptNo,frd.paymentMode
            ) AS a
            $whereCondition
            GROUP BY 
                a.studentId, a.feeClassId";
            
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
//-------------------------------------------------------
//  This function is used to fetch Payment Details Of Student Fee
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getFeeDetailsNew($condition='',$whereCondition='',$limit='',$sortOrderBy='',$sortField='') {
        
        $query = "
            SELECT 
                a.studentName, a.branchName, a.periodName, 
                IFNULL(a.rollNo,'".NOT_APPLICABLE_STRING."') AS rollNo, 
                IFNULL(a.regNo,'".NOT_APPLICABLE_STRING."') AS regNo, 
                SUM(a.cashAmount) as cashAmount, SUM(a.checkAmount) As checkAmount, 
                SUM(a.DDAmount) As DDAmount,a.feeClassId,a.studentId,a.receiptDate,a.receiptNo,a.feeTypeOf
            FROM(
                SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName, c.className, b.branchName,sp.periodName, s.rollNo,s.regNo,
                 SUM(frd.amount) AS cashAmount, '' AS checkAmount, '' AS DDAmount,frd.paymentMode ,
                 date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo, CONCAT(IF(frd.feeType=1,'Academic',IF(frd.feeType=2,'Transport',IF(frd.feeType=3,'Hostel','All'))),' (', 
                      IF(frd.paidAt=1,'Bank','On Desk'),')') AS feeTypeOf
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
                        AND    frd.paymentMode = 1
                        AND    frm.status = 1
                        AND    frd.isDelete = 0
                        $condition
                        GROUP BY frd.receiptNo,frd.classId,frd.studentId,frd.paymentMode 
                        
             UNION 
             
             SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName,c.className, b.branchName,sp.periodName, s.rollNo,s.regNo ,
             '' AS cashAmount,sum(frd.amount) AS checkAmount, '' AS DDAmount,frd.paymentMode,
             date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,frm.feeClassId,s.studentId,frd.receiptNo, 
             CONCAT(IF(frd.feeType=1,'Academic',IF(frd.feeType=2,'Transport',IF(frd.feeType=3,'Hostel','All'))),' (', 
                      IF(frd.paidAt=1,'Bank','On Desk'),')') AS feeTypeOf
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
                        AND    frd.paymentMode = 2
                        AND    frm.status = 1
                        AND    frd.isDelete = 0
                       $condition
                        GROUP BY frd.receiptNo,frd.classId,frd.studentId,frd.paymentMode 
                        
            UNION 
            
            SELECT CONCAT(s.firstName,' ',s.lastName) AS studentName,c.className, b.branchName,sp.periodName, s.rollNo,s.regNo,
             '' cashAmount,'' AS checkAmount,sum(frd.amount) AS DDAmount,frd.paymentMode,date_format(frd.receiptDate, '%Y-%m-%d' ) AS receiptDate,
             frm.feeClassId,s.studentId,frd.receiptNo, CONCAT(IF(frd.feeType=1,'Academic',IF(frd.feeType=2,'Transport',IF(frd.feeType=3,'Hostel','All'))),' (', 
                      IF(frd.paidAt=1,'Bank','On Desk'),')') AS feeTypeOf
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
                        AND    frd.paymentMode = 3
                        AND    frm.status = 1
                        AND    frd.isDelete = 0
                        $condition
                        GROUP BY frd.receiptNo,frd.classId,frd.studentId,frd.paymentMode
            ) AS a
            $whereCondition
            GROUP By a.receiptNo,a.studentId, a.feeClassId
             ORDER BY 
                            $sortField $sortOrderBy $limit
            ";
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
}

//History : $

?>
