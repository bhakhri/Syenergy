<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the Country Module
//
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

//Author : Arvind Singh Rawat
//updated on 25-06-2008 
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class BlockStudent {
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
	public function addStudent() {
		//echo "reached"; die;
		global $REQUEST_DATA;
                global $sessionHandler;

 /////////////////////////////NEW QUERY FUNCTION
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
		$rollNo = $REQUEST_DATA['rollNo'];
 		$arr=($REQUEST_DATA['rollNo']);
  		$rollNO=explode(",",$arr);
		//print_r($rollNO);
    		   //echo count($rollNO);
			$message = $REQUEST_DATA['blkmessage'];
			 $dt = date('Y-m-d');
	 		  $userId = $sessionHandler->getSessionVariable('UserId');
			
        	   			
		
       
			for($i=0;$i<count($rollNO);$i++){	
			SystemDatabaseManager::getInstance()->runAutoInsert('block_stu', 
			  array('studentId','isStatus','message','userId','date'), 
		 	   array($rollNO[$i],1,$message,$userId,$dt));
				}

    			return true;
  			}

		   public function getStudent($conditions='') {
     				
      			  $query = "SELECT *
      				  FROM block_stu
      				  $conditions";
 			//   echo $query;

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    					}

		
		public function getStudentList($conditions='', $limit = '', $orderBy='rollNo') {
 	
		      $query="SELECT 
				   a.blockId, a.isStatus, a.message,
				   CONCAT(IFNULL(b.firstName,''),IFNULL(b.lastName,'')) AS studentName,
				   b.rollNo, c.className, b.studentId  
			      FROM 
				   block_stu a,student b, class c 
			      WHERE 
				   a.studentId=b.rollNo AND
				   c.classId = a.classId 
			      $conditions 
			      ORDER BY $orderBy $limit ";

			
     		     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		 }
		 

		public function getTotalStudent($conditions='') {
 	
		      $query="SELECT 
				     COUNT(*) AS totalRecords	
			      FROM 
				   block_stu a,student b, class c 
			      WHERE 
				   a.studentId=b.rollNo AND
				   c.classId = a.classId 
			      $conditions";

     		     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		 }


			   public function checkInState($studentId) {
     
    			    $query = "SELECT COUNT(*) AS found 
     				   FROM block_stu 
     				   WHERE blockId=$studentId";
    			    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  		         	  }
       
			
		  public function deleteStudent($studentId) {
     
        $query = "DELETE 
        FROM block_stu 
        WHERE blockId=$studentId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
   
   
}
?>

<?php 

//$History: StuManager.inc.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/08/09    Time: 6:04p
//Updated in $/LeapCC/Model
//country master validation & required fields added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/05/08    Time: 12:47p
//Updated in $/Leap/Source/Model
//added a new field nationalityName
//
//*****************  Version 7  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:24p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 6  *****************
//User: Arvind       Date: 6/25/08    Time: 11:53a
//Updated in $/Leap/Source/Model
//added a new dependency constraint fucntion 
//added comments
//
//*****************  Version 5  *****************
//User: Arvind       Date: 6/24/08    Time: 4:04p
//Updated in $/Leap/Source/Model
//modified files
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


