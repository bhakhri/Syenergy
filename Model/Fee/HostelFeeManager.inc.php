<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "student and teacher_comment" TABLE
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class HostelFeeManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "StudentConcessionManager" CLASS
//
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct(){
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "StudentConcessionManager" CLASS
//
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
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
// THIS FUNCTION IS USED TO GET TOTAL NUMBER OF ROOMS IN HOSTEL
//
// Author :Nishu Bindal
// Created on : (17.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
        public function getHostelTotalRooms($conditions='') {
       
       		global $sessionHandler;

       		$query = "SELECT
                      		COUNT(DISTINCT hr.hostelRoomId) AS totalRecords
                  	FROM 
                     		  hostel_room_type hrt , 
                     		`hostel_room` hr
                     	WHERE	hr.hostelRoomTypeId = hrt.hostelRoomTypeId
                  	$conditions";
              
       		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    	}
   //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET ROOMS List IN HOSTEL WITH THERE FEES
//
// Author :Nishu Bindal
// Created on : (17.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
 	public function getHostelRoomList($conditions='',$classId,$limit,$orderBy='hostelName') {
       
       		

       		$query = "SELECT
                      		DISTINCT hrt.roomType,hr.hostelRoomTypeId,h.hostelName,hf.amount,h.hostelId
                  	FROM 
                     		  hostel_room_type hrt , hostel h, 
                     		`hostel_room` hr LEFT JOIN `hostel_fees` hf ON hf.roomTypeId = hr.hostelRoomTypeId AND hf.hostelId = hr.hostelId AND hf.classId = '$classId'
                     	WHERE	hr.hostelRoomTypeId = hrt.hostelRoomTypeId
                     	AND	h.hostelId = hr.hostelId
                  	
                  	$conditions
                  	ORDER BY $orderBy
                  	 $limit";
                  	
       		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    	}
   //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT FEE OF HOSTEL ROOMS
// Author :Nishu Bindal
// Created on : (17.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    	public function insertIntoFeeValues($values){
    		$query ="INSERT INTO `hostel_fees` (hostelFeeId,hostelId,roomTypeId,classId,amount)
    					VALUES $values";		
    		 return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    	}
   //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO DELETE HOSTEL FEES
// Author :Nishu Bindal
// Created on : (17.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    	
    	public function deleteFeeValues($conditions =''){
    		$query ="DELETE from `hostel_fees` $conditions"; 
    		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    	}
   //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Room Types
// Author :Nishu Bindal
// Created on : (17.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    	public function fetchHostelRoomTypes($hostelIdList){
    		$query ="	SELECT 
                         		DISTINCT hrt.hostelRoomTypeId, hrt.roomType
				  FROM 
				         hostel_room_type hrt, hostel_room_type_detail hrtd, hostel h
				  WHERE 
				        h.hostelId = hrtd.hostelId
				        AND hrtd.hostelRoomTypeId = hrt.hostelRoomTypeId
	    				 AND h.hostelId IN($hostelIdList)
	    				  ORDER BY roomType ASC";
	    				
    		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    	}
    	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Check if fee is generated for this class
// Author :Nishu Bindal
// Created on : (17.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    	public function checkForFeeGeneration($classId){
    		$query = "SELECT	
                            count(feeReceiptId) AS cnt 
    				   FROM	
                            `fee_receipt_master`
    				   WHERE	
                            feeClassId IN ($classId)
    				        AND	status = 1 ";
    		
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
