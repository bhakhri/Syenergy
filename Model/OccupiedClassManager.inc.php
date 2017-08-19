<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Time TABLE" TABLE
//
//
// Author :Jaineesh 
// Created on : (02.03.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class OccupiedClassManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "TIME TABLE" CLASS
//
// Author :Jaineesh 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "TIME TABLEr" CLASS
//
// Author :Jaieesh 
// Created on : (02.03.2010)
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
// THIS FUNCTION IS USED FOR GETTING BUS LIST
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getPeriods($periodSlotId) {
     
        $query = "	SELECT
							periodNumber, periodId
                    FROM	period
					WHERE	periodSlotId = ".$periodSlotId."
                 $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUS LIST
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getTimeTableFreeGroups($getWeekDay,$getPeriod,$timeTableGroupId) {
     
      $query = "	SELECT
							COUNT(*) AS totalRecords
                    FROM	time_table
					WHERE	daysOfWeek = ".$getWeekDay."
					AND		toDate IS NULL
					AND		periodId = ".$getPeriod."
					AND		groupId = ".$timeTableGroupId."
                 $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUS LIST
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getTimeTableDailyFreeGroups($getDate,$getPeriod,$timeTableGroupId) {
     
      $query = "	SELECT
							COUNT(*) AS totalRecords
                    FROM	time_table
					WHERE	fromDate = '".$getDate."'
					AND		toDate IS NULL
					AND		periodId = ".$getPeriod."
					AND		groupId = ".$timeTableGroupId."
                 $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUS LIST
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getTimeTableFreeRooms($getWeekDay,$getPeriod,$timeTableRoomId) {
        global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
     
      $query = "	SELECT
							COUNT(*) AS totalRecords
                    FROM	time_table
					WHERE	daysOfWeek = ".$getWeekDay."
					AND		periodId = ".$getPeriod."
					AND		roomId = ".$timeTableRoomId."
					AND		toDate IS NULL
					AND		sessionId = $sessionId
                 $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FREE ROOMS
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getTimeTableDailyFreeRooms($getDate,$getPeriod,$timeTableRoomId) {
        global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
     
    $query = "	SELECT
							COUNT(*) AS totalRecords
                    FROM	time_table
					WHERE	fromDate = '".$getDate."'
					AND		periodId = ".$getPeriod."
					AND		roomId = ".$timeTableRoomId."
					AND		toDate IS NULL
					AND		sessionId = $sessionId
                 $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUS LIST
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getPeriodName($getPeriod) {
     
      $query = "	SELECT
							periodNumber
                    FROM	period
					WHERE	periodId = ".$getPeriod."
                 $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF GROUPS TIME TABLE
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (01.04.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTimeTableGroups($timeTableLabelId) {
        global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

        
    $query = "	SELECT 
							distinct(groupId)
					FROM 
							time_table
					WHERE	toDate IS NULL
					AND		timeTableLabelId = ".$timeTableLabelId."
					AND		instituteId = ".$instituteId."
					AND		sessionId = ".$sessionId."
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF ROOMS OF TIME TABLE
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (01.04.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTimeTableRooms($timeTableLabelId) {
        global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

        
    $query = "	SELECT 
							r.roomId, concat(c.abbreviation, '-',b.abbreviation,'-',r.roomAbbreviation) as roomName
					FROM 
							room r,
							room_institute ri,
							block b, building c
					WHERE	r.roomId = ri.roomId
					AND		ri.instituteId = ".$instituteId."
					AND		r.blockId = b.blockId AND b.buildingId = c.buildingId
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FREE classes LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh 
// Created on : (02.03.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getFreeClasses($timeTableGroupId) {
        global $sessionHandler;
		
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

      $query = "	SELECT 
							gr.groupName, 
							gr.parentGroupId,
							cl.classId,
							cl.className,
							(select count(sg.studentId) from student_groups sg where gr.groupId = sg.groupId and sg.classId = cl.classId) as groupStudent
					FROM 
							`group` gr,
							class cl
					WHERE	gr.groupId = ".$timeTableGroupId."
					AND		gr.classId = cl.classId
					AND		cl.instituteId = ".$instituteId."
					AND		cl.sessionId = ".$sessionId."
							ORDER BY className
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FREE classes LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (05.03.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getFreeRooms($timeTableRoomId) {
        global $sessionHandler;
		
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

    $query = "	SELECT 
							r.roomId,
							concat_ws('-',c.abbreviation, b.abbreviation,r.roomAbbreviation) AS roomName,
							c.buildingName, b.blockName,
							if(r.capacity IS NULL,'".NOT_APPLICABLE_STRING."',r.capacity) AS capacity,
							if(r.examCapacity IS NULL,'".NOT_APPLICABLE_STRING."',r.examCapacity) AS examCapacity
					FROM 
							room r,
							block b, 
							building c
					WHERE	r.roomId = ".$timeTableRoomId."
					AND		r.blockId = b.blockId
					AND		b.buildingId = c.buildingId
							ORDER BY roomName
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Occupied Classes LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (03.03.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getOccupiedClasses($groupList) {
        global $sessionHandler;
		
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

       $query = "	SELECT 
							distinct(cl.classId),
							cl.className
					FROM 
							`group` gr,
							class cl
					WHERE	gr.groupId IN ($groupList)
					AND		gr.classId = cl.classId
					AND		cl.instituteId = ".$instituteId."
					AND		cl.sessionId = ".$sessionId."
							$conditions
							ORDER BY classId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TIME TABLE TYPE
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getTimeTableType($timeTableLabelId) {
     
      $query = "	SELECT
							timeTableType
                    FROM	time_table_labels
					WHERE	timeTableLabelId = ".$timeTableLabelId."
                 $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

}
// $History: OccupiedClassManager.inc.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/16/10    Time: 4:34p
//Updated in $/LeapCC/Model
//changes in query getTimeTableFreeRooms()
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/07/10    Time: 1:27p
//Updated in $/LeapCC/Model
//show building & block 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/06/10    Time: 12:21p
//Created in $/LeapCC/Model
//new file to fetch database queries 
//
?>
