<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class BookDispatchManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BookDispatchManager" CLASS
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BookDispatchManager" CLASS
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------       
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
    
    
    public function deleteBookDispatch($classId,$status) {
     
        $query = "UPDATE
                        books_issue
                  SET 
                        status=$status
                  WHERE
                        classId=$classId
                        AND status =".BOOK_DISPACHED."
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function doBookDispatch($classId,$studentIds,$status) {
     
        $query = "UPDATE
                        books_issue
                  SET 
                        status=$status
                  WHERE
                        classId=$classId
                        AND studentId IN ($studentIds)
                        AND status =".BOOK_PACKED."
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function getBookDispatchList($classId,$conditions='', $limit = '', $orderBy=' studentName') {
     
            $query = "SELECT 
                            DISTINCT s.studentId,
                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,' ')) AS studentName,
                            s.rollNo,s.universityRollNo,s.universityRegNo,
                            i.status
                      FROM 
                            student_groups sg,
                            student s
                            INNER JOIN books_issue i ON ( i.studentId=s.studentId AND i.classId=$classId AND i.status IN (".BOOK_PACKED.",".BOOK_DISPACHED.") )
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
                            INNER JOIN books_issue i ON ( i.studentId=s.studentId AND i.classId=$classId AND i.status IN (".BOOK_PACKED.",".BOOK_DISPACHED.") )
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