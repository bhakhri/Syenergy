<?php 
global $FE;
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class GuidersManager {
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

    public function getGuidersEntry($entry) {
        global $sessionHandler;
        $userId=$sessionHandler->getSessionVariable('UserId'); 
        
        $query="INSERT INTO guiders_info (moduleName,userId,guiderBit) values ('$entry','$userId','1')";
        $returnStatus = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        if($returnStatus===true) {
            $query="SELECT id FROM menulookup_count WHERE userId = '$userId'";  
            $returnStatus = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
            if(is_array($returnStatus) && count($returnStatus)>0 ) {   
              $query="UPDATE menulookup_count SET total=total+1  WHERE userId = '$userId' ";  
            }  
            else {
              $query="INSERT INTO menulookup_count(userId,total) values($userId,1)";    
            }
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");        
        }
        return false;
    }

    public function checkGuidersEntry($entry) {
        global $sessionHandler;
        
        $userId=$sessionHandler->getSessionVariable('UserId'); 
        $query="SELECT * FROM guiders_info WHERE moduleName='$entry' and userId='$userId'";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function updateMenuLookupCount($count) {
        global $sessionHandler;
        $userId=$sessionHandler->getSessionVariable('UserId'); 
        
        $query="SELECT id FROM menulookup_count WHERE userId = '$userId'";  
        $returnStatus = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
        if(is_array($returnStatus) && count($returnStatus)>0 ) {   
           $query="UPDATE menulookup_count SET total=total+1  WHERE userId = '$userId' ";  
        }  
        else {
           $query="INSERT INTO menulookup_count(userId,total) values($userId,1)";    
        }
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query"); 
    }
}
?>
