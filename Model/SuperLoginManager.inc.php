<?php 
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class SuperLoginManager {
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

    public function getSuperLoginStudentList($condition='',$limit="",$orderBy='') {
      
           global $sessionHandler;
           $systemDatabaseManager = SystemDatabaseManager::getInstance();
           $instituteId = $sessionHandler->getSessionVariable('InstituteId');  
           
           if($orderBy=='') {
             $orderBy = "studentName"  ;
           }
           
           $query ="SELECT
                          DISTINCT b.classId, b.className, b.isActive, s.studentId,s.studentStatus, 
                          CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName, 
                          IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                          IF(IFNULL(s.regNo,'')='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,
                          IF(IFNULL(s.studentEmail,'')='','".NOT_APPLICABLE_STRING."',s.studentEmail) AS studentEmail,
                          IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                          IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                          IF(IFNULL(s.motherName,'')='','".NOT_APPLICABLE_STRING."',s.motherName) AS motherName,
                          IF(IFNULL(s.guardianName,'')='','".NOT_APPLICABLE_STRING."',s.guardianName) AS guardianName,
                          IFNULL(s.userId,'') AS userId, IFNULL(s.fatherUserId,'') AS fatherUserId, 
                          IFNULL(s.motherUserId,'') AS motherUserId, IFNULL(s.guardianUserId,'') AS guardianUserId ,
                          IFNULL(s.studentPhoto,'') AS studentPhoto
                    FROM    
                          student s, class b
                    WHERE 
                          b.isActive = 1 AND
                          s.classId = b.classId AND
                          b.instituteId  = '$instituteId'
                          $condition      
                    ORDER BY 
                          $orderBy
                    $limit";

            return $systemDatabaseManager->executeQuery($query,"Query: $query");
      }
      
      public function getSuperLoginStudentTotal($condition='') {
      
           global $sessionHandler;
           $systemDatabaseManager = SystemDatabaseManager::getInstance();
           $instituteId = $sessionHandler->getSessionVariable('InstituteId');   
           
           $query ="SELECT
                          COUNT(*) AS totalRecords
                    FROM    
                          student s, class b
                    WHERE 
                          b.isActive = 1 AND  
                          s.classId = b.classId AND
                          b.instituteId  = '$instituteId'
                    $condition ";

            return $systemDatabaseManager->executeQuery($query,"Query: $query");
      }
      
      public function getBranch($condition='') {
      
           global $sessionHandler;
           $systemDatabaseManager = SystemDatabaseManager::getInstance();
           $instituteId = $sessionHandler->getSessionVariable('InstituteId');   
           
           $query ="SELECT
                          COUNT(*) AS totalRecords
                    FROM    
                          student s, class b
                    WHERE 
                          b.isActive = 1 AND  
                          s.classId = b.classId AND
                          b.instituteId  = '$instituteId'
                    $condition ";

            return $systemDatabaseManager->executeQuery($query,"Query: $query");
      }
      
      public function getBatch($condition='') {
      
           global $sessionHandler;
           $systemDatabaseManager = SystemDatabaseManager::getInstance();
           $instituteId = $sessionHandler->getSessionVariable('InstituteId');   
           
           $query ="SELECT
                          COUNT(*) AS totalRecords
                    FROM    
                          student s, class b
                    WHERE 
                          b.isActive = 1 AND
                          s.classId = b.classId AND
                          b.instituteId  = '$instituteId'
                    $condition ";

            return $systemDatabaseManager->executeQuery($query,"Query: $query");
      }
      
      public function getClass($condition='') {
      
           global $sessionHandler;
           $systemDatabaseManager = SystemDatabaseManager::getInstance();
           $instituteId = $sessionHandler->getSessionVariable('InstituteId');   
           
           $query ="SELECT
                          COUNT(*) AS totalRecords
                    FROM    
                          student s, class b
                    WHERE 
                          b.isActive = 1 AND
                          s.classId = b.classId AND
                          b.instituteId  = '$instituteId'
                    $condition ";

            return $systemDatabaseManager->executeQuery($query,"Query: $query");
      }
      
      
      public function getLoginClass($condition='') {
      
           global $sessionHandler;
           $systemDatabaseManager = SystemDatabaseManager::getInstance();
           $instituteId = $sessionHandler->getSessionVariable('InstituteId');   
           
           $query ="SELECT
                          i.instituteId, c.classId, c.degreeId, c.batchId, c.branchId,  
                          i.instituteCode, c.className, d.degreeCode, br.batchName, b.branchCode, 
                          CONCAT_WS('!!~!!~!!',i.instituteId, c.classId, c.degreeId, c.batchId, c.branchId) AS combineId,
                          CONCAT_WS('!!~!!~!!',i.instituteCode, c.className, d.degreeCode, br.batchName, b.branchCode) AS combineName
                    FROM    
                          institute i, class c, degree d, batch b, branch br
                    WHERE 
                          c.isActive = 1 AND
                          c.instituteId = i.instituteId AND
                          c.instituteId  = '$instituteId' AND
                          c.degreeId = d.degreeId AND
                          c.batchId = b.batchId AND
                          c.branchId = br.branchId
                    $condition ";

            return $systemDatabaseManager->executeQuery($query,"Query: $query");
      }
      
      
}     //end of class
?>

