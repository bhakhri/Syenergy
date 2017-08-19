<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "busstop" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class TransportStuffManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "TransportStuffRepairManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "TransportStuffRepairManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
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
// THIS FUNCTION IS USED FOR ADDING A BUSSTOP
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addTransportStuff() {
		global $REQUEST_DATA;
        global $sessionHandler;
        $userId=$sessionHandler->getSessionVariable('UserId');
        
        $leavingDate=$REQUEST_DATA['leavingDate']!='' ? $REQUEST_DATA['leavingDate'] : NULL;

		return SystemDatabaseManager::getInstance()->runAutoInsert('transport_stuff',
        array('name','stuffCode','dlNo','dlIssuingAuthority','dlExpiryDate','joiningDate','stuffType','addByUserId','inService','leavingDate'), 
        array(
               trim(add_slashes($REQUEST_DATA['stuffName'])),
               trim(add_slashes($REQUEST_DATA['stuffCode'])),
               trim(add_slashes($REQUEST_DATA['dlNo'])),
               trim(add_slashes($REQUEST_DATA['dlAuthority'])),
               $REQUEST_DATA['dlExp'],
               $REQUEST_DATA['join'],
               $REQUEST_DATA['stuffType'],
               $userId,
               $REQUEST_DATA['inService'],
               $leavingDate
             ) 
        );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A BUSSTOP
//
//$id:busStopId
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editTransportStuff($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        $userId=$sessionHandler->getSessionVariable('UserId');
        
        $leavingDate=$REQUEST_DATA['leavingDate']!='' ? $REQUEST_DATA['leavingDate'] : NULL;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('transport_stuff',
        array('name','stuffCode','dlNo','dlIssuingAuthority','dlExpiryDate','joiningDate','stuffType','addByUserId','inService','leavingDate'), 
        array(
               trim(add_slashes($REQUEST_DATA['stuffName'])),
               trim(add_slashes($REQUEST_DATA['stuffCode'])),
               trim(add_slashes($REQUEST_DATA['dlNo'])),
               trim(add_slashes($REQUEST_DATA['dlAuthority'])),
               $REQUEST_DATA['dlExp'],
               $REQUEST_DATA['join'],
               $REQUEST_DATA['stuffType'],
               $userId,
               $REQUEST_DATA['inService'],
               $leavingDate
             ), 
        "stuffId=$id" 
        );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUSSTOP LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getTransportStuff($conditions='') {
     
        $query = "SELECT
                          stuffId,name,stuffCode,dlNo,dlIssuingAuthority,dlExpiryDate,joiningDate,stuffType,
                          inService,
                          IF(leavingDate IS NULL OR leavingDate='0000-00-00',-1,leavingDate) AS leavingDate
                  FROM 
                          transport_stuff
                  $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//**AS BUSSTOP TABLE IS INDEPENDENT NO NEED TO CHECK TO INTEGRITY CONSTRAINTS**//

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A BUSSTOP
//
//$cityId :busStopid of the TransportStuff
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteTransportStuff($id) {
     
        $query = "DELETE 
                  FROM
                      transport_stuff
                  WHERE 
                      stuffId=$id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUSSTOP LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getTransportStuffList($conditions='', $limit = '', $orderBy=' name') {
        global $sessionHandler;
        
        $query = "SELECT 
                         stuffId,name,stuffCode,dlNo,dlIssuingAuthority,dlExpiryDate,joiningDate,stuffType,
                         IF(inService=1,'Yes','No') AS inService
                  FROM 
                         transport_stuff
                         $conditions 
                  ORDER BY $orderBy 
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF BUSSTOPSS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalTransportStuff($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT 
                         COUNT(*) AS totalRecords 
                  FROM 
                         transport_stuff
                         $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}
// $History: TransportStuffManager.inc.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/04/09   Time: 13:16
//Created in $/LeapCC/Model
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/04/09    Time: 19:27
//Updated in $/SnS/Model
//Done enhancement for transport staff master
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 16:46
//Created in $/SnS/Model
//Created module Transport Stuff Master
?>
