<?php
//-------------------------------------------------------------------------------
//
//RoomManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
  include_once(DA_PATH ."/SystemDatabaseManager.inc.php");
  
  class RoomManager {
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
    

//-------------------------------------------------------------------------------
// Thit function is used for mapping rooms with institutes
// Author : Dipanjan Bhattacharjee
// Created on : 14.08.2009
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function doRoomInstituteMapping($str) {

        $query ="INSERT INTO room_institute(roomId,instituteId) VALUES $str ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    

//-------------------------------------------------------------------------------
// Thit function is used for deleting mapping rooms with institutes
// Author : Dipanjan Bhattacharjee
// Created on : 14.08.2009
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function deleteRoomInstituteMapping($roomId) {

        $query ="DELETE FROM room_institute WHERE roomId=$roomId ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }    
        
//-------------------------------------------------------------------------------
//
//addRoom() function is used for adding new periods into the period table....
// Author : Jaineesh
// Created on : 2.07.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addRoom() {
        global $REQUEST_DATA;
		$eCap = trim($REQUEST_DATA['examCapacity'])!=''?trim($REQUEST_DATA['examCapacity']):"NULL";
        $query ="
                 INSERT INTO     
                         room 
                 SET    
                        roomName = '".add_slashes(trim($REQUEST_DATA['roomName']))."', 
                        roomAbbreviation = '".add_slashes(trim(strtoupper($REQUEST_DATA['roomAbbreviation'])))."', 
                        roomTypeId = '".$REQUEST_DATA['roomType']."',
                        blockId = '".$REQUEST_DATA['blockName']."', 
                        capacity = '".trim($REQUEST_DATA['capacity'])."', 
                        examCapacity = $eCap" ;
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
//-------------------------------------------------------------------------------
//
//editPeriods() function is used for edit the existing period into the period table....
// Author : Jaineesh
// Created on : 2.07.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editRoom($id) {
        global $REQUEST_DATA;
		$eCap = trim($REQUEST_DATA['examCapacity'])!=''?trim($REQUEST_DATA['examCapacity']):"NULL";
        $query ="
                 UPDATE 
                        room 
                 SET    
                        roomName = '".add_slashes(trim($REQUEST_DATA['roomName']))."', 
                        roomAbbreviation = '".add_slashes(trim(strtoupper($REQUEST_DATA['roomAbbreviation'])))."', 
                        roomTypeId = '".$REQUEST_DATA['roomType']."',
                        blockId = '".$REQUEST_DATA['blockName']."', 
                        capacity = '".trim($REQUEST_DATA['capacity'])."', 
                        examCapacity = $eCap
			WHERE	roomId=$id
		";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
 }
    
//-------------------------------------------------------------------------------
//
//deleteRoom() function is used to delete records from Room....
// $periodId - used to generate the unique id of the table
// Author : Jaineesh
// Created on : 2.07.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function deleteRoom($roomId) {
     
        $query = "DELETE
                  FROM 
                        room 
                  WHERE 
                        roomId=$roomId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


//-------------------------------------------------------------------------------
// This function is used for getting room and institute mapping
// Author : Dipanjan Bhattacharjee
// Created on : 14.08.2009
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function getInstituteRoomMapping($conditions='') {
     
        $query = "SELECT 
                         r.roomId,
                         ri.instituteId
                  FROM    
                         room r,room_institute ri 
                  WHERE   
                         r.roomId = ri.roomId
                         $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
       
//-------------------------------------------------------------------------------
//
//getRoom() function is used for getting the value of room table....
// 
// Author : Jaineesh
// Created on : 2.07.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getRoom($conditions='') {
     
        $query = "	SELECT 
							r.roomId,
							r.roomName,
							r.roomAbbreviation,
							r.roomTypeId,
							rt.roomType,
							b.blockId,
							b.blockName,
							r.capacity,
							bu.buildingId,
							bu.buildingName,
							if(r.examCapacity is NULL,'',r.examCapacity) as examCapacity
					FROM	room r, 
							block b,
							building bu,
							room_institute ri,
							room_type rt
					WHERE	r.blockId = b.blockId
					AND		b.buildingId = bu.buildingId
					AND		r.roomId = ri.roomId
					AND		r.roomTypeId = rt.roomTypeId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
      //-------------------------------------------------------------------------------
//
//getRoomList() function is used to list the records of room table
// $condtions - used to check condition while selecting the records
// $limit - used to check the limit of showing records in list
// Author : Jaineesh
// Created on : 02.07.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getRoomList($conditions='', $limit = '', $orderBy='r.roomName') {
       global $sessionHandler;
       $query = "SELECT 
						DISTINCT r.roomId,
						r.roomName,
						r.roomAbbreviation,
						r.roomTypeId,
						b.blockId,
						b.blockName,
						r.capacity,
						bu.buildingName,
						rt.roomType,
						if(r.examCapacity is NULL,'--',r.examCapacity) as examCapacity
				FROM	room r, 
						block b,
						building bu,
                        room_institute ri,
						room_type rt
				WHERE	r.blockId = b.blockId
				AND		b.buildingId = bu.buildingId
                AND     r.roomId=ri.roomId
				AND		r.roomTypeId = rt.roomTypeId
                AND     ri.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
						$conditions 
				        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
  //-------------------------------------------------------------------------------
//
//getTotalRoom() function returns the total no. of records
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 02.07.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getTotalRoom($conditions='') {
        global $sessionHandler;
        $query = "	SELECT	COUNT(DISTINCT r.roomId) AS totalRecords 
					FROM	room r, 
							block b, 
							building bu,
                            room_institute ri,
							room_type rt
					WHERE	r.blockId = b.blockId
					AND		b.buildingId = bu.buildingId
                    AND     r.roomId=ri.roomId
					AND		rt.roomTypeId = r.roomTypeId
                    AND     ri.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
//
//getBlock() function returns the block from block table
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 12.08.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getBlock($buildingId) {
    
       $query = "	SELECT	blockId, 
							blockName
					FROM	block bl,
							building bu
					WHERE	bl.buildingId = bu.buildingId
					AND		bu.buildingId = $buildingId
							ORDER BY blockName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

//-------------------------------------------------------------------------------
// This function is used for checking "room" uses in "time_table" table
// Author : Dipanjan Bhattacharjee
// Created on : 17.08.2009
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function checkInTimeTable($conditions='') {
     
        $query = "  SELECT 
                            DISTINCT t.instituteId AS `found`
                    FROM    room r, 
                             ".TIME_TABLE_TABLE."  t,
                            time_table_labels ttl
                    WHERE   r.roomId = t.roomId
                    AND     ttl.timeTableLabelId=t.timeTableLabelId
                    AND     t.toDate IS NULL
                    AND     ttl.isActive=1
                            $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------------------------------
//
//getBlockDetail() function returns the block from block table
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 08.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getBlockDetail($blockName,$buildingId) {
    
        $query = "	SELECT	bl.blockId,
							bl.blockName
					FROM	block bl
					WHERE	bl.blockName = '$blockName'
					AND		bl.buildingId = $buildingId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getCountBuilding() function returns the building from building table
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 20.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getCountBuilding($buildingName) {
    
        $query = "	SELECT	count(*) AS totalRecords
					FROM	building bu
					WHERE	bu.buildingName = '$buildingName'";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getBuildingDetail() function returns the building from building table
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 20.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getBuildingDetail($buildingName) {
    
        $query = "	SELECT	bu.buildingId,
							bu.buildingName,
							bu.abbreviation
					FROM	building bu
					WHERE	bu.buildingName = '$buildingName'";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getBlockDetail() function returns the block from block table
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 08.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getInstitute($instituteCode) {
    
        $query = "	SELECT	ins.instituteId
					FROM	institute ins
					WHERE	ins.instituteCode ='$instituteCode'";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//addRoomInTransaction() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 08.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function addRoomInTransaction($roomName,$roomAbbreviation,$roomType,$getBlockId,$capacity,$examCapacity) {
		$query = "INSERT INTO room (roomName,roomAbbreviation,roomType,blockId,capacity,examCapacity) VALUES ('$roomName','$roomAbbreviation','$roomType',$getBlockId,$capacity,$examCapacity)";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

//-------------------------------------------------------------------------------
//
//addRoomInTransaction() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 08.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function addNewRoomInTransaction($roomName,$roomAbbreviation,$roomType,$lastBlockId,$capacity,$examCapacity) {
		$query = "INSERT INTO room (roomName,roomAbbreviation,roomType,blockId,capacity,examCapacity) VALUES ('$roomName','$roomAbbreviation','$roomType',$lastBlockId,$capacity,$examCapacity)";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

//-------------------------------------------------------------------------------
//
//addBuildingInTransaction() function used to Add Building from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 20.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function addBuildingInTransaction($buildingName) {
		$query = "INSERT INTO building (buildingName,abbreviation) VALUES ('$buildingName','$buildingName')";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


//-------------------------------------------------------------------------------
//
//addBlockInTransaction() function used to Add Block from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 20.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function addBlockInTransaction($blockName,$lastBuildingId) {
		$query = "INSERT INTO block (blockName,abbreviation,buildingId) VALUES ('$blockName','$blockName',$lastBuildingId)";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

//-------------------------------------------------------------------------------
//
//addSectionInTransaction() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 08.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function addRoomInstituteInTransaction($lastId,$getInstituteId) {
		$query = "INSERT INTO room_institute(roomId,instituteId) VALUES ($lastId,$getInstituteId)";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

//-------------------------------------------------------------------------------
//
//getRoomCheck() function to check existing room name
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 08.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getRoomCheck($roomName,$getBlockId) {
    
       $query = "	SELECT	r.roomName
					FROM	room r,
							block b
					WHERE	r.roomName ='$roomName'
					AND		b.blockId = r.blockId
					AND		r.blockId = $getBlockId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getRoomAbbrCheck() function returns the block from block table
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 08.10.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getRoomAbbrCheck($roomAbbreviation,$getBlockId) {
    
        $query = "	SELECT	r.roomAbbreviation
					FROM	room r,
							block b
					WHERE	r.roomAbbreviation ='$roomAbbreviation'
					AND		b.blockId = r.blockId
					AND		r.blockId = $getBlockId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

        
 }
?>

<?php 
  // $History: RoomManager.inc.php $
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 4/19/10    Time: 3:48p
//Updated in $/LeapCC/Model
//fixed bug no.0003289
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 11/03/09   Time: 4:40p
//Updated in $/LeapCC/Model
//fixed bug nos.0001899, 0001898, 0001891,0001889
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 10/21/09   Time: 6:50p
//Updated in $/LeapCC/Model
//Fixed bug nos. 0001822, 0001823, 0001824, 0001847, 0001850, 0001825
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 10/15/09   Time: 2:35p
//Updated in $/LeapCC/Model
//fixed bug nos. 0001790, 0001789, 0001768, 0001767, 0001769, 0001761,
//0001758, 0001759, 0001757, 0001791
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 9/03/09    Time: 7:33p
//Updated in $/LeapCC/Model
//fixed bug nos.0001440, 0001433, 0001432, 0001423, 0001239, 0001406,
//0001405, 0001404
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/17/09    Time: 7:34p
//Updated in $/LeapCC/Model
//fixed bug nos.0001093, 0001086, 0000672, 0001087
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 17/08/09   Time: 14:17
//Updated in $/LeapCC/Model
//Added the check : If a room is used in time table then it cannot be
//deleted and cannot be de-allocated from the institute with which it is
//associated in time table
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 14/08/09   Time: 16:43
//Updated in $/LeapCC/Model
//Done enhancement in "Room" module---added room and institute mapping so
//that one room can be shared across institutes
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/12/09    Time: 7:27p
//Updated in $/LeapCC/Model
//fixed bug nos. 0000969, 0000965, 0000962, 0000963, 0000980, 0000950
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/27/09    Time: 6:31p
//Updated in $/LeapCC/Model
//show "--" instead of NULL
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/26/09    Time: 6:10p
//Updated in $/LeapCC/Model
//fixed bugs No.5,6 bugs-report.doc dated 26.05.09
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 9/30/08    Time: 3:09p
//Updated in $/Leap/Source/Model
//modified for NULL value
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 9/26/08    Time: 3:06p
//Updated in $/Leap/Source/Model
//new field exam capacity added 
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/18/08    Time: 1:22p
//Updated in $/Leap/Source/Model
//modified in sql query
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/18/08    Time: 1:11p
//Updated in $/Leap/Source/Model
//modified for maxlength room name
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:40p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/12/08    Time: 2:28p
//Updated in $/Leap/Source/Model
//modified in functions
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/04/08    Time: 11:08a
//Updated in $/Leap/Source/Model
//modified in table fields
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/02/08    Time: 8:24p
//Updated in $/Leap/Source/Model
//add data base query for add, delete or edit
?>
