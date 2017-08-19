<?php

//  This File calls Edit Function used in adding Notice Records
//
// Author :Arvind Singh Rawat
// Created on : 5-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','EventMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

 require_once(MODEL_PATH . "/EventManager.inc.php");
 $eventManager = EventManager::getInstance();
 
  $id  = htmlentities(trim($REQUEST_DATA['userWishEventId']));  
  $abbr = htmlentities(trim($REQUEST_DATA['eventAbbrevation']));
  $eventComment =  htmlentities(trim($REQUEST_DATA['elm12']));
  $eventWishDate=  $REQUEST_DATA['eventWishDate1'];
  $hiddenFile=  $REQUEST_DATA['eventPicture'];  
  $roleIdArray  =  $REQUEST_DATA['roleId']; 
  $isStatus  =  $REQUEST_DATA['visibility'];  
  $hiddenFile=  $REQUEST_DATA['eventPicture'];   
  
 
 
    $str = " comments = '$eventComment' AND eventWishDate = '$eventWishDate' AND userWishEventId != '$id' ";
    $returnStatus = $eventManager->getEvenetCheck($str);
    $cnt = $returnStatus[0]['cnt'];
    
    if($cnt>0) {
       echo "Record already exist";
       die;    
    }
 
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        $returnStatus = $eventManager->editEvent($eventWishDate,$isStatus,$eventComment,$abbr,$id);
        if($returnStatus == false) {
          echo FAILURE;
          die;
        }
        else {
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
              $returnStatus = $eventManager->deleteEventRole($id);
              if($returnStatus == false) {
                echo FAILURE;
                die;
              } 
              $returnStatus = $eventManager->addRole($str);
              if($returnStatus == false) {
                echo FAILURE;
                die;
              } 
           }
        }
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
            $sessionHandler->setSessionVariable('IdToFileUpload',$id);
            $sessionHandler->setSessionVariable('OperationMode',2);
            //Stores file upload info
            $sessionHandler->setSessionVariable('HiddenFile',$hiddenFile);
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


