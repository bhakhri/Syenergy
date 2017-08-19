<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the Fee Head Module
// Author :Nishu Bindal
// Created on : 2-feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class FeeHeadManager {
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
     public function addFeeHead() {
        global $REQUEST_DATA;
        global $sessionHandler;
   
        $headName = trim(addslashes($REQUEST_DATA['headName']));
        $headAbbr = trim(addslashes($REQUEST_DATA['headAbbr']));
        $isSpecial = $REQUEST_DATA['isSpecial'];
        $isConsessionable = $REQUEST_DATA['isConsessionable'];
        $sortOrder = $REQUEST_DATA['sortOrder'];
        $isRefundable = $REQUEST_DATA['isRefundable'];
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');  
        $sessionId = $sessionHandler->getSessionVariable('SessionId'); 
       
         
        $query="INSERT INTO `fee_head_new` (feeHeadId,headName,headAbbr,isConsessionable,isRefundable,isSpecial,sortingOrder,instituteId,sessionId)
         				VALUES('','$headName','$headAbbr',' $isConsessionable','$isRefundable','$isSpecial','$sortOrder','$instituteId','$sessionId')";
    
        return SystemDatabaseManager::getInstance()->executeUpdate($query);  				
    }
   
    public function editFeeHead($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $headName = trim(addslashes($REQUEST_DATA['headName']));
        $headAbbr = trim(addslashes($REQUEST_DATA['headAbbr']));
        $isSpecial = $REQUEST_DATA['isSpecial'];
        $isConsessionable = $REQUEST_DATA['isConsessionable'];
        $sortOrder = $REQUEST_DATA['sortOrder'];
        $isRefundable = $REQUEST_DATA['isRefundable'];
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');  
        $sessionId = $sessionHandler->getSessionVariable('SessionId'); 
        
        $query ="UPDATE `fee_head_new` SET headName = '$headName',
        				headAbbr = '$headAbbr',
        				isConsessionable = '$isConsessionable',
        				isRefundable = '$isRefundable',
        				isSpecial = '$isSpecial',
        				sortingOrder = '$sortOrder',
        				instituteId = '$instituteId',
        				sessionId = '$sessionId'
        		WHERE feeHeadId = '$id'";
        	
       return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }   
    
     
    public function getFeeHead($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT  
                        feeHeadId, headName, headAbbr, sortingOrder, isRefundable,  isConsessionable,isSpecial
                  FROM 
                        fee_head_new  
                  WHERE 
                        instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                       AND  sessionId = '".$sessionId = $sessionHandler->getSessionVariable('SessionId')."' 
                  $conditions";
                
      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    //Gets the Fee Head table fields
    public function getFeeHeadName(){
        global $sessionHandler;
        
        $query="SELECT 
                        feeHeadId, headName, headAbbr, sortingOrder, isRefundable, isVariable, isConsessionable
                FROM 
                        `fee_head_new`
                WHERE 
                        instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
                ORDER BY headName ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
   
    public function getFeeHeadList($conditions='', $limit = '', $orderBy=' headName') {
        global $sessionHandler;
    
	$query = "SELECT 
			feeHeadId, headName, headAbbr, sortingOrder, isRefundable, isSpecial , isConsessionable
		FROM 
			`fee_head_new` 
		WHERE 
			instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
		AND	sessionId = '".$sessionHandler->getSessionVariable('SessionId')."'
		$conditions
		ORDER BY 
		$orderBy $limit";
  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
     public function getTotalFeeHead($conditions='') {
        global $sessionHandler;
        
	$query = "SELECT 
				COUNT(feeHeadId) AS totalRecords 
			FROM  
				`fee_head_new` 
			WHERE 
				instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
			AND	sessionId = '".$sessionHandler->getSessionVariable('SessionId')."'
			$conditions";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    
   // checks dependency constraint
  public function checkInHead($feeHeadId) {
        global $sessionHandler;
        
        $query = "SELECT 
                        COUNT(*) AS found 
                  FROM
                        fee_head 
                  WHERE 
                        instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
      
   
    // checks dependency constraint
    public function getFeeHeadValueCheck($feeHeadId='') {
        
        global $sessionHandler;
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        fee_head_values_new c 
                  WHERE 
                        c.feeHeadId = '$feeHeadId' ";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    // checks dependency constraint
    public function checkHeadInFee($feeHeadId='') {
        
        global $sessionHandler;
        
        $query = "SELECT 
                        COUNT(fri.feeReceiptInstrumentId) AS totalRecords 
                  FROM 
                        `fee_receipt_instrument` fri, `fee_receipt_master` frm
                  WHERE   fri.feeReceiptId = frm.feeReceiptId
                  AND	  fri.classId = frm.feeClassId
                  AND	  fri.studentId = frm.studentId
                  AND	  frm.status = 1
                  AND     fri.feeHeadId = '$feeHeadId' ";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    // checks dependency constraint
    public function getFeeCycleFinesCheck($feeHeadId='') {
        
        global $sessionHandler;
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        fee_cycle_fines c 
                  WHERE 
                        c.feeHeadId = '$feeHeadId' ";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    // deletes the feeHead
     public function deleteFeeHead($feeHeadId='') {
        
        global $sessionHandler;
        
        $query = "DELETE FROM fee_head_new WHERE 
                  feeHeadId='$feeHeadId' AND
                  instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'";
                        
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }   
    
    // checks dependency constraint
    public function getParentHeadId($condition=' parentHeadId') {
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $query = "SELECT 
                        headName 
                  FROM 
                        fee_head  
                  WHERE 
                        instituteId ='".$sessionHandler->getSessionVariable('InstituteId')."' AND 
                        feeHeadId=$condition ";
        
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    // checks dependency constraint
    public function checkSelfParent($groupID='', $parentGroupID='') {
        
        global $sessionHandler;
        
        $query = "SELECT 
                        COUNT(*) AS cnt 
                  FROM 
                        `fee_head` 
                  WHERE 
                        instituteId ='".$sessionHandler->getSessionVariable('InstituteId')."' AND 
                        feeHeadId = '$parentGroupID' AND parentHeadId = '$groupID' ";
                       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    // checks dependency constraint
    public function getParent($parentHeadId='') {
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $query = "SELECT 
                        COUNT(*) AS cnt
                  FROM 
                        fee_head 
                  WHERE 
                        instituteId ='".$sessionHandler->getSessionVariable('InstituteId')."' AND 
                        parentHeadId = '$parentHeadId' ";
                        
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

    function checkChildCount($feeHeadId) {
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        count(*) as cnt 
                  FROM 
                        fee_head 
                  WHERE parentHeadId = $feeHeadId AND instituteId = $instituteId";
        
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
}


?>
