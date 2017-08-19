<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "nationality" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class NationalityManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "NationalityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------    
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "NationalityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
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
// THIS FUNCTION IS USED FOR ADDING A NATIONALITY
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
	public function addNationality() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('nationality', array('nationName'), array($REQUEST_DATA['nationName']));
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A NATIONALITY
//
//$id:nationId
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------      
    public function editNationality($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('nationality', array('nationName'), array($REQUEST_DATA['nationName']), "nationId=$id" );
    } 
    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING NATIONALITY LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function getNationality($conditions='') {
        $query = "SELECT nationId,nationName
        FROM nationality
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
   /*
    Not Required as "Nationality" table is independent
    public function checkInInstitute($cityId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM institute 
        WHERE cityId=$cityId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   */ 
   
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A NATIONILITY
//
//$cityId :nationid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------         
    public function deleteNationality($nationId) {
     
        $query = "DELETE 
        FROM nationality
        WHERE nationId=$nationId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING NATIONILITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
   public function getNationalityList($conditions='', $limit = '', $orderBy=' nt.nationName') {
     
        $query = "SELECT nt.nationId, nt.nationName
        FROM nationality nt
        $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    


//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF NATIONALITIES
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------        
    public function getTotalNationality($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM nationality nt
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
 
}
?>
<?php
  // $History: NationalityManager.inc.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:36p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:52p
//Updated in $/Leap/Source/Model
//Complete Comment Insertion
?>