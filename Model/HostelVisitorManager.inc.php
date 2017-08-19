<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "hostelvisitor" TABLE
//
//
// Author :Gurkeerat Sidhu 
// Created on : (20.4.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); 

class HostelVisitorManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "HostelVisitorManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (20.4.2009 )
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "HostelVisitorManager" CLASS
//
// Author :Gurkeerat Sidhu  
// Created on : (20.4.2009 )
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// THIS FUNCTION IS USED FOR ADDING A HOSTEL VISITOR
//
// Author :Gurkeerat Sidhu  
// Created on : (20.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addHostelVisitor() {
		global $REQUEST_DATA;
        return SystemDatabaseManager::getInstance()->runAutoInsert('hostel_visitor', array('visitorName','toVisit','address','dateOfVisit','timeOfVisit','purpose','contactNo','relation'), 
        array($REQUEST_DATA['visitorName'],$REQUEST_DATA['toVisit'],$REQUEST_DATA['address'],$REQUEST_DATA['dateOfVisit'],$REQUEST_DATA['timeOfVisit'],$REQUEST_DATA['purpose'],$REQUEST_DATA['contactNo'],$REQUEST_DATA['relation']) 
        );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A HOSTEL VISITOR
//
//$id:visitorId
// Author :Gurkeerat Sidhu
// Created on : (20.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editHostelVisitor($id) {
        global $REQUEST_DATA;
        
     return SystemDatabaseManager::getInstance()->runAutoUpdate('hostel_visitor', array('visitorName','toVisit','address','dateOfVisit','timeOfVisit','purpose','contactNo','relation'), 
        array($REQUEST_DATA['visitorName'],$REQUEST_DATA['toVisit'],$REQUEST_DATA['address'],$REQUEST_DATA['dateOfVisit'],$REQUEST_DATA['timeOfVisit'],$REQUEST_DATA['purpose'],$REQUEST_DATA['contactNo'],$REQUEST_DATA['relation']), 
        "visitorId=$id" 
        );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING HOSTEL VISITOR LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (20.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getHostelVisitor($conditions='') {
     
        $query = "SELECT visitorId,visitorName,toVisit,address,dateOfVisit,timeOfVisit,purpose,contactNo,relation 
        FROM hostel_visitor
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A HOSTEL VISITOR
//
// Author :Gurkeerat Sidhu 
// Created on : (20.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteHostelVisitor($visitorid) {
     
        $query = "DELETE 
        FROM hostel_visitor 
        WHERE visitorId=$visitorid";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING HOSTEL VISITOR LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu 
// Created on : (20.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getHostelVisitorList($conditions='', $limit = '', $orderBy=' visitorName') {
        
        
        $query = "    SELECT * 
                    FROM hostel_visitor  
                    $conditions
                    ORDER BY $orderBy 
                    $limit";
        //echo $query;
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF HOSTEL VISITORS
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu  
// Created on : (20.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalHostelVisitor($conditions='') {
        
        
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM hostel_visitor 
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}
?>
