<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Evaluation Criteria" TABLE
//
//
// Author :Rajeev Aggarwal 
// Created on : (13.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class EvaluationCriteriaManager{
	private static $instance = null;
	
	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "EVALUATION CRITIERIA" CLASS
	//
	// Author :Rajeev Aggarwal 
	// Created on : (12.06.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------     
	private function __construct() {
	}
	//-------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "EVALUATION CRITIERIA" CLASS
	//
	// Author :Rajeev Aggarwal 
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
	//-------------------------------------------------------
	// THIS FUNCTION IS USED FOR ADDING A EVALUATION CRITIERIA
	//
	// Author :Rajeev Aggarwal 
	// Created on : (12.06.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------    

	public function addEvaluationCritieria() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('evaluation_criteria', array('evaluationCriteriaName'), array($REQUEST_DATA['evaluationCriteria']));
	}

	//-------------------------------------------------------
	// THIS FUNCTION IS USED FOR EDITING A EVALUATION CRITIERIA
	//
	//$id:cityId
	// Author :Rajeev Aggarwal 
	// Created on : (12.06.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------     
    public function editEvaluationCritieria($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('evaluation_criteria', array('evaluationCriteriaName'), array($REQUEST_DATA['evaluationCriteria']), "evaluationCriteriaId=$id" );
    }
	
	//-------------------------------------------------------
	// THIS FUNCTION IS USED FOR GETTING EVALUATION CRITIERIA LIST
	//
	//$conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (12.06.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------- 
    public function getEvaluationCritieria($conditions='') {
     
        $query = "SELECT evaluationCriteriaId,  evaluationCriteriaName   
        FROM evaluation_criteria  
        $conditions";
		 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
	 //-------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR DELETING A EVALUATION CRITIERIA
	//
	//$cityId :cityid of the City
	// Author :Rajeev Aggarwal 
	// Created on : (12.06.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------------------------------------------------------  
    public function deleteEvalutionCritieria($evalutioncritieriaId) {
     
        $query = "DELETE 
        FROM evaluation_criteria  
        WHERE evaluationCriteriaId=$evalutioncritieriaId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
	//-------------------------------------------------------
	// THIS FUNCTION IS USED FOR GETTING EVALUATION CRITIERIA LIST
	//
	//$conditions :db clauses
	//$limit:specifies limit
	//orderBy:sort on which column
	// Author :Rajeev Aggarwal 
	// Created on : (12.06.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------    
    public function getEvaluationCriteriaList($conditions='', $limit = '', $orderBy=' evaluationCriteriaId') {
     
        $query .= "SELECT evaluationCriteriaId,evaluationCriteriaName from evaluation_criteria";
		if($conditions)
			$query .= " Where $conditions";
        $query .= " ORDER BY $orderBy $limit";
		 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

	//---------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF EVALUATION CRITIERIA
	//
	//$conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (12.06.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------   
    public function getTotalEvaluationCriteriaType($conditions='') {
    
        $query .= "SELECT COUNT(*) AS totalRecords 
        FROM evaluation_criteria";
		if($conditions)
			$query .= " Where $conditions";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
	
	 
}
 
// $History: EvaluationCriteriaManager.inc.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/16/08    Time: 6:24p
//Updated in $/Leap/Source/Model
//updated bug no 0000069
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:28p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 6/25/08    Time: 7:05p
//Updated in $/Leap/Source/Model
//updated the defects and comments
?>