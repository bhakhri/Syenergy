<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "FeeLedgerManager" 
//
// Author :Nishu Bindal
// Created on : (28.03.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeeLedgerManager {
	private static $instance = null;
	
//----------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF  CLASS
//
// Author :Nishu Bindal
// Created on : (28.03.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
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
// THIS FUNCTION IS USED FOR Student FEE DETAILS
// Author :NISHU BINDAL
// Created on : (28.MAR.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------      
    public function getStudentLedger($regRollNo) {          
        $query = "
        	SELECT
                       IFNULL(a.feeLedgerDebitCreditId,'') AS feeLedgerDebitCreditId,a.feeReceiptId,a.concession,a.hostelFees,
                       a.transportFees, a.debit, a.credit,a.className,a.studentName, a.fatherName,
                       a.rollNo,a.studentId,a.feeClassId,a.comments,a.date, a.feeType,a.feeCycleId,
                       a.cycleName,a.hostelSecurity,a.receiptNo
                 FROM (
			     SELECT
		               '' AS feeLedgerDebitCreditId,frm.feeReceiptId,frm.concession,frm.hostelFees,
		               frm.transportFees, sum(fri.amount) AS debit, '' AS credit,c.className,
                       concat(s.firstName,' ',s.lastName) AS studentName, s.fatherName,s.rollNo,s.studentId,frm.feeClassId,'' AS comments,
                       frm.dated AS date, '' As feeType,frm.feeCycleId,fc.cycleName,frm.hostelSecurity,'' AS receiptNo
		          FROM 
		          	`student` s,`fee_receipt_instrument` fri,`class` c,`fee_receipt_master` frm,`fee_cycle_new` fc 
		          WHERE	frm.feeReceiptId  = fri.feeReceiptId
		          AND	frm.studentId = fri.studentId
		          AND	s.studentId = frm.studentId
		          AND	s.studentId = fri.studentId
		          AND	fri.classId = frm.feeClassId
		          AND	c.classId = frm.feeClassId
		          AND	fri.classId = c.classId
		          AND	fc.feeCycleId = frm.feeCycleId
		          AND	frm.status =1
		          AND	(s.rollNo LIKE '$regRollNo' OR s.regNo LIKE '$regRollNo')
		          GROUP BY fri.feeReceiptId
		         
		          
		          UNION
		          
		          SELECT 
		          	fl.feeLedgerDebitCreditId,'','','','',fl.debit,fl.credit,c.className,
                    concat(s.firstName,' ',s.lastName) AS studentName, s.fatherName,s.rollNo,s.studentId,
                    fl.classId As feeClassId,fl.comments,fl.date,'' As feeType, 
                    IFNULL(fl.feeCycleId,'') AS feeCycleId, '' AS cycleName,
                    '','' AS receiptNo
		          FROM 
		          	`fee_ledger_debit_credit` fl, class c, `student` s
		          WHERE
		          		c.classId = fl.classId
		          	AND	s.studentId = fl.studentId
		          	AND	fl.status <> 3
		        	AND	(s.rollNo LIKE '$regRollNo' OR s.regNo LIKE '$regRollNo')
		       	UNION
  		        SELECT
		               DISTINCT '' AS feeLedgerDebitCreditId,'' AS feeReceiptId,'' As concession,'' AS hostelFees,
		               '' AS transportFees, '' AS debit, sum(frd.amount) AS credit,c.className,
                       concat(s.firstName,' ',s.lastName) AS studentName, s.fatherName,s.rollNo,s.studentId,
                       frm.feeClassId,'' AS comments,frd.receiptDate AS date,frd.feeType,
                       frm.feeCycleId,fc.cycleName,'',frd.receiptNo
		         FROM 
		          	`student` s,`class` c,`fee_receipt_details` frd,`fee_cycle_new` fc ,`fee_receipt_master` frm 
		          WHERE	frm.studentId =  frd.studentId
		          AND	frd.feeReceiptId = frm.feeReceiptId
		          AND	frm.feeClassId = frd.classId
		          AND	s.studentId = frm.studentId
		          AND	c.classId = frm.feeClassId
		          AND	frm.status =1
		          AND	fc.feeCycleId = frm.feeCycleId
		          AND	frd.isDelete = 0
		          AND	(s.rollNo LIKE '$regRollNo' OR s.regNo LIKE '$regRollNo')
		          GROUP BY frd.feeReceiptId, frd.receiptNo, frd.installmentNo
		    ) AS a
            ORDER BY 
               a.feeClassId, a.date ASC";
              
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

    }
    
    
    //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT DBIT/CREDIT
