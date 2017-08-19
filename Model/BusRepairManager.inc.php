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

class BusRepairManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BusRepairRepairManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BusRepairRepairManager" CLASS
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
    public function addBusRepair() {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoInsert('bus_repair',
        array('busId','stuffId','serviceFor','dated','cost','comments','workshopName','billNumber'), 
        array(
               $REQUEST_DATA['busId'],$REQUEST_DATA['stuffId'],
               trim(add_slashes($REQUEST_DATA['serviceFor'])),$REQUEST_DATA['dated'],
               trim(add_slashes($REQUEST_DATA['cost'])),
               trim(add_slashes($REQUEST_DATA['comments'])),
               trim(add_slashes($REQUEST_DATA['workShop'])),
               trim(add_slashes($REQUEST_DATA['billNumber']))
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
    public function editBusRepair($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('bus_repair',
        array('busId','stuffId','serviceFor','dated','cost','comments','workshopName','billNumber'), 
        array(
               $REQUEST_DATA['busId'],$REQUEST_DATA['stuffId'],
               trim(add_slashes($REQUEST_DATA['serviceFor'])),$REQUEST_DATA['dated'],
               trim(add_slashes($REQUEST_DATA['cost'])),
               trim(add_slashes($REQUEST_DATA['comments'])),
               trim(add_slashes($REQUEST_DATA['workShop'])),
               trim(add_slashes($REQUEST_DATA['billNumber']))
             ), 
        "repairId=$id" 
        );
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR Adding bus repair action(Engine Oil Change,Gear Box Oil Change etc)
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (09.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------     
public function addActionRepaired($repairId,$str){
    
    $query1=" DELETE FROM bus_repair_detail WHERE repairId=".$repairId;
    $ret1=SystemDatabaseManager::getInstance()->executeDelete($query1,"Query: $query");
    
    if($ret1===true){
        $query2="INSERT INTO bus_repair_detail VALUES ".$str;
        return SystemDatabaseManager::getInstance()->executeUpdate($query2,"Query: $query");
    }
    else{
        return false;
    }
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
    public function getBusRepair($conditions='') {
     
        $query = "SELECT
                          repairId,busId,stuffId,serviceFor,dated,cost,comments,workshopName,billNumber
                    FROM bus_repair
                 $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
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
    public function getBusRepairedAction($conditions='') {
     
        $query = "SELECT
                          actionId,dueDate
                    FROM 
                          bus_repair_detail
                 $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }      
    
//**AS BUSSTOP TABLE IS INDEPENDENT NO NEED TO CHECK TO INTEGRITY CONSTRAINTS**//

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A BUSSTOP
//
//$cityId :busStopid of the BusRepair
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteBusRepair($id) {
     
        $query1 = "DELETE 
                  FROM
                      bus_repair_detail
                  WHERE repairId=$id";
        SystemDatabaseManager::getInstance()->executeDelete($query1,"Query: $query");
        
        $query = "DELETE 
                  FROM
                      bus_repair
                  WHERE repairId=$id";
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
    
    public function getBusRepairList($conditions='', $limit = '', $orderBy=' bs.busName') {
        global $sessionHandler;
        
        $query = "SELECT 
                        br.repairId,br.dated,br.serviceFor,br.cost,br.billNumber,br.workshopName,
                        trs.name,
                        bs.busName,bs.busNo
                  FROM 
                        bus_repair br,transport_stuff trs,bus bs
                  WHERE
                        br.stuffId=trs.stuffId
                        AND br.busId=bs.busId
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
    public function getTotalBusRepair($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT 
                         COUNT(*) AS totalRecords 
                  FROM 
                        bus_repair br,transport_stuff trs,bus bs
                  WHERE
                        br.stuffId=trs.stuffId
                        AND br.busId=bs.busId
                        AND bs.isActive=1      
                        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
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
    
   public function getRepairCostData($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT 
                        br.busId,SUM(br.cost) AS totalCost,
                        bs.busName ,bs.busNo
                  FROM 
                        bus_repair br,bus bs
                  WHERE
                        br.busId=bs.busId
                        $conditions
                  GROUP BY br.busId      
                ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }      
   
  
}
// $History: BusRepairManager.inc.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:11
//Updated in $/LeapCC/Model
//Replicated bus repair module's enhancements from leap to leapcc
//
//*****************  Version 4  *****************
//User: Administrator Date: 14/05/09   Time: 10:35
//Updated in $/Leap/Source/Model
//Done bug fixing.
//Bug Ids---1001 to 1005
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/09   Time: 16:12
//Updated in $/Leap/Source/Model
//Corrected "Delete" query
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Model
//Updated fleet mgmt file in Leap 
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/04/09    Time: 15:15
//Updated in $/SnS/Model
//Enhanced bus repair module by adding action (Engine Oil Change,Gear Box
//Oil Change etc) and their due dates
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/04/09    Time: 11:16
//Updated in $/SnS/Model
//Added "Bus Repair Cost Report" module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/04/09    Time: 11:25
//Updated in $/SnS/Model
//Enhanced bus repair module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 12:55
//Created in $/SnS/Model
//Created Bus Repair Module
?>
