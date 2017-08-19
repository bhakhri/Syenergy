<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the Country Module
//
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

//Author : Arvind Singh Rawat
//updated on 25-06-2008 
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class CountryManager {
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
	public function addCountry() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('countries', array('countryCode','countryName','nationalityName'), array(strtoupper($REQUEST_DATA['countryCode']),$REQUEST_DATA['countryName'],$REQUEST_DATA['nationalityName']));
	}
    public function editCountry($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('countries', array('countryCode','countryName','nationalityName'), array(strtoupper($REQUEST_DATA['countryCode']),$REQUEST_DATA['countryName'],$REQUEST_DATA['nationalityName']), "countryId=$id" );  
    }    
    public function getCountry($conditions='') {
     
        $query = "SELECT countryId,countryCode,countryName ,nationalityName
        FROM countries 
        $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    //Gets the country table fields
      
    public function getCountryList($conditions='', $limit = '', $orderBy=' countryName') {
     
        $query = "SELECT countryId, countryCode, countryName,nationalityName FROM countries  
        $conditions                   
        ORDER BY $orderBy $limit";
             
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    // checks dependency constraint
    public function checkInState($countryId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM states 
        WHERE countryId=$countryId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
       
    public function getTotalCountry($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM countries  
        $conditions ";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    // deletes the country
     public function deleteCountry($countryId) {
     
        $query = "DELETE 
        FROM countries 
        WHERE countryId=$countryId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }   
}
?>

<?php 

//$History: CountryManager.inc.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/08/09    Time: 6:04p
//Updated in $/LeapCC/Model
//country master validation & required fields added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/05/08    Time: 12:47p
//Updated in $/Leap/Source/Model
//added a new field nationalityName
//
//*****************  Version 7  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:24p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 6  *****************
//User: Arvind       Date: 6/25/08    Time: 11:53a
//Updated in $/Leap/Source/Model
//added a new dependency constraint fucntion 
//added comments
//
//*****************  Version 5  *****************
//User: Arvind       Date: 6/24/08    Time: 4:04p
//Updated in $/Leap/Source/Model
//modified files
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