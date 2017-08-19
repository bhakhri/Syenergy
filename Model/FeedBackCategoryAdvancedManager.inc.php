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

class FeedBackCategoryAdvancedManager {
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
// THIS FUNCTION IS USED FOR ADDING An Adv. Feedback category
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------    
	//public function addAdvFeedbackCategory($labelId,$catName,$parentCatId,$catReln,$subjectType,$catDesc,$printOrder,$catComments) {
    public function addAdvFeedbackCategory($catName,$parentCatId,$catReln,$subjectType,$catDesc,$printOrder,$catComments) {
        if($parentCatId==''){
            $parentCatId='NULL';
        }
        if($subjectType==''){
            $subjectType='NULL';
        }
        /*
        $query="INSERT INTO  
                          feedbackadv_category
                SET
                    feedbackSurveyId=$labelId,
                    feedbackCategoryName='$catName',
                    parentFeedbackCategoryId=$parentCatId,
                    feedbackType=$catReln,
                    subjectTypeId=$subjectType,
                    hasFeedbackComments=$catComments,
                    description='$catDesc',
                    printOrder='$printOrder'
                ";
        */
        $query="INSERT INTO  
                          feedbackadv_category
                SET
                    feedbackCategoryName='$catName',
                    parentFeedbackCategoryId=$parentCatId,
                    feedbackType=$catReln,
                    subjectTypeId=$subjectType,
                    hasFeedbackComments=$catComments,
                    description='$catDesc',
                    printOrder='$printOrder'
                ";
		return SystemDatabaseManager::getInstance()->executeUpdate($query); 
	}
    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR Editing An Adv. Feedback category
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------    
    //public function editAdvFeedbackCategory($catId,$labelId,$catName,$parentCatId,$catReln,$subjectType,$catDesc,$printOrder,$catComments) {
    public function editAdvFeedbackCategory($catId,$catName,$parentCatId,$catReln,$subjectType,$catDesc,$printOrder,$catComments) {
        if($parentCatId==''){
            $parentCatId='NULL';
        }
        if($subjectType==''){
            $subjectType='NULL';
        }
       /* 
        $query="UPDATE
                      feedbackadv_category
                SET
                      feedbackSurveyId=$labelId,
                      feedbackCategoryName='$catName',
                      parentFeedbackCategoryId=$parentCatId,
                      feedbackType=$catReln,
                      subjectTypeId=$subjectType,
                      hasFeedbackComments=$catComments,
                      description='$catDesc',
                      printOrder='$printOrder'
                WHERE
                      feedbackCategoryId=$catId
                ";
        */
        $query="UPDATE
                      feedbackadv_category
                SET
                      feedbackCategoryName='$catName',
                      parentFeedbackCategoryId=$parentCatId,
                      feedbackType=$catReln,
                      subjectTypeId=$subjectType,
                      hasFeedbackComments=$catComments,
                      description='$catDesc',
                      printOrder='$printOrder'
                WHERE
                      feedbackCategoryId=$catId
                ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    }    
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Parent Category
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------         
    public function getParentCategory($conditions='') {
        /*
        $query = "SELECT 
                        fc.feedbackCategoryId,
                        fc.feedbackCategoryName
                  FROM 
                        feedbackadv_category fc,feedbackadv_survey fs
                  WHERE
                        fc.feedbackSurveyId=fs.feedbackSurveyId
                        AND ( fc.parentFeedbackCategoryId IS NULL OR fc.parentFeedbackCategoryId='') 
                        $conditions
                 ";
        */
        $query = "SELECT 
                        fc.feedbackCategoryId,
                        fc.feedbackCategoryName
                  FROM 
                        feedbackadv_category fc
                  WHERE
                        ( fc.parentFeedbackCategoryId IS NULL OR fc.parentFeedbackCategoryId='' ) 
                        $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Category Informations
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------         
    public function getFeedbackCategory($conditions='') {
     
        $query = "SELECT 
                        *
                  FROM 
                        feedbackadv_category
                  $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

//-------------------------------------------------------------
// THIS FUNCTION IS USED FOR Checking category usage
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------         
    public function getCategoryUsage($catId) {
        
        //check with answer table
        $query = "SELECT 
                        COUNT(*) AS cnt
                  FROM 
                        feedbackadv_survey_mapping fsm,
                        feedbackadv_survey_answer fsa
                  WHERE
                        fsm.feedbackMappingId=fsa.feedbackMappingId
                        AND fsm.feedbackCategoryId=$catId
                 ";
        $returnArray=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        if($returnArray[0]['cnt']!=0){ //if this is used
          return 1;            
        }
        
        //check with comments table
        $query = "SELECT 
                        COUNT(*) AS cnt
                  FROM 
                        feedbackadv_survey_comments
                  WHERE
                        feedbackCategoryId=$catId
                 ";
        $returnArray=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        if($returnArray[0]['cnt']!=0){ //if this is used
          return 1;            
        }
        
        //check with mapping table
        $query = "SELECT 
                        COUNT(*) AS cnt
                  FROM 
                        feedbackadv_to_question
                  WHERE
                        feedbackCategoryId=$catId
                 ";
        $returnArray=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        if($returnArray[0]['cnt']!=0){ //if this is used
          return 1;            
        }
        
        return 0;  //if this is not used
    }    
    

//-------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Category's print order Informations
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------         
    public function getPrintOrder($conditions='') {
     
        $query = "SELECT 
                        printOrder
                  FROM 
                        feedbackadv_category
                  $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//---------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Category's parent information
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------         
    public function checkParentCategory($catId) {
     
        $query = "SELECT 
                        COUNT(*) AS cnt
                  FROM 
                        feedbackadv_category
                  WHERE
                        parentFeedbackCategoryId=$catId                        
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }              
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING An Adv. category
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------      
    public function deleteCategory($catId) {
     
        $query = "DELETE 
                  FROM 
                        feedbackadv_category
                  WHERE 
                        feedbackCategoryId=$catId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    

//-------------------------------------------------------------
// THIS FUNCTION IS USED FOR Checking category usage
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------         
    public function getCategoryUsageList($conditions='') {
        require_once(BL_PATH . "/UtilityManager.inc.php"); 
        
        $returnArray=array();
        $retArray1=array();
        $retArray2=array();
        $retArray3=array();
        
        //fetch from answer table
        $query = "SELECT 
                        DISTINCT fsm.feedbackCategoryId
                  FROM 
                        feedbackadv_survey_mapping fsm,
                        feedbackadv_survey_answer fsa
                  WHERE
                        fsm.feedbackMappingId=fsa.feedbackMappingId 
                  $conditions
                 ";
        $returnArray1=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        if(count($returnArray1)>0 and is_array($returnArray1)){
          $retArray1=explode(',',UtilityManager::makeCSList($returnArray1,'feedbackCategoryId'));
        }
        
        
        //check with comments table
        $query = "SELECT 
                        DISTINCT feedbackCategoryId
                  FROM 
                        feedbackadv_survey_comments
                  $conditions
                 ";
        $returnArray2=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        if(count($returnArray2)>0 and is_array($returnArray2)){
          $retArray2=explode(',',UtilityManager::makeCSList($returnArray2,'feedbackCategoryId'));
        }
        
        //check with comments table
        $query = "SELECT 
                        DISTINCT feedbackCategoryId
                  FROM 
                        feedbackadv_to_question
                  $conditions
                 ";
        $returnArray3=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        if(count($returnArray3)>0 and is_array($returnArray3)){
          $retArray3=explode(',',UtilityManager::makeCSList($returnArray3,'feedbackCategoryId'));
        }

        $returnString=implode(',',$retArray1).implode(',',$retArray2).implode(',',$retArray3);
        if($returnString!=''){
         $returnArray=array_unique(explode(',',$returnString));
        }
        
        return $returnArray; 
    }    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Adv. Category List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------       
    public function getFeedbackCategoryList($conditions='', $limit = '', $orderBy=' fc.feedbackCategoryName') {
        global $sessionHandler;
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        /*
        $query = "SELECT 
                         fc.feedbackCategoryId,
                         fc.feedbackCategoryName,
                         IF(fc1.feedbackCategoryName IS NULL,'',fc1.feedbackCategoryName) AS parentCategoryName,
                         fc.parentFeedbackCategoryId,
                         fc.feedbackType,
                         fc.printOrder,
                         st.subjectTypeName,
                         fs.feedbackSurveyLabel
                 FROM 
                         time_table_labels ttl,
                         feedbackadv_survey fs,
                         feedbackadv_category fc
                         LEFT JOIN subject_type st ON fc.subjectTypeId=st.subjectTypeId
                         LEFT JOIN feedbackadv_category fc1 ON fc.parentFeedbackCategoryId=fc1.feedbackCategoryId
                 WHERE 
                         fc.feedbackSurveyId=fs.feedbackSurveyId
                         AND fs.timeTableLabelId=ttl.timeTableLabelId
                         AND ttl.instituteId=$instituteId
                         AND ttl.sessionId=$sessionId
                 $conditions 
                 ORDER BY $orderBy 
                 $limit";
        */
        $query = "SELECT 
                         fc.feedbackCategoryId,
                         fc.feedbackCategoryName,
                         IF(fc1.feedbackCategoryName IS NULL,'',fc1.feedbackCategoryName) AS parentCategoryName,
                         fc.parentFeedbackCategoryId,
                         fc.feedbackType,
                         fc.printOrder,
                         st.subjectTypeName
                 FROM 
                         feedbackadv_category fc
                         LEFT JOIN subject_type st ON fc.subjectTypeId=st.subjectTypeId
                         LEFT JOIN feedbackadv_category fc1 ON fc.parentFeedbackCategoryId=fc1.feedbackCategoryId
                 $conditions 
                 ORDER BY $orderBy 
                 $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Total Adv. Category Count
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (09.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------      
    public function getTotalFeedbackCategory($conditions='') {
        global $sessionHandler;
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        /*
        $query = "SELECT 
                         fc.feedbackCategoryId,
                         IF(fc1.feedbackCategoryName IS NULL,'',fc1.feedbackCategoryName) AS parentCategoryName  
                  FROM 
                         time_table_labels ttl,
                         feedbackadv_survey fs,
                         feedbackadv_category fc
                         LEFT JOIN subject_type st ON fc.subjectTypeId=st.subjectTypeId
                         LEFT JOIN feedbackadv_category fc1 ON fc.parentFeedbackCategoryId=fc1.feedbackCategoryId
                  WHERE 
                         fc.feedbackSurveyId=fs.feedbackSurveyId
                         AND fs.timeTableLabelId=ttl.timeTableLabelId
                         AND ttl.instituteId=$instituteId
                         AND ttl.sessionId=$sessionId
                  $conditions ";
        */
        
        $query = "SELECT 
                         fc.feedbackCategoryId,
                         IF(fc1.feedbackCategoryName IS NULL,'',fc1.feedbackCategoryName) AS parentCategoryName  
                  FROM 
                         feedbackadv_category fc
                         LEFT JOIN subject_type st ON fc.subjectTypeId=st.subjectTypeId
                         LEFT JOIN feedbackadv_category fc1 ON fc.parentFeedbackCategoryId=fc1.feedbackCategoryId
                  $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}
// $History: FeedBackCategoryAdvancedManager.inc.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 18/02/10   Time: 12:59
//Updated in $/LeapCC/Model
//Done bug fixing.
//Bug ids---
//0002900,0002899,0002898,0002897
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 25/01/10   Time: 16:29
//Updated in $/LeapCC/Model
//Corrected table column names
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 25/01/10   Time: 15:52
//Updated in $/LeapCC/Model
//Made UI related changes as instructed by sachin sir
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 25/01/10   Time: 11:56
//Updated in $/LeapCC/Model
//Corrected queries
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/01/10    Time: 18:29
//Updated in $/LeapCC/Model
//Updated "Advanced Feedback Category" module as feedbackSurveyId is
//removed from table
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/01/10    Time: 16:48
//Created in $/LeapCC/Model
//Created module "Advanced Feedback Category Module"
//
//*****************  Version 1  *****************
?>