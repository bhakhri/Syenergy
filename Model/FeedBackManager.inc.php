<?php
//-------------------------------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "tfeedback_survey" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); 

class FeedBackManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "FeedBackManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "FeedBackManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
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
    
    
    //*******************************FUNCTIONS USED FOR FEEDBACK LABEL*********************
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A  FeedBack Label
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addFeedBackLabel() {
        global $REQUEST_DATA;
        global $sessionHandler;  

        return SystemDatabaseManager::getInstance()->runAutoInsert('feedback_survey', 
        array('feedbackSurveyLabel','surveyType','visibleFrom','visibleTo','noAttempts','isActive','sessionId','instituteId'), 
        array(strtoupper($REQUEST_DATA['labelName']),$REQUEST_DATA['surveyType'],$REQUEST_DATA['startDate'],$REQUEST_DATA['toDate'],$REQUEST_DATA['noAttempts'],$REQUEST_DATA['isActive'],
               $sessionHandler->getSessionVariable('SessionId'),
               $sessionHandler->getSessionVariable('InstituteId')
              ) 
        );
    }

//--------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A FeedBack Label
//
//$id:busRouteId
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------        
    public function editFeedBackLabel($id) {
        global $REQUEST_DATA;
        global $sessionHandler;  
        
        
        if ($REQUEST_DATA['surveyType'] == 2) {
            $REQUEST_DATA['noAttempts']    = 1;    
        }
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('feedback_survey', 
        array('feedbackSurveyLabel','surveyType','visibleFrom','visibleTo','noAttempts','isActive','sessionId','instituteId'), 
        array(strtoupper($REQUEST_DATA['labelName']),$REQUEST_DATA['surveyType'],$REQUEST_DATA['startDate1'],$REQUEST_DATA['toDate1'],$REQUEST_DATA['noAttempts'],$REQUEST_DATA['isActive'],
               $sessionHandler->getSessionVariable('SessionId'),
               $sessionHandler->getSessionVariable('InstituteId')
              ),
              "feedbackSurveyId=$id" 
            );
    }   
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TimeTable Label LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getFeedBackLabel($conditions='') {
		global $sessionHandler;

        $query = "	SELECT 
								feedbackSurveyId,
								feedbackSurveyLabel,
								surveyType,
								visibleFrom,
								visibleTo,
								noAttempts,
								isActive
						FROM	feedback_survey
						WHERE	sessionId =".$sessionHandler->getSessionVariable('SessionId')."
						AND		instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
								$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A FeedBack Label
//
//$feedBackLabelId :feedBackLabelId of the feedback label
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteFeedBackLabel($feedBackLabelId) {
     
        $query = "DELETE 
        FROM feedback_survey 
        WHERE feedbackSurveyId=$feedBackLabelId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Label LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getFeedBackLabelList($conditions='', $limit = '', $orderBy=' ffl.feedbackSurveyLabel') {
        global $sessionHandler;  
        
        $query = "SELECT ffl.feedbackSurveyId,ffl.feedbackSurveyLabel,IF(ffl.surveyType=1,'General Feedback','Teacher Feedback') AS surveyType,ffl.visibleFrom,ffl.visibleTo,ffl.noAttempts,IF(ffl.isActive=1,'Yes','No') AS isActive 
        FROM feedback_survey ffl
        WHERE ffl.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
        AND ffl.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
        $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF FeedBack Labels 
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalFeedBackLabel($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM feedback_survey ffl 
        WHERE ffl.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
        AND   ffl.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."  
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }          
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Making All FeedBack Labels Inactive
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function makeAllFeedBackLabelInActive($conditions='') {
        global $sessionHandler;
        
        $query = "UPDATE feedback_survey ffl 
        SET ffl.isActive=0
        WHERE ffl.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
        AND ffl.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."  
        AND ffl.isActive=1
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }     
    

    
    
    //*******************************FUNCTIONS USED FOR FEEDBACK CATEGORY*********************
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A  FeedBack Category
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addFeedBackCategory() {
        global $REQUEST_DATA;
        global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        return SystemDatabaseManager::getInstance()->runAutoInsert('feedback_category', 
        array('feedbackCategoryName','instituteId'), 
        array(strtoupper($REQUEST_DATA['categoryName']), $instituteId) 
        );
    }

//--------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A FeedBack Category
//
//$id:busRouteId
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------        
    public function editFeedBackCategory($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        return SystemDatabaseManager::getInstance()->runAutoUpdate('feedback_category', 
        array('feedbackCategoryName', 'instituteId'), 
        array(strtoupper($REQUEST_DATA['categoryName']), $instituteId),
              "feedbackCategoryId=$id" 
            );
    }   
//----------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Category Label
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------         
    public function getFeedBackCategory($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
    
        $query = "SELECT feedbackCategoryId,feedbackCategoryName
        FROM feedback_category
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A FeedBack Category
//
//$FeedBackCategoryId :FeedBackCategoryId of the FeedBack Category
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteFeedBackCategory($feedBackCategoryId) {
     
        $query = "DELETE 
        FROM feedback_category 
        WHERE feedbackCategoryId=$feedBackCategoryId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR checking whether a feedback category is used in question masters
//$FeedBackCategoryId :FeedBackCategoryId of the FeedBack Category
// Author :Dipanjan Bhattacharjee 
// Created on : (11.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------      
    public function checkFeedBackCategory($feedBackCategoryId) {
     
        $query = "SELECT 
                        feedBackCategoryId 
                  FROM 
                        feedback_questions 
                  WHERE 
                        feedbackCategoryId=$feedBackCategoryId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Category LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getFeedBackCategoryList($conditions='', $limit = '', $orderBy=' ffc.feedbackCategoryName') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        $query = "SELECT ffc.feedbackCategoryId,ffc.feedbackCategoryName
        FROM feedback_category ffc
        $conditions 
        ORDER BY $orderBy $limit";
        
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF FeedBack Categorys 
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalFeedBackCategory($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        $query = "SELECT COUNT(*) AS totalRecords
        FROM feedback_category ffc
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }          
    

//*******************************FUNCTIONS USED FOR FeedBack Grades*********************
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A  FeedBack Grades
//
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addFeedBackGrades() {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoInsert('feedback_grade', 
        array('feedbackSurveyId','feedbackGradeLabel','feedbackGradeValue'), 
        array($REQUEST_DATA['surveyId'],strtoupper(add_slashes($REQUEST_DATA['gradeLabel'])),$REQUEST_DATA['gradeValue']) 
        );
    }

//--------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A FeedBack Grades
//
//$id:busRouteId
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------        
    public function editFeedBackGrades($id) {
        global $REQUEST_DATA;
    
        return SystemDatabaseManager::getInstance()->runAutoUpdate('feedback_grade', 
        array('feedbackSurveyId','feedbackGradeLabel','feedbackGradeValue'), 
        array($REQUEST_DATA['surveyId'],strtoupper(add_slashes($REQUEST_DATA['gradeLabel'])),$REQUEST_DATA['gradeValue']), 
              "feedbackGradeId=$id" 
            );
    }   
//----------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Grades Label
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------         
    public function getFeedBackGrades($conditions='') {
     
        $query = "SELECT feedbackGradeId,feedbackSurveyId,feedbackGradeLabel,feedbackGradeValue
        FROM feedback_grade
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A FeedBack Grades
//
//$FeedBackGradesId :FeedBackGradesId of the FeedBack Grades
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteFeedBackGrades($feedbackGradeId) {
     
        $query = "DELETE 
        FROM feedback_grade 
        WHERE feedbackGradeId=$feedbackGradeId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Grades LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getFeedBackGradesList($conditions='', $limit = '', $orderBy=' ffg.feedbackGradeLabel') {

        $query = "SELECT 
                        ffg.feedbackGradeId,
                        ffs.feedbackSurveyLabel, 
                        ffg.feedbackGradeLabel,
                        ffg.feedbackGradeValue
                  FROM 
                        feedback_grade ffg, feedback_survey ffs
                  WHERE 
                        ffg.feedbackSurveyId=ffs.feedbackSurveyId
                        $conditions 
                        ORDER BY $orderBy 
                        $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF FeedBack Gradess 
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalFeedBackGrades($conditions='') {
       
        $query = "SELECT 
                         COUNT(*) AS totalRecords
                  FROM 
                        feedback_grade ffg, feedback_survey ffs
                  WHERE 
                        ffg.feedbackSurveyId=ffs.feedbackSurveyId
                         $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
//---------------------------------------------------------------------
// THIS FUNCTION IS USED FOR chaecking overlaping grade lables
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------       
    
    public function checkFeedBackGrades($labelName,$labelIdCondition='',$surveyId) {

        $query = "SELECT feedbackGradeId,feedbackGradeLabel,feedbackGradeValue
        FROM feedback_grade 
        WHERE 
         (
          UCASE(feedbackGradeLabel)='".strtoupper(add_slashes($labelName))."' 
         )
        AND feedbackSurveyId=".$surveyId."  
        $labelIdCondition  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }                  
    

    //---------------------------------------------------------------------
// THIS FUNCTION IS USED FOR chaecking overlaping grade lables
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------       
    
    public function checkFeedBackLabel($labelValue,$labelIdCondition='',$surveyId) {

        $query = "SELECT feedbackGradeId,feedbackGradeLabel,feedbackGradeValue
        FROM feedback_grade 
        WHERE 
         (
          feedbackGradeValue=$labelValue
         )
        AND feedbackSurveyId=".$surveyId."  
        $labelIdCondition  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }                  

    
//---------------------------------------------------------------------
// THIS FUNCTION IS USED FOR chaecking of grade lables uses
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (13.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------       
    
    public function checkFeedBackGradesUses($gradeId) {

        //First Check it against general feedback table
        $query = "SELECT feedbackGradeId FROM  feedback_survey_answer WHERE  feedbackGradeId=".$gradeId;
        $ret1=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

        if($ret1[0]['feedbackGradeId']==''){ //if this is not used in general table
           //Then Check it against teacher feedback table
           $query = "SELECT feedbackGradeId FROM  feedback_teacher WHERE  feedbackGradeId=".$gradeId;
           $ret2=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
           return $ret2[0]['feedbackGradeId'];
        }
        else{
           return $ret1[0]['feedbackGradeId'];
        }
    }    
    

//*******************************FUNCTIONS USED FOR FeedBack Question*********************
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A  FeedBack Question
//
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addFeedBackQuestions() {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoInsert('feedback_questions', 
        array('feedbackSurveyId','feedbackCategoryId','feedbackQuestion'), 
        array($REQUEST_DATA['labelId'],$REQUEST_DATA['categoryId'], add_slashes(trim($REQUEST_DATA['questionTxt']))) 
        );
    }

//--------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A FeedBack Question
//
//$id:busRouteId
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------        
    public function editFeedBackQuestions($id) {
        global $REQUEST_DATA;
    
        return SystemDatabaseManager::getInstance()->runAutoUpdate('feedback_questions', 
        array('feedbackSurveyId','feedbackCategoryId','feedbackQuestion'), 
        array($REQUEST_DATA['labelId'],$REQUEST_DATA['categoryId'],add_slashes(trim($REQUEST_DATA['questionTxt']))), 
              "feedbackQuestionId=$id" 
            );
    }   
//----------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Question 
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------         
    public function getFeedBackQuestions($conditions='') {
     
        $query = "SELECT feedbackQuestionId,feedbackSurveyId,feedbackCategoryId,feedbackQuestion
        FROM feedback_questions
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A FeedBack Question
//
//$FeedBackQuestionsId :FeedBackQuestionsId of the FeedBack Question
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteFeedBackQuestions($feedbackQuestionId) {
     
        $query = "DELETE 
        FROM feedback_questions 
        WHERE feedbackQuestionId=$feedbackQuestionId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Question LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------       
    
    public function getFeedBackQuestionsList($conditions='', $limit = '', $orderBy=' ffq.feedbackQuestion') {
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        /*SELECT 
                       DISTINCT 
                       CONCAT(\"'\",ffq.feedbackQuestionId,'~',IF(fet.feedbackQuestionId IS NULL,-1,fet.feedbackQuestionId),\"'\") AS feedbackQuestionId,
                       ffl.feedbackSurveyLabel,ffc.feedbackCategoryName,ffq.feedbackQuestion
                 FROM 
                    feedback_category ffc,feedback_survey ffl,feedback_questions ffq
                    LEFT JOIN sc_feedback_teacher fet ON fet.feedbackQuestionId=ffq.feedbackQuestionId      
                 WHERE
                    ffq.feedbackSurveyId=ffl.feedbackSurveyId
                    AND
                    ffq.feedbackCategoryId=ffc.feedbackCategoryId
                    AND 
                    ffl.sessionId=$sessionId
                    ANd ffl.instituteId=$instituteId
                    $conditions 
                    ORDER BY $orderBy 
                    $limit*/
       $query = "SELECT 
                       DISTINCT 
                       ffl.feedbackSurveyLabel,ffc.feedbackCategoryName,ffq.feedbackQuestion,ffq.feedbackQuestionId
                 FROM 
                    feedback_category ffc,feedback_survey ffl,feedback_questions ffq
                 WHERE
                    ffq.feedbackSurveyId=ffl.feedbackSurveyId
                    AND
                    ffq.feedbackCategoryId=ffc.feedbackCategoryId
                    AND 
                    ffl.sessionId=$sessionId
                    ANd ffl.instituteId=$instituteId
                    ANd ffc.instituteId=$instituteId
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
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalFeedBackQuestions($conditions='') {
       
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT COUNT( DISTINCT ffq.feedbackQuestionId) AS totalRecords
        FROM 
            feedback_category ffc,feedback_survey ffl,feedback_questions ffq
            LEFT JOIN feedback_teacher fet ON fet.feedbackQuestionId=ffq.feedbackQuestionId      
        WHERE
        ffq.feedbackSurveyId=ffl.feedbackSurveyId
        AND
        ffq.feedbackCategoryId=ffc.feedbackCategoryId
        AND 
        ffl.sessionId=$sessionId
        ANd ffl.instituteId=$instituteId
        ANd ffc.instituteId=$instituteId
        $conditions ";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET FEEDBACKSURVEY ID IS USING IN ANOTHER TABLE
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------- 
    public function getCheckFeedBackLabel($conditions='') {
        
    $query = "    SELECT    count(fs.feedbackSurveyId) as feedbackSurveyId
                    FROM    feedback_survey fs,
                            feedback_survey_answer fsa,
                            feedback_questions fq
                    WHERE    fsa.feedbackQuestionId = fq.feedbackQuestionId
                            $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED GET FEEDBACKSURVEY ID IS USING IN ANOTHER TABLE
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------- 
    
    public function getCheckFeedBackGrade($conditions='') {
        
    $query = "    SELECT    count(fs.feedbackSurveyId) as feedbackSurveyId
                    FROM    feedback_survey fs,
                            feedback_grade fg
                    WHERE    fg.feedbackSurveyId = fs.feedbackSurveyId
                            $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getCheckFeedBackSurveyId($conditions='') {
        
    $query = "    SELECT    count(fs.feedbackSurveyId) as feedbackSurveyId
                    FROM    feedback_survey fs,
                            feedback_questions fq
                    WHERE    fq.feedbackSurveyId = fs.feedbackSurveyId
                            $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function checkGeneralFeedBackQuestion($conditions='') {
        
    $query = "        SELECT    fq.feedbackQuestionId
                    FROM    feedback_questions fq,
                            feedback_survey_answer fsa
                    WHERE    fsa.feedbackQuestionId = fq.feedbackQuestionId
                            $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function checkTeacherFeedBackQuestion($conditions='') {
        
    $query = "        SELECT    fq.feedbackQuestionId
                    FROM    feedback_questions fq,
                            feedback_teacher sft
                    WHERE    sft.feedbackQuestionId = fq.feedbackQuestionId
                            $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Label LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getSourceFeedBackLabel($conditions='') {
        global $sessionHandler;  
        
        $query = "SELECT 
                         DISTINCT ffl.feedbackSurveyId,ffl.feedbackSurveyLabel
                  FROM 
                        feedback_survey ffl,feedback_questions ffq
                  WHERE 
                        ffl.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND ffl.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND ffl.feedbackSurveyId=ffq.feedbackSurveyId
                  $conditions 
                  ORDER BY ffl.feedbackSurveyLabel";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Label LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getCopyFeedBackLabel($conditions='') {
        global $sessionHandler;  
        
        $query = "SELECT 
                         DISTINCT ffl.feedbackSurveyId,ffl.feedbackSurveyLabel
                  FROM 
                        feedback_survey ffl
                  WHERE 
                        ffl.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND ffl.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                  $conditions 
                  ORDER BY ffl.feedbackSurveyLabel";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Question LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------       
    
    public function getQuestionsList($conditions='', $limit = '', $orderBy=' ffq.feedbackQuestion') {
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
       $query = "SELECT 
                       DISTINCT 
                       ffl.feedbackSurveyLabel,ffc.feedbackCategoryName,ffq.feedbackQuestion,ffq.feedbackQuestionId
                 FROM 
                    feedback_category ffc,feedback_survey ffl,feedback_questions ffq
                 WHERE
                    ffq.feedbackSurveyId=ffl.feedbackSurveyId
                    AND
                    ffq.feedbackCategoryId=ffc.feedbackCategoryId
                    AND 
                    ffl.sessionId=$sessionId
                    ANd ffl.instituteId=$instituteId
                    ANd ffc.instituteId=$instituteId
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
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
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
                    feedback_category ffc,feedback_survey ffl,feedback_questions ffq
                 WHERE
                    ffq.feedbackSurveyId=ffl.feedbackSurveyId
                    AND
                    ffq.feedbackCategoryId=ffc.feedbackCategoryId
                    AND 
                    ffl.sessionId=$sessionId
                    ANd ffl.instituteId=$instituteId
                    ANd ffc.instituteId=$instituteId
                    $conditions 
                    ";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
//------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR checking unique question
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------       
    
    public function checkUniqueQuestions($sourceId,$targetId,$questionIds) {
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
                 
        $query = "SELECT 
                       feedbackQuestionId,feedbackQuestion 
                 FROM 
                       feedback_questions 
                 WHERE
                       feedbackSurveyId = ".$sourceId."
                       AND feedBackQuestionId IN (".$questionIds.") 
                       AND feedbackQuestion NOT IN
                        (
                          SELECT feedbackQuestion FROM feedback_questions WHERE feedbackSurveyId=".$targetId."
                        )                        
                    ";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR checking options
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Rajeev Aggarwal 
// Created on : (19.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------       
    
    public function checkOptionExists($targetId) {
       
                 
        $query = "SELECT 
                       COUNT(*) as totalRecords
                 FROM 
                       feedback_grade 
                 WHERE
                       feedbackSurveyId = ".$targetId;
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//This function is used to copy questions from one survey to another survey
//Author: Dipanjan Bhattacherjee
//Date : 15.04.2009
  public function copyQuestions($questionIds,$targetId){
      $query="INSERT INTO feedback_questions
              (
                feedbackSurveyId,feedbackCategoryId,feedbackQuestion
              )
              SELECT 
                     $targetId,feedbackCategoryId,feedbackQuestion
              FROM
                     feedback_questions
              WHERE 
                     feedbackQuestionId IN (".$questionIds.")";
      return SystemDatabaseManager::getInstance()->executeUpdate($query);
  
  }            
//This function is used to copy questions from one survey option to another survey
//Author: Rajeev Aggarwal
//Date : 19.05.2009
  public function copySurveyOption($sourceId,$targetId){

      $query="INSERT INTO feedback_grade
              (
                feedbackSurveyId,feedbackGradeLabel,feedbackGradeValue
              )
              SELECT 
                     $targetId,feedbackGradeLabel,feedbackGradeValue
              FROM
                     feedback_grade
              WHERE 
                     feedbackSurveyId IN (".$sourceId.")";
      return SystemDatabaseManager::getInstance()->executeUpdate($query);
  
  }   
  
  //--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET Feedback Data
//
// Author :Jaineesh
// Created on : 10-11-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
  public function getFeedBackData($condition) {
        global $sessionHandler;
        
 $query = "
                SELECT 
                                fc.feedbackCategoryName, 
                                fq.feedbackQuestion,
                                fc.feedbackCategoryId,
                                fq.feedbackQuestionId,
                                fs.feedbackSurveyId

                FROM            feedback_category fc, 
                                feedback_questions fq,  
                                feedback_survey fs 
                WHERE            fq.feedbackCategoryId = fc.feedbackCategoryId
                AND                fq.feedbackSurveyId = fs.feedbackSurveyId
                AND                fs.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
                AND                fs.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                AND                fc.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                 
                                $condition ORDER BY fc.feedbackCategoryId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
  }

  
}

// $History: FeedBackManager.inc.php $
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 24/10/09   Time: 14:41
//Updated in $/LeapCC/Model
//Corrected edit query
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 14/08/09   Time: 18:19
//Updated in $/LeapCC/Model
//corrected add & edit functions
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/25/09    Time: 6:18p
//Updated in $/LeapCC/Model
//fixed bug no.0000202,0000177,0000176,0000175
//
//*****************  Version 5  *****************
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Model
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
//
//*****************  Version 4  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Model
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 5/19/09    Time: 4:51p
//Updated in $/Leap/Source/Model
//Added Preview survey related function.
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 5/19/09    Time: 10:47a
//Updated in $/Leap/Source/Model
//Added functionality to add question options(grades) along with feedback
//question
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 15/04/09   Time: 14:45
//Updated in $/Leap/Source/Model
//Created "Copy Survey" Module
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 4/14/09    Time: 6:17p
//Updated in $/Leap/Source/Model
//modified in feedback label & role wise graph
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 1/22/09    Time: 12:18p
//Updated in $/Leap/Source/Model
//add new query to check duplicate value
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 13/01/09   Time: 16:34
//Updated in $/Leap/Source/Model
//Modified Code as one field is added in feedback_grade table
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 1/07/09    Time: 5:22p
//Updated in $/Leap/Source/Model
//modified code accordingly new table feedback_survey_answer
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 1/07/09    Time: 1:29p
//Updated in $/Leap/Source/Model
//modified to check constraint
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 1/06/09    Time: 3:55p
//Updated in $/Leap/Source/Model
//make new function getCheckFeedBackLabel() to check duplicate record
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 12/12/08   Time: 14:42
//Updated in $/Leap/Source/Model
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 12/12/08   Time: 13:58
//Updated in $/Leap/Source/Model
//Corrected Question List Query
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 12/09/08   Time: 5:30p
//Updated in $/Leap/Source/Model
//Added the functionality that once a question has been used in student
//feedback module it can not be edited or deleted
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/21/08   Time: 12:10p
//Updated in $/Leap/Source/Model
//Corrected problem corresponding to Issues [20-11-08] Build
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:41p
//Created in $/Leap/Source/Model
//Created FeedBack Masters
?>