<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class TabManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "TabManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "TabManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
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
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addTab() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('employee_appraisal_tab', 
         array('appraisalTabName','appraisalProofText'), array(trim($REQUEST_DATA['tabName']),trim($REQUEST_DATA['tabProofText'])) 
        );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A CITY
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editTab($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('employee_appraisal_tab', 
         array('appraisalTabName','appraisalProofText'), 
         array(trim($REQUEST_DATA['tabName']),trim($REQUEST_DATA['tabProofText'])), 
        "appraisalTabId=$id" );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getTab($conditions='') {
     
        $query = "SELECT 
                         * 
                  FROM 
                         employee_appraisal_tab
                  $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS CITYID EXISTS IN INSTITUTE TABLE OR NOT(DELETE CHECK)
//
//$cityId :cityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInAppraisalData($tabId) {
     
        $query = "SELECT 
                        COUNT(*) AS found 
                  FROM 
                        employee_appraisal_tab 
                  WHERE 
                        appraisalTabId IN 
                        (
                          SELECT 
                                DISTINCT am.appraisalTabId
                          FROM
                               employee_appraisal_master am,
                               employee_appraisal_data ad
                          WHERE
                                am.appraisalId=ad.appraisalId
                                AND am.appraisalTabId=$tabId
                        )";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A CITY
//
//$cityId :cityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteTab($tabId) {
     
        $query = "DELETE 
                  FROM 
                        employee_appraisal_tab 
                  WHERE 
                        appraisalTabId=$tabId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getTabList($conditions='', $limit = '', $orderBy=' appraisalTabName') {
     
        $query = "SELECT 
                        *
                  FROM 
                        employee_appraisal_tab
                  $conditions 
                  ORDER BY $orderBy 
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF CITIES
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalTab($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        employee_appraisal_tab
                        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}
// $History: TabManager.inc.php $
?>