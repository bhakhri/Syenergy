<?php
/**
 * The DatabaseManager is an abstract class that knows how to interact with a database
 *
 * There is only one system database in the application, but there are numerous site databases.
 * To query a database, you must have one of the concrete classes that subclass the DatabaseManager class:
 * SystemDatabaseManager.
 * <code>
 * // here is an example that shows how to use the Database API to query the system database
 * $systemDatabaseManager = SystemDatabaseManager::getInstance();
 * $query = "SELECT * FROM account_profile;";
 * $results = $systemDatabaseManager->executeQuery($query);
 * </code>
 *
 * @package Database
 * @see SystemDatabaseManager
 */
require_once(BL_PATH.'/UtilityManager.inc.php'); 
abstract class DatabaseManager {

    protected $connection;
    protected $databaseName;
    
    public static $lastDatabaseSelected = 0;
    public static $SYSTEM_NOT_INITIALIZED = 0;
    public static $SYSTEM_INITIALING = 1;
    public static $SYSTEM_INITIALIZED = 2;
    
    
    protected function __construct($host, $username, $password, $databaseName) {
        $this->databaseName = $databaseName;
        $this->connection = DatabaseManager::connectToServer($host, $username, $password);
    }
    

    private static function connectToServer($DBHost, $DBUsername, $DBPassword) {
    
        $connection = @mysql_connect($DBHost, $DBUsername, $DBPassword,false,MYSQL_CLIENT_INTERACTIVE);   
        
        if ($connection === false) {
            logError("Unable to connect to the database on $DBHost with username $DBUsername", ERROR_SEVERITY);
            global $sessionHandler;
            $sessionHandler->destroySession();
            redirectBrowser(UI_HTTP_PATH.'/connectionError.php');
            // through to maintenance page
            exit;
        }

        return $connection;
    }

    
    /**
     * creates a new database in the server.
     */
    public function createDatabase($DBName) {
        $update = "CREATE DATABASE IF NOT EXISTS $DBName;";
        DatabaseManager::executeUpdateOnConnection($this->connection, $update, $DBName);
    }
    

    /**
     * selects a specific database in the application
     *
     * @access public
     * @static
     * @param $database The name of the database
     * @param $connection The connection to use
     *
     * @return true if database selected successfully, false otherwise
     */
    public static function selectDatabase($database, $connection) {
        $success = mysql_select_db($database, $connection);
        if ($success === false) {
            logError("Failed to select database '$database'", ERROR_SEVERITY);
            return false;
        }
        
        return true;
    }


    /**
     * returns true if the SYSTEM database has been defined, false otherwise
     */
    private static function doesSystemDBexist($tempConnection) {
        $result = DatabaseManager::executeQueryOnConnection($tempConnection, "show databases like '" . SYSTEM_DATABASE_PREFIX . "';");
        return count($result) == 1;
    }


    /**
     * returns true if a table has been defined
     */
    public static function doesTableExist($tempConnection, $tableName) {
        if (!DatabaseManager::selectDatabase(SYSTEM_DATABASE_PREFIX, $tempConnection)) {
            exit;
        }
        $result = DatabaseManager::executeQueryOnConnection($tempConnection, "show tables like '$tableName';");
        return count($result) == 1;
    }
    
    
    /**
     * makes sure that a certain column exists in a table in the system database.
     */
    private static function doesColumnExist($tempConnection, $tableName, $column) {
        $result = DatabaseManager::executeQueryOnConnection($tempConnection, "SHOW COLUMNS FROM $tableName FROM system;");
        if (count($result) == 0) {
            return false;    
        }
        
        for ($i=0; $i<count($result); $i++) {
            if (strcasecmp($result[$i]['Field'], $column) == 0) {
                return true;
            }            
        }
        
        return false;
    }


    /**
     * returns the status of the sytem (as defined in this class)
     */
    public static function getSystemStatus() {

        global $FP; require_once($FP . '/Library/Database/SystemDatabaseManager.inc.php');
        $tempConnection = DatabaseManager::connectToServer(SystemDatabaseManager::$HOST, SystemDatabaseManager::$USERNAME, SystemDatabaseManager::getPassword());

        if (!DatabaseManager::doesSystemDBexist($tempConnection) || !DatabaseManager::doesTableExist($tempConnection, 'population') || !DatabaseManager::doesColumnExist($tempConnection, 'population', 'Population_Status')) {
            return DatabaseManager::$SYSTEM_NOT_INITIALIZED;
        }

        $result = DatabaseManager::executeQueryOnConnection($tempConnection, "select Population_Status from population;");
        
        if (count($result) == 0) {
            return DatabaseManager::$SYSTEM_NOT_INITIALIZED;
        }
        
        return $result[0]['Population_Status'];
    }


    /**
     * closes the connection to the MySQL server on a specific connection.
     */
    private static function closeConnection($connection) {
        $result = mysql_close($connection);
        if ($result === false) {
            exit;
        }
    }

    /**
     * closes the connection to the MySQL server for this object.
     */
    public function close() {
        DatabaseManager::closeConnection($this->connection);
    }

