<?php 

//----------------------------------------------------------------------------------------
//  This File contains Bussiness Logic of the modules of "Parent Activities" 
//
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');




class ParentManager {
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
	
//----------------------------------------------------------------------------------------
//  This Function gets the notice Table data based on parent role for "DisplayNotices" Module
//
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------- 
   
    public function getNotices($conditions='') {
     
	 global $sessionHandler;
       $query = "SELECT n.noticeId, n.noticeSubject,n.noticeText
FROM notice n, notice_visible_to_role nr, role r
WHERE n.noticeId = nr.noticeId
AND r.roleId = nr.roleId
AND r.roleName = 'Parent'
AND nr.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
AND n.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
		$conditions
		";
		
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
 
//----------------------------------------------------------------------------------------
//  This Function  Gets the notices table fields for "DisplayNotices" Module
//
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------- 
      
    public function getNoticesList($conditions='', $limit = '', $orderBy=' noticeSubject') {
    global $sessionHandler;
        $query = "SELECT n.noticeId, n.noticeSubject,n.visibleFromDate,n.visibleToDate,noticeText
FROM notice n, notice_visible_to_role nr, role r
WHERE n.noticeId = nr.noticeId
AND r.roleId = nr.roleId
AND r.roleName = 'Parent'
AND nr.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
AND n.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
		$conditions                  
        ORDER BY $orderBy $limit ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
 
//----------------------------------------------------------------------------------------
//  This Function  Gets the notices table fields for "DisplayNotices" Module
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------- 
      
    public function getTotalNotices($conditions='') {
    global $sessionHandler;
        $query = "SELECT COUNT(*) AS totalRecords
FROM notice n, notice_visible_to_role nr, role r
WHERE n.noticeId = nr.noticeId
AND r.roleId = nr.roleId
AND r.roleName = 'Parent'
AND nr.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
AND n.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

//------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------
//                           Display Comments 
//------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------


//------------------------------------------------------------------------------------------------
// This Function  Gets the comments table fields for "DisplayComments" Module
//
// Author : Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------ 
	
	public function getComments($conditions='') {
     
	 global $sessionHandler;
       $query = "SELECT comments,studentId
	   FROM 
	   teacher_comment
	   WHERE
	    studentId='".$sessionHandler->getSessionVariable('StudentId')."'
	   AND curdate() BETWEEN visibleFromDate AND visibleToDate 
	   AND toParent='1'
		$conditions
		";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
  
//------------------------------------------------------------------------------------------------
// This Function  Gets the Comments List table fields for "DisplayComments" Module
//
// Author : Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------ 
	 
     
    public function getCommentsList($conditions='', $limit = '', $orderBy=' comments') {
    global $sessionHandler;
        $query = "SELECT comments,studentId
	   FROM 
	   teacher_comment
	   WHERE
	   studentId='".$sessionHandler->getSessionVariable('StudentId')."'
	   AND curdate() BETWEEN visibleFromDate AND visibleToDate 
	   AND toParent='1'
		$conditions                  
        ORDER BY $orderBy $limit";
     
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
//------------------------------------------------------------------------------------------------
// This Function  counts the Comments List table fields for "DisplayComments" Module
//
// Author : Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------ 

       
    public function getTotalComments($conditions='') {
    global $sessionHandler;
        $query = "SELECT COUNT(*) AS totalRecords
 	   FROM 
	   teacher_comment
	   WHERE
	   studentId='".$sessionHandler->getSessionVariable('StudentId')."'
	   AND curdate() BETWEEN visibleFromDate AND visibleToDate 
	   AND toParent='1'";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
	
//------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------	
/////                         Display Attendance module Function                          ////////
//------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------



//------------------------------------------------------------------------------------------------
// This Function  gets the subjects List  for "DisplayAttendance" Module
//
// Author : Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------ 
		public function getSubjectClass() {
       global $sessionHandler;
	    $query = "SELECT sub.subjectId, sub.subjectName  
        FROM subject sub, subject_to_class subcls,student s 
        WHERE sub.subjectId = subcls.subjectId
		 AND
		  subcls.classId=s.classId
		  AND 
		  s.studentId='".$sessionHandler->getSessionVariable('StudentId')."'
		   ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//------------------------------------------------------------------------------------------------
// This Function  gets the Daily Lecture Delivered List  for "DisplayAttendance" Module
//
// Author : Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------ 

	
  	 public function dailylecDelivered($subjectId,$startDate,$endDate) {
		global $REQUEST_DATA;
		global $sessionHandler;
        $query = "select count(dailyAttendanceId) AS lecturedelivered 
		FROM 
		attendance_daily ad,student s
		WHERE 
				s.studentId='".$sessionHandler->getSessionVariable('StudentId')."'
				 AND 
				ad.classId= s.classId AND
				isMemberOfClass =1 AND
				subjectId ='".$subjectId."'";
				
		if($startDate)
			$query .=" AND forDate >='".$startDate."' ";
		if($endDate)
			$query .=" and forDate <='".$endDate."' ";
		//$query .=" ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//------------------------------------------------------------------------------------------------
// This Function  gets the Bulk Lecture Delivered List  for "DisplayAttendance" Module
//
// Author : Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------ 
	
	public function bulklecDelivered($subjectId,$startDate,$endDate) {
		global $REQUEST_DATA;
        global $sessionHandler;
        $query = "select ad.lectureDelivered 
				FROM 
		attendance_bulk ad,student s
		WHERE 
				s.studentId='".$sessionHandler->getSessionVariable('StudentId')."' AND 
				ad.classId= s.classId AND
				isMemberOfClass =1 AND
				subjectId ='".$subjectId."'";
			
		if($startDate)
			$query .=" AND fromDate >='".$startDate."' ";
		if($endDate)
			$query .=" and toDate <='".$endDate."' ";
		//$query .=" ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
	
//------------------------------------------------------------------------------------------------
// This Function  gets the Daily Full Lecture Attend List  for "DisplayAttendance" Module
//
// Author : Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------ 
	

	 public function dailyFullLecAttend($subjectId,$startDate,$endDate) {
		global $REQUEST_DATA;
        global $sessionHandler;
        $query = "SELECT COUNT(*) AS fullattend
		 FROM 
		 attendance_daily attd, attendance_code attc ,student s
		 WHERE 
				attd.studentId='".$sessionHandler->getSessionVariable('StudentId')."' AND 
				attc.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' AND 
				attd.classId=s.classId AND
				attd.isMemberOfClass ='1' AND
				s.studentId=attd.studentId AND
				attc.instituteId = $instituteId
				attd.subjectId ='".$subjectId."'";
			
		if($startDate)
			$query .=" AND attd.forDate >='".$startDate."' ";
		if($endDate)
			$query .=" and attd.forDate <='".$endDate."' ";
		$query .="AND attd.attendanceCodeId = attc.attendanceCodeId AND attc.attendanceCodeAction =1";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
	
//------------------------------------------------------------------------------------------------
// This Function  gets the Daily Half Lecture Attend List  for "DisplayAttendance" Module
//
// Author : Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

	
	public function dailyHalfLecAttend($subjectId,$startDate,$endDate) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT COUNT(*) AS halfattend 
		FROM 
		attendance_daily attd, attendance_code attc,student s
		 WHERE 
				attd.studentId='".$sessionHandler->getSessionVariable('StudentId')."' AND 
				attc.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' AND 
				attd.classId= s.classId AND
				attd.isMemberOfClass =1 AND
				s.studentId='1' AND 
				attd.subjectId ='".$subjectId."'";
				
		if($startDate)
			$query .=" AND attd.forDate >='".$startDate."' ";
		if($endDate)
			$query .=" and attd.forDate <='".$endDate."' ";
		$query .="AND attd.attendanceCodeId = attc.attendanceCodeId AND attc.attendanceCodeAction =2";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//------------------------------------------------------------------------------------------------
// This Function  gets the Bulk Half Lecture Attend List  for "DisplayAttendance" Module
//
// Author : Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
	
	public function bulkLecAttend($subjectId,$startDate,$endDate) {
		global $REQUEST_DATA;
		global $sessionHandler;
        $query = "SELECT ab.lectureAttended 
		FROM
		 attendance_bulk ab,student s  
		 WHERE 
				s.studentId='".$sessionHandler->getSessionVariable('StudentId')."' AND 
				ab.classId=s.classId AND
				ab.isMemberOfClass =1 AND
				ab.subjectId ='".$subjectId."'";
			
		if($startDate)
			$query .=" AND fromDate >='".$startDate."' ";
		if($endDate)
			$query .=" and toDate <='".$endDate."' ";
		//$query .=" ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------
//                      Functions used for Dashboard Listing                           //////
//-------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------



//------------------------------------------------------------------------------------------------
// This Function  gets the Events List  for "DashBoard" Module
//
// Author : Arvind Singh Rawat
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

/*	public function getEvents($conditions='') {
     
	 global $sessionHandler;
	 $RoleIds=$sessionHandler->getSessionVariable('InstituteId');
       $query = "SELECT eventId,eventTitle,shortDescription,longDescription,startDate,endDate
	   FROM 
	   event
	   WHERE
	   instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
	   AND 
	   sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
	   and roleIds LIKE '%~$RoleIds~%'
		$conditions
		
		";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  */
	
	public function getEvents($conditions='', $limit = '', $orderBy=' eventTitle'){

 global $sessionHandler;
 $roleId=$sessionHandler->getSessionVariable('RoleId');
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $sessionId=$sessionHandler->getSessionVariable('SessionId');
   
 $query="SELECT eventTitle,shortDescription,longDescription,startDate,endDate
       FROM event  
       WHERE instituteId=$instituteId AND sessionId=$sessionId
        AND roleIds LIKE '%~$roleId~%'
        $conditions   ORDER BY $orderBy $limit " ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
}
	
//------------------------------------------------------------------------------------------------
// This Function  counts the Events List 
//
// Author : Arvind Singh Rawat
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

	
	public function getTotalEvents($conditions='') {
     
	 global $sessionHandler;
	 $RoleIds=$sessionHandler->getSessionVariable('RoleId');
       $query = "SELECT COUNT(*) AS totalRecords
	   FROM 
	   event
	   WHERE
	   instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
	   AND 
	   sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
	   and roleIds LIKE '%~$RoleIds~%'
		$conditions
		
		";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
	
	
//------------------------------------------------------------------------------------------------
// This Function  gets the Comments List  for "DashBoard" Module
//
// Author : Arvind Singh Rawat
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------


	public function getCommentsListing($conditions='',$limit = '',$orderBy=' tc.comments') {
     
	   global $sessionHandler;
       $query = "SELECT tc.comments,tc.studentId,e.employeeName,tc.visibleFromDate,tc.visibleToDate
	   FROM 
	   teacher_comment tc,employee e
	   WHERE
	   tc.teacherId=e.employeeId
	   AND
	   tc.studentId='".$sessionHandler->getSessionVariable('StudentId')."'
	   AND
	   e.isTeaching='1'
	   AND (curdate() BETWEEN tc.visibleFromDate AND tc.visibleToDate )
	   AND tc.toParent='1'
		$conditions ORDER BY $orderBy $limit " ;   
        
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
	 
//------------------------------------------------------------------------------------------------
// This Function  gets the" Fee status " in ALERT List   for "DashBoard" Module
//
// Author : Arvind Singh Rawat
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
	
public function getFeeStatus()
{
	global $sessionHandler;
	$query="SELECT feeHeadStudentId 
	FROM 
	fee_head_student fhs,fee_cycle fc
	WHERE 
	fhs.feeCycleId=fc.feeCycleId
	AND
	fhs.studentId='".$sessionHandler->getSessionVariable('StudentId')."'
	AND
	CURDATE() BETWEEN fc.fromDate AND fc.toDate
	";
	
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}	
	
	
	 
	 
//------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------

//////                        Functions used for Display Marks Module                  ///////////

//------------------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------------------


//------------------------------------------------------------------------------------------------
// This Function  gets the Comments List  for "Display Marks" Module
//
// Author : Arvind Singh Rawat
// Created on : 16-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

	public function getDisplayMarksSubjectClass() {
        global $sessionHandler;
		$query = "SELECT sub.subjectId, sub.subjectName
        FROM subject sub, subject_to_class subcls 
        WHERE 
		sub.subjectId = subcls.subjectId 
		AND
		 subcls.classId='". $sessionHandler->getSessionVariable('ClassId')."'";
	
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//------------------------------------------------------------------------------------------------
// //Funcitons gets the student Marks based on Internal or External test for "Display Marks" Module
//
// Author : Arvind Singh Rawat
// Created on : 16-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
	
	public function getStudentMarksClass($subjectId) {
		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		
        $query = "SELECT 
				  sub.subjectName,if(ttype.conductingAuthority='1','Internal','external') as type,ttype.testTypeName,sum(tm.maxMarks) as TotalMarks,SUM(tm.marksScored) as MarksObtained
				  FROM 
				  ".TEST_MARKS_TABLE." tm,".TEST_TABLE." tt,test_type ttype, subject sub 
				  WHERE
				   tt.testId=tm.testId 
				  AND
				    tm.studentId='".$sessionHandler->getSessionVariable('StudentId')."' 
				  AND
					 tm.subjectId='$subjectId' 
			      AND 
				  tt.testTypeId=ttype.testTypeId  
				  AND
				   tm.subjectId=sub.subjectId 
				  AND
				   ttype.instituteId=$instituteId 
				  GROUP BY conductingAuthority";
				
				
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
	
//------------------------------------------------------------------------------------------------
// This Function  gets the data from time table of student
//
// Author : Arvind Singh Rawat
// Created on : 25.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------    
      
     
 public function getStudentTimeTable ()
      {
           global $sessionHandler;
        $query = "SELECT tt.periodId, tt.daysOfWeek, p.periodNumber, gr.classId, s.studentId, sub.subjectAbbreviation, emp.employeeName, r.roomName 
FROM  ".TIME_TABLE_TABLE."  tt , period p, `group` gr, student s, subject sub, employee emp, room r
WHERE tt.periodId = p.periodId
AND tt.groupId = gr.groupId
AND gr.classId = s.classId
AND tt.subjectId=sub.subjectId
AND tt.employeeId=emp.employeeId
AND tt.roomId = r.roomId
AND s.studentId=".$studentId = $sessionHandler->getSessionVariable('StudentId')."";
return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");      
      }


  
}
?>

<?php 

//$History: ParentManager.inc.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:14p
//Updated in $/LeapCC/Model
//added code for multiple tables.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/17/08    Time: 4:13p
//Created in $/Leap/Source/Model
//
//*****************  Version 10  *****************
//User: Arvind       Date: 7/30/08    Time: 7:33p
//Updated in $/Leap/Source/Model
//modified the getCommentsListing and getTotalEvents functions
//
//*****************  Version 9  *****************
//User: Arvind       Date: 7/25/08    Time: 6:05p
//Updated in $/Leap/Source/Model
//added a function getStudentTimeTable()
//
//*****************  Version 8  *****************
//User: Arvind       Date: 7/17/08    Time: 6:39p
//Updated in $/Leap/Source/Model
//added session variable
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/17/08    Time: 6:31p
//Updated in $/Leap/Source/Model
//added session variable for
//studentId='".$sessionHandler->getSessionVariable('StudentId')."'
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/17/08    Time: 12:19p
//Updated in $/Leap/Source/Model
//Added Comments above functions
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/16/08    Time: 7:21p
//Updated in $/Leap/Source/Model
//added a new condition in getCommentsListing() function
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/16/08    Time: 5:29p
//Updated in $/Leap/Source/Model
//added new functions for dashboard of parent
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/16/08    Time: 12:38p
//Updated in $/Leap/Source/Model
//added session variable institute in functions
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/15/08    Time: 6:27p
//Updated in $/Leap/Source/Model
//Added new functions for "displayattendance" modules
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/14/08    Time: 6:04p
//Created in $/Leap/Source/Model
//added new files for parent module


?>