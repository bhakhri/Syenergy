<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "busroute" TABLE
//
//
// Author : Nishu Bindal
// Created on : (19.April.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class BusRouteManagerNew {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BusRouteManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BusRouteManager" CLASS
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
// THIS FUNCTION IS USED FOR ADDING A BUSROUTE
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addBusRoute($insertValue) {
		global $REQUEST_DATA;
        global $sessionHandler;
     
        $query = "INSERT INTO `bus_route_new`
                  (routeName,routeCode)
                  VALUES
                  $insertValue";
                  
        $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
    
    public function addBus($busRouteId,$busId){
        global $REQUEST_DATA;
        global $sessionHandler;
     
        $query = "INSERT INTO `bus_route_mapping_new`
                  (busRouteId,busId)
                  VALUES
                  ('$busRouteId','$busId')";
              
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
    }

    public function deleteBusMapping($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
     
        $query = "DELETE FROM `bus_route_mapping_new` WHERE busRouteId = '$id'";
                   
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
    }
          
    //-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A BUSROUTE
//$busRouteId :busRouteId of the BusStop
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteBusRoute($busRouteId) {
     
        $query = "DELETE 
        FROM bus_route_new 
        WHERE busRouteId=$busRouteId";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
    }      


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A BUSROUTE
//$id:busRouteId
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------        
    public function editBusRoute($routeName,$routeCode, $id) {
        global $REQUEST_DATA;
        global $sessionHandler;
     
        $query = "UPDATE 
                        `bus_route_new`
                  SET
                        routeName = '$routeName',
                        routeCode = '$routeCode'
                  WHERE
                        busRouteId=$id";
                   
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUSROUTE LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getBusRoute($conditions='') {
     
  $query = "	SELECT
                        br.busRouteId,
			br.routeName,
			br.routeCode,
			brm.busId
		FROM	
                        bus_route_new br LEFT JOIN bus_route_mapping_new brm ON br.busRouteId = brm.busRouteId  
					$conditions";            
                        
              
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      
    }  
    

    //**AS BUSROUTE TABLE IS INDEPENDENT NO NEED TO CHECK FOR INTEGRITY CONSTRAINTS**//



//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUSROUTE,STUDENTCOUNT,BUSSTOP LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
//
//--------------------------------------------------------       
    
    public function getBusRouteList($conditions='', $limit = '', $orderBy=' br.routeCode') {                            
       $query = "SELECT
                        br.busRouteId, br.routeName, 
                        br.routeCode,  br.busNo
                 FROM     
                       (SELECT
                                br.busRouteId,
                                br.routeName, br.routeCode, 
                                IF(brm.busId IS NULL,'".NOT_APPLICABLE_STRING."', busNo ) AS busNo
                         FROM
                                bus_route_new br
                               
                                LEFT JOIN  bus_route_mapping_new brm ON br.busRouteId = brm.busRouteId 
                                LEFT JOIN  bus b ON b.busId = brm.busId 
                         $conditions                                
                         GROUP BY   
                                br.busRouteId) AS  br
                 ORDER BY 
                          $orderBy $limit";               
                                    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF BUSROUTES
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalBusRoute($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                       (SELECT
                                br.busRouteId, 
                                
                                br.routeName, br.routeCode,
                                IF(brm.busId IS NULL,'".NOT_APPLICABLE_STRING."',GROUP_CONCAT(DISTINCT busNo  SEPARATOR ', ')) AS busNo
                         FROM
                                bus_route_new br
                                LEFT JOIN  bus_route_mapping_new brm ON br.busRouteId = brm.busRouteId 
                                LEFT JOIN  bus b ON b.busId = brm.busId   
                         $conditions                                
                         GROUP BY   
                                br.busRouteId) AS  br";
                             
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF BUS ROUTE MAPPING
//$conditions :db clauses
// Author :NISHU BINDAL
// Created on : (5.April.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------  
    public function checkBusRouteMapping($condition =''){
    	$query = " SELECT COUNT(busRouteStopMappingId) AS cnt FROM `bus_route_stop_mapping` $condition";
    	 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO CHECK IF BUS IS MAPPED IN FEE RECEIPT MASTER TABLE
//$conditions :db clauses
// Author :NISHU BINDAL
// Created on : (5.April.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------  
   public function checkBusFee($condition = ''){
   	$query ="SELECT COUNT(feeReceiptId) AS cnt FROM `fee_receipt_master` $condition";
   	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }



  
}

?>
