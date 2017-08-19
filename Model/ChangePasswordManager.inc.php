<?php 
//-------------------------------------------------------
//  This File contains Bussiness Logic of the ChangePassword Module
//
//
// Author :Arvind Singh Rawat
// Created on : 09-Sept-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ChangePasswordManager {
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
    public function addNewPassword() {
        global $REQUEST_DATA;
		global $sessionHandler;  
		return SystemDatabaseManager::getInstance()->runAutoUpdate('user', array('userPassword'), array(md5(trim($REQUEST_DATA['userPassword']))), "userName='".$sessionHandler->getSessionVariable('UserName')."'" );  
    }    
    public function getOldPassword($conditions='') {
		global $sessionHandler;
        $query = "SELECT userPassword
        FROM user WHERE userName='".$sessionHandler->getSessionVariable('UserName')."' AND instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
        $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
}
//$History: ChangePasswordManager.inc.php $
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 10-03-27   Time: 12:57p
//Updated in $/LeapCC/Model
//removed roleId from "addNewPassword()" 
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10-03-09   Time: 12:58p
//Updated in $/LeapCC/Model
//Removed roleId from getOldPassword function as now single user can have
//multiple roles
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-08-24   Time: 1:05p
//Updated in $/LeapCC/Model
//Updated with Institute Wise Checks including ACCESS rights DEFINE
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 1  *****************
//User: Arvind       Date: 9/09/08    Time: 6:19p
//Created in $/Leap/Source/Model
//initail chekin
?>