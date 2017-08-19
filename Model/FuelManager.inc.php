<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "busstop" TABLE
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class FuelManager {
    private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "FuelRepairManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "FuelRepairManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
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

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A BUSSTOP
//
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function addFuel() {
        global $REQUEST_DATA;
        global $sessionHandler;
        $userId=$sessionHandler->getSessionVariable('UserId');

        $query="INSERT INTO
                           fuel
                           SET
                              busId=".$REQUEST_DATA['busId'].",
                              staffId=".$REQUEST_DATA['staffId'].",
                              dated='".$REQUEST_DATA['dated']."',
                              litres='".trim(add_slashes($REQUEST_DATA['litres']))."',
                              amount='".trim(add_slashes($REQUEST_DATA['amount']))."',
                              lastMilege='".trim(add_slashes($REQUEST_DATA['lastMilege']))."',
                              currentMilege='".trim(add_slashes($REQUEST_DATA['currentMilege']))."',
                              addedOnDate='".date('Y-m-d')."',
                              addByUserId=".$userId;
        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A BUSSTOP
//
//$id:busStopId
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function editFuel($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        $userId=$sessionHandler->getSessionVariable('UserId');

        $query="UPDATE
                           fuel
                           SET
                              busId=".$REQUEST_DATA['busId'].",
                              staffId=".$REQUEST_DATA['staffId'].",
                              dated='".$REQUEST_DATA['dated']."',
                              litres='".trim(add_slashes($REQUEST_DATA['litres']))."',
                              amount='".trim(add_slashes($REQUEST_DATA['amount']))."',
                              lastMilege='".trim(add_slashes($REQUEST_DATA['lastMilege']))."',
                              currentMilege='".trim(add_slashes($REQUEST_DATA['currentMilege']))."',
                              addedOnDate='".date('Y-m-d')."',
                              addByUserId=".$userId."
                              WHERE fuelId=$id";
        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A BUSSTOP
//
//$id:busStopId
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function updatedFuelLastMileageRecords($id,$lastMilege) {
        global $REQUEST_DATA;
        global $sessionHandler;
        $userId=$sessionHandler->getSessionVariable('UserId');

        $query="UPDATE
                           fuel
                           SET
                              lastMilege='".trim(add_slashes($lastMilege))."'
                           WHERE
                               fuelId=$id";
        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUSSTOP LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getFuel($conditions='') {

        $query = "	SELECT
							fuel.fuelId,
							fuel.busId,
							fuel.staffId,
							fuel.dated,
							fuel.litres,
							fuel.amount,
							fuel.lastMilege,
							fuel.currentMilege,
							fuel.addedOnDate,
							vt.vehicleTypeId
					FROM
							fuel,
							bus b,
							vehicle_type vt
					WHERE	fuel.busId = b.busId
					AND		b.vehicleTypeId = vt.vehicleTypeId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//**AS BUSSTOP TABLE IS INDEPENDENT NO NEED TO CHECK TO INTEGRITY CONSTRAINTS**//

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A BUSSTOP
//
//$cityId :busStopid of the Fuel
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function deleteFuel($id) {

        $query = "DELETE
                  FROM
                      fuel
                  WHERE
                      fuelId=$id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUSSTOP LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getFuelList($conditions='', $limit = '', $orderBy=' trs.name') {
        global $sessionHandler;

        $query = "SELECT
                         f.fuelId,f.dated,f.litres,f.amount ,IF(f.lastMilege='NULL','',f.lastMilege) as lastMilege,IF(f.currentMilege='NULL','',f.currentMilege) as currentMilege,
                         bs.busName,bs.busNo,
                         trs.name
                  FROM
                         transport_staff trs,bus bs,fuel f
                  WHERE
                         f.staffId=trs.staffId
                         AND f.busId=bs.busId
                         AND bs.isActive=1
                         $conditions
                  ORDER BY $orderBy
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF BUSSTOPSS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getTotalFuel($conditions='') {
        global $sessionHandler;

        $query = "SELECT
                         COUNT(*) AS totalRecords
                  FROM
                         transport_staff trs,bus bs,fuel f
                  WHERE
                         f.staffId=trs.staffId
                         AND f.busId=bs.busId
                         AND bs.isActive=1
                         $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING last milege for a bus
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getLastMilege($conditions='') {

       $query = "SELECT
                          IFNULL(MAX(CONVERT(currentMilege,unsigned )),0) AS currentMilege
                  FROM
                          fuel
                  $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-----------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING maximum fuelId corresponding to a bus
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------
    public function getMaxFuelId($conditions='') {

       $query = "SELECT
                          IFNULL(MAX(fuelId),0) AS fuelId
                  FROM
                          fuel
                  $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//----------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING last fuel record for a bus
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------
    public function getLastFuelRecord($conditions='') {

       $query = "SELECT
                         litres,dated
                  FROM
                          fuel
                  $conditions
                  ORDER BY fuelId DESC";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING fuel uses data for a set of bus(s)
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getFuelUsesData($conditions='',$orderBy='') {

      $query = "SELECT
                       f.busId,b.busName,b.busNo,
                       SUM( CONVERT(f.litres,unsigned) ) AS fuelConsumed,
                       MAX( CONVERT( f.currentMilege,unsigned ) ) AS totalKm,
                       ROUND(MAX(CONVERT( f.currentMilege,unsigned ))/SUM( CONVERT(f.litres,unsigned) ),2) AS fuelAvg,
                       ts.name,ts.staffType
                 FROM
                      fuel f,bus b,transport_staff ts
                 WHERE
                      f.busId=b.busId
                      AND f.staffId=ts.staffId
                  $conditions
                  GROUP BY busId
                  $orderBy
                ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getAllFuelUsesData($busId, $fromDate, $toDate) {

      $query = "SELECT
                       f.busId,f.amount,b.busName,b.busNo,
                       f.litres AS fuelConsumed,
                       f.currentMilege AS totalKm,
                       ts.name,
					   ts.staffType
                 FROM
                      fuel f,bus b,transport_staff ts
                 WHERE
                      f.busId=b.busId
                      AND f.staffId=ts.staffId
					  AND f.busId = $busId
					  AND f.dated between '$fromDate' AND '$toDate'
					  ORDER BY f.dated
                ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getFuelUsesData2($conditions='',$orderBy='') {

      $query = "SELECT
                       b.busId,b.busName,b.busNo,
                       0 AS fuelConsumed,
                       0 AS totalKm,
                       0 AS fuelAvg,
                       '' AS name,
					   '' AS staffType
                 FROM
                      bus b
                 WHERE
                  $conditions
                  GROUP BY busId
                  $orderBy
                ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING fuel uses data for a set of bus(s)
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function countRefillingOnDate($busId, $fromDate) {

        $query = "	SELECT
							COUNT(*) AS cnt
					FROM
							fuel f,bus b,transport_staff ts
					WHERE
							f.busId=b.busId
					AND		f.staffId=ts.staffId
					AND		f.busId = $busId
					AND		f.dated = '$fromDate'";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING fuel uses data for a set of bus(s)
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getRefillingDate($busId,$fromDate) {

        $query = "	SELECT
							max(dated) AS fromDate
					FROM
		                    fuel
					WHERE
							busId = $busId
                    AND		dated < '$fromDate'
							GROUP BY busId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUS NAME
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function getBusName($busId) {

      $query = "SELECT
                       b.busId,b.busNo
                 FROM
                      bus b
                 WHERE
                      b.busId = $busId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

}
// $History: FuelManager.inc.php $
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Model
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 12/18/09   Time: 6:28p
//Updated in $/Leap/Source/Model
//changes in fuel as database changed
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 6/08/09    Time: 16:04
//Updated in $/Leap/Source/Model
//Corrected validation code for fuel module when we add a fuel entry
//which  is between two existing dates.
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 5/08/09    Time: 17:27
//Updated in $/Leap/Source/Model
//Done bug fixing.
//bug ids--
//0000878 to 0000883
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 27/07/09   Time: 19:00
//Updated in $/Leap/Source/Model
//Updated fuel usage report by adding "fuel usage average details"
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/15/09    Time: 4:56p
//Updated in $/Leap/Source/Model
//Updated fuel usage report replaced busname with bus number
//
//*****************  Version 3  *****************
//User: Administrator Date: 14/05/09   Time: 10:35
//Updated in $/Leap/Source/Model
//Done bug fixing.
//Bug Ids---1001 to 1005
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Model
//Updated fleet mgmt file in Leap
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 30/04/09   Time: 11:26
//Updated in $/SnS/Model
//Done bug fixing
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/04/09    Time: 10:54
//Updated in $/SnS/Model
//Added "Fuel Uses Report" module
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/04/09    Time: 13:36
//Updated in $/SnS/Model
//Enhanced fuel master
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/04/09    Time: 13:04
//Updated in $/SnS/Model
//Enhanced fuel master
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 18:37
//Created in $/SnS/Model
//Created Fuel Master
?>
