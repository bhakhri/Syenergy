<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "feedbackadv_answer_set" table
// Author :Gurkeerat Sidhu 
// Created on : (12.01.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeedbackOptionsManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ComplaintManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ComplaintManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (12.01.2010)
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
// THIS FUNCTION IS USED FOR ADDING A  FeedBack Grades
//
// Author :Gurkeerat Sidhu 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
   /* public function addFeedBackOptions() {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoInsert('feedbackadv_answer_set_option', 
        array('answerSetId','optionLabel','optionPoints','printOrder'), 
        array($REQUEST_DATA['surveyId'],strtoupper(add_slashes($REQUEST_DATA['optionLabel'])),add_slashes(trim($REQUEST_DATA['optionPoints'])),add_slashes(trim($REQUEST_DATA['printOrder'])) ) 
        );
    } */
    
    public function addFeedBackOptions($value) {

        $query ="INSERT INTO feedbackadv_answer_set_option(answerSetId,optionLabel,optionPoints,printOrder) VALUES $value ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//--------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A FeedBack Options
//
//$id:busRouteId
// Author :Gurkeerat Sidhu 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------        
    public function editFeedBackOptions($id) {
        global $REQUEST_DATA;
    
        return SystemDatabaseManager::getInstance()->runAutoUpdate('feedbackadv_answer_set_option', 
        array('answerSetId','optionLabel','optionPoints','printOrder'), 
        array($REQUEST_DATA['surveyId'],(add_slashes($REQUEST_DATA['optionLabel'])),add_slashes(trim($REQUEST_DATA['optionPoints'])),add_slashes(trim($REQUEST_DATA['printOrder'])) ), 
              "answerSetOptionId=$id" 
            );
    }   
//----------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Options Label
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------         
    public function getFeedBackOptions($conditions='') {
     
        $query = "SELECT 
                        answerSetOptionId,answerSetId,optionLabel,optionPoints,printOrder
                  FROM 
                        feedbackadv_answer_set_option
                        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A FeedBack Options
//
//$FeedBackOptionsId :FeedBackOptionsId of the FeedBack Options
// Author :Gurkeerat Sidhu 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteFeedBackOptions($answerSetOptionId) {
     
        $query = "DELETE 
                  FROM 
                        feedbackadv_answer_set_option 
                  WHERE 
                        answerSetOptionId=$answerSetOptionId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeedBack Options LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getFeedBackOptionsList($conditions='', $limit = '', $orderBy=' faso.optionLabel') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                        faso.answerSetOptionId,
                        fas.answerSetName, 
                        faso.optionLabel,
                        faso.optionPoints,
                        faso.printOrder
                  FROM 
                        feedbackadv_answer_set_option faso, feedbackadv_answer_set fas
                  WHERE 
                        faso.answerSetId=fas.answerSetId
                        AND fas.instituteId=$instituteId
                        $conditions 
                        ORDER BY $orderBy 
                        $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF FeedBack Options 
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalFeedBackOptions($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                         COUNT(*) AS totalRecords
                  FROM 
                        feedbackadv_answer_set_option faso, feedbackadv_answer_set fas
                  WHERE 
                        faso.answerSetId=fas.answerSetId
                        AND fas.instituteId=$instituteId
                        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
//---------------------------------------------------------------------
// THIS FUNCTION IS USED FOR chaecking overlaping grade lables
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------       
    
    public function checkFeedBackOptions($labelName,$labelIdCondition='',$surveyId) {

        $query = "SELECT answerSetOptionId,optionLabel,optionPoints
        FROM feedbackadv_answer_set_option 
        WHERE 
         (
          UCASE(optionLabel)='".strtoupper(add_slashes($labelName))."' 
         )
        AND answerSetId=".$surveyId."  
        $labelIdCondition  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }                  
    

    //---------------------------------------------------------------------
// THIS FUNCTION IS USED FOR chaecking overlaping grade lables
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------       
    
    public function checkFeedBackLabel($labelValue,$labelIdCondition='',$surveyId) {

        $query = "SELECT answerSetOptionId,optionLabel,optionPoints
        FROM feedbackadv_answer_set_option 
        WHERE 
         (
          optionPoints=$labelValue
         )
        AND answerSetId=".$surveyId."  
        $labelIdCondition  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    
    //---------------------------------------------------------------------
// THIS FUNCTION IS USED FOR chaecking overlaping grade lables
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------       
    
    public function checkFeedBackOrder($printOrder,$labelIdCondition='',$surveyId) {

        $query = "SELECT answerSetOptionId,optionLabel,optionPoints,printOrder
        FROM feedbackadv_answer_set_option 
        WHERE 
         (
          printOrder=$printOrder
         )
        AND answerSetId=".$surveyId."  
        $labelIdCondition  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }                      

//---------------------------------------------------------------------
// THIS FUNCTION IS USED FOR chaecking overlaping grade lables
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (6.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------       
    
    public function checkFeedBackOptions2($conditions='') {
     
        $query = "SELECT answerSetOptionId,optionLabel,optionPoints
        FROM feedbackadv_answer_set_option
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    //---------------------------------------------------------------------
// THIS FUNCTION IS USED FOR chaecking overlaping grade lables
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (6.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------       
    
    public function checkFeedBackLabel2($conditions='') {
     
        $query = "SELECT answerSetOptionId,optionLabel,optionPoints
        FROM feedbackadv_answer_set_option
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    //---------------------------------------------------------------------
// THIS FUNCTION IS USED FOR chaecking overlaping grade lables
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (6.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------       
    
    public function checkFeedBackOrder2($conditions='') {
     
        $query = "SELECT answerSetOptionId,optionLabel,optionPoints,printOrder
        FROM feedbackadv_answer_set_option
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }     
 //---------------------------------------------------------------------
// THIS FUNCTION IS USED FOR chaecking of grade lables uses
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (6.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------    
 public function checkOptionsDependency($id) {
        
        $query = "SELECT count(*) AS cnt
                FROM
                    feedbackadv_survey_answer
                WHERE
                    answerSetOptionId = $id";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }                    
//---------------------------------------------------------------------
// THIS FUNCTION IS USED FOR chaecking of grade lables uses
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------       
    
  /*  public function checkFeedBackOptionsUses($gradeId) {

        //First Check it against general feedback table
        $query = "SELECT answerSetOptionId FROM  feedback_survey_answer WHERE  answerSetOptionId=".$gradeId;
        $ret1=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

        if($ret1[0]['answerSetOptionId']==''){ //if this is not used in general table
           //Then Check it against teacher feedback table
           $query = "SELECT answerSetOptionId FROM  feedback_teacher WHERE  answerSetOptionId=".$gradeId;
           $ret2=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
           return $ret2[0]['answerSetOptionId'];
        }
        else{
           return $ret1[0]['answerSetOptionId'];
        }
    }   */ 
    


                          
}

?>
