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
       $query = "SELECT 
                       n.noticeId, n.noticeSubject,n.noticeText
                 FROM 
                       notice n, notice_visible_to_role nr, role r
                 WHERE 
                        n.noticeId = nr.noticeId
                        AND r.roleId = nr.roleId
                        AND r.roleId=4
                        AND nr.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                        AND n.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
		                $conditions
                 GROUP BY n.noticeId
		";
		
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
 
//----------------------------------------------------------------------------------------
//  This Function  Gets the notices table fields for "DisplayNotices" Module
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------------- 

public function getClassMiscInfo($classId){
    $query="SELECT * FROM class WHERE classId=$classId";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getStudentClass($studentId){
    $query="SELECT classId FROM student WHERE studentId=$studentId";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}
      
 public function getNoticesList($conditions='', $limit = '', $orderBy=' ') {
    global $sessionHandler;
     
     
     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');
     $roleId=$sessionHandler->getSessionVariable('RoleId');
     $curDate=date('Y')."-".date('m')."-".date('d');
     
     $extraCondition='';
     $classArray=$this->getStudentClass($sessionHandler->getSessionVariable('StudentId'));
     $classId=$classArray[0]['classId'];
     //get university,degree and branchId of this class
     $classArray=$this->getClassMiscInfo($classId);
     if(is_array($classArray)>0 and count($classArray)>0){
            $extraCondition=' AND (
                                    (nvr.universityId IS NULL OR nvr.universityId='.$classArray[0]['universityId'].')
                                     AND
                                    (nvr.degreeId IS NULL OR nvr.degreeId='.$classArray[0]['degreeId'].')
                                     AND
                                    (nvr.branchId IS NULL OR nvr.branchId='.$classArray[0]['branchId'].')
                                   )';
     }
    
     $query="SELECT 
                    DISTINCT n.noticeId, 
                    n.noticeText,
                    n.noticeSubject,
                    n.visibleFromDate,
                    n.visibleToDate,
                    n.noticeAttachment,
                    n.downloadCount,
                    d.abbr,
                    d.departmentName ,
                    n.visibleMode
            FROM    
                    department d, notice n INNER JOIN notice_visible_to_role nvr ON  ( n.noticeId=nvr.noticeId $extraCondition )
                    AND isClass = CASE WHEN '1' THEN (SELECT 
                                                        DISTINCT 1 FROM notice_visible_to_class c 
                                                  WHERE 
                                                        n.noticeId=c.noticeId AND c.classId='$classId' LIMIT 0,1)  ELSE '0' END 
            WHERE    
                    nvr.roleId=$roleId          
                    AND nvr.instituteId=$instituteId 
                    AND n.instituteId=$instituteId 
                    AND nvr.sessionId=$sessionId 
                    AND n.departmentId = d.departmentId 
                    $conditions 
            GROUP BY 
                    n.noticeId
            UNION  
            SELECT 
                    DISTINCT  n.noticeId, 
                    n.noticeText,
                    n.noticeSubject,
                    n.visibleFromDate,
                    n.visibleToDate,
                    n.noticeAttachment,
                    n.downloadCount,
                    d.abbr,
                    d.departmentName,
                    n.visibleMode
              FROM  
                    department d, notice n INNER JOIN notice_visible_to_institute nvr ON (n.noticeId=nvr.noticeId) 
              WHERE        
                    nvr.noticeInstituteId=$instituteId 
                    AND n.departmentId = d.departmentId 
                    $conditions 
              GROUP BY 
                    n.noticeId
              ORDER BY 
                    $orderBy $limit " ;              
           
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

	$instituteId=$sessionHandler->getSessionVariable('InstituteId');
	$sessionId=$sessionHandler->getSessionVariable('SessionId');
	$roleId=$sessionHandler->getSessionVariable('RoleId');
	$curDate=date('Y')."-".date('m')."-".date('d');

        $extraCondition='';
        $classArray=$this->getStudentClass($sessionHandler->getSessionVariable('StudentId'));
        $classId=$classArray[0]['classId'];
        //get university,degree and branchId of this class
        $classArray=$this->getClassMiscInfo($classId);
        if(is_array($classArray)>0 and count($classArray)>0){
            $extraCondition=' AND (
                                    (nvr.universityId IS NULL OR nvr.universityId='.$classArray[0]['universityId'].')
                                     AND
                                    (nvr.degreeId IS NULL OR nvr.degreeId='.$classArray[0]['degreeId'].')
                                     AND
                                    (nvr.branchId IS NULL OR nvr.branchId='.$classArray[0]['branchId'].')
                                   )';
        }
     
        $query = "SELECT
                    COUNT(*) AS totalRecords
            FROM        
                    (SELECT 
                            DISTINCT n.noticeId, 
                            n.noticeText,
                            n.noticeSubject,
                            n.visibleFromDate,
                            n.visibleToDate,
                            n.noticeAttachment,
                            n.downloadCount,
                            d.abbr,
                            d.departmentName ,
                            n.visibleMode
                    FROM    
                            department d, notice n INNER JOIN notice_visible_to_role nvr ON  ( n.noticeId=nvr.noticeId $extraCondition ) 
                            AND isClass = CASE WHEN '1' THEN (SELECT 
                                                        DISTINCT 1 FROM notice_visible_to_class c 
                                                  WHERE 
                                                        n.noticeId=c.noticeId AND c.classId='$classId' LIMIT 0,1)  ELSE '0' END
                    WHERE    
                            nvr.roleId=$roleId          
                            AND nvr.instituteId=$instituteId 
                            AND n.instituteId=$instituteId 
                            AND nvr.sessionId=$sessionId 
                            AND n.departmentId = d.departmentId 
                            $conditions 
                    GROUP BY 
                            n.noticeId
                    UNION  
                    SELECT 
                            DISTINCT  n.noticeId, 
                            n.noticeText,
                            n.noticeSubject,
                            n.visibleFromDate,
                            n.visibleToDate,
                            n.noticeAttachment,
                            n.downloadCount,
                            d.abbr,
                            d.departmentName,
                            n.visibleMode
                      FROM  
                            department d, notice n INNER JOIN notice_visible_to_institute nvr ON (n.noticeId=nvr.noticeId) 
                      WHERE        
                            nvr.noticeInstituteId=$instituteId 
                            AND n.departmentId = d.departmentId 
                            $conditions 
                      GROUP BY 
                            n.noticeId ) AS tt";
     
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
       if($conditions=='') {
          $conditions = " tcd.commentId =tc.commentId AND tc.teacherId=emp.employeeId ";
       }
       else {
          $conditions .= " AND tcd.commentId =tc.commentId AND tc.teacherId=emp.employeeId ";
       }
       $query = "SELECT 
                     emp.employeeName,tc.subject,tc.commentId,tc.comments,tc.postedOn,
                     commentAttachment, visibleFromDate, visibleToDate    
                 FROM 
                     teacher_comment_detail tcd, teacher_comment tc, employee emp      
                 WHERE
                  $conditions ";

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
	 
     
    public function getCommentsList($conditions='', $limit = '', $orderBy=' tc.comments') {
       
       global $sessionHandler;
       
       $query = "SELECT 
                        emp.employeeName,tc.subject,tc.commentId,tc.comments,tc.postedOn, commentAttachment,
                        visibleFromDate, visibleToDate  
				FROM 
					    teacher_comment_detail tcd,teacher_comment tc,employee emp
				WHERE 
					    tcd.studentId='".$sessionHandler->getSessionVariable('StudentId')."' AND
                        tcd.receiverType='".$sessionHandler->getSessionVariable('ParentType')."' AND     
					    tcd.toParent='1' AND tcd.commentId =tc.commentId AND tc.teacherId=emp.employeeId AND 
                        tc.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' AND	
                        tc.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
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
          $query = "SELECT 
                          COUNT(*) AS totalRecords
					FROM 
					      teacher_comment_detail tcd,teacher_comment tc,employee emp
					WHERE 
					      tcd.studentId='".$sessionHandler->getSessionVariable('StudentId')."' AND 
                          tcd.receiverType='".$sessionHandler->getSessionVariable('ParentType')."' AND 
					      tcd.toParent='1' 	AND tcd.commentId =tc.commentId AND
					      tc.teacherId=emp.employeeId AND
					      tc.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' AND
					      tc.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
					$conditions ";
                    
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
        FROM `subject` sub, subject_to_class subcls,student s 
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
           
         $query="SELECT 
                        eventId,eventTitle,shortDescription,longDescription,startDate,endDate
                 FROM 
                        event  
                 WHERE  
                        instituteId=$instituteId AND sessionId=$sessionId AND 
                        roleIds LIKE '%~$roleId~%' 
                 $conditions   
                 ORDER BY $orderBy $limit ";   
     
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
       
       $query = "SELECT 
                        COUNT(*) AS totalRecords
                 FROM 
                        event
                 WHERE
                        instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' AND 
                        sessionId='".$sessionHandler->getSessionVariable('SessionId')."' AND
                        roleIds LIKE '%~$RoleIds~%'
                 $conditions ";
        
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
       $query = "SELECT tcd.studentId,tcd.visibleFromDate,tcd.visibleToDate,
	    emp.employeeName,tc.subject,tc.comments,tc.postedOn   
					FROM 
					teacher_comment_detail tcd,teacher_comment tc,employee emp
					WHERE 
					tcd.studentId='".$sessionHandler->getSessionVariable('StudentId')."' 
					AND 
					tcd.toParent='1'
					AND
					CURDATE() BETWEEN tcd.visibleFromDate AND tcd.visibleToDate
					AND 
					tcd.commentId =tc.commentId
					AND
					 tc.teacherId=emp.employeeId
					AND
					 tc.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
					AND
					tcd.dashboard='1'
					AND
					tc.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
					$conditions                  
		        ORDER BY $orderBy $limit" ;   
        
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
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	/*	$query = "SELECT sub.subjectId, sub.subjectName
        FROM `subject` sub, subject_to_class subcls 
        WHERE 
		sub.subjectId = subcls.subjectId 
		AND
		 subcls.classId='". $sessionHandler->getSessionVariable('ClassId')."'";   */
         $query="SELECT CONCAT( t.testAbbr, t.testIndex ) AS testName, s.studentId, s.firstName, IF( tt.conductingAuthority =1, 'Internal', 'External' ) AS examType, su.subjectName, su.subjectCode, (
tm.maxMarks
) AS totalMarks, IF( tm.isMemberOfClass =0, 'Not MOC', IF( isPresent =1, tm.marksScored, 'A' ) ) AS obtained
FROM ".TEST_TABLE." t, test_type tt, ".TEST_MARKS_TABLE." tm, student s, `subject` su
WHERE t.testTypeId = tt.testTypeId
AND t.testId = tm.testId
AND tm.studentId = s.studentId
AND tm.subjectId = su.subjectId
AND tm.studentId ='".$sessionHandler->getSessionVariable('StudentId')."'
AND tt.instituteId = $instituteId
ORDER BY tm.subjectId, tt.conductingAuthority, t.testId, tm.studentId
 ";
	
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
				  ".TEST_MARKS_TABLE." tm,".TEST_TABLE." tt,test_type ttype, `subject` sub 
				  WHERE
				   tt.testId=tm.testId 
				  AND
				    tm.studentId='".$sessionHandler->getSessionVariable('StudentId')."' 
				  AND
					 tm.subjectId='$subjectId' 
			      AND 
				  tt.testTypeId=ttype.testTypeId  
			      AND 
				  ttype.instituteId = $instituteId  
				  AND
				   tm.subjectId=sub.subjectId 
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
      
     
 public function getClassName ()
 {
      global $sessionHandler;
      $query = "SELECT  
                       c.classId,  
                       c.className,
                       SUBSTRING_INDEX(c.className,'-',-3) AS className1 
                FROM 
                       class c,student s 
                WHERE 
                       c.classId=s.classId
                       AND s.studentId=".$sessionHandler->getSessionVariable('StudentId')."
                       AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId');
                       
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
        $query = "SELECT tt.periodId, tt.daysOfWeek, p.periodNumber, gr.classId, s.studentId, sub.subjectAbbreviation,sub.subjectCode, emp.employeeName, r.roomName 
FROM  ".TIME_TABLE_TABLE." tt , period p, `group` gr, student s, `subject` sub, employee emp, room r
WHERE tt.periodId = p.periodId
AND tt.groupId = gr.groupId
AND gr.classId = s.classId
AND tt.subjectId=sub.subjectId
AND tt.employeeId=emp.employeeId
AND tt.roomId = r.roomId
AND s.studentId=".$sessionHandler->getSessionVariable('StudentId')."
AND tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')." ORDER BY p.periodNumber,tt.daysOfWeek

";

return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");      
      }


