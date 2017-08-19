<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Grade" Module
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class GradeManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "GradeManager" CLASS
//
//-------------------------------------------------------------------------------


	private function __construct() {
	}


//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "GradeManager" CLASS
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Grade
//
//
//-------------------------------------------------------------------------------


	public function addGrade() {
		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $gradeLabel = htmlentities(add_slashes(strtoupper(trim($REQUEST_DATA['gradeLabel']))));
        $failGrade = htmlentities(add_slashes(trim($REQUEST_DATA['failGrade'])));
        $gradeStatus = htmlentities(add_slashes(trim($REQUEST_DATA['gradeStatus'])));
        
		return SystemDatabaseManager::getInstance()->runAutoInsert('grades', 
            array('gradeLabel','gradePoints','gradeSetId','instituteId','failGrade','gradeStatus'), 
            array( $gradeLabel,$REQUEST_DATA['gradePoints'],$REQUEST_DATA['gradeSetId'], $instituteId,$failGrade,$gradeStatus));
	}


//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR UPDATING GRADE DESCRIPTION VALUES
//  Author: Aditi Miglani
//  Created on: 23 August 2011
//  Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------

public function updateGradeDescription($filter,$gradeSetId,$gradeLabel,$gradePoints,$condition='',$failGrade='',$gradeStatus='') {

	global $REQUEST_DATA;
	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
    
     
        $gradeLabel = htmlentities(add_slashes(strtoupper(trim($gradeLabel))));
        $failGrade = htmlentities(add_slashes(trim($failGrade)));
        $gradeStatus = htmlentities(add_slashes(trim($gradeStatus)));
        
     	$query="$filter 
		     grades
		SET
		     gradeLabel='".$gradeLabel."',
		     gradePoints=$gradePoints,
		     gradeSetId=$gradeSetId,
		     instituteId= $instituteId,
             failGrade= '$failGrade',
             gradeStatus= '$gradeStatus'
		$condition";

	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	//return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Grade
//
//-------------------------------------------------------------------------------


    public function editGrade($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $gradeLabel = htmlentities(add_slashes(strtoupper(trim($REQUEST_DATA['gradeLabel']))));
        $failGrade = htmlentities(add_slashes(trim($REQUEST_DATA['failGrade'])));
        $gradeStatus = htmlentities(add_slashes(trim($REQUEST_DATA['gradeStatus'])));
        
        return SystemDatabaseManager::getInstance()->runAutoUpdate('grades', 
        array('gradeLabel','gradePoints','gradeSetId','failGrade','gradeStatus'), 
        array($gradeLabel,$REQUEST_DATA['gradePoints'],$REQUEST_DATA['gradeSetId'],$failGrade,$gradeStatus), 
        "gradeId=$id AND instituteId = $instituteId" );
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Grade Label
//
//-------------------------------------------------------------------------------


    public function getGradeLabel($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        if ($conditions == '') {
			  $conditions .= ' WHERE ';
        }
		  else {
			  $conditions .= ' AND ';
		  }
		  $conditions .= " g.instituteId = $instituteId ";
        $query = "SELECT
                        g.gradeId, g.gradeLabel, g.gradePoints, gs.gradeSetName, g.gradeSetId,
                        IFNULL(g.failGrade,'') AS failGrade, IFNULL(g.gradeStatus,'') AS gradeStatus,
                        IF(IFNULL(gs.gradeSetName,'')='','".NOT_APPLICABLE_STRING."',gs.gradeSetName) AS gradeSetName
                  FROM
                        grades g LEFT JOIN grades_set gs ON (g.gradeSetId = gs.gradeSetId AND gs.instituteId = $instituteId)
                  $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A "Grade" RECORD
//
//-------------------------------------------------------------------------------

    public function deleteGrade($gradeId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "DELETE
                  FROM grades
                  WHERE gradeId=$gradeId AND instituteId = $instituteId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Grade LIST
//-------------------------------------------------------------------------------
    public function getGrade($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        if ($conditions == '') {
			  $conditions .= ' WHERE ';
        }
		  else {
			  $conditions .= ' AND ';
		  }
		  $conditions .= " g.instituteId = $instituteId ";

        $query = "SELECT
                         g.gradeId, g.gradeLabel, g.gradePoints, gs.gradeSetName, g.gradeSetId,
                         IFNULL(g.failGrade,'') AS failGrade, IFNULL(g.gradeStatus,'') AS gradeStatus,
                         IFNULL(gs.gradeSetName,'') AS gradeSetName, IFNULL(g.gradeId,'-1') AS gradeId1
                  FROM
                        grades g LEFT JOIN grades_set gs ON (g.gradeSetId = gs.gradeSetId AND gs.instituteId = $instituteId)
                  $conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Grade LIST
//-------------------------------------------------------------------------------


    public function getGradeList($conditions='', $limit = '', $orderBy=' gradeLabel') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        if ($conditions == '') {
			  $conditions .= ' WHERE ';
        }
		  else {
			  $conditions .= ' AND ';
		  }
		  $conditions .= " g.instituteId = $instituteId ";
        $query = "SELECT
                        g.gradeId, g.gradeLabel, g.gradePoints, gs.gradeSetName, g.gradeSetId,
                        IF(IFNULL(g.failGrade,'')='','".NOT_APPLICABLE_STRING."',g.failGrade) AS failGrade,
                        IF(IFNULL(g.gradeStatus,'')='','".NOT_APPLICABLE_STRING."',g.gradeStatus) AS gradeStatus,
                        IF(IFNULL(gs.gradeSetName,'')='','".NOT_APPLICABLE_STRING."',gs.gradeSetName) AS gradeSetName
                  FROM
                        grades g LEFT JOIN grades_set gs ON (g.gradeSetId = gs.gradeSetId AND gs.instituteId = $instituteId)
                  $conditions
                  ORDER BY $orderBy $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Grade" TABLE
//-------------------------------------------------------------------------------


    public function getTotalGrade($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        if ($conditions == '') {
			  $conditions .= ' WHERE ';
        }
		  else {
			  $conditions .= ' AND ';
		  }
		  $conditions .= " g.instituteId = $instituteId ";

        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
                       grades g LEFT JOIN grades_set gs ON (g.gradeSetId = gs.gradeSetId AND gs.instituteId = $instituteId) $conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "".TOTAL_TRANSFERRED_MARKS_TABLE."" TABLE
//-------------------------------------------------------------------------------
    public function getCheckTotalTransferMarks($conditions='') {

        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." WHERE gradeId = ".$conditions;

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}

?>
