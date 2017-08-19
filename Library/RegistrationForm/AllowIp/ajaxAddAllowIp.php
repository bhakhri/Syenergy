<?php 

//  This File calls addStudent used in adding blockstudent Records
//
// Author :Abhay Kant
// Created on : 22-June-2011
// Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','AllowIp');
    define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/RegistrationForm/AllowIpManager.inc.php");
    $allowIpManager =AllowIpManager::getInstance();
    
    require_once(BL_PATH.'/HtmlFunctions.inc.php');   
    $htmlFunctions =HtmlFunctions::getInstance();  

    global $sessionHandler;

        $dt = date('Y-m-d');
       

        if(trim($REQUEST_DATA['allowIp']) == '') {
          echo "Enter Allow IP Nos";
          die;
        }
        
        //insert into `allow_ip_address` (allowIPNo) values('192.168.1.11'),('192.168.1.12')
        $allowIp=($REQUEST_DATA['allowIp']);
        
        $allowedIpArray=explode(",",$allowIp);
        $count=count($allowedIpArray);
        
       	$str='';
        for($i=0;$i<$count;$i++){
           if($str!=''){
              $str.=',';
           }
           $ip= htmlentities(add_slashes(trim($allowedIpArray[$i])));
           $str.="('$ip')";
        }
        
        $returnStatus = $allowIpManager->getCheckIPList($str);  
        $duplicatStatus='';
        for($i=0;$i<count($returnStatus);$i++) {
          if($duplicatStatus!='') {
            $duplicatStatus .=', ';  
          }  
          $duplicatStatus .=$returnStatus[$i]['allowIPNo'];
          if($i==5) {
            $duplicatStatus .="\n\r";  
          }
        }
       
        if($duplicatStatus!='') {
          echo "Following Allow IPs already exist\n\r".$duplicatStatus;
          die;
        }
        
        
        if($str!=''){
          if(SystemDatabaseManager::getInstance()->startTransaction()) {
	         $returnStatus = $allowIpManager->addIp($str);
	         if(SystemDatabaseManager::getInstance()->commitTransaction()) {
	           if($returnStatus === false) {
		          echo FAILURE;
		       }
		       else {
		          echo SUCCESS;           
		       }
	         }
	       } 
        }
?>
