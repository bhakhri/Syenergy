<?php
//-------------------------------------------------------
// Purpose: To make Add STUDENT HOSTEL FEES
// Author : Nishu Bindal
// Created on : (17.Feb.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/Fee/HostelFeeManager.inc.php");   
$hostelFeeManager = HostelFeeManager::getInstance(); 
define('MODULE','HostelFeeMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

  
global $sessionHandler;
$queryDescription ='';

	
	$classIds      =   $REQUEST_DATA['classId']; 
	$roomTypeId    =   $REQUEST_DATA['roomTypeId']; 
	$feeAmountData =  $REQUEST_DATA['feeAmountData'];
    
    
	$errorMessages = '';
	
	if($classIds == ''){
	  $errorMessages .= SELECT_CLASS."\n";
	}
	
	if($feeAmountData == ''){
	   $errorMessages .= "Required Parameter Is Missing !!\n";
	}
	
   /* $recordArray = $hostelFeeManager->checkForFeeGeneration($classIds);
	    if($recordArray[0]['cnt'] > 0){
		    $errorMessages .= "Hostel Fee Can't be Edited.\nThis Class Fees is Already Generated.\n";
	    }
  */	
    if (trim($errorMessages) == '') {
          //****************************************************************************************************************    
          //***********************************************STRAT TRANSCATION************************************************
          //****************************************************************************************************************
          if(SystemDatabaseManager::getInstance()->startTransaction()) {
		  	
               $dataArray = array();
		  	   $dataArray = explode(',',$feeAmountData);	
               $classArray = explode(',',$classIds);    
               
		       $hostelId ='';
		       $values ='';
		       $roomType ='';
               
               for($kk=0;$kk<count($classArray);$kk++) {
                   $classId = $classArray[$kk];
                   
                   $values = '';
                   foreach($dataArray as $key =>$value) {
		       		    $subArray = array();
		       		    $subArray = explode('-',$value);
		       		    
                        $hostelId ='';
                        $roomType ='';
                        
		       		    //hostelFeeId,hostelId,roomTypeId,studyPeriodId,amount
		       		    // 0 => Amount, 1 => HostelId, 2-> room type
		       		    if($subArray[0] != '' && $subArray[0] > 0){
		       			    if($values != ''){
		       				    $values .=', ';
		       			    }
		       			    $values .="('','$subArray[1]','$subArray[2]','$classId','$subArray[0]')";
		       		    }
		       		    $roomType ="$subArray[2]";
		       		    $hostelId ="$subArray[1]";
                        
                        // to delete existing fee amount of same filters fields 
                        $condition =" WHERE  roomTypeId LIKE '$roomType' AND hostelId LIKE '$hostelId' AND classId LIKE '$classId' ";
                        $deleteStatus = $hostelFeeManager->deleteFeeValues($condition);
                        if($deleteStatus === false) {
                           echo FAILURE; 
                           die;  
                        }
		           }
                   
		           if($values != ''){
		       	      // to insert new values 
		       	      $returnStatus = $hostelFeeManager->insertIntoFeeValues($values);
		       	      if($returnStatus === false) {
		          	    echo FAILURE; 
                        die;
  	       		      }
		           }
               }
             
              //*****************************COMMIT TRANSACTION************************* 
              if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		        $errorMessages = SUCCESS;
              }
              else {
                 $errorMessages .=  FAILURE;
              }
          }    
    }
 
    echo $errorMessages;
 ?>
