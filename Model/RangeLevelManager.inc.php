<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Range Level " Module
//
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class RangeLevelManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "RangeLevelManager" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

	
	private function __construct() {
	}
	

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "RangeLevelManager" CLASS
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
// THIS FUNCTION IS USED FOR ADDING A Range
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

	
	public function addRange() {
		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		return SystemDatabaseManager::getInstance()->runAutoInsert('range_levels', array('rangeFrom','rangeTo', 'rangeLabel', 'instituteId'), array($REQUEST_DATA['rangeFrom'],$REQUEST_DATA['rangeTo'], $REQUEST_DATA['rangeLabel'], $instituteId));
	}
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A range
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       	
	
	
    public function editRange($id) {
        global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        return SystemDatabaseManager::getInstance()->runAutoUpdate('range_levels', array('rangeFrom','rangeTo', 'rangeLabel', 'instituteId'), array($REQUEST_DATA['rangeFrom'],$REQUEST_DATA['rangeTo'], $REQUEST_DATA['rangeLabel'], $instituteId), "rangeId = $id" );
    }
	
  

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A "range" RECORD
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

    public function deleteRange($rangeId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "DELETE FROM range_levels 
				 WHERE rangeId=$rangeId and instituteId = $instituteId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING range LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
    public function getRange($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
 		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        $query = "SELECT 
						rangeId, 
						rangeFrom, 
						rangeTo,
						rangeLabel
				 FROM	range_levels 
						$conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING range LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
   	
	
    public function getRangeLevelList($conditions='', $limit = '', $orderBy=' rangeId') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        $query = "SELECT 
							rangeId, 
							rangeFrom, 
							rangeTo,  
							rangeLabel  
				 FROM		range_levels 
							$conditions 
				ORDER BY	$orderBy $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "range_levels" TABLE
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
		
	 
    public function getTotalRangeLevel($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
    
        $query = "SELECT 
						COUNT(*) AS totalRecords 
				 FROM	range_levels $conditions ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
}
?>