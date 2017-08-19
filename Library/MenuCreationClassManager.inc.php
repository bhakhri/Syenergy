 <?php
 class MenuCreationClassManager {
	private static $instance = null;

	private $allMenus = array();
	private $moduleNameHelpUrlLink = array();
	private $allMenuNameLabelArray = array();

	private function __construct() {
	}
	public static function getInstance() {
		$class = __CLASS__;
		return new $class;
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
		if ($this->allMenuNameLabelArray[$moduleName] != '') {
			echo 'Module Name: '.$moduleName.' ALREADY EXISTS';
			die;
		}
		else if (in_array($moduleLabel, $this->allMenuNameLabelArray)) {
			echo 'Module Label: '.$moduleLabel.' ALREADY EXISTS';
			die;
		}
		else {
			$this->allMenuNameLabelArray[$moduleName] = $moduleLabel;
		}
		$this->moduleNameHelpUrlLink[$moduleName] = $helpUrl;
		$this->moduleNameVideoUrlLink[$moduleName] = $videoHelpUrl;
		$this->moduleNameShowHelpBar[$moduleName] = $showHelpBar;
		$this->moduleNameShowSearch[$moduleName] = $showSearch;
		$this->moduleLinkedArray[$moduleName] = $linkedModulesArray;
		$this->moduleLink[$moduleName] = $moduleLink;
		$this->moduleLabel[$moduleName] = $moduleLabel;
		return Array($moduleName, $moduleLabel, $moduleLink, $accessArray, $description, $helpUrl);
	}

	public function makeThisArray($moduleName) {
		$this->moduleNameHelpUrlLink[$moduleName] = $helpUrl;
		$this->moduleNameVideoUrlLink[$moduleName] = $videoHelpUrl;
		$this->moduleNameShowHelpBar[$moduleName] = $showHelpBar;
		$this->moduleNameShowSearch[$moduleName] = $showSearch;
		$this->moduleLinkedArray[$moduleName] = $linkedModulesArray;
		$this->moduleLink[$moduleName] = $moduleLink;
		$this->moduleLabel[$moduleName] = $moduleLabel;
	}

	public function makeSingleMenu($array = array()) {
		if (!is_array($array)) {
			echo ARRAY_NOT_FOUND_LINE_.__LINE__;
		}
		foreach($array as $key => $value) {
			$$key = $value;
		}
		if (!is_array($accessArray)) {
			$accessArray = Array(ADD,EDIT,VIEW,DELETE);
		}
		if ($this->allMenuNameLabelArray[$moduleName] != '') {
			echo 'Module Name: '.$moduleName.' ALREADY EXISTS';
			die;
		}
		else if (in_array($moduleLabel, $this->allMenuNameLabelArray)) {
			echo 'Module Label: '.$moduleLabel.' ALREADY EXISTS';
			die;
		}
		else {
			$this->allMenuNameLabelArray[$moduleName] = $moduleLabel;
		}
		$currentMenuId = $this->getCurrentMenuId();
		$this->moduleNameHelpUrlLink[$moduleName] = $helpUrl;
		$this->moduleNameVideoUrlLink[$moduleName] = $videoHelpUrl;
		$this->moduleNameShowHelpBar[$moduleName] = $showHelpBar;
		$this->moduleNameShowSearch[$moduleName] = $showSearch;
		$this->moduleLinkedArray[$moduleName] = $linkedModulesArray;
		$this->moduleLink[$moduleName] = $moduleLink;
		$this->moduleLabel[$moduleName] = $moduleLabel;
		$this->allMenus[$currentMenuId][] = Array(MAKE_SINGLE_MENU, $moduleName, Array($moduleName, $moduleLabel, $moduleLink, $accessArray, $description, $helpUrl));
	}

	public function getCurrentMenuId() {
		return count($this->allMenus)-1;
	}

	public function makeMenu($menuText, $menuItemArray) {
		$currentMenuId = $this->getCurrentMenuId();
		$newArray = array();
		foreach($menuItemArray as $record) {
			foreach($record as $key => $value) {
				$$key = $value;
			}
			if ($this->allMenuNameLabelArray[$moduleName] != '') {
				echo 'Module Name: '.$moduleName.' ALREADY EXISTS';
				die;
			}
			else if (in_array($moduleLabel, $this->allMenuNameLabelArray)) {
				echo 'Module Label: '.$moduleLabel.' ALREADY EXISTS';
				die;
			}
			else {
				$this->allMenuNameLabelArray[$moduleName] = $moduleLabel;
			}
			$this->moduleNameHelpUrlLink[$moduleName] = $helpUrl;
			$this->moduleNameVideoUrlLink[$moduleName] = $videoHelpUrl;
			$this->moduleNameShowHelpBar[$moduleName] = $showHelpBar;
			$this->moduleNameShowSearch[$moduleName] = $showSearch;
			$this->moduleLinkedArray[$moduleName] = $linkedModulesArray;
			$this->moduleLink[$moduleName] = $moduleLink;
			$this->moduleLabel[$moduleName] = $moduleLabel;
		}
		foreach($menuItemArray as $key => $value) {
			$newArray[] = array_values($value);
		}

		$this->allMenus[$currentMenuId][] = Array(MAKE_MENU, $menuText, $newArray);
	}

	public function makeHeadingMenu($array = array()) {
		if (!is_array($array)) {
			echo ARRAY_NOT_FOUND_LINE_.__LINE__;
		}
		foreach($array as $key => $value) {
			$$key = $value;
		}
		$this->moduleNameHelpUrlLink[$moduleName] = $helpUrl;
		$this->moduleNameVideoUrlLink[$moduleName] = $videoHelpUrl;
		$this->moduleNameShowHelpBar[$moduleName] = $showHelpBar;
		$this->moduleNameShowSearch[$moduleName] = $showSearch;
		$this->moduleLinkedArray[$moduleName] = $linkedModulesArray;
		$this->moduleLink[$moduleName] = $moduleLink;
		$this->moduleLabel[$moduleName] = $moduleLabel;
		if (!is_array($accessArray)) {
			$accessArray = Array(ADD,EDIT,VIEW,DELETE);
		}
		if ($this->allMenuNameLabelArray[$moduleName] != '') {
			echo 'Module Name: '.$moduleName.' ALREADY EXISTS';
			die;
		}
		else if (in_array($moduleLabel, $this->allMenuNameLabelArray)) {
			echo 'Module Label: '.$moduleLabel.' ALREADY EXISTS';
			die;
		}
		else {
			$this->allMenuNameLabelArray[$moduleName] = $moduleLabel;
		}
		$this->addToAllMenus($moduleLabel);
		$currentMenuId = $this->getCurrentMenuId();
		$this->allMenus[$currentMenuId] =  Array(Array(MAKE_HEADING_MENU, "$moduleName, $moduleLabel, $moduleLink", $accessArray, $description, $helpUrl));
	}

	public function getHelpUrl($moduleName) {
		return $this->moduleNameHelpUrlLink[$moduleName];
	}
	public function getVideoHelpUrl($moduleName) {
		return $this->moduleNameVideoUrlLink[$moduleName];
	}
	public function showHelpBar($moduleName) {
		return $this->moduleNameShowHelpBar[$moduleName];
	}
	public function showSearch($moduleName) {
		return $this->moduleNameShowSearch[$moduleName];
	}

	public function showLinkedModules($moduleName) {
		return $this->moduleLinkedArray[$moduleName];
	}

	public function getModuleLink($moduleName) {
		return $this->moduleLink[$moduleName];
	}
	public function getModuleLabel($moduleName) {
		return $this->moduleLabel[$moduleName];
	}

};

?>