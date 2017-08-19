<?php

//-------------------------------------------------------------------------------
//
//ReportComplaintsManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ReportComplaintsManager {
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
//
//addReportComplaints() is used to add new record in database.
// Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function addReportComplaints() {
		global $REQUEST_DATA;
		global $sessionHandler;

		return SystemDatabaseManager::getInstance()->runAutoInsert('complaints', array('subject','description','complaintCategoryId','hostelRoomId','studentId','complaintOn','trackingNumber','complaintStatus','addedUserId','modifyUserId'), array($REQUEST_DATA['subject'],$REQUEST_DATA['description'],$REQUEST_DATA['category'],$REQUEST_DATA['room'],$REQUEST_DATA['reportedBy'],$REQUEST_DATA['complaintOn'],$REQUEST_DATA['trackingNumber'],$REQUEST_DATA['complaintStatus'],$sessionHandler->getSessionVariable('UserId'),$sessionHandler->getSessionVariable('UserId')));
	}

//-------------------------------------------------------------------------------
//
//addReportEscalateComplaints() is used to add new record in database.
// Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function addReportEscalateComplaints($complaintStatus) {
		global $REQUEST_DATA;
		global $sessionHandler;

		return SystemDatabaseManager::getInstance()->runAutoInsert('complaints', array('subject','description','complaintCategoryId','hostelRoomId','studentId','complaintOn','trackingNumber','complaintStatus','addedUserId','modifyUserId'), array($REQUEST_DATA['subject'],$REQUEST_DATA['description'],$REQUEST_DATA['category'],$REQUEST_DATA['room'],$REQUEST_DATA['reportedBy'],$REQUEST_DATA['complaintOn'],$REQUEST_DATA['trackingNumber'],$complaintStatus,$sessionHandler->getSessionVariable('UserId'),$sessionHandler->getSessionVariable('UserId')));
	}
    //-------------------------------------------------------------------------------
//
//editHostelRoom() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editReportComplaints($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('complaints', array('subject','description','complaintCategoryId','hostelRoomId','studentId','complaintOn','complaintStatus'), array(strtoupper($REQUEST_DATA['subject']),$REQUEST_DATA['description'], $REQUEST_DATA['category'],$REQUEST_DATA['room'],$REQUEST_DATA['reportedBy'],$REQUEST_DATA['complaintOn'],$REQUEST_DATA['complaintStatus']), "complaintId=$id" );
    }    
