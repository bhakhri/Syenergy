<?php
//-------------------------------------------------------------------------------
//
//FeeCycleManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
// Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function addFeeCycle() {
		global $REQUEST_DATA;
        global $sessionHandler;
        $instituteid= $sessionHandler->getSessionVariable('InstituteId');
        
		return SystemDatabaseManager::getInstance()->runAutoInsert('fee_cycle', array('cycleName','cycleAbbr','instituteId','fromDate','toDate'), array($REQUEST_DATA['cycleName'],strtoupper($REQUEST_DATA['cycleAbbr']),$instituteid,$REQUEST_DATA['fromDate'],$REQUEST_DATA['toDate']));
	}
    
//-------------------------------------------------------------------------------
//
//editFeeCycle() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

//-------------------------------------------------------------------------------
//
//modified the name of date fields 
//Author : Arvind Singh Rawat
// Created on : 24.07.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editFeeCycle($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('fee_cycle', array('cycleName','cycleAbbr','fromDate', 
        'toDate'), array($REQUEST_DATA['cycleName'],strtoupper($REQUEST_DATA['cycleAbbr']), $REQUEST_DATA['fromDate1'],$REQUEST_DATA['toDate1']), "feeCycleId=$id" );
    }    
    
    //-------------------------------------------------------------------------------
//
//getFeeCycle() is used to get the data.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getFeeCycle($conditions='') {
     
        $query = "SELECT feeCycleId,cycleName,cycleAbbr,fromDate,toDate 
        FROM fee_cycle 
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
 
 //-------------------------------------------------------------------------------
//
//deleteFeeCycle() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   
    public function deleteFeeCycle($feeCycleId) {
     
        $query = "DELETE 
        FROM fee_cycle 
        WHERE feeCycleId=$feeCycleId";
       return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
    //-------------------------------------------------------------------------------
//
//getFeeCycleList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getFeeCycleList($conditions='', $limit = '', $orderBy=' cycleName') {
     global $sessionHandler;
        $query = "SELECT feeCycleId, cycleName, cycleAbbr, fromDate, toDate 
        FROM fee_cycle WHERE  instituteId= '".$sessionHandler->getSessionVariable('InstituteId')."'
        $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //-------------------------------------------------------------------------------
//
//getTotalFeeCycle() is used to get total no. of records
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getTotalFeeCycle($conditions='') {
    global $sessionHandler;
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM fee_cycle WHERE instituteId= '".$sessionHandler->getSessionVariable('InstituteId')."'
        $conditions ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //-------------------------------------------------------------------------------
//
//checkFeeCycle() is used to get total no. of records
//Author : Jaineesh
// Created on : 13.08.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
//Author : Jaineesh
// Created on : 13.08.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
//Author : Jaineesh
// Created on : 13.08.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function checkFeeReceiptValues($feeCycleId) {
    global $sessionHandler;
       $query = "	SELECT	COUNT(feeCycleId) AS cnt 
					FROM	fee_receipt
					WHERE	feeCycleId = $feeCycleId";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	

}
?>
<?php 
// $History : $
 ?>