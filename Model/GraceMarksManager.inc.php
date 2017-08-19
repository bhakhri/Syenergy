<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Grace Marks" table
// Author :Jaineesh 
// Created on : (28.02.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class GraceMarksManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "TestTypeManager" CLASS
//
// Author :Jaineesh
// Created on : (28.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "DocumentManager" CLASS
//
// Author :Jaineesh 
// Created on : (28.02.2008)
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
    
    
     //used to delete old records
    public function deleteGraceMarks($studentId,$classId,$subjectId){
        $query="DELETE from ".TEST_GRACE_MARKS_TABLE." WHERE studentId='".$studentId."' AND classId='".$classId."' AND subjectId='".$subjectId."'";
        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    //used to insert new records
    public function insertGraceMarks($studentId,$classId,$subjectId,$graceMarks,$int,$ext,$tot){
        $query="INSERT INTO ".TEST_GRACE_MARKS_TABLE." (studentId,classId,subjectId,graceMarks,internalGraceMarks,externalGraceMarks,totalGraceMarks) 
                VALUES($studentId,$classId,$subjectId,$graceMarks,$int,$ext,$tot)";
        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    //this function will check whether a group is optional group or not
    public function checkOptionalGroup($groupId){
        $query="SELECT
                      g.groupId,g.isOptional
                FROM
                      `group` g
                WHERE
                       g.groupId=$groupId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
          
    public function getGraceMaxMarks($conditions='', $orderBy = ' studentName'){

           global $sessionHandler;
           global $REQUEST_DATA;

           $group=((trim($REQUEST_DATA['group'])!=""?trim($REQUEST_DATA['group']):0));
           $optionalGroup=0;
           if($group!=0){
             //check whether it is optional group or not
             $optGrArray=$this->checkOptionalGroup($group);
             if(count($optGrArray)>0 and is_array($optGrArray)){
                 $optionalGroup=$optGrArray[0]['isOptional'];
             }
             else{
                 $optionalGroup=0;
             }
           }
           

        if($optionalGroup==0){//if this group is not optional group
          $query="SELECT
                        IFNULL(SUM(ttm.maxMarks),0) AS maxMarks, '1' AS 'mksType'
                  FROM
                        student_groups sg, ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,student s
                  WHERE     
                        sg.studentId = ttm.studentId
                        AND sg.studentId = s.studentId
                        AND sg.classId = ttm.classId
                        AND ttm.conductingAuthority IN (1,3)
                        $conditions
                  GROUP BY     
                        ttm.studentId, sg.groupId
                  UNION
                  SELECT
                        IFNULL(SUM(ttm.maxMarks),0) AS maxMarks, '2' AS 'mksType'
                  FROM
                        student_groups sg, ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,student s
                  WHERE 
                        sg.studentId = ttm.studentId
                        AND sg.studentId = s.studentId
                        AND sg.classId = ttm.classId
                        AND ttm.conductingAuthority IN (2)
                        $conditions
                  GROUP BY     
                        ttm.studentId, sg.groupId ";
                        
        }
        else{
          $query="SELECT
                        IFNULL(SUM(ttm.maxMarks),0) AS maxMarks, '1' AS 'mksType'
                  FROM
                        student_optional_subject sg, ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,student s
                  WHERE 
                        sg.studentId = ttm.studentId
                        AND sg.studentId = s.studentId
                        AND sg.classId = ttm.classId
                        AND ttm.conductingAuthority IN (1,3)
                        $conditions
                  GROUP BY     
                        ttm.studentId, sg.groupId
                  UNION
                  SELECT
                        IFNULL(SUM(ttm.maxMarks),0) AS maxMarks, '2' AS 'mksType'
                  FROM
                        student_optional_subject sg, ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,student s
                  WHERE 
                        sg.studentId = ttm.studentId
                        AND sg.studentId = s.studentId
                        AND sg.classId = ttm.classId
                        AND ttm.conductingAuthority IN (2)
                        $conditions
                  GROUP BY     
                        ttm.studentId, sg.groupId";
        }
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    public function getGraceMarksList($conditions='', $orderBy = ' studentName'){

           global $sessionHandler;
           global $REQUEST_DATA;

           $group=((trim($REQUEST_DATA['group'])!=""?trim($REQUEST_DATA['group']):0));
           $optionalGroup=0;
           if($group!=0){
             //check whether it is optional group or not
             $optGrArray=$this->checkOptionalGroup($group);
             if(count($optGrArray)>0 and is_array($optGrArray)){
                 $optionalGroup=$optGrArray[0]['isOptional'];
             }
             else{
                 $optionalGroup=0;
             }
           }
           

        if($optionalGroup==0){//if this group is not optional group
          $query="SELECT
                         DISTINCT s.studentId,
                         CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                         IFNULL(s.rollNo,'---') AS rollNo,
                         IFNULL(s.universityRollNo,'---') AS universityRollNo,
                         SUM( ttm.maxMarks ) AS maxMarks,
                         SUM( ttm.marksScored ) AS marksScored,
                         IFNULL((select graceMarks from ".TEST_GRACE_MARKS_TABLE." tgm where tgm.studentId= sg.studentId and tgm.classId = sg.classId and tgm.subjectId = ttm.subjectId),0) as finalGraceMarks,
                         IFNULL((select tgm.internalGraceMarks from ".TEST_GRACE_MARKS_TABLE." tgm where tgm.studentId= sg.studentId and tgm.classId = sg.classId and tgm.subjectId = ttm.subjectId),0) as internalGraceMarks,
                         IFNULL((select tgm.externalGraceMarks from ".TEST_GRACE_MARKS_TABLE." tgm where tgm.studentId= sg.studentId and tgm.classId = sg.classId and tgm.subjectId = ttm.subjectId),0) as externalGraceMarks,
                         IFNULL((select tgm.totalGraceMarks from ".TEST_GRACE_MARKS_TABLE." tgm where tgm.studentId= sg.studentId and tgm.classId = sg.classId and tgm.subjectId = ttm.subjectId),0) as totalGraceMarks
                    FROM
                         student_groups sg, ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,student s
                    WHERE
                         sg.studentId = ttm.studentId
                         AND sg.studentId = s.studentId
                         AND sg.classId = ttm.classId
                         $conditions
                    GROUP BY
                         ttm.studentId, sg.groupId          
                    ORDER BY 
                          $orderBy ";
        }
        else{
            $query="SELECT
                          DISTINCT  s.studentId,
                          CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                          IFNULL(s.rollNo,'---') AS rollNo,
                          IFNULL(s.universityRollNo,'---') AS universityRollNo,
                          SUM( ttm.maxMarks ) AS maxMarks,
                          SUM( ttm.marksScored ) AS marksScored,
                          IFNULL((select graceMarks from ".TEST_GRACE_MARKS_TABLE." tgm where tgm.studentId= sg.studentId and tgm.classId = sg.classId and tgm.subjectId = ttm.subjectId),0) as finalGraceMarks,
                          IFNULL((select tgm.internalGraceMarks from ".TEST_GRACE_MARKS_TABLE." tgm where tgm.studentId= sg.studentId and tgm.classId = sg.classId and tgm.subjectId = ttm.subjectId),0) as internalGraceMarks,
                          IFNULL((select tgm.externalGraceMarks from ".TEST_GRACE_MARKS_TABLE." tgm where tgm.studentId= sg.studentId and tgm.classId = sg.classId and tgm.subjectId = ttm.subjectId),0) as externalGraceMarks,
                          IFNULL((select tgm.totalGraceMarks from ".TEST_GRACE_MARKS_TABLE." tgm where tgm.studentId= sg.studentId and tgm.classId = sg.classId and tgm.subjectId = ttm.subjectId),0) as totalGraceMarks
                    FROM
                          student_optional_subject sg,  ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,student s
                    WHERE 
                          sg.studentId = ttm.studentId
                          AND sg.studentId = s.studentId
                          AND sg.classId = ttm.classId
                          $conditions
                    GROUP BY 
                          ttm.studentId, sg.groupId
                    ORDER BY 
                          $orderBy ";
        }
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getClassName($classId) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "
                    SELECT
                            className
                    FROM    class
                    WHERE    classId = $classId;";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
   
   public function getSubjectCode($subjectId) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "
                    SELECT
                            subjectCode
                    FROM    subject
                    WHERE    subjectId = $subjectId;";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
          
}

?>
