<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeedBackQuestionSetAdvancedManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
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
    
//-----------------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING An Adv. Feedback Question Set
// Author :Dipanjan Bhattacharjee 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------    
    public function addAdvFeedbackQuestionSet($setName) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query="INSERT INTO  
                          feedbackadv_question_set
                SET
                    feedbackQuestionSetName='$setName',
                    instituteId=$instituteId
                ";
		return SystemDatabaseManager::getInstance()->executeUpdate($query); 
	}
    
    
    public function addAdvFeedbackQuestionSetMultiple($insertString) {
        //global $sessionHandler;
        //$instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query="INSERT INTO  
                          feedbackadv_question_set (feedbackQuestionSetName,instituteId)
                VALUES    $insertString
                ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
    }
    

//---------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Editing An Adv. Feedback Question Set 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------    
    public function editAdvFeedbackQuestionSet($setId,$setName) {
        //instituteId will not be updated to prevent : Question set cannot span across multiple institutes.
        $query="UPDATE
                      feedbackadv_question_set
                SET
                      feedbackQuestionSetName='$setName'
                WHERE
                      feedbackQuestionSetId=$setId
                ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    }    
    
//-------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Question Set Informations
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------         
    public function getFeedbackQuestionSet($conditions='') {
     
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        *
                  FROM 
                        feedbackadv_question_set
                  WHERE
                        instituteId=$instituteId
                  $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

//-------------------------------------------------------------
// THIS FUNCTION IS USED FOR Checking Question Set usage
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------         
    public function getQuestionSetUsage($setId) {
        
        //check with answer table
        $query = "SELECT 
                        COUNT(*) AS cnt
                  FROM 
                        feedbackadv_questions 
                  WHERE
                        feedbackQuestionSetId=$setId
                 ";
        $returnArray=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        if($returnArray[0]['cnt']!=0){ //if this is used
          return 1;            
        }
    }    
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING An Adv. Question Set
// Author :Dipanjan Bhattacharjee 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------      
    public function deleteQuestionSet($setId) {
     
        $query = "DELETE 
                  FROM 
                        feedbackadv_question_set
                  WHERE 
                        feedbackQuestionSetId=$setId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Adv. Question Set List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------       
    public function getFeedbackQuestionSetList($conditions='', $limit = '', $orderBy=' qs.feedbackQuestionSetName') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                         qs.feedbackQuestionSetId,
                         qs.feedbackQuestionSetName,
                         IF(q.feedbackQuestionSetId IS NULL,-1,q.feedbackQuestionSetId) AS usedQuestionSetId
                 FROM 
                         feedbackadv_question_set qs
                         LEFT JOIN feedbackadv_questions q ON q.feedbackQuestionSetId=qs.feedbackQuestionSetId
                 WHERE
                         qs.instituteId=$instituteId
                 $conditions
                 GROUP BY qs.feedbackQuestionSetId 
                 ORDER BY $orderBy 
                 $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Total Adv. Question Set Count
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------      
    public function getTotalFeedbackQuestionSet($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                         COUNT(*) AS totalRecords  
                  FROM 
                         feedbackadv_question_set qs
                         LEFT JOIN feedbackadv_questions q ON q.feedbackQuestionSetId=qs.feedbackQuestionSetId
                  WHERE
                         qs.instituteId=$instituteId
                 $conditions
                 GROUP BY qs.feedbackQuestionSetId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

   
  
}
// $History: FeedBackQuestionSetAdvancedManager.inc.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 18/02/10   Time: 18:30
//Updated in $/LeapCC/Model
//Modified UI design: Now users can add multiple records at a time.
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 1/18/10    Time: 2:42p
//Updated in $/LeapCC/Model
//made updations under feedback module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/01/10   Time: 12:30
//Created in $/LeapCC/Model
//Created  "Question Set Master"  module
?>