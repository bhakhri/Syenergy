<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class StudentEnquiryManager {
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

    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A CITY
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addCity($cityCode,$cityName,$stateId) {
       global $sessionHandler;
       global $REQUEST_DATA;
       
       if($sessionHandler->getSessionVariable('RoleId')!=1){ //if not admin
          if($REQUEST_DATA['userId']!=$sessionHandler->getSessionVariable('UserId')){ //if try to hack as if other user
              return false;
          }     
       }
       
       return SystemDatabaseManager::getInstance()->runAutoInsert('city', 
                 array('cityCode','cityName','stateId'), 
                 array(strtoupper($cityCode),$cityName,$stateId));
}

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getCity($conditions='') {
     
        $query = "SELECT 
                         cityCode
                  FROM   city
                  $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT STUDENT_enquiry
//
// Author :Dipanjan Bhattacharjee
// Created on : (29.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------   	

	public function addStudentEnquiry($cityId, $instituteId, $sessionId, $studentDomicile1, $studentCategory1,$stateId,$countryId,$studentNationality1) {
        
		global $sessionHandler;
        global $REQUEST_DATA;
       
      /* 
       if($sessionHandler->getSessionVariable('RoleId')!=1){ //if not admin
          if($REQUEST_DATA['userId']!=$sessionHandler->getSessionVariable('UserId')){ //if try to hack as if other user
              return false;
          }     
       }
      */  
       foreach($REQUEST_DATA as $key => $values) {
         $$key =($values=='' ? "''" : "'".add_slashes(trim($values))."'" );
       }
        
        if($cityId=='') {
          $cityId='NULL';
        }
        
        if($studentDomicile1=='') {
          $studentDomicile1='NULL';
        }
        
        if($studentCategory1=='') {
          $studentCategory1='NULL';
        }
        
        if($stateId=='') {
          $stateId ='NULL';  
        }
        
        if($countryId=='') {
          $countryId ='NULL';  
        }
        
        if($studentNationality1=='') {
          $studentNationality1 ='NULL'; 
        }
        
        
        $studentRemarks=trim($REQUEST_DATA['studentRemarks'])!='' ? trim(add_slashes($REQUEST_DATA['studentRemarks'])) :' ';
        /*studentStatus=3 :Decide Later  */
        
        $query = "INSERT INTO `student_enquiry` SET 
                            `candidateStatus` = $candidateStatus,
                            `enquiryDate`=$enquiryDate ,
                            `classId`=$degree , 
                            `firstName`=$studentFName, 
                            `lastName`=$studentLName, 
                            `dateOfBirth`=$studentDob , 
                            `studentGender`=$studentGender , 
                            `studentEmail`=$studentEmail , 
                            `nationalityId`=$studentNationality1 ,
                            `studentPhone`=$studentNo , 
                            `studentMobileNo`=$studentMobile ,
                            `domicileId`=$studentDomicile1, 
                            `quotaId`=$studentCategory1,
                            
                            `compExamRank`=$studentRank , 
                            `compExamBy`=$entranceExam, 
                            
                            `corrAddress1`=$correspondeceAddress1 ,
                            `corrAddress2`=$correspondeceAddress2 , 
                            `corrCountryId`=$countryId, 
                            `corrStateId`=$stateId , 
                            `corrCityId`=$cityId, 
                            `corrPinCode`=$correspondecePincode ,

                            `fatherName`=$fatherName,
                            `motherName`=$motherName,

                            `studentStatus`='3' ,  
                            `studentRemarks`='".$studentRemarks."',
                             addedByUserId=$userId,
                             `visitPurpose` = $visitPurpose,
                             `visitorName`  = $visitorName,
                             `visitSource`  = $visitSource,
                             `paperName`    = $paperName,
                             `instituteId`  = $instituteId,
                             `sessionId`    = $sessionId,
                             `compExamRollNo` = $compExamRollNo,
                             `applicationNo` = $applicationNo ";
         
        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO update STUDENT_enquiry
//
// Author :Dipanjan Bhattacharjee
// Created on : (29.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------       

    public function addCounseler($studentId, $startDate, $endDate, $counselingId,$verificationId ) {
        
       $query = "UPDATE `student_enquiry` SET 
                        `candidateStatus`      =  '5',
                        `counselingDate_start` =  '$startDate',
                        `counselingDate_end`   = '$endDate',
                        `counselingId`         = '$counselingId',
                        `verificationId`       = '$verificationId'
                 WHERE studentId IN (".$studentId.")";
         
       return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO update STUDENT_enquiry
//
// Author :Dipanjan Bhattacharjee
// Created on : (29.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------       

    public function editStudentEnquiry($studentId,$cityId, $instituteId, $sessionId, $studentDomicile1, $studentCategory1,$stateId,$countryId,$studentNationality1) {
        global $sessionHandler;
        global $REQUEST_DATA;
       
      /* if($sessionHandler->getSessionVariable('RoleId')!=1){ //if not admin
           if($REQUEST_DATA['userId']!=$sessionHandler->getSessionVariable('UserId')){ //if try to hack as if other user
              return false;
           }     
         }
      */
      
        foreach($REQUEST_DATA as $key => $values) {
           $$key =( $values=='' ? "''" : "'".add_slashes(trim($values))."'" );
        }
        
        if($cityId=='') {
            $cityId='NULL';
        }
        
        if($studentCategory1=='') {
          $studentCategory1='NULL';    
        }  
        
        if($studentDomicile1=='') {
          $studentDomicile1='NULL';    
        } 
        
        if($stateId=='') {
          $stateId ='NULL';  
        }
        
        if($countryId=='') {
          $countryId ='NULL';  
        }
        
        if($studentNationality1=='') {
          $studentNationality1 ='NULL'; 
        }
        
        $studentRemarks=trim($REQUEST_DATA['studentRemarks'])!='' ? trim(add_slashes($REQUEST_DATA['studentRemarks'])) :' ';
        /*studentStatus=3 :Decide Later  */
        
        $query = "UPDATE `student_enquiry` SET 
        `candidateStatus` = $candidateStatus,
        `enquiryDate`=$enquiryDate ,
        `classId`=$degree , 
        `firstName`=$studentFName, 
        `lastName`=$studentLName, 
        `dateOfBirth`=$studentDob , 
        `studentGender`=$studentGender , 
        `studentEmail`=$studentEmail , 
        `nationalityId`=$studentNationality1 ,
        `studentPhone`=$studentNo , 
        `studentMobileNo`=$studentMobile ,

        `domicileId`=$studentDomicile1, 
        `quotaId`=$studentCategory1,
        
        `compExamRank`=$studentRank , 
        `compExamBy`=$entranceExam, 
        
        `corrAddress1`=$correspondeceAddress1 ,
        `corrAddress2`=$correspondeceAddress2 , 
        `corrCountryId`=$countryId, 
        `corrStateId`=$stateId , 
        `corrCityId`=$cityId, 
        `corrPinCode`=$correspondecePincode ,

        `fatherName`=$fatherName,
        `motherName`=$motherName,

        `studentStatus`='3' ,  
        `studentRemarks`='".$studentRemarks."',
         addedByUserId=$userId,
         `visitPurpose` = $visitPurpose,
         `visitorName`  = $visitorName,
         `visitSource`  = $visitSource,
         `paperName`    = $paperName,
         `instituteId`  = $instituteId, 
         `sessionId`    = $sessionId,
         `compExamRollNo` = $compExamRollNo,
         `applicationNo` = $applicationNo 
         WHERE studentId=".$studentId;
         
        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }    




//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT DATA
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   		
	public function getStudentData($studentId) {
     
        $query = "SELECT
                        * 
                 FROM 
                       student_enquiry
                 WHERE 
                       studentId=$studentId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET states corresponding to a country
// Author :Dipanjan Bhattacharjee
// Created on : (29.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------           
    public function getStudentStates($countyId) {
     
        $query = "SELECT
                        stateId,stateName 
                 FROM 
                       states
                 WHERE 
                       countryId=$countyId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET cities corresponding to a state
// Author :Dipanjan Bhattacharjee
// Created on : (29.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------           
    public function getStudentCities($stateId) {
     
        $query = "SELECT
                        cityId,cityName 
                 FROM 
                       city
                 WHERE 
                       stateId=$stateId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }        




//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT LIST
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
    public function getStudentList($conditions='', $limit = '', $orderBy=' studentName') {
        
        global $sessionHandler;
        global $enquiryStatusArr;
        
        $sepratorLen = strlen(CLASS_SEPRATOR);
        $query = "SELECT 
                         DISTINCT 
                         IF(IFNULL(f.classId,'')='',
                            (SELECT IFNULL(SUBSTRING_INDEX(className,'-',-2),'---') AS className FROM class WHERE classId = a.classId),
                            (SELECT IFNULL(SUBSTRING_INDEX(className,'-',-2),'---') AS className FROM class WHERE classId = f.classId)) AS className1,  
                         IFNULL(a.studentId,'') AS studentId, a.enquiryDate, IFNULL(c.classId,'') AS classId,
                         CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName,
                         IF(IFNULL(a.fatherName,'')='','".NOT_APPLICABLE_STRING."',a.fatherName) AS fatherName,
                         IF(IFNULL(a.studentEmail,'')='','".NOT_APPLICABLE_STRING."',a.studentEmail) AS studentEmail,
                         IF(IFNULL(ct.cityName,'')='','".NOT_APPLICABLE_STRING."',ct.cityName) AS corrCityId,
                         IFNULL(SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-2),'".NOT_APPLICABLE_STRING."') AS className,
                         IF(IFNULL(a.studentMobileNo,'')='','".NOT_APPLICABLE_STRING."',a.studentMobileNo) AS studentMobileNo,
                         IF(IFNULL(a.studentPhone,'')='','".NOT_APPLICABLE_STRING."',a.studentPhone) AS studentPhone, 
                         u.userName,IF(IFNULL(u.displayName,'')='','".NOT_APPLICABLE_STRING."',u.displayName) AS displayName,
                         IF(IFNULL(compExamRollNo,'')='','".NOT_APPLICABLE_STRING."',compExamRollNo) AS compExamRollNo,
                         IF(IFNULL(compExamRank,'')='','".NOT_APPLICABLE_STRING."',compExamRank) AS compExamRank,
                         IF(IFNULL(compExamBy,'')='','".NOT_APPLICABLE_STRING."',compExamBy) AS compExamBy,
                         CONCAT(IF(IFNULL(a.corrAddress1,'')='','',a.corrAddress1),IF(IFNULL(a.corrAddress2,'')='','',CONCAT('<br/>',a.corrAddress2))) AS corrAddress1,
                         IFNULL(a.visitPurpose,'') AS visitPurpose,  IFNULL(a.visitorName,'') AS visitorName,
                         IFNULL(a.visitSource,'')  AS visitSource,   IFNULL(a.paperName ,'')  AS paperName, 
                         IFNULL(applicationNo,'') AS applicationNo, a.candidateStatus,
                         IF(a.candidateStatus=1,'".$enquiryStatusArr[1]."',
                         IF(a.candidateStatus=2,'".$enquiryStatusArr[2]."',
                         IF(a.candidateStatus=3,'".$enquiryStatusArr[3]."',
                         IF(a.candidateStatus=4,'".$enquiryStatusArr[4]."',
                         IF(a.candidateStatus=5,'".$enquiryStatusArr[5]."','".$enquiryStatusArr[1]."'))))) AS candidateStatus1,
                         IFNULL(a.candidateStatus,'1') AS candidateStatus,
                         a.counselingDate_start, a.counselingDate_end   
                  FROM        
                         student_enquiry a LEFT JOIN city ct ON a.corrCityId = ct.cityId
                         LEFT JOIN states s ON a.corrStateId = s.stateId
                         LEFT JOIN countries cn ON a.corrCountryId = cn.countryId
                         LEFT JOIN user u ON a.addedByUserId=u.userId
                         LEFT JOIN role r ON u.roleId=r.roleId
                         LEFT JOIN adm_fee_receipt f ON (f.studentId=a.studentId AND f.cancelStatus='N')   
                         LEFT JOIN class c ON (a.classId = c.classId OR f.classId = c.classId) AND 
                                               c.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'       
                  WHERE      
                         $conditions
                  ORDER BY  $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
                
    }
    	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT LIST
//
// Author :Parveen Sharma
// Created on : 29-05-09
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
        
    public function getStudentInformationList($studentId=''){
        
        global $sessionHandler;
        global $enquiryStatusArr;     
        
        $query = "SELECT 
                            DISTINCT s.studentId, cls.className, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                            IFNULL(s.studentMobileNo,'') AS studentMobileNo, 
                            s.studentEmail, s.studentGender, s.studentStatus, s.dateOfBirth,
                            c.nationalityName, st.stateName AS domicileName, q.quotaName, s.compExamRollNo,
                            s.compExamRank, s.compExamBy, s.studentPhoto, IFNULL(s.studentPhone,'') As studentPhone, s.fatherName, s.motherName,
                            IFNULL(s.corrAddress1,'') AS corrAddress1, IFNULL(s.corrAddress2,'') AS corrAddress2, 
                            cct.cityName AS corrCity, cs.stateName AS corrState, cc.nationalityName AS corrCountry,
                            IFNULL(s.corrPinCode,'') AS corrPinCode, s.studentRemarks, s.enquiryDate,
                            st.stateName as domicile,
                            IFNULL(s.visitPurpose,'') AS visitPurpose,  IFNULL(s.visitorName,'') AS visitorName,
                            IFNULL(s.visitSource,'')  AS visitSource,   IFNULL(s.paperName ,'')  AS paperName,
                            IFNULL(applicationNo,'') AS applicationNo,
                            IF(s.candidateStatus=1,'".$enquiryStatusArr[1]."',
                            IF(s.candidateStatus=2,'".$enquiryStatusArr[2]."',
                            IF(s.candidateStatus=3,'".$enquiryStatusArr[3]."',
                            IF(s.candidateStatus=4,'".$enquiryStatusArr[4]."',
                            IF(s.candidateStatus=5,'".$enquiryStatusArr[5]."','".$enquiryStatusArr[1]."'))))) AS candidateStatus1,
                            IFNULL(s.candidateStatus,'1') AS candidateStatus
                        FROM 
                            class cls, degree deg, 
                            branch br, batch bt, study_period sp, university un, student_enquiry s 
                            LEFT JOIN countries c ON ( s.nationalityId = c.countryId )
                            LEFT JOIN states st ON ( s.domicileId = st.stateId )            
                            LEFT JOIN quota q ON ( s.quotaId = q.quotaId )
                            LEFT JOIN `user` u ON ( s.addedByUserId = u.userId )
                            LEFT JOIN countries cc ON ( s.corrCountryId=cc.countryId )
                            LEFT JOIN states cs ON ( s.corrStateId=cs.stateId )
                            LEFT JOIN city cct ON ( s.corrCityId = cct.cityId )
                        WHERE 
                            studentId=$studentId 
                            AND s.classId=cls.classId 
                            AND cls.universityId=un.universityId 
                            AND cls.degreeId=deg.degreeId 
                            AND cls.branchId=br.branchId 
                            AND cls.batchId=bt.batchId 
                            AND cls.studyPeriodId=sp.studyPeriodId
                            AND cls.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
                            AND cls.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."'" ;
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
    } 
    
        
        
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET TOTAL NUMBER OF STUDENTS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   	

    public function getTotalStudent($conditions='') {
        global $sessionHandler;
		$query = "SELECT 
						   COUNT(DISTINCT a.studentId) AS totalRecords
                  FROM                                  
                           student_enquiry a 
                           LEFT JOIN class c ON a.classId = c.classId 
                           LEFT JOIN user u ON a.addedByUserId=u.userId
                           LEFT JOIN role r ON u.roleId=r.roleId
                  WHERE      
                           $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
   
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A student enquiry
//$studentId :studentId of the student_enquiry
// Author :Dipanjan Bhattacharjee 
// Created on : (29.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------     
   public function deleteStudentEnquiry($studentId){
       global $sessionHandler;
       $condition='';
       if($sessionHandler->getSessionVariable('RoleId')!=1){ //if not admin
          $condition=' AND addedByUserId='.$sessionHandler->getSessionVariable('UserId');
       }
       $query = "DELETE 
                 FROM 
                       student_enquiry 
                 WHERE 
                       studentId=$studentId
                       $condition ";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
   }
   
   
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING Candidate Details
//
// Author :Parveen Sharma
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addCandidate($insertValues) {
        
        $query = "INSERT INTO student_enquiry SET ".$insertValues;
        
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query" );
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR UPDATING Candidate Details
//
// Author :Parveen Sharma
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function updateCandidate($insertValues) {
        
        $query = "UPDATE student_enquiry SET ".$insertValues;
        
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query" );
    }
   
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT ENquiry DATA
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------           
    public function getStudentEnquiryData($condition='',$orderBy='',$limit='') {
     
        $query = "SELECT
                        * 
                  FROM 
                       student_enquiry 
                  $condition 
                  $orderBy $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------
// THIS FUNCTION IS USED INSERT FEE DETAIL IN TRANSACTION
//
// Author :Parveen Sharma
// Created on : (07.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function insertFeeDetailsInTransaction($insertValues) {
        
        $query = 'INSERT INTO adm_fee_receipt SET '.$insertValues;
            
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query" ); 
    }  
    

//-------------------------------------------------------
// THIS FUNCTION IS USED INSERT FEE CANCELLATION DETAIL IN TRANSACTION
//
// Author :Parveen Sharma
// Created on : (07.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function insertFeeCancellationDetailsInTransaction($insertValues) {
        
        $query = 'INSERT INTO adm_fee_receipt_cancellation SET '.$insertValues;
            
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query" ); 
    }     
    
    
//-------------------------------------------------------
// THIS FUNCTION IS USED INSERT FEE DETAIL IN TRANSACTION
//
// Author :Parveen Sharma
// Created on : (07.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function updateStudentStatus($tableName,$insertValues,$condition) {
        
        $query = "UPDATE $tableName SET $insertValues $condition";
            
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query" ); 
    } 
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET ALL PROGRAM OF A CANDIDATE BASED ON CANDIDATE CATEGORY
//
//$candidateId : unique id of Candidate
// Author :Parveen Sharma
// Created on : (15.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getReceiptNo() {
    
        $query = "SELECT receiptNo FROM adm_fee_receipt ORDER BY receiptNo DESC ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        
    }       
        
//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET CANDIDATE DETAIL
//$conditions :db clauses
// Author :Vimal Sharma 
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------                
   public function getCandidateAllDetail($conditions='') {
     
        $query = "SELECT 
                        c.classId, c.className, c.degreeDuration, d.degreeName, b.branchName, 
                        CONCAT(d.degreeName,' ',b.branchName) AS programName, sp.periodName,
                        afr.receiptNo, afr.receiptDate, afr.cashAmount, afr.ddAmount, afr.totalAmountPaid, 
                        afr.ddNo, afr.ddDate, afr.ddBankName, afr.userId, afr.remarks,
                        CONCAT(IFNULL(se.firstName,''),' ',IFNULL(lastName,'')) AS studentName, se.studentMobileNo, 
                        se.studentEmail, se.studentGender, IFNULL(se.fatherName,'') AS fatherName, se.studentGender,
                        IFNULL(se.motherName,'') AS motherName, IFNULL(se.guardianName,'') AS guardianName,
                        se.applicationNo, se.studentId,
                        IF(se.candidateStatus=1,'".$enquiryStatusArr[1]."',
                        IF(se.candidateStatus=2,'".$enquiryStatusArr[2]."',
                        IF(se.candidateStatus=3,'".$enquiryStatusArr[3]."',
                        IF(se.candidateStatus=4,'".$enquiryStatusArr[4]."',
                        IF(se.candidateStatus=5,'".$enquiryStatusArr[5]."','".$enquiryStatusArr[1]."'))))) AS candidateStatus1,
                        IFNULL(se.candidateStatus,'1') AS candidateStatus
                  FROM 
                        student_enquiry se, adm_fee_receipt afr,class c, degree d, branch b, study_period sp
                  WHERE
                        afr.studentId = se.studentId
                        AND c.classId = afr.classId
                        AND d.degreeId = c.degreeId
                        AND b.branchId = c.branchId
                        AND sp.studyPeriodId = c.studyPeriodId
                  $conditions";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }      
   
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT ENquiry DATA
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------           
    public function getStudentEnquiryFeeData($condition1='',$condition='',$orderBy='',$limit='') {
     
        $query = "SELECT 
                       se.studentId,se.classId,se.enquiryDate,se.firstName,se.lastName,se.studentMobileNo,se.studentEmail,
                       se.studentGender,se.studentStatus,se.dateOfBirth, se.nationalityId,se.domicileId,se.quotaId,se.compExamRollNo,
                       se.compExamRank,se.compExamBy,se.studentPhoto,se.fatherName,se.motherName,se.corrAddress1,
                       se.corrAddress2,se.corrCountryId,se.corrStateId,se.corrCityId,se.corrPinCode,se.studentRemarks,
                       se.addedByUserId,se.fatherMobileNo,se.motherMobileNo,se.guardianMobileNo,se.hostelFacility,se.applicationNo,
                       se.guardianName,se.studentPhone,se.visitPurpose,se.visitorName,se.visitSource,se.paperName,
                       se.instituteId,se.sessionId,se.counselingDate_start,se.counselingDate_end,se.candidateStatus,se.counselingId,se.scheduleId,
                       af.classId AS classId1, IFNULL(af.feeReceiptId,'') AS feeReceiptId, af.receiptNo,af.receiptDate,af.cashAmount,
                       af.ddAmount,af.totalAmountPaid,af.ddNo,af.ddDate,af.ddBankName,af.userId,af.remarks,
                       CONCAT(IFNULL(se.firstName,''),' ',IFNULL(se.lastName,'')) AS studentName
                  FROM 
                       student_enquiry se LEFT JOIN adm_fee_receipt af ON se.studentId=af.studentId $condition1
                  $condition 
                  $orderBy $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING PROGRAM LIST
//
// Author :Parveen Sharma
// Created on : (20.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    public function getProgramAllotmentStatusList($conditions='', $limit = '', $orderBy=' c.className') {
     
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT 
                        DISTINCT CONCAT(d.degreeName,' ',b.branchName) AS programName, COUNT(s.candidateStatus) AS totalSeats
                  FROM 
                       degree d, branch b, class c LEFT JOIN student_enquiry s ON (c.classId = s.classId  AND s.candidateStatus = 2) 
                  WHERE 
                        c.instituteId = $instituteId 
                        AND c.sessionId = $sessionId
                        AND c.isActive = 1
                        AND d.degreeId = c.degreeId
                        AND b.branchId = c.branchId
                        AND c.classId IN (SELECT 
                                                DISTINCT cls.classId
                                          FROM 
                                                class cls,study_period sp
                                          WHERE 
                                                cls.instituteId='".$instituteId."' AND 
                                                cls.sessionId='".$sessionId."' AND 
                                                cls.isActive =1  AND 
                                                cls.studyPeriodId = sp.studyPeriodId AND
                                                sp.periodValue IN(1,3,5))
                  GROUP BY c.classId 
                  ORDER BY $orderBy ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
}//end of class


// for VSS
// $History: StudentEnquiryManager.inc.php $
//
//*****************  Version 22  *****************
//User: Parveen      Date: 4/14/10    Time: 11:23a
//Updated in $/LeapCC/Model
//validation and format updated
//
//*****************  Version 21  *****************
//User: Parveen      Date: 4/13/10    Time: 4:36p
//Updated in $/LeapCC/Model
//query and validation format updated
//
//*****************  Version 20  *****************
//User: Parveen      Date: 3/24/10    Time: 4:20p
//Updated in $/LeapCC/Model
//insertQuery udpated
//
//*****************  Version 19  *****************
//User: Parveen      Date: 3/24/10    Time: 3:53p
//Updated in $/LeapCC/Model
//condition format updated
//
//*****************  Version 18  *****************
//User: Parveen      Date: 3/23/10    Time: 6:34p
//Updated in $/LeapCC/Model
//getProgramAllotmentStatusList function added
//
//*****************  Version 16  *****************
//User: Parveen      Date: 3/10/10    Time: 3:46p
//Updated in $/LeapCC/Model
//getStudentInformationList function updated,
//addCandidateg, getStudentEnquiryData function added
//
//*****************  Version 15  *****************
//User: Parveen      Date: 3/05/10    Time: 4:58p
//Updated in $/LeapCC/Model
//validation & condition format updated 
//
//*****************  Version 14  *****************
//User: Parveen      Date: 3/05/10    Time: 1:07p
//Updated in $/LeapCC/Model
//getStudentList, editStudentEnquiry, addStudentEnquiry function updated
//
//*****************  Version 13  *****************
//User: Parveen      Date: 3/03/10    Time: 5:44p
//Updated in $/LeapCC/Model
//getStudentList function updated
//
//*****************  Version 12  *****************
//User: Parveen      Date: 3/03/10    Time: 11:35a
//Updated in $/LeapCC/Model
//editStudentEnquiry, addStudentEnquiry Query udpated (visitor details
//added)
//
//*****************  Version 11  *****************
//User: Parveen      Date: 3/02/10    Time: 1:03p
//Updated in $/LeapCC/Model
//new function added addCandidate
//
//*****************  Version 10  *****************
//User: Administrator Date: 3/06/09    Time: 17:22
//Updated in $/LeapCC/Model
//Done these modifications :
//
//1. My Time Table in Teacher: Add a link in the cell of Period/Day in My
//Time Table of teacher module, that takes the teacher to Daily
//Attendance interface and sets the value in Class, Subject,  and group
//DDMs from the time table. however, teacher will need to select Date and
//Period manually.
//
//2. Student Info in Teacher: Please add just "And/Or" between Name and
//Roll No search text boxes.
//
//3. Department wise Employee Selection in send messages links in teacher
//
//*****************  Version 9  *****************
//User: Parveen      Date: 6/02/09    Time: 5:29p
//Updated in $/LeapCC/Model
//getStudentList left join added
//
//*****************  Version 8  *****************
//User: Administrator Date: 1/06/09    Time: 17:18
//Updated in $/LeapCC/Model
//Updated student enquiry module
//
//*****************  Version 7  *****************
//User: Parveen      Date: 5/30/09    Time: 7:15p
//Updated in $/LeapCC/Model
//studentlist query udpate
//
//*****************  Version 6  *****************
//User: Administrator Date: 30/05/09   Time: 17:57
//Updated in $/LeapCC/Model
//Corrected bugs
//
//*****************  Version 5  *****************
//User: Parveen      Date: 5/30/09    Time: 5:44p
//Updated in $/LeapCC/Model
//enquiryDate added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 5/30/09    Time: 11:31a
//Updated in $/LeapCC/Model
//getStudentInformationList, getStudentList function enquiryDate added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/30/09    Time: 11:26a
//Updated in $/LeapCC/Model
//getStudentList function update (studentPhone field name added) 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/29/09    Time: 6:01p
//Updated in $/LeapCC/Model
// function added getStudentInformationList
//
//*****************  Version 1  *****************
//User: Administrator Date: 29/05/09   Time: 16:51
//Created in $/LeapCC/Model
//Created "Student Enquiry" module

?> 