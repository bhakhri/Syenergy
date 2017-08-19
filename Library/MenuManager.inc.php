<?php
//-------------------------------------------------------
//  This File contains Presentation Logic of Menu
//
//
// Author :Ajinder Singh
// Created on : 18-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
// 
//--------------------------------------------------------


class MenuManager {

	private $menuHeading;
	private $menuItem = array();
	private $menu = '';
	private $menuCode = '';
	private $menuMainHeading = '';
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

	/* function for setting menu heading */
	private function setMenuMainHeading($menuMainHeading) {
		$this->menu = '';
		$this->menuCode = '';
		$this->menuMainHeading = $menuMainHeading;
	}
	
	/* function for setting menu heading */
	private function setMenuHeading($menuHeading) {
		$this->menuHeading = $menuHeading;
	}

	/* function for adding menu item */
	private function addMenuItem($menuHeading, $menuItemsArray = array()) {
		$this->setMenuHeading($menuHeading);
		$this->menuItem = array();
		foreach($menuItemsArray as $menuItem) {
			$this->menuItem[] = $menuItem;
		}
	}

	/* function for making menu item */
	private function addToMenu() {
		global $sessionHandler, $checkAccessArray;
		foreach($this->menuItem as $menuItemArray) {
			if(in_array($sessionHandler->getSessionVariable('RoleId'), $checkAccessArray)) {
				$this->menu .= "<li><a href='" . $menuItemArray[2] . "'>" . $menuItemArray[1] . "</a></li>";
			}
			else {
				$moduleName = $menuItemArray[0];
				if (is_array($sessionHandler->getSessionVariable($moduleName))) {
					$accessValArr = $sessionHandler->getSessionVariable($moduleName);
					if ($accessValArr['view'] or $accessValArr['add'] or $accessValArr['edit'] or $accessValArr['delete']) {
						$this->menu .= "<li><a href='" . $menuItemArray[2] . "'>" . $menuItemArray[1] . "</a></li>";
					}
				}
			}
		}
	}

	/* function for making menu item */
	private function makeSingleMenu($moduleArray) {
		$moduleName = $moduleArray[0];
		$menuLabel = $moduleArray[1];
		$menuLink = $moduleArray[2];
		global $sessionHandler, $checkAccessArray;
        if($sessionHandler->getSessionVariable('RoleId')==5){
            $checkAccessArray = Array('Administrator'=>1);
        }
        else{
            global $checkAccessArray;
        }
		if(in_array($sessionHandler->getSessionVariable('RoleId'), $checkAccessArray)) {
			$this->menuCode .= "<li><a href='" . $menuLink . "'>" . $menuLabel . "</a></li>";
		}
		else {
			if (is_array($sessionHandler->getSessionVariable($moduleName))) {
				$accessValArr = $sessionHandler->getSessionVariable($moduleName);
				if ($accessValArr['view'] or $accessValArr['add'] or $accessValArr['edit'] or $accessValArr['delete']) {
					$this->menuCode .= "<li><a href='" . $menuLink . "'>" . $menuLabel . "</a></li>";
				}
			}
		}
	}

	/* this calls other functions and creates menu */
	private function makeMenu($menuHeading, $menuItemsArray = array()) {
		$this->menu = '';
		$this->addMenuItem($menuHeading, $menuItemsArray);
		$this->addToMenu();
		if (!empty($this->menu)) {
			$this->menuCode .= "<li><a class='img_class' href='javascript:void(0)'>" . $this->menuHeading . "</a>";
			$this->menuCode .= "<ul>" . $this->menu . "</ul>";
			$this->menuCode .= "</li>";
		}
	}

	/* function used by the user. this function creates menu */
	public function showMenu() {
		if (!empty($this->menuCode)) {
			$menu .= "<li><a class='qmparent' href='javascript:void(0)'><font size='2'>" . $this->menuMainHeading . "</font></a><ul>";
			$menu .= $this->menuCode;
			$menu .= "</ul></li>";
			$menu .= "<li><span class='qmdivider qmdividery'></span></li>";
		}
		return $menu;
	}

	private function makeHeadingMenu($moduleArray, $showDivider = true) {
		list($moduleName, $menuLabel,$menuLink) = explode(',',$moduleArray);
		$menu='';

		global $sessionHandler;
		if($sessionHandler->getSessionVariable('RoleId')==5){

			$checkAccessArray = Array('Administrator'=>1);
		}
		else{
		
			global $checkAccessArray;
		}
	 
		if(in_array($sessionHandler->getSessionVariable('RoleId'), $checkAccessArray)) {

			
			$menu = "<li><a class='qmparent' href='" . $menuLink . "'><font size='2'>" . $menuLabel . "</font></a></li>";
			$menu .= "<li><span class='qmdivider qmdividery'></span></li>";
		}
		else {
			if (is_array($sessionHandler->getSessionVariable($moduleName))) {
				$accessValArr = $sessionHandler->getSessionVariable($moduleName);
				if ($accessValArr['view'] or $accessValArr['add'] or $accessValArr['edit'] or $accessValArr['delete']) {
					$menu = "<li><a class='qmparent' href='" . $menuLink . "'><font size='2'>" . $menuLabel . "</font></a></li>";
					$menu .= "<li><span class='qmdivider qmdividery'></span></li>";
				}
			}
		}
		return $menu;
	}

	public function makeThisMenu($menu) {
		foreach($menu as $menuItemArray) {
			if ($menuItemArray[0] == SET_MENU_HEADING) {
				$this->setMenuMainHeading($menuItemArray[1]);
			}
			elseif($menuItemArray[0] == MAKE_SINGLE_MENU) {
				$this->makeSingleMenu($menuItemArray[2]);
			}
			elseif($menuItemArray[0] == MAKE_MENU) {
				$this->makeMenu($menuItemArray[1], $menuItemArray[2]);
			}
		}
	}

	public function makeThisHeadingMenu($menu) {
		$menuItemArray = $menu[0];
		
		return $this->makeHeadingMenu($menuItemArray[1]);
	}

}

//$History: MenuManager.inc.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 6/04/09    Time: 11:01a
//Updated in $/LeapCC/Library
//Added 'Parent, Student, Teacher and Management' Role permission
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 11/05/08   Time: 7:07p
//Updated in $/Leap/Source/Library
//added function makeThisMenu()
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 10/08/08   Time: 4:43p
//Updated in $/Leap/Source/Library
//added function makeHeadingMenu()
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 10/08/08   Time: 3:49p
//Updated in $/Leap/Source/Library
//added function showMenu()
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 10/08/08   Time: 3:40p
//Updated in $/Leap/Source/Library
//added functions for dynamic menus
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 10/07/08   Time: 6:19p
//Updated in $/Leap/Source/Library
//added function makeSingleMenu()
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 10/07/08   Time: 1:43p
//Updated in $/Leap/Source/Library
//started menu access
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/07/08   Time: 11:11a
//Updated in $/Leap/Source/Library
//stopped menu access levels for time being
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 10/03/08   Time: 6:38p
//Created in $/Leap/Source/Library
//File added for making menus dynamically.
//

?>