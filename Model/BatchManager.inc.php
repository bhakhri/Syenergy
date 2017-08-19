<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Batch" Module
//
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class BatchManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BatchManager" CLASS
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


	private function __construct() {
	}


//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BatchManager" CLASS
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
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

// THIS FUNCTION IS USED FOR ADDING A Batch
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------




	public function addBatch() {
		global $REQUEST_DATA;
		global $sessionHandler;
		$batchYear = date('Y', strtotime($REQUEST_DATA['startDate']));
		return SystemDatabaseManager::getInstance()->runAutoInsert('batch', array('batchName','startDate','endDate','batchYear','instituteId'), array($REQUEST_DATA['batchName'],$REQUEST_DATA['startDate'],$REQUEST_DATA['endDate'],$batchYear,$sessionHandler->getSessionVariable('InstituteId')) );
	}

// THIS FUNCTION IS USED FOR EDITING A Batch
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


    public function editBatch($id) {
        global $REQUEST_DATA;
     	global $sessionHandler;
		$batchYear = date('Y', strtotime($REQUEST_DATA['startDate']));
        return SystemDatabaseManager::getInstance()->runAutoUpdate('batch', array('batchName','startDate','endDate','batchYear','instituteId'), array($REQUEST_DATA['batchName'],$REQUEST_DATA['startDate'],$REQUEST_DATA['endDate'],$batchYear,$sessionHandler->getSessionVariable('InstituteId')), "batchId=$id" );
    }

// THIS FUNCTION IS USED FOR GETTING Batch LIST
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


   public function getBatch($conditions='') {
        global $sessionHandler;
     //    $query =  "SELECT batchName,batchStartDate,batchEndDate from batch";
        $query =  "SELECT bat.batchId, bat.batchName, (
                                SELECT 
                                COUNT(stu.studentId) 
                                FROM  student stu, class cls 
                                WHERE cls.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
                                AND cls.batchId = bat.batchId 
                                AND stu.classId = cls.classId
                                )  
                                AS studentCount,bat.startDate,bat.endDate
                                FROM batch bat   
                                $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


// THIS FUNCTION IS USED FOR DELETING A "Batch" RECORD
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function deleteBatch($batchId) {

        $query = "DELETE
        FROM batch
        WHERE batchId=$batchId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

// THIS FUNCTION IS USED FOR GETTING Batch LIST
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


    public function getBatchList($conditions='', $limit = '', $orderBy=' bat.batchName') {
       global $sessionHandler;
       /*
        $query = "SELECT bat.batchId, bat.batchName,bat.startDate,bat.endDate, bat.instituteId,bat.batchYear
        FROM batch bat
        WHERE
		bat.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
		$conditions
        ORDER BY $orderBy $limit";
       //    AND c.classId=c.batchId                  */


       $query = "SELECT bat.batchId, bat.batchName, (select count(s.studentId) from student s, class c where s.classId  = c.classId and c.batchId = bat.batchId) AS studentId, bat.startDate, bat.endDate,bat.batchYear
                    FROM batch bat
                    WHERE bat.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                    GROUP BY bat.batchId HAVING 1=1 $conditions
                    ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

    }

// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Batch" TABLE
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


    public function getTotalBatch($conditions='') {

       global $sessionHandler;
        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
                        (SELECT bat.batchId, bat.batchName, (select count(s.studentId) from student s, class c where s.classId  = c.classId and c.batchId = bat.batchId) AS studentId, bat.startDate, bat.endDate,bat.batchYear
                    FROM batch bat
                    WHERE bat.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                    $conditions
                    GROUP BY bat.batchId) as t";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function checkClassBatch($batchId) {

        $query = "SELECT
                         COUNT(*) AS cnt
                  FROM
                        `class`
                  WHERE
                         batchId=$batchId ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

}
?>