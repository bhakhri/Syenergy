<?php

//-------------------------------------------------------------------------------
//
// HistogramScaleManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
  include_once(DA_PATH ."/SystemDatabaseManager.inc.php");
  
  class HistogramScaleManager {
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
//addHistogramScale() is used to add new record in database.
// Author : Jaineesh
// Created on : 22.10.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addHistogramScale() {
        global $REQUEST_DATA;
		global $sessionHandler;

        return SystemDatabaseManager::getInstance()->runAutoInsert('sc_histogram_scales', array('histogramId', 'histogramRangeFrom', 'histogramRangeTo'), array($REQUEST_DATA['histogramLabel'],$REQUEST_DATA['histogramRangeFrom'],$REQUEST_DATA['histogramRangeTo']));
    }
    
    //-------------------------------------------------------------------------------
//
//editHistogramScale() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 22.10.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editHistogramScale($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('sc_histogram_scales', array('histogramId', 'histogramRangeFrom', 'histogramRangeTo'), array($REQUEST_DATA['histogramLabel'],$REQUEST_DATA['histogramRangeFrom'],$REQUEST_DATA['histogramRangeTo']) , "histogramScaleId=$id" );
    }    
  //-------------------------------------------------------------------------------
//
//deleteHistogramScale() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 22.10.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function deleteHistogramScale($histogramScaleId) {
     
        $query = "DELETE 
        FROM sc_histogram_scales 
        WHERE histogramScaleId=$histogramScaleId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
   
   //-------------------------------------------------------------------------------
//
//getHistogramScale() is used to get the list of data 
//Author : Jaineesh
// Created on : 22.10.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getHistogramScale($conditions='') {
     
   $query = "SELECT 
						histogramScaleId, 
						histogramRangeFrom, 
						histogramRangeTo, 
						histogramId
			FROM		sc_histogram_scales
						$conditions"; 
					
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //-------------------------------------------------------------------------------
//
//getHistogramScaleList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 22.10.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getHistogramScaleList($conditions='', $limit = '', $orderBy='hs.histogramRangeFrom') {
     
      $query = "	
		SELECT 
					hs.histogramScaleId, 
					hs.histogramId, 
					hl.histogramId, 
					hl.histogramLabel, 
					hs.histogramRangeFrom, 
					hs.histogramRangeTo
        FROM		sc_histogram_scales hs, 
					sc_histogram_labels hl 
		WHERE		hs.histogramId=hl.histogramId $conditions
        ORDER BY	$orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
     //-------------------------------------------------------------------------------
//
//getTotalHistogramScale() is used to get total no. of records
//Author : Jaineesh
// Created on : 22.10.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function getTotalHistogramScale($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM sc_histogram_scales hs, sc_histogram_labels hl
		WHERE hs.histogramId=hl.histogramId$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
       
}

// $History: HistogramScaleManager.inc.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/24/08   Time: 4:26p
//Created in $/Leap/Source/Model
//contain all histogram scale data base queries
?>