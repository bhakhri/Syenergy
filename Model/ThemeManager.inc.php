<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "user_prefs" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (17.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ThemeManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ThemeManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ThemeManager" CLASS
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
    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR changing theme of a user
//$themeId:themeId
//$userId=userId
// Author :Dipanjan Bhattacharjee 
// Created on : (17.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function changeTheme($themeId,$userId) {
        return SystemDatabaseManager::getInstance()->runAutoUpdate('user_prefs', 
         array('themeId'),
         array($themeId), 
         "userId=$userId" 
        );
    }   
    
	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO CHECK USER PREF RECORD EXISTS OR NOT
	//
	// Author :Rajeev Aggarwal
	// Created on : (11.08.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------   	
	public function checkUserPref($userId) {
    
        $query = "SELECT COUNT(*) AS userExists FROM user_prefs  WHERE userId= '$userId'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO INSERT USER PREF
	//
	// Author :Rajeev Aggarwal
	// Created on : (11.08.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------   	
	public function insertUserPref($userId) {

		global $REQUEST_DATA;
		return SystemDatabaseManager::getInstance()->runAutoInsert('user_prefs',array('userId'),array($userId));
	}
    
//------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR changing grouping facility for a user
//$grouping:grouping
//$userId=userId
// Author :Dipanjan Bhattacharjee 
// Created on : (16.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------        
    public function changeGrouping($grouping,$userId) {
        return SystemDatabaseManager::getInstance()->runAutoUpdate('user_prefs', 
         array('grouping'),
         array($grouping), 
         "userId=$userId" 
        );
    }
    

//----------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR changing dashboard layout for a user(Teacher Role)
// $dashboardLayout:$dashboardLayout
// $userId=userId
// Author :Dipanjan Bhattacharjee 
// Created on : (08.07.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------        
    public function changeDashBoardLayout($dashboardLayout,$userId) {
        return SystemDatabaseManager::getInstance()->runAutoUpdate('user_prefs', 
         array('dashboardLayout'),
         array($dashboardLayout), 
         "userId=$userId" 
        );
    }    
   
  
}
// $History: ThemeManager.inc.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/11/09    Time: 2:52p
//Updated in $/LeapCC/Model
//Updated user prefs logic to insert record in user_prefs if respective
//user doesnot exists
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 6/08/09    Time: 3:09p
//Created in $/LeapCC/Model
//file added for themes.
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 17/12/08   Time: 13:52
//Created in $/Leap/Source/Model
//Theme change done
?>
