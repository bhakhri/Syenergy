<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FrozenClassManager {
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


//------------------------------------------------------------------------------------------------
// This Function  gets the data from time table for timetable labelwise
//
// Author : Jaineesh
// Created on : 01-07-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

   public function getFrozenClasses($labelId,$filter='', $conditions='', $orderBy='') {

       global $sessionHandler;

      $query ="SELECT
                        distinct(ttc.classId),
						ttc.timeTableLabelId,
						ttc.timeTableClassId,
						cl.className,
						cl.isFrozen
                FROM
                        time_table_classes ttc,
						time_table_labels ttl,
						class cl
                WHERE   ttc.timeTableLabelId = ttl.timeTableLabelId
				AND		ttc.classId = cl.classId
				AND		ttc.timeTableLabelId = $labelId
                AND     cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                AND     cl.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
						GROUP BY ttc.classId
						ORDER BY $orderBy ";

       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

   //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO Update class to frozen
//
// Author :Jaineesh
// Created on : 01-07-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function updateFreezeClass() {
		global $REQUEST_DATA;
		global $sessionHandler;
		$chb  = $REQUEST_DATA['chb'];

		$insertValue = "";
		foreach($chb as $classId){

				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator('".$classId."')";
		}

		$query = "UPDATE `class` set isFrozen=1 WHERE
					  classId IN (".$insertValue.")";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


   //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT FREEZE LOG
//
// Author :Jaineesh
// Created on : 01-07-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function insertFreezeUnfreezeLog() {
		global $REQUEST_DATA;
		global $sessionHandler;

		$labelId = $REQUEST_DATA['labelId'];
		$chb  = $REQUEST_DATA['chb'];
		$reason  = trim(addslashes($REQUEST_DATA['reason']));
		$action  = $REQUEST_DATA['imageField'];
		$date = date('Y-m-d h:i:s');
		$userId = $sessionHandler->getSessionVariable('UserId');

		$insertValue = "";
		foreach($chb as $classId){

				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator(".$userId.",".$labelId.",".$classId.",'".$date."','".$action."','".$reason."')";
		}

		$query = "INSERT INTO  freeze_unfreeze_log
					  (userId,timeTableLabelId,classId,logDateTime,action,reason)
					  VALUES ".$insertValue;

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

 //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE CLASS FOR UNFREEZE
//
// Author :Jaineesh
// Created on : 01-07-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function updateUnfreezeClasses() {
		global $REQUEST_DATA;
		global $sessionHandler;
		$chb  = $REQUEST_DATA['chb'];

		$insertValue = "";
		foreach($chb as $classId){

				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator('".$classId."')";
		}

		//echo ($insertValue);
		//die();

		$query = "UPDATE `class` set isFrozen=0 WHERE
					  classId IN (".$insertValue.")";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

	 //--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT FREEZE LOG
//
// Author :Jaineesh
// Created on : 01-07-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function insertUnfreezeLog() {
		global $REQUEST_DATA;
		global $sessionHandler;

		$labelId = $REQUEST_DATA['labelId'];
		$chb  = $REQUEST_DATA['chb'];
		$reason  = trim(addslashes($REQUEST_DATA['reason']));
		$action  = $REQUEST_DATA['imageField1'];
		$date = date('Y-m-d h:i:s');
		$userId = $sessionHandler->getSessionVariable('UserId');

		$insertValue = "";
		foreach($chb as $classId){

				$querySeprator = '';
			    if($insertValue!=''){
					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator(".$userId.",".$labelId.",".$classId.",'".$date."','".$action."','".$reason."')";
		}

		$query = "INSERT INTO  freeze_unfreeze_log
					  (userId,timeTableLabelId,classId,logDateTime,action,reason)
					  VALUES ".$insertValue;

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//------------------------------------------------------------------------------------------------
// This Function  gets the data from TotalMarksTransfer
//
// Author : Jaineesh
// Created on : 01-07-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

   public function getClassMarksTransferred($classId) {

		global $REQUEST_DATA;
		global $sessionHandler;

		$query = "
					SELECT
								count(subjectId) as cnt
					FROM		subject_to_class
					WHERE		classId = $classId and externalTotalMarks != 0
					AND			subjectId NOT IN (
													SELECT
																DISTINCT subjectId
													FROM
																".TOTAL_TRANSFERRED_MARKS_TABLE."
													WHERE		classId = $classId
													AND			conductingAuthority = 2
												)";

       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

   //------------------------------------------------------------------------------------------------
// This Function  gets the data from TotalMarksTransfer
//
// Author : Jaineesh
// Created on : 01-07-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

   public function getUseClassMarksTransferred($classId) {

		global $REQUEST_DATA;
		global $sessionHandler;

		$query = "
					SELECT
								COUNT(*) AS cnt
					FROM 		".TOTAL_TRANSFERRED_MARKS_TABLE."
					WHERE		classId = $classId
					AND			conductingAuthority = 2";

       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

   //------------------------------------------------------------------------------------------------
// This Function  gets the class
//
// Author : Jaineesh
// Created on : 17-12-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

   public function getClass($classId) {

		global $REQUEST_DATA;
		global $sessionHandler;

		$query = "	SELECT  className
					FROM	class
					WHERE	classId = ".$classId;

       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

}

// $History: FrozenClassManager.inc.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/21/10    Time: 1:59p
//Updated in $/LeapCC/Model
//fixed bug nos. 0002672, 0002660, 0002657, 0002656, 0002658, 0002659,
//0002661, 0002662
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/28/09   Time: 5:54p
//Updated in $/LeapCC/Model
//done changes for checking if external marks for all subjects have been
//transferred or not.
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/17/09   Time: 5:24p
//Updated in $/LeapCC/Model
//Change in coding during class has been frozen if no marks has been
//transferred of class.
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/07/09    Time: 1:50p
//Created in $/LeapCC/Model
//get the queries for frozen class
//
?>