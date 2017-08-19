<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class SubjectTopicManager {
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
	public function addSubjectTopic() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('subject_topic', array('subjectId','topic','topicAbbr'), array($REQUEST_DATA['studentSubject'],$REQUEST_DATA['subjectTopic'],$REQUEST_DATA['subjectAbbr']) );
	}

	public function addSubjectTopicInTransaction($subjectId,$courseTopic, $subjectAbbr) {
		$query = "INSERT INTO subject_topic(subjectId, topic, topicAbbr) VALUES($subjectId, '$courseTopic', '$subjectAbbr')";
      return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	public function deleteSubjectTopicInTransaction($subjectId) {
		$query = "DELETE FROM subject_topic WHERE subjectId = $subjectId";
      return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

    public function editSubjectTopic($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('subject_topic', array('subjectId','topic','topicAbbr'),  array($REQUEST_DATA['studentSubject'],$REQUEST_DATA['subjectTopic'],$REQUEST_DATA['subjectAbbr']), "subjectTopicId=$id" );
    }   

    public function addBulkCourseTopic() {

        global $REQUEST_DATA;

        $courseTopicArr = explode($REQUEST_DATA['topicSeprator'], $REQUEST_DATA['courseTopic']);
        //print_r($courseTopicArr);
        $courseTopicArr1 = array_unique($courseTopicArr);

        $courseTopicArr2 = array_intersect($courseTopicArr, $courseTopicArr1);
        //print_r($courseTopicArr);
        $cnt = count($courseTopicArr2);
        $errorFound = 0;
        //print_r($courseTopicArr2);
        for($i=0;$i<$cnt; $i++)
        {
            $querySeprator = '';
            if($insertValue!=''){

                $querySeprator = ",";
            }

             
            if(trim($courseTopicArr2[$i])!=''){

                $insertValue .= "$querySeprator ($REQUEST_DATA[studentCourse],'".addslashes(trim($courseTopicArr2[$i]))."','".addslashes(trim($courseTopicArr2[$i]))."')";

                $insertValue1 .= "$querySeprator '".addslashes($courseTopicArr2[$i])."'";
            }
            else{
                //echo($cnt.'--'.$i);
                $errorFound = 2;
            }
        }
        //echo $insertValue;
        if($errorFound==1){

            
            return "greaterValue";
        
        }
        elseif($errorFound==2){

             
            return "emptyValue";
        
        }
        else{

            $query = "SELECT COUNT(*) as totalRecords FROM `subject_topic` where topic IN($insertValue1) AND  subjectId = $REQUEST_DATA[studentCourse]";
            $foundArr = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
            if($foundArr[0]['totalRecords']>0){
            
                return false;
            }
            else{
            
                $query = "INSERT INTO `subject_topic` (subjectId,topic,topicAbbr) VALUES ".$insertValue;
                SystemDatabaseManager::getInstance()->executeUpdate($query);
                return true;
            }
        }
   }     
   
   public function checkInSubjectTopic($id) {
     
        $query = "
					SELECT 
							COUNT(*) AS found 
					FROM	topics_taught
					WHERE	subjectTopicId like '%~$id~%'
				";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getSubjectTopic($conditions='') {
    
        $query = "SELECT st.subjectTopicId,st.subjectId,st.topic,st.topicAbbr, sub.subjectName, sub.subjectCode   
        FROM subject sub, subject_topic st
        WHERE st.subjectId = sub.subjectId
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
  
    public function deleteSubjectTopic($Id) {
     
        $query = "DELETE 
        FROM subject_topic 
        WHERE subjectTopicId=$Id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
    public function getSubjectTopicList($conditions='', $limit = '', $orderBy=' st.topic') {
    
        $query = "SELECT    st.subjectTopicId,st.subjectId,st.topic,st.topicAbbr, sub.subjectName, 
                            sub.subjectCode, IFNULL(t.employeeId,-1) AS sEmployeeId    
                  FROM 
                            subject sub, subject_topic st LEFT JOIN topics_taught  t ON t.subjectTopicId=st.subjectTopicId
                  WHERE 
                            st.subjectId = sub.subjectId
                            $conditions 
                  ORDER BY $orderBy $limit";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
     
    public function getTotalSubjectTopic($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM subject sub, subject_topic st
        WHERE st.subjectId = sub.subjectId
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
}
?>