<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Reports" Module
//
//
// Author :Arvind Singh Rawat
// Created on : 08-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


require_once(DA_PATH . '/SystemDatabaseManager.inc.php');


class ReportManager {
	private static $instance = null;
	
	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
	
	
}
?>

<?php 

//$History: ReportManager.inc.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/17/08    Time: 4:13p
//Created in $/Leap/Source/Model
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/08/08    Time: 3:10p
//Created in $/Leap/Source/Model
//added a new file for ALL reports database operation

?>
