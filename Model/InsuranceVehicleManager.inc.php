<?php

//-------------------------------------------------------------------------------
//
//EmployeeManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
  global $FE;
  require_once($FE . "/Library/common.inc.php");
  include_once(DA_PATH ."/SystemDatabaseManager.inc.php");
  
  class InsuranceVehicleManager {
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
//addVehicleType() is used to add new record in database.
// Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addInsuranceVehicle() {
        global $REQUEST_DATA;
		global $sessionHandler;
		
		$busId = trim($REQUEST_DATA['busNo']);
		$insuranceDate = trim($REQUEST_DATA['insuranceDate']);
		$insuranceDueDate = trim($REQUEST_DATA['insuranceDueDate']);
		$insuringCompanyId = trim($REQUEST_DATA['insuringCompanyId']);
		$policyNo = trim($REQUEST_DATA['policyNo']);
		$valueInsured = trim($REQUEST_DATA['valueInsured']);
		$insurancePremium = trim($REQUEST_DATA['insurancePremium']);
		$ncb = trim($REQUEST_DATA['ncb']);
		$paymentMode = trim($REQUEST_DATA['paymentMode']);
		$branchName = trim($REQUEST_DATA['branchName']);
		$agentName = trim($REQUEST_DATA['agentName']);
		$paymentDescription = trim(addslashes($REQUEST_DATA['paymentDescription']));


		$query = "	INSERT INTO bus_insurance (busId,lastInsuranceDate,insuranceDueDate,insuringCompanyId,policyNo,valueInsured,insurancePremium,paymentMode,branchName,agentName,paymentDescription,ncb) 
		VALUES ('$busId','$insuranceDate','$insuranceDueDate','$insuringCompanyId','$policyNo','$valueInsured','$insurancePremium','$paymentMode','$branchName','$agentName','$paymentDescription','$ncb')";

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

//-------------------------------------------------------------------------------
//
// addInsuranceVehicleHistory() is used to add new record in database.
// Author : Kavish Manjkhola
// Created on : 08.04.2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addInsuranceVehicleHistory() {
		global $sessionHandler;
        global $REQUEST_DATA;
		$busId = trim($REQUEST_DATA['busNo']);
		$insuranceId = $sessionHandler->getSessionVariable('IdToInsurance');
	
		$date = new DateTime(date('Y-m-d'));
		$date->modify("+1 Year");
		$insuranceDueDate =  $date->format("Y-m-d");

		$status = 0;  //Status field shows the status of insurance policy O-Unpaid, 1-Paid
		$query = "	INSERT INTO 
								insurance_paid_history (vehicleId,insuranceId,lastInsuranceDate,insuranceDueDate,status,paidOn) 
					VALUES 
								('$busId','$insuranceId','','$insuranceDueDate','$status','')";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }



    
//-------------------------------------------------------------------------------
//
//editVehicleType() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editInsuranceVehicle($id) {
        global $REQUEST_DATA;
		
		$busId = trim($REQUEST_DATA['busNo']);
		$insuranceDate = trim($REQUEST_DATA['insuranceDate']);
		$insuranceDueDate = trim($REQUEST_DATA['insuranceDueDate']);
		$insuringCompanyId = trim($REQUEST_DATA['insuringCompanyId']);
		$policyNo = trim($REQUEST_DATA['policyNo']);
		$valueInsured = trim($REQUEST_DATA['valueInsured']);
		$insurancePremium = trim($REQUEST_DATA['insurancePremium']);
		$ncb = trim($REQUEST_DATA['ncb']);
		$paymentMode = trim($REQUEST_DATA['paymentMode']);
		$branchName = trim($REQUEST_DATA['branchName']);
		$agentName = trim($REQUEST_DATA['agentName']);
		$paymentDescription = trim(addslashes($REQUEST_DATA['paymentDescription']));


		$query = "	UPDATE bus_insurance SET	busId = $busId,
												lastInsuranceDate = '$insuranceDate',
												insuranceDueDate = '$insuranceDueDate',
												insuringCompanyId = '$insuringCompanyId',
												policyNo = '$policyNo',
												valueInsured = '$valueInsured',
												insurancePremium = '$insurancePremium',
												paymentMode = '$paymentMode',
												branchName = '$branchName',
												agentName = '$agentName',
												paymentDescription = '$paymentDescription',
												ncb = '$ncb'
										WHERE	insuranceId = $id";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
        
    }    
  //-------------------------------------------------------------------------------
