<?php
/**
 * Defines the interface for the SessionManager class
 *
 */
class SessionManager {
	private static $instance = NULL;
	private $isDebug;		//	true/false if true log messages will be written in log file otherwise not to write 

	private function __construct() {
		session_start();
		$this->isDebug = true;
	}

	private function logError($message) {
		if ($this->isDebug === true) {
			logError($message);
		}
	}


	/**
	 * Returns a reference to the SessionManager object
	 *
	 */

	public static function getInstance() {
		if (SessionManager::$instance === NULL) {
			SessionManager::$instance = new SessionManager();
			SessionManager::$instance->logError("SessionManager Instance Created");
		}
//		var_dump(SessionManager::$instance);
		SessionManager::$instance->logError("SessionManager->getInstance()");
		return SessionManager::$instance;
	}


	/**
	 * 
	 * Create/Update a session variable
	 *
	 * @param $sessionName: Name of the session variable
	 * @param $sessionValue: value for the session variable
	 *
	 */
	public function setSessionVariable($sessionName, $sessionValue) {
		$this->logError("SessionManager->setSessionVariable() == " . $sessionName);
		$_SESSION[$sessionName] = $sessionValue;
	}

	/**
	 * 
	 * Returns the value of session variable given in $sessionName
	 *
	 * @param $sessionName: Name of the session variable
	 *
	 */
	public function getSessionVariable($sessionName) {
		$this->logError("SessionManager->getSessionValue() == " . $sessionName);
		if (isset($_SESSION[$sessionName])) {
			return $_SESSION[$sessionName];
		}
		else {
			return "";
		}
	}

	/**
	 * 
	 * Returns the true if session variable exists with any value other than blank else return false
	 *
	 * @param $sessionName: Name of the session variable
	 *
	 */
	public function isSessionAlive($sessionName) { 
		$this->logError("SessionManager->isSessionAlive() == " . $sessionName);
		if (isset($_SESSION[$sessionName]) && $_SESSION[$sessionName] != "") {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * 
	 * Unset a session variable and returns true
	 *
	 * @param $sessionName: Name of the session variable
	 *
	 */
	public function unsetSessionVariable($sessionName) {
		$this->logError("SessionManager->unsetSessionVariable() == " . $sessionName);
		if (isset($_SESSION[$sessionName]) && $_SESSION[$sessionName] != "") {
			unset($_SESSION[$sessionName]);
			return true;
		}
	}

	/**
	 * 
	 * Destroy session and returns true
	 *
	 * @param : NA
	 *
	 */
	public function destroySession() { 
		$this->logError("SessionManager->destroySession()");
		session_destroy();
		return true;
	}

	/**
	 * 
	 * Get session Id
	 *
	 * @param : NA
	 *
	 */
	public function getSessionId() {
		$this->logError("SessionManager->getSessionId()");
		return session_id();
	}
	
	public function printSession(){
		print"<pre>";
		print_r($_SESSION);
	}
}
?>