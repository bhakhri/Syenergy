<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR BUS FEES
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class BusFeeManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BusFeeManager" 
//
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct(){
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BusFeeManager" 
//
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
    

    //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET TOTAL NUMBER OF Bus Routes
//
// Author :Nishu Bindal
// Created on : (17.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
        public function getTotalBusStopHeads($conditions='') {
       
       		$query = "SELECT
                      		COUNT(DISTINCT bs.busStopId) AS totalRecords
                  	FROM 
                     		 `bus_stop_new` bs,`bus_stop_city` bsc, `bus_route_new` br, `bus_route_stop_mapping` brsm 
                     	WHERE	bs.busStopCityId = bsc.busStopCityId
                     	AND	bs.busStopId = brsm.busStopId
                     	AND	br.busRouteId = brsm.busRouteId
                  	$conditions";
          	
       		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    	}
   //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET City List IN Route WITH THERE FEES
//
// Author :Nishu Bindal
// Created on : (17.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
 	public function getBusStopHeadsList($conditions='',$classId,$limit,$orderBy='routeCode') {
       
       		$query = "SELECT
                      		DISTINCT br.routeCode,br.routeName,br.busRouteId,bsc.busStopCityId,bsc.cityName,
                      			(select bf.amount FROM bus_fees bf WHERE bf.busRouteId = brsm.busRouteId AND bf.busStopCityId = bs.busStopCityId  AND bf.classId = '$classId') AS amount
                  	FROM 
                     		 `bus_stop_city` bsc, `bus_route_new` br,`bus_stop_new` bs, `bus_route_stop_mapping` brsm 
                     	WHERE	bs.busStopCityId = bsc.busStopCityId
                     	AND	bs.busStopId = brsm.busStopId
                     	AND	br.busRouteId = brsm.busRouteId                  	
                  	$conditions
                  	ORDER BY $orderBy
                  	 $limit";
                  	
       		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    	}
   //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT FEE OF BUS ROUTE CITY
// Author :Nishu Bindal
// Created on : (17.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    	public function insertIntoFeeValues($values){
    		$query ="INSERT INTO `bus_fees` (busFeesId,busRouteId,busStopCityId,classId,amount)
    					VALUES $values";
    					
    		 return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    	}
   //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO DELETE Bus FEES
// Author :Nishu Bindal
// Created on : (17.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    	
    	public function deleteFeeValues($conditions =''){
    		$query ="DELETE from `bus_fees` $conditions"; 
    		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    	}
   //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH CLASSES
// Author :Nishu Bindal
// Created on : (17.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    	public function fetchClases($batchId,$studyPeriodId){
    		$query ="SELECT DISTINCT classId, className FROM `class` WHERE 	batchId = '$batchId' AND studyPeriodId = '$studyPeriodId' ORDER BY className ASC";
    		
    		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    	}
    	
    	public function fetchStudyPeriod($batchId){
    		$query ="SELECT			DISTINCT sp.studyPeriodId, sp.periodName  
    					FROM	`class` c, `study_period` sp 
    					WHERE	c.studyPeriodId = sp.studyPeriodId 	
    					AND	c.batchId = '$batchId'
    					ORDER BY sp.periodName ASC";
    		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    	}
    	
    	public function fetchBusStopCity($routeIdList){
    		$query = "SELECT 	DISTINCT bsc.busStopCityId,bsc.cityName 
    				FROM 	`bus_stop_city` bsc, `bus_stop_new` bsn, `bus_route_stop_mapping` brsm
    				WHERE	bsc.busStopCityId = bsn.busStopCityId
    				AND	brsm.busStopId = bsn.busStopId
    				AND	brsm.busRouteId IN ($routeIdList)";
    		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");		
    	}
    	 public function fetchAllBatches($condition){
    	 global $sessionHandler;
    	$query ="SELECT		DISTINCT b.batchId,b.batchName
    			FROM	`batch` b , `class` c
    			WHERE	c.batchId = b.batchId
    			AND	b.instituteId = c.instituteId
    			AND	b.instituteId = '".$sessionHandler->getSessionVariable('InstituteId')."'
    			$condition
    			ORDER BY b.batchName ASC
    	";
    	
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
         public function fetchAllBranches($degreeId){
      	 global $sessionHandler;
    	$query ="SELECT
    			DISTINCT	
    			b.branchId,b.branchCode 
    		FROM	`branch` b , `class` c
    		WHERE	c.branchId = b.branchId
    		AND	c.instituteId = '".$sessionHandler->getSessionVariable('InstituteId')."'
    		AND	c.degreeId = $degreeId
    		ORDER BY b.branchCode ASC
    		";
    		
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
      public function fetchAllClases($condition = ''){
    	$query ="SELECT		DISTINCT c.classId,c.className
    			FROM	`class` c
    				$condition
    		ORDER BY c.className Asc";
    		
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    	
    }
    //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Check if fee is generated for this class
// Author :Nishu Bindal
// Created on : (17.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    	public function checkForFeeGeneration($classId='0'){
    	   
           if($classId=='') {
             $classId='0';  
           }
            
           $query = "SELECT	
                            count(feeReceiptId) AS cnt 
    				  FROM	
                            `fee_receipt_master`
    				   WHERE	
                             feeClassId IN ($classId)
    				         AND status = 1";
    		
    		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    	}



    public function getAllSearchValue($fieldName='', $condition='') {
       
        $query ="SELECT  
                         $fieldName
                 FROM    
                        `class` c, degree deg, branch br, batch bat, institute ii
                 WHERE
                        c.instituteId = ii.instituteId AND
                        c.degreeId = deg.degreeId AND
                        c.batchId = bat.batchId AND
                        c.branchId = br.branchId               
                 $condition
                 ORDER BY 
                        ii.instituteCode, deg.degreeCode, br.branchCode, bat.batchName, c.className ";
            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}

// $History: StudentConcessionManager.inc.php $
?>
