<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "leave" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class LeaveManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BuildingManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BuildingManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// THIS FUNCTION IS USED FOR ADDING A Building
//
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
public function addLeave() {
    global $REQUEST_DATA;
    global $sessionHandler;  

    $instituteId=$sessionHandler->getSessionVariable('InstituteId');  
    
    return SystemDatabaseManager::getInstance()->runAutoInsert('leave_type',array('leaveTypeName','carryForward','reimbursed','isActive','instituteId'),
	  array(strtoupper(trim($REQUEST_DATA['leaveName'])),$REQUEST_DATA['carryForward'],$REQUEST_DATA['reimbursed'],$REQUEST_DATA['isActive'],$instituteId));
}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A leave
//
//$id:busRouteId
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------   
public function editleave($id) {
    global $REQUEST_DATA;
    global $sessionHandler;  
    
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 
    return SystemDatabaseManager::getInstance()->runAutoUpdate('leave_type', 
    array('leaveTypeName','carryForward','reimbursed','isActive','instituteId'), 
    array(strtoupper(trim($REQUEST_DATA['leaveTypeName'])),$REQUEST_DATA['carryForward'],$REQUEST_DATA['reimbursed'],$REQUEST_DATA['isActive'],$instituteId),
          "leaveTypeId=$id" 
         );
}   
    
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Building LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
 public function getLeave($conditions='') {
        
        global $sessionHandler;
        
        if($conditions=='') {
           $conditions = " WHERE instituteId=".$sessionHandler->getSessionVariable('InstituteId');
        }
        else {
            $conditions .= " AND instituteId=".$sessionHandler->getSessionVariable('InstituteId');
        }
        
        $query = "SELECT 
                        lt.leaveTypeId, lt.leaveTypeName, lt.isActive, lt.carryForward, lt.reimbursed 
                  FROM 
                        leave_type lt
                  $conditions";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
       
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Building
//
//$leaveId :leaveId of the Building
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
public function deleteLeave($leaveTypeId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        //$sessionId = $sessionHandler->getSessionVariable('SessionId');     
     $query = "DELETE  FROM leave_type 
                  WHERE 
                           instituteId = $instituteId AND
                           leaveTypeId=$leaveTypeId";
                           
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
       
    }
    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Building LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
 public function getLeaveList($conditions='', $limit = '', $orderBy=' leaveTypeName') {
       
       global $sessionHandler;
       
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');
       
       $query = "SELECT 
                        lt.leaveTypeId, lt.leaveTypeName, lt.isActive, lt.carryForward, lt.reimbursed  
                  FROM 
                        leave_type lt
				  WHERE 
                        lt.instituteId=".$instituteId."
                  $conditions 
                  ORDER BY $orderBy $limit";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

    
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Buildings
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      

 public function getTotalLeave($conditions='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');   
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords
                  FROM
                       (SELECT 
                              lt.leaveTypeId, lt.leaveTypeName, lt.isActive, lt.carryForward, lt.reimbursed 
                        FROM 
                              leave_type lt
                        WHERE 
                              lt.instituteId=".$instituteId."
                        $conditions) AS tt ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }        


 public function checkLeaveTypeUsage($leaveTypeId) {
        $query = "SELECT
                        COUNT(*) AS cnt
                  FROM 
                        leave_set_mapping
                  WHERE
                        leaveTypeId=$leaveTypeId ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
  
}

// $History: BuildingManager.inc.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 13/08/09   Time: 12:00
//Updated in $/LeapCC/Model
//Added the check in during leave deletion---If this leave is used
//in "room" table then it should not be deleted
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/08/09    Time: 16:01
//Updated in $/LeapCC/Model
//Done bug fixing.
//bug ids--
//0000861 to 0000877
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:20p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/10/08    Time: 6:54p
//Updated in $/Leap/Source/Model
//Created Building Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 5:28p
//Created in $/Leap/Source/Model
//Initial Checkin
?>
