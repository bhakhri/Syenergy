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

class AttendancePercentManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "AttendancePercentManager" CLASS
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	private function __construct() {
	}

	//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "AttendancePercentManager" CLASS
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

    public function getActiveTimeTableLabelId() {
        global $REQUEST_DATA;
		global $sessionHandler;

        $query = "SELECT timeTableLabelId FROM time_table_labels WHERE isActive=1 AND instituteId = ".$sessionHandler->getSessionVariable('InstituteId');

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function addAttendancePercent($str) {
		global $REQUEST_DATA;

        //$query = "INSERT INTO attendance_marks_percent (percentFrom,percentTo,marksScored,subjectTypeId,timeTableLabelId, instituteId,degreeId) values $str";
        $query = "INSERT INTO attendance_marks_percent (percentFrom,percentTo,marksScored,instituteId,attendanceSetId)
                  values
                  $str";

        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	}

// THIS FUNCTION IS USED FOR EDITING A Attendance
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function editAttendancePercent($id) {
        global $REQUEST_DATA;
		global $sessionHandler;

       // return SystemDatabaseManager::getInstance()->runAutoUpdate('`attendance_marks_percent` ', array('percentFrom','percentTo','marksScored','subjectTypeId','timeTableLabelId', 'instituteId','degreeId'), array(strtoupper($REQUEST_DATA['percentFrom']),$REQUEST_DATA['percentTo'],$REQUEST_DATA['marksScored'],$REQUEST_DATA['subjectTypeId'],$REQUEST_DATA['timeTableLabelId'], $sessionHandler->getSessionVariable('InstituteId'),$REQUEST_DATA['degreeId']), "attendanceMarksId=$id" );
       return SystemDatabaseManager::getInstance()->runAutoUpdate('`attendance_marks_percent` ', array('percentFrom','percentTo','marksScored','instituteId','attendanceSetId'), array(strtoupper($REQUEST_DATA['percentFrom']),$REQUEST_DATA['percentTo'],$REQUEST_DATA['marksScored'], $sessionHandler->getSessionVariable('InstituteId'),$REQUEST_DATA['attendanceSetId']), "attendanceMarksId=$id" );
    }

// THIS FUNCTION IS USED FOR GETTING Attendance LIST
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


    public function getAttendancePercent($conditions='') {

        global $sessionHandler;
        $query = "SELECT
                        attendanceMarksId,  percentFrom, percentTo , marksScored, a.subjectTypeId, a.timeTableLabelId,
                        a.degreeId, att.attendanceSetId, att.attendanceSetName, att.evaluationCriteriaId
                  FROM
                        `attendance_marks_percent` a,  attendance_set att
                  WHERE
                        a.attendanceSetId = att.attendanceSetId AND
                        a.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                  $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

// THIS FUNCTION IS USED FOR GETTING Attendance LIST
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getAttendancePercentList($conditions='', $limit = '', $orderBy=' attendancePercentName') {

        $query = "SELECT
                        attendanceMarksId,  percentFrom, percentTo , marksScored, a.subjectTypeId, a.timeTableLabelId,
                        a.degreeId, att.attendanceSetId, att.attendanceSetName, att.evaluationCriteriaId
                  FROM
                        `attendance_marks_percent` a,  attendance_set att
                  WHERE
                        a.attendanceSetId = att.attendanceSetId AND
                        a.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                  $conditions
                  ORDER BY $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "attendancePercent" TABLE
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getTotalAttendancePercent($conditions='') {

        $query = "SELECT
                        COUNT(*) AS cnt
                  FROM
                        `attendance_marks_percent` a,  attendance_set att
                  WHERE
                        a.attendanceSetId = att.attendanceSetId AND
                        a.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                  $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

// THIS FUNCTION IS USED FOR DELETING A "attendancePercent" RECORD
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
     public function deleteAttendancePercent($condition='') {
        $query = "DELETE
                  FROM `attendance_marks_percent`
                  $condition";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
}

//$History: AttendancePercentManager.inc.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/29/09   Time: 2:05p
//Updated in $/LeapCC/Model
//new enhancement attendance Set Id base checks updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/20/09   Time: 12:26p
//Updated in $/LeapCC/Model
//degreeId, timeTableLabelId added
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/18/09    Time: 12:28p
//Created in $/LeapCC/Model
//file added

?>