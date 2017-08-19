<?php
//-------------------------------------------------------------------------------
//
//PeriodsManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for InstituteId

class PeriodsManager {
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
//addPeriods() function is used for adding new periods into the period table....
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function addPeriods() {
		global $REQUEST_DATA;
        global $sessionHandler;
        
		return SystemDatabaseManager::getInstance()->runAutoInsert('period', array('periodNumber','startTime', 
        'startAmPm', 'endTime', 'endAmPm', 'periodSlotId'), array(strtoupper($REQUEST_DATA['periodNumber']), $REQUEST_DATA['startTime'],$REQUEST_DATA['startAmPm'], $REQUEST_DATA['endTime'],$REQUEST_DATA['endAmPm'], $REQUEST_DATA['slotName']));
	}
 //-------------------------------------------------------------------------------
//
//editPeriods() function is used for edit the existing period into the period table....
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editPeriods($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('period', array('periodNumber','startTime',
        'startAmPm','endTime','endAmPm', 'periodSlotId'), array(strtoupper($REQUEST_DATA['periodNumber']),$REQUEST_DATA['startTime'],$REQUEST_DATA['startAmPm'], $REQUEST_DATA['endTime'],$REQUEST_DATA['endAmPm'],$REQUEST_DATA['slotName']), "periodId=$id" );
    }  
    
    //-------------------------------------------------------------------------------
//
//getPeriods() function is used for getting the value of period table....
// 
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getPeriods($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');	
     $query = "	SELECT 
								pr.periodId, 
								pr.periodNumber,
								if(pr.startTime='00:00:00','',date_format(pr.startTime, '%h:%i')) as startTime,
								pr.startAmPm,
								if(pr.endTime='00:00:00','',date_format(pr.endTime, '%h:%i')) as endTime,
								pr.endAmPm,
								ps.slotName,
								pr.periodSlotId
					FROM		period pr,
								period_slot ps
					WHERE		pr.periodSlotId = ps.periodSlotId
					AND			ps.instituteId = $instituteId
								$conditions";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
//-------------------------------------------------------------------------------
//
//deletePeriods() function is used to delete records from Employee....
// $periodId - used to generate the unique id of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function checkPeriodWithTimeTable($periodId) {
     
        $query = "	SELECT	COUNT(periodId) as totalRecords
					FROM	 ".TIME_TABLE_TABLE."  
					WHERE	periodId=$periodId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//deletePeriods() function is used to delete records from Employee....
// $periodId - used to generate the unique id of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function deletePeriods($periodId) {
     
        $query = "DELETE 
        FROM period 
        WHERE periodId=$periodId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
       //-------------------------------------------------------------------------------
//
//getPeriodsList() function is used to list the records of Period table
// $condtions - used to check condition while selecting the records
// $limit - used to check the limit of showing records in list
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getPeriodsList($conditions='', $limit = '', $orderBy=' pr.periodNumber') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
      $query = "	SELECT 
									pr.periodId,
									pr.periodNumber,
									ps.slotName, 
									if(pr.startTime='00:00:00','--',CONCAT(date_format(pr.startTime,'%h:%i'),\"  \", pr.startAmPm)) as startTime,
									if(pr.endTime='00:00:00','--',CONCAT(date_format(pr.endTime,'%h:%i'),\"  \",pr.endAmPm)) as endTime
					FROM			period pr,
									period_slot ps
					WHERE			ps.periodSlotId = pr.periodSlotId
					AND				ps.instituteId = $instituteId
									$conditions 
									ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    //-------------------------------------------------------------------------------
//
//getTotalPeriods() function returns the total no. of records
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getTotalPeriods($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "	SELECT			COUNT(*) AS totalRecords 
					FROM			period pr,
									period_slot ps
					WHERE			ps.periodSlotId = pr.periodSlotId
					AND				ps.instituteId = $instituteId
									$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	 public function getPeriodSlotPeriods($periodSlotId, $periodId = '') {
		 $conditions = '';
		 if ($periodId != '') {
			 $conditions = " AND periodId != $periodId";
		 }
		 $query = "SELECT startTime, endTime, startAmPm, endAmPm FROM period WHERE periodSlotId = $periodSlotId $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }
}
 
  //$History : $
  //
?>