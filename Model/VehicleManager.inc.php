<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "busstop" TABLE
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class VehicleManager {
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

	public function getVehicleTyres($vehicleTypeId) {
		$query = "SELECT mainTyres, spareTyres FROM `vehicle_type` WHERE vehicleTypeId = '$vehicleTypeId'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function coutVehicleType($vehicleTypeId) {
		$query = "SELECT COUNT(vehicleTypeId) AS cnt FROM `vehicle_type` WHERE vehicleTypeId = '$vehicleTypeId'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countVehicleNo($busNo,$conditions='') {
		$query = "SELECT COUNT(busId) AS cnt FROM `bus` WHERE busNo = '$busNo' $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countEngineNo($engineNo,$conditions='') {
		$query = "SELECT COUNT(engineNo) AS cnt FROM `bus` WHERE engineNo = '$engineNo' $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countChasisNo($chasisNo,$conditions='') {
		$query = "SELECT COUNT(chasisNo) AS cnt FROM `bus` WHERE chasisNo = '$chasisNo' $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countPolicyNo($policyNo,$conditions='') {
		$query = "SELECT COUNT(policyNo) AS cnt FROM `bus_insurance` WHERE policyNo = '$policyNo' $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countInsuringCompany($insuringCompanyId) {
		$query = "SELECT COUNT(insuringCompanyId) AS cnt FROM `insurance_company` WHERE insuringCompanyId = '$insuringCompanyId'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function addVehicleInTransaction($saveString) {
		$query = "INSERT INTO `bus` SET $saveString";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	public function updateVehicleInTransaction($saveString, $busId) {
		$query = "UPDATE `bus` SET $saveString WHERE busId = '$busId'";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	public function getLastVehicleId() {
		$query = "SELECT MAX(busId) AS busId from bus";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function addVehicleInsuranceInTransaction($insertedBusId, $insuranceDate, $insuranceDueDate, $policyNo, $insuringCompanyId, $valueInsured, $insurancePremium, $paymentMode, $branchName, $agentName, $paymentDescription, $ncb) {
		global $sessionHandler;
		$query = "INSERT INTO `bus_insurance` SET busId = '$insertedBusId', lastInsuranceDate = '$insuranceDate', insuranceDueDate= '$insuranceDueDate', policyNo = '$policyNo', insuringCompanyId = '$insuringCompanyId', valueInsured = '$valueInsured', insurancePremium = '$insurancePremium', paymentMode = '$paymentMode', branchName = '$branchName', agentName = '$agentName', paymentDescription = '$paymentDescription', ncb = '$ncb'";
		$returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
		if($returnStatus===false) {
	  	   return false;
		}
		else {
 		   $insuranceId=SystemDatabaseManager::getInstance()->lastInsertId();
           $sessionHandler->setSessionVariable('IdToInsurance',$insuranceId);
		   return true;
		}
	}



//------------------------------------------------------
//
//addVehicleType() is used to add new record in database.
// Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------
    public function addInsuranceVehicleHistory($insertedBusId, $insuranceDate, $insuranceDueDate) {
		global $sessionHandler;
        global $REQUEST_DATA;
		$insuranceId = $sessionHandler->getSessionVariable('IdToInsurance');
		$status = 0;  //Status field shows the status of insurance policy O-Unpaid, 1-Paid
		$query = "	
					INSERT INTO 
								insurance_paid_history (vehicleId,insuranceId,lastInsuranceDate,insuranceDueDate,status,paidOn) 
					VALUES 
								('$insertedBusId','$insuranceId','$insuranceDate','$insuranceDueDate','$status','')";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }



	public function addVehicleTaxInTransaction($insertedBusId, $regnNoValidTill, $passengerTaxValidTill, $roadTaxValidTill, $pollutionTaxValidTill, $passingValidTill) {
		$query = "INSERT INTO `bus_entries` SET busId = '$insertedBusId', busNoValidTill = '$regnNoValidTill', passengerTaxValidTill= '$passengerTaxValidTill', roadTaxValidTill = '$roadTaxValidTill', pollutionCheckValidTill = '$pollutionTaxValidTill', passingValidTill = '$passingValidTill'";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	public function addVehicleBatteryTransaction($insertedBusId, $batteryNo, $batteryMake, $warrantyDate) {
		$query = "INSERT INTO `bus_battery` SET busId = '$insertedBusId', batteryNo = '$batteryNo', batteryMake= '$batteryMake', warrantyDate = '$warrantyDate'";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	public function updateVehicleInsuranceInTransaction($busId, $insuranceDate, $insuranceDueDate, $policyNo, $insuringCompanyId, $valueInsured, $insurancePremium, $paymentMode, $branchName, $agentName, $paymentDescription, $ncb) {
		$query = "UPDATE `bus_insurance` SET  lastInsuranceDate = '$insuranceDate', insuranceDueDate = '$insuranceDueDate', policyNo = '$policyNo', insuringCompanyId = '$insuringCompanyId', valueInsured = '$valueInsured', insurancePremium = '$insurancePremium', paymentMode = '$paymentMode', branchName = '$branchName', agentName = '$agentName', paymentDescription = '$paymentDescription', ncb = '$ncb' WHERE busId = '$busId'";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	public function countTyreNo($tyreNo) {
		$query = "SELECT COUNT(tyreNumber) AS cnt FROM `tyre_master` WHERE tyreNumber = '$tyreNo'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countBatteryNo($batteryNo) {
		$query = "SELECT COUNT(batteryNo) AS cnt FROM `bus_battery` WHERE batteryNo = '$batteryNo'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function addTyreInTransaction($tyreNo, $tyreManufacturingCompany, $tyreModelNo, $purchaseDate, $active) {
		$query = "INSERT INTO `tyre_master` SET tyreNumber = '$tyreNo', manufacturer = '$tyreManufacturingCompany', modelNumber = '$tyreModelNo', purchaseDate = '$purchaseDate', isActive = '$active'";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	public function addServiceInTransaction($insertedBusId, $serviceNo, $serviceDate, $serviceKM) {
		 $query = "INSERT INTO `bus_service` SET busId = '$insertedBusId', serviceType = '1', serviceNo = '$serviceNo', serviceDueDate = '$serviceDate', serviceDueKM = '$serviceKM'";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	public function getLastTyreId() {
		$query = "SELECT MAX(tyreId) AS tyreId from tyre_master";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function addTyreHistoryInTransaction($insertedTyreId, $insertedBusId, $busReadingOnInstallation, $busReadingOnRemoval, $purchaseDate, $usedAsMainTyre, $tyrePlacementReason, $prevHistoryId) {
		$query = "INSERT INTO `tyre_history` SET tyreId = '$insertedTyreId', busId = '$insertedBusId', busReadingOnInstallation = '$busReadingOnInstallation', busReadingOnRemoval = '$busReadingOnRemoval', placementDate = '$purchaseDate', usedAsMainTyre = '$usedAsMainTyre', placementReason = '$tyrePlacementReason', prevHistoryId = '$prevHistoryId'";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	public function getCountVehicleList($conditions = '', $limit = '',$orderBy = '') {
        //AND            b.isActive = 1
	/*	$query = "
			SELECT
						count(*) AS totalRecords
			FROM		vehicle_type vt, bus b
			WHERE		vt.vehicleTypeId = b.vehicleTypeId
                        AND   b.isActive = 1
						$conditions
						$orderBy $limit
		";      */
        $query = "
             SELECT COUNT(*) AS totalRecords FROM (SELECT
                                bs.busNo, bs.busName, bs.engineNo, bs.chasisNo, bs.purchaseDate, bs.modelNumber, bs.isActive,
                                vt.vehicleType, bi.lastInsuranceDate, bi.branchName, bi.agentName,
                                (SELECT DISTINCT    insuringCompanyName
                                FROM insurance_company ic
                                WHERE ic.insuringCompanyId = bi.insuringCompanyId)
                                AS insuringCompanyName,
                                bi.insuranceDueDate,  pvno.passengerTaxValidTill,rtno.roadTaxValidTill,pcno.pollutionCheckValidTill,pano.passingValidTill

                    FROM
                                vehicle_type vt,
                                bus bs
                                LEFT JOIN bus_insurance bi ON bs.busId = bi.busId
                                        AND
                                        bi.lastInsuranceDate IN
                                            (SELECT MAX(lastInsuranceDate) FROM bus_insurance WHERE busId = bi.busId)

                                LEFT JOIN bus_entries pvno ON pvno.busId=bs.busId AND
                                pvno.passengerTaxValidTill IN
                                            (SELECT MAX(passengerTaxValidTill) FROM bus_entries WHERE busId = pvno.busId)

                                LEFT JOIN bus_entries rtno ON rtno.busId=bs.busId AND
                                rtno.roadTaxValidTill IN
                                            (SELECT MAX(roadTaxValidTill) FROM bus_entries WHERE busId = rtno.busId)

                                LEFT JOIN bus_entries pcno ON pcno.busId=bs.busId AND
                                pcno.pollutionCheckValidTill IN
                                         (SELECT MAX(pollutionCheckValidTill) FROM bus_entries WHERE busId = pcno.busId)

                                LEFT JOIN bus_entries pano ON pano.busId=bs.busId AND
                                pano.passingValidTill IN
                                        (SELECT MAX(passingValidTill) FROM bus_entries WHERE busId = pano.busId)

                WHERE
                                vt.vehicleTypeId = bs.vehicleTypeId AND
                                bs.isActive = 1) AS tt ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getVehicleList($conditions = '', $limit = '',$orderBy = '') {
        //AND            b.isActive = 1
	/*	 $query = "
			SELECT
						b.busId,
						b.busNo,
                        b.isActive,
						vt.vehicleType,
						(SELECT MIN(bi.lastInsuranceDate) FROM  bus_insurance bi WHERE b.busId = bi.busId) as lastInsuranceDate,
						(SELECT ic.insuringCompanyName from insurance_company ic, bus_insurance bi WHERE b.busId = bi.busId and bi.insuringCompanyId = ic.insuringCompanyId ORDER BY insuranceId ASC LIMIT 0,1) as insuringCompanyName
			FROM		vehicle_type vt, bus b
			WHERE		vt.vehicleTypeId = b.vehicleTypeId
                        AND   b.isActive = 1
						$conditions
						$orderBy $limit
		";        */
         $query = "    SELECT
                                 bs.busId, bs.busNo, bs.busName, bs.engineNo, bs.chasisNo, bs.purchaseDate, bs.modelNumber, bs.isActive,
                                vt.vehicleType, bi.lastInsuranceDate, bi.branchName, bi.agentName,
                                (SELECT DISTINCT    insuringCompanyName
                                FROM insurance_company ic
                                WHERE ic.insuringCompanyId = bi.insuringCompanyId)
                                AS insuringCompanyName,
                                bi.insuranceDueDate,  pvno.passengerTaxValidTill,rtno.roadTaxValidTill,pcno.pollutionCheckValidTill,pano.passingValidTill

                    FROM
                                vehicle_type vt,
                                bus bs
                                LEFT JOIN bus_insurance bi ON bs.busId = bi.busId
                                        AND
                                        bi.lastInsuranceDate IN
                                            (SELECT MAX(lastInsuranceDate) FROM bus_insurance WHERE busId = bi.busId)

                                LEFT JOIN bus_entries pvno ON pvno.busId=bs.busId AND
                                pvno.passengerTaxValidTill IN
                                            (SELECT MAX(passengerTaxValidTill) FROM bus_entries WHERE busId = pvno.busId)

                                LEFT JOIN bus_entries rtno ON rtno.busId=bs.busId AND
                                rtno.roadTaxValidTill IN
                                            (SELECT MAX(roadTaxValidTill) FROM bus_entries WHERE busId = rtno.busId)

                                LEFT JOIN bus_entries pcno ON pcno.busId=bs.busId AND
                                pcno.pollutionCheckValidTill IN
                                         (SELECT MAX(pollutionCheckValidTill) FROM bus_entries WHERE busId = pcno.busId)

                                LEFT JOIN bus_entries pano ON pano.busId=bs.busId AND
                                pano.passingValidTill IN
                                        (SELECT MAX(passingValidTill) FROM bus_entries WHERE busId = pano.busId)

                WHERE
                                vt.vehicleTypeId = bs.vehicleTypeId AND
                                bs.isActive = 1
                                 $conditions
                                 $orderBy $limit ";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countVehicleId($vehicleId) {
		$query = "SELECT COUNT(busId) AS cnt FROM `bus` WHERE busId = '$vehicleId'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getVehicleDetails($vehicleId) {
		$query = "
				SELECT
							vehicleTypeId,
                            busId,
							busName,
							busNo,
							engineNo,
							chasisNo,
							yearOfManufacturing,
							purchaseDate,
							modelNumber,
							busImage,
							seatingCapacity,
							fuelCapacity,
							if(bodyMaker='','---',bodyMaker) AS bodyMaker,
							chasisCost,
							chasisPurchaseDate,
							bodyCost,
							putOnRoad,
                            vechicleCategoryId
				FROM		`bus`
				WHERE		busId = '$vehicleId'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getVehicleLastInsuranceDetails($vehicleId) {
		 $query = "	SELECT	*
					FROM	`bus_insurance`
					WHERE	insuranceId IN (SELECT MAX(insuranceId) FROM `bus_insurance` WHERE busId = '$vehicleId') ORDER BY lastInsuranceDate ASC LIMIT 0,1";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getVehicleTaxDetail($vehicleId) {
		$query = "	SELECT	MAX(busNoValidTill) AS busNoValidTill,
							MAX(passengerTaxValidTill) AS passengerTaxValidTill,
							MAX(roadTaxValidTill) AS roadTaxValidTill,
							MAX(pollutionCheckValidTill) AS pollutionCheckValidTill,
							MAX(passingValidTill) AS passingValidTill
					FROM	`bus_entries` WHERE busId = '$vehicleId' GROUP BY busId ORDER BY busNoValidTill ASC";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getVehicleTyreDetail($vehicleId) {
		$query = "	SELECT	tm.modelNumber,
							tm.manufacturer,
							tm.tyreNumber,
							th.usedAsMainTyre
					FROM	tyre_master tm,
							tyre_history th
					WHERE	tm.tyreId = th.tyreId
					AND		tm.isActive = 1
					AND		th.busId = '$vehicleId'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getTotalVehicleService($vehicleId) {
		$query = "	SELECT	count(serviceNo) AS countRecords
					FROM	bus_service
					WHERE	done = 0
					AND		serviceType = 1
					AND		busId = '$vehicleId'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getVehicleServiceDetail($vehicleId) {
		$query = "	SELECT	serviceNo,
							serviceDueDate,
							serviceDueKM
					FROM	bus_service
					WHERE	done = 0
					AND		serviceType = 1
					AND		busId = '$vehicleId'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getVehicleBatteryDetail($vehicleId) {
		$query = "	SELECT	batteryNo,
							batteryMake,
							warrantyDate
					FROM	`bus_battery` WHERE busId = '$vehicleId' ORDER BY busId ASC LIMIT 0,1";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getVehicleLastTyreDetails($vehicleId) {
		$query = "SELECT b.*, a.tyreNumber FROM tyre_history b, tyre_master a WHERE b.busId = $vehicleId AND a.tyreId = b.tyreId AND b.historyId NOT IN (b.prevHistoryId)  AND a.isActive = 1";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function discardBusInTransaction($vehicleId) {
		$query = "UPDATE bus set isActive = 0 where busId = '$vehicleId'";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

    public function getVehicleImage($busId) {
        $query = "SELECT busImage FROM `bus` WHERE busId = '$busId' ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function updateVehicleImage($busId,$imageName) {
        if($fileName!=''){
            $query="UPDATE bus SET busImage='".$fileName."' WHERE busId=".$busId;
        }
        else{
            $query="UPDATE bus SET busImage=Null WHERE busId=".$busId;
        }

        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

	public function getVehicleInsuranceList($vehicleId,$conditions) {
		$query = "	SELECT	bi.*,
							ic.insuringCompanyName
					FROM	bus_insurance bi,
							insurance_company ic
					WHERE	busId = '$vehicleId'
					AND		bi.insuringCompanyId = ic.insuringCompanyId
							$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getVehicleFuelList($vehicleId,$fuelStaffId,$conditions) {
		//$staffId = $REQUEST_DATA['staffId'];
		$query = "	SELECT	f.*,
							ts.name
					FROM	fuel f,
							transport_staff ts
					WHERE	f.busId = '$vehicleId'
					AND		f.staffId = ts.staffId
					AND		f.staffId = $fuelStaffId
							$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getVehicleAccidentList($vehicleId,$accidentStaffId,$conditions) {

		$query = "	SELECT	ba.*,
							date(ba.accidentDate) AS accidentDate,
							ts.name,
							br.routeName
					FROM	bus_accident ba,
							transport_staff ts,
							bus_route br
					WHERE	ba.busId = '$vehicleId'
					AND		ts.staffId = ba.staffId
					AND		ba.staffId = $accidentStaffId
							$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getVehicleServiceList($vehicleId,$busServiceId,$conditions) {

		$query = "	SELECT	bs.*
					FROM	bus_service bs
					WHERE	bs.busId = '$vehicleId'
					AND		bs.serviceType = $busServiceId
							$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


}
// $History: VehicleManager.inc.php $
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 1/29/10    Time: 5:23p
//Updated in $/Leap/Source/Model
//add functions and link for vehcile report
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 1/25/10    Time: 11:14a
//Updated in $/Leap/Source/Model
//Show latest vehicle insurance detail non-editable
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 1/08/10    Time: 7:39p
//Updated in $/Leap/Source/Model
//fixed bug in fleet management
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 1/07/10    Time: 2:44p
//Updated in $/Leap/Source/Model
//fixed bug for fleet management
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 12/29/09   Time: 10:10a
//Updated in $/Leap/Source/Model
//fixed bugs
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 12/24/09   Time: 7:05p
//Updated in $/Leap/Source/Model
//fixed bug nos.0002354,0002353,0002351,0002352,0002350,0002347,0002348,0
//002355,0002349
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Model
//fixed bug during self testing
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/17/09   Time: 3:41p
//Updated in $/Leap/Source/Model
//put DL image in transport staff and changes in modules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/12/09    Time: 10:14
//Updated in $/Leap/Source/Model
//checked in files
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/07/09   Time: 12:44p
//Created in $/Leap/Source/Model
//initial file check-in
//

?>
