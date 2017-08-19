<?php 
//-------------------------------------------------------
// This File contains Bussiness Logic of the feeDetail report
// Created By:  Harpreet
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeeDetailHistoryReportManager {
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

     
	 public function getTotalPaidFee($condition='',$having='',$limit='',$sortOrderBy='',$sortField=''){	
  global $sessionHandler;
    	$query =" SELECT 
    					((IFNULL(frm.hostelFees,0) + IFNULL(frm.transportFees,0) + IFNULL(sum(fri.amount),0)) - IFNULL(frm.concession,0)) AS totalFees,
               		 IFNULL(frm.hostelFees,0) AS hostelFees,IFNULL(frm.transportFees,0) AS transportFees,
               		 IFNULL(sum(fri.amount),0) AS academicFees, sum(frd.amount) AS paidAmount,frd.feeType,frd.receiptNo,
               		 	frd.receiptDate,frd.paidAt,	frd.academicFeePaid,frd.hostelFeePaid,frd.transportFeePaid,frd.tranportFine,frd.hostelFine,
    					frd.academicFine,frd.academicFinePaid,frd.hostelFinePaid,frd.transportFinePaid,frd.hostelSecurity,frd.isDelete,
    					c.className,CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo,s.studentId,frd.classId,
    					SUM(IF(fl.ledgerTypeId =1,fl.debit,0)) AS ledgerAcademicDebit,
		       			 SUM(IF(fl.ledgerTypeId =1,fl.credit,0)) AS ledgerAcademicCredit,
		       			 SUM(IF(fl.ledgerTypeId =2,fl.debit,0)) AS ledgerTransportDebit,
		       			 SUM(IF(fl.ledgerTypeId =2,fl.credit,0)) AS ledgerTransportCredit,                
		       			 SUM(IF(fl.ledgerTypeId =3,fl.debit,0)) AS ledgerHostelDebit, 
		       			 SUM(IF(fl.ledgerTypeId =3,fl.credit,0)) AS ledgerHostelCredit,frm.concession
					FROM 
						`fee_receipt_details` frd,`class` c,`student` s,`fee_receipt_master` frm LEFT JOIN `fee_receipt_instrument` fri ON frm.feeReceiptId = fri.feeReceiptId 
							AND frm.studentId = fri.studentId AND fri.classId = frm.feeClassId AND frm.status = 1
							LEFT JOIN `fee_ledger_debit_credit` fl ON frm.studentId = fl.studentId AND frm.feeClassId = fl.classId $feeTypeCondition							
					WHERE
						frm.feeReceiptId = frd.feeReceiptId
                        AND    frm.feeClassId = frd.classId
                        AND    frm.studentId = frd.studentId
                        AND    s.studentId = frd.studentId
                        AND    s.studentId = frm.studentId
                        AND    frd.classId = c.classId
                        AND    frm.feeClassId = c.classId
                        AND    frm.status = 1
						AND  frd.isDelete = 0 					
						
						 $condition		
					 GROUP BY frd.studentId,frd.classId						 			
               ";
    	
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
     public function getTotalAcademicFee($ttStudentId='',$ttClassId='',$feeType='',$condition=''){
     	 if($feeType=='1') {
              $ttFeeType = "frd.feeType = '1' "; 
            }
            else {
              $ttFeeType = "frd.feeType IN (1,4) "; 
            }	
        global $sessionHandler;
    	$query =" SELECT
				    DISTINCT s.studentId, c.classId, SUM(fri.amount) AS academicFees,
				    SUM(DISTINCT IF(IFNULL(frd.feeReceiptDetailId,0)=0,0,frd.amount)) AS paidAmount,
				    SUM(DISTINCT IF(fl.isFine=0,fl.debit,0)) AS ledgerDebit,
				    SUM(DISTINCT IF(fl.isFine=0,fl.credit,0)) AS ledgerCredit,
				    SUM(DISTINCT IF(fl.isFine=1,fl.debit,0)) AS ledgerDebitFine,SUM(DISTINCT IF(fl.isFine=1,fl.credit,0)) AS ledgerCreditFine,
				    IFNULL(con.adhocAmount,0) AS concession,IFNULL(frd.academicFine,0) AS fine,
				    (IFNULL(fl.debit,0) - IFNULL(fl.credit,0)+ IFNULL(sum(fri.amount),0) - IFNULL(con.adhocAmount,0)) AS totalFees
				FROM
				    student s 
				    INNER JOIN fee_receipt_instrument fri ON fri.studentId = s.studentId 
				    INNER JOIN class c ON fri.classId = c.classId
				    LEFT JOIN fee_receipt_details frd ON frd.studentId = s.studentId AND frd.classId = c.classId  AND frd.isDelete=0 AND  $ttFeeType	
				    LEFT JOIN fee_ledger_debit_credit fl ON fl.classId = c.classId AND fl.studentId = s.studentId AND fl.ledgerTypeId=1
				    LEFT JOIN adhoc_concession_master_new con ON con.feeClassId = c.classId AND con.studentId = s.studentId 
				WHERE
				       s.studentId = '$ttStudentId'						
					AND c.classId= '$ttClassId'						
						 $condition		 
				GROUP BY 
				    s.studentId, c.classId	";
    	
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
     public function getTotalTransportFee($ttStudentId='',$ttClassId='',$feeType='',$condition=''){
     	if($feeType=='2') {
              $ttFeeType = "frd.feeType = '2' "; 
            }
            else {
              $ttFeeType = "frd.feeType IN (2,4) "; 
            }		
  global $sessionHandler;
    	$query ="SELECT
				    DISTINCT s.studentId, c.classId, SUM(frd.amount) AS paidAmount,IFNULL(frd.tranportFine,0) AS fine,
				    SUM(IF(fl.isFine=0,fl.debit,0)) AS ledgerDebit, SUM(IF(fl.isFine=0,fl.credit,0)) AS ledgerCredit,
				    SUM(IF(fl.isFine=1,fl.debit,0)) AS ledgerDebitFine,SUM(IF(fl.isFine=1,fl.credit,0)) AS ledgerCreditFine,
				    brsm.routeCharges,IFNULL(frd.tranportFine,0) AS fine,
				    (SUM(IFNULL(fl.debit,0)) - SUM(IFNULL(fl.credit,0))+ IFNULL(brsm.routeCharges,0)) AS totalFees
				FROM
				     student s 
				    INNER JOIN bus_route_student_mapping brsm  ON brsm.studentId = s.studentId 
				    INNER JOIN class c ON brsm.classId = c.classId 
				    LEFT JOIN fee_receipt_details frd ON frd.studentId = brsm.studentId AND frd.classId = brsm.classId  AND frd.isDelete=0 AND $ttFeeType	
				    LEFT JOIN fee_ledger_debit_credit fl ON fl.classId = brsm.classId AND fl.studentId = brsm.studentId AND fl.ledgerTypeId=2
				WHERE
				      brsm.studentId = s.studentId
				      AND	brsm.classId = c.classId
				      AND  s.studentId = '$ttStudentId'						
					AND    c.classId= '$ttClassId'						
						 $condition		 
				GROUP BY 
				    s.studentId, c.classId ";
    	
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }      
    
    
     public function getTotalHostelFee($ttStudentId='',$ttClassId='',$feeType='',$condition=''){
     	if($feeType=='3') {
              $ttFeeType = "frd.feeType = '3' "; 
            }
            else {
              $ttFeeType = "frd.feeType IN (3,4) "; 
            }		
  global $sessionHandler;
    	$query =" SELECT
				    DISTINCT s.studentId, c.classId,  SUM(DISTINCT IF(IFNULL(frd.feeReceiptDetailId,0)=0,0,frd.amount)) AS paidAmount,IFNULL(frd.hostelFine,0) AS fine,
				    SUM( IF(fl.isFine=0,fl.debit,0)) AS ledgerDebit, SUM( IF(fl.isFine=0,fl.credit,0)) AS ledgerCredit,
				    SUM(DISTINCT IF(fl.isFine=1,fl.debit,0)) AS ledgerDebitFine,SUM(DISTINCT IF(fl.isFine=1,fl.credit,0)) AS ledgerCreditFine,
				   (IFNULL(hs.hostelCharges,0) + IFNULL(hs.securityAmount,0)) AS hostelCharges,hs.securityAmount,IFNULL(frd.hostelFine,0) AS fine,
				    (SUM(IFNULL(fl.debit,0)) - SUM(IFNULL(fl.credit,0))+ IFNULL(hs.hostelCharges,0) + IFNULL(hs.securityAmount,0)) AS totalFees
				FROM
				    student s 
				    INNER JOIN hostel_students hs  ON hs.studentId = s.studentId 
				    INNER JOIN class c ON hs.classId = c.classId
				    LEFT JOIN fee_receipt_details frd ON frd.studentId = s.studentId AND frd.classId = c.classId  AND frd.isDelete=0 AND  $ttFeeType	
				    LEFT JOIN fee_ledger_debit_credit fl ON fl.classId = c.classId AND fl.studentId = s.studentId AND fl.ledgerTypeId=3
				WHERE
				       s.studentId = '$ttStudentId'						
					AND    c.classId= '$ttClassId'						
						 $condition		 
				GROUP BY 
				    s.studentId, c.classId";
    	
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
  
    
     public function getPendingAcademicFee($ttStudentId='',$ttClassId='',$condition=''){
      	 global $sessionHandler;
    	$query ="SELECT 	
    					(IFNULL(fl.debit,0) - IFNULL(fl.credit,0)+ IFNULL(sum(fri.amount),0) - IFNULL(frm.concession,0)) AS totalFees,
               		    IFNULL(sum(fri.amount),0) AS academicFees, sum(frd.amount) AS paidAmount,frd.feeType,frd.receiptNo,
               		 	frd.receiptDate,frd.paidAt,	frd.academicFeePaid,
    					frd.academicFine,IFNULL(frd.academicFine,0) AS fine,frd.academicFinePaid,frd.isDelete,
    					c.className,CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo,s.studentId,frd.classId,    					
		       			SUM(IF(fl.ledgerTypeId =1,fl.debit,0)) AS ledgerDebit, 
		       			 SUM(IF(fl.ledgerTypeId =1,fl.credit,0)) AS ledgerCredit,frm.concession
				FROM  
						class c,`student` s , fee_receipt_details frd,
						`fee_receipt_master` frm LEFT JOIN `fee_receipt_instrument` fri ON frm.feeReceiptId = fri.feeReceiptId 
						AND frm.studentId = fri.studentId AND	fri.classId = frm.feeClassId  AND	frm.status = 1
						LEFT JOIN `fee_ledger_debit_credit` fl	ON	frm.studentId = fl.studentId AND	frm.feeClassId = fl.classId
						AND	frm.instituteId  = fl.instituteId	AND fl.ledgerTypeId =1
							 	
				WHERE	
						frm.studentId = s.studentId
						AND	frm.feeClassId = c.classId						
						AND	frm.status = 1
						AND frm.feeClassId = frd.classId 
						AND	frm.feeReceiptId = frd.feeReceiptId AND	frd.studentId = frm.studentId
						AND frd.isDelete = 0 AND frd.feeType IN(1,4)
						AND  s.studentId = '$ttStudentId'						
						AND c.classId= '$ttClassId'
						$condition
				GROUP BY frm.studentId,frm.feeClassId,fri.feeReceiptId ";   			
			
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getStudentAllFeeCount($feeType='1',$isPaid='',$classId='',$condition='') {  
    
        global $sessionHandler;
        
        if($classId=='') {
          $classId='0';  
        }
        if($isPaid=='0') {  // Paid
          $isPaid = " AND IFNULL(frd.feeReceiptId,0) > 0 ";  
        }
        else if($isPaid=='1') {  // unPaid
          $isPaid = " AND IFNULL(frd.feeReceiptId,0) = 0 ";   
        }
        
        
        $returnArray1 = array();
        $returnArray2 = array();
        $returnArray3 = array();
        $valueArray = array();
        
        if($feeType=='1' || $feeType=='4') {        
            
            if($feeType=='1') {
              $ttFeeType = "frd.feeType = '1' "; 
            }
            else {
              $ttFeeType = "frd.feeType IN (1,4) "; 
            }
            
            // Academic
            $query ="SELECT 
                         DISTINCT CONCAT_WS(',',s.studentId, f.classId) AS studentClassId
                     FROM 
                         fee_head_values_new f 
                         INNER JOIN class c ON c.classId = f.classId 
                         INNER JOIN study_period sp ON sp.studyPeriodId = c.studyPeriodId 
                         LEFT JOIN student s ON 
                         IF(s.migrationStudyPeriod=0,
                           ((s.isLeet = 0 AND sp.periodValue<=2) OR sp.periodValue >2), (sp.periodValue>s.migrationStudyPeriod)
                         ) AND (INSTR(s.sAllClass,CONCAT('~',f.classId,'~'))>0) $condition
                         LEFT JOIN fee_receipt_details frd ON (s.studentId = frd.studentId AND frd.isDelete = 0 AND
                         $ttFeeType AND frd.classId = f.classId AND INSTR(s.sAllClass,CONCAT('~',frd.classId,'~'))>0) 
                     WHERE
                         f.classId IN ($classId) 
                         $isPaid 
                          
                     ORDER BY 
                         s.studentId, f.classId ";                        
            $returnArray1 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }
        
        if($feeType=='2' || $feeType=='4') {
            
            if($feeType=='2') {
              $ttFeeType = "frd.feeType = '2' "; 
            }
            else {
              $ttFeeType = "frd.feeType IN (2,4) "; 
            }
            
            // Transport
            $query ="SELECT 
                         DISTINCT CONCAT_WS(',',s.studentId, f.classId) AS studentClassId
                     FROM 
                         bus_route_student_mapping f
                         INNER JOIN class c ON c.classId = f.classId 
                         INNER JOIN study_period sp ON sp.studyPeriodId = c.studyPeriodId 
                         LEFT JOIN student s ON (INSTR(s.sAllClass,CONCAT('~',f.classId,'~'))>0) $condition
                         LEFT JOIN fee_receipt_details frd ON (s.studentId = frd.studentId AND frd.isDelete = 0 AND
                         $ttFeeType AND frd.classId = f.classId AND INSTR(s.sAllClass,CONCAT('~',frd.classId,'~'))>0) 
                     WHERE
                         f.classId IN ($classId) 
                         $isPaid 
                          
                     ORDER BY 
                         s.studentId, f.classId ";
            $returnArray2 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }
        
        if($feeType=='3' || $feeType=='4') {
            
            if($feeType=='3') {
              $ttFeeType = "frd.feeType = '3' "; 
            }
            else {
              $ttFeeType = "frd.feeType IN (3,4) "; 
            }
            
            // Hostel
            $query ="SELECT 
                         DISTINCT CONCAT_WS(',',s.studentId, f.classId) AS studentClassId
                     FROM 
                         hostel_students f 
                         INNER JOIN class c ON c.classId = f.classId 
                         INNER JOIN study_period sp ON sp.studyPeriodId = c.studyPeriodId 
                         LEFT JOIN student s ON (INSTR(s.sAllClass,CONCAT('~',f.classId,'~'))>0) $condition
                         LEFT JOIN fee_receipt_details frd ON (s.studentId = frd.studentId AND frd.isDelete = 0 AND
                         $ttFeeType AND frd.classId = f.classId AND INSTR(s.sAllClass,CONCAT('~',frd.classId,'~'))>0) 
                     WHERE
                         f.classId IN ($classId) 
                         $isPaid 
                          
                     ORDER BY 
                         s.studentId, f.classId ";
            $returnArray3 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }
        
        for($i=0;$i<count($returnArray1);$i++) {
          $valueArray[] = $returnArray1[$i]['studentClassId'];
        }
        for($i=0;$i<count($returnArray2);$i++) {
          $valueArray[] = $returnArray2[$i]['studentClassId'];
        }
        for($i=0;$i<count($returnArray3);$i++) {
          $valueArray[] = $returnArray3[$i]['studentClassId'];
        }
        
        if(count($valueArray)>0) {
          $resultArray = array_unique($valueArray);
          $valueArray = array_values($resultArray);
        }
        
        return  $valueArray;
    }
    
    public function getStudentDetails($ttStudentId='',$ttClassId='',$condition=''){	
  global $sessionHandler;
    	$query =" SELECT 
    					c.className,CONCAT(s.firstName,' ',s.lastName) AS studentName, s.rollNo,s.regNo,s.studentId,c.classId,s.universityRollNo 
		       		FROM
						`class` c,`student` s						 				
					WHERE 
                           s.studentId = '$ttStudentId'						
						   AND 	c.classId= '$ttClassId'
						 $condition		
					 GROUP BY s.studentId	";
    	
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }      
    
    // Academic Fee Detail
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
                    fri.studentId, fri.feeClassId, SUM(fri.concession) AS concession
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
                     SUM( IF(fri.isFine=0,fri.debit,0)) AS acdDebit,  SUM( IF(fri.isFine=0,fri.credit,0)) AS acdCredit,
                    SUM( IF(fri.isFine>0,fri.debit,0)) AS fineDebit,  SUM( IF(fri.isFine>0,fri.credit,0)) AS fineCredit
                 FROM
                    fee_ledger_debit_credit fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.ledgerTypeId = 1                       
                 $condition ";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function getTotalAcademicPaidFee($ttStudentId='',$ttClassId='',$feeType='',$condition=''){
   
        global $sessionHandler;
        
        if($feeType=='1') {
          $ttFeeType = "AND fri.feeType = '1' "; 
        }
        else {
          $ttFeeType = "AND fri.feeType IN (1,4) "; 
        }        

        $query ="SELECT
                    SUM(IFNULL(fri.amount,0)) AS paidAmount
                 FROM
                    fee_receipt_details fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.isDelete = 0
                    $ttFeeType      
                 $condition 
                 GROUP BY
                     fri.studentId, fri.classId, fri.receiptNo ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function getTotalAcademicPaidFine($ttStudentId='',$ttClassId='',$feeType='',$condition=''){
   
        global $sessionHandler;
        
        if($feeType=='1') {
          $ttFeeType = "AND fri.feeType = '1' "; 
        }
        else {
          $ttFeeType = "AND fri.feeType IN (1,4) "; 
        }        

        $query ="SELECT
                    SUM(IFNULL(fri.academicFine,0)) AS paidFine
                 FROM
                    fee_receipt_details fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.isDelete = 0
                    $ttFeeType      
                 $condition 
                 GROUP BY
                     fri.studentId, fri.classId, fri.receiptNo ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   //Hostel Fees
    public function getTotalHostelHeadFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                    DISTINCT fri.studentId, fri.classId, fri.hostelCharges AS hostelFees,
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
                     SUM( IF(fri.isFine=0,fri.debit,0)) AS hostDebit,
                     SUM( IF(fri.isFine=0,fri.credit,0)) AS hostCredit,
                   	 SUM( IF(fri.isFine>0,fri.debit,0)) AS finehostDebit,
                      SUM( IF(fri.isFine>0,fri.credit,0)) AS finehostCredit
                 FROM
                    fee_ledger_debit_credit fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.ledgerTypeId = 3                       
                 $condition ";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function getTotalHostelPaidFee($ttStudentId='',$ttClassId='',$feeType='',$condition=''){
   
        global $sessionHandler;
        
        if($feeType=='3') {
          $ttFeeType = "AND fri.feeType = '3' "; 
        }
        else {
          $ttFeeType = "AND fri.feeType IN (3,4) "; 
        }        

        $query ="SELECT
                    SUM(IFNULL(fri.amount,0)) AS paidAmount
                 FROM
                    fee_receipt_details fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.isDelete = 0
                    $ttFeeType      
                 $condition 
                 GROUP BY
                     fri.studentId, fri.classId, fri.receiptNo ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
	
	public function getTotalHostelPaidFine($ttStudentId='',$ttClassId='',$feeType='',$condition=''){
   
        global $sessionHandler;
        
        if($feeType=='3') {
          $ttFeeType = "AND fri.feeType = '3' "; 
        }
        else {
          $ttFeeType = "AND fri.feeType IN (3,4) "; 
        }        

        $query ="SELECT
                    SUM(IFNULL(fri.hostelFine,0)) AS paidFine
                 FROM
                    fee_receipt_details fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.isDelete = 0
                    $ttFeeType      
                 $condition 
                 GROUP BY
                     fri.studentId, fri.classId, fri.receiptNo ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	//Transport Fee
	 public function getTotalTransportHeadFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                    DISTINCT fri.studentId, fri.classId, fri.routeCharges AS transportFees
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
                     SUM( IF(fri.isFine=0,fri.debit,0)) AS transDebit,
                     SUM( IF(fri.isFine=0,fri.credit,0)) AS transCredit,
                   	 SUM( IF(fri.isFine>0,fri.debit,0)) AS finetransDebit,
                      SUM( IF(fri.isFine>0,fri.credit,0)) AS finetransCredit
                 FROM
                    fee_ledger_debit_credit fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.ledgerTypeId = 2                       
                 $condition ";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function getTotalTransportPaidFee($ttStudentId='',$ttClassId='',$feeType='',$condition=''){
   
        global $sessionHandler;
        
        if($feeType=='2') {
          $ttFeeType = "AND fri.feeType = '2' "; 
        }
        else {
          $ttFeeType = "AND fri.feeType IN (2,4) "; 
        }        

        $query ="SELECT
                    SUM(fri.amount) AS paidAmount 
                 FROM
                    fee_receipt_details fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.isDelete = 0
                    $ttFeeType      
                 $condition 
                 GROUP BY
                     fri.studentId, fri.classId";
    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
	
	public function getTotalTransportPaidFine($ttStudentId='',$ttClassId='',$feeType='',$condition=''){
   
        global $sessionHandler;
        
        if($feeType=='2') {
          $ttFeeType = "AND fri.feeType = '2' "; 
        }
        else {
          $ttFeeType = "AND fri.feeType IN (2,4) "; 
        }        

        $query ="SELECT
               		SUM(fri.tranportFine) AS paidFine
                 FROM
                    fee_receipt_details fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.isDelete = 0
                    $ttFeeType      
                 $condition 
                 GROUP BY
                     fri.studentId, fri.classId, fri.receiptNo ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}

//History : $

?>
