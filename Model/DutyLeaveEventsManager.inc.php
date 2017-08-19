<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class DutyLeaveEventsManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "DutyLeaveEventsManager" CLASS
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "DutyLeaveEventsManager" CLASS
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
	public function addEvent() {
		global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

		return SystemDatabaseManager::getInstance()->runAutoInsert('duty_event',
         array('instituteId','sessionId','eventTitle','startDate','endDate','timeTableLabelId'),
         array($instituteId,$sessionId,trim($REQUEST_DATA['eventTitle']),trim($REQUEST_DATA['startDate']),trim($REQUEST_DATA['endDate']),trim($REQUEST_DATA['labelId']))
        );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A CITY
// $id:cityId
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function editEvent($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

        return SystemDatabaseManager::getInstance()->runAutoUpdate('duty_event',
          array('instituteId','sessionId','eventTitle','startDate','endDate','timeTableLabelId'),
          array($instituteId,$sessionId,trim($REQUEST_DATA['eventTitle']),trim($REQUEST_DATA['startDate']),trim($REQUEST_DATA['endDate']),trim($REQUEST_DATA['labelId'])),
        "eventId=$id" );
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getEvent($conditions='') {

        $query = "SELECT
                        *
                  FROM
                        duty_event
                  $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS CITYID EXISTS IN INSTITUTE TABLE OR NOT(DELETE CHECK)
// $cityId :cityid of the City
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------
    public function checkDutyLeave($eventId) {

        $query = "SELECT
                        COUNT(*) AS found
                  FROM
                        ".DUTY_LEAVE_TABLE." 
                  WHERE
                        eventId=$eventId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A CITY
// $cityId :cityid of the City
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------
    public function deleteEvent($eventId) {

        $query = "DELETE
                  FROM
                        duty_event
                  WHERE
                        eventId=$eventId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getEventList($conditions='', $limit = '', $orderBy=' eventTitle') {
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        d.*,
                        t.labelName
                  FROM
                        duty_event d,
                        time_table_labels t
                  WHERE
                        d.sessionId=$sessionId
                        AND d.instituteId=$instituteId
                        AND d.timeTableLabelId=t.timeTableLabelId
                        $conditions
                  ORDER BY $orderBy
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF CITIES
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------
    public function getTotalEvent($conditions='') {

        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
                        duty_event d,
                        time_table_labels t
                  WHERE
                        d.sessionId=$sessionId
                        AND d.instituteId=$instituteId
                        AND d.timeTableLabelId=t.timeTableLabelId
                        $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


}
//$History: DutyLeaveEventsManager.inc.php $
?>