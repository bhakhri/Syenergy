<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "lecture type" TABLE
//
//
// Author :Rajeev Aggarwal 
// Created on : (13.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class LectureTypeManager{
	private static $instance = null;
	
	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "LectureTypeManager" CLASS
	//
	// Author :Rajeev Aggarwal 
	// Created on : (12.06.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------     
	private function __construct() {
	}
	//-------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "LectureTypeManager" CLASS
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
	// THIS FUNCTION IS USED FOR ADDING A LECTURE TYPE
	//
	// Author :Rajeev Aggarwal 
	// Created on : (12.06.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------    
	public function addLectureType() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('lecture_type', array('lectureName'), array($REQUEST_DATA['lectureType']));
	}
	//-------------------------------------------------------
	// THIS FUNCTION IS USED FOR EDITING A LECTURE TYPE
	//
	//$id:cityId
	// Author :Rajeev Aggarwal 
	// Created on : (12.06.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------     
    public function editLectureType($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('lecture_type', array('lectureName'), array($REQUEST_DATA['lectureType']), "lectureTypeId=$id" );
    }    
	//-------------------------------------------------------
	// THIS FUNCTION IS USED FOR GETTING LECTURE TYPE LIST
	//
	//$conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (12.06.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------         
    public function getLectureType($conditions='') {
     
        $query = "SELECT lectureTypeId,lectureName 
        FROM lecture_type 
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    //-------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR DELETING A LECTURE TYPE
	//
	//$cityId :cityid of the City
	// Author :Rajeev Aggarwal 
	// Created on : (12.06.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------------------------------------------------------  
   public function deleteLectureType($lectureTypeId) {
     
        $query = "DELETE 
        FROM lecture_type 
        WHERE lectureTypeId=$lectureTypeId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
	//-------------------------------------------------------
	// THIS FUNCTION IS USED FOR GETTING LECTURE TYPE LIST
	//
	//$conditions :db clauses
	//$limit:specifies limit
	//orderBy:sort on which column
	// Author :Rajeev Aggarwal 
	// Created on : (12.06.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------       
    public function getLectureTypeList($conditions='', $limit = '', $orderBy=' lectureTypeId') {
     
        $query = "SELECT * from lecture_type";
		if($conditions)
			$query .= " Where $conditions";
        $query .= " ORDER BY $orderBy $limit";
		 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

	//---------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF LECTURE TYPE
	//
	//$conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (12.06.2008)
	// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------     
    public function getTotalLectureType($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM lecture_type";
		if($conditions)
			$query .= " Where $conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
}

// $History: LectureManager.inc.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/17/08    Time: 11:33a
//Updated in $/Leap/Source/Model
//updated issue no 0000062,0000061,0000070
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:34p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/03/08    Time: 7:11p
//Updated in $/Leap/Source/Model
//updated new table structure
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 6/25/08    Time: 4:35p
//Updated in $/Leap/Source/Model
//updated the defects and comments
?>