<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class BirthdayGreetingManager {
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
public function getUserBirthday() 
{
				global $sessionHandler;
				
	$userName = $sessionHandler->getSessionVariable('UserId');



        $query = "  SELECT 
                            DATE_FORMAT(dateOfBirth,'%d %b') as `newDOB`, DATE_FORMAT(NOW(),'%d %b') as `today`,
                            DATE_FORMAT(dateOfMarriage,'%d %b') as `newDOA`
                    FROM 
                            `employee` e 
                   	WHERE   
                            e.userId='".add_slashes($userName)."' "; 
                            
	  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    	

//Get Student Birthday
public function getStudentBirthday() {
		
		global $sessionHandler;
				
		$userName = $sessionHandler->getSessionVariable('UserId');

		$query = "  SELECT DATE_FORMAT(dateOfBirth,'%d %b') as `newDOB`, DATE_FORMAT(NOW(),'%d %b') as `today`
        
                    FROM `student` e 

                   	WHERE   e.userId='".add_slashes($userName)."' 
	 "; 
	  //echo $query;exit;
	  	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    	
	
    //Post Birthday detail through Ajax
    public function postBirthdayDetail($wishFormat='') {
		
		global $sessionHandler;
	
		$userId = $sessionHandler->getSessionVariable('UserId');
		$cDate = date('Y-m-d');     		
				
        $query = "INSERT INTO user_wishes 
                  (userId,wishFormat,lastWishDate,wishBit) 
                  VALUES
                  ('$userId', '$wishFormat', '$cDate', 1)";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");      
    }    

    public function updateBirthdayDetail($id='') {
		
		global $sessionHandler;
	    $userId = $sessionHandler->getSessionVariable('UserId');
        $cDate = date('Y-m-d'); 
        
        $query = "UPDATE 
                       user_wishes 
                   SET
                       wishBit=wishBit+1,
                       lastWishDate = '$cDate'
                   WHERE      
                       userWishId = '$id'";
          
         return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");                           
    }    	

//Check for the Birthday
public function checkBirthday($wishFormat='B') {
			 
		global $sessionHandler;
		 
		$userId = $sessionHandler->getSessionVariable('UserId');
			
		$query = "SELECT 
                        userWishId, lastWishDate 
                  FROM
                        user_wishes 
                  WHERE 
                        userId='".add_slashes($userId)."' AND wishFormat='$wishFormat'";
			
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");				
}


// check last wish date

public function checkLastWishBit($wishFormat) {
			 
		global $sessionHandler;
		$date = date('Y-m-d');
		$year = date('Y');
		 
		$userId = $sessionHandler->getSessionVariable('UserId');
			
		
                $query="SELECT wishBit 
                                 FROM
                                    user_wishes
                                 WHERE
                                    userId='".add_slashes($userId)."' and
                                    wishFormat ='$wishFormat'";
			
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");				
}

public function checkLastWishDateAnniversary() {
			 
		global $sessionHandler;
		$date = date('Y-m-d');
		$year = date('Y');
		 
		$userId = $sessionHandler->getSessionVariable('UserId');
			
		$query = "SELECT year(lastWishDate)  from user_wishes where userId='".add_slashes($userId)."' and lastWishDate >= '".$date."' and 
						Year(lastWishDate) in('".$year."') and wishBit= 1 AND wishFormat='A'";
			
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");				
}




    //Check for the Anniversary
    public function checkAnniversary() {
			     
		    global $sessionHandler;
		    $date = date('Y-m-d');
		    $year = date('Y');
			     
		    $userId = $sessionHandler->getSessionVariable('UserId');
				    
		    $query = "SELECT count(userId) as cnt from user_wishes where userId='".add_slashes($userId)."' and lastWishDate >= '".$date."' and 
						    Year(lastWishDate) in('".$year."') and wishBit> 0 AND wishFormat='A'";
		    
		    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");				
    }

    public function eventDetailNew($condition=''){

       global $sessionHandler;
       $roleId = $sessionHandler->getSessionVariable('RoleId');
       $userId = $sessionHandler->getSessionVariable('UserId');  
       
       $query="SELECT 
                    IF(isStatus,1 ,0) as status, eventPhoto,comments,m.userWishEventId,m.abbr, 
                    IFNULL(u.userWishId,'-1') AS userWishId
               FROM 
		            user_wishes_events_detail d, user_wishes_events_master m 
		            LEFT JOIN user_wishes u ON 
		            m.eventWishDate = u.lastWishDate AND u.userId='$userId' AND CONCAT('E-',m.userWishEventId) LIKE u.wishFormat 
               WHERE
                    m.userWishEventId=d.userWishEventId AND
                    m.isStatus = 1 AND
                    d.roleId = $roleId AND
                    DATE_FORMAT(m.eventWishDate,'%d %b') = DATE_FORMAT(NOW(),'%d %b')
                    $condition
               ORDER BY 
		            userWishId ASC";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }


     public function eventDetail($condition=''){

       global $sessionHandler;
       $roleId = $sessionHandler->getSessionVariable('RoleId'); 
       
       $query="SELECT 
                    IF(isStatus,1 ,0) as status, eventPhoto,comments,m.userWishEventId,m.abbr
               FROM 
                    user_wishes_events_master m, user_wishes_events_detail d 
               WHERE
                    m.userWishEventId=d.userWishEventId AND
                    m.isStatus = 1 AND
                    d.roleId = $roleId AND
                    DATE_FORMAT(m.eventWishDate,'%d %b') = DATE_FORMAT(NOW(),'%d %b')
               $condition
               ORDER BY m.eventWishDate ASC";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }

     public function multipleEventCheck($abbr){

           global $sessionHandler;
           $userId = $sessionHandler->getSessionVariable('UserId');
           $query="SELECT * 
      	           FROM 
                        user_wishes
  	               WHERE 
                        userId =$userId
                        AND DATE_FORMAT( lastWishDate,  '%b %d %Y' ) <> DATE_FORMAT( NOW( ) ,  '%b %d %Y' )
                        AND wishFormat='$abbr'";
                        
           return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }
}
?>
