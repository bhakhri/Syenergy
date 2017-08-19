<?php
class menuCreationManager {
	private static $instance = null;

	private $allMenus = array();

	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

	public function addToAllMenus($allMenuItem) {
		$this->allMenus[] = $allMenuItem;
	}

	public function getAllMenus() {
		return $this->allMenus;
	}

	public function getLastMenu() {
		$currentMenuId = $this->getCurrentMenuId();
		return $this->allMenus[$currentMenuId];
	}

	public function setMenuHeading($menuHeading) {
		$currentMenuId = $this->getCurrentMenuId();
		$this->allMenus[$currentMenuId][] = Array(SET_MENU_HEADING, $menuHeading);
	}

	public function getArray($moduleName, $moduleLabel, $moduleLink, $accessArray = '', $description = '', $helpUrl = '') {
		if (!is_array($accessArray)) {
			$accessArray = Array(ADD,EDIT,VIEW,DELETE);
		}
		return Array($moduleName, $moduleLabel, $moduleLink, $accessArray, $description, $helpUrl);
	}

	public function makeSingleMenu($array = array()) {
		if (!is_array($array)) {
			echo ARRAY_NOT_FOUND_LINE_.__LINE__;
			echo '<pre>';
			print_r($array);
			echo '</pre>';
			die;
		}
		foreach($array as $key => $value) {
			$$key = $value;
		}
		if (!is_array($accessArray)) {
			$accessArray = Array(ADD,EDIT,VIEW,DELETE);
		}
		$currentMenuId = $this->getCurrentMenuId();
		$this->allMenus[$currentMenuId][] = Array(MAKE_SINGLE_MENU, $moduleName, Array($moduleName, $moduleLabel, $moduleLink, $accessArray, $description, $helpUrl));
	}

	public function getCurrentMenuId() {
		return count($this->allMenus)-1;
	}

	public function makeMenu($menuText, $menuItemArray) {
		$currentMenuId = $this->getCurrentMenuId();
		$newArray = array();
		foreach($menuItemArray as $key => $value) {
			$newArray[] = array_values($value);
		}
		$this->allMenus[$currentMenuId][] = Array(MAKE_MENU, $menuText, $newArray);
	}

	public function makeHeadingMenu($array = array()) {
		if (!is_array($array)) {
			echo ARRAY_NOT_FOUND_LINE_.__LINE__;
			echo '<pre>';
			print_r($array);
			echo '</pre>';
			die;
		}
		foreach($array as $key => $value) {
			$$key = $value;
		}
		if (!is_array($accessArray)) {
			$accessArray = Array(ADD,EDIT,VIEW,DELETE);
		}
		$this->addToAllMenus($moduleLabel);
		$currentMenuId = $this->getCurrentMenuId();
		$this->allMenus[$currentMenuId] =  Array(Array(MAKE_HEADING_MENU, "$moduleName, $moduleLabel, $moduleLink", $accessArray, $description, $helpUrl));
	}

};

?>