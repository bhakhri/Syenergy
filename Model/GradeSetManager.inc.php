<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "GradeSet" Module
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class GradeSetManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "GradeSetManager" CLASS
//
//-------------------------------------------------------------------------------     

	
	private function __construct() {
	}
	

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "GradeSetManager" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
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
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Grade Set
//
//
//-------------------------------------------------------------------------------       
	
	public function addGradeSet() {
		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		return SystemDatabaseManager::getInstance()->runAutoInsert('grades_set', array('gradeSetName','isActive','instituteId'), array( add_slashes(strtoupper($REQUEST_DATA['gradeSetName'])),$REQUEST_DATA['isActive'], $instituteId));
	}
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Grade Set
//
//-------------------------------------------------------------------------------       	
	
    public function editGradeSet($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        return SystemDatabaseManager::getInstance()->runAutoUpdate('grades_set', array('gradeSetName','isActive'), array(add_slashes(strtoupper($REQUEST_DATA['gradeSetName'])),$REQUEST_DATA['isActive']), "gradeSetId=$id AND instituteId = $instituteId" );
    }    
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Grade Set Label
//
//-------------------------------------------------------------------------------       
 	
	
    public function getgradeSetName($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        if ($conditions == '') {
			  $conditions .= ' WHERE ';
        }
		  else {
			  $conditions .= ' AND ';
		  }
		  $conditions .= " instituteId = $instituteId ";
        $query = "SELECT gradeSetId, gradeSetName, isActive
        FROM grades_set 
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    


//THIS FUNCTION IS USED FOR get information For "gradeSet printCsv"
   

  public function getGradeSetDetail($conditions='', $limit = '', $orderBy=' gradeSetName') {
        global $sessionHandler;  
        
		$query = "	SELECT 
								gradeSetId,
								gradeSetName,
								isActive
								
					FROM   grades_set 					
						
						$conditions
						ORDER BY $orderBy $limit";
				return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

		
    }
	


//------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A "Grade Set" RECORD
//
//-------------------------------------------------------------------------------     

    public function deleteGradeSet($gradeSetId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     
        $query = "DELETE 
        FROM grades_set 
        WHERE gradeSetId=$gradeSetId AND instituteId = $instituteId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Grade Set LIST 
//-------------------------------------------------------------------------------          
    public function getGradeSet($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        if ($conditions == '') {
			  $conditions .= ' WHERE ';
        }
		  else {
			  $conditions .= ' AND ';
		  }
		  $conditions .= " instituteId = $instituteId ";
     
        $query = "SELECT gradeSetId, gradeSetName, isActive
        FROM grades_set 
        $conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Grade Set LIST 
//-------------------------------------------------------------------------------          
   	
	
    public function getGradeSetList($conditions='', $limit = '', $orderBy=' gradeSetName') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        if ($conditions == '') {
			  $conditions .= ' WHERE ';
        }
		  else {
			  $conditions .= ' AND ';
		  }
		  $conditions .= " instituteId = $instituteId ";
        $query = "SELECT gradeSetId, gradeSetName, IF(isActive=1,'Yes','No') AS isActive   
		FROM grades_set $conditions ORDER BY $orderBy $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   


//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Making All Grade Set Inactive
//
//$conditions :db clauses
// Author :Parveen Sharma
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function makeAllGradeSetInActive($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "UPDATE grades_set ps 
        SET ps.isActive=0
        WHERE ps.isActive=1
		  AND ps.instituteId = $instituteId
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "GradeSet" TABLE
//-------------------------------------------------------------------------------       
    public function getTotalGradeSet($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        if ($conditions == '') {
			  $conditions .= ' WHERE ';
        }
		  else {
			  $conditions .= ' AND ';
		  }
		  $conditions .= " instituteId = $instituteId ";
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        grades_set $conditions ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Grades" TABLE
//-------------------------------------------------------------------------------       
    public function getCheckGrades($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        if ($conditions == '') {
			  $conditions .= ' WHERE ';
        }
		  else {
			  $conditions .= ' AND ';
		  }
		  $conditions .= " instituteId = $instituteId ";

        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        grades WHERE gradeSetId = ".$conditions;
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
}

?>