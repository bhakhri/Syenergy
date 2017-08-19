<?php 
/**
 * The DirectoryManager.inc.php file contains definitions that relate to the folder structure of the application
 * 
 */

/**
 * Defines an absolute path to the 'storage' folder in the application
 */


/**
 *
 * @author Pushpender Kumar Kumar
 */
class DirectoryManager {
	
	public static $USER_GROUP_PREVILAGE = 0777;
	
	public static function getLocalStorageDir() {
		return LOCAL_STORAGE_DIR;
	}
	
	private function __construct() {}

	public static function createFolderIfNotExists($folder) {
		if (!file_exists($folder)) {
			mkdir($folder, DirectoryManager::$USER_GROUP_PREVILAGE);
		}
	}
}

?>