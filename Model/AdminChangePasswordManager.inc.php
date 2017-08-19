<?php 
//-------------------------------------------------------
//  This File contains Bussiness Logic of the ChangePassword Module
//
// Author :Rajeev Aggarwal
// Created on : 22-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
		return SystemDatabaseManager::getInstance()->runAutoUpdate('user', array('userPassword'), array(md5(trim($REQUEST_DATA['userPassword']))), "userName='".$sessionHandler->getSessionVariable('UserName')."' AND roleId='".$sessionHandler->getSessionVariable('RoleId')."'" );  
    }    
    public function getOldPassword($conditions='') {
		global $sessionHandler;
        $query = "SELECT userPassword
        FROM user WHERE userName='".$sessionHandler->getSessionVariable('UserName')."' AND UserId='".$sessionHandler->getSessionVariable('UserId')."'
        $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
}
//$History: AdminChangePasswordManager.inc.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/22/08   Time: 5:40p
//Created in $/LeapCC/Model
//Intial Checkin
?>