<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "event" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (4.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class CalendarManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CalendarManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (4.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CalendarManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (4.7.2008)
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
// THIS FUNCTION IS USED FOR ADDING An event
//
// Author :Dipanjan Bhattacharjee 
// Created on : (4.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addEvent() {
		global $REQUEST_DATA;
        global $sessionHandler;

		return SystemDatabaseManager::getInstance()->runAutoInsert('event', array('instituteId','sessionId','eventTitle','shortDescription','longDescription','startDate','endDate','roleIds'), 
        array($sessionHandler->getSessionVariable('InstituteId'),$sessionHandler->getSessionVariable('SessionId'),$REQUEST_DATA['eventTitle'],$REQUEST_DATA['shortDescription'],$REQUEST_DATA['longDescription'],$REQUEST_DATA['startDate'],$REQUEST_DATA['endDate'],$REQUEST_DATA['roleIds']) );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING An event
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (4.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editEvent($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('event', array('instituteId','sessionId','eventTitle','shortDescription','longDescription','startDate','endDate','roleIds'), 
        array($sessionHandler->getSessionVariable('InstituteId'),$sessionHandler->getSessionVariable('SessionId'),$REQUEST_DATA['eventTitle'],$REQUEST_DATA['shortDescription'],$REQUEST_DATA['longDescription'],$REQUEST_DATA['startDate'],$REQUEST_DATA['endDate'],$REQUEST_DATA['roleIds']), "eventId=$id" );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING event LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (4.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getEvent($conditions='') {
     
        global $sessionHandler; 
        
        $query = "SELECT eventId,instituteId,eventTitle,shortDescription,longDescription,startDate,endDate ,roleIds
        FROM event
        WHERE sessionId=".$sessionHandler->getSessionVariable('SessionId')."
        AND   instituteId=".$sessionHandler->getSessionVariable('InstituteId')." 
        $conditions ORDER BY startDate";
        
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING An event
//
//$cityId :cityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (4.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteEvent($eventId) {
     
        $query = "DELETE 
        FROM event 
        WHERE eventId=$eventId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING event LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (4.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getEventList($conditions='', $limit = '', $orderBy=' ev.eventTitle') {
     
        global $sessionHandler;
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        $roleConditions='';
        if($roleId!=1){
            $roleConditions='AND ev.roleIds LIKE "%~'.$roleId.'~%"';
        }
        
        $query = "SELECT ev.eventId,ev.eventTitle,ev.shortDescription,ev.longDescription,
        ev.startDate,ev.endDate ,ev.roleIds
        FROM event ev 
        WHERE ev.sessionId=".$sessionHandler->getSessionVariable('SessionId')." 
        AND ev.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
        $roleConditions
        $conditions 
        ORDER BY $orderBy $limit";
        
       // echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF event
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (4.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalEvent($conditions='') {
    
        global $sessionHandler;
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        $roleConditions='';
        if($roleId!=1){
            $roleConditions='AND ev.roleIds LIKE "%~'.$roleId.'~%"';
        }
        
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM event ev 
        WHERE ev.sessionId=".$sessionHandler->getSessionVariable('SessionId')." 
        AND ev.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." 
        $roleConditions
        $conditions";
		//print_r($query);
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}
// $History: CalendarManager.inc.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/17/08    Time: 8:40p
//Updated in $/Leap/Source/Model
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/03/08    Time: 10:53a
//Updated in $/Leap/Source/Model
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:22p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/05/08    Time: 5:15p
//Updated in $/Leap/Source/Model
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/05/08    Time: 12:29p
//Updated in $/Leap/Source/Model
//Added SessionId in the code 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/04/08    Time: 7:18p
//Updated in $/Leap/Source/Model
//Added datePicker() and dateDropdown() function for calender
//functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/03/08    Time: 12:35p
//Created in $/Leap/Source/Model
//Initial Checkin
?>
