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

//--------------------------------------------------------------
//  THIS FUNCTION IS STUDENT REGISTRATION FORM  ADD/EDIT
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------
    public function editStudentRegistration($queryFormat,$studentId,$currentClassId,$instituteId,$cgpa,$majorConcentration,$condition='',$confirmId='',$regDate='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $fieldFilter = '';
        if($confirmId!='') {
          $fieldFilter = ", confirmId = '$confirmId'";
        }

        if($regDate!='') {
          $fieldFilter .= ", `registrationDate` = NOW()";
        }

        $query = "$queryFormat `student_registration_master` SET
                  studentId = '$studentId' ,
                  currentClassId = '$currentClassId' ,
                  instituteId = '$instituteId' ,
                  cgpa = '$cgpa',
                  majorConcentration = '$majorConcentration'
                  $fieldFilter
                  $condition ";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

	 public function getMappedSubjects($classId, $subjectId) {
		 $query = "SELECT a.subjectId, b.subjectCode from optional_subject_to_class a, subject b where a.classId = $classId and a.parentOfSubjectId = $subjectId and a.subjectId = b.subjectId";
        return SystemDatabaseManager::getInstance()->executeQuery($query);
	 }

//--------------------------------------------------------------
//  THIS FUNCTION IS STUDENT REGISTRATION FORM  ADD
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------
    public function addStudentRegistration($filter='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "INSERT INTO `student_registration_detail`
                  (registrationId,classId,subjectId,credits,subjectType) VALUES
                  $filter";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//--------------------------------------------------------------
//  THIS FUNCTION IS STUDENT REGISTRATION FORM  Delete
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------
    public function deleteStudentRegistration($tableName='',$condition='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "DELETE FROM $tableName $condition";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

	public function getTestTypeData($condition ='') {

		$query = "SELECT
						DISTINCT
								tt.testTypeCategoryId, ttc.testTypeName
				  FROM
						test_type_category ttc, test_type tt

					  $condition
				  AND
					  ttc.testTypeCategoryId = tt.testTypeCategoryId ";


		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}



//--------------------------------------------------------------
//  THIS FUNCTION IS STUDENT REGISTRATION FORM  Delete
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------
    public function getStudentRegistration($condition='',$orderBy=' d.detailRegistrationId',$filter='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        if($filter=='') {
           $filter = "DISTINCT m.registrationId, m.studentId, m.currentClassId, m.instituteId,
                               IF(IFNULL(m.cgpa,'')='','',m.cgpa) AS cgpa,
                               IF(IFNULL(m.majorConcentration,'')='','',m.majorConcentration) AS majorConcentration,
                               m.confirmId, m.registrationDate, DATE_FORMAT(m.registrationDate,'%Y-%m-%d') AS regDate,
                               d.classId, d.subjectId, d.credits, d.subjectType, CONCAT(d.subjectId,'~',d.classId) AS subjectIds,
                               IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                               IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                               sub.subjectName, sub.subjectCode,CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName";
        }

        if($orderBy=='') {
          $orderBy='d.classId, d.subjectType ASC';
        }

        $query = "SELECT
                         $filter
                  FROM
                        `class` c, `student_registration_master` m, `student_registration_detail` d,
                        `subject` sub, student_groups sg LEFT JOIN `student` s ON s.studentId = sg.studentId
                  WHERE
                         c.classId = m.currentClassId AND
                         sub.subjectId = d.subjectId AND
                         m.studentId = sg.studentId AND
                         m.registrationId = d.registrationId AND
                         m.instituteId = '$instituteId'
                   $condition

                  ORDER BY
                         $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//---------------------------------------------------------------------------------------------------------------------
// Function gets fetch student records
//
// Author :Parveen Sharma
// Created on : 14-oct-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------------------------------
    public function getRegistrationStudentList($conditions='',$orderBy=' classId, rollNo, studentId',$limit='',$conditionClassId='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                        DISTINCT s.studentId, sg.classId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                                IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                                IF(IFNULL(m.cgpa,'')='','".NOT_APPLICABLE_STRING."',m.cgpa) AS cgpa,
                                m.confirmId, m.registrationDate, DATE_FORMAT(m.registrationDate,'%Y-%m-%d') AS regDate,
                                IF(IFNULL(m.majorConcentration,'')='','".NOT_APPLICABLE_STRING."',m.majorConcentration) AS majorConcentration
                  FROM
                        class c, student_groups sg
                        LEFT JOIN `student` s ON s.studentId = sg.studentId
                        LEFT JOIN student_registration_master m ON m.studentId = sg.studentId AND m.currentClassId=sg.classId
                        LEFT JOIN student_registration_detail d ON m.registrationId = d.registrationId  $conditionClassId
                  WHERE
                        s.studentId = sg.studentId AND
                        c.classId = sg.classId  AND
                        c.instituteId = $instituteId
                  $conditions
                  ORDER BY
                        $orderBy
                  $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
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
				        stu.universityRollNo,
						stu.rollNo,
                        stu.fatherName,
                        cls.classId,
                        cls.instituteId,
                        SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-3) AS className,
				        cls.studyPeriodId,
                        cls.universityId,
                        cls.batchId,
                        cls.degreeId,
                        cls.branchId,
                        sp.periodName,
						cls.studyPeriodId,
						stu.transportFacility,
						stu.hostelFacility,
						IF(IFNULL(stu.busStopId,'')='','0',stu.busStopId) AS busStopId,
						IF(IFNULL(stu.hostelRoomId,'')='','0',stu.hostelRoomId) AS hostelRoomId
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
// THIS FUNCTION IS USED TO FETCH STUDENT DETAILS BASED ON ROLL NO
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
public function getQuarantineStudentDetailClass($condition) {
        $query = "SELECT
                        stu.studentId,
                        stu.firstName,
                        stu.lastName,
                        stu.quotaId,
                        stu.hostelRoomId,
                        stu.isLeet,
				        stu.universityRollNo,
						stu.rollNo,
                        stu.fatherName,
                        cls.classId,
                        cls.instituteId,
                        SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-3) AS className,
				        cls.studyPeriodId,
                        cls.universityId,
                        cls.batchId,
                        cls.degreeId,
                        cls.branchId,
                        sp.periodName,
						cls.studyPeriodId,
						stu.transportFacility,
						stu.hostelFacility,
						IF(IFNULL(stu.busStopId,'')='','0',stu.busStopId) AS busStopId,
						IF(IFNULL(stu.hostelRoomId,'')='','0',stu.hostelRoomId) AS hostelRoomId
				  FROM
                        quarantine_student stu,
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
 public function getStudentQuartineRollNo($conditions='') {

        $query = "SELECT rollNo FROM quarantine_student $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
 public function getStudentQuartineUnivRollNo($conditions='') {

        $query = "SELECT universityRollNo FROM quarantine_student $conditions";
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
// THIS FUNCTION IS USED TO FETCH STUDENT USER DETAILS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getConcessionDetail($conditions='') {

        $query = "SELECT concessionType,concessionValue,feeHeadId,reason FROM student_concession $conditions";
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

		 $previousDues = urldecode($REQUEST_DATA['totalFees'])+urldecode($REQUEST_DATA['studentFine'])-urldecode($REQUEST_DATA['totalConcession'])-urldecode($REQUEST_DATA['paidAmount'])-urldecode($REQUEST_DATA['previousPayment']);
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

		  //echo ($REQUEST_DATA['instId'][0]);
		$cnt = count($REQUEST_DATA['amtId']);
		if($cnt){

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
					  (studentId,feeCycleId,installment,paymentInstrument,instrumentNo,instrumentAmount,instrumentDate,
                       issuingBankId,receiptStatus,instrumentStatus,feeReceiptId)
					  VALUES
					  $insertValue";

				SystemDatabaseManager::getInstance()->executeUpdate($query);
			}
		}
		 if($recordReceipt)
		 {
			 SystemDatabaseManager::getInstance()->runAutoUpdate('fee_receipt', array('nextReceiptId'), array($lastReceipt), " feeReceiptId = $recordReceipt" );
		 }
		 else
		 {
            if($REQUEST_DATA['feeHeadId']!='') {
               $feeArr  = implode(",",$REQUEST_DATA['feeHeadId']);
			   $feeHead = $REQUEST_DATA['feeHeadId'];
			   for($i=0;$i<count($REQUEST_DATA['feeHeadId']); $i++) {
				  $concessionValue = $REQUEST_DATA['chb'];
				  $feeHeadAmtValue = $REQUEST_DATA['feeHeadAmt'];

				  SystemDatabaseManager::getInstance()->runAutoInsert('fee_head_student',
                  array('firstReceiptId','studentId','feeHeadId','feeCycleId','feeHeadAmount','discountedAmount','feeStudyPeriodId'),
                  array($lastReceipt,$REQUEST_DATA['studentId'],$feeHead[$i],$REQUEST_DATA['feeCycle'],
                          $feeHeadAmtValue[$i],$concessionValue[$i],$REQUEST_DATA['feeStudyPeriod']) );
			   }
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

         $query = "SELECT paymentInstrument,instrumentNo,instrumentDate,(instrumentAmount) as totalAmount,bankName FROM fee_payment_detail,bank $condition AND fee_payment_detail.issuingBankId=bank.bankId";

		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT Concession
//
// Author :Rajeev Aggarwal
// Created on : (02.08.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getFeeConcessionClass($studentId,$feeCycleId) {

         $query = "SELECT * FROM student_concession WHERE studentId=$studentId AND feeCycleId=$feeCycleId AND concessionValue!='0.00'";

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
// THIS FUNCTION IS USED TO FETCH STUDENT FEES INSTALLMENT
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getStudentInstallmentAmt($studentId,$feeCycleId,$feeStudyPeriodId,$feeTypeId) {

         $query = "SELECT SUM(discountedFeePayable) as toalInstallmentAmt FROM `fee_receipt` WHERE studentId = ".$studentId." AND feeCycleId =".$feeCycleId." AND feeStudyPeriodId=".$feeStudyPeriodId." AND feeType=".$feeTypeId;

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
// THIS FUNCTION IS USED TO FETCH STUDENT PREVIOUS FEES
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getPreviousPayment($studentId,$feeCycleId,$receiptId) {

		 global $REQUEST_DATA;
         $query = "SELECT SUM(totalAmountPaid) as previousPayment FROM `fee_receipt` where studentId=$studentId and feeCycleId =$feeCycleId and `feeReceiptId` <$receiptId group by studentId";

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
						 AND m.feeCycleId = '".$feeCycleId."'

						 AND fh.hostelHead!=1
						 AND fh.transportHead!=1
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


public function getStudentFeeHeadClass($feeCycleId,$studyPeriodId,$studentInstitute, $universityId,$batchId,$degreeId,$branchId,$quotaId,$isLeet,$feeHead) {
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
						 AND m.feeCycleId = '".$feeCycleId."'
						 AND fh.hostelHead!=1
						 AND fh.transportHead!=1
						  AND fh.feeHeadId=$feeHead
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
/*public function addStudentFeeRecipt($studentId,$instituteId,$installmentCount,$feeCycleId,$receiptNo,$receiptNo,$paidDate,$currentStudyPeriodId,$feeStudyPeriod,$totalFees,$totalFees,$dicountedFeePayable,$paymentMode,$chequeDraftNo,$paidDate,$previousDues,$previousOverPayment,$studentName,$userId,$feeType){

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
}*/

public function addStudentFeeRecipt($studentId,$instituteId,$installmentCount,$feeCycleId,$receiptNo,$receiptNo,$paidDate,$currentStudyPeriodId,$feeStudyPeriod,$totalFees,$dicountedFeePayable,$paymentMode,$chequeDraftNo,$paidDate,$previousDues,$previousOverPayment,$studentName,$userId,$feeType,$transId,$paidFeeAmt){

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
                      $currentStudyPeriodId,$totalFees,'Uploaded from excel file','Uploaded from excel file',
                      $paidFeeAmt,0,$dicountedFeePayable,1,
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
	public function getStudentBusDetailClass($busCondition,$transportFacility) {

		if($busCondition!=''){

			$query = "Select  feehead.feeHeadId, feehead.headName ,bus.transportCharges as feeHeadAmount,feehead.isConsessionable,feehead.transportHead
				  from fee_head feehead, bus_stop bus
				  where feehead.transportHead = 1 $busCondition";
		}
		else{

			if($transportFacility==1){

				$query = "Select  feehead.feeHeadId, feehead.headName,feehead.isConsessionable,feehead.transportHead,fhv.feeHeadAmount
				  from fee_head feehead,fee_head_values fhv
				  where feehead.transportHead = 1 AND fhv.feeHeadId=feehead.feeHeadId";
			}
			else{

				$query = "Select  feehead.feeHeadId, feehead.headName,feehead.isConsessionable,feehead.transportHead
				  from fee_head feehead
				  where feehead.transportHead = 1";

			}
			/*$query = "Select  feehead.feeHeadId, feehead.headName ,feehead.isConsessionable,feehead.transportHead
				  from fee_head feehead
				  where feehead.transportHead = 1 ";*/
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
	public function getStudentHostelDetailClass($hostelCondition,$hostelFacility) {

		if($hostelCondition!=''){

			$query = "Select  feehead.feeHeadId, feehead.headName ,hosroom.roomRent as feeHeadAmount,feehead.isConsessionable,feehead.hostelHead
				  from fee_head feehead, hostel_room hosroom
				  where feehead.hostelHead = 1";
		}
		else{

			if($hostelFacility==1){

				$query = "Select  feehead.feeHeadId, feehead.headName,feehead.isConsessionable,feehead.hostelHead,fhv.feeHeadAmount
				  from fee_head feehead,fee_head_values fhv
				  where feehead.hostelHead = 1 AND fhv.feeHeadId=feehead.feeHeadId";
			}
			else{

				$query = "Select  feehead.feeHeadId, feehead.headName,feehead.isConsessionable,feehead.hostelHead
				  from fee_head feehead
				  where feehead.hostelHead = 1";

			}
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
				  where lcase(trim(rollNo)) = lcase('".add_slashes($studentRoll)."') AND studentId!=$studentId";
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
				  where lcase(trim(rollNo)) = lcase('".add_slashes($studentRoll)."') AND studentId!=$studentId";
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
    public function checkStudentAll($condition='') {

        $query = "SELECT
                       COUNT(*) AS cnt
                  FROM
                       student
                  WHERE
                       $condition ";

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
    public function checkStudentQuarantineAll($condition='') {

        $query = "SELECT
                       COUNT(*) AS cnt
                  FROM
                        `quarantine_student`
                  WHERE
                        $condition ";

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
				  where lcase(trim(regNo)) = lcase('".add_slashes($studentReg)."') AND studentId!=$studentId";
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
				  where lcase(trim(regNo)) = lcase('".add_slashes($studentReg)."') AND studentId!=$studentId";
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
				  where lcase(trim(universityRollNo)) = lcase('".add_slashes($studentUniv)."') AND studentId!=$studentId";
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
				  where lcase(trim(universityRollNo)) = lcase('".add_slashes($studentUniv)."') AND studentId!=$studentId";
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
				  where lcase(trim(universityRegNo)) = lcase('".add_slashes($studentUnivReg)."') AND studentId!=$studentId";
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
				  where lcase(trim(universityRegNo)) = lcase('".add_slashes($studentUnivReg)."')
                  AND studentId!=$studentId";
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
				  where lcase(trim(feeReceiptNo)) = lcase('".add_slashes($studentFeeReceipt)."') AND studentId!=$studentId";
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
				  where lcase(trim(feeReceiptNo)) = lcase('".add_slashes($studentFeeReceipt)."') AND studentId!=$studentId";
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
               $extC=' AND cl.classId IN ('.$classId.')';
           }



        $isHoldCondition = "";
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        if($roleId==3 || $roleId==4){
          $isHoldCondition = " AND cl.holdTestMarks = '0' ";
        }


          $query = " SELECT
                        s.studentId,
						CONCAT(su.subjectName,' (',su.subjectCode,')') AS subjectName,
						CONCAT(IF( ttc.examType = 'PC', 'Internal', 'External' ), ' (' , ttc.testTypeName, ')' ) AS examType,
						ttc.testTypeName,t.testDate,
						emp.employeeName,
						CONCAT( t.testAbbr,'-',t.testIndex ) AS testName,
						su.subjectCode,
						(tm.maxMarks) AS totalMarks1,
                        (t.maxMarks) AS totalMarks,
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
						$extC $isHoldCondition
                        			$conditions

						ORDER BY $orderBy
                $limit ";


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
			$classCond =" AND cl.classId IN (".add_slashes($classId).")";
		   }

		global $REQUEST_DATA;
		global $sessionHandler;

 $query = "	SELECT
						COUNT(*) as totalRecords
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

    public function insertOptionalFieldStudent($id='') {

        global $REQUEST_DATA;



        $presentCountry = urldecode($REQUEST_DATA['presentCountry']);
        if($presentCountry=="")
            $presentCountry = 'NULL';

        $presentStates = urldecode($REQUEST_DATA['presentStates']);
        if($presentStates=="")
            $presentStates = 'NULL';

        $presentCity = urldecode($REQUEST_DATA['presentCity']);
        if($presentCity=="")
            $presentCity = 'NULL';


        $spouseCountry = urldecode($REQUEST_DATA['spouseCountry']);
        if($spouseCountry=="")
            $spouseCountry = 'NULL';

        $spouseStates = urldecode($REQUEST_DATA['spouseStates']);
        if($spouseStates=="")
            $spouseStates = 'NULL';

        $spouseCity = urldecode($REQUEST_DATA['spouseCity']);
        if($spouseCity=="")
            $spouseCity = 'NULL';

        $presentAddress1 = urldecode($REQUEST_DATA['presentAddress1']);
        $presentAddress2 = urldecode($REQUEST_DATA['presentAddress2']);
        $presentPincode  = urldecode($REQUEST_DATA['presentPincode']);
        $presentPhone    = urldecode($REQUEST_DATA['presentPhone']);

        $spouseAddress1 = urldecode($REQUEST_DATA['spouseAddress1']);
        $spouseAddress2 = urldecode($REQUEST_DATA['spouseAddress2']);
        $spousePincode  = urldecode($REQUEST_DATA['spousePincode']);
        $spousePhone    = urldecode($REQUEST_DATA['spousePhone']);

        $currentOrg = urldecode($REQUEST_DATA['currentOrg']);
        $companyDesignation = urldecode($REQUEST_DATA['companyDesignation']);
        $workEmail  = urldecode($REQUEST_DATA['workEmail']);
        $officeContactNo    = urldecode($REQUEST_DATA['officeContactNo']);

        $spouseName = urldecode($REQUEST_DATA['spouseName']);
        $spouseRelation  = urldecode($REQUEST_DATA['spouseRelation']);
        $spouseEmail    = urldecode($REQUEST_DATA['spouseEmail']);

        $query = "UPDATE `student`
                  SET
                        `presentAddress1`='".htmlentities(add_slashes($presentAddress1))."' ,
                        `presentAddress2`='".htmlentities(add_slashes($presentAddress2))."' ,
                        `presentCountryId`=$presentCountry,
                        `presentStateId`=$presentStates,
                        `presentCityId`=$presentCity,
                        `presentPinCode`='".htmlentities(add_slashes($presentPincode))."' ,
                        `presentPhone`='".htmlentities(add_slashes($presentPhone))."' ,

                        `spouseName`='".htmlentities(add_slashes($spouseName))."' ,
                        `spouseRelation`='".htmlentities(add_slashes($spouseRelation))."' ,
                        `spouseEmail`='".htmlentities(add_slashes($spouseEmail))."',

                        `spouseAddress1`='".htmlentities(add_slashes($spouseAddress1))."' ,
                        `spouseAddress2`='".htmlentities(add_slashes($spouseAddress2))."' ,
                        `spouseCountryId`=$spouseCountry,
                        `spouseStateId`=$spouseStates,
                        `spouseCityId`=$spouseCity,
                        `spousePinCode`='".htmlentities(add_slashes($spousePincode))."' ,
                        `spousePhone`='".htmlentities(add_slashes($spousePhone))."' ,

                        `currentOrg`='".htmlentities(add_slashes($currentOrg))."' ,
                        `companyDesignation`='".htmlentities(add_slashes($companyDesignation))."' ,
                        `workEmail`='".htmlentities(add_slashes($workEmail))."' ,
                        `officeContactNo`='".htmlentities(add_slashes($officeContactNo))."'
                  WHERE
                        `studentId` = '$id' ";

         return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

         //return 1;
    }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT STUDENT from ADMIT STUDENT
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function insertStudentaToClass($classId,$sAllClass) {

		global $REQUEST_DATA;

		$correspondenceCountry = urldecode($REQUEST_DATA['correspondenceCountry']);
		if($correspondenceCountry=="")
			$correspondenceCountry = 'NULL';

		$correspondenceStates = urldecode($REQUEST_DATA['correspondenceStates']);
		if($correspondenceStates=="")
			$correspondenceStates = 'NULL';

		$correspondenceCity = urldecode($REQUEST_DATA['correspondenceCity']);
		if($correspondenceCity=="")
			$correspondenceCity = 'NULL';


		$permanentCountry = urldecode($REQUEST_DATA['permanentCountry']);
		if($permanentCountry=="")
			$permanentCountry = 'NULL';

		$permanentStates = urldecode($REQUEST_DATA['permanentStates']);
		if($permanentStates=="")
			$permanentStates = 'NULL';

		$permanentCity = urldecode($REQUEST_DATA['permanentCity']);
		if($permanentCity=="")
			$permanentCity = 'NULL';

		$permanentAddress1 = htmlentities(urldecode($REQUEST_DATA['permanentAddress1']));
		$permanentAddress2 = htmlentities(urldecode($REQUEST_DATA['permanentAddress2']));
		$permanentPincode  = urldecode($REQUEST_DATA['permanentPincode']);
		$permanentPhone    = urldecode($REQUEST_DATA['permanentPhone']);

		if($REQUEST_DATA['sameText'])
		{
			$permanentAddress1 = htmlentities(urldecode($REQUEST_DATA['correspondeceAddress1']));
			$permanentAddress2 = htmlentities(urldecode($REQUEST_DATA['correspondeceAddress2']));
			$permanentPincode  = urldecode($REQUEST_DATA['correspondecePincode']);
			$permanentPhone    = urldecode($REQUEST_DATA['correspondecePhone']);
			$permanentCountry  = $correspondenceCountry;
			$permanentStates   = $correspondenceStates;
			$permanentCity     = $correspondenceCity;
		}

		$guardianCountry = urldecode($REQUEST_DATA['guardianCountry']);
		if($guardianCountry=="")
			$guardianCountry = 'NULL';

		$guardianStates = urldecode($REQUEST_DATA['guardianStates']);
		if($guardianStates=="")
			$guardianStates = 'NULL';

		$guardianCity = urldecode($REQUEST_DATA['guardianCity']);
		if($guardianCity=="")
			$guardianCity = 'NULL';

		$motherCountry = urldecode($REQUEST_DATA['motherCountry']);
		if($motherCountry=="")
			$motherCountry = 'NULL';

		$motherStates = urldecode($REQUEST_DATA['motherStates']);
		if($motherStates=="")
			$motherStates = 'NULL';

		$motherCity = urldecode($REQUEST_DATA['motherCity']);
		if($motherCity=="")
			$motherCity = 'NULL';

		$fatherCountry = urldecode($REQUEST_DATA['fatherCountry']);
		if($fatherCountry=="")
			$fatherCountry = 'NULL';

		$fatherStates = urldecode($REQUEST_DATA['fatherStates']);
		if($fatherStates=="")
			$fatherStates = 'NULL';

		$fatherCity = urldecode($REQUEST_DATA['fatherCity']);
		if($fatherCity=="")
			$fatherCity = 'NULL';

		//$todayDate = date('Y-m-d');
		$dateOfBirth = urldecode($REQUEST_DATA['studentYear'])."-".urldecode($REQUEST_DATA['studentMonth'])."-".urldecode($REQUEST_DATA['studentDate']);
		$todayDate = urldecode($REQUEST_DATA['admissionYear'])."-".urldecode($REQUEST_DATA['admissionMonth'])."-".urldecode($REQUEST_DATA['admissionDate']);

		if(urldecode($REQUEST_DATA['everStayedInHostel'] == 1)) {
			$everStayedHostel = 1;
		}
		else {
			$everStayedHostel = 0;
		}

		if(urldecode($REQUEST_DATA['education'] == 1)) {
			$education = 1;
		}
		else {
			$education = 0;
		}

		if(urldecode($REQUEST_DATA['completedGraduation'] == 1)) {
			$completedGraduation = 1;
		}
		else {
			$completedGraduation = 0;
		}

		if(urldecode($REQUEST_DATA['writtenFinalExam'] == 1)) {
			$writtenFinalExam = 1;
			if($REQUEST_DATA['resultDue'] != '') {
				$resultDue = $REQUEST_DATA['resultDue'];
			}
		}
		else {
			$writtenFinalExam = 0;
			$resultDue = '';
		}

		if(urldecode($REQUEST_DATA['coachingCenter'] != '')) {
			$coachingCenter = urldecode($REQUEST_DATA['coachingCenter']);
			$coachingManager = urldecode($REQUEST_DATA['coachingManager']);
			$coachingAddress = urldecode($REQUEST_DATA['address']);
		}
		else {
			$coachingCenter = 0;
			$coachingManager = '';
			$coachingAddress = '';
		}
		if(urldecode($REQUEST_DATA['workExperience'] == 1)) {
			$workExperience = 1;
			$department = urldecode($REQUEST_DATA['department']);
			$organization = urldecode($REQUEST_DATA['organization']);
			$place = urldecode($REQUEST_DATA['place']);
		}
		else {
			$workExperience = 0;
			$department = '';
			$organization = '';
			$place = '';
		}


        $quotaId = htmlentities(add_slashes(urldecode($REQUEST_DATA[studentCategory])));
        $domicileId = htmlentities(add_slashes(urldecode($REQUEST_DATA[studentDomicile])));
        if($quotaId=='') {
          $quotaId='NULL';
        }

        if($domicileId=='') {
          $domicileId='NULL';
        }

 	$migratedStudyPeriod	= urldecode($REQUEST_DATA['migratedStudyPeriod']);
		if($sAllClass == ''){
			$sAllClass='0';
		}

		$query = "INSERT INTO `student` SET
		`classId`='$classId',
		`migrationClassId`='$classId',
		`regNo`='". strtoupper(htmlentities(add_slashes($this->getClearSpecialChar(urldecode($REQUEST_DATA[collegeRegNo])))))."' ,
		`rollNo`='".strtoupper(htmlentities(add_slashes($this->getClearSpecialChar(urldecode($REQUEST_DATA[studentClassRole])))))."' ,
		`universityRollNo`='".strtoupper(htmlentities(add_slashes($this->getClearSpecialChar(urldecode($REQUEST_DATA[studentUniversityRole])))))."' ,
		`isLeet`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[isLeet])))."' ,
		`firstName`='".strtoupper(htmlentities(add_slashes(urldecode($REQUEST_DATA[studentName]))))."' ,
		`lastName`='".strtoupper(htmlentities(add_slashes(urldecode($REQUEST_DATA[studentLName]))))."' ,
		`studentMobileNo`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[studentMobile])))."' ,
        `studentEmail`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[studentEmail])))."' ,
		`alternateStudentEmail`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[alternateEmail])))."' ,
		`studentGender`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[genderRadio])))."' ,
		`religion` = '".htmlentities(add_slashes(urldecode($REQUEST_DATA[religion])))."' ,
		`hobbies` = '".htmlentities(add_slashes(urldecode($REQUEST_DATA[hobbies])))."' ,
		`studentStatus`='1' ,
		`dateOfAdmission`='$todayDate' ,
		`dateOfBirth`='$dateOfBirth' ,
		`managementCategory`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[isMgmt])))."' ,
        `managementReference`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[studentReference])))."' ,
		`isMigration`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[isMigration])))."' ,
		`migrationStudyPeriod`='$migratedStudyPeriod' ,
		`nationalityId`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[country])))."' ,
		`domicileId`=$domicileId,
		`quotaId`=$quotaId ,
		`everHostelStayed` = $everStayedHostel,
		`years` = '".urldecode($REQUEST_DATA[yearsInHostel])."',
		`education` = '".htmlentities(add_slashes($education))."',
		`bankNameAddress` = '".htmlentities(add_slashes(trim(urldecode($REQUEST_DATA[bankName]))))."',
		`loanAmount` = '".htmlentities(add_slashes(trim(urldecode($REQUEST_DATA[loanAmount]))))."',
		`compExamRank`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[studentRank])))."' ,
		`compExamBy`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[entranceExam])))."' ,
        `compExamRollNo`='".htmlentities(add_slashes(urldecode($REQUEST_DATA['studentEntranceRole'])))."' ,
		`studentPhone`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[studentNo])))."' ,
		`fatherTitle`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherTitle])))."' ,
		`fatherName`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherName])))."' ,
        `fatherOccupation`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherOccupation])))."',
        `fatherMobileNo`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherMobile])))."' ,
        `fatherAddress1`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherAddress1])))."' ,
        `fatherAddress2`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherAddress2])))."' ,
        `fatherEmail`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherEmail])))."' ,
		`fatherPinCode`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherPincode])))."' ,
		`fatherCountryId`=$fatherCountry ,
		`fatherStateId`=$fatherStates ,
		`fatherCityId`=$fatherCity ,
		`motherTitle`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherTitle])))."' ,
		`motherName`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherName])))."',
        `motherOccupation`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherOccupation])))."' ,
        `motherMobileNo`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherMobile])))."' ,
        `motherAddress1`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherAddress1])))."' ,
        `motherAddress2`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherAddress2])))."' ,
         `motherEmail`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherEmail])))."' ,
		`motherPinCode`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherPincode])))."' ,
		`motherCountryId`=$motherCountry ,
		`motherStateId`=$motherStates ,
		`motherCityId`=$motherCity ,
		`guardianTitle`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianTitle])))."' ,
        `guardianName`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianName])))."' ,
        `guardianOccupation`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianOccupation])))."' ,
        `guardianMobileNo`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianMobile])))."' ,
        `guardianAddress1`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianAddress1])))."' ,
        `guardianAddress2`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianAddress2])))."' ,
        `guardianEmail`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianEmail])))."' ,
		`guardianPinCode`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianPincode])))."' ,
		`guardianCountryId`= $guardianCountry ,
		`guardianStateId`=$guardianStates ,
		`guardianCityId`=$guardianCity ,
		`corrAddress1`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[correspondeceAddress1])))."' ,
        `corrAddress2`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[correspondeceAddress2])))."' ,
		`corrCountryId`=$correspondenceCountry,
		`corrStateId`=$correspondenceStates ,
		`corrCityId`=$correspondenceCity,
		`corrPinCode`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[correspondecePincode])))."' ,
        `corrPhone`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[correspondecePhone])))."' ,
		`permAddress1`='".htmlentities(add_slashes($permanentAddress1))."' ,
		`permAddress2`='".htmlentities(add_slashes($permanentAddress2))."' ,
		`permCountryId`=$permanentCountry,
		`permStateId`=$permanentStates,
		`permCityId`=$permanentCity,
		`permPinCode`='".htmlentities(add_slashes($permanentPincode))."' ,
		`permPhone`='".htmlentities(add_slashes($permanentPhone))."' ,
		`referenceName`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[referenceName])))."' ,
		`feeReceiptNo`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[feeReceiptNo])))."' ,
		`studentBloodGroup`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[bloodGroup])))."' ,
		`studentSportsActivity`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[sportsActivity])))."' ,
        `transportFacility`='".htmlentities(add_slashes(urldecode($REQUEST_DATA['transportFacility'])))."' ,
        `hostelFacility`='".htmlentities(add_slashes(urldecode($REQUEST_DATA['hostelFacility'])))."' ,
		`languageRead`='".htmlentities(add_slashes(urldecode($REQUEST_DATA['toRead'])))."' ,
		`languageWrite`='".htmlentities(add_slashes(urldecode($REQUEST_DATA['toWrite'])))."' ,
		`languageSpeak`='".htmlentities(add_slashes(urldecode($REQUEST_DATA['toSpeak'])))."' ,
		`completedGraduation` = '".htmlentities(add_slashes($completedGraduation))."' ,
		`writtenFinalExam` = '".htmlentities(add_slashes($writtenFinalExam))."' ,
		`resultDue` = '$resultDue',
		 `coachingName` = '".htmlentities(add_slashes(trim($coachingCenter)))."',
		 `coachingManagerName` = '".htmlentities(add_slashes(trim($coachingManager)))."',
		 `coachingAddress` = '".htmlentities(add_slashes(trim($coachingAddress)))."',
		 `workExperience` = '".htmlentities(add_slashes($workExperience))."' ,
		 `departmentName` = '".htmlentities(add_slashes(trim($department)))."',
		 `organization` = '".htmlentities(add_slashes(trim($organization)))."',
		 `place` = '".htmlentities(add_slashes(trim($place)))."',
		 `studentRemarks`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[studentRemarks])))."',
		 `branchId` = '".htmlentities(add_slashes(urldecode($REQUEST_DATA['branch'])))."',
		  `sAllClass`='".$sAllClass."'";

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
		//echo($cnt);
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

					$insertValue .= "$querySeprator ('".add_slashes(urldecode($previousClass[$i]))."','".add_slashes(urldecode($studentId))."','".add_slashes(urldecode($rollNo[$i]))."','".add_slashes(urldecode($session[$i]))."','".add_slashes(urldecode($institute[$i]))."','".add_slashes(urldecode($board[$i]))."','".add_slashes(urldecode($marks[$i]))."','".add_slashes(urldecode($maxMarks[$i]))."','".add_slashes(urldecode($percentage[$i]))."','".add_slashes(trim(urldecode($educationStream[$i])))."')";
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

  public function insertStudentAilment($returnStatus,$regularAilment,$natureAilment,$familyAilment,$otherAilment) {

	$query = "	INSERT INTO student_ailment
				SET studentId=$returnStatus,
					ailment = '$regularAilment',
					otherAilment = '$natureAilment',
					familyAilment = '$familyAilment',
					familyOtherAilment = '$otherAilment'
				";

	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
  }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE STUDENT from STUDENT PROFILE
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getClearSpecialChar($text) {
       if($text!="") {
         $text=strtolower($text);
         $code_entities_match = array(' ');
         $code_entities_replace = array('');
         $text = str_replace($code_entities_match, $code_entities_replace, $text);
       }
       return $text;
    }

	public function updateStudent($stUserId='NULL',$fatherId='NULL',$motherId='NULL',$guardianId='NULL',$sAllClass) {
		global $REQUEST_DATA;
		global $sessionHandler;

		$loggedUserId = $sessionHandler->getSessionVariable('UserId');

		$correspondenceCountry = urldecode($REQUEST_DATA['correspondenceCountry']);
		if($correspondenceCountry=="")
			$correspondenceCountry = 'NULL';

		$correspondenceStates = urldecode($REQUEST_DATA['correspondenceStates']);
		if($correspondenceStates=="")
			$correspondenceStates = 'NULL';

		$correspondenceCity = urldecode($REQUEST_DATA['correspondenceCity']);
		if($correspondenceCity=="")
			$correspondenceCity = 'NULL';


		$permanentCountry = urldecode($REQUEST_DATA['permanentCountry']);
		if($permanentCountry=="")
			$permanentCountry = 'NULL';

		$permanentStates = urldecode($REQUEST_DATA['permanentStates']);
		if($permanentStates=="")
			$permanentStates = 'NULL';

		$permanentCity = urldecode($REQUEST_DATA['permanentCity']);
		if($permanentCity=="")
			$permanentCity = 'NULL';

		$permanentAddress1 = urldecode($REQUEST_DATA['permanentAddress1']);
		$permanentAddress2 = urldecode($REQUEST_DATA['permanentAddress2']);
		$permanentPincode  = urldecode($REQUEST_DATA['permanentPincode']);
		$permanentPhone    = urldecode($REQUEST_DATA['permanentPhone']);

		if(urldecode($REQUEST_DATA['sameText']))
		{
			$permanentAddress1 = urldecode($REQUEST_DATA['correspondeceAddress1']);
			$permanentAddress2 = urldecode($REQUEST_DATA['correspondeceAddress2']);
			$permanentPincode  = urldecode($REQUEST_DATA['correspondecePincode']);
			$permanentPhone    = urldecode($REQUEST_DATA['correspondecePhone']);
			$permanentCountry  = $correspondenceCountry;
			$permanentStates   = $correspondenceStates;
			$permanentCity     = $correspondenceCity;
		}

		/*$studentHostel = $REQUEST_DATA['studentHostel'];
		if($studentHostel=="")
			$studentHostel = 'NULL';

		$studentRoom = $REQUEST_DATA['studentRoom'];
		if($studentRoom=="")
			$studentRoom = 'NULL';*/

		$studentRoute = urldecode($REQUEST_DATA['studentRoute']);
		if($studentRoute=="")
			$studentRoute = 'NULL';

		$studentStop = urldecode($REQUEST_DATA['studentStop']);
		if($studentStop=="")
			$studentStop = 'NULL';

		$guardianCountry = urldecode($REQUEST_DATA['guardianCountry']);
		if($guardianCountry=="")
			$guardianCountry = 'NULL';

		$guardianStates = urldecode($REQUEST_DATA['guardianStates']);
		if($guardianStates=="")
			$guardianStates = 'NULL';

		$guardianCity = urldecode($REQUEST_DATA['guardianCity']);
		if($guardianCity=="")
			$guardianCity = 'NULL';

		$motherCountry = urldecode($REQUEST_DATA['motherCountry']);
		if($motherCountry=="")
			$motherCountry = 'NULL';

		$motherStates = urldecode($REQUEST_DATA['motherStates']);
		if($motherStates=="")
			$motherStates = 'NULL';

		$motherCity = urldecode($REQUEST_DATA['motherCity']);
		if($motherCity=="")
			$motherCity = 'NULL';

		$fatherCountry = urldecode($REQUEST_DATA['fatherCountry']);
		if($fatherCountry=="")
			$fatherCountry = 'NULL';

		$fatherStates = urldecode($REQUEST_DATA['fatherStates']);
		if($fatherStates=="")
			$fatherStates = 'NULL';

		$fatherCity = urldecode($REQUEST_DATA['fatherCity']);
		if($fatherCity=="")
			$fatherCity = 'NULL';

		$thGroup = urldecode($REQUEST_DATA['thGroup']);
		if($thGroup=="")
			$thGroup = 'NULL';

		$prGroup = urldecode($REQUEST_DATA['prGroup']);
		if($prGroup=="")
			$prGroup = 'NULL';

		$dateOfBirth = urldecode($REQUEST_DATA['studentYear'])."-".urldecode($REQUEST_DATA['studentMonth'])."-".urldecode($REQUEST_DATA['studentDate']);

		$dateOfAdmission = urldecode($REQUEST_DATA['studentAdmissionYear'])."-".urldecode($REQUEST_DATA['studentAdmissionMonth'])."-".urldecode($REQUEST_DATA['studentAdmissionDate']);

		$studentRoll = add_slashes($this->getClearSpecialChar(urldecode($REQUEST_DATA['studentRoll'])));
		if($studentRoll=="")
			$studentRoll = 'NULL';
		else
			$studentRoll = "'".strtoupper($studentRoll)."'";

		$studentReg = add_slashes($this->getClearSpecialChar(urldecode($REQUEST_DATA['studentReg'])));
		if($studentReg=="")
			$studentReg = 'NULL';
		else
			$studentReg = "'".strtoupper($studentReg)."'";

		$studentUniversityNo = add_slashes($this->getClearSpecialChar(urldecode($REQUEST_DATA['studentUniversityNo'])));
		if($studentUniversityNo=="")
			$studentUniversityNo = 'NULL';
		else
			$studentUniversityNo = "'".strtoupper($studentUniversityNo)."'";

		$studentUniversityRegNo = add_slashes($this->getClearSpecialChar(urldecode($REQUEST_DATA['studentUniversityRegNo'])));
		if($studentUniversityRegNo=="")
			$studentUniversityRegNo = 'NULL';
		else
			$studentUniversityRegNo = "'".strtoupper($studentUniversityRegNo)."'";

		$studentDomicile = add_slashes(urldecode($REQUEST_DATA['studentDomicile']));
		if($studentDomicile=="")
			$studentDomicile = 'NULL';
		else
			$studentDomicile = "'$studentDomicile'";

		if(urldecode($REQUEST_DATA['everStayedInHostel']) == 1) {
			$everStayedHostel = 1;
		}
		else {
			$everStayedHostel = 0;
		}

		if(urldecode($REQUEST_DATA['education']) == 1) {
			$education = 1;
		}
		else {
			$education = 0;
		}

		if(urldecode($REQUEST_DATA['completedGraduation']) == 1) {
			$completedGraduation = 1;
		}
		else {
			$completedGraduation = 0;
		}

		if(urldecode($REQUEST_DATA['writtenFinalExam']) == 1) {
			$writtenFinalExam = 1;
			if(urldecode($REQUEST_DATA['resultDue']) != '') {
				$resultDue = urldecode($REQUEST_DATA['resultDue']);
			}
		}
		else {
			$writtenFinalExam = 0;
			$resultDue = '';
		}

		if(urldecode($REQUEST_DATA['coachingCenter']) != '') {
			$coachingCenter = urldecode($REQUEST_DATA['coachingCenter']);
			$coachingManager = urldecode($REQUEST_DATA['coachingManager']);
			$coachingAddress = urldecode($REQUEST_DATA['address']);
		}
		else {
			$coachingCenter = 0;
			$coachingManager = '';
			$coachingAddress = '';
		}
		if($REQUEST_DATA['workExperience'] == 1) {
			$workExperience = 1;
			$department = urldecode($REQUEST_DATA['department']);
			$organization = urldecode($REQUEST_DATA['organization']);
			$place = urldecode($REQUEST_DATA['place']);
		}
		else {
			$workExperience = 0;
			$department = '';
			$organization = '';
			$place = '';
		}

        $quotaId = htmlentities(add_slashes(urldecode($REQUEST_DATA[studentCategory])));
        $domicileId = htmlentities(add_slashes(urldecode($REQUEST_DATA[studentDomicile])));
        if($quotaId=='') {
          $quotaId='NULL';
        }

        if($domicileId=='') {
          $domicileId='NULL';
        }
	$migratedStudyPeriod =urldecode($REQUEST_DATA['migratedStudyPeriod']);
		 $query = "UPDATE `student` SET
		`firstName`='".strtoupper(htmlentities(add_slashes(urldecode($REQUEST_DATA[studentName]))))."' ,
		`lastName`='".strtoupper(htmlentities(add_slashes(urldecode($REQUEST_DATA[studentLName]))))."' ,
		`rollNo`= $studentRoll,
		`regNo`=$studentReg ,
		`universityRollNo`=$studentUniversityNo,
		`universityRegNo`=$studentUniversityRegNo,
		`dateOfBirth`='$dateOfBirth' ,
		`dateOfAdmission`='$dateOfAdmission' ,
		`studentGender`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[genderRadio])))."' ,
		`religion` = '".htmlentities(add_slashes(urldecode($REQUEST_DATA[religion])))."',
		`hobbies` = '".htmlentities(add_slashes(urldecode($REQUEST_DATA[hobbies])))."',
		`studentEmail`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[studentEmail])))."' ,
		`alternateStudentEmail`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[alternateEmail])))."' ,
		`nationalityId`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[country])))."' ,
		`studentPhone`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[studentNo])))."' ,
		`studentMobileNo`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[studentMobile])))."' ,
		`domicileId`=".$studentDomicile.",
		`quotaId`=$quotaId ,
		`everHostelStayed` = $everStayedHostel,
		`years` = '".urldecode($REQUEST_DATA[yearsInHostel])."',
		`education` = $education,
		`bankNameAddress` = '".htmlentities(add_slashes(trim(urldecode($REQUEST_DATA[bankName]))))."',
		`loanAmount` = '".htmlentities(add_slashes(trim(urldecode($REQUEST_DATA[loanAmount]))))."',
		`isLeet`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[isLeet])))."' ,
        `isMigration`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[isMigration])))."' ,
	  `migrationStudyPeriod`=$migratedStudyPeriod ,
		`studentStatus`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[isActive])))."' ,
		`compExamRank`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[studentRank])))."' ,
		`compExamBy`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[entranceExam])))."' ,
		`compExamRollNo`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[entranceRoll])))."' ,
		`corrAddress1`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[correspondeceAddress1])))."' ,
        `corrAddress2`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[correspondeceAddress2])))."' ,
		`corrCountryId`=$correspondenceCountry,
		`corrStateId`=$correspondenceStates,
		`corrCityId`=$correspondenceCity ,
		`corrPinCode`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[correspondecePincode])))."' ,
        `corrPhone`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[correspondecePhone])))."' ,
		`permAddress1`='".htmlentities(add_slashes($permanentAddress1))."' ,
		`permAddress2`='".htmlentities(add_slashes($permanentAddress2))."' ,
		`permCountryId`=$permanentCountry,
		`permStateId`=$permanentStates,
		`permCityId`=$permanentCity,
		`permPinCode`='".htmlentities(add_slashes($permanentPincode))."' ,
		`permPhone`='".htmlentities(add_slashes($permanentPhone))."' ,
		`studentRemarks`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[studentRemarks])))."',
		`managementCategory`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[isMgmt])))."' ,
         `managementReference`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[studentReference])))."' ,
		`fatherTitle`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherTitle])))."' ,
		`fatherName`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherName])))."' ,
        `fatherOccupation`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherOccupation])))."',
        `fatherMobileNo`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherMobileNo])))."' ,
        `fatherAddress1`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherAddress1])))."' ,
        `fatherAddress2`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherAddress2])))."' ,
        `fatherEmail`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherEmail])))."' ,
		`fatherPinCode`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[fatherPinCode])))."' ,
		`fatherCountryId`=$fatherCountry ,
		`fatherStateId`=$fatherStates ,
		`fatherCityId`=$fatherCity ,
		`motherTitle`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherTitle])))."' ,
		`motherName`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherName])))."',
        `motherOccupation`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherOccupation])))."' ,
        `motherMobileNo`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherMobileNo])))."' ,
        `motherAddress1`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherAddress1])))."' ,
        `motherAddress2`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherAddress2])))."' ,
        `motherEmail`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherEmail])))."' ,
		`motherPinCode`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[motherPinCode])))."' ,
		`motherCountryId`=$motherCountry ,
		`motherStateId`=$motherStates ,
		`motherCityId`=$motherCity ,
		`guardianTitle`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianTitle])))."' ,
        `guardianName`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianName])))."' ,
        `guardianOccupation`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianOccupation])))."' ,
         `guardianMobileNo`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianMobileNo])))."' ,
          `guardianAddress1`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianAddress1])))."' ,
          `guardianAddress2`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianAddress2])))."' ,
          `guardianEmail`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianEmail])))."' ,
		`guardianPinCode`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[guardianPinCode])))."' ,
		`guardianCountryId`=$guardianCountry ,
		`guardianStateId`=$guardianStates ,
		`guardianCityId`=$guardianCity ,  ";

		if($stUserId)
			$query .="`userId`=$stUserId, ";

		if($fatherId)
			$query .="`fatherUserId`=$fatherId, ";

		if($motherId)
			$query .="`motherUserId`=$motherId, ";

		if($guardianId)
			$query .="`guardianUserId`=$guardianId, ";

		if(urldecode($REQUEST_DATA[correspondeceVerified])!=urldecode($REQUEST_DATA[previousCorrespondence])){

			$query .=" `correspondenceAddressVerified`= '".urldecode($REQUEST_DATA[correspondeceVerified])."' ,
            correspondenceAddressVerifiedBy =".$loggedUserId.", ";
		}
		if(urldecode($REQUEST_DATA[permanentVerified])!=urldecode($REQUEST_DATA[previousPermanent])){

			$query .=" permanentAddressVerified= '".urldecode($REQUEST_DATA[permanentVerified])."' ,
            permanentAddressVerifiedBy =".$loggedUserId.", ";
		}
		if(urldecode($REQUEST_DATA[fatherVerified])!=urldecode($REQUEST_DATA[previousFatherVerified])){

			$query .=" fatherAddressVerified= '".urldecode($REQUEST_DATA[fatherVerified])."' ,
            fatherAddressVerifiedBy =".$loggedUserId.", ";
		}
		if(urldecode($REQUEST_DATA[motherVerified])!=urldecode($REQUEST_DATA[previousMotherVerified])){

			$query .=" motherAddressVerified= '".urldecode($REQUEST_DATA[motherVerified])."' ,
            motherAddressVerifiedBy =".$loggedUserId.", ";
		}
		if(urldecode($REQUEST_DATA[guardianVerified])!=urldecode($REQUEST_DATA[previousGuardianVerified])){

			$query .=" guardianAddressVerified= '".urldecode($REQUEST_DATA[guardianVerified])."' ,
            guardianAddressVerifiedBy =".$loggedUserId.", ";
		}
		$query .="`icardNumber`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[iCardNo])))."' ,
		`managementCategory`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[managementCategory])))."' ,
		`managementReference`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[managementReference])))."' ,
		`busRouteId`=$studentRoute ,
		`busStopId`=$studentStop ,
		`referenceName`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[referenceName])))."' ,
		`feeReceiptNo`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[feeReceiptNo])))."' ,
		`studentBloodGroup`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[bloodGroup])))."' ,
		`hostelFacility`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[hostelFacility])))."' ,
		`transportFacility`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[transportFacility])))."' ,
		`studentSportsActivity`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[sportsActivity])))."',
		`languageRead`='".htmlentities(add_slashes(urldecode($REQUEST_DATA['toRead'])))."' ,
		`languageWrite`='".htmlentities(add_slashes(urldecode($REQUEST_DATA['toWrite'])))."' ,
		`languageSpeak`='".htmlentities(add_slashes(urldecode($REQUEST_DATA['toSpeak'])))."',
		`completedGraduation` = $completedGraduation,
		`writtenFinalExam` = $writtenFinalExam,
		`resultDue` = '$resultDue',
		`coachingName` = '".htmlentities(add_slashes(trim($coachingCenter)))."',
		`coachingManagerName` = '".htmlentities(add_slashes(trim($coachingManager)))."',
		`coachingAddress` = '".htmlentities(add_slashes(trim($coachingAddress)))."',
		`workExperience` = '$workExperience',
		`departmentName` = '".htmlentities(add_slashes(trim($department)))."',
		`organization` = '".htmlentities(add_slashes(trim($organization)))."',
		`place` = '".htmlentities(add_slashes(trim($place)))."',
		`studentRemarks`='".htmlentities(add_slashes(urldecode($REQUEST_DATA[studentRemarks])))."',
		`sAllClass`= $sAllClass ,
		`branchId` = ".htmlentities(add_slashes(urldecode($REQUEST_DATA[branch])))."


		WHERE studentId = '".urldecode($REQUEST_DATA[studentId])."'";

		SystemDatabaseManager::getInstance()->executeUpdate($query);
    }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE STUDENT Medical Ailment
