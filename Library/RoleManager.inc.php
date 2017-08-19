<?php
//-------------------------------------------------------
//  This File contains Presentation Logic of Menu
//
//
// Author :Ajinder Singh
// Created on : 18-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
class RoleManager {
	private $menuHeading;
	private $menuItem = array();
	private $menu = '';
	private static $instance;

	private function __construct() {
	}
	
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

	/* function is made for checking if requested file has been given any access or not */
	public function hasRoleAccess() {
        global $sessionHandler;
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        $homePageFile = 'indexHome.php';
        if($roleId==2){
		 $homePageFile = 'index.php';
        }
        if($roleId==3){
         $homePageFile = 'index.php';
        }
        if($roleId==4){
         $homePageFile = 'index.php';
        }
		$currentFile = $_SERVER['SCRIPT_FILENAME'];
		if (!stristr($currentFile,$homePageFile)) {
			if (!defined('MODULE') or !defined('ACCESS')) {
				return false;
			}
		}
		return true;
	}

	/* function is made for checking if requested file has been given any defined access or not */
	public function hasFileAccess($internalFile = false) {
		global $sessionHandler;
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        $homePageFile = 'indexHome.php';
        if($roleId==2){
         $homePageFile = 'index.php';
        }
        if($roleId==3){
         $homePageFile = 'index.php';
        }
        if($roleId==4){
         $homePageFile = 'index.php';
        }
		$currentFile = $_SERVER['SCRIPT_FILENAME'];
		if (!stristr($currentFile,$homePageFile)) {
			if (!defined('MODULE') or !defined('ACCESS')) {
				return 0;
			}
			elseif (MODULE == 'COMMON') {
				return 1;
			}
			else {
				if(is_array($sessionHandler->getSessionVariable(MODULE))) {
					$moduleName = $sessionHandler->getSessionVariable(MODULE);
					if (ACCESS == 'view') {
						if($moduleName['add'] == 1) {
							return 1;
						}
						elseif ($moduleName['edit'] == 1) {
							return 1;
						}
						elseif ($moduleName['delete'] == 1) {
							return 1;
						}
						elseif ($moduleName['view'] == 1) {
							return 1;
						}
						else {
							return 0;
						}
					}
					else {
						if ($internalFile == false) {
							if ($moduleName[ACCESS] == 1 or $moduleName['view'] == 1) {
								return 1;
							}
							else {
								return 0;
							}
						}
						else {
							return $moduleName[ACCESS];
						}
					}
				}
				else {
					return 0;
				}
			}
		}
		return 1;
	}

	
}

//$History: RoleManager.inc.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 6/13/09    Time: 4:05p
//Updated in $/LeapCC/Library
//made $internalFile = false in hasFileAccess funtion
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 6/08/09    Time: 5:16p
//Updated in $/LeapCC/Library
//modified function hasFileAccess()
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 10/08/08   Time: 4:10p
//Created in $/Leap/Source/Library
//file added for role level access
//

?>
