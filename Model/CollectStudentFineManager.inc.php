<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "fine_student" TABLE
// Author :Rajeev Aggarwal
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class CollectStudentFineManager {
    private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "FineCategoryManager" CLASS
//
// Author :Rajeev Aggarwal
// Created on : (03.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "FineCategoryManager" CLASS
//
// Author :Rajeev Aggarwal
// Created on : (03.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function addFineStudent() {
        global $REQUEST_DATA;
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		$remarks = htmlentities(trim($REQUEST_DATA['remarksTxt']));

        return SystemDatabaseManager::getInstance()->runAutoInsert('fine_student',
          array('studentId','amount','fineCategoryId','fineDate','reason','noDues','paid','status','userId','instituteId','classId'),
          array(trim($REQUEST_DATA['studentId']),
			trim($REQUEST_DATA['amount']),
			trim($REQUEST_DATA['fineCategoryId']),
			trim($REQUEST_DATA['fineDate1']),
			$remarks,
			trim($REQUEST_DATA['dueStatus']),0,2,trim($userId),
			trim($instituteId),
			trim($REQUEST_DATA['classId']))
         );
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING Bulk Fine
// Author :Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
     public function addBulkFineStudent($filter='',$value='',$condition=''){

        $query = "$filter $value $condition";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//---------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT ROLLNUMBER
// Author :Gurkeerat Sidhu
// Created on : (28.10.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function editFineStudent($id) {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoUpdate('fine_student',
            array('amount','fineCategoryId','fineDate','reason','noDues'),
            array(trim($REQUEST_DATA['amount']),trim($REQUEST_DATA['fineCategoryId']),trim($REQUEST_DATA['fineDate2']),trim($REQUEST_DATA['remarksTxt']),trim($REQUEST_DATA['dueStatus'])),
            "fineStudentId=$id");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Student Fine LIST
// $conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (03.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getFineStudent($conditions='') {

        $query = "SELECT
                        fs.fineStudentId,fc.fineCategoryId,fc.fineCategoryName,
                        fc.fineCategoryAbbr,fs.studentId,fs.amount,fs.fineDate,
                        fs.reason,fs.noDues ,fs.paid,fs.status,
                        CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) as fullName,
                        IF(stu.rollNo IS NULL OR stu.rollNo='','".NOT_APPLICABLE_STRING."',stu.rollNo) AS rollNo,
                        cls.className, cls.classId, cls.instituteId
                  FROM
                        fine_category fc,fine_student fs,student stu,class cls
				  WHERE
						fc.fineCategoryId = fs.fineCategoryId AND
						cls.classId = stu.classId AND
						fs.studentId = stu.studentId

                  $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Fine Category