    /**
     * performs a DDL query.
     */
    public function executeUpdate($update, $comment = '') {
        return DatabaseManager::executeUpdateOnConnection($this->connection, $update, $this->databaseName);
    }
    
    /**
     * performs a DDL query, for a specific connection.
     */
    private static function executeUpdateOnConnection($connection, $update, $databaseName) {
        
        //TODO: remove these
        //quickLog($update);
        queryLog($update);
        //start transaction code added on 23/9/2008
        if(mysql_query('START TRANSACTION', $connection)) {
            $result = mysql_query($update, $connection);
            if ($result === false) {                        
                logError("Error in database " . $databaseName . ", executing update: " . $update . "\r\n".mysql_errno($connection)."\r\n". mysql_error($connection), ERROR_SEVERITY);
                //send error mail            
                DatabaseManager::queryErrorMail($connection, $update);
                //DatabaseManager::closeConnection($connection);
                //exit;
                return $result;
            } 
            else {
                if(mysql_query('COMMIT', $connection)) {
                    return true;
                }
                else {
                    return false;
                }
            }
        }
        else {
            return false;
        }
    }

   /* execute this query for queries log - 19/8/2008*/ 
    public function executeLogQuery($update, $comment = '') {
        return DatabaseManager::executeLogQueryOnConnection($this->connection, $update, $this->databaseName);
    }
    /**
     * performs a DDL query, for a specific connection. - 19/8/2008
     */
    private static function executeLogQueryOnConnection($connection, $update, $databaseName) {
        
        //TODO: remove these
        //quickLog($update);
        $result = mysql_query($update, $connection);
        if ($result === false) {                        
            logError("Error in database " . $databaseName . ", executing update: " . $update . "\r\n" . mysql_error($connection), ERROR_SEVERITY);
            // send error mail
            DatabaseManager::queryErrorMail($connection, $update);
            DatabaseManager::closeConnection($connection);
            exit;
        }
    }
    
    /**
     * performs a delete query.
     */
    public function executeDelete($delete, $comment = '') {
        return DatabaseManager::executeDeleteOnConnection($this->connection, $delete, $this->databaseName);
    }



    /**
     * performs a delete query, for a specific connection.
     */
    private static function executeDeleteOnConnection($connection, $delete, $databaseName) {
        
        //TODO: remove these
        //quickLog($update);
         queryLog($delete);
        //start transaction code added on 23/9/2008
        if(mysql_query('START TRANSACTION', $connection)) {
            $result = mysql_query($delete, $connection);
            if ($result === false) {
                logError( "Error in database " . $databaseName . ", executing delete: " . $delete . "\r\n".mysql_errno($connection)."\r\n". mysql_error($connection) , ERROR_SEVERITY );
                // send error mail
                DatabaseManager::queryErrorMail($connection, $delete);                
                //DatabaseManager::closeConnection($connection);
                return $result;
                //exit;
            }
            else {
                if(mysql_query('COMMIT', $connection)) {
                    return true;
                }
                else {
                    return false;
                }
            }
        }
        else {
            return false;
        }
         
    }


    /**
     * get last insert id.
     */
    public static function getLastInsertId($connection) {
        // i had to write this code as mysql_insert_id does not return last insert id if Start Transaction action is performed
        $result = mysql_query('SELECT LAST_INSERT_ID() as lid',$connection);
        $row    = mysql_fetch_array($result);
        return $row['lid'];
    }


    /**
     * performs a DML query returning an array holding the query results.
     *
     * The column names are used as keys for the secondary array:
     * i.e. $result[0][<columnName>]
     * i.e. $result[1][<columnName>]
     * ...
     */
    public function executeQuery($query, $comment = '') {    
        return DatabaseManager::executeQueryOnConnection($this->connection, $query);
    }
    

    /**
     * performs a DML query returning an array holding the query results, for a specific connection.
     */
    private static function executeQueryOnConnection($connection, $query) {
        
        //TODO: remove these
        //quickLog($query);
        queryLog($query);
        $result = mysql_query($query, $connection);
        if ($result === false) {
            logError("Error in database executing query:\n" . $query . "\r\n" . mysql_error($connection), ERROR_SEVERITY);
            // send error mail
            DatabaseManager::queryErrorMail($connection, $query);
            DatabaseManager::closeConnection($connection);
            exit;
        }
        
        // extract data from results, returning an associative array
        $rows = Array();
        while ($row = mysql_fetch_assoc($result)) {            
            $rows[] = $row;
        }

        return $rows;
    }
    // start transaction
    public function startTransaction() {    
        return DatabaseManager::startTransactionOnConnection($this->connection);
    }    

    private static function startTransactionOnConnection($connection) {
        $result = mysql_query('SET AUTOCOMMIT=0', $connection);
        if ($result === false) {
            logError("Error in database executing query:\n SET AUTOCOMMIT\r\n" . mysql_error($connection), ERROR_SEVERITY);
            //DatabaseManager::closeConnection($connection);
            //exit;
        }
        else {
            $result = mysql_query('START TRANSACTION', $connection);
            if ($result === false) {
                logError("Error in database executing query:\n START TRANSACTION\r\n" . mysql_error($connection), ERROR_SEVERITY);
                //DatabaseManager::closeConnection($connection);
                //exit;
            }
        }
        return $result;
    }
   // commit transaction
    public function commitTransaction() {    
        return DatabaseManager::commitTransactionOnConnection($this->connection);
    }    

