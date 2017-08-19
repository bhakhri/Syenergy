<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeedBackTeacherMappingManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
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
// THIS FUNCTION IS USED FOR ADDING An Adv. Feedback category
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------    
    public function doTeacherMapping($insertString) {
        
        $query="INSERT INTO feedbackadv_teacher_mapping
                (timeTableLabelId,feedbackSurveyId,classId,groupId,subjectId,employeeId)
                VALUES 
                $insertString";
                
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
	}   
    

    public function deletePreviousTeacherMapping($labelId,$surveyId,$classId,$groupId,$subjectId,$employeeIds=-1) {
        $employeeCondition='';
        if($employeeIds!=-1){
          $employeeCondition .=' AND employeeId NOT IN ('.$employeeIds.')';
        }
        $query = "DELETE 
                  FROM 
                        feedbackadv_teacher_mapping
                  WHERE 
                        timeTableLabelId=$labelId
                        AND feedbackSurveyId=$surveyId
                        AND classId=$classId
                        AND groupId=$groupId
                        AND subjectId=$subjectId
                        $employeeCondition
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function deleteTeacherMapping($deleteString) {
     
        $query = "DELETE 
                  FROM 
                        feedbackadv_teacher_mapping
                  WHERE 
                        CONCAT_WS('~',timeTableLabelId,classId,groupId,subjectId,feedbackSurveyId) IN ( $deleteString )";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
    
    public function fetchMappedClassSubjectGroupEmployee($mappingId) {
        $query = "SELECT 
                        *
                  FROM 
                        feedbackadv_teacher_mapping
                  WHERE
                        teacherMappingId=$mappingId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function fetchMappedValues($selectionString) {
     
        $query = "SELECT
                        *  
                  FROM 
                        feedbackadv_teacher_mapping
                  WHERE 
                        CONCAT_WS('~',timeTableLabelId,classId,groupId,subjectId) IN ( $selectionString )";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }     
    
    public function fetchMappedValuesNew($selectionString) {
     
        $query = "SELECT
                        *  
                  FROM 
                        feedbackadv_teacher_mapping
                  WHERE 
                        CONCAT_WS('_',timeTableLabelId,feedbackSurveyId,classId,subjectId,groupId) IN ( $selectionString )";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
  
    
//--------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Classes Associated with a time table label id
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------         
    public function getTimeTableClass($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT 
                        DISTINCT c.classId,
                        c.className
                  FROM 
                        time_table_classes ttc, class c
                  WHERE
                        ttc.classId=c.classId
                        AND c.instituteId=$instituteId
                        AND c.sessionId=$sessionId
                        $conditions
                  ORDER BY c.studyPeriodId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getTimeTableGroups($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT 
                        DISTINCT g.groupId,
                        g.groupName
                  FROM 
                        time_table_classes ttc, class c,`group` g
                  WHERE
                        ttc.classId=c.classId
                        AND c.classId=g.classId
                        AND c.instituteId=$instituteId
                        AND c.sessionId=$sessionId
                        $conditions
                  ORDER BY LENGTH(g.groupName)+0,g.groupName
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getTimeTableSubjects($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT 
                        DISTINCT sub.subjectId,
                        sub.subjectName,
                        sub.subjectCode
                  FROM 
                        time_table_classes ttc, class c,
                        subject_to_class stc,
                        subject sub
                  WHERE
                        ttc.classId=c.classId
                        AND c.classId=stc.classId
                        AND stc.subjectId=sub.subjectId
                        AND c.instituteId=$instituteId
                        AND c.sessionId=$sessionId
                        $conditions
                  ORDER BY LENGTH(sub.subjectCode)+0,sub.subjectCode
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

    public function getTimeTableTeachers($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT 
                        DISTINCT t.employeeId
                  FROM 
                         ".TIME_TABLE_TABLE."  t,
                        time_table_classes ttc, class c
                  WHERE
                        t.toDate IS NULL
                        AND ttc.classId=c.classId
                        AND ttc.timeTableLabelId=t.timeTableLabelId
                        AND c.instituteId=$instituteId
                        AND c.sessionId=$sessionId
                        $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }        
    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Mapped Teacher List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------       
    public function getMappedTeachersList($conditions='', $limit = '', $orderBy=' className') {
        global $sessionHandler;
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                         ftm.teacherMappingId,
                         c.className,
                         g.groupName,
                         sub.subjectName,
                         sub.subjectCode,
                         ttl.labelName,
                         c.classId,
                         g.groupId,
                         sub.subjectId,
                         ftm.employeeId,
                         COUNT(ftm.employeeId) AS mappedEmployees,
                         e.employeeName,
                         IFNULL(fs.feedbackSurveyLabel,'".NOT_APPLICABLE_STRING."') AS feedbackSurveyLabel
                 FROM 
                         time_table_labels ttl,class c,
                         subject sub,`group` g,
                         employee e,
                         feedbackadv_teacher_mapping ftm
                         LEFT JOIN feedbackadv_survey fs ON (fs.feedbackSurveyId = ftm.feedbackSurveyId)
                 WHERE
                         ftm.timeTableLabelId=ttl.timeTableLabelId
                         AND ftm.classId=c.classId
                         AND ftm.groupId=g.groupId
                         AND ftm.subjectId=sub.subjectId
                         AND ftm.employeeId=e.employeeId
                         AND c.instituteId=$instituteId
                         AND c.sessionId=$sessionId
                 GROUP BY ttl.timeTableLabelId,c.classId,g.groupId,sub.subjectId,ftm.employeeId,ftm.feedbackSurveyId
                 $conditions 
                 ORDER BY $orderBy 
                 $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Total Mapped Teachers
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------      
    public function getTotalMappedTeachers($conditions='') {
        global $sessionHandler;
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                         COUNT( DISTINCT(CONCAT_WS('_',ftm.timeTableLabelId,ftm.classId,ftm.groupId,ftm.subjectId))) AS totalRecords,
                         c.className,
                         g.groupName,
                         sub.subjectName,
                         sub.subjectCode,
                         ttl.labelName,
                         COUNT(ftm.employeeId) AS mappedEmployees,
                         e.employeeName,
                         IFNULL(fs.feedbackSurveyLabel,'".NOT_APPLICABLE_STRING."') AS feedbackSurveyLabel  
                  FROM 
                         time_table_labels ttl,class c,
                         subject sub,`group` g,
                         employee e,
                         feedbackadv_teacher_mapping ftm
                         LEFT JOIN feedbackadv_survey fs ON (fs.feedbackSurveyId = ftm.feedbackSurveyId)
                  WHERE
                         ftm.timeTableLabelId=ttl.timeTableLabelId
                         AND ftm.classId=c.classId
                         AND ftm.groupId=g.groupId       
                         AND ftm.subjectId=sub.subjectId
                         AND ftm.employeeId=e.employeeId
                         AND c.instituteId=$instituteId
                         AND c.sessionId=$sessionId
                  GROUP BY ttl.timeTableLabelId,c.classId,g.groupId,sub.subjectId,ftm.employeeId,ftm.feedbackSurveyId
                  $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

    public function getExcludedEmployeeList($timeTableLabelId,$classId,$subjectId) {
        
        $query = "SELECT 
                         DISTINCT employeeId
                  FROM 
                         feedbackadv_survey_answer 
                  WHERE
                         classId=$classId
                         AND subjectId=$subjectId
                         AND feedbackToQuestionId 
                          IN
                            (
                              SELECT 
                                     DISTINCT feedbackToQuestionId
                              FROM
                                     feedbackadv_to_question
                              WHERE
                                     feedbackSurveyId
                                      IN
                                        (
                                          SELECT
                                                 DISTINCT  feedbackSurveyId
                                          FROM
                                                 feedbackadv_survey
                                          WHERE
                                                 timeTableLabelId=$timeTableLabelId
                                        )
                            )
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

 public function getClassSubjectGroupEmployeeUsageList() {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                         DISTINCT CONCAT_WS('_',f.classId,f.subjectId,f.groupId,f.employeeId) AS uniqueCombination
                  FROM
                         feedbackadv_survey_answer f,
                         class c
                  WHERE
                         f.classId=c.classId
                         AND f.classId IS NOT NULL
                         AND c.instituteId=$instituteId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

public function getClassSubjectGroupUsage($classId,$subjectId,$groupId) {
        
        $query = "SELECT 
                         COUNT(*) AS cnt
                  FROM
                         feedbackadv_survey_answer 
                  WHERE
                         classId=$classId
                         AND subjectId=$subjectId
                         AND groupId=$groupId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
public function getClassSubjectGroupUsageConcatenated($concatString) {
        
        $query = "SELECT 
                         COUNT(*) AS cnt
                  FROM
                         feedbackadv_survey_answer 
                  WHERE
                         CONCAT_WS('~',classId,subjectId,groupId) IN ($concatString)
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
public function getClassSubjectUsage($classId,$subjectId,$surveyId) {
        
        $query = "SELECT 
                         COUNT(*) AS cnt
                  FROM
                         feedbackadv_survey_answer 
                  WHERE
                         classId=$classId
                         AND subjectId=$subjectId
                         AND classId IN 
                                  (
                                     SELECT 
                                           DISTINCT classId 
                                     FROM 
                                           feedbackadv_survey_mapping 
                                     WHERE 
                                           feedbackSurveyId=$surveyId
                                   )
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
public function getClassUsage($classId,$surveyId) {
        
        $query = "SELECT 
                         COUNT(*) AS cnt
                  FROM
                         feedbackadv_survey_answer 
                  WHERE
                         classId=$classId
                         AND subjectId=$subjectId
                         AND classId IN 
                                  (
                                     SELECT 
                                           DISTINCT classId 
                                     FROM 
                                           feedbackadv_survey_mapping 
                                     WHERE 
                                           feedbackSurveyId=$surveyId
                                   )
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    public function getClassSubjectUsageConcatenated($concatString,$surveyId) {
        
        $query = "SELECT 
                         COUNT(*) AS cnt
                  FROM
                         feedbackadv_survey_answer 
                  WHERE
                         CONCAT_WS('~',classId,subjectId) IN ($concatString)
                         AND classId IN 
                                  (
                                     SELECT 
                                           DISTINCT classId 
                                     FROM 
                                           feedbackadv_survey_mapping 
                                     WHERE 
                                           feedbackSurveyId=$surveyId
                                   )
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
   
   public function getCheckFeedbackClass($condition='') {
      
        $query = "SELECT 
                        DISTINCT c.className, c.classId
                  FROM
                        feedbackadv_teacher_mapping fm, class c
                  WHERE
                        fm.classId = c.classId 
                   $condition";           
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getTimeTableClassList($condition='') {
      
        $query = "SELECT 
                        DISTINCT tt.employeeId, tt.groupId, tt.subjectId, tt.timeTableLabelId, tt.classId
                  FROM
                         ".TIME_TABLE_TABLE."  tt 
                  WHERE
                        tt.toDate IS NULL
                        $condition
                   ORDER BY
                         tt.timeTableLabelId, tt.classId";           
                   
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getTotalMappedTeachersNew($conditions='') {
        global $sessionHandler;
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM      
                      (SELECT 
                             DISTINCT ttl.timeTableLabelId, c.classId, fs.feedbackSurveyId,
                             ttl.labelName, c.className,  
                             IFNULL(fs.feedbackSurveyLabel,'".NOT_APPLICABLE_STRING."') AS feedbackSurveyLabel
                      FROM 
                             time_table_labels ttl,class c, `subject` sub,`group` g,
                             employee e, feedbackadv_teacher_mapping ftm, feedbackadv_survey fs
                      WHERE
                             fs.feedbackSurveyId = ftm.feedbackSurveyId
                             AND ftm.timeTableLabelId=ttl.timeTableLabelId
                             AND ftm.classId=c.classId
                             AND ftm.groupId=g.groupId       
                             AND ftm.subjectId=sub.subjectId
                             AND ftm.employeeId=e.employeeId
                             AND c.instituteId=$instituteId
                             AND c.sessionId=$sessionId
                      GROUP BY ttl.timeTableLabelId,c.classId,g.groupId,sub.subjectId,ftm.employeeId,ftm.feedbackSurveyId
                      $conditions) AS tt";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     public function getMappedTeachersListNew($conditions='',$limit='',$orderBy='labelName') {
        global $sessionHandler;
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                         DISTINCT  ttl.timeTableLabelId, c.classId, fs.feedbackSurveyId,
                         ttl.labelName, c.className,  
                         IFNULL(fs.feedbackSurveyLabel,'".NOT_APPLICABLE_STRING."') AS feedbackSurveyLabel
                  FROM 
                         time_table_labels ttl,class c, `subject` sub,`group` g,
                         employee e, feedbackadv_teacher_mapping ftm, feedbackadv_survey fs
                  WHERE
                         fs.feedbackSurveyId = ftm.feedbackSurveyId
                         AND ftm.timeTableLabelId=ttl.timeTableLabelId
                         AND ftm.classId=c.classId
                         AND ftm.groupId=g.groupId       
                         AND ftm.subjectId=sub.subjectId
                         AND ftm.employeeId=e.employeeId
                         AND c.instituteId=$instituteId
                         AND c.sessionId=$sessionId
                  GROUP BY 
                        ttl.timeTableLabelId,c.classId,g.groupId,sub.subjectId,ftm.employeeId,ftm.feedbackSurveyId
                  $conditions
                  ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     public function getMappedTeachersDetail($conditions='',$limit='',$orderBy='subjectCode') {
      
        global $sessionHandler;
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                         ftm.teacherMappingId, c.className, g.groupName, sub.subjectName, sub.subjectCode,
                         ttl.labelName, c.classId, g.groupId, sub.subjectId,ftm.employeeId, 
                         COUNT(ftm.employeeId) AS mappedEmployees, e.employeeName,
                         IFNULL(fs.feedbackSurveyLabel,'".NOT_APPLICABLE_STRING."') AS feedbackSurveyLabel,
                         CONCAT(sub.subjectName,' (',sub.subjectCode,')') AS subjectNameCode,
                         CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeNameCode,
                         ftm.timeTableLabelId, ftm.feedbackSurveyId
                 FROM 
                         time_table_labels ttl,class c,
                         `subject` sub,`group` g, feedbackadv_survey fs,
                         employee e, feedbackadv_teacher_mapping ftm
                 WHERE
                         ftm.timeTableLabelId=ttl.timeTableLabelId
                         AND fs.feedbackSurveyId = ftm.feedbackSurveyId
                         AND ftm.classId=c.classId
                         AND ftm.groupId=g.groupId
                         AND ftm.subjectId=sub.subjectId
                         AND ftm.employeeId=e.employeeId
                         AND c.instituteId=$instituteId
                         AND c.sessionId=$sessionId
                         $conditions 
                 GROUP BY 
                    ttl.timeTableLabelId,c.classId,g.groupId,sub.subjectId,ftm.employeeId,ftm.feedbackSurveyId
                 ORDER BY $orderBy ";
                 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}
?>