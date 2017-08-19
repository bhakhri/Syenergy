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

class BusManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BusManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BusManager" CLASS
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
    public function addBus() {
        global $REQUEST_DATA;
        $insCompany=trim($REQUEST_DATA['insCompany'])!=''? add_slashes(trim($REQUEST_DATA['insCompany'])) : NULL;
        $insDueDate=trim($REQUEST_DATA['insDueDate'])!=''? add_slashes(trim($REQUEST_DATA['insDueDate'])) : NULL;
        
        return SystemDatabaseManager::getInstance()->runAutoInsert('bus',
        array(
              'busName','busNo','yearOfManufacturing','isActive',
              'purchaseDate','modelNumber','seatingCapacity',
              'insuringCompany','insuranceDueDate','remindDueInsurance'
             ), 
        array(
               $REQUEST_DATA['busName'],strtoupper($REQUEST_DATA['busNo']),$REQUEST_DATA['manYear'],$REQUEST_DATA['isActive'],
               $REQUEST_DATA['purchaseDate'],add_slashes($REQUEST_DATA['modelNo']),add_slashes($REQUEST_DATA['seatingCapacity']),
               $insCompany,$insDueDate,$REQUEST_DATA['insReminder']
             ) 
        );
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
    public function editBus($id) {
        global $REQUEST_DATA;
        
        $insCompany=trim($REQUEST_DATA['insCompany'])!=''? add_slashes(trim($REQUEST_DATA['insCompany'])) : NULL;
        $insDueDate=trim($REQUEST_DATA['insDueDate'])!=''? add_slashes(trim($REQUEST_DATA['insDueDate'])) : NULL;
        
        return SystemDatabaseManager::getInstance()->runAutoUpdate('bus',
        array(
              'busName','busNo','yearOfManufacturing','isActive',
              'purchaseDate','modelNumber','seatingCapacity',
              'insuringCompany','insuranceDueDate','remindDueInsurance'
             ), 
        array(
               $REQUEST_DATA['busName'],strtoupper($REQUEST_DATA['busNo']),$REQUEST_DATA['manYear'],$REQUEST_DATA['isActive'],
               $REQUEST_DATA['purchaseDate'],add_slashes($REQUEST_DATA['modelNo']),add_slashes($REQUEST_DATA['seatingCapacity']),
               $insCompany,$insDueDate,$REQUEST_DATA['insReminder']
             ), 
        "busId=$id" 
        );
    }   

/*
@@ purpose: To update filename(for logo image) in 'bus' table
@@ author: Dipanjan Bhattacharjee
@@ Params: Id (Bus ID), filename (name of the file)
@@ created On: 23.06.2008
@@ returns: boolean value
*/
    public function updateLogoFilenameInBus($id, $fileName) {
        return SystemDatabaseManager::getInstance()->runAutoUpdate('bus', 
        array('busImage'), 
        array($fileName), "busId=$id" );
    }
        
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUS LIST
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getBus($conditions='') {
     
        $query = "SELECT
                          busId,busName,busNo,yearOfManufacturing,isActive,
                          purchaseDate,modelNumber,seatingCapacity,insuringCompany,insuranceDueDate,
                          remindDueInsurance,
                          IFNULL(busImage,-1) AS busImage
                    FROM bus
                 $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//**AS BUSSTOP TABLE IS INDEPENDENT NO NEED TO CHECK TO INTEGRITY CONSTRAINTS**//

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A BUSSTOP
//
//$cityId :busStopid of the Bus
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteBus($busStopid) {
     
        $query = "DELETE 
                 FROM
                      bus
                WHERE busId=$busStopid";
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
    
    public function getBusList($conditions='', $limit = '', $orderBy=' bs.busName') {
        global $sessionHandler;
        
       $query = "	SELECT 
							bs.busId,
							bs.busName,
							bs.busNo,
							IF(bs.isActive=1,'Yes','No') AS isActive,
							bs.purchaseDate,
							bs.modelNumber,
							bs.seatingCapacity,
							ic.insuringCompanyName,
							bi.insuranceDueDate,
							bi.policyNo
					FROM 
							bus bs,
							bus_insurance bi,
							insurance_company ic
					WHERE	bi.busId = bs.busId
					AND		ic.insuringCompanyId = bi.insuringCompanyId
					AND		bi.insuranceId IN (select Max(insuranceId) FROM bus_insurance GROUP BY busId ORDER BY insuranceDueDate) 
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
    public function getTotalBus($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT 
                         COUNT(*) AS totalRecords 
                  FROM 
							bus bs,
							bus_insurance bi,
							insurance_company ic
					WHERE	bi.busId = bs.busId
					AND		ic.insuringCompanyId = bi.insuringCompanyId
					AND		bi.insuranceId IN (select Max(insuranceId) FROM bus_insurance GROUP BY busId ORDER BY insuranceDueDate) 
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}
// $History: BusManager.inc.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:00
//Updated in $/LeapCC/Model
//Copied bus master enhancements from leap to leapcc
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 5/05/09    Time: 12:07
//Updated in $/Leap/Source/Model
//Corrected data population bug in bus master
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/04/09    Time: 13:26
//Updated in $/SnS/Model
//Added "InsuranceDue Report" module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/04/09    Time: 16:37
//Updated in $/SnS/Model
//Enhanced bus master module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/02/09    Time: 19:12
//Created in $/SnS/Model
//Created Bus Master Module
?>