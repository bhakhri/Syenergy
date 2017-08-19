<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the Fee Head Module
//
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
       
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');  
         
        return SystemDatabaseManager::getInstance()->runAutoInsert('fee_head', 
            array('headName','headAbbr','instituteId','isRefundable','isVariable','sortingOrder','isConsessionable'), 
            array($REQUEST_DATA['headName'],$REQUEST_DATA['headAbbr'],$instituteId,
                  $REQUEST_DATA['isRefundable'],$REQUEST_DATA['isVariable'],$REQUEST_DATA['sortOrder'],$REQUEST_DATA['isConsessionable']));
    }
   
    public function editFeeHead($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');  
        
        return SystemDatabaseManager::getInstance()->runAutoUpdate('fee_head', 
        array('headName','headAbbr','instituteId','isRefundable','isVariable','sortingOrder','isConsessionable'), 
        array($REQUEST_DATA['headName'],$REQUEST_DATA['headAbbr'],$instituteId,$REQUEST_DATA['isRefundable'],
              $REQUEST_DATA['isVariable'],$REQUEST_DATA['sortOrder'],$REQUEST_DATA['isConsessionable']), "feeHeadId=$id" );  
    }   
    
     
    public function getFeeHead($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT  
                        c.feeHeadId, c.headName, c.headAbbr, c.sortingOrder, c.isRefundable, c.isVariable, c.isConsessionable   
                  FROM 
                        fee_head c 
                  WHERE 
                        c.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
                  $conditions";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    //Gets the Fee Head table fields
    public function getFeeHeadName(){
        global $sessionHandler;
        
        $query="SELECT 
                        feeHeadId, headName, headAbbr, sortingOrder, isRefundable, isVariable, isConsessionable
                FROM 
                        `fee_head` 
                WHERE 
                        instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
                ORDER BY headName ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
   
    public function getFeeHeadList($conditions='', $limit = '', $orderBy=' headName') {
        global $sessionHandler;
        //  IF(p.headName IS NULL,'".NOT_APPLICABLE_STRING."', p.headName) AS parentHead, 
        //  c.headAbbr, c.isConsessionable, c.transportHead, c.hostelHead, c.miscHead,
        $query = "SELECT 
                        c.feeHeadId, c.headName, c.headAbbr, c.sortingOrder, c.isRefundable, c.isVariable , c.isConsessionable
                  FROM 
                        `fee_head` c
                  WHERE 
                        c.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                  $conditions
                  ORDER BY 
                  $orderBy $limit";
    
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
      
    public function getTotalFeeHead($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                     (SELECT 
                            c.feeHeadId, c.headName, c.headAbbr, c.sortingOrder, c.isRefundable, c.isVariable , c.isConsessionable
                      FROM 
                            `fee_head` c
                      WHERE 
                            c.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                      $conditions) AS t";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    // checks dependency constraint
    public function getFeeHeadValueCheck($feeHeadId='') {
        
        global $sessionHandler;
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        fee_head_values c 
                  WHERE 
                        c.feeHeadId = '$feeHeadId' ";
               
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
        
        $query = "DELETE FROM fee_head WHERE 
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

//$History: FeeHeadManager.inc.php $
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Model
//updated with all the fees enhancements
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/21/09    Time: 12:01p
//Updated in $/LeapCC/Model
//checkChildCount function added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/30/09    Time: 4:08p
//Updated in $/LeapCC/Model
//parent checks updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/30/09    Time: 10:23a
//Updated in $/LeapCC/Model
//validation updated (edit/delete relation checks updated)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/22/09    Time: 12:34p
//Updated in $/LeapCC/Model
//alignment & drop down value updated (parent head name) search condition
//udpated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 12  *****************
//User: Arvind       Date: 8/29/08    Time: 4:54p
//Updated in $/Leap/Source/Model
//modified
//
//*****************  Version 11  *****************
//User: Arvind       Date: 8/05/08    Time: 6:05p
//Updated in $/Leap/Source/Model
//added a parameter in getfeeheadname()
//
//*****************  Version 10  *****************
//User: Arvind       Date: 7/29/08    Time: 4:39p
//Updated in $/Leap/Source/Model
//added duplicate check for feehead
//
//*****************  Version 9  *****************
//User: Arvind       Date: 7/29/08    Time: 3:50p
//Updated in $/Leap/Source/Model
//modified the query of getFeeHeadList()
//
//*****************  Version 8  *****************
//User: Arvind       Date: 7/15/08    Time: 6:52p
//Updated in $/Leap/Source/Model
//added a new function  getFeeHeadName() from Dropdown PArent  HEad
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/15/08    Time: 6:23p
//Updated in $/Leap/Source/Model
//modifed the queries
//
//*****************  Version 6  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:30p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/11/08    Time: 4:10p
//Updated in $/Leap/Source/Model
//added a new function for Dependency check of HEadID and ParentHead
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/11/08    Time: 3:58p
//Updated in $/Leap/Source/Model
//added InstituteId from session variable
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/04/08    Time: 3:26p
//Updated in $/Leap/Source/Model
//added $sessionHandler->getSessionVariable['InstituteId'] in every file
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/03/08    Time: 7:32p
//Updated in $/Leap/Source/Model
//modified table name 
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:21a
//Created in $/Leap/Source/Model
//Added three files for three new modules "feehead" , "fee cyclefine"
//,"feefeefundallocation" module"

?>