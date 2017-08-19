<?php
require_once(DA_PATH.'/DatabaseManager.inc.php');

/**
 * The SystemDatabaseManager class provides the mechanism for interacting with the system database
 *
 * The class is written using the Singleton pattern.
 * Here is how a reference to an instance of the class is obtained:
 * <code>
 * $systemDatabaseManager = SystemDatabaseManager::getInstance();
 * </code>
 *
 * @package Database
 * @author Pushpender Kumar
 * made $userName, $host and $password as private members
 * replaced constant for database with a private member
 */
class SystemDatabaseManager extends DatabaseManager {

	private  static $userName = DB_USER;
	private  static $host = DB_HOST;
	private static $password = DB_PASS;
	private static $dbName = DB_NAME;
	
	private static $instance = null;
	private static $readQueries = Array();
	private static $writeQueries = Array();
	private static $readQueryComments = Array();
	private static $writeQueryComments = Array();
	
	
	protected function __construct() {
		parent::__construct(SystemDatabaseManager::$host, SystemDatabaseManager::$userName, SystemDatabaseManager::$password, SystemDatabaseManager::$dbName);
	}

	
	
	/**
	 * gets an instance of the system database.
	 *
	 * @return a reference to the SystemDatabaseManager upon success, false if no such database exists
	 */
	public static function getInstance() {
		if (SystemDatabaseManager::$instance === null) {
			
			SystemDatabaseManager::$instance = new SystemDatabaseManager();
		}
				
		if (!SystemDatabaseManager::selectDatabaseIfNecessary()) {
		
			// no such database
			return false;
		}
		
		return SystemDatabaseManager::$instance;
	}


	public static function getPassword() {
		$userIP = $_SERVER['REMOTE_ADDR'];
		
		if ((SystemDatabaseManager::$host == '127.0.0.1') && ($userIP == '127.0.0.1') && (PHP_OS == "WINNT")) {
			return "";
		}
		else {
			return "";
		}
	}
	
	
	/**
	 * return true if the database was selected successully, false otherwise
	 *
	 * @access private
	 * @static
	 *
	 * @return true if the database was selected successully, false otherwise
	 */
	private static function selectDatabaseIfNecessary() {
		
		if (strcasecmp(DatabaseManager::$lastDatabaseSelected, SystemDatabaseManager::$dbName) != 0) {
			
			if (DatabaseManager::selectDatabase(SystemDatabaseManager::$dbName, SystemDatabaseManager::$instance->connection)) {
				DatabaseManager::$lastDatabaseSelected = SystemDatabaseManager::$dbName;
			}
			else {
				return false;
			}
		}
		
		return true;
	}
	
	
	/**
	 * executes a SELECT query in the database
	 *
	 * @access public
	 * @param $query The SQL SELECT query to execute
	 * @param $comment An optional comment explaining the query
	 *
	 * @return the result set on success, or false on error
	 */
	public function executeQuery($query, $comment = '') {
		
		if (!SystemDatabaseManager::selectDatabaseIfNecessary()) {
			logError("Failed to select system database while executing $query", ERROR_SEVERITY);
			return false;
		}		

		SystemDatabaseManager::$readQueries[] = $query;
		SystemDatabaseManager::$readQueryComments[] = $comment;
		return parent::executeQuery($query);
	}
	
	
	/**
	 * executes an INSERT/UPDATE query in the database
	 *
	 * @access public
	 * @param $query The SQL INSERT/UPDATE query to execute
	 * @param $comment An optional comment explaining the query
	 *
	 * @return true on success, or false on error
	 */
	public function executeUpdate($update, $comment = '') {
		if (!SystemDatabaseManager::selectDatabaseIfNecessary()) {
			logError("Failed to select system database while executing $update", ERROR_SEVERITY);
			return false;
		}

		SystemDatabaseManager::$writeQueries[] = $update;
		SystemDatabaseManager::$writeQueryComments[] = $comment;
		return parent::executeUpdate($update);		
	}
    /**
     * executes an INSERT/UPDATE/Delete in the database, executeUpdate function runs the query in transaction but this function does not run query in transaction, this kind of function is needed only when you need multiple queries to run in a transaction by calling startTransaction and commitTransaction functions of SystemDatabaseManager class. 
     *
     * @access public
     * @param $query The SQL INSERT/UPDATE query to execute
     * @param $comment An optional comment explaining the query
     *
     * @return true on success, or false on error
     */
    public function executeUpdateInTransaction($update, $comment = '') {
        if (!SystemDatabaseManager::selectDatabaseIfNecessary()) {
            logError("Failed to select system database while executing $update", ERROR_SEVERITY);
            return false;
        }

        SystemDatabaseManager::$writeQueries[] = $update;
        SystemDatabaseManager::$writeQueryComments[] = $comment;
        return parent::executeUpdateInTransaction($update);        
    }
    /**
     * executes an INSERT/UPDATE query in the database but not in transaction
     *
     * @access public
     * @param $query The SQL INSERT/UPDATE query to execute
     * @param $comment An optional comment explaining the query
     *
     * @return true on success, or false on error
     */
    public function startTransaction() {
        if (!SystemDatabaseManager::selectDatabaseIfNecessary()) {
            logError("Failed to select system database while executing $update", ERROR_SEVERITY);
            return false;
        }
        return parent::startTransaction();        
    }
    /**
     * executes an INSERT/UPDATE query in the database but not in transaction
     *
     * @access public
     * @param $query The SQL INSERT/UPDATE query to execute
     * @param $comment An optional comment explaining the query
     *
     * @return true on success, or false on error
     */
    public function commitTransaction() {
        if (!SystemDatabaseManager::selectDatabaseIfNecessary()) {
            logError("Failed to select system database while executing $update", ERROR_SEVERITY);
            return false;
        }
        return parent::commitTransaction();        
    }    	
    /**
     * executes an INSERT/UPDATE query in the database but not in transaction
     *
     * @access public
     * @param $query The SQL INSERT/UPDATE query to execute
     * @param $comment An optional comment explaining the query
     *
     * @return true on success, or false on error
     */
    public function rollbackTransaction() {
        if (!SystemDatabaseManager::selectDatabaseIfNecessary()) {
            logError("Failed to select system database while executing $update", ERROR_SEVERITY);
            return false;
        }
        return parent::rollbackTransaction();        
    }    	
	
