<?php
//-------------------------------------------------------------------------------
//
//GroupTypeManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
  include_once(DA_PATH ."/SystemDatabaseManager.inc.php");
  
  class GroupTypeManager {
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
    //-------------------------------------------------------------------------------
//
//addGroupType() function is used for adding new groupType into the groupType table....
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addGroupType() {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoInsert('group_type', array('groupTypeName','groupTypeCode'), array($REQUEST_DATA['groupTypeName'],strtoupper($REQUEST_DATA['groupTypeCode'])));
    }

    //-------------------------------------------------------------------------------
//
//editGroupType() function is used for edit the existing user into the groupType table....
// $id is used as the unique identification of the existing group type data
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function editGroupType($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('group_type', array('groupTypeName','groupTypeCode'), array($REQUEST_DATA['groupTypeName'],strtoupper($REQUEST_DATA['groupTypeCode'])) , "groupTypeId=$id" );
    }    
    
     //-------------------------------------------------------------------------------
//
//deleteGroupType() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function deleteGroupType($groupTypeId) {
     
        $query = "DELETE 
        FROM group_type 
        WHERE groupTypeId=$groupTypeId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
   
     //-------------------------------------------------------------------------------
//
//getGroupType() function is used to list the records of Group type table
// $condtions - used to check condition while selecting the records
// $limit - used to check the limit of showing records in list
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getGroupType($conditions='') {
     
        $query = "SELECT groupTypeId, groupTypeName, groupTypeCode 
        FROM group_type $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
        //-------------------------------------------------------------------------------
//
//getGroupTypeList() function is used to list the records of Group Type table
// $condtions - used to check condition while selecting the records
// $limit - used to check the limit of showing records in list
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getGroupTypeList($conditions='', $limit = '', $orderBy='groupTypeName') {
     
        $query = "SELECT groupTypeId, groupTypeName, groupTypeCode
        FROM group_type $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
       //-------------------------------------------------------------------------------
//
//getTotalGroupType() function returns the total no. of records
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
    public function getTotalGroupType($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM group_type $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
       
}
?>
<?php 
// $History: GroupTypeManager.inc.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:32p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/03/08    Time: 8:01p
//Updated in $/Leap/Source/Model
//modified in table fields
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/01/08    Time: 11:46a
//Updated in $/Leap/Source/Model
//modified in delete comment
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:45p
//Updated in $/Leap/Source/Model
?>
