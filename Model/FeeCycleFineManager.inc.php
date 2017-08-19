<?php 

//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "FeeCycleFine" TABLE
//
//
// Author :Arvind Singh Rawat
// Created on : 1-July
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class FeeCycleFineManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "FeeCycleFineManager" CLASS
//
//
// Author :Arvind Singh Rawat
// Created on : 1-July
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 	
	
	private function __construct() {
	}

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF"FeeCycleFineManager" CLASS
//
//
// Author :Arvind Singh Rawat
// Created on : 1-July
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 	

	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED geting fee cycle name 
//
// Created on : 1-July
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 	

	public function geatFeeCycleName($id) {
		$query ="SELECT 	fc.cycleName, fcf.feeCycleId
				FROM 	fee_cycle fc, fee_cycle_fines fcf
				WHERE 	fc.feeCycleId = fcf.feeCycleId
				AND 	fcf.feeCycleFineId =$id";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED geting fee cycle name 
//
// Created on : 1-July
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 	

	public function geatFeeCycle($id) {
		$query ="SELECT 	cycleName
				FROM 	fee_cycle 
				WHERE 	
				 	feeCycleId =$id";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING "FeeCycleFine"
//
//
// Author :Arvind Singh Rawat
// Created on : 1-July
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 	

	public function addFeeCycleFine() {
		global $REQUEST_DATA;
			
		return SystemDatabaseManager::getInstance()->runAutoInsert('fee_cycle_fines', array('feeCycleId','fromDate','toDate','fineAmount','fineType'), array(strtoupper($REQUEST_DATA['feeCycleId']),$REQUEST_DATA['fromDate'],$REQUEST_DATA['toDate'],$REQUEST_DATA['fineAmount'],$REQUEST_DATA['fineType']));
	}
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING "FeeCycleFine"
//
//
// Author :Arvind Singh Rawat
// Created on : 1-July
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 		
	
    public function editFeeCycleFine($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('fee_cycle_fines', array('feeCycleId','fromDate','toDate','fineAmount','fineType'), array(strtoupper($REQUEST_DATA['feeCycleId']),$REQUEST_DATA['fromDate'],$REQUEST_DATA['toDate'],$REQUEST_DATA['fineAmount'],$REQUEST_DATA['fineType']), "feeCycleFineId=$id" );  
    }   
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING "FeeCycleFine" LIST
//
//
// Author :Arvind Singh Rawat
// Created on : 1-July
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 		
	 
    public function getFeeCycleFine($conditions='') {
      global $sessionHandler;
        $query = "SELECT fcf.feeCycleFineId, fcf.feeCycleId, fcf.fromDate, fcf.toDate, fcf.fineAmount, fcf.fineType, fc.cycleName
FROM fee_cycle fc, fee_cycle_fines fcf
WHERE fcf.feeCycleId = fc.feeCycleId
 AND fc.instituteid= '".$sessionHandler->getSessionVariable('InstituteId')."'
        $conditions  ";
		
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
 
 //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING "FeeCycleFineManager" LIST
//
//
// Author :Arvind Singh Rawat
// Created on : 1-July
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 	
 
    public function getFeeCycleFineList($conditions='', $limit = '', $orderBy=' fineAmount') {
      global $sessionHandler;
        $query = "SELECT fcf.feeCycleFineId, fcf.feeCycleId,fcf.fromDate, fcf.toDate, fcf.fineAmount, fcf.fineType, fc.cycleName
FROM fee_cycle fc, fee_cycle_fines fcf
WHERE fcf.feeCycleId = fc.feeCycleId
 AND fc.instituteid= '".$sessionHandler->getSessionVariable('InstituteId')."'
        $conditions                   
        ORDER BY $orderBy $limit";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

 //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING "FeeCycleFineManager" LIST
//
//
// Author :Arvind Singh Rawat
// Created on : 22-oct
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 	
 
    public function getFeeCycleFineListPrint($conditions='', $limit = '', $orderBy=' fineAmount') {
        global $sessionHandler;
        
        $query = "SELECT 
                        fcf.feeCycleFineId, fcf.feeCycleId, fcf.fromDate, fcf.toDate, fcf.fineAmount,
                        IF(fcf.fineType=1,'Fixed','Daily') As fineType, fc.cycleName
                  FROM 
                        fee_cycle fc, fee_cycle_fines fcf
                  WHERE 
                        fcf.feeCycleId = fc.feeCycleId
                        AND fc.instituteid= '".$sessionHandler->getSessionVariable('InstituteId')."'
                  $conditions                   
                  ORDER BY $orderBy $limit";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

//--------------------------------------------------------------------------------
//// THIS FUNCTION IS USED FOR GETTING the Total  "FeeCycleFineManager" LIST Records
//
//
// Author :Arvind Singh Rawat
// Created on : 1-July
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 	

    public function getTotalFeeCycleFine($conditions='') {
     global $sessionHandler;
        $query = "SELECT COUNT(*) AS totalRecords 
                        FROM fee_cycle fc, fee_cycle_fines fcf
                WHERE fcf.feeCycleId = fc.feecycleId
                 AND fc.instituteid= '".$sessionHandler->getSessionVariable('InstituteId')."'
		 $conditions ";
		
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
 
 //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING "FeeCycleFineManager" CLASS
//
//
// Author :Arvind Singh Rawat
// Created on : 1-July
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 	
 
     public function deleteFeeCycleFine($feeCycleFineId) {
     
        $query = "DELETE 
        FROM fee_cycle_fines 
        WHERE feeCycleFineId=$feeCycleFineId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }   
}
?>

<?php 

//$History: FeeCycleFineManager.inc.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Model
//updated with all the fees enhancements
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 11  *****************
//User: Arvind       Date: 10/24/08   Time: 1:05p
//Updated in $/Leap/Source/Model
//added new function getFeeCycleFineListPrint()
//
//*****************  Version 10  *****************
//User: Arvind       Date: 8/02/08    Time: 11:04a
//Updated in $/Leap/Source/Model
//added instituteId
//
//*****************  Version 9  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:28p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 8  *****************
//User: Arvind       Date: 7/11/08    Time: 5:53p
//Updated in $/Leap/Source/Model
//added comments
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/05/08    Time: 3:56p
//Updated in $/Leap/Source/Model
//removed print_r() funciton
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/05/08    Time: 3:55p
//Updated in $/Leap/Source/Model
//table field changed in getFeeCycleFineList() function
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/05/08    Time: 3:54p
//Updated in $/Leap/Source/Model
//table field changed in getTotalFeeCycleFine() funciton
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/03/08    Time: 7:32p
//Updated in $/Leap/Source/Model
//modified table name 
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/03/08    Time: 3:48p
//Updated in $/Leap/Source/Model
//modification the query of getFeeCycleFine()  function
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/03/08    Time: 3:10p
//Updated in $/Leap/Source/Model
//modified queries in feecyclemanager
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:21a
//Created in $/Leap/Source/Model
//Added three files for three new modules "feehead" , "fee cyclefine"
//,"feefeefundallocation" module"


?>
