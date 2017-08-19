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

class EmployeeManager {
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
// THIS FUNCTION IS USED FOR getting list of `student`'s fees due
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
    
	$query = "SELECT rollNo,receiptNo,DATE_FORMAT(receiptDate, '%d-%b-%y') as		
			 receiptDate,feeReceiptId,CONCAT(stu.firstName,' ',stu.lastName) as fullName,previousDues,fc.cycleName,fr.totalAmountPaid as totalAmountPaid,
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
// THIS FUNCTION IS USED FOR getting list of institute notices for a management
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getNoticeList($conditions='', $limit = '', $orderBy=' n.noticeText'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query="SELECT 
			n.noticeId, 
			n.noticeText,
			n.noticeSubject,
			n.visibleFromDate,
			n.visibleToDate,
			n.noticeAttachment,
			d.abbr,
			d.departmentName
FROM		department d, notice n INNER JOIN notice_visible_to_role nvr ON n.noticeId=nvr.noticeId 
WHERE		nvr.instituteId=$instituteId 
AND			n.instituteId=$instituteId
AND			nvr.sessionId=$sessionId 
AND			n.departmentId = d.departmentId 
		   $conditions 
		   GROUP BY n.noticeId
		   ORDER BY $orderBy $limit 
		" ;   
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute notices for a management
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getNoticeMonthWiseList($conditions='', $limit = '', $orderBy=' n.noticeText'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query="SELECT 
		   n.noticeId,MONTH(visibleFromDate) as visibleMonth , noticeSubject ,noticeText , visibleFromDate , visibleToDate,noticeAttachment 
		   FROM 
		   notice n, notice_visible_to_role nvr 
		   WHERE 
		   n.noticeId=nvr.noticeId AND nvr.instituteId=$instituteId  AND n.instituteId=$instituteId AND sessionId = $sessionId  
		   $conditions 
		   GROUP BY n.noticeId
		   ORDER BY $orderBy $limit 
		" ;   
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}


//-----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute events for a management
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------------------  
public function getEventList($conditions='', $limit = '', $orderBy=' endDate DESC'){

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $sessionId=$sessionHandler->getSessionVariable('SessionId');
    
 /*$query="SELECT e.eventId,e.eventTitle,e.shortDescription,e.longDescription,e.startDate,e.endDate
         FROM event e 
         WHERE e.instituteId=$instituteId AND e.sessionId=$sessionId  
         $conditions   ORDER BY $orderBy $limit " ; */

 $query="SELECT e.eventId,e.eventTitle,e.shortDescription,e.longDescription,e.startDate,e.endDate
         FROM event e 
         WHERE e.instituteId=$instituteId AND e.sessionId=$sessionId 
         $conditions   ORDER BY $orderBy $limit " ;   		 
 //echo $query;       
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
}

 

//------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying event details for a particular event
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (29.9.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------------   
public function getEventDetail($eventId){
    
    $query="SELECT e.eventId,e.eventTitle,e.shortDescription,e.longDescription,e.startDate,e.endDate
           FROM event e
           WHERE e.eventId=".$eventId;

      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
} 

 
//----------------------------------------------------------------------------------------------------
//funciton return records for missed attendance report for all classes, all subjects, all groups

// Author :Ajinder Singh
// Created on : 29-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getAllClassMissedAttendanceReport($tillDate, $sortField, $sortOrderBy,$limit) {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$query = "	SELECT 
					REPLACE( SUBSTRING_INDEX( b.className, '-' , -3 ) , '-', ' ' ) AS className, 
					c.subjectCode, 
					d.sectionName, 
					e.employeeName, 
					MAX(toDate) AS testDate,
					DATE_FORMAT(max(toDate), '%d-%b-%y') AS toDate
					FROM sc_attendance a, class b, subject c, `sc_section` d, employee e 
					WHERE a.classId = b.classId 
					AND a.subjectId = c.subjectId 
					AND a.sectionId = d.sectionId 
					AND a.employeeId = e.employeeId 
					AND b.instituteId = '$instituteId' 
					AND b.sessionId = '$sessionId'
					GROUP BY a.classId, a.subjectId, a.sectionId, a.employeeId having testDate < '$tillDate' 
					ORDER BY $sortField $sortOrderBy $limit ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

	}
//--------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute notices for a management
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
    
 $query=" 
		
		SELECT 
		   *
		   FROM 
		   notice n, notice_visible_to_role nvr 
		   WHERE 
		   n.noticeId=nvr.noticeId AND nvr.instituteId=$instituteId  AND n.instituteId=$instituteId AND sessionId = $sessionId  
		   $conditions 
		   GROUP BY n.noticeId
		   ORDER BY $orderBy 
		" ;    
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
}

//--------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute notices for a management
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------  
public function getAllCountNotice($conditions='', $limit = '', $orderBy=' n.noticeText'){

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $sessionId=$sessionHandler->getSessionVariable('SessionId');
    
 $query=" 
		
		SELECT 
		   *
		   FROM 
		   notice n, notice_visible_to_role nvr 
		   WHERE 
		   n.noticeId=nvr.noticeId AND nvr.instituteId=$instituteId AND n.instituteId=$instituteId AND sessionId = $sessionId  
		    
		   GROUP BY n.noticeId
		   ORDER BY $orderBy 
		" ;    
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
// THIS FUNCTION IS USED to count students
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getTotalStudent($conditions=''){

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $sessionId=$sessionHandler->getSessionVariable('SessionId');
    
 $query="SELECT COUNT(*) AS totalRecords
        FROM student sc, class cls
		where sc.classId = cls.classId AND cls.sessionId=$sessionId AND cls.instituteId=$instituteId
        $conditions  " ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
}

//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED to count employee
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (14.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getTotalEmployee($conditions=''){

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $sessionId=$sessionHandler->getSessionVariable('SessionId');
    
 $query="SELECT COUNT(*) 
		AS totalRecords
        FROM 
		`employee` 
		WHERE 
		instituteId=$instituteId AND
		isActive = 1
        $conditions  " ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
}

//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED to count degree
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (14.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getTotalDegree($conditions=''){

 $query="SELECT COUNT(*) AS totalRecords
        FROM degree dg
        $conditions  " ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
}

//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED to count branches
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (14.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getTotalBranch($conditions=''){

 $query="SELECT COUNT(*) AS totalRecords
        FROM branch
        $conditions  " ;   
        
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
}

//----------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED to get total fees
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (14.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------  
public function getTotalFees($conditions=''){

 global $sessionHandler;
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $sessionId=$sessionHandler->getSessionVariable('SessionId');

 $query="SELECT FORMAT(SUM(totalAmountPaid),2) as totalAmount 
		FROM 
		fee_receipt fr, student sc, class cls
		WHERE 
		fr.studentId = sc.studentId AND 
		sc.classId = cls.classId AND
		cls.sessionId=$sessionId AND 
		cls.instituteId=$instituteId
        $conditions  " ;   
        
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

//------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying notice details for a particular notice
//
// $conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------------   
public function getNoticeDetail($noticeId){
    
	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $query="SELECT n.noticeId,n.noticeSubject,n.noticeText,n.visibleFromDate,n.visibleToDate,d.departmentName,d.abbr
           FROM notice n,department d
           WHERE n.departmentId=d.departmentId AND n.instituteId = $instituteId AND n.noticeId=".$noticeId;
     
   return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
} 

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING DEGREE LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Rajeev Aggarwal
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getDegreeList($conditions='', $limit = '', $orderBy=' dg.degreeName') {
     
        $query = "SELECT dg.degreeId, dg.degreeCode, dg.degreeName, dg.degreeAbbr
        FROM degree dg
        $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Branch LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Rajeev Aggarwal
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 
public function getBranchList($conditions='', $limit = '', $orderBy=' branchName') {
     
        $query = "SELECT branchId, branchCode, branchName FROM branch  
        $conditions                   
        ORDER BY $orderBy $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
	
//------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying admin message list for a a teacher.
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (18.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------         
    public function getAdminMessageList($conditions='', $limit = '', $orderBy=' us.userName') {
    
       global $sessionHandler;
       
       $query="SELECT adm.messageId,IF(e.employeeName IS NULL,us.userName,e.employeeName) AS userName,
               adm.subject,adm.message,adm.dated
               FROM admin_messages adm,user us
               LEFT JOIN employee e ON e.userId=us.userId
               WHERE
               us.userId=adm.senderId AND
               adm.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." 
               AND adm.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
               $conditions ORDER BY $orderBy $limit ";
      // echo $query;        

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");            
    }       
    
    
    
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR counting total no of admin message list for a a teacher.    
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (22.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getTotalAdminMessage($conditions='') {
    
       global $sessionHandler;
       
       $query="SELECT COUNT(*) AS totalRecords
               FROM admin_messages adm,user us
               LEFT JOIN employee e ON e.userId=us.userId
               WHERE
               us.userId=adm.senderId AND
               adm.messageType='Dashboard'
               AND adm.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." 
               AND adm.sessionId=".$sessionHandler->getSessionVariable('SessionId')." 
               $conditions ";
        //echo  $query;       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");            
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the fees deposited
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getCollectedFeesList($conditions='', $limit = '', $orderBy=' receiptNo ASC') {
    
       global $sessionHandler;
        $query = "SELECT 
				  feeReceiptId,
				  receiptNo,
				  DATE_FORMAT(receiptDate, '%d-%b-%y') as receiptDate,
				  CONCAT('Installment','-',installmentCount) as installmentCount,	
				  FORMAT(sum(discountedAmount),2) as discountAmount, feeReceiptId,
				  stu.rollNo, 
				  stu.regNo,		
				  stu.universityRollNo, 
				  stu.universityRegNo, 
				  CONCAT(stu.firstName,' ',stu.lastName) as fullName,
				  fr.totalFeePayable as totalFeePayable, 
				  fr.fine as fine,
				  fr.totalAmountPaid as totalAmountPaid,
				  fr.discountedFeePayable as discountedFeePayable,
				  fr.previousDues  as previousDues,
				  fr.previousOverPayment as previousOverPayment,
				  receiptStatus,
				  instrumentStatus,CAST(if(fr.previousDues>0,fr.previousDues,if(fr.previousOverPayment>0,CONCAT('-',fr.previousOverPayment),'0.00')) AS signed) as outstanding,
				  fc.cycleName
				  FROM student stu, fee_receipt fr,fee_head_student fhs,fee_cycle fc,class cls 
				  WHERE 
				  stu.studentId = fr.studentId AND 
				  stu.studentId = fhs.studentId 
				  $conditions AND 
				  fr.feeCycleId = fc.feeCycleId AND 
				  stu.classId = cls.classId AND
				  cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				  cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY feeReceiptId  ORDER BY $orderBy $limit";
		        
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
    public function getStudentCityWiseList($conditions='',$orderBy='') {
    
       global $sessionHandler;
        $query = "SELECT  firstName,lastName,studentMobileNo,studentEmail,studentGender,DATE_FORMAT(dateOfAdmission, '%d-%b-%y') as dateOfAdmission,DATE_FORMAT(dateOfBirth, '%d-%b-%y') as dateOfBirth,fatherTitle,fatherName, cty.cityId, cityName
				 FROM `student` sc, city cty, class cls
				 WHERE 
				 sc.corrCityId = cty.cityId AND
				 sc.classId = cls.classId 
				 $conditions
				 AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" ORDER BY $orderBy";
		        
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
				  FROM student sc, class cls,branch br 
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
				  FROM student sc, class cls,branch br
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
				  FROM student sc, class cls,degree deg
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
//THIS FUNCTION IS USED FOR all the hostel wise list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentHostelWiseList($conditions='',$orderBy='') {
    
       global $sessionHandler;
       $query = "SELECT firstName,lastName,studentMobileNo,studentEmail,studentGender,DATE_FORMAT(dateOfAdmission, '%d-%b-%y') as dateOfAdmission,DATE_FORMAT(dateOfBirth, '%d-%b-%y') as dateOfBirth,fatherTitle,fatherName
				  FROM student sc, class cls
				  WHERE 
				  sc.classId = cls.classId  
				  $conditions
				  AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				  cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" ORDER BY $orderBy ";
		        
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
    public function getStudentHostelDetailWiseList($conditions='',$orderBy='') {

	   global $sessionHandler;
       $query = "SELECT firstName,lastName,studentMobileNo,studentEmail,studentGender,DATE_FORMAT(dateOfAdmission, '%d-%b-%y') as dateOfAdmission,DATE_FORMAT(dateOfBirth, '%d-%b-%y') as dateOfBirth,fatherTitle,fatherName,hs.hostelId,hs.hostelName
				  FROM student sc, hostel hs, class cls
				  WHERE 
				  sc.hostelId = hs.hostelId  AND
				  sc.classId = cls.classId  
				  $conditions
				  AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				  cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" ORDER BY $orderBy ";
		        
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
//THIS FUNCTION IS USED FOR all the Gender list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentGenderWiseList($conditions='',$orderBy='') {

	   global $sessionHandler;
       $query = "SELECT firstName,lastName,studentMobileNo,studentEmail,studentGender,DATE_FORMAT(dateOfAdmission, '%d-%b-%y') as dateOfAdmission,DATE_FORMAT(dateOfBirth, '%d-%b-%y') as dateOfBirth,fatherTitle,fatherName
				  FROM student sc, class cls
				  WHERE 
				  sc.classId = cls.classId  
				  $conditions
				  AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				  cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" ORDER BY $orderBy ";
		        
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
				 FROM `student` sc, states sta, class cls
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
//THIS FUNCTION IS USED FOR all the student state list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (17.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentStateWiseList($conditions='',$orderBy) {
    
       global $sessionHandler;
        $query = "SELECT firstName,lastName,studentMobileNo,studentEmail,studentGender,DATE_FORMAT(dateOfAdmission, '%d-%b-%y') as dateOfAdmission,DATE_FORMAT(dateOfBirth, '%d-%b-%y') as dateOfBirth,fatherTitle,fatherName, sta.stateId, stateName
				 FROM `student` sc, states sta, class cls
				 WHERE 
				 sc.corrStateId = sta.stateId AND
				 sc.classId = cls.classId 
				 $conditions
				 AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" ORDER BY $orderBy ";
		        
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
				 FROM `student` sc, bus_stop bs, class cls
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
//THIS FUNCTION IS USED FOR all the student bus stop list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (17.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentBusStopWiseList($conditions='',$orderBy) {
    
       global $sessionHandler;
        $query = "SELECT firstName,lastName,studentMobileNo,studentEmail,studentGender,DATE_FORMAT(dateOfAdmission, '%d-%b-%y') as dateOfAdmission,DATE_FORMAT(dateOfBirth, '%d-%b-%y') as dateOfBirth,fatherTitle,fatherName, bs.busStopId,stopName, stopAbbr
				 FROM `student` sc, bus_stop bs, class cls
				 WHERE 
				 sc.busStopId = bs.busStopId AND
				 sc.classId = cls.classId 
				 $conditions
				 AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" ORDER BY $orderBy";
		        
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
				 FROM `student` sc, bus_route br, class cls
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
//THIS FUNCTION IS USED FOR all the student bus route list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (17.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentBusRouteWiseList($conditions='',$orderBy='') {
    
       global $sessionHandler;
        $query = "SELECT firstName,lastName,studentMobileNo,studentEmail,studentGender,DATE_FORMAT(dateOfAdmission, '%d-%b-%y') as dateOfAdmission,DATE_FORMAT(dateOfBirth, '%d-%b-%y') as dateOfBirth,fatherTitle,fatherName, br.busRouteId,routeName,routeCode
				 FROM `student` sc, bus_route br, class cls
				 WHERE 
				 sc.busRouteId = br.busRouteId AND
				 sc.classId = cls.classId 
				 $conditions
				 AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" ORDER BY $orderBy";
		        
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
    public function getStudentSectionList($conditions='') {
    
       global $sessionHandler;
        $query = "select count(*) as totalCount,scse.sectionId,sectionName,sectionType 
				 FROM 
				 student sc, student_section_subject scs,sc_section scse 
				 WHERE 
				 sc.studentId = scs.studentId AND 
				 scs.sectionId = scse.sectionId AND 
				 $conditions
				 scs.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')." AND 
				 scs.sessionId=".$sessionHandler->getSessionVariable('SessionId');
				  
		$query .=" GROUP BY scs.sectionId";
		        
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
    public function getStudentSectionWiseList($conditions='',$orderBy='') {
    
       global $sessionHandler;
        $query = "SELECT firstName,lastName,studentMobileNo,studentEmail,studentGender,DATE_FORMAT(dateOfAdmission, '%d-%b-%y') as dateOfAdmission,DATE_FORMAT(dateOfBirth, '%d-%b-%y') as dateOfBirth,fatherTitle,fatherName,scse.sectionId,sectionName,sectionType,subjectCode 
				 FROM 
				 student sc, student_section_subject scs,sc_section scse,subject sub 
				 WHERE 
				 sc.studentId = scs.studentId AND 
				 scs.subjectId = sub.subjectId AND 
				 scs.sectionId = scse.sectionId  
				 $conditions
				 AND scs.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')." AND 
				 scs.sessionId=".$sessionHandler->getSessionVariable('SessionId');
				  
		$query .=" ORDER BY $orderBy";
		        
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
				 FROM class cls, student sc,countries cnt 
				 
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
//THIS FUNCTION IS USED FOR all the student Nationality list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (18.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentNationalityWiseList($conditions='',$orderBy='') {
    
       global $sessionHandler;
        $query = "SELECT firstName,lastName,studentMobileNo,studentEmail,studentGender,DATE_FORMAT(dateOfAdmission, '%d-%b-%y') as dateOfAdmission,DATE_FORMAT(dateOfBirth, '%d-%b-%y') as dateOfBirth,fatherTitle,fatherName,cnt.countryName, cnt.countryId, cnt.nationalityName,cnt.countryCode  
				 FROM class cls, student sc ,countries cnt
				 
				 WHERE 
				 sc.classId = cls.classId 
				 $conditions
				 AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" ORDER BY $orderBy ";
		        
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
    public function getStudentBatchWiseList($conditions='',$orderBy='') {
    
       global $sessionHandler;
        $query = "SELECT firstName,lastName,studentMobileNo,studentEmail,studentGender,DATE_FORMAT(dateOfAdmission, '%d-%b-%y') as dateOfAdmission,DATE_FORMAT(dateOfBirth, '%d-%b-%y') as dateOfBirth,fatherTitle,fatherName,br.batchId,br.batchName 
				 FROM student sc, class cls, batch br
				 WHERE 
				 sc.classId = cls.classId AND  
				 br.batchId =cls.batchId 
				 $conditions
				 AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" ORDER BY $orderBy ";
		        
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
//THIS FUNCTION IS USED FOR all the student study period list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (18.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getStudentStudyPeriodWiseList($conditions='',$orderBy='') {
    
       global $sessionHandler;
        $query = "SELECT firstName,lastName,studentMobileNo,studentEmail,studentGender,DATE_FORMAT(dateOfAdmission, '%d-%b-%y') as dateOfAdmission,DATE_FORMAT(dateOfBirth, '%d-%b-%y') as dateOfBirth,fatherTitle,fatherName,sp.studyPeriodId ,sp.periodName 
				 FROM student sc, class cls, study_period sp
				 WHERE 
				 sc.classId = cls.classId AND  
				 sp.studyPeriodId =cls.studyPeriodId 
				 $conditions
				 AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')." AND 
				 cls.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" ORDER BY $orderBy ";
		        
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
				 FROM student sc, class cls, institute inst
				 WHERE 
				 sc.classId = cls.classId AND  
				 inst.instituteId=cls.instituteId 
				 $conditions
				 AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId');
				  
		$query .=" GROUP BY inst.instituteId ";
		        
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
    public function getStudentInstituteWiseList($conditions='',$orderBy) {
    
       global $sessionHandler;
        $query = "SELECT firstName,lastName,studentMobileNo,studentEmail,studentGender,DATE_FORMAT(dateOfAdmission, '%d-%b-%y') as dateOfAdmission,DATE_FORMAT(dateOfBirth, '%d-%b-%y') as dateOfBirth,fatherTitle,fatherName,inst.instituteId,inst.instituteName 
				 FROM student sc, class cls, institute inst
				 WHERE 
				 sc.classId = cls.classId AND  
				 inst.instituteId=cls.instituteId 
				 $conditions
				 AND cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId');
				  
		$query .=" ORDER BY $orderBy ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee role wise for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (18.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getEmployeeRoleList($conditions='') {
    
       global $sessionHandler;
        $query = "SELECT count(*) as totalCount,rol.roleId ,roleName 
				 FROM 
				 `employee` emp, `user` usr, role rol 
				 WHERE 
				 emp.userId = usr.userId AND usr.roleId = rol.roleId AND
				 $conditions 
				 emp.instituteId = ".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY roleName";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee role wise for print report
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (23.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getEmployeeRoleWiseList($conditions='',$orderBy='') {
    
       global $sessionHandler;
        $query = "SELECT * 
				 FROM 
				 `employee` emp, `user` usr, role rol,designation dsg, branch br 
				 WHERE 
				 emp.userId = usr.userId AND 
				 usr.roleId = rol.roleId 
				 $conditions
				 AND dsg.designationId = emp.designationId
				 AND br.branchId = emp.branchId
				 AND emp.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')." ORDER BY $orderBy";
				  
		 
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }


//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee role wise for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (18.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getEmployeeTeachingList($conditions='') {
    
       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT count(*) as totalCount
				 FROM 
				 `employee` 
				 WHERE 
                 instituteId=$instituteId AND
                 isActive = 1
                 $conditions  " ;   
				 //instituteId = ".$sessionHandler->getSessionVariable('InstituteId')." 
                 //AND isActive = 1  ;
		//if($conditions)
			//$query .=" $conditions";
		
		//$query .=" GROUP BY roleName";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee role wise for print report
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (18.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getEmployeeTeachList($conditions='',$orderBy='') {
    
       global $sessionHandler;
        $query = "SELECT *
				 FROM 
				 `employee` emp, designation dsg, branch br
				 WHERE 
				 emp.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."   
				 $conditions 
				 AND dsg.designationId = emp.designationId
				 AND  isActive = 1   
				 AND br.branchId = emp.branchId ORDER BY $orderBy";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee marital status for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (18.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getEmployeeMaritalList($conditions='') {
    
       global $sessionHandler;
        $query = "SELECT 
				 COUNT(*) as totalCount,if(isMarried=0,'Un-Married','Married') as maritalStatus,isMarried
				 FROM `employee` 
				 WHERE
				 instituteId = ".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY isMarried";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
         public function getEmployeeMaritalStatusList($conditions='',$orderBy='') {
    
       global $sessionHandler;
        $query = "SELECT *
                 FROM 
                 `employee` emp, designation dsg, branch br  
                 WHERE 
                 emp.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                 $conditions
                 AND dsg.designationId = emp.designationId
                 AND br.branchId = emp.branchId ORDER BY $orderBy";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee city for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (18.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getEmployeeCityList($conditions='') {
    
       global $sessionHandler;
        $query = "SELECT 
				 COUNT(*) as totalCount,cty.cityId,cty.cityCode,cty.cityName
				 FROM `employee` emp, `city` cty
				 WHERE
				 emp.cityId = cty.cityId AND
				 instituteId = ".$sessionHandler->getSessionVariable('InstituteId'); 
		
		if($conditions)
			$query .=" $conditions";

		$query .=" GROUP BY emp.cityId";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

	public function getEmployeeCityWiseList($conditions='',$orderBy='') {
    
       global $sessionHandler;
        $query = "SELECT *
				 FROM 
				 `employee` emp, designation dsg, branch br,city cty  
				 WHERE 
				 emp.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
				 $conditions
                 AND emp.cityId = cty.cityId 
				 AND dsg.designationId = emp.designationId
				 AND br.branchId = emp.branchId ORDER BY $orderBy";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee state for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (18.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getEmployeeStateList($conditions='') {
    
       global $sessionHandler;
        $query = "SELECT 
				 COUNT(*) as totalCount,sta.stateId,sta.stateCode,sta.stateName
				 FROM `employee` emp, `states` sta
				 WHERE
				 emp.stateId = sta.stateId AND
				 instituteId = ".$sessionHandler->getSessionVariable('InstituteId'); 
		
		if($conditions)
			$query .=" $conditions";

		$query .=" GROUP BY sta.stateId";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

   
    public function getEmployeeStateWiseList($conditions='',$orderBy='') {
    
       global $sessionHandler;
        $query = "SELECT *
                 FROM 
                 `employee` emp, designation dsg, branch br,`states` sta  
                 WHERE 
                 emp.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                 $conditions
                 AND  emp.stateId = sta.stateId
                 AND dsg.designationId = emp.designationId
                 AND br.branchId = emp.branchId ORDER BY $orderBy";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee Gender list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getEmployeeGenderList($conditions='') {

	   global $sessionHandler;
       $query = "SELECT count( * ) as totalCount
				  FROM `employee`
				  WHERE 
				  instituteId =".$sessionHandler->getSessionVariable('InstituteId');
		if($conditions)
			$query .=" $conditions";
		
		$query .=" GROUP BY gender ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
    
    public function getEmployeeGenderWiseList($conditions='',$orderBy='') {
    
       global $sessionHandler;
        $query = "SELECT *
                 FROM 
                 `employee` emp, designation dsg, branch br  
                 WHERE 
                 emp.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                 $conditions
                 AND dsg.designationId = emp.designationId
                 AND br.branchId = emp.branchId ORDER BY $orderBy";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee designation list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getEmployeeDesignationList($conditions='') {

	   global $sessionHandler;
       $query = "SELECT count( * ) as totalCount, des.designationId, des.designationName, des.designationCode
				  FROM `employee` emp, `designation` des
				  WHERE 
				  emp.designationId = des.designationId AND
				  instituteId =".$sessionHandler->getSessionVariable('InstituteId');
		if($conditions)
			$query .=" $conditions";
		
		$query .=" GROUP BY emp.designationId ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the branch for employee list for pie chart
//
//$conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (18.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------         
    public function getEmployeeBranchList($conditions='') {
    
       global $sessionHandler;
       $query = "SELECT count( * ) as totalCount, br.branchId, br.branchCode, br.branchName 
				  FROM employee emp, branch br 
				  WHERE 
				   
				  emp.branchId = br.branchId AND
				  $conditions
				  emp.instituteId =".$sessionHandler->getSessionVariable('InstituteId');
				  
		$query .=" GROUP BY br.branchId ";
		        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }
    

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute notices for a management
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getMonthWiseEventList($conditions=''){

		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
		
		$query="SELECT COUNT(*) as totalCount,MONTH(startDate) as eventMonth  
				FROM 
				`event` 
				GROUP BY MONTH(startDate) 
				ORDER BY MONTH(startDate)" ;   
		//echo $query;       
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
	}

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute notices for a management
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getMonthEventList($conditions='', $orderBy=' receiptNo ASC'){

		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
		
		$query="SELECT eventTitle, shortDescription,DATE_FORMAT(startDate, '%d-%b-%y') as startDate, DATE_FORMAT(endDate, '%d-%b-%y') as endDate  
				FROM 
				`event` 
				WHERE
				instituteId = $instituteId AND
				sessionId = $sessionId
				$conditions
				ORDER BY $orderBy" ;   
    
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
	}

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute notices for a management for particualr month
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (13.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getMonthWiseNoticeList($conditions='', $limit = '', $orderBy=' n.noticeText'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query="SELECT   
		   noticecount(*) as totalCount,MONTH(visibleFromDate) FROM  
		   notice
		   where instituteId = $instituteId
		   GROUP BY MONTH(visibleFromDate)
		   ORDER BY $orderBy $limit 
		" ;   
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute notices for a management
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (21.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getMonthNoticeList($conditions='', $orderBy=' n.noticeText'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query="SELECT 
		   n.noticeId,MONTH(visibleFromDate) as visibleMonth , noticeSubject ,noticeText , DATE_FORMAT(visibleFromDate, '%d-%b-%y') as visibleFromDate , DATE_FORMAT(visibleToDate, '%d-%b-%y') as visibleToDate,noticeAttachment,d.abbr,
			d.departmentName 
		   FROM 
		   notice n, notice_visible_to_role nvr,department d
		   WHERE 
		   n.noticeId=nvr.noticeId AND nvr.instituteId=$instituteId AND n.instituteId=$instituteId AND sessionId = $sessionId  
		   AND n.departmentId = d.departmentId
		   $conditions 
		   GROUP BY n.noticeId
		   ORDER BY $orderBy $limit 
		" ;   
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of class wise subject
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (21.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getClassWiseSubjectList($conditions=''){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query	="SELECT 
			 IF(subjectToClassId IS NULL,0,count(*)) as totalCount,
			 cls.classId,
			 cls.className  
			 FROM 
			 class cls 
			 LEFT JOIN subject_to_class stc ON(stc.classId = cls.classId) 
			 WHERE cls.instituteId = '$instituteId' AND 
			 cls.sessionId = '$sessionId' AND 
			 (cls.isActive =1 OR cls.isActive =3)
			 GROUP BY cls.classId " ;   
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}


//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of class wise subject
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (21.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getClassSubjectList($conditions='', $orderBy=' cls.classId'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query	="SELECT 
			 subjectName,subjectCode,IF(offered='0','No','Yes') as offered,
			 IF(optional='0','No','Yes') as optional,credits,
			 IF(midSemTestDate IS NULL,'--',DATE_FORMAT(midSemTestDate, '%d-%b-%y')) as midSemTestDate, IF(midSemTestDate IS NULL,'--',DATE_FORMAT(finalExamDate, '%d-%b-%y')) as finalExamDate,
			 className 
			 FROM 
			 `subject_to_class` stc, subject sub, `class` cls
			 WHERE 
			 stc.subjectId = sub.subjectId AND 
			 stc.classId = cls.classId AND
			 cls.instituteId = '$instituteId' AND 
			 cls.sessionId = '$sessionId' 
			 $conditions ORDER BY $orderBy" ;   
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

}
// $History: EmployeeManager.inc.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model/Management
//changed queries to add instituteId
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:02p
//Created in $/LeapCC/Model/Management
//Intial checkin
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 11/05/08   Time: 4:51p
//Updated in $/Leap/Source/Model/Management
//added new reports on management dashboard
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 10/30/08   Time: 2:37p
//Updated in $/Leap/Source/Model/Management
//updated management reports
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 10/24/08   Time: 5:51p
//Updated in $/Leap/Source/Model/Management
//added all student print reports
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 10/22/08   Time: 11:53a
//Updated in $/Leap/Source/Model/Management
//updated with validations for mangement role
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 10/20/08   Time: 3:40p
//Updated in $/Leap/Source/Model/Management
//updated with new pie charts
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 10/18/08   Time: 6:22p
//Updated in $/Leap/Source/Model/Management
//updated with employee graphs
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 10/17/08   Time: 5:21p
//Updated in $/Leap/Source/Model/Management
//updated section with section type
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 10/17/08   Time: 1:49p
//Updated in $/Leap/Source/Model/Management
//added functions for student pie chart for management role
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 10/15/08   Time: 6:21p
//Updated in $/Leap/Source/Model/Management
//updated fees collected module
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10/15/08   Time: 5:29p
//Updated in $/Leap/Source/Model/Management
//added new files as per management role
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10/15/08   Time: 10:11a
//Updated in $/Leap/Source/Model/Management
//added function to get total count for statistic for dashboard
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/13/08   Time: 2:07p
//Created in $/Leap/Source/Model/Management
//intial checkin
?>