<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class TopicManager {
	private static $instance = null;
	
	private function __construct(){
   
   }
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
    
	public function addTopicInfoInTransaction($SubjectId,$topic,$topicAbbr) {
		 
        
			$query = "INSERT INTO `subject_topic` (subjectId,topic,topicAbbr) VALUES ($SubjectId,'$topic','$topicAbbr')";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
		}
	
		
	public function getSubjectId($value) {
		 $query = "SELECT subjectId from `subject` where subjectCode='$value'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
	public function getTopicList($value) {
		echo $query = "SELECT topic from `subject_topic` where subjectId='$value'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
}
?>