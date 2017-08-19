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

class FeedBackReportAdvancedManager {
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

             

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Parent Category
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (09.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getLabelDetails($conditions='') {
        $query = "SELECT
                        *
                  FROM
                        feedbackadv_survey
                        $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getMappedCategories($conditions='') {
        $query = "SELECT
                        fc.feedbackCategoryId,
                        fc.feedbackCategoryName,
                        IF(fc.subjectTypeId IS NULL,-1,1) AS subjectTypeId
                  FROM
                        feedbackadv_category fc,
                        feedbackadv_survey_mapping fsm
                  WHERE
                        fc.feedbackCategoryId=fsm.feedbackCategoryId
                        $conditions
                  GROUP BY fsm.feedbackSurveyId,fsm.feedbackCategoryId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getUniqueCombinationOfSurveyCategoryQuestionSet($labelId,$categoryId) {
        $query = "SELECT
                        DISTINCT CONCAT_WS('~',feedbackSurveyId,feedbackCategoryId,feedbackQuestionSetId) AS uniqueCombination
                  FROM
                        feedbackadv_to_question
                  WHERE
                        feedbackSurveyId=$labelId
                        AND  feedbackCategoryId=$categoryId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

   public function getTotalPointsForCategories($labelId,$categoryId,$subjectFlag=-1) {
      if($subjectFlag==-1){
        $query = "
                  SELECT
                        SUM(t.totalPoints) AS totalPoints
                  FROM
                    (
                      SELECT
                            t1.feedbackQuestionId,
                            t2.answerSetId,
                            t3.optionLabel,
                            MAX(t3.optionPoints)*(
                                SELECT
                                      COUNT(*) AS cnt
                                FROM
                                      feedbackadv_survey_visible_to_users
                                WHERE
                                      feedbackMappingId
                                      IN
                                        (
                                           SELECT
                                                 DISTINCT feedbackMappingId
                                           FROM
                                                 feedbackadv_survey_mapping fsm
                                           WHERE
                                                 CONCAT_WS('~',fsm.feedbackSurveyId,fsm.feedbackCategoryId,fsm.feedbackQuestionSetId)
                                                  IN
                                                   (
                                                     SELECT
                                                           DISTINCT CONCAT_WS('~',feedbackSurveyId,feedbackCategoryId,feedbackQuestionSetId)
                                                     FROM
                                                           feedbackadv_to_question ftq
                                                     WHERE
                                                           t1.feedbackQuestionId=ftq.feedbackQuestionId
                                                           AND ftq.feedbackSurveyId=$labelId
                                                           AND ftq.feedbackCategoryId=$categoryId
                                                    )
                                         )
                                ) AS totalPoints
                     FROM
                         feedbackadv_questions t1,
                         feedbackadv_answer_set t2,
                         feedbackadv_answer_set_option t3
                     WHERE
                         t1.answerSetId=t2.answerSetId
                         AND t2.answerSetId=t3.answerSetId
                     GROUP BY t1.feedbackQuestionId
                    )
                   AS t
                 ";
      }
      else{
         /*
          $query="
                  SELECT
                    SUM(t.total) AS totalPoints
                    FROM (
                      SELECT
                            t1.feedbackQuestionId,
                            t2.answerSetId,
                            t3.optionLabel,
                            MAX(t3.optionPoints) *
                            (
                              SELECT
                                COUNT(*) AS cnt
                              FROM
                                 feedbackadv_survey_visible_to_users a ,
                                 feedbackadv_to_class_subjects b
                              WHERE
                                 a.feedbackMappingId = b.feedbackMappingId
                                 AND
                                  a.feedbackMappingId
                                  IN
                                   (
                                    SELECT
                                          DISTINCT feedbackMappingId
                                    FROM
                                          feedbackadv_survey_mapping fsm
                                    WHERE
                                          CONCAT_WS('~',fsm.feedbackSurveyId,fsm.feedbackCategoryId,fsm.feedbackQuestionSetId)
                                           IN
                                           (
                                              SELECT
                                                    DISTINCT CONCAT_WS('~',feedbackSurveyId,feedbackCategoryId,feedbackQuestionSetId)
                                              FROM
                                                    feedbackadv_to_question ftq
                                              WHERE
                                                    t1.feedbackQuestionId=ftq.feedbackQuestionId
                                                    AND ftq.feedbackSurveyId=$labelId
                                                    AND ftq.feedbackCategoryId=$categoryId
                                           )
                                      )
                            ) AS total
                    FROM
                         feedbackadv_questions t1,
                         feedbackadv_answer_set t2,
                         feedbackadv_answer_set_option t3
                    WHERE
                         t1.answerSetId=t2.answerSetId
                         AND t2.answerSetId=t3.answerSetId
                         GROUP BY t1.feedbackQuestionId
             ) as t
          ";
          */
          $query="select
                         sum(maxOptionPoints) totalPoints from (
                select
                        c.userId, d.studentId, f.groupId , d.classId, k.employeeId, k.subjectId,
                        c.feedbackMappingId, n.feedbackQuestionId, o.answerSetId, p.answerSetName,
                        (select max(q.optionPoints) from feedbackadv_answer_set_option q where q.answerSetId = p.answerSetId) as maxOptionPoints
                from         feedbackadv_survey_visible_to_users c, student d, feedbackadv_to_class_subjects e, student_groups f, subject g,
                        `group` h, subject_type i, group_type j, feedbackadv_teacher_mapping k, feedbackadv_survey_mapping m,
                        feedbackadv_to_question n, feedbackadv_questions o, feedbackadv_answer_set p
                where         c.roleId = 4
                and         c.feedbackMappingId in (
                                    select
                                        b.feedbackMappingId
                                    from     feedbackadv_survey_mapping b,  feedbackadv_to_question a
                                    where     a.feedbackSurveyId = b.feedbackSurveyId
                                    AND     a.feedbackCategoryId = b.feedbackCategoryId
                                    and     a.feedbackQuestionSetId = b.feedbackQuestionSetId
                                    and     a.feedbackCategoryId in ($categoryId)
                                    and a.feedbackSurveyId = $labelId
                                    )
                and         c.userId = d.userId
                and         d.studentId = f.studentId
                and         d.classId = f.classId
                and         e.subjectId = g.subjectId
                and         f.groupId = h.groupId
                and         g.subjectTypeId = i.subjectTypeId
                and         h.groupTypeId = j.groupTypeId
                and         i.subjectTypeName = j.groupTypeName
                and         e.subjectId = k.subjectId
                and         f.groupId = k.groupId
                and         d.classId = k.classId
                and         k.feedbackSurveyId = $labelId
                and         c.feedbackMappingId = e.feedbackMappingId
                and         c.feedbackMappingId = m.feedbackMappingId
                and         m.feedbackSurveyId = n.feedbackSurveyId
                and         m.feedbackCategoryId = n.feedbackCategoryId
                and         m.feedbackSurveyId = n.feedbackSurveyId
                and         n.feedbackQuestionId = o.feedbackQuestionId
                and         o.answerSetId = p.answerSetId
                ) as t
                ";
      }


        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function getPointsScoredForCategories($labelId,$categoryId) {
        $query = "
                  SELECT
                        IF(SUM(t.pointScored) IS NULL,0,SUM(t.pointScored)) AS pointsScored
                  FROM
                       (
                         SELECT
                                f1.answerSetOptionId ,
                                COUNT(f1.answerSetOptionId)*(f2.optionPoints) AS pointScored
                         FROM
                                feedbackadv_survey_answer f1,
                                feedbackadv_answer_set_option f2
                         WHERE
                                feedbackToQuestionId
                                 IN
                                  (
                                    SELECT
                                          feedbackToQuestionId
                                    FROM
                                          feedbackadv_to_question
                                    WHERE
                                          feedbackSurveyId=$labelId
                                          AND feedbackCategoryId=$categoryId
                                  )
                        AND f1.answerSetOptionId!=-1
                        AND f1.answerSetOptionId=f2.answerSetOptionId
                        GROUP BY f1.answerSetOptionId
                       ) AS t
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

  public function getEmployeesFromAnswerTable($conditions='',$employeeId='',$classCondition='') {
      $employeeCondition='';
      if($employeeId!=''){
         $employeeCondition=' AND employeeId='.$employeeId;
      }
        $query = "SELECT
                        DISTINCT e.employeeId,e.employeeName
                  FROM
                        employee e
                  WHERE
                        e.employeeId
                                    IN
                                       (
                                         SELECT
                                                DISTINCT employeeId
                                         FROM
                                                feedbackadv_survey_answer
                                         WHERE
                                                employeeId IS NOT NULL
                                                $classCondition
                                                $employeeCondition
                                                AND feedbackMappingId
                                                          IN
                                                            (
                                                              SELECT
                                                                     DISTINCT feedbackMappingId
                                                              FROM
                                                                     feedbackadv_survey_mapping
                                                              $conditions
                                                            )

                                       )
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


public function getTotalPageCount($timeTableLabelId,$labelId,$classId,$employeeId='') {

        $employeeCondition='';
        if($employeeId!=''){
            $employeeCondition=' AND a.employeeId='.$employeeId;
        }
        $query = "SELECT
                        COUNT(distinct(concat(subjectId,'#',employeeId))) AS totalPages
                  FROM
                        feedbackadv_survey_answer a,
                        feedbackadv_to_question b,feedbackadv_survey d
                  WHERE
                        a.feedbackToQuestionId=b.feedbackToQuestionId
                        AND b.feedbackSurveyId=d.feedbackSurveyId
                        AND d.feedbackSurveyId=$labelId
                        AND d.timeTableLabelId=$timeTableLabelId
                        AND (a.classId IS NOT NULL OR a.classId!=0)
                        AND a.classId=$classId
                        $employeeCondition
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

public function getClassFromAnswerTable($timeTableLabelId,$labelId,$classId='') {

        $classCondition='';
        if($classId!=''){
            $classCondition=' AND a.classId='.$classId;
        }
        $query = "SELECT
                        DISTINCT c.classId,c.className
                  FROM
                        class c,feedbackadv_survey_answer a,
                        feedbackadv_to_question b,feedbackadv_survey d
                  WHERE
                        c.classId=a.classId
                        AND a.feedbackToQuestionId=b.feedbackToQuestionId
                        AND b.feedbackSurveyId=d.feedbackSurveyId
                        AND d.feedbackSurveyId=$labelId
                        AND d.timeTableLabelId=$timeTableLabelId
                        AND (a.classId IS NOT NULL OR a.classId!=0)
                        $classCondition
                  ORDER BY c.studyPeriodId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


public function getSubjectsFromAnswerTable($timeTableLabelId,$labelId,$classId='',$employeeId='') {

        $classCondition='';
        if($classId!=''){
            $classCondition=' AND a.classId='.$classId;
        }
        $employeeCondition='';
        if($employeeId!=''){
            $employeeCondition=' AND a.employeeId='.$employeeId;
        }
        $query = "SELECT
                        DISTINCT s.subjectId,s.subjectCode,s.subjectName
                  FROM
                        `class` c,feedbackadv_survey_answer a,
                        feedbackadv_to_question b,feedbackadv_survey d,
                        `subject` s
                  WHERE
                        c.classId=a.classId
                        AND a.subjectId=s.subjectId
                        AND a.feedbackToQuestionId=b.feedbackToQuestionId
                        AND b.feedbackSurveyId=d.feedbackSurveyId
                        AND d.feedbackSurveyId=$labelId
                        AND d.timeTableLabelId=$timeTableLabelId
                        AND (a.classId IS NOT NULL OR a.classId!=0)
                        $classCondition
                        $employeeCondition
                  ORDER BY s.subjectTypeId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

public function getActualEmplyeesFromAnswerTable($timeTableLabelId,$labelId,$classId,$subjectId,$emplyeeId='') {

    $employeeCondition='';
    if($emplyeeId!=''){
        $employeeCondition=' AND a.employeeId='.$emplyeeId;
    }

        $query = "SELECT
                        DISTINCT e.employeeId,e.employeeCode,e.employeeName
                  FROM
                        `class` c,feedbackadv_survey_answer a,
                        feedbackadv_to_question b,feedbackadv_survey d,
                        `subject` s,employee e
                  WHERE
                        c.classId=a.classId
                        AND a.subjectId=s.subjectId
                        AND a.employeeId=e.employeeId
                        AND a.feedbackToQuestionId=b.feedbackToQuestionId
                        AND b.feedbackSurveyId=d.feedbackSurveyId
                        AND d.feedbackSurveyId=$labelId
                        AND d.timeTableLabelId=$timeTableLabelId
                        AND (a.classId IS NOT NULL OR a.classId!=0)
                        AND (a.employeeId IS NOT NULL OR a.employeeId!=0)
                        AND a.classId=$classId
                        AND a.subjectId=$subjectId
                        $employeeCondition
                  ORDER BY e.employeeName
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

public function getMaxOptionsLength($classIds) {

        $query = "SELECT
                        MAX(t.cnt) as maxOptionLength
                  FROM
                       (
                         SELECT
                               COUNT(DISTINCT d.answerSetOptionId) AS cnt
                         FROM
                               feedbackadv_survey_answer a,feedbackadv_to_question b,
                               feedbackadv_questions c,feedbackadv_answer_set_option d
                         WHERE
                               a.feedbackToQuestionId=b.feedbackToQuestionId
                               AND b.feedbackQuestionId=c.feedbackQuestionId
                               AND c.answerSetId=d.answerSetId
                               AND a.classId IN ( $classIds )
                         GROUP BY c.feedbackQuestionId,c.answerSetId
                       ) AS t
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

public function getMaxOptionsLengthForCategories($categoryIds) {
       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
      /*
        $query = "SELECT
                        MAX(t.cnt) as maxOptionLength
                  FROM
                       (
                         SELECT
                               COUNT(DISTINCT d.answerSetOptionId) AS cnt
                         FROM
                               feedbackadv_survey_answer a,feedbackadv_to_question b,
                               feedbackadv_questions c,feedbackadv_answer_set_option d
                         WHERE
                               a.feedbackToQuestionId=b.feedbackToQuestionId
                               AND b.feedbackQuestionId=c.feedbackQuestionId
                               AND c.answerSetId=d.answerSetId
                               AND b.feedbackCategoryId IN ( $categoryIds )
                         GROUP BY c.feedbackQuestionId,c.answerSetId
                       ) AS t
                 ";
       */
       $query = "SELECT
                        MAX(t.cnt) as maxOptionLength
                  FROM
                       (
                         SELECT
                               COUNT(DISTINCT d.answerSetOptionId) AS cnt
                         FROM
                               feedbackadv_to_question b,
                               feedbackadv_questions c,
                               feedbackadv_answer_set_option d,
                               feedbackadv_answer_set e
                         WHERE
                               b.feedbackQuestionId=c.feedbackQuestionId
                               AND c.answerSetId=d.answerSetId
                               AND d.answerSetId=e.answerSetId
                               AND e.instituteId=$instituteId
                               AND b.feedbackCategoryId IN ( $categoryIds )
                         GROUP BY c.feedbackQuestionId,c.answerSetId
                       ) AS t
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

public function getOptionsInformation($labelId,$classId='') {
        $classCondition='';
        if($classId!=''){
            $classCondition=' WHERE classId='.$classId;
        }

        $query = "SELECT
                         DISTINCT answerSetOptionId,optionLabel,optionPoints
                 FROM
                         feedbackadv_answer_set_option
                 WHERE
                         answerSetId
                                 IN
                                     (
                                       SELECT
                                             DISTINCT answerSetId
                                       FROM
                                             feedbackadv_questions
                                       WHERE
                                             feedbackQuestionId
                                               IN
                                                 (
                                                    SELECT
                                                          DISTINCT feedbackQuestionId
                                                    FROM
                                                          feedbackadv_to_question
                                                    WHERE
                                                          feedbackToQuestionId
                                                            IN
                                                              (
                                                                SELECT
                                                                      DISTINCT feedbackToQuestionId
                                                                FROM
                                                                      feedbackadv_survey_answer
                                                                      $classCondition
                                                              )
                                                          AND feedbackSurveyId=$labelId
                                                    )
                                     )
                   ORDER BY optionPoints
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


public function getOptionsInformationForEmployees($labelId,$categoryIds='-1') {
        $categoryCondition='';
        if($categoryIds!=''){
            $categoryCondition=' AND feedbackCategoryId IN ('.$categoryIds.')';
        }

        $query = "SELECT
                         DISTINCT answerSetOptionId,optionLabel,optionPoints
                 FROM
                         feedbackadv_answer_set_option
                 WHERE
                         answerSetId
                                 IN
                                     (
                                       SELECT
                                             DISTINCT answerSetId
                                       FROM
                                             feedbackadv_questions
                                       WHERE
                                             feedbackQuestionId
                                               IN
                                                 (
                                                    SELECT
                                                          DISTINCT feedbackQuestionId
                                                    FROM
                                                          feedbackadv_to_question
                                                    WHERE
                                                          feedbackSurveyId=$labelId
                                                          $categoryCondition
                                                 )
                                     )
                   ORDER BY optionPoints
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


 public function getMappedCategoriesForTeachers($labelId,$employeeId,$timeTableLabelId) {

        $query = "SELECT
                        DISTINCT c.feedbackCategoryId,c.feedbackCategoryName
                  FROM
                        feedbackadv_category  c,
                        feedbackadv_survey_mapping m
                  WHERE
                        c.feedbackCategoryId=m.feedbackCategoryId
                        AND m.feedbackSurveyId=$labelId
                        AND m.classId
                                    IN
                                       (
                                         SELECT
                                               DISTINCT classId
                                         FROM
                                               feedbackadv_teacher_mapping
                                         WHERE
                                               timeTableLabelId = $timeTableLabelId
                                               AND employeeId   = $employeeId
                                       )
                        AND m.feedbackMappingId
                                                IN
                                                    (
                                                      SELECT
                                                             DISTINCT a.feedbackMappingId
                                                      FROM
                                                             feedbackadv_survey_answer a, feedbackadv_to_question b
                                                      WHERE
                                                             a.feedbackToQuestionId=b.feedbackToQuestionId
                                                             AND b.feedbackSurveyId=$labelId
                                                             AND a.employeeId=$employeeId
                                                    )
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


 public function getMappedSubjectsForTeachers($labelId,$categoryId,$employeeId,$timeTableLabelId,$classId) {

         $query="SELECT
                        DISTINCT s.subjectId,s.subjectCode,s.subjectName
                  FROM
                        subject s
                  WHERE
                        s.subjectId
                                   IN
                                       (
                                         SELECT
                                               DISTINCT subjectId
                                         FROM
                                               feedbackadv_teacher_mapping
                                         WHERE
                                               timeTableLabelId = $timeTableLabelId
                                               AND employeeId   = $employeeId
                                               AND classId=$classId
                                       )
                        AND s.subjectId
                                      IN
                                        (
                                          SELECT
                                                 DISTINCT a.subjectId
                                          FROM
                                                 feedbackadv_survey_answer a,
                                                 feedbackadv_survey_mapping b
                                          WHERE
                                                 a.employeeId=$employeeId
                                                 AND a.classId=$classId
                                                 AND a.feedbackMappingId=b.feedbackMappingId
                                                 AND b.feedbackSurveyId=$labelId
                                                 AND b.feedbackCategoryId=$categoryId
                                        )
               ";

     /*
        $query = "SELECT
                        DISTINCT s.subjectId,s.subjectCode,s.subjectName
                  FROM
                        subject s
                  WHERE
                        s.subjectId
                                   IN
                                       (
                                         SELECT
                                               DISTINCT subjectId
                                         FROM
                                               feedbackadv_teacher_mapping
                                         WHERE
                                               timeTableLabelId = $timeTableLabelId
                                               AND employeeId   = $employeeId
                                               AND
                                                   classId
                                                           IN
                                                             (
                                                               SELECT
                                                                     DISTINCT classId
                                                               FROM
                                                                     feedbackadv_survey_mapping
                                                               WHERE
                                                                     feedbackSurveyId=$labelId
                                                                     AND feedbackCategoryId=$categoryId
                                                             )
                                       )
                 ";
        */

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

 public function getMappedClassesForTeachers($labelId,$categoryId,$employeeId,$timeTableLabelId) {

        $query = "SELECT
                        DISTINCT c.classId,c.className
                  FROM
                        class c
                  WHERE
                        c.classId
                                   IN
                                       (
                                         SELECT
                                               DISTINCT classId
                                         FROM
                                               feedbackadv_teacher_mapping
                                         WHERE
                                               timeTableLabelId = $timeTableLabelId
                                               AND employeeId   = $employeeId
                                               AND
                                                   classId
                                                           IN
                                                             (
                                                               SELECT
                                                                     DISTINCT classId
                                                               FROM
                                                                     feedbackadv_survey_mapping
                                                               WHERE
                                                                     feedbackSurveyId=$labelId
                                                                     AND feedbackCategoryId=$categoryId
                                                             )
                                       )
                        AND c.classId
                                      IN
                                        (
                                          SELECT
                                                 DISTINCT a.classId
                                          FROM
                                                 feedbackadv_survey_answer a,
                                                 feedbackadv_survey_mapping b
                                          WHERE
                                                 a.employeeId=$employeeId
                                                 AND a.feedbackMappingId=b.feedbackMappingId
                                                 AND b.feedbackSurveyId=$labelId
                                                 AND b.feedbackCategoryId=$categoryId
                                        )
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


public function getTotalPointsForCategoriesForTeachers($labelId,$categoryId,$employeeId,$timeTableLabelId,$subjectFlag=-1) {
    if($subjectFlag==-1){
        $query = "
                  SELECT
                        SUM(t.totalPoints) AS totalPoints
                  FROM
                    (
                      SELECT
                            t1.feedbackQuestionId,
                            t2.answerSetId,
                            t3.optionLabel,
                            MAX(t3.optionPoints)*(
                                SELECT
                                      COUNT(*) AS cnt
                                FROM
                                      feedbackadv_survey_visible_to_users
                                WHERE
                                      feedbackMappingId
                                      IN
                                        (
                                           SELECT
                                                 DISTINCT feedbackMappingId
                                           FROM
                                                 feedbackadv_survey_mapping fsm
                                           WHERE
                                                 CONCAT_WS('~',fsm.feedbackSurveyId,fsm.feedbackCategoryId,fsm.feedbackQuestionSetId)
                                                  IN
                                                   (
                                                     SELECT
                                                           DISTINCT CONCAT_WS('~',feedbackSurveyId,feedbackCategoryId,feedbackQuestionSetId)
                                                     FROM
                                                           feedbackadv_to_question ftq
                                                     WHERE
                                                           t1.feedbackQuestionId=ftq.feedbackQuestionId
                                                           AND ftq.feedbackSurveyId=$labelId
                                                           AND ftq.feedbackCategoryId=$categoryId
                                                    )
                                                 AND fsm.classId IN
                                                               (
                                                                 SELECT
                                                                       DISTINCT classId
                                                                 FROM
                                                                       feedbackadv_teacher_mapping
                                                                 WHERE
                                                                       timeTableLabelId=$timeTableLabelId
                                                                       AND employeeId=$employeeId
                                                               )
                                         )
                                ) AS totalPoints
                     FROM
                         feedbackadv_questions t1,
                         feedbackadv_answer_set t2,
                         feedbackadv_answer_set_option t3
                     WHERE
                         t1.answerSetId=t2.answerSetId
                         AND t2.answerSetId=t3.answerSetId
                     GROUP BY t1.feedbackQuestionId
                    )
                   AS t
                 ";
    }
    else{
        $query="
                SELECT
                        SUM(t.totalPoints) AS totalPoints
                  FROM
                    (
                      SELECT
                            t1.feedbackQuestionId,
                            t2.answerSetId,
                            t3.optionLabel,
                            MAX(t3.optionPoints)*(
                                SELECT
                                      COUNT(*) AS cnt
                                FROM
                                      feedbackadv_survey_visible_to_users a ,
                                      feedbackadv_to_class_subjects b
                                WHERE
                                      a.feedbackMappingId = b.feedbackMappingId
                                      AND b.subjectId IN
                                       (
                                         SELECT
                                                  DISTINCT
                                                          subjectId
                                                  FROM
                                                          feedbackadv_teacher_mapping
                                                  WHERE
                                                          timeTableLabelId=$timeTableLabelId
                                                          AND employeeId=$employeeId
                                        )
                                      AND a.feedbackMappingId
                                       IN
                                        (
                                           SELECT
                                                 DISTINCT feedbackMappingId
                                           FROM
                                                 feedbackadv_survey_mapping fsm
                                           WHERE
                                                 CONCAT_WS('~',fsm.feedbackSurveyId,fsm.feedbackCategoryId,fsm.feedbackQuestionSetId)
                                                  IN
                                                   (
                                                     SELECT
                                                           DISTINCT CONCAT_WS('~',feedbackSurveyId,feedbackCategoryId,feedbackQuestionSetId)
                                                     FROM
                                                           feedbackadv_to_question ftq
                                                     WHERE
                                                           t1.feedbackQuestionId=ftq.feedbackQuestionId
                                                           AND ftq.feedbackSurveyId=$labelId
                                                           AND ftq.feedbackCategoryId=$categoryId
                                                    )
                                                 AND fsm.classId IN
                                                               (
                                                                 SELECT
                                                                       DISTINCT classId
                                                                 FROM
                                                                       feedbackadv_teacher_mapping
                                                                 WHERE
                                                                       timeTableLabelId=$timeTableLabelId
                                                                       AND employeeId=$employeeId
                                                               )
                                         )
                                ) AS totalPoints
                     FROM
                         feedbackadv_questions t1,
                         feedbackadv_answer_set t2,
                         feedbackadv_answer_set_option t3
                     WHERE
                         t1.answerSetId=t2.answerSetId
                         AND t2.answerSetId=t3.answerSetId
                     GROUP BY t1.feedbackQuestionId
                    )
                   AS t
        ";
      }
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

public function getPointsScoredForCategoriesForTeachers($labelId,$categoryId,$employeeId) {
        $query = "
                  SELECT
                        IF(SUM(t.pointScored) IS NULL,0,SUM(t.pointScored)) AS pointsScored
                  FROM
                       (
                         SELECT
                                f1.answerSetOptionId ,
                                COUNT(f1.answerSetOptionId)*(f2.optionPoints) AS pointScored
                         FROM
                                feedbackadv_survey_answer f1,
                                feedbackadv_answer_set_option f2
                         WHERE
                                feedbackToQuestionId
                                 IN
                                  (
                                    SELECT
                                          feedbackToQuestionId
                                    FROM
                                          feedbackadv_to_question
                                    WHERE
                                          feedbackSurveyId=$labelId
                                          AND feedbackCategoryId=$categoryId
                                  )
                        AND f1.employeeId=$employeeId
                        AND f1.answerSetOptionId!=-1
                        AND f1.answerSetOptionId=f2.answerSetOptionId
                        GROUP BY f1.answerSetOptionId
                       ) AS t
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function getTotalPointsForSubjectCategoriesForTeachers($labelId,$categoryId,$employeeId,$timeTableLabelId,$subjectId,$classId) {

       $query="
               SELECT
                     t.classId, t.subjectId,
                     SUM(t.maxOptionPoints) AS totalPoints
               FROM (
                      SELECT
                            a.feedbackToQuestionId, b.feedbackQuestionId, c.answerSetId, a.classId, a.subjectId,
                            (
                              SELECT
                                    MAX(d.optionPoints)
                              FROM
                                    feedbackadv_answer_set_option d
                              WHERE
                                   c.answerSetId = d.answerSetId
                            ) AS maxOptionPoints
                      FROM
                            feedbackadv_survey_answer a, feedbackadv_to_question b, feedbackadv_questions c
                      WHERE
                            a.employeeId=$employeeId
                            AND a.answerSetOptionId != -1
                            AND a.feedbackToQuestionId = b.feedbackToQuestionId
                            AND b.feedbackQuestionId = c.feedbackQuestionId
                            AND b.feedbackSurveyId=$labelId
                            AND b.feedbackCategoryId=$categoryId
                            AND a.classId=$classId
                            AND a.subjectId=$subjectId
                ) AS t
             GROUP BY t.classId, t.subjectId;
       ";
       /*
        $query="
                SELECT
                        SUM(t.totalPoints) AS totalPoints
                  FROM
                    (
                      SELECT
                            t1.feedbackQuestionId,
                            t2.answerSetId,
                            t3.optionLabel,
                            MAX(t3.optionPoints)*(
                                SELECT
                                      COUNT(*) AS cnt
                                FROM
                                      feedbackadv_survey_visible_to_users a ,
                                      feedbackadv_to_class_subjects b
                                WHERE
                                      a.feedbackMappingId = b.feedbackMappingId
                                      AND b.subjectId=$subjectId
                                      AND
                                      a.feedbackMappingId
                                      IN
                                        (
                                           SELECT
                                                 DISTINCT feedbackMappingId
                                           FROM
                                                 feedbackadv_survey_mapping fsm
                                           WHERE
                                                 CONCAT_WS('~',fsm.feedbackSurveyId,fsm.feedbackCategoryId,fsm.feedbackQuestionSetId)
                                                  IN
                                                   (
                                                     SELECT
                                                           DISTINCT CONCAT_WS('~',feedbackSurveyId,feedbackCategoryId,feedbackQuestionSetId)
                                                     FROM
                                                           feedbackadv_to_question ftq
                                                     WHERE
                                                           t1.feedbackQuestionId=ftq.feedbackQuestionId
                                                           AND ftq.feedbackSurveyId=$labelId
                                                           AND ftq.feedbackCategoryId=$categoryId
                                                    )
                                                 AND fsm.classId IN
                                                               (
                                                                 SELECT
                                                                       DISTINCT classId
                                                                 FROM
                                                                       feedbackadv_teacher_mapping
                                                                 WHERE
                                                                       timeTableLabelId=$timeTableLabelId
                                                                       AND employeeId=$employeeId
                                                                       AND subjectId =$subjectId
                                                               )
                                         )
                                ) AS totalPoints
                     FROM
                         feedbackadv_questions t1,
                         feedbackadv_answer_set t2,
                         feedbackadv_answer_set_option t3
                     WHERE
                         t1.answerSetId=t2.answerSetId
                         AND t2.answerSetId=t3.answerSetId
                     GROUP BY t1.feedbackQuestionId
                    )
                   AS t
        ";

       */

      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


public function getPointsScoredForSubjectCategoriesForTeachers($labelId,$categoryId,$employeeId,$timeTableLabelId,$subjectId,$classId) {

      $query = "
                  SELECT
                        IF(SUM(t.pointScored) IS NULL,0,SUM(t.pointScored)) AS pointsScored
                  FROM
                       (
                         SELECT
                                f1.answerSetOptionId ,
                                COUNT(f1.answerSetOptionId)*(f2.optionPoints) AS pointScored
                         FROM
                                feedbackadv_survey_answer f1,
                                feedbackadv_answer_set_option f2
                         WHERE
                                feedbackToQuestionId
                                 IN
                                  (
                                    SELECT
                                          feedbackToQuestionId
                                    FROM
                                          feedbackadv_to_question
                                    WHERE
                                          feedbackSurveyId=$labelId
                                          AND feedbackCategoryId=$categoryId
                                  )
                        AND f1.employeeId=$employeeId
                        AND f1.subjectId=$subjectId
                        AND f1.classId=$classId
                        AND f1.answerSetOptionId!=-1
                        AND f1.answerSetOptionId=f2.answerSetOptionId
                        GROUP BY f1.answerSetOptionId
                       ) AS t
                 ";

      /*
       $query = "
                  SELECT
                        IF(SUM(t.pointScored) IS NULL,0,SUM(t.pointScored)) AS pointsScored
                  FROM
                       (
                         SELECT
                                f1.answerSetOptionId ,
                                COUNT(f1.answerSetOptionId)*(f2.optionPoints) AS pointScored
                         FROM
                                feedbackadv_survey_answer f1,
                                feedbackadv_answer_set_option f2
                         WHERE
                                feedbackToQuestionId
                                 IN
                                  (
                                    SELECT
                                          feedbackToQuestionId
                                    FROM
                                          feedbackadv_to_question
                                    WHERE
                                          feedbackSurveyId=$labelId
                                          AND feedbackCategoryId=$categoryId
                                  )
                        AND f1.employeeId=$employeeId
                        AND f1.subjectId=$subjectId
                        AND f1.classId
                                IN
                                  (
                                    SELECT
                                           DISTINCT classId
                                    FROM
                                           feedbackadv_teacher_mapping
                                    WHERE
                                           timeTableLabelId=$timeTableLabelId
                                           AND employeeId=$employeeId
                                           AND subjectId =$subjectId
                                  )
                        AND f1.answerSetOptionId!=-1
                        AND f1.answerSetOptionId=f2.answerSetOptionId
                        GROUP BY f1.answerSetOptionId
                       ) AS t
                 ";
         */
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getGPAScalingFactor($labelId,$categoryId) {
       $query = "SELECT
                        MAX(optionPoints) AS gpaScalingFactor
                  FROM
                        feedbackadv_answer_set_option
                  WHERE
                        answerSetId
                         IN
                           (
                             SELECT
                                    DISTINCT answerSetId
                             FROM
                                    feedbackadv_questions
                             WHERE
                                    feedbackQuestionId
                                     IN
                                       (
                                         SELECT
                                                DISTINCT feedbackQuestionId
                                         FROM
                                                feedbackadv_to_question
                                         WHERE
                                                feedbackSurveyId=$labelId
                                                AND feedbackCategoryId=$categoryId
                                       )
                           )
                   GROUP BY  answerSetId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getTeacherResponseCount($labelId,$classId,$subjectId,$employeeId) {


        $query = "SELECT
                        a.feedbackQuestionId,
                        a.feedbackQuestion,
                        d.feedbackCategoryId ,
                        b.optionLabel,
                        (
                          SELECT
                                COUNT(c.feedbackSurveyAnswerId) AS answerCount
                          FROM
                                feedbackadv_survey_answer c
                          WHERE
                                b.answerSetOptionId = c.answerSetOptionId
                                AND c.feedbackToQuestionId = d.feedbackToQuestionId
                                AND c.employeeId = $employeeId
                                AND c.classId=$classId
                                AND c.subjectId=$subjectId
                        ) AS answerCount
                 FROM
                        feedbackadv_questions a,
                        feedbackadv_answer_set_option b,
                        feedbackadv_to_question d
                 WHERE
                        a.answerSetId = b.answerSetId
                        AND a.feedbackQuestionId = d.feedbackQuestionId
                        AND d.feedbackToQuestionId
                         IN
                          (
                            SELECT
                                  DISTINCT f.feedbackToQuestionId
                            FROM
                                  feedbackadv_survey_answer f
                            WHERE
                                 f.classId=$classId
                                 AND f.subjectId=$subjectId
                                 AND f.employeeId=$employeeId
                          )
                 AND d.feedbackSurveyId = $labelId
                 ORDER BY a.feedbackQuestionId,b.optionPoints
               ";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


public function getEmployeeResponseCount($labelId,$categoryId,$employeeId='') {

    $employeeConditions='';
    if($employeeId!=''){
        $employeeConditions=' AND c.employeeId='.$employeeId;
    }


        $query = "SELECT
                        a.feedbackQuestionId,
                        a.feedbackQuestion,
                        d.feedbackCategoryId ,
                        b.optionLabel,
                        (
                          SELECT
                                COUNT(c.feedbackSurveyAnswerId) AS answerCount
                          FROM
                                feedbackadv_survey_answer c
                          WHERE
                                b.answerSetOptionId = c.answerSetOptionId
                                AND c.feedbackToQuestionId = d.feedbackToQuestionId
                                $employeeConditions
                        ) AS answerCount
                 FROM
                        feedbackadv_questions a,
                        feedbackadv_answer_set_option b,
                        feedbackadv_to_question d
                 WHERE
                        a.answerSetId = b.answerSetId
                        AND a.feedbackQuestionId = d.feedbackQuestionId
                        AND d.feedbackCategoryId=$categoryId
                        AND d.feedbackSurveyId = $labelId
                 ORDER BY a.feedbackQuestionId,b.optionPoints
               ";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

public function getTotalPageCountForEmployees($labelId,$categoryIds) {
        $query = "SELECT
                        COUNT(DISTINCT a.feedbackQuestionId,d.feedbackCategoryId) AS totalPages

                 FROM
                        feedbackadv_questions a,
                        feedbackadv_answer_set_option b,
                        feedbackadv_to_question d
                 WHERE
                        a.answerSetId = b.answerSetId
                        AND a.feedbackQuestionId = d.feedbackQuestionId
                        AND d.feedbackCategoryId IN ($categoryIds)
                        AND d.feedbackSurveyId = $labelId
                 ORDER BY a.feedbackQuestionId,b.optionPoints
               ";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

public function getTeacherQuestionWiseGPA($labelId,$classId,$subjectId,$employeeId) {


        $query = "SELECT
                        feedbackQuestionId,
                        SUM(t.individualPoints) AS points,
                        SUM(t.answerCount) AS counts,
                        SUM(t.individualPoints) / SUM(t.answerCount) AS gpa
                        FROM (
                               SELECT
                                     a.feedbackQuestionId,
                                     b.optionLabel,
                                     b.optionPoints,
                                     (
                                       SELECT
                                             COUNT(c.feedbackSurveyAnswerId) AS answerCount
                                       FROM
                                             feedbackadv_survey_answer c
                                       WHERE
                                             b.answerSetOptionId = c.answerSetOptionId
                                             AND c.feedbackToQuestionId = d.feedbackToQuestionId
                                             AND c.employeeId = $employeeId
                                             AND c.subjectId=$subjectId
                                             AND c.classId=$classId
                                      ) AS answerCount,
                                      (
                                        SELECT
                                              COUNT(c.feedbackSurveyAnswerId)*b.optionPoints AS individualPoints
                                        FROM
                                              feedbackadv_survey_answer c
                                        WHERE
                                              b.answerSetOptionId = c.answerSetOptionId
                                              AND c.feedbackToQuestionId = d.feedbackToQuestionId
                                              AND c.employeeId = $employeeId
                                              AND c.subjectId=$subjectId
                                              AND c.classId=$classId
                                        ) AS individualPoints
                                 FROM
                                           feedbackadv_questions a,
                                           feedbackadv_answer_set_option b,
                                           feedbackadv_to_question d
                                 WHERE
                                           a.answerSetId = b.answerSetId
                                           AND a.feedbackQuestionId = d.feedbackQuestionId
                                           AND d.feedbackToQuestionId
                                           IN
                                             (
                                               SELECT
                                                     DISTINCT f.feedbackToQuestionId
                                               FROM
                                                     feedbackadv_survey_answer f
                                               WHERE
                                                     f.classId=$classId
                                                     AND f.subjectId=$subjectId
                                                     AND f.employeeId=$employeeId
                                              )
                                 AND d.feedbackSurveyId = $labelId
                                 ORDER BY a.feedbackQuestionId
                               ) as t
                        GROUP BY t.feedbackQuestionId
                        ORDER BY t.feedbackQuestionId;
               ";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


public function getEmployeeQuestionWiseGPA($labelId,$categoryId,$employeeId='') {

        $employeeConditions='';
        if($employeeId!=''){
          $employeeConditions=' AND c.employeeId='.$employeeId;
        }
        $query = "SELECT
                        feedbackQuestionId,
                        SUM(t.individualPoints) AS points,
                        SUM(t.answerCount) AS counts,
                        SUM(t.individualPoints) / SUM(t.answerCount) AS gpa
                        FROM (
                               SELECT
                                     a.feedbackQuestionId,
                                     b.optionLabel,
                                     b.optionPoints,
                                     (
                                       SELECT
                                             COUNT(c.feedbackSurveyAnswerId) AS answerCount
                                       FROM
                                             feedbackadv_survey_answer c
                                       WHERE
                                             b.answerSetOptionId = c.answerSetOptionId
                                             AND c.feedbackToQuestionId = d.feedbackToQuestionId
                                             $employeeConditions
                                      ) AS answerCount,
                                      (
                                        SELECT
                                              COUNT(c.feedbackSurveyAnswerId)*b.optionPoints AS individualPoints
                                        FROM
                                              feedbackadv_survey_answer c
                                        WHERE
                                              b.answerSetOptionId = c.answerSetOptionId
                                              AND c.feedbackToQuestionId = d.feedbackToQuestionId
                                              $employeeConditions
                                        ) AS individualPoints
                                 FROM
                                           feedbackadv_questions a,
                                           feedbackadv_answer_set_option b,
                                           feedbackadv_to_question d
                                 WHERE
                                           a.answerSetId = b.answerSetId
                                           AND a.feedbackQuestionId = d.feedbackQuestionId
                                           AND d.feedbackToQuestionId
                                           AND d.feedbackCategoryId=$categoryId
                                           AND d.feedbackSurveyId = $labelId
                                 ORDER BY a.feedbackQuestionId
                               ) as t
                        GROUP BY t.feedbackQuestionId
                        ORDER BY t.feedbackQuestionId;
               ";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }



    public function getFeedbackCommentsList($conditions='', $limit = '', $orderBy=' c.className') {

        $query = "SELECT
                        c.className,
                        s.subjectCode,s.subjectName,
                        e.employeeName,
                        f.comments
                  FROM
                        feedbackadv_survey_comments f,
                        `class` c,`subject` s,employee e,
                        feedbackadv_survey fs
                  WHERE
                        f.classId=c.classId
                        AND f.subjectId=s.subjectId
                        AND f.employeeId=e.employeeId
                        AND f.feedbackSurveyId=fs.feedbackSurveyId
                        $conditions
                  ORDER BY $orderBy
                  $limit
                  ";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


    public function getTotalFeedbackComments($conditions='') {

        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
                        feedbackadv_survey_comments f,
                        `class` c,`subject` s,employee e,
                        feedbackadv_survey fs
                  WHERE
                        f.classId=c.classId
                        AND f.subjectId=s.subjectId
                        AND f.employeeId=e.employeeId
                        AND f.feedbackSurveyId=fs.feedbackSurveyId
                        $conditions
                  ";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


    public function getFeedbackCommentsFromEmployeesList($conditions='', $limit = '', $orderBy=' fs.feedbackSurveyLabel') {

        $query = "SELECT
                        fs.feedbackSurveyLabel,
                        fc.feedbackCategoryName,
                        f.comments,
                        c.className,
                        s.subjectCode,s.subjectName,
                        e.employeeName,
								stu.rollNo,
								concat(stu.firstName,' ',stu.lastName) as studentName
                  FROM
                        student stu,
								feedbackadv_survey fs,
                        feedbackadv_category fc,
                        feedbackadv_survey_comments f
                        LEFT JOIN employee e ON e.employeeId=f.employeeId
                        LEFT JOIN class c ON c.classId=f.classId
                        LEFT JOIN `subject` s ON s.subjectId=f.subjectId
                  WHERE
                        f.feedbackSurveyId=fs.feedbackSurveyId
								and f.userId = stu.userId
                        AND f.feedbackCategoryId=fc.feedbackCategoryId
                        $conditions
                  ORDER BY $orderBy
                  $limit
                  ";

		 return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


    public function getTotalFeedbackCommentsFromEmployees($conditions='') {

        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
                        feedbackadv_survey fs,
                        feedbackadv_category fc,
                        feedbackadv_survey_comments f
                        LEFT JOIN employee e ON e.employeeId=f.employeeId
                        LEFT JOIN class c ON c.classId=f.classId
                        LEFT JOIN `subject` s ON s.subjectId=f.subjectId
                  WHERE
                        f.feedbackSurveyId=fs.feedbackSurveyId
                        AND f.feedbackCategoryId=fc.feedbackCategoryId
                        $conditions
                  ";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }



    public function getFeedbackLabelsForTeachers($conditions='') {

        $query = "SELECT
                        f.feedbackSurveyId,
                        f.feedbackSurveyLabel
                  FROM
                        feedbackadv_survey f,feedbackadv_survey_answer fa,
                        feedbackadv_survey_mapping fsm
                  WHERE
                        f.feedbackSurveyId=fsm.feedbackSurveyId
                        AND fsm.feedbackMappingId=fa.feedbackMappingId
                        AND f.isActive=1
                        $conditions
                  GROUP BY f.feedbackSurveyId
                  ";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }





//---------- functions for teacher final report ---------------

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET LABEL
//
// Author :Gurkeerat Sidhu
// Created on : (15.02.10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getSelectedTimeTableLabel($timeTableLabelId,$type=1) {

        $extraCondition='';
        if($type==2){
          $extraCondition=' AND feedbackSurveyId IN
                            (
                              SELECT
                                     DISTINCT fsm.feedbackSurveyId
                              FROM
                                     feedbackadv_survey_mapping fsm,
                                     feedbackadv_category fc
                              WHERE
                                     fc.feedbackCategoryId=fsm.feedbackCategoryId
                                     AND fc.subjectTypeId IS NOT NULL
                            )';
        }
        $query = "SELECT
                        DISTINCT feedbackSurveyId,feedbackSurveyLabel
                  FROM
                        feedbackadv_survey fas
                  WHERE
                        timeTableLabelId = $timeTableLabelId
                        AND isActive=1
                        $extraCondition
                  ";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET LABEL
//
// Author :Gurkeerat Sidhu
// Created on : (15.02.10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getTotalCount($feedbackToQuestionId,$employeeId,$answerSetOptionId) {


        $query = "    select
                            count(answerSetOptionId) as count
                      from
                            feedbackadv_survey_answer
                      where
                            feedbackToQuestionId = $feedbackToQuestionId
                            and employeeId = $employeeId
                            and answerSetOptionId = $answerSetOptionId";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET LABEL
//
// Author :Gurkeerat Sidhu
// Created on : (15.02.10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getQuestions($feedbackCategoryId,$feedbackSurveyId) {


        $query = "    SELECT
                            *
                      FROM
                            feedbackadv_to_question
                      WHERE
                            feedbackCategoryId = $feedbackCategoryId
                      AND
                            feedbackSurveyId = $feedbackSurveyId";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET LABEL
//
// Author :Gurkeerat Sidhu
// Created on : (15.02.10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getOptions($employeeId,$feedbackCategoryId,$feedbackSurveyId) {


        $query = "    select
                            a.feedbackQuestionId,
                            a.feedbackQuestion,
                            b.optionLabel,
                            (select count(c.feedbackSurveyAnswerId) as answerCount from feedbackadv_survey_answer c where b.answerSetOptionId = c.answerSetOptionId
                            and c.feedbackToQuestionId = d.feedbackToQuestionId and c.employeeId = $employeeId
                            ) as answerCount

                            from feedbackadv_questions a, feedbackadv_answer_set_option b, feedbackadv_to_question d
                            where  a.answerSetId = b.answerSetId
                            and a.feedbackQuestionId = d.feedbackQuestionId
                            and d.feedbackCategoryId = $feedbackCategoryId
                            and d.feedbackSurveyId = $feedbackSurveyId
                            order by a.feedbackQuestionId";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Periodicity
//
//orderBy: on which column to sort
//
// Author :Gurkeerat Sidhu
// Created on : (15.02.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getCategory12($labelId,$orderBy=' feedbackCategoryName') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT
                         *
                  FROM
                         feedbackadv_category
                  WHERE
                         subjectTypeId IS NOT NULL
                         AND feedbackCategoryId
                                             IN
                                                (
                                                   SELECT
                                                          DISTINCT feedbackCategoryId
                                                   FROM
                                                          feedbackadv_to_question
                                                    WHERE
                                                          feedbackSurveyId=$labelId
                                                )
                  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Periodicity
//
//orderBy: on which column to sort
//
// Author :Gurkeerat Sidhu
// Created on : (15.02.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getCategory($labelId,$teacherId='',$timeTableLabelId,$categoryId='',$orderBy=' feedbackCategoryName') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $categoryCondition='';
        $employeeCondition='';
         if($categoryId!=''){
            $categoryCondition=' AND feedbackCategoryId='.$categoryId;
         }
         if($teacherId!=''){
            $employeeCondition=' AND employeeId='.$teacherId;
         }
        $query =  "select
                         feedbackCategoryId,
                         feedbackCategoryName
                   from
                          feedbackadv_category
                   where
                          feedbackCategoryId
                           in
                             (
                               select
                                      distinct feedbackCategoryId
                               from
                                      feedbackadv_survey_mapping
                               where
                                      feedbackSurveyId=$labelId
                                      and classId in
                                                     (
                                                        select
                                                               distinct classId
                                                        from
                                                               feedbackadv_teacher_mapping
                                                        where
                                                               timeTableLabelId=$timeTableLabelId
                                                               $employeeCondition
                                                      )
                             )
                    $categoryCondition
                    ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET LABEL
// Author :Dipanjan Bhattacharjee
// Created on : (15.02.10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getCategoryResponseCount($feedbackCategoryId,$feedbackSurveyId) {
        $query = "
                  SELECT
                        t.feedbackCategoryId,
                        t.feedbackCategoryName,
                        t.feedbackQuestion,
                        t.feedbackQuestionId,
                        t.optionLabel,
                        t.responseCount,
                        IFNULL(t.optionPoints*t.responseCount,0) AS points
                    FROM (
                    SELECT
                        fc.feedbackCategoryId,
                        fc.feedbackCategoryName,
                        a.feedbackQuestion,
                        a.feedbackQuestionId,
                        b.optionLabel,
                        b.optionPoints,
                        (
                           SELECT
                                 COUNT(c.feedbackSurveyAnswerId) AS answerCount
                           FROM
                                 feedbackadv_survey_answer c
                           WHERE
                                 b.answerSetOptionId = c.answerSetOptionId
                                 AND c.feedbackToQuestionId = d.feedbackToQuestionId
                        ) As responseCount
                   FROM
                        feedbackadv_questions a,
                        feedbackadv_answer_set_option b,
                        feedbackadv_to_question d,
                        feedbackadv_category fc
                   WHERE
                        a.answerSetId = b.answerSetId
                        AND a.feedbackQuestionId = d.feedbackQuestionId
                        AND d.feedbackCategoryId=fc.feedbackCategoryId
                        AND fc.feedbackCategoryId=$feedbackCategoryId
                        AND d.feedbackSurveyId=$feedbackSurveyId
                   ORDER BY a.feedbackQuestionId
                   ) AS t ;";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


    public function getSelectedTimeTableLabelCategories($timeTableLabelId,$labelId,$categoryId='-1') {

        $categoryCondition='';
        if($categoryId!='-1'){
            $categoryCondition=' AND fc.feedbackCategoryId='.$categoryId;
        }
        $query = "SELECT
                         DISTINCT fc.feedbackCategoryId,fc.feedbackCategoryName
                  FROM
                         feedbackadv_category fc,
                         feedbackadv_survey fs,
                         feedbackadv_survey_mapping fsm
                  WHERE
                         fc.feedbackCategoryId=fsm.feedbackCategoryId
                         AND fsm.feedbackSurveyId=fs.feedbackSurveyId
                         AND fs.isActive=1
                         AND fs.timeTableLabelId=$timeTableLabelId
                         AND fs.feedbackSurveyId=$labelId
                         $categoryCondition
                  ";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


    public function getAllCategories($labelId,$orderBy=' feedbackCategoryName',$catId='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $categoryCondition='';
        if($catId!=''){
           $categoryCondition=' AND feedbackCategoryId='.$catId;
        }
        $query = "SELECT
                         *
                  FROM
                         feedbackadv_category
                  WHERE
                         feedbackCategoryId
                                             IN
                                                (
                                                   SELECT
                                                          DISTINCT feedbackCategoryId
                                                   FROM
                                                          feedbackadv_to_question
                                                    WHERE
                                                          feedbackSurveyId=$labelId
                                                )
                         $categoryCondition
                  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }



 public function getSurveyLabelType($labelId) {

        $query = "SELECT
                        COUNT(*) AS cnt
                  FROM
                        feedbackadv_survey fas
                  WHERE
                        isActive=1
                        AND feedbackSurveyId=$labelId
                        AND feedbackSurveyId IN
                            (
                              SELECT
                                     DISTINCT fsm.feedbackSurveyId
                              FROM
                                     feedbackadv_survey_mapping fsm,
                                     feedbackadv_category fc
                              WHERE
                                     fc.feedbackCategoryId=fsm.feedbackCategoryId
                                     AND fc.subjectTypeId IS NOT NULL
                            )
                  ";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

    public function getFeedbackList($condition='',$orderBy='studentName',$limit='' ) {

        if($orderBy=='') {
          $orderBy='studentName';
        }
        
        $query ="SELECT    
                        t.feedbackSurveyLabel, IFNULL(t.className,'".NOT_APPLICABLE_STRING."') AS className, t.studentName, 
                        IFNULL(t.rollNo,'".NOT_APPLICABLE_STRING."') AS rollNo, 
                        IFNULL(t.employeeName,'".NOT_APPLICABLE_STRING."') AS employeeName, 
                        IFNULL(t.subjectName,'".NOT_APPLICABLE_STRING."') AS subjectName, 
                        IFNULL(t.subjectCode,'".NOT_APPLICABLE_STRING."') AS subjectCode, 
                        IF(IFNULL(subjectName,'')='','".NOT_APPLICABLE_STRING."',
                            CONCAT(IFNULL(subjectName,''),' (',IFNULL(subjectCode,''),')')) AS tSubjectName, 
                        IFNULL(t.points,'".NOT_APPLICABLE_STRING."') AS points, feedbackCategoryName
                 FROM       
                       (SELECT
                                feedbackadv_survey.feedbackSurveyLabel
                                , class.className
                                , IFNULL(student.rollNo,'') AS rollNo
                                , CONCAT(IFNULL(student.firstName,''),' ',IFNULL(student.lastName,'')) AS studentName
                                , employee.employeeName
                                , subject.subjectName
                                , subject.subjectCode
                                , SUM(feedbackadv_answer_set_option.optionPoints) AS points
                                , feedbackadv_category.feedbackCategoryName
                        FROM
                                feedbackadv_survey_answer
                                LEFT JOIN feedbackadv_to_question 
                                    ON (feedbackadv_survey_answer.feedbackToQuestionId = feedbackadv_to_question.feedbackToQuestionId)
                                LEFT JOIN feedbackadv_questions 
                                    ON (feedbackadv_to_question.feedbackQuestionId = feedbackadv_questions.feedbackQuestionId)
                                LEFT JOIN feedbackadv_survey_mapping 
                                    ON (feedbackadv_survey_answer.feedbackMappingId = feedbackadv_survey_mapping.feedbackMappingId)
                                LEFT JOIN student 
                                    ON (feedbackadv_survey_answer.userId = student.userId)
                                LEFT JOIN employee 
                                    ON (feedbackadv_survey_answer.employeeId = employee.employeeId)
                                LEFT JOIN subject 
                                    ON (feedbackadv_survey_answer.subjectId = subject.subjectId)
                                LEFT JOIN feedbackadv_survey 
                                    ON (feedbackadv_survey_mapping.feedbackSurveyId = feedbackadv_survey.feedbackSurveyId)
                                LEFT JOIN class 
                                    ON (feedbackadv_survey_mapping.classId = class.classId)
                                LEFT JOIN feedbackadv_answer_set_option 
                                    ON (feedbackadv_answer_set_option.answerSetOptionId = feedbackadv_survey_answer.answerSetOptionId)
                                LEFT JOIN feedbackadv_category 
                                    ON (feedbackadv_survey_mapping.feedbackCategoryId = feedbackadv_category.feedbackCategoryId)    
                        $condition           
                        GROUP BY
                               feedbackadv_survey.feedbackSurveyId, employee.employeeId, subject.subjectId, student.studentId) AS t 
                  ORDER BY $orderBy
                  $limit ";
        
            return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
    
    
   public function getFeedbackListCount($condition='') {

        $query ="SELECT
                        COUNT(*) AS cnt
                 FROM       
                       (SELECT
                                feedbackadv_survey.feedbackSurveyLabel
                                , class.className
                                , IFNULL(student.rollNo,'') AS rollNo
                                , CONCAT(IFNULL(student.firstName,''),' ',IFNULL(student.lastName,'')) AS studentName
                                , employee.employeeName
                                , subject.subjectName
                                , subject.subjectCode
                                , SUM(feedbackadv_answer_set_option.optionPoints) AS points
                                , feedbackadv_category.feedbackCategoryName
                        FROM
                                feedbackadv_survey_answer
                                LEFT JOIN feedbackadv_to_question 
                                    ON (feedbackadv_survey_answer.feedbackToQuestionId = feedbackadv_to_question.feedbackToQuestionId)
                                LEFT JOIN feedbackadv_questions 
                                    ON (feedbackadv_to_question.feedbackQuestionId = feedbackadv_questions.feedbackQuestionId)
                                LEFT JOIN feedbackadv_survey_mapping 
                                    ON (feedbackadv_survey_answer.feedbackMappingId = feedbackadv_survey_mapping.feedbackMappingId)
                                LEFT JOIN student 
                                    ON (feedbackadv_survey_answer.userId = student.userId)
                                LEFT JOIN employee 
                                    ON (feedbackadv_survey_answer.employeeId = employee.employeeId)
                                LEFT JOIN subject 
                                    ON (feedbackadv_survey_answer.subjectId = subject.subjectId)
                                LEFT JOIN feedbackadv_survey 
                                    ON (feedbackadv_survey_mapping.feedbackSurveyId = feedbackadv_survey.feedbackSurveyId)
                                LEFT JOIN class 
                                    ON (feedbackadv_survey_mapping.classId = class.classId)
                                LEFT JOIN feedbackadv_answer_set_option 
                                    ON (feedbackadv_answer_set_option.answerSetOptionId = feedbackadv_survey_answer.answerSetOptionId)
                                LEFT JOIN feedbackadv_category 
                                    ON (feedbackadv_survey_mapping.feedbackCategoryId = feedbackadv_category.feedbackCategoryId)    
                        $condition           
                        GROUP BY
                               feedbackadv_survey.feedbackSurveyId, employee.employeeId, subject.subjectId, student.studentId) AS t ";
        
            return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    } 
    
    public function getFeedbackQuestionList($condition='',$orderBy='feedbackQuestion') {

        if($orderBy=='') {
          $orderBy='feedbackQuestion';
        }
        
        $query ="SELECT    
                       t.feedbackSurveyLabel, t.feedbackQuestionId, t.feedbackQuestion 
                 FROM       
                       (SELECT
                                DISTINCT feedbackadv_survey.feedbackSurveyLabel, feedbackadv_questions.feedbackQuestionId,
                                feedbackadv_questions.feedbackQuestion
                        FROM
                                feedbackadv_survey_answer
                                LEFT JOIN feedbackadv_to_question 
                                    ON (feedbackadv_survey_answer.feedbackToQuestionId = feedbackadv_to_question.feedbackToQuestionId)
                                LEFT JOIN feedbackadv_questions 
                                    ON (feedbackadv_to_question.feedbackQuestionId = feedbackadv_questions.feedbackQuestionId)
                                LEFT JOIN feedbackadv_survey_mapping 
                                    ON (feedbackadv_survey_answer.feedbackMappingId = feedbackadv_survey_mapping.feedbackMappingId)
                                LEFT JOIN student 
                                    ON (feedbackadv_survey_answer.userId = student.userId)
                                LEFT JOIN employee 
                                    ON (feedbackadv_survey_answer.employeeId = employee.employeeId)
                                LEFT JOIN subject 
                                    ON (feedbackadv_survey_answer.subjectId = subject.subjectId)
                                LEFT JOIN feedbackadv_survey 
                                    ON (feedbackadv_survey_mapping.feedbackSurveyId = feedbackadv_survey.feedbackSurveyId)
                                LEFT JOIN class 
                                    ON (feedbackadv_survey_mapping.classId = class.classId)
                                LEFT JOIN feedbackadv_answer_set_option 
                                    ON (feedbackadv_answer_set_option.answerSetOptionId = feedbackadv_survey_answer.answerSetOptionId)
                                LEFT JOIN feedbackadv_category 
                                    ON (feedbackadv_survey_mapping.feedbackCategoryId = feedbackadv_category.feedbackCategoryId)    
                        $condition           
                        GROUP BY
                               feedbackadv_survey.feedbackSurveyId, feedbackadv_questions.feedbackQuestionId) AS t 
                        ORDER BY $orderBy ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
    
     public function getFeedbackPointsList($condition='',$orderBy='optionPoints') {

        if($orderBy=='') {
          $orderBy='optionPoints';
        }
        
        $query ="SELECT    
                       t.feedbackSurveyLabel, t.optionPoints
                 FROM       
                       (SELECT
                                DISTINCT feedbackadv_survey.feedbackSurveyLabel, 
                                         IFNULL(feedbackadv_answer_set_option.optionPoints,0) AS optionPoints
                        FROM                                                     
                                feedbackadv_survey_answer
                                LEFT JOIN feedbackadv_to_question 
                                    ON (feedbackadv_survey_answer.feedbackToQuestionId = feedbackadv_to_question.feedbackToQuestionId)
                                LEFT JOIN feedbackadv_questions 
                                    ON (feedbackadv_to_question.feedbackQuestionId = feedbackadv_questions.feedbackQuestionId)
                                LEFT JOIN feedbackadv_survey_mapping 
                                    ON (feedbackadv_survey_answer.feedbackMappingId = feedbackadv_survey_mapping.feedbackMappingId)
                                LEFT JOIN student 
                                    ON (feedbackadv_survey_answer.userId = student.userId)
                                LEFT JOIN employee 
                                    ON (feedbackadv_survey_answer.employeeId = employee.employeeId)
                                LEFT JOIN subject 
                                    ON (feedbackadv_survey_answer.subjectId = subject.subjectId)
                                LEFT JOIN feedbackadv_survey 
                                    ON (feedbackadv_survey_mapping.feedbackSurveyId = feedbackadv_survey.feedbackSurveyId)
                                LEFT JOIN class 
                                    ON (feedbackadv_survey_mapping.classId = class.classId)
                                LEFT JOIN feedbackadv_answer_set_option 
                                    ON (feedbackadv_answer_set_option.answerSetOptionId = feedbackadv_survey_answer.answerSetOptionId)
                                LEFT JOIN feedbackadv_category 
                                    ON (feedbackadv_survey_mapping.feedbackCategoryId = feedbackadv_category.feedbackCategoryId)    
                        $condition           
                        GROUP BY
                               feedbackadv_survey.feedbackSurveyId, feedbackadv_answer_set_option.optionPoints) AS t 
                        ORDER BY $orderBy ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
    
    public function getFeedbackScoreList($condition='',$orderBy='') {

        if($orderBy=='') {
          $orderBy='t.feedbackQuestionId, t.optionPoints';
        }
        
        $query ="SELECT    
                       t.feedbackSurveyLabel, t.feedbackQuestionId, t.feedbackQuestion, t.optionPoints, t.userId AS response
                 FROM       
                       (SELECT
                                feedbackadv_survey.feedbackSurveyLabel,
                                feedbackadv_questions.feedbackQuestionId,
                                feedbackadv_questions.feedbackQuestion,
                                feedbackadv_answer_set_option.optionPoints,
                                COUNT(student.userId) AS userId
                        FROM
                                feedbackadv_survey_answer
                                LEFT JOIN feedbackadv_to_question 
                                    ON (feedbackadv_survey_answer.feedbackToQuestionId = feedbackadv_to_question.feedbackToQuestionId)
                                LEFT JOIN feedbackadv_questions 
                                    ON (feedbackadv_to_question.feedbackQuestionId = feedbackadv_questions.feedbackQuestionId)
                                LEFT JOIN feedbackadv_survey_mapping 
                                    ON (feedbackadv_survey_answer.feedbackMappingId = feedbackadv_survey_mapping.feedbackMappingId)
                                LEFT JOIN student 
                                    ON (feedbackadv_survey_answer.userId = student.userId)
                                LEFT JOIN employee 
                                    ON (feedbackadv_survey_answer.employeeId = employee.employeeId)
                                LEFT JOIN subject 
                                    ON (feedbackadv_survey_answer.subjectId = subject.subjectId)
                                LEFT JOIN feedbackadv_survey 
                                    ON (feedbackadv_survey_mapping.feedbackSurveyId = feedbackadv_survey.feedbackSurveyId)
                                LEFT JOIN class 
                                    ON (feedbackadv_survey_mapping.classId = class.classId)
                                LEFT JOIN feedbackadv_answer_set_option 
                                    ON (feedbackadv_answer_set_option.answerSetOptionId = feedbackadv_survey_answer.answerSetOptionId)
                                LEFT JOIN feedbackadv_category 
                                    ON (feedbackadv_survey_mapping.feedbackCategoryId = feedbackadv_category.feedbackCategoryId)    
                        $condition           
                        GROUP BY
                              feedbackadv_survey.feedbackSurveyId, feedbackadv_questions.feedbackQuestionId, 
                              feedbackadv_answer_set_option.optionPoints) AS t 
                        ORDER BY 
                              $orderBy ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
    
   
}
// $History: FeedBackReportAdvancedManager.inc.php $
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 23/03/10   Time: 11:07
//Updated in $/LeapCC/Model
//Created Feedback Teacher Detailed GPA Report (Advanced) for Teacher
//login
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 3/05/10    Time: 12:58p
//Updated in $/LeapCC/Model
//Created "Feedback Comments Report"
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 25/02/10   Time: 13:54
//Updated in $/LeapCC/Model
//Created "Class Final Report"  for advanced feedback modules.
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/16/10    Time: 12:13p
//Updated in $/LeapCC/Model
//added functions under feedback teacher final report
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 12/02/10   Time: 11:47
//Updated in $/LeapCC/Model
//Modified GPA calculation logic.
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/02/10   Time: 18:44
//Updated in $/LeapCC/Model
//Created "Teacher Detaile GPA Report"
?>