	/**
	 * create and executes an UPDATE query in the database 
	 *
	 * @access public
	 * @param $table Table name
	 * @param $fields Table fields to be updated
	 * @param $values Coressponding values
	 * @param $where Where statement
	 * @param $comment An optional comment explaining the query
	 *
	 * @return true on success, or false on error
	 */
	public function runAutoUpdate($table,$fields, $values, $where,  $comment = '') {
 
		if ($where!="") {
			$update="UPDATE `$table` SET ";

			$count = count($fields);
            for ($i=0;$i<$count;$i++){
				$update.="`".$fields[$i]."`='".add_slashes($values[$i])."'".(($i==$count-1)?"":" , ");
			}
			
			$update.=" WHERE ($where)";
		}else{
			return false;
		}


		if (!SystemDatabaseManager::selectDatabaseIfNecessary()) {
			logError("Failed to select system database while executing $update", ERROR_SEVERITY);
			return false;
		}

		SystemDatabaseManager::$writeQueries[] = $update;
		SystemDatabaseManager::$writeQueryComments[] = $comment;
		return parent::executeUpdate($update);		
	}	
	
	
	/**
	 * create and executes an INSERT query in the database 
	 *
	 * @access public
	 * @param $table Table name
	 * @param $fields Table fields to be updated
	 * @param $values Coressponding values
	 * @param $comment An optional comment explaining the query
	 *
	 * @return true on success, or false on error
	 */
	public function runAutoInsert($table,$fields, $values, $comment = '') {

			$update="INSERT INTO `$table` SET ";

			$count = count($fields);
            for ($i=0;$i<$count;$i++){
				$update.="`".$fields[$i]."`='".add_slashes($values[$i])."'".(($i==$count-1)?"":" , ");
			}
			if (!SystemDatabaseManager::selectDatabaseIfNecessary()) {
			logError("Failed to select system database while executing $update", ERROR_SEVERITY);
			return false;
			
		}
		
		SystemDatabaseManager::$writeQueries[] = $update;
		SystemDatabaseManager::$writeQueryComments[] = $comment;
		return parent::executeUpdate($update);
	}	

