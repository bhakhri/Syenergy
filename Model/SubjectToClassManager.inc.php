<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "subject_to_class" TABLE
//
//
// Author :Rajeev Aggarwal
// Created on : (14.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class SubjectToClassManager {
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
// THIS FUNCTION IS USED TO FETCH SUBJECT TO CLASS DATA
//
// Author :Rajeev Aggarwal
// Created on : (14.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getClassData($conditions='') {

        $query = "SELECT subjectId,optional,offered,credits
        FROM subject_to_class
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH SUBJECT TO CLASS DATA
//
// Author :Rajeev Aggarwal
// Created on : (14.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getClass($universityID,$degreeID,$branchID,$studyperiodId) {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
				  classId,
				  className
				  FROM class
				  WHERE universityId = '$universityID' and degreeId = '$degreeID' and branchId = '$branchID' and studyPeriodId = '$studyperiodId' and instituteId = '$instituteId' and sessionId = '$sessionId'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function insertOptSubToClass($classId, $parentSubjectId) {
		global $REQUEST_DATA;
		$chb  = $REQUEST_DATA['chb'];
		$cnt = count($chb);
		if($classId)
		{
			if($REQUEST_DATA['subjectDetail']==''){

				$query = "DELETE
				FROM optional_subject_to_class
				WHERE classId = $classId AND parentOfSubjectId = $parentSubjectId";
				SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
			}
			$insertValue = "";
			for($i=0;$i<$cnt; $i++) {
				$querySeprator = '';
				if($insertValue!=''){

					$querySeprator = ",";
				}
				$insertValue .= "$querySeprator ('".$classId."','".$chb[$i]."','".$parentSubjectId."')";
			}
			$query = "INSERT INTO `optional_subject_to_class`
					  (classId,subjectId,parentOfSubjectId)
					  VALUES
					  $insertValue";

			SystemDatabaseManager::getInstance()->executeUpdate($query);
			return true;
		}
		else {
			return false;
		}
    }
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT SUBJECT TO CLASS DATA
//
// Author :Rajeev Aggarwal
// Created on : (14.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function insertSubToClass($classId) {
		global $REQUEST_DATA;
		$chb  = $REQUEST_DATA['chb'];
		$cnt = count($chb);
		if($classId) {
            
            $searchSubjectCodeList = trim($REQUEST_DATA['searchSubjectCode']); 
            
            $advanceSearch = '';
            $duplicateArray =array();
            if($searchSubjectCodeList!='') {
               $searchSubjectCodeArray = explode(',',$searchSubjectCodeList);
               $searchSubjectCode='';  
               for($i=0;$i<count($searchSubjectCodeArray);$i++) {
                  if(trim($searchSubjectCodeArray[$i])!='') {
                    $find='0';  
                    for($j=0;$j<count($duplicateArray);$j++) {
                      if($duplicateArray[$j] == strtoupper(trim($searchSubjectCodeArray[$i])))  {
                       $find='1';   
                        break;  
                      }
                    }
                    if($find=='0') {  
                      if($searchSubjectCode!='') {
                        $searchSubjectCode .=", ";  
                      } 
                      $searchSubjectCode .= "'".htmlentities(add_slashes(trim($searchSubjectCodeArray[$i])))."'";   
                      $duplicateArray[] = strtoupper(trim($searchSubjectCodeArray[$i]));
                    }
                  }
               } 
               if($searchSubjectCode != '') {
                 $advanceSearch = " AND (subjectId IN (SELECT DISTINCT subjectId FROM `subject` WHERE UPPER(TRIM(subjectCode)) IN ($searchSubjectCode))) ";
               }
               
            }

			if($REQUEST_DATA['subjectDetail']=='') {
			  $query = "DELETE FROM subject_to_class WHERE classId = $classId  $advanceSearch ";
			  $returnArray = SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
              if($returnArray===false) {
                return false;  
              }
			}
            
			$insertValue = "";
			for($i=0;$i<$cnt; $i++) {
				$querySeprator = '';
				if($insertValue!=''){
                  $querySeprator = ",";
				}
				 $isAlernate = $REQUEST_DATA['isAlternate'.$chb[$i]];
                if($isAlernate=='') {
                  $isAlernate='0';  
                }
                
				$insertValue .= "$querySeprator ('".$classId."','".$chb[$i]."',
				'".$REQUEST_DATA['optional'.$chb[$i]]."',
				'".$REQUEST_DATA['hasParentCategory'.$chb[$i]]."',
				'".$REQUEST_DATA['offered'.$chb[$i]]."',
				'".$REQUEST_DATA['credit'.$chb[$i]]."',
				'".$REQUEST_DATA['internalMarks'.$chb[$i]]."',
				'".$REQUEST_DATA['externalMarks'.$chb[$i]]."',
				'".$isAlernate."')";
				
			}
            $query = "INSERT INTO `subject_to_class`
					  (classId,subjectId,optional,hasParentCategory,offered,credits,internalTotalMarks,externalTotalMarks,isAlternateSubject)
					  VALUES
					  $insertValue";
					
					  

			SystemDatabaseManager::getInstance()->executeUpdate($query);
			return true;
		}
		else {
			return false;
		}
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH SUBJECT TO CLASS DATA
//
// Author :Rajeev Aggarwal
// Created on : (14.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getSubToClassList($conditions='',$conditions1='', $limit = '', $orderBy=' subjectName') {
      $query = "SELECT
				  DISTINCT sub.subjectId,
				  sub.*,
				  subtocls.subjectToClassId,
				  subtocls.optional,
				  subtocls.hasParentCategory,
				  subtocls.offered,
				  subtocls.isAlternateSubject,
				  subtocls.credits,subtocls.internalTotalMarks,
				  subtocls.externalTotalMarks,
				  st.subjectTypeName
				  FROM
				  `subject_type` st,subject sub
				  LEFT JOIN subject_to_class subtocls
				  ON (sub.subjectId = subtocls.subjectId $conditions)

				   WHERE
				  sub.subjectTypeId = st.subjectTypeId
				   $conditions1
				   group by sub.subjectId
				  ORDER BY $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH SUBJECT TO CLASS DATA
