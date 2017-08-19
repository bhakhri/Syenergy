<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Payroll" Module
//
//
// Author :Abhiraj Malhotra
// Created on : 04-April-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class CietManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "PayrollManager" CLASS
//
// Author :Abhiraj Malhotra 
// Created on : 04-April-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

	
	private function __construct() {
	}
	

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "PayrollManager" CLASS
//
// Author :Abhiraj 
// Created on : 04-April-2010
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
	
	public function getNotices($date) {
     
        $query = "select noticeId from notice where '".$date."' between visibleFromDate and visibleToDate";
		logError($query);
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
	
	public function getNoticeStudent($noticeId,$mobile) {
        $countStudent=0;
        $query="select userId from student where studentMobileNo like '%".$mobile."%'";
        logError($query);
        $userId=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        $query = "select * from notice_visible_to_role where noticeId=$noticeId and roleId=4";
        $notices=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        $cnt=count($notices);
        logError("xxxxxxxxxxxx".$userId[0]['userId']);
        if($cnt>0)
        {
            $query1="select * from notice_read where noticeId=".$noticeId." and userId=".$userId[0]['userId'];
            $noticeRead=SystemDatabaseManager::getInstance()->executeQuery($query1,"Query: $query");
            if(count($noticeRead)==0)
            {
                $countStudent++;
            }
        }
        return $countStudent;
    }
	public function getNoticeParent($noticeId,$mobile) {
        $countParent=0;
        $query="select fatherUserId from student where studentMobileNo like '%".$mobile."%'";
        $userId=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        $query = "select * from notice_visible_to_role where noticeId=$noticeId and roleId=3";
        $notices=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        $cnt=count($notices);
        for($i=0;$i<$cnt;$i++)
        {
            $query1="select * from notice_read where noticeId=".$noticeId." and userId=".$userId[0]['fatherUserId'];
            $noticeRead=SystemDatabaseManager::getInstance()->executeQuery($query1,"Query: $query"); 
            if(count($noticeRead)==0)
            {
                $countParent++;
            }
        }
        return $countParent;
    }
     	
}
?>