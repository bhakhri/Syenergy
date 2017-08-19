<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the Subject Type Module
//
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

//Main responsible for operation in subjecttype table in database

class SubjectTypeManager {
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
	// used to add data in database
	public function addSubjectType() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('subject_type', array('subjectTypeCode','subjectTypeName','universityId'), array(strtoupper($REQUEST_DATA['subjectTypeCode']),$REQUEST_DATA['subjectTypeName'],$REQUEST_DATA['universityId']));
	}
// used to update data
    public function editSubjectType($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('subject_type', array('subjecttypeCode','subjecttypeName','universityId'), array(strtoupper($REQUEST_DATA['subjectTypeCode']),$REQUEST_DATA['subjectTypeName'],$REQUEST_DATA['universityId']), "subjectTypeId=$id" );  
    }   
	//used to get data
    public function getSubjectType($conditions='') {
     
        $query = "SELECT subjectTypeId,subjectTypeCode,subjectTypeName ,universityId
        FROM subject_type 
        $conditions";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
//used to get data with more features
    public function getSubjectTypeList($conditions='', $limit = '', $orderBy=' subjectTypeName') {
     
        $query = "SELECT sub.subjectTypeId, sub.subjectTypeCode, sub.subjectTypeName ,uni.universityId,uni.universityName FROM subject_type sub,university uni 
        WHERE sub.universityId=uni.universityId 
        $conditions                   
        ORDER BY $orderBy $limit";
        
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
	
	//used to count rows in table
    public function getTotalsubjectType($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM subject_type sub, university uni 
		WHERE sub.universityId=uni.universityId 
        $conditions ";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
//used to delete data

     public function deleteSubjectType($subjectTypeId) {
     
        $query = "DELETE FROM subject_type 
                  WHERE subjectTypeId=$subjectTypeId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }   
    
    public function getCheckSubject($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS cnt 
                  FROM 
                        subject 
                  $conditions ";
               
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
}


//$History: SubjectTypeManager.inc.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Model
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/05/09    Time: 1:21p
//Updated in $/LeapCC/Model
//fixed bug nos.0000800,0000802,0000801,0000776,0000775,0000776,0000801
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/12/09    Time: 4:49p
//Updated in $/LeapCC/Model
//totalSubject condtion update
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:46p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/03/08    Time: 7:32p
//Updated in $/Leap/Source/Model
//modified table name 
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/01/08    Time: 1:40p
//Updated in $/Leap/Source/Model
//added new field universityName in the sql query of getSubjectTypeList
//function
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:26p
//Created in $/Leap/Source/Model
//new files added


?>