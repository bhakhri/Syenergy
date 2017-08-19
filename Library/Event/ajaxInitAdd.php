<?php
//  This File calls addFunction used in adding Notice Records
//
// Author :Arvind Singh Rawat
// Created on : 5-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/EventManager.inc.php");
$eventManager = EventManager::getInstance();
define('MODULE','EventMaster');
define('ACCESS','add');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();  

    /*
    if(!isset($REQUEST_DATA['eventAbbrevation']) || trim($REQUEST_DATA['eventAbbrevation']) == '') {
      $errorMessage .= "Enter abbrevation\n";
    }
    if(!isset($REQUEST_DATA['eventComment']) || trim($REQUEST_DATA['eventComment']) == '') {
      $errorMessage .= ENTER_NOTICE_TEXT."\n";
    }
	if(!isset($REQUEST_DATA['eventWishDate']) || trim($REQUEST_DATA['eventWishDate']) == '') {
      $errorMessage .= EMPTY_FROM_DATE."\n";
    }

    if(!isset($REQUEST_DATA['roleId']) || trim($REQUEST_DATA['roleId']) == '') {
      $errorMessage .= EMPTY_FROM_DATE."\n";
    }
    */
   $eventAbbrevation = htmlentities(trim($REQUEST_DATA['eventAbbrevation']));
   $eventComment =  htmlentities(trim($REQUEST_DATA['elm11']));
   $eventWishDate=  $REQUEST_DATA['eventWishDate'];
   $hiddenFile=  $REQUEST_DATA['eventPicture'];  
   $roleIdArray  =  $REQUEST_DATA['roleId']; 
   $visibility  =  $REQUEST_DATA['visibility'];  
    

    $str = " comments = '$eventComment' AND eventWishDate = '$eventWishDate' ";
    $returnStatus = $eventManager->getEvenetCheck($str);
    if($returnStatus[0]['cnt']>0) {
       echo "Record already exist";
       die;    
    }
    
    $str="";
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        $str="('$eventWishDate','$visibility','$eventComment','$eventAbbrevation')";
        $returnStatus = $eventManager->addEvent($str);
        if($returnStatus == false) {
          echo FAILURE;
		  die;
        }
        else {
           $id=SystemDatabaseManager::getInstance()->lastInsertId();  
           $sessionHandler->setSessionVariable('IdToFileUpload',$id);
           $str="";
           for($i=0;$i<count($roleIdArray);$i++) {
              if($str!='') {
                $str .=",";  
              }  
              $roleId=$roleIdArray[$i]; 
              $str .= "($id,$roleId)";
           }
           
           if($str!='') {
              $returnStatus = $eventManager->addRole($str);
              if($returnStatus == false) {
                echo FAILURE;
                die;
              } 
           }
           
           $sessionHandler->setSessionVariable('OperationMode',1);
           //Stores file upload info
           $sessionHandler->setSessionVariable('HiddenFile',$hiddenFile);
           //echo SUCCESS;
        }
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
?>