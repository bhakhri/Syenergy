<?php

    // global $FE;
    // require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifStudentNotLoggedIn();
    
    require_once(MODEL_PATH . "/Fee/OnlineFeeManager.inc.php");
    $onlineFeeManager = OnlineFeeManager::getInstance();
    
    global $sessionHandler;      
    $studentId = $sessionHandler->getSessionVariable('StudentId');
    $UserId = $sessionHandler->getSessionVariable('UserId');

         // IdToOnlieFee => $feeClassId.'~'.$feeClassType.'~'.$totalPayableAmount.'~'.$interestAmount.'~'.$totalFeeAmount.'~'.$studentId;
    $id = $REQUEST_DATA['amt']; 
    
    $feeHistory =  htmlentities(add_slashes(trim($id)));
    $onlineHolderName = $sessionHandler->getSessionVariable('StudentName');
    
	
    if($feeHistory=='') {
      echo TECHNICAL_PROBLEM;
      die;  
    }
    if($studentId=='') {
      echo TECHNICAL_PROBLEM;
      die;  
    }
    
    $bankAccessCode = ONLINE_ACCESS_CODE;
    $bankMerchant = ONLINE_MERCHANT;
    $bankSECURE_SECRET = ONLINE_SECURE_SECRET;
   
    
    $totalAmout = 0;
    $queryMaster="";
    $queryDetail="";
	
    
        
   
        $retArray = explode('~',$feeHistory);
        $classId = $retArray[0];   
        $feeType = $retArray[1];
        $amout = $retArray[2];
        $taxAmout = '0';
        $ttTotalFee = $retArray[3];
        $ttTaxAmount = doubleval($taxAmout);
        $totalAmout = doubleval($amout);	  

  		$classIdDetailsArray = $onlineFeeManager->getClassPaymentDetails($classId);
       		
			if($feeType=='1'){
       			$feeName="Academic";
       		}
			if($feeType=='2'){
       			$feeName="Transport";
       		}
			if($feeType=='3'){
       			$feeName="hostel";
       		}
       		
			$classIdArray .= $classIdDetailsArray[0]['className']."(".$feeName.")";
	
	
    		
    $md5HashDataCode = '';
    if(SystemDatabaseManager::getInstance()->startTransaction()) {    
       $userIp = $_SERVER['REMOTE_ADDR']; 
     
       $queryMaster = "($UserId,$studentId,$feeType,$classId,$ttTotalFee,$totalAmout,'$userIp')";
       $onlineFeePaymentId = $onlineFeeManager->insertOnlineTransaction($queryMaster); 
       // var_dump($onlineFeePaymentId);die;
	   if($onlineFeePaymentId===false) {
         echo TECHNICAL_PROBLEM;   
         die; 
       }
	   // var_dump($onlineFeePaymentId);die;
       if(SystemDatabaseManager::getInstance()->commitTransaction()) {   
            $studentOrderId=str_pad($studentId, 6, "0", STR_PAD_LEFT);
            $OrderId = $onlineFeePaymentId."".$studentOrderId;
			// var_dump( $OrderId);die;
			$postUrl=onlineTransactionUrl($OrderId,$totalAmout);
			
			$updateCode="sendData ='".$postUrl['data']."'";
			if(SystemDatabaseManager::getInstance()->startTransaction()) { 
				$update=$onlineFeeManager->updateOnlineTransaction($updateCode,$onlineFeePaymentId,$studentId);
				if($update===false) {
					echo TECHNICAL_PROBLEM;   
					die; 
				}
				if(SystemDatabaseManager::getInstance()->commitTransaction()) {
					// var_dump($update);die;
					header('location:'.$postUrl['url']);
					// echo $postUrl;
					die;
				}
    //==============================================================================================================       
           }
		}
	}
?>
