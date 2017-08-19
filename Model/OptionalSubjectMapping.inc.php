<?php
//-------------------------------------------------------
// Purpose: contains business logic of student to  optional Subject Mapping
//
// Author : Arvind Singh Rawat
// Created on : (28.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class OptionalSubjectMappingManager {
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

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Student LIST
//
// Author :Arvind Singh Rawat
// Created on : (28.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  		 
    public function getStudentList($conditions='') {
     
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT s.studentId,s.rollNo,s.regNo,s.firstName,s.lastName FROM
                student s,subject_to_class stc,`group` g,class c
                WHERE 
                s.classId=stc.classId
                AND s.classId= g.classId 
                AND c.classId= s.classId
                AND c.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                AND c.sessionId='".$sessionHandler->getSessionVariable('SessionId')."' 
                $conditions";
                return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     public function insertStudents($REQUEST_DATA) {
     
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId'); 
       
        $counter=count($REQUEST_DATA['chb']);
        if(isset($REQUEST_DATA['checkBoxCount'])){ 
            $arr=explode('-',$REQUEST_DATA['checkBoxCount']);
        //if(isset($arr)){ 
            for($j=0;$j<count($arr);$j++){
                    $queryDelete="DELETE FROM student_optional_subject WHERE studentId='".$arr[$j]."'";
                    SystemDatabaseManager::getInstance()->executeUpdate($queryDelete);
            }
        }
        //print_r( $arr);
       // echo "hi"; 
        for($i=0;$i<$counter;$i++){
         //  $id=$this->checkStudentRecord($REQUEST_DATA['chb'][$i],$REQUEST_DATA);
         //  if(!isset($id[0]['studentId'])){
               $query="INSERT INTO student_optional_subject(subjectId,studentId,classId) values('".$REQUEST_DATA['subject']."','".$REQUEST_DATA['chb'][$i]."','".$REQUEST_DATA['studentClass']."')";
              $result= SystemDatabaseManager::getInstance()->executeUpdate($query); 
         //  }
          // else{
                
        //   }
        }
        return $result;
  }
  
  public function checkStudentRecord($id,$REQUEST_DATA) {
            
      $query="SELECT studentId FROM student_optional_subject WHERE studentId=$id AND subjectId='".$REQUEST_DATA['subject']."' AND classId='".$REQUEST_DATA['studentClass']."'";
        $result = SystemDatabaseManager::getInstance()->executeQuery($query); 
        return $result;
  }
    
}
//History: $
?>