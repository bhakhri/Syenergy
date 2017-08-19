<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class RoomAllocationManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "RoomAllocationManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "RoomAllocationManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
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
// THIS FUNCTION IS USED FOR ADDING A CITY
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addRoomAllocation($hostelCharges='0') {
		
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $pChkOut=trim($REQUEST_DATA['pChkOut'])!='' ? trim($REQUEST_DATA['pChkOut']) : '0000-00-00';
        $userId = $sessionHandler->getSessionVariable('UserId');
        
        $query = "SELECT s.studentId, s.classId, c.instituteId FROM `student` s, class c 
                  WHERE c.classId = s.classId AND s.studentId = '".$REQUEST_DATA['studentId']."'";
        $currentArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
        
        $query = "SELECT 
                        c.param, c.value
                  FROM 
                        `config` c
                  WHERE 
                        param IN ('INSTITUTE_ACCOUNT_NO','INSTITUTE_BANK_NAME') 
                        AND instituteId = '".$currentArray[0]['instituteId']."'";
        $configArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
        
        for($i=0;$i<count($configArray);$i++) {
          if($configArray[$i]['param'] == 'INSTITUTE_ACCOUNT_NO') {
            $instituteBankAccountNo = $configArray[$i]['value'];
          }
          if($configArray[$i]['param'] == 'INSTITUTE_BANK_NAME') {
            $instituteBankId = $configArray[$i]['value'];
          }
        }
        
        $query = "SELECT 
                        COUNT(*) AS cnt 
                  FROM 
                        `fee_receipt_master` 
                  WHERE 
                        studentId = '".$REQUEST_DATA['studentId']."' AND feeClassId = '".$REQUEST_DATA['classId']."'";
        $feeArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
        $feeQuery = '';
        $feeCondition = '';
        if($feeArray[0]['cnt'] > 0 ) {
          $feeQuery = " UPDATE fee_receipt_master SET ";  
          $feeCondition = " WHERE studentId = '".$REQUEST_DATA['studentId']."' AND feeClassId = '".$REQUEST_DATA['classId']."'";             
        }
        else {
          $feeQuery = " INSERT INTO fee_receipt_master SET ";  
        } 
                                 
        $comments = htmlentities(add_slashes(trim($REQUEST_DATA['comments'])));
        
        $query = "INSERT INTO `hostel_students` 
                  (hostelRoomId,studentId,dateOfCheckIn,userId,addedOnDate,possibleDateOfCheckOut,
                   securityAmount,securityStatus,dateOfCheckOut,classId,hostelCharges,comments, feeCycleId)
                  VALUES 
                  ('".$REQUEST_DATA['roomId']."','".$REQUEST_DATA['studentId']."','".$REQUEST_DATA['chkIn']."',
                   '".$userId."','".date('Y-m-d')."','".$pChkOut."','".$REQUEST_DATA['securityAmount']."',
                   '".$REQUEST_DATA['securityStatus']."','$pChkOut','".$REQUEST_DATA['classId']."','".$hostelCharges."','$comments',
                   '".$REQUEST_DATA['feeCycleId']."'
                   )";

        $returnArray = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($returnArray===false) {
          return false;  
        }
        
        
        $query = "$feeQuery
                    bankId = '$instituteBankId', 
                    instituteBankAccountNo = '$instituteBankAccountNo', 
                    studentId = '".$REQUEST_DATA['studentId']."' , 
                    currentClassId = '".$currentArray[0]['classId']."', 
                    feeClassId = '".$REQUEST_DATA['classId']."' ,
                    dated = 'now()' , 
                    receiptGeneratedDate = '0000-00-00 00:00:00', 
                    feeCycleId =  '".$REQUEST_DATA['feeCycleId']."' , 
                    hostelFees = '$hostelCharges' , 
                    hostelSecurity = '".$REQUEST_DATA['securityAmount']."', 
                    hostelId = '".$REQUEST_DATA['hostelId']."', 
                    hostelRoomId = '".$REQUEST_DATA['roomId']."', 
                    hostelFeeStatus = '0' , 
                    `status` = '1' , 
                    userId = '".$userId."' , 
                    instituteId = '".$currentArray[0]['instituteId']."', 
                    isHostelFee  = '1'
                  $feeCondition";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
    
    
  
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A CITY
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editRoomAllocation($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
                       
        //$chkOut=trim($REQUEST_DATA['chkOut'])!='' ? trim($REQUEST_DATA['chkOut']) : '0000-00-00';
        $userId = $sessionHandler->getSessionVariable('UserId');   
        
        $query = "SELECT s.studentId, s.classId, c.instituteId FROM `student` s, class c 
                  WHERE c.classId = s.classId AND s.studentId = '".$REQUEST_DATA['studentId']."'";
        $currentArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
        
        $query = "SELECT 
                        c.param, c.value
                  FROM 
                        `config` c
                  WHERE 
                        param IN ('INSTITUTE_ACCOUNT_NO','INSTITUTE_BANK_NAME') 
                        AND instituteId = '".$currentArray[0]['instituteId']."'";
        $configArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
        
        for($i=0;$i<count($configArray);$i++) {
          if($configArray[$i]['param'] == 'INSTITUTE_ACCOUNT_NO') {
            $instituteBankAccountNo = $configArray[$i]['value'];
          }
          if($configArray[$i]['param'] == 'INSTITUTE_BANK_NAME') {
            $instituteBankId = $configArray[$i]['value'];
          }
        }
        
        $query = "SELECT COUNT(*) AS cnt FROM `fee_receipt_master` 
                  WHERE studentId = '".$REQUEST_DATA['studentId']."' AND feeClassId = '".$REQUEST_DATA['classId']."'";
        $feeArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
        $feeQuery = '';
        $feeCondition = '';
        if($feeArray[0]['cnt'] > 0 ) {
          $feeQuery = " UPDATE fee_receipt_master SET ";  
          $feeCondition = " WHERE studentId = '".$REQUEST_DATA['studentId']."' AND feeClassId = '".$REQUEST_DATA['classId']."'";             
        }
        else {
          $feeQuery = " INSERT INTO fee_receipt_master SET ";  
        } 
                                 
        $comments = htmlentities(add_slashes(trim($REQUEST_DATA['comments'])));
        
          
        $query = "UPDATE 
                      `hostel_students` 
                   SET
                       hostelRoomId = '".$REQUEST_DATA['roomId']."',
                       classId   = '".$REQUEST_DATA['classId']."',
                       studentId   = '".$REQUEST_DATA['studentId']."',
                       dateOfCheckIn = '".$REQUEST_DATA['chkIn']."',
                       dateOfCheckOut= '".$REQUEST_DATA['chkOut']."',
                       modifyUserId  = '".$userId."',
                       securityAmount  = '".$REQUEST_DATA['securityAmount']."',
                       securityStatus = '".$REQUEST_DATA['securityStatus']."',
                       hostelCharges = '".$REQUEST_DATA['hostelCharges']."',
                       comments   = '".$comments."',
                       modifyOnDate = '".date('Y-m-d')."',
                       feeCycleId = '".$REQUEST_DATA['feeCycleId']."'
                   WHERE
                       hostelStudentId =  '".$REQUEST_DATA['hostelStudentId']."'";     

        $returnArray = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($returnArray===false) {
          return false;  
        }
        
        $query = "$feeQuery
                    bankId = '$instituteBankId', 
                    instituteBankAccountNo = '$instituteBankAccountNo', 
                    studentId = '".$REQUEST_DATA['studentId']."',  
                    feeClassId = '".$REQUEST_DATA['classId']."' ,
                    dated = 'now()' , 
                    feeCycleId =  '".$REQUEST_DATA['feeCycleId']."' , 
                    hostelFees = '".$REQUEST_DATA['hostelCharges']."' , 
                    hostelSecurity = '".$REQUEST_DATA['securityAmount']."', 
                    hostelId = '".$REQUEST_DATA['hostelId']."', 
                    hostelRoomId = '".$REQUEST_DATA['roomId']."', 
                    userId = '".$sessionHandler->getSessionVariable('UserId')."' , 
                    instituteId = '".$currentArray[0]['instituteId']."' 
                  $feeCondition";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
       
    }
    
    //update hostel & room in student table    
    public function updateRoomAllocationInStudentTable($studentId,$hostelId,$roomId,$classId='') {
       if(trim($hostelId)=='' and ($roomId)==''){
          $hostelId="NULL";
          $roomId="NULL";
       }
       
       $condition = "";
       if($classId != '') {
         $condition = " AND classId = $classId ";  
       }
       
       if(trim($hostelId)=='' and ($roomId)==''){
           $query="UPDATE student SET hostelFacility=1, hostelId=NULL, hostelRoomId=NULL WHERE studentId=$studentId $condition ";
       }
       else{
          $query="UPDATE student SET hostelFacility=1, hostelId=$hostelId, hostelRoomId=$roomId WHERE studentId=$studentId $condition";  
       }
       
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }       

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A CITY
//
//$cityId :cityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteRoomAllocation($hostelStudentId) {
     
        $query = "DELETE  FROM hostel_students WHERE hostelStudentId=$hostelStudentId";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getRoomAllocationList($conditions='', $limit = '', $orderBy=' studentName',$having='') {
     
      $query = "SELECT 
                         DISTINCT   hs.studentId, hs.classId, hs.hostelStudentId,hs.hostelRoomId,
                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                            IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                            IF(IFNULL(s.regNo,'')='','---',s.regNo)  AS regNo,
                            h.hostelCode, h.hostelName, c.className,			   
                            CONCAT(r.roomName,' (',hrt.roomType,'-',IFNULL(hf.amount,'0'),')') AS roomName,r.roomName as RoomName,
                            hs.dateOfCheckIn, 
                            IF(hs.dateOfCheckOut='0000-00-00','".NOT_APPLICABLE_STRING."',hs.dateOfCheckOut) AS dateOfCheckOut,
                            If(hs.dateOfCheckIn='0000-00-00','".NOT_APPLICABLE_STRING."',DATE_FORMAT(hs.dateOfCheckIn,'%d-%b-%y')) AS checkInDate,
                            If(hs.dateOfCheckOut='0000-00-00','".NOT_APPLICABLE_STRING."',DATE_FORMAT(hs.dateOfCheckOut,'%d-%b-%y')) AS checkOutDate,                                                
                            hs.hostelCharges, hs.securityAmount, s.classId AS currentClassId,
                            IFNULL(hs.comments,'') AS comments, hs.feeCycleId AS feeCycleId,
                            IFNULL(SUM(frd.amount),'".NOT_APPLICABLE_STRING."') AS paidAmount,
			                IF(s.corrAddress1 IS NULL OR s.corrAddress1='','', CONCAT(s.corrAddress1,' ',(SELECT cityName from city where city.cityId=s.corrCityId),' ',(SELECT stateName from states where states.stateId=s.corrStateId),' ',(SELECT countryName from countries where countries.countryId=s.corrCountryId),IF(s.corrPinCode IS NULL OR s.corrPinCode='','',CONCAT('-',s.corrPinCode)))) AS corrAddress,IF(s.studentMobileNo='','".NOT_APPLICABLE_STRING."',s.studentMobileNo) AS studentMobileNo,
                            IFNULL(frd.feeReceiptId,'') AS feeReceiptId,e.branchName,frd.receiptNo,s.dateOfBirth AS DOB,s.studentPhoto AS Photo, s.studentBloodGroup,
                            hs.isAllocation, hs.employeeId, IFNULL(hs.isPassStatus,'') AS isPassStatus, 
			IFNULL(hs.passIssueDate,'') AS passIssueDate, IFNULL(hs.passSrNo,'') AS passSrNo,  
			IFNULL(hs.passComments,'') AS passComments,  IFNULL(hs.passUserId,'') AS passUserId,
			IFNULL(fldc.debit,0) AS ledgerDebit,IFNULL(fldc.credit,0) AS ledgerCredit,fldc.ledgerTypeId
                     FROM 
                           hostel_room_type hrt, class c,student s,hostel h,hostel_room r, hostel_fees hf,branch e,
			  hostel_students hs LEFT JOIN fee_receipt_details frd ON 
                           (frd.studentId = hs.studentId AND frd.classId=hs.classId AND frd.isDelete=0 AND frd.feeType IN (3,4) )
			 LEFT JOIN fee_ledger_debit_credit fldc ON
			( hs.studentId = fldc.studentId AND hs.classId = fldc.classId AND fldc.ledgerTypeId ='3')
                     WHERE 
                           hrt.hostelRoomTypeId = r.hostelRoomTypeId  
                           AND hf.hostelId = r.hostelId
                           AND hf.roomTypeId = r.hostelRoomTypeId 
                           AND hf.classId = hs.classId
                           AND hs.classId = c.classId 
                           AND s.studentId=hs.studentId
                           AND hs.hostelRoomId=r.hostelRoomId
                           AND h.hostelId=r.hostelId
			   AND c.branchId = e.branchId			  
                           $conditions 
                     GROUP BY
                           hs.studentId, hs.classId    
                     $having                    
                 ORDER BY 
                        $orderBy 
                 $limit";
	
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getTotalRoomAllocation($conditions='',$having='') {
     
        $query = "SELECT
                       COUNT(tt.studentId) AS totalRecords                 
                  FROM     
                      (SELECT 
                            DISTINCT hs.studentId, hs.classId, hs.dateOfCheckIn,
                            hs.hostelCharges, hs.securityAmount, SUM(IFNULL(frd.amount,0)) AS paidAmount
                       FROM 
                            class c, student s, hostel_room r, hostel h, 
                            hostel_students hs LEFT JOIN fee_receipt_details frd ON 
                            (frd.studentId = hs.studentId AND frd.classId=hs.classId AND frd.isDelete=0 AND frd.feeType IN (3,4))
			   LEFT JOIN fee_ledger_debit_credit fldc ON
			( hs.studentId = fldc.studentId AND hs.classId = fldc.classId AND fldc.ledgerTypeId ='3')
                       WHERE                                        
                            s.studentId=hs.studentId 
                            AND hs.classId = c.classId 
                            AND hs.hostelRoomId=r.hostelRoomId
                            AND r.hostelId = h.hostelId
                            $conditions 
                       GROUP BY
                           hs.studentId, hs.classId
                       $having) AS tt";
                       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getStudentData($conditions='') {
     
        $query = "SELECT 
                        s.studentId,
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                        IF(IFNULL(s.regNo,'')='','---',s.regNo)  AS regNo,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                        s.hostelFacility
                 FROM 
                       student s, class c
                 WHERE 
                       s.classId=c.classId
                       AND c.isActive=1
                       $conditions 
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getStudentAllData($conditions='') {
     
        $query = "SELECT 
                        s.studentId,
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                        IF(IFNULL(s.regNo,'')='','---',s.regNo)  AS regNo,
                        c.className AS className, s.hostelFacility, 
                        c.degreeId, c.batchId, c.branchId
                 FROM 
                       student s, class c
                 WHERE 
                       s.classId=c.classId
                       $conditions ";
                       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getAllClass($conditions='') {
     
        $query = "SELECT 
                       classId, IF(isActive=1,CONCAT(className,' (Active)'),
                                    IF(isActive=2,CONCAT(className,' (Future)'),
                                      IF(isActive=3,CONCAT(className,' (Past)'),
                                         IF(isActive=4,CONCAT(className,' (Unused)'),'')))) AS className
                 FROM 
                       class c
                 WHERE 
                       $conditions ";
                       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function checkStudentPayData($condition='') {
       
       $query = "SELECT 
                       fm.isHostelPaid 
                 FROM 
                       fee_receipt_master fm, hostel_students hs
                 WHERE
                       hs.studentId = fm.studentId
                       AND hs.classId = fm.feeClassId      
                 $condition ";
                 
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getFeeCycleNew($condition='') {
       
       $query = "SELECT 
                       DISTINCT fc.feeCycleId, fc.cycleName, fc.fromDate, fc.toDate, fc.status,
                       CONCAT(fc.cycleName,' (',DATE_FORMAT(fc.fromDate,'%d-%b-%y'),' to ',
                              DATE_FORMAT(fc.toDate,'%d-%b-%y'),')','  ',IF(fc.status=1,'Active','Inactive')) AS cycleName1
                 FROM 
                       fee_cycle_new fc, class c
                 WHERE
                       fc.instituteId = c.instituteId AND 
			fc.sessionId = c.sessionId
                 $condition ";
               
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    
    public function checkStudentData($conditions='') {
     
        $query = "SELECT 
                        studentId, modifyOnDate, dateOfCheckIn, dateOfCheckOut
                 FROM 
                       hostel_students
                 $conditions ";
                 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //------------------------------------------------------------------------------------------------------    
    //this function is used to check whether related records exists in
    //hostel_discipline table or not
    //------------------------------------------------------------------------------------------------------
    public function checkDependency($conditions=''){
        $query=" SELECT
                        hd.studentId,
                        hd.hostelRoomId
                 FROM
                        hostel_discipline hd,
                        hostel_students hs
                 WHERE
                        hd.studentId=hs.studentId
                        AND hd.hostelRoomId=hs.hostelRoomId
                        $conditions
               ";
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
    public function checkRoomCapacity($conditions='') {
     
        $query = "SELECT 
                        hr.roomCapacity AS capacity
                 FROM 
                       hostel_room hr,hostel_room_type_detail hrtd
                 WHERE 
                       hr.hostelRoomTypeId=hrtd.hostelRoomTypeId
                       $conditions 
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function checkRoomCurrentCondition($conditions='') {
     
        $query = "SELECT 
                       COUNT(studentId) AS capacity
                  FROM 
                       hostel_students
                  WHERE
                       dateOfCheckOut = '0000-00-00' 
                       $conditions ";
                       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
     public function checkRoomCurrentStatus($conditions='',$dateCheckIn) {
     
        $query = "SELECT 
                       COUNT(studentId) AS capacity,  SUM(IF(isPay=0,1,0)) AS notPay,
                       SUM(IF('$dateCheckIn' >= dateOfCheckOut,1,0)) AS expectedCapacity
                  FROM 
                       hostel_students
                  WHERE
                       $conditions ";
                       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
       public function getRoomAllocationData($conditions='') {
     
        $query = "SELECT 
                        hs.studentId, hs.classId,
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                        IF(IFNULL(s.regNo,'')='','---',s.regNo)  AS regNo,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                        hs.hostelStudentId,
                        hs.hostelRoomId,
                        hs.dateOfCheckIn,
                        IF(hs.dateOfCheckOut='0000-00-00','',hs.dateOfCheckOut) AS dateOfCheckOut,
                        IF(hs.possibleDateOfCheckOut='0000-00-00','',hs.possibleDateOfCheckOut) AS possibleDateOfCheckOut,
                        hr.hostelId,hr.hostelRoomTypeId,hs.securityAmount,hs.securityStatus, hs.hostelCharges, 
                        hs.comments, hs.feeCycleId
                 FROM 
                        hostel_students hs,student s,class c,hostel_room hr
                 WHERE
                        hs.studentId=s.studentId
                        AND s.classId=c.classId
                        AND hs.hostelRoomId=hr.hostelRoomId
                        $conditions 
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

    public function getRoomTypeData($conditions='') {
     
        $query = "SELECT 
                         DISTINCT hrt.hostelRoomTypeId, hrt.roomType
                  FROM 
                         hostel_room_type hrt, hostel_room_type_detail hrtd, hostel h
                  WHERE 
                        h.hostelId = hrtd.hostelId
                        AND hrtd.hostelRoomTypeId = hrt.hostelRoomTypeId
                        $conditions
                        ORDER BY  hrt.roomType
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getRoomData($conditions='') {
     
        $query = "SELECT 
                         DISTINCT hr.hostelRoomId, 
                         CONCAT(hr.roomName,' (',hr.roomFloor,'-',hr.roomRent,')') AS roomName, hr.roomFloor, hr.roomRent
                  FROM 
                         hostel_room hr 
                  $conditions
                  ORDER BY  
                        LENGTH(trim(hr.roomName))+0,trim(hr.roomName)";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getRoomRentData($conditions='') {
     
        $query = "SELECT 
                         DISTINCT hr.hostelRoomId, 
                         CONCAT(hr.roomName,' (',hr.roomFloor,'-',IFNULL(hf.amount,'0'),')') AS roomName, hr.roomFloor, 
                         IFNULL(hf.amount,'0') AS roomRent
                  FROM 
                         hostel_room hr, hostel_fees hf
                  WHERE
                        hr.hostelId = hf.hostelId
                        AND hf.roomTypeId = hr.hostelRoomTypeId
                  $conditions
                  ORDER BY  
                        LENGTH(trim(hr.roomName))+0,trim(hr.roomName)";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     public function getRoomTypeDetailData($conditions='') {
     
        $query = "SELECT 
                         IF(hrtd.airConditioned=1,'Yes','No') AS airConditioned,
                         IF(hrtd.internetFacility=1,'Yes','No') AS internetFacility,
                         IF(hrtd.attachedBath=1,'Yes','No') AS attachedBath
                  FROM 
                         hostel_room_type_detail hrtd
                         $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
//-------------------------------------------------------------------------------
//Purpose : To fetch list of POSSIBLE vacant rooms
//Author : Dipanjan Bhattacharjee
// Created on : 27.04.2010
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------  
public function getPossibleVacantRoomList($conditions='', $limit = '', $orderBy=' hrt.roomType',$conditions2='') {
    
   $query = "SELECT 
                        hr.hostelRoomId,
                        h.hostelId,
                        h.hostelName,
                        hr.roomName, 
                        SUM(IF(hs.hostelRoomId IS NULL,0,1))  AS occupied, 
                        hr.roomCapacity, 
                        (hr.roomCapacity - SUM(IF(hs.hostelRoomId IS NULL,0,1)) ) AS vacant,
                        hrt.roomType
                 FROM 
                        hostel_room hr
                        LEFT JOIN hostel_students hs ON hs.hostelRoomId = hr.hostelRoomId  $conditions2 AND dateOfCheckOut='0000-00-00'
                        INNER JOIN hostel h ON h.hostelId = hr.hostelId
                        INNER JOIN hostel_room_type hrt ON hrt.hostelRoomTypeId = hr.hostelRoomTypeId 
                 $conditions
                 GROUP BY hr.hostelRoomId
                 ORDER BY $orderBy $limit
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------
// Purpose : To fetch total no of POSSIBLE vacant rooms
// Author : Dipanjan Bhattacharjee
// Created on : 27.04.2010
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------  
    public function getPossibleTotalVacantRoom($conditions='',$conditions2='') {
    
       $query = "SELECT 
                        COUNT(*) AS totalRecords
                 FROM 
                        hostel_room hr
                        LEFT JOIN hostel_students hs ON hs.hostelRoomId = hr.hostelRoomId  $conditions2 AND dateOfCheckOut='0000-00-00'
                        INNER JOIN hostel h ON h.hostelId = hr.hostelId
                        INNER JOIN hostel_room_type hrt ON hrt.hostelRoomTypeId = hr.hostelRoomTypeId
                        $conditions
                        GROUP BY hr.hostelRoomId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
public function getPossibleVacantRoomOccupantsList($conditions='', $limit = '', $orderBy=' hrt.roomType',$conditions2='') {
    
   $query = "SELECT 
                        hr.hostelRoomId,
                        h.hostelId,
                        s.studentId,
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                        IF(IFNULL(s.regNo,'')='','---',s.regNo)  AS regNo,
                        c.className,
                        hs.dateOfCheckIn
                 FROM 
                        hostel_room hr
                        LEFT JOIN hostel_students hs ON hs.hostelRoomId = hr.hostelRoomId  $conditions2 AND dateOfCheckOut='0000-00-00'
                        LEFT JOIN student s ON s.studentId=hs.studentId
                        LEFT JOIN class c ON c.classId=s.classId
                        INNER JOIN hostel h ON h.hostelId = hr.hostelId
                        INNER JOIN hostel_room_type hrt ON hrt.hostelRoomTypeId = hr.hostelRoomTypeId 
                 $conditions
                 ORDER BY $orderBy $limit
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------
// Purpose : To fetch total no of POSSIBLE vacant rooms
// Author : Dipanjan Bhattacharjee
// Created on : 27.04.2010
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------  
    public function getPossibleTotalVacantRoomOccupants($conditions='',$conditions2='') {
    
       $query = "SELECT 
                        COUNT(*) AS totalRecords
                 FROM 
                        hostel_room hr
                        LEFT JOIN hostel_students hs ON hs.hostelRoomId = hr.hostelRoomId  $conditions2 AND dateOfCheckOut='0000-00-00'
                        LEFT JOIN student s ON s.studentId=hs.studentId
                        LEFT JOIN class c ON c.classId=s.classId
                        INNER JOIN hostel h ON h.hostelId = hr.hostelId
                        INNER JOIN hostel_room_type hrt ON hrt.hostelRoomTypeId = hr.hostelRoomTypeId 
                 $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    
    public function checkOutOccupants($studentId,$roomId) {
        global $sessionHandler;
        $userId=$sessionHandler->getSessionVariable('UserId');
        $date=date('Y-m-d');
        $query = "UPDATE 
                        hostel_students 
                  SET
                        dateOfCheckOut='".$date."',
                        userId=$userId,
                        modifyOnDate='".$date."'
                  WHERE
                        studentId=$studentId
                        AND hostelRoomId=$roomId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
   public function deallocateHostelRoom($studentId) {
        global $sessionHandler;
        $userId=$sessionHandler->getSessionVariable('UserId');
        $date=date('Y-m-d');
        $query = "UPDATE 
                        student 
                  SET   hostelId=NULL,
                        hostelRoomId=NULL
                  WHERE
                        studentId=$studentId  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    } 
    
  
   public function deallocateHostelFee($studentId, $classId) {

        global $sessionHandler;

        $userId=$sessionHandler->getSessionVariable('UserId');
        $date=date('Y-m-d');

        $query = "UPDATE 
                        fee_receipt_master 
                  SET   
                        isHostelFee=0,
                        isHostelPaid=0,
                        hostelId=0,
                        hostelRoomId=0,
                        hostelFeeStatus=0,
                        hostelDues=0,
                        hostelFees=0,
                        hostelSecurity=0
                  WHERE
                        studentId='$studentId' AND feeClassId = '$classId' ";
                        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    } 
  
    public function getSecurityFeeCheck($condition='') {   
     
        $query = "SELECT 
                        COUNT(*) AS cnt
                  FROM 
                        `hostel_students` hs, student s
                  WHERE 
                        hs.studentId = s.studentId AND
                        hs.securityStatus = 1
                  $condition";
     
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
    } 
      
    public function getStudentClassData($condition='') {
        
            $query = "SELECT 
                        hs.studentId, hs.classId
                     FROM 
                        `hostel_students` hs, student s
                     WHERE 
                        hs.studentId = s.studentId 
                  $condition";
     
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
    }
    
    public function getHostelPayement($condition='') {
        
            $query = "SELECT 
                          COUNT(*) AS totalRecords
                      FROM 
                          fee_receipt_details
                       WHERE 
                           isDelete=0 AND feeType IN (3,4) 
                       $condition";
     
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
    }
    
      public function updateBusStatus($condition,$comments,$passSrNo='') {
        
        global $REQUEST_DATA;
        global $sessionHandler;   
        $userId = $sessionHandler->getSessionVariable('UserId');
        
        $comments = htmlentities(add_slashes(trim($comments)));
        $issueDate = date('Y-m-d');
        
        $fieldName = '';
        if($passSrNo!='') {
          $fieldName = ", passSrNo = '$passSrNo' ";  
        }
        
        $query = "UPDATE 
                     `hostel_students`
                  SET
                     passComments = passComments + '$comments',
                     passUserId =  '$userId',
                     passIssueDate = '$issueDate',
                     isPassStatus = isPassStatus + 1
                     $fieldName
                  WHERE
                     $condition   ";          
  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    } 
    
    public function getPassSrNo($condition='') { 
       
        global $REQUEST_DATA;
        global $sessionHandler;   
        
        $query = "SELECT   
                       MAX(CAST(passSrNo AS UNSIGNED)) AS passSrNo 
                  FROM
                      hostel_students";          
  
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    } 
    
      public function getHostelRoomData($condition='') { 
       
        global $REQUEST_DATA;
        global $sessionHandler;   
        
        $query = "SELECT   
                      hr.hostelRoomId, hr.roomName,
                      CONCAT(hr.roomName,' (',h.hostelName,')') AS hostelRoomName
                  FROM
                      hostel_room hr, hostel h
                  WHERE
                      hr.hostelId = h.hostelId      
                      $condition  
                   ORDER BY
                      CONCAT(h.hostelName,' (',hr.roomName,')')  ";          
  
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }   
    
    public function getRoomCheck($studentId='',$classId='') {
        
        global $REQUEST_DATA;
        global $sessionHandler;   
        
        $query = "SELECT   
                      DISTINCT studentId, feeClassId, hostelId, hostelRoomId
                  FROM
                      fee_receipt_master 
                  WHERE
                      studentId = '$studentId' AND
                      feeClassId = '$classId' ";          
  
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }  
    
    public function getStudentAlreadyExist($condition) {
        
        global $REQUEST_DATA;
        global $sessionHandler;   
        
        $query = "SELECT   
                      DISTINCT studentId, classId
                  FROM
                      hostel_students 
                  WHERE
                      $condition ";          
  
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }  
    
    public function getAllInstituteFeeCycle($condition='') {
       
       $query = "SELECT 
                       DISTINCT fc.feeCycleId, fc.cycleName, fc.fromDate, fc.toDate, fc.status,
                       CONCAT(fc.cycleName,' (',DATE_FORMAT(fc.fromDate,'%d-%b-%y'),' to ',
                              DATE_FORMAT(fc.toDate,'%d-%b-%y'),')','  ',IF(fc.status=1,'Active','Inactive')) AS cycleName1
                 FROM 
                       fee_cycle_new fc
                 WHERE
                       $condition 
                 ORDER BY
                       fc.status DESC, fc.fromDate ASC";
               
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getInstituteClass($condition='') {
       
       $query = "SELECT 
                       c.classId, c.instituteId
                 FROM 
                       class c
                 WHERE
                       $condition ";
               
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
       public function getGenerateStudentFeeValue($ttStudentId='',$ttClassId=''){
		  $query ="SELECT
               		*
                 FROM
                    `generate_student_fee` fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                   ";
  
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		
	}
	public function updateGenerateStudentFeeValue($ttStudentId='',$ttClassId='',$strQuery=''){
			$query = "UPDATE `generate_student_fee` 
    				SET	$strQuery 
    				WHERE	
    					studentId = '$ttStudentId'
    				AND	classId = '$ttClassId'
    				";
    		
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);		
			
		
	}
	public function insertGenerateStudentFeeValue($strQuery=''){
			$query = "INSERT INTO
							 `generate_student_fee` 
    				SET	
    						$strQuery ";
						  
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);		
			
		
	}  
}
?>
