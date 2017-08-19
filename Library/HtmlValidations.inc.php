<?php

   class Htmlvalidations{

	    var $strValidStringName;

		var $strErrorMessage;

		function classValidField(){

			$this->strValidStringName = "";
			$this->strErrorMessage    = "";			
        }
        
        function checkvalidQueryStringValue($intPId=0,$strPType='I'){
        	if($strPType == 'S'){
        		if(is_string($intPId) && $intPId !='')
        			return true;        		
        	}        
        	else if($strPType == 'I'){
        		if(is_numeric($intPId) && $intPId >0)
        			return true;        		
        	}        	      
        	
        	return false; 
        }
        
   
       
        
        #FUNCTION TO CHECK WHETHER A FIELD IS EMPTY OR NOT
      	function checkMandatoryField($strPStringName,$strPErrorBlankMessage='',$strPErrorInvalidMessage=''){
			if(trim($strPStringName) == ""){ 
					if($strPErrorBlankMessage =='')
						$strPErrorBlankMessage = _ERR_FIELD_BLANK;
						
					$this->strErrorMessage = $strPErrorBlankMessage;
					return false;
			}else {
				if($strPErrorInvalidMessage) {
					if(!$this->checkValidText($strPStringName,$strPErrorInvalidMessage)){
						return false;
					}	
				}
  			}
			return true;			
		}		
		
   		function checkValidStringValue($strPStringName,$strPErrorBlankMessage='',$strPErrorInvalidMessage=''){
			if(trim($strPStringName) != ""){ 
				if(!eregi("^([\/\[:space:]\@\&\#\$\é\%\*\'\’\"\{\}\!\;\(\)\:\|\=\)\?\,^_a-zA-Z0-9\.\+\-]){1,255}$",trim($strPStringName))){
					
					if($strPErrorInvalidMessage =='')
						$strPErrorInvalidMessage = _ERR_FIELD_INVALID;
					
					$this->strErrorMessage = $strPErrorInvalidMessage;
					return false;
				}
			}else{
					if($strPErrorBlankMessage =='')
						$strPErrorBlankMessage = _ERR_FIELD_BLANK;
						
					$this->strErrorMessage = $strPErrorBlankMessage;
					return false;
			}
			return true;
		}
		
		function checkValidName($strPStringName,$limit=30){
			if(trim($strPStringName) != ""){ 
				if(!eregi("^([\[:space:]a-zA-Z]){1,}$",trim($strPStringName))){
					
					if($strPErrorInvalidMessage =='')
						$strPErrorInvalidMessage = _ERR_FIELD_INVALID;
					
					$this->strErrorMessage = $strPErrorInvalidMessage;
					return false;
				}
			}else{
					if($strPErrorBlankMessage =='')
						$strPErrorBlankMessage = _ERR_FIELD_BLANK;
						
					$this->strErrorMessage = $strPErrorBlankMessage;
					return false;
			}
			return true;
		}
		
		function checkValidLimitStringValue($strPStringName,$strPErrorBlankMessage='',$strPErrorInvalidMessage='',$Limit=1024){
			if(trim($strPStringName) != ""){ 
				if(strlen($strPStringName)<=$Limit){
					if(!eregi("^([\/\[:space:]\@\&\#\$\é\%\*\'\"\{\}\!\;\,\(\)\:\|\=\)\?\,^_a-zA-Z0-9\.\+\-]){1,}$",trim($strPStringName))){
						if($strPErrorInvalidMessage =='')
						$strPErrorInvalidMessage = _ERR_FIELD_INVALID;
					
					$this->strErrorMessage = $strPErrorInvalidMessage;
					return false;
					}
				}else{
					$this->strErrorMessage = $strPErrorInvalidMessage;
					return false;
				}
			}else{
					if($strPErrorBlankMessage =='')
						$strPErrorBlankMessage = _ERR_FIELD_BLANK;
						
					$this->strErrorMessage = $strPErrorBlankMessage;
					return false;
			}
			return true;
		}
		
		// checkValidText(string name, invalid msg)
		// function allows only valid characters from the user 
		function  checkValidText($strPStringName,$strPErrorInvalidMessage=''){
			if(trim($strPStringName) != ""){ 
//				if(!eregi("^([\/\[:space:]\@\!\#\&\$\%\*\(\~\'\"\)\?\,^_a-zA-Z0-9\.\-]){0,255}$",trim($strPStringName))){
//				Changed by ADS, previous statement is commented above
				if(!eregi("^([\/\[:space:]\@\!\#\&\$\%\*\(\~\'\"\)\?\,^_a-zA-Z0-9\.\-]*)$",trim($strPStringName))){
					
					if($strPErrorInvalidMessage =='')
						$strPErrorInvalidMessage = _ERR_FIELD_INVALID;
					
					$this->strErrorMessage = $strPErrorInvalidMessage;
					return false;
				}
			}
			return true;
		}
		

  		function  checkValidOPTStringValue($strPStringName,$strPErrorInvalidMessage=''){
			if(trim($strPStringName) != ""){ 
				if(!eregi("^([\/\[:space:]\@\#\$\%\*\'\"\)\?\,^_a-zA-Z0-9\.\-]){1,255}$",trim($strPStringName))){
					
					if($strPErrorInvalidMessage =='')
						$strPErrorInvalidMessage = _ERR_FIELD_INVALID;
					
					$this->strErrorMessage = $strPErrorInvalidMessage;
					return false;
				}
			}
			return true;
		}
		#FUNCTIN TO CHECK FOR VALID EMAIL ADDRESS OR NOT

		function validEmailId($strPMailId,$errString = '',$errBlank = ''){
			
			
        	if(trim($strPMailId) != ""){
        		

//if(!eregi("^[\'+\\./0-9A-Z^_\`a-z{|}~\-]+@[a-zA-Z0-9_\-]+(\.[a-zA-Z0-9_\-]+){1,3}$",trim($strP//MailId))){

			if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",trim($strPMailId))){
					$this->strErrorMessage = $errString;
					return false;
			}
				return true;
			}
			
			$this->strErrorMessage = $errBlank;

			return false;
		}
		
		

		#FUNCTIN TO CHECK FOR VALID EMAIL ADDRESS OR NOT IF FIELD IS OPTIONAL

		function validOPTEmailId($strPMailId){

        	if(trim($strPMailId) != ""){

				if(!eregi("^[\'+\\./0-9A-Z^_\`a-z{|}~\-]+@[a-zA-Z0-9_\-]+(\.[a-zA-Z0-9_\-]+){1,3}$",trim($strPMailId))){

					$this->strErrorMessage = ERR_INVALID_EMAIL;

					return false;
				}

				return true;
			}

     		return true;
		}

		#FUNCTION TO CHECK VALID PASSWORD LENGTH 6 - 20

		function validPasswordLength($strPString,$strPErrorBlankMessage='',$strPErrorInvalidMessage=''){
   			if(trim($strPString) !=""){   			
   			  if(strlen($strPString)<6 || strlen($strPString)>20)	{
	   			  if($strPErrorInvalidMessage != '')	
	   			  	$this->strErrorMessage = $strPErrorInvalidMessage;
	   			  else	
					$this->strErrorMessage = ERR_PASSWORD_LENGTH;
	
		    		return false;
   			  }
			}
			else {				
				if($strPErrorBlankMessage !='')
					$this->strErrorMessage = $strPErrorBlankMessage;	
				else
					$this->strErrorMessage = ERR_PASSWORD_BLANK;		
					return  false;
			}
			return true;
		}
			
		#FUNCTION TO CHECK DESCRIPTION FIELD LENGTH

		function validFieldLength($strPString,$strPMaxInteger='255',$strPErrorMSG=''){		
			if((trim($strPString)) != ''){
				if(strlen($strPString)>$strPMaxInteger){
					if($strPErrorMSG == ''){
						$this->strErrorMessage = ERR_FIELD_LENGTH;
					}else{
						$this->strErrorMessage = $strPErrorMSG;
					}
	    			return false;	
				}
			}
			return true;
		}
		
		function validDate($strDate,$format=''){
			if($strDate==""){
				$this->strErrorMessage = ERR_DATE_BLANK;
			}else{
				if($format=='' || $format=='yyyy-mm-dd')
				 $dateArray=$explode("-",$strDate);
				 $year=$dateArray[0];
				 $month=$dateArray[1];
				 $day=$dateArray[2];
				 If(!checkdate($mm,$dd,$yy)){
					$this->strErrorMessage = INVALID_DATE;
					return false;
					}else {
					return true;
				}
			}
		}
		
		function validStartAndEndDate($Startdate,$Enddate){
   			if(intVal($Startdate[0]) <= intVal($Enddate[0]) ){
				if(intVal($Startdate[0]) == intVal($Enddate[0])){	
					if(intVal($Startdate[1]) <= intVal($Enddate[1])){
						if(intVal($Startdate[1]) == intVal($Enddate[1])){
							if (intVal($Startdate[2]) <= intVal($Enddate[2])) {
								$this->strErrorMessage= "";
							}else{
							$this->strErrorMessage .="Date is less than start data"."<BR/>";
							}
						}
					}else{
						$this->strErrorMessage .="Month is less than start month"."<BR/>";	
					}
				}		 	
			}else{
				$this->strErrorMessage .="Year is less than start year"."<BR/>";		
			}
			
			if($this->strErrorMessage != ''){
				return false;
			}else{
				return true;
			}		
		}
		
		function checkDateLater($startPDate='') {						
			if(trim($startPDate) != '') {
				
				$strCurrentDate = date("Y-m-d");
				 				
				if($startPDate < $strCurrentDate){
					$this->strErrorMessage ="Plan start date should not be less than current date.";
					return false;
				}
				else {
					return true;
				}
			}			
			 else {
				$this->strErrorMessage ='Please enter the plan start date.';
				return false;
			}		
		}  
		

   

		#FUNTION TO CHECK WHETHER CONFIRM PASSWORD FIELD IS EQUAL TO PASSWORD FIELD

		function validConfirmPassword($strPPassword1,$strPPassword2){

			if(trim($strPPassword1) != trim($strPPassword2)){

				$this->strErrorMessage = ERR_PASSWORD_CONFIRM;

				return false;

			}

			return true;
		}

		#FUNCTION TO CHECK WHETHER A FIELD STRING IS VALID OR NOT
      	function checkValidStringName($strPStringName,$strPErrorBlankMessage='',$strPErrorInvalidMessage=''){
      		
			if(trim($strPStringName) != ""){ 
				if(!eregi("^[0-9a-zA-Z]{1,30}$",trim($strPStringName))){
					
					if($strPErrorInvalidMessage =='')
						$strPErrorInvalidMessage = _ERR_FIELD_INVALID;
					
					$this->strErrorMessage = $strPErrorInvalidMessage;
					return false;
				}
			}else{
					if($strPErrorBlankMessage =='')
						$strPErrorBlankMessage = _ERR_FIELD_BLANK;
						
					$this->strErrorMessage = $strPErrorBlankMessage;
					return false;
			}
			return true;
			
			
		}

		#FUNCTION TO CHECK WHETHER A FIELD STRING IS VALID OR NOT
      	function checkStringName($strPStringName,$strPErrorBlankMessage='',$strPErrorInvalidMessage=''){
			if(trim($strPStringName) != ""){ 
				if(!eregi("^[a-zA-Z]{1,30}$",trim($strPStringName))){
					if($strPErrorInvalidMessage =='')
						$strPErrorInvalidMessage = _ERR_FIELD_INVALID;
					
					$this->strErrorMessage = $strPErrorInvalidMessage;
					return false;
				}
			}else{ 
					if($strPErrorBlankMessage =='')
						$strPErrorBlankMessage = _ERR_FIELD_BLANK;
						
					$this->strErrorMessage = $strPErrorBlankMessage;
					return false;
			}
			return true;
		}
		#FUNCTION TO CHECK WHETHER A FIELD STRING IS VALID OR NOT
      	function checkZip($strPStringName){
			if(trim($strPStringName) != ""){ 				
				if(!eregi("^[a-zA-Z0-9]{5,10}$",trim($strPStringName))){
					$this->strErrorMessage = ERR_ZIPCODE_INVALID;
					return false;
				}
			}
			return  true;
			/*else{
					$this->strErrorMessage = ERR_ZIPCODE_BLANK;
					return false;
			}*/
		}

		#FUNCTION TO CHECK WHETHER A FIELD STRING IS VALID OR NOT
      	function checkStringNameWithSpace($strPStringName,$strPErrorBlankMessage='',$strPErrorInvalidMessage=''){
			if(trim($strPStringName) != "") { 
				if(!eregi("^[a-zA-Z\[:space:]0-9]{1,100}$",trim($strPStringName))){
					
					if($strPErrorInvalidMessage =='')
						$strPErrorInvalidMessage = _ERR_FIELD_INVALID;
						$this->strErrorMessage = $strPErrorInvalidMessage;
						return false;
					}
					
			}else{
					if($strPErrorBlankMessage =='')
					$strPErrorBlankMessage = _ERR_FIELD_BLANK;
						
					$this->strErrorMessage = $strPErrorBlankMessage;
					return false;
			}
			return true;
		}


		
		#FUNCTION TO CHECK WHETHER A FIELD STRING IS VALID OR NOT
      	function checkStringLength($strPStringName,$length,$strPErrorMessage=''){
			if(strlen(trim($strPStringName)) < $length){
				$this->strErrorMessage = $strPErrorMessage;
				return false;
			}
			return true;
		}


		#FUNCTION TO CHECK WHETHER A FIELD STRING IS VALID OR NOT IF FIELD IS OPTIONAL

      	function checkValidStringOPTName($strPStringName,$strPMessage=''){

			if(trim($strPStringName) != ""){ 

				if(!eregi("^[0-9a-zA-Z]{1,200}$",trim($strPStringName))){
	
					 if($strPMessage)		
					 	$this->strErrorMessage = $strPMessage;
					 else 
						$this->strErrorMessage = _ERR_FIELD_INVALID;

					return false;
				}
				return true;
			}
			return true;
		}

		#FUNCTION TO CHECK WHETHER A FIELD USER NAME IS VALID OR NOT

      	function checkValidUserName($strPStringName){
      		
//     		if(strlen(trim($strPStringName)) < 4 || strlen(trim($strPStringName)) > 20) {
//      			$this->strErrorMessage = ERR_USERNAME_LENGTH_INVALID;
//      			return false;
//      		}
      	

			if(trim($strPStringName) != ""){

				if(!eregi("^[a-zA-Z0-9_]{6,20}$",trim($strPStringName))){

					$this->strErrorMessage = ERR_USERNAME_INVALID;

					return false;
				}

				return true;
			}

			$this->strErrorMessage = ERR_USERNAME_BLANK;

			return false;
		}

		#FUNCTION TO CHECK WHETHER A FIELD PASSWORD FIELD IS VALID OR NOT

