<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "feedbackadv_answer_set" table
// Author :Gurkeerat Sidhu 
// Created on : (14.01.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeedbackQuestionManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ComplaintManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (14.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ComplaintManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (14.01.2010)
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
// THIS FUNCTION IS USED FOR ADDING A  FeedBack Question
//
// Author :Gurkeerat Sidhu 
// Created on : (14.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
   /* public function addFeedBackQuestions($value) {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoInsert('feedbackadv_questions', 
        array('feedbackQuestionSetId','answerSetId','feedbackQuestion'), 
        array($REQUEST_DATA['questionSet'],$REQUEST_DATA['answerSet'], add_slashes(trim($REQUEST_DATA['questionTxt']))) 
        );
    } */
     public function addFeedBackQuestions($value) {

        $query ="INSERT INTO feedbackadv_questions(feedbackQuestionSetId,answerSetId,feedbackQuestion) VALUES $value ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//--------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A FeedBack Question
//
//$id:busRouteId
// Author :Gurkeerat Sidhu 
// Created on : (14.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------        
    public function editFeedBackQuestions($id) {
        global $REQUEST_DATA;
    
        return SystemDatabaseManager::getInstance()->runAutoUpdate('feedbackadv_questions', 
        array('feedbackQuestionSetId','answerSetId','feedbackQuestion'), 
        array($REQUEST_DATA['questionSet'],$REQUEST_DATA['answerSet'],trim($REQUEST_DATA['questionTxt'])), 
              "feedbackQuestionId=$id" 
            );
    }   
//----------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Question 
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (14.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------         
    public function getFeedBackQuestions($conditions='') {
     
        $query = "SELECT feedbackQuestionId,feedbackQuestionSetId,answerSetId,feedbackQuestion
        FROM feedbackadv_questions
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A FeedBack Question
//
//$FeedBackQuestionsId :FeedBackQuestionsId of the FeedBack Question
// Author :Gurkeerat Sidhu 
// Created on : (14.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteFeedBackQuestions($feedbackQuestionId) {
     
        $query = "DELETE 
        FROM feedbackadv_questions 
        WHERE feedbackQuestionId=$feedbackQuestionId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Question LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu 
// Created on : (14.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------       
    
    public function getFeedBackQuestionsList($conditions='', $limit = '', $orderBy=' ffq.feedbackQuestion') {
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
       $query = "SELECT 
                       DISTINCT 
                       fqs.feedbackQuestionSetName,fas.answerSetName,ffq.feedbackQuestion,ffq.feedbackQuestionId
                 FROM 
                    feedbackadv_answer_set fas,feedbackadv_question_set fqs,feedbackadv_questions ffq
                 WHERE
                    ffq.feedbackQuestionSetId=fqs.feedbackQuestionSetId
                    AND
                    ffq.answerSetId=fas.answerSetId
                    ANd fqs.instituteId=$instituteId
                    ANd fas.instituteId=$instituteId
                    $conditions 
                    ORDER BY $orderBy 
                    $limit
                    ";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF FeedBack Questions 
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (14.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalFeedBackQuestions($conditions='') {
       
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT COUNT( DISTINCT ffq.feedbackQuestionId) AS totalRecords
        FROM 
            feedbackadv_answer_set fas,feedbackadv_question_set fqs,feedbackadv_questions ffq
            LEFT JOIN feedback_teacher fet ON fet.feedbackQuestionId=ffq.feedbackQuestionId      
        WHERE
        ffq.feedbackQuestionSetId=fqs.feedbackQuestionSetId
        AND
        ffq.answerSetId=fas.answerSetId
        AND fqs.instituteId=$instituteId
        AND fas.instituteId=$instituteId
        $conditions ";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    



    
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Label LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getSourceFeedBackLabel($conditions='') {
        global $sessionHandler;  
        
        $query = "SELECT 
                         DISTINCT fqs.feedbackQuestionSetId,fqs.feedbackQuestionSetName
                  FROM 
                        feedbackadv_question_set fqs,feedbackadv_questions ffq
                  WHERE 
                        fqs.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND fqs.feedbackQuestionSetId=ffq.feedbackQuestionSetId
                  $conditions 
                  ORDER BY fqs.feedbackQuestionSetName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    

    
//------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Question LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu 
// Created on : (14.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------       
    
    public function getQuestionsList($conditions='', $limit = '', $orderBy=' ffq.feedbackQuestion') {
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
       $query = "SELECT 
                       DISTINCT 
                       fqs.feedbackQuestionSetName,fas.answerSetName,ffq.feedbackQuestion,ffq.feedbackQuestionId
                 FROM 
                    feedbackadv_answer_set fas,feedbackadv_question_set fqs,feedbackadv_questions ffq
                 WHERE
                    ffq.feedbackQuestionSetId=fqs.feedbackQuestionSetId
                    AND
                    ffq.answerSetId=fas.answerSetId
                    ANd fqs.instituteId=$instituteId
                    ANd fas.instituteId=$instituteId
                    $conditions 
                    ORDER BY $orderBy 
                    $limit
                    ";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Question LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu 
// Created on : (14.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------       
    
    public function getTotalQuestions($conditions='') {
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
       $query = "SELECT 
                       DISTINCT ffq.feedbackQuestionId
                 FROM 
                    feedbackadv_answer_set fas,feedbackadv_question_set fqs,feedbackadv_questions ffq
                 WHERE
                    ffq.feedbackQuestionSetId=fqs.feedbackQuestionSetId
                    AND
                    ffq.answerSetId=fas.answerSetId
                    ANd fqs.instituteId=$instituteId
                    ANd fas.instituteId=$instituteId
                    $conditions 
                    ";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
 
 //------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR checking questiond ependency
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu 
//Modified By : Dipanjan Bhattacharjee
// Created on : (21.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------       
    
    public function checkQuestionDependency($feedbackQuestionId) {
        
        //check in mapping table
        $query = "SELECT 
                        COUNT(*) AS cnt
                  FROM
                        feedbackadv_to_question
                  WHERE
                        feedbackQuestionId=$feedbackQuestionId";
        
        $retArray=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        if($retArray[0]['cnt']!=0){
            return 1;
        }
        
        //check in answer table
        $query = "SELECT 
                        COUNT(*) AS cnt
                  FROM
                        feedbackadv_survey_answer fas,
                        feedbackadv_to_question ftq
                  WHERE
                        ftq.feedbackToQuestionId=fas.feedbackToQuestionId
                        AND ftq.feedbackQuestionId=$feedbackQuestionId";
        
        $retArray=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        if($retArray[0]['cnt']!=0){
            return 1;
        }
        
        return 0;
    }
                          
}

?>