//------------------------------------------------------------------------------------------------
// This Function  gets the data from fee_receipt of student
//
// Author : Arvind
// Created on : 31.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------          
      
      
      public function getStudentFee($conditions='', $limit = '', $orderBy=' receiptNo') {
         global $sessionHandler;
        $query = "SELECT receiptNo,receiptDate,discountedFeePayable ,totalAmountPaid,receiptStatus,paymentInstrument,instrumentStatus 
        FROM fee_receipt
        WHERE studentId='".$sessionHandler->getSessionVariable('StudentId')."' 
        
        AND instituteId = '".$sessionHandler->getSessionVariable('InstituteId')."' 
		$conditions ORDER BY $orderBy $limit
		 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	public function getTotalFee($conditions='') {
         global $sessionHandler;
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM fee_receipt
        WHERE studentId=".$sessionHandler->getSessionVariable('StudentId')." 
        
        AND instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
		$conditions
		 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getFeeDetail() {
        global $sessionHandler;
        $query = "   SELECT 
                        DISTINCT(totalFeePayable), SUM( fine ) AS totalFine, SUM( totalAmountPaid ) AS totalAmountPaid, s.periodName
                        FROM fee_receipt fr, study_period s
                        WHERE s.studyPeriodId = fr.feeStudyPeriodId
                        AND fr.studentId=".$sessionHandler->getSessionVariable('StudentId')." 
                        GROUP BY fr.feeCycleId,fr.feeStudyPeriodId
                 ";    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
  

  ////NEW QUERY FILES FOR TASK MODULE /////////

		//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A DOCUMENT
//
// Author :Jaineesh 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addTask($reminderOptions) {
        global $REQUEST_DATA;
		global $sessionHandler;

		$userId = $sessionHandler->getSessionVariable('UserId');
        
     $query="INSERT INTO task (title,shortDesc,dueDate,reminderOptions,daysPrior,status,userId) 
      VALUES('".add_slashes($REQUEST_DATA['title'])."','".add_slashes($REQUEST_DATA['shortDesc'])."','".$REQUEST_DATA['dueDate']."','".$reminderOptions."','".$REQUEST_DATA['daysPrior']."','".$REQUEST_DATA['status']."','".$userId."')";
      
      return SystemDatabaseManager::getInstance()->executeUpdate($query);     
        
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A DOCUMENT
//
//$id:documentId
// Author :Jaineesh 
// Created on : (28.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editTask($id,$reminderOptions) {
        global $REQUEST_DATA;
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
        
   $query="UPDATE task SET	title ='".add_slashes($REQUEST_DATA['title'])."',
							shortDesc ='".add_slashes($REQUEST_DATA['shortDesc'])."',
							dueDate = '".$REQUEST_DATA['dueDate']."',
							daysPrior = '".$REQUEST_DATA['daysPrior']."',
							reminderOptions = '".$reminderOptions."',	
							status = '".$REQUEST_DATA['status']."'
							WHERE taskId=".$id."
							AND userId=".$userId;
       
       return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    } 

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Document
//
//$publishId :publishId   of document
// Author :Jaineesh 
// Created on : (05.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteTask($Id) {
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
        $query = "	DELETE 
					FROM task
					WHERE userId = $userId 	
					AND taskId=$Id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

 
	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TASK
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (18.3.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getTask($conditions='') {
		
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');

    $query = "	SELECT	*
				FROM	task
				WHERE	userId = $userId
						$conditions
						ORDER BY dueDate desc";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TASK LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (19.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
     public function getTaskList($filter, $orderBy='',$limit = '') {
      
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');

     $query = "	SELECT 
							*,
							( date_sub( dueDate, INTERVAL daysPrior DAY ) ) AS Result
					FROM	task
					WHERE	userId = $userId
							$filter
							ORDER BY $orderBy 
							$limit";
        //echo $query;
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF DOCUMENT
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalTask($filter) {
         global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
   $query = "	SELECT	COUNT(*) AS totalRecords 
				FROM	task
				WHERE	userId = $userId
							$filter
							$conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF TASKS
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTaskMessages($limit = 'LIMIT 0,5') {
         global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
$query = "	SELECT	*,
					( date_sub( dueDate, INTERVAL daysPrior DAY ) ) AS Result
			FROM	task
			WHERE	userId = $userId
			ORDER BY Result ASC
			$limit
				";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A STATUS
//
//$id:documentId
// Author :Jaineesh 
// Created on : (28.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editTaskStatus($id,$status) {
        global $REQUEST_DATA;
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');

		if($status == 0) {
        
			$query="UPDATE task SET	status = 1
									WHERE taskId = ".$id."
									AND userId = ".$userId;
		}
		else {
			$query="UPDATE task SET	status = 0
									WHERE taskId = ".$id."
									AND userId = ".$userId;
		}
       
       return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    }

     public function getAdminMessages1 ($orderBy="dated DESC",$limit='LIMIT 0,5')
     {
        global $sessionHandler;
        $studentId = $sessionHandler->getSessionVariable('StudentId');
        
        $query = "SELECT 
                        am.messageId AS messageId, message, dated, userName, am.messageFile
                  FROM 
                        admin_messages am, user u
                  WHERE 
                        FIND_IN_SET('$studentId',REPLACE(receiverIds,\"~\",\",\")) 
                        AND am.receiverType='".$sessionHandler->getSessionVariable('ParentType')."' 
                        AND am.messageType='Dashboard' 
                        AND am.senderId=u.userId
                        AND am.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                        AND am.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
                        AND am.visibleToDate >= curdate()
                  ORDER BY 
                        $orderBy $limit" ;
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");      
     }

     
     public function getTotalAdminMessages ($condition='')
     {
        global $sessionHandler;
        
        $studentId = $sessionHandler->getSessionVariable('StudentId');
        $query = "SELECT 
                         COUNT(*) AS totalRecords 
                  FROM 
                        admin_messages am, user u
                  WHERE 
                        FIND_IN_SET('$studentId',REPLACE(receiverIds,\"~\",\",\")) 
                        AND am.receiverType='".$sessionHandler->getSessionVariable('ParentType')."'
                        AND am.messageType='Dashboard' 
                        AND am.senderId=u.userId
                        AND am.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                        AND sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
                        $condition";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");      
     }
    
     public function getAdminMessages($condition='',$orderBy='am.visibleFromDate DESC',$limit='')
     {                                                                   
        global $sessionHandler;
        $studentId = $sessionHandler->getSessionVariable('StudentId');
        $query = "SELECT 
                        am.messageId, am.message, am.subject, u.userName, am.visibleFromDate, am.visibleToDate, am.messageFile 
                 FROM admin_messages am, user u
        WHERE FIND_IN_SET('$studentId',REPLACE(receiverIds,\"~\",\",\")) 
        AND am.messageType='Dashboard' 
        AND am.senderId=u.userId
        AND am.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
        AND sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
        AND am.receiverType='".$sessionHandler->getSessionVariable('ParentType')."'
        $condition ORDER BY $orderBy $limit";
        
        // AND visibleToDate <= curdate()
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");      
     }
  
   public function getCommentsListing1($conditions='', $orderBy='tcd.visibleToDate',$limit='LIMIT 0,5') {
     
        global $sessionHandler;
        $query = "SELECT tc.commentId, tc.comments,e.employeeName,tcd.visibleFromDate,tcd.visibleToDate 
        FROM    
        teacher_comment_detail tcd,employee e, teacher_comment tc
        WHERE
        tc.teacherId=e.employeeId
        AND
        tcd.studentId='".$sessionHandler->getSessionVariable('StudentId')."' AND
        tcd.receiverType='".$sessionHandler->getSessionVariable('ParentType')."' AND 
        e.isTeaching='1' AND tcd.dashboard='1' AND tcd.toStudent='0' AND tcd.toParent='1' AND tc.commentId=tcd.commentId
        AND tc.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
        AND tc.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
        AND (curdate() BETWEEN tcd.visibleFromDate AND tcd.visibleToDate )
        $conditions ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
  
  
          //------------------------------------------------------------------------------------------------
// This Function  gets the data of student group detail
//
// Author : Jaineesh
// Created on : 25.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------ 

    public function getStudentGroup($studentId,$classId,$limit,$orderBy) {
        
        if ($classId != "" and $classId != "0") {
            $classCond =" AND sg.classId =".add_slashes($classId);
           }
    
        global $sessionHandler;
    
        $query = "SELECT
                         DISTINCT
                                gr.groupName,   
                                sub.subjectName,
                                gt.groupTypeName, 
                                gt.groupTypeCode,
                                sub.subjectCode,
                                SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-1) AS className
                    FROM        `student_groups` sg,
                                `group` gr, 
                                `class` cls ,
                                `subject_to_class` stc,
                                `subject` sub,
                                `group_type` gt
                    WHERE        cls.classId = sg.classId 
                    AND            stc.subjectId = sub.subjectId 
                    AND            stc.classId = cls.classId
                    AND            sg.groupId = gr.groupId
                    AND            gr.groupTypeId = gt.groupTypeId
                    AND            sg.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
                    AND            sg.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                     AND        sg.studentId=$studentId
                                $classCond ORDER BY  $orderBy $limit";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

            //------------------------------------------------------------------------------------------------
// This Function  gets the data of student group detail
//
// Author : Jaineesh
// Created on : 25.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------ 

    public function getTotalStudentGroup($studentId,$classId,$orderBy) {
        
        if ($classId != "" and $classId != "0") {
            $classCond =" AND sg.classId =".add_slashes($classId);
        }
    
       global $sessionHandler;
     
       $query = "    SELECT
                                COUNT(*) AS totalRecords       
                    FROM        `student_groups` sg,
                                `group` gr, 
                                `class` cls ,
                                `subject_to_class` stc,
                                `subject` sub,
                                `group_type` gt
                    WHERE        cls.classId = sg.classId 
                                AND  stc.subjectId = sub.subjectId 
                                AND  stc.classId = cls.classId
                                AND  sg.groupId = gr.groupId
                                AND  gr.groupTypeId = gt.groupTypeId
                                AND  sg.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
                                AND  sg.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                                AND  sg.studentId=$studentId
                                $classCond 
                     ORDER BY $orderBy ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

   
   public function getStudentTotalFeesClass($conditions='') {
     
        $query = "SELECT 
                         COUNT(*) as totalRecords
                  FROM 
                         fee_receipt 
                  $conditions";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getStudentFeesClass($conditions='',$orderBy=' receiptNo',$limit='') {
     
        $query = "SELECT f.receiptNo, DATE_FORMAT(f.receiptDate,'%d-%b-%Y') AS receiptDate,
                         f.totalFeePayable,f.discountedFeePayable,f.totalAmountPaid,f.receiptStatus,
                         f.paymentInstrument,f.instrumentStatus,
                         (SELECT periodName from study_period s WHERE s.studyPeriodId = f.feeStudyPeriodId)  AS periodName
                  FROM   
                         fee_receipt f
                  $conditions
                  ORDER BY $orderBy $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
  
}
?>

<?php 

//$History: ParentManager.inc.php $
//
//*****************  Version 16  *****************
//User: Parveen      Date: 9/24/09    Time: 12:29p
//Updated in $/LeapCC/Model/Parent
//getStudentFeesClass, getStudentTotalFeesClass function added
//
//*****************  Version 15  *****************
//User: Parveen      Date: 9/24/09    Time: 10:57a
//Updated in $/LeapCC/Model/Parent
//alignment & condition format updated
//
//*****************  Version 14  *****************
//User: Parveen      Date: 9/23/09    Time: 4:42p
//Updated in $/LeapCC/Model/Parent
//getNoticesList, getTotalNotices function roleId check updated
//
//*****************  Version 13  *****************
//User: Parveen      Date: 9/10/09    Time: 2:43p
//Updated in $/LeapCC/Model/Parent
//getAdminMessages, getCommentsList
// function parentType updated
//issue fix 1378, 1316, 1310, 1309 
//
//*****************  Version 12  *****************
//User: Parveen      Date: 9/04/09    Time: 12:01p
//Updated in $/LeapCC/Model/Parent
//getAdminMessages condition updated (group by clause remove)
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 9/01/09    Time: 3:05p
//Updated in $/LeapCC/Model/Parent
//modified in query during insertion of task
//
//*****************  Version 10  *****************
//User: Parveen      Date: 8/28/09    Time: 5:03p
//Updated in $/LeapCC/Model/Parent
//issue fix format & conditions & alignment updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 8/27/09    Time: 5:16p
//Updated in $/LeapCC/Model/Parent
//getAdminMessages function instituteId check updated
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/26/09    Time: 6:47p
//Updated in $/LeapCC/Model/Parent
//Gurkeerat: Resolved issues regarding 1226 and 1227
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:14p
//Updated in $/LeapCC/Model/Parent
//added code for multiple tables.
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/19/09    Time: 3:37p
//Updated in $/LeapCC/Model/Parent
//Gurkeerat: updated function getAdminMessages1
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/18/09    Time: 6:22p
//Updated in $/LeapCC/Model/Parent
//formating, validations & conditions updated
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model/Parent
//changed queries to add instituteId
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/11/09    Time: 5:10p
//Updated in $/LeapCC/Model/Parent
//getComments, getCommentsList Query Updated
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/21/09    Time: 1:30p
//Updated in $/LeapCC/Model/Parent
//put the task files in parent module
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model/Parent
//
//*****************  Version 15  *****************
//User: Arvind       Date: 10/06/08   Time: 6:26p
//Updated in $/Leap/Source/Model/Parent
//added the Download option in notice
//
//*****************  Version 14  *****************
//User: Arvind       Date: 9/24/08    Time: 4:38p
//Updated in $/Leap/Source/Model/Parent
//added condition on getEvents ( events should be visible from 10 days
//before the events)
//
//*****************  Version 13  *****************
//User: Arvind       Date: 9/17/08    Time: 9:32p
//Updated in $/Leap/Source/Model/Parent
//modiifie dthe events query
//
//*****************  Version 11  *****************
//User: Arvind       Date: 9/12/08    Time: 2:34p
//Updated in $/Leap/Source/Model/Parent
//modified functions for parents
//
//*****************  Version 9  *****************
//User: Arvind       Date: 9/06/08    Time: 3:38p
//Updated in $/Leap/Source/Model/Parent
//no change
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/23/08    Time: 11:53a
//Updated in $/Leap/Source/Model/Parent
//added a field in time table query
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/13/08    Time: 6:08p
//Updated in $/Leap/Source/Model/Parent
//added a new function getClassName()
//
//*****************  Version 6  *****************
//User: Arvind       Date: 8/12/08    Time: 6:49p
//Updated in $/Leap/Source/Model/Parent
//modified the teacher comments queries
//
//*****************  Version 5  *****************
//User: Arvind       Date: 8/09/08    Time: 4:22p
//Updated in $/Leap/Source/Model/Parent
//added new function feedetail in display
//
//*****************  Version 4  *****************
//User: Arvind       Date: 8/06/08    Time: 5:32p
//Updated in $/Leap/Source/Model/Parent
//modified query  for display marks 
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/02/08    Time: 2:06p
//Updated in $/Leap/Source/Model/Parent
//modified getattendance query
//
//*****************  Version 2  *****************
//User: Arvind       Date: 8/02/08    Time: 1:40p
//Updated in $/Leap/Source/Model/Parent
//modified the query of  notices module
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/31/08    Time: 6:39p
//Created in $/Leap/Source/Model/Parent
//checkin into a different folder
//
//*****************  Version 12  *****************
//User: Arvind       Date: 7/31/08    Time: 3:41p
//Updated in $/Leap/Source/Model
//modified the timetable query
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
