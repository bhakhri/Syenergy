<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "advfeedback_answer_set" table
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeedbackLabelManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ComplaintManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ComplaintManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
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
// THIS FUNCTION IS USED FOR GETTING TimeTable Label LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getFeedBackLabel($conditions='') {
        global $sessionHandler;

        $query = "SELECT 
                         ffl.feedbackSurveyId,
                         ffl.feedbackSurveyLabel,
                         ffl.isActive,
                         ffl.visibleFrom,
                         ffl.visibleTo,
                         ffl.noOfAttempts,
                         ffl.timeTableLabelId,
                         ffl.roleId,
                         ffl.extendTo
                  FROM  
                         feedbackadv_survey ffl,time_table_labels tt
                  WHERE  
                         ffl.timeTableLabelId = tt.timeTableLabelId
                         AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                         AND tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."  
                         $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }      
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A  FeedBack Label
//
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addFeedBackLabel($labelName,$timeTableLabelId,$isActive,$startDate,$toDate,$noOfAttempts,$roleId,$extendDate) {
        $query="INSERT INTO 
                           feedbackadv_survey
                SET
                           feedbackSurveyLabel='$labelName',
                           isActive=$isActive,
                           visibleFrom='$startDate',
                           visibleTo='$toDate',
                           noOfAttempts='$noOfAttempts',
                           timeTableLabelId=$timeTableLabelId,
                           roleId=$roleId,
                           extendTo='$extendDate'
                ";
         return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }
    
