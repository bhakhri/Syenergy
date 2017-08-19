<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class QuestionManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "QuestionManager" CLASS
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "QuestionManager" CLASS
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A CITY
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	public function addQuestion() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('employee_appraisal_master',
         array('appraisalTabId','appraisalTitleId','appraisalProof','appraisalProofId','appraisalText','appraisalWeightage','isActive'),
         array(
                trim($REQUEST_DATA['tabId']),trim($REQUEST_DATA['titleId']),trim($REQUEST_DATA['isProof']),
                trim($REQUEST_DATA['proofId']),trim($REQUEST_DATA['question']),trim($REQUEST_DATA['weightage']),
                trim($REQUEST_DATA['isActive'])
              )
        );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A CITY
// $id:cityId
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function editQuestion($id) {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoUpdate('employee_appraisal_master',
         array('appraisalTabId','appraisalTitleId','appraisalProof','appraisalProofId','appraisalText','appraisalWeightage','isActive'),
         array(
                trim($REQUEST_DATA['tabId']),trim($REQUEST_DATA['titleId']),trim($REQUEST_DATA['isProof']),
                trim($REQUEST_DATA['proofId']),trim($REQUEST_DATA['question']),trim($REQUEST_DATA['weightage']),
                trim($REQUEST_DATA['isActive'])
              ),
        "appraisalId=$id" );
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getQuestion($conditions='') {

        $query = "SELECT
                         *
                  FROM
                         employee_appraisal_master
                  $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS CITYID EXISTS IN INSTITUTE TABLE OR NOT(DELETE CHECK)
// $cityId :cityid of the City
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------
    public function checkInAppraisalData($appraisalId) {

        $query = "SELECT
                        COUNT(*) AS found
                  FROM
                        employee_appraisal_data
                  WHERE
                        appraisalId = $appraisalId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A CITY
// $cityId :cityid of the City
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------
    public function deleteQuestion($appraisalId) {

        $query = "DELETE
                  FROM
                        employee_appraisal_master
                  WHERE
                        appraisalId=$appraisalId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getQuestionList($conditions='', $limit = '', $orderBy='') {

        $query = "SELECT
                        am.appraisalId,
                        am.appraisalProof,
                        am.appraisalText,
                        am.appraisalWeightage,
                        am.isActive,
                        at.appraisalTabName,
                        atl.appraisalTitle,
                        ap.appraisalProofName
                  FROM
                        employee_appraisal_tab `at`,
                        employee_appraisal_title atl,
                        employee_appraisal_master am
                        LEFT JOIN employee_appraisal_proof ap ON ap.appraisalProofId=am.appraisalProofId
                  WHERE
                        at.appraisalTabId=am.appraisalTabId
                        AND atl.appraisalTitleId=am.appraisalTitleId
                        $conditions
                  ORDER BY am.appraisalText
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF CITIES
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------
    public function getTotalQuestion($conditions='') {

        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
                        employee_appraisal_tab `at`,
                        employee_appraisal_title atl,
                        employee_appraisal_master am
                        LEFT JOIN employee_appraisal_proof ap ON ap.appraisalProofId=am.appraisalProofId
                  WHERE
                        at.appraisalTabId=am.appraisalTabId
                        AND atl.appraisalTitleId=am.appraisalTitleId
                        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

}

// $History: QuestionManager.inc.php $
?>