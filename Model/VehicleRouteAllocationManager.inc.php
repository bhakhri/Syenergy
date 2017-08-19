<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Vehicle Route" 
// Author :NISHU BINDAL
// Created on : (28.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class VehicleRouteAllocationManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "VehicleRouteAllocationManager" CLASS
//
// Author :NISHU BINDAL
// Created on : (28.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------   
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "VehicleRouteAllocationManager" CLASS
//
// Author :NISHU BINDAL
// Created on : (28.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
 public function getStudentAllData($conditions='') {
     
        $query = "SELECT 
                        s.studentId,
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                        IF(IFNULL(s.regNo,'')='','---',s.regNo)  AS regNo,
                        c.className AS className, s.transportFacility, 
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
    


//update hostel & room in student table    
public function updateStudentTable($studentId) {
    
       $query="UPDATE student SET transportFacility=1 WHERE studentId='$studentId'";
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
  
    
 public function insertIntoBusStudentRouteMapping($busRouteStopMappingId,$busRouteId,$busStopId,$busStopCityId,$busRouteStudentMappingId='') {

		
        global $REQUEST_DATA;
        global $sessionHandler;   
       
          $userId = $sessionHandler->getSessionVariable('UserId');
        
    
 $comments = htmlentities(add_slashes(trim($REQUEST_DATA['comments'])));
        //$busRouteStopMappingId= trim($REQUEST_DATA['busRouteStopMappingId']);
        $employeeId  = trim($REQUEST_DATA['employeeId']);
        $seatNumber  = trim($REQUEST_DATA['seatNumber']);
        $validFrom  = trim($REQUEST_DATA['validFrom']);
        $validTo   = trim($REQUEST_DATA['validTo']);     
        $routeCharges    = trim($REQUEST_DATA['transportCharges']);
        $feeCycleId    = trim($REQUEST_DATA['feeCycleId']); 

if($busRouteStudentMappingId=='') {
            $query =  "INSERT INTO `bus_route_student_mapping` 					
                       (employeeId,busRouteStopMappingId,seatNumber,validFrom,validTo,routeCharges,
                        comments, busRouteId, busStopId, busStopCityId, feeCycleId,isAllocation) 
                       VALUES 
                       ('$employeeId','$busRouteStopMappingId','$seatNumber','$validFrom','$validTo','$routeCharges','$comments','$busRouteId','$busStopId','$busStopCityId','$feeCycleId','2')";
        }
        else {             
            $query =  "UPDATE `bus_route_student_mapping`                     
                       SET         
                          seatNumber= '$seatNumber',
                          validFrom= '$validFrom',
                          validTo= '$validTo',                         
                          routeCharges= '$routeCharges',
                           comments= '$comments',
                           busRouteId= '$busRouteId',
                           busStopId= '$busStopId',
                           busStopCityId= '$busStopCityId',
                           feeCycleId= '$feeCycleId'
                      WHERE
                           busRouteStudentMappingId = '$busRouteStudentMappingId' 
                            AND employeeId='$employeeId'
                           "; 
        }
     //echo $query;die;
    	$returnArray =SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($returnArray===false) {
          return false;  
        }
}
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR INSERTING DATA INTO BUS_ROUTE_STUDENT_MAPPING TABLE

// Author :Nishu Bindal 
// Created on : (1.Mar.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------- 
    public function insertIntoBusStopRouteMapping($busRouteStopMappingId,$busRouteId,$busStopId,$busStopCityId,$busRouteStudentMappingId='') {
    	
        global $REQUEST_DATA;
        global $sessionHandler;
        
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
        
        $studentId  = trim($REQUEST_DATA['studentId']);
        $seatNumber  = trim($REQUEST_DATA['seatNumber']);
        $validFrom  = trim($REQUEST_DATA['validFrom']);
        $validTo   = trim($REQUEST_DATA['validTo']);
        $classId    = trim($REQUEST_DATA['classId']);
        $routeCharges    = trim($REQUEST_DATA['transportCharges']);
        $feeCycleId    = trim($REQUEST_DATA['feeCycleId']); 
        
        if($busRouteStudentMappingId=='') {
            $query =  "INSERT INTO `bus_route_student_mapping` 					
                       (studentId,busRouteStopMappingId,seatNumber, validFrom,validTo,classId, routeCharges,
                        comments, busRouteId, busStopId, busStopCityId, feeCycleId) 
                       VALUES 
                       ('$studentId','$busRouteStopMappingId','$seatNumber','$validFrom','$validTo','$classId', 			'$routeCharges','$comments', '$busRouteId', '$busStopId', '$busStopCityId','$feeCycleId' )";
        }
        else {
            $query =  "UPDATE `bus_route_student_mapping`                     
                       SET
                          studentId = '$studentId',			
                          busRouteStopMappingId= '$busRouteStopMappingId',
                          seatNumber= '$seatNumber',
                          validFrom= '$validFrom',
                          validTo= '$validTo',
                          classId= '$classId',
                          routeCharges= '$routeCharges',
                           comments= '$comments',
                           busRouteId= '$busRouteId',
                           busStopId= '$busStopId',
                           busStopCityId= '$busStopCityId',
                           feeCycleId= '$feeCycleId'
                      WHERE
                           busRouteStudentMappingId = '$busRouteStudentMappingId' "; 
        }
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
                    feeCycleId =  '".$REQUEST_DATA['feeCycleId']."' , 
                    transportFees =  '$routeCharges',
                    busRouteId =  '".$busRouteId."' , 
                    busStopId =  '".$busStopId."', 
                    busStopCityId = '".$busStopCityId."', 
                    transportFeeStatus = 0,
                    `status` = '1' , 
                    userId = '".$sessionHandler->getSessionVariable('userId')."' , 
                    instituteId = '".$currentArray[0]['instituteId']."', 
                    isTransportFee   = '1'
                  $feeCondition";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CHECKING IF STUDENT IS ALREADY MAPPED
// Author :Nishu Bindal 
// Created on : (1.Mar.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------- 
    public function checkForAlReadyMapped($condition =''){
    	$query = "SELECT COUNT(studentId) As totalRecord FROM `bus_route_student_mapping` $condition";
    	 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }       


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A bus Route Student Mapping
// Author :Nishu Bindal
// Created on : (5.April.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteRootAllocation($busRouteStudentMappingId) {
     
        $query = "DELETE FROM `bus_route_student_mapping` WHERE busRouteStudentMappingId ='$busRouteStudentMappingId' ";
                        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }  

    public function updateFeeReceiptMaster($studentId,$classId) {

 	    $query = "UPDATE 
                      `fee_receipt_master`
		          SET
                      transportDues='0',
			          transportFees='0',
			          busRouteId='0',
			          busStopId ='0',
			          busStopCityId='0',
			          transportFeeStatus='0',
			          isTransportFee='0',
			          isTransportPaid='0',
                      transportSecurity ='0'
			       WHERE 
                      studentId ='$studentId' AND 
                      feeClassId = '$classId' ";
          
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT ID
// Author :Nishu Bindal
// Created on : (5.April.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------------------------- 
    public function getStudentId($busRouteStudentMappingId){
    	 $query ="SELECT studentId  
    	 		FROM	`bus_route_student_mapping` 
    	 		WHERE	busRouteStudentMappingId = '$busRouteStudentMappingId'";
    	
    	  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE STUDENT 
// Author :Nishu Bindal
// Created on : (5.April.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------------------------- 
    public function updateStudentTableData($studentId){
      
       $query="UPDATE 
                    student 
               SET 
                    transportFacility = '0', busStopId = 'NULL', busRouteId = 'NULL' 
               WHERE 
                    studentId='$studentId'";
       
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Mapped Student Vehicle Route
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Nishu Bindal
// Created on : (28.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    public function getVehicleRouteAllocationList($conditions='', $limit = '', $orderBy=' studentName',$having='') {
    
 
        $query = "SELECT 
                        s.studentId,brsm.busRouteStudentMappingId,brsm.seatNumber,
                        s.classId AS currentClassId, brsm.classId,brsm.isAllocation,
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                        IF(IFNULL(s.regNo,'')='','---',s.regNo)  AS regNo,
                        c.className,
                        bsc.cityName,bs.stopName,br.routeName,
                        brsm.validFrom,  brsm.validTo AS validTo,
                        If(brsm.validFrom='0000-00-00','".NOT_APPLICABLE_STRING."',DATE_FORMAT(brsm.validFrom,'%d-%b-%y')) AS validFrom1,
                        If(brsm.validTo='0000-00-00','".NOT_APPLICABLE_STRING."',DATE_FORMAT(brsm.validTo,'%d-%b-%y')) AS validTo1, 	
                        brsm.routeCharges,brsm.comments,brsm.feeCycleId,
                        IFNULL(SUM(IFNULL(frd.amount,0)),'".NOT_APPLICABLE_STRING."') AS paidAmount,
                        IFNULL(frd.feeReceiptId,'') AS feeReceiptId,'S' as allocationType  ,
			IFNULL(brsm.isPassStatus,'') AS isPassStatus, 
			IFNULL(brsm.passIssueDate,'') AS passIssueDate, IFNULL(brsm.passSrNo,'') AS passSrNo,  
			IFNULL(brsm.passComments,'') AS passComments,  IFNULL(brsm.passUserId,'') AS passUserId,
			IFNULL(fldc.debit,0) AS ledgerDebit,IFNULL(fldc.credit,0) AS ledgerCredit,fldc.ledgerTypeId

                  FROM 
                        `student` s,`class` c,`bus_route_new` br, `bus_stop_new` bs, 
                        `bus_stop_city` bsc , `bus_route_stop_mapping` bsm,   
                        `bus_route_student_mapping` brsm LEFT JOIN fee_receipt_details frd ON 
                        (frd.studentId = brsm.studentId AND frd.classId=brsm.classId AND frd.isDelete=0 AND frd.feeType IN (2,4))
			LEFT JOIN fee_ledger_debit_credit fldc ON 
			(brsm.studentId = fldc.studentId AND brsm.classId = fldc.classId  AND fldc.ledgerTypeId ='2')
                   WHERE 
                        brsm.classId = c.classId 
                        AND s.studentId = brsm.studentId
                        AND bsm.busStopId = bs.busStopId
                        AND br.busRouteId = bsm.busRouteId
                        AND bsm.busRouteStopMappingId = brsm.busRouteStopMappingId
                        AND bs.busStopCityId = bsc.busStopCityId
			
                        $conditions 
                 GROUP BY
                        brsm.studentId, brsm.classId 
		 $having  
                 ORDER BY 
                        $orderBy $limit";
	
/*                        
UNION
SELECT 
DISTINCT e.employeeId,brsm.busRouteStudentMappingId,brsm.seatNumber,'','',brsm.isAllocation,                         
CONCAT( IFNULL( e.employeeName, '' ) , ' ', IFNULL( e.middleName, '' ) , ' ', IFNULL( e.lastName, '' ) ) AS employeeName,IFNULL( e.employeeCode, '---' ) AS employeeCode,'', d.designationName AS designationName,   
bsc.cityName,bs.stopName,br.routeName,
brsm.validFrom,  brsm.validTo AS validTo,
If(brsm.validFrom='0000-00-00','".NOT_APPLICABLE_STRING."',DATE_FORMAT(brsm.validFrom,'%d-%b-%y')) AS validFrom1,
If(brsm.validTo='0000-00-00','".NOT_APPLICABLE_STRING."',DATE_FORMAT(brsm.validTo,'%d-%b-%y')) AS validTo1,     
brsm.routeCharges,brsm.comments,brsm.feeCycleId,
'','','E' as allocationType    
FROM 
`bus_route_new` br, `bus_stop_new` bs,`employee` e,`designation` d, 
`bus_stop_city` bsc , `bus_route_stop_mapping` bsm,
`bus_route_student_mapping` brsm LEFT JOIN fee_receipt_details frd ON 
(frd.studentId = brsm.studentId AND frd.classId=brsm.classId AND frd.isDelete=0 AND frd.feeType IN (2,4))
WHERE 
bsm.busStopId = bs.busStopId
AND e.employeeId=brsm.employeeId
AND br.busRouteId = bsm.busRouteId
AND bsm.busRouteStopMappingId = brsm.busRouteStopMappingId
AND bs.busStopCityId = bsc.busStopCityId
AND e.designationId=d.designationId
$conditions 
GROUP BY
brsm.employeeId                           
*/
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING COUNT OF VEHICLE ROUTE MAPPING
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Nishu Bindal
// Created on : (28.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    
    public function getTotalVehicleRouteAllocation($conditions='',$having='') {
     
        $query = "SELECT 
                       COUNT(*) AS totalRecords
                  FROM      
                      (SELECT
                            brsm.studentId,brsm.classId,SUM(IFNULL(frd.amount,0)) as paidAmount,brsm.routeCharges 
                       FROM 
                            `student` s, `bus_route_stop_mapping` bsm,`class` c,
                            `bus_route_student_mapping` brsm LEFT JOIN fee_receipt_details frd ON 
                            (frd.studentId = brsm.studentId AND frd.classId=brsm.classId AND frd.isDelete=0 AND frd.feeType IN (2,4))
			    LEFT JOIN fee_ledger_debit_credit fldc ON 
			(brsm.studentId = fldc.studentId AND brsm.classId = fldc.classId  AND fldc.ledgerTypeId ='2')
                       WHERE 
                            s.studentId = brsm.studentId  AND c.classId = brsm.classId                              
                            AND bsm.busRouteStopMappingId = brsm.busRouteStopMappingId
                            $conditions 
                       GROUP BY
                            brsm.studentId, brsm.classId
			$having ) AS tt ";
                            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function fetchStopNames($cityId){
    	$query = "SELECT  busStopId, stopName FROM `bus_stop_new` WHERE busStopCityId = '$cityId' ORDER BY stopName Asc";
    	 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUS ROUTE NAME ACORDING TO BUS STOP
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Nishu Bindal
// Created on : (28.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  
    public function fetchRootNames($stopId){
    	$query = "SELECT  br.busRouteId,br.routeName FROM `bus_route_new` br, `bus_route_stop_mapping` bsm 
    				WHERE	br.busRouteId = bsm.busRouteId
    				AND	bsm.busStopId = '$stopId' 
    				ORDER BY br.routeName Asc";
    	 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    


    
    
    public function getStudentData($conditions='') {
     
        $query = "SELECT 
                        s.studentId,
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                        IF(IFNULL(s.regNo,'')='','---',s.regNo)  AS regNo,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                 FROM 
                       student s,class c
                 WHERE 
                       s.classId=c.classId
                       AND c.isActive=1
                       $conditions 
                 ";echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING DATA OF STUDENT  VEHICLE ROUTE MAPPING
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Nishu Bindal
// Created on : (28.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  
    
       public function getStudentVehicleRouteData($conditions='') {
     
        $query = "SELECT 
                        s.studentId,brsm.busRouteStudentMappingId,
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                        IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                        IF(IFNULL(s.regNo,'')='','---',s.regNo)  AS regNo,
                        brsm.validFrom, c.classId ,
                        IF(brsm.validTo='0000-00-00','',brsm.validTo) AS validTo,
                        brsm.routeCharges,brsm.comments,brsm.feeCycleId ,
		                bsm.busRouteId,bsn.busStopCityId,bsn.busStopId,brsm.seatNumber,brsm.isAllocation 
                 FROM 
                        `bus_route_student_mapping` brsm,`bus_route_stop_mapping` bsm,student s,class c,`bus_stop_new` bsn
                 WHERE
                        brsm.studentId=s.studentId
                        AND brsm.classId = c.classId
                        AND	brsm.busRouteStopMappingId = bsm.busRouteStopMappingId
                        AND	bsm.busStopId = bsn.busStopId
                        $conditions";
            // echo $query;
//die;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

  public function getEmployeeVehicleRouteData($conditions='') {
     
        $query = "SELECT 
                                    DISTINCT  brsm.busRouteStudentMappingId,e.employeeId,brsm.routeCharges,brsm.comments,brsm.feeCycleId ,
                                    bsm.busRouteId,bsn.busStopCityId,bsn.busStopId,brsm.seatNumber,brsm.isAllocation ,
                                    CONCAT(IFNULL(e.employeeName,''),' ',IFNULL(e.middleName,''),' ',IFNULL(e.lastName,'')) AS employeeName,
                                    IFNULL( e.employeeCode, '---' ) AS employeeCode,d.designationName AS designationName ,  
                                    brsm.validFrom,
                                    IF(brsm.validTo='0000-00-00','',brsm.validTo) AS validTo
                                    
                         FROM 
                                    `bus_route_student_mapping` brsm,`bus_route_stop_mapping` bsm,student s,class c,`bus_stop_new` bsn,
                                    `employee` e,`designation` d
                         WHERE
                                       e.employeeId=brsm.employeeId                       
                                    AND	brsm.busRouteStopMappingId = bsm.busRouteStopMappingId
                                    AND	bsm.busStopId	= bsn.busStopId
                                    AND	e.designationId	= d.designationId
                                    $conditions";
            //echo $query;die;
//die;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR UPDATING Student BUS ROUTE STOP MAPPING
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Nishu Bindal
// Created on : (28.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------   
    public function editBusRouteStudentMapping($studentId,$busRouteStopMappingId,$busRouteStudentMappingId,$seatNumber,$validFrom,$validTo,$comments){
    	  $query = "UPDATE 
                      `bus_route_student_mapping` 
                   SET
                       busRouteStudentMappingId = '".$REQUEST_DATA['busRouteStudentMappingId']."',
                       classId   = '".$REQUEST_DATA['classId']."',
                       studentId   = '".$REQUEST_DATA['studentId']."',
                       validFrom = '".$REQUEST_DATA['validFrom']."',
                       validTo= '".$REQUEST_DATA['validTo']."',
                       modifyUserId  = '".$userId."',
                         seatNumber   = '".$REQUEST_DATA['seatNumber']."',
                       routeCharges = '".$REQUEST_DATA['routeCharges']."',
                       comments   = '".$comments."',
                       modifyDate = '".date('Y-m-d')."',
                       feeCycleId = '".$REQUEST_DATA['feeCycleId']."'
                   WHERE
                       busRouteStopMappingId =  '".$REQUEST_DATA['busRouteStopMappingId']."'";     

        $returnArray = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($returnArray===false) {
          return false;  
        }
          
    }
   
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUS ROUTE STOP MAPPING ID 
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Nishu Bindal
// Created on : (28.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------   
    public function getBusStopRouteMappingId($busStopId,$busRouteId){
        
    	$query = "SELECT 
                        DISTINCT brsm.busRouteStopMappingId, brsm.busRouteId,
                        bsn.busStopId, bsn.busStopCityId 
                  FROM 
                       `bus_route_stop_mapping` brsm, bus_stop_new bsn
                  WHERE 
                        brsm.busStopId = bsn.busStopId
                        AND brsm.busRouteId = '$busRouteId'  
                        AND brsm.busStopId = '$busStopId' ";
    	
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
    }
    

    public function checkStudentData($conditions='') {
     
        $query = "SELECT 
                      DISTINCT studentId, validFrom, validTo, modifyDate
                  FROM 
                      bus_route_student_mapping
                  $conditions ";
                 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function checkEmployeeData($conditions='') {
     
        $query = "SELECT 
                      employeeId, validFrom, validTo, modifyDate
                  FROM 
                      bus_route_student_mapping
                  $conditions ";
                 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function fetchRootNamesWithCharges($stopId='',$classId=''){
       
        $query = "SELECT  
                        DISTINCT br.busRouteId,br.routeName,
                        IFNULL((SELECT  amount FROM bus_fees bf WHERE bf.busRouteId = bsm.busRouteId AND bf.busStopCityId = bs.busStopCityId AND classId = '$classId' LIMIT 0,1),'0') AS transportCharges 
                    FROM 
                        `bus_route_new` br, `bus_route_stop_mapping` bsm, bus_stop_new bs, bus_stop_city bsc
                    WHERE
                        br.busRouteId = bsm.busRouteId
                        AND bsm.busStopId = bs.busStopId
                        AND bsc.busStopCityId = bs.busStopCityId
                        AND bsm.busStopId = '$stopId' 
                    ORDER BY 
                        br.routeName ASC";
                        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    public function fetchRootInfo($condition=''){
       
        $query = "SELECT  
                        DISTINCT br.busRouteId,br.routeName
                    FROM 
                        `bus_route_new` br, `bus_route_stop_mapping` bsm, bus_stop_new bs, bus_stop_city bsc, 
                        bus_route_student_mapping brsm
                    WHERE
                        brsm.busRouteId = bsm.busRouteId  
                        AND brsm.busStopCityId = bsc.busStopCityId
                        AND br.busRouteId = bsm.busRouteId
                        AND bsm.busStopId = bs.busStopId
                        AND bsc.busStopCityId = bs.busStopCityId
                    $condition    
                    ORDER BY 
                        br.routeName ASC";
                        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function checkForSeatNumber($condition =''){
      
        $query = "SELECT 
                        COUNT(*) AS cnt
                  FROM 
                        `bus_route_student_mapping` 
                  WHERE
                        $condition";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  


		 public function getStudentClassData($condition='') {
        
            $query = "SELECT 
                        DISTINCT brsm.studentId, brsm.classId
                     FROM 
                        `bus_route_student_mapping` brsm, student s
                     WHERE 
                        brsm.studentId = s.studentId 
                  $condition";
     
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
    }


 public function getTransportPayement($condition='') {
        
            $query = "SELECT 
                          COUNT(*) AS totalRecords
                      FROM 
                          fee_receipt_details
                       WHERE 
                          isDelete=0 AND feeType IN (2,4) 
                       $condition";
     
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
    }
    

   public function getEmployeeAllData($conditions='') {
     
             $query = "SELECT
                                    e.employeeId, 
                                    CONCAT( IFNULL( e.employeeName, '' ) , ' ', IFNULL( e.middleName, '' ) , ' ', IFNULL( e.lastName, '' ) ) AS employeeName, 
                                    IFNULL( e.employeeCode, '---' ) AS employeeCode, d.designationName AS designationName          
                            FROM 
                                        employee e, designation d
                        WHERE 
                                    e.designationId=d.designationId
                       $conditions ";
                     
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    
     public function getStudentAllocationList($conditions='',$orderBy='') {
     
        $query = "SELECT 
                        s.studentId,brsm.busRouteStudentMappingId,brsm.seatNumber,
                        s.classId AS currentClassId, brsm.classId,
                        UCASE(CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,''))) AS studentName,
                        UCASE(IF(IFNULL(s.rollNo,'')='','---',s.rollNo)) AS rollNo,
                        UCASE(IF(IFNULL(s.regNo,'')='','---',s.regNo))  AS regNo,
                        c.className,
                        bsc.cityName,bs.stopName,br.routeName,br.routeCode,
                        brsm.validFrom,  brsm.validTo AS validTo,
                        If(brsm.validFrom='0000-00-00','".NOT_APPLICABLE_STRING."',DATE_FORMAT(brsm.validFrom,'%d-%b-%y')) AS validFrom1,
                        If(brsm.validTo='0000-00-00','".NOT_APPLICABLE_STRING."',DATE_FORMAT(brsm.validTo,'%d-%b-%y')) AS validTo1,     
                        brsm.routeCharges,brsm.comments,brsm.feeCycleId,
                        IFNULL(SUM(IFNULL(frd.amount,0)+
                                   IFNULL(frd.tranportFine,0)),'".NOT_APPLICABLE_STRING."') AS paidAmount,
                        IFNULL(frd.feeReceiptId,'') AS feeReceiptId,   
                          REPLACE(TRIM(CONCAT(
IFNULL(s.corrAddress1,''),' ',IFNULL(s.corrAddress2,''),' ',
IFNULL((SELECT cityName FROM city WHERE city.cityId=s.corrCityId),''),' ',
IFNULL((SELECT stateName FROM states WHERE states.stateId=s.corrStateId),''),' ',
IFNULL((SELECT countryName FROM countries WHERE countries.countryId=s.corrCountryId),''),' ',
IFNULL(s.corrPinCode,''))),'  ',' ') AS corrAddress,
             REPLACE(TRIM(CONCAT(
IFNULL(s.permAddress1,''),' ',IFNULL(s.permAddress2,''),' ',
IFNULL((SELECT cityName FROM city WHERE city.cityId=s.permCityId),''),' ',
IFNULL((SELECT stateName FROM states WHERE states.stateId=s.permStateId),''),' ',
IFNULL((SELECT countryName FROM countries WHERE countries.countryId=s.permCountryId),''),' ',
IFNULL(s.permPinCode,''))),'  ',' ') AS permAddress,  
                        s.dateOfBirth AS DOB, s.studentPhoto AS Photo, s.studentBloodGroup,
                        IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
			            IF(s.studentMobileNo='','".NOT_APPLICABLE_STRING."',s.studentMobileNo) AS studentMobileNo,e.branchName,frd.receiptNo,e.branchCode,
                        brsm.isAllocation, 
IFNULL(brsm.isPassStatus,'') AS isPassStatus, 
IFNULL(brsm.passIssueDate,'') AS passIssueDate, IFNULL(brsm.passSrNo,'') AS passSrNo,  
IFNULL(brsm.passComments,'') AS passComments,  IFNULL(brsm.passUserId,'') AS passUserId
                 FROM 
                        `student` s, `class` c,`bus_route_new` br, `bus_stop_new` bs,branch e, 
                        `bus_stop_city` bsc , `bus_route_stop_mapping` bsm, 
                        `bus_route_student_mapping` brsm LEFT JOIN fee_receipt_details frd ON 
                        (frd.studentId = brsm.studentId AND frd.classId=brsm.classId AND frd.isDelete=0 AND frd.feeType IN (2,4))
                 WHERE 
                        brsm.studentId = s.studentId 
                        AND brsm.classId = c.classId
                        AND brsm.busRouteId = br.busRouteId
                        AND brsm.busStopId = bs.busStopId
                        AND brsm.busStopCityId = bsc.busStopCityId    
                        AND brsm.busRouteId = bsm.busRouteId
                        AND brsm.busStopId = bsm.busStopId
			AND c.branchId = e.branchId
                        $conditions 
                 GROUP BY
                        brsm.studentId, brsm.classId       
                 ORDER BY $orderBy";
                 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
 
  
    //employee fee cycle 
    public function getFeeCycleNew($condition='') {
       
       $query = "SELECT 
                       DISTINCT fc.feeCycleId, fc.cycleName, fc.fromDate, fc.toDate, fc.status,
                       CONCAT(fc.cycleName,' (',DATE_FORMAT(fc.fromDate,'%d-%b-%y'),' to ',
                       DATE_FORMAT(fc.toDate,'%d-%b-%y'),')','  ',IF(fc.status=1,'Active','Inactive')) AS cycleName1    
                 FROM 
                       fee_cycle_new fc
                 $condition ";
            
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
  
   
   public function getIsAllocation($condition='') {
       
       $query = "SELECT 
                                brsm.isAllocation
                        FROM 
                            bus_route_student_mapping brsm
                        $condition
               ";
          //  echo $query;
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
   public function getVehicleRouteAllocations($conditions='', $limit = '', $orderBy=' studentName') {
    
 
        $query = "SELECT 
                        s.studentId,brsm.busRouteStudentMappingId,brsm.seatNumber,
                        s.classId AS currentClassId, brsm.classId,brsm.isAllocation,
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                        IF(IFNULL(s.regNo,'')='','---',s.regNo)  AS regNo,
                        c.className,
                        bsc.cityName,bs.stopName,br.routeName,
                        brsm.validFrom,  brsm.validTo AS validTo,
                        If(brsm.validFrom='0000-00-00','".NOT_APPLICABLE_STRING."',DATE_FORMAT(brsm.validFrom,'%d-%b-%y')) AS validFrom1,
                        If(brsm.validTo='0000-00-00','".NOT_APPLICABLE_STRING."',DATE_FORMAT(brsm.validTo,'%d-%b-%y')) AS validTo1, 	
                        brsm.routeCharges,brsm.comments,brsm.feeCycleId,
                        IFNULL(SUM(IFNULL(frd.transportFeePaid,0)+
                        IFNULL(frd.tranportFine,0)),'".NOT_APPLICABLE_STRING."') AS paidAmount,
                        IFNULL(frd.feeReceiptId,'') AS feeReceiptId,'S' as allocationType,
			IFNULL(brsm.isPassStatus,'') AS isPassStatus, 
			IFNULL(brsm.passIssueDate,'') AS passIssueDate, IFNULL(brsm.passSrNo,'') AS passSrNo,  
			IFNULL(brsm.passComments,'') AS passComments,  IFNULL(brsm.passUserId,'') AS passUserId
  
                    FROM 
                        `student` s,`class` c,`bus_route_new` br, `bus_stop_new` bs, 
                        `bus_stop_city` bsc , `bus_route_stop_mapping` bsm,
                        `bus_route_student_mapping` brsm LEFT JOIN fee_receipt_details frd ON 
                        (frd.studentId = brsm.studentId AND frd.classId=brsm.classId AND frd.isDelete=0 AND frd.feeType IN (2,4))
                    WHERE 
                        brsm.classId = c.classId 
                        AND s.studentId = brsm.studentId
                        AND bsm.busStopId = bs.busStopId
                        AND br.busRouteId = bsm.busRouteId
                        AND bsm.busRouteStopMappingId = brsm.busRouteStopMappingId
                        AND bs.busStopCityId = bsc.busStopCityId
                        $conditions 
                    GROUP BY
                            brsm.studentId, brsm.classId  
      
                    ORDER BY 
                                $orderBy $limit";

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
                     `bus_route_student_mapping`
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
                      bus_route_student_mapping";          
  
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    } 
        
	public function getStudentAlreadyExist($condition='') {
		global $REQUEST_DATA;
        global $sessionHandler;   
        
        $query = "SELECT   
                      DISTINCT studentId, classId
                  FROM
                      bus_route_student_mapping 
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
