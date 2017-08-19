<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
//
//
// Author :Gurkeerat Sidhu 
// Created on : (9.02.2010 )
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeedBackTeacherFinalReportManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CityManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (9.02.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CityManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (9.02.2010)
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
//  THIS FUNCTION IS USED TO GET LABEL
//
// Author :Gurkeerat Sidhu
// Created on : (15.01.10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------      
    public function getSelectedTimeTableLabel($timeTableLabelId) {
        

        $query = "    SELECT feedbackSurveyId,feedbackSurveyLabel 
                        FROM feedbackadv_survey fas
                        WHERE timeTableLabelId = $timeTableLabelId
                        AND isActive=1 
                        AND roleId=4";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    } 
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET LABEL
//
// Author :Gurkeerat Sidhu
// Created on : (15.01.10)
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
// Created on : (15.01.10)
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
// Created on : (15.01.10)
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
// Created on : (30.06.2008)
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
// Created on : (30.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function getCategory($labelId,$teacherId,$timeTableLabelId,$orderBy=' feedbackCategoryName') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
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
                                                               employeeId=$teacherId
                                                               and timeTableLabelId=$timeTableLabelId     
                                                      )
                             )
                    ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
  
}
// $History: FeedBackTeacherFinalReportManager.inc.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 2/19/10    Time: 2:55p
//Created in $/LeapCC/Model
//created file under feedback teacher final report
//

?>