// $fineCategoryId :fineCategoryId of the fine_category
// Author :Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------
    public function deleteFineStudent($fineStudentId) {

        $query = "DELETE
                  FROM
                        fine_student
                  WHERE
                        fineStudentId=$fineStudentId
                  ";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
		//return true;
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Categories List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getFineStudentList($conditions='', $limit = '', $orderBy=' fineCategoryName') {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        IFNULL(emp.employeeAbbreviation,'Admin') as employeeAbbreviation,
			IFNULL(emp.employeeName,'Admin') AS issueEmployee,	
			IFNULL(empApprove.employeeName,'Admin') AS approvedBy,
                        fs.fineStudentId,fc.fineCategoryId,fc.fineCategoryName,
                        fc.fineCategoryAbbr,fs.studentId,fs.amount,
                        fs.fineDate,fs.reason,if(fs.noDues=1,'Yes','No') as noDues ,if(fs.paid=1,'Yes','No') as paid,
                        fs.status, fs.reason,
						c.classId as classId,
                        CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) as fullName,
                        IF(IFNULL(stu.rollNo,'')='','".NOT_APPLICABLE_STRING."',stu.rollNo) AS rollNo,
                        IF(IFNULL(stu.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',stu.universityRollNo) AS universityRollNo,
                        c.className
                  FROM
                        class c, fine_category fc,student stu,user u
			LEFT JOIN employee emp ON (emp.userId = u.userId),fine_student fs
			LEFT JOIN employee empApprove ON (fs.approveByUserId=empApprove.userId)
		 WHERE
                        fs.classId = c.classId AND
                        fs.userId = u.userId AND
			fc.fineCategoryId = fs.fineCategoryId AND
			fs.studentId = stu.studentId AND
			fs.instituteId = $instituteId
                        $conditions
                  ORDER BY $orderBy
                  $limit
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR Adding Fine
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Rajeev Aggarwal
// Created on : (03.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
		$cnt = count($chb);

		if($studentId){

			$insertValue = "";
			for($i=0;$i<$cnt; $i++){

				$totalAmount +=$chb1Value[$chb[$i]];
			}
			//echo $totalAmount;
			//die();
			//$retStatus =1;
		    $retStatus = SystemDatabaseManager::getInstance()->runAutoInsert('fine_receipt',
		    array('fineReceiptNo','studentId','totalAmount','receiptDate','userId','fineRemarks','instituteId','classId'),
		    array(trim($REQUEST_DATA['receiptNo']),trim($REQUEST_DATA['studentId']),trim($totalAmount),trim($REQUEST_DATA['receiptDate']),trim($userId),trim($REQUEST_DATA['printRemarks']),trim($instituteId),trim($classId)));
			if($retStatus){

				$lastReceipt = SystemDatabaseManager::getInstance()->lastInsertId();
				for($i=0;$i<$cnt; $i++){

					/* update query for paid student fine*/
					SystemDatabaseManager::getInstance()->runAutoUpdate('fine_student',
					array('paid','fineReceiptId','classId'),array(1,$lastReceipt,trim($classId)), "classId = $classId AND fineStudentId=$chb[$i]");
					/* update query for paid student fine*/
				}

				/* Calculate total no due fine*/
					$query = "SELECT SUM(amount) as totalAmount FROM fine_student WHERE
						studentId = $studentId AND classId = $classId AND noDues = 1 AND paid = 0";

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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------
    public function getTotalFineStudent($conditions='') {

		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
						class c, fine_category fc,fine_student fs,student stu
				  WHERE
                        fs.classId = c.classId AND
						fc.fineCategoryId = fs.fineCategoryId AND
						fs.studentId = stu.studentId AND
						fs.instituteId = $instituteId
                        $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING student detail
//
//$conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getStudentDetail($conditions='') {

       global $REQUEST_DATA;
       $query = "SELECT
                     st.studentId, CONCAT(st.firstName,' ',st.lastName) AS studentName,
                     st.rollNo, st.classId, cl.instituteId, st.fatherName, st.regNo, className
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getStudentFineSerial() {

         $query = "SELECT MAX(fineReceiptNo) as maxSerialNo FROM `fine_receipt`";
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



    //************FUNCTION NEEDED FOR ASSIGN ROLE TO FINE CATEGORIES MAPPING************//

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Category LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getMappedFineCategories($conditions='') {

        $query = "SELECT
                        rf.roleFineId,                       
                        r.roleId,
                        GROUP_CONCAT( DISTINCT f.fineCategoryId ) AS fineCategoryId,
                        GROUP_CONCAT( DISTINCT i.instituteId ) AS fineInstituteId,
                        GROUP_CONCAT( DISTINCT u.userName) AS userName
                 FROM
                        role r, role_fine rf, role_fine_category rfc, role_fine_institute rfi, role_fine_approve rfa, fine_category f, `user` u, institute i
                 WHERE
                        r.roleId = rf.roleId
                        AND rf.roleFineId = rfc.roleFineId
                        AND rf.roleFineId = rfi.roleFineId
                        AND rfi.instituteId = i.instituteId
                        AND rfc.fineCategoryId = f.fineCategoryId
                        AND rf.roleFineId = rfa.roleFineId
                        AND rfa.userId = u.userId
                        $conditions
                        GROUP BY rf.roleFineId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Fine Category LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function deletePreviouslyRoletoFineMapping($roleId) {
        global $sessionHandler;
		
		//delete from role_fine_institute table
        $query = "DELETE
                  FROM
                        role_fine_institute
                  WHERE
                        roleFineId
                         IN
                          (
                            SELECT
                                  roleFineId
                            FROM
                                  role_fine
                            WHERE
                                  roleId=$roleId                                  
                           )
                 ";
        $r=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($r===false){
            return false;
        }
		
        //delete from role_fine_category table
        $query = "DELETE
                  FROM
                        role_fine_category
                  WHERE
                        roleFineId
                         IN
                          (
                            SELECT
                                  roleFineId
                            FROM
                                  role_fine
                            WHERE
                                  roleId=$roleId                                  
                           )
                 ";
        $r1=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($r1===false){
            return false;
        }

        //delete from role_fine_approve table
        $query = "DELETE
                  FROM
                        role_fine_approve
                  WHERE
                        roleFineId
                         IN
                          (
                            SELECT
                                  roleFineId
                            FROM
                                  role_fine
                            WHERE
                                  roleId=$roleGRADE_INFOId
                                  
                           )
                 ";
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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



// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function addFineCategory() {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoInsert('fine_category',
          array('fineCategoryName','fineCategoryAbbr'),
          array(trim($REQUEST_DATA['categoryName']),trim($REQUEST_DATA['categoryAbbr']))
         );
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Fine Category
// $id:fineCategoryId
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function editFineCategory($id) {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoUpdate('fine_category',
            array('fineCategoryName','fineCategoryAbbr'),
            array(trim($REQUEST_DATA['categoryName']),trim($REQUEST_DATA['categoryAbbr'])),
            "fineCategoryId=$id"
        );
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Fine Category
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getFineCategory($conditions='') {

        $query = "SELECT
                        fineCategoryId,fineCategoryName,fineCategoryAbbr
                  FROM
                        fine_category
                  $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Fine Category
// $fineCategoryId :fineCategoryId of the fine_category
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Author :Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getFineCategoriesList($conditions='', $limit = '', $orderBy=' fineCategoryName') {

        $query = "SELECT
                        fineCategoryId,fineCategoryName,fineCategoryAbbr
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getStudentFineList($conditions,$orderBy) {

		$query = "SELECT
					CONCAT(s.firstName,' ',s.lastName ) AS studentName, fs.fineDate, fs.reason, fs.amount, fc.fineCategoryName,'---' AS paidAmount,'---' AS balance
				  FROM
				  	`fine_student` fs, `student` s, `fine_category` fc
				  WHERE
				  	fs.fineCategoryId = fc.fineCategoryId
				  	AND s.studentId=fs.studentId
                    AND fs.status = 1
				  	$conditions
				  	ORDER BY $orderBy";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    
    public function getStudentFine($conditions) {

		$query = "SELECT
					    DISTINCT GROUP_CONCAT(DISTINCT fc.fineType) AS fineCategoryName,
					    SUM(fs.amount) AS amount
				  FROM
				  	    `fine_student` fs, `student` s, `fine_category` fc
				  WHERE
				  	    fs.fineCategoryId = fc.fineCategoryId AND
                        fs.status = 1 AND
				        $conditions
				  GROUP BY 
                        fc.fineType";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getStudentTotalFine($rollNo1) {

		$query = "SELECT  
				  	SUM(amount) as totalAmount 
				  FROM 
				  	fine_student fs, student s 
				  WHERE
					s.studentId = '$rollNo1' 
					AND fs.studentId=s.studentId 
					AND fs.paid = 0 
					AND fs.status=1
				  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

     public function getTotalStudentFineList($conditions) {

		$query = "SELECT
					COUNT(*) AS count
				  FROM
				  	`fine_student` fs
				  WHERE					
					$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Reason
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Jaineesh
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function updateFineAmount($id,$amt) {

     $query = "	UPDATE 
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getStudentWiseFineCollectionReportDetail($conditions,$limit,$orderBy) {
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
							GROUP BY studentId
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Student Detail Fine List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Jaineesh
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
   		  DISTINCT emp.employeeName,
		 r.roleName
	   FROM 
		user u, role_fine rf,role r,employee emp
	  WHERE 
		u.roleId = rf.roleId And u.roleId=r.roleId 
		AND emp.userId=u.userId";
   return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
   public function getRoleFineCategoryData($conditions='') {
       $query = "SELECT 
					fc.fineCategoryId, 
					fc.fineCategoryAbbr 
				FROM 
					fine_category fc, role_fine rf, role_fine_category rfc, role_fine_institute rfi ,  student s
				WHERE 
					rfc.fineCategoryId = fc.fineCategoryId 
					AND rfc.roleFineId = rf.roleFineId 
					AND rfi.roleFineId = rf.roleFineId 
					
					$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getBankAccountNoList($instituteId='') {   
        
         $query = "SELECT 
                        c.param, c.value, IFNULL(bk.bankAbbr,'') AS bankAbbr
                   FROM 
                        `config` c LEFT JOIN bank bk ON c.value = bk.bankId
                   WHERE 
                        c.param IN ('INSTITUTE_ACCOUNT_NO','INSTITUTE_BANK_NAME') 
                        AND c.instituteId = '".$instituteId."'";
                        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
	public function getFinePaymentDetails($condition,$orderBy) {   
         $todayDate = date('Y-m-d');
         $span1 = "'<span class=redColor>',";
         $span2 = ",'</span>'";

         $fieldName1 = "frd.fineReceiptNo";
         
         $queryStr = "SELECT
				   		frd.receiptDate AS fineDate, CONCAT(s.firstName,' ',s.lastName ) AS studentName, 
				   		<fineReceiptNo> AS fineCategoryName,
				   		frd.reason AS reason,  '---' AS amount, frd.amount AS paidAmount, 
				   		(SUM(fs.amount)-SUM(frd.amount)) AS balance
				  FROM
				  		`fine_student` fs, `student` s, `fine_category` fc, `fine_receipt_detail` frd
				  WHERE
				  		fs.fineCategoryId = fc.fineCategoryId
				  		AND s.studentId=fs.studentId
                        AND frd.isDelete = 0
                        AND fs.status = 1
                        AND fs.studentId=frd.studentId
                        $condition
                      GROUP BY frd.fineReceiptNo
                      ORDER BY $orderBy";     
		
		 $query = $queryStr;			  
		 $query = str_replace("<fineReceiptNo>","CONCAT(".$span1.$fieldName1.$span2.")",$query);	
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
	public function getStudentFineTotal($condition='') {   
        
         $query = "SELECT 
         		   		SUM(frd.amount) AS totalPaidAmount
				   FROM 
				   		`fine_receipt_detail` frd
				   WHERE 
				   		$condition";
                        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}
