<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define("AdminID","-1");
define ("ACTIVATE",1);
class SignInManager {
	private static $instance = null;
	
	private function __construct() {
	}
	
	public static function getInstance() {
		if (SignInManager::$instance === null) {
			SignInManager::$instance = new SignInManager();
		}
		return SignInManager::$instance;
	}

	
}
?>