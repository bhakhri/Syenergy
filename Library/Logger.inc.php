<?php
/**
 * Defines the interace for the Logger.inc.php file
 *
 * @package Library 
 *
 */

require_once('common.inc.php');
 
/**
 * Defines the API for the management of the log file
 *
 * @package BL 
 * @author Shailendra Kumar
 */
class Logger {
	
	private static $instance = NULL;
	
	private $severityDescription;
	private $logFormatter;
	private $fileHandle;
	
	/**
	 * creates a new Logger object
	 *
	 * @access private
	 */
	private function __construct() {
		
		$this->severityDescription = 0;
				
		require_once(BL_PATH . '/DirectoryManager.inc.php');
		if (!file_exists(LOCAL_STORAGE_DIR)) {
			mkdir(LOCAL_STORAGE_DIR, DirectoryManager::$USER_GROUP_PREVILAGE);
		}
		
		$this->fileHandle = fopen(LOCAL_STORAGE_DIR . LOG_FILE_NAME, "at+");	
		if ($this->fileHandle === false) {
			// Failure to obtain a handle to the log file
			exit;	
		}			
		
		require_once(BL_PATH . '/FileLogFormatter.inc.php');
		$this->logFormatter = new FileLogFormatter($this);
	}
	
	
	/**
	 * appends a text message to the log file
	 *
	 * Log messages with severity level lower than the current level supported by the logger will be ignored
	 *
	 * @access public
	 * @param $textMessage The message to write
	 * @param $severityLevel The severity of the message. 
	 */
	public function write($textMessage, $severityLevel = DEBUG_SEVERITY) {
		
		// safety check - check the supplied security level
		if ($severityLevel != DEBUG_SEVERITY && 
		    $severityLevel != INFO_SEVERITY && 
		    $severityLevel != NOTICE_SEVERITY && 
		    $severityLevel != WARNING_SEVERITY && 
		    $severityLevel != ERROR_SEVERITY) {
		    	
		    // Invalid severity level
			trigger_error("Logger detected the invalid severity log level $severityLevel while attempting to write a log message", E_USER_ERROR);
			exit;
		}
		
//		if ($severityLevel < MINIMAL_SUPPORTED_SEVERITY) {
//			// severity level is not high enough
//			return;
//		}
		
		
		$textMessage = $this->logFormatter->formatMessage($textMessage, $severityLevel);
		
		if (fwrite($this->fileHandle, $textMessage) === false) {
			// An error occurred while attempting to write to the log file
			
			// What should be done here? Obviously we cannot log this to the log file as this was the original problem
			// We cannot stop the application because that would be too strict	       	
		}
	}
	
	
	/**
	 * closes the handle to the log file
	 *
	 * @access public
	 */
	public function close() {		
		$success = fclose($this->fileHandle);
		if ($success === false) {
			// Failure to close the log file
			$this->write("Logger failed to close the handle to the log file", ERROR_SEVERITY);
		}
			
		Logger::$instance = NULL;	
	}
	
	
	/**
	 * returns an instance of the Logger object
	 *
	 * @access public
	 * @static
	 *
	 * @return an instance of the Logger object
	 */
	public static function getInstance() {
		if (Logger::$instance == NULL) {
			Logger::$instance = new Logger();
		}
		return Logger::$instance;
	}
	
	
	/**
	 * initializes the "severity to description" array
	 *
	 * @access private
	 */
	private function setSeverityDescription() {
		$this->severityDescription = Array(
			DEBUG_SEVERITY => "Debug", 
	 		INFO_SEVERITY => "Info",
	 		NOTICE_SEVERITY => "Notice",
	 		WARNING_SEVERITY => "Warning",
	 		ERROR_SEVERITY => "Error");
	}
	
	
	/**
	 * returns the description associated with the passed severityLevel
	 *
	 * @access public
	 * @param $severityLevel One of severity levels supported by the logger
	 */
	public function getSeverityDescription($severityLevel) {
		if ($this->severityDescription == 0) {
			$this->setSeverityDescription();
		}
		
		if (!array_key_exists($severityLevel, $this->severityDescription)) {
			// Invalid severity level
			exit;
		}
		
		return $this->severityDescription[$severityLevel];
	}

	
	/**
	 * clean the log file, removing all log messages
	 *
	 * @access public
	 */
	public function cleanLog() {
		require_once(BL_PATH . '/DirectoryManager.inc.php');
		$this->fileHandle = fopen(LOCAL_STORAGE_DIR . LOG_FILE_NAME, "w+");
		if ($this->fileHandle === false) {
			// Failure to obtain a handle to the log file
			exit;				
		}
	}
    /**
     * create query log
     *
     * @access public
     */
    public function queryLog($qry, $level=DB_QUERY_LEVEL,$destination=DB_QUERY_LOG_DESTINATION) {
            if($destination==1) {  // database log
            
                require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
                global $sessionHandler;
                // get type of query, Insert/update/delete/select
                $queryType = strtoupper(substr(trim($qry),0,1));
                if($queryType=='C') {
                  $queryType = 'SP'; // stored procedure;  
                }
                //echo "$instituteId=$sessionId=$userId";
                
                $qry = str_replace("'","`",$qry);
                $qry = str_replace('"',"`",$qry);
                
                if($sessionHandler->getSessionVariable('InstituteId')!='') {
                    if($queryType!='S') {
                         $q = 'INSERT INTO `query_log`(instituteId,sessionId,fromIp,userId,queryDetail,queryType,logDate)
                               VALUES('.$sessionHandler->getSessionVariable('InstituteId').','.$sessionHandler->getSessionVariable('SessionId').',
                                      "'.$_SERVER['REMOTE_ADDR'].'",'.$sessionHandler->getSessionVariable('UserId').', 
                                      "'.add_slashes($qry).'","'.$queryType.'",NOW())';

                        
                        if($level=='1') {
                            SystemDatabaseManager::getInstance()->executeLogQuery($q);
                           //mysql-query($q);
                        }
                        else {
                            if($level=='2' && $queryType=='S') {
                              SystemDatabaseManager::getInstance()->executeLogQuery($q);
                             //mysql-query($q);                    
                            }
                            elseif($level=='3' && $queryType!='S' ) {
                              SystemDatabaseManager::getInstance()->executeLogQuery($q);
                              //mysql-query($q);
                            }
                        }
                    }
                }
            }
            else {
                //// save log into file //
				$fh = fopen(LOCAL_STORAGE_DIR . LOG_FILE_NAME, 'a');
				if ($fh) {
					fwrite($fh, "\r\n" . $qry . "\r\n");
					fclose($fh);
				}
            }
    }
}
?>