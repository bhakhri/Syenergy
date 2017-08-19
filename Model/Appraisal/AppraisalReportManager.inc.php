<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class AppraisalReportManager {
	private static $instance = null;
	
   
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "AppraisalReportManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
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
    public function showAppraisalReport($conditions='') {
        global $sessionHandler;
        $sesionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                        ead.employeeId,
                        e.employeeName, 
                        eat.appraisalTabName,
                        SUM(ead.selfEvaluation) AS self, 
                        SUM(ead.hodEvaluation) AS hod,
                        IFNULL(d.abbr,'".NOT_APPLICABLE_STRING."') AS abbr
                   FROM 
                        employee_appraisal_data ead,
                        employee_appraisal_tab eat,
                        employee_appraisal_master eam,
                        employee e
                        LEFT JOIN department d ON d.departmentId=e.departmentId
                   WHERE
                        e.employeeId=ead.employeeId
                        AND ead.appraisalId=eam.appraisalId
                        AND eam.appraisalTabId=eat.appraisalTabId
                        AND ead.sessionId=$sesionId
                        AND e.instituteId=$instituteId
                        $conditions
                   GROUP BY  ead.employeeId, eat.appraisalTabId 
                        ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     public function showReviewerReport($conditions='') {
        global $sessionHandler;
        $sesionId=$sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT 
                               e.employeeId,
                               IFNULL(e.grandtotal,0) AS rev 
                        FROM
                               employee_appraisal_reviewer e
                        WHERE
                               e.sessionId=$sesionId
                               $conditions
                        ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    
public function getAppraisalTabs($conditions='') {
        global $sessionHandler;
        $sesionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                        DISTINCT appraisalTabId,appraisalTabName
                   FROM 
                        employee_appraisal_tab eat
                        $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
  
}
?>