//
//deleteVehicleType() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function deleteInsuredVehicle($insuranceId) {
     
        $query = "	DELETE 
					FROM bus_insurance 
					WHERE insuranceId=$insuranceId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
   
   //-------------------------------------------------------------------------------
//
//getVehicleType() is used to get the list of data 
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleInsurance($conditions='') {
       $query = "	SELECT	bi.*, 
							b.busId,
							b.busNo,
							vt.vehicleTypeId
					FROM	bus_insurance bi, 
							bus b,
							vehicle_type vt
					WHERE	bi.busId = b.busId
					AND		b.vehicleTypeId = vt.vehicleTypeId
					AND		b.isActive = 1
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //-------------------------------------------------------------------------------
//
//getVehicleTypeList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 26.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getInsuranceVehicleList($conditions='', $limit = '', $orderBy='insuringCompanyName') {
       
		$query = "	SELECT	bi.*, 
							if(bi.paymentMode=1,'Cash',if(bi.paymentMode=2,'Cheque',if(bi.paymentMode=3,'Draft','Cash'))) AS paymentMode, 
							b.busNo, 
							ic.insuringCompanyName
					FROM	bus_insurance bi, 
							bus b, 
							insurance_company ic
					WHERE	bi.busId = b.busId
					AND		bi.insuringCompanyId = ic.insuringCompanyId
							$conditions 
							ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------
//
//getTotalVehicleInsurance() is used to get total no. of records
//Author : Jaineesh
// Created on : 26.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function getTotalInsuranceVehicle($conditions='') {
    
        $query = "	SELECT  COUNT(*) AS totalRecords 
					FROM	bus_insurance bi, 
							bus b, 
							insurance_company ic
					WHERE	bi.busId = b.busId
					AND		bi.insuringCompanyId = ic.insuringCompanyId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getInsuranceDate() is used to get total no. of records
//Author : Jaineesh
// Created on : 26.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function checkInsuranceDate($insuranceDate,$busNo) {
    
       $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	bus_insurance 
					WHERE	('$insuranceDate' BETWEEN lastInsuranceDate AND insuranceDueDate)
					AND		busId = '$busNo'
					$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//checkBus() is used to get total no. of records
//Author : Jaineesh
// Created on : 26.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function checkBus($insuranceId) {
    
       $query = "	SELECT	busId
					FROM	bus_insurance
					WHERE	insuranceId = $insuranceId
					$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//checkBus() is used to get total no. of records
//Author : Jaineesh
// Created on : 26.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function checkBusActive($busId) {
    
       $query = "	SELECT	busId, 
							isActive
					FROM	bus
					WHERE	busId = $busId
					$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//-------------------------------------------------------------------------------
//
//getVehicleInsuranceDetail() is used to vehicle insurance detail
//Author : Jaineesh
// Created on : 06.01.10
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getVehicleInsuranceDetail($insuranceId) {
     
        $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	bus_insurance bi,
							bus b
					WHERE	bi.insuranceId=$insuranceId
					AND		bi.busId = b.busId
					AND		b.isActive = 1";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//-------------------------------------------------------------------------------
//
//updateNotificationsMessage() is used to vehicle insurance detail
//Author : Praveen Kumar
// Created on : 12.04.11
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function updateNotificationsMessage() {
		
		define('NOTIFICATION_MSG_1',' day(s) is/are left to pay the Insurance policy');
		define('NOTIFICATION_MSG_2','Insurance Due Date expired');
     
        $query = "	UPDATE
							notifications n
					SET
							message = (SELECT 
												IF(DATEDIFF(i.insuranceDueDate,now())< 0,
												CONCAT(i.vehicleId,'!~~!','".NOTIFICATION_MSG_2."'),  
												CONCAT(i.vehicleId,'!~~!Only ',DATEDIFF(i.insuranceDueDate,now()),'".NOTIFICATION_MSG_1."'))
									   FROM
												insurance_paid_history i
									   WHERE
												DATEDIFF(i.insuranceDueDate,now()) <=10 AND
												i.vehicleId = SUBSTRING(n.message,1,IF(INSTR(n.message,'!~~!')=0,0,INSTR(n.message,'!~~!')-1)))
					 WHERE
												SUBSTRING(n.message,1,IF(INSTR(n.message,'!~~!')=0,0,INSTR(n.message,'!~~!')-1)) IN
												(SELECT
														DISTINCT i.vehicleId
												 FROM
														insurance_paid_history i
												 WHERE
														DATEDIFF(i.insuranceDueDate,now()) <=10)";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//getInsuranceDueDateList() is used to vehicle insurance due date list
//Author : Kavish Manjkhola
//Created on : 06.04.11
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------  
    public function getInsuranceDueDateList($dayLimit='10') {
     
        $query = "	SELECT 
								count(*) as recordCount,
								CONCAT('Vehicle Insurance Pending') AS message
					FROM 
								insurance_paid_history iph,
								bus b
					WHERE
								DATEDIFF(iph.insuranceDueDate,now()) <= '$dayLimit'
					AND			iph.vehicleId = b.busId
					AND			b.isActive = 1
					GROUP BY 	iph.vehicleId
				 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//---------------------------------------------------------------------------------------
//getInsuranceMessageSearch() is used to Search the message pattern in notification table
//Author : Kavish Manjkhola
//Created on : 18.04.11
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------  
    public function getInsuranceMessageSearch() {
     
        $query = "	SELECT
								count(*) as totalRecords
					FROM
								notifications
					WHERE
								message LIKE '%Vehicle Insurance Pending%'
				 ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//updateInsuranceNotificationsMessage() is used to update message when insurance is paid
//Author : Kavish Manjkhola
//Created on : 06.04.11
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------  
    public function updateInsuranceNotificationsMessage() {
     
        $query = "	UPDATE 
							notifications n 
					SET 	message = (
										SELECT 
												CONCAT(i.vehicleId,'!~~!','Insurance Paid') 
										FROM 
												insurance_paid_history i 
										WHERE 
												i.vehicleId = SUBSTRING(n.message,1,IF(INSTR(n.message,'!~~!')=0,0,INSTR(n.message,'!~~!')-1)) AND i.status=1
										) 
					WHERE 
							SUBSTRING(n.message,1,IF(INSTR(n.message,'!~~!')=0,0,INSTR(n.message,'!~~!')-1)) IN (
										SELECT 
												DISTINCT IFNULL(i.vehicleId,'-1') 
										FROM 
												insurance_paid_history i 
										WHERE 	
												i.status=1)
				 ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//getNotificationDetails() is used to vehicle insurance due date list
//Author : Kavish Manjkhola
//Created on : 06.04.11
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------  
    public function getNotificationDetails() {
     
        $query = "	SELECT 
							SUBSTRING(message,1,IF(INSTR(message,'!~~!')=0,0,INSTR(message,'!~~!')-1)) AS vehicleId
					FROM 
							notifications
				 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//getInsuranceDueDateListDetails() is used to vehicle insurance due date list
//Author : Kavish Manjkhola
//Created on : 06.04.11
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------  
    public function getInsuranceDueDateListDetails($filter, $limit, $orderBy, $dayLimit='10') {
     
        $query = "	SELECT 
								bi.busId, b.busNo, bi.lastInsuranceDate, bi.insuranceDueDate, bi.insuringCompanyId, bi.policyNo, bi.valueInsured, bi.insurancePremium, bi.branchName, bi.agentName
					FROM 
								bus_insurance bi,
								insurance_paid_history iph,
								bus b
					WHERE
								DATEDIFF(iph.insuranceDueDate,now()) <= '$dayLimit'
					AND			iph.vehicleId = bi.busId
					AND			iph.vehicleId = b.busId
					AND			iph.status = 0
					$filter
					ORDER BY $orderBy
					$limit
				 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//getInsuranceDueDateListCount() is used to vehicle insurance due date list
//Author : Kavish Manjkhola
//Created on : 06.04.11
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------  
    public function getInsuranceDueDateListCount($filter, $limit, $orderBy, $dayLimit='10') {
     
        $query = "	SELECT 
								COUNT(bi.busId) AS totalRecords
					FROM 
								bus_insurance bi,
								insurance_paid_history iph,
								bus b
					WHERE
								DATEDIFF(iph.insuranceDueDate,now()) <= '$dayLimit'
					AND			iph.vehicleId = bi.busId
					AND			iph.vehicleId = b.busId
					$filter
					ORDER BY $orderBy
					$limit
				 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//deleteNotifications() is used to insert notification in the notification table
//Author : Kavish Manjkhola
//Created on : 06.04.11
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------  
    public function deleteNotifications() {
     
        $query = "	DELETE FROM notifications
				 ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//insertNotifications() is used to insert notification in the notification table
//Author : Kavish Manjkhola
//Created on : 06.04.11
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------  
    public function insertNotifications($message) {
     
        $query = "	INSERT INTO
								notifications (msgId, message, publishDateTime, viewDateTime)
					VALUES
								('','$message',now(),'')
				 ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }


//-----------------------------------------------------------------------
//getVehicleDetials() is used to fetch vehicle details based on vehicleId
//Author : Kavish Manjkhola
//Created on : 11.04.11
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------
    public function getVehicleInsuranceDueDate($vehicleId) {
     
        $query = "	SELECT 
							insuranceDueDate
					FROM
							bus_insurance
					WHERE	busId = $vehicleId
				 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------------------
//updateVehicleInsuranceDetails() is used to insert vehicle insurance detials in the bus_insurance table
//Author : Kavish Manjkhola
//Created on : 11.04.11
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------
    public function updateVehicleInsuranceDetails($vehicleId, $insuranceDueDate, $insurancePaidOn) {
		

		$query = "	UPDATE
							bus_insurance
					SET
							lastInsuranceDate = '$insurancePaidOn',
							insuranceDueDate = '$insuranceDueDate'
					WHERE	
							busId = '$vehicleId'
				 ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


//-----------------------------------------------------------------------------------------------------------------
//updateVehicleInsuranceStatus() is used to update the vehicle insurance status in the insurance_paid_history table
//Author : Kavish Manjkhola
//Created on : 11.04.11
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------
    public function updateVehicleInsuranceStatus($vehicleId, $insuranceDueDate, $insurancePaidOn) {
     
        $query = "	UPDATE
							insurance_paid_history
					SET		
							status = '1',
							lastInsuranceDate = '$insurancePaidOn',
							insuranceDueDate = '$insuranceDueDate',
							paidOn = now()
					WHERE	
							vehicleId = '$vehicleId' 
				 ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


//---------------------------------------------------------------------------------------
//updateViewStatus() is used to update the notification status in the notifications table
//Author : Kavish Manjkhola
//Created on : 11.04.11
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------
    public function updateViewStatus() {
     
        $query = "	UPDATE
							notifications
					SET
							viewStatus = 1
					WHERE
							message LIKE '%Vehicle Insurance Pending%' 
				 ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }



//-------------------------------------------------------------------------------
//getNotificationViewStatus() is used to fetch the viewStatus from the notification
//Author : Kavish Manjkhola
//Created on : 18.04.2011
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------
    public function getNotificationViewStatus() {
     
        $query = "	SELECT
							viewStatus, viewDateTime
					FROM
							notifications
				 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-----------------------------------------------------------------------------------------------------------------
//getInsurancePendingList() is used to fetch the totalRecords from the insurance_paid_history table based on status
//Author : Kavish Manjkhola
//Created on : 18.04.2011
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------
    public function getInsurancePendingList() {
     
        $query = "	SELECT
							count(*) AS totalRecords
					FROM
							insurance_paid_history
					WHERE
							status = 0
				 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-----------------------------------------------------------------------------------------------------------------
//updateNoticePublishTime() is used to update the vehicle insurance status in the insurance_paid_history table
//Author : Kavish Manjkhola
//Created on : 11.04.11
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------
    public function updateNoticePublishTime() {
     
        $query = "	UPDATE
							notifications
					SET
							publishDateTime = now(), viewStatus = 0
					WHERE
							message LIKE '%Vehicle Insurance Pending%' 
				 ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }

}
?>
<?php
// $History: InsuranceVehicleManager.inc.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Model
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Model
//fixed bug in fleet management
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Model
//fixed bug on fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Model
//fixed bug during self testing
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:17p
//Created in $/Leap/Source/Model
//new model file for vehicle insurance
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:29p
//Created in $/Leap/Source/Model
//new model for vehicle insurance
//
?>