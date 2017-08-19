<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "time_table_labels" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class TimeTableLabelManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "TimeTableLabelManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "TimeTableLabelManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
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
// THIS FUNCTION IS USED FOR ADDING A  TimeTableLabel
//
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addTimeTableLabel() {
        global $REQUEST_DATA;
        global $sessionHandler;  

        return SystemDatabaseManager::getInstance()->runAutoInsert('time_table_labels', 
        array('labelName','startDate','endDate','isActive','timeTableType','sessionId','instituteId'), 
        array(strtoupper($REQUEST_DATA['labelName']),
               $REQUEST_DATA['fromDate'],
               $REQUEST_DATA['toDate'],
               $REQUEST_DATA['isActive'],
			   $REQUEST_DATA['timeTableType'],
               $sessionHandler->getSessionVariable('SessionId'),
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editTimeTableLabel($id) {
        global $REQUEST_DATA;
        global $sessionHandler;  
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('time_table_labels', 
        array('labelName','startDate','endDate','isActive','timeTableType','sessionId','instituteId'), 
        array(strtoupper($REQUEST_DATA['labelName']),$REQUEST_DATA['fromDate1'],$REQUEST_DATA['toDate1'],$REQUEST_DATA['isActive'],$REQUEST_DATA['timeTableType'],
               $sessionHandler->getSessionVariable('SessionId'),
               $sessionHandler->getSessionVariable('InstituteId')
              ),
              "timeTableLabelId=$id" 
            );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TimeTable Label LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getTimeTableLabel($conditions='') {
        
        global $sessionHandler;
        
        if($conditions=='') {
           $conditions = " WHERE sessionId=".$sessionHandler->getSessionVariable('SessionId')." AND
                                 instituteId=".$sessionHandler->getSessionVariable('InstituteId');
        }
        else {
            $conditions .= " AND sessionId=".$sessionHandler->getSessionVariable('SessionId')." AND
                                 instituteId=".$sessionHandler->getSessionVariable('InstituteId');
        }
        
        $query = "SELECT timeTableLabelId,labelName,startDate,endDate,isActive,timeTableType
        FROM time_table_labels
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//--------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR checking whether a timeTableLableId is used in time_table or not
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (21.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------
    public function checkInTimeTable($conditions='') {
        
        
        $query = " SELECT 
                        ttl.timeTableLabelId
                   FROM 
                        time_table_labels ttl, ".TIME_TABLE_TABLE." t
                   WHERE
                        ttl.timeTableLabelId=t.timeTableLabelId 
                   $conditions
                   GROUP BY t.timeTableLabelId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	 

	 public function countAssociatedClasses($labelId) {
		 $query = "SELECT COUNT(classId) as cnt from time_table_classes where timeTableLabelId = '$labelId'";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }
	
	public function countAssociatedTimeTable($labelId) {
		 $query = "SELECT COUNT(timeTableId) as cnt from  ".TIME_TABLE_TABLE."  where timeTableLabelId = '$labelId'";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }
    


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A TimeTable Label
//
//$busRouteId :busRouteId of the BusStop
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteTimeTableLabel($timeTableLabelId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');     
     
        $query = "DELETE  FROM time_table_labels 
                  WHERE 
                           instituteId = $instituteId AND
                           sessionId = $sessionId AND
                           timeTableLabelId=$timeTableLabelId";
                           
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getTimeTableLabelList($conditions='', $limit = '', $orderBy=' ttl.labelName') {
        global $sessionHandler;
     
       $query = "SELECT 
                        ttl.timeTableLabelId,ttl.startDate,ttl.endDate,ttl.labelName,
                        ttl.isActive,ttl.timeTableType, IF(ttl.timeTableType=1,'WEEKLY','DAILY') AS typeOf
                  FROM 
                        time_table_labels ttl 
                  WHERE 
                        ttl.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND ttl.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                  $conditions 
                  ORDER BY $orderBy $limit";
		//	echo $query;die;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF TimeTable Labels 
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalTimeTableLabel($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        time_table_labels ttl 
                  WHERE 
                        ttl.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND ttl.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."  
                  $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

	}          
    
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Making All TimeTable Labels Inactive
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function makeAllTimeTableLabelInActive($conditions='') {
        global $sessionHandler;
        
        $query = "UPDATE time_table_labels ttl 
                  SET ttl.isActive=0
                  WHERE ttl.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND ttl.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."  
                        AND ttl.isActive=1
                  $conditions ";
                  
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }     

}

// $History: TimeTableLabelManager.inc.php $
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 4/21/10    Time: 4:34p
//Updated in $/LeapCC/Model
//done changes as per FCNS No. 1625
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
