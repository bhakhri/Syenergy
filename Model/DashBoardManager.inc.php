
<?php
//---------------------------------------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "teacher" module
//
//
// Author :Rajeev Aggarwal 
// Created on : (12.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId 

class DashBoardManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CityManager" CLASS
//
// Author :Rajeev Aggarwal 
// Created on : (13.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CityManager" CLASS
//
// Author :Rajeev Aggarwal 
// Created on : (12.07.2008)
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
//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of student's fees due
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (01.09.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------  
public function getAllFeesDue($conditions='',$limit = '', $orderBy=' previousDues DESC',$classId){
 
	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query = "SELECT rollNo,receiptNo,DATE_FORMAT(receiptDate, '%d-%b-%y') as receiptDate,feeReceiptId,CONCAT(stu.firstName,' ',stu.lastName) as fullName,previousDues,fc.cycleName,fr.totalAmountPaid as totalAmountPaid,
	fr.discountedFeePayable as discountedFeePayable
			  FROM `fee_receipt` fr, `student` stu ,`class` cls,fee_cycle fc
			  WHERE feeReceiptId
              IN 
			  (
				SELECT max( feeReceiptId ) FROM fee_receipt GROUP BY studentId
			  ) 
			  AND 
			  stu.studentId=fr.studentId AND 
			  stu.classId = cls.classId AND 
			  previousDues>0 AND 
			  cls.instituteId = $instituteId AND 
			  cls.sessionId = $sessionId and 
			  fr.feeCycleId = fc.feeCycleId
			  $conditions
			  ORDER BY $orderBy $limit 
		" ;   

		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}
    
//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute notices for a teacher
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------  

public function getUserBranch($userId){
    $query="SELECT branchId FROM employee WHERE userId=$userId";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}
public function getNoticeList($conditions='', $limit = '', $orderBy=' noticeText'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    	$roleId=$sessionHandler->getSessionVariable('RoleId');
    	$userId=$sessionHandler->getSessionVariable('UserId');
    
    $extraCondition='';
    if($roleId!=1 and roleId!=5){ //1 : admin,5:management
      //check if this user has branchId
      $branchArray=$this->getUserBranch($userId);
      if($branchArray[0]['branchId']!=''){
       $extraCondition=' AND ( nvr.branchId='.$branchArray[0]['branchId'].' OR nvr.branchId IS NULL )';  
      }
      $extraCondition .=' AND nvr.roleId='.$roleId; 
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
            WHERE		
                    nvr.instituteId=$instituteId 
                    AND n.instituteId=$instituteId 
                    AND	nvr.sessionId=$sessionId 
                    AND	n.departmentId = d.departmentId 
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


public function getNoticeListCount($conditions=''){

    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        $userId=$sessionHandler->getSessionVariable('UserId');
    
    $extraCondition='';
    if($roleId!=1 and roleId!=5){ //1 : admin,5:management
      //check if this user has branchId
      $branchArray=$this->getUserBranch($userId);
      if($branchArray[0]['branchId']!=''){
       $extraCondition=' AND ( nvr.branchId='.$branchArray[0]['branchId'].' OR nvr.branchId IS NULL )';  
      }
      $extraCondition .=' AND nvr.roleId='.$roleId; 
    }
    
    $query="SELECT
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
                WHERE        
                        nvr.instituteId=$instituteId 
                        AND n.instituteId=$instituteId 
                        AND    nvr.sessionId=$sessionId 
                        AND    n.departmentId = d.departmentId 
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
                        n.noticeId) AS tt" ;   
         
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}
	//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute events for a teacher
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------  
public function getEventList($conditions='', $limit = '', $orderBy=' endDate DESC'){

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $sessionId=$sessionHandler->getSessionVariable('SessionId');
 $roleId=$sessionHandler->getSessionVariable('RoleId');
 $roleConditions='';
 
 if($roleId!=1){
    $roleConditions='AND e.roleIds LIKE "%~'.$roleId.'~%"';
 }
    
 $query="SELECT 
                e.eventId,e.eventTitle,e.shortDescription,e.longDescription,e.startDate,e.endDate
         FROM 
                event e 
         WHERE 
                e.instituteId=$instituteId 
                AND e.sessionId=$sessionId 
                AND DATE_SUB(startDate,INTERVAL ".EVENT_DAY_PRIOR." DAY)<=CURDATE() AND endDate>=CURDATE()
                $roleConditions
                $conditions 
                ORDER BY $orderBy 
                $limit " ;   
 //echo $query;       
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
}
//----------------------------------------------------------------------------------------------------
//funciton return records for missed attendance report for all classes, all subjects, all groups

// Author :Ajinder Singh
// Created on : 29-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getAllClassMissedAttendanceReport($tillDate, $sortField, $sortOrderBy) {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$query = "	SELECT 
					REPLACE( SUBSTRING_INDEX( b.className, '-' , -3 ) , '-', ' ' ) AS className, 
					c.subjectCode, 
					d.groupName, 
					e.employeeName, 
					MAX(toDate) AS testDate,
					DATE_FORMAT(max(toDate), '%d-%b-%y') AS toDate
					FROM attendance a, class b, subject c, `group` d, employee e 
					WHERE a.classId = b.classId 
					AND a.subjectId = c.subjectId 
					AND a.groupId = d.groupId 
					AND a.employeeId = e.employeeId 
					AND b.instituteId = '$instituteId' 
					AND b.sessionId = '$sessionId'
					GROUP BY a.classId, a.subjectId, a.groupId, a.employeeId having testDate < '$tillDate' 
					ORDER BY $sortField $sortOrderBy";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

	}
//--------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute notices for a teacher
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------  
public function getTotalNotice($conditions='', $limit = '', $orderBy=' n.noticeText'){

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $sessionId=$sessionHandler->getSessionVariable('SessionId');
    
 $query="SELECT COUNT(*) AS totalRecords
       FROM notice n, notice_visible_to_role nvr 
       WHERE  nvr.instituteId=$instituteId AND nvr.sessionId=$sessionId
         AND n.noticeId=nvr.noticeId 
        $conditions group by noticeSubject" ;    
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
}
//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute events 
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.04.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getTotalEvent($conditions='', $limit = '', $orderBy=' e.eventTitle'){

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $sessionId=$sessionHandler->getSessionVariable('SessionId');
    
 $query="SELECT COUNT(*) AS totalRecords
        FROM event e 
        WHERE 
		e.instituteId=$instituteId AND e.sessionId=$sessionId
        $conditions  ORDER BY $orderBy $limit " ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
}

//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetch institute events 
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (04.09.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getEvent($conditions='', $limit = '', $orderBy=' e.eventTitle'){

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $sessionId=$sessionHandler->getSessionVariable('SessionId');
    
 $query="SELECT e.eventTitle,e.longDescription
        FROM event e 
        WHERE 
		e.instituteId=$instituteId AND e.sessionId=$sessionId
        $conditions  ORDER BY $orderBy $limit " ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
}

//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR employee activity 
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (06.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getEmployeeActivityList($conditions=''){

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $query="SELECT 
              COUNT( DISTINCT (a.userId) ) AS  totalCount,
              DATE_FORMAT(a.dateTimeIn,'%Y-%m-%d') as loggedInTime,
              DATE_FORMAT(a.dateTimeIn,'%d-%b-%y') as loggedTime 
		  FROM 
		       user_log a, user b
		  WHERE 
		      a.dateTimeIn >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
		      AND a.userId = b.userId AND b.instituteId=$instituteId
              $conditions
		GROUP BY 
              loggedInTime 
		ORDER BY 
              loggedInTime DESC" ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
}
//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR AVERAGE ATTENDANCE
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getAverageMarks($conditions=''){

global $sessionHandler;
$query	="SELECT 
		subjectCode, ROUND((SUM(marksScored)/SUM(maxMarks)*100),2) as percentage 
		
		FROM 
		".TEST_TRANSFERRED_MARKS_TABLE." ttm, subject sub, class cls
		
		WHERE 
		ttm.subjectId = sub.subjectId AND
		cls.classId = ttm.classId AND
		cls.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')." AND
		cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND
		cls.isActive = 1

		GROUP BY ttm.subjectId 
		ORDER BY subjectCode" ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
}

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentCityList($conditions='') {
    
       global $sessionHandler;
        $query = "SELECT count( * ) as totalCount, cty.cityId, cityName
				 FROM `student` sc, city cty, class cls
				 WHERE 
				 sc.corrCityId = cty.cityId AND
				 sc.classId = cls.classId AND
				 $conditions
				 cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY sc.corrCityId ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the branch list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentBranchList($conditions='') {
    
       global $sessionHandler;
       $query = "SELECT br.branchId,count( * ) as totalCount, br.branchName, br.branchCode 
				  FROM `student` sc, `class` cls,`branch` br 
				  WHERE 
				  sc.classId = cls.classId AND 
				  cls.branchId = br.branchId 
				  $conditions
				  AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				  cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY br.branchId ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the Gender list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentGenderList($conditions='') {

	   global $sessionHandler;
       $query = "SELECT count( * ) as totalCount
				  FROM student sc, class cls
				  WHERE 
				  sc.classId = cls.classId  
				  $conditions
				  AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				  cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY sc.studentGender ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student study period list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (18.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentStudyPeriodList($conditions='') {
    
       global $sessionHandler;
        $query = "SELECT count(*)  as totalCount,sp.studyPeriodId ,sp.periodName 
				 FROM student sc, class cls, study_period sp
				 WHERE 
				 sc.classId = cls.classId AND  
				 sp.studyPeriodId =cls.studyPeriodId 
				 $conditions
				 AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY sp.studyPeriodId ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the branch wise report for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentBranchWiseList($conditions='',$orderBy='') {
    
       global $sessionHandler;
       $query = "SELECT firstName,lastName,studentMobileNo,studentEmail,studentGender,DATE_FORMAT(dateOfAdmission, '%d-%b-%y') as dateOfAdmission,DATE_FORMAT(dateOfBirth, '%d-%b-%y') as dateOfBirth,fatherTitle,fatherName,branchName 
				  FROM `student` sc, `class` cls,`branch` br
				  WHERE 
				  sc.classId = cls.classId AND 
				  cls.branchId = br.branchId 
				  
				  $conditions
				  AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				  cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" ORDER BY $orderBy ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the degree list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentDegreeList($conditions='') {
    
       global $sessionHandler;
       $query = "SELECT count( * ) as totalCount, deg.degreeId, deg.degreeName 
				  FROM `student` sc, `class` cls,`degree` deg
				  WHERE 
				  sc.classId = cls.classId AND 
				  cls.degreeId = deg.degreeId AND
				  $conditions
				  cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				  cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY deg.degreeId ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the hostel list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentHostelList($conditions='') {
    
       global $sessionHandler;
       $query = "SELECT count( * ) as totalCount
				  FROM student sc, class cls
				  WHERE 
				  sc.classId = cls.classId  
				  $conditions
				  AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				  cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY sc.hostelId ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the hostel detail list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentHostelDetailList($conditions='') {

	   global $sessionHandler;
       $query = "SELECT count( * ) as totalCount,hs.hostelId,hs.hostelName
				  FROM student sc, hostel hs, class cls
				  WHERE 
				  sc.hostelId = hs.hostelId  AND
				  sc.classId = cls.classId  
				  $conditions
				  AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				  cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY hs.hostelId ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying event details for a particular event
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (29.9.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------   
public function getEventDetail($eventId){
    
    $query="SELECT e.eventId,e.eventTitle,e.shortDescription,e.longDescription,e.startDate,e.endDate
           FROM event e
           WHERE e.eventId=".$eventId;

      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
} 

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying notice details for a particular notice
//
//$conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (29.9.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------   
public function getNoticeDetail($noticeId){
    
    $query="SELECT n.noticeId,n.noticeSubject,n.downloadCount,n.noticeText,n.visibleFromDate,n.visibleToDate,d.departmentName,d.abbr
           FROM notice n,department d
           WHERE n.departmentId=d.departmentId AND n.noticeId= '".$noticeId."'";
     
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
}

//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR AVERAGE ATTENDANCE
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getAverageAttendance($conditions=''){
global $sessionHandler;
 $query	="SELECT 
		 sub.subjectCode,
		 SUM(IF(a.isMemberOfClass = 0,0, IF(b.attendanceCodePercentage IS NULL, a.lectureDelivered, 1))) AS lectureDelivered,
		 ROUND(SUM(IF(a.isMemberOfClass = 0,0, if(b.attendanceCodePercentage IS NULL, a.lectureAttended, 
		 b.attendanceCodePercentage/100))),2) as lectureAttended,
		 ROUND(SUM(IF(a.isMemberOfClass = 0,0, if(b.attendanceCodePercentage IS NULL, a.lectureAttended, 
		 b.attendanceCodePercentage/100))) / SUM(IF(a.isMemberOfClass = 0,0, IF(b.attendanceCodePercentage IS NULL, a.lectureDelivered, 1)))*100,2) as percentage
		 
		 FROM 
		 subject_to_class stc,subject sub,student c, class d,  " .ATTENDANCE_TABLE." a 
		 LEFT JOIN		
		 
		 attendance_code b ON (a.attendanceCodeId = b.attendanceCodeId AND
		 b.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
		 WHERE
		 a.studentId = c.studentId AND
		 a.classId=c.classId AND
		 c.classId=d.classId AND
		 d.isActive = 1 AND
		 d.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND
		 d.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')." AND
		 stc.subjectId = sub.subjectId AND
		 a.subjectId = sub.subjectId 
		 $conditions
		 GROUP BY a.subjectId 
		 ORDER BY subjectCode ASC" ;   

		 $query = "select 'aaa' as subjectCode, 10  as lectureDelivered, 8 as lectureAttended, 80 as percentage";
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
 
}


//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR AVERAGE ATTENDANCE
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getDistinctSubject($conditions=''){

global $sessionHandler;
$query	="SELECT 
		 sub.subjectId,
		 sub.subjectCode 

		 FROM 
		 subject_to_class stc, subject sub
		 
		 WHERE 
		 sub.subjectId = stc.subjectId
		 $conditions
		 
		 ORDER BY subjectCode" ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
}

//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR AVERAGE ATTENDANCE
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getDistinctTestType($conditions=''){

$query	="SELECT 
		testTypeCategoryId 	,
		testTypeName 
		$conditions
		FROM `test_type_category` ttc  " ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
        
   
}

//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR AVERAGE ATTENDANCE
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getCountTest($subjectId,$testypeCode,$classId){

global $sessionHandler;

	$query	="SELECT sct.testId,sct.testTopic,sub.subjectCode,stm.maxMarks,sct.testIndex,emp.employeeName,sct.testDate,ttc.testTypeName,gr.groupShort
			FROM ".TEST_TABLE." sct, ".TEST_MARKS_TABLE." stm,`subject` as sub, `employee` emp,`test_type_category` ttc,`group` gr
			WHERE sct.testId = stm.testId
			AND sub.subjectId= stm.subjectId
			AND gr.groupId= sct.groupId
			AND emp.employeeId = sct.employeeId
			AND sct.classId =$classId
			AND ttc.testTypeCategoryId =$testypeCode
			AND ttc.testTypeCategoryId = sct.testTypeCategoryId
			AND stm.subjectId =$subjectId
			AND sct.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." 
			AND sct.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')." 
			GROUP BY sct.testId
			ORDER BY gr.groupShort DESC";
 
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
}

//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR AVERAGE ATTENDANCE
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getCountTest1($subjectId,$testypeCode,$classId){

global $sessionHandler;

	$query	="SELECT MAX(sct.testIndex)  as totalRecords
			FROM ".TEST_TABLE." sct, ".TEST_MARKS_TABLE." stm,`test_type_category` ttc
			WHERE sct.testId = stm.testId
			 
			AND sct.classId =$classId
			AND ttc.testTypeCategoryId =$testypeCode
			AND ttc.testTypeCategoryId = sct.testTypeCategoryId
			AND stm.subjectId =$subjectId
			AND sct.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." 
			AND sct.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
			GROUP BY ttc.testTypeCategoryId";

  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
}
//This is used to get the list of tests of a particular class
public function getCountTests($subjectId,$testypeCode,$classId){ 
	global $sessionHandler;
	$query = "SELECT * 
			  FROM ".TEST_TABLE." sct
			  WHERE 
                    classId ='$classId'
			        AND testTypeCategoryId =$testypeCode
			        AND subjectId =$subjectId
			        AND sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
			        AND instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
			        GROUP BY sct.testId";
 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
}
//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR AVERAGE ATTENDANCE
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getTestType($conditions=''){

global $sessionHandler;
$query	="SELECT 
		 COUNT(*) as totalRecords,testTypeCode,stc.subjectId,sub.subjectCode 

		 FROM 
		 sc_test sct, subject_to_class stc, subject sub, test_type tt 
		 
		 WHERE 
		 stc.subjectId = sct.subjectId AND 
		 sct.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND
		 sct.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')." AND
		 tt.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')." AND
		 sct.subjectId = sub.subjectId AND 
		 tt.testTypeId = sct.testTypeId 
		 $conditions

		 GROUP BY stc.subjectId,testTypeCode
		 
		 ORDER BY testTypeCode" ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
}

 //----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR employee activity 
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (06.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getUserLogActivityList($conditions='',$orderBy=' userName,usr.roleId,dateTimeIn DESC'){

//select distinct(userId),DATE_FORMAT(dateTimeIn,'%Y-%m-%d') as loggedInTime  from user_log 

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $query	="SELECT 
		 DISTINCT(usr.userId),userName,roleName,DATE_FORMAT(dateTimeIn,'%Y-%m-%d') as loggedInTime,DATE_FORMAT(MAX(dateTimeIn),'%d-%b-%y: %r') as dateTimeIn,IF(usr.roleId=4,(SELECT firstName from student where userId=usr.userId LIMIT 0,1),(IF(usr.roleId=3,'Parent',(SELECT IF( e.employeeName IS NULL , us.userName, e.employeeName ) AS userName
FROM user us
LEFT JOIN employee e ON e.userId = us.userId where us.userId = usr.userId limit 0,1))))  as roleUserName 

		FROM 
		`user_log` ul,`user` usr ,`role` ro 

		WHERE 
		DATE_FORMAT(dateTimeIn,'%Y-%m-%d') = '$conditions' AND 
		ul.userId = usr.userId AND
		usr.instituteId = $instituteId AND
		usr.roleId = ro.roleId
		group by loggedInTime,userId
		ORDER BY $orderBy" ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
} 

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student batch list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (18.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentBatchList($conditions='') {
    
       global $sessionHandler;
        $query = "SELECT count(*)  as totalCount,br.batchId,br.batchName 
				 FROM student sc, class cls, batch br
				 WHERE 
				 sc.classId = cls.classId AND  
				 br.batchId =cls.batchId 
				 $conditions
				 AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY br.batchId ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student Quota list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (18.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentQuotaList($conditions='') {
    
       global $sessionHandler;
        $query = "SELECT count( * ) as totalCount, qut.quotaId,quotaName, quotaAbbr
				 FROM `student` sc, `quota` qut, `class` cls
				 WHERE 
				 qut.quotaId = sc.quotaId AND
				 sc.classId = cls.classId AND
				 $conditions
				 cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY sc.quotaId";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student state list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (17.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentStateList($conditions='') {
    
       global $sessionHandler;
        $query = "SELECT count( * ) as totalCount, sta.stateId, stateName
				 FROM `student` sc, `states` sta, `class` cls
				 WHERE 
				 sc.corrStateId = sta.stateId AND
				 sc.classId = cls.classId AND
				 $conditions
				 cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY sc.corrStateId ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
	//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student Nationality list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (18.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentNationalityList($conditions='') {
    
       global $sessionHandler;
        $query = "SELECT  
				 COUNT(*)  as totalCount, cnt.countryId ,cnt.countryName
				 FROM `class` cls, `student` sc,`countries` cnt 
				 
				 WHERE 
				 sc.classId = cls.classId AND
				 sc.NationalityId =cnt.countryId 
				 $conditions
				 AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY cnt.countryName ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student bus route list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (17.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentBusRouteList($conditions='') {
    
       global $sessionHandler;
        $query = "SELECT count( * ) as totalCount, br.busRouteId,routeName,routeCode
				 FROM `student` sc, `bus_route` br, `class` cls
				 WHERE 
				 sc.busRouteId = br.busRouteId AND
				 sc.classId = cls.classId AND
				 $conditions
				 cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY br.busRouteId";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student bus stop list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (17.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentBusStopList($conditions='') {
    
       global $sessionHandler;
        $query = "SELECT count( * ) as totalCount, bs.busStopId,stopName, stopAbbr
				 FROM `student` sc, `bus_stop` bs, `class` cls
				 WHERE 
				 sc.busStopId = bs.busStopId AND
				 sc.classId = cls.classId AND
				 $conditions
				 cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY bs.busStopId";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student study period list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (18.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentInstituteList($conditions='') {
    
       global $sessionHandler;
        $query = "SELECT COUNT(*) as totalCount,inst.instituteId,inst.instituteName 
				 FROM `student` sc, `class` cls, `institute` inst
				 WHERE 
				 sc.classId = cls.classId AND  
				 inst.instituteId=cls.instituteId 
				 $conditions
				 AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId');
				  
		$query .=" GROUP BY inst.instituteId ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

	//--------------------------------------------------------  
	// Purpose: function to fetch dashboard module list
	// Author:Rajeev Aggarwal
	// Created on : (29.12.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//--------------------------------------------------------  
	public function getDashboardFrameTotal($condition='', $orderBy='') {
	
		$query = "SELECT 
					COUNT(*) as totalRecords
					FROM		
					dashboard_frame  
					$condition
					$orderBy";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//--------------------------------------------------------  
	// Purpose: function to fetch dashboard module list
	// Author:Rajeev Aggarwal
	// Created on : (29.12.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//--------------------------------------------------------  
	public function getDashboardFrameList($condition='', $orderBy='', $limit='') {
		$query = "SELECT 
					frameId,frameName 
					FROM		
					dashboard_frame  
					$condition
					$orderBy $limit";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//-------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR ADDING Suggestion
	//
	// Author :Rajeev Aggarwal
	// Created on : 09-02-2009
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------       

	
	public function addSuggestion() {

		global $REQUEST_DATA;
		global $sessionHandler;

		$userId = $sessionHandler->getSessionVariable('UserId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$todayDate = date('Y-m-d');
		return SystemDatabaseManager::getInstance()->runAutoInsert('suggestion', array('userId', 'suggestionOn','suggestionSubjectId', 'suggestionText', 'instituteId'), array($userId, $todayDate, $REQUEST_DATA['suggestionSubject'], $REQUEST_DATA['suggestionText'], $instituteId));
	}

	//--------------------------------------------------------  
	// Purpose: function to fetch suggestion count
	// Author:Rajeev Aggarwal
	// Created on : (09.02.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//--------------------------------------------------------  
	public function getTotalSuggestion($condition='') {
	
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT 
					COUNT(*) as totalRecords
					FROM		
					`suggestion` where instituteId =   $instituteId
					$condition
					";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//--------------------------------------------------------  
	// Purpose: function to fetch suggestion list
	// Author:Rajeev Aggarwal
	// Created on : (29.12.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//--------------------------------------------------------  
	public function getSuggestionList($condition='', $orderBy='', $limit='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		$query = "SELECT 
							usr.userName,su.suggestionId,su.suggestionOn,
							su.suggestionSubjectId,su.suggestionText,IF(su.repliedOn IS NULL,'-1',su.repliedOn) as repliedOn
					FROM		
							`suggestion` su, `user` usr  
					WHERE

					su.userId = usr.userId
					and su.instituteId =   $instituteId
					$condition
					ORDER BY $orderBy $limit";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//--------------------------------------------------------  
	// Purpose: function to fetch suggestion detail
	// Author:Rajeev Aggarwal
	// Created on : (29.12.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//--------------------------------------------------------  
	public function getSuggestionDetail($condition='') {
		$query = "SELECT 
					usr.userName,su.suggestionId,su.suggestionOn,
					su.suggestionSubjectId,su.suggestionText,IF(su.repliedOn IS NULL,'-1',su.repliedOn) as repliedOn
					FROM		
					`suggestion` su, `user` usr  
					WHERE

					su.userId = usr.userId
					$condition";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//--------------------------------------------------------  
	// Purpose: function to read suggestion detail
	// Author:Rajeev Aggarwal
	// Created on : (10.02.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//--------------------------------------------------------  
	public function readSuggestion($suggestionId) {
		
		$todayDate = date('Y-m-d');
		return SystemDatabaseManager::getInstance()->runAutoUpdate('suggestion', array('repliedOn'), array($todayDate), "suggestionId=$suggestionId" );
	}

	//--------------------------------------------------------  
	// Purpose: function to read suggestion detail
	// Author:Rajeev Aggarwal
	// Created on : (10.02.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//--------------------------------------------------------  
	public function getEmailAddress() {
		
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$query = "SELECT  userName, userId, roleId,
					(CASE roleId
					WHEN 1
					THEN if((SELECT CONCAT(emailAddress,'~',employeeName) FROM employee WHERE userId=user.userId) is null,'-', (SELECT CONCAT(emailAddress,'~',employeeName) FROM employee WHERE userId=user.userId))
					WHEN 2
					THEN if((SELECT CONCAT(emailAddress,'~',employeeName) FROM employee WHERE userId=user.userId) is null,'-', (SELECT CONCAT(emailAddress,'~',employeeName) FROM employee WHERE userId=user.userId))
					WHEN 3
					THEN (
					 select CONCAT(stu.fatherEmail,'~',stu.fatherName)  from student stu  where stu.fatherUserId = user.userId
					 union 
					 select CONCAT(stu.motherEmail,'~',stu.motherName) from student stu  where stu.motherUserId = user.userId
					 union 
					 select CONCAT(stu.guardianEmail,'~',stu.guardianName) from student stu  where stu.guardianUserId = user.userId
					)
					WHEN 4
					THEN if((SELECT CONCAT(studentEmail,'~',firstName,' ',lastName) FROM student WHERE userId=user.userId) is null,'-', (SELECT CONCAT(studentEmail,'~',firstName,' ',lastName) FROM student WHERE userId=user.userId))
					ELSE 
					if((SELECT CONCAT(emailAddress,'~',employeeName) FROM employee WHERE userId=user.userId) is null,'-', (SELECT CONCAT(emailAddress,'~',employeeName) FROM employee WHERE userId=user.userId))
					END) AS email
					FROM  user
					WHERE    user.userId = '$userId'";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//---------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR ACTIVE CLASS
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getActiveClassId($conditions=''){

global $sessionHandler;
$instituteId=$sessionHandler->getSessionVariable('InstituteId');

$query	="select 
		ttc.classId 
		
		FROM  
		`time_table_classes` ttc, `time_table_labels` ttl,`class` cls 
		
		WHERE 
		ttc.timeTableLabelId = ttc.timeTableLabelId AND 
		ttl.isActive=1 AND 
		ttc.classId=cls.classId AND 
		cls.isActive=1 AND cls.instituteId = $instituteId
		
		ORDER by ttc.classID LIMIT 0,1" ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
}

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student Enquiry list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (02.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentEnquiryCityList($conditions='') {
    
       global $sessionHandler;
       if($sessionHandler->getSessionVariable('RoleType')=='11'){
		
			$userCondition = " AND sc.addedByUserId = ".$sessionHandler->getSessionVariable('UserId');
	   }
	   $query = "SELECT 
					SUM(IF(sc.corrCityId IS Null,1,0)) AS notfoundCity,
					SUM(IF(sc.corrCityId IS NOT Null,1,0)) AS foundCity,
					IF(cty.cityId IS NULL,0,cty.cityId) as cityId, IF(cty.cityName IS		NULL,'Un-Specified',cty.cityName) as cityName
				  
				  FROM 
					`user` usr, 
					`student_enquiry` sc LEFT JOIN `city` cty  ON sc.corrCityId = cty.cityId
				  
				  WHERE 
					sc.addedByUserId = usr.userId  
					$conditions
				    $userCondition
				  
				  GROUP BY 
					sc.corrCityId";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student Enquiry list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (02.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentEnquiryStateList($conditions='') {
    
       global $sessionHandler;
       if($sessionHandler->getSessionVariable('RoleType')=='11'){
		
			$userCondition = " AND sc.addedByUserId = ".$sessionHandler->getSessionVariable('UserId');
	   }
       $query = "SELECT 
					SUM(IF(sc.corrStateId IS Null,1,0)) AS notfoundState,
					SUM(IF(sc.corrStateId IS NOT Null,1,0)) AS foundState,
					IF(sta.stateId IS NULL,0,sta.stateId) as stateId, IF(sta.stateName IS		NULL,'Un-Specified',sta.stateName) as stateName
				  
				  FROM 
					`user` usr, 
					`student_enquiry` sc LEFT JOIN `states` sta  ON sc.corrStateId = sta.stateId
				  
				  WHERE 
					sc.addedByUserId = usr.userId  
					$conditions
					$userCondition
				     
				  
				  GROUP BY 
					sc.corrStateId ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student Enquiry list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (02.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentEnquiryDegreeList($conditions='') {
    
       global $sessionHandler;
       if($sessionHandler->getSessionVariable('RoleType')=='11'){
		
			$userCondition = " AND sc.addedByUserId = ".$sessionHandler->getSessionVariable('UserId');
	   }
       $query = "SELECT 
					count( * ) as totalCount, cls.classId, cls.className
				  FROM `student_enquiry` sc, `class` cls,`user` usr
				  
				  WHERE 
					sc.classId = cls.classId AND
					sc.addedByUserId = usr.userId 
					$conditions
				    $userCondition
				  
				  GROUP BY sc.classId ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
//--------------------------------------------------------  
// Purpose: function to fetch city wise student enquiry  list
// Author:Rajeev Aggarwal
// Created on : (29.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------  
	public function getStudentEnquiryCityRecords($city, $orderBy='') {

		global $sessionHandler;
		if($sessionHandler->getSessionVariable('RoleType')=='11'){
		
			$userCondition = " AND sc.addedByUserId = ".$sessionHandler->getSessionVariable('UserId');
	    }
		if($city){
		
			$query = "SELECT 
					firstName,lastName,studentPhone,studentMobileNo,studentEmail,studentGender,enquiryDate dateOfBirth,fatherName,studentPhone,cty.cityName
				  FROM 
					`student_enquiry` sc, `class` cls,`user` usr, `city` cty
				  
				  WHERE 
					sc.corrCityId = cty.cityId AND
					sc.classId = cls.classId  AND
					sc.addedByUserId = usr.userId AND
					sc.corrCityId = $city
				    $userCondition
					 
					AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
					cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
			$query .=" $orderBy ";
		} 
		else{
		
			$query = "SELECT 
					firstName,lastName,studentPhone,studentMobileNo,studentEmail,studentGender,enquiryDate dateOfBirth,fatherName,studentPhone 
				  FROM 
					`student_enquiry` sc, `class` cls,`user` usr 
				  
				  WHERE 
					 
					sc.classId = cls.classId  AND
					sc.addedByUserId = usr.userId  AND sc.corrCityId IS NULL
				    $userCondition
					 
					AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
					cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
			$query .=" $orderBy ";
		} 
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//--------------------------------------------------------  
// Purpose: function to fetch state wise student enquiry  list
// Author:Rajeev Aggarwal
// Created on : (29.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------  
	public function getStudentEnquiryStateRecords($state='', $orderBy='') {

		global $sessionHandler;
		if($sessionHandler->getSessionVariable('RoleType')=='11'){
		
			$userCondition = " AND sc.addedByUserId = ".$sessionHandler->getSessionVariable('UserId');
	    }
		if($state){
		
			$query = "SELECT 
					firstName,lastName,studentPhone,studentMobileNo,studentEmail,studentGender,enquiryDate dateOfBirth,fatherName,studentPhone,sta.stateName
				  FROM 
					`student_enquiry` sc, `class` cls,`user` usr, `states` sta
				  
				  WHERE 
					sc.corrStateId = sta.stateId AND
					sc.classId = cls.classId  AND
					sc.addedByUserId = usr.userId 
				     AND sc.corrStateId =$state
					 
					$userCondition
					AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
					cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
			$query .=" $orderBy ";
		} 
		else{
		
			$query = "SELECT 
					firstName,lastName,studentPhone,studentMobileNo,studentEmail,studentGender,enquiryDate dateOfBirth,fatherName,studentPhone
				  FROM 
					`student_enquiry` sc, `class` cls,`user` usr
				  
				  WHERE 
					 
					sc.classId = cls.classId  AND
					sc.addedByUserId = usr.userId 
					AND sc.corrStateId IS NULL
					$userCondition 
					AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
					cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
			$query .=" $orderBy ";
		} 
		
		 
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//--------------------------------------------------------  
// Purpose: function to fetch state wise student enquiry  list
// Author:Rajeev Aggarwal
// Created on : (29.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------  
	public function getStudentEnquiryDegreeRecords($conditions='', $orderBy='') {

		global $sessionHandler;
		if($sessionHandler->getSessionVariable('RoleType')=='11'){
		
			$userCondition = " AND sc.addedByUserId = ".$sessionHandler->getSessionVariable('UserId');
	    }
		$query = "SELECT 
					firstName,lastName,studentPhone,studentMobileNo,studentEmail,studentGender,enquiryDate dateOfBirth,fatherName,studentPhone,cls.className
				  FROM 
					`student_enquiry` sc, `class` cls,`user` usr
				  
				  WHERE 
					
					sc.classId = cls.classId  AND
					sc.addedByUserId = usr.userId 
				  
					$conditions
					$userCondition
					AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
					cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" $orderBy ";
		 
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student Enquiry consoler wise list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (03.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentEnquiryConsolerList($conditions='') {
    
       global $sessionHandler;
       $query = "SELECT 
				COUNT( * ) as totalCount, usr.userName,sc.addedByUserId
				FROM `student_enquiry` sc,`user` usr

				WHERE 

				sc.addedByUserId = usr.userId 
				$conditions

				GROUP BY sc.addedByUserId ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
	
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student Enquiry consoler wise list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (03.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentEnquiryConsolerRecord($conditions='') {
    
       global $sessionHandler;
       $query = "SELECT 
					usr.userName,firstName,lastName,studentPhone,studentMobileNo,studentEmail,studentGender,
					enquiryDate dateOfBirth,fatherName,studentPhone,cls.className
				FROM 
					`student_enquiry` sc, `class` cls,`user` usr

				WHERE 

				sc.classId = cls.classId  AND
				sc.addedByUserId = usr.userId 
				$conditions
				AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."  
				AND cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');

		$query .=" $orderBy ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student Enquiry consoler wise list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (03.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentEnquiryGenderList($conditions='') {
    
	   global $sessionHandler;
       $query = "SELECT count( * ) as totalCount,sc.studentGender
				  FROM `student_enquiry` sc, `class` cls,`user` usr
				 
				  WHERE 
				  sc.classId = cls.classId  AND
				  sc.addedByUserId = usr.userId 
				  $conditions
				  AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				  cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY sc.studentGender ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student Enquiry gender wise list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (03.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentEnquiryGenderRecord($conditions='') {
    
       global $sessionHandler;
       $query = "SELECT 
					usr.userName,firstName,lastName,studentPhone,studentMobileNo,studentEmail,studentGender,
					enquiryDate dateOfBirth,fatherName,studentPhone,cls.className
				FROM 
					`student_enquiry` sc, `class` cls,`user` usr

				WHERE 

				sc.classId = cls.classId  AND
				sc.addedByUserId = usr.userId 
				$conditions
				AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."  
				AND cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');

		$query .=" $orderBy ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student Enquiry consoler wise list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (03.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getTotalStudentEnquiry($conditions='') {
    
	   global $sessionHandler;
       $query = "SELECT count( * ) as totalCount 
				  FROM `student_enquiry`  
				  $conditions
				  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }	

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the student Enquiry consoler wise list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (03.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getTotalConsular($conditions='') {
    
	   global $sessionHandler;
       $query = "SELECT count( * ) as totalCount 
				  FROM `user` usr,`role` rol
				  WHERE 
				  usr.roleId=rol.roleId 
				  AND rol.roleType='11'
				  $conditions
				  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
    

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute notices for a teacher
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------  
public function getUnApprovedFineCount($conditions=''){
    global $sessionHandler;
    $query=" SELECT 
                    COUNT( * ) AS totalRecords
             FROM 
                    fine_student
             WHERE 
                    status=2
                    AND fineCategoryId
                    IN (
                         SELECT 
                                rfc.fineCategoryId
                         FROM 
                                role_fine_approve rfa, role_fine_category rfc
                         WHERE 
                              rfa.roleFineId = rfc.roleFineId
                              AND rfa.userId='".$sessionHandler->getSessionVariable('UserId')."'
                       )
                     AND instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'  
              $conditions";    
         
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }

	//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Teacher subjects
//
// Author :Parveen Sharma
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------        
    public function getClassSubjects($getClassId,$condition='') {
        global $sessionHandler;

        $query = "	SELECT 
							sub.subjectId,
							sub.subjectCode
					FROM 	subject_to_class stc,
							subject sub
					WHERE	stc.subjectId = sub.subjectId
					AND		stc.classId = $getClassId
							$condition
							ORDER BY subjectCode";
                         
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
    }

	//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Teacher subjects
//
// Author :Jaineesh
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------        
    public function getClassName($classId) {
        global $sessionHandler;

        $query = "	SELECT 
							cl.className
					FROM 	class cl
					WHERE	cl.classId = $classId";
                         
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
    }

	//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Teacher subjects
//
// Author :Jaineesh
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------        
    public function getSubjectName($subjectId) {
        global $sessionHandler;

        $query = "	SELECT 
							CONCAT(sub.subjectCode,'(',sub.subjectName,')') AS subjectCode
					FROM 	subject sub
					WHERE	sub.subjectId = $subjectId";
                         
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
    }

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Student threshold detail as per subject
//
// Author :Jaineesh
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------   
	/*public function getAttendanceThresholdRecords($concatStr) {
    global $sessionHandler;
    $attendaceThreshold =	$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
	$instituteId		=	$sessionHandler->getSessionVariable('InstituteId');

	 $query = "
				SELECT		t.subjectId, count(t.studentId) AS CNT, t.subjectCode FROM (
				SELECT 
							att.subjectId, s.studentId, sub.subjectCode,
							ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage/100), att.lectureAttended)))/SUM(IF(att.isMemberOfClass =0, 0, att.lectureDelivered))*100,2) AS percentage
				FROM		subject sub, student s INNER JOIN ".ATTENDANCE_TABLE." att ON (att.studentId = s.studentId and att.classId = s.classId)
				LEFT JOIN	attendance_code ac
				ON			(ac.attendanceCodeId = att.attendanceCodeId and ac.instituteId = '$instituteId')
				WHERE		CONCAT(att.subjectId,'#',att.classId)
				IN			($concatStr)
				AND			att.subjectId = sub.subjectId
							GROUP BY att.subjectId, s.studentId
							HAVING percentage < '$attendaceThreshold') as t group by t.subjectCode ORDER BY subjectCode";
     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
 }*/

 public function getAttendanceThresholdRecords2($subjectId, $classId) {
    global $sessionHandler;
    $attendaceThreshold =	$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
	$instituteId		=	$sessionHandler->getSessionVariable('InstituteId');

	 $query = "
				SELECT 
							att.subjectId, s.studentId, sub.subjectCode,
							ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage/100), att.lectureAttended)))/SUM(IF(att.isMemberOfClass =0, 0, att.lectureDelivered))*100,2) AS percentage
				FROM		subject sub, student s INNER JOIN ".ATTENDANCE_TABLE." att ON (att.studentId = s.studentId and att.classId = s.classId)
				LEFT JOIN	attendance_code ac
				ON			(ac.attendanceCodeId = att.attendanceCodeId and ac.instituteId = '$instituteId')
				WHERE		att.subjectId = $subjectId and att.classId = $classId
				AND			att.subjectId = sub.subjectId
							GROUP BY att.subjectId, s.studentId
							HAVING percentage < '$attendaceThreshold'";
     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
 }



//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Teacher subjects
//
// Author :Jaineesh
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

 public function getAttendanceThresholdRecordsPrint($classId,$subjectId) {
    global $sessionHandler;
    $attendaceThreshold =	$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
	$instituteId		=	$sessionHandler->getSessionVariable('InstituteId');

	 $query = "
				
				SELECT 
							att.subjectId, 
							s.studentId, 
							CONCAT(s.firstName,'',s.lastName) AS studentName, 
							CONCAT(sub.subjectCode,' (',sub.subjectName,')') AS subjectCode, 
							cl.className, 
							emp.employeeName, 
							g.groupName,
							ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage/100), att.lectureAttended)))/SUM(IF(att.isMemberOfClass =0, 0, att.lectureDelivered))*100,2) AS percentage
				FROM		subject sub, `group` g, class cl, employee emp, student s 
				INNER JOIN ".ATTENDANCE_TABLE." att ON (att.studentId = s.studentId and att.classId = s.classId)
				LEFT JOIN	attendance_code ac
				ON			(ac.attendanceCodeId = att.attendanceCodeId and ac.instituteId = '$instituteId')
				WHERE		att.subjectId = $subjectId
				AND			att.classId = $classId
				AND			att.subjectId = sub.subjectId
				AND			att.groupId = g.groupId
				AND			att.classId = cl.classId
				AND			att.employeeId = emp.employeeId
							GROUP BY att.subjectId, s.studentId
							HAVING percentage < '$attendaceThreshold' 
							ORDER BY percentage";
     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
 }

 public function getCountAttendanceThresholdRecords($concatStr) {
    global $sessionHandler;
    $attendaceThreshold =	$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
	$instituteId		=	$sessionHandler->getSessionVariable('InstituteId');

	 $query = "
				SELECT 
							s.studentId,
							ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage/100), att.lectureAttended)) )/SUM(IF(att.isMemberOfClass =0, 0, att.lectureDelivered))*100,2) AS percentage
							FROM		student s INNER JOIN ".ATTENDANCE_TABLE." att ON (att.studentId = s.studentId and att.classId = s.classId)
							LEFT JOIN	attendance_code ac 
							ON		(ac.attendanceCodeId = att.attendanceCodeId and ac.instituteId = '$instituteId')
							WHERE		CONCAT(att.subjectId,'#',att.classId) 
							IN			($concatStr)
							GROUP BY att.subjectId, att.studentId
							having percentage > '$attendaceThreshold'";
     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
 }
 
 //------------- functions for user login report--------- 
 
 //----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR employee activity 
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (06.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getUserLoginList($conditions='',$limit,$orderBy="userId, DATE_FORMAT(dateTimeIn,'%Y-%m-%d') DESC"){

//select distinct(userId),DATE_FORMAT(dateTimeIn,'%Y-%m-%d') as loggedInTime  from user_log 

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
   $query    ="SELECT 
                    usr.userId, userName, roleName, DATE_FORMAT(dateTimeIn,'%d-%b-%y') as loggedInTime,
                    DATE_FORMAT(MAX(dateTimeIn),'%d-%b-%y: %r') as dateTimeIn,
                    GROUP_CONCAT(DATE_FORMAT(dateTimeIn,'%h:%i:%s %p')) AS timeIn,
                    IF(usr.roleId=4,(SELECT firstName from student where userId=usr.userId LIMIT 0,1),
                    (IF(usr.roleId=3,'Parent', (SELECT IF( e.employeeName IS NULL , us.userName, e.employeeName ) AS userName 
                                                FROM user us
                    LEFT JOIN employee e ON e.userId = us.userId WHERE us.userId = usr.userId LIMIT 0,1))))  as roleUserName, 
                    COUNT(usr.userId) AS userCount
  FROM 
                    `user_log` ul,`user` usr ,`role` ro 
WHERE 
        $conditions AND
        ul.userId = usr.userId AND
        usr.instituteId = $instituteId AND
        usr.roleId = ro.roleId 
GROUP BY 
        userId, DATE_FORMAT(dateTimeIn,'%Y-%m-%d')
ORDER BY  $orderBy
    
    $limit" ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
} 
 //----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR employee activity 
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (19.02.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getStudentNotLoggedinList($conditions='',$limit,$orderBy=''){

//select distinct(userId),DATE_FORMAT(dateTimeIn,'%Y-%m-%d') as loggedInTime  from user_log 

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
   $query    ="SELECT
                        u.userId,u.roleId,CONCAT(IFNULL(s1.firstName,''), ' ',IFNULL(s1.lastName,'')) AS roleUserName,s1.rollNo, c.className
                   FROM
                        user u,student s1,class c
                   WHERE
                        
                        c.classId = s1.classId AND
                        s1.userId = u.userId AND
                        roleId = 4 AND 
                        c.instituteId = $instituteId AND
                        u.userId NOT IN 
                                        (SELECT 
                                                DISTINCT ul.userId 
                                         FROM 
                                                user_log ul, student s 
                                         WHERE     
                                                $conditions
                                                 AND ul.userId = s.userId)
                                                 
                  ORDER BY  $orderBy
                  $limit" ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
} 
 //----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR employee activity 
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (19.02.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getStudentNotLoggedInTotal($conditions=''){

//select distinct(userId),DATE_FORMAT(dateTimeIn,'%Y-%m-%d') as loggedInTime  from user_log 

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
  $query    ="SELECT
                        COUNT(*) AS totalCount
                   FROM
                        user u,student s1,class c
                   WHERE
                        
                        c.classId = s1.classId AND
                        s1.userId = u.userId AND
                        roleId = 4 AND 
                        c.instituteId = $instituteId AND
                        u.userId NOT IN 
                                        (SELECT 
                                                DISTINCT ul.userId 
                                         FROM 
                                                user_log ul, student s 
                                         WHERE     
                                                $conditions
                                                 AND ul.userId = s.userId)
                   " ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
} 

 //----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR employee activity 
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (06.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getUserLoginTotal($conditions=''){

//select distinct(userId),DATE_FORMAT(dateTimeIn,'%Y-%m-%d') as loggedInTime  from user_log 

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $query    ="SELECT 
    count(*)  as totalCount
FROM 
      `user_log` ul,`user` usr ,`role` ro 
WHERE
$conditions AND 
    ul.userId = usr.userId AND
    usr.instituteId = $instituteId AND
    usr.roleId = ro.roleId 
GROUP BY 
    usr.userId, DATE_FORMAT(dateTimeIn,'%Y-%m-%d')
ORDER BY 
    usr.userId, DATE_FORMAT(dateTimeIn,'%Y-%m-%d') DESC" ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
} 


//-------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching expected date of checkout student list
// $conditions :db clauses
// Author : Dipanjan Bhattacharjee
// Created on : (30.04.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------------  
public function getExpectedDateOfCheckOutList($dateInterval=7){
 
 $query=" SELECT 
                SUM(IF(hs.hostelRoomId IS NULL,0,1))  AS occupied, 
                hr.roomCapacity, 
                (hr.roomCapacity - SUM(IF(hs.hostelRoomId IS NULL,0,1)) ) AS vacant,
                hrt.roomType
          FROM 
                hostel_room hr
                LEFT JOIN hostel_students hs ON hs.hostelRoomId = hr.hostelRoomId   AND ( hs.possibleDateOfCheckOut between current_date() and date_sub(current_date(), interval -$dateInterval day) )  AND dateOfCheckOut='0000-00-00'
                INNER JOIN hostel h ON h.hostelId = hr.hostelId
                INNER JOIN hostel_room_type hrt ON hrt.hostelRoomTypeId = hr.hostelRoomTypeId 
          GROUP BY hr.hostelRoomId
          ORDER BY  hostelName ASC " ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
 }     
 
//-------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEEID 
// $conditions :db clauses
// Author : Gagan Gill
// Created on : (09.02.2011)
// Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------------  

	public function getSingleField($table, $field, $conditions='') {
			$query = "SELECT $field FROM $table $conditions";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}


   public function lecturesDelivered($classId='',$subjectId='') {
	   
	   global $sessionHandler;
	   $instituteId=$sessionHandler->getSessionVariable('InstituteId');
  
	   $query ="SELECT 
	                    SUM(a.lectureDelivered) AS lectureDelivered, MAX(a.toDate) AS uptoDate
				FROM   
						".ATTENDANCE_TABLE." a, class c
				WHERE   
						c.classId = a.classId  AND
						a.classId = '$classId' AND    
						a.subjectId = '$subjectId' AND
						c.instituteId = '$instituteId'
                GROUP BY  
				       a.classId , a.subjectId, a.studentId
				ORDER BY 
				       lectureDelivered DESC
				LIMIT 0,1";

		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
     }     

//This function will get the download count
	public function getDownloadCount($id) {

		global $REQUEST_DATA;
	
		$query = "UPDATE
				 notice
			  SET  
				downloadCount = downloadCount+1
			  WHERE
				 noticeId= '$id' ";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    	}    

/*	public function getNoticeCount($id) {

		global $REQUEST_DATA;
	
		$query = "INSERT INTO 
				 	notice_read(userId,noticeId,roleId,Sessiond,notice_count)
			  VALUES(".$sessionHandler->getSessionVariable('UserId').",".$noticeId.",".$sessionHandler->getSessionVariable('RoleId').",".$sessionHandler->getSessionVariable('SessionId').")";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    	}    
*/

    public function getNoticeReadList($id) {

       global $REQUEST_DATA;
       global $sessionHandler;
       
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       $userId=$sessionHandler->getSessionVariable('UserId');
       $roleId=$sessionHandler->getSessionVariable('RoleId');
    
        $query = "SELECT 
                       COUNT(*) AS cnt
                  FROM
                      notice_read_count      
                  WHERE
                       noticeId= '$id' AND
                       instituteId ='$instituteId' AND
                       sessionId = '$sessionId' AND
                       userId ='$userId' AND
                       roleId='$roleId' ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }    
     
     public function getNoticeReadInsert($id) {

       global $REQUEST_DATA;
       global $sessionHandler;
       
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       $userId=$sessionHandler->getSessionVariable('UserId');
       $roleId=$sessionHandler->getSessionVariable('RoleId');
    
       $lastViewDate = date('Y-m-d');
    
       $query = "INSERT INTO notice_read_count        
                 (noticeId,userId,roleId,lastViewDate,viewCount,instituteId,sessionId)
                 VALUES
                 ($id,$userId,$roleId,'$lastViewDate',1,$instituteId,$sessionId) ";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");  
     }   
     
     public function getNoticeReadUpdate($id) {
   
       global $REQUEST_DATA;
       global $sessionHandler;
       
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       $userId=$sessionHandler->getSessionVariable('UserId');
       $roleId=$sessionHandler->getSessionVariable('RoleId');
    
       $lastViewDate = date('Y-m-d');
    
       $query = "UPDATE 
                     notice_read_count        
                 SET 
                     lastViewDate = '$lastViewDate',
                     viewCount = viewCount + 1 
                 WHERE
                    noticeId = $id AND 
                    userId = $userId AND
                    roleId = $roleId AND 
                    instituteId = $instituteId AND
                    sessionId = '$sessionId' ";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");  
     }   


}


// $History: DashBoardManager.inc.php $
//
//*****************  Version 28  *****************
//User: Jaineesh     Date: 4/15/10    Time: 10:20a
//Updated in $/LeapCC/Model
//show subject Name with subject code
//
//*****************  Version 27  *****************
//User: Jaineesh     Date: 4/13/10    Time: 7:12p
//Updated in $/LeapCC/Model
//solved problem of attendance threshold
//
//*****************  Version 26  *****************
//User: Jaineesh     Date: 3/03/10    Time: 11:36a
//Updated in $/LeapCC/Model
//fixed bug for LLRIET
//
//*****************  Version 25  *****************
//User: Jaineesh     Date: 2/24/10    Time: 5:56p
//Updated in $/LeapCC/Model
//fixed query error
//
//*****************  Version 24  *****************
//User: Gurkeerat    Date: 2/22/10    Time: 6:21p
//Updated in $/LeapCC/Model
//added function under user login report
//
//*****************  Version 23  *****************
//User: Jaineesh     Date: 2/19/10    Time: 3:10p
//Updated in $/LeapCC/Model
//Show a graph showing count of students who are falling short of
//attendance in any subject. On clicking the bar, the attendance details
//of the particular student or all students are shown.
//
//*****************  Version 22  *****************
//User: Rajeev       Date: 09-12-28   Time: 11:14a
//Updated in $/LeapCC/Model
//Earlier 'User Logged In List Report' shows the login-in time as the
//'First' login of the day now it will show last login time 
//
//*****************  Version 21  *****************
//User: Rajeev       Date: 09-11-20   Time: 2:28p
//Updated in $/LeapCC/Model
//updated with multiple institute check on queries
//
//*****************  Version 20  *****************
//User: Rajeev       Date: 09-11-02   Time: 10:21a
//Updated in $/LeapCC/Model
//Added institute check on attendance graph on dashboard
//
//*****************  Version 19  *****************
//User: Rajeev       Date: 09-09-30   Time: 5:42p
//Updated in $/LeapCC/Model
//Changed attendance table to changed as per institute
//
//*****************  Version 18  *****************
//User: Rajeev       Date: 09-09-19   Time: 4:33p
//Updated in $/LeapCC/Model
//Updated with instituteId validation
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 09-09-01   Time: 5:12p
//Updated in $/LeapCC/Model
//fixed bug no 1245
//
//*****************  Version 16  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:21p
//Updated in $/LeapCC/Model
//added multiple table defines.
//
//*****************  Version 15  *****************
//User: Ajinder      Date: 8/18/09    Time: 7:07p
//Updated in $/LeapCC/Model
//fixed bug no.1143
//
//*****************  Version 14  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 7/07/09    Time: 19:31
//Updated in $/LeapCC/Model
//corrected query for "unapproved fines alert" 
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 7/07/09    Time: 10:53
//Updated in $/LeapCC/Model
//Added institute wise check in unapproved fine alerts query
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 6/07/09    Time: 18:29
//Updated in $/LeapCC/Model
//Added "UnApproved Fine Display" in admin's dashboard
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 6/03/09    Time: 12:08p
//Updated in $/LeapCC/Model
//Intial checkin for student enquiry demographics for admin
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 6/02/09    Time: 7:22p
//Updated in $/LeapCC/Model
//Updated with "unsepcified" parameter if city and state is NULL
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 6/02/09    Time: 6:15p
//Updated in $/LeapCC/Model
//Updated with "Pre admission" dashboard and print report
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 5/22/09    Time: 2:58p
//Updated in $/LeapCC/Model
//Updated test type distribution to have unique value for class
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 5/19/09    Time: 5:56p
//Updated in $/LeapCC/Model
//Updated Admin dashboard with role permission, test type and average
//attendance
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 4/15/09    Time: 11:43a
//Updated in $/LeapCC/Model
//added suggestion link
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/19/09    Time: 4:26p
//Updated in $/LeapCC/Model
//added role permission and dashboard permission
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/22/08   Time: 1:39p
//Updated in $/LeapCC/Model
//Updated as per CC functionality
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/11/08   Time: 3:00p
//Updated in $/LeapCC/Model
//Updated module as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 9/08/08    Time: 12:36p
//Updated in $/Leap/Source/Model
//updated dashboard file
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 9/04/08    Time: 2:13p
//Updated in $/Leap/Source/Model
//updated event list function
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 9/04/08    Time: 12:57p
//Updated in $/Leap/Source/Model
//updated the formatting and made floating div for event description
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 9/01/08    Time: 3:31p
//Updated in $/Leap/Source/Model
//updated with fees dues on dashboard
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/25/08    Time: 6:34p
//Updated in $/Leap/Source/Model
//updated attendance missed function with institute and session id
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/18/08    Time: 11:49a
//Updated in $/Leap/Source/Model
//list notice function is updated
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/13/08    Time: 6:42p
//Updated in $/Leap/Source/Model
//added function for list institute events
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/13/08    Time: 6:05p
//Created in $/Leap/Source/Model
//intial checkin
?>
