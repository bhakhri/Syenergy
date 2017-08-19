<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Thoughts" TABLE
//

// Author :Parveen Sharma
// Created on : (18.3.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ThoughtsManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ThoughtsManager" CLASS
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ThoughtsManager" CLASS
//-------------------------------------------------------------------------------       
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Thoughts
//--------------------------------------------------------    
	public function addThoughts() {
		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		return SystemDatabaseManager::getInstance()->runAutoInsert('thoughts', array('thought','instituteId'), array(strtoupper($REQUEST_DATA['thought']), $instituteId));
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Thoughts
//--------------------------------------------------------        
    public function editThoughts($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('thoughts', array('thought'), array(strtoupper($REQUEST_DATA['thought'])), "thoughtId=$id" );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Thoughts LIST
//--------------------------------------------------------         
    public function getThoughts($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        $query = "SELECT thoughtId,thought
        FROM thoughts
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    

    //**AS BUSROUTE TABLE IS INDEPENDENT NO NEED TO CHECK FOR INTEGRITY CONSTRAINTS**//

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Thoughts
//--------------------------------------------------------------------------------------------------------      
    public function deleteThoughts($Id) {
     
        $query = "DELETE 
        FROM thoughts
        WHERE thoughtId=$Id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Thoughts LIST
//--------------------------------------------------------       
    
    public function getThoughtsList($conditions='', $limit = '', $orderBy='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
   
        $query = "SELECT thoughtId,thought
        FROM thoughts $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Thoughtss
//----------------------------------------------------------------------------------------      
    public function getTotalThoughts($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM thoughts $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
  
}

// $History: ThoughtsManager.inc.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/20/09    Time: 11:11a
//Created in $/LeapCC/Model
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/18/09    Time: 6:31p
//Created in $/Leap/Source/Model
//file added
//

?>
