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

class FeedBackQuestionMappingAdvancedManager {
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
    

//-----------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Adv. Question Mapped List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (11.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------------       
    public function getFeedbackMappedQuestionList($conditions='',$conditions2='' ,$limit = '', $orderBy=' fc.feedbackCategoryName') {
        global $sessionHandler;
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                         fq.feedbackQuestionId,
                         fq.feedbackQuestion,
                         fqs.feedbackQuestionSetId,
                         IF(ftq.feedbackToQuestionId IS NULL,-1,ftq.feedbackToQuestionId) AS feedbackToQuestionId,
                         IF(ftq.printOrder IS NULL,-1,ftq.printOrder) AS printOrder,
                         fa.answerSetName,
                         IF(fs.feedbackSurveyId IS NULL,-1,1) AS feedbackSurveyId,
                         IF(fc.feedbackCategoryId IS NULL,-1,1) AS feedbackCategoryId
                  FROM 
                         feedbackadv_questions fq
                         INNER JOIN feedbackadv_answer_set fa     ON  ( fa.answerSetId=fq.answerSetId AND fa.instituteId=$instituteId )
                         INNER JOIN feedbackadv_question_set fqs  ON  ( fqs.feedbackQuestionSetId = fq.feedbackQuestionSetId AND fqs.instituteId=$instituteId $conditions2 )
                         LEFT  JOIN feedbackadv_to_question ftq   ON  (
                                                                         ftq.feedbackQuestionId=fq.feedbackQuestionId 
                                                                         $conditions 
                                                                      )
                         LEFT  JOIN  feedbackadv_category fc      ON  fc.feedbackCategoryId = ftq.feedbackCategoryId
                         LEFT  JOIN  feedbackadv_survey   fs      ON  fs.feedbackSurveyId   = ftq.feedbackSurveyId
                   
                  ORDER BY $orderBy 
                  $limit";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Total Adv. Question Mapped List
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (11.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------      
    public function getTotalFeedbackMappedQuestion($conditions='',$conditions2='') {
        global $sessionHandler;
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        
        $query = "SELECT 
                         COUNT(*) AS totalRecords  
                  FROM 
                         feedbackadv_questions fq
                         INNER JOIN feedbackadv_answer_set fa     ON  ( fa.answerSetId=fq.answerSetId AND fa.instituteId=$instituteId )
                         INNER JOIN feedbackadv_question_set fqs  ON  ( fqs.feedbackQuestionSetId = fq.feedbackQuestionSetId AND fqs.instituteId=$instituteId $conditions2)
                         LEFT  JOIN feedbackadv_to_question ftq   ON  (
                                                                         ftq.feedbackQuestionId=fq.feedbackQuestionId 
                                                                         $conditions 
                                                                      )
                         LEFT  JOIN  feedbackadv_category fc      ON  fc.feedbackCategoryId = ftq.feedbackCategoryId
                         LEFT  JOIN  feedbackadv_survey   fs      ON  fs.feedbackSurveyId   = ftq.feedbackSurveyId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-----------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Usage of questions in feedback_ans table
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (11.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------      
    public function getQuestionUsageList($feedbackToQuestionIds) {
        
        $query="SELECT
                      DISTINCT feedbackToQuestionId
                FROM
                      feedbackadv_survey_answer
                WHERE
                      feedbackToQuestionId IN ($feedbackToQuestionIds)
        ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        
    }
    
//-------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Usage of questions related to a particular label,cat and question set
// in feedback_ans table
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------      
    public function getQuestionUsageListAdvanced($conditions='') {
        
        $query="SELECT
                      DISTINCT feedbackToQuestionId
                FROM
                      feedbackadv_survey_answer
                WHERE
                      feedbackToQuestionId 
                             IN
                                 (
                                   SELECT 
                                          DISTINCT feedbackToQuestionId
                                   FROM  
                                          feedbackadv_to_question
                                          $conditions
                                 ) 
        ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        
    }    
    
//-----------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR deleting mapped questions
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (11.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------      
    public function deleteMappedQuestions($labelId,$catId,$questionSetId,$mappedQuestionIds) {
        
        $query="DELETE
                FROM
                      feedbackadv_to_question
                WHERE
                      feedbackSurveyId=$labelId
                      AND feedbackCategoryId=$catId
                      AND feedbackQuestionSetId=$questionSetId
                      AND feedbackToQuestionId IN ( $mappedQuestionIds )
               ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    

//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR deleting mapped questions related to a particular label,cat and questions set
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------------------------      
    public function deleteMappedQuestionsAdvanced($labelId,$catId,$questionSetId) {
        
        $query="DELETE
                FROM
                      feedbackadv_to_question
                WHERE
                      feedbackSurveyId=$labelId
                      AND feedbackCategoryId=$catId
                      AND feedbackQuestionSetId=$questionSetId
                      AND feedbackToQuestionId NOT IN 
                           (
                              SELECT 
                                    DISTINCT feedbackToQuestionId 
                              FROM 
                                    feedbackadv_survey_answer
                           )
               ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }    
    
//-----------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR mapping questions
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (11.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------      
    public function doQuestionsMapping($insertQuery) {
        
        $query="INSERT INTO
                      feedbackadv_to_question ( feedbackSurveyId, feedbackCategoryId, feedbackQuestionSetId, feedbackQuestionId, printOrder)
                VALUES
                      $insertQuery 
               ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
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
                        AND isActive=1 ";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
    
    public function getQuestionCount($labelId,$catId,$questionSetId) {
        
        $query="SELECT
                      COUNT(*) AS cnt
                FROM
                      feedbackadv_to_question
                WHERE
                      feedbackSurveyId=$labelId
                      AND feedbackCategoryId=$catId
                      AND feedbackQuestionSetId=$questionSetId
        ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function updateFeedbackStudentStatus($labelId,$catId,$questionSetId) {

        $query="UPDATE
                      feedbackadv_student_status  
                SET 
                      status=".FEEDBACK_STUDENT_BLOCKED."
                WHERE
                      userId 
                        IN 
                          (
                            SELECT 
                                   DISTINCT userId
                            FROM
                                   feedbackadv_survey_visible_to_users
                            WHERE
                                   feedbackMappingId
                                                  IN
                                                    (
                                                      SELECT 
                                                             DISTINCT feedbackMappingId
                                                      FROM
                                                             feedbackadv_survey_mapping
                                                      WHERE
                                                             feedbackSurveyId=$labelId
                                                             AND feedbackCategoryId=$catId
                                                             AND feedbackQuestionSetId=$questionSetId
                                                    )
                          )
        ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function insertFeedbackStudentStatusLog($labelId,$catId,$questionSetId) {
        global $sessionHandler;
        $userId=$sessionHandler->getSessionVariable('UserId');
        
        $query="INSERT INTO
                      feedbackadv_student_status_log
                      (userId,logDescription,logStatus,logDate,doneByUserId)
                SELECT
                       DISTINCT userId,'New Questions Added',".FEEDBACK_STUDENT_INCOMPLETE.",CURDATE(),$userId
                FROM
                       feedbackadv_survey_visible_to_users
                WHERE
                       feedbackMappingId
                                      IN
                                        (
                                          SELECT 
                                                 DISTINCT feedbackMappingId
                                          FROM
                                                 feedbackadv_survey_mapping
                                          WHERE
                                                 feedbackSurveyId=$labelId
                                                 AND feedbackCategoryId=$catId
                                                 AND feedbackQuestionSetId=$questionSetId
                                        )
        ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }        
    
}
// $History: FeedBackQuestionMappingAdvancedManager.inc.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/02/10    Time: 18:21
//Updated in $/LeapCC/Model
//Made modifications in Feedback modules---Added block/unblock feature
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 27/01/10   Time: 16:57
//Updated in $/LeapCC/Model
//Corrected query
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 20/01/10   Time: 14:03
//Updated in $/LeapCC/Model
//Corrected query for fetching mapped questions
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 1/18/10    Time: 2:42p
//Updated in $/LeapCC/Model
//made updations under feedback module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/01/10   Time: 11:17
//Updated in $/LeapCC/Model
//Added institute wise checks in queries
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/01/10   Time: 10:54
//Created in $/LeapCC/Model
//Created module "Feedback Question Mapping (Advanced)"
?>