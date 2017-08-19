<?php 
//-------------------------------------------------------
// This File contains Bussiness Logic of the feeDetail report
// Created By:  Harpreet
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class StudentGenerateFeeManager {
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
    
    
    public function showAllFeeClasses($condition=''){

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
      
        $query ="SELECT    
                        DISTINCT frm.classId AS feeClassId, 
                                 CONCAT(TRIM(SUBSTRING_INDEX(cls.className,'-',-1)),' (',fcn.cycleName,')') AS cycleName
                    FROM     
                        `fee_cycle_new` fcn , `fee_head_values_new` frm, class cls, study_period sp   
                    WHERE 
                        cls.classId = frm.classId   
                        AND fcn.feeCycleId = frm.feeCycleId
			            AND cls.studyPeriodId = sp.studyPeriodId                       
			                                    AND frm.classId IN (SELECT 
                                                 DISTINCT classId 
                                             FROM 
                                                 class cc 
                                             WHERE 
                                                 CONCAT_WS(',',cc.batchId,cc.degreeId,cc.branchId) IN 
                                                 (SELECT 
                                                      DISTINCT CONCAT_WS(',',c.batchId,c.degreeId,c.branchId) 
                                                  FROM 
                                                      student s, class c WHERE c.classId = s.classId 
						                         ))
			            
                    ORDER BY 
                       frm.classId";
                  
		 $query1 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                                 
         
         $query ="SELECT    
                        DISTINCT hs.classId AS feeClassId, 
                                 CONCAT(TRIM(SUBSTRING_INDEX(hc.className,'-',-1)),' (',f.cycleName,')') AS cycleName
                  FROM     
                        `fee_cycle_new` f , `hostel_students` hs, class hc
                  WHERE   
                        f.feeCycleId = hs.feeCycleId 
                        AND hc.classId = hs.classId
			           
                  ORDER BY
                       hs.classId";
                      
          $query2 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                                                   
          
          
          $query ="SELECT    
                        DISTINCT brsm.classId AS feeClassId, CONCAT(TRIM(SUBSTRING_INDEX(cc.className,'-',-1)),' (',ff.cycleName,')') AS cycleName
                    FROM     
                        `fee_cycle_new` ff , `bus_route_student_mapping` brsm, class cc
                    WHERE   
                        ff.feeCycleId = brsm.feeCycleId 
                        AND brsm.classId = cc.classId
			             
                  ORDER BY 
                      brsm.classId";
                   
          $query3 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");              
                        
          $valueArray = array();
          for($i=0;$i<count($query1);$i++) {
            $valueArray[]= array("feeClassId"=>$query1[$i]['feeClassId'],
                                   "cycleName"=>$query1[$i]['cycleName']
                                 );
          }                       
          for($i=0;$i<count($query2);$i++) {
            $feeClassId = $query2[$i]['feeClassId'];  
            $findId='';
            for($j=0;$j<count($valueArray);$j++) {
              if($valueArray[$j]['feeClassId']==$feeClassId) {
                $findId='1';
                break;  
              }
            }
            if($findId=='') {
               $valueArray[]= array("feeClassId"=>$query2[$i]['feeClassId'],
                                    "cycleName"=>$query2[$i]['cycleName']
                                    );
            }
          }
          
          for($i=0;$i<count($query3);$i++) {
            $feeClassId = $query3[$i]['feeClassId'];  
            $findId='';
            for($j=0;$j<count($valueArray);$j++) {
              if($valueArray[$j]['feeClassId']==$feeClassId) {
                $findId='1';
                break;  
              }
            }
            if($findId=='') {
               $valueArray[]= array("feeClassId"=>$query3[$i]['feeClassId'],
                                    "cycleName"=>$query3[$i]['cycleName']
                                    );
            }
          }
          return $valueArray;            
    }
   
     public function getStudentAllFeeCount($feeType='',$classId='',$condition='') {  
    
        global $sessionHandler;
        
        if($classId=='') {
          $classId='0';  
        }
       
        $returnArray1 = array();
        $returnArray2 = array();
        $returnArray3 = array();
        $valueArray = array();
    
            // Academic
            $query ="SELECT 
                         DISTINCT CONCAT_WS(',',s.studentId, f.classId ,sp.periodValue) AS studentClassId
                     FROM 
                         fee_head_values_new f 
                         INNER JOIN class c ON c.classId = f.classId 
                         INNER JOIN study_period sp ON sp.studyPeriodId = c.studyPeriodId 
                         LEFT JOIN student s ON 
                         IF(s.migrationStudyPeriod=0,
                           ((s.isLeet = 0 AND sp.periodValue<=2) OR sp.periodValue >2), (sp.periodValue>s.migrationStudyPeriod)
                         ) AND (INSTR(s.sAllClass,CONCAT('~',f.classId,'~'))>0) $condition
                         LEFT JOIN fee_receipt_details frd ON (s.studentId = frd.studentId AND frd.isDelete = 0 AND
                         frd.feeType IN(1,4) AND frd.classId = f.classId AND INSTR(s.sAllClass,CONCAT('~',frd.classId,'~'))>0) 
                     WHERE
                         f.classId IN ($classId) 
                          
                          
                     ORDER BY 
                         s.studentId, f.classId ";    
                                        
            $returnArray1 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
       
            // Transport
            $query ="SELECT 
                         DISTINCT CONCAT_WS(',',s.studentId, f.classId,sp.periodValue) AS studentClassId 
                     FROM 
                         bus_route_student_mapping f
                         INNER JOIN class c ON c.classId = f.classId 
                         INNER JOIN study_period sp ON sp.studyPeriodId = c.studyPeriodId 
                         LEFT JOIN student s ON (INSTR(s.sAllClass,CONCAT('~',f.classId,'~'))>0) $condition
                         LEFT JOIN fee_receipt_details frd ON (s.studentId = frd.studentId AND frd.isDelete = 0 AND
                         frd.feeType IN(2,4)  AND frd.classId = f.classId AND INSTR(s.sAllClass,CONCAT('~',frd.classId,'~'))>0) 
                     WHERE
                         f.classId IN ($classId) 
                          
                          
                     ORDER BY 
                         s.studentId, f.classId ";
						     
            $returnArray2 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     
            // Hostel
            $query ="SELECT 
                         DISTINCT CONCAT_WS(',',s.studentId, f.classId,sp.periodValue) AS studentClassId
                     FROM 
                         hostel_students f 
                         INNER JOIN class c ON c.classId = f.classId 
                         INNER JOIN study_period sp ON sp.studyPeriodId = c.studyPeriodId 
                         LEFT JOIN student s ON (INSTR(s.sAllClass,CONCAT('~',f.classId,'~'))>0) $condition
                         LEFT JOIN fee_receipt_details frd ON (s.studentId = frd.studentId AND frd.isDelete = 0 AND
                         frd.feeType IN(3,4)  AND frd.classId = f.classId AND INSTR(s.sAllClass,CONCAT('~',frd.classId,'~'))>0) 
                     WHERE
                         f.classId IN ($classId)                           
                          
                     ORDER BY 
                         s.studentId, f.classId ";
						
            $returnArray3 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      
        for($i=0;$i<count($returnArray1);$i++) {
          $valueArray[] = $returnArray1[$i]['studentClassId'];
         
        }
        for($i=0;$i<count($returnArray2);$i++) {
          $valueArray[] = $returnArray2[$i]['studentClassId'];
           
        }
        for($i=0;$i<count($returnArray3);$i++) {
          $valueArray[] = $returnArray3[$i]['studentClassId'];
           
        }
        
        if(count($valueArray)>0) {
          $resultArray = array_unique($valueArray);
          $valueArray = array_values($resultArray);
        }
        
        return  $valueArray;
    }
    
    public function getAcademicHeadFeeDetails($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                    fri.classId,
                    fh.feeHeadId,fh.headName AS feeHeadName,fri.feeHeadAmount AS amount
                    
                 FROM
                    fee_head_values_new fri ,fee_head_new fh
                 WHERE
                 fri.feeheadId = fh.feeHeadId AND 
                 	fri.classId= '$ttClassId'                        
                 $condition
                 ";
  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    // Academic Fee Detail
    public function getTotalAcademicHeadFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                    fri.classId,fri.quotaId,SUM(fri.feeHeadAmount) AS academicFees
                 FROM
                    fee_head_values_new fri  
                 WHERE
                   fri.classId= '$ttClassId'                        
                 $condition
                 GROUP BY
                     fri.classId";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function getTotalAcademicConcessionFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                    fri.studentId, fri.feeClassId, SUM(fri.concession) AS concession
                 FROM
                    fee_receipt_master fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.feeClassId= '$ttClassId'                        
                 $condition 
                 GROUP BY
                     fri.studentId, fri.feeClassId";
       
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function getTotalAcademicLedgerFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                     IFNULL(SUM( IF(fri.isFine=0,fri.debit,0)),0) AS acdDebit,  IFNULL(SUM( IF(fri.isFine=0,fri.credit,0)),0) AS acdCredit,
                    IFNULL(SUM( IF(fri.isFine>0,fri.debit,0)),0) AS fineDebit,  IFNULL(SUM( IF(fri.isFine>0,fri.credit,0)),0) AS fineCredit
                 FROM
                    fee_ledger_debit_credit fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.ledgerTypeId = 1                       
                 $condition ";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
 
   //Hostel Fees
    public function getTotalHostelHeadFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                    DISTINCT fri.studentId, fri.classId, fri.hostelCharges AS hostelFees,
                    fri.securityAmount AS hostelSecurity,h.hostelRoomId,h.hostelId
                 FROM
                    hostel_students fri ,hostel_room h 
                 WHERE
                 	h.hostelRoomId = fri.hostelRoomId AND
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId'                        
                 $condition
                 GROUP BY
                     fri.studentId, fri.classId ";
         
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    public function getTotalHostelLedgerFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                     IFNULL(SUM( IF(fri.isFine=0,fri.debit,0)),0) AS hostDebit,
                     IFNULL(SUM( IF(fri.isFine=0,fri.credit,0)),0) AS hostCredit,
                   	 IFNULL(SUM( IF(fri.isFine>0,fri.debit,0)),0) AS finehostDebit,
                      IFNULL(SUM( IF(fri.isFine>0,fri.credit,0)),0) AS finehostCredit
                 FROM
                    fee_ledger_debit_credit fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.ledgerTypeId = 3                       
                 $condition ";
      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
   
	//Transport Fee
	 public function getTotalTransportHeadFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                    DISTINCT fri.studentId, fri.classId, fri.routeCharges AS transportFees,
                    fri.busRouteStopMappingId,fri.busRouteId,fri.busStopId,fri.busStopCityId
                 FROM
                    bus_route_student_mapping fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId'                        
                 $condition
                 GROUP BY
                     fri.studentId, fri.classId ";
          
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
     public function getTotalTransportLedgerFee($ttStudentId='',$ttClassId='',$condition=''){
   
        global $sessionHandler;
        $query ="SELECT
                    IFNULL( SUM( IF(fri.isFine=0,fri.debit,0)),0) AS transDebit,
                     IFNULL(SUM( IF(fri.isFine=0,fri.credit,0)),0) AS transCredit,
                   	 IFNULL(SUM( IF(fri.isFine>0,fri.debit,0)),0) AS finetransDebit,
                     IFNULL( SUM( IF(fri.isFine>0,fri.credit,0)),0) AS finetransCredit
                 FROM
                    fee_ledger_debit_credit fri  
                 WHERE
                    fri.studentId = '$ttStudentId'                        
                    AND fri.classId= '$ttClassId' 
                    AND fri.ledgerTypeId = 2                       
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

//History : $

?>
