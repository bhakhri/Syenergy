<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "fine_student" TABLE
// Author :Rajeev Aggarwal
// Created on : (03.07.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FineManager {
    private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "FineCategoryManager" CLASS
//
// Author :Rajeev Aggarwal
// Created on : (03.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "FineCategoryManager" CLASS
//
// Author :Rajeev Aggarwal
// Created on : (03.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public static function getInstance() {
        if (self::$instance === null) {
            $class = __CLASS__;
            return self::$instance = new $class;
        }
        return self::$instance;
    }



//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Fine Category
// Author :Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function addFineStudent() {
        global $REQUEST_DATA;
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		$remarks = htmlentities(trim($REQUEST_DATA['remarksTxt']));
        $studentId = trim($REQUEST_DATA['studentId']);
        $amount = trim($REQUEST_DATA['amount']);
        $fineCategoryId = trim($REQUEST_DATA['fineCategoryId']);
        $fineDate1 = trim($REQUEST_DATA['fineDate1']);
        $remarks = $remarks;
        $paid = 0;
        $status = 1;
        $userId = trim($userId);
        $instituteId = trim($instituteId);
        $classId = trim($REQUEST_DATA['classId']);
        
        if($studentId == '' && $classId == '' && $fineCategoryId == '' && $userId == '') {
           return false; 
        }  
        
        $query = "INSERT INTO `fine_student` 
                  (studentId,amount,fineCategoryId,fineDate,reason,paid,status,userId,instituteId,classId)
                  VALUES 
                  ('$studentId','$amount','$fineCategoryId','$fineDate1','$remarks','$paid','$status','$userId','$instituteId','$classId')";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);     
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING Bulk Fine
// Author :Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
     public function addBulkFineStudent($filter='',$value='',$condition=''){

        $query = "$filter $value $condition";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//---------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT ROLLNUMBER
// Author :Gurkeerat Sidhu
// Created on : (28.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------
    public function getStudentRoll($groupText = '') {
        $query = "
                    SELECT
                                rollNo
                    FROM        student
                    WHERE       rollNo LIKE '".add_slashes(trim($groupText))."%'
                     ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Fine Category
// $id:fineCategoryId
// Author :Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function editFineStudent($id) {
        global $REQUEST_DATA;

        $remarks = htmlentities(trim($REQUEST_DATA['remarksTxt'])); 
        
        $query = "UPDATE 
                        `fine_student` 
                  SET 
                        amount= '".trim($REQUEST_DATA['amount'])."',
                        fineCategoryId = '".trim($REQUEST_DATA['fineCategoryId'])."',
                        fineDate = '".trim($REQUEST_DATA['fineDate2'])."',
                        reason = '".$remarks."'
                  WHERE
                        fineStudentId = '$id' ";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);    
        
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Student Fine LIST
// $conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (03.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getFineStudent($conditions='') {

        $query = "SELECT
                        IFNULL(fs.fineStudentId,'') AS fineStudentId,
                        fc.fineCategoryId,fc.fineCategoryName,
                        fc.fineCategoryAbbr,fs.studentId,fs.amount,fs.fineDate,
                        fs.reason,fs.paid,fs.status,
                        CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) as fullName,
                        IF(stu.rollNo IS NULL OR stu.rollNo='','".NOT_APPLICABLE_STRING."',stu.rollNo) AS rollNo,
                        cls.className, cls.classId, cls.instituteId
                  FROM
                        fine_category fc, fine_student fs, student stu, class cls
				  WHERE
						fc.fineCategoryId = fs.fineCategoryId AND
						cls.classId = fs.classId AND
						fs.studentId = stu.studentId
                  $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Fine Category
// $fineCategoryId :fineCategoryId of the fine_category
// Author :Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------
    public function deleteFineStudent($fineStudentId) {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "DELETE  FROM fine_student WHERE fineStudentId IN ($fineStudentId) ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);   
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Categories List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getFineStudentList($conditions='', $limit = '', $orderBy=' fineDate') {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        IFNULL(emp.employeeAbbreviation,'Admin') as employeeAbbreviation,
			            IFNULL(emp.employeeName,'Admin') AS issueEmployee,	
			            IFNULL(empApprove.employeeName,'Admin') AS approvedBy,
                        fs.fineStudentId,fc.fineCategoryId,fc.fineCategoryName,
                        fc.fineCategoryAbbr,fs.studentId,fs.amount,
                        fs.fineDate,fs.reason, if(fs.paid=1,'Yes','No') as paid,
                        fs.status, fs.reason, '---' AS paidAmount,
						c.classId as classId,
                        '".NOT_APPLICABLE_STRING."' AS receiptNo,
                        CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) as fullName,
                        IF(IFNULL(stu.rollNo,'')='','".NOT_APPLICABLE_STRING."',stu.rollNo) AS rollNo,
                        IF(IFNULL(stu.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',stu.universityRollNo) AS universityRollNo,
                        c.className, '0' AS paidType
                  FROM
                        class c, fine_category fc,student stu,user u
						LEFT JOIN employee emp ON (emp.userId = u.userId),fine_student fs
						LEFT JOIN employee empApprove ON (fs.approveByUserId=empApprove.userId)
				  WHERE
                        fs.classId = c.classId AND
                        fs.userId = u.userId AND
						fc.fineCategoryId = fs.fineCategoryId AND
						fs.studentId = stu.studentId
						AND fs.status=1
                   $conditions
                  ORDER BY 
						$orderBy
                  $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR Adding Fine
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Rajeev Aggarwal
// Created on : (03.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
	



    public function insertStudentFine() {		
		global $REQUEST_DATA;
		global $sessionHandler;
        $userId = $sessionHandler->getSessionVariable('UserId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		$studentId = $REQUEST_DATA['studentId'];
        $classId = $REQUEST_DATA['studentClass'];
		$chb  = $REQUEST_DATA['chb1'];
		$chb1Value = $REQUEST_DATA['chb1Value'];
		//print_r($chb);die;
		$cnt = count($chb);
		//echo $instituteId;
		if($studentId){

			$insertValue = "";
			for($i=0;$i<$cnt; $i++){

				$totalAmount +=$chb1Value[$chb[$i]];
			}
		    $retStatus = SystemDatabaseManager::getInstance()->runAutoInsert('fine_receipt',
		    array('fineReceiptNo','studentId','totalAmount','receiptDate','userId','fineRemarks','instituteId','classId'),
		    array(trim($REQUEST_DATA['receiptNo']),
		    	  trim($REQUEST_DATA['studentId']),
		    	  trim($totalAmount),
		    	  trim($REQUEST_DATA['receiptDate']),
		    	  trim($userId),
		    	  trim($REQUEST_DATA['printRemarks']),
		    	  trim($instituteId),
		    	  trim($classId)
				 ));
			
			if($retStatus){
				$lastReceipt = SystemDatabaseManager::getInstance()->lastInsertId();
				//echo $lastReceipt;die;
				for($i=0;$i<$cnt; $i++){
					/* update query for paid student fine*/
					SystemDatabaseManager::getInstance()->runAutoUpdate('fine_student',
					array('paid','fineReceiptId','classId'),array(1,$lastReceipt,trim($classId)), "fineStudentId=$chb[$i]");
					/* update query for paid student fine*/
				}

				/* Calculate total no due fine*/
					$query = "SELECT SUM(amount) as totalAmount FROM fine_student WHERE
						studentId = $studentId AND classId = $classId AND paid = 0";
					$amountArray = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
					//if($amountArray[0]['totalAmount']){
						$todayDate = date('Y-m-d');
						$query = "DELETE FROM `no_due` WHERE studentId= $studentId AND classId = $classId AND dueType=2";
						SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
						SystemDatabaseManager::getInstance()->runAutoInsert('no_due',
		                array('instituteId','studentId','classId','amountDue','dueType','onDate'),
		                array(trim($instituteId),trim($studentId),$classId,trim($amountArray[0]['totalAmount']),2,$todayDate));
					//}
				/* Calculate total no due fine*/
				return true.'~'.$lastReceipt;
			}
			else{

				return false;
			}
		}
		else {
			return false;
		}
    }
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT RECEIPT
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getFineReceiptDetail($conditions='') {

        $query = "SELECT
				 fr.receiptDate,
				 stu.rollNo,
				 stu.fatherTitle,
				 stu.fatherName,
				 stu.studentGender,
				 stu.corrAddress1,
				 stu.corrAddress2,
				 stu.corrPinCode,
				 cc.countryName,
				 cs.stateName,
				 ct.cityName,
				 fr.fineReceiptNo,
				 cls.classId,
				 cls.className,
				 stu.firstName,
				 stu.lastName,
				 fr.fineRemarks,
				 fc.fineCategoryAbbr,
				 fs.amount

				 FROM fine_receipt fr, class cls, fine_student fs,fine_category fc,student stu
				 left join countries cc on(stu.corrCountryId = cc.countryId)
				 left join states cs on(stu.corrStateId = cs.stateId)
				 left join city ct on(stu.corrCityId = ct.cityId)
				 WHERE $conditions AND
				 fr.studentId = stu.studentId AND
				 cls.classId = stu.classId AND
				 fs.fineReceiptId = fr.fineReceiptId AND
				 fs.classId = stu.classId AND
				 fs.studentId = stu.studentId AND
				 fs.fineCategoryId = fc.fineCategoryId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING No due for student
// $conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------
    public function getNoDueFineStudent($conditions='') {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT
                        amountDue
                  FROM
                        `no_due`
				  WHERE
						instituteId = $instituteId
						AND dueType = '2'
                        $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING No due for student
// $conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------
    public function updateNoDueFineStudent($totalAmount,$studentId,$classId) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$todayDate = date('Y-m-d');
        SystemDatabaseManager::getInstance()->runAutoUpdate('no_due',
					array('amountDue','onDate'),array($totalAmount,$todayDate), "classId='$classId' AND studentId='".$studentId."' AND dueType=2 AND instituteId=$instituteId");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING No due for student
// $conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------
    public function insertNoDueFineStudent() {

		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$todayDate = date('Y-m-d');
        SystemDatabaseManager::getInstance()->runAutoInsert('no_due',
		    array('instituteId','studentId','classId','amountDue','dueType','onDate'),
		    array(trim($instituteId),trim($REQUEST_DATA['studentId']),trim($REQUEST_DATA['classId']),trim($REQUEST_DATA['amount']),2,$todayDate));
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Fine Cateroies
// $conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------
    public function getTotalFineStudent($conditions='') {

		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        
        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
						class c, fine_category fc, fine_student fs, student stu
				  WHERE
                        fs.classId = c.classId AND
						fc.fineCategoryId = fs.fineCategoryId AND
						fs.studentId = stu.studentId
                  $conditions ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING student detail
//
//$conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getStudentDetail($conditions='') {

      global $REQUEST_DATA;
      $query = "SELECT
                    st.studentId, CONCAT(st.firstName,' ',st.lastName) AS studentName,
                    st.rollNo, st.classId, st.fatherName, cl.instituteId, st.regNo,
                    cl.className
                FROM
                    student st, class cl
                WHERE
                    st.classId=cl.classId
                $conditions ";
                    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT FINE SERIAL NUMBER
//
// Author :Rajeev Aggarwal
// Created on : (03.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getStudentFineSerial() {

         $query = "SELECT 
                        MAX(CAST(fineReceiptNo AS UNSIGNED)) AS maxSerialNo   
                    FROM 
                        `fine_receipt_detail`";        
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



    //************FUNCTION NEEDED FOR ASSIGN ROLE TO FINE CATEGORIES MAPPING************//

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Category LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getMappedFineList($conditions='',$limit='',$orderBy=' roleName') {
        global $sessionHandler;
        $query = "SELECT
                        rf.roleFineId,rf.roleId,                        
	                        
                        GROUP_CONCAT( DISTINCT r.roleName) as roleNames,
                        GROUP_CONCAT( DISTINCT f.fineCategoryAbbr ) AS fineCategoryAbbrs,
                        GROUP_CONCAT( DISTINCT i.instituteAbbr ) AS instituteId, 
                        GROUP_CONCAT( DISTINCT u.userName ) AS userNames
                 FROM
                        role r, role_fine rf, role_fine_category rfc, role_fine_approve rfa, fine_category f, role_fine_institute rfi, `user` u, institute i
                 WHERE
                 		rf.roleId=r.roleId
                        AND rf.roleFineId = rfc.roleFineId
                        AND rf.roleFineId = rfi.roleFineId
                        AND rfc.fineCategoryId = f.fineCategoryId
                        AND rfi.instituteId = i.instituteId
                        AND rf.roleFineId = rfa.roleFineId
                        AND rfa.userId = u.userId
                        GROUP BY rf.roleFineId
                        $conditions
                        ORDER BY $orderBy
                        $limit
                 ";				 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Category LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getTotalMappedFine($conditions='') {
        global $sessionHandler;
        $query = "SELECT
                        GROUP_CONCAT( DISTINCT r.roleName) as roleNames,
                        GROUP_CONCAT( DISTINCT f.fineCategoryAbbr ) AS fineCategoryAbbrs,
                        GROUP_CONCAT( DISTINCT i.instituteAbbr ) AS instituteId,
                        GROUP_CONCAT( DISTINCT u.userName ) AS userNames
                 FROM
                        role r, role_fine rf, role_fine_category rfc, role_fine_approve rfa, role_fine_institute rfi, institute i, fine_category f, `user` u
                 WHERE
                        r.roleId = rf.roleId
                        AND rf.roleFineId = rfc.roleFineId
                        AND rfc.fineCategoryId = f.fineCategoryId
                        AND rfi.instituteId = i.instituteId
                        AND rf.roleFineId = rfa.roleFineId
                        AND rfa.userId = u.userId                        
                        GROUP BY rf.roleFineId
                        $conditions
                 ";           
           
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Category LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getMappedFineCategories($conditions='') {

        $query = "SELECT
                        rf.roleFineId, r.roleId,
                        IFNULL(GROUP_CONCAT( DISTINCT f.fineCategoryId),'') AS fineCategoryId,
                        IFNULL(GROUP_CONCAT( DISTINCT rfi.instituteId),'') AS fineInstituteId,
                        IFNULL(GROUP_CONCAT( DISTINCT rc.classId),'') AS fineClassId,
                        IFNULL(GROUP_CONCAT( DISTINCT u.userName),'') AS userName
                 FROM
                        role r,  role_fine_category rfc, role_fine_approve rfa, 
                        fine_category f, `user` u, role_fine rf 
                        LEFT JOIN role_fine_institute rfi ON  (rf.roleFineId = rfi.roleFineId)   
                        LEFT JOIN role_fine_class rc ON (rc.roleFineId = rfi.roleFineId)  
                 WHERE
                        r.roleId = rf.roleId
                        AND rf.roleFineId = rfc.roleFineId
                        AND rfc.fineCategoryId = f.fineCategoryId
                        AND rf.roleFineId = rfa.roleFineId
                        AND rfa.userId = u.userId
                        $conditions
                 GROUP BY 
                        rf.roleFineId";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Category LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function checkUserNames($conditions='') {

        $query = "SELECT
                        DISTINCT u.userId,
                        u.userName,
                        u.roleId,
                        u.instituteId
                  FROM
                        user u
                        $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Category LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function deletePreviouslyRoletoFineMapping($roleId) {
        global $sessionHandler;
		
        if($roleId=='') {
          $roleId='0';  
        }
        
		//delete from role_fine_institute table
        $query = "DELETE  FROM role_fine_institute WHERE roleFineId IN (SELECT roleFineId FROM role_fine WHERE roleId=$roleId) ";
        $r=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($r===false){
          return false;
        }
		
        $query = "DELETE  FROM role_fine_class WHERE roleFineId IN (SELECT roleFineId FROM role_fine WHERE roleId=$roleId) ";
        $r=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($r===false){
          return false;
        }
        
        
        //delete from role_fine_category table
        $query = "DELETE FROM role_fine_category WHERE roleFineId IN (SELECT roleFineId FROM role_fine WHERE roleId=$roleId)";
        $r1=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($r1===false){
          return false;
        }

        //delete from role_fine_approve table
        $query = "DELETE FROM role_fine_approve WHERE roleFineId IN (SELECT roleFineId FROM role_fine WHERE roleId=$roleId)";
        $r2=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($r2===false){
          return false;
        }

        $query="DELETE FROM role_fine WHERE roleId=$roleId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Category LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function addRoletoFineMapping($roleId) {        
		   $query="INSERT INTO role_fine (roleId) VALUES ($roleId)";
			
		   return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);	
    }

//-------------------------------------------------------

// THIS FUNCTION IS USED FOR GETTING Fine Category LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function addRoletoFineCategoryMapping($str) {
        $query="INSERT INTO  role_fine_category (roleFineId,fineCategoryId) VALUES $str ";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//-------------------------------------------------------

	 public function addInstitute($str) {
        $query="INSERT INTO  role_fine_institute (roleFineId,instituteId) VALUES $str ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    
    public function addFineClass($str) {
        $query="INSERT INTO role_fine_class (roleFineId,classId) VALUES $str ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function addRoletoApproveMapping($str) {
        $query="INSERT INTO  role_fine_approve (roleFineId,userId) VALUES $str ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Category LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function deleteRoletoFineMapping($roleFineId) {
        global $sessionHandler;
        //delete from role_fine_category table
        $query = "DELETE
                  FROM
                        role_fine_category
                  WHERE
                        roleFineId=$roleFineId
                 ";
        $r1=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($r1===false){
            return false;
        }
		
		
		//delete from role_fine_institute table
        $query = "DELETE
                  FROM
                        role_fine_institute
                  WHERE
                        roleFineId=$roleFineId
                 ";
        $r2=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($r2===false){
            return false;
        }
		
		
        //delete from role_fine_approve table
        $query = "DELETE
                  FROM
                        role_fine_approve
                  WHERE
                        roleFineId=$roleFineId
                 ";
        $r3=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($r3===false){
            return false;
        }

        $query="DELETE FROM role_fine WHERE roleFineId=$roleFineId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    //************FUNCTION NEEDED FOR ASSIGN ROLE TO FINE CATEGORIES MAPPING************//


    //************FUNCTION NEEDED FOR  FINE CATEGORIES MASTER MODULE************//


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Fine Category
// Author :Saurabh Thukral
// Created on : (09.08.2012)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function addFineCategory() {
        global $REQUEST_DATA;
        return SystemDatabaseManager::getInstance()->runAutoInsert('fine_category',
          array('fineCategoryName','fineCategoryAbbr','fineType'),
          array(trim($REQUEST_DATA['categoryName']),trim($REQUEST_DATA['categoryAbbr']),trim($REQUEST_DATA['fineType']))
         );
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Fine Category
// $id:fineCategoryId
// Author :Saurabh Thukral
// Created on : (09.08.2012)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function editFineCategory($id) {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoUpdate('fine_category',
            array('fineCategoryName','fineCategoryAbbr','fineType'),
            array(trim($REQUEST_DATA['categoryName']),trim($REQUEST_DATA['categoryAbbr']),trim($REQUEST_DATA['fineType'])),
            "fineCategoryId=$id"
        );
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Fine Category
// $conditions :db clauses
// Author :Saurabh Thukral
// Created on : (09.08.2012)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getFineCategory($conditions='') {

        $query = "SELECT
                        fineCategoryId,fineCategoryName,fineCategoryAbbr,fineType
                  FROM
                        fine_category
                  $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Fine Category
// $fineCategoryId :fineCategoryId of the fine_category
// Author :Saurabh Thukral
// Created on : (09.08.2012)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------
    public function deleteFineCategory($fineCategoryId) {

        $query = "DELETE
                  FROM
                        fine_category
                  WHERE
                        fineCategoryId=$fineCategoryId
                  ";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

 public function countFineType($fineCategoryId) {
		$query =" Select 
							COUNT(*) AS cnt
					from   
							role_fine_category
					where 
							fineCategoryId = $fineCategoryId
				";
				
		 return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
 }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Categories List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Saurabh Thukral
// Created on : (09.08.2012)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getFineCategoriesList($conditions='', $limit = '', $orderBy=' fineCategoryName') {

        $query = "SELECT
                        fineCategoryId,fineCategoryName,fineCategoryAbbr,fineType
                  FROM
                        fine_category
                        $conditions
                  ORDER BY $orderBy
                  $limit
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Fine Cateroies
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------
    public function getTotalFineCategories($conditions='') {

        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
                        fine_category
                        $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Categories List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Jaineesh
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getStudentFineListApproval($conditions,$orderBy) {
		global $sessionHandler;

		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$userId = $sessionHandler->getSessionVariable('UserId');
		$query = "	SELECT
							DISTINCT
							fs.*,
                            IF(IFNULL(c.className,'')='','".NOT_APPLICABLE_STRING."',c.className) AS className,
							fc.fineCategoryName,
							CONCAT(st.firstName,' ',st.lastName) AS studentName,
							IF(IFNULL(st.rollNo,'')='','".NOT_APPLICABLE_STRING."',st.rollNo) AS rollNo,
                            IF(IFNULL(fs.status,'')='','".NOT_APPLICABLE_STRING."',fs.status) AS status,
							IF(IFNULL(fs.statusReason,'')='','".NOT_APPLICABLE_STRING."',fs.statusReason) AS statusReason,
							IF(IFNULL(emp.employeeName,'')='','".NOT_APPLICABLE_STRING."',emp.employeeName) AS employeeName
					FROM
                            class c,
                            time_table_classes ttc,
							fine_student fs,
							student st,
							fine_category fc,
							role_fine_approve rfa,
							user u
					        LEFT JOIN employee emp ON (emp.userId = u.userId)
					WHERE
                            fs.userId = u.userId                            
                            AND fs.classId = c.classId
					        AND	fs.studentId = st.studentId
					        AND	fs.fineCategoryId = fc.fineCategoryId
					        AND	fs.fineCategoryId
					        AND	fs.paid=0
					        IN	(SELECT
								       rfc.fineCategoryId
								 FROM
                                      role_fine_approve rfa, role_fine_category rfc
								 WHERE
								      rfa.roleFineId = rfc.roleFineId
							      )
					$conditions
					ORDER BY $orderBy";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    

    
    




	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Categories List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Jaineesh
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getStudentFineList($conditions,$orderBy) {
		global $sessionHandler;

		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$userId = $sessionHandler->getSessionVariable('UserId');
		$query = "	SELECT
							DISTINCT
							fs.*,
                            IF(IFNULL(c.className,'')='','".NOT_APPLICABLE_STRING."',c.className) AS className,
							fc.fineCategoryName,
							CONCAT(st.firstName,' ',st.lastName) AS studentName,
							IF(IFNULL(st.rollNo,'')='','".NOT_APPLICABLE_STRING."',st.rollNo) AS rollNo,
                            IF(IFNULL(fs.status,'')='','".NOT_APPLICABLE_STRING."',fs.status) AS status,
							IF(IFNULL(fs.statusReason,'')='','".NOT_APPLICABLE_STRING."',fs.statusReason) AS statusReason,
							IF(IFNULL(emp.employeeName,'')='','".NOT_APPLICABLE_STRING."',emp.employeeName) AS employeeName
					FROM
                            class c,
                            time_table_classes ttc,
							fine_student fs,
							student st,
							fine_category fc,
							role_fine_approve rfa,
							user u
					        LEFT JOIN employee emp ON (emp.userId = u.userId)
					WHERE
                            fs.userId = u.userId                            
                            AND fs.classId = c.classId
					        AND	fs.studentId = st.studentId
					        AND	fs.fineCategoryId = fc.fineCategoryId
					        AND	fs.instituteId = $instituteId
					        AND	fs.fineCategoryId
					        IN	(SELECT
								       rfc.fineCategoryId
								 FROM
                                      role_fine_approve rfa, role_fine_category rfc
								 WHERE
								      rfa.roleFineId = rfc.roleFineId
							      )
					$conditions
					ORDER BY $orderBy";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Reason
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Jaineesh
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getReason($conditions) {
		global $sessionHandler;

		$query = "	SELECT
							reason, statusReason
					FROM
							fine_student fs
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR Update fine_student
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Jaineesh
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function updateFine($statusUpdate,$chb,$approveReason) {
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		//$chb  = $REQUEST_DATA['chb'];
		//print_r($chb);

		$insertValue = "";
		foreach ($chb as $studentId) {
			$querySeprator = '';
			if($insertValue!=''){
				$querySeprator = ",";
			}
			$insertValue .= "$querySeprator(".$studentId.")";
		}

     $query = "	UPDATE	fine_student
					SET		status = $statusUpdate,
							approveByUserId = $userId,
							statusReason = '$approveReason'
					WHERE	fineStudentId IN (".$insertValue.")";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }

//-------------------------------------------------------


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR Update Amount in fine_student
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Saurabh Thukral
// Created on : (30.07.2012)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function updateFineAmount($id,$amt) {

      $query = "UPDATE 
     				fine_student
				SET 	
					amount=$amt
				WHERE 
					fineStudentId=$id";
	
	   return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }

//-------------------------------------------------------

// THIS FUNCTION IS USED FOR GETTING Total Fine Collection
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Jaineesh
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getTotalFineCollectionReportDetail($conditions) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "	SELECT
							fs.fineCategoryId,
							fc.fineCategoryName,
							COUNT(*) AS count,
							SUM(fs.amount) AS amount
					FROM
							fine_student fs,
							fine_category fc,
							fine_receipt fr
					WHERE	fs.fineCategoryId = fc.fineCategoryId
					AND		fs.instituteId = $instituteId
					AND		fs.fineReceiptId = fr.fineReceiptId
					AND		paid = 1
							$conditions
							GROUP BY fineCategoryId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


// Author :Jaineesh
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getFineCollectionReportDetail($conditions,$limit,$orderBy) {
		global $sessionHandler;

		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "	SELECT
							fs.fineCategoryId,
							fc.fineCategoryName,
							COUNT(*) AS count,
							SUM(fs.amount) AS amount
					FROM
							fine_student fs,
							fine_category fc,
							fine_receipt fr
					WHERE	fs.fineCategoryId = fc.fineCategoryId
					AND		fs.instituteId = $instituteId
					AND		fs.fineReceiptId = fr.fineReceiptId
					AND		paid = 1
							$conditions
							GROUP BY fineCategoryId
							ORDER BY $orderBy
							$limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Student Wise Total Fine Collection
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Jaineesh
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getTotalStudentWiseFineCollectionReportDetail($conditions) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "	SELECT
							fs.fineCategoryId,
							fc.fineCategoryName,
							CONCAT(st.firstName,' ',st.lastName) AS studentName,
							st.rollNo,
							fs.studentId,
							SUM(fs.amount) AS amount
					FROM
							fine_student fs,
							fine_category fc,
							student st,
							fine_receipt fr
					WHERE	fs.fineCategoryId = fc.fineCategoryId
					AND		fs.instituteId = $instituteId
					AND		fs.studentId = st.studentId
					AND		fr.fineReceiptId = fs.fineReceiptId
					AND		paid = 1
							$conditions
							GROUP BY studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Student Wise Summary Fine List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Jaineesh
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getStudentWiseFineCollectionReportDetail($conditions,$limit,$orderBy) {
		global $sessionHandler;
		
		$query = "SELECT 
		          	fs.studentId, st.rollNo, CONCAT( st.firstName, ' ', st.lastName ) AS studentName, 
		          	SUM( fs.amount ) AS totalFineAmount, c.className, 
		          		(SELECT 
		          			SUM( frd.amount )
		          		 FROM 
		          			fine_receipt_detail frd 
		          		 WHERE 
		          			frd.studentId = st.studentId
						) AS totalFinePaid
					FROM 
						fine_student fs, student st, class c
					WHERE 
						fs.studentId = st.studentId
						AND fs.classId=c.classId		
						$conditions
					GROUP BY studentId
					ORDER BY $orderBy
						$limit";
					//echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getStudentWiseFineCollectionReportDetailNew($condition,$limit,$orderBy) {
		global $sessionHandler;
		
		$query = "SELECT 
		          	fs.studentId, st.rollNo, CONCAT( st.firstName, ' ', st.lastName ) AS studentName, 
		          	SUM( fs.amount ) AS totalFineAmount, c.className, 
		          		(SELECT 
		          			SUM( frd.amount )
		          		 FROM 
		          			fine_receipt_detail frd 
		          		 WHERE 
		          			frd.studentId = st.studentId
						) AS totalFinePaid
					FROM 
						fine_student fs, student st, class c
					WHERE 
						fs.studentId = st.studentId
						AND fs.classId=c.classId		
						$condition
					GROUP BY studentId
					ORDER BY $orderBy
						$limit";
					//echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Student Detail Fine List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Jaineesh
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getTotalStudentDetailFineCollectionReportDetail($conditions) {
		global $sessionHandler;

		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		 $query = "	SELECT
							fs.*,
							fc.fineCategoryName,
							CONCAT(st.firstName,' ',st.lastName) AS studentName,
							st.rollNo,
							fs.status,
							if(emp.employeeName IS NULL OR emp.employeeName='','".NOT_APPLICABLE_STRING."',emp.employeeName) as employeeName
					FROM
							fine_student fs,
							student st,
							fine_category fc,
							fine_receipt fr,
							user u
					LEFT JOIN employee emp ON (emp.userId = u.userId)
					WHERE	fs.userId = u.userId
					AND		fs.studentId = st.studentId
					AND		fs.fineCategoryId = fc.fineCategoryId
					AND		fs.instituteId = $instituteId
					AND		fr.fineReceiptId = fs.fineReceiptId
					AND		fs.paid = 1
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getTotalStudentDetailFineCollectionReportDetailNew($condition) {
		global $sessionHandler;
		 $query = "	SELECT
							fs.*,
							fc.fineCategoryName,
							CONCAT(st.firstName,' ',st.lastName) AS studentName,
							st.rollNo,
							fs.status,
							if(emp.employeeName IS NULL OR emp.employeeName='','".NOT_APPLICABLE_STRING."',emp.employeeName) as employeeName
					FROM
							fine_student fs,
							student st,
							fine_category fc,
							fine_receipt fr,
							user u
					LEFT JOIN employee emp ON (emp.userId = u.userId)
					WHERE	fs.userId = u.userId
					AND		fs.studentId = st.studentId
					AND		fs.fineCategoryId = fc.fineCategoryId
					AND		fr.fineReceiptId = fs.fineReceiptId
					AND		fs.paid = 1
							$condition";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getStudentDetailFineCollectionReportDetailNew($condition,$limit,$orderBy) {
		global $sessionHandler;
		 $query = "	SELECT
							fs.*,
							fc.fineCategoryName,
							CONCAT(st.firstName,' ',st.lastName) AS studentName,
							st.rollNo,
							fs.status,
							fr.receiptDate,
							if(emp.employeeName IS NULL OR emp.employeeName='','".NOT_APPLICABLE_STRING."',emp.employeeName) as employeeName
					FROM
							fine_student fs,
							student st,
							fine_category fc,
							fine_receipt fr,
							user u
					LEFT JOIN employee emp ON (emp.userId = u.userId)
					WHERE	fs.userId = u.userId
					AND		fs.studentId = st.studentId
					AND		fs.fineCategoryId = fc.fineCategoryId
					AND		fr.fineReceiptId = fs.fineReceiptId
					AND		fs.paid = 1
							$condition
							ORDER BY $orderBy

							$limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Student Detail Fine List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Jaineesh
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getStudentDetailFineCollectionReportDetail($conditions,$limit,$orderBy) {
		global $sessionHandler;

		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		 $query = "	SELECT
							fs.*,
							fc.fineCategoryName,
							CONCAT(st.firstName,' ',st.lastName) AS studentName,
							st.rollNo,
							fs.status,
							fr.receiptDate,
							if(emp.employeeName IS NULL OR emp.employeeName='','".NOT_APPLICABLE_STRING."',emp.employeeName) as employeeName
					FROM
							fine_student fs,
							student st,
							fine_category fc,
							fine_receipt fr,
							user u
					LEFT JOIN employee emp ON (emp.userId = u.userId)
					WHERE	fs.userId = u.userId
					AND		fs.studentId = st.studentId
					AND		fs.fineCategoryId = fc.fineCategoryId
					AND		fs.instituteId = $instituteId
					AND		fr.fineReceiptId = fs.fineReceiptId
					AND		fs.paid = 1
							$conditions
							ORDER BY $orderBy
							$limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Categories List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Jaineesh
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getFineCollectionListPrint($conditions,$orderBy) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		$query = "	SELECT
							fs.*,
							fc.fineCategoryName,
							CONCAT(st.firstName,' ',st.lastName) AS studentName,
							st.rollNo,
							fs.status,
							fr.receiptDate,
							if(emp.employeeName IS NULL OR emp.employeeName='','".NOT_APPLICABLE_STRING."',emp.employeeName) as employeeName
					FROM
							fine_student fs,
							student st,
							fine_category fc,
							fine_receipt fr,
							user u
					LEFT JOIN employee emp ON (emp.userId = u.userId)
					WHERE	fs.userId = u.userId
					AND		fs.studentId = st.studentId
					AND		fs.fineCategoryId = fc.fineCategoryId
					AND		fs.fineReceiptId = fr.fineReceiptId
					AND		fs.instituteId = $instituteId
					AND		fs.paid = 1
							$conditions
							ORDER BY $orderBy";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    //************FUNCTION NEEDED FOR  FINE CATEGORIES MASTER MODULE************//

	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENTS FOR PAYMENT HISTORY
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getFineHistoryList($conditions='', $limit = '', $orderBy='') {

		global $sessionHandler;
        $query = "SELECT
				  fr.fineReceiptId,
				  fineReceiptNo,
				  IFNULL(emp.employeeAbbreviation,'Admin') as collectedBy,
				  IFNULL(emp1.employeeAbbreviation,'Admin') as approvedBy,

				  receiptDate,
				  stu.rollNo,
				  stu.regNo,
				  stu.universityRollNo,
				  stu.universityRegNo,
				  CONCAT(stu.firstName,' ',stu.lastName) as fullName,
				  stu.fatherName,
				  stu.fatherTitle,
				  fr.totalAmount

				  FROM student stu, fine_receipt fr,class cls, user u
				  LEFT JOIN employee emp ON (emp.userId = u.userId),fine_student fs
				  LEFT JOIN employee emp1 ON (fs.approveByUserId=emp1.userId)
				  WHERE
				  fr.userId = u.userId AND
				  stu.studentId = fr.studentId AND
				  stu.studentId = fs.studentId AND
				  fr.fineReceiptId = fs.fineReceiptId
				  $conditions AND
				  stu.classId = cls.classId AND

				  fr.instituteId =".$sessionHandler->getSessionVariable('InstituteId');

		$query .=" GROUP BY fineReceiptId  $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//These functions are used to fetch information of students who have not paid fines
    public function getFineHistoryNotPaidList($conditions='', $limit = '', $orderBy='') {

        global $sessionHandler;
        $query = "SELECT
                        fs.fineDate AS receiptDate,
                        stu.rollNo,
                        stu.regNo,
                        stu.universityRollNo,
                        stu.universityRegNo,
                        CONCAT(stu.firstName,' ',stu.lastName) as fullName,
                        stu.fatherName,
                        stu.fatherTitle,
                        fs.amount AS totalAmount,
                        IFNULL(emp1.employeeAbbreviation,'Admin') as approvedBy
                  FROM
                        student stu,
                        class cls,
                        fine_student fs
                        LEFT JOIN employee emp1 ON (fs.approveByUserId=emp1.userId)
                  WHERE
                        stu.studentId = fs.studentId
                        AND stu.classId=cls.classId
                        AND fs.paid=0
                        AND fs.status=1
                        AND fs.instituteId =".$sessionHandler->getSessionVariable('InstituteId')."
                        $conditions
                        $orderBy
                        $limit
                  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getTotalFineHistoryNotPaid($conditions='') {

        global $sessionHandler;
        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
                        student stu,
                        class cls,
                        fine_student fs
                  WHERE
                        stu.studentId = fs.studentId
                        AND stu.classId=cls.classId
                        AND fs.paid=0
                        AND fs.status=1
                        AND fs.instituteId =".$sessionHandler->getSessionVariable('InstituteId')."
                        $conditions
                  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//This will get all the employee names associative with a particular role
   public function getRoleAssociatedUserNames(){
     
     $query="SELECT 
   		         DISTINCT 
                    emp.employeeName, emp.employeeCode, r.roleName,
                    CONCAT(emp.employeeName,' (',emp.employeeCode,')') AS associativeEmployee
             FROM 
		             `user` u, user_role ur, role_fine rf, role r,employee emp 
	         WHERE 
                     ur.roleId = rf.roleId And ur.roleId=r.roleId AND u.userId = ur.userId
		             AND emp.userId=u.userId  ";
                 
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
   
//THIS WILL FETCH THE FINE ABBR. WHICH ARE ASSIGNED RELATED TO THE STUDENT INSTITUTE   
   public function getRoleFineCategoryData($conditions='') {
       $query = "SELECT 
					DISTINCT fc.fineCategoryId, 
					fc.fineCategoryAbbr 
				FROM 
					fine_category fc, role_fine rf, role_fine_category rfc, role_fine_institute rfi ,  student s
				WHERE 
					rfc.fineCategoryId = fc.fineCategoryId 
					AND rfc.roleFineId = rf.roleFineId 
					AND rfi.roleFineId = rf.roleFineId 
					





					$conditions 
						ORDER BY fc.fineCategoryAbbr ASC";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR Adding Fine
// Author :SAURABH THUKRAL
// Created on : (22.08.2012)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

	public function insertFine($totalAmount='0') {		
		
		global $REQUEST_DATA;
		global $sessionHandler;

		$userId = $sessionHandler->getSessionVariable('UserId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

	    $bankScrollNo = add_slashes(trim($REQUEST_DATA['bankScrollNo']));
	    $paymentMode = trim($REQUEST_DATA['paidAt']);
		$studentId=trim($REQUEST_DATA['studentId']);
		$classId = trim($REQUEST_DATA['studentClass']);
		$receiptDate=trim($REQUEST_DATA['receiptDate']);
		$printRemarks=htmlentities(add_slashes(trim($REQUEST_DATA['printRemarks'])));
		$todayDate = date('Y-m-d');	
		$ddType = $REQUEST_DATA['paymentTypeId'];
		$ddNo = $REQUEST_DATA['number'];
		$ddAmount = $REQUEST_DATA['amount'];
		$bankId = $REQUEST_DATA['issuingBankId'];
		$ddDate = $REQUEST_DATA['dated'];

        // Fetch Institute BankAccountNo, Bank Id and Fine MiscReceiptPrefix
		$query = "SELECT s.studentId, s.classId, c.instituteId, br.miscReceiptPrefix FROM `student` s, class c, branch br 
                  WHERE br.branchId = c.branchId AND c.classId = s.classId AND s.studentId = '".$studentId."'";
        $currentArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
		
		$miscReceiptPrefix = trim($currentArray[0]['miscReceiptPrefix']);

        $query = "SELECT 
                        c.param, c.value
                  FROM 
                        `config` c
                  WHERE 
                        param IN ('INSTITUTE_ACCOUNT_NO','INSTITUTE_BANK_NAME') 
                        AND instituteId = '".$currentArray[0]['instituteId']."'";
        $configArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
        

        for($i=0;$i<count($configArray);$i++) {
          if($configArray[$i]['param'] == 'INSTITUTE_ACCOUNT_NO') {
            $instituteBankAccountNo = $configArray[$i]['value'];
          }
          if($configArray[$i]['param'] == 'INSTITUTE_BANK_NAME') {
            $instituteBankId = $configArray[$i]['value'];
          }
        }

		// $paymentMode:  2=> On Desk, 1=> Bank
		// MAX(CAST(fineReceiptNo AS UNSIGNED)) AS maxSerialNo   
		if($paymentMode=='2') {
		  $query = "SELECT 
						MAX(CAST(IF(INSTR(fineReceiptNo,'/')=0,0,TRIM(SUBSTRING_INDEX(fineReceiptNo,'/',-1))) AS UNSIGNED)) AS maxSerialNo   
                    FROM 
                        `fine_receipt_detail`";        
		  $studentFineSerialArr = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

		  $ttFineReceiptNo  = $studentFineSerialArr[0]['maxSerialNo'];
		  if($ttFineReceiptNo!='') {
			$ttFineReceiptNo = $studentFineSerialArr[0]['maxSerialNo']+1;
		  }
		  else {
		    $ttFineReceiptNo = 1;
		  }
		  if(strlen($ttFineReceiptNo)==1) {
			$ttFineReceiptNo = "0000".$ttFineReceiptNo;
		  }
		  else if(strlen($ttFineReceiptNo)==2) {
			$ttFineReceiptNo = "000".$ttFineReceiptNo;
		  }
		  else if(strlen($ttFineReceiptNo)==3) {
			$ttFineReceiptNo = "00".$ttFineReceiptNo;
		  }
		  else if(strlen($ttFineReceiptNo)==4) {
			$ttFineReceiptNo = "0".$ttFineReceiptNo;
		  }
		  else {
			$ttFineReceiptNo = $ttFineReceiptNo;
		  }
		  if($miscReceiptPrefix=='') {
		    $studentFineSerialNo = "CUPB/12-13/MISC/".$ttFineReceiptNo;
		  }
		  else {
		    $studentFineSerialNo = "CUPB/12-13/MISC/$miscReceiptPrefix/".$ttFineReceiptNo;
		  }
		}
		else if ($paymentMode=='1') {
	       $studentFineSerialNo  = $bankScrollNo;
	    }
		$query="INSERT INTO fine_receipt_detail 
		        (fineReceiptNo, receiptDate, studentId, classId, instituteId, amount, userId, entryDate, 
				 isDelete, reason, paidAt, bankId,instituteBankAccountNo) 
				VALUES 
				('$studentFineSerialNo', '$receiptDate', $studentId, $classId, $instituteId, '$totalAmount', $userId, '$todayDate',
				  0,'$printRemarks', '$paymentMode', '$instituteBankId', '$instituteBankAccountNo' )";
		$returnStatus=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($returnStatus===false) {
       	  return false;  
       	}
		if($returnStatus===true) {
		   $lastReceiptId = SystemDatabaseManager::getInstance()->lastInsertId();	

		   for($i=0;$i<count($ddType);$i++){
		      $query="INSERT INTO fine_receipt_instrument 
					  (fineReceiptDetailId, studentId, classId, bankId, ddNo, ddDate, ddAmount, ddType) 
					  VALUES 
	                  ($lastReceiptId,$studentId,$classId,$bankId[$i],'$ddNo[$i]','$ddDate[$i]',$ddAmount[$i],$ddType[$i])";
	  	      $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);				
              if($returnStatus===false) {
       	        return false;  
       	      }
	  	   }  
		}

        return $studentFineSerialNo;
	}
	
	public function studentCountFineList($conditions='') {

		global $sessionHandler;

        $query = "SELECT 
         			    COUNT(*) AS totalRecords
				  FROM 
				        class c, student stu, `fine_receipt_detail` frd
                  WHERE
                        frd.classId = c.classId
	  				    AND frd.studentId = stu.studentId
						AND frd.isDelete = 0
				  $conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function studentCountFineListNew($condition='') {

		global $sessionHandler;

        $query = "SELECT 
         			    COUNT(*) AS totalRecords
				  FROM 
				        class c, student stu, `fine_receipt_detail` frd
                  WHERE
                        frd.classId = c.classId
	  				    AND frd.studentId = stu.studentId
						AND frd.isDelete = 0
				  $condition";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function studentFineListNew($condition='', $limit = '', $orderBy='') {

		global $sessionHandler;
        $query = "SELECT 
				    frd.fineReceiptDetailId,
        			frd.receiptDate AS receiptDate, 
        			frd.fineReceiptNo AS fineReceiptNo, 
        			CONCAT( stu.firstName, ' ', stu.lastName ) AS fullName, 
        			stu.rollNo AS rollNo, 
        			c.className AS className, 
        			(frd.amount - IFNULL(SUM(fri.ddAmount),0)) AS receiveCash, 
        			IFNULL(SUM(fri.ddAmount),0) AS receiveDD, 
        			frd.amount AS totalAmount,
                    IFNULL(CONCAT(emp.employeeName, ' (',emp.employeeCode,')'),'Admin') AS employeeCodeName,
                    IF(frd.paidAt=1,'Bank','On Accounts Desk') AS paidAt
				  FROM 
				    class c, student stu, 
					`fine_receipt_detail` frd 
                     LEFT JOIN `fine_receipt_instrument` fri ON (frd.classId = fri.classId	AND frd.studentId = fri.studentId AND frd.fineReceiptDetailId = fri.fineReceiptDetailId)
                     LEFT JOIN `employee` emp ON (emp.userId = frd.userId)
                  WHERE

                    frd.classId = c.classId

					AND frd.studentId = stu.studentId

					AND frd.isDelete = 0

					$condition 

				  GROUP BY 
					frd.fineReceiptDetailId

			  	  $orderBy 
				  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function studentFineList($conditions='', $limit = '', $orderBy='') {

		global $sessionHandler;
        $query = "SELECT 
				    frd.fineReceiptDetailId,
        			frd.receiptDate AS receiptDate, 
        			frd.fineReceiptNo AS fineReceiptNo, 
        			CONCAT( stu.firstName, ' ', stu.lastName ) AS fullName, 
        			stu.rollNo AS rollNo, 
        			c.className AS className, 
        			(frd.amount - IFNULL(SUM(fri.ddAmount),0)) AS receiveCash, 
        			IFNULL(SUM(fri.ddAmount),0) AS receiveDD, 
        			frd.amount AS totalAmount,
                    IFNULL(CONCAT(emp.employeeName, ' (',emp.employeeCode,')'),'Admin') AS employeeCodeName,
                    IF(frd.paidAt=1,'Bank','On Accounts Desk') AS paidAt
				  FROM 
				    class c, student stu, 
					`fine_receipt_detail` frd 
                     LEFT JOIN `fine_receipt_instrument` fri ON (frd.classId = fri.classId	AND frd.studentId = fri.studentId AND frd.fineReceiptDetailId = fri.fineReceiptDetailId)
                     LEFT JOIN `employee` emp ON (emp.userId = frd.userId)
                  WHERE
                    frd.classId = c.classId
					AND frd.studentId = stu.studentId
					AND frd.isDelete = 0
					$conditions 
				  GROUP BY 
					frd.fineReceiptDetailId
			  	  $orderBy 
				  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getFinePaymentDetails($condition) {   
        
         $todayDate = date('Y-m-d');
         
         $query = "SELECT
				   		IFNULL(frd.receiptDate,'') AS fineDate, fs.fineStudentId, 
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName, 
				   		IFNULL(frd.fineReceiptNo,'') AS receiptNo,  frd.reason AS reason,  
                        '".NOT_APPLICABLE_STRING."' AS fineCategoryAbbr,
                        '".NOT_APPLICABLE_STRING."' AS amount, IFNULL(frd.amount,'') AS paidAmount, 
                        '1' AS paidType
				  FROM
				  		`fine_student` fs, `student` s, `fine_category` fc, `fine_receipt_detail` frd
				  WHERE
				  		fs.fineCategoryId = fc.fineCategoryId
				  		AND s.studentId=fs.studentId
                        AND fs.studentId=frd.studentId
					    AND fs.status=1
                        AND frd.isDelete = 0
                        $condition
                  GROUP BY 
                        frd.fineReceiptNo";     
		
		 
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function fetchClases($condition='') {   
         
         $query = "SELECT 
                        c.classId,c.className, c.isActive, 
                        CONCAT(c.className,' (',IF(c.isActive=1,'Active',IF(c.isActive=2,'Future',IF(c.isActive=3,'Past','Active'))),') - ',i.instituteCode) AS instituteClassName    
                    FROM 
                        class c, institute i 
                    WHERE
                        i.instituteId = c.instituteId 
                        $condition    
                    ORDER BY
                        c.isActive, c.className";
                   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getTotalFineStudentNew($conditions='') {

        global $sessionHandler;
        $userId = $sessionHandler->getSessionVariable('UserId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        
        $query = "SELECT
                       COUNT(*) AS totalRecords
                  FROM
                       student s, class c, fine_category fc, fine_student f, 
                       `user` u LEFT JOIN employee emp ON emp.userId = u.userId
                  WHERE
                       f.studentId = s.studentId AND f.classId = c.classId AND u.userId = f.userId AND
                       f.fineCategoryId = fc.fineCategoryId 
                  $conditions ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    
    public function getFineStudentListNew($conditions='', $limit = '', $orderBy=' fineCategoryName') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $userId = $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        
        $query = "SELECT
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) as studentName,f.paid,
                        IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        c.className, IFNULL(emp.employeeName,'Admin') AS issueEmployee,    
                        fc.fineCategoryId, fc.fineCategoryName, fc.fineCategoryAbbr,
                        f.fineStudentId, f.studentId, f.amount, f.fineDate, f.reason, f.status,f.statusReason
                  FROM
                       student s, class c, fine_category fc, fine_student f,
                       `user` u LEFT JOIN employee emp ON emp.userId = u.userId
                  WHERE
                       f.studentId = s.studentId AND f.classId = c.classId AND u.userId = f.userId AND
                       f.fineCategoryId = fc.fineCategoryId
                  $conditions
                  ORDER BY $orderBy $limit";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    
    public function getSearchFineRole($id='') {
        
         global $sessionHandler;    
         
         $query = "SELECT
                       roleFineId, roleId 
                  FROM
                       role_fine
                  WHERE
                       roleId='$id'";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getSearchFineClass($id='',$condition='') {
        
         global $sessionHandler;    
         
         $query = "SELECT
                       r.roleFineId, r.classId, i.instituteId, CONCAT(c.className,' (',i.instituteCode,')') AS className
                  FROM
                       role_fine_class r, class c, institute i
                  WHERE
					   r.classId = c.classId AND
					   c.instituteId = i.instituteId AND
                       r.roleFineId = '$id'
					   $condition 
				  ORDER BY 
					  i.instituteId, c.className";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getSearchFineCategory($id='') {
        
         global $sessionHandler;    
         
         $query = "SELECT
                       rf.roleFineId, rf.fineCategoryId,
                       fc.fineCategoryName, fc.fineCategoryAbbr, fc.fineType, 
                       CONCAT(fc.fineCategoryAbbr,' (',fc.fineType,')') AS fineCategoryAbbrType
                  FROM
                       role_fine_category rf, fine_category fc
                  WHERE
                       rf.fineCategoryId = fc.fineCategoryId AND 
                       rf.roleFineId = '$id'";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getSearchFineInstitute($id='') {
        
         global $sessionHandler;    
         
         $query = "SELECT
                       r.roleFineId, r.instituteId, i.instituteCode
                  FROM
                       role_fine_institute r, institute i
                  WHERE
					   r.instituteId = i.instituteId AND	
                       r.roleFineId = '$id' ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getSearchFineApprove($id='') {
        
         global $sessionHandler;    
         
         $query = "SELECT
                       roleFineId, userId 
                  FROM
                       role_fine_approve
                  WHERE
                       roleFineId = '$id' ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
 
  public function checkFineStudent($studentId='',$fineCategoryId='',$fineDate1=''){

		$query = "SELECT 
						*
			  FROM
				fine_student 
			  WHERE
				studentId=$studentId
				AND fineCategoryId=$fineCategoryId
				AND fineDate='$fineDate1'
				";	

	 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");


}

public function getFineStudentNewList($condition='', $limit = '', $orderBy=' fineCategoryName') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $userId = $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        
        $query = "SELECT
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) as studentName,
                        IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        c.className, IFNULL(emp.employeeName,'Admin') AS issueEmployee,    
                        fc.fineCategoryId, fc.fineCategoryName, fc.fineCategoryAbbr,
                        f.fineStudentId, f.studentId, f.amount, f.fineDate, f.reason, f.status
                  FROM
                       student s, class c, fine_category fc, fine_student f,
                       `user` u LEFT JOIN employee emp ON emp.userId = u.userId
                  WHERE
                       f.studentId = s.studentId AND f.classId = c.classId AND u.userId = f.userId AND
                       f.fineCategoryId = fc.fineCategoryId
                  $condition
                  ORDER BY $orderBy $limit";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	 public function getTotalStudent($conditions='') {
  	   
	   global $sessionHandler;

       $query = "SELECT
					   COUNT(DISTINCT s.studentId) AS totalRecords
				  FROM 
					   class c, student s
				  WHERE 
					   c.classId = s.classId	
				  $conditions";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getStudentList($conditions='', $limit = '', $orderBy='rollNo') {
      
		global $sessionHandler;

        $query = "SELECT
                      DISTINCT s.studentId, s.classId,
					  s.rollNo, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                      c.className, IFNULL(s.fatherName,'') AS fatherName, IFNULL(s.universityRollNo,'') AS universityRollNo,
					  IFNULL(s.studentMobileNo,'') AS studentMobileNo, IFNULL(s.studentPhoto,'') AS studentPhoto
                  FROM 
					  class c, student s
				  WHERE 
					  c.classId = s.classId	
					  $conditions
                  ORDER BY 
				 	  $orderBy 
				  $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

     public function checkStudentInstitute($classId=''){

		$query = "SELECT 
					  classId, instituteId
				  FROM
					  `class` 
				   WHERE	  
					  classId = '$classId' ";	

	   return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	} 

	public function getReceiptDetails($receiptNo,$condition=''){
		
		$query = "SELECT 
						DISTINCT frd.studentId, frd.classId, frd.receiptDate, DATE_FORMAT(frd.receiptDate,'%d-%b-%y') AS dated1,
						frd.fineReceiptDetailId, IFNULL(frd.bankId,'') AS bankId, 
						IFNULL(frd.instituteBankAccountNo,'') AS instituteBankAccountNo, IFNULL(frd.paidAt,'') AS paidAt,
						IFNULL(frd.amount,'') AS amount, 
						sp.periodName AS feeStudyPeriodName, b.batchYear, br.branchName,d.degreeAbbr,
						CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,s.regNo,
						d.degreeName,s.fatherName,bk.bankAbbr, br.branchCode,
						s.rollNo,frd.fineReceiptNo,d.degreeAbbr,c.className, 
						IFNULL(s.studentGender,'M') AS studentGender, c.instituteId, s.rollNo,
						ins.instituteName, ins.instituteCode, ins.instituteAbbr, ins.instituteLogo
				  FROM    
						`study_period` sp ,`degree` d,`batch` b,`branch` br,`student` s,`class` c, institute ins,
						`fine_receipt_detail` frd LEFT JOIN `bank` bk ON (bk.bankId = frd.bankId)
				  WHERE    
						s.studentId = frd.studentId
						AND frd.classId = c.classId
						AND ins.instituteId = c.instituteId
						AND sp.studyPeriodId = c.studyPeriodId
						AND c.batchId = b.batchId
						AND c.degreeId = d.degreeId
						AND c.branchId = br.branchId
						AND frd.isDelete = 0
						AND frd.fineReceiptNo = '$receiptNo'
  		          $condition ";
				
	   return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");			
	}

	public function getPaymentDetails($receiptNo,$condition=''){

		$query = "SELECT
                    	frd.fineReceiptNo, frd.receiptDate AS dated, DATE_FORMAT(frd.receiptDate,'%d-%b-%y') AS dated1,
                        IFNULL(frd.amount,0) AS amountPaid
					FROM 
						`fine_receipt_detail` frd 
					WHERE 
						 frd.fineReceiptNo = '$receiptNo'
						 AND frd.isDelete = 0
					$condition ";
					
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	public function getFineReceiptInstrument($condition=''){
	
		$query = "SELECT
                   	  fri.fineReceiptInstrumentId, fri.fineReceiptDetailId, fri.studentId, fri.classId, 
					  fri.bankId, fri.ddNo, fri.ddDate, fri.ddAmount, fri.ddType,
					  bk.bankName, bk.bankAbbr, DATE_FORMAT(fri.ddDate,'%d-%b-%y') AS dated1
	 	 		  FROM 
 					  fine_receipt_instrument fri, bank bk
				  WHERE 
				      bk.bankId = fri.bankId 
				  $condition ";
					
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
	public function getStudentFineHead($condition='') {   
        
         $query = "SELECT
					   fs.studentId, fc.fineType, IFNULL(SUM(fs.amount),0) AS fineAmount
			 	   FROM
					  fine_student fs, fine_category fc 
			  	   WHERE
			  		  fs.status = 1 AND
			  		  fc.fineCategoryId = fs.fineCategoryId
					  AND fc.fineType IN ('Fine','Activity')
					  $condition
				   GROUP BY
					  fs.studentId, fc.fineType
				   ORDER BY	  
				      fs.studentId, fc.fineType";
                        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getStudentFineHeadPay($condition='') {   
        
         $query = "SELECT
					  IFNULL(SUM(fsd.amount),0) AS paidAmount
			 	   FROM
					  fine_receipt_detail fsd 
			  	   WHERE
			  		  fsd.isDelete = 0 
					  $condition
				   GROUP BY
					  fsd.studentId";
                        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	 public function getLastEntry() {
    
        $query = "SELECT 
                           fineReceiptDetailId, fineReceiptNo, receiptDate, 
						   date_format(receiptDate, '%Y-%m-%d' ) AS receiptDated
                  FROM       
                           fine_receipt_detail
                  WHERE 
                           isDelete = 0         
                  ORDER BY
                           fineReceiptDetailId 	DESC    
                  LIMIT 0,1"; 
                        
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
//  This function is used for Logical Delete of feeReceiptDetails data
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	public function deleteFromReceiptDetails($fineReceiptDetailId,$reason){

		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		
		$query ="UPDATE 
					fine_receipt_detail 
				 SET 
					isDelete = 1,
					reasonDelete = '$reason',
					userId = '$userId'
				WHERE
					fineReceiptDetailId = '$fineReceiptDetailId'
					AND	isDelete = 0 ";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
}
?>