//      	function checkValidPassword($strPStringName){
//
//			if(trim($strPStringName) != ""){
//
//				//if(!eregi("^[0-9a-zA-Z]{1,20}$",trim($strPStringName))){
//
//				//	$this->strErrorMessage = _ERR_INVALID_PASSWORD;
//
//				//	return false;
//			//	}
//
//				return true;
//			}
//
//			$this->strErrorMessage = _ERR_PASSWORD_BLANK;
//
//			return false;
//		}

		function checkCustomPhone($intPNumber, $strPErrorBlankMessage='',$strPErrorInvalidMessage=''){
			if(!empty($intPNumber)){
			if(!eregi("^[(][0-9]{1,3}[)][0-9]{1,3}[-][0-9]{4,10}$",trim($intPNumber))){
				$this->strErrorMessage = $strPErrorInvalidMessage;
				return false;
			}
			}else{
				$this->strErrorMessage = $strPErrorBlankMessage;
				return false;
			}
			return true;
		}
		#FUNCTION TO CHECK THAT A NUMBER IS VALID OR NOT

        function checkValidNumber($intPNumber, $strPErrorBlankMessage='',$strPErrorInvalidMessage=''){
            $intPNumber = trim($intPNumber);
			if(!empty($intPNumber)){
				if(!eregi("^[0-9]{1,11}$",trim($intPNumber))){
					if($strPErrorInvalidMessage =='')
						$strPErrorInvalidMessage = _ERR_NONNUMERIC;
						$this->strErrorMessage = $strPErrorInvalidMessage;
					return false;
				}
			}else {
				if($strPErrorBlankMessage =='')
						$strPErrorBlankMessage = _ERR_NUMERICFIELDEMPTY;
						$this->strErrorMessage = $strPErrorBlankMessage;
					return false;
			}
				return true;
		}

		#FUNCTION TO CHECK THAT A NUMBER IS VALID OR NOT

        function checkValidCCNumber($intPNumber){
             $intPNumber =  trim($intPNumber);
			if(!empty($intPNumber)){

				if(!eregi("^[0-9]{14,16}$",trim($intPNumber))){

					$this->strErrorMessage = _ERR_INVALID_CC_NUMBER;

					return false;
				}

				return true;
			}

			$this->strErrorMessage = _ERR_INVALID_CC_NUMBER;

			return false;
		}	
		
		
		#FUNCTION TO CHECK THAT A NUMBER IS VALID OR NOT IF FIELD IS OPTIONAL

        function checkValidOPTNumber($intPNumber){

			if(trim($intPNumber) != ""){

				if(!eregi("^[0-9]{1,15}$",trim($intPNumber))){

					$this->strErrorMessage = ERR_NONNUMERIC;

					return false;
				}

				return true;
			}

			return true;
		}


		#FUNCTION TO CHECK WHETHER A FIELD STRING IS VALID OR NOT

      	function checkValidAddress($strPStringName,$strPInvalidMessage='',$strPBlankMessage='',$blPRequired=false){

			if(trim($strPStringName) != ""){

				if(!eregi("^[0-9a-zA-Z\[:space:]\#\.\:\,]{1,200}$",trim($strPStringName))){
					
					 if($strPInvalidMessage)	
					 	$this->strErrorMessage = $strPInvalidMessage;
					 else 
						$this->strErrorMessage = ERR_INVALID_ADDRESS;

					 return false;
				}

				return true;
			}

			if($blPRequired){
				if(trim($strPStringName) == ""){
					if($strPBlankMessage)
						$this->strErrorMessage = $strPBlankMessage;
					else 
						$this->strErrorMessage = ERR_FIELD_BLANK;
					return false;	
				}	
			}
			else 
				return true;	
			
		}


	 #FUNCTION TO CHECK WHETHER A valid url  IS VALID OR NOT
		function isValidUrl($var)
		{
			//if(eregi("^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]", $var ))
			if(eregi("^(http:\/\/www\.|https:\/\/www\.|ftp:\/\/|www\.|www\.)+([0-9a-zA-Z])+(\.{1}[0-9a-zA-Z]{1,3}){1}(\/{1}[\w]+)*$", trim($var)))
			{
				return true;
			}
				else
			{
				$this->strErrorMessage = ERR_WEBSITE_VALID;
				return false;
			}
		}




		#FUNCTION TO CHECK WHETHER A FIELD STRING IS VALID OR NOT

      	function checkValidNotes($strPStringName){

			if(trim($strPStringName) != ""){

				if(!eregi("^[0-9a-zA-Z\[:space:]\#\.\,]$",trim($strPStringName))){

					$this->strErrorMessage = _ERR_FIELD_INVALID;

					return false;
				}

				return true;
			}

			$this->strErrorMessage = _ERR_FIELD_BLANK;

			return false;
		}

	

		#FUNCTION TO CHECK WHETHER A CONTACT FIELD STRING IS VALID OR NOT

      	function checkValidContact($strPStringName){

			if(trim($strPStringName) != ""){

				if(!eregi("^[0-9a-zA-Z\[:space:]]{1,100}$",trim($strPStringName))){

					$this->strErrorMessage = _ERR_FIELD_INVALID;

					return false;
				}

				return true;
			}

			$this->strErrorMessage = _ERR_FIELD_BLANK;

			return false;
		}

		#FUNCTION TO CHECK WHETHER A CITY FIELD STRING IS VALID OR NOT

      	function checkValidCity($strPStringName,$blPRequired=false,$strPMessage=''){

			if(trim($strPStringName) != ""){

				if(!eregi("^[0-9a-zA-Z\[:space:]]{1,50}$",trim($strPStringName))){
					$this->strErrorMessage = ERR_USER_CITY_INVALID;
					return false;
				}
				return true;
			}
			if($blPRequired) {
				if(trim($strPStringName) == "") {
					if($strPMessage)
						$this->strErrorMessage = $strPMessage;
					else 
						$this->strErrorMessage = ERR_FIELD_BLANK;	
					return false;
				}	
			}	
			else 
				return true;
		}

		#FUNCTION TO CHECK WHETHER A COMPANY NAME FIELD STRING IS VALID OR NOT

      	function checkValidCompanyName($strPStringName){

			if(trim($strPStringName) != ""){

				if(!eregi("^[0-9a-zA-Z\[:space:]]{1,30}$",trim($strPStringName))){

					$this->strErrorMessage = _ERR_USER_COMPANY_INVALID;

					return false;
				}

				return true;
			}

			$this->strErrorMessage = _ERR_FIELD_BLANK;

			return false;
		}

		#FUNCTION TO CHECK WHETHER A FIELD STRING HAS VALID URL OR NOT

      	function checkValidUrlAddress($strPStringName){

			if(trim($strPStringName) != ""){

				if(!eregi("^[\'+\/0-9A-Z^_\`a-z{|}~\-]+(\.[a-zA-Z0-9_\-]+)+(\.[a-zA-Z0-9_\-]+){1,3}$",trim($strPStringName))){

					$this->strErrorMessage = _ERR_FIELD_URL;

					return false;
				}

				return true;
			}

			$this->strErrorMessage = _ERR_FIELD_BLANK;

			return false;
		}

		#FUNCTION TO CHECK WHETHER THE ENTERED AMOUNT IS PROPER OR NOT

		function checkValidAmount($intPAmount,$strPErrorBlankMessage='',$strPErrorInvalidMessage=''){
			
			if(trim($intPAmount) != ""){
			//	if(!eregi("^[0-9\.]{1,11}$",trim($intPAmount))){
			/*	
				
				Modified by 		: Amardeep Singh
				Modified on 		: 29-12-2006
				
			*/
				if(!eregi("^[0-9]{0,11}.\..[0-9]{0,1}$",trim($intPAmount)) && !eregi("^[0-9]{0,11}$",trim($intPAmount))){
					
					if($strPErrorInvalidMessage =='')
						$strPErrorInvalidMessage = _ERR_FIELD_INVALID;
					
					$this->strErrorMessage = $strPErrorInvalidMessage;
					return false;
				}
			}else{
					if($strPErrorBlankMessage =='')
						$strPErrorBlankMessage = _ERR_FIELD_BLANK;
						
					$this->strErrorMessage = $strPErrorBlankMessage;
					return false;
			}
			return true;
		}

		#FUNCTION TO CHECK WHETHER THE ENTERED TIME IS PROPER OR NOT

		function checkValidTime($intPTime,$strPErrorBlankMessage='',$strPErrorInvalidMessage=''){
			
			if(trim($intPTime) != ""){
			//	if(!eregi("^[0-9\.]{1,11}$",trim($intPAmount))){
			/*	
				
				Modified by 		: Amardeep Singh
				Modified on 		: 08-03-2007
				
			*/
				if(!eregi("^([0-1][0-9]|[2][0-3]):([0-5][0-9])$",trim($intPTime))){
					
					if($strPErrorInvalidMessage =='')
						$strPErrorInvalidMessage = _ERR_FIELD_INVALID;
					
					$this->strErrorMessage = $strPErrorInvalidMessage;
					return false;
				}
			}else{
					if($strPErrorBlankMessage =='')
						$strPErrorBlankMessage = _ERR_FIELD_BLANK;
						
					$this->strErrorMessage = $strPErrorBlankMessage;
					return false;
			}
			return true;
		}

		#FUNCTION TO CHECK WHETHER THE ENTERED AMOUNT IS PROPER OR NOT

		function checkValidOPTAmount($intPAmount){

			if(trim($intPAmount) != ""){

				if(!eregi("^[0-9\.]{1,11}$",trim($intPAmount))){

					$this->strErrorMessage = _ERR_INVALID_AMOUNT ;

					return false;
				}

				return true;
			}
     		return true;
		}

		#FUNCTION TO CHECK WHETHER THE ENTERED AMOUNT IS PROPER OR NOT

		function checkValidPercent($intPAmount){

			if(trim($intPAmount) != ""){

				if(!eregi("^[0-9\.]{1,11}$",trim($intPAmount))){

					$this->strErrorMessage = _ERR_INVALID_PERCENT ;

					return false;
				}elseif(ceil($intPAmount)>100){
				    $this->strErrorMessage = _ERR_INVALID_PERCENT ;
					return false;
				}
                  
				return true;
			}

			$this->strErrorMessage = _ERR_AMOUNT_BLANK ;
			return false;
		}


		#FUNCTION TO CHECK FOR VALID PHONE NUMBER

		function validPhoneNumber($strPNumber){

			if(trim($strPNumber) != ""){
				if(!eregi("^[0-9\-]{6,15}$",trim($strPNumber))){
					 $this->strErrorMessage = ERR_PHONE_VALID;					
					return false;
				}
				return true;
			}

			$this->strErrorMessage = ERR_PHONE_BLANK;

			return false;
		}
		
		function optionalValidPhoneNumber($strPNumber){

			if(trim($strPNumber) != ""){
				if(!eregi("^[0-9\-]{6,15}$",trim($strPNumber))){
					 $this->strErrorMessage = ERR_PHONE_VALID;					
					return false;
				}
				return true;
			}

			

			return false;
		}
		
		function validaverageprice($strPNumber){

			if(trim($strPNumber) != ""){
				if(!eregi("^[0-9\-]{1,15}$",trim($strPNumber))){
					 $this->strErrorMessage = ERR_PHONE_VALID;					
					return false;
				}
				return true;
			}

			$this->strErrorMessage = ERR_PHONE_BLANK;

			return false;
		}
		 
		
		#OPTIONAL FUNCTION TO CHECK PHONE NUMBER

		function validPriceNumber($strPNumber){

			if(trim($strPNumber) != ""){
				if(!eregi("^[0-9\-]{6,15}$",trim($strPNumber))){
					 $this->strErrorMessage = ERR_PHONE_VALID;					
					return false;
				}
				return true;
			}

			$this->strErrorMessage = ERR_PHONE_BLANK;

			return false;
		}
		
		


		#FUNCTION TO CHECK THAT A NUMBER IS VALID OR NOT

        function validCheckNumber($intPNumber){
             $intPNumber =  trim($intPNumber);
			if(!empty($intPNumber)){

				if(!eregi("^[0-9]{7,7}$",trim($intPNumber))){

					$this->strErrorMessage = _ERR_NONNUMERIC;

					return false;
				}

				return true;
			}

			$this->strErrorMessage = _ERR_NUMERICFIELDEMPTY;

			return false;
		}
		
		#FUNCTION TO CHECK WHETHER A YEAR IS VALID OR NOT
		
		function checkYear($intPYear,$strPErrorBlankMessage='',$strPErrorInvalidMessage=''){
			if(trim($intPYear) != "") { 
			if(!eregi("^[1|2]{1}[9|0]{1}[0-9]{2}$",trim($intPYear))){
					
						if($strPErrorInvalidMessage =='')
						$strPErrorInvalidMessage = _ERR_FIELD_INVALID;
						$this->strErrorMessage = $strPErrorInvalidMessage;
						return false;
					}
				else if(!ereg("19", $intPYear)){
					if(strtotime($intPYear) > strtotime(date("Y"))) {
						if($strPErrorInvalidMessage =='')
							$strPErrorInvalidMessage = _ERR_FIELD_INVALID;
							$this->strErrorMessage = $strPErrorInvalidMessage;
							return false;
						}
					
				}
						
			}else{
					if($strPErrorBlankMessage =='')
					$strPErrorBlankMessage = _ERR_FIELD_BLANK;
						
					$this->strErrorMessage = $strPErrorBlankMessage;
					return false;
			}
			return true;
		}
		
		
		#FUNCTION TO CHECK WHETHER A COMPANY NAME FIELD STRING IS VALID OR NOT

      /*	function checkValidPlanName($strPStringName){

			if(trim($strPStringName) != ""){

				if(!eregi("^[0-9a-zA-Z\[:space:]]{1,30}$",trim($strPStringName))){

					$this->strErrorMessage = _ERR_PLAN_NAME_INVALID;

					return false;
				}

				return true;
			}

			$this->strErrorMessage = _ERR_PLAN_NAME_BLANK;

			return false;
		}*/


		function getErrorMessage(){

			return $this->strErrorMessage;
		}
		
		function checkAplhanumericStringNameWithSpace($strPStringName,$strPErrorBlankMessage='',$strPErrorInvalidMessage=''){
			if(trim($strPStringName) != "") { 
				if(!eregi("^[a-zA-Z0-9\[:space:]]{1,30}$",trim($strPStringName))){
					
					if($strPErrorInvalidMessage =='')
						$strPErrorInvalidMessage = _ERR_FIELD_INVALID;
						$this->strErrorMessage = $strPErrorInvalidMessage;
						return false;
					}
					
			}else{
					if($strPErrorBlankMessage =='')
					$strPErrorBlankMessage = _ERR_FIELD_BLANK;
						
					$this->strErrorMessage = $strPErrorBlankMessage;
					return false;
			}
			return true;
		}
   }
?>