//-------------------------------------------------------------------------------
//
//getHostelRoom() is used to get the data.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getReportComplaintDetail($conditions='') {
     
     $query = "	SELECT 
							c.complaintId,
							c.subject,
							c.description,
							c.complaintCategoryId,
							c.hostelRoomId,
							c.studentId,
							c.complaintOn,
							c.complaintStatus,
							c.trackingNumber,
							cc.categoryName,
							hr.roomName,
							hs.hostelId,
							hs.hostelName
					FROM	complaints c,
							hostel_complaint_category cc,
							hostel hs,
							hostel_room hr,
							student s
					WHERE	hr.hostelId=hs.hostelId 
					AND		c.complaintCategoryId = cc.complaintCategoryId
					AND		hs.hostelId = hr.hostelId
					AND		c.studentId = s.studentId
					AND		c.hostelRoomId = hr.hostelRoomId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getHostelRoom() is used to check duplicate tracking number
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function checkDuplicateTrackDetail($conditions='') {
     
   $query = "	SELECT 
							distinct(c.trackingNumber)
					FROM	complaints c
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getHostelRoom() is used to get the data.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getReportTrackingComplaintDetail($conditions='') {
     
    $query = "	SELECT 
							c.complaintId,
							c.subject,
							c.description,
							c.complaintCategoryId,
							c.hostelRoomId,
							c.studentId,
							c.complaintOn,
							c.complaintStatus,
							c.trackingNumber,
							cc.categoryName,
							hr.roomName,
							hs.hostelId,
							hs.hostelName
					FROM	complaints c,
							hostel_complaint_category cc,
							hostel hs,
							hostel_room hr,
							student s
					WHERE	hr.hostelId=hs.hostelId 
					AND		c.complaintCategoryId = cc.complaintCategoryId
					AND		hs.hostelId = hr.hostelId
					AND		c.studentId = s.studentId
					AND		c.hostelRoomId = hr.hostelRoomId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //-------------------------------------------------------------------------------
//
//deleteHostelRoom() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   
    public function deleteReportComplaint($id) {
     
       $query = "	DELETE 
					FROM complaints 
					WHERE complaintId=$id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------
//
//getHostelRoomList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getReportComplaintDetailList($conditions='', $limit = '', $orderBy=' hr.roomName') {
     
  $query = "	SELECT 
							c.complaintId,
							c.subject,
							c.description,
							cc.categoryName,
							c.complaintOn,
							if(c.complaintStatus=1,'Pending',if(c.complaintStatus=2,'Escalate','Complete')) AS complaintStatus,
							c.complaintCategoryId,
							c.hostelRoomId,
							c.studentId,
							hr.roomName,
							hs.hostelName,
							CONCAT(s.firstName,' ',s.lastName) AS studentName
					FROM	complaints c,
							hostel_complaint_category cc,
							hostel hs,
							hostel_room hr,
							student s
					WHERE	c.complaintCategoryId = cc.complaintCategoryId
					AND		c.hostelRoomId = hr.hostelRoomId
					AND		hs.hostelId = hr.hostelId
					AND		c.studentId = s.studentId
							$conditions 
							ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------
//
//getTotalHostelRoom() is used to get total no. of records
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getTotalReportComplaintDetail($conditions='') {
    
     $query = "	SELECT	COUNT(*) AS totalRecords
					FROM	complaints c,
							hostel_complaint_category cc,
							hostel hs,
							hostel_room hr,
							student s
					WHERE	c.complaintCategoryId = cc.complaintCategoryId
					AND		c.hostelRoomId = hr.hostelRoomId
					AND		hs.hostelId = hr.hostelId
					AND		c.studentId = s.studentId
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getTotalHostelRoom() is used to get total no. of records
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getTotalAllHandleComplaintDetail($conditions,$orderBy) {
    
 $query = "	SELECT	COUNT(*) AS totalRecords
				FROM	complaints c,
						hostel hs,
						hostel_room hr,
						hostel_complaint_category cc,
						student s
				WHERE	hr.hostelRoomId = c.hostelRoomId
				AND		hs.hostelId = hr.hostelId
				AND		cc.complaintCategoryId = c.complaintCategoryId
				AND		c.hostelRoomId = hr.hostelRoomId
				AND		c.studentId = s.studentId
						$conditions ORDER BY $orderBy";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getHostelRoomList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getHandleAllComplaintDetailList($conditions,$orderBy,$limit) {
     
$query = "	SELECT 
						c.complaintId,
						c.subject,
						c.description,
						cc.categoryName,
						c.complaintOn,
						c.complaintStatus,
						c.studentId,
						if(c.complaintStatus=1,'Pending',if(c.complaintStatus=2,'Escalate','Complete')) AS updateComplaintStatus,
						c.complaintCategoryId,
						c.hostelRoomId,
						c.studentId,
						c.completionDate,
						c.completionRemarks,
						hr.roomName,
						hs.hostelName,
						CONCAT(s.firstName,' ',s.lastName) AS studentName
				FROM	complaints c,
						hostel hs,
						hostel_room hr,
						hostel_complaint_category cc,
						student s
				WHERE	hr.hostelRoomId = c.hostelRoomId
				AND		hs.hostelId = hr.hostelId
				AND		cc.complaintCategoryId = c.complaintCategoryId
				AND		c.hostelRoomId = hr.hostelRoomId
				AND		c.studentId = s.studentId
						$conditions ORDER BY $orderBy 
						$limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



	//-------------------------------------------------------------------------------
//
//getHostelRoomList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getReportComplaint($complaintId) {
     
	$query = "	SELECT 
						c.complaintId,
						c.subject,
						c.complaintOn,
						c.complaintStatus,
						c.completionRemarks,
						CONCAT(s.firstName,' ',s.lastName) AS studentName,
						hr.roomName
				FROM	complaints c,
						hostel_room hr,
						student s
				WHERE	c.complaintId = $complaintId
				AND		hr.hostelRoomId = c.hostelRoomId
				AND		c.studentId = s.studentId
						$conditions 
						ORDER BY studentName 
						$limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//getHostelRoomList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function updateHandleComplaints($complaintId,$complaintStatus,$completionDate,$remarks) {
     global $sessionHandler;

	$query = "	UPDATE	complaints
				SET		complaintStatus = $complaintStatus,
						completionDate = '$completionDate',
						completionRemarks = '$remarks',
						modifyUserId = ".$sessionHandler->getSessionVariable('UserId')."
				WHERE	complaintId = $complaintId";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//UpdateReportComplaints() is used to update complaintsStatus
//Author : Jaineesh
// Created on : 04.05.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function UpdateReportComplaints($conditions) {
     global $sessionHandler;

	$query = "	UPDATE	complaints
					SET		complaintStatus = 2
							$conditions";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getTotalHostelRoom() is used to get room name
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getRoomData($hostelId) {
    
       $query = "	SELECT	hostelRoomId,
							roomName
					FROM	hostel_room hr,
							hostel h
					WHERE	h.hostelId=hr.hostelId
					AND		h.hostelId = $hostelId
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getTotalHostelRoom() is used to get room name
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getStudent($roomId) {
    
    $query = "	SELECT	distinct(hr.hostelRoomId),
							c.studentId,
							c.complaintId,
							CONCAT(s.firstName,' ',s.lastName) AS studentName
					FROM	complaints c,
							student s,
							hostel_room hr
					WHERE	c.studentId=s.studentId
					AND		c.hostelRoomId = hr.hostelRoomId
					AND		hr.hostelRoomId = $roomId
							group By hr.hostelRoomId
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getTotalHostelRoom() is used to get total no. of records
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getStudentHostelData($hostelId) {
    
       $query = "	SELECT	hostelRoomId,
							roomName
					FROM	hostel_room hr,
							hostel h
					WHERE	h.hostelId=hr.hostelId
					AND		h.hostelId = $hostelId
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

}
?>

<?php
  //$History: ReportComplaintsManager.inc.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/04/09    Time: 7:07p
//Updated in $/LeapCC/Model
//make the changes as per discussion with pushpender sir
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/02/09    Time: 4:34p
//Updated in $/LeapCC/Model
//show some fields on list
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:18p
//Created in $/LeapCC/Model
//new query files for report complaints manager
//
//

?>