<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeedBackProvideAdvancedManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
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
    
//-------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Question Set Informations
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------         
    public function getMappedCategoriesForUsers($labelId=-1,$roleId=-1,$userId=-1) {
    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');
    $logInDate=date('Y-m-d');
    /*
    $query ="SELECT 
                   fc.feedbackCategoryId,
                   fc.feedbackCategoryName
             FROM 
                   feedbackadv_category fc,
                   feedbackadv_survey_mapping fsm,
                   feedbackadv_survey_visible_to_users fsvu,
                   `user` u,
                   `role` r
             WHERE
                   u.roleId=r.roleId
                   AND u.userId=fsvu.userId
                   AND r.roleId=fsvu.roleId
                   AND fsvu.feedbackMappingId=fsm.feedbackMappingId
                   AND fsm.feedbackCategoryId=fc.feedbackCategoryId
                   AND fsm.feedbackSurveyId=$labelId
                   AND u.userId=$userId
                   AND r.roleId=$roleId
                   AND u.instituteId=$instituteId
             GROUP BY fc.feedbackCategoryId
             ORDER BY fc.feedbackCategoryName
             ";
    */
    $groupByCondtion='';
    if($roleId==2){//as categories will repeat for students when mapping is on with subjects
        $groupByCondtion='GROUP BY fc.feedbackCategoryId';
    }
    
      $query ="SELECT 
                   fc.feedbackCategoryId,
                   fc.feedbackCategoryName,
                   IF(ftcs.subjectId IS NULL,-1,ftcs.subjectId) AS genSubjectId,
                   IF(s.subjectCode IS NULL,-1,s.subjectCode) AS gensubjectCode
             FROM 
                   feedbackadv_category fc,
                   feedbackadv_survey_visible_to_users fsvu,
                   `user` u,
                   `role` r,
                   feedbackadv_survey_mapping fsm
                   LEFT JOIN  feedbackadv_to_class_subjects ftcs ON (ftcs.feedbackMappingId=fsm.feedbackMappingId)
                   LEFT JOIN  `subject` s ON (s.subjectId=ftcs.subjectId)
             WHERE
                   u.roleId=r.roleId
                   AND u.userId=fsvu.userId
                   AND r.roleId=fsvu.roleId
                   AND fsvu.feedbackMappingId=fsm.feedbackMappingId
                   AND fsm.feedbackCategoryId=fc.feedbackCategoryId
                   AND fsm.feedbackSurveyId=$labelId
                   AND u.userId=$userId
                   AND r.roleId=$roleId
                   AND u.instituteId=$instituteId
             $groupByCondtion
             ORDER BY fc.printOrder,IF(gensubjectCode!=-1,gensubjectCode,1)
             ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

 public function fetchMappedQuestionsForTeachers($labelId=-1,$catId=-1,$userId=-1,$roleId=-1) {
    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
    $query ="SELECT 
                   fq.feedbackQuestionId,
                   fq.feedbackQuestion,
                   ftq.feedbackToQuestionId,
                   ftq.printOrder,
                   fsm.feedbackMappingId
             FROM 
                   feedbackadv_to_question  ftq,
                   feedbackadv_survey_visible_to_users fsvu,
                   feedbackadv_survey_mapping fsm,
                   feedbackadv_questions fq
             WHERE
                   fsvu.feedbackMappingId=fsm.feedbackMappingId
                   AND fsm.feedbackSurveyId=ftq.feedbackSurveyId
                   AND fsm.feedbackCategoryId=ftq.feedbackCategoryId
                   AND fsm.feedbackQuestionSetId=ftq.feedbackQuestionSetId
                   AND ftq.feedbackQuestionId=fq.feedbackQuestionId
                   AND fsm.feedbackSurveyId=$labelId
                   AND fsm.feedbackCategoryId=$catId
                   AND fsvu.userId=$userId
                   AND fsvu.roleId=$roleId
             ORDER BY ftq.printOrder
             ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

 public function fetchMappedQuestionsForStudents($labelId=-1,$catId=-1,$userId=-1,$roleId=-1,$subjectId=-1) {
    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');
    $subStr='';
    $subjectCondition='';
    if($subjectId!=-1){
        $subjectArray=explode(',',$subjectId);
        $cnt=count($subjectArray);
        for($i=0;$i<$cnt;$i++){
            if(trim($subjectArray[$i])==-1){
                continue;
            }
            if($subStr!=''){
                $subStr .=',';
            }
            $subStr .=trim($subjectArray[$i]);
        }
        if($subStr!=''){
            $subjectCondition =' AND ftcs.subjectId IN ('.$subStr.')';
        }
    }
    
    $query ="SELECT 
                   fq.feedbackQuestionId,
                   fq.feedbackQuestion,
                   ftq.feedbackToQuestionId,
                   ftq.printOrder,
                   fsm.feedbackMappingId,
                   IF(ftcs.feedbackMappingId IS NULL,-1,ftcs.feedbackMappingId) AS genMappingId,
                   IF(ftcs.subjectId IS NULL,-1,ftcs.subjectId) AS genSubjectId,
                   IF(s.subjectCode IS NULL,-1,s.subjectCode) AS genSubjectCode,
                   IF(fsm.classId IS NULL OR fsm.classId=0,-1,fsm.classId) AS genClassId
             FROM 
                   feedbackadv_to_question  ftq,
                   feedbackadv_survey_visible_to_users fsvu,
                   feedbackadv_questions fq,
                   feedbackadv_survey_mapping fsm
                   LEFT JOIN feedbackadv_to_class_subjects ftcs ON (ftcs.feedbackMappingId=fsm.feedbackMappingId AND ftcs.subjectId=$subjectId)
                   LEFT JOIN subject s ON (s.subjectId=ftcs.subjectId AND s.subjectId=$subjectId)
             WHERE
                   fsvu.feedbackMappingId=fsm.feedbackMappingId
                   AND fsm.feedbackSurveyId=ftq.feedbackSurveyId
                   AND fsm.feedbackCategoryId=ftq.feedbackCategoryId
                   AND fsm.feedbackQuestionSetId=ftq.feedbackQuestionSetId
                   AND ftq.feedbackQuestionId=fq.feedbackQuestionId
                   AND fsm.feedbackSurveyId=$labelId
                   AND fsm.feedbackCategoryId=$catId
                   AND fsvu.userId=$userId
                   AND fsvu.roleId=$roleId
                   $subjectCondition
             ORDER BY LENGTH(subjectCode)+0,subjectCode,ftq.printOrder
             ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }        

public function fetchAnswerSetRecords($questionIds=-1) { 
    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
    $query="
             SELECT
                    fq.feedbackQuestionId,
                    fas.answerSetId,
                    fas.answerSetName,
                    faso.answerSetOptionId,
                    faso.optionLabel,
                    faso.optionPoints
             FROM
                    feedbackadv_questions fq,
                    feedbackadv_answer_set fas,
                    feedbackadv_answer_set_option faso
             WHERE
                    fq.answerSetId=fas.answerSetId
                    AND fas.answerSetId=faso.answerSetId
                    AND fas.instituteId=$instituteId
                    AND fq.feedbackQuestionId IN ( $questionIds )
             ORDER BY faso.printOrder
           ";       
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function checkCategoryHasComments($catId=-1) { 
    $query="
             SELECT
                    feedbackCategoryId,
                    hasFeedbackComments,
                    description
             FROM
                    feedbackadv_category
             WHERE
                    feedbackCategoryId=$catId
           ";       
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getNumberOfAttempts($feedbackSurveyId=-1) { 
    $query="
             SELECT
                    feedbackSurveyId,
                    noOfAttempts
             FROM
                    feedbackadv_survey
             WHERE
                    feedbackSurveyId=$feedbackSurveyId
           ";       
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getUserNumberOfAttempts($userId=-1,$feedbackToQuestionId=-1) {
    $query="
             SELECT
                    feedbackSurveyAnswerId,
                    attempts
             FROM
                    feedbackadv_survey_answer
             WHERE
                    userId=$userId
                    AND feedbackToQuestionId=$feedbackToQuestionId
           ";       
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


 public function totalFetchMappedQuestionsForTeachers($labelId=-1,$userId=-1,$roleId=-1) {
    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
    $query ="SELECT 
                   COUNT(*) AS cnt
             FROM 
                   feedbackadv_to_question  ftq,
                   feedbackadv_survey_visible_to_users fsvu,
                   feedbackadv_survey_mapping fsm,
                   feedbackadv_questions fq
             WHERE
                   fsvu.feedbackMappingId=fsm.feedbackMappingId
                   AND fsm.feedbackSurveyId=ftq.feedbackSurveyId
                   AND fsm.feedbackCategoryId=ftq.feedbackCategoryId
                   AND fsm.feedbackQuestionSetId=ftq.feedbackQuestionSetId
                   AND ftq.feedbackQuestionId=fq.feedbackQuestionId
                   AND fsm.feedbackSurveyId IN ( $labelId )
                   AND fsvu.userId=$userId
                   AND fsvu.roleId=$roleId
             ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
 public function totalFetchMappedQuestionsForStudents($labelId=-1,$userId=-1,$roleId=-1,$subjectIds=-1) {
    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');
    $subjectCondition='';
    if($subjectIds!=-1){
        $subjectCondition=' AND ftcs.subjectId IN ('.$subjectIds.')';
    }
   $query ="SELECT 
                   COUNT(*) AS cnt
             FROM 
                   feedbackadv_to_question  ftq,
                   feedbackadv_survey_visible_to_users fsvu,
                   feedbackadv_questions fq,
                   feedbackadv_survey_mapping fsm
                   LEFT JOIN  feedbackadv_to_class_subjects ftcs ON (ftcs.feedbackMappingId=fsm.feedbackMappingId $subjectCondition )
             WHERE
                   fsvu.feedbackMappingId=fsm.feedbackMappingId
                   AND fsm.feedbackSurveyId=ftq.feedbackSurveyId
                   AND fsm.feedbackCategoryId=ftq.feedbackCategoryId
                   AND fsm.feedbackQuestionSetId=ftq.feedbackQuestionSetId
                   AND ftq.feedbackQuestionId=fq.feedbackQuestionId
                   AND fsm.feedbackSurveyId IN ( $labelId )
                   AND fsvu.userId=$userId
                   AND fsvu.roleId=$roleId
             ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
public function totalFetchMappedQuestionsForStudentsWithOutTeacher($labelId=-1,$userId=-1,$roleId=-1,$subjectIds=-1) {
    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');
    $subjectCondition='';
    $subjectIds = str_replace("%","",$subjectIds);
    if($subjectIds!=-1){
        $subjectCondition=' AND ftcs.subjectId IN ('.$subjectIds.')';
    }
    
    
    
    $query ="SELECT 
                  COUNT(*) AS cnt         
             FROM 
                   feedbackadv_to_question  ftq,
                   feedbackadv_survey_visible_to_users fsvu,
                   feedbackadv_questions fq,
                   feedbackadv_survey_mapping fsm   
                   LEFT JOIN  feedbackadv_to_class_subjects ftcs ON (ftcs.feedbackMappingId=fsm.feedbackMappingId $subjectCondition )
             WHERE
                   fsvu.feedbackMappingId=fsm.feedbackMappingId
                   AND fsm.feedbackSurveyId=ftq.feedbackSurveyId
                   AND fsm.feedbackCategoryId=ftq.feedbackCategoryId
                   AND fsm.feedbackQuestionSetId=ftq.feedbackQuestionSetId
                   AND ftq.feedbackQuestionId=fq.feedbackQuestionId
                   AND fsm.feedbackSurveyId IN ( $labelId )
                   AND fsvu.userId=$userId
                   AND fsvu.roleId=$roleId
                   AND ftcs.subjectId IS NULL
             ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function totalWithOutTeacher($labelId=-1,$userId=-1,$roleId=-1) {
         
         global $sessionHandler;
         $instituteId = $sessionHandler->getSessionVariable('InstituteId');
         $sessionId   = $sessionHandler->getSessionVariable('SessionId');    
         
         $query ="SELECT  
                       COUNT(*) AS cnt         
                  FROM 
                        feedbackadv_survey_answer
                  WHERE
                        userId=$userId 
                        AND answerSetOptionId!=-1  
                        AND
                        feedbackMappingId IN 
                                             (SELECT 
                                                      DISTINCT fsm.feedbackMappingId
                                              FROM 
                                                       feedbackadv_to_question  ftq,
                                                       feedbackadv_survey_visible_to_users fsvu,
                                                       feedbackadv_questions fq,
                                                       feedbackadv_survey_mapping fsm
                                                       LEFT JOIN  feedbackadv_to_class_subjects ftcs ON (ftcs.feedbackMappingId=fsm.feedbackMappingId $subjectCondition )
                                              WHERE
                                                       fsvu.feedbackMappingId=fsm.feedbackMappingId
                                                       AND fsm.feedbackSurveyId=ftq.feedbackSurveyId
                                                       AND fsm.feedbackCategoryId=ftq.feedbackCategoryId
                                                       AND fsm.feedbackQuestionSetId=ftq.feedbackQuestionSetId
                                                       AND ftq.feedbackQuestionId=fq.feedbackQuestionId
                                                       AND fsm.feedbackSurveyId IN ( $labelId )
                                                       AND fsvu.userId=$userId
                                                       AND fsvu.roleId=$roleId
                                                       AND ftcs.subjectId IS NULL)   ";
                                                       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
public function totalFetchMappedQuestionsForStudentsWithTeacher($labelId=-1,$userId=-1,$roleId=-1,$subjectIds=-1) {
    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');
    $subjectCondition='';
    if($subjectIds!=-1){
        $subjectCondition=' AND ftcs.subjectId IN ('.$subjectIds.')';
    }
   $query ="SELECT 
                   COUNT(*) AS cnt        
             FROM 
                   feedbackadv_to_question  ftq,
                   feedbackadv_survey_visible_to_users fsvu,
                   feedbackadv_questions fq,
                   feedbackadv_survey_mapping fsm
                   LEFT JOIN  feedbackadv_to_class_subjects ftcs ON (ftcs.feedbackMappingId=fsm.feedbackMappingId $subjectCondition )
             WHERE
                   fsvu.feedbackMappingId=fsm.feedbackMappingId
                   AND fsm.feedbackSurveyId=ftq.feedbackSurveyId
                   AND fsm.feedbackCategoryId=ftq.feedbackCategoryId
                   AND fsm.feedbackQuestionSetId=ftq.feedbackQuestionSetId
                   AND ftq.feedbackQuestionId=fq.feedbackQuestionId
                   AND fsm.feedbackSurveyId IN ( $labelId )
                   AND fsvu.userId=$userId
                   AND fsvu.roleId=$roleId    
                   AND ftcs.subjectId IS NOT NULL
             ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
public function fetchAllocatedTeachersForThisSubject($classIds='',$subjectIds='',$surveyId='') { 
    global $sessionHandler;
    $userId=$sessionHandler->getSessionVariable('UserId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    
   
    if(trim($surveyId)=='') {
      $surveyId='0';
    }
    
    if(trim($subjectIds)=='') {
      $subjectIds='0';
    }
   
    $str="";
    if(trim($classIds)=='') {
      $str='0';
    }
    else {
       $classId=explode(",",$classIds);
       $cnt=count($classId);

      for($i=0;$i<$cnt;$i++){
        if(trim($classId[$i])!='') {
	   if($str!=""){
	     $str .=",";
	   }
	   $str .=$classId[$i];
	}
      }
    }
    
    
    $query=" SELECT
                    COUNT(*) AS cnt
             FROM
                    employee e,
                    feedbackadv_teacher_mapping ftm,
                    feedbackadv_survey_mapping fsm,
                    feedbackadv_to_class_subjects ftcs
             WHERE
                    fsm.feedbackMappingId=ftcs.feedbackMappingId
                    AND fsm.classId=ftm.classId
                    AND ftcs.subjectId=ftm.subjectId
                    AND ftm.employeeId=e.employeeId
                    AND fsm.feedbackSurveyId IN ($surveyId)
                    AND fsm.roleId=4
                    AND ftm.classId   IN ($str)
                    AND ftm.subjectId IN ($subjectIds)
                    AND ftm.groupId IN 
                     (
                        SELECT 
                              DISTINCT sg.groupId
                        FROM
                              student_groups sg,
                              student s,
                              feedbackadv_teacher_mapping ftm1,
                              `group` g,group_type gt
                        WHERE
                              sg.studentId=s.studentId
                              AND sg.classId=ftm1.classId
                              AND sg.groupId=ftm1.groupId
                              AND s.userId='$userId'
                              AND sg.sessionId='$sessionId'
                              AND sg.instituteId='$instituteId'
                              AND sg.classId IN ( $str )
                              AND sg.groupId=g.groupId
                              AND g.groupTypeId=gt.groupTypeId
                              AND gt.groupTypeName=(
                                                     SELECT 
                                                           DISTINCT st.subjectTypeName 
                                                     FROM 
                                                           subject su,subject_type st
                                                     WHERE
                                                           su.subjectTypeId=st.subjectTypeId
                                                           AND su.subjectId IN ( $subjectIds )
                               )
                     )";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


 public function fetchPreviousAnswersForTeachers($mappingIds=-1,$mappedQuestionIds=-1,$userId=-1) {
    
    $query ="SELECT 
                   feedbackToQuestionId,
                   feedbackMappingId,
                   answerSetOptionId,
                   userId
             FROM 
                   feedbackadv_survey_answer
             WHERE
                   feedbackMappingId IN ($mappingIds)
                   AND feedbackToQuestionId IN ($mappedQuestionIds)
                   AND userId=$userId
             ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
 public function fetchPreviousAnswersForStudents($mappingIds=-1,$mappedQuestionIds=-1,$userId=-1,$classIds=-1,$subjectIds=-1) {
     
    $subjectCondition=''; 
    $classCondition='';
    if($classIds!=-1 and $subjectIds!=-1){
       $classCondition   = ' AND classId IN ('.$classIds.')';
       $subjectCondition = ' AND subjectId IN ('.$subjectIds.')';
    }
        
    $query ="SELECT 
                   feedbackToQuestionId,
                   feedbackMappingId,
                   answerSetOptionId,
                   userId,
                   IF(classId IS NULL OR classId=0,-1,classId) AS classId,
                   IF(subjectId IS NULL OR subjectId=0,-1,subjectId) AS subjectId,
                   IF(employeeId IS NULL OR employeeId=0,-1,employeeId) AS employeeId
             FROM 
                   feedbackadv_survey_answer
             WHERE
                   feedbackMappingId IN ($mappingIds)
                   AND feedbackToQuestionId IN ($mappedQuestionIds)
                   AND userId=$userId
                   $classCondition
                   $subjectCondition
             ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }    
    
 public function fetchGivenCategoryComments($surveyId=-1,$catId=-1,$userId=-1,$subjectId=-1,$employeeId=-1,$classId=-1) {
    $subjectConditions='';
    if($subjectId!=-1){
        $subjectConditions=' AND subjectId='.$subjectId;
    }
    $employeeConditions='';
    if($employeeId!=-1){
        $employeeConditions=' AND employeeId='.$employeeId;
    }
    $classConditions='';
    if($classId!=-1){
        $classConditions=' AND classId='.$classId;
    }
    $query ="SELECT 
                   feedbackSurveyCommentsId,
                   TRIM(comments) AS comments,
                   employeeId
             FROM 
                   feedbackadv_survey_comments
             WHERE
                   feedbackSurveyId=$surveyId
                   AND feedbackCategoryId=$catId
                   AND userId=$userId
                   $subjectConditions
                   $employeeConditions
                   $classConditions
             ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

    
public function getUserAttemptsStatus($surveyId=-1,$userId=-1) {
    $query="
             SELECT
                    fs.feedbackSurveyId,
                    fs.feedbackSurveyLabel,
                    fs.noOfAttempts,
                    IF(fsa.attempts IS NULL,0,fsa.attempts) AS attempts
             FROM
                    feedbackadv_survey fs,
                    feedbackadv_to_question ftq
                    LEFT JOIN feedbackadv_survey_answer fsa ON ( 
                                                                 fsa.feedbackToQuestionId=ftq.feedbackToQuestionId
                                                                 AND fsa.userId=$userId
                                                               )
             WHERE
                    fs.feedbackSurveyId=ftq.feedbackSurveyId
                    AND fs.feedbackSurveyId=$surveyId
             GROUP BY fs.feedbackSurveyId
           ";       
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function fetchMappedTeacher($classIds,$subjectIds,$surveyId) { 
    global $sessionHandler;
    $userId=$sessionHandler->getSessionVariable('UserId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    
    $query=" SELECT
                    DISTINCT e.employeeId,e.employeeName,ftm.subjectId,ftm.groupId,ftm.classId
             FROM
                    employee e,
                    feedbackadv_teacher_mapping ftm,
                    feedbackadv_survey_mapping fsm,
                    feedbackadv_to_class_subjects ftcs
             WHERE
                    fsm.feedbackMappingId=ftcs.feedbackMappingId
                    AND fsm.classId=ftm.classId
                    AND ftcs.subjectId=ftm.subjectId
                    AND ftm.employeeId=e.employeeId
                    AND fsm.feedbackSurveyId=$surveyId
                    AND fsm.roleId=4
                    AND ftm.classId   IN ($classIds)
                    AND ftm.subjectId IN ($subjectIds)
                    AND ftm.groupId IN 
                     (
                        SELECT 
                              DISTINCT sg.groupId
                        FROM
                              student_groups sg,
                              student s,
                              feedbackadv_teacher_mapping ftm1,
                              `group` g,group_type gt
                        WHERE
                              sg.studentId=s.studentId
                              AND sg.classId=ftm1.classId
                              AND sg.groupId=ftm1.groupId
                              AND s.userId=$userId
                              AND sg.sessionId=$sessionId
                              AND sg.instituteId=$instituteId
                              AND sg.classId IN ( $classIds )
                              AND sg.groupId=g.groupId
                              AND g.groupTypeId=gt.groupTypeId
                              AND gt.groupTypeName=(
                                                     SELECT 
                                                           DISTINCT st.subjectTypeName 
                                                     FROM 
                                                           subject su,subject_type st
                                                     WHERE
                                                           su.subjectTypeId=st.subjectTypeId
                                                           AND su.subjectId IN ( $subjectIds )
                               )
                     )";
    
    /*$query="
             SELECT
                    DISTINCT e.employeeId,e.employeeName,ftm.subjectId,ftm.groupId
             FROM
                    employee e,
                    feedbackadv_teacher_mapping ftm,
                    feedbackadv_survey_mapping fsm,
                    feedbackadv_to_class_subjects ftcs
             WHERE
                    fsm.feedbackMappingId=ftcs.feedbackMappingId
                    AND fsm.classId=ftm.classId
                    AND ftcs.subjectId=ftm.subjectId
                    AND ftm.employeeId=e.employeeId
                    AND fsm.feedbackSurveyId=$surveyId
                    AND fsm.roleId=4
                    AND ftm.classId   IN ( $classIds   )
                    AND ftm.subjectId IN ( $subjectIds )
                    AND ftm.groupId IN 
                     (
                        SELECT 
                              DISTINCT sg.groupId
                        FROM
                              student_groups sg,
                              student s,
                              feedbackadv_teacher_mapping ftm1
                        WHERE
                              sg.studentId=s.studentId
                              AND sg.classId=ftm1.classId
                              AND sg.groupId=ftm1.groupId
                              AND s.userId=$userId
                              AND sg.sessionId=$sessionId
                              AND sg.instituteId=$instituteId
                     )
           ";
       */           
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//this function is used to fetch answers related to mapped teachers for categories whose relations is subject centric
public function fetchAnswersForMappedTeaches($classIds,$subjectIds,$surveyId) { 
    global $sessionHandler;
    $userId=$sessionHandler->getSessionVariable('UserId');
    
    $query="
             SELECT
                    DISTINCT fsa.employeeId
             FROM
                    feedbackadv_survey_answer fsa
             WHERE
                    userId=$userId
                    AND classId IN ($classIds)
                    AND subjectId IN ($subjectIds)
                    AND feedbackMappingId IN 
                    (
                      SELECT 
                            DISTINCT feedbackMappingId
                      FROM
                            feedbackadv_survey_mapping
                      WHERE
                            feedbackSurveyId=$surveyId
                    )
           ";        
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


 public function fetchPreviousAnswersForMappedTeachers($userId,$classIds,$subjectIds,$surveyId) {
    $query ="SELECT 
                   IF(employeeId IS NULL,-1,employeeId) AS employeeId,
                   IF(classId IS NULL,-1,classId) AS classId,
                   IF(subjectId IS NULL,-1,subjectId) AS subjectId
             FROM 
                   feedbackadv_survey_answer
             WHERE
                   userId=$userId
                   AND classId IN ($classIds)
                   AND subjectId IN ($subjectIds)
                   AND feedbackMappingId IN 
                    (
                      SELECT 
                            DISTINCT feedbackMappingId
                      FROM
                            feedbackadv_survey_mapping
                      WHERE
                            feedbackSurveyId=$surveyId
                    )
             GROUP BY classId,subjectId
             ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


 public function totalAnsweresForMappedQuestionsForStudents($labelIds,$userId,$roleId,$condition='') {
    
       $query ="SELECT 
                   COUNT(*) as cnt      
                FROM 
                       feedbackadv_survey_answer
                WHERE
                       userId=$userId
                       AND feedbackMappingId IN 
                                              (
                                                SELECT 
                                                      DISTINCT feedbackMappingId
                                                FROM
                                                      feedbackadv_survey_visible_to_users
                                                WHERE
                                                      userId=$userId
                                                      AND roleId=$roleId
                                                      AND feedbackMappingId IN
                                                                             (
                                                                               SELECT 
                                                                                      DISTINCT feedbackMappingId
                                                                               FROM   
                                                                                      feedbackadv_survey_mapping
                                                                               WHERE
                                                                                      feedbackSurveyId IN ($labelIds)
                                                                                      AND roleId=$roleId
                                                                             )
                                              )
                       AND answerSetOptionId!=-1 
                       $condition";
                 
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
                                                           
public function deleteCategoryComments($userId=-1,$surveyId=-1,$catIds=-1,$subjectIds=-1) {
    
    $subjectCondition='';
    if($subjectIds!=-1){
        $subjectCondition=' AND subjectId IN ('.$subjectIds.')';
    }
    
    $query="
             DELETE
             FROM
                    feedbackadv_survey_comments
             WHERE
                    userId=$userId
                    AND feedbackSurveyId=$surveyId
                    AND feedbackCategoryId IN ($catIds) 
                    $subjectCondition
           ";       
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteCategoryCommentsForTeachers($userId=-1,$surveyId=-1,$catIds=-1,$subjectIds=-1) {
    
    $subjectCondition='';
    if($subjectIds!=-1){
        $subjectCondition=' AND subjectId IN ('.$subjectIds.')';
    }
    
    $query="
             DELETE
             FROM
                    feedbackadv_survey_comments
             WHERE
                    userId=$userId
                    AND feedbackSurveyId=$surveyId
                    AND feedbackCategoryId IN ($catIds)
                    AND classId IS NULL 
                    AND subjectId IS NULL
                    AND employeeId IS NULL
                    $subjectCondition
           ";       
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteCategoryCommentsForStudents($deleteString,$mode) {
  if($mode==1){  
    $query="
             DELETE
             FROM
                    feedbackadv_survey_comments
             WHERE
                    CONCAT_WS('~',feedbackSurveyId,feedbackCategoryId,classId,subjectId,employeeId,userId) IN (".$deleteString.")
           ";
  }
  else{
    $query="
             DELETE
             FROM
                    feedbackadv_survey_comments
             WHERE
                    CONCAT_WS('~',feedbackSurveyId,feedbackCategoryId,userId) IN (".$deleteString.")
           ";  
  }       
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertCategoryComments($commentInsertString) {
    $query="
             INSERT INTO
                    feedbackadv_survey_comments
             (feedbackSurveyId,feedbackCategoryId,classId,subjectId,employeeId,dated,userId,comments)
             VALUES  $commentInsertString
           ";       
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function deleteFeedBackAnswersForTeachers($userId=-1,$mappingIds=-1,$feedbackToQuestionIds=-1) {
    $query="
             DELETE
             FROM
                    feedbackadv_survey_answer
             WHERE
                    userId=$userId
                    AND feedbackToQuestionId IN ($feedbackToQuestionIds)
                    AND feedbackMappingId IN ($mappingIds) 
           ";       
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function deleteFeedBackAnswersForStudents($userId=-1,$mappingIds=-1,$feedbackToQuestionIds=-1,$classIds=-1,$subjectIds=-1,$conditions='') {
    
    $subjectCondition='';
    if($classId!='' and $subjectIds!='') {
      if($classId!=-1 and $subjectIds!=-1){
        $classCondition   =' AND classId IN   ('.$classIds.')';
        $subjectCondition =' AND subjectId IN ('.$subjectIds.')';
      }
    }
    $query="
             DELETE
             FROM
                    feedbackadv_survey_answer
             WHERE
                    userId=$userId
                    AND feedbackToQuestionId IN ($feedbackToQuestionIds)
                    AND feedbackMappingId IN ($mappingIds) 
                    $classCondition
                    $subjectCondition
					$conditions
           ";       
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertFeedbackAnswers($insertAnswerString) {
    
   $insertAnswerString = str_replace("''","NULL",$insertAnswerString);
   $insertAnswerString = str_replace(",,",",NULL,",$insertAnswerString);
   $insertAnswerString = str_replace("''","'",$insertAnswerString);
   $insertAnswerString = str_replace(", ,",",NULL,",$insertAnswerString);
   $insertAnswerString = str_replace("'NULL'","NULL",$insertAnswerString);
   
   $query="INSERT INTO feedbackadv_survey_answer            
           (feedbackToQuestionId, feedbackMappingId,userId, answerSetOptionId,classId, subjectId,groupId,employeeId, attempts,dated )
           VALUES  
           $insertAnswerString ";
           
   return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


 public function updateFeedbackStudentStatus($userId,$status) {
     $query="UPDATE
                      feedbackadv_student_status  
                SET 
                      status=$status
                WHERE
                      userId =$userId
        ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
 }
 
 public function insertFeedbackStudentStatusLog($userId,$description,$logStatus) {
        $query="INSERT INTO
                      feedbackadv_student_status_log
                      (userId,logDescription,logStatus,logDate,doneByUserId)
                VALUES
                      ( $userId,'".$description."',$logStatus,CURDATE(),$userId )
        ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    //-------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Student Id from user Id
// $conditions :db clauses
// Author :Abhay kant
// Created on : (9.8.2011)
// Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd.
  public function getStudentId($condition=''){
  	$query="SELECT 
  			studentId,classId
		FROM 
			student
		WHERE 
			$condition";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }
      
         //-------------------------------------------------------------
// THIS FUNCTION IS USED FOR Saving student data when student submit details
// $conditions :db clauses
// Author :Abhay kant
// Created on : (9.8.2011)
// Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd.
    public function insertStudentReport($surveyId,$classId,$studentId,$isStatus,$totalWeightage,$final='',$feedbackToQuestionId='',$employeeId='',$groupId='',$subjectId='',$answerSetOptionId=''){
  	
        $query="INSERT INTO feedback_student_status
  			       (surveyId,classId,studentId,isStatus,totalAnswer,finalId,feedbackToQuestionId,employeeId,groupId,subjectId,answerSetOptionId)
		        VALUES
			       ($surveyId,$classId,$studentId,$isStatus,$totalWeightage,'$final','$feedbackToQuestionId','$employeeId','$groupId','$subjectId','$answerSetOptionId')";
			
	    return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
 	 }
  
  
        
         //-------------------------------------------------------------
// THIS FUNCTION IS USED FOR deleting student older data when student submit details
// $conditions :db clauses
// Author :Abhay kant
// Created on : (9.8.2011)
// Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd.
      public function deleteStudentReport($studentId='',$labelId='',$classId='') {
  	     
          $query="DELETE FROM feedback_student_status
		         WHERE studentId='$studentId' AND surveyId='$labelId' AND classId='$classId' ";
              
	      return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
 	 }
  
         //-------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting answer weightage
// $conditions :db clauses
// Author :Abhay kant
// Created on : (9.8.2011)
// Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd.
      public function getStudentWeightage($answerId){
  	
  	$query="SELECT 
  			optionPoints
  		FROM 
  			feedbackadv_answer_set_option
		WHERE answerSetOptionId=$answerId";
		
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 	 }
                 //-------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting teacher Id
// $conditions :db clauses
// Author :Abhay kant
// Created on : (9.8.2011)
// Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd.
      public function getTeacherId($surveyId){
  	
  	$query="SELECT 
  			teacherMappingId
  		FROM 
  			feedbackadv_teacher_mapping
		WHERE 
			feedbackSurveyId=$surveyId";
		
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 	 }

     
             //-------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting subject code
// $conditions :db clauses
// Author :Abhay kant
// Created on : (9.8.2011)
// Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd.
      public function getSubjectCode($subjectId){
  
  	$query="SELECT 
  			subjectCode,subjectId
  		FROM
  			 subject
		WHERE
			 subjectId IN ($subjectId)";
		
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 	 }
               //-------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting block feedback Student
// $conditions :db clauses
// Author :Abhay kant
// Created on : (9.8.2011)
// Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd.
      public function getblockStudent($condition=''){
  
  	$query="SELECT
  			 status
  		 FROM  
  		 	feedback_student_status
  		 WHERE 
  		 	$condition";
		
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 	 }
  
  
}
// $History: FeedBackProvideAdvancedManager.inc.php $
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 3/05/10    Time: 12:58p
//Updated in $/LeapCC/Model
//Created "Feedback Comments Report"
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 26/02/10   Time: 18:37
//Updated in $/LeapCC/Model
//Modified fetchMappedTeacher() function
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 25/02/10   Time: 17:50
//Updated in $/LeapCC/Model
//Changed internal logic : Now along with class,subject and employee ,
//group information will also be stored in feedback survey answer table.
//This is needed to place add/edit/delete check in teacher mapping
//module.
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 12/02/10   Time: 12:12
//Updated in $/LeapCC/Model
//Corrected Print order of tabs during display of questions in "Provide
//Feedback Module"
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 10/02/10   Time: 12:01
//Updated in $/LeapCC/Model
//Added Features :
//1.Changed the logic of incomplete feedback algorithm.
//2.Showing category description in top of the tabs instead of bottom.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/02/10    Time: 18:21
//Updated in $/LeapCC/Model
//Made modifications in Feedback modules---Added block/unblock feature
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 3/02/10    Time: 17:40
//Updated in $/LeapCC/Model
//Corrected application logic related to survey id check
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/01/10   Time: 17:19
//Updated in $/LeapCC/Model
//Added printOrder checks for answer set options 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/01/10   Time: 12:17
//Created in $/LeapCC/Model
//Created "Provide Feedback" Module
?>
