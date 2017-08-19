<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ClassManager {
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
	public function addClass() {
		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

		return SystemDatabaseManager::getInstance()->runAutoInsert('class', array('universityId','instituteId','sessionId','batchId','degreeId','branchId','studyPeriodId','degreeDuration','classDescription','className','isActive'), array($REQUEST_DATA['university'],$instituteId,$sessionId,$REQUEST_DATA['batch'],$REQUEST_DATA['degree'],$REQUEST_DATA['branch'],$REQUEST_DATA['studyperiod'],$REQUEST_DATA['degreeDuration'],$REQUEST_DATA['classDescription'],$REQUEST_DATA['className'],$REQUEST_DATA['radioactive']) );
	}
    public function editClass($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('class', array('universityId','instituteId','batchId','degreeId','sessionId','branchId','studyPeriodId','degreeDuration','classDescription','className','isActive'), array(strtoupper($REQUEST_DATA['university']),1,$REQUEST_DATA['batch'],$REQUEST_DATA['degree'],1,$REQUEST_DATA['branch'],$REQUEST_DATA['studyperiod'],$REQUEST_DATA['degreeDuration'],$REQUEST_DATA['classDescription'],$REQUEST_DATA['className'],$REQUEST_DATA['radioactive']), "classId=$id" );
    }    
    public function getClass($conditions='') {
     
        $query = "SELECT * 
        FROM class 
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
     public function checkInStudent($classId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM student 
        WHERE classId=$classId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    public function getClassList($conditions='', $limit = '', $orderBy=' className') {
     
        $query = "SELECT * FROM class";
        if($conditions)
			  $query .= " WHERE $conditions "; 
		 $query .= " ORDER BY $orderBy $limit";
		//echo $query;
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    public function getTotalClass($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM class";
		if($conditions)
			$query .= " WHERE $conditions "; 
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	public function deleteClass($classId) {
     
        $query = "DELETE 
        FROM class 
        WHERE classId=$classId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
}
?>