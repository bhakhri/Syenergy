<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "slabs" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId    

class SlabsManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "SlabsManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "SlabsManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
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
// THIS FUNCTION IS USED FOR ADDING A slab
//
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addSlabs() {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        return SystemDatabaseManager::getInstance()->runAutoInsert('slabs', 
         array('deliveredFrom','deliveredTo','marks','instituteId','sessionId'), 
         array($REQUEST_DATA['deliveredFrom'],$REQUEST_DATA['deliveredTo'],$REQUEST_DATA['marks'],$instituteId,$sessionId) 
        );
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A slab
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editSlabs($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('slabs', 
         array('deliveredFrom','deliveredTo','marks','instituteId','sessionId'), 
         array($REQUEST_DATA['deliveredFrom'],$REQUEST_DATA['deliveredTo'],$REQUEST_DATA['marks'],$instituteId,$sessionId), "slabId=$id" );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING slabs LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getSlabs($conditions='') {
     
        $query = "SELECT slabId,deliveredFrom,deliveredTo,marks
        FROM slabs
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    


///-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A slab
//
//$cityId :cityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteSlabs($slabId) {
     
        $query = "DELETE 
        FROM slabs 
        WHERE slabId=$slabId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING slabs LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getSlabsList($conditions='', $limit = '', $orderBy=' sl.deliveredFrom') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT sl.slabId,sl.deliveredFrom,sl.deliveredTo,sl.marks
        FROM slabs sl
        WHERE sl.instituteId=$instituteId AND sessionId=$sessionId
        $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF slabs
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalSlabs($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM slabs sl
        WHERE sl.instituteId=$instituteId AND sessionId=$sessionId
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR checking existing slabs
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function checkSlabs($delFrom,$delTo,$slabId="") {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $extCondition="";
        if($slabId!=""){
            $extCondition=" AND sl.slabId !=$slabId";  //it will be used when checking for overlaping range during edit
        } 
        
        $query = "SELECT sl.slabId,sl.deliveredFrom
        FROM slabs sl
        WHERE sl.instituteId=$instituteId AND sessionId=$sessionId
        AND ( 
            ($delFrom BETWEEN sl.deliveredFrom AND sl.deliveredTo )
             OR
            ($delTo BETWEEN sl.deliveredFrom AND sl.deliveredTo)
             OR
            ($delFrom <= sl.deliveredFrom AND $delTo >= sl.deliveredTo) 
           )
        $extCondition ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
      
}
?>
<?php
// $History: SlabsManager.inc.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/12/08    Time: 11:44a
//Created in $/Leap/Source/Model
?>
