<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Attendance" Module
//
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class AttendanceCodeManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "AttendanceCodeManager" CLASS
//
// Author :Arvind Singh Rawat 
// Created on : 12-June-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

	private function __construct() {
	}
	
	//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "AttendanceCodeManager" CLASS
//
// Author :Arvind Singh Rawat 
// Created on : 12-June-2008
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

// THIS FUNCTION IS USED FOR ADDING A Attendance
//
// Author :Arvind Singh Rawat 
// Created on : 12-June-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       


	public function addAttendanceCode() {
		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
		return SystemDatabaseManager::getInstance()->runAutoInsert('attendance_code', 
        array(
               'attendanceCode','attendanceCodeName','attendanceCodeDescription','attendanceCodePercentage','showInLeaveType', 'instituteId'
             ), 
        
        array(
               strtoupper($REQUEST_DATA['attendanceCode']),$REQUEST_DATA['attendanceCodeName'],
               $REQUEST_DATA['attendanceCodeDescription'],$REQUEST_DATA['attendanceCodePercentage'],$REQUEST_DATA['showInLeaveType'], $instituteId
             )
        );
	}
 
// THIS FUNCTION IS USED FOR EDITING A Attendance
//
// Author :Arvind Singh Rawat 
// Created on : 12-June-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
 
 
    public function editAttendanceCode($id) {
        global $REQUEST_DATA;  
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        return SystemDatabaseManager::getInstance()->runAutoUpdate('attendance_code',
        array(
               'attendanceCode','attendanceCodeName','attendanceCodeDescription','attendanceCodePercentage','showInLeaveType', 'instituteId'
             ), 
        
        array(
               strtoupper($REQUEST_DATA['attendanceCode']),$REQUEST_DATA['attendanceCodeName'],
               $REQUEST_DATA['attendanceCodeDescription'],$REQUEST_DATA['attendanceCodePercentage'],$REQUEST_DATA['showInLeaveType'], $instituteId
             ), 
        "attendanceCodeId=$id" );  
    }    
 
// THIS FUNCTION IS USED FOR GETTING Attendance LIST
//
// Author :Arvind Singh Rawat 
// Created on : 12-June-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
 
 
    public function getAttendanceCode($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        
        $query = "SELECT 
                         attendanceCodeDescription,
                         attendanceCodePercentage,
                         attendanceCodeId,
                         attendanceCode,
                         attendanceCodeName,
                         showInLeaveType
        FROM attendance_code 
        $conditions"; 
              
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
	
// THIS FUNCTION IS USED FOR GETTING Attendance LIST 
//
// Author :Arvind Singh Rawat 
// Created on : 12-June-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
	
    public function getAttendanceCodeList($conditions='', $limit = '', $orderBy=' attendanceCodeName') {
  		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
  
        $query = "SELECT 
                        attendanceCodeDescription,
                        attendanceCodePercentage,
                        attendanceCodeId, 
                        attendanceCode, 
                        attendanceCodeName,
                        IF(showInLeaveType=1,'Yes','No') AS showInLeaveType 
                 FROM 
                        attendance_code  
                 $conditions                   
                 ORDER BY $orderBy 
                 $limit";
         
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
	
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "attendanceCode" TABLE
//
// Author :Arvind Singh Rawat 
// Created on : 12-June-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
	
	
	 
    public function getTotalAttendanceCode($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM attendance_code 
        $conditions ";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
	
// THIS FUNCTION IS USED FOR DELETING A "attendanceCode" RECORD
//
// Author :Arvind Singh Rawat 
// Created on : 12-June-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
	
     public function deleteAttendanceCode($attendanceCodeId) {
     
        $query = "DELETE 
        FROM attendance_code 
        WHERE attendanceCodeId=$attendanceCodeId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }   
    
    // check DEPENDENCY CONSTRAINT 
    public function getCheckAttendance($conditions='') {
    
        $query = "SELECT COUNT(*) AS cnt
                  FROM ".ATTENDANCE_TABLE."
                  $conditions  ";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    
}    

//$History: AttendanceCodeManager.inc.php $
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:14p
//Updated in $/LeapCC/Model
//added code for multiple tables.
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Model
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/11/09    Time: 6:38p
//Updated in $/LeapCC/Model
//getTotalAttendanceCode function update
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 20/04/09   Time: 15:27
//Updated in $/LeapCC/Model
//modified codes
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/21/08    Time: 4:07p
//Updated in $/Leap/Source/Model
//removed a field in queries attendanceCodeAction
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/19/08    Time: 12:48p
//Updated in $/Leap/Source/Model
//no change
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:18p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/11/08    Time: 1:14p
//Updated in $/Leap/Source/Model
//Added comments above functions
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/03/08    Time: 7:32p
//Updated in $/Leap/Source/Model
//modified table name 
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/17/08    Time: 4:16p
//Updated in $/Leap/Source/Model
//added new fields
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 7:18p
//Created in $/Leap/Source/Model
//file added
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:20p
//Created in $/Leap/Source/Model
//New Files Added in Model Folder

?>