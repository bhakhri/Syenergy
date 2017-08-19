<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the "FEE HEAD VALUES" Module
//
//
// Author :Arvind Singh Rawat
// Created on : 18-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class FeeHeadValuesManager {
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
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Add Total Seats Intake of class
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function addFeeCylceHeadValue($fieldValue) {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "INSERT INTO `fee_head_values_new` 
                  (classId,feeHeadId,quotaId,isLeet,feeHeadAmount,sessionId,instituteId,feeCycleId)
                  VALUES 
                  $fieldValue";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
//--------------------------------------------------------------
// THIS FUNCTION IS USED TO CHECK IF CLASS FEE IS ALREADY GENERATED 
// Author :NISHU BINDAL
// Created on : (7-April-2012)
// Copyright 2011-2012 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------    
    public function checkIfFeeAlreadyGenerated($classId){
    	
        $query = " SELECT 
                         COUNT(feeClassId) AS cnt
    				FROM 
                        `fee_receipt_master`
    				WHERE	
                        feeClassId = '$classId'
    				    AND	isAcademicFee = 1 ";
    				
     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
    public function checkIfFeePaidAlreadyGenerated($classId){
        $query = " SELECT 
                         COUNT(classId) AS cnt
                    FROM 
                       `fee_receipt_details` 
                    WHERE    
                        classId = '$classId'
                        AND isDelete = 0 AND feeType NOT IN (2,3) ";
                    
     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
//--------------------------------------------------------------
// THIS FUNCTION IS USED TO CHECK IF Concession is Given 
// Author :NISHU BINDAL
// Created on : (7-April-2012)
// Copyright 2011-2012 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------  
    
    public function checkInAdhocConcession($classId){
    	$query = " SELECT  count(adhocId) AS cnt
    				FROM	`adhoc_concession_master_new`
    				WHERE	feeClassId = '$classId'";
    	
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }

    public function deleteFeeCylceHeadValue($classId) {
        
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "DELETE	FROM `fee_head_values_new` 
        		WHERE	`classId` = '$classId'
        		AND	sessionId = '$sessionId'
        		AND	instituteId = '$instituteId'";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    } 

//--------------------------------------------------------------
//  THIS FUNCTION IS Add Copy seat intakes
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------       
    
    public function addCopyFeeHeadValue($mainClassId='',$classId='',$condition='') {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "INSERT INTO `fee_head_values_new` 
                         ( `feeHeadId`, `quotaId`,`isLeet`,`feeHeadAmount` , `classId`,`sessionId`, `instituteId`, `feeCycleId`)    
                  SELECT 
                         fh.feeHeadId, quotaId, isLeet, feeHeadAmount, $classId, sessionId,instituteId, feeCycleId
                  FROM 
                       `fee_head_values_new` fh       
                  WHERE 
                        fh.classId = $mainClassId 
                  $condition";
               
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
             
    
    public function getFeeCycleHeadList($conditions =''){    
        $query = "SELECT 
                      	fh.feeHeadId, IFNULL(fh.quotaId,'') AS quotaId, fh.isLeet, fh.feeHeadAmount,
                        fh.feeCycleId
                  FROM 
                       `fee_head_values_new` fh   
                        LEFT JOIN `fee_head_new` ff ON fh.feeHeadId = ff.feeHeadId  AND  fh.instituteId = ff.instituteId AND fh.sessionId = ff.sessionId
                  WHERE 
                  	$conditions
                  ORDER BY
                       fh.feeHeadValueId";
          
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
    
    public function getFeeCycleClassList($condition='')  {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT 
                       DISTINCT  fh.classId, c.className
                  FROM 
                       `fee_head_values_new` fh, `class` c 
                  WHERE 
                       fh.classId = c.classId      
                  $condition";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
	public function addFeeHeadValues() {
		global $REQUEST_DATA;
      
		$query="INSERT INTO fee_head_values 
            (feeCycleId,feeHeadId,feeFundAllocationId,quotaId,universityId,degreeId,branchId,batchId,studyPeriodId,isLeet,feeHeadAmount)
             VALUES 
             ('".$REQUEST_DATA['feeCycleId']."','".$REQUEST_DATA['feeHeadId']."','".$REQUEST_DATA['feeFundAllocationId']."',".$REQUEST_DATA['quotaId'].",".$REQUEST_DATA['universityId'].",".$REQUEST_DATA['degreeId'].",".$REQUEST_DATA['branchId'].",".$REQUEST_DATA['batchId'].",".$REQUEST_DATA['studyPeriodId'].",".$REQUEST_DATA['isLeet'].",'".$REQUEST_DATA['feeHeadAmount']."')";
	
	return SystemDatabaseManager::getInstance()->executeUpdate($query); 
	}
    
 //-------------------------------------------------------
//  This Function update the fields in the database "FEE HEAD VALUES" Module
//
//
// Author :Arvind Singh Rawat
// Created on : 18-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
    public function editFeeHeadValues($id) {
        global $REQUEST_DATA;
     
      /*  return SystemDatabaseManager::getInstance()->runAutoUpdate('fee_head_values', array('feeCycleId','feeHeadId','feeFundAllocationId','quotaId','universityId','degreeId','branchId','batchId','studyPeriodId','feeHeadAmount'), array($REQUEST_DATA['feeCycleId'],$REQUEST_DATA['feeHeadId'],$REQUEST_DATA['feeFundAllocationId'],$REQUEST_DATA['quotaId'],$REQUEST_DATA['universityId'],$REQUEST_DATA['degreeId'],$REQUEST_DATA['branchId'],$REQUEST_DATA['batchId'],$REQUEST_DATA['studyPeriodId'],$REQUEST_DATA['feeHeadAmount']), "feeHeadValueId=$id" );  */
	  
	  $query="UPDATE fee_head_values set feeCycleId='".$REQUEST_DATA['feeCycleId']."',feeHeadId='".$REQUEST_DATA['feeHeadId']."',feeFundAllocationId='".$REQUEST_DATA['feeFundAllocationId']."',quotaId=".$REQUEST_DATA['quotaId'].",universityId=".$REQUEST_DATA['universityId'].",degreeId=".$REQUEST_DATA['degreeId'].",branchId=".$REQUEST_DATA['branchId'].",batchId=".$REQUEST_DATA['batchId'].",studyPeriodId=".$REQUEST_DATA['studyPeriodId'].",feeHeadAmount='".$REQUEST_DATA['feeHeadAmount']."',isLeet=".$REQUEST_DATA['isLeet']." WHERE feeHeadValueId='".$REQUEST_DATA['feeHeadValueId']."'";
	return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    }    
//-------------------------------------------------------
//  This Function gets the fields in the database "FEE HEAD VALUES" Module
//
//
// Author :Arvind Singh Rawat
// Created on : 18-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


    public function getFeeHeadValues($conditions='') {
     
        $query = "SELECT   feeCycleId,feeHeadId,feeFundAllocationId,quotaId,universityId,degreeId,branchId,batchId,studyPeriodId,feeHeadAmount ,feeHeadValueId,isLeet
        FROM fee_head_values 
        $conditions";
		
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    //Gets the country table fields
      
//-------------------------------------------------------
//  This Function gets the fields in the database "FEE HEAD VALUES" Module
//
//
// Author :Arvind Singh Rawat
// Created on : 18-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


    public function getFeeHeadValuesList($conditions='', $limit = '', $orderBy=' fh.headAbbr') {
        global $sessionHandler;
        
        $query = "SELECT 
                         fhv.feeHeadValueId, fc.cycleName, fh.headAbbr, ffa.allocationEntity, 
                         fhv.feeHeadAmount,univ.universityAbbr,b.branchCode,std.periodName,d.degreeAbbr,
                         bat.batchName, fhv.isLeet
                  FROM  
                         fee_head_values fhv
                         LEFT JOIN fee_cycle fc ON fc.feeCycleId = fhv.feeCycleId 
                         LEFT JOIN fee_head fh ON fh.feeHeadId = fhv.feeHeadId
                         LEFT JOIN fee_fund_allocation ffa ON ffa.feeFundAllocationId = fhv.feeFundAllocationId
                         LEFT JOIN university univ  ON univ.universityId = fhv.universityId
                         LEFT JOIN quota q ON q.quotaId = fhv.quotaId
                         LEFT JOIN degree d ON d.degreeId = fhv.degreeId
                         LEFT JOIN branch b ON b.branchId = fhv.branchId
                         LEFT JOIN batch bat ON bat.batchId = fhv.batchId
                         LEFT JOIN study_period std ON std.studyPeriodId = fhv.studyPeriodId
                    WHERE 
                         fc.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                    $conditions                   
                    ORDER BY $orderBy $limit";
     
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
 
 //-------------------------------------------------------
//  This Function counts the fields in the database "FEE HEAD VALUES" Module
//
//
// Author :Arvind Singh Rawat
// Created on : 18-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   
    public function getTotalFeeHeadValues($conditions='') {
      global $sessionHandler;
        $query = "SELECT 
                          COUNT(*) AS totalRecords
                  FROM  
                         fee_head_values fhv
                         LEFT JOIN fee_cycle fc ON fc.feeCycleId = fhv.feeCycleId 
                         LEFT JOIN fee_head fh ON fh.feeHeadId = fhv.feeHeadId
                         LEFT JOIN fee_fund_allocation ffa ON ffa.feeFundAllocationId = fhv.feeFundAllocationId
                         LEFT JOIN university univ  ON univ.universityId = fhv.universityId
                         LEFT JOIN quota q ON q.quotaId = fhv.quotaId
                         LEFT JOIN degree d ON d.degreeId = fhv.degreeId
                         LEFT JOIN branch b ON b.branchId = fhv.branchId
                         LEFT JOIN batch bat ON bat.batchId = fhv.batchId
                         LEFT JOIN study_period std ON std.studyPeriodId = fhv.studyPeriodId
                    WHERE 
                         fc.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                    $conditions ";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    
    public function getFeeHeadList($condition='')   {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT 
                       fh.feeCycleId, fh.classId, fh.feeHeadId, IFNULL(fh.quotaId,'') AS quotaId, fh.isLeet, fh.feeHeadAmount,
                       c.className, ff.headName AS feeHeadName, IFNULL(q.quotaName,'ALL') AS quotaName,
                       IF(fh.isLeet=1,'Leet',IF(fh.isLeet=2,'Non Leet','Leet & Non Leet')) AS isLeetName
                  FROM 
                       `fee_head_values` fh 
                        LEFT JOIN `class` c ON fh.classId = c.classId      
                        LEFT JOIN `fee_head` ff ON fh.feeHeadId = ff.feeHeadId  
                        LEFT JOIN `quota` q ON q.quotaId = fh.quotaId
                  WHERE 
                       $condition
                  ORDER BY
                       feeHeadValueId";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
  
//-------------------------------------------------------
//  This Function deletes the fields in the database "FEE HEAD VALUES" Module
//
//
// Author :Arvind Singh Rawat
// Created on : 18-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------  
  
     public function deleteFeeHeadValues($feeHeadValueId) {
     
        $query = "DELETE 
        FROM fee_head_values 
        WHERE feeHeadValueId=$feeHeadValueId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    } 
    
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
    
    public function fetchClases($condition = ''){
	 global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

    	$query ="SELECT		DISTINCT c.classId,c.className
    			FROM	`class` c
			
    				$condition
    		ORDER BY c.className Asc";
    		
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    	
    }
    
    public function fetchFeeClases($condition = ''){
        $query ="SELECT  
                      DISTINCT c.classId, c.className, sp.periodValue
                  FROM  
                      `class` c, fee_head_values_new f, study_period sp
                  WHERE
                       sp.studyPeriodId = c.studyPeriodId AND
                       c.classId = f.classId
		 $condition
                  ORDER BY 
                      c.className Asc";
            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        
    }
	public function fetchPendingFeeClases($generateClassId = ''){
        $query ="SELECT  
                      DISTINCT c.classId, c.className, sp.periodValue
                  FROM  
                      `class` c, fee_head_values_new f, study_period sp
                  WHERE
                       sp.studyPeriodId = c.studyPeriodId AND
                       c.classId = f.classId AND
			c.classId IN ($generateClassId)
                  ORDER BY 
                      c.className Asc";
            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        
    }
     
      public function getFeeHeadName($feeHeadId='')   {
        global $sessionHandler;
     
        $query = "SELECT 
                       ff.feeHeadId,ff.headName
                  FROM 
                        `fee_head_new` ff 
                  WHERE 
                       feeHeadId ='$feeHeadId'
                  ORDER BY
                       feeHeadId";
                 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
      public function getStudentAllFeeCount($classId='',$condition='') {  
    
        global $sessionHandler;
        
        if($classId=='') {
          $classId='0';  
        }
       $returnArray1= array();
       $valueArray =array();
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
                               
            //  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
              $returnArray1 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      
        for($i=0;$i<count($returnArray1);$i++) {
        	
          $valueArray[] = $returnArray1[$i]['studentClassId']; 
                  
        }
         
        return  $valueArray;
     
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
