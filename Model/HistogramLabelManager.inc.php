<?php

//-------------------------------------------------------------------------------
//
// HistogramLabelManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
  include_once(DA_PATH ."/SystemDatabaseManager.inc.php");
  
  class HistogramLabelManager {
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
    
  //-------------------------------------------------------------------------------
//
//addHistogramLabel() is used to add new record in database.
// Author : Jaineesh
// Created on : 22.10.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addHistogramLabel() {
        global $REQUEST_DATA;
		global $sessionHandler;

        return SystemDatabaseManager::getInstance()->runAutoInsert('sc_histogram_labels', array('histogramLabel', 'instituteId', 'sessionId'), array(strtoupper($REQUEST_DATA['histogramLabel']),$sessionHandler->getSessionVariable('InstituteId'),$sessionHandler->getSessionVariable('SessionId')));
    }
    
    //-------------------------------------------------------------------------------
//
//editHistogramLabel() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 22.10.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editHistogramLabel($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('sc_histogram_labels', array('histogramLabel'), array(strtoupper($REQUEST_DATA['histogramLabel'])) , "histogramId=$id" );
    }    
  //-------------------------------------------------------------------------------
//
//deleteHistogramLabel() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 22.10.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function deleteHistogramLabel($histogramId) {
     
        $query = "DELETE 
        FROM sc_histogram_labels 
        WHERE histogramId=$histogramId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
   
   //-------------------------------------------------------------------------------
//
//getHistogramLabel() is used to get the list of data 
//Author : Jaineesh
// Created on : 22.10.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getHistogramLabel($conditions='') {
     
        $query = "SELECT histogramId, histogramLabel 
        FROM sc_histogram_labels $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //-------------------------------------------------------------------------------
//
//getHistogramLabelList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 22.10.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getHistogramLabelList($conditions='', $limit = '', $orderBy='histogramLabel') {
     
        $query = "SELECT histogramId, histogramLabel
        FROM sc_histogram_labels $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
     //-------------------------------------------------------------------------------
//
//getTotalHistogramLabel() is used to get total no. of records
//Author : Jaineesh
// Created on : 22.10.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function getTotalHistogramLabel($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM sc_histogram_labels $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
       
}

// $History: HistogramLabelManager.inc.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/24/08   Time: 4:26p
//Created in $/Leap/Source/Model
//contain all the data base queries
?>