<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "fine_student" TABLE
// Author :Rajeev Aggarwal
// Created on : (03.07.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FineCancelledReportManager {
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
    

	
	public function studentCountFineList($conditions='') {

		global $sessionHandler;

        $query = "SELECT 
         			    COUNT(*) AS totalRecords
				  FROM 
				        class c, student stu, `fine_receipt_detail` frd
                  WHERE
                        frd.classId = c.classId
	  				    AND frd.studentId = stu.studentId
						AND frd.isDelete = 1
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
						AND frd.isDelete = 1
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
        			IFNULL(SUM(fri.ddAmount),0) AS receiveDD,IFNULL(usr.userName,'') AS userName, 
        			frd.amount AS totalAmount,frd.reasonDelete ,
                    IFNULL(CONCAT(emp.employeeName, ' (',emp.employeeCode,')'),'Admin') AS employeeCodeName,
                    IF(frd.paidAt=1,'Bank','On Accounts Desk') AS paidAt
 		FROM 
		        class c, student stu,
			`fine_receipt_detail` frd  LEFT JOIN `user` usr ON usr.userId = frd.userId
                    LEFT JOIN employee emp ON usr.userId = emp.userId LEFT JOIN `fine_receipt_instrument` fri ON 
			 (frd.classId = fri.classId	AND frd.studentId = fri.studentId AND frd.fineReceiptDetailId = fri.fineReceiptDetailId) 
                  WHERE
				

            			frd.classId = c.classId
				AND frd.studentId = stu.studentId
				AND frd.isDelete = 1
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
        			IFNULL(SUM(fri.ddAmount),0) AS receiveDD,IFNULL(usr.userName,'') AS userName, 
        			frd.amount AS totalAmount,frd.reasonDelete ,
                    IFNULL(CONCAT(emp.employeeName, ' (',emp.employeeCode,')'),'Admin') AS employeeCodeName,
                    IF(frd.paidAt=1,'Bank','On Accounts Desk') AS paidAt
 		FROM 
		        class c, student stu,
			`fine_receipt_detail` frd  LEFT JOIN `user` usr ON usr.userId = frd.userId
                    LEFT JOIN employee emp ON usr.userId = emp.userId LEFT JOIN `fine_receipt_instrument` fri ON 
			 (frd.classId = fri.classId	AND frd.studentId = fri.studentId AND frd.fineReceiptDetailId = fri.fineReceiptDetailId) 
                  WHERE
				
            			frd.classId = c.classId
				AND frd.studentId = stu.studentId
				AND frd.isDelete = 1
				$conditions 
		 GROUP BY 
				frd.fineReceiptDetailId
			  	  $orderBy 
				  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	
}
?>
