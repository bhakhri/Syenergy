<?php
//-------------------------------------------------------
//  This File outputs student userName and password
//
// Author :Gurkeerat Sidhu
// Created on : 05.11.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
  
      require_once(BL_PATH . "/UtilityManager.inc.php");
      define('MODULE','UpdatePasswordReport');
      define('ACCESS','view');
      define('MANAGEMENT_ACCESS',1);
      UtilityManager::ifNotLoggedIn();
      
     // $csvData = $REQUEST_DATA['result'];
     // UtilityManager::makeCSV($csvData, 'StudentUserInfo.csv');
       $csvData = $REQUEST_DATA['result'];
      // $csvData  = str_replace("&#65533;",chr(160),$csvData);  
       $fileName = substr('SSSSSSSSSSStuInfo'.microtime(),10,20);
    UtilityManager::makeCSV($csvData, $fileName.'.csv');
      die;
?>
