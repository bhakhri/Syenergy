<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "student and teacher_comment" TABLE
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class StudentFeeManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "StudentConcessionManager" CLASS
//
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct(){
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "StudentConcessionManager" CLASS
//
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
    
    
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to fetch student Fee Class
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------
    public function getFeeClass($feeClassId){
    	global $sessionHandler;
        $studentId = $sessionHandler->getSessionVariable('StudentId');
        $classId = $sessionHandler->getSessionVariable('ClassId');
        
    	$query = "SELECT 
		classId  AS feeClass, studyPeriodId ,batchId
		FROM 
			class cc
		WHERE
			cc.classId = '$feeClassId'";
		
	 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
         
    public function getCheckStudentConcession($condition) {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                       COUNT(*) AS cnt
                  FROM
                       fee_student_concession_mapping
                  WHERE    
                       $condition ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to fetch student all clases
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    
    public function fetchClases($rollNo = ''){
    	$query ="SELECT classId,className FROM class
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
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to INSERT INTO FEE_RECEIPT
// Author :Nishu Bindal
// Created on : (4.Mar.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    public function insertFeeReceiptTime($feeReceiptId){
    	  global $sessionHandler;
          $userId = $sessionHandler->getSessionVariable('UserId');
        
    	$query ="UPDATE	`fee_receipt_master` 
    		SET	receiptGeneratedDate = now(),
    			userId = '$userId'
    		WHERE	feeReceiptId = '$feeReceiptId'";
    					 
    	 return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }
    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to GET STUDENT FEE RECEIPT COUNT 
// Author :Nishu Bindal
// Created on : (4.Mar.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
   public function getCountOfReceiptNo(){
   	$query = "SELECT COUNT(feeReceiptId) AS noOfReceipt 
   				FROM	`fee_receipt_master`
					WHERE	
						feeReceiptNo <> ''
					";
   	
   	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to Check Receipt No already exists 
// Author :Nishu Bindal
// Created on : (4.Mar.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
   public function checkReceiptNo($receiptNo){
   	$query ="SELECT COUNT(feeReceiptId) AS cnt FROM `fee_receipt_master` WHERE feeReceiptNo = '$receiptNo' ";
   	
   	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

   
   //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to GET STUDENT Fee Details
// Author :Nishu Bindal
// Created on : (22.Mar.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    public function getStudentFeeDetails($studentId,$classId,$feeClassId){
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
				AND	s.studentId = frm.studentId
				AND	frm.feeClassId = '$feeClassId'
				AND	c.classId = frm.feeClassId
				AND	sp.studyPeriodId = c.studyPeriodId
				AND	c.batchId = b.batchId
				AND	c.degreeId = d.degreeId
				AND	c.branchId = br.branchId
				AND	bk.bankId = frm.bankId
				AND	frm.status = 1";
				
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
	  public function getStudentPreviousFeeDetails($condition=''){
	$query =" SELECT 
                a.studentName, a.branchName, a.periodName, 
                IFNULL(a.rollNo,'".NOT_APPLICABLE_STRING."') AS rollNo, 
                IFNULL(a.regNo,'".NOT_APPLICABLE_STRING."') AS regNo, 
                SUM(a.cashAmount) as cashAmount, SUM(a.checkAmount) As checkAmount, 
                SUM(a.DDAmount) As DDAmount,a.feeClassId,a.studentId,a.receiptDate,a.receiptNo
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
                        AND    frd.paymentMode IN(1,4)
                        AND    frm.status = 1
                        AND    frd.isDelete = 0
                        $condition
                        GROUP BY frd.receiptNo,frd.classId,frd.studentId,frd.paymentMode 
                        
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
                        GROUP BY frd.receiptNo,frd.classId,frd.studentId,frd.paymentMode 
                        
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
                        GROUP BY frd.receiptNo,frd.classId,frd.studentId,frd.paymentMode
            ) AS a
           
            GROUP By a.receiptNo,a.studentId, a.feeClassId
            
            ";
		
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to GET STUDENT Fee Details
// Author :Nishu Bindal
// Created on : (22.Mar.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------   
   public function getLedgerData($studentId='',$feeCycleId='',$feeClassId='',$ledgerTypeId='' ){
	
    global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
    
    if($ledgerTypeId=='') {
      $ledgerTypeId='0';  
    }
	
   	$query = "SELECT 
                    comments,debit,credit
   			FROM	
                    `fee_ledger_debit_credit`
   			WHERE	
                    studentId = '$studentId'
             		AND	classId = '$feeClassId'
   			        AND	instituteId = '$instituteId'
                    AND ledgerTypeId IN ($ledgerTypeId)
   			        AND	status <> 3";
   			
   	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }


   public function getFineSetUpDetails($classId='',$feeFineTypeId=''){
	
  	
   	 $query = "SELECT 
                   feeFineId, classId, feeFineTypeId, fromDate, toDate, chargesFormat, charges
	          FROM 
			      `fee_fine_new`
		      WHERE
                   classId = '$classId' AND
                   feeFineTypeId = '$feeFineTypeId' 
              ORDER BY
                   fromDate DESC";
   			
   	 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

   public function updateFineLedgerData($studentId='',$feeClassId='',$feeType='',$fineCharges='',$feeCycleId='' ) {
	
        global $sessionHandler;
	    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    	$userId = $sessionHandler->getSessionVariable('UserId');
	    $comments = 'Late Fee Fine';

    	$query = "INSERT INTO `fee_ledger_debit_credit`
		          SET
			        studentId = '$studentId',
			        classId = '$feeClassId',
			        debit = '$fineCharges',
			        comments = '$comments',
			        instituteId = '$instituteId',
			        userId = '$userId',
			        feeCycleId = '$feeCycleId',
			        date = now(),
			        isFine = '1',
			        ledgerTypeId ='$feeType'";
   		
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    	//return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

   public function deleteFineLedgerData($studentId='',$feeClassId='',$feeType=''){
	
      	$query = "DELETE FROM `fee_ledger_debit_credit`
                  WHERE 
                        studentId = '$studentId' AND 
                        classId = '$feeClassId' AND			
	                isFine = '1' AND 
                        ledgerTypeId ='$feeType'";
   		
   	    return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
   }
   
   public function getCheckStudentFine($condition='') {
        
       $query = "SELECT 
                       DISTINCT frd.studentId, frd.classId 
                 FROM 
                       `fee_receipt_details` frd
                  WHERE 
                       frd.isDelete = 0 
                  $condition";
           
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
   public function getLedgerCheckFine($condition='') {
        
       $query = "SELECT 
                       DISTINCT frd.studentId, frd.classId, frd.isFine 
                 FROM 
                       `fee_ledger_debit_credit` frd
                  WHERE                        
                  $condition";
         
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
   public function getStudentId($condition='') {
        
       $query = "SELECT 
                       DISTINCT rollNo, classId, studentId 
                 FROM 
                       student
                  WHERE                        
                       $condition";
           
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
   public function applyFineAcd($studentId='',$classId='') {
        
       $query = "SELECT 
                       DISTINCT classId
                 FROM 
                       fee_head_values_new
                 WHERE                        
                       classId = '$classId' ";
           
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
   public function applyFineTransport($studentId='',$classId='') {
        
       $query = "SELECT 
                       DISTINCT studentId , classId  
                 FROM 
                       bus_route_student_mapping
                  WHERE                        
                       studentId='$studentId' AND
                       classId = '$classId'";
           
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
   public function applyFineHostel($studentId='',$classId='') {
        
       $query = "SELECT 
                       DISTINCT studentId,classId  
                 FROM 
                       hostel_students
                  WHERE                        
                       studentId='$studentId' AND
                       classId = '$classId'";
           
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
  
  
  public function insertFeeSlipGenerate($studentId='',$feeClassId='',$feeSlipDetail='',$comment='',$paymentMode='',$ddNo='',$ddDate='',$ddBankName='') {
	
        global $sessionHandler;
	    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    	$userId = $sessionHandler->getSessionVariable('UserId');
	    
    	$query = "INSERT INTO `generate_fee_slip`
		          SET
			        studentId = '$studentId',
			        classId = '$feeClassId',
			        feeSlipDetail = '$feeSlipDetail',
			        userId = '$userId',		
                    slipComment = '$comment',	
                    paymentMode = '$paymentMode',
			        ddNo = '$ddNo',
			        ddDate = '$ddDate',		
                    ddBankName = '$ddBankName',        
			        dateOfEntry = now()";
   
    	return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
   } 
  public function getPreviousPeriodValue($studentId='',$feeClassPeriodValue='') {
        
       $query = "SELECT 
						 DISTINCT cc.classId ,sp.periodValue,cc.className,sp.periodName
                 FROM 
                     	class cc , study_period sp   
                WHERE 
	                   cc.studyPeriodId = sp.studyPeriodId
	                   AND sp.periodValue < '$feeClassPeriodValue'  
	                    AND 
	                     CONCAT_WS(',',cc.batchId,cc.degreeId,cc.branchId) IN 
	                     (SELECT 
	                          DISTINCT CONCAT_WS(',',c.batchId,c.degreeId,c.branchId) 
	                      FROM 
	                         student s,class c WHERE c.classId=s.classId AND s.studentId = '$studentId')
                ORDER BY 
                   CAST(sp.periodValue AS SIGNED) ASC";
           
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
    public function getFeeClassPeriodValue($classId='') {
        
       $query = "SELECT 
					DISTINCT cc.classId, sp.periodValue,cc.className
                 FROM 
                    class cc, study_period sp   
                 WHERE 
	                cc.studyPeriodId = sp.studyPeriodId AND cc.classId= '$classId'	                     
                 ORDER BY 
                    CAST(sp.periodValue AS SIGNED) ASC ";
           
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
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
                    fee_receipt_instrument fri LEFT JOIN fee_receipt_master frm ON frm.studentId = fri.studentId AND frm.feeClassId = fri.classId AND frm.status = 1
                    LEFT JOIN hostel_students hs ON hs.studentId = fri.studentId AND hs.classId = fri.classId 
                    LEFT JOIN fee_ledger_debit_credit fl ON fl.studentId = fri.studentId AND fl.classId = fri.classId AND ledgerTypeId IN(1,2,3)
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
                    SUM(fri.amount) AS paidAcademicAmount 
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
                    SUM(fri.amount) AS paidHostelAmount 
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
                    SUM(fri.amount) AS paidTransportAmount 
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
    
     
}
// $History: StudentConcessionManager.inc.php $
?>
