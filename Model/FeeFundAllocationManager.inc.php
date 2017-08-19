<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the "FeeFundAllocation" Module
//
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class FeeFundAllocationManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "FeeFundAllocationManager" CLASS
//
// Author :Arvind Singh Rawat 
// Created on : 2-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     
	
	private function __construct() {
	}
	
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "FeeFundAllocationManager" CLASS
//
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------	
	
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
	
//-------------------------------------------------------
/// THIS FUNCTION IS USED FOR ADDING A FeeFundAllocation
//
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------	
	
	public function addFeeFundAllocation() {
		global $REQUEST_DATA;
		global $sessionHandler;  
		return SystemDatabaseManager::getInstance()->runAutoInsert('fee_fund_allocation', array('allocationEntity','entityType','instituteId'), array($REQUEST_DATA['allocationEntity'],$REQUEST_DATA['entityType'],$sessionHandler->getSessionVariable('InstituteId')));
	}
	
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A FeeFundAllocation
//
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------	
	
    public function editFeeFundAllocation($id) {
        global $REQUEST_DATA;
     global $sessionHandler; 
        return SystemDatabaseManager::getInstance()->runAutoUpdate('fee_fund_allocation', array('allocationEntity','entityType','instituteId'), array($REQUEST_DATA['allocationEntity'],$REQUEST_DATA['entityType'],$sessionHandler->getSessionVariable('InstituteId')), "feeFundAllocationId=$id" );  
    }  
	
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeeFundAllocation LIST
//
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------	
	  
    public function getFeeFundAllocation($conditions='') {
      global $sessionHandler; 
        $query = "SELECT feeFundAllocationId,allocationEntity,entityType,instituteId 
        FROM fee_fund_allocation WHERE instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
        $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING FeeFundAllocation LIST 
//
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------   
      
    public function getFeeFundAllocationList($conditions='', $limit = '', $orderBy=' allocationEntity') {
      global $sessionHandler; 
        $query = "SELECT feeFundAllocationId,allocationEntity,entityType,instituteId 
        FROM fee_fund_allocation WHERE instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
        $conditions                   
        ORDER BY $orderBy $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "FeeFundAllocation" TABLE
//
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getTotalFeeFundAllocation($conditions='') {
     global $sessionHandler; 
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM fee_fund_allocation WHERE instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'  $conditions  ";
             
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

//-------------------------------------------------------
//
// THIS FUNCTION IS USED FOR DELETING A "FeeFundAllocation" RECORD
//
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

     public function deleteFeeFundAllocation($feeFundAllocationId) {
     
        $query = "DELETE 
        FROM fee_fund_allocation 
        WHERE feeFundAllocationId=$feeFundAllocationId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }   
    
//-------------------------------------------------------
//
// THIS FUNCTION IS USED FOR Link A "FeeheadValue" RECORD
//
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

     public function getFeeHeadValue($feeFundAllocationId='') {
        
        $query = "SELECT 
                        count(*) AS cnt 
                  FROM 
                        fee_head_values 
                  WHERE 
                        feeFundAllocationId=$feeFundAllocationId ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }       
}

//$History: FeeFundAllocationManager.inc.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/25/09    Time: 2:26p
//Updated in $/LeapCC/Model
//getTotalFeeFundAllocation function updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/22/09    Time: 3:52p
//Updated in $/LeapCC/Model
//condition & formatting, required parameter checks updated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/05/08    Time: 2:55p
//Updated in $/Leap/Source/Model
//no change
//
//*****************  Version 6  *****************
//User: Arvind       Date: 8/02/08    Time: 10:43a
//Updated in $/Leap/Source/Model
//added institute id in queruies
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:29p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/11/08    Time: 6:27p
//Updated in $/Leap/Source/Model
//added comments above functions 
//and added session variables in  the function
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/04/08    Time: 3:26p
//Updated in $/Leap/Source/Model
//added $sessionHandler->getSessionVariable['InstituteId'] in every file
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/03/08    Time: 7:32p
//Updated in $/Leap/Source/Model
//modified table name 
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:21a
//Created in $/Leap/Source/Model
//Added three files for three new modules "feehead" , "fee cyclefine"
//,"feefeefundallocation" module"

?>