//--------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A FeedBack Label
//
//$id:busRouteId
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------        
    public function editFeedBackLabel($labelId,$labelName,$timeTableLabelId,$isActive,$startDate,$toDate,$noOfAttempts,$roleId,$usage,$extendDate) {
         /*$query="UPDATE 
                           feedbackadv_survey
                SET
                           feedbackSurveyLabel='$labelName',
                           isActive=$isActive,
                           visibleFrom='$startDate',
                           visibleTo='$toDate',
                           noOfAttempts='$noOfAttempts',
                           timeTableLabelId=$timeTableLabelId,
                           roleId=$roleId
                WHERE
                           feedbackSurveyId=$labelId 
                ";*/
         if ($usage == 1){
         $query="UPDATE 
                           feedbackadv_survey
                SET
                           feedbackSurveyLabel='$labelName',
                           isActive=$isActive,
                           visibleFrom='$startDate',
                           visibleTo='$toDate',
                           noOfAttempts='$noOfAttempts',
                           extendTo='$extendDate'
                           
                WHERE
                           feedbackSurveyId=$labelId 
                ";
         }  
         if ($usage == 2){
         $query="UPDATE 
                           feedbackadv_survey
                SET
                           isActive=$isActive,
                           visibleFrom='$startDate',
                           visibleTo='$toDate',
                           extendTo='$extendDate'
                WHERE
                           feedbackSurveyId=$labelId 
                ";
         } 
         if ($usage == 3){
         $query="UPDATE 
                           feedbackadv_survey
                SET
                           feedbackSurveyLabel='$labelName',
                           isActive=$isActive,
                           visibleFrom='$startDate',
                           visibleTo='$toDate',
                           noOfAttempts='$noOfAttempts',
                           timeTableLabelId=$timeTableLabelId,
                           roleId=$roleId,
                           extendTo='$extendDate'
                WHERE
                           feedbackSurveyId=$labelId 
                ";
         }     
         return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }
        
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A FeedBack Label
//
//$feedBackLabelId :feedBackLabelId of the feedback label
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteFeedBackLabel($feedBackLabelId) {
     
        $query = "DELETE 
                  FROM 
                        feedbackadv_survey 
                  WHERE 
                        feedbackSurveyId=$feedBackLabelId
                  ";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
   
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF FeedBack Labels 
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalFeedBackLabel($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM
                        time_table_labels tt,
                        feedbackadv_survey ffl
                        LEFT JOIN feedbackadv_to_question fq ON fq.feedbackSurveyId=ffl.feedbackSurveyId
                 WHERE  
                       ffl.timeTableLabelId = tt.timeTableLabelId
                       AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                       AND tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."  
                       $conditions
                 GROUP BY  ffl.feedbackSurveyId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Label LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getFeedBackLabelList($conditions='', $limit = '', $orderBy=' ffl.feedbackSurveyLabel') {
        global $sessionHandler;  
        
       /* $query = "SELECT 
                        ffl.feedbackSurveyId,
                        ffl.feedbackSurveyLabel,
                        ffl.visibleFrom,
                        ffl.visibleTo,
                        ffl.noOfAttempts,
                        ffl.timeTableLabelId,
                        ffl.isActive,
                        IF(fq.feedbackSurveyId IS NULL,-1,1) AS usedSurveyId
                 FROM
                        time_table_labels tt,
                        feedbackadv_survey ffl
                        LEFT JOIN feedbackadv_to_question fq ON fq.feedbackSurveyId=ffl.feedbackSurveyId
                 WHERE  
                       ffl.timeTableLabelId = tt.timeTableLabelId
                       AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                       AND tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."  
                 $conditions
                 GROUP BY  ffl.feedbackSurveyId 
                 ORDER BY $orderBy $limit";*/     
        $query = "SELECT 
                        ffl.feedbackSurveyId,
                        ffl.feedbackSurveyLabel,
                        ffl.visibleFrom,
                        ffl.visibleTo,
                        ffl.noOfAttempts,
                        ffl.timeTableLabelId,
                        ffl.isActive
                        
                 FROM
                        time_table_labels tt,
                        feedbackadv_survey ffl
                        LEFT JOIN feedbackadv_to_question fq ON fq.feedbackSurveyId=ffl.feedbackSurveyId
                 WHERE  
                       ffl.timeTableLabelId = tt.timeTableLabelId
                       AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                       AND tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."  
                 $conditions
                 GROUP BY  ffl.feedbackSurveyId 
                 ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }                
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED to check FEEDBACKSURVEY ID usage
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------- 
    public function checkFeedBackLabelUsage($conditions='') {
        
    $query = "SELECT
                    COUNT(feedbackSurveyId) AS cnt
              FROM   
                    feedbackadv_to_question
                    $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    

    
// ------------- functions to add class-----------
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING An event
//
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 
//public function addToClass($feedbackSurveyId,$classId) {
public function addToClass($insQuery) {
        /*        
        return SystemDatabaseManager::getInstance()->runAutoInsert('feedbackadv_to_class', array('feedbackSurveyId','classId'), 
        array($feedbackSurveyId,$classId) );
        */
        $query="INSERT INTO feedbackadv_to_class (feedbackSurveyId,classId) VALUES  ".$insQuery;
        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }   
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING An event
//
//$cityId :cityid of the City
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteToClass($feedbackSurveyId) {
     
        $query = "DELETE 
        FROM feedbackadv_to_class 
        WHERE feedbackSurveyId=$feedbackSurveyId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }    
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING event LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getToClass($conditions='') {
     
     
        
        $query = "SELECT classId
        FROM feedbackadv_to_class
        WHERE 
        $conditions ";
        
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING event LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (03.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function checkFeedbackLabelInSurveyMapping($conditions='') {
        $query = "SELECT  
                        COUNT(feedbackSurveyId) AS foundRecord 
                  FROM  
                        feedbackadv_survey_mapping
                        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING event LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (03.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function checkFeedbackLabelInAnswer($labelId) {
        $query = "SELECT 
                        COUNT(feedbackToQuestionId) AS totalRecord 
                  FROM 
                        feedbackadv_survey_answer 
                  WHERE 
                        feedbackToQuestionId 
                        IN 
                         (
                            SELECT 
                                feedbackToQuestionId 
                            FROM 
                                feedbackadv_to_question 
                            WHERE 
                                feedbackSurveyId = $labelId)";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING event LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (03.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getMaxDate($labelId) {
        $query = "SELECT 
                        MAX(dated) AS maxDate 
                  FROM 
                        feedbackadv_survey_answer 
                  WHERE 
                        feedbackToQuestionId 
                        IN 
                         (
                            SELECT 
                                feedbackToQuestionId 
                            FROM 
                                feedbackadv_to_question 
                            WHERE 
                                feedbackSurveyId = $labelId)";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING event LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (03.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
  /*  public function getValueForTeacherMapping() {
        $query = "SELECT     
                        distinct tt.timeTableLabelId,
                        (select classId from `group` g where g.groupId=tt.groupId) gg ,
                        tt.groupId,
                        tt.subjectId,
                        tt.employeeId
                 FROM     
                         ".TIME_TABLE_TABLE."  tt
                 WHERE  
                        tt.timeTableLabelId 
                        IN 
                        (
                            SELECT 
                                time_table_labels.timeTableLabelId
                            FROM 
                                time_table_labels
                            )
                                and tt.toDate IS NULL
                            HAVING 
                                gg IS NOT NULL";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } */ 
     public function getValueForTeacherMapping() {
        $query = "SELECT     
                            distinct tt.timeTableLabelId,
                            g.classId,
                            tt.groupId,
                            tt.subjectId,
                            tt.employeeId
                 FROM     
                             ".TIME_TABLE_TABLE."  tt 
                 INNER JOIN 
                            `group` g 
                 ON 
                            tt.groupId = g.groupId 
                 WHERE  
                            tt.timeTableLabelId 
                            IN 
                            (
                                SELECT 
                                    time_table_labels.timeTableLabelId
                                FROM 
                                    time_table_labels
                             )
                 and
                           tt.toDate IS NULL
                 and          
                        CONCAT(  
                            tt.timeTableLabelId,'#',g.classId,'#',tt.groupId,'#',tt.subjectId)
                        NOT IN 
                        (
                        SELECT 
                            concat_ws('#',tm.timeTableLabelId,tm.classId,tm.groupId,tm.subjectId) 
                        FROM 
                            feedbackadv_teacher_mapping tm
                            )
               
               HAVING 
                        g.classId IS NOT NULL";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING event LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (03.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function countTeacherMapping($concatStr) {
        $query = "SELECT
                        timeTableLabelId,
                        classId,
                        groupId,
                        subjectId
                  FROM
                        feedbackadv_teacher_mapping
                  WHERE
                        CONCAT
                            (timeTableLabelId,'#',classId,'#',groupId,'#',subjectId) 
                        IN 
                            ('.$concatStr.')";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A  FeedBack Grades
//
// Author :Gurkeerat Sidhu 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
  
    
    public function addTeacherMappingData($value) {

        $query ="INSERT INTO feedbackadv_teacher_mapping(timeTableLabelId,classId,groupId,subjectId,employeeId) VALUES $value ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }                                                           
}

?>
