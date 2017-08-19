<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the "FEE HEAD VALUES" Module
// Author :Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class GenerateFeeManager {
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
//  This function is used to fetch all branches
// Author :Nishu Bindal
// Created on : 21-Mar-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    
      public function fetchAllBranches($degreeId){
      	 global $sessionHandler;
    	$query ="SELECT
    			DISTINCT	
    			b.branchId,b.branchCode 
    		FROM	`branch` b , `class` c
    		WHERE	c.branchId = b.branchId
    		AND	c.instituteId = '".$sessionHandler->getSessionVariable('InstituteId')."'
    		AND	c.degreeId = $degreeId
    		ORDER BY b.branchCode ASC
    		";
    		
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------
//  This function is used to fetch all batches
// Author :Nishu Bindal
// Created on : 21-Mar-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function fetchAllBatches($condition){
    	 global $sessionHandler;
    	$query ="SELECT		DISTINCT b.batchId,b.batchName
    			FROM	`batch` b , `class` c
    			WHERE	c.batchId = b.batchId
    			AND	b.instituteId = c.instituteId
    			AND	b.instituteId = '".$sessionHandler->getSessionVariable('InstituteId')."'
    			$condition
    			ORDER BY b.batchName ASC
    	";
    	
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------
//  This function is used to fetch all Classes
// Author :Nishu Bindal
// Created on : 21-Mar-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function fetchClases($condition = ''){
    	$query ="SELECT		DISTINCT c.classId,c.className
    			FROM	`class` c
    				$condition
    		ORDER BY c.className Asc";
    		
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");	
    }
    
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to fetch student Fee Class
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------
    public function getClass($feeClassId){
        
    	$query = "SELECT 
		              classId, studyPeriodId ,batchId, isActive,
                      (SELECT studyPeriodId FROM class WHERE classId='$feeClassId') AS feeStudyPeriodId
		          FROM 
			          class cc
		          WHERE
                  	  CONCAT_WS(',',cc.batchId,cc.degreeId,cc.branchId) 
			          IN 
			          (SELECT CONCAT_WS(',',c.batchId,c.degreeId,c.branchId) FROM class c WHERE c.classId = '$feeClassId')
			      ORDER BY 
                      classId DESC";
		
	    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getCheckStudentMigration($studentId='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        if($studentId=='') {
          $studentId='0';  
        }
        $query = "SELECT    
                     s.studentId, s.isMigration, s.migrationClassId, s.migrationStudyPeriod
                  FROM
                     student s
                  WHERE
                     s.studentId = '$studentId'   ";
     
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getMigrationStudyPeriod($classId='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        if($classId=='') {
          $classId='0';  
        }
        $query = "SELECT    
                     c.classId, sp.periodValue
                  FROM
                      class c, study_period sp
                  WHERE
                      sp.studyPeriodId = c.studyPeriodId AND c.classId = '$classId'   ";
     
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getStudentDetails($currentClassId='', $condition1='', $condition2='') {
        
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		
        if($currentClassId=='') {
          $currentClassId='0';  
        }
        
        $query = "SELECT
                        DISTINCT stu.studentId,  stu.quotaId,stu.hostelId,stu.hostelRoomId, stu.isLeet,stu.regNo,
                        stu.fatherName, cls.classId, cls.instituteId, 
                        CONCAT(stu.firstName,' ',stu.lastName) As studentName, 
                        cls.className AS className, cls.studyPeriodId,cls.batchId, 
                        IF(IFNULL(stu.transportFacility,'')='',0,stu.transportFacility)  AS transportFacility,    
                        IF(IFNULL(stu.hostelFacility,'')='',0,stu.hostelFacility) AS hostelFacility, 
                        IF(IFNULL(brsm.busStopId,'')='','0',brsm.busStopId) AS busStopId,
                        stu.isMigration, stu.migrationClassId,brsm.busRouteStopMappingId,
                        ii.instituteName, ii.instituteCode, ii.instituteAbbr                                    
                  FROM
                        institute ii,  class cls ,
                        `student` stu LEFT JOIN `bus_route_student_mapping` brsm ON brsm.studentId = stu.studentId 
                  WHERE
                        ii.instituteId  = cls.instituteId          
                        AND stu.classId = cls.classId
                  	    AND	cls.classId IN ($currentClassId)
                        $condition2
                  ORDER BY 
                        studentName";
 	
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getStudentDetailsNew($currentClassId='', $condition1='', $condition2='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        if($currentClassId=='') {
          $currentClassId='0';  
        }
        
        $query = "SELECT
                        DISTINCT stu.studentId,  stu.quotaId,stu.hostelId,stu.hostelRoomId, stu.isLeet,stu.regNo,
                        stu.fatherName, cls.classId, cls.instituteId, CONCAT(stu.firstName,' ',stu.lastName) AS studentName, 
                        cls.className AS className, cls.studyPeriodId,cls.batchId, 
                        IF(IFNULL(stu.transportFacility,'')='',0,stu.transportFacility)  AS transportFacility,    
                        IF(IFNULL(stu.hostelFacility,'')='',0,stu.hostelFacility) AS hostelFacility, 
                        stu.isMigration, stu.migrationClassId, ii.instituteName, ii.instituteCode, ii.instituteAbbr                                    
                  FROM
                        institute ii,  class cls, student stu
                  WHERE
                        ii.instituteId  = cls.instituteId          
                        AND stu.classId = cls.classId
                        AND cls.classId IN ($currentClassId)
                        $condition2
                  ORDER BY 
                        studentName";
     
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to Get Bank Name 
// Author :Nishu Bindal
// Created on : (4.Mar.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
   public function getInstituteBankName($bankId){
   	  
      $query ="SELECT	bankAbbr FROM `bank` WHERE bankId = '$bankId'";
      
   	  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to fetch student Fee Concession
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
   public function getStudentAdhocConcession($condition='') {  
        
      global $sessionHandler;
       
      $instituteId = $sessionHandler->getSessionVariable('InstituteId');
      $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
      $query = "SELECT
                    acm.adhocId, acm.dateOfEntry, acm.studentId, acm.feeClassId, 
                    acm.userId, acm.description, acm.adhocAmount
                FROM
                    adhoc_concession_master_new acm
                WHERE 
                    acm.instituteId = '$instituteId'
                $condition";
          
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
   public function getStudentAdhocConcessionNew($condition='') {  
        
      global $sessionHandler;
       
      $instituteId = $sessionHandler->getSessionVariable('InstituteId');
      $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
      $query = "SELECT
                    acm.adhocId, acm.dateOfEntry, acm.studentId, acm.feeClassId, 
                    acm.userId, acm.description, acm.adhocAmount
                FROM
                    adhoc_concession_master_new acm
                WHERE 
                    $condition";
          
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
    
              //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to fetch student Fee Head wise
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    public function getStudentFeeHeadDetail($classId,$quotaId,$isLeet,$studentId,$isMigrated) {  
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
      //fh.instituteId = '$instituteId' AND fh.sessionId = '$sessionId' AND
        $query = "SELECT
		    	        DISTINCT tt.feeId, tt.feeHeadId, tt.feeHeadAmt, tt.classId, 
			                    tt.headName, tt.headAbbr, tt.sortingOrder, tt.isSpecial, tt.isConsessionable, tt.isRefundable,tt.quotaId,tt.isLeet
				  FROM
				    (SELECT
					        fhv.feeHeadValueId AS feeId, fhv.feeHeadId, fhv.feeHeadAmount AS feeHeadAmt, fhv.classId, 
					        fh.headName, fh.headAbbr, fh.sortingOrder, fh.isSpecial, fh.isConsessionable, fh.isRefundable,fhv.quotaId,fhv.isLeet
					FROM
					        fee_head_new fh,fee_head_values_new fhv  
					WHERE
				            fhv.feeHeadId  = fh.feeHeadId AND
				            fhv.instituteId  = fh.instituteId AND
				            fhv.sessionId  = fh.sessionId AND
				            fhv.classId    = '$classId'  AND
				            fh.isSpecial  = 0
				            AND fhv.quotaId = CASE when fhv.quotaId = '$quotaId' THEN '$quotaId' ELSE 0 END 
		 			        AND if(fhv.isLeet < 3 ,fhv.isLeet = CASE WHEN fhv.isLeet = '$isLeet' THEN '$isLeet' ELSE 2 END,fhv.isLeet = case 
		 			    	when fhv.isLeet = '$isMigrated' THEN '$isMigrated' ELSE -2 END)  
					UNION
					SELECT
					        smc.feeMiscId AS feeId, smc.feeHeadId, smc.charges  AS feeHeadAmt, smc.classId, 
					        fh.headName, fh.headAbbr, fh.sortingOrder, fh.isSpecial, fh.isConsessionable, fh.isRefundable,fh.instituteId,fh.sessionId
					FROM 
                            fee_head_new fh,student_misc_fee_charges_new smc  
					WHERE
					    	fh.feeHeadId = smc.feeHeadId 
						    AND smc.classId = '$classId' 
						    AND smc.studentId = '$studentId'
						    AND fh.instituteId = smc.instituteId	    
						    AND fh.sessionId = smc.sessionId
                            AND fh.isSpecial  = '1'
				) AS tt 
			    ORDER BY
			        tt.sortingOrder ASC, tt.quotaId DESC";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to fetch student Hostel Fees
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------
    public function getStudentHostelFee($studentId,$classId){
    	$query = "	SELECT 	
    					hf.amount AS roomRent , hs.securityAmount
    				FROM	 `hostel_fees` hf, `hostel_room` hr, `student` s LEFT JOIN `hostel_students` hs ON hs.studentId = s.studentId AND hs.hostelRoomId = s.hostelRoomId AND hs.dateOfCheckOut LIKE '0000-00-00' AND hs.securityStatus = 0
    				WHERE	hf.hostelId = hr.hostelId
    				AND	s.hostelId = hf.hostelId
    				AND	hr.hostelId = s.hostelId 
    				AND	s.hostelRoomId = hr.hostelRoomId
    				AND	hf.roomTypeId = hr.hostelRoomTypeId
    				AND	s.studentId = '$studentId'
    				AND	hf.classId = '$classId'
    				";
    			
    	 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to GET STUDENT TRANSPORTAION FEES
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------
    public function getStudentTransportFee($studentId,$feeClassId,$busRouteStopMappingId){
    	$query ="SELECT 	bf.amount AS transportFee,brsm.busStopId, bf.busRouteId
    			FROM 	 `bus_route_stop_mapping` brsm,  `bus_fees` bf,`bus_stop_new` bsn
    			WHERE	brsm.busRouteStopMappingId = '$busRouteStopMappingId' 
    			AND	brsm.busStopId = bsn.busStopId
    			AND	bsn.busStopCityId = bf.busStopCityId
    			AND	brsm.busRouteId = bf.busRouteId
    			AND	bf.classId = '$feeClassId'
    			";
    			
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getFeeMasterId($studentId='',$feeClassId='') {
        
        $query = "SELECT 
                         feeReceiptId
                  FROM 
                        `fee_receipt_master` 
                  WHERE 
                        studentId = '".$studentId."' AND feeClassId = '".$feeClassId."'";
                        
        return  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
     public function insertIntoFeeMaster($studentId,$currentClass,$feeClassId,$feeCycleId,$concession='0'){
        
        global $REQUEST_DATA;
        global $sessionHandler;
        
        if($concession=='') {
          $concession='0';  
        }
        
        $userId = $sessionHandler->getSessionVariable('UserId');     
        
        $query = "SELECT s.studentId, s.classId, c.instituteId FROM `student` s, class c 
                  WHERE c.classId = s.classId AND s.studentId = '".$studentId."'";
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
                        studentId = '".$studentId."' AND feeClassId = '".$feeClassId."'";
        $feeArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
        
        $feeQuery = '';
        $feeCondition = '';
        $fieldName = "";
        if($feeArray[0]['cnt'] > 0 ) {
          $feeQuery = " UPDATE fee_receipt_master SET ";  
          $feeCondition = " WHERE studentId = '".$studentId."' AND feeClassId = '".$feeClassId."'";             
        }
        else {
          $feeQuery = " INSERT INTO fee_receipt_master SET ";  
          $fieldName = " receiptGeneratedDate = '0000-00-00 00:00:00', "; 
        } 
        
        $query = "$feeQuery
                    bankId = '$instituteBankId', 
                    instituteBankAccountNo = '$instituteBankAccountNo', 
                    studentId = '".$studentId."' , 
                    currentClassId = '".$currentClass."', 
                    feeClassId = '".$feeClassId."',
                    concession = '".$concession."',
                    dated = 'now()' , 
                    $fieldName
                    feeCycleId =  '".$feeCycleId."' , 
                    `status` = '1' , 
                    userId = '".$userId."' , 
                    instituteId = '".$currentArray[0]['instituteId']."', 
                    isAcademicFee = '1'
                  $feeCondition";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to INSERT INTO FEE RECEIPT MASTER
// Author :Nishu Bindal
// Created on : (28.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------  
    public function insertIntoFeeReceiptMaster($values){
    	$query = "INSERT INTO `fee_receipt_master` 
                               (feeReceiptId,bankId,instituteBankAccountNo,studentId,currentClassId,feeClassId,feeCycleId,concession,
    							hostelFees,hostelId,hostelRoomId,transportFees,busRouteId,busStopId,
    							status,userId,instituteId,hostelFeeStatus,transportFeeStatus,dated,hostelSecurity,receiptGeneratedDate)
    				VALUES $values";
    	
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to INSERT INTO FEE RECEIPT INSTRUMENT
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
    public function insertIntoReceiptInstrument($values){
    	
        $query ="INSERT INTO `fee_receipt_instrument` 
                (feeReceiptInstrumentId,feeReceiptId,studentId,classId,feeHeadId,feeHeadName,amount,feeStatus)
    			VALUES $values";
    	
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to CHECK IF FEE IS ALREADY DEFINED FOR THIS CLASS
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
    public function checkForAlreadyGenerated($feeClassId,$currentClass,$feeCycleId){
    	global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
    	$query ="SELECT	COUNT(feeReceiptId) AS cnt 
    			FROM	`fee_receipt_master`
    			WHERE	feeClassId = '$feeClassId'
    			AND	feeCycleId = '$feeCycleId'
    			AND	instituteId = '$instituteId'
    			AND	status = 1
    			AND	currentClassId IN ($currentClass)";
     	
     	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Check If Student has Generated Fee
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------   
    
    public function checkForReceiptGenerated($feeClassId,$currentClass,$feeCycleId){
    	global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    	$query =" SELECT COUNT(feeReceiptId) as cnt 
    			FROM	`fee_receipt_master`
    			WHERE	feeClassId = '$feeClassId'
    			AND	feeCycleId = '$feeCycleId'
    			AND	instituteId = '$instituteId'
    			AND	currentClassId IN($currentClass)
    			AND	status = 1
    			AND	(receiptGeneratedDate <> '0000-00-00 00:00:00' OR receiptGeneratedDate <> NULL)";
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    
    }
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Check If Student has Paid Fee
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------     
    public function checkForPaidFee($feeClassId,$currentClass,$feeCycleId){
    		$query = "SELECT COUNT(frd.feeReceiptDetailId) AS cnt
    					FROM	`fee_receipt_details` frd, `fee_receipt_master` frm
    					WHERE	frd.feeReceiptId = frm.feeReceiptId
    					AND	frd.classId = frm.feeClassId	
    					AND	frd.classId = '$feeClassId'
    					AND	frm.feeCycleId = '$feeCycleId'
    					AND	frm.currentClassId IN($currentClass)
    					AND	frm.status = 1
    					AND	frd.isDelete = 0";
    		
    		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO LOGICALLY DELETE FEE CLASS
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
    public function deleteClassFee($feeClassId,$currentClass,$feeCycleId){
    	global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    	
    	$query ="UPDATE `fee_receipt_master`
    		            set status = 0 
    	         WHERE	
                       feeClassId = '$feeClassId'
    			       AND	feeCycleId = '$feeCycleId'
    			       AND	instituteId = '$instituteId'
    			       AND	currentClassId IN($currentClass)";
    	
    	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
     public function checkStudentFeeCycle($condition=''){
         
        $query = "SELECT 
                        DISTINCT feeCycleId
                    FROM  
                        fee_head_values_new  
                    WHERE
                        $condition ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function checkStudentFeeGenerate($condition=''){
         
        $query = "SELECT 
                        DISTINCT studentId, classId
                    FROM  
                        `fee_receipt_instrument`   
                    WHERE
                        $condition ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function checkStudentFeeDetail($condition=''){
         
        $query = "SELECT 
                        DISTINCT studentId, classId
                    FROM  
                        `fee_receipt_details`   
                    WHERE
                        feeType IN (1,4) AND isDelete = 0
                        $condition ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function checkStudentFeeHeadDelete($studentId='',$feeClassId='') {
         
        $query = "DELETE FROM fee_receipt_instrument WHERE studentId = $studentId AND classId = $feeClassId ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);      
    }
    
    public function checkStudentInstrument($studentId='', $classId = ''){
         
        $query = "SELECT 
                        DISTINCT studentId, classId  
                    FROM  
                        `fee_receipt_instrument`   
                    WHERE
                        studentId = $studentId AND
                        classId = $classId ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
     public function checkStudentAcademicList($classId=''){
         
        $query = "SELECT 
                        DISTINCT s.studentId, '$classId' AS classId
                    FROM  
                        `student` s, class c  
                    WHERE
                        s.classId = c.classId AND
                        CONCAT_WS(',',c.batchId,c.degreeId,c.branchId)
                        IN 
                        (SELECT DISTINCT CONCAT_WS(',',cls.batchId,cls.degreeId,cls.branchId) FROM class cls WHERE cls.classId = '$classId') ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function checkPendingStudentAcademicList($classId='',$isLeetCondition=''){
         
        $query = "SELECT 
                        DISTINCT s.studentId, '$classId' AS classId
                  FROM  
                        class c, `student` s 
                        LEFT JOIN fee_receipt_instrument ff ON ff.studentId = s.studentId AND ff.classId = '$classId'
                  WHERE
                        s.classId = c.classId AND
                        IFNULL(ff.feeReceiptId,'') = '' AND
                        CONCAT_WS(',',c.batchId,c.degreeId,c.branchId)
                        IN 
                        (SELECT DISTINCT CONCAT_WS(',',cls.batchId,cls.degreeId,cls.branchId) FROM class cls WHERE cls.classId = '$classId') 
                  $isLeetCondition ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
      
}
//History : $

?>
