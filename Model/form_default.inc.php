<?php 
//Author : Usheer Fotedar
// Created on : (20.03.2014)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//Edited on : 04/04/2014
?>

<?php
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");	
	
	class form_default{
	
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
		
		// Update Query
		
		public function update($data, $path, $table='form_default'){
			$sql = "update ".$table." set data='".$data."' where url_name='".$path."'";
			if(mysql_query($sql)){
				return($data);
			}else{
				return "false";
			}
		}
		
		//Insert Query
		
		public function insert($data, $path, $table='form_default'){
			$sql = "insert ".$table." (data, url_name) values ('".$data."','".$path."')";
			if(mysql_query($sql)){
				return($data);
			}else{
				return "false";
			}
		}
		
		//Select Query
		
		public function disp_rec($path, $table='form_default'){
			$sql = "select data from ".$table." where url_name='".$path."'";
			$retval	= mysql_query($sql);
			if(mysql_num_rows($retval) != ""){		
				while($row = mysql_fetch_array($retval))
				{
					$data = $row['data'];
				}
				return($data);
			}else{
				return "false";
			}
		}
		
		//Query to fetch the number of rows
		
		public function num_of_rows($path, $table='form_default'){
			$sql = "select count(id) from ".$table." where url_name='".$path."'";
			$num_rows = mysql_fetch_array(mysql_query($sql));
			return $num_rows;
		}
	}
?>