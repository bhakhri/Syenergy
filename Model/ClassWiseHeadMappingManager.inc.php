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



class ClassWiseHeadValuesManager {
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
        
         $query = "INSERT INTO `fee_classwise_concession_values` 
                  (`classId`,`categoryId`,`feeHeadId`,`isLeet`,`concessionType`,`concessionAmount`)
                  VALUES 
                  $fieldValue";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function deleteFeeCylceHeadValue($classId) {
        
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "DELETE FROM `fee_classwise_concession_values` WHERE `classId` = '$classId' ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    } 

    public function addCopyFeeConcessionValue($mainClassId='',$classId='',$condition='') {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "INSERT INTO `fee_classwise_concession_values` 
                         (classId, categoryId, feeHeadId, isLeet, concessionType, concessionAmount)    
                  SELECT 
                        $classId, categoryId, feeHeadId, isLeet, concessionType, concessionAmount
                  FROM 
                       `fee_classwise_concession_values` fh       
                  WHERE 
                        fh.classId = $mainClassId 
                  $condition";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }        
    
    public function getClassWiseHeadListing($condition='')   {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
      $query = "SELECT 
                         fcc.classId, fcc.categoryId,fcc.feeHeadId, fcc.isLeet, fcc.concessionType,fcc.concessionAmount,
                       c.className
                  FROM 
                       `fee_classwise_concession_values` fcc 
                        LEFT JOIN `class` c ON fcc.classId = c.classId      
                        LEFT JOIN `fee_head` ff ON fcc.feeHeadId = ff.feeHeadId  
                  WHERE 
                       $condition
                  ORDER BY
                       categoryId";
                
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
    
 /*   public function getFeeCycleClassList($condition='')  {
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
    }*/
    
	public function addClassWiseHeadValues() {
		global $REQUEST_DATA;
      
		$query="INSERT INTO `fee_classwise_concession_values` (classId,categoryId,feeHeadId,isLeet,concessionType,concessionAmount)VALUES ('".$REQUEST_DATA['classId']."','".$REQUEST_DATA['categoryId']."','".$REQUEST_DATA['feeHeadId']."',".$REQUEST_DATA['isLeet'].",".$REQUEST_DATA['isconcessionType'].",".$REQUEST_DATA['totalAmount']."')";
		
		return SystemDatabaseManager::getInstance()->executeUpdate($query);
		
	}
    
    
    public function getFeeConcessionHeadList($condition='')   {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT 
                       fh.classConcessionId, fh.classId, fh.categoryId, fh.feeHeadId, fh.isLeet, fh.concessionType, fh.concessionAmount,
                       c.className
                  FROM 
                       `fee_classwise_concession_values` fh 
                        LEFT JOIN `class` c ON fh.classId = c.classId      
                        LEFT JOIN `fee_head` ff ON fh.feeHeadId = ff.feeHeadId  
                  WHERE 
                       $condition
                  ORDER BY
                       classConcessionId";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
    
    public function getFeeConcessionClassList($condition='')  {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT 
                       DISTINCT fh.classId, c.className
                  FROM 
                       `fee_classwise_concession_values` fh, `class` c 
                  WHERE 
                       fh.classId = c.classId      
                  $condition";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
    
public function getClassWiseHeadList($condition='')   {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT 
                           fcc.classId, fcc.categoryId,fcc.feeHeadId, fcc.isLeet, fcc.concessionType,fcc.concessionAmount,
                           c.className, fc.categoryName, ff.HeadName,
                           IF(fcc.isLeet=1,'Leet',IF(fcc.isLeet=2,'Non Leet','Leet & Non Leet')) AS isLeetName
                  FROM 
                       `fee_classwise_concession_values` fcc
                        LEFT JOIN `class` c ON fcc.classId = c.classId      
                        LEFT JOIN `fee_head` ff ON fcc.feeHeadId = ff.feeHeadId  
                        LEFT JOIN `fee_concession_category` fc ON fcc.categoryId = fc.categoryId
                  WHERE 
                       $condition
                  ORDER BY
                       categoryId";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
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
	  
	  $query="UPDATE `fee_classwise_concession_values` set classId='".$REQUEST_DATA['classId']."',categoryId='".$REQUEST_DATA['categoryId']."',feeHeadId='".$REQUEST_DATA['feeHeadId']."',isLeet=".$REQUEST_DATA['isLeet'].",concessionType=".$REQUEST_DATA['isConcessionType'].",concessionAmount=".$REQUEST_DATA['totalAmount']." WHERE classConcessionId='".$REQUEST_DATA['classConcessionId']."'";
	return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    }    

    public function getClassWiseHeadValues($conditions='') {
     
        $query = "SELECT   `classConcessionId`,`classId`,`categoryId`,`feeHeadId`,`isLeet`,`concessionType`,`concessionAmount`
        FROM `fee_classwise_concession_values` 
        $conditions";
		
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}

?>