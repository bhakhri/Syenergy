<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class StatesManager {
	private static $instance = null;
	
	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
	public function addState() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('states', array('stateCode','stateName','countryId'), array(strtoupper($REQUEST_DATA['stateCode']),$REQUEST_DATA['stateName'],$REQUEST_DATA['countries']) );
	}
    public function editState($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('states', array('stateCode','stateName','countryId'), array(strtoupper($REQUEST_DATA['stateCode']),$REQUEST_DATA['stateName'],$REQUEST_DATA['countries']), "stateId=$id" );
    }    
    public function getState($conditions='') {
     
        $query = "SELECT stateId,stateCode,stateName,countryId 
        FROM states 
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    public function checkInCity($stateId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM city 
        WHERE stateId=$stateId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    public function deleteState($stateId) {
     
        $query = "DELETE 
        FROM states 
        WHERE stateId=$stateId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
    public function getStateList($conditions='', $limit = '', $orderBy=' st.stateName') {
     
        $query = "SELECT st.stateId, st.stateCode, st.stateName, st.countryId, cnt.countryName 
        FROM states st, countries cnt 
        WHERE st.countryId=cnt.countryId $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    public function getTotalStates($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM states st, countries cnt 
        WHERE st.countryId=cnt.countryId $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
}
?>