//
// Author :Rajeev Aggarwal
// Created on : (14.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getSubList($conditions='', $limit = '', $orderBy=' subjectName') {
        $query = "SELECT
				  sub.subjectId,
				  sub.*,
				  st.subjectTypeName,
				  subtocls.subjectToClassId,
				  subtocls.optional,
				  subtocls.hasParentCategory,
				  subtocls.offered,
				  subtocls.credits,subtocls.internalTotalMarks,subtocls.externalTotalMarks
				  FROM
				  `subject` sub, `subject_to_class` subtocls,`subject_type` st
				  WHERE
				  sub.subjectId = subtocls.subjectId AND
				  sub.subjectTypeId = st.subjectTypeId
				  $conditions
				  ORDER BY $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH SUBJECT TO CLASS DATA
//
// Author :Rajeev Aggarwal
// Created on : (14.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getSubList1($conditions='', $limit = '', $orderBy=' subjectName') {
        $query = "SELECT

				  sub.*,
				  subtocls.subjectToClassId,
				  subtocls.optional,
				  subtocls.offered,
				  subtocls.credits,subtocls.internalTotalMarks,subtocls.externalTotalMarks
				  FROM
				  subject sub, subject_to_class subtocls

				  $conditions
				  ORDER BY $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH SUBJECT TO CLASS DATA
//
// Author :Rajeev Aggarwal
// Created on : (14.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function countSubList($conditions='') {
        $query = "SELECT
				  COUNT(*) as countRecords
				  FROM
				  subject sub, `subject_type` st,subject_to_class subtocls
				  WHERE
				  sub.subjectId = subtocls.subjectId
				  and sub.subjectTypeId = st.subjectTypeId

				  $conditions
				  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH TOTAL OF SUBJECT TO CLASS DATA
//
// Author :Rajeev Aggarwal
// Created on : (14.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getTotalSubToClass($conditions='') {

        $query = "SELECT COUNT(*) AS totalRecords
        FROM subject sub, subject_to_class subcls
        WHERE sub.subjectId=subcls.subjectId $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH SUBJECT TO CLASS DATA FOR PRINT
//
// Author :Rajeev Aggarwal
// Created on : (14.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	 public function getSubToClassListPrint($conditions='', $limit = '', $orderBy=' subjectName') {
		$query = "SELECT
		          className, subjectName,subjectCode,subjectAbbreviation,subjectTypeName,if(optional=1,'Yes','No') optional,if(hasParentCategory=1,'Yes','No') hasParentCategory,if(offered=1,'Yes','No') offered,if(isAlternateSubject=1,'Yes','No') isAlternateSubject,credits,internalTotalMarks,externalTotalMarks
				  FROM
				  `subject` sub,`subject_to_class` subtocls, `subject_type` st,`class` cls
				  WHERE
				  subtocls.classId = cls.classId AND
				  sub.subjectId = subtocls.subjectId AND
				  st.subjectTypeId = sub.subjectTypeId
				  $conditions
				  ORDER BY $orderBy";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
}
// $History: SubjectToClassManager.inc.php $
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 3/30/10    Time: 1:33p
//Updated in $/LeapCC/Model
//bugs fixed. FCNS No.1490
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 10-01-20   Time: 12:40p
//Updated in $/LeapCC/Model
//removed bug of subject to class module
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/10/09    Time: 11:13a
//Updated in $/LeapCC/Model
//Fixed bug no 984,982
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 7/20/09    Time: 12:56p
//Updated in $/LeapCC/Model
//Added "hasParentCategory" in subject to class module
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 5/07/09    Time: 2:11p
//Updated in $/LeapCC/Model
//Updated subject list function to show subjectype also
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 4/06/09    Time: 12:14p
//Updated in $/LeapCC/Model
//Updated with subject type
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/05/09    Time: 6:38p
//Updated in $/LeapCC/Model
//added internaltotalmarks and externaltotalmarks field
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/11/08   Time: 3:00p
//Updated in $/LeapCC/Model
//Updated module as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 9/11/08    Time: 5:38p
//Updated in $/Leap/Source/Model
//updated formatting and added comments
?>