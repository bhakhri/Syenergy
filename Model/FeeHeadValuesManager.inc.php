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
        
        $query = "INSERT INTO `fee_head_values` 
                  (`classId`,`feeHeadId`,`quotaId`,`isLeet`,`feeHeadAmount`)
                  VALUES 
                  $fieldValue";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function deleteFeeCylceHeadValue($classId) {
        
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "DELETE FROM `fee_head_values` WHERE `classId` = '$classId' ";
        
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
        
        $query = "INSERT INTO `fee_head_values` 
                         (`feeCycleId`, `classId`, `feeHeadId`, `quotaId`, `isLeet`, `feeHeadAmount`)    
                  SELECT 
                       fh.feeCycleId, $classId, fh.feeHeadId, fh.quotaId, fh.isLeet, fh.feeHeadAmount
                  FROM 
                       `fee_head_values` fh       
                  WHERE 
                        fh.classId = $mainClassId 
                  $condition";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
             
    
    public function getFeeCycleHeadList($condition='')   {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT 
                       fh.feeCycleId, fh.classId, fh.feeHeadId, IFNULL(fh.quotaId,'') AS quotaId, fh.isLeet, fh.feeHeadAmount,
                       c.className
                  FROM 
                       `fee_head_values` fh 
                        LEFT JOIN `class` c ON fh.classId = c.classId      
                        LEFT JOIN `fee_head` ff ON fh.feeHeadId = ff.feeHeadId  
                  WHERE 
                       $condition
                  ORDER BY
                       feeHeadValueId";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
    
    public function getFeeCycleClassList($condition='')  {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT 
                       DISTINCT fh.feeCycleId, fh.classId, c.className
                  FROM 
                       `fee_head_values` fh, `class` c 
                  WHERE 
                       fh.classId = c.classId      
                  $condition";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
	public function addFeeHeadValues() {
		global $REQUEST_DATA;
      
		$query="INSERT INTO fee_head_values (feeCycleId,feeHeadId,feeFundAllocationId,quotaId,universityId,degreeId,branchId,batchId,studyPeriodId,isLeet,feeHeadAmount)VALUES ('".$REQUEST_DATA['feeCycleId']."','".$REQUEST_DATA['feeHeadId']."','".$REQUEST_DATA['feeFundAllocationId']."',".$REQUEST_DATA['quotaId'].",".$REQUEST_DATA['universityId'].",".$REQUEST_DATA['degreeId'].",".$REQUEST_DATA['branchId'].",".$REQUEST_DATA['batchId'].",".$REQUEST_DATA['studyPeriodId'].",".$REQUEST_DATA['isLeet'].",'".$REQUEST_DATA['feeHeadAmount']."')";
	
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
}
//History : $

?>