    private static function commitTransactionOnConnection($connection) {    
        $result = mysql_query('COMMIT', $connection);
        if ($result === false) {
            logError("Error in database executing query:\n COMMIT\r\n" . mysql_error($connection), ERROR_SEVERITY);
            //DatabaseManager::closeConnection($connection);
            //exit;
        }
        return $result;
    }
   // rollback transaction
    public function rollbackTransaction() {    
        return DatabaseManager::rollbackTransactionOnConnection($this->connection);
    }    

    private static function rollbackTransactionOnConnection($connection) {    
        $result = mysql_query('ROLLBACK', $connection);
        if ($result === false) {
            logError("Error in database executing query:\n ROLLBACK\r\n" . mysql_error($connection), ERROR_SEVERITY);
            //DatabaseManager::closeConnection($connection);
            //exit;
        }
        return $result;
    }
    /* execute queries in transaction 8/12/2008 , had to write this function as execute update/delete function runs in transaction but in some situation, we do not need transaction*/
    public function executeUpdateInTransaction($update, $comment = '') {
        return DatabaseManager::executeUpdateInTransactionOnConnection($this->connection, $update, $this->databaseName);
    }
    /**
     * performs a DDL query, for a specific connection. - 8/12/2008
     */
    private static function executeUpdateInTransactionOnConnection($connection, $update, $databaseName) {
        
        //TODO: remove these
        queryLog($update);
        $result = mysql_query($update, $connection);
        if ($result === false) {                        
            logError("Error in database " . $databaseName . ", executing update: " . $update . "\r\n".mysql_errno($connection)."\r\n". mysql_error($connection), ERROR_SEVERITY);
            // send error mail            
            DatabaseManager::queryErrorMail($connection, $update);
            //DatabaseManager::closeConnection($connection);
            //exit;
        }
        return $result;
    }
    //on error send mail
    public static function queryErrorMail($connection, $query) {
        global $sessionHandler;
        $body ="<b>Username: </b>".$sessionHandler->getSessionVariable('UserName')."<br><br><b>Server:</b>".HTTP_PATH."<br><br><b>File:</b> ".$_SERVER['PHP_SELF']."<br><br><b>Query:</b> $query"."<br><br><b>Error:</b> ".mysql_error($connection);
        $from = "From: ".ADMIN_MSG_EMAIL.";\nContent-type: text/html;";
        $qERefNo = date('dMy-His');
        
       $body = $body . "<br><br><br><table border=\"1\" cellpadding=\"5\" cellspacing=\"0\"> <tr><th>QERefNo.</th><th>Server</th><th>Account</th><th>Version</th><th>UserName</th><th>Role</th><th>Path</th><th>File</th><th>Query</th><th>QueryError</th></tr><tr><td nowrap>$qERefNo</td><td>".$_SERVER['HTTP_HOST']."</td><td>".substr(HTTP_PATH,strrpos(HTTP_PATH,'/')+1)."</td><td>Leap".strtoupper(CURRENT_PROCESS_FOR)."</td><td>".$sessionHandler->getSessionVariable('UserName')."</td><td>".$sessionHandler->getSessionVariable('RoleName')."</td><td>".substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/'))."</td><td>".substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1)."</td><td nowrap>$query</td><td nowrap>".mysql_error($connection)."</td></tr></table>";
        
        return UtilityManager::sendMail(ERROR_MAIL_TO, 'Query Error at '.HTTP_PATH.' RefNo : '.$qERefNo, $body, $from );
    }    
    
     /**
     * performs a DML query returning an array holding the query results.
     *
     * The column names are used as keys for the secondary array:
     * i.e. $result[0][<columnName>]
     * i.e. $result[1][<columnName>]
     * ...
     */
    public function executeField($query, $comment = '') {    
        return DatabaseManager::executeFieldOnConnection($this->connection, $query);
    }
    

    /**
     * performs a DML query returning an array holding the query results, for a specific connection.
     */
    private static function executeFieldOnConnection($connection, $query) {
        
        //TODO: remove these
        //quickLog($query);
        queryLog($query);
        $result = mysql_query($query, $connection);
        if ($result === false) {
            logError("Error in database executing query:\n" . $query . "\r\n" . mysql_error($connection), ERROR_SEVERITY);
            // send error mail
            DatabaseManager::queryErrorMail($connection, $query);
            DatabaseManager::closeConnection($connection);
            exit;
        }
        
        // extract data from results, returning an associative array
        $rows = Array();
        $cnt = mysql_num_fields($result);
        for($i=0; $i<$cnt; $i++) {
           $rows[] = mysql_field_name($result,$i);
        }

        return $rows;
    }
    
}
?>