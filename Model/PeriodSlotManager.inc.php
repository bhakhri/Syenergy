<?php
//-------------------------------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "period_slot" TABLE
// Author :Jaineesh 
// Created on : (15.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); 

class PeriodSlotManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "PeriodSlotManager" CLASS
//
// Author : Jaineesh 
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "PeriodSlotManager" CLASS
//
// Author :Jaineesh 
// Created on : (15.12.2008)
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
// THIS FUNCTION IS USED FOR ADDING A  PERIOD SLOT DETAIL
//
// Author : Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addPeriodSlot() {
		global $REQUEST_DATA;
        global $sessionHandler;  

		return SystemDatabaseManager::getInstance()->runAutoInsert('period_slot', 
        array('slotName','slotAbbr','isActive', 'instituteId'), 
        array($REQUEST_DATA['slotName'],$REQUEST_DATA['slotAbbr'],$REQUEST_DATA['isActive'],$sessionHandler->getSessionVariable('InstituteId')));
	}

//--------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A PERIOD SLOT
//
//$id:periodSlotId
// Author : Jaineesh 
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------        
    public function editPeriodSlot($id) {
        global $REQUEST_DATA;
        global $sessionHandler;  
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('period_slot', 
        array('slotName','slotAbbr','isActive','instituteId'), array($REQUEST_DATA['slotName'],$REQUEST_DATA['slotAbbr'],$REQUEST_DATA['isActive'], $sessionHandler->getSessionVariable('InstituteId')),"periodSlotId=$id");
    }   
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING PERIOD SLOT LIST
//
//$conditions :db clauses
// Author : Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getPeriodSlot($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
     
       $query = "SELECT periodSlotId,slotName,slotAbbr,isActive
        FROM period_slot
        $conditions";
        
        //echo  $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A PERIOD SLOT
//
//$periodSlotId :periodSlotId of the PEROD SLOT
// Author :Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deletePeriodSlot($periodSlotId) {
     
        $query = "DELETE 
        FROM period_slot 
        WHERE periodSlotId=$periodSlotId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING PERIOD SLOT LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh 
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getPeriodSlotDetail($conditions='', $limit = '', $orderBy=' slotName') {
        global $sessionHandler;  
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        
     $query = "	SELECT 
							periodSlotId,
							slotName,
							slotAbbr,
							IF(period_slot.isActive=1,'Yes','No') AS isActive
					FROM	period_slot
							$conditions ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

		
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF PERIOD SLOT 
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalPeriodSlotDetail($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        
        $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	period_slot $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Making All period slot Inactive
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function makeAllPeriodSlotInActive($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        
        $query = "UPDATE period_slot ps 
        SET ps.isActive=0
        WHERE ps.isActive=1
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }


//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET ACTIVE PERIODSLOTID
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getPeriodSlotIdActive($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        
       $query = "		SELECT	periodSlotId
						FROM	period_slot
						WHERE	isActive=1
						$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getTotalPeriods() function returns the total no. of records
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getPeriodSlotId($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        
     $query = "	SELECT 
							period_slot.periodSlotId,
							period.periodSlotId
					FROM	period_slot, 
							period 
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   




}

// $History: PeriodSlotManager.inc.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:33p
//Created in $/LeapCC/Model
//get all the data base query for period slot
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:16p
//Created in $/Leap/Source/Model
//get all the database query of period slot
//

?>
