<?php 
//-------------------------------------------------------
//  This File contains Bussiness Logic of the Periodicity Module
//
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

//Main responsible for operation in subjecttype table in database

class PeriodicityManager {
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
	public function addPeriodicity() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('periodicity', array('periodicityCode','periodicityName','periodicityFrequency'), array(strtoupper($REQUEST_DATA['periodicityCode']),$REQUEST_DATA['periodicityName'],$REQUEST_DATA['periodicityFrequency']));
	}
    public function editPeriodicity($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('periodicity', array('periodicityCode','periodicityName','periodicityFrequency'), array(strtoupper($REQUEST_DATA['periodicityCode']),$REQUEST_DATA['periodicityName'],$REQUEST_DATA['periodicityFrequency']), "periodicityId=$id" );  
    }    
    public function getPeriodicity($conditions='') {
     
        $query = "SELECT periodicityFrequency,periodicityId,periodicityCode,periodicityName 
        FROM periodicity 
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    public function getPeriodicityList($conditions='', $limit = '', $orderBy=' periodicityName') {
     
        $query = "SELECT periodicityFrequency,periodicityId, periodicityCode, periodicityName FROM periodicity  
        $conditions                   
        ORDER BY $orderBy $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    public function getTotalPeriodicity($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM periodicity 
        $conditions ";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
     public function deletePeriodicity($periodicityId) {
     
        $query = "DELETE 
        FROM periodicity 
        WHERE periodicityId=$periodicityId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }   

    public function getStudyPeriod($conditions='') {

        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        study_period 
                  $conditions ";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
}
?>
<?php 
//$History: PeriodicityManager.inc.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/21/09    Time: 4:04p
//Updated in $/LeapCC/Model
//getStudyPeriod function added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/25/09    Time: 12:32p
//Updated in $/LeapCC/Model
//query update getTotalPeriodicity
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 7  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:37p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/14/08    Time: 11:52a
//Updated in $/Leap/Source/Model
//added a missing paramter in edit funciton periodicityFrequency
//
//*****************  Version 5  *****************
//User: Arvind       Date: 6/17/08    Time: 3:17p
//Updated in $/Leap/Source/Model
//modification
//
//*****************  Version 4  *****************
//User: Arvind       Date: 6/14/08    Time: 7:19p
//Updated in $/Leap/Source/Model
//modification
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:04p
//Updated in $/Leap/Source/Model
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:20p
//Created in $/Leap/Source/Model
//New Files Added in Model Folder

?>