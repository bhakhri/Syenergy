<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Stream Wise Attendance Manager" Module
//
// Author :Alka Handa
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class StreamWiseAttendanceManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "AttendancePercentManager" CLASS
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	private function __construct() {
	}

	//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "AttendancePercentManager" CLASS
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
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


    public function getTimeTableDegreeBrach($condition='') {
         
        $query = "SELECT 
                          DISTINCT b.branchCode, d.degreeCode, d.degreeName, b.branchId, d.degreeId      
                  FROM 
                           ".TIME_TABLE_TABLE."  tt, class c, branch b, degree d
                  WHERE
                          tt.classId = c.classId AND
                          b.branchId = c.branchId AND
                          d.degreeId = c.degreeId AND
                          tt.toDate IS NULL    
                  $condition";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   
    }
    
     public function getTimeTableStudyPeriod($condition='') {
         
        $query = "SELECT 
                         DISTINCT sp.periodName, sp.studyPeriodId    
                  FROM 
                          ".TIME_TABLE_TABLE."  tt, class c, branch b, degree d, study_period sp
                  WHERE
                         tt.classId = c.classId AND
                         b.branchId = c.branchId AND
                         d.degreeId = c.degreeId AND
                         tt.toDate IS NULL    AND
                         c.studyPeriodId = sp.studyPeriodId
                  $condition       
                  ORDER BY
                         sp.studyPeriodId ";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   
    }
    
    
    public function getAttendance($condition='', $dateCondition='') {
        global $sessionHandler; 
        $query = "SELECT                                                             
                        SUM(IF(att.isMemberOfClass =0, 0,IF(att.attendanceType=2,(ac.attendanceCodePercentage /100), att.lectureAttended)))  AS attended,
                        SUM(IF(att.isMemberOfClass =0, 0,att.lectureDelivered)) AS delivered
                  FROM 
                        ".ATTENDANCE_TABLE."  att
                        LEFT JOIN attendance_code ac ON 
                        (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId= '".$sessionHandler->getSessionVariable('InstituteId')."' )
                  WHERE 
                        att.classId IN (SELECT 
                                             DISTINCT c.classId 
                                        FROM 
                                             class c,  ".TIME_TABLE_TABLE."  tt 
                                        WHERE 
                                             c.classId = tt.classId AND
                                             c.instituteId= '".$sessionHandler->getSessionVariable('InstituteId')."' AND
                                             c.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."' 
                                        $condition)
                         $dateCondition               
                  GROUP BY
                        att.classId";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   
    }
    
    
    
}

?>
