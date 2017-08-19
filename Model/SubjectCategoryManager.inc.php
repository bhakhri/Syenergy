<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class SubjectCategoryManager {
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
    
	public function addSubjectCategory() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('subject_category', array('categoryName','abbr','parentCategoryId'), array($REQUEST_DATA['categoryName'],$REQUEST_DATA['abbr'],$REQUEST_DATA['parentCategoryId']));
	}

	   public function getSubjectList($conditions='', $limit = '', $orderBy=' sub.subjectName') {
     
        $query = "SELECT 
                         sub.subjectId, sub.subjectCode, sub.subjectName, 
                         (select subjectTypeName from subject_type where subjectTypeId=sub.subjectTypeId)  as subjectTypeName, 
                         IF(sub.hasAttendance=1,'Yes','No') AS hasAttendance, 
                         IF(sub.hasMarks=1,'Yes','No') AS hasMarks 
                  FROM 
                        subject_type subtyp, university b, subject sub  
                  WHERE 
                        sub.subjectTypeId=subtyp.subjectTypeId AND subtyp.universityId = b.universityId $conditions 
                  ORDER BY $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

 public function getSubjectListNew($conditions='', $limit = '', $orderBy=' sub.subjectName') {
	$query = " SELECT 
     			sub.subjectId, sub.subjectCode, sub.subjectName, 
     			IFNULL(st.subjectTypeName,'".NOT_APPLICABLE_STRING."') AS subjectTypeName,  
     			IF( sub.hasAttendance =1, 'Yes', 'No' ) AS hasAttendance, 
     			IF( sub.hasMarks =1, 'Yes', 'No' ) AS hasMarks
		    FROM 
     			`subject` sub  
     			 LEFT JOIN `subject_type` st ON st.subjectTypeId = sub.subjectTypeId	
           		 LEFT JOIN university b ON st.universityId = b.universityId
		    WHERE 
     			$conditions 

                  ORDER BY $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function editSubjectCategory($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('subject_category', array('categoryName','abbr','parentCategoryId'), array($REQUEST_DATA['categoryName'],$REQUEST_DATA['abbr'],$REQUEST_DATA['parentCategoryId']), "subjectCategoryId=$id");
    }


    public function deleteSubjectCategoryId($id='') {
     
        $query = "DELETE FROM `subject_category` WHERE subjectCategoryId = '$id' ";
        
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    

    public function getSubjectCategory($condition='')
    {          
        global $sessionHandler; 
        
        $query = "SELECT 
                        categoryName 
                  FROM 
                        `subject_category`
                  WHERE 
                        $condition ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

   //to get subjectcategory list and subject count
    
    public function getSubjectCategoryList($conditions='', $limit='', $orderBy='') {

         
		 /*$query = "SELECT 

                         s.subjectCategoryId, s.categoryName, IF(s.abbr IS NULL,'".NOT_APPLICABLE_STRING."',s.abbr) AS abbr, s.parentCategoryId,
                         IF(s.parentCategoryId IS NULL,'".NOT_APPLICABLE_STRING."', IF(s.parentCategoryId = 0,'".NOT_APPLICABLE_STRING."', 
                         (SELECT categoryName FROM `subject_category` WHERE subjectCategoryId=s.parentCategoryId))) AS parentCategoryName,
						 (select count(sb.subjectId) from `subject` sb where sb.subjectCategoryId = s.subjectCategoryId) as subjectcount
                         FROM 
                        `subject_category` s

                         $conditions 
                         $orderBy $limit";

                  $conditions 
                  $orderBy $limit";  */
        $query = "SELECT 
				tt.subjectCategoryId, tt.categoryName, tt.abbr, tt.parentCategoryId, tt.parentCategoryName, tt.subjectCount
		  FROM 
			(SELECT 
				DISTINCT s.subjectCategoryId, s.categoryName, IF( s.abbr IS NULL , '---', s.abbr ) AS abbr, s.parentCategoryId, 
			  	IF( s.parentCategoryId IS NULL , '---', IF( s.parentCategoryId =0, '---', (
				SELECT categoryName
				FROM
					 `subject_category`
				WHERE 
					subjectCategoryId = s.parentCategoryId) ) ) AS parentCategoryName, 
				COUNT( ss.subjectId ) AS subjectCount
			FROM 
				`subject_category` s 
				LEFT JOIN `subject` ss ON s.subjectCategoryId = ss.subjectCategoryId
				LEFT JOIN `subject_type` st ON st.subjectTypeId = ss.subjectTypeId
		GROUP BY 
				s.subjectCategoryId
			) AS tt 

                  $conditions          
                  $orderBy $limit";   
    

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

    
    public function getSubjectCategoryCount($conditions='') {
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords
                  FROM 
                       ( SELECT 
                         s.subjectCategoryId, s.categoryName, IF(s.abbr IS NULL,'".NOT_APPLICABLE_STRING."',s.abbr) AS abbr, s.parentCategoryId,
                         IF(s.parentCategoryId IS NULL,'".NOT_APPLICABLE_STRING."', IF(s.parentCategoryId = 0,'".NOT_APPLICABLE_STRING."', 
                         (SELECT categoryName FROM `subject_category` WHERE subjectCategoryId=s.parentCategoryId))) AS parentCategoryName,
                         COUNT(ss.subjectId) as subjectCount
                  FROM 
                        `subject_category` s LEFT JOIN subject ss ON s.subjectCategoryId=ss.subjectCategoryId
						 GROUP BY s.subjectCategoryId 
						 $conditions
                       ) ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
      
    public function checkSelfParent($condition='') {
        
        $query = "SELECT 
                        COUNT(*) AS cnt 
                  FROM 
                        `subject_category` 
                  $condition ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    public function getParentSubjectCategory($orderBy=' categoryName') {
        
        $query = "SELECT subjectCategoryId, abbr, categoryName FROM `subject_category` ORDER BY $orderBy";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getParent($condition='') {
    
       $query = "SELECT 
                       IF(parentCategoryId IS NULL,'', IF(parentCategoryId = 0,'', parentCategoryId)) AS  parentCategoryId 
                 FROM 
                       `subject_category` WHERE $condition  ";
    
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    
    public function getParentSubject($Id) {
    
       $query = "SELECT COUNT(*) AS cnt FROM `subject` WHERE subjectCategoryId=$Id ";
    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }     
    
   function checkChildCount($parentCategoryId) {
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $query = "SELECT count(*) as cnt FROM subject_category WHERE parentCategoryId = $parentCategoryId ";
        
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
}
?>
