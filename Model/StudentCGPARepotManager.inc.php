<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Document" table
// Author :Jaineesh 
// Created on : (28.02.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class StudentCGPARepotManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "TestTypeManager" CLASS
//
// Author :Jaineesh
// Created on : (28.02.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "DocumentManager" CLASS
//
// Author :Jaineesh 
// Created on : (28.02.2008)
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
    
    //----------------------------------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR getting Total classwise GPA
    //
    // Author :Parveen Sharma
    // Created on : (22.12.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------------
      public function getStudentClasswiseGPA($condition='',$orderBy='') {

            if($condition == '') {
               $cond = " WHERE  s1.studentId = s.studentId ";
            }
            else {
               $cond = " WHERE s1.studentId = s.studentId AND ".$condition;
            }
            $query = "SELECT
                            s.classId, className, s1.studentId,
                            IF(s.credits=0,0,(s.gradeIntoCredits/s.credits)) AS gpa, FORMAT(credits,0) AS credits
                      FROM
                            `student` s1, `student_cgpa` s  LEFT JOIN `class` c ON s.classId = c.classId
                      $cond 
                      $orderBy";

            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }
      
//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Total classwise CGPA
// Author :Parveen Sharma
// Created on : (22.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------
      public function getStudentClasswiseCGPA($condition='',$orderBy) {

            if($condition == '') {
               $cond = " WHERE  s1.studentId = s.studentId ";
            }
            else {
               $cond = " WHERE s1.studentId = s.studentId AND ".$condition;
            }

            $query = "SELECT
                            t.classId, t.className, t.studentId,
                            IF(t.credits=0,0,(t.gradeIntoCredits/t.credits)) AS CGPA, FORMAT(t.credits,0) AS credits
                      FROM
                            (SELECT
                                      s.studentId, s.classId, className, SUM(gradeIntoCredits) AS gradeIntoCredits, SUM(credits) AS credits
                             FROM
                                      `student` s1, `student_cgpa` s LEFT JOIN `class` c ON s.classId = c.classId
                             $cond
                             GROUP BY
                                      s.studentId ) AS t
                      $orderBy";

            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }
      
      public function getStudentCGPASGPA($condition='',$orderBy='') {       
           
            $query = "SELECT
                           sc.studentId, sc.classId, sc.cgpa, sc.gpa
                      FROM
                           student_cgpa sc
                      $condition     
                      $orderBy";

            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }
        
}

?>
