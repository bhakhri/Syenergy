<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "feedbackadv_answer_set" table
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeedbackAnswerSetManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ComplaintManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ComplaintManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
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
// THIS FUNCTION IS USED FOR ADDING A COMPLAINTCATEGORY
//
// Author :Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addAnswerSet() {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId'); 
     $query="INSERT INTO feedbackadv_answer_set (answerSetName,instituteId) 
      VALUES('".addslashes(htmlentities(($REQUEST_DATA['answerSetName'])))."',$instituteId)"; 
      
      return SystemDatabaseManager::getInstance()->executeUpdate($query);     
        
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING COMPLAINT CATEGORY
//
//$id:cityId
// Author :Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editAnswerSet($id) {
        global $REQUEST_DATA;
         global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     $query="UPDATE feedbackadv_answer_set SET answerSetName ='".addslashes(htmlentities(($REQUEST_DATA['answerSetName'])))."',
     instituteId=$instituteId
        WHERE   answerSetId=".$id;
       
       return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    } 


	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING COMPLAINT CATEGORY LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getAnswerSet($conditions='') {
        $query = "SELECT * 
        FROM feedbackadv_answer_set
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING COMPLAINT CATEGORY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getAnswerSetList($conditions='', $filter, $orderBy=' answerSetName', $limit = '') {
     
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $query = "	SELECT * 
					FROM feedbackadv_answer_set 
                    WHERE
                       instituteId=$instituteId 
					$filter
			        ORDER BY $orderBy 
					$limit";
        //echo $query;
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF COMPLAINT  CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalAnswerSet($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
      global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');            
       $query = "SELECT COUNT(*) AS totalRecords 
        FROM feedbackadv_answer_set 
        WHERE
                       instituteId=$instituteId
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF COMPLAINT CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function checkAnswerSet($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
      $query = "SELECT count(answerSetId) AS foundRecord 
        FROM  feedbackadv_answer_set 
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING COMPLAINTCATEGORY
//
//$testtypeId :testTypeCategoryId  of testtypecategory
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteAnswerSet($id) {
     
        $query = "DELETE 
        FROM feedbackadv_answer_set
        WHERE answerSetId=$id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF COMPLAINT CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function checkExistanceAnswerSet($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
      $query = "SELECT count(answerSetId) AS foundRecord 
        FROM  feedbackadv_answer_set_option
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF COMPLAINT CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function checkExistanceQuestion($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
      $query = "SELECT count(answerSetId) AS foundRecord 
        FROM  feedbackadv_questions
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF COMPLAINT CATEGORY
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (06.02.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function checkExistanceAnswer($id) {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
      $query = "SELECT 
                        COUNT(answerSetOptionId) AS totalRecord 
                  FROM 
                        feedbackadv_survey_answer 
                  WHERE 
                        answerSetOptionId 
                        IN 
                         (
                            SELECT 
                                answerSetOptionId 
                            FROM 
                                feedbackadv_answer_set_option 
                            WHERE 
                                answerSetId = $id)";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
                          
}

?>
