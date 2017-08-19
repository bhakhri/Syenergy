<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class BookPackManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BookPackManager" CLASS
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BookPackManager" CLASS
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
    
         
    public function checkBookDispatchedOrNot($classStudentIds,$conditions='') {
     
        $query = "SELECT 
                        DISTINCT studentId 
                  FROM 
                        books_issue
                  WHERE
                       CONCAT(classId,'~',studentId) IN ($classStudentIds)
                       AND status IN (".BOOK_DISPACHED.")
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
    
    public function deleteBookPacking($classId,$status) {
     
        $query = "UPDATE
                        books_issue
                  SET 
                        status=$status
                  WHERE
                        classId=$classId
                        AND status !=".BOOK_DISPACHED."
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function doBookPacking($classId,$studentIds,$status) {
     
        $query = "UPDATE
                        books_issue
                  SET 
                        status=$status
                  WHERE
                        classId=$classId
                        AND studentId IN ($studentIds)
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function getBookPackList($classId,$conditions='', $limit = '', $orderBy=' studentName') {
     
            $query = "SELECT 
                            DISTINCT s.studentId,
                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,' ')) AS studentName,
                            s.rollNo,s.universityRollNo,s.universityRegNo,
                            i.status
                      FROM 
                            student_groups sg,
                            student s
                            INNER JOIN books_issue i ON ( i.studentId=s.studentId AND i.classId=$classId AND i.status IN (".BOOK_ISSUED.",".BOOK_PACKED.") AND  i.status NOT IN (".BOOK_DISPACHED.") )
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
                            INNER JOIN books_issue i ON ( i.studentId=s.studentId AND i.classId=$classId AND i.status IN (".BOOK_ISSUED.",".BOOK_PACKED.") AND  i.status NOT IN (".BOOK_DISPACHED.") )
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