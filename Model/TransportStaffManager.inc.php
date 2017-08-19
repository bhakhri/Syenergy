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

class TransportStaffManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "TransportStuffRepairManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "TransportStuffRepairManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
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
// THIS FUNCTION IS USED FOR ADDING A BUSSTOP
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addTransportStaff() {
        global $REQUEST_DATA;
        global $sessionHandler;
        $userId=$sessionHandler->getSessionVariable('UserId');
        
        $leavingDate=$REQUEST_DATA['leavingDate']!='' ? $REQUEST_DATA['leavingDate'] : NULL;

        return SystemDatabaseManager::getInstance()->runAutoInsert('transport_staff',
        array('name','permanantAddress','dob','staffCode','dlNo','dlIssuingDate','dlExpiryDate','dlIssuingAuthority','verificationDone','bloodGroup','joiningDate','leavingDate','medicalExaminationDate','staffType','addByUserId'), 
        array(
				trim(add_slashes($REQUEST_DATA['staffName'])),
				trim(add_slashes($REQUEST_DATA['address'])),
				trim(add_slashes($REQUEST_DATA['dob'])),
				trim(add_slashes($REQUEST_DATA['staffCode'])),
				trim(add_slashes($REQUEST_DATA['dlNo'])),
				trim(add_slashes($REQUEST_DATA['issueDate'])),
				$REQUEST_DATA['dlExp'],
				trim(add_slashes($REQUEST_DATA['dlAuthority'])),
				$REQUEST_DATA['verificationDone'],
				$REQUEST_DATA['bloodGroup'],
				$REQUEST_DATA['join'],
				$leavingDate,
				$REQUEST_DATA['medExamDate'],
				$REQUEST_DATA['staffType'],
				$userId
             ) 
        );
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A BUSSTOP
//
//$id:busStopId
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editTransportStaff($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        $userId=$sessionHandler->getSessionVariable('UserId');
        
        $leavingDate=$REQUEST_DATA['leavingDate']!='' ? $REQUEST_DATA['leavingDate'] : NULL;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('transport_staff',
        array('name','permanantAddress','dob','staffCode','dlNo','dlIssuingDate','dlExpiryDate','dlIssuingAuthority','verificationDone','bloodGroup','joiningDate','leavingDate','medicalExaminationDate','staffType'), 
        array(
               trim(add_slashes($REQUEST_DATA['staffName'])),
				trim(add_slashes($REQUEST_DATA['address'])),
				trim(add_slashes($REQUEST_DATA['dob'])),
				trim(add_slashes($REQUEST_DATA['staffCode'])),
				trim(add_slashes($REQUEST_DATA['dlNo'])),
				trim(add_slashes($REQUEST_DATA['issueDate'])),
				$REQUEST_DATA['dlExp'],
				trim(add_slashes($REQUEST_DATA['dlAuthority'])),
				$REQUEST_DATA['verificationDone'],
				$REQUEST_DATA['bloodGroup'],
				$REQUEST_DATA['join'],
				$leavingDate,
				$REQUEST_DATA['medExamDate'],
				$REQUEST_DATA['staffType'],
             ), 
        "staffId=$id" 
        );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUSSTOP LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getTransportStaff($conditions='') {
     
        $query = "SELECT
                          staffId,
						  name,
						  permanantAddress,
						  IF(dob IS NULL OR dob='0000-00-00',-1,dob) AS dob,
						  staffCode,
						  dlNo,
						  dlIssuingDate,
						  dlExpiryDate,
						  dlIssuingAuthority,
						  verificationDone,
						  bloodGroup,
						  joiningDate,
						  staffType,
                          IF(leavingDate IS NULL OR leavingDate='0000-00-00',-1,leavingDate) AS leavingDate,
						  IF(medicalExaminationDate='0000-00-00',-1,medicalExaminationDate) AS medicalExaminationDate,
						  if(photo IS NULL OR photo='',-1,photo) as photo,
						  if(dlPhoto IS NULL OR dlPhoto = '',-1,dlPhoto) as dlPhoto
                  FROM 
                          transport_staff
                  $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//**AS BUSSTOP TABLE IS INDEPENDENT NO NEED TO CHECK TO INTEGRITY CONSTRAINTS**//

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A BUSSTOP
//
//$cityId :busStopid of the TransportStuff
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteTransportStaff($id) {
     
        $query = "DELETE 
                  FROM
                      transport_staff
                  WHERE 
                      staffId=$id";
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getTransportStaffList($conditions='', $limit = '', $orderBy=' name') {
        global $sessionHandler;
        
        $query = "SELECT 
                         staffId,
						 name,
						 staffCode,
						 dlNo,
						 dlIssuingAuthority,
						 dlExpiryDate,
						 joiningDate,
						 staffType,
						 IF(medicalExaminationDate='0000-00-00','--',medicalExaminationDate) AS medicalExaminationDate,
                         IF(verificationDone =1,'Yes','No') AS verificationDone 
                  FROM 
                         transport_staff
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalTransportStaff($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT 
                         COUNT(*) AS totalRecords 
                  FROM 
                         transport_staff
                         $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Staff Image
//
// Author :Jaineesh                        
// Created on : (10.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------- 
	public function updateStaffImage($id, $fileName) {
        if($fileName!=''){
          $query="UPDATE transport_staff SET photo='".$fileName."' WHERE staffId=".$id;
        }
        else{
            $query="UPDATE transport_staff SET employeeImage=NULL WHERE staffId=".$id;
        }
        
        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }


	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Staff Image
//
// Author :Jaineesh                        
// Created on : (10.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------- 
	public function updateDLImage($id, $fileName1) {
        if($fileName1!=''){
          $query="UPDATE transport_staff SET dlPhoto='".$fileName1."' WHERE staffId=".$id;
        }
        else{
            $query="UPDATE transport_staff SET dlPhoto=NULL WHERE staffId=".$id;
        }
        
        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }
  
  //---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE IMAGE DETAIL
//
// Author :Jaineesh                        
// Created on : (20.08.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------- 
	public function getStaffImageDetail($condition) {
        $query = "SELECT photo FROM transport_staff $condition";

		return SystemDatabaseManager::getInstance()->executeQuery($query);
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE IMAGE DETAIL
//
// Author :Jaineesh                        
// Created on : (20.08.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------- 
	public function getDLImageDetail($condition) {
        $query = "SELECT dlPhoto FROM transport_staff $condition";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Employee Image
//
// Author :Jaineesh                        
// Created on : (20.08.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------- 
	public function deleteStaffImage($id = '') {
		$query="UPDATE transport_staff SET photo = NULL WHERE staffId = ".$id;

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Employee Image
//
// Author :Jaineesh                        
// Created on : (20.08.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------- 
	public function deleteDLImage($id = '') {
		$query="UPDATE transport_staff SET dlPhoto = NULL WHERE staffId = ".$id;

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//-------------------------------------------------------------------------------
//
//getVehicleAccidentStaffDetail() is used to vehicle accident detail
//Author : Jaineesh
// Created on : 06.01.10
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getVehicleAccidentStaffDetail($staffId) {
     
        $query = "	SELECT	COUNT(*) AS totalRecords
					FROM	bus_accident ba,
							transport_staff ts
					WHERE	ba.staffId = $staffId
					AND		ba.staffId = ts.staffId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getVehicleRepairStaffDetail() is used to vehicle repair staff detail
//Author : Jaineesh
// Created on : 06.01.10
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getVehicleRepairStaffDetail($staffId) {
     
        $query = "	SELECT	COUNT(*) AS totalRecords
					FROM	bus_repair br,
							transport_staff ts
					WHERE	br.staffId = $staffId
					AND		br.staffId = ts.staffId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getVehicleBusRouteStaffDetail() is used to vehicle route staff detail
//Author : Jaineesh
// Created on : 06.01.10
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getVehicleBusRouteStaffDetail($staffId) {
     
        $query = "	SELECT	COUNT(*) AS totalRecords
					FROM	bus_route_staff brf,
							transport_staff ts
					WHERE	brf.staffId = $staffId
					AND		brf.staffId = ts.staffId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
  
}
// $History: TransportStaffManager.inc.php $
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 1/21/10    Time: 4:07p
//Updated in $/Leap/Source/Model
//Add new field medical examination date
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Model
//fixed bug in fleet management
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 12/17/09   Time: 3:41p
//Updated in $/Leap/Source/Model
//put DL image in transport staff and changes in modules
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/10/09   Time: 4:15p
//Updated in $/Leap/Source/Model
//add new fields and upload image
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/09/09   Time: 6:08p
//Updated in $/Leap/Source/Model
//change in menu item from bus master to fleet management and doing
//changes in transport staff
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/05/09    Time: 17:57
//Updated in $/Leap/Source/Model
//Fixed bugs in bus & transport staff master as reported by vimal sir
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/04/09    Time: 19:27
//Updated in $/SnS/Model
//Done enhancement for transport staff master
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 16:46
//Created in $/SnS/Model
//Created module Transport Stuff Master
?>
