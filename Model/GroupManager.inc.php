<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class GroupManager {
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

    public function getClass($universityID,$degreeID,$branchID,$batchId,$studyperiodId)
    {
        global $sessionHandler;

        $query = "SELECT classId
        FROM class
        WHERE universityId = '$universityID' and degreeId = '$degreeID' and branchId = '$branchID' and batchId = '$batchId' and studyPeriodId = '$studyperiodId' and instituteId ='".$sessionHandler->getSessionVariable('InstituteId')."' and sessionId ='".$sessionHandler->getSessionVariable('SessionId')."'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getParentClass($parentId)
    {
        global $sessionHandler;

        $query = "SELECT classId
        FROM `group`
        WHERE groupId = $parentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function addGroup($classid,$optional) {
		global $REQUEST_DATA;

		$groupName = $REQUEST_DATA['groupName'];
		$groupShort = strtoupper($REQUEST_DATA['groupShort']);
		$parentGroup = $REQUEST_DATA['parentGroup'];
		$groupTypeName = $REQUEST_DATA['groupTypeName'];
		$classId = $classid;
		$optional = $optional;
		$optionalSubjectId = $optionalSubjectId;

		if($REQUEST_DATA['optionalSubject'] == '') {
			$optionalSubjectId = 'NULL';
		}
		else {
			$optionalSubjectId = $REQUEST_DATA['optionalSubject'];
		}

		$query = "	INSERT INTO `group`
					SET		groupName = '$groupName',
							groupShort = '$groupShort',
							parentGroupId = '$parentGroup',
							groupTypeId = $groupTypeName,
							classId = $classid,
							isOptional = $optional,
							optionalSubjectId = $optionalSubjectId";

		return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");

		/*return SystemDatabaseManager::getInstance()->runAutoInsert('group', array('groupName','groupShort','parentGroupId','groupTypeId','classId','isOptional','optionalSubjectId'), array($REQUEST_DATA['groupName'],strtoupper($REQUEST_DATA['groupShort']),$REQUEST_DATA['parentGroup'],$REQUEST_DATA['groupTypeName'], $classid,$optional,$optionalSubjectId));*/
	}

	public function editClass($classId)
    {
        $query = "SELECT CONCAT(universityId,\"-\",degreeId,\"-\",branchId) as degree, batchId,studyPeriodId from class where classId=$classId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function editGroup($id,$classid,$optional) {
        global $REQUEST_DATA;

		if($REQUEST_DATA['optionalSubject'] == '') {
			$optionalSubjectId = 'NULL';
		}
		else {
			$optionalSubjectId = $REQUEST_DATA['optionalSubject'];
		}

		$groupName = $REQUEST_DATA['groupName'];
		$groupShort = strtoupper($REQUEST_DATA['groupShort']);
		$parentGroup = $REQUEST_DATA['parentGroup'];
		$groupTypeName = $REQUEST_DATA['groupTypeName'];
		$classId = $classid;
		$optional = $optional;
		$optionalSubjectId = $optionalSubjectId;
		$groupId = $id;

		$query = "	UPDATE	`group`
					SET		groupName = '$groupName',
							groupShort = '$groupShort',
							parentGroupId = '$parentGroup',
							groupTypeId = $groupTypeName,
							classId = $classid,
							isOptional = $optional,
							optionalSubjectId = $optionalSubjectId
					WHERE	groupId = $groupId";

		return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");

		/*return SystemDatabaseManager::getInstance()->runAutoUpdate('group', array('groupName','groupShort','parentGroupId','groupTypeId','classId','isOptional','optionalSubjectId'), array($REQUEST_DATA['groupName'],strtoupper($REQUEST_DATA['groupShort']),$REQUEST_DATA['parentGroup'],$REQUEST_DATA['groupTypeName'],$classid,$optional,$optionalSubjectId), "groupId=$id" );*/
    }

    public function getGroupName($optional, $degree = '') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		//echo $optional;
		//echo $optionalId;
		$conditions = '';
		if ($degree != '') {
			$conditions = "AND		gr.classId = '$degree'";
		}

		if ($optional == 1) {
			$query="SELECT
							gr.groupId,
							gr.groupName
					FROM	`group` gr,
							class cl
					WHERE	gr.classId=cl.classId
					AND		isOptional = 1
					AND		cl.instituteId=$instituteId
					AND		cl.isActive = 1
					$conditions
					ORDER BY groupName";
		}
		else {
			$query="SELECT
							gr.groupId,
							gr.groupName
					FROM	`group` gr,
							class cl
					WHERE	gr.classId=cl.classId
					AND		isOptional = 0
					AND		cl.instituteId=$instituteId
					AND		cl.isActive = 1
					$conditions
					ORDER BY groupName";
		}
		//echo $query;
		//die;

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getClassAllOptionalSubjects($classId) {
		$query = "
					SELECT
									a.subjectId, b.subjectCode
					from			subject_to_class a, subject b
					where			a.optional = 1
					and			a.hasParentCategory = 0
					and			a.subjectId = b.subjectId and a.classId = $classId
					UNION
					SELECT
									a.subjectId, b.subjectCode
					from			optional_subject_to_class a, subject b
					where			a.subjectId = b.subjectId and a.classId = $classId
					";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getGroupTypeCode($groupTypeId) {
		$query = "SELECT groupTypeCode, groupTypeName from group_type where groupTypeId = $groupTypeId";
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getSubjectTypeCode($optionalSubject) {
		$query = "SELECT b.subjectTypeCode, b.subjectTypeName from subject a, subject_type b where a.subjectTypeId = b.subjectTypeId and a.subjectId = $optionalSubject";
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getOptionalSubject($optional,$classId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		if ($optional == 1) {
			$query="SELECT
							sub.subjectId,
							sub.subjectCode
					FROM	subject_to_class stc,
							`subject` sub,
							subject_category sc
					WHERE	stc.subjectId=sub.subjectId
					AND		stc.optional = 1
					AND		sc.subjectCategoryId = sub.subjectCategoryId
					AND		sub.subjectCategoryId IN (SELECT sb.subjectCategoryId FROM `subject` sb, subject_category sct WHERE sb.subjectCategoryId = sct.subjectCategoryId)
					AND		stc.hasParentCategory = 0
					AND		stc.classId = ".$classId."";
		}

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getOptionalParentSubject($optional,$classId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		//echo $optional;
		//echo $optionalId;
		if ($optional == 1) {
		$query="SELECT
							sub.subjectId,
							sub.subjectCode,
							sub.subjectCategoryId
					FROM	subject_to_class stc,
							`subject` sub,
							subject_category sc
					WHERE	stc.subjectId=sub.subjectId
					AND		stc.optional = 1
					AND		sc.subjectCategoryId = sub.subjectCategoryId
					AND	sub.subjectCategoryId IN (SELECT sb.subjectCategoryId FROM `subject` sb, subject_category sct WHERE sb.subjectCategoryId = sct.subjectCategoryId)
					AND		stc.hasParentCategory = 1
					AND		stc.classId = ".$classId;
		}

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getOptionalSubjects($classId) {
		$query = "SELECT a.subjectId, CONCAT(b.subjectCode,' (',b.subjectName,')') as subjectCode, a.hasParentCategory, b.subjectCategoryId from `subject_to_class` a, subject b where a.classId = $classId AND a.optional = 1 and a.subjectId = b.subjectId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	public function getSubjectsOtherCategory($subjectCategoryId) {
		$query = "SELECT subjectId, CONCAT(subjectCode,' (',subjectName,')') AS subjectCode from subject where subjectCategoryId != $subjectCategoryId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}




	public function getOptionalParentSub($subjectCategoryList) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		//echo $optional;
		//echo $optionalId;

		$query="SELECT
							distinct(subjectId),
							subjectCode,
							subjectCategoryId
					FROM	`subject` sub
					WHERE	sub.subjectCategoryId NOT IN($subjectCategoryList)";
					return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}




	public function getGroup($conditions='') {

        $query = "	SELECT
							groupId,
							groupName,
							groupShort,
							parentGroupId,
							groupTypeId,
							classId,
							isOptional,
							if(optionalSubjectId IS NULL,'',optionalSubjectId) AS optionalSubjectId
			        FROM	`group`
							$conditions";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function checkGroupInTimeTable($groupId){
		$query ="SELECT COUNT(groupId) as found
				 FROM  ".TIME_TABLE_TABLE."  WHERE groupId=$groupId";
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getGroupCompare($groupId) {

       $query = "SELECT COUNT(*) as found
        FROM student_groups WHERE groupId=$groupId";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getStudentOptionalGroup($groupId) {

       $query = "SELECT COUNT(*) as found
        FROM student_optional_subject WHERE groupId=$groupId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getParent($groupId) {

       $query = "SELECT parentGroupId
        FROM `group` WHERE parentGroupId=$groupId";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function checkParent($foundParentId) {

       $query = "SELECT parentGroupId
        FROM `group` WHERE parentGroupId=$foundParentId";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getOptionalParent($groupId) {

       $query = "SELECT parentGroupId
        FROM `group` WHERE parentgroupId=$groupId
		AND isOptional = 1";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function deleteGroup($groupId) {

        $query = "DELETE
        FROM `group`
        WHERE groupId=$groupId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }



    public function getGroupList($conditions='', $limit = '', $orderBy='c.groupName') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        
        $classCondition = "";
        if($roleId>=2 && $roleId<=4) {
          $classCondition = " AND cl.isActive IN (1) ";
        }
        else {
          $classCondition = " AND cl.isActive IN (1,3) ";  
        }
        
        
        $query = "	SELECT
							c.groupId,
							c.groupName ,
							(if( p.groupName IS NULL , '', p.groupName )) AS parentGroup,
							c.groupShort,
							gt.groupTypeName,
							cl.className
					FROM	`group` c
					LEFT JOIN `group` p ON c.parentGroupId = p.groupId
					INNER JOIN  group_type as gt ON c.groupTypeId=gt.groupTypeId
					INNER JOIN class as cl ON c.classId=cl.classId
					WHERE	cl.instituteId=$instituteId AND cl.sessionId = $sessionId
					        $classCondition $conditions ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    public function getTotalGroup($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        
        $classCondition = "";
        if($roleId>=2 && $roleId<=4) {
          $classCondition = " AND cl.isActive IN (1) ";
        }
        else {
          $classCondition = " AND cl.isActive IN (1,3) ";  
        }
        

        $query = "SELECT COUNT(*) AS totalRecords
        from `group` c, group_type gt, class cl
        WHERE 
              c.groupTypeId=gt.groupTypeId AND c.classId=cl.classId AND 
              cl.instituteId=$instituteId AND cl.sessionId = $sessionId $classCondition
              $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function checkSelfParent($groupID, $parentGroupID=0) {
		$query = "SELECT COUNT(*) AS cnt FROM `group`
				  WHERE groupId = $parentGroupID AND parentGroupId = $groupID";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	public function getGroupChange($condition) {
		$query = "	SELECT	*
					FROM	`group`
						$condition";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getMaxGroupChange($condition) {
		$query = "	SELECT	MAX(groupId) AS groupId
					FROM	`group`
					$condition";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getWithoutParentGroupChange($condition) {
		$query = "	SELECT	*
					FROM	`group`
					WHERE	parentGroupId = 0
						$condition";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getParentGroupChange($condition) {
		$query = "	SELECT	*
					FROM	`group`
						$condition";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	public function newGroupEntry($groupName,$groupShort,$parentGroupId,$groupTypeId,$newClassId,$optional) {

		$query = " INSERT INTO `Group` (groupName,groupShort,parentGroupId,groupTypeId,classId,isOptional) VALUES
					('$groupName','$groupShort',$parentGroupId,$groupTypeId,$newClassId,$optional)
					";
		return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	}

	public function updateGroupEntry($parentGroupId,$groupName,$newClassId,$maxId) {
		$parentGroupId = $maxId+$parentGroupId;

		$query = "	UPDATE `Group`
					SET		parentGroupId = $parentGroupId
					WHERE	groupName = '$groupName'
					AND		classId = $newClassId";
		return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	}

	public function getVisibleToRoleGroup($groupId) {

       $query = "SELECT COUNT(*) as found
        FROM classes_visible_to_role WHERE groupId=$groupId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getTimeTableGroup($groupId) {

       $query = "SELECT COUNT(*) as found
        FROM  ".TIME_TABLE_TABLE."  WHERE groupId=$groupId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


/************THESE FUNCTIONS ARE NEEDED FOR TRANSFER OF GROUPS FROM ONE CLASS TO ANOTHER CLASS*************/

    public function getGroupsForTransfer($classId) {

        $query = "SELECT
                        t1.groupName,
                        IF(t1.parentGroupId is null,-1,t1.parentGroupId) as parentId,
                        t1.groupId
                  FROM
                        `group` AS t1
                         LEFT JOIN `group` AS t2 ON ( t1.parentGroupId = t2.groupId)
                  WHERE
                        t1.classId=$classId
                        ORDER BY parentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function doGroupTransfer($classId,$parentGroupId,$sourceGroupId) {

        $query = "INSERT INTO
                       `group`
                        (
                          groupName,groupShort,parentGroupId,groupTypeId,classId,isOptional
                        )
                  SELECT
                        groupName,groupShort,$parentGroupId,groupTypeId,$classId,isOptional
                  FROM
                        `group` WHERE groupId=".$sourceGroupId;
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function doStudentGroupUpdation($oldClassId,$oldGroupId,$newClassId,$newGroupId,$newInstituteId,$newSessionId) {

        $query = "INSERT INTO
                       `student_groups`
                        (
                          studentId,classId,groupId,instituteId,sessionId
                        )
                  SELECT
                        studentId,$newClassId,$newGroupId,$newInstituteId,$newSessionId
                  FROM
                        `student_groups`
                  WHERE  classId=".$oldClassId."
                         AND groupId=".$oldGroupId;
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function doGroupPrivilegesUpdation($oldClassId,$oldGroupId,$newClassId,$newGroupId) {
        $query = "INSERT INTO `classes_visible_to_role` (userId, roleId, classId,groupId)
                  SELECT
                        userId, roleId,$newClassId,$newGroupId
                  FROM
                        `classes_visible_to_role`
                  WHERE  classId=".$oldClassId."
                         AND groupId=".$oldGroupId;
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

	public function getOldClassPrivileges($oldClassId) {
		$query = "SELECT * FROM `classes_visible_to_role` where classId = $oldClassId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getOldNewGroups($oldClassId, $newClassId) {
		$query = "SELECT a.groupId as oldGroupId, b.groupId as newGroupId from `group` a, `group` b where a.classId = $oldClassId and b.classId = $newClassId and a.groupName = b.groupName and a.groupShort = b.groupShort and a.groupTypeId = b.groupTypeId and a.isOptional = b.isOptional";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

   public function getClassInfo($classId) {
        $query = "SELECT
                        instituteId,sessionId
                  FROM
                        class
                  WHERE
                        classId=$classId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

  public function checkTargetClassGroupAllocation($classId) {

        $query = "SELECT
                         groupId
                  FROM
                        `group`
                  WHERE
                         classId=$classId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

 public function checkTargetClassGroupAllocationDetailed($classId) {

        $query = "SELECT
                         groupId
                  FROM
                         student_groups
                  WHERE
                         classId=$classId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


/*************THESE FUNCTIONS ARE NEEDED FOR TRANSFER OF GROUPS FROM ONE CLASS TO ANOTHER CLASS************/

}
?>