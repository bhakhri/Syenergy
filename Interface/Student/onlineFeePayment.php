<style>
    fieldset {
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
    }
</style>
<?php

	global $FE;

	require_once($FE . "/Library/common.inc.php");
	
	
	if(isset($REQUEST_DATA['amt'])){
		
		require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
	
		require_once($FE . '/Library/Fee/OnlineFee/ajaxGetPaymentFee.php');
	}
	else {
		$FE = dirname(__FILE__);   
		$FE = substr($FE,0,strlen(str_replace("Student","",$FE))-1);   
		require_once($FE.'/Fee/listOnlinePayment.php');
	}
?> 