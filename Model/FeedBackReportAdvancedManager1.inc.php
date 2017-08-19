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

public function getFeedbackAll1($labelId='0',$timeTableLabelId='0'){
   
     $query="SELECT 
   		  a.studentId, a.finalId
	     FROM 
	  	  student s, `feedback_student_status` a, `feedbackadv_survey` b
	     WHERE
	          s.studentId = a.studentId AND
	     	  b.feedbacksurveyId = a.surveyId AND
	  	  b.timeTableLabelId ='$timeTableLabelId' AND 
	          a.surveyId =$labelId";
	    	 
 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 
 }
 
 
 public function getFeedbackQuestionAnswer($labelId='',$timeTableLabelId=''){
   
     $query="SELECT 
                  a.studentId,a.feedbackToQuestionId,a.answerSetOptionId
	         FROM 
	  	          student s, feedback_student_status a, feedbackadv_survey b
	         WHERE
	                s.studentId = a.studentId AND
	     	        b.feedbacksurveyId = a.surveyId AND
                    a.isStatus IN (0,1) AND
	  	            b.timeTableLabelId = '$timeTableLabelId' AND 
	                a.surveyId = '$labelId' ";
	    	 
             return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }
 
 
  public function getFeedbackName1($labelId='0'){
   
     $query=" SELECT 
     			feedbackSurveyLabel 
     		FROM 
     			feedbackadv_survey
     	      WHERE 
     	      		feedbackSurveyId=$labelId";
	    	 
 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 
 }
 
 
 ////This function is used to get the option id from db
  public function getOptionPoint($answerOptionId=''){
   
     $query="SELECT
     		     answerSetOptionId,optionPoints
	      FROM 
	             `feedbackadv_answer_set_option`
	      WHERE
	      	      answerSetOptionId
		      IN ( $answerOptionId )";
	    	 
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 
 }
  //This function is used to get the question id  from db
  public function getOptionName($answerOptionId=''){
  
     if($answerOptionId=='') {
       $answerOptionId=0;  
     }
       
     $query="SELECT
     		      DISTINCT fq.feedbackQuestionId, fq.feedbackQuestion, fq.answerSetId
	         FROM 
                  feedbackadv_to_question ftq, feedbackadv_questions fq
             WHERE
                  ftq.feedbackQuestionId = fq.feedbackQuestionId AND
	      	      ftq.feedbackToQuestionId IN ($answerOptionId) ";
	    	
     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 
 }
 
 //This function is used to get the question id  from db
  public function getAnswerOptionValue($answerOptionId='',$orderBy='optionLabel'){
      
      if($answerOptionId=='') {
        $answerOptionId=0;  
      }
  
     $query="SELECT
                   DISTINCT fq.answerSetId, ansOpt.optionLabel, ansOpt.optionPoints, ansOpt.printOrder, ansOpt.answerSetOptionId
             FROM 
                  feedbackadv_to_question ftq, feedbackadv_questions fq, feedbackadv_answer_set_option ansOpt
             WHERE
                  ftq.feedbackQuestionId = fq.feedbackQuestionId AND
                  ftq.feedbackToQuestionId IN ($answerOptionId) AND
                  ansOpt.answerSetId = fq.answerSetId 
             ORDER BY
                   $orderBy";
            
     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }
 
 
}
?>
