<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "leave_set" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class LeaveSetManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "LeaveSetManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "LeaveSetManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
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
// THIS FUNCTION IS USED FOR ADDING A  LeaveSet
//
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addLeaveSet() {
        global $REQUEST_DATA;
        global $sessionHandler;  

        return SystemDatabaseManager::getInstance()->runAutoInsert('leave_set', 
        array('leaveSetName','isActive','instituteId'), 
        array(strtoupper($REQUEST_DATA['leaveSetName']),
               //$REQUEST_DATA['fromDate'],
               //$REQUEST_DATA['toDate'],
               $REQUEST_DATA['isActive'],
			   //$REQUEST_DATA['timeTableType'],
               //$sessionHandler->getSessionVariable('SessionId'),
               $sessionHandler->getSessionVariable('InstituteId')
              ) 
        );
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A TimeTable Label
//
//$id:busRouteId
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editLeaveSet($id) {
        global $REQUEST_DATA;
        global $sessionHandler;  
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('leave_set', 
        array('leaveSetName','isActive','instituteId'), 
        array(strtoupper($REQUEST_DATA['leaveSetName']),$REQUEST_DATA['isActive'],
               //$sessionHandler->getSessionVariable('SessionId'),
               $sessionHandler->getSessionVariable('InstituteId')
              ),
              "leaveSetId=$id" 
            );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TimeTable Label LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getLeaveSet($conditions='') {
        
        global $sessionHandler;
        
        if($conditions=='') {
           $conditions = " WHERE instituteId=".$sessionHandler->getSessionVariable('InstituteId');
        }
        else {
            $conditions .= " AND instituteId=".$sessionHandler->getSessionVariable('InstituteId');
        }
        
        $query = "SELECT leaveSetId,leaveSetName,isActive
        FROM leave_set
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A TimeTable Label
//
//$busRouteId :busRouteId of the BusStop
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function checkLeaveSet($leaveSetId) {
        
					   $query="SELECT count(leaveSetId) as c FROM leave_set_employee where leaveSetId=$leaveSetId ";
					   return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
					   
         }  
		      
		
	public function deleteLeaveSet($leaveSetId)	{
     
       $query = "DELETE  FROM leave_set 
                  WHERE 
                            leaveSetId=$leaveSetId";
						  
                           
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TimeTable Label LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getLeaveSetList($conditions='', $limit = '', $orderBy=' leaveSetName') {
        global $sessionHandler;
     
       $query = "SELECT 
                        leaveSetId,leaveSetName,
                        isActive
                  FROM 
                        leave_set 
						WHERE 
                        instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                  $conditions 
                  ORDER BY $orderBy $limit";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF TimeTable Labels 
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalLeaveSet($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        leave_set 
                  WHERE 
                        instituteId=".$sessionHandler->getSessionVariable('InstituteId')."  
                  $conditions ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }          
    
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Making All TimeTable Labels Inactive
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function makeAllLeaveSetInActive($conditions='') {
        global $sessionHandler;
        
        $query = "UPDATE leave_set
                  SET isActive=0
                  WHERE instituteId=".$sessionHandler->getSessionVariable('InstituteId')."  
                        AND isActive=1
                  $conditions ";
                  
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }
    
 public function checkLeaveSetUsage($leaveSetId) {
        $query = "SELECT
                        COUNT(*) AS cnt
                  FROM 
                        leave_set_mapping
                  WHERE
                        leaveSetId=$leaveSetId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
}

// $History: TimeTableLabelManager.inc.php $
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:31p
//Updated in $/LeapCC/Model
//done changes for new Session End Activities
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 21/07/09   Time: 16:25
//Updated in $/LeapCC/Model
//Corrected checkInTimeTable() function
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 21/07/09   Time: 15:54
//Updated in $/LeapCC/Model
//Added the check : Those time table label cannot be deleted which are
//used in time table
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 20/07/09   Time: 19:08
//Updated in $/LeapCC/Model
//Done bug fixing.
//bug ids ---0000629 to 0000631
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/29/09    Time: 2:19p
//Updated in $/LeapCC/Model
//getTimeTableLabel function condition updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/10/09    Time: 2:35p
//Updated in $/LeapCC/Model
//start and end date for fields added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 10/01/08   Time: 11:01a
//Updated in $/Leap/Source/Model
//Added database checkings
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/30/08    Time: 3:40p
//Updated in $/Leap/Source/Model
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/30/08    Time: 3:34p
//Created in $/Leap/Source/Model
//Created TimeTable Labels
?>
