<?php
	global $FE;

	require_once($FE . "/Library/common.inc.php");
	if(isset($REQUEST_DATA)){

		// var_dump($REQUEST_DATA);die;
		$responseData=onlineTransactionResponse($REQUEST_DATA);
			 	
		require_once($FE.'/Library/Fee/OnlineFee/onlinePaymentSlip.php');
   
	}
	else{
		echo TECHNICAL_PROBLEM;
	}
?> 