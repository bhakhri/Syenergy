<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Reappear / Re-Exam" Module
//
// Author :Parveen Sharma   
// Created on : 19-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ClassUpdateManager {
    private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "SessionsManager" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "SessionsManager" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
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
    
    
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Batch Year
//
// Author :Abhay Kant
// Created on : 26-July-2011
// Copyright 2010-2011 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
   public function getBatchYear($conditions='') {
	
       global $sessionHandler;
        
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');
       $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        

	$query="SELECT 
		    DISTINCT b.batchId, b.batchName 
		FROM
		   class c, batch b
	        WHERE
		 c.batchId = b.batchId AND c.isActive IN (1,2,3) AND
		 c.instituteId = $instituteId
		 $conditions ";
	
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }    
     


    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING DEGREE 
//
// Author :Abhay Kant
// Created on : 26-July-2011
// Copyright 2010-2011 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
   public function getDegree($conditions) {
	
 	 global $sessionHandler; 

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

	$query="SELECT 
			DISTINCT d.degreeId, d.degreeName
	       FROM 
			class c, degree d 
	       WHERE     
			c.degreeId = d.degreeId AND c.isActive IN (1,2,3)  AND
	    	        c.instituteId = $instituteId AND c.batchId = $conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }    
     
         
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Branch
//
// Author :Abhay Kant
// Created on : 26-July-2011
// Copyright 2010-2011 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
   public function getBranch($batchId,$degreeId) {
     
	global $sessionHandler; 

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        

	$query="SELECT 
			DISTINCT b.branchId, b.branchName 
		FROM 
			class c, branch b 
		WHERE 
			c.branchId = b.branchId AND c.isActive IN (1,2,3) AND
			c.instituteId = $instituteId AND			 
			c.batchId = $batchId AND 
			c.degreeId = $degreeId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }    
     
//--------------------------------------------------------------
//  THIS FUNCTION IS Fetch all selected class
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getSessionClasses($batchId='',$branchId='',$degreeId='',$condition='', $orderBy='className', $limits='') {
       
        global $sessionHandler; 

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');  
        
        if($orderBy=='') {
          $orderBy=" classStatus, SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3), studyPeriodId";  
        }
                                     
        $query ="SELECT 
			classId,className,studyPeriodId,sessionTitleName, displayOrder, IF(internalPassMarks=0.00,'',internalPassMarks) AS internalPassMarks , IF(externalPassMarks=0.00,'',externalPassMarks) AS externalPassMarks 
		 FROM 
			class 
		 WHERE 
			batchId=$batchId   AND 
			degreeId=$degreeId AND 
			branchId=$branchId AND
			instituteId = $instituteId AND
			isActive IN (1,2,3)
                 ORDER BY 
	  	       $orderBy $limits";
			  
		
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   
    }
    
    

	public function addUpdateTitle($titleName,$classId,$displayOrder,$Internal,$External) {
		
	   //$query = "INSERT INTO reappear_classes (labelId,classId,instituteId) VALUES $str ";
	   $query = "UPDATE 
			       class 
		         SET 
			       sessionTitleName='$titleName',
                   displayOrder='$displayOrder',
                   internalPassMarks='$Internal',
                   externalPassMarks='$External' 
		         WHERE 
			       classId = '$classId' ";
			      

	   return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
	}

	public function getSingleField($table, $conditions='') {
    
        $query = "SELECT 
			            classId,className,sessionTitleName,displayOrder  FROM $table $conditions";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


}
?>
