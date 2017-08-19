<?php 
//----------------------------------------------------------------
// THIS FILE IS USED TO copy periodicity
// Author : Dipanjan Bhattacharjee
// Created on : (29.01.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    require_once(MODEL_PATH . "/ClassesManager.inc.php");
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
    
    $classMgr=ClassesManager::getInstance();

     //starting transaction
     if(SystemDatabaseManager::getInstance()->startTransaction()) {
      //fetch periodicity

		$return = $classMgr->checkCopyPeriodicity();
		if ($return == false) {
			echo FAILURE;
			die;
		}


      //commit transaction
     if(SystemDatabaseManager::getInstance()->commitTransaction()) {
         echo SUCCESS;
         die;
     }
     else {
      echo FAILURE;
      die;
      }
    }
    else {
     echo FAILURE;
     die;
    }

// $History: ajaxCopyPeriodicity.php $    
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 2/01/10    Time: 2:28p
//Updated in $/LeapCC/Library
//done changes for code of making Alumni Class.
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 29/01/10   Time: 17:27
//Created in $/LeapCC/Library
//Created Script for copying "Periodicity"
?>