<?php
//-------------------------------------------------------
//  This File contains Presentation Logic of Menu
//
//
// Author :Ajinder Singh
// Created on : 04-June-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
// 
//--------------------------------------------------------


class ExcelManager {
	private static $instance;
	private $outputFileName;
	private $fileContents;

	private function __construct() {
	}
	
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

	public function openFile($outputFileName) {
		$this->outputFileName = $outputFileName;
		$this->fileContents = pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
	}

	public function closeFile() {
		$this->fileContents .= pack("ss", 0x0A, 0x00);
		$fp = @fopen($this->outputFileName, 'wb');
		if ($fp) {
			fwrite($fp, $this->fileContents);
			fclose($fp);
		}
		
	}

	public function writeNumber($rowNum, $colNum, $value) {
		$this->fileContents .= pack("sssss", 0x203, 14, $rowNum, $colNum, 0x0);
		$this->fileContents .= pack("d", $value);
	}

	public function writeText($rowNum, $colNum, $value) {
		$valueLength = strlen($value);
		$this->fileContents .= pack("ssssss", 0x204, 8 + $valueLength, $rowNum, $colNum, 0x0, $valueLength);
		$this->fileContents .= $value;
	}
}


// for VSS
//$History: ExcelManager.inc.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 6/05/09    Time: 11:59a
//Created in $/Leap/Source/Library
//class file added for generating excel files.
//




?>