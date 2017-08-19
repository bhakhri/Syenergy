<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Fee Cycle Classes" TABLE
//
// Author :Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeeCycleClassesManager {
	private static $instance = null;
	
//----------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "QuotaManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
	private function __construct() {
	}

//----------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "QuotaManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------    
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

//--------------------------------------------------------------
//  THIS FUNCTION IS Delete Fee Cycle Classes
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function deleteFeeCylceClasses($condition='') {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "DELETE FROM fee_cycle_class WHERE instituteId = $instituteId AND sessionId = $sessionId  $condition ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
   
//--------------------------------------------------------------
//  THIS FUNCTION IS Add Fee Cycle Classes
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function addFeeCylceClasses($fieldValue) {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "INSERT INTO `fee_cycle_class` (`instituteId`,`sessionId`, `feeCycleId`,`classId`) 
                  VALUES 
                  $fieldValue ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    
//--------------------------------------------------------------
//  THIS FUNCTION IS Fetch Fee Cycle Classes
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getFeeCycleClasses($feeCycleId='',$condition='', $orderBy='className', $limits='') {
       
        global $sessionHandler; 
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');  
        
        if($orderBy=='') {
          $orderBy=" classStatus, SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3), studyPeriodId";  
        }
                                     
        $query ="SELECT
                        c.classId, c.className,   
                        IF(isActive=1,'Active',
                          IF(isActive=2,'Future',
                            IF(isActive=3,'Past','Unused'))) AS classStatus,
                        IFNULL(fcc.feeCycleClassId,'-1') AS feeCycleClassId, 
                        IFNULL(fcc.feeCycleId,'-1') AS feeCycleId,     
                        IFNULL((SELECT 
                                     CONCAT(fc.feeCycleId,'~',fc.cycleName)
                                FROM 
                                     fee_cycle fc, fee_cycle_class f 
                                WHERE
                                     fc.feeCycleId = f.feeCycleId AND
                                     f.classId = c.classId),'".NOT_APPLICABLE_STRING."') AS mappedFeeCycle
                 FROM
                        class c LEFT JOIN fee_cycle_class fcc ON fcc.instituteId = $instituteId AND  fcc.sessionId = $sessionId AND 
                        fcc.classId = c.classId AND fcc.feeCycleId = $feeCycleId
                 WHERE
                        c.instituteId=$instituteId AND   
                        
                        c.isActive IN (1,2,3)
                 $condition 
                 ORDER BY $orderBy 
                 $limits";
               //c.sessionId=$sessionId AND  
        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   
    }     
    
    public function getCheckFeeCycleClasses($condition='') {    
    
        global $sessionHandler; 
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');  
        
        
        
        $query ="SELECT     
                        feeCycleClassId, feeCycleId, classId
                 FROM 
                        fee_cycle_class 
                 WHERE
                        instituteId=$instituteId AND   
                        sessionId=$sessionId   
                 $condition ";
        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   
    }     
    
    public function getCheckFeeReceipt($condition='') {    
    
        global $sessionHandler; 
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');  
        
        
        $query ="SELECT     
                        feeCycleId, classId
                 FROM 
                        fee_receipt 
                 WHERE
                        $condition ";
        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   
    }      
}
?>
