<?php 
/**
 * Defines the interface that every log formatter should follow
 *
 * @package BL
 * @author Shailendra Kumar
 */
abstract class LogFormatter {

	protected $logger;
	
	
	/**
	 * creates a new LogFormatter object
	 *
	 * @access protected
	 * @param $logger The invoking logging object
	 */
	protected function __construct($logger) {
		$this->logger = $logger;
	}
	
	
/**
	 * format the current message
	 *
	 * @abstract
	 * @access public
	 * @param $message The message to format
	 * @param $severityLevel The severity level of the message
	 *
	 * @return The formatted log message
	 */
	abstract public function formatMessage($message, $severityLevel);
}

?>