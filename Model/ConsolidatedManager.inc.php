<?php
//-------------------------------------------------------
//  This File contains Query Report of the "Consolidated" Module
//
//--------------------------------------------------------
global $FE;
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId


class ConsolidatedManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ConsolidatedManager" CLASS
//
//-------------------------------------------------------------------------------     
	
	private function __construct() {
	}
	

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ConsolidatedManager" CLASS
//
// Author :Parveen Sharma
// Created on : 19-July-2008
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
	
    
    public function getSingleQueryList($filter='') {
     
        $query = "SELECT $filter  $limit ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    
    // Query to show list of active classes with batch, className and no. of students 
 
    public function getBatchwiseList($conditions='',$limit='',$flag='') {
       
        $query = "SELECT batch.batchYear AS 'Batch Year', class.className AS 'Class', 
                        COUNT( student.classId) AS 'Students'
                  FROM class
                        LEFT JOIN batch ON ( class.batchId = batch.batchId )
                        LEFT JOIN student ON ( student.classId = class.classId )
                   WHERE class.isActive = 1   
                   GROUP BY class.classId
                   ORDER BY batch.batchYear ASC, class.className ASC $limit ";
        
        if($flag!='') {
          return SystemDatabaseManager::getInstance()->executeField($query,"Query: $query");  
        }
        else {
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }
    } 
    
     // Query to show list of active classes with Institute, batch, className and no. of students  
    public function getInstitutewiseList($conditions='',$limit='',$flag='') {
       
        $query = "SELECT
                        institute.instituteCode  AS 'Institute Code'
                        , batch.batchYear  AS 'Batch Year'
                        , class.className    AS 'Class' 
                        ,(SELECT COUNT(studentId) FROM `student` WHERE classId = class.classId) AS Students
                        ,(SELECT IF(COUNT(*)>0,CONCAT('Yes',' (',COUNT(DISTINCT subjectId),')'),
                                               '<font color=\"red\">No</font>') FROM `subject_to_class` WHERE classId = class.classId) AS 'Subject Mapped'
                        ,(SELECT IF(COUNT(*)>0,CONCAT('Yes',' (',COUNT(DISTINCT groupId),')'),
                                                '<font color=\"red\">No</font>') FROM `group` WHERE classId = class.classId ) AS 'Group'   
                        ,(SELECT IF(COUNT(*)>0,CONCAT('Yes',' (',COUNT(DISTINCT subjectId),')'),
                                                '<font color=\"red\">No</font>') FROM  ".TIME_TABLE_TABLE."  WHERE 
                         ".TIME_TABLE_TABLE.".instituteId=class.instituteId AND class.sessionId =  ".TIME_TABLE_TABLE.".sessionId AND toDate IS NULL AND 
                        classId = class.classId) AS 'Time Table'
                    FROM
                        class
                        INNER JOIN batch ON (class.batchId = batch.batchId)
                        INNER JOIN institute ON (class.instituteId = institute.instituteId) AND (batch.instituteId = institute.instituteId)
                        WHERE class.isActive = 1
                        ORDER BY institute.instituteCode ASC, batch.batchYear ASC, class.className ASC $limit ";
                        
        if($flag!='') {
          return SystemDatabaseManager::getInstance()->executeField($query,"Query: $query");  
        }
        else {
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }
    } 
    
    
    // Query to show institute department wise data statistics of employees 
    public function getDepartmentwiseList($conditions='',$limit='',$flag='') {
       
        $query = "SELECT 
                        institute.instituteCode   AS 'Institute Code' , 
                        IF(department.departmentName IS NULL , '--', department.departmentName ) AS Department, 
                        IF( isTeaching =1, 'Teaching', 'Non Teaching' ) AS 'Status', count( employee.employeeId ) AS employees
                  FROM employee
                       LEFT JOIN department ON ( employee.departmentId = department.departmentId )
                       LEFT JOIN institute ON ( institute.instituteId = employee.instituteId )
                  GROUP BY department.departmentName, isTeaching, institute.instituteId
                  ORDER BY institute.instituteCode ASC , Status ASC, department.departmentName ASC $limit ";
                        
        if($flag!='') {
          return SystemDatabaseManager::getInstance()->executeField($query,"Query: $query");  
        }
        else {
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }
    }
    
    

// Query to show institute batch class wise list of subjects 
    public function getClasswiseList($conditions='',$limit='',$flag='') {
       
        $query = "SELECT
                    institute.instituteCode   AS 'Institute Code' 
                    , batch.batchYear   AS 'Batch Year' 
                    , class.className   AS 'Class' 
                    ,(SELECT COUNT( subjectToClassId ) FROM subject_to_class WHERE classId = class.classId) AS Total 
                    , subject.subjectCode   AS 'Subject Code' 
                    , subject.subjectName   AS 'Subject Name' 
                FROM
                    subject_to_class
                    INNER JOIN subject ON (subject_to_class.subjectId = subject.subjectId)
                    INNER JOIN class ON (subject_to_class.classId = class.classId)
                    INNER JOIN batch ON (class.batchId = batch.batchId)
                    INNER JOIN institute ON (class.instituteId = institute.instituteId) AND (batch.instituteId = institute.instituteId)
                    ORDER BY institute.instituteCode ASC, batch.batchYear ASC, class.className ASC, subject.subjectCode ASC  $limit ";
                    
         if($flag!='') {
          return SystemDatabaseManager::getInstance()->executeField($query,"Query: $query");  
        }
        else {
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }
    }

    
    // Query to show institute batch class wise data statistics of subjects 
    public function getSubjectwiseList($conditions='',$limit='',$flag='') {
       
        $query = "SELECT
                        institute.instituteCode    AS 'Institute Code' 
                        , batch.batchYear   AS 'Batch Year' 
                        , class.className   AS 'Class' 
                        ,(SELECT COUNT( subjectToClassId ) FROM subject_to_class WHERE classId = class.classId) AS 'Total Subjects'
                        , (SELECT GROUP_CONCAT(subjectCode) FROM `subject` WHERE subjectId IN 
                           (SELECT subjectId FROM subject_to_class WHERE classId = class.classId)) AS Subjects
                    FROM
                        class
                        INNER JOIN batch ON (class.batchId = batch.batchId)
                        INNER JOIN institute ON (class.instituteId = institute.instituteId) AND (batch.instituteId = institute.instituteId)
                        WHERE class.isActive = 1
                        ORDER BY institute.instituteCode ASC, batch.batchYear ASC, class.className ASC $limit ";
      
         if($flag!='') {
          return SystemDatabaseManager::getInstance()->executeField($query,"Query: $query");  
        }
        else {
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }
    }  

    // Query to show institute wise data statistics of class, group,  
    public function getClassGroupwiseList($conditions='',$limit='',$flag='') {
         
        $query = "SELECT
                            inst.instituteCode  AS 'Institute Code'
                            , (SELECT COUNT(classId) FROM class WHERE isActive = 1 AND instituteId = inst.instituteId) AS Classes
                            , (SELECT COUNT(groupId) FROM `group` WHERE classId IN (SELECT classId FROM class WHERE isActive = 1 AND instituteId = inst.instituteId)) AS Groups
                            , (SELECT COUNT(subjectId) FROM `subject_to_class` WHERE classId IN (SELECT classId FROM class WHERE isActive = 1 AND instituteId = inst.instituteId)) AS Subjects
                            , (SELECT COUNT(studentId) FROM student WHERE classId IN (SELECT classId FROM class WHERE isActive = 1 AND instituteId = inst.instituteId)) AS Students
                            , (SELECT COUNT(departmentId) FROM department) AS Department
                            , (SELECT COUNT(designationId) FROM designation) AS Designation    
                            , (SELECT COUNT(employeeId) FROM employee WHERE instituteId = inst.instituteId AND isActive = 1 ) AS Staff
                            , (SELECT COUNT(employeeId) FROM employee WHERE instituteId = inst.instituteId AND isActive = 1 AND isTeaching = 1) AS 'Teaching Staff'
                            , (SELECT COUNT(employeeId) FROM employee WHERE instituteId = inst.instituteId AND isActive = 1 AND isTeaching = 0) AS 'Non Teaching Staff'
                            , (SELECT COUNT(busId) FROM bus) AS Bus
                            , (SELECT COUNT(busRouteId) FROM bus_route) AS 'Bus Route'
                            , (SELECT COUNT(busStopId) FROM bus_stop) AS 'Bus Stop'
                 FROM
                           institute AS inst ORDER BY inst.instituteCode  $limit ";
                           
         if($flag!='') {
          return SystemDatabaseManager::getInstance()->executeField($query,"Query: $query");  
        }
        else {
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }
    }  
                     
    

    // Query to show institute wise data statistics of buildings, blocks, rooms, theory, labs, nonteaching
   
    public function getRoomwiseList($conditions='',$limit='',$flag='') {
         
        $query = "SELECT
                          (SELECT COUNT(buildingId) FROM building WHERE 1) AS Buildings
                        , (SELECT COUNT(blockId) FROM block WHERE 1) AS Blocks
                        , (SELECT COUNT(roomId) FROM room WHERE 1) AS Rooms
                        , (SELECT COUNT(r.roomId) FROM room r,room_type rt WHERE r.roomTypeId=rt.roomTypeId AND rt.roomType = 'Theory') AS Theory
                        , (SELECT COUNT(r.roomId) FROM room r,room_type rt WHERE r.roomTypeId=rt.roomTypeId AND ( rt.roomType = 'Lab' OR rt.roomType = 'Laboratory') ) AS Labs
                        , (SELECT COUNT(r.roomId) FROM room r,room_type rt WHERE r.roomTypeId=rt.roomTypeId AND rt.roomType = 'Non Teaching') AS 'Non Teaching'
                    FROM
                        institute AS inst LIMIT 1   $limit ";
                        
        if($flag!='') {
          return SystemDatabaseManager::getInstance()->executeField($query,"Query: $query");  
        }
        else {
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }
    }  

    // Query to show building block wise list of rooms with data statistics  
    public function getBlockswiseList($conditions='',$limit='',$flag='') {
         
        $query = "SELECT
                        building.buildingName    AS 'Building Name' 
                        , block.blockName  AS 'Block Name' 
                        , GROUP_CONCAT(DISTINCT room.roomAbbreviation ORDER BY room.roomAbbreviation SEPARATOR ', ')  AS 'Room Abbr.' 
                        , (SELECT COUNT(roomId) FROM room WHERE room.blockId = block.blockId) AS Rooms
                    FROM
                        block
                        INNER JOIN building ON (block.buildingId = building.buildingId)
                        INNER JOIN room  ON (room.blockId = block.blockId)
                    GROUP BY block.blockId    
                    ORDER BY building.buildingName ASC, block.blockName ASC, room.roomName ASC $limit ";  
                        
        if($flag!='') {
          return SystemDatabaseManager::getInstance()->executeField($query,"Query: $query");  
        }
        else {
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }
    }  


    // Query to show bus data statistics (Stop Points of a root)  
    public function getStopPointwiseList($conditions='',$limit='',$flag='') {
         
        $query = "SELECT
                        bus_route.routeCode AS RouteName
                        , count(bus_stop.busRouteId) AS 'Total Stop Points'
                    FROM
                        bus_stop
                        INNER JOIN bus_route ON (bus_stop.busRouteId = bus_route.busRouteId)
                    GROUP BY bus_route.routeCode
                    ORDER BY bus_route.routeCode ASC, 'Total Stop Points' ASC $limit ";
        
        if($flag!='') {
          return SystemDatabaseManager::getInstance()->executeField($query,"Query: $query");  
        }
        else {
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }
    }

   // Query to show degrees with branch

    public function getDegreewiseList($conditions='',$limit='',$flag='') {
         
        $query = "SELECT
                        degree.degreeCode AS Degree 
                        , branch.branchCode AS Branch
                        , (SELECT COUNT(branchId) FROM class WHERE isActive = 1 AND degreeId = degree.degreeId AND branchId = branch.branchId) AS Degrees
                    FROM
                        class
                        INNER JOIN degree ON (class.degreeId = degree.degreeId)
                        INNER JOIN branch ON (class.branchId = branch.branchId)
                    GROUP BY branch.branchName, degree.degreeCode
                    ORDER BY degree.degreeName ASC, branch.branchName ASC $limit ";
                    
        if($flag!='') {
          return SystemDatabaseManager::getInstance()->executeField($query,"Query: $query");  
        }
        else {
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }
    }


    // Query to show institute wise data statistics of users  

    public function getUserwiseList($conditions='',$limit='',$flag='') {
         
        $query = "SELECT
                        inst.instituteCode
                        , (SELECT COUNT(userId) FROM `user` WHERE instituteId = inst.instituteId) AS Users
                        , (SELECT COUNT(userId) FROM `user` WHERE roleId = 1 AND instituteId = inst.instituteId) AS Admin
                        , (SELECT COUNT(userId) FROM `user` WHERE roleId = 2 AND instituteId = inst.instituteId) AS Teachers 
                        , (SELECT COUNT(userId) FROM `user` WHERE roleId = 3 AND instituteId = inst.instituteId) AS Parents
                        , (SELECT COUNT(userId) FROM `user` WHERE roleId = 4 AND instituteId = inst.instituteId) AS Students
                        , (SELECT COUNT(userId) FROM `user` WHERE roleId = 5 AND instituteId = inst.instituteId) AS Management
                        , (SELECT COUNT(userId) FROM `user` WHERE roleId NOT IN (1,2,3,4,5) AND instituteId = inst.instituteId) AS Others
                    FROM
                        institute AS inst $limit ";
                         
         if($flag!='') {
          return SystemDatabaseManager::getInstance()->executeField($query,"Query: $query");  
        }
        else {
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }
    }               
    
    // Query to show institute class wise data statistics of groups  

     public function getGroupwiseList($conditions='',$limit='',$flag='') {
         
        $query = "SELECT
                        institute.instituteCode
                        , batch.batchYear
                        , class.className
                        ,(SELECT COUNT(groupId) FROM `group` WHERE classId = class.classId) AS Groups 
                        ,(SELECT COUNT(groupId) FROM `group` WHERE classId = class.classId AND groupTypeId=3) AS Theory
                        ,(SELECT COUNT(groupId) FROM `group` WHERE classId = class.classId AND groupTypeId=1) AS Tutorial
                        ,(SELECT COUNT(groupId) FROM `group` WHERE classId = class.classId AND groupTypeId=2) AS Practical
                    FROM
                        class
                        INNER JOIN batch ON (class.batchId = batch.batchId)
                        INNER JOIN institute ON (class.instituteId = institute.instituteId) AND (batch.instituteId = institute.instituteId)
                        WHERE class.isActive = 1
                        ORDER BY institute.instituteCode ASC, batch.batchYear ASC, class.className ASC $limit ";
                         
         if($flag!='') {
            return SystemDatabaseManager::getInstance()->executeField($query,"Query: $query");  
         } 
         else {
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
         }
     }     

     // Query to show institute wise hostel data statistics of rooms 
     public function getHostelwiseList($conditions='',$limit='',$flag='') {
             
           $query = "SELECT
                            hostel.hostelName
                            , hostel_room_type.roomType
                            , count(hostel_room.hostelRoomId)
                        FROM
                            hostel_room
                            INNER JOIN hostel 
                                ON (hostel_room.hostelId = hostel.hostelId)
                            INNER JOIN rimtd.hostel_room_type 
                                ON (hostel_room.hostelRoomTypeId = hostel_room_type.hostelRoomTypeId)
                        GROUP BY hostel.hostelName, hostel_room_type.roomType
                        ORDER BY hostel.hostelName ASC, hostel_room_type.roomType ASC $limit ";  
        
           
         if($flag!='') {
           return SystemDatabaseManager::getInstance()->executeField($query,"Query: $query");  
         }
         else {
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
         }
     } 
     
     
     public function getDuplicateAttendance($conditions='',$limit='',$flag='') {
          
         $query = "SELECT 
                            DISTINCT ins.instituteCode AS 'Institute Code', 
                                     c.className AS 'Class', 
                                     emp.employeeName AS 'Employee Name',
                                     CONCAT(sub.subjectName, '<br>',sub.subjectCode) AS 'Subject', 
                                     grp.groupName AS 'Group', 
                                     IFNULL(p.periodNumber,'".NOT_APPLICABLE_STRING."') AS 'Period Number',
                                     CONCAT(DATE_FORMAT(att.fromDate,'%d-%b-%Y'), '<br>',DATE_FORMAT(att.toDate,'%d-%b-%Y')) AS 'Attendance<br>Date',  
                                     IF(attendanceType=1,'Bulk','Daily') AS  'Attendance<br>Type',
                                     COUNT(*) AS 'No. of Times<br>Attendance<br>Duplicated'
                     FROM 
                             class c, employee emp, `subject` sub,
                            `group` grp, `institute` ins, 
                            ".ATTENDANCE_TABLE." att LEFT JOIN `period` p ON att.periodId = p.periodId 
                     WHERE
                            ins.instituteId = c.instituteId AND
                            att.employeeId = emp.employeeId AND
                            att.classId = c.classId AND 
                            att.subjectId = sub.subjectId AND
                            att.groupId = grp.groupId 
                     GROUP BY
                            att.employeeId, att.classId, att.subjectId, att.groupId, att.periodId, 
                            att.fromDate, att.studentId, c.instituteId, c.sessionId    
                     HAVING 
                            COUNT(*) > 1 ";
         if($flag!='') {
           return SystemDatabaseManager::getInstance()->executeField($query,"Query: $query");  
         }
         else {
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
         }                            
     }        
     
}

?>