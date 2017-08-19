<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class BookIssueManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BookIssueManager" CLASS
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BookIssueManager" CLASS
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------       
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
    
         
    public function checkBookPackedDispatchedOrNot($classStudentIds,$conditions='') {
     
        $query = "SELECT 
                        DISTINCT studentId 
                  FROM 
                        books_issue
                  WHERE
                       CONCAT(classId,'~',studentId) IN ($classStudentIds)
                       AND status IN (".BOOK_PACKED.",".BOOK_DISPACHED.")
                       $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getStudentList($studentIds,$conditions='') {
     
        $query = "SELECT 
                        DISTINCT studentId,
                        CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,' ')) AS studentName,
                        rollNo
                  FROM 
                        student
                  WHERE
                       studentId IN ($studentIds)
                       $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getMappedBooksInfo($classId,$conditions='') {
     
        $query = "SELECT 
                        DISTINCT bookId,
                        bookNo,
                        noOfBooks
                  FROM 
                        books_master
                  WHERE
                       bookId
                             IN 
                             (
                               SELECT DISTINCT bookId FROM book_class_mapping WHERE classId=$classId
                             )
                       $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function deleteBookIssue($classId) {
     
        $query = "DELETE 
                  FROM 
                        books_issue
                  WHERE 
                   classId=$classId
				   AND status=".BOOK_ISSUED;
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function insertBookIssueData($insertString) {
     
        $query = "INSERT INTO 
                        books_issue(bookId,classId,studentId,status)
                  VALUES $insertString
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function getBookIssueList($classId,$conditions='', $limit = '', $orderBy=' studentName') {
     
            $query = "SELECT 
                            DISTINCT s.studentId,
                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,' ')) AS studentName,
                            s.rollNo,s.universityRollNo,s.universityRegNo,
                            i.status
                      FROM 
                            student_groups sg,
                            student s
                            LEFT JOIN books_issue i ON ( i.studentId=s.studentId AND i.classId=$classId )
                      WHERE
                            s.studentId=sg.studentId
                            AND sg.classId=$classId
                            $conditions
                      GROUP BY s.studentId
                   UNION 
                      SELECT 
                            DISTINCT s.studentId,
                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,' ')) AS studentName,
                            s.rollNo,s.universityRollNo,s.universityRegNo,
                            i.status
                      FROM 
                            student_optional_subject sos,
                            student s
                            LEFT JOIN books_issue i ON ( i.studentId=s.studentId AND i.classId=$classId )
                      WHERE
                            s.studentId=sos.studentId
                            AND sos.classId=$classId
                            $conditions
                      GROUP BY s.studentId
                  ORDER BY $orderBy 
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

}
?>