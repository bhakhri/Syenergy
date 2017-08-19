<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "block" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class BlockManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BlockManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BlockManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
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
// THIS FUNCTION IS USED FOR ADDING A Block
//
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addBlock() {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoInsert('block', array('blockName','abbreviation','buildingId'), array(strtoupper(trim($REQUEST_DATA['blockName'])),trim($REQUEST_DATA['abbreviation']),$REQUEST_DATA['buildingId']) );
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Block
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editBlock($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('block', array('blockName','abbreviation','buildingId'), array(strtoupper(trim($REQUEST_DATA['blockName'])),trim($REQUEST_DATA['abbreviation']),$REQUEST_DATA['buildingId']), "blockId=$id" );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Block LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getBlock($conditions='') {
     
        $query = "SELECT blockId,blockName,abbreviation,buildingId 
        FROM block
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS blockID EXISTS IN Room TABLE OR NOT(DELETE CHECK)
//
//$blockId :blockId of the Block
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInRoom($blockId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM room 
        WHERE blockId=$blockId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Bloxk
//
//$blockId :blockId of the Block 
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteBlock($blockId) {
     
        $query = "DELETE 
        FROM block
        WHERE blockId=$blockId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Block LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getBlockList($conditions='', $limit = '', $orderBy=' bl.blockName') {
     
        $query = "SELECT bl.blockId, bl.blockName, bl.abbreviation, bi.buildingName
        FROM block bl,building bi
        WHERE bl.buildingId=bi.buildingId $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Blocks
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalBlock($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM block bl,building bi
        WHERE bl.buildingId=bi.buildingId $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}
?>
<?php
// $History: BlockManager.inc.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 5/08/09    Time: 12:39
//Updated in $/LeapCC/Model
//Done bug fixing.
//bug ids---
//0000887 to 0000895,
//0000906 to 0000909
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:19p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/11/08    Time: 12:43p
//Updated in $/Leap/Source/Model
//Created "Block" Module
?>
