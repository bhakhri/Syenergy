<?php
//----------------------------------------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "student and quarantine_student" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (06.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class QuarantineStudentManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ScQuarantineStudentManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (06.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ScQuarantineStudentManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (06.11.2008)
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
    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING student LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (06.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getStudentList($conditions='', $limit = '', $orderBy=' studentName') {

       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
        $query = "SELECT 
                        s.studentId, universityRollNo, concat(firstName,' ',lastName) AS studentName,
                        IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(s.regNo IS NULL OR s.regNo='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,
                        IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        IF(s.dateOfBirth IS NULL OR s.dateOfBirth='' OR s.dateOfBirth='0000-00-00' ,'".NOT_APPLICABLE_STRING."',s.dateOfBirth) AS dateOfBirth,
                        IF(s.fatherName IS NULL OR s.fatherName='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                        SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) AS className,
                        deg.degreeAbbr,br.branchCode,stp.periodName
        FROM student s,class cl,degree deg,branch br,study_period stp,university uni
        WHERE s.classId=cl.classId
        AND cl.degreeId=deg.degreeId 
        AND cl.branchId=br.branchId 
        AND cl.studyPeriodId=stp.studyPeriodId
        AND cl.universityId=uni.universityId
        AND cl.instituteId=".$instituteId." 
        AND cl.sessionId=".$sessionId." 
        $conditions 
        ORDER BY $orderBy $limit";
        
       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF students
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (06.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalStudent($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM student s,class cl,degree deg,branch br,study_period stp,university uni
        WHERE s.classId=cl.classId
        AND cl.degreeId=deg.degreeId 
        AND cl.branchId=br.branchId 
        AND cl.studyPeriodId=stp.studyPeriodId
        AND cl.universityId=uni.universityId
        AND cl.instituteId=".$instituteId." 
        AND cl.sessionId=".$sessionId." 
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR qurantine students
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (06.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------         
    public function quarantineStudents($studentIds) {
        
        //set foreignkey check OFF
        $fstr="SET SESSION foreign_key_checks = 0";
        SystemDatabaseManager::getInstance()->executeUpdateInTransaction($fstr); 
        
        $query="INSERT INTO quarantine_student 
               (
                 `studentId`,`classId`,`rollNo`,`regNo`,`isLeet`,`universityRollNo`,`universityRegNo`,`firstName`,`lastName`,`studentMobileNo`,
                 `studentEmail`,`studentGender`,`studentStatus`,`dateOfAdmission`,`dateOfLeaving`,`dateOfBirth`,`icardNumber`,`managementCategory`,
                 `managementReference`,`nationalityId`,`domicileId`,`quotaId`,`hostelId`,`hostelRoomId`,`busStopId`,`busRouteId`,`compExamRollNo`,
                 `compExamRank`,`compExamBy`,`studentPhoto`,`studentPhone`,`fatherTitle`,`fatherName`,`fatherOccupation`,`fatherMobileNo`,
                 `fatherAddress1`,`fatherAddress2`,`fatherCountryId`,`fatherStateId`,`fatherCityId`,`fatherPinCode`,`fatherEmail`,`fatherUserId`,
                 `motherTitle`,`motherName`,`motherOccupation`,`motherMobileNo`,`motherAddress1`,`motherAddress2`,`motherCountryId`,`motherStateId`,
                 `motherCityId`,`motherPinCode`,`motherEmail`,`motherUserId`,`guardianTitle`,`guardianName`,`guardianOccupation`,`guardianMobileNo`,
                 `guardianAddress1`,`guardianAddress2`,`guardianCountryId`,`guardianStateId`,`guardianCityId`,`guardianPinCode`,`guardianEmail`,
                 `guardianUserId`,`corrAddress1`,`corrAddress2`,`corrCountryId`,`corrStateId`,`corrCityId`,`corrPinCode`, `corrPhone`,`permAddress1`,
                 `permAddress2`, `permCountryId`,`permStateId`,`permCityId`,`permPinCode`,`permPhone`,`studentRemarks`,`userId`,
                 `referenceName`,`feeReceiptNo`,`studentBloodGroup`,`correspondenceAddressVerified` ,
                 `correspondenceAddressVerifiedBy`,`permanentAddressVerified`,`permanentAddressVerifiedBy`,
                 `fatherAddressVerified`,`fatherAddressVerifiedBy`,`motherAddressVerified` ,`motherAddressVerifiedBy`,
                 `guardianAddressVerified`,`guardianAddressVerifiedBy`,`studentSportsActivity`,`transportFacility`,
                 `hostelFacility`,`isMigration`,`migrationStudyPeriod`,`migrationClassId`
               )
             SELECT
                 `studentId`,`classId`,`rollNo`,`regNo`,`isLeet`,`universityRollNo`,`universityRegNo`,`firstName`,`lastName`,`studentMobileNo`,
                 `studentEmail`,`studentGender`,`studentStatus`,`dateOfAdmission`,`dateOfLeaving`,`dateOfBirth`,`icardNumber`,`managementCategory`,
                 `managementReference`,`nationalityId`,`domicileId`,`quotaId`,`hostelId`,`hostelRoomId`,`busStopId`,`busRouteId`,`compExamRollNo`,
                 `compExamRank`,`compExamBy`,`studentPhoto`,`studentPhone`,`fatherTitle`,`fatherName`,`fatherOccupation`,`fatherMobileNo`,
                 `fatherAddress1`,`fatherAddress2`,`fatherCountryId`,`fatherStateId`,`fatherCityId`,`fatherPinCode`,`fatherEmail`,`fatherUserId`,
                 `motherTitle`,`motherName`,`motherOccupation`,`motherMobileNo`,`motherAddress1`,`motherAddress2`,`motherCountryId`,`motherStateId`,
                 `motherCityId`,`motherPinCode`,`motherEmail`,`motherUserId`,`guardianTitle`,`guardianName`,`guardianOccupation`,`guardianMobileNo`,
                 `guardianAddress1`,`guardianAddress2`,`guardianCountryId`,`guardianStateId`,`guardianCityId`,`guardianPinCode`,`guardianEmail`,
                 `guardianUserId`,`corrAddress1`,`corrAddress2`,`corrCountryId`,`corrStateId`,`corrCityId`,`corrPinCode`, `corrPhone`,`permAddress1`,
                 `permAddress2`, `permCountryId`,`permStateId`,`permCityId`,`permPinCode`,`permPhone`,`studentRemarks`,`userId` ,
                 `referenceName`,`feeReceiptNo`,`studentBloodGroup`,`correspondenceAddressVerified` ,
                 `correspondenceAddressVerifiedBy`,`permanentAddressVerified`,`permanentAddressVerifiedBy`,
                 `fatherAddressVerified`,`fatherAddressVerifiedBy`,`motherAddressVerified` ,`motherAddressVerifiedBy`,
                 `guardianAddressVerified`,`guardianAddressVerifiedBy`,`studentSportsActivity`,`transportFacility`,
                 `hostelFacility`,`isMigration`,`migrationStudyPeriod`,`migrationClassId`
             
             FROM student WHERE student.studentId IN ($studentIds)";
           
           //insert into quarantine table
           $ret= SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
           
           $fstr="SET SESSION foreign_key_checks = 0";
           SystemDatabaseManager::getInstance()->executeUpdateInTransaction($fstr);
           
           return $ret;
    }
    
 public function deleteStudents($studentIds) {
        
        //set foreignkey check OFF
        $fstr="SET SESSION foreign_key_checks = 0";
        SystemDatabaseManager::getInstance()->executeUpdateInTransaction($fstr); 
        
        $query = "DELETE FROM student WHERE studentId IN ($studentIds)";
        $ret=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        
        $fstr="SET SESSION foreign_key_checks = 0";
        SystemDatabaseManager::getInstance()->executeUpdateInTransaction($fstr);
        
        return $ret;
    }
public function getStudentName($studentId) {
		 $systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = "SELECT * FROM student
				WHERE studentId = $studentId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}
    
public function fetchCurrentClassIds($studentIds){
    $query="SELECT CONCAT(\"'\",studentId,\"~\",classId,\"'\") AS studentClassIds FROM student WHERE studentId IN ($studentIds)";
    return SystemDatabaseManager::getInstance()->executeQuery($query);
}

 public function deleteFromStudentGroups($studentClassIds) {
        $query = "DELETE FROM student_groups WHERE CONCAT(studentId,'~',classId) IN ($studentClassIds)";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
 }
 
 public function deleteFromStudentOptionalGroups($studentClassIds) {
        $query = "DELETE FROM student_optional_subject WHERE CONCAT(studentId,'~',classId) IN ($studentClassIds)";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
 }
  
}
// $History: QuarantineStudentManager.inc.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 27/08/09   Time: 11:34
//Updated in $/LeapCC/Model
//Done bug fixing.
//bug ids---
//00001283,00001294,00001297
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 24/07/09   Time: 16:13
//Updated in $/LeapCC/Model
//Modified Quarantine and Restore Student Modules as new fields are added
//in "student" and "quarantine_student"  table
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 23/12/08   Time: 11:47
//Updated in $/LeapCC/Model
//Added subject and group dropdown in student filter
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/03/08   Time: 6:48p
//Created in $/LeapCC/Model
//Created quarantine student module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/06/08   Time: 5:12p
//Created in $/Leap/Source/Model
//Created Quarantine(delete) Student Module
?>
