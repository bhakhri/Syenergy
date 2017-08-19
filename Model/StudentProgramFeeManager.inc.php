<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class StudentProgramFeeManager {
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
	public function addProgramFee() {
		global $REQUEST_DATA;
		return SystemDatabaseManager::getInstance()->runAutoInsert('student_program_fee', 
         array('programFeeName'), 
         array(trim($REQUEST_DATA['programFeeName']))
        );
	}
    public function editProgramFee($id) {
        global $REQUEST_DATA;
        return SystemDatabaseManager::getInstance()->runAutoUpdate('student_program_fee',
        array('programFeeName'), 
        array(trim($REQUEST_DATA['programFeeName'])), 
        "programFeeId=$id" );
    }    
    public function getProgramFee($conditions='') {
     
        $query = "SELECT 
                         * 
                  FROM 
                         student_program_fee 
                  $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    public function checkInStudent($programFeeId) {
     
        $query = "SELECT 
                        COUNT(*) AS found 
                  FROM  
                        student
                  WHERE 
                         programFeeId=$programFeeId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    public function deleteProgramFee($programFeeId) {
     
        $query = "DELETE 
                  FROM 
                        student_program_fee 
                  WHERE 
                        programFeeId=$programFeeId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
    public function getProgramFeeList($conditions='', $limit = '', $orderBy=' programFeeName') {
     
        $query = "SELECT 
                         *
                  FROM 
                         student_program_fee 
                  $conditions 
                  ORDER BY $orderBy 
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    public function getTotalProgramFees($conditions='') {
    
        $query = "SELECT 
                         COUNT(*) AS totalRecords 
                  FROM 
                         student_program_fee
                         $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
}
?>