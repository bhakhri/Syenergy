<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the Branch Module
//
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class BranchManager {
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
	public function addBranch() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('branch', array('branchCode','branchName','miscReceiptPrefix'), array($REQUEST_DATA['branchCode'],$REQUEST_DATA['branchName'],$REQUEST_DATA['miscReceiptPrefix']));
	}
    public function editBranch($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('branch', array('branchCode','branchName','miscReceiptPrefix'), array($REQUEST_DATA['branchCode'],$REQUEST_DATA['branchName'],$REQUEST_DATA['miscReceiptPrefix']), "branchId=$id" );  
    }    
   public function getBranch($conditions='') {
     
        $query = "SELECT branchId,branchCode,branchName,miscReceiptPrefix 
        FROM branch 
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }          
    public function getBranchList($conditions='', $limit = '', $orderBy=' branchName') {
     
     /*  $query = "SELECT branchId, branchCode, branchName FROM branch  
        $conditions                   
        ORDER BY $orderBy $limit";      */ 
        
       $query =  "SELECT br.branchId, br.branchCode,br.branchName,br.miscReceiptPrefix, (
                                SELECT 
                                COUNT(stu.studentId)    
                                FROM  student stu, class cls 
                                WHERE cls.branchId = br.branchId 
                                AND stu.classId = cls.classId
                                )  
                AS studentCount 
                 FROM branch br
                 having 1=1 $conditions                   
                 ORDER BY $orderBy $limit ";
                 
          
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    public function getTotalBranch($conditions='') {
    
      /*  $query = "SELECT COUNT(*) AS totalRecords 
        FROM branch  
        $conditions ";   */
         $query = "SELECT COUNT(*) AS totalRecords 
                   FROM (  
                            SELECT br.branchId, br.branchCode,br.branchName,br.miscReceiptPrefix, (
                                SELECT 
                                COUNT(stu.studentId) 
                                FROM  student stu, class cls 
                                WHERE cls.branchId = br.branchId 
                                AND stu.classId = cls.classId
                            )  
                 AS studentCount
                 FROM branch br
                 GROUP BY br.branchId HAVING 1=1 $conditions) as t";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
     public function deleteBranch($branchId) {
     
        $query = "DELETE 
        FROM branch 
        WHERE branchId=$branchId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    } 

    public function checkBranch($branchId) {
        $query = "SELECT 
                         COUNT(*) AS cnt 
                  FROM 
                         branch b LEFT JOIN `employee` e ON e.branchId = b.branchId 
                                  LEFT JOIN `class` c ON e.branchId = b.branchId
                  WHERE 
                         b.branchId=$branchId ";
                         
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");        
    }  
    
    public function checkClassBranch($branchId) {
      
        $query = "SELECT 
                         COUNT(*) AS cnt 
                  FROM 
                        `class` 
                  WHERE           
                         branchId=$branchId ";
                         
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");        
    }  
    
    public function checkEmployeeBranch($branchId) {
      
        $query = "SELECT 
                         COUNT(*) AS cnt 
                  FROM 
                        `employee` 
                  WHERE           
                        branchId=$branchId ";
                         
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");        
    }  
    
}
?>

<?php 

//$History: BranchManager.inc.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 10/20/09   Time: 3:09p
//Updated in $/LeapCC/Model
//checkEmployeeBranch function added
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/12/09    Time: 5:41p
//Updated in $/LeapCC/Model
//checkClassBranch condition updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/12/09    Time: 5:28p
//Updated in $/LeapCC/Model
//checkClassBranch function added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 7/03/09    Time: 2:01p
//Updated in $/LeapCC/Model
//checkBranch condition updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/02/09    Time: 2:56p
//Updated in $/LeapCC/Model
//formatting & validations, conditions updated 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/02/09    Time: 1:25p
//Updated in $/LeapCC/Model
//checkBranch function added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/25/09    Time: 12:39p
//Updated in $/LeapCC/Model
//query update getTotalBranch
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:20p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 4  *****************
//User: Arvind       Date: 6/14/08    Time: 7:19p
//Updated in $/Leap/Source/Model
//modification
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:04p
//Updated in $/Leap/Source/Model
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:20p
//Created in $/Leap/Source/Model
//New Files Added in Model Folder

?>
