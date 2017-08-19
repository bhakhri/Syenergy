<?php 
require_once('LogFormatter.inc.php');

/**
 * Defines the API for formatting log messages to a text file
 *
  * @author Pushpender Kumar
 */
class FileLogFormatter extends LogFormatter {
	
	/**
	 * creates a new FileLogFormatter object
	 *
	 * @access public
	 * @param $logger The invoking logging object
	 */
	public function __construct($logger) {
		parent::__construct($logger);
	}

	
	/**
	 * format the current message (possibly using some system parameters) and return the formatted message.
	 *
	 * @abstract
	 * @access public
	 * @param $message The message to format
	 * @param $severityLevel The severity level of the message
	 *
	 * @return The formatted log message
	 */
	public function formatMessage($message, $severityLevel) {
		
		$line = date("d/m/y") . " " . date("G:i:s") . " "; 
		$line .= $_SERVER['REMOTE_ADDR'] . " : ";		
		$line .= $_SERVER['PHP_SELF'] . "\t";		
		$line .= $this->logger->getSeverityDescription($severityLevel);
		$line .= ": " . $message . "\r\n";		
		return $line;			
	}
}

?>