// Author :Jaineesh
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
public function updateStudentAilment($regularAilment,$natureAilment,$familyAilment,$otherAilment,$studentId) {
    global $REQUEST_DATA;
    global $sessionHandler;

	$query = "	UPDATE	student_ailment
				SET		ailment = '$regularAilment',
						otherAilment = '$natureAilment',
						familyAilment = '$familyAilment',
						familyOtherAilment = '$otherAilment'
				WHERE	studentId = $studentId
				";

	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
  }


  public function updateStudentInfo($studentId) {
    global $REQUEST_DATA;
    global $sessionHandler;

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

    $programFeeId = $REQUEST_DATA['programFeeId'];
    if($programFeeId==""){
        $programFeeId = 'NULL';
    }

    $permanentAddress1 = htmlentities($REQUEST_DATA['permanentAddress1']);
    $permanentAddress2 = htmlentities($REQUEST_DATA['permanentAddress2']);
    $permanentPincode  = $REQUEST_DATA['permanentPincode'];
    $permanentPhone    = $REQUEST_DATA['permanentPhone'];


    if($REQUEST_DATA['education'] == 1) {
        $education = 1;
    }
    else {
        $education = 0;
    }

    if($REQUEST_DATA['completedGraduation'] == 1) {
        $completedGraduation = 1;
    }
    else {
        $completedGraduation = 0;
    }

    if($REQUEST_DATA['writtenFinalExam'] == 1) {
        $writtenFinalExam = 1;
        if($REQUEST_DATA['resultDue'] != '') {
            $resultDue = $REQUEST_DATA['resultDue'];
        }
    }
    else {
        $writtenFinalExam = 0;
        $resultDue = '';
    }

    if($REQUEST_DATA['coachingCenter'] != '') {
        $coachingCenter = $REQUEST_DATA['coachingCenter'];
        $coachingManager = $REQUEST_DATA['coachingManager'];
        $coachingAddress = $REQUEST_DATA['address'];
    }
    else {
        $coachingCenter = 0;
        $coachingManager = '';
        $coachingAddress = '';
    }
    if($REQUEST_DATA['workExperience'] == 1) {
        $workExperience = 1;
        $department = $REQUEST_DATA['department'];
        $organization = $REQUEST_DATA['organization'];
        $place = $REQUEST_DATA['place'];
    }
    else {
        $workExperience = 0;
        $department = '';
        $organization = '';
        $place = '';
    }

    $query = "UPDATE student
              SET
                 `studentEmail`='".htmlentities(add_slashes($REQUEST_DATA[studentEmail]))."' ,
                 `studentPhone`='".htmlentities(add_slashes($REQUEST_DATA[studentNo]))."' ,
                 `studentMobileNo`='".htmlentities(add_slashes($REQUEST_DATA[studentMobile]))."' ,
                 `corrAddress1`='".htmlentities(add_slashes($REQUEST_DATA[correspondeceAddress1]))."' ,
                 `corrAddress2`='".htmlentities(add_slashes($REQUEST_DATA[correspondeceAddress2]))."' ,
                 `corrCountryId`=$correspondenceCountry,
                 `corrStateId`=$correspondenceStates,
                 `corrCityId`=$correspondenceCity ,
                 `corrPinCode`='".htmlentities(add_slashes($REQUEST_DATA[correspondecePincode]))."' ,
                 `corrPhone`='".htmlentities(add_slashes($REQUEST_DATA[correspondecePhone]))."' ,
                 `permAddress1`='".htmlentities(add_slashes($permanentAddress1))."' ,
                 `permAddress2`='".htmlentities(add_slashes($permanentAddress2))."' ,
                 `permCountryId`=$permanentCountry,
                 `permStateId`=$permanentStates,
                 `permCityId`=$permanentCity,
                 `permPinCode`='".htmlentities(add_slashes($permanentPincode))."' ,
                 `permPhone`='".htmlentities(add_slashes($permanentPhone))."' ,
                 `years` = '".$REQUEST_DATA[yearsInHostel]."',
                 `education` = $education,
                 `bankNameAddress` = '".$REQUEST_DATA[bankName]."',
                 `loanAmount` = '".$REQUEST_DATA[loanAmount]."',
                 `religion` = '".htmlentities(add_slashes($REQUEST_DATA[religion]))."',
                 `hobbies` = '".htmlentities(add_slashes($REQUEST_DATA[hobbies]))."',
                 `studentSportsActivity`='".htmlentities(add_slashes($REQUEST_DATA[sportsActivity]))."',
                 `languageRead`='".htmlentities(add_slashes($REQUEST_DATA['toRead']))."' ,
                 `languageWrite`='".htmlentities(add_slashes($REQUEST_DATA['toWrite']))."' ,
                 `languageSpeak`='".htmlentities(add_slashes($REQUEST_DATA['toSpeak']))."',
                 `completedGraduation` = $completedGraduation,
                 `writtenFinalExam` = $writtenFinalExam,
                 `resultDue` = '$resultDue',
                 `coachingName` = '$coachingCenter',
                 `coachingManagerName` = '$coachingManager',
                 `coachingAddress` = '$coachingAddress',
                 `workExperience` = '$workExperience',
                 `departmentName` = '$department',
                 `organization` = '$organization',
                 `place` = '$place',
                 `programFeeId`=$programFeeId,
                 `highestEducationalQualification`='".add_slashes(trim($REQUEST_DATA['highestQualification'])) ."'
              WHERE  studentId = $studentId";

    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
  }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Delete Student Payment Details