// Author :NISHU BINDAL
// Created on : (28.MAR.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    public function addDebitCredit($classId,$studentId,$comments,$debit,$credit,$feeCycleId,$ledgerTypeId) {
        
        global $sessionHandler;
        $comments = addslashes($comments);
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $userId = $sessionHandler->getSessionVariable('UserId');
        
        $query = "INSERT INTO `fee_ledger_debit_credit` (feeLedgerDebitCreditId,classId,studentId,comments,date,debit,credit,instituteId,feeCycleId,userId,ledgerTypeId)
        		VALUES ('','$classId','$studentId','$comments',now(),'$debit','$credit','$instituteId','$feeCycleId',$userId,$ledgerTypeId)";
   
       return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

	 public function editDebitCredit($classId,$studentId,$comments,$debit,$credit,$feeCycleId,$feeLedgerDebitCreditId,$ledgerTypeId,$isFine) {
        
        global $sessionHandler;
        $comments = addslashes($comments);
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $userId = $sessionHandler->getSessionVariable('UserId');
		if($isFine==1){
		$isFine=2;	
		}
        $query ="UPDATE	
				`fee_ledger_debit_credit` 
			SET	classId = '$classId',
  				ledgerTypeId = '$ledgerTypeId', 
				studentId = '$studentId', 
				comments = '$comments',
				date = now(), 
				debit = '$debit',
				credit = '$credit',
				userId = '$userId', 
				instituteId = '$instituteId', 
				feeCycleId = '$feeCycleId' ,
				isFine = '$isFine'
			WHERE	
				feeLedgerDebitCreditId = '$feeLedgerDebitCreditId'";
			
		return SystemDatabaseManager::getInstance()->executeUpdate($query);
   
    }

	  public function getFeeLedgerValues($feeLedgerDebitCreditId) {   
        $query = "
        	SELECT
                       feeLedgerDebitCreditId,classId,studentId,comments,date,debit,credit,instituteId,feeCycleId,
			userId,ledgerTypeId,isFine
                 FROM 
			fee_ledger_debit_credit 
		WHERE
			feeLedgerDebitCreditId = $feeLedgerDebitCreditId
			";
		
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}
	
	  public function deleteFeeLedger($feeLedgerDebitCreditId) {   
        $query = "DELETE
                 FROM 
			fee_ledger_debit_credit 
		WHERE
			feeLedgerDebitCreditId = $feeLedgerDebitCreditId
			";
		
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

}
    
    public function fetchClases($rollNo = ''){
        $query ="SELECT 
                     classId,className FROM class
                 WHERE 
                     CONCAT(degreeId,'~',batchId,'~',branchId) LIKE
                    (SELECT 
                        DISTINCT CONCAT(cc.degreeId,'~',cc.batchId,'~',cc.branchId) 
                     FROM 
                        student s, class cc 
                     WHERE 
                        cc.classId = s.classId AND (s.rollNo LIKE '$rollNo' OR s.regNo LIKE '$rollNo'  OR s.universityRollNo LIKE '$rollNo'))
                     ORDER BY className Asc";
            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        
    }
    
    public function fetchStudentId($rollNo = ''){
        
        $query ="SELECT 
                    DISTINCT s.studentId, IFNULL(s.sAllClass,'') AS sAllClass
                 FROM 
                    student s
                 WHERE 
                    (s.rollNo LIKE '$rollNo' OR s.regNo LIKE '$rollNo' OR s.universityRollNo LIKE '$rollNo')
                 LIMIT 0,1";
            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
      public function getGenerateStudentFeeValue($ttStudentId='',$ttClassId=''){
		  $query ="SELECT
               		*
                 FROM
                    `generate_student_fee` fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                   ";
  
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		
	}
	public function updateGenerateStudentFeeValue($ttStudentId='',$ttClassId='',$strQuery=''){
			$query = "UPDATE `generate_student_fee` 
    				SET	$strQuery 
    				WHERE	
    					studentId = '$ttStudentId'
    				AND	classId = '$ttClassId'
    				";
    		
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);		
			
		
	}
	public function insertGenerateStudentFeeValue($strQuery=''){
			$query = "INSERT INTO
							 `generate_student_fee` 
    				SET	
    						$strQuery ";
						  
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);		
			
		
	} 
	 public function checkFeeLedger($studentId,$classId,$ledgerTypeId) {   
        $query = "
        		SELECT
                       feeLedgerDebitCreditId,classId,studentId,comments,date,debit,credit,instituteId,feeCycleId,
						userId,ledgerTypeId,isFine
                FROM 
						fee_ledger_debit_credit 
				WHERE
					studentId  ='$studentId' AND
					classId ='$classId' AND
					ledgerTypeId = '$ledgerTypeId' AND
					isFine IN(1,2)
					";
	
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}


 public function getStudentLedgerNew($regRollNo) {          
        $query = "
            SELECT
                       IFNULL(a.feeLedgerDebitCreditId,'') AS feeLedgerDebitCreditId,a.feeReceiptId,a.concession,a.hostelFees,
                       a.transportFees, a.debit, a.credit,a.className,a.studentName, a.fatherName,
                       a.rollNo,a.studentId,a.feeClassId,a.comments,a.date, a.feeType,a.feeCycleId,
                       a.cycleName,a.hostelSecurity,a.receiptNo
                 FROM (
                 SELECT
                        '' AS feeLedgerDebitCreditId,frm.feeReceiptId,frm.concession,frm.hostelFees,
                        frm.transportFees, SUM(fri.amount) AS debit, '' AS credit,c.className,
                        CONCAT(s.firstName,' ',s.lastName) AS studentName, s.fatherName,s.rollNo,s.studentId,frm.feeClassId,'' AS comments,
                        frm.dated AS DATE, '' AS feeType,frm.feeCycleId,fc.cycleName,frm.hostelSecurity,'' AS receiptNo
                 FROM 
                        student s,class c,fee_cycle_new fc,
                        fee_receipt_master frm LEFT JOIN  fee_receipt_instrument fri ON 
                        (frm.studentId = fri.studentId AND frm.feeClassId = fri.classId AND frm.feeReceiptId  = fri.feeReceiptId) 
                 WHERE      
                        s.studentId = frm.studentId
                        AND c.classId = frm.feeClassId
                        AND fc.feeCycleId = frm.feeCycleId
                        AND frm.status =1
                        AND (s.rollNo LIKE '$regRollNo' OR s.regNo LIKE '$regRollNo')
                  GROUP BY 
                        frm.feeReceiptId
                  UNION
                  SELECT 
                      fl.feeLedgerDebitCreditId,'','','','',fl.debit,fl.credit,c.className,
                    concat(s.firstName,' ',s.lastName) AS studentName, s.fatherName,s.rollNo,s.studentId,
                    fl.classId As feeClassId,fl.comments,fl.date,'' As feeType, 
                    IFNULL(fl.feeCycleId,'') AS feeCycleId, '' AS cycleName,
                    '','' AS receiptNo
                  FROM 
                      `fee_ledger_debit_credit` fl, class c, `student` s
                  WHERE
                          c.classId = fl.classId
                      AND    s.studentId = fl.studentId
                      AND    fl.status <> 3
                    AND    (s.rollNo LIKE '$regRollNo' OR s.regNo LIKE '$regRollNo')
                   UNION
                  SELECT
                       DISTINCT '' AS feeLedgerDebitCreditId,'' AS feeReceiptId,'' As concession,'' AS hostelFees,
                       '' AS transportFees, '' AS debit, sum(frd.amount) AS credit,c.className,
                       concat(s.firstName,' ',s.lastName) AS studentName, s.fatherName,s.rollNo,s.studentId,
                       frm.feeClassId,'' AS comments,frd.receiptDate AS date,frd.feeType,
                       frm.feeCycleId,fc.cycleName,'',frd.receiptNo
                 FROM 
                      `student` s,`class` c,`fee_receipt_details` frd,`fee_cycle_new` fc ,`fee_receipt_master` frm 
                  WHERE    frm.studentId =  frd.studentId
                  AND    frd.feeReceiptId = frm.feeReceiptId
                  AND    frm.feeClassId = frd.classId
                  AND    s.studentId = frm.studentId
                  AND    c.classId = frm.feeClassId
                  AND    frm.status =1
                  AND    fc.feeCycleId = frm.feeCycleId
                  AND    frd.isDelete = 0
                  AND    (s.rollNo LIKE '$regRollNo' OR s.regNo LIKE '$regRollNo')
                  GROUP BY frd.feeReceiptId, frd.receiptNo, frd.installmentNo
            ) AS a
            ORDER BY 
               a.feeClassId, a.date ASC";
              
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

    }
           
}
?>