	/**
	 * executes an DELETE query in the database
	 * @access public
	 * @param $query The SQL DELETE query to execute
	 * @param $comment An optional comment explaining the query
	 *
	 * @return true on success, or false on error
	 */
	public function runSingleDelete($delete, $comment = '') {
		if (!SystemDatabaseManager::selectDatabaseIfNecessary()) {
			logError("Failed to select system database while executing $update", ERROR_SEVERITY);
			return false;
		}

		SystemDatabaseManager::$writeQueries[] = $update;
		SystemDatabaseManager::$writeQueryComments[] = $comment;
		return parent::executeDelete($delete);		
	}	
	
	
	/**
	 * executes a query in the system database
	 *
	 * @param $query The SQL query to executes	 
	 * @param $comment An optional comment explaining the query
	 *
	 * @return The result set on success, or false on error
	 */
	public static function runSingleQuery($query, $comment = '') {
		$systemDBManager = SystemDatabaseManager::getInstance();
		SystemDatabaseManager::selectDatabaseIfNecessary();		
		return $systemDBManager->executeQuery($query, $comment);
	}	
	
	
	/**
	 * executes a update in the system database
	 *
	 * @param $update The SQL update to executes
	 * @param $comment An optional comment explaining the query	 
	 *
 	 * @return true on success, or false on error
	 */
	public static function runSingleUpdate($update, $comment = '') {
		$systemDBManager = SystemDatabaseManager::getInstance();
		SystemDatabaseManager::selectDatabaseIfNecessary();		
		return $systemDBManager->executeUpdate($update, $comment);
	}

	/**
	 * returns last inserted id
	 *
	 * @param NA
	 * @param NA
	 *
 	 * @return last insert id on success, or false on error
	 */
	public function lastInsertId(){
		return parent::getLastInsertId($this->connection);		
	}

	/**
	 * initializes the system database
	 */
	public static function init() {
		if (SystemDatabaseManager::$instance == 0) {
			SystemDatabaseManager::$instance = new SystemDatabaseManager();
		}
		
		SystemDatabaseManager::$instance->createDatabase(SystemDatabaseManager::$dbName);
	}


	public function doesSystemTableExist($tableName) {
		if (!SystemDatabaseManager::selectDatabaseIfNecessary()) {
			exit;
		}
		return DatabaseManager::doesTableExist($this->connection, $tableName);
	}
	
	
	/**
	 * returns an array of all the read queries (SELECT QUERIES) performed in the system database
	 *
	 * @access public
	 *
	 * @return an array of all the read queries (SELECT QUERIES) performed in the system database
	 */
	public static function getReadQueries() {
		return SystemDatabaseManager::$readQueries;
	}
	
	
	/**
	 * returns the total number of read queries (SELECT QUERIES) performed in the system database
	 *
	 * @access public
	 *
	 * @return the total number of read queries (SELECT QUERIES) performed in the system database
 	 */
	public static function getCountReadQueries() {
		return count(SystemDatabaseManager::$readQueries);
	}
	
	
	/**
	 * returns an array of all the comments on read queries (SELECT QUERIES) performed in the system database
	 *
	 * @access public
	 *
	 * @return an array of all the comments on read queries (SELECT QUERIES) performed in the system database
	 */
	public static function getReadQueryComments() {
		return SystemDatabaseManager::$readQueryComments;
	}
	
	
	/**
	 * returns an array of all the write queries (INSERT/UPDATE QUERIES) performed in the system databas
	 *
	 * @access public
	 *
	 * @return an array of all the write queries (INSERT/UPDATE QUERIES) performed in the system databas
	 */
	public static function getWriteQueries() {
		return SystemDatabaseManager::$writeQueries;
	}
	
	
	/**
	 * returns the total number of write queries (INSERT/UPDATE QUERIES) performed in the system database
	 *
	 * @access public
	 *
	 * @return the total number of read queries (INSERT/UPDATE QUERIES) performed in the system database
 	 */	
	public static function getCountWriteQueries() {
		return count(SystemDatabaseManager::$writeQueries);
	}
	
	
	/**
	 * returns an array of all the comments on write queries (INSERT/UPDATE QUERIES) performed in the system database
	 *
	 * @access public
	 *
	 * @return an array of all the comments on write queries (INSERT/UPDATE QUERIES) performed in the system database
	 */
	public static function getWriteQueryComments() {
		return SystemDatabaseManager::$writeQueryComments;
	}
    
    /*
     * executes a SELECT query in the database
     *
     * @access public
     * @param $query The SQL SELECT query to execute
     * @param $comment An optional comment explaining the query
     *
     * @return the result set on success, or false on error
     */
    public function executeField($query, $comment = '') {
        
        if (!SystemDatabaseManager::selectDatabaseIfNecessary()) {
            logError("Failed to select system database while executing $query", ERROR_SEVERITY);
            return false;
        }        

        SystemDatabaseManager::$readQueries[] = $query;
        SystemDatabaseManager::$readQueryComments[] = $comment;
        return parent::executeField($query);
    }
}

?>