// Author :Dipanjan BHattacharjee
// Created on : (29.09.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
public function deleteStudentPaymentDetails($studentId){
    $query="DELETE FROM student_payment_details WHERE studentId=$studentId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Insert Student Payment Details
// Author :Dipanjan BHattacharjee
// Created on : (29.09.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
public function insertStudentPaymentDetails($insertString){
    $query="INSERT INTO
                        student_payment_details
                         (studentId,ddNo,ddDate,ddAmount,ddBank)
                        VALUES $insertString ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteDocumentFile($studentId,$docId){
    $query="DELETE FROM student_document WHERE studentId=$studentId AND documentId=$docId";
    return SystemDatabaseManager::getInstance()->executeUpdate($query);
}

public function insertDocumentFile($studentId,$docId,$documentFileName){
    $query="INSERT INTO student_document(studentId,documentId,documentFileName) VALUES ($studentId ,$docId ,'".trim(add_slashes($documentFileName))."')";
    return SystemDatabaseManager::getInstance()->executeUpdate($query);
}

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE STUDENT Medical Ailment
//
// Author :Jaineesh
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
public function getStudentAilment($studentId) {

	$query = "	SELECT	COUNT(*) AS totalRecords
				FROM	student_ailment
				WHERE	studentId = $studentId
				";

	return SystemDatabaseManager::getInstance()->executeQuery($query);
  }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT LIST
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getStudentList($conditions='', $limit = '', $orderBy=' firstName',$showAlumnniStudent=1) {
        global $sessionHandler;
        $sepratorLen = strlen(CLASS_SEPRATOR);

        $alumniConditions='';
        if($showAlumnniStudent==1){
           $alumniConditions=' AND a.studentStatus=1  AND f.periodValue!=99999';
        }
        else if($showAlumnniStudent==2){
            $alumniConditions=' AND f.periodValue=99999';
        }
        else{
            $alumniConditions='AND ( b.isActive=1 or f.periodValue=99999 ) ';
        }

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
                          SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-3) AS className1,
                          (SELECT className FROM `class` cc WHERE cc.classId = a.classId) AS className,
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



                          studentPhoto, a.presentAddress1,   a.presentAddress2,  a.presentCountryId, a.presentStateId,
                          a.presentCityId,  a.presentPinCode,  a.presentPhone,
                          a.spouseName,  a.spouseRelation, a.spouseEmail, a.spouseAddress1, a.spouseAddress2,
                          a.spouseCountryId,  a.spouseStateId, a.spouseCityId, a.spousePinCode,  a.spousePhone,
                          a.currentOrg, a.companyDesignation, a.workEmail, a.officeContactNo,a.migrationStudyPeriod
                 FROM
                          university c, degree d, branch e, study_period f, batch btch, student a
                          LEFT JOIN `user` u ON  u.userId = a.userId
                          LEFT JOIN student_groups sg ON a.studentId = sg.studentId
                          LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
                          INNER JOIN class b ON (b.classId = a.classId OR sg.classId = b.classId)
                 WHERE
                          btch.batchId = b.batchId
                          AND b.universityId = c.universityId
                          AND b.degreeId = d.degreeId
                          AND b.branchId = e.branchId
                          AND b.studyPeriodId = f.studyPeriodId
                          AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                          $alumniConditions
                  $conditions
                  GROUP BY a.studentId
                  ORDER BY $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET TOTAL NUMBER OF STUDENTS (Duplicated getTotalStudent with a faster query)
//
// Author :Abhiraj
// Created on : (23.06.2011)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getTotalStudentFast($conditions='',$showAlumnniStudent=1) {
		global $sessionHandler;

        $alumniConditions='';
        if($showAlumnniStudent==1){
            $alumniConditions=' AND a.studentStatus=1  AND f.periodValue!=99999';
        }
        else if($showAlumnniStudent==2){
            $alumniConditions=' AND f.periodValue=99999';
        }
        else{
            $alumniConditions='AND ( a.studentStatus in (0,1) ) ';
        }


	  $query = "SELECT
							COUNT(DISTINCT a.studentId) AS totalRecords
				  FROM university c, degree d, branch e, study_period f, student a

				  LEFT JOIN student_groups sg ON a.studentId = sg.studentId
                  LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
                  INNER JOIN class b ON (b.classId = a.classId)

				  WHERE b.universityId = c.universityId
						AND b.degreeId = d.degreeId
						AND b.branchId = e.branchId
						AND b.studyPeriodId = f.studyPeriodId
				        AND	b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                        $alumniConditions
				  $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT LIST (Duplicated getstudentlist with a faster query)
//
// Author :Abhiraj
// Created on : (23.06.2011)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getStudentListFast($conditions='', $limit = '', $orderBy=' firstName',$showAlumnniStudent=1) {
        global $sessionHandler;
        $sepratorLen = strlen(CLASS_SEPRATOR);

        $alumniConditions='';
        if($showAlumnniStudent==1){
           $alumniConditions=' AND a.studentStatus=1  AND f.periodValue!=99999';
        }
        else if($showAlumnniStudent==2){
            $alumniConditions=' AND f.periodValue=99999';
        }
        else{
            $alumniConditions='AND ( a.studentStatus in (0,1) ) ';
        }

        $query = "SELECT
                        DISTINCT
                          b.classId as class_id,
                          a.studentId, lastName,a.studentStatus as studentStatus,
                          CONCAT(IFNULL(a.firstName,''),IFNULL(a.lastName,''),'-',IFNULL(batchName,'')) AS studentNameBatch,
                          CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) as studentName,
                          CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) as firstName,
                          IFNULL(a.firstName,'') as firstName1,
                          IF(IFNULL(rollNo,'')='','".NOT_APPLICABLE_STRING."',rollNo) AS rollNo,
                          IF(IFNULL(regNo,'')='','".NOT_APPLICABLE_STRING."',regNo) AS regNo,
                          IF(IFNULL(studentEmail,'')='','".NOT_APPLICABLE_STRING."',studentEmail) AS studentEmail,
                          IF(IFNULL(fatherName,'')='','".NOT_APPLICABLE_STRING."',fatherName) AS fatherName,
                          IF(IFNULL(universityRollNo,'')='','".NOT_APPLICABLE_STRING."',universityRollNo) AS universityRollNo,
                          SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-3) AS className1,
                          (SELECT className FROM `class` cc WHERE cc.classId = a.classId) AS className,
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
                          studentPhoto, a.presentAddress1,   a.presentAddress2,  a.presentCountryId, a.presentStateId,
                          a.presentCityId,  a.presentPinCode,  a.presentPhone,
                          a.spouseName,  a.spouseRelation, a.spouseEmail, a.spouseAddress1, a.spouseAddress2,
                          a.spouseCountryId,  a.spouseStateId, a.spouseCityId, a.spousePinCode,  a.spousePhone,
                          a.currentOrg, a.companyDesignation, a.workEmail, a.officeContactNo,a.migrationStudyPeriod
                 FROM
                          university c, degree d, branch e, study_period f, batch btch, student a
                          LEFT JOIN `user` u ON  u.userId = a.userId
                          LEFT JOIN student_groups sg ON a.studentId = sg.studentId
                          LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
                          INNER JOIN class b ON (b.classId = a.classId)
                 WHERE
                          btch.batchId = b.batchId
                          AND b.universityId = c.universityId
                          AND b.degreeId = d.degreeId
                          AND b.branchId = e.branchId
                          AND b.studyPeriodId = f.studyPeriodId
                          AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                          $alumniConditions
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

    public function getTotalRoleStudent($conditions='',$userId,$showAlumnniStudent=1) {
		global $sessionHandler;
		$roleId = $sessionHandler->getSessionVariable('RoleId');

        $alumniConditions='';
        if($showAlumnniStudent==1){
            $alumniConditions=' AND a.studentStatus=1  AND f.periodValue!=99999';
        }
        else if($showAlumnniStudent==2){
            $alumniConditions=' AND f.periodValue=99999';
        }
        else{
            $alumniConditions='AND ( b.isActive=1 or f.periodValue=99999 ) ';
        }

		$query = "SELECT
							COUNT(DISTINCT a.studentId) AS totalRecords
				  FROM
						  university c, degree d, branch e, study_period f, student a
					      LEFT JOIN student_groups sg ON a.studentId = sg.studentId
						  INNER JOIN classes_visible_to_role cvtr ON cvtr.classId = a.classId AND cvtr.userId = $userId AND cvtr.roleId = $roleId
					      LEFT JOIN `group` grp ON sg.groupId = grp.groupId AND cvtr.groupId = grp.groupId
					      LEFT JOIN class b ON b.classId = a.classId OR sg.classId = b.classId
				  WHERE
                          b.universityId = c.universityId
					      AND b.degreeId = d.degreeId
                          AND b.branchId = e.branchId
                          AND b.studyPeriodId = f.studyPeriodId
				          AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                          $alumniConditions
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
	public function getRoleStudentList($conditions='', $limit = '', $orderBy=' firstName',$userId,$showAlumnniStudent=1) {
		global $sessionHandler;
		$roleId = $sessionHandler->getSessionVariable('RoleId');

        $alumniConditions='';
        if($showAlumnniStudent==1){
            $alumniConditions=' AND a.studentStatus=1  AND f.periodValue!=99999';
        }
        else if($showAlumnniStudent==2){
            $alumniConditions=' AND f.periodValue=99999';
        }
        else{
            $alumniConditions='AND ( b.isActive=1 or f.periodValue=99999 ) ';
        }

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
				          SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-3) AS className1,
                          (SELECT className FROM `class` cc WHERE cc.classId = a.classId) AS className,
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
                          university c, degree d, branch e, study_period f, student a
					      LEFT JOIN student_groups sg ON a.studentId = sg.studentId
						  INNER JOIN classes_visible_to_role cvtr ON cvtr.classId = a.classId AND cvtr.userId = $userId AND cvtr.roleId = $roleId
					      LEFT JOIN `group` grp ON sg.groupId = grp.groupId AND cvtr.groupId = grp.groupId
					      LEFT JOIN class b ON b.classId = a.classId OR sg.classId = b.classId
				 WHERE
                          b.universityId = c.universityId
					      AND b.degreeId = d.degreeId
                          AND b.branchId = e.branchId
                          AND b.studyPeriodId = f.studyPeriodId
				          AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                          $alumniConditions
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

    public function getTotalRoleStudentFast($conditions='',$userId,$showAlumnniStudent=1) {
		global $sessionHandler;
		$roleId = $sessionHandler->getSessionVariable('RoleId');

        $alumniConditions='';
        if($showAlumnniStudent==1){
            $alumniConditions=' AND a.studentStatus=1  AND f.periodValue!=99999';
        }
        else if($showAlumnniStudent==2){
            $alumniConditions=' AND f.periodValue=99999';
        }
        else{
            $alumniConditions='AND ( a.studentStatus in (0,1) ) ';
        }

		$query = "SELECT
							COUNT(DISTINCT a.studentId) AS totalRecords
				  FROM
						  university c, degree d, branch e, study_period f, student a
					      LEFT JOIN student_groups sg ON a.studentId = sg.studentId
						  INNER JOIN classes_visible_to_role cvtr ON cvtr.classId = a.classId AND cvtr.userId = $userId AND cvtr.roleId = $roleId
					      LEFT JOIN `group` grp ON sg.groupId = grp.groupId AND cvtr.groupId = grp.groupId
					      LEFT JOIN class b ON b.classId = a.classId
				  WHERE
                          b.universityId = c.universityId
					      AND b.degreeId = d.degreeId
                          AND b.branchId = e.branchId
                          AND b.studyPeriodId = f.studyPeriodId
				          AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                          $alumniConditions
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
	public function getRoleStudentListFast($conditions='', $limit = '', $orderBy=' firstName',$userId,$showAlumnniStudent=1) {
		global $sessionHandler;
		$roleId = $sessionHandler->getSessionVariable('RoleId');

        $alumniConditions='';
        if($showAlumnniStudent==1){
            $alumniConditions=' AND a.studentStatus=1  AND f.periodValue!=99999';
        }
        else if($showAlumnniStudent==2){
            $alumniConditions=' AND f.periodValue=99999';
        }
        else{
            $alumniConditions='AND ( a.studentStatus in (0,1) ) ';
        }

		$sepratorLen = strlen(CLASS_SEPRATOR);
        $query = "SELECT
				        DISTINCT
                          b.classId as class_id,
				          a.studentId,
				          firstName,
				          lastName,a.studentStatus as studentStatus,
				          IF(a.studentEmail='','".NOT_APPLICABLE_STRING."',a.studentEmail) AS studentEmail,
				          CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) as firstName,
				          IF(rollNo='','".NOT_APPLICABLE_STRING."',IFNULL(rollNo,'".NOT_APPLICABLE_STRING."')) AS rollNo,
				          SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-3) AS className1,
                          (SELECT className FROM `class` cc WHERE cc.classId = a.classId) AS className,
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
                          university c, degree d, branch e, study_period f, student a
					      LEFT JOIN student_groups sg ON a.studentId = sg.studentId
						  INNER JOIN classes_visible_to_role cvtr ON cvtr.classId = a.classId AND cvtr.userId = $userId AND cvtr.roleId = $roleId
					      LEFT JOIN `group` grp ON sg.groupId = grp.groupId AND cvtr.groupId = grp.groupId
					      LEFT JOIN class b ON b.classId = a.classId
				 WHERE
                          b.universityId = c.universityId
					      AND b.degreeId = d.degreeId
                          AND b.branchId = e.branchId
                          AND b.studyPeriodId = f.studyPeriodId
				          AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                          $alumniConditions
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
	public function getCountFeesHistoryList($conditions='') {

		global $sessionHandler;
        $query = "SELECT
				  receiptNo
				  FROM student stu, fee_receipt fr,fee_cycle fc,class cls
				  WHERE
				  stu.studentId = fr.studentId
				  $conditions AND
				  fr.feeCycleId = fc.feeCycleId AND
				  stu.classId = cls.classId AND
				  cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND
				  cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');

		$query .=" GROUP BY feeReceiptId";

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
				  installmentCount as installmentCount1,
				 feeReceiptId,
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
				  FROM student stu, fee_receipt fr,fee_cycle fc,class cls
				  WHERE
				  stu.studentId = fr.studentId
				  $conditions AND
				  fr.feeCycleId = fc.feeCycleId AND
				  stu.classId = cls.classId AND
				  cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND
				  cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');

		$query .=" GROUP BY feeReceiptId,fullName  $orderBy $limit";

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
    public function getFeesHistoryListNew($conditions='', $limit = '', $orderBy='') {

        global $sessionHandler;
        $query = "SELECT
                        stu.studentId,receiptNo,
                        fr.totalFeePayable AS totalFeePayable, fr.fine AS fine,
                        (IFNULL(fr.fine,0)+IFNULL(totalFeePayable,0))-IFNULL((SELECT SUM(discountedAmount) FROM fee_head_student fhs
                                WHERE fhs.studentId=stu.studentId AND fhs.feeCycleId=fr.feeCycleId GROUP BY fhs.studentId),0)-
                             IFNULL((SELECT SUM(oldFr.totalAmountPaid) FROM fee_receipt oldFr WHERE
                                     oldFr.studentId = fr.studentId  AND
                                     oldFr.feeCycleId = fr.feeCycleId AND
                                     oldFr.feeReceiptId <  fr.feeReceiptId
                                GROUP BY oldFr.studentId, oldFr.feeCycleId),0) AS discountedFeePayable,
                        fr.totalAmountPaid AS totalAmountPaid,
                        IFNULL((IFNULL(fr.fine,0)+IFNULL(totalFeePayable,0))-
                        IFNULL((SELECT SUM(discountedAmount) FROM fee_head_student fhs
                        WHERE fhs.studentId=stu.studentId AND fhs.feeCycleId=fr.feeCycleId GROUP BY fhs.studentId),0)-(
                        IFNULL((SELECT SUM(oldFr.totalAmountPaid) FROM fee_receipt oldFr WHERE
                        oldFr.studentId = fr.studentId  AND
                        oldFr.feeCycleId = fr.feeCycleId AND
                        oldFr.feeReceiptId <= fr.feeReceiptId
                        GROUP BY oldFr.studentId, oldFr.feeCycleId),0)),0) AS previousDues,
                        fr.previousOverPayment AS previousOverPayment,
                        CAST(IF(fr.previousDues>0,fr.previousDues,IF(fr.previousOverPayment>0,CONCAT('-',fr.previousOverPayment),'0.00')) AS SIGNED) AS outstanding,
                        fc.cycleName, feeReceiptId,stu.rollNo,stu.regNo, stu.universityRollNo, stu.universityRegNo, CONCAT(stu.firstName,' ',stu.lastName) AS fullName,
                        srNo,feeType,DATE_FORMAT(receiptDate, '%d-%b-%y') AS receiptDate,
                        CONCAT('Installment','-',installmentCount) AS installmentCount, installmentCount AS installmentCount1,
                        SUBSTRING_INDEX(cls.className,'-',-3) AS className,  fc.feeCycleId,cls.classId,
                        fr.receiptStatus,fr.instrumentStatus
                  FROM
                        student stu, fee_receipt fr,fee_cycle fc,class cls
                  WHERE
                        stu.studentId = fr.studentId
                        $conditions AND
                        fr.feeCycleId = fc.feeCycleId AND
                        stu.classId = cls.classId AND
                        cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND
                        cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId')."
                  GROUP BY
                        feeReceiptId,fullName  $orderBy $limit";

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
				        DISTINCT
                        fc.cycleName, stu.classId, fr.paymentInstrument,
				        fr.feeCycleId, fr.totalAmountPaid, fr.transportPaid,
                        fr.transportFine, fr.hostelPaid, fr.hostelFine,
                        fr.feePaid, fr.fine, fr.isNew, fr.feeReceiptId, fr.cashAmount
				  FROM
                        student stu, fee_receipt fr,class cls,fee_cycle fc
				  WHERE
                        stu.classId = cls.classId AND stu.studentId = fr.studentId AND
                        fc.feeCycleId = fr.feeCycleId
                        $conditions
                  $orderBy";
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
				 stu.regNo,
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
// THIS FUNCTION IS USED TO GET STUDENT RECEIPT
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getFeesReceiptDetailNew($conditions='') {

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
                 stu.regNo,
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
                 fr.discountedFeePayable,
                 fr.fine,
                 fr.totalAmountPaid,


                 fr.payableBankId,
                 fr.favouringBankBranchId,

                 IFNULL((IFNULL(fr.fine,0)+IFNULL(totalFeePayable,0))-
                        IFNULL((SELECT SUM(discountedAmount) FROM fee_head_student fhs
                        WHERE fhs.studentId=stu.studentId AND fhs.feeCycleId=fr.feeCycleId GROUP BY fhs.studentId),0)-(
                        IFNULL((SELECT SUM(oldFr.totalAmountPaid) FROM fee_receipt oldFr WHERE
                        oldFr.studentId = fr.studentId  AND
                        oldFr.feeCycleId = fr.feeCycleId AND
                        oldFr.feeReceiptId <= fr.feeReceiptId
                        GROUP BY oldFr.studentId, oldFr.feeCycleId),0)),0) AS previousDues



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
// THIS FUNCTION IS USED TO GET STUDENT AILMENT DATA
//
// Author :Jaineesh
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getStudentAilmentData($studentId) {

        $query = "SELECT *
        FROM student_ailment
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

        $query = "SELECT receiptNo,DATE_FORMAT(receiptDate,'%d-%b-%Y') AS receiptDate,totalFeePayable,discountedFeePayable ,totalAmountPaid,sp.periodName,fc.cycleName
        FROM fee_receipt fr,fee_cycle fc,study_period sp
        WHERE
            fr.studentId=$studentId AND
            fr.feeCycleId = fc.feeCycleId AND
            fr.feeStudyPeriodId = sp.studyPeriodId
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

    public function getTotalStudent($conditions='',$showAlumnniStudent=1) {
		global $sessionHandler;

        $alumniConditions='';
        if($showAlumnniStudent==1){
            $alumniConditions=' AND a.studentStatus=1 AND f.periodValue!=99999';
        }
        else if($showAlumnniStudent==2){
            $alumniConditions=' AND f.periodValue=99999';
        }
        else{
            $alumniConditions='AND ( b.isActive=1 or f.periodValue=99999 ) ';
        }


	  $query = "SELECT
							COUNT(DISTINCT a.studentId) AS totalRecords
				  FROM university c, degree d, branch e, study_period f, student a

				  LEFT JOIN student_groups sg ON a.studentId = sg.studentId
                  LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
                  INNER JOIN class b ON (b.classId = a.classId OR sg.classId = b.classId)

				  WHERE b.universityId = c.universityId
						AND b.degreeId = d.degreeId
						AND b.branchId = e.branchId
						AND b.studyPeriodId = f.studyPeriodId
				        AND	b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                        $alumniConditions
				  $conditions";
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


    public function getTotalQuarantineStudent($conditions='',$showAlumnniStudent=1) {
		global $sessionHandler;

        $alumniConditions='';
        if($showAlumnniStudent==1){
            $alumniConditions=' AND b.isActive=1';
        }
        else if($showAlumnniStudent==2){
            $alumniConditions=' AND f.periodValue=99999';
        }
        else{
            $alumniConditions='AND ( b.isActive=1 or f.periodValue=99999 ) ';
        }

	  $query = "SELECT
							COUNT(DISTINCT a.studentId) AS totalRecords
				  FROM university c, degree d, branch e, study_period f, quarantine_student a

				  LEFT JOIN student_groups sg ON a.studentId = sg.studentId
                  LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
                  INNER JOIN class b ON (b.classId = a.classId OR sg.classId = b.classId)

				  WHERE b.universityId = c.universityId
						AND b.degreeId = d.degreeId
						AND b.branchId = e.branchId
						AND b.studyPeriodId = f.studyPeriodId
				        AND	b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                        $alumniConditions
				  $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT LIST
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getQuarantineStudentList($conditions='', $limit = '', $orderBy=' firstName',$showAlumnniStudent=1) {
        global $sessionHandler;
        $sepratorLen = strlen(CLASS_SEPRATOR);

        $alumniConditions='';
        if($showAlumnniStudent==1){
            $alumniConditions=' AND b.isActive=1';
        }
        else if($showAlumnniStudent==2){
            $alumniConditions=' AND f.periodValue=99999';
        }
        else{
            $alumniConditions='AND ( b.isActive=1 or f.periodValue=99999 ) ';
        }

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
                          university c, degree d, branch e, study_period f, batch btch, quarantine_student a
                          LEFT JOIN `user` u ON  u.userId = a.userId
                          LEFT JOIN student_groups sg ON a.studentId = sg.studentId
                          LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
                          INNER JOIN class b ON (b.classId = a.classId OR sg.classId = b.classId)

                 WHERE
                          btch.batchId = b.batchId
                          AND b.universityId = c.universityId
                          AND b.degreeId = d.degreeId
                          AND b.branchId = e.branchId
                          AND b.studyPeriodId = f.studyPeriodId
                          AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                          $alumniConditions
                  $conditions
                  GROUP BY a.studentId
                  ORDER BY $orderBy $limit";

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

    public function insertStudentParentUserRole($userId,$rollId) {

        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        return SystemDatabaseManager::getInstance()->runAutoInsert('user_role', array('userId','roleId','defaultRoleId','instituteId'), array($userId,$rollId,$rollId,$instituteId));
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

    public function insertUserRole($userId,$rollId) {

        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        //SystemDatabaseManager::getInstance()->runAutoInsert('user_role', array('userId','roleId', 'instituteId'), array($userId,$rollId,$instituteId));
        //return SystemDatabaseManager::getInstance()->lastInsertId();
        $query = "INSERT INTO user_role (`userId`,`roleId`,`defaultRoleId`, `instituteId`) values ('$userId','$rollId','$rollId','$instituteId') ";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function getUserRoleList($condition='') {

       global $REQUEST_DATA;
       global $sessionHandler;
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');

       $query = "SELECT
                      ur.userId, ur.roleId, ur.defaultRoleId, ur.instituteId
                 FROM
                      user_role ur
                 WHERE
                      ur.instituteId = $instituteId
                 $condition ";

       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
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
        s.quotaId,
        u.userName,
		cl.className,
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
        gudct.CityName as guardianCity,
		pcn.countryName as presentCountry,
        pst.stateName as presentState,
        pct.cityName as presentCity,
		scn.countryName as spouseCountry,
        sst.stateName as spouseState,
        sct.cityName as spouseCity
    FROM class cl, degree deg,
        branch br, batch bt, study_period sp, university un, student s
	LEFT JOIN states sst ON ( s.spouseStateId=sst.stateId )
    LEFT JOIN city sct ON ( s.spouseCityId = sct.cityId )
    LEFT JOIN countries scn ON ( s.spouseCountryId = scn.countryId )
	LEFT JOIN states pst ON ( s.presentStateId=pst.stateId )
    LEFT JOIN city pct ON ( s.presentCityId = pct.cityId )
    LEFT JOIN countries pcn ON ( s.presentCountryId = pcn.countryId )
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

 public function getStudentDetailedQuota($quotaId){
     $query="SELECT
                    CONCAT(IF(parentQuotaId!=0,(SELECT CONCAT(quotaName,'-- ') FROM quota WHERE quotaId =(SELECT parentQuotaId FROM quota WHERE quotaId=$quotaId)),''),quotaName) AS quotaName
             FROM
                    quota
             WHERE
                    quotaId=$quotaId";
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
			$classCond =" AND cl.classId IN ( ".add_slashes($classId).")";
		   }
		if ($classId==0) {
			$classCond = " AND cl.classId IN (".add_slashes($currentClassId).")";
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
                         ".TIME_TABLE_TABLE."  tt, `period` p, `student` s, `subject` sub, `employee` emp,
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
				      $classCond
                  UNION
                  SELECT
                        $fieldName
                  FROM
                         ".TIME_TABLE_TABLE."  tt, `period` p, `student` s, `subject` sub, `employee` emp,
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
		$query = "select count(studentId) as cnt from ".ATTENDANCE_TABLE." where classId = $classId and groupId in ($groupList)";
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
          $extC=' AND c.classId IN ('.$classId.')';
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
          $extC=' AND c.classId IN ('.$classId.')';
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
      $classCondition=" AND  stc.classId IN (".add_slashes($classId).')';
     }

     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');

     $query="SELECT courseResourceId,resourceName,description,subjectCode AS subject,
             IF(IFNULL(resourceUrl,'')='',-1,resourceUrl) AS resourceUrl,
             IF(IFNULL(attachmentFile,'')='',-1,attachmentFile) AS attachmentFile,
             employeeName, postedDate
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
		$query = "SELECT rollNo, concat(firstName,' ',lastName) as studentName FROM student WHERE classId = $classId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


    public function getActiveClasses() {
        global $sessionHandler;
        $instituteId    =    $sessionHandler->getSessionVariable('InstituteId');
        $sessionId        =    $sessionHandler->getSessionVariable('SessionId');
        $query = "select a.classId, className, count(b.studentId) as studentCount, marksTransferred from class a, student b where a.instituteId = $instituteId AND a.sessionId = $sessionId and a.isActive = 1 and a.classId = b.classId group by a.classId ORDER BY a.degreeId, a.branchId, a.studyPeriodId";
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

			$classCondition=" AND a.classId IN (".add_slashes($classId).')';
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
				AND			ttm.classId IN ( $classId )";

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

			$classCondition=" AND a.classId IN (".add_slashes($classId).')';
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


public function getStudentFinalResultListAdv($studentId,$classId='',$orderBy='',$limit=''){

        global $REQUEST_DATA;
        global $sessionHandler;

        if($classId!='' and $classId!=0){
           $classCondition=" AND cls.classId IN (".add_slashes($classId).')';
        }

        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

       $query="SELECT
                    DISTINCT a.subjectId, a.classId,
                    CONCAT(sub.subjectName,'- (',sub.subjectCode,')') as subjectCode,
                    SUBSTRING_INDEX(cls.className,'-',-1) AS periodName,
                    IFNULL(
                    (SELECT
                          IF(marksScoredStatus='Marks',concat(FORMAT(marksScored,2),'/',FORMAT(maxMarks,2)),marksScoredStatus)
                        FROM
                          ".TOTAL_TRANSFERRED_MARKS_TABLE."
                        WHERE
                          studentId = a.studentId and subjectId = a.subjectId
                          AND classId = a.classId
                          AND conductingAuthority=1
                      ),'".NOT_APPLICABLE_STRING."'
                    ) AS preComprehensive,
                    IFNULL(
                     (
                           SELECT
                                  IF(marksScoredStatus='Marks',concat(FORMAT(marksScored,2),'/',FORMAT(maxMarks,2)),marksScoredStatus)
                           FROM
                                  ".TOTAL_TRANSFERRED_MARKS_TABLE."
                           WHERE
                                  studentId = a.studentId and subjectId = a.subjectId
                          AND classId = a.classId
                          AND conductingAuthority=2
                          ),'".NOT_APPLICABLE_STRING."'
                     ) AS Comprehensive,
                     IFNULL(
                     (
                           SELECT
                                  IF(marksScoredStatus='Marks',concat(FORMAT(marksScored,2),'/',FORMAT(maxMarks,2)),marksScoredStatus)
                           FROM
                                  ".TOTAL_TRANSFERRED_MARKS_TABLE."
                           WHERE
                                  studentId = a.studentId and subjectId = a.subjectId
                          AND classId = a.classId
                          AND conductingAuthority=3
                          ),'".NOT_APPLICABLE_STRING."'
                     ) AS attendance
                    FROM
                    ".TOTAL_TRANSFERRED_MARKS_TABLE."  a,
                    `subject` sub,class cls
                  WHERE
                  a.studentId = $studentId
                  $classCondition
                  AND a.subjectId=sub.subjectId
                  AND a.classId=cls.classId
                  AND a.holdResult=0
                  AND cls.instituteId=$instituteId
                 GROUP BY a.subjectId, a.classId
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

	public function getStudentConductingFinalResultList($studentId,$classId='',$orderBy='',$limit=''){

		global $REQUEST_DATA;
		global $sessionHandler;

		if($classId!='' and $classId!=0){

			$classCondition=" AND a.classId IN (".add_slashes($classId).")";
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
								concat(a.firstName,' ',a.lastName) as studentName,
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
			$query = "SELECT a.classId, a.studentId, b.sessionId, b.instituteId,concat(a.firstName,' ',a.lastName) as studentName
			FROM student a, class b WHERE a.rollNo = '$rollNo' AND a.classId = b.classId and b.sessionId = $sessionId and b.instituteId = $instituteId";
		}
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getClassGroupTypeGroups($classId,$groupTypeId,$isOptionalCondition='') {
		$query = "SELECT groupId, groupShort FROM `group` WHERE groupTypeId = $groupTypeId and classId = $classId $isOptionalCondition order by groupShort";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentCurrentGroups($studentId,$classId, $conditions = '') {
		$query = "SELECT a.groupId, b.groupShort FROM student_groups a, `group` b WHERE a.studentId = $studentId and a.classId = $classId and a.groupId = b.groupId $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

    public function getSubjectOptionalGroups($studentId,$classId,$conditions='') {
       $query = "SELECT
                        s.subjectId,
                        s.subjectCode,
                        s.subjectName,
                        g.groupId,
                        g.groupShort,
                        IFNULL((
                          SELECT
                                1
                          FROM
                                student_optional_subject sos
                          WHERE
                                sos.subjectId=g.optionalSubjectId
                                AND sos.groupId=g.groupId
                                AND sos.studentId=$studentId
                                AND sos.classId=$classId
                        ),-1) as assignedGroup
                   FROM
                        `group` g,`subject` s
                   WHERE
                         g.optionalSubjectId=s.subjectId
                         AND g.classId=$classId
                         AND g.isOptional=1
                         AND g.optionalSubjectId
                                                IN
                                                  (
                                                    SELECT
                                                          DISTINCT subjectId
                                                    FROM
                                                          student_optional_subject
                                                    WHERE
                                                          classId=$classId
                                                          AND studentId=$studentId
                                                  )
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getStudentSubjectOptionalGroups($studentId,$classId,$conditions='') {
      $query = "SELECT
                        s.subjectId,
                        GROUP_CONCAT(g.groupId) AS groupIds
                   FROM
                        `group` g,`subject` s
                   WHERE
                         g.optionalSubjectId=s.subjectId
                         AND g.classId=$classId
                         AND g.isOptional=1
                         AND g.optionalSubjectId
                                                IN
                                                  (
                                                    SELECT
                                                          DISTINCT subjectId
                                                    FROM
                                                          student_optional_subject
                                                    WHERE
                                                          classId=$classId
                                                          AND studentId=$studentId
                                                  )
                 GROUP BY s.subjectId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getStudentSubjectOptionalGroupsAssigned($studentId,$classId,$conditions='') {
       $query = "SELECT
                        s.subjectId,
                        s.subjectCode,
                        s.subjectName,
                        g.groupId,
                        g.groupShort,
                        IFNULL((
                          SELECT
                                1
                          FROM
                                student_optional_subject sos
                          WHERE
                                sos.subjectId=g.optionalSubjectId
                                AND sos.groupId=g.groupId
                                AND sos.studentId=$studentId
                                AND sos.classId=$classId
                        ),-1) as assignedGroup
                   FROM
                        `group` g,`subject` s
                   WHERE
                         g.optionalSubjectId=s.subjectId
                         AND g.classId=$classId
                         AND g.isOptional=1
                         AND g.optionalSubjectId
                                                IN
                                                  (
                                                    SELECT
                                                          DISTINCT subjectId
                                                    FROM
                                                          student_optional_subject
                                                    WHERE
                                                          classId=$classId
                                                          AND studentId=$studentId
                                                  )
                   HAVING assignedGroup=1
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

 public function getGroupsInformation($conditions='') {

     $query="SELECT groupId,groupShort FROM `group` $conditions ";
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

        public function updateGradeScaleInTransaction($rangeFrom,$rangeTo, $whereCondition) {
            $query = "UPDATE grading_scales SET
                        gradingRangeFrom = '$rangeFrom',
                        gradingRangeTo = '$rangeTo'
                     WHERE
                        $whereCondition";
            return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
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
			$query = "SELECT count(studentId) AS count FROM ".ATTENDANCE_TABLE." WHERE classId = $classId";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function countTests($classId) {
			$query = "SELECT count(testId) AS count FROM test WHERE classId = $classId";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getClassGradeSetGradingLabel($classId,$subjectId) {
			$query = "SELECT DISTINCT gradeSetId, gradingLabelId FROM ".TOTAL_TRANSFERRED_MARKS_TABLE." WHERE gradeId IS NOT NULL and subjectId = '$subjectId' AND classId = '$classId'";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR updating marks of students
//
// Author :Ajinder Singh
// Created on : (05-apr-2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
	public function updateStudentMarks($studentId,$classId,$subjectId,$conductingAuthority,$marksScored,$gradeId,$marksScoredStatus = 'Marks', $gradeSetId, $gradingLabelId) {
		$query = "
				UPDATE
								".TOTAL_TRANSFERRED_MARKS_TABLE."
				SET				marksScored = $marksScored,
								gradeId = $gradeId,
								marksScoredStatus = '$marksScoredStatus',
								gradeSetId = $gradeSetId,
								gradingLabelId = $gradingLabelId
				WHERE			studentId = $studentId
				AND				classId = $classId
				AND				subjectId = $subjectId
				AND				conductingAuthority = $conductingAuthority";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}



		public function getStudentOldAttendance($studentId, $classId, $subjectId, $groupId) {
			global $sessionHandler;
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');
			$query = "
					  SELECT
									SUM(IF(a.isMemberOfClass = 0,0, IF(b.attendanceCodePercentage IS NULL, a.lectureDelivered, 1))) AS lectureDelivered,
									ROUND(SUM(IF(a.isMemberOfClass = 0,0, if(b.attendanceCodePercentage IS NULL, a.lectureAttended,
									b.attendanceCodePercentage/100))),2) as lectureAttended
					  FROM			".ATTENDANCE_TABLE." a
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
							  FROM			".ATTENDANCE_TABLE." a, student b
							  WHERE			a.subjectId=$subjectId
							  AND			a.classId=$classId
							  AND			a.groupId = $groupId
							  AND			a.studentId = b.studentId
							  GROUP BY		a.studentId) as t";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getGroupEmployee($classId,$subjectId,$groupId) {
			$query = "SELECT employeeId FROM  ".TIME_TABLE_TABLE." WHERE subjectId = $subjectId AND groupId = $groupId ORDER BY timeTableId DESC limit 0,1";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getGroupAttDate($classId,$subjectId,$groupId) {
			$query = "SELECT min(fromDate) as fromDate, max(toDate) as toDate FROM ".ATTENDANCE_TABLE." WHERE classId = $classId AND subjectId = $subjectId AND groupId = $groupId";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getGroupAttendanceDetails($classId,$subjectId,$groupId) {
			$query = "
						SELECT
										DISTINCT employeeId, attendanceType, attendanceCodeId, periodId, fromDate, toDate, lectureDelivered, topicsTaughtId
						FROM			".ATTENDANCE_TABLE."
						WHERE			classId = $classId
						AND			subjectId = $subjectId
						AND			groupId = $groupId
						and			studentId =
														(
																	SELECT
																				studentId
																	FROM
																				(
																						SELECT a.studentId, SUM(a.lectureDelivered) AS lectureDelivered FROM ".ATTENDANCE_TABLE." a, student b WHERE a.subjectId=$subjectId AND a.classId=$classId AND a.groupId = $groupId AND a.studentId = b.studentId GROUP BY	a.studentId
																				) as t order by lectureDelivered desc limit 0,1
														)
						order by fromDate desc";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getSingleField($table, $field, $conditions='') {
			$query = "SELECT $field FROM $table $conditions";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}





             public function getSingleFieldForGrandMarks( $field, $conditions='') {
			$query = "SELECT $field FROM `subject_to_class` $conditions";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}


                public function getSubjectIds($table,$subjectIdCondition) {
			$query = "SELECT  DISTINCT subjectId FROM $table$subjectIdCondition";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}






		public function getMultipleField($table1,$table2 = '', $field, $conditions='') {
			$query = "SELECT $field FROM $table1, $table2 $conditions";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getClassSubjectRounding($subjectId,$classId) {
			$query = "
					SELECT
									rounding
					FROM			subject_to_class
					WHERE			subjectId = $subjectId
					AND			classId = $classId
					UNION
					SELECT
									rounding
					FROM			optional_subject_to_class
					WHERE			subjectId = $subjectId
					AND			classId = $classId
			";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		//-------------------------------------------------------
		//  THIS FUNCTION IS USED FOR fetching max. marks
		//
		// Author :Ajinder Singh
		// Created on : 04-May-2009
		// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
		//
		//--------------------------------------------------------
		public function getSubjectMaxMarks($subjectId, $degreeId) {
			$query = "SELECT SUM(maxMarks) AS maxMarks FROM ".TOTAL_TRANSFERRED_MARKS_TABLE." where classId IN ($degreeId) and subjectId = $subjectId GROUP BY studentId,classId,subjectId LIMIT 0,1";
			return SystemDatabaseManager::getInstance()->executeQuery($query);
		}

		public function getLineChartData($subjectId, $degreeList, $gradingFormula = '') {

            global $sessionHandler;
            $sessionId = $sessionHandler->getSessionVariable('SessionId');
            $instituteId = $sessionHandler->getSessionVariable('InstituteId');

            $decimals = '';
			if ($gradingFormula == 'round2') {
				$gradingFormula = 'round';
				$decimals = ',2';
			}


            if($subjectId=='') {
              $subjectId='0';
            }

            if($degreeList=='') {
              $degreeList='0';
            }



			$query = "
							SELECT cnt, count(*) as cnt2 FROM
							(
								SELECT
											$gradingFormula(SUM(if(a.marksScored < 0, 0, a.marksScored))+IFNULL((SELECT SUM(graceMarks) FROM ".TEST_GRACE_MARKS_TABLE." gm
                                                    WHERE gm.studentId = a.studentId AND gm.classId = a.classId AND gm.subjectId = a.subjectId GROUP BY gm.studentId, gm.classId, gm.subjectId),0)$decimals) as cnt
								FROM 		".TOTAL_TRANSFERRED_MARKS_TABLE." a, class b, student c
								WHERE 		a.classId = b.classId
								AND			b.classId IN ($degreeList)
								AND 		a.subjectId = $subjectId
								AND			b.instituteId = $instituteId
								AND			b.sessionId = $sessionId
								AND			a.studentId = c.studentId
								AND			a.marksScoredStatus  = 'Marks'
								GROUP	BY	a.studentId
							)	AS	sumTotal GROUP BY cnt";
			  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

		}

		public function checkGradingLabelName($gradingLabelName) {
			global $sessionHandler;
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');
			$query = "SELECT COUNT(*) AS cnt FROM grading_labels WHERE gradingLabel = '$gradingLabelName' AND instituteId = $instituteId";
			 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function addGradingLabelInTransaction($gradingLabelName) {
			global $sessionHandler;
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');
			$sessionId = $sessionHandler->getSessionVariable('SessionId');
			$query = "INSERT INTO grading_labels SET gradingLabel = '$gradingLabelName', instituteId = $instituteId, sessionId = $sessionId";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function getActiveSetGrades($ordering = 'ASC') {
			global $sessionHandler;
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');
			$query = "SELECT a.gradeId, a.gradeLabel, a.gradePoints, b.gradeSetId FROM grades a, grades_set b where a.gradeSetId = b.gradeSetId and b.isActive = 1 AND a.instituteId = $instituteId AND b.instituteId = $instituteId ORDER BY a.gradePoints $ordering";
			return SystemDatabaseManager::getInstance()->executeQuery($query);
		}

        public function getShowGrades($condition='',$ordering = 'ASC') {
            global $sessionHandler;
            $instituteId = $sessionHandler->getSessionVariable('InstituteId');

            $query = "SELECT
                            a.gradeId, a.gradeLabel, a.gradePoints, IFNULL(b.gradingScaleId,'-1') AS gradingScaleId,
                            b.gradingLabelId, b.gradingRangeFrom, b.gradingRangeTo
                      FROM
                            grades_set gs, grades a
                            LEFT JOIN grading_scales b ON a.gradeId = b.gradeId $condition
                      WHERE
                            a.instituteId = '$instituteId' AND
                            gs.gradeSetId = a.gradeSetId AND
                            gs.isActive = '1' AND
                            gs.instituteId = '$instituteId'
                      ORDER BY
                            a.gradePoints $ordering";

            return SystemDatabaseManager::getInstance()->executeQuery($query);
        }


		public function getGradeRangeStudents($subjectId, $rangeFrom, $rangeTo, $degreeList,$gradingFormula='') {
			$decimals = '';
			if ($gradingFormula == 'round2') {
				$gradingFormula = 'round';
				$decimals = ',2';
			}
			global $sessionHandler;
			$sessionId = $sessionHandler->getSessionVariable('SessionId');
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');
			/*
			$query = "
					SELECT
								distinct ttm.studentId,ttm.classId
					FROM		".TOTAL_TRANSFERRED_MARKS_TABLE." ttm, class cls, student stu
					WHERE		ttm.subjectId = $subjectId
					AND			ttm.classId IN ($degreeList)
					AND			ttm.classId = cls.classId
					AND			cls.instituteId = $instituteId
					AND			ttm.studentId = stu.studentId
					AND			ttm.marksScoredStatus = 'Marks'
					GROUP BY	stu.studentId
					HAVING		$gradingFormula((SUM(if(ttm.marksScored < 0, 0, ttm.marksScored))*100)/SUM(ttm.maxMarks) $decimals)
					BETWEEN		$rangeFrom AND $rangeTo
				";
				*/
				$query = "SELECT a.studentId, a.classId,
                            $gradingFormula(SUM(IF(a.marksScored < 0, 0, a.marksScored))+IFNULL((SELECT
								SUM(graceMarks) FROM ".TEST_GRACE_MARKS_TABLE." gm WHERE gm.studentId = a.studentId AND gm.classId = a.classId AND gm.subjectId = a.subjectId GROUP BY gm.studentId, gm.classId, gm.subjectId),0)$decimals) AS totalMarks FROM ".TOTAL_TRANSFERRED_MARKS_TABLE." a, class b, student c
				WHERE 		a.classId = b.classId AND b.classId IN ($degreeList) AND a.subjectId = $subjectId AND b.instituteId = $instituteId AND b.sessionId = $sessionId AND a.studentId = c.studentId AND a.marksScoredStatus  = 'Marks' GROUP	BY	a.studentId, subjectId having totalMarks between $rangeFrom AND $rangeTo";
			  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function getTestTypeClasses() {
			$query = "select a.testTypeId, b.classId from test_type a, class b where SUBSTRING_INDEX(a.testTypeName,'#',-1) = b.className";
			 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

		public function updateClassIdInTransaction($testTypeId, $classId) {
			$query = "UPDATE test_type set classId = $classId where testTypeId = $testTypeId";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function getGradeLabels($gadeLabelId) {
			  global $sessionHandler;
			  $instituteId = $sessionHandler->getSessionVariable('InstituteId');
				$query = "
			            SELECT
                	        a.gradingRangeFrom,
					        a.gradingRangeTo,
					        a.gradeId,
					        b.gradeLabel,
					        b.gradePoints,
                            b.gradeSetId
				        FROM
                            grading_scales a,
					        grades b
				        WHERE
                	        a.gradingLabelId = $gadeLabelId
				            AND	a.gradeId = b.gradeId
				            AND b.instituteId = $instituteId
				            AND	a.instituteId = $instituteId
				        ORDER BY
                            gradingRangeFrom DESC";

			  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}


		public function addGradingScalesInTransaction($insertStr) {
			$query = "INSERT INTO grading_scales (gradingLabelId,gradingRangeFrom,gradingRangeTo,gradeId, instituteId) VALUES $insertStr";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}


        public function deleteLastGradingScalesInTransaction($internalTotalMarks) {
            global $sessionHandler;
            $instituteId = $sessionHandler->getSessionVariable('InstituteId');

            $query = "DELETE FROM grading_scales_last WHERE instituteId = $instituteId  AND totalInternalMarks = '$internalTotalMarks'";
            return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        }

        public function addLastGradingScalesInTransaction($insertStr) {
            $query = "INSERT INTO grading_scales_last (gradingRangeFrom,gradingRangeTo,instituteId,totalInternalMarks) VALUES $insertStr";
            return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        }

        public function getLastGradingScales() {
            global $sessionHandler;
            $instituteId = $sessionHandler->getSessionVariable('InstituteId');

            $query = "SELECT
                           gradingRangeFrom, gradingRangeTo
                      FROM
                           grading_scales_last
                      WHERE
                           instituteId = $instituteId
                      ORDER BY
                           gradingScaleLastId";

            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }

     public function getLastGradingScalesNew($internalTotalMarks) {
            global $sessionHandler;
            $instituteId = $sessionHandler->getSessionVariable('InstituteId');

            $query = "SELECT
                           gradingRangeFrom, gradingRangeTo
                      FROM
                           grading_scales_last
                      WHERE
                           instituteId = $instituteId AND totalInternalMarks='$internalTotalMarks'
                      ORDER BY
                           gradingScaleLastId";

            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }

		public function addAttendanceInTransaction($classId,$groupId, $studentId, $subjectId, $employeeId, $fromDate, $toDate, $attendanceType, $attendanceCodeId, $periodId, $lectureDelivered, $lectureAttended, $topicsTaughtId) {
			global $sessionHandler;
			$userId = $sessionHandler->getSessionVariable('UserId');
			$query = "
						INSERT INTO			".ATTENDANCE_TABLE."
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
								".QUARANTINE_ATTENDANCE_TABLE." (
														classId,groupId,studentId,subjectId,employeeId,
														attendanceType,attendanceCodeId,periodId,fromDate,toDate,
														isMemberOfClass,lectureDelivered,lectureAttended,userId,deletionType
													 )
								SELECT

														classId,groupId,studentId,subjectId,employeeId,
														attendanceType,attendanceCodeId,periodId,fromDate,toDate,
														isMemberOfClass,lectureDelivered,lectureAttended,userId,2

								FROM			".ATTENDANCE_TABLE."
								WHERE			classId = $classId
								AND				groupId = $groupId
								AND				studentId = $studentId
								AND				subjectId = $subjectId";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function deleteAttendanceInTransaction($classId,$groupId, $studentId, $subjectId) {
			$query = "
						DELETE FROM
												".ATTENDANCE_TABLE."
								WHERE			classId = $classId
								AND				groupId = $groupId
								AND				studentId = $studentId
								AND				subjectId = $subjectId";

			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function addMarksInTransaction($newTestId, $studentId, $subjectId, $newMaxMarks, $newMarksScored, $isPresent, $isMemberOfClass) {
			$query = "
						INSERT INTO ".TEST_MARKS_TABLE." set
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

			$query = "insert into ".QUARANTINE_TEST_MARKS_TABLE." (testMarksId,testId, studentId, subjectId, maxMarks, marksScored, isPresent, isMemberOfClass, deletionType) select testMarksId,testId, studentId, subjectId, maxMarks, marksScored, isPresent, isMemberOfClass, 2 from test_marks where testId = $testId and studentId = $studentId and subjectId = $subjectId";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function deleteMarksInTransaction($oldTestId,$studentId, $classId, $subjectId, $groupId) {
			$query = "delete from ".TEST_MARKS_TABLE." where testId = $oldTestId and studentId = $studentId and subjectId = $subjectId";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

		public function updateStudentGroupInTransaction($classId,$studentId, $oldGroupId, $newGroupId) {
			$query = "update student_groups set groupId = $newGroupId where studentId = $studentId and classId = $classId and groupId = $oldGroupId";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}

        public function updateStudentOptionalGroupInTransaction($classId,$subjectId,$studentId, $oldGroupId, $newGroupId) {
            $query = "update student_optional_subject set groupId = $newGroupId where studentId = $studentId and classId = $classId and groupId = $oldGroupId and subjectId=$subjectId";
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
								".TEST_TABLE." t,
								".TEST_MARKS_TABLE." tm
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
								".TEST_TABLE." t
					WHERE
								t.testTypeCategoryId = tt.testTypeCategoryId
					AND			t.classId = $classId
					AND			t.subjectId = $subjectId
					AND			t.groupId = $groupId
					ORDER BY	testName";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}

	public function getGradeNotEnteredSubjects($degreeId, $classSubjectList) {
		$query = "
					SELECT
								DISTINCT a.subjectId, b.subjectCode
					FROM
								".TOTAL_TRANSFERRED_MARKS_TABLE." a, subject b
					WHERE		a.classId = $degreeId
					AND			a.subjectId IN ($classSubjectList)
					AND			a.gradeId IS NULL
					AND			a.subjectId = b.subjectId
					AND			a.marksScoredStatus = 'Marks'
					AND			a.studentId in (SELECT studentId FROM student)";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}
	public function deleteClassCGPA($degreeId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "DELETE FROM student_cgpa  WHERE classId = $degreeId AND instituteId = $instituteId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function doCGPACalculation($degreeId, $classSubjectList) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');



		$query = "INSERT IGNORE INTO student_cgpa
                         (studentId, classId, credits, gradeIntoCredits, instituteId)
				  SELECT
                         md.studentId, md.classId,sum(md.credits) as totalCredits, sum(md.credits * md.gradePoints) as gradeIntoCredits, $instituteId
				  FROM (SELECT
                                DISTINCT a.studentId, a.classId,a.subjectId, a.gradeId, b.credits, c.gradePoints
                        FROM
                                ".TOTAL_TRANSFERRED_MARKS_TABLE." a, subject_to_class b, grades c
                        WHERE
                                b.optional=0 AND a.classId = $degreeId AND a.subjectId IN ($classSubjectList) AND a.classId = b.classId
					            AND	a.subjectId = b.subjectId AND a.gradeId = c.gradeId AND c.instituteId = $instituteId
                        UNION
                        SELECT
                                DISTINCT a.studentId, a.classId,a.subjectId, a.gradeId, b.credits, c.gradePoints
                        FROM
                                ".TOTAL_TRANSFERRED_MARKS_TABLE." a, subject_to_class b, grades c, optional_subject_to_class oc
                        WHERE
                                oc.classId = b.classId AND b.optional=1 AND
                                oc.parentOfSubjectId = b.subjectId AND
                                oc.subjectId IN  ($classSubjectList) AND
                                a.classId = $degreeId AND a.subjectId IN ($classSubjectList) AND a.classId = b.classId
                                AND a.subjectId = oc.subjectId AND a.gradeId = c.gradeId AND c.instituteId = $instituteId
                        UNION
                        SELECT
                                DISTINCT a.studentId, a.classId,a.subjectId, a.gradeId, b.credits, c.gradePoints
                        FROM
                                ".TOTAL_TRANSFERRED_MARKS_TABLE." a, subject_to_class b, grades c, student_optional_subject oc
                        WHERE
                                oc.classId = b.classId AND b.optional=1 AND
                                oc.subjectId = b.subjectId AND
                                oc.subjectId IN  ($classSubjectList) AND  a.studentId = oc.studentId AND
                                a.classId = $degreeId AND a.subjectId IN ($classSubjectList) AND a.classId = b.classId
                                AND a.subjectId = oc.subjectId AND a.gradeId = c.gradeId AND c.instituteId = $instituteId
                        ) AS md
                  GROUP BY md.studentId ORDER BY md.studentId";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

	}


    public function  doCGPANotExternalMarks($degreeId,$classSubjectList) {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        DISTINCT a.classId, a.studentId, a.subjectId, a.conductingAuthority, a.marksScoredStatus
                  FROM
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." a
                  WHERE
                        a.classId = '$degreeId' AND
                        a.subjectId IN ($classSubjectList) AND
                        a.conductingAuthority IN (2) AND
                        a.marksScoredStatus != 'Marks'
                  ORDER BY
                        a.classId, a.studentId, a.subjectId, a.conductingAuthority ";

       return SystemDatabaseManager::getInstance()->executeQuery($query);
    }

     public function doCGPAStudentList($degreeId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                      DISTINCT a.studentId
                  FROM
                      ".TOTAL_TRANSFERRED_MARKS_TABLE." a
                  WHERE
                      classId = '$degreeId' ";

       return SystemDatabaseManager::getInstance()->executeQuery($query);
    }


    public function doCGPACalculationNew($degreeId, $classSubjectList,$studentId='',$condition='',$includeFail='1') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        if($includeFail=='') {
          $includeFail='1';
        }

        $query = "INSERT IGNORE INTO student_cgpa
                         (studentId, classId, credits, gradeIntoCredits,  instituteId, includeFailGrade)
                  SELECT
                         md.studentId, md.classId,
                         SUM(md.credits) as totalCredits, SUM(md.credits * md.gradePoints) as gradeIntoCredits,
                         $instituteId, $includeFail
                  FROM (SELECT
                                DISTINCT a.studentId, a.classId,a.subjectId, a.gradeId, b.credits, c.gradePoints
                        FROM
                                ".TOTAL_TRANSFERRED_MARKS_TABLE." a, subject_to_class b, grades c
                        WHERE
                                b.optional=0 AND a.classId = $degreeId AND a.subjectId IN ($classSubjectList) AND a.classId = b.classId
                                AND a.subjectId = b.subjectId AND a.gradeId = c.gradeId AND c.instituteId = $instituteId
                                AND a.studentId = '$studentId'
                                AND a.marksScoredStatus = 'Marks'
                                $condition
                        UNION
                        SELECT
                                DISTINCT a.studentId, a.classId,a.subjectId, a.gradeId, b.credits, c.gradePoints
                        FROM
                                ".TOTAL_TRANSFERRED_MARKS_TABLE." a, subject_to_class b, grades c, optional_subject_to_class oc
                        WHERE
                                oc.classId = b.classId AND b.optional=1 AND
                                oc.parentOfSubjectId = b.subjectId AND
                                oc.subjectId IN  ($classSubjectList) AND
                                a.classId = $degreeId AND a.subjectId IN ($classSubjectList) AND a.classId = b.classId
                                AND a.subjectId = oc.subjectId AND a.gradeId = c.gradeId AND c.instituteId = $instituteId
                                AND a.studentId = '$studentId'
                                AND a.marksScoredStatus = 'Marks'
                                $condition
                        UNION
                        SELECT
                                DISTINCT a.studentId, a.classId,a.subjectId, a.gradeId, b.credits, c.gradePoints
                        FROM
                                ".TOTAL_TRANSFERRED_MARKS_TABLE." a, subject_to_class b, grades c, student_optional_subject oc
                        WHERE
                                oc.classId = b.classId AND b.optional=1 AND
                                oc.subjectId = b.subjectId AND
                                oc.subjectId IN  ($classSubjectList) AND
                                a.studentId = oc.studentId AND
                                a.classId = $degreeId AND a.subjectId IN ($classSubjectList) AND a.classId = b.classId
                                AND a.subjectId = oc.subjectId AND a.gradeId = c.gradeId AND c.instituteId = $instituteId
                                AND a.studentId = '$studentId'
                                AND a.marksScoredStatus = 'Marks'
                                $condition
                        ) AS md
                  GROUP BY md.studentId ORDER BY md.studentId";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

   public function doGPACGPA($classId, $studentId='', $gpa='',$cgpa='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "UPDATE student_cgpa
                  SET
                       gpa = '$gpa',
                       cgpa = '$cgpa'
                  WHERE
                       studentId = '$studentId' AND
                       classId = '$classId' ";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function doCGPANet($classId, $studentId='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "UPDATE student_cgpa
                  SET
                       netCredit = (currentCredit+previousCredit)-lessCredit,
                       netGradePoint = (currentGradePoint+previousGradePoint)-lessGradePoint,
                       netCreditEarned =(currentCreditEarned+previousCreditEarned)
                  WHERE
                       studentId = '$studentId' AND
                       classId = '$classId' ";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


    public function doCGPAPreviousUpdate($classId, $studentId='', $lessCredit='',$lessCreditEarned='0',$lessGradePoint='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "UPDATE student_cgpa
                  SET
                       previousCredit = '$lessCredit',
                       previousGradePoint = '$lessGradePoint',
                       previousCreditEarned = '$lessCreditEarned'
                  WHERE
                       studentId = '$studentId' AND
                       classId = '$classId' ";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


     public function doCGPACurrentUpdate($classId, $studentId='', $lessCredit='',$lessCreditEarned='0',$lessGradePoint='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "UPDATE student_cgpa
                  SET
                       currentCredit = '$lessCredit',
                       currentGradePoint = '$lessGradePoint',
                       currentCreditEarned = '$lessCreditEarned'
                  WHERE
                       studentId = '$studentId' AND
                       classId = '$classId' ";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


    public function doCGPALessUpdate($classId, $studentId='', $lessCredit='',$lessCreditEarned='0',$lessGradePoint='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "UPDATE student_cgpa
                  SET
                       lessCredit = '$lessCredit',
                       lessGradePoint = '$lessGradePoint',
                       lessCreditEarned = '$lessCreditEarned'
                  WHERE
                       studentId = '$studentId' AND
                       classId = '$classId' ";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function getStudentPreviousClasses($classId) {

        $query = "SELECT
                        c.classId, c.studyPeriodId, sp.periodName, sp.periodValue
                  FROM
                        class c, study_period sp
                  WHERE
                        c.studyPeriodId = sp.studyPeriodId AND
                        CONCAT_WS(',',degreeId,batchId,branchId) IN
                        (SELECT CONCAT_WS(',',cc.degreeId,cc.batchId,cc.branchId) FROM class cc WHERE classId = '$classId') AND
                        classId < '$classId' ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getStudentPreviousGrade($condition='') {

         $query = "SELECT
                         sub.subjectCode, c.className, c.classId, a.marksScoredStatus, a.studentId, a.subjectId,  gr.failGrade,
                         IFNULL(gr.gradeLabel,'') AS gradeLabel, IFNULL(gr.gradeSetId,'') AS gradeSetId, IFNULL(gr.gradeId,'') AS gradeId,
                         IFNULL(gr.gradePoints,'0') AS gradePoints, sc.credits, IFNULL(gr.gradePoints,'0')*IFNULL(sc.credits,'0') AS totalGrade
                   FROM
                         subject sub, class c, subject_to_class sc, ".TOTAL_TRANSFERRED_MARKS_TABLE." a
                         LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId
                   WHERE
                        sub.subjectId = a.subjectId AND
                        c.classId = sc.classId AND
                        sc.optional=0 AND
                        sc.classId = a.classId AND
                        sc.subjectId = a.subjectId AND
                        a.conductingAuthority IN (1,2,3)
                        $condition
                   GROUP BY
                        a.studentId, a.classId, a.subjectId
                   UNION
                   SELECT
                         sub.subjectCode, c.className, c.classId, a.marksScoredStatus, a.studentId, a.subjectId, gr.failGrade,
                         IFNULL(gr.gradeLabel,'') AS gradeLabel, IFNULL(gr.gradeSetId,'') AS gradeSetId, IFNULL(gr.gradeId,'') AS gradeId,
                         IFNULL(gr.gradePoints,'0') AS gradePoints, sc.credits, IFNULL(gr.gradePoints,'0')*IFNULL(sc.credits,'0') AS totalGrade
                   FROM
                         subject sub, class c, optional_subject_to_class oc, subject_to_class sc, ".TOTAL_TRANSFERRED_MARKS_TABLE." a
                         LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId
                   WHERE
                        sub.subjectId = a.subjectId AND
                        c.classId = oc.classId AND
                        oc.classId = sc.classId AND sc.optional=1 AND
                        oc.parentOfSubjectId = sc.subjectId AND
                        oc.classId = a.classId AND
                        oc.subjectId = a.subjectId AND
                        a.conductingAuthority IN (1,2,3)
                        $condition
                   GROUP BY
                        a.studentId, a.classId, a.subjectId
                   UNION
                   SELECT
                         sub.subjectCode, c.className, c.classId, a.marksScoredStatus, a.studentId, a.subjectId,  gr.failGrade,
                         IFNULL(gr.gradeLabel,'') AS gradeLabel, IFNULL(gr.gradeSetId,'') AS gradeSetId, IFNULL(gr.gradeId,'') AS gradeId,
                         IFNULL(gr.gradePoints,'0') AS gradePoints, sc.credits, IFNULL(gr.gradePoints,'0')*IFNULL(sc.credits,'0') AS totalGrade
                   FROM
                         subject sub, class c, student_optional_subject oc, subject_to_class sc, ".TOTAL_TRANSFERRED_MARKS_TABLE." a
                         LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId
                   WHERE
                        sub.subjectId = a.subjectId AND
                        c.classId = oc.classId AND
                        oc.classId = sc.classId AND sc.optional=1 AND
                        oc.subjectId = sc.subjectId AND
                        oc.classId = a.classId AND
                        oc.subjectId = a.subjectId AND
                        a.conductingAuthority IN (1,2,3) AND a.studentId = oc.studentId
                        $condition
                   GROUP BY
                        a.studentId, a.classId, a.subjectId
                   ORDER BY
                        studentId, subjectId, classId DESC";

          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getStudentExternalMarks($classId='') {

       $query = "SELECT
                      DISTINCT studentId,classId,subjectId,marksScoredStatus
                 FROM
                      ".TOTAL_TRANSFERRED_MARKS_TABLE." a
                 WHERE
                      a.marksScoredStatus != 'Marks' AND
                      a.classId = '$classId'
                 ORDER BY
                      a.studentId, a.subjectId, a.classId  ";

       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getCreditsNotEnteredSubjects($degreeId, $classSubjectList) {
		//AND       a.credits = 0
        $query = "
					SELECT
								DISTINCT a.subjectId, b.subjectCode
					FROM
								subject_to_class a, subject b
					WHERE		a.classId = $degreeId
					AND			a.subjectId IN ($classSubjectList)
				    AND         IFNULL(a.credits,'') = ''
					AND			a.subjectId = b.subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
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

			$classCondition=" AND sd.classId IN (".add_slashes($classId).')';
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
                    AND             b.classId = $degree
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
                    AND             b.classId = c.classId
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
                    SELECT
                            grp.studentId, att.marksScored
                    FROM
                            (SELECT
                                    a.studentId, FORMAT(SUM(IF(a.isMemberOfClass=0,0, IF(a.attendanceType =2, (b.attendanceCodePercentage /100),
                                    a.lectureAttended ))), 1 ) AS lectureAttended,
                                    (SELECT
                                            SUM(leavesTaken)
                                     FROM
                                           ".DUTY_LEAVE_TABLE."  dl
                                     WHERE
                                          dl.studentId = a.studentId

                                          AND dl.classId=a.classId
                                          AND dl.subjectId=a.subjectId
                                          AND dl.rejected   = ".DUTY_LEAVE_APPROVE." ) as dutyLeaves,
                                     SUM(IF(a.isMemberOfClass=0,0, a.lectureDelivered)) AS lectureDelivered FROM ".ATTENDANCE_TABLE." a LEFT JOIN attendance_code b ON (a.attendanceCodeId = b.attendanceCodeId and b.instituteId = $instituteId) WHERE a.classId IN ($classId) AND a.subjectId = $subjectId group by a.studentId) AS grp, attendance_marks_percent AS att, student stu WHERE grp.studentId = stu.studentId AND att.subjectTypeId = $subjectTypeId AND att.instituteId = $instituteId AND att.timeTableLabelId = $labelId AND att.degreeId =  (SELECT degreeId from class where classId = $classId) AND ceil(((grp.lectureAttended + IFNULL(grp.dutyLeaves,0))*100)/grp.lectureDelivered) between att.percentFrom and att.percentTo order by grp.studentId";

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
                              b.isActive,
                              b.className
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

    public function storePromotionRecord($classId, $attendance, $test, $marks) {
        global $sessionHandler;
        $userId = $sessionHandler->getSessionVariable('UserId');
        $query = "insert into class_promotion set classId = $classId, userId = $userId, promotionDateTime = NOW(), attendanceCompleted = $attendance, testsCompleted = $test, marksTransferred = $marks";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

	public function getShortAttendanceStudents($having) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "select distinct studentId from (select att.studentId, IF(ROUND(SUM(IF(att.isMemberOfClass=0,0, att.lectureDelivered))*100,2)<=0,0,ROUND(SUM(IF(att.isMemberOfClass=0,0, IF(att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended )) ) / SUM(IF(att.isMemberOfClass=0,0, att.lectureDelivered))*100,2)) AS percentage from student ss, ".ATTENDANCE_TABLE." att LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId = $instituteId) where ss.studentId = att.studentId and ss.classId = att.classId group by att.classId, att.studentId, att.subjectId $having) as t";
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

			$query = "SELECT cls.classId,cls.className FROM class cls,study_period sp

					  WHERE
						 cls.sessionId='".$sessionId."' AND
						 (cls.isActive =1 OR cls.isActive =2)  AND cls.studyPeriodId = sp.studyPeriodId

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

        $rollNo = strtolower($rollNo);
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

        $userName = strtolower($userName);

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
				FROM	".ATTENDANCE_TABLE."
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


    public function getStudentNewClassSubject_New($conditions='') {

        global $sessionHandler;

     $query = "SELECT
                     DISTINCT stc.subjectId
               FROM
                     subject_to_class stc
               WHERE
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

	 $query = "	SELECT
                       distinct(stc.subjectId)
				FROM
                       subject_to_class stc,
					   student s

				WHERE
                       s.classId = stc.classId
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
					AND       a.classId = $degreeId
					ORDER BY  a.degreeId,a.branchId,a.studyPeriodId";

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

		$query = "	UPDATE	".ATTENDANCE_TABLE."
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
		$query = "SELECT a.subjectId, a.subjectCode, b.categoryName FROM subject a, subject_category b WHERE a.subjectCategoryId != (SELECT subjectCategoryId from subject where subjectId = $subjectId) and a.subjectCategoryId = b.subjectCategoryId order by b.categoryName, a.subjectCode";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getClassMMSubjects($classId,$parentSubjectId) {
		$query = "SELECT a.subjectId, a.subjectCode, a.subjectName, b.categoryName, 1 as mapped, c.subjectTypeName FROM subject_category b, subject a, subject_type c WHERE a.subjectTypeId = c.subjectTypeId and a.subjectCategoryId != (SELECT subjectCategoryId from subject where subjectId = $parentSubjectId) and a.subjectCategoryId = b.subjectCategoryId and a.subjectId in (select subjectId from optional_subject_to_class where classId = $classId and parentOfSubjectId = $parentSubjectId) UNION SELECT a.subjectId, a.subjectCode, a.subjectName, b.categoryName, 0 as mapped, c.subjectTypeName FROM subject_category b, subject a, subject_type c WHERE a.subjectTypeId = c.subjectTypeId and a.subjectCategoryId != (SELECT subjectCategoryId from subject where subjectId = $parentSubjectId) and a.subjectCategoryId = b.subjectCategoryId and a.subjectId not in (select subjectId from optional_subject_to_class where classId = $classId and parentOfSubjectId = $parentSubjectId) order by mapped desc, subjectCode ";
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
                     IF(IFNULL(resourceUrl,'')='',-1,resourceUrl) AS resourceUrl,
                     IF(IFNULL(attachmentFile,'')='',-1,attachmentFile) AS attachmentFile,
                     employeeName, postedDate
             FROM
               course_resources,resource_category,subject,employee
             WHERE
                  course_resources.resourceTypeId=resource_category.resourceTypeId
                  AND
                  course_resources.subjectId=subject.subjectId
                  AND
                  course_resources.employeeId=employee.employeeId
                  AND
                  course_resources.instituteId='$instituteId'
                  AND
                  resource_category.instituteId='$instituteId'
                  AND
                  course_resources.sessionId='$sessionId'
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
        $conditions = " AND course_resources.subjectId = '$subjectId'";
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
                  course_resources.instituteId='$instituteId'
                  AND
                  resource_category.instituteId='$instituteId'
                  AND
                  course_resources.sessionId='$sessionId'
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
								DISTINCT g.groupId, g.groupShort, g.groupTypeId, gt.groupTypeName, g.parentGroupId
				FROM			`group` g, group_type gt
				WHERE			g.classId = $classId
				AND				g.groupTypeId = gt.groupTypeId
				AND				g.isOptional = 0
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
								DISTINCT s.studentId, s.rollNo, universityRollNo, concat(s.firstName,' ',s.lastName) as studentName,
                                group_concat(sg.groupId) as groupsAllocated
				FROM			student s left join student_groups sg on (s.studentId = sg.studentId AND s.classId = sg.classId )
				WHERE			s.classId = $classId
				group by		s.studentId
				ORDER BY		$sortBy
				";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

    public function getClassStudentGroupAllocationNew($classId, $endDate='', $sortBy) {

        $query = "SELECT
                         DISTINCT tt.studentId, tt.rollNo, tt.universityRollNo, tt.studentName, tt.groupsAllocated
                  FROM
                          (SELECT
                                 DISTINCT s.studentId, s.rollNo, universityRollNo,
                                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                        IFNULL(GROUP_CONCAT(sg.groupId ORDER BY sg.groupId),'') AS groupsAllocated
                          FROM
                                student s left join student_groups sg on (s.studentId = sg.studentId AND s.classId = sg.classId )
                          WHERE
                                s.studentStatus = 1
                                AND s.classId = '$classId'
                          GROUP BY
                                s.studentId
                          UNION
                          SELECT
                                DISTINCT s.studentId, s.rollNo, universityRollNo,
                                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                        IFNULL(GROUP_CONCAT(sg.groupId ORDER BY sg.groupId),'') AS groupsAllocated
                          FROM
                                student s LEFT JOIN student_groups sg ON (s.studentId = sg.studentId)
                          WHERE
                                s.studentStatus = 1
                                AND sg.classId = '$classId'
                          GROUP BY
                                s.studentId
                          UNION
                          SELECT
                                  DISTINCT s.studentId, s.rollNo, universityRollNo,
                                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                        '' AS groupsAllocated
                            FROM
                                 student s, class c
                            WHERE
                                 s.classId = c.classId AND
                                 CONCAT_WS(',',c.batchId,c.degreeId,c.branchId) IN
                                 (SELECT CONCAT_WS(',',cc.batchId,cc.degreeId,cc.branchId) FROM class cc WHERE cc.classId = '$classId') AND
                                 s.studentId NOT IN (SELECT DISTINCT studentId FROM student_groups ss WHERE ss.studentId = s.studentId AND ss.classId = '$classId')
                                 AND s.dateOfAdmission <= '$endDate' AND s.studentStatus = 1) AS tt
                  ORDER BY
                        $sortBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	///////////////////////////////////////////////////////////////////////////////////////
	//This function is used To find the Major/Minor Subjects with Child Subjects to a class
	//Author: Kavish Manjkhola
	//Created On: 01/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	///////////////////////////////////////////////////////////////////////////////////////


	public function hasParentSubjectDetails($degree) {
		$query = "
					SELECT
								DISTINCT s.subjectId ,s.subjectName,s.subjectCode,stc.hasParentCategory
					FROM 		`subject` s, `subject_to_class` stc
					WHERE		stc.classId = $degree
					AND			s.subjectId = stc.subjectId
					AND			stc.optional = 1
					AND	 		stc.offered = 1
					AND			stc.hasParentCategory = 1
					ORDER BY	s.subjectCode
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	///////////////////////////////////////////////////////////////////////////////////////
	//This function is used To find the Major/Minor Subjects without Child Subjects to a class
	//Author: Kavish Manjkhola
	//Created On: 01/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	///////////////////////////////////////////////////////////////////////////////////////


	public function noParentSubjectDetails($degree) {
		$query = "
					SELECT
								DISTINCT s.subjectId ,s.subjectName,s.subjectCode,stc.hasParentCategory
					FROM 		`subject` s, `subject_to_class` stc
					WHERE		stc.classId = $degree
					AND			s.subjectId = stc.subjectId
					AND			stc.optional = 1
					AND	 		stc.offered = 1
					AND			stc.hasParentCategory = 0
					ORDER BY	s.subjectCode
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	/////////////////////////////////////////////////////////////
	//This function is used to fetch Child Optional Subject List
	//Author: Kavish Manjkhola
	//Created On: 07/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	/////////////////////////////////////////////////////////////

	public function getChildClassesDetails($degree, $parentSubjectIds) {
		$query = "
					SELECT
								DISTINCT ostc.subjectId as childSubjectId, s.subjectCode as childSubjectCode, ostc.parentOfSubjectId, sub2.subjectCode as parentSubjectCode
					FROM 		`optional_subject_to_class` ostc, `subject` s, `subject` sub2
					WHERE		ostc.classId = $degree
					AND 		ostc.parentOfSubjectId IN ($parentSubjectIds)
					AND 		ostc.subjectId = s.subjectId
					AND  		ostc.parentOfSubjectId = sub2.subjectId
					ORDER BY	s.subjectCode
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	////////////////////////////////////////////////////////////////////
	//This function is used to fetch group details of a particular class
	//Author: Kavish Manjkhola
	//Created On: 07/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	/////////////////////////////////////////////////////////////////////

	public function getClassGroupDetails($degree, $childSubjectIds) {
		$query = "
					SELECT
								DISTINCT g.groupId, g.groupName, g.groupShort, (g.optionalSubjectId) as childsubjectId
					FROM		`group` g
					WHERE		g.classId = $degree
					AND			optionalSubjectId IN($childSubjectIds)
					AND			optionalSubjectId IS NOT NULL
					ORDER BY 	g.groupName
				 ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	////////////////////////////////////////////////////////////////
	//This function is used to fetch students of a particular class
	//Author: Kavish Manjkhola
	//Created On: 07/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	////////////////////////////////////////////////////////////////

	public function getStudentDetails($degree, $sortBy) {
		$query = "  SELECT
                          DISTINCT s.studentId, s.rollNo, s.universityRollNo, concat(s.firstName,' ',s.lastName) as studentName
                    FROM
                          student s
                    WHERE
                          classId = '$degree' AND
						  s.studentStatus = '1'

                    UNION
					SELECT
						  DISTINCT sg.studentId, s.rollNo, s.universityRollNo, concat(s.firstName,' ',s.lastName) as studentName
					FROM
                          student s, student_groups sg
					WHERE
                          s.studentId = sg.studentId AND
                          sg.classId = '$degree' AND
						  s.studentStatus = '1'

					ORDER BY
                    	$sortBy";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	/////////////////////////////////////////////////////////////////////////////////
	//This function is used to fetch student count corresponding to particular groups
	//Author: Kavish Manjkhola
	//Created On: 07/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	////////////////////////////////////////////////////////////////////////////////

	public function getGroupCount($degree) {
		$query = "
					SELECT
								DISTINCT g.groupId, g.groupName, (SELECT count(sos.studentId)
														 FROM	student_optional_subject sos
									   					 WHERE   sos.groupId = g.groupId) as studentCount
					FROM 		`group` g
					WHERE		g.classId = $degree
					AND			g.isOptional = 1
				 ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	/////////////////////////////////////////////////////////////////////
	//This function is used to fetch groups alloted to particular student
	//Author: Kavish Manjkhola
	//Created On: 07/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	/////////////////////////////////////////////////////////////////////

	public function studentGroupDetails($degree, $studentIds) {
		$query = "
					SELECT
								DISTINCT sos.studentId, sos.groupId, sos.subjectId, sos.parentOfSubjectId
					FROM		student_optional_subject sos, student s
					WHERE		sos.studentId IN ($studentIds)
					AND 		sos.classId = $degree
					AND			sos.studentId = s.studentId
				 ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	///////////////////////////////////////////////////////////////////////
	//This function is used to fetch details of a particular PARENT subject
	//Author: Kavish Manjkhola
	//Created On: 13/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	///////////////////////////////////////////////////////////////////////

	public function getParentSubjectDetails($classId,$parentSubjectId) {
		$query = "
					SELECT
								DISTINCT sos.subjectId, sos.studentId, sos.classId, sos.groupId, sos.parentOfsubjectId
					FROM 		student_optional_subject sos
					WHERE		sos.classId = $classId
					AND			sos.parentOfSubjectId = $parentSubjectId
				 ";
		/*echo $query;
		die;*/
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	///////////////////////////////////////////////////////////////////////
	//This function is used to fetch details of a particular PARENT subject
	//Author: Kavish Manjkhola
	//Created On: 16/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	///////////////////////////////////////////////////////////////////////

	public function subjectDetailsArray($classId,$subjectId) {
		$query = "
					SELECT
								DISTINCT sos.subjectId, sos.studentId, sos.classId, sos.groupId, sos.parentOfsubjectId
					FROM 		student_optional_subject sos
					WHERE		classId = $classId
					AND			subjectId = $subjectId
				 ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	///////////////////////////////////////////////////
	//This function is used to fetch attendance details
	//Author: Kavish Manjkhola
	//Created On: 13/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	//////////////////////////////////////////////////////

	public function getAttendanceDetails($classId, $groupIds, $studentIds, $subjectIds) {
		$query = "
					SELECT
								at.studentId, count(*) as cnt
					FROM		".ATTENDANCE_TABLE." at
					WHERE		classId = $classId
					AND			groupId IN ($groupIds)
					AND			studentId IN ($studentIds)
					AND			subjectId IN ($subjectIds)
					GROUP BY	at.studentId
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	///////////////////////////////////////////////////////////////////
	//This function is used to fetch group details of all the subjects
	//Author: Kavish Manjkhola
	//Created On: 13/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	//////////////////////////////////////////////////////////////////

	public function getGroupAttendanceRecord($classId, $subjectIds) {
		$query = "
					SELECT
								at.studentId, at.groupId, count(*) as cnt
					FROM		".ATTENDANCE_TABLE." at
					WHERE		classId = $classId
					AND			subjectId IN ($subjectIds)
					GROUP BY	at.studentId, at.groupId
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	///////////////////////////////////////////////////
	//This function is used to fetch attendance details
	//Author: Kavish Manjkhola
	//Created On: 13/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	//////////////////////////////////////////////////////

	public function getStudentGroupAttendanceDetails($classId, $groupIds, $studentIds) {
		$query = "
					SELECT
								at.studentId, count(*) as cnt
					FROM		".ATTENDANCE_TABLE." at
					WHERE		classId = $classId
					AND			groupId IN ($groupIds)
					AND			studentId IN ($studentIds)
					GROUP BY	at.studentId
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	///////////////////////////////////////////////////////////////////////
	//This function is used to fetch details of a particular CHILD subject
	//Author: Kavish Manjkhola
	//Created On: 13/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	///////////////////////////////////////////////////////////////////////

	public function getChildSubjectDetails($classId,$childSubjectId) {
		$query = "

					SELECT
								DISTINCT sos.subjectId, sos.studentId, sos.classId, sos.groupId, sos.parentOfsubjectId
					FROM		student_optional_subject sos
					WHERE		classId = $classId
					AND			subjectId = $childSubjectId
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	///////////////////////////////////////////////////////////////////////////
	//This function is used to fetch details of a particular GROUP of a subject
	//Author: Kavish Manjkhola
	//Created On: 13/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	///////////////////////////////////////////////////////////////////////////

	public function getStudentGroupDetails($classId,$subjectId,$groupId) {
		$query = "
					SELECT
								DISTINCT sos.subjectId, sos.studentId, sos.classId, sos.groupId, sos.parentOfsubjectId
					FROM		student_optional_subject sos
					WHERE		classId = $classId
					AND			subjectId= $subjectId
					AND			groupId = $groupId
				";
	}


	///////////////////////////////////////////////////////////////////////////
	//This function is used to fetch details of a particular GROUP of a subject
	//Author: Kavish Manjkhola
	//Created On: 13/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	///////////////////////////////////////////////////////////////////////////

	public function getGroupRecordsArray($classId,$subjectId,$groupId) {
		$query = "
					SELECT
								DISTINCT sos.subjectId, sos.studentId, sos.classId, sos.groupId, sos.parentOfsubjectId
					FROM		student_optional_subject sos
					WHERE		classId = $classId
					AND			subjectId= $subjectId
					AND			groupId = $groupId
				";
	}


	////////////////////////////////////////////////////////////////
	//This function is used to fetch details of a particular STUDENT
	//Author: Kavish Manjkhola
	//Created On: 13/02/2011
	//Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd
	////////////////////////////////////////////////////////////////

	public function getStudentRecords($classId,$subjectId,$groupId,$studentId) {
		$query = "
					SELECT
								DISTINCT sos.subjectId, sos.studentId, sos.classId, sos.groupId, sos.parentOfsubjectId
					FROM		student_optional_subject sos
					WHERE		classId = $classId
					AND			subjectId= $subjectId
					AND			groupId= $groupId
					AND			studentId = $studentId
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//-------------------------------------------------------
    //  THIS FUNCTION IS to add current allocation for a class
    // Author :Kavish Manjkhola
    // Created on : (15-Sep-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
	public function addStudentGroup($insertStr) {
		$query = "
					INSERT INTO
								student_optional_subject (subjectId, studentId, classId, groupId, parentOfSubjectId)
					VALUES
								$insertStr";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	//-------------------------------------------------------
    //  THIS FUNCTION IS to remove current allocation for a class
    //
    // Author :Ajinder Singh
    // Created on : (15-Sep-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
	public function removeStudentGroupAllocationRecords($degree) {
		$query = "
					DELETE FROM
								student_optional_subject
					WHERE
								classId = $degree";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
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

		$query = "SELECT
                        DISTINCT b.studentId, b.rollNo, CONCAT(IFNULL(b.firstName,''),' ',IFNULL(b.lastName,'')) AS studentName, a.groupId
                  FROM
                        ".ATTENDANCE_TABLE." a, student b
                  WHERE
                        a.classId = $classId AND a.studentId = b.studentId
		          UNION
		          SELECT
                        DISTINCT b.studentId, b.rollNo, CONCAT(IFNULL(b.firstName,''),' ',IFNULL(b.lastName,'')) AS studentName, t.groupId
                  FROM
                        ".TEST_TABLE." t, ".TEST_MARKS_TABLE." tm, student b
                  WHERE
                        t.testId = tm.testId AND t.classId = $classId and tm.studentId = b.studentId ";

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

    public function countClassStudentGroupAllocationNew($degree) {

        $query = "SELECT
                       DISTINCT tt.groupId, tt.groupStudentCount
                  FROM
                      (SELECT
                             DISTINCT
                             a.groupId, count(a.studentId) as groupStudentCount
                       FROM
                             student_groups a, student b
                       WHERE
                            b.studentStatus = 1 AND
                            a.classId = $degree and a.classId = b.classId and a.studentId = b.studentId
                      GROUP BY
                             a.groupId
                      UNION
                      SELECT
                            DISTINCT a.groupId, COUNT(a.studentId) as groupStudentCount
                      FROM
                            student b LEFT JOIN student_groups a ON b.studentId = a.studentId
                      WHERE
                            b.studentStatus = 1 AND
                            a.classId = $degree
                      GROUP BY
                            a.groupId) AS tt";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getStudentGroupAllocationList($degree,$endDate='',$sortBy='') {

          if($sortBy=='') {
            $sortBy = "s.rollNo";
          }

          if($sortBy=='studentName') {
            $sortBy = " CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) ";
          }

          $query = "SELECT
                          DISTINCT s.studentId, s.rollNo, universityRollNo,
                                CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                '' AS groupsAllocated
                    FROM
                         student s, class c
                    WHERE
                         s.studentStatus = 1 AND
                         s.classId = c.classId AND
                         CONCAT_WS(',',c.batchId,c.degreeId,c.branchId) IN
                         (SELECT CONCAT_WS(',',cc.batchId,cc.degreeId,cc.branchId) FROM class cc WHERE cc.classId = '$degree')
                         AND s.dateOfAdmission <= '$endDate'
                    ORDER BY
                        $sortBy ";

           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getLabelDate($labelId='') {

        $query = "SELECT
                        timeTableLabelId, endDate
                 FROM
                        time_table_labels
                 WHERE
                        timeTableLabelId = '$labelId' ";

           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getStudentGroupAllocationCountList($degree) {

          $query = "SELECT
                          DISTINCT s.studentId, s.rollNo, universityRollNo,
                                CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                '' AS groupsAllocated
                    FROM
                         student s, class c
                    WHERE
                         s.classId = c.classId AND
                         CONCAT_WS(',',c.batchId,c.degreeId,c.branchId) IN
                         (SELECT CONCAT_WS(',',cc.batchId,cc.degreeId,cc.branchId) FROM class cc WHERE cc.classId = '$degree')";
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

		$query = "SELECT
                        DISTINCT s.studentId, s.rollNo, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName
                  FROM
                        student s LEFT JOIN student_groups sg ON sg.studentId = s.studentId
                  WHERE
                        s.classId = $classId
                  UNION
                  SELECT
                        DISTINCT s.studentId, s.rollNo, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName
                  FROM
                        student s LEFT JOIN student_groups sg ON sg.studentId = s.studentId
                  WHERE
                        sg.classId = $classId ";

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
// Author :Ajinder Singh
// Created on : (02.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------
	public function getStudentIdDetails($rollNo) {

		 $query = "select
					a.studentId,
					a.classId,
					b.sessionId,
					b.instituteId,
					CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) as studentName,
					IFNULL(a.fatherName,'') AS fatherName
				 from
					student a, class b
				 where
					a.rollNo = '$rollNo'
					and a.classId = b.classId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	public function getStudentMarksGrades($studentId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
         $query = "SELECT
                          DISTINCT
                                '1' AS examType, ttm.studentId, ttm.subjectId, ttm.classId,
                                (select cls.className from class cls where ttm.classId = cls.classId) as className,
                                (select sub.subjectCode from subject sub where ttm.subjectId = sub.subjectId) as subjectCode ,
                                if((select COUNT(*) FROM ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where ttm2.isActive=1 AND ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 1 and ttm2.subjectId = ttm.subjectId ) = 0, '-', (select if(ttm2.marksScoredStatus = 'Marks', ttm2.marksScored, ttm2.marksScoredStatus) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where ttm2.isActive=1 AND ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 1 and ttm2.subjectId = ttm.subjectId)) as preCompre ,if((select COUNT(*) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where  ttm2.isActive=1 AND ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 2 and ttm2.subjectId = ttm.subjectId ) = 0, '-', (select if(ttm2.marksScoredStatus = 'Marks', ttm2.marksScored, ttm2.marksScoredStatus) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where  ttm2.isActive=1 AND ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 2 and ttm2.subjectId = ttm.subjectId)) as compre,if((select COUNT(*) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where  ttm2.isActive=1 AND ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 3 and ttm2.subjectId = ttm.subjectId ) = 0, '-', (select if(ttm2.marksScoredStatus = 'Marks', ttm2.marksScored, ttm2.marksScoredStatus) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where  ttm2.isActive=1 AND ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 3 and ttm2.subjectId = ttm.subjectId)) as attendance, ttm.holdResult, IF (ttm.gradeId IS NULL, 'I',(SELECT e.gradeLabel FROM grades e WHERE ttm.isActive=1 AND e.gradeId = ttm.gradeId AND e.instituteId = $instituteId)) AS grades, SUM(ttm.marksScored) AS totalMarks
                   FROM
                         ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm
                   WHERE
                         ttm.studentId = $studentId AND
                         ttm.isActive=1
                   GROUP BY ttm.studentId, ttm.subjectId, ttm.classId
                   UNION
                   SELECT
                          DISTINCT
                                '2' AS examType, ttm.studentId, ttm.subjectId, ttm.classId,
                                (select cls.className from class cls where ttm.classId = cls.classId) as className,
                                (select sub.subjectCode from subject sub where ttm.subjectId = sub.subjectId) as subjectCode ,
                                if((select COUNT(*) FROM ".TOTAL_UPDATED_MARKS_TABLE." ttm2 where ttm2.isActive=1 AND ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 1 and ttm2.subjectId = ttm.subjectId ) = 0, '-',
                                   (select if(ttm2.marksScoredStatus = 'Marks', ttm2.marksScored, ttm2.marksScoredStatus) from ".TOTAL_UPDATED_MARKS_TABLE." ttm2 where ttm2.isActive=1 AND ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 1 and ttm2.subjectId = ttm.subjectId)) as preCompre,
                                    if((select COUNT(*) from ".TOTAL_UPDATED_MARKS_TABLE." ttm2 where  ttm2.isActive=1 AND ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 2 and ttm2.subjectId = ttm.subjectId ) = 0, '-', (select if(ttm2.marksScoredStatus = 'Marks', ttm2.marksScored, ttm2.marksScoredStatus) from ".TOTAL_UPDATED_MARKS_TABLE." ttm2 where  ttm2.isActive=1 AND ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 2 and ttm2.subjectId = ttm.subjectId)) as compre,
                                    if((select COUNT(*) from ".TOTAL_UPDATED_MARKS_TABLE." ttm2 where ttm2.isActive=1 AND ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 3 and ttm2.subjectId = ttm.subjectId ) = 0, '-', (select if(ttm2.marksScoredStatus = 'Marks', ttm2.marksScored, ttm2.marksScoredStatus) from ".TOTAL_UPDATED_MARKS_TABLE." ttm2 where  ttm2.isActive=1 AND ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 3 and ttm2.subjectId = ttm.subjectId)) as attendance, ttm.holdResult,
                                    IF (ttm.gradeId IS NULL, 'I',(SELECT e.gradeLabel FROM grades e WHERE  ttm.isActive=1 AND e.gradeId = ttm.gradeId AND e.instituteId = $instituteId)) AS grades,  SUM(ttm.marksScored) AS totalMarks
                   FROM
                         ".TOTAL_UPDATED_MARKS_TABLE." ttm
                   WHERE
                         ttm.studentId = $studentId AND
                         ttm.isActive=1
                   GROUP BY
                        ttm.studentId, ttm.subjectId, ttm.classId
                   ORDER BY
                        className, subjectCode";
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getStudentMarksGradesOld($studentId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
		 $query = "
					SELECT distinct ttm.studentId, ttm.subjectId, ttm.classId, (select cls.className from class cls where ttm.classId = cls.classId) as className, (select sub.subjectCode from subject sub where ttm.subjectId = sub.subjectId) as subjectCode ,if((select COUNT(*) FROM ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 1 and ttm2.subjectId = ttm.subjectId ) = 0, '-', (select if(ttm2.marksScoredStatus = 'Marks', ttm2.marksScored, ttm2.marksScoredStatus) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 1 and ttm2.subjectId = ttm.subjectId)) as preCompre ,if((select COUNT(*) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 2 and ttm2.subjectId = ttm.subjectId ) = 0, '-', (select if(ttm2.marksScoredStatus = 'Marks', ttm2.marksScored, ttm2.marksScoredStatus) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 2 and ttm2.subjectId = ttm.subjectId)) as compre,if((select COUNT(*) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 3 and ttm2.subjectId = ttm.subjectId ) = 0, '-', (select if(ttm2.marksScoredStatus = 'Marks', ttm2.marksScored, ttm2.marksScoredStatus) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 3 and ttm2.subjectId = ttm.subjectId)) as attendance, ttm.holdResult, IF (ttm.gradeId IS NULL, 'I',(SELECT e.gradeLabel FROM grades e WHERE e.gradeId = ttm.gradeId AND e.instituteId = $instituteId)) AS grades from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm where ttm.studentId = $studentId group by ttm.studentId, ttm.subjectId, ttm.classId ORDER BY	className, subjectCode";
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentIdClass($rollNo) {
		$query = "SELECT distinct a.studentId, b.classId FROM student a, ".TOTAL_TRANSFERRED_MARKS_TABLE." b where a.studentId = b.studentId and a.rollNo = '$rollNo'";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	public function unholdResult($studentId) {
		$query = "UPDATE ".TOTAL_TRANSFERRED_MARKS_TABLE." set holdResult = 0 where studentId = $studentId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function makeOldResultInActive($classId, $studentId, $subjectId) {
		$query = "UPDATE ".TOTAL_TRANSFERRED_MARKS_TABLE." SET isActive = 0 where classId = $classId AND studentId = $studentId and subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
	public function makePrevResultInActive($classId, $studentId, $subjectId) {
		$query = "UPDATE ".TOTAL_UPDATED_MARKS_TABLE." SET isActive = 0 where classId = $classId AND studentId = $studentId and subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function makeOldResultActive($classId, $studentId, $subjectId) {
		$query = "UPDATE ".TOTAL_TRANSFERRED_MARKS_TABLE." SET isActive = 1 where classId = $classId AND studentId = $studentId and subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function getReappearMarks($studentId, $classId, $subjectId) {
		$query = "SELECT
                        a.marksUpdationId, a.maxMarks, a.marksScored, a.conductingAuthority,
                        a.updateDateTime, a.isActive, IFNULL(b.gradeLabel,'I') AS gradeLabel
                  FROM
                        ".TOTAL_UPDATED_MARKS_TABLE." a LEFT JOIN grades b ON a.gradeId = b.gradeId
                  where
                        a.classId = $classId and a.studentId = $studentId and a.subjectId = $subjectId
                  ORDER BY
                        a.marksUpdationId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function makeMarksActive($updateDateTime, $studentId,$classId,$subjectId) {
		$query = "UPDATE ".TOTAL_UPDATED_MARKS_TABLE." set isActive = 1 where updateDateTime = '$updateDateTime' and studentId = $studentId and classId = $classId and subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function insertRecordInTransaction($str) {
		$query = "INSERT INTO ".TOTAL_UPDATED_MARKS_TABLE." SET $str";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


	public function checkTotalUpdatedMarks($studentId,$classId,$subjectId) {
		$query = "SELECT COUNT(*) AS cnt FROM ".TOTAL_UPDATED_MARKS_TABLE." WHERE studentId = $studentId AND classId = $classId AND subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getRegularMarks($studentId, $classId, $subjectId) {

        $query = "SELECT
                        a.maxMarks, a.marksScored, a.conductingAuthority, a.isActive, IFNULL(b.gradeLabel,'I') AS gradeLabel
                  FROM
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." a
                        LEFT JOIN grades b ON a.gradeId = b.gradeId
                  WHERE
                        a.classId = $classId and a.studentId = $studentId and a.subjectId = $subjectId ";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getReappearMarksCount($studentId,$classId,$subjectId) {
		$query = "SELECT updateDateTime, isActive, count(marksScored) as cnt from ".TOTAL_UPDATED_MARKS_TABLE." where classId = $classId and studentId = $studentId and subjectId = $subjectId GROUP BY updateDateTime, isActive";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function checkTestTransferredMarks($studentId,$classId,$subjectId) {
		$query = "SELECT COUNT(*) AS cnt FROM ".TEST_TRANSFERRED_MARKS_TABLE." WHERE studentId = $studentId AND classId = $classId AND subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function checkTestUpdatedMarks($studentId,$classId,$subjectId) {
		$query = "SELECT COUNT(*) AS cnt FROM ".TOTAL_UPDATED_MARKS_TABLE." WHERE studentId = $studentId AND classId = $classId AND subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentSubjectMarks($studentId='',$classId='',$subjectId='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
				SELECT distinct ttm.studentId, ttm.subjectId, ttm.classId, (select SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-3) from class cls where ttm.classId = cls.classId) as className, (select sub.subjectCode from subject sub where ttm.subjectId = sub.subjectId) as subjectCode ,if((select COUNT(*) FROM ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 1 and ttm2.subjectId = ttm.subjectId ) = 0, '-', (select if(ttm2.marksScoredStatus = 'Marks', ttm2.marksScored, ttm2.marksScoredStatus) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 1 and ttm2.subjectId = ttm.subjectId)) as preCompre ,if((select COUNT(*) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 2 and ttm2.subjectId = ttm.subjectId ) = 0, '-', (select if(ttm2.marksScoredStatus = 'Marks', ttm2.marksScored, ttm2.marksScoredStatus) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 2 and ttm2.subjectId = ttm.subjectId)) as compre,if((select COUNT(*) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 3 and ttm2.subjectId = ttm.subjectId ) = 0, '-', (select if(ttm2.marksScoredStatus = 'Marks', ttm2.marksScored, ttm2.marksScoredStatus) from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2 where ttm.studentId = ttm2.studentId and ttm.classId = ttm2.classId and ttm2.conductingAuthority = 3 and ttm2.subjectId = ttm.subjectId)) as attendance, ttm.holdResult, IF (ttm.gradeId IS NULL, 'I',(SELECT e.gradeLabel FROM grades e WHERE e.gradeId = ttm.gradeId AND e.instituteId = $instituteId)) AS grades from ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm where ttm.studentId = $studentId AND ttm.classId = $classId and ttm.subjectId = $subjectId group by ttm.studentId, ttm.subjectId, ttm.classId ORDER BY	className, subjectCode

		";
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countReExamMarks($classId, $studentId, $subjectId) {
		$query = "SELECT count(marksScored) as cnt from ".TOTAL_UPDATED_MARKS_TABLE." where classId = $classId AND studentId = $studentId and subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

  public function getStudentActiveMarks($classId, $studentId, $subjectId) {
		$query = "
				SELECT
						'Regular' as type, a.conductingAuthority, a.marksScored, a.maxMarks, IFNULL(b.gradeLabel,'I')  AS gradeLabel
				FROM
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." a LEFT JOIN grades b ON a.gradeId = b.gradeId
				WHERE
						a.classId = $classId
				        AND	a.subjectId = $subjectId
				        AND	a.studentId = $studentId
				        AND	a.isActive = 1
				UNION
				SELECT
						'Re-Exam' as type, a.conductingAuthority, a.marksScored, a.maxMarks, IFNULL(b.gradeLabel,'I')   AS gradeLabel
				FROM	".TOTAL_UPDATED_MARKS_TABLE." a  LEFT JOIN grades b ON a.gradeId = b.gradeId
				WHERE
                        a.classId = $classId
				        AND	a.subjectId = $subjectId
				        AND	a.studentId = $studentId
				        AND	a.isActive = 1";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


	public function getStudentSubjectAttendance($studentId,$subjectId,$classId) {
	   global $sessionHandler;
	   $instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
					SELECT
								a.attendanceId, a.employeeId, c.employeeName, if(a.attendanceType = 1,'Bulk','Daily') as attendanceType, a.attendanceCodeId, b.attendanceCode, a.periodId, a.fromDate as attendanceDate, a.toDate, a.lectureDelivered, if(a.attendanceType = 1, a.lectureAttended, (b.attendanceCodePercentage/100)) as lectureAttended,
								if(a.attendanceType = 1,'Bulk',ifnull((select concat(dl.dutyLeaveId,'#',dl.rejected) from  ".DUTY_LEAVE_TABLE."  dl where dl.dutyDate = a.fromDate and dl.studentId = a.studentId and dl.periodId = a.periodId LIMIT 0,1),'Daily')) as dutyLeave
					FROM
								employee c, ".ATTENDANCE_TABLE." a left join attendance_code b
					ON			(a.attendanceCodeId = b.attendanceCodeId AND b.instituteId = $instituteId)
					WHERE		a.studentId = $studentId
					AND			a.subjectId = $subjectId
					AND			a.isMemberOfClass = 1
					AND			a.classId = $classId
					AND			a.employeeId = c.employeeId
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentSubjectTests($studentId,$subjectId,$classId) {
		$query = "
					SELECT
								a.testId,concat(b.testTypeName,'-',a.testIndex) as testTypeName, a.testTypeCategoryId, a.testDate,a.employeeId,c.employeeName, a.maxMarks,d.marksScored
					FROM
								test_type_category b, ".TEST_MARKS_TABLE." d, ".TEST_TABLE." a left join employee c on (a.employeeId = c.employeeId)
					WHERE		a.testTypeCategoryId = b.testTypeCategoryId
					AND			a.testId  = d.testId
					AND			d.studentId = $studentId
					AND			a.subjectId = $subjectId
					AND			a.classId = $classId
					AND			b.examType = 'PC'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR updating student daily attendance
//
// Author :Ajinder Singh
// Created on : (05-apr-2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
	public function updateStudentDailyAttendance($attendanceId,$attendanceCodeId) {
		$query = "
				UPDATE
								".ATTENDANCE_TABLE."
				SET
								attendanceCodeId = $attendanceCodeId
				WHERE			attendanceId = $attendanceId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR updating student attendance
//
// Author :Ajinder Singh
// Created on : (05-apr-2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
	public function updateStudentAttendance($attendanceId,$lectureAttended) {
		$query = "
				UPDATE
								".ATTENDANCE_TABLE."
				SET
								lectureAttended = $lectureAttended
				WHERE			attendanceId = $attendanceId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	//-------------------------------------------------------
	//  THIS FUNCTION IS USED FOR fetching marks scored for attendance for a class
	//
	// Author :Ajinder Singh
	// Created on : (14-09-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------
	public function getAttendanceMarks($percent, $classId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "	SELECT		marksScored
					FROM		attendance_marks
					WHERE		attendancePercent = $percent
					AND		instituteId = $instituteId
					AND			timeTableLabelId = (select timeTableLabelId from time_table_classes where classId = $classId )
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//-------------------------------------------------------
	//  THIS FUNCTION IS USED FOR updating test marks
	//
	// Author :Ajinder Singh
	// Created on : (14-09-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------
	public function updateMarksInTransaction($studentId,$subjectId,$classId,$testId,$markScored) {
		$query = "update ".TEST_MARKS_TABLE." set marksScored = $markScored, isPresent = 1, isMemberOfClass = 1 where studentId = $studentId and subjectId = $subjectId and testId = $testId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	//-------------------------------------------------------
	//  THIS FUNCTION IS USED FOR SUBJECTWISE TEST TYPES WITH EVALUATION CRITERIA
	//
	// Author :Ajinder Singh
	// Created on : (16.10.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------
	 public function getMarksDistributionOneSubjectCategory($degree, $subjectId,$studentTestTypeCategoryList) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
			$query = "SELECT
							b.testTypeId,
							b.evaluationCriteriaId,
							b.cnt,
							ROUND(b.weightagePercentage,1) AS weightagePercentage,
							b.weightageAmount,
							b.testTypeName
					FROM	`subject` a, test_type b
					WHERE	a.subjectId = $subjectId AND
                            b.classId = $degree AND
							a.subjectId = b.subjectId AND
							a.subjectTypeId = b.subjectTypeId AND
							b.conductingAuthority = 1 AND
							b.testTypeCategoryId IN ($studentTestTypeCategoryList) AND
							b.timeTableLabelId = (select timeTableLabelId from time_table_classes where classId = $degree )
							AND b.instituteId = $instituteId
					UNION
					SELECT
							b.testTypeId,
							b.evaluationCriteriaId,
							b.cnt,
							ROUND(b.weightagePercentage,1) AS weightagePercentage,
							b.weightageAmount,
							b.testTypeName
					FROM	`subject` a, test_type b, class c
					WHERE	c.classId = $degree AND
                            b.classId = c.classId AND
							a.subjectId = $subjectId AND
							b.conductingAuthority = 1 AND
							a.subjectTypeId = b.subjectTypeId AND
							b.testTypeCategoryId IN ($studentTestTypeCategoryList) AND
							b.instituteId = $instituteId AND
							b.timeTableLabelId = (select timeTableLabelId from time_table_classes where classId = $degree ) AND
							a.subjectId NOT in (SELECT IF(subjectId IS NULL,0, subjectId) FROM test_type WHERE instituteId = $instituteId) AND
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

	public function getSubjectTestTypes($classId, $subjectId, $studentTestTypeCategoryList) {
			$query = "
					SELECT
									testTypeId,
									evaluationCriteriaId,
									cnt,
									ROUND(weightagePercentage,1) AS weightagePercentage,
									weightageAmount,
									testTypeName
					FROM		test_type
					WHERE		classId = $classId
					AND		subjectId = $subjectId
					AND		testTypeCategoryId IN ($studentTestTypeCategoryList)
					";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getSubjectTestTypeCategoryTestMarks($classId, $subjectId, $testTypeId, $conditions = '') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
				SELECT
							a.studentId,
							c.maxMarks,
							c.marksScored,
							ROUND((c.marksScored/c.maxMarks)*100) AS per
				FROM		student a, ".TEST_TABLE." b, ".TEST_MARKS_TABLE." c, test_type d
				WHERE		b.classId = $classId
				AND			a.studentId = c.studentId
				AND			b.testId = c.testId
				AND			b.subjectId = $subjectId
				AND			b.testTypeCategoryId = d.testTypeCategoryId
				AND			d.testTypeId = $testTypeId
				AND			d.instituteId = $instituteId
							$conditions
				ORDER BY	a.studentId,per DESC";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentTestCompreMarks($studentId,$subjectId,$classId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

		$query = "SELECT
                        a.maxMarks, a.marksScored
				  FROM
                    	".TOTAL_TRANSFERRED_MARKS_TABLE." a
				  WHERE
                        a.studentId = $studentId
					    AND	a.subjectId = $subjectId
					    AND	a.classId = $classId
					    AND a.conductingAuthority IN (2)";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR updating student attendance grade
//
// Author :Ajinder Singh
// Created on : (05-apr-2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
	public function updateStudentAttendanceGrade($studentId,$classId,$subjectId,$conductingAuthority,$gradeId,$marksScoredStatus = 'Marks',$gradeSetId, $gradingLabelId) {
		$query = "
				UPDATE
								".TOTAL_TRANSFERRED_MARKS_TABLE."
				SET				gradeId = $gradeId,
								gradeSetId = $gradeSetId,
								gradingLabelId = $gradingLabelId
				WHERE			studentId = $studentId
				AND				classId = $classId
				AND				subjectId = $subjectId
				AND				conductingAuthority = $conductingAuthority";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	//-------------------------------------------------------
	//  THIS FUNCTION IS USED FOR fetching max marks for attendance for a class
	//
	// Author :Ajinder Singh
	// Created on : (14-09-2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------
	public function getAttendanceMaxMarks($classId, $subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "	SELECT	weightageAmount as maxMarksScored
					FROM		test_type
					WHERE classId = $classId
					AND	subjectId = $subjectId
					AND evaluationCriteriaId IN (" . PERCENTAGES . ", " . SLABS . ")";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getGradeSet($subjectId,$classId) {
		$query = "SELECT DISTINCT gradeSetId as gradeSetId FROM ".TOTAL_TRANSFERRED_MARKS_TABLE." WHERE gradeId IS NOT NULL and subjectId = '$subjectId' AND classId = '$classId' and gradeSetId is not null";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentGrade($studentId,$subjectId,$classId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "	SELECT		a.gradeId, ifnull(b.gradeLabel, 'I')
					FROM		".TOTAL_TRANSFERRED_MARKS_TABLE." a left join grades b on (a.gradeId = b.gradeId AND b.instituteId = $instituteId)
					WHERE		a.studentId = $studentId
					AND			a.subjectId = $subjectId
					AND			a.classId = $classId
					AND			conductingAuthority = 1
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentOldMarks($studentId,$classId,$subjectId) {
		$query = "
					SELECT
								marksScored, gradeId, conductingAuthority
					FROM		".TOTAL_TRANSFERRED_MARKS_TABLE."
					WHERE		studentId = $studentId
					AND			classId = $classId
					AND			subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getEvaluationCriteria($classId,$subjectId) {
		$query = "SELECT evaluationCriteriaId from test_type where classId = $classId and subjectId = $subjectId and evaluationCriteriaId IN (" . PERCENTAGES.", ".SLABS.")";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentAttendanceMarksPercent($percent, $classId,$subjectId) {
		$query = "
				SELECT
									amp.marksScored
				FROM				attendance_marks_percent amp,
									subject_to_class stc
				WHERE				amp.attendanceSetId = stc.attendanceSetId
				AND				stc.classId = $classId
				AND				stc.subjectId = $subjectId
				AND				$percent BETWEEN amp.percentFrom AND amp.percentTo
				UNION
				SELECT
									amp.marksScored
				FROM				attendance_marks_percent amp,
									optional_subject_to_class stc
				WHERE				amp.attendanceSetId = stc.attendanceSetId
				AND				stc.classId = $classId
				AND				stc.subjectId = $subjectId
				AND				$percent BETWEEN amp.percentFrom AND amp.percentTo
		";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentAttendanceMarksSlabs($totalLecturesDelivered, $totalLecturesAttended, $classId, $subjectId) {
		$query = "
				SELECT
									amp.marksScored
				FROM				attendance_marks_slabs amp,
									subject_to_class stc
				WHERE				amp.attendanceSetId = stc.attendanceSetId
				AND				stc.classId = $classId
				AND				stc.subjectId = $subjectId
				AND				amp.lectureDelivered = $totalLecturesDelivered
				AND				amp.lectureAttended = $totalLecturesAttended
				UNION
				SELECT
									amp.marksScored
				FROM				attendance_marks_slabs amp,
									optional_subject_to_class stc
				WHERE				amp.attendanceSetId = stc.attendanceSetId
				AND				stc.classId = $classId
				AND				stc.subjectId = $subjectId
				AND				amp.lectureDelivered = $totalLecturesDelivered
				AND				amp.lectureAttended = $totalLecturesAttended
		";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function addMarksUpdationLog($studentId,$classId,$subjectId, $oldMarks, $conductingAuthority,$oldGradeId,$reason) {
        global $sessionHandler;
		  $instituteId = $sessionHandler->getSessionVariable('InstituteId');


        $query = "
				INSERT INTO
								marks_updation_log (userId, logDateTime, studentId, classId, subjectId, conductingAuthority, oldMarks, oldGradeId, reason, instituteId)
				VALUES			(".$sessionHandler->getSessionVariable('UserId').", NOW(), '".$studentId."', '".$classId."', '".$subjectId."',
								'".$conductingAuthority."', '".$oldMarks."','".$oldGradeId."','".$reason."', $instituteId) ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}


	public function getStudentOldCGPA($studentId,$classId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
					SELECT
								gradeIntoCredits, credits
					FROM		student_cgpa
					WHERE		studentId = $studentId
					AND		classId = $classId
					AND		instituteId = $instituteId
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function addCgpaLog($studentId,$classId,$totalGradeIntoPoints, $totalCredits,$reason) {
        global $sessionHandler;
		  $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "INSERT INTO
								cgpa_log (userId, logDateTime, studentId, classId, oldGradeIntoCredits, oldCredits, reason, instituteId)
								VALUES(".$sessionHandler->getSessionVariable('UserId').",NOW(),'".$studentId."','".$classId."',
								'".$totalGradeIntoPoints."','".$totalCredits."','".$reason."',$instituteId)";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Total classwise CGPA
//
// Author :Parveen Sharma
// Created on : (22.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------
  public function updateStudentCGPA($studentId,$classId,$totalGradeIntoPoints,$totalCredits) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
					UPDATE
								student_cgpa
					SET		gradeIntoCredits = $totalGradeIntoPoints,
								credits = $totalCredits
					WHERE		studentId = $studentId
					AND		classId = $classId
					AND		instituteId = $instituteId
					";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	public function insertStudentCGPA($studentId,$classId,$totalGradeIntoPoints,$totalCredits) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
					INSERT INTO
								student_cgpa
					SET		gradeIntoCredits = $totalGradeIntoPoints,
								credits = $totalCredits,
								studentId = $studentId,
								classId = $classId,
								instituteId = $instituteId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	public function countMarks($classId, $studentId, $subjectId) {
		$query = "SELECT count(marksScored) as cnt from ".TOTAL_TRANSFERRED_MARKS_TABLE." a where a.classId = $classId and a.subjectId = $subjectId and a.studentId = $studentId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getGradeSetGrades($classId, $studentId, $subjectId) {
		$query = "SELECT distinct b.gradeId, b.gradeLabel from grades b, ".TOTAL_TRANSFERRED_MARKS_TABLE." a where a.classId = $classId and a.subjectId = $subjectId and a.studentId = $studentId and a.gradeSetId = b.gradeSetId order by b.gradePoints desc";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getGradeSetGradingLabel($classId, $studentId, $subjectId) {
		$query = "SELECT gradeSetId, gradingLabelId from ".TOTAL_TRANSFERRED_MARKS_TABLE." where classId = $classId and subjectId = $subjectId and studentId = $studentId ";
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

    public function getStudent_Delete($conditions) {

            $query = "SELECT
                            COUNT(*) AS totalRecords
                       FROM
                          (SELECT
                                DISTINCT studentId
                           FROM
                                student
                           $conditions
                           UNION
                           SELECT
                                DISTINCT studentId
                           FROM
                                quarantine_student
                           $conditions) AS tt ";

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


	public function addStudentInfoInTransaction($getClassId,$rollNo,$univRollNo,$isLeet,$firstName,$lastName,$fatherName,$fatherOccupation,$fatherMobile,$fatherAddress1,$fatherAddress2,$fatherCountryId,$fatherStateId,$fatherCityId,$motherName,$dateOfBirth,$corrAddress1,$corrAddress2,$corrPinCode,$corrCountryId,$corrStateId,$corrCityId,$permAddress1,$permAddress2,$permPinCode,$permCountryId,$permStateId,$permCityId,$studentMobile,$domicileId,$hostelFacility,$busFacility,$correspondencePhone,$permanentPhone,$studentStatus,$gender,$nationalityId,$quotaId,$contactNo,$emailAddress,$alternateEmailAddress,$dateOfAdmission,$registrationNo,$sAllClass) {

        if(trim($corrCountryId)=='') {
         $corrCountryId='NULL';
        }
        if(trim($corrStateId)=='') {
         $corrStateId='NULL';
        }
        if(trim($corrCityId)=='') {
         $corrCityId='NULL';
        }
        if(trim($permCountryId)=='') {
         $permCountryId='NULL';
        }
        if(trim($permStateId)=='') {
         $permStateId='NULL';
        }
        if(trim($permCityId)=='') {
         $permCityId='NULL';
        }
        if(trim($domicileId)=='') {
         $domicileId='NULL';
        }
        if(trim($fatherCountryId)=='') {
         $fatherCountryId='NULL';
        }
        if(trim($fatherStateId)=='') {
         $fatherStateId='NULL';
        }
        if(trim($fatherCityId)=='') {
         $fatherCityId='NULL';
        }

        if(trim($nationalityId)=='') {
         $nationalityId='NULL';
        }

        if(trim($quotaId)=='') {
         $quotaId='NULL';
        }

        if(trim($domicileId)=='') {
         $domicileId='NULL';
        }

		$query = "INSERT INTO `student`
                (classId,rollNo,universityRollNo,isLeet,firstName,lastName,fatherName,fatherOccupation,fatherMobileNo,fatherAddress1,
                 fatherAddress2,fatherCountryId,fatherStateId,fatherCityId,motherName,dateOfBirth,corrAddress1,corrAddress2,corrPinCode,
                 corrCountryId,corrStateId,corrCityId,permAddress1,permAddress2,permPinCode,permCountryId,permStateId,permCityId,studentMobileNo,
                 domicileId,hostelFacility,transportFacility,corrPhone,permPhone,studentStatus,studentGender,nationalityId,quotaId,studentPhone,
                 studentEmail,alternateStudentEmail,dateOfAdmission,regNo,sAllClass)
                 VALUES
                 ('$getClassId','$rollNo','$univRollNo','$isLeet','$firstName','$lastName','$fatherName','$fatherOccupation','$fatherMobile',
                  '$fatherAddress1','$fatherAddress2',$fatherCountryId,$fatherStateId,$fatherCityId,'$motherName','$dateOfBirth','$corrAddress1',
                  '$corrAddress2','$corrPinCode',$corrCountryId,$corrStateId,$corrCityId,'$permAddress1','$permAddress2','$permPinCode',
                  $permCountryId, $permStateId, $permCityId,'$studentMobile',$domicileId,'$hostelFacility','$busFacility',
                  '$correspondencePhone','$permanentPhone','$studentStatus', '$gender',$nationalityId,$quotaId,'$contactNo','$emailAddress',
                  '$alternateEmailAddress','$dateOfAdmission','$registrationNo','$sAllClass')";

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

        if($quotaId=='') {
          $quotaId='NULL';
        }

        if($domicileId=='') {
          $domicileId='NULL';
        }


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
							quotaId = $quotaId,
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


        if($quotaId=='') {
          $quotaId='NULL';
        }

        if($domicileId=='') {
          $domicileId='NULL';
        }


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
							quotaId = $quotaId,
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

        if($quotaId=='') {
          $quotaId='NULL';
        }

        if($domicileId=='') {
          $domicileId='NULL';
        }

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
							quotaId = $quotaId,
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


	//-------------------------------------------------------------------------------
//
//updateRecordWithUnivRoll() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Himani Jaswal
// Created on : 9.11.10
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function updateRecordWithUnivRoll($univRollNo,$firstName,$rollNo,$isLeet,$lastName,$fatherName,$fatherOccupation,$fatherMobile,$fatherAddress1,$fatherAddress2,$fatherCountryId,$fatherStateId,$fatherCityId,$motherName,$dateOfBirth,$corrAddress1,$corrAddress2,$corrPinCode,$corrCountryId,$corrStateId,$corrCityId,$permAddress1,$permAddress2,$permPinCode,$permCountryId,$permStateId,$permCityId,$studentMobile,$domicileId,$hostelFacility,$busFacility,$correspondencePhone,$permanentPhone,$studentStatus,$gender,$nationalityId,$quotaId,$contactNo,$emailAddress,$alternateEmailAddress,$dateOfAdmission,$registrationNo,$sAllClass)  {

        global $REQUEST_DATA;

        if($quotaId=='') {
          $quotaId='NULL';
        }

        if($domicileId=='') {
          $domicileId='NULL';
        }

        if($nationalityId=='') {
          $nationalityId='NULL';
        }

       if($corrCountryId=="")
         $corrCountryId = 'NULL';

       if($corrStateId=="")
         $corrStateId = 'NULL';

       if($corrCityId=="")
         $corrCityId = 'NULL';

       if($permCountryId=="")
          $permCountryId = 'NULL';

       if($permStateId=="")
          $permStateId = 'NULL';

       if($permCityId=="")
          $permCityId = 'NULL';


        if($fatherCountryId=="")
         $fatherCountryId = 'NULL';

       if($fatherStateId=="")
         $fatherStateId = 'NULL';

       if($fatherCityId=="")
         $fatherCityId = 'NULL';

       if($motherCountryId=="")
         $motherCountryId = 'NULL';

       if($motherStateId=="")
         $motherStateId = 'NULL';

       if($motherCityId=="")
         $motherCityId = 'NULL';



      $query = "	UPDATE `student`
					SET		universityRollNo = '$univRollNo',
							isLeet = '$isLeet',
							firstName = '$firstName',
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
							quotaId = $quotaId,
							studentPhone = '$contactNo',
							studentEmail = '$emailAddress',
							alternateStudentEmail = '$alternateEmailAddress',
							dateOfAdmission = '$dateOfAdmission',
							regNo = '$registrationNo',
							sAllClass ='$sAllClass'
					WHERE	rollNo ='$rollNo'
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
				FROM		".TEST_TABLE." a, ".TEST_MARKS_TABLE." b, test_type_category c
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
		$query = "SELECT IFNULL(MAX(testIndex),0) as testIndex from ".TEST_TABLE." where classId = $classId and subjectId = $subjectId and groupId = $groupId and testTypeCategoryId = $newTestTypeCategoryId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

    public function getNewTestIndexNew($classId, $subjectId, $groupId, $newTestTypeCategoryId) {

        $query = "SELECT
                        DISTINCT IFNULL(testIndex,0) AS testIndex
                  FROM
                        ".TEST_TABLE."
                  WHERE
                        classId = '$classId' and subjectId = '$subjectId' and
                        groupId = '$groupId' and testTypeCategoryId = '$newTestTypeCategoryId' ";

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
		$query = "UPDATE ".TEST_TABLE." set testTypeCategoryId = $newTestTypeCategoryId, testIndex = $newTestIndex where classId = $classId and subjectId = $subjectId and groupId = $groupId and testId = $testId";
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

	public function updateTestTransferMarks($testTypeId,$studentId,$classId,$subjectId,$maxMarks,$maxValue) {
		 $query = "	UPDATE ".TEST_TRANSFERRED_MARKS_TABLE."
					SET		maxMarks = $maxMarks,
							marksScored = $maxValue
					WHERE	studentId = $studentId
					AND		classId = $classId
					AND		subjectId = $subjectId
					AND		testTypeId = $testTypeId";
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
	public function getClassName($classId) {
		$query = "	SELECT
							className
					FROM	class
					WHERE
							classId = $classId";
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

        global $sessionHandler;
        $userId = $sessionHandler->getSessionVariable('userId');

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

         global $sessionHandler;

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
	public function getLabelClass($labelId,$orderBy=' cls.degreeId,cls.branchId,cls.studyPeriodId') {

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

		$query = "	SELECT	CONCAT_WS(' ',st.firstName,st.lastName) AS studentName,
							st.rollNo, st.universityRollNo,
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
							st.rollNo, st.universityRollNo,
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


	public function makeUserRoleInTransaction($userId, $roleId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "INSERT INTO user_role(userId, roleId, defaultRoleId, instituteId) values ($userId, $roleId, $roleId, $instituteId)";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	/*
	public function getActiveSetGrades($ordering = 'ASC') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT a.gradeId, a.gradeLabel, a.gradePoints, b.gradeSetId FROM grades a, grades_set b where a.gradeSetId = b.gradeSetId and b.isActive = 1 AND a.instituteId = $instituteId AND b.instituteId = $instituteId ORDER BY a.gradePoints $ordering";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}
	*/

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
	public function getDeletedStudentDetail($filter,$limit='',$orderBy) {
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
							$filter
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
	public function getCountDeletedStudentDetail($filter='') {
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
							$filter";
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
                        c.className AS className
            FROM        class c,
                        quarantine_student s
            INNER JOIN    ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
            LEFT JOIN    attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
			INNER JOIN	subject su ON su.subjectId = att.subjectId
            WHERE       s.studentId = $studentId
            AND         att.classId = c.classId
            AND         c.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
					   GROUP BY att.subjectId
					   ORDER BY $orderBy
					";																		//To view all the data of deleted user
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
				AND		cl.instituteId = $instituteId
						ORDER BY $orderBy
					";																		//To view all the data of deleted user
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
				AND		cl.instituteId = $instituteId
						ORDER BY $orderBy
					";																		//To view all the data of deleted user
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
	public function insertQuarantineFee($receiptId) {

		global $sessionHandler;
		$deleteUserId = $sessionHandler->getSessionVariable('UserId');
		$dated=date('Y-m-d h:i:s');

		$query = "INSERT INTO quarantine_fee_receipt

          (feeReceiptId,studentId,feeType,instituteId,installmentCount,feeCycleId,srNo,receiptNo,receiptDate,receiptStatus,reasonOfCancellation,currentStudyPeriodId ,feeStudyPeriodId,totalFeePayable,printRemarks,generalRemarks,previousDues,previousOverPayment,totalAmountPaid,cashAmount,fine,discountedFeePayable,paymentInstrument,instrumentNo,instrumentDate,payableBankId,issuingBankId,favouringBankBranchId,instrumentStatus,instrumentClearanceDate,nextReceiptId,receivedFrom,userId,deleteUserId,deletedOn)

		  SELECT

          feeReceiptId,studentId,feeType,instituteId,installmentCount,feeCycleId,srNo,receiptNo,receiptDate,receiptStatus,reasonOfCancellation,currentStudyPeriodId ,feeStudyPeriodId,totalFeePayable,printRemarks,generalRemarks,previousDues,previousOverPayment,totalAmountPaid,cashAmount,fine,discountedFeePayable,paymentInstrument,instrumentNo,instrumentDate,payableBankId,issuingBankId,favouringBankBranchId,instrumentStatus,instrumentClearanceDate,nextReceiptId,receivedFrom,userId,$deleteUserId,'$dated'

		  FROM

				  fee_receipt  WHERE `feeReceiptId` =".$receiptId;
		  SystemDatabaseManager::getInstance()->executeUpdate($query);


		  $query = "INSERT INTO quarantine_fee_head_student
					(feeHeadStudentId,firstReceiptId,studentId,feeHeadId,feeHeadAmount,feeCycleId,discountedAmount,feeStudyPeriodId)

					SELECT
			        feeHeadStudentId,firstReceiptId,studentId,feeHeadId,feeHeadAmount,feeCycleId,discountedAmount,feeStudyPeriodId

					FROM
				    fee_head_student  WHERE `firstReceiptId` =".$receiptId;
		  SystemDatabaseManager::getInstance()->executeUpdate($query);

		  $query = "INSERT INTO quarantine_fee_payment_detail
					(feePaymentDetailId,studentId,feeCycleId,installment,paymentInstrument,instrumentNo,instrumentAmount,instrumentDate,issuingBankId,receiptStatus,instrumentStatus,instrumentClearanceDate,feeReceiptId)

					SELECT
			        feePaymentDetailId,studentId,feeCycleId,installment,paymentInstrument,instrumentNo,instrumentAmount,instrumentDate,issuingBankId,receiptStatus,instrumentStatus,instrumentClearanceDate,feeReceiptId

					FROM
				    fee_payment_detail  WHERE `feeReceiptId` =".$receiptId;
		 SystemDatabaseManager::getInstance()->executeUpdate($query);

		 $query = "DELETE FROM fee_payment_detail WHERE feeReceiptId=$receiptId";
         SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");

		 $query = "DELETE FROM fee_head_student WHERE firstReceiptId=$receiptId";
         SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");

		 $query = "DELETE FROM fee_receipt WHERE feeReceiptId=$receiptId";
         return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");

	}


	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT ROLL NO
//
// Author :Jaineesh
// Created on : (15.05.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function checkExistRollNo($rollNo) {

        $query = "	SELECT	COUNT(rollNo) AS cnt
					FROM	student
					WHERE	rollNo = '$rollNo'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO HOLD STUDENT RESULT
//
// Author :Jaineesh
// Created on : (15.05.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function studentHoldResult($studentId) {

        $query = "	UPDATE	".TOTAL_TRANSFERRED_MARKS_TABLE."
					SET		holdResult = 1
					WHERE	studentId = $studentId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO HOLD STUDENT RESULT
//
// Author :Jaineesh
// Created on : (15.05.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function studentUnHoldResult($studentId) {

        $query = "	UPDATE	".TOTAL_TRANSFERRED_MARKS_TABLE."
					SET		holdResult = 0
					WHERE	studentId = $studentId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT ROLL NO
//
// Author :Jaineesh
// Created on : (15.05.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getStudentIDs($rollNo) {

       $query = "	SELECT	studentId
					FROM	student
					WHERE	rollNo = '$rollNo'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    // Failed Upload (delete Student)
    public function deleteStudentFailedUpload($studentId) {
        global $sessionHandler;

        //First Delete the records student ailment
        $query = "DELETE FROM student_ailment WHERE studentId=$studentId ";
        $ret=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($ret===false){
           return false;
        }
        //Second delete records student academic
        $query = "DELETE FROM `student_academic` WHERE studentId=$studentId ";
        $ret=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($ret===false){
           return false;
        }

        //Then delete records student
        $query = "DELETE FROM `student` WHERE studentId=$studentId ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


 public function checkAlumniClass($classId) {

        $query = "SELECT
                        COUNT(*) AS isAlumni
                  FROM
                        class
                  WHERE
                        classId=$classId
                        AND studyPeriodId IN (SELECT DISTINCT studyPeriodId FROM study_period WHERE periodValue=99999)";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	 public function getAllSubjectList($classId) {
		 $query = "SELECT DISTINCT a.subjectId, b.subjectCode from ".TOTAL_TRANSFERRED_MARKS_TABLE." a, subject b where a.subjectId = b.subjectId and a.classId = $classId UNION SELECT DISTINCT a.subjectId, b.subjectCode from subject_to_class a, subject b where a.subjectId = b.subjectId and a.classId = $classId and a.hasParentCategory = 0 UNION SELECT DISTINCT a.subjectId, b.subjectCode from student_optional_subject a, subject b where a.subjectId = b.subjectId and a.classId = $classId ORDER BY subjectCode";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

 public function getStudentAllClasses($studentId) {

        $query = "
                  SELECT DISTINCT classId FROM student_groups WHERE studentId=$studentId
                   UNION
                  SELECT DISTINCT classId FROM student_optional_subject WHERE studentId=$studentId
                  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENTS FOR PAYMENT HISTORY LIST
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getStudentFeesHistoryList($conditions='', $limit = '', $orderBy='',$feeClassId='') {

        global $sessionHandler;

        $oldFeeCondition = "";
        $newFeeCondition = "";
        if($feeClassId!='') {
          $oldFeeCondition = " AND stu.classId = '$feeClassId' ";
          $newFeeCondition = " AND fr.feeClassId = '$feeClassId' ";
        }


        $query = "SELECT
                       DISTINCT tt.studentId, tt.receiptNo, tt.totalFeePayable, tt.fine,
                       tt.discountedFeePayable, tt.totalAmountPaid,  tt.previousDues,
                       tt.previousOverPayment, tt.outstanding, tt.cycleName, tt.feeReceiptId,
                       tt.rollNo, tt.regNo, tt.universityRollNo, tt.universityRegNo, tt.studentName,
                       tt.srNo, tt.feeType, tt.receiptDate, tt.receiptDate1, tt.installmentCount, tt.installmentCount1,
                       tt.className, tt.feeCycleId, tt.classId, tt.receiptStatus, tt.instrumentStatus
                  FROM
                      (SELECT
                                stu.studentId,fr.receiptNo,
                                fr.totalFeePayable AS totalFeePayable, fr.fine AS fine,
                                (IFNULL(fr.fine,0)+IFNULL(totalFeePayable,0))-IFNULL((SELECT SUM(discountedAmount) FROM fee_head_student fhs
                                        WHERE fhs.studentId=stu.studentId AND fhs.feeCycleId=fr.feeCycleId GROUP BY fhs.studentId),0)-
                                     IFNULL((SELECT SUM(oldFr.totalAmountPaid) FROM fee_receipt oldFr WHERE
                                             oldFr.studentId = fr.studentId  AND
                                             oldFr.feeCycleId = fr.feeCycleId AND
                                             oldFr.feeReceiptId <  fr.feeReceiptId
                                        GROUP BY oldFr.studentId, oldFr.feeCycleId),0) AS discountedFeePayable,
                                fr.totalAmountPaid AS totalAmountPaid,
                                IFNULL((IFNULL(fr.fine,0)+IFNULL(totalFeePayable,0))-
                                IFNULL((SELECT SUM(discountedAmount) FROM fee_head_student fhs
                                WHERE fhs.studentId=stu.studentId AND fhs.feeCycleId=fr.feeCycleId GROUP BY fhs.studentId),0)-(
                                IFNULL((SELECT SUM(oldFr.totalAmountPaid) FROM fee_receipt oldFr WHERE
                                oldFr.studentId = fr.studentId  AND
                                oldFr.feeCycleId = fr.feeCycleId AND
                                oldFr.feeReceiptId <= fr.feeReceiptId
                                GROUP BY oldFr.studentId, oldFr.feeCycleId),0)),0) AS previousDues,
                                fr.previousOverPayment AS previousOverPayment,
                                CAST(IF(fr.previousDues>0,fr.previousDues,IF(fr.previousOverPayment>0,CONCAT('-',fr.previousOverPayment),'0.00')) AS SIGNED) AS outstanding,
                                fc.cycleName, feeReceiptId,stu.rollNo,stu.regNo, stu.universityRollNo, stu.universityRegNo,
                                CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) AS studentName,

                                srNo,feeType,DATE_FORMAT(receiptDate, '%d-%b-%y') AS receiptDate, fr.receiptDate AS receiptDate1,
                                CONCAT('Installment','-',installmentCount) AS installmentCount, installmentCount AS installmentCount1,
                                SUBSTRING_INDEX(cls.className,'-',-3) AS className,  fc.feeCycleId,cls.classId,
                                fr.receiptStatus,fr.instrumentStatus
                        FROM
                                student stu, fee_receipt fr,fee_cycle fc,class cls
                        WHERE
                                stu.studentId = fr.studentId AND
                                fr.feeCycleId = fc.feeCycleId AND
                                stu.classId = cls.classId AND
                                cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND
                                cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId')." AND
                                fr.isNew='0'
                                $oldFeeCondition
                                $conditions
                        GROUP BY
                               fr.feeReceiptId,stu.studentId
                        UNION
                        SELECT
                                stu.studentId,fr.receiptNo,
                                fr.totalFeePayable AS totalFeePayable, fr.fine,
                                IFNULL(
                                SUM((SELECT
                                            SUM((
                                                  IFNULL((IF(IFNULL(fhv.quotaId,'')=stu.quotaId,
                                                       (IF(fhv.isLeet=3,fhv.feeHeadAmount, IF(fhv.isLeet=1,IF(stu.isLeet=1,fhv.feeHeadAmount,0),
                                                         IF(fhv.isLeet=2,IF(stu.isLeet=0,fhv.feeHeadAmount,0),0)))),
                                                       (IF(IFNULL(fhv.quotaId,'')='',
                                                         (IF(fhv.isLeet=3,fhv.feeHeadAmount, IF(fhv.isLeet=1,IF(stu.isLeet=1,fhv.feeHeadAmount,0),
                                                            IF(fhv.isLeet=2,IF(stu.isLeet=0,fhv.feeHeadAmount,0),0)))),0)))),0)-
                                                  IFNULL((SELECT
                                                               IFNULL(headValue,0)-IFNULL(discountValue,0)
                                                          FROM
                                                               student_concession sc
                                                          WHERE
                                                               sc.feeCycleId=fhv.feeCycleId AND sc.classId=fhv.classId AND
                                                               sc.feeHeadId=fhv.feeHeadId AND sc.studentId= stu.studentId),0)
                                                )) AS feeHeadAmt
                                     FROM
                                            fee_head fh,
                                            fee_head_values fhv
                                     WHERE
                                            fhv.feeHeadId  = fh.feeHeadId AND
                                            fh.instituteId = '".$sessionHandler->getSessionVariable('InstituteId')."' AND
                                            fhv.feeCycleId = fr.feeCycleId  AND
                                            fhv.classId  = fr.feeClassId  AND
                                            fh.isVariable  = 0
                                     GROUP BY
                                            fhv.feeCycleId, fhv.classId
                                     UNION
                                     SELECT
                                           IFNULL(SUM(sm.charges),0) AS feeHeadAmt
                                     FROM
                                            fee_head fh, student_misc_fee_charges sm
                                     WHERE
                                            fh.feeHeadId  = sm.feeHeadId AND
                                            fh.instituteId = '".$sessionHandler->getSessionVariable('InstituteId')."' AND
                                            sm.feeCycleId = fr.feeCycleId AND
                                            sm.classId = fr.feeClassId AND
                                            fh.isVariable = 1 AND
                                            sm.studentId = stu.studentId
                                     GROUP BY
                                            sm.feeCycleId, sm.classId, sm.studentId
                                   )),0) AS discountedFeePayable,
                                (fine+feePaid+hostelPaid+hostelFine+transportPaid+transportFine) AS totalAmountPaid,
                                '' AS previousDues, '' AS previousOverPayment,
                                '' AS outstanding,
                                fc.cycleName, fr.feeReceiptId,
                                stu.rollNo, stu.regNo, stu.universityRollNo, stu.universityRegNo,
                                CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) AS studentName,
                                fr.srNo, fr.feeType, DATE_FORMAT(fr.receiptDate, '%d-%b-%y') AS receiptDate,  fr.receiptDate AS receiptDate1,
                                CONCAT('Installment','-',fr.installmentCount) AS installmentCount, fr.installmentCount AS installmentCount1,
                                cls.className, fr.feeCycleId, cls.classId, fr.receiptStatus, fr.instrumentStatus
                        FROM
                                student stu, fee_receipt fr,fee_cycle fc,class cls
                        WHERE
                                stu.studentId = fr.studentId AND
                                fr.feeCycleId = fc.feeCycleId AND
                                fr.feeClassId = cls.classId AND
                                fr.isNew='1' AND
                                cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND
                                cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId')."
                                $conditions
                                $newFeeCondition
                        GROUP BY
                                fr.feeReceiptId,stu.studentId
                      ) AS tt
                  $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET TABS WHICH ARE BLOCKED  FROM ROLE PERMISSION
//
// Author :=Abhay Kant
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


	public function getBlockedTab($titleName="") {

		global $sessionHandler;
		$roleId = $sessionHandler->getSessionVariable('RoleId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');



		     $query = "SELECT
		                      a.frameId, a.frameName AS  frameName1,
				      REPLACE(a.frameName,' ','_') AS frameName
			       FROM
				      dashboard_frame a
			       WHERE
				      a.isActive = 1 AND
				      a.titleName LIKE '".$titleName."'	AND
				      a.frameId NOT IN (SELECT
							     DISTINCT frameId
						        FROM
							     dashboard_permissions
						        WHERE
							     roleId=".$roleId."
				              	             AND instituteId =".$instituteId.")";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


    //-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Jaineesh
    // Created on : 17-04-2010
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
    public function getLabelClassNew($labelId='',$orderBy=' degreeId,branchId,studyPeriodId') {



        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');

        $classId = '';
        $employeeCondition='';
        if($roleId=='2') {      // Teacher Login
          $employeeId = $sessionHandler->getSessionVariable('EmployeeId');
          $employeeCondition =" AND tt.employeeId = '$employeeId' ";
        }
        else {
           if($roleId!='1') {   // All Login except Admin
               $query = "SELECT
                              DISTINCT
                                        cvtr.classId
                      FROM
                              classes_visible_to_role cvtr
                      WHERE   cvtr.userId = $userId
                              AND cvtr.roleId = $roleId ";

                $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
                $count = count($result);
                if($count>0) {
                   $classId = " AND cls.classId IN (0";
                   for($i=0; $i<count($result); $i++) {
                      $classId .= ",".$result[$i]['classId'];
                   }
                   $classId .= ")";
                }
           }
        }

        $query = "SELECT
                         DISTINCT cls.classId,
                         CONCAT(cls.className,' (',IF(cls.isActive=1,'Active',IF(cls.isActive=2,'Future',IF(cls.isActive=3,'Past',''))),')') AS className
                 FROM
                         class cls, time_table_labels ttl,
                         time_table_classes ttc LEFT JOIN  ".TIME_TABLE_TABLE." tt ON
                         (tt.classId = ttc.classId AND tt.timeTableLabelId = '$labelId' AND tt.toDate IS NULL)
                 WHERE
                         cls.instituteId='".$instituteId."' AND
                         cls.sessionId='".$sessionId."' AND
                         cls.isActive IN (1,3)  AND
                         cls.classId = ttc.classId AND
                         ttc.timeTableLabelId = ttl.timeTableLabelId AND
                         ttc.timeTableLabelId = '$labelId'
                         $classId
                         $employeeCondition
                 ORDER BY
                         degreeId,branchId,studyPeriodId ASC";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

    public function getStudentListExternal($condition='',$orderBy='universityRollNo',$subjectId='') {

        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');

        $employeeCondition='';
        if($roleId=='2') {      // Teacher Login
          $employeeId = $sessionHandler->getSessionVariable('EmployeeId');
          $employeeCondition =" AND tt.employeeId = '$employeeId' AND tt.subjectId='$subjectId' ";
        }

        $query = "SELECT
                        DISTINCT s.studentId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                 IFNULL(s.rollNo,'') AS rollNo, IFNULL(s.universityRollNo,'') AS universityRollNo,
                        IFNULL(ttm.maxMarks,'') AS maxMarks, IFNULL(ttm.marksScored,'') AS marksScored
                  FROM
                       student s, `group` g,  ".TIME_TABLE_TABLE." tt, student_groups sg
                       LEFT JOIN ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm ON
                       sg.studentId = ttm.studentId AND sg.classId=ttm.classId AND ttm.subjectId='$subjectId'
                  WHERE
                       s.studentId = sg.studentId AND
                       g.classId = sg.classId AND
                       g.groupId = sg.groupId AND
                       g.groupId = tt.groupId AND
                       g.classId = tt.classId AND
                       sg.instituteId = '$instituteId' AND
                       sg.sessionId='$sessionId'
                   $employeeCondition
                   $condition
                   ORDER BY
                        $orderBy ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


     public function getStudentListExternalNew($condition='',$orderBy='universityRollNo') {

        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');

        $employeeCondition='';
        if($roleId=='2') {      // Teacher Login
          $employeeId = $sessionHandler->getSessionVariable('EmployeeId');
          $employeeCondition =" AND tt.employeeId = '$employeeId' AND tt.subjectId='$subjectId' ";
        }

        $query = "SELECT
                        DISTINCT s.studentId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IFNULL(s.rollNo,'') AS rollNo, IFNULL(s.universityRollNo,'') AS universityRollNo
                  FROM
                       student s, `group` g, ".TIME_TABLE_TABLE." tt, student_groups sg
                  WHERE
                       s.studentId = sg.studentId AND
                       tt.toDate IS NULL AND
                       g.classId = sg.classId AND
                       g.groupId = sg.groupId AND
                       g.groupId = tt.groupId AND
                       g.classId = tt.classId AND
                       sg.instituteId = '$instituteId' AND
                       sg.sessionId='$sessionId'
                       $employeeCondition
                       $condition
                   ORDER BY
                        $orderBy ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

   public function getClassSubjectsTestTypes($classId, $conditions = '',$tableName='') {
        $query = "
            SELECT
                      DISTINCT   a.subjectId, a.classId,
                        b.subjectCode,
                        b.subjectName,
                        a.internalTotalMarks,
                        a.externalTotalMarks,
                        b.hasAttendance,
                        b.hasMarks,
                        a.optional,
                        a.hasParentCategory,
                        a.offered,
                        (select c.subjectTypeName FROM subject_type c, class cls WHERE b.subjectTypeId = c.subjectTypeId AND cls.classId = a.classId AND c.universityId = cls.universityId) AS subjectType,
                        (
                            SELECT IFNULL(SUM(weightageAmount),0) FROM test_type tt, class cls WHERE tt.conductingAuthority = 1 AND tt.subjectId = a.subjectId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.classId = $classId AND tt.instituteId = cls.instituteId and cls.classId = a.classId
                        ) AS internalTestTypeSum,
                        (
                            SELECT IFNULL(SUM(weightageAmount),0) FROM test_type tt, class cls WHERE tt.conductingAuthority = 2 AND tt.subjectId = a.subjectId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.instituteId = cls.instituteId  and tt.classId = $classId AND cls.classId = a.classId

                        ) AS externalTestTypeSum,
                        (
                            SELECT IFNULL(SUM(weightageAmount),0) FROM test_type tt, class cls WHERE tt.conductingAuthority = 3 AND tt.subjectId = a.subjectId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.instituteId = cls.instituteId  and tt.classId = $classId AND cls.classId = a.classId
                        ) AS attendanceTestTypeSum

            FROM        subject_to_class a, subject b   $tableName
            WHERE        a.subjectId = b.subjectId
            AND        a.classId = $classId
            AND        a.hasParentCategory = 0
            $conditions
            UNION
            SELECT
                     DISTINCT   a.subjectId,  a.classId,
                        b.subjectCode,
                        b.subjectName,
                        (select internalTotalMarks from subject_to_class where classId = $classId and subjectId = a.parentOfSubjectId) as internalTotalMarks,
                        (select externalTotalMarks from subject_to_class where classId = $classId and subjectId = a.parentOfSubjectId) as externalTotalMarks,
                        1 as hasAttendance,
                        1 as hasMarks,
                        0 as optional,
                        0 as hasParentCategory,
                        1 as offered,
                        (select c.subjectTypeName FROM subject_type c, class cls WHERE b.subjectTypeId = c.subjectTypeId AND cls.classId = a.classId AND c.universityId = cls.universityId) AS subjectType,
                        (
                            SELECT IFNULL(SUM(weightageAmount),0) FROM test_type tt, class cls WHERE tt.conductingAuthority = 1 AND tt.subjectId = a.subjectId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.classId = $classId AND tt.instituteId = cls.instituteId and cls.classId = a.classId
                        ) AS internalTestTypeSum,
                        (
                            SELECT IFNULL(SUM(weightageAmount),0) FROM test_type tt, class cls WHERE tt.conductingAuthority = 2 AND tt.subjectId = a.subjectId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.instituteId = cls.instituteId  and tt.classId = $classId AND cls.classId = a.classId
                        ) AS externalTestTypeSum,
                        (
                            SELECT IFNULL(SUM(weightageAmount),0) FROM test_type tt, class cls WHERE tt.conductingAuthority = 3 AND tt.subjectId = a.subjectId AND tt.universityId = cls.universityId AND tt.degreeId = cls.degreeId AND tt.branchId = cls.branchId AND tt.studyPeriodId = cls.studyPeriodId AND tt.instituteId = cls.instituteId  and tt.classId = $classId AND cls.classId = a.classId
                        ) AS attendanceTestTypeSum

            FROM        optional_subject_to_class a, subject b  $tableName
            WHERE        a.subjectId = b.subjectId
            AND        a.classId = $classId
            $conditions
            ORDER BY    subjectCode";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


     public function getStudentClasswiseCGPA($condition='') {

            $query = "SELECT
                           sg.classId, c.className, sg.studentId,
                           sg.previousCredit, sg.previousGradePoint, sg.previousCreditEarned,
                           sg.lessCredit, sg.lessGradePoint, sg.lessCreditEarned,
                           sg.currentCredit, sg.currentGradePoint, sg.currentCreditEarned,
                           sg.netCredit, sg.netGradePoint, sg.netCreditEarned,
                           sg.gradeIntoCredits, sg.credits, sg.cgpa, sg.gpa
                     FROM
                          `student` s, `student_cgpa` sg , class c
                     WHERE
                           s.studentId = sg.studentId AND
                           sg.classId = c.classId
                     $condition
                     ORDER BY
                           sg.studentId, sg.classId DESC
                     LIMIT 0,1";

            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getScStudentCGPA($studentId='') {

       global $sessionHandler;
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');

       if($instituteId=='') {
         $instituteId ='0';
       }

       $query = "SELECT
                       studentId, classId, cgpa, gpa
                 FROM
                       student_cgpa
                 WHERE
                       studentId = '$studentId'
                 ORDER BY
                        classId ";

       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getStudentExternalMarksCheck($studentId='') {

       $query = "SELECT
                      DISTINCT studentId,classId,subjectId,marksScoredStatus
                 FROM
                      ".TOTAL_TRANSFERRED_MARKS_TABLE." a
                 WHERE
                      a.marksScoredStatus != 'Marks' AND
                      a.studentId = '$studentId'
                 ORDER BY
                       a.studentId, a.classId, a.subjectId ";

       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getScStudentGradeDetailsNew($studentId='',$classId='',$orderBy='',$limit='',$where='',$holdStudentClassId='') {
      global $sessionHandler;
      $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $isHoldCondition = "";
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        if($roleId==3 || $roleId==4){
          $isHoldCondition = " AND cc.holdGrades = '0'";


	  if($holdStudentClassId!='') {
           $isHoldCondition .= " AND cc.classId NOT IN (".$holdStudentClassId.")";
        }
}
      if($instituteId=='') {
        $instituteId ='0';
      }

      $str = "";
      //if($orderBy=='') {
       $orderBy =" studyPeriodId, subjectCode";
      //}

      $str .= " AND a.studentId = '$studentId' ";

      $transferTable = array("0" => TOTAL_TRANSFERRED_MARKS_TABLE, "1" => TOTAL_UPDATED_MARKS_TABLE);

      $query ="";
      for($i=0;$i<count($transferTable);$i++) {
          if($i!=0) {
            $query .= " UNION ";
          }
          $query .= "SELECT
                        DISTINCT a.studentId, a.classId,a.subjectId, a.gradeId, b.credits, pc.periodicityName, pc.periodicityCode,
                                 c.gradePoints,
                                IF(b.isAlternateSubject='0',sub.subjectCode,sub.alternateSubjectCode) AS subjectCode,
  IF(b.isAlternateSubject='0',sub.subjectName,sub.alternateSubjectName) AS subjectName,
  cc.className, sp.periodName, sp.periodValue,
                                 IF(c.gradeLabel is null, 'I', c.gradeLabel) as gradeLabel1,
                                 IF(INSTR(GROUP_CONCAT(DISTINCT CONCAT('~',a.marksScoredStatus,'~')),'~D~')>0,'I',
      IF(INSTR(GROUP_CONCAT(DISTINCT CONCAT('~',a.marksScoredStatus,'~')),'~A~')>0,'I',
      IF(c.gradeLabel IS NULL, 'I', c.gradeLabel))) AS gradeLabel, sp.studyPeriodId, '$i' AS testType
                    FROM
                        ".$transferTable[$i]." a, subject_to_class b, periodicity pc,
                        grades c, `subject` sub, class cc, study_period sp
                    WHERE
                        a.classId = cc.classId AND sp.studyPeriodId = cc.studyPeriodId AND
                        sub.subjectId = a.subjectId AND a.isActive = '1' AND sp.periodicityId = pc.periodicityId AND
                        b.optional=0 AND a.classId = b.classId AND a.subjectId = b.subjectId AND
                        a.gradeId = c.gradeId AND c.instituteId = $instituteId $str
			            $isHoldCondition
                    GROUP BY
                         a.studentId, a.classId, a.subjectId
                    UNION
                    SELECT
                         DISTINCT a.studentId, a.classId,a.subjectId, a.gradeId, b.credits, pc.periodicityName, pc.periodicityCode,
                         c.gradePoints,
                         IF(b.isAlternateSubject='0',sub.subjectCode,sub.alternateSubjectCode) AS subjectCode,
  IF(b.isAlternateSubject='0',sub.subjectName,sub.alternateSubjectName) AS subjectName,
 cc.className, sp.periodName, sp.periodValue,
                         IF(c.gradeLabel is null, 'I', c.gradeLabel) as gradeLabel1,
                                 IF(INSTR(GROUP_CONCAT(DISTINCT CONCAT('~',a.marksScoredStatus,'~')),'~D~')>0,'I',
      IF(INSTR(GROUP_CONCAT(DISTINCT CONCAT('~',a.marksScoredStatus,'~')),'~A~')>0,'I',
      IF(c.gradeLabel IS NULL, 'I', c.gradeLabel))) AS gradeLabel, sp.studyPeriodId, '$i' AS testType
                    FROM
                         ".$transferTable[$i]." a, subject_to_class b, grades c, periodicity pc,
                         optional_subject_to_class oc,  `subject` sub, class cc, study_period sp
                    WHERE
                        a.classId = cc.classId AND sp.studyPeriodId = cc.studyPeriodId AND
                        sub.subjectId = a.subjectId AND a.isActive = '1' AND
                        oc.classId = b.classId AND b.optional=1 AND
                        oc.parentOfSubjectId = b.subjectId AND sp.periodicityId = pc.periodicityId AND
                        a.classId = b.classId AND a.subjectId = oc.subjectId AND
                        a.gradeId = c.gradeId AND c.instituteId = $instituteId $str
			            $isHoldCondition
                    GROUP BY
                         a.studentId, a.classId, a.subjectId
                    UNION
                    SELECT
                        DISTINCT a.studentId, a.classId,a.subjectId, a.gradeId, b.credits, pc.periodicityName, pc.periodicityCode,
                        c.gradePoints,
IF(b.isAlternateSubject='0',sub.subjectCode,sub.alternateSubjectCode) AS subjectCode,
  IF(b.isAlternateSubject='0',sub.subjectName,sub.alternateSubjectName) AS subjectName,
 cc.className, sp.periodName, sp.periodValue,
                        IF(c.gradeLabel is null, 'I', c.gradeLabel) as gradeLabel1,
                                 IF(INSTR(GROUP_CONCAT(DISTINCT CONCAT('~',a.marksScoredStatus,'~')),'~D~')>0,'I',
      IF(INSTR(GROUP_CONCAT(DISTINCT CONCAT('~',a.marksScoredStatus,'~')),'~A~')>0,'I',
      IF(c.gradeLabel IS NULL, 'I', c.gradeLabel))) AS gradeLabel, sp.studyPeriodId, '$i' AS testType
                    FROM
                        ".$transferTable[$i]." a, subject_to_class b, grades c, periodicity pc,
                        student_optional_subject oc,  `subject` sub, class cc, study_period sp
                    WHERE
                        a.classId = cc.classId AND sp.studyPeriodId = cc.studyPeriodId AND
                        sub.subjectId = a.subjectId AND a.isActive = '1' AND
                        oc.classId = b.classId AND b.optional=1 AND sp.periodicityId = pc.periodicityId AND
                        oc.subjectId = b.subjectId AND a.studentId = oc.studentId AND a.classId = b.classId
                        AND a.subjectId = oc.subjectId AND a.gradeId = c.gradeId AND c.instituteId = $instituteId $str
			            $isHoldCondition
                      GROUP BY
                         a.studentId, a.classId, a.subjectId";
      }
      $query .= " ORDER BY $orderBy $limit";


      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getScStudentGradeDetailsNewOld($studentId='',$classId='',$orderBy='',$limit='',$where='') {
      global $sessionHandler;
     $instituteId = $sessionHandler->getSessionVariable('InstituteId');

      $str = "";
      if($orderBy=='') {
        $orderBy =" periodValue, subjectCode";
      }

      if ($studentId != '') {
        $str .= " AND a.studentId = '$studentId' ";
      }
      if($classId!='' and $classId!=0){
        $classCondition=" AND a.classId='".add_slashes($classId)."'";
      }

      $query = "
        SELECT
        a.classId,
        e.className,
        a.subjectId,
        a.gradeId,
        if(b.gradeLabel is null, 'I', b.gradeLabel) as gradeLabel,
        c.subjectCode,
        c.subjectName,
        d.periodName,
        f.credits,
        b.gradePoints,
        '-1' AS updatedExamType,
        d.periodValue
        FROM
        `subject` c, study_period d, class e, time_table_classes ttc, subject_to_class  f,".TOTAL_TRANSFERRED_MARKS_TABLE." a left join grades b on (a.gradeId = b.gradeId AND             b.instituteId = '$instituteId')
        WHERE   a.subjectId = c.subjectId
                AND        a.classId = e.classId
             AND        d.studyPeriodId = e.studyPeriodId
             AND        a.classId = f.classId
             AND    a.subjectId = f.subjectId
             AND    a.isActive = 1
             AND        e.classId = ttc.classId
                    $str
                    $classCondition
             $where
        GROUP BY
         a.subjectId, a.classId
        UNION
        SELECT
            a.classId,
            e.className,
            a.subjectId,
            a.gradeId,
            if(b.gradeLabel is null, 'I', b.gradeLabel) as gradeLabel,
            c.subjectCode,
            c.subjectName,
            d.periodName,
            f.credits,
            b.gradePoints,
            '1' AS updatedExamType,
            d.periodValue
        FROM
          `subject` c, study_period d, class e, subject_to_class f,".TOTAL_UPDATED_MARKS_TABLE." a left join             grades  b on (a.gradeId = b.gradeId AND b.instituteId = '$instituteId')
        WHERE
        a.subjectId = c.subjectId
            AND a.classId = e.classId
            AND d.studyPeriodId = e.studyPeriodId
                AND a.classId = f.classId
            AND a.subjectId = f.subjectId
            AND a.isActive = 1
                $str
                $classCondition
             $where
        GROUP BY  a.subjectId, a.classId
        ORDER BY  $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }

      public function addPollDetails($filter='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "INSERT INTO `poll_details`
                  (studentId,classId,questionId1,questionId2,questionId3,questionId4,questionId5)
                  VALUES
                  ($filter)";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
      }

	  public function pollReport($condition='',$orderBy='') {

          global $sessionHandler;
          $instituteId = $sessionHandler->getSessionVariable('InstituteId');

          $query = "SELECT
				        tt.employeeName, tt.employeeId,
                        CONCAT(tt.employeeName,' (',tt.employeeCode,')') AS employeeNameCode,
				        SUM(IF(tt.employeeId=p.questionId1,1,0)) AS q1,
				        SUM(IF(tt.employeeId=p.questionId2,1,0)) AS q2,
				        SUM(IF(tt.employeeId=p.questionId3,1,0)) AS q3,
				        SUM(IF(tt.employeeId=p.questionId4,1,0)) AS q4,
				        SUM(IF(tt.employeeId=p.questionId5,1,0)) AS q5,
				        (SUM(IF(tt.employeeId=p.questionId1,1,0))+
				         SUM(IF(tt.employeeId=p.questionId2,1,0))+
				         SUM(IF(tt.employeeId=p.questionId3,1,0))+
				         SUM(IF(tt.employeeId=p.questionId4,1,0))+
				         SUM(IF(tt.employeeId=p.questionId5,1,0))) AS total
				    FROM
				        (SELECT
					        e.employeeId, e.employeeName, e.employeeCode
				         FROM
					        employee e
				         WHERE
					        e.employeeId IN (SELECT questionId1 FROM poll_details)
					        OR
					        e.employeeId IN (SELECT questionId2 FROM poll_details)
					        OR
					        e.employeeId IN (SELECT questionId3 FROM poll_details)
					        OR
					        e.employeeId IN (SELECT questionId4 FROM poll_details)
					        OR
					        e.employeeId IN (SELECT questionId5 FROM poll_details)
				         ) AS tt, poll_details p
				    GROUP BY
				        tt.employeeId
                    $condition
				    ORDER BY
				        $orderBy";

            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }

      public function getCheckPoll($condition='') {

          global $sessionHandler;
          $instituteId = $sessionHandler->getSessionVariable('InstituteId');

          $studentId = $sessionHandler->getSessionVariable('StudentId');
          $classId = $sessionHandler->getSessionVariable('ClassId');

          $query = "SELECT
                         COUNT(*) AS totalRecord
                    FROM
                         poll_details pd
                    WHERE
                         pd.studentId = '$studentId'
                         AND pd.classId = '$classId' ";

            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }

      public function getAlumniStudentTotal($condition='',$deleteStudent='0') {

           global $sessionHandler;
           $systemDatabaseManager = SystemDatabaseManager::getInstance();

           $tableName='student';
           if($deleteStudent=='1') {
             $tableName='quarantine_student';
           }

           $query ="SELECT
                          COUNT(*) AS  totalRecords
                    FROM
                          $tableName s, class b
                    WHERE
                          s.classId = b.classId
                    $condition ";

            return $systemDatabaseManager->executeQuery($query,"Query: $query");
      }

      public function getAlumniStudentList($condition='',$limit,$orderBy='',$deleteStudent='0') {

           global $sessionHandler;
           $systemDatabaseManager = SystemDatabaseManager::getInstance();

           $tableName='student';
           $fieldName = " '0' AS isDeleted, ";
           if($deleteStudent=='1') {
             $tableName='quarantine_student';
             $fieldName = " '1' AS isDeleted, ";
           }

           $query ="SELECT
                          DISTINCT b.classId, b.className, b.isActive, s.studentId,s.studentStatus,
                          CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName, $fieldName
                          IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                          IF(IFNULL(s.regNo,'')='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,
                          IF(IFNULL(s.studentEmail,'')='','".NOT_APPLICABLE_STRING."',s.studentEmail) AS studentEmail,
                          IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                          IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo
                    FROM
                          $tableName s, class b
                    WHERE
                          s.classId = b.classId
                    $condition
                    ORDER BY
                          $orderBy
                    $limit";

            return $systemDatabaseManager->executeQuery($query,"Query: $query");
      }

      public function getStudentGPA($condition='') {

            if($condition == '') {
               $cond = " WHERE  s1.studentId = s.studentId ";
            }
            else {
               $cond = " WHERE s1.studentId = s.studentId AND ".$condition;
            }
            $query = "SELECT
                            s.classId, className,
                            IF(s.credits=0,0,(s.gradeIntoCredits/s.credits)) AS gpa, FORMAT(credits,0) AS credits,
                            IF(s.credits=0,0,(s.gradeIntoCredits/s.credits)) AS gpa1
                      FROM
                            `student` s1, `student_cgpa` s  LEFT JOIN `class` c ON s.classId = c.classId
                      $cond ";

            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }


      public function getStudentCGPA($condition='') {

            if($condition == '') {
               $cond = " WHERE  s1.studentId = s.studentId ";
            }
            else {
               $cond = " WHERE s1.studentId = s.studentId AND ".$condition;
            }

            $query = "SELECT
                            t.classId, t.className,
                            IF(t.credits=0,0,(t.gradeIntoCredits/t.credits)) AS CGPA, FORMAT(t.credits,0) AS credits
                      FROM
                            (SELECT
                                      s.classId, className, SUM(gradeIntoCredits) AS gradeIntoCredits, SUM(credits) AS credits
                             FROM
                                      `student` s1, `student_cgpa` s LEFT JOIN `class` c ON s.classId = c.classId
                             $cond
                             GROUP BY
                                      s.studentId) AS t";

            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }

	public function getHoldStudentsData($studentId) {

        $query = "SELECT
		           shr.studentId,shr.classId,shr.studentId as holdStudentId,shr.holdAttendance,
		           shr.holdTestMarks,shr.holdFinalResult,shr.holdGrades
                  FROM
                           student_hold_result shr
                   WHERE
                     	    shr.studentId = '$studentId' ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
     public function updateStudentAllClasses($classId) {

        $query = "SELECT
						 DISTINCT cc.classId ,sp.periodValue,cc.className
                 FROM
                     	class cc , study_period sp
                WHERE
	                   cc.studyPeriodId = sp.studyPeriodId
	                    AND
	                     CONCAT_WS(',',cc.batchId,cc.degreeId,cc.branchId) IN
	                     (SELECT
	                          DISTINCT CONCAT_WS(',',c.batchId,c.degreeId,c.branchId)
	                      FROM
	                         class c WHERE c.classId='$classId')
                ORDER BY
                   sp.periodValue";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    public function updateEditStudentAllClasses($studentId) {

        $query = "SELECT
						 DISTINCT cc.classId ,sp.periodValue,cc.className
                 FROM
                     	class cc , study_period sp
                WHERE
	                   cc.studyPeriodId = sp.studyPeriodId
	                    AND
	                     CONCAT_WS(',',cc.batchId,cc.degreeId,cc.branchId) IN
	                     (SELECT
	                          DISTINCT CONCAT_WS(',',c.batchId,c.degreeId,c.branchId)
	                      FROM
	                         student s,class c WHERE c.classId=s.classId AND s.studentId = '$studentId')
                ORDER BY
                   sp.periodValue";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

}     //end of class
?>

