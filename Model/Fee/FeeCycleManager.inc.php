<?php
//-------------------------------------------------------------------------------
//
//FeeCycleManager is used having all the Add, edit, delete function..
// Author : Nishu Bindal
// Created on : 3.feb.12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class FeeCycleManager {
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
    
    //-------------------------------------------------------------------------------
//
//addFeeCycle() is used to add new record in database.
// Author : Nishu
// Created on : 3.feb.12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function addFeeCycle() {
		global $REQUEST_DATA;
        	global $sessionHandler;
        	$instituteId= $sessionHandler->getSessionVariable('InstituteId');
        	$sessionId= $sessionHandler->getSessionVariable('SessionId');
        	$cycleName = addslashes(trim($REQUEST_DATA['cycleName']));
        	$cycleAbbr = addslashes(strtoupper(trim($REQUEST_DATA['cycleAbbr'])));
        	$fromDate = $REQUEST_DATA['fromDate'];
        	$toDate = $REQUEST_DATA['toDate'];
		$academicFromDate = $REQUEST_DATA['academicFromDate'];
        	$academicToDate = $REQUEST_DATA['academicToDate'];
		$hostelFromDate = $REQUEST_DATA['hostelFromDate'];
        	$hostelToDate = $REQUEST_DATA['hostelToDate'];
		$transportFromDate = $REQUEST_DATA['transportFromDate'];
        	$transportToDate = $REQUEST_DATA['transportToDate'];
        	$active = $REQUEST_DATA['active'];
        
        	$query ="INSERT INTO `fee_cycle_new` (feeCycleId,cycleName,cycleAbbr,instituteId,sessionId,fromDate,toDate,status,academicFromDate,academicToDate,
                        hostelFromDate ,hostelToDate,transportFromDate , transportToDate ) 
        			VALUES ('','$cycleName','$cycleAbbr','$instituteId','$sessionId','$fromDate','$toDate','$active','$academicFromDate','$academicToDate',
                        '$hostelFromDate' ,'$hostelToDate','$transportFromDate' ,'$transportToDate' )";
        			
		return SystemDatabaseManager::getInstance()->executeUpdate($query);
		echo $query;die;
	}
    
//-------------------------------------------------------------------------------
//
//editFeeCycle() is used to edit the existing record through id.
//Author : Nishu Bindal
// Created on : 3.feb.12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function editFeeCycle($id) {
		global $REQUEST_DATA;
	     
	     	$cycleName = addslashes(trim($REQUEST_DATA['cycleName']));
		$cycleAbbr = addslashes(strtoupper(trim($REQUEST_DATA['cycleAbbr'])));
		$fromDate = $REQUEST_DATA['fromDate1'];
		$toDate = $REQUEST_DATA['toDate1'];
		$academicFromDate = $REQUEST_DATA['academicFromDate1'];
        	$academicToDate = $REQUEST_DATA['academicToDate1'];
		$hostelFromDate = $REQUEST_DATA['hostelFromDate1'];
        	$hostelToDate = $REQUEST_DATA['hostelToDate1'];
		$transportFromDate = $REQUEST_DATA['transportFromDate1'];
        	$transportToDate = $REQUEST_DATA['transportToDate1'];
		$active = $REQUEST_DATA['active'];
			
		$query ="UPDATE		`fee_cycle_new` 
				SET	cycleName = '$cycleName', 
					cycleAbbr = '$cycleAbbr', 
					fromDate = '$fromDate', 
					toDate = '$toDate',
					academicFromDate = '$academicFromDate',
					academicToDate = '$academicToDate',
                        		hostelFromDate = '$hostelFromDate',
					hostelToDate = '$hostelToDate',
					transportFromDate = '$transportFromDate' , 
					transportToDate  = '$transportToDate',
					status = '$active'
				WHERE	feeCycleId = '$id'";
				
		return SystemDatabaseManager::getInstance()->executeUpdate($query);
		
    }    
    
    //-------------------------------------------------------------------------------
//
//getFeeCycle() is used to get the data.
//Author : Nishu Bindal
// Created on : 3.feb.12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getFeeCycle($conditions='') {
     	global $sessionHandler;
	$instituteId= $sessionHandler->getSessionVariable('InstituteId');
	$sessionId= $sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT	
			feeCycleId,cycleName,cycleAbbr,fromDate,toDate,status,academicFromDate,academicToDate,
                        hostelFromDate ,hostelToDate,transportFromDate , transportToDate 
        		FROM	`fee_cycle_new`
        		WHERE	instituteId = '$instituteId'
        		AND	sessionId = '$sessionId' 
        $conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
 
 //-------------------------------------------------------------------------------
//
//deleteFeeCycle() is used to delete the existing record through id.
//Author : Nishu bindal
// Created on : 3.feb.2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   
    public function deleteFeeCycle($feeCycleId) {
     
        $query = "DELETE 
			FROM `fee_cycle_new` 
			WHERE feeCycleId='$feeCycleId'";
       return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
    //-------------------------------------------------------------------------------
//
//getFeeCycleList() is used to get the list of data order by name.
//Author : Nishu Bindal
// Created on : 13.feb.12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getFeeCycleList($conditions='', $limit = '', $orderBy=' cycleName') {
     global $sessionHandler;
        $query = "SELECT 	
			feeCycleId, cycleName, cycleAbbr, fromDate, toDate,status,academicFromDate,academicToDate,
                        hostelFromDate ,hostelToDate,transportFromDate , transportToDate  
        		FROM 	`fee_cycle_new` WHERE  instituteId= '".$sessionHandler->getSessionVariable('InstituteId')."'
       			 AND	sessionId = '".$sessionHandler->getSessionVariable('SessionId')."'
        		$conditions 
        		ORDER BY $orderBy $limit";
        	
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //-------------------------------------------------------------------------------
//
//getTotalFeeCycle() is used to get total no. of records
//Author : Nishu Bindal
// Created on : 13.feb.12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getTotalFeeCycle($conditions='') {
    	global $sessionHandler;
        $query = "SELECT	 COUNT(feeCycleId) AS totalRecords 
        		FROM 	`fee_cycle_new` 
        		WHERE 	instituteId= '".$sessionHandler->getSessionVariable('InstituteId')."'
        		AND	sessionId = '".$sessionHandler->getSessionVariable('SessionId')."'
        $conditions ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //-------------------------------------------------------------------------------
//
//checkFeeCycle() is used to get total no. of records
//Author : Nishu Bindal
// Created on : 13.feb.12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function checkFeeCycle($feeCycleId) {
    global $sessionHandler;
       $query = "	SELECT	COUNT(feeCycleId) AS cnt 
					FROM	fee_cycle_fines 
					WHERE	feeCycleId = $feeCycleId";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------------------------------
//
//checkFeeCycle() is used to get total no. of records
//Author : Nishu Bindal
// Created on : 13.feb.12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function checkFeeHeadValues($feeCycleId) {
    global $sessionHandler;
       $query = "	SELECT	COUNT(feeCycleId) AS cnt 
					FROM	fee_head_values 
					WHERE	feeCycleId = $feeCycleId";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//checkFeeCycle() is used to get total no. of records
//Author : Nishu Bindal
// Created on : 13.feb.12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function checkFeeReceiptValues($feeCycleId) {
    global $sessionHandler;
       $query = "	SELECT	COUNT(feeCycleId) AS cnt 
					FROM	`fee_receipt_master`
					WHERE	feeCycleId = '$feeCycleId'";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	


}
?>
<?php 
// $History : $
 ?>
