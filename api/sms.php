<?php
//No-time-limit imposed for execution 
set_time_limit(0); 

//The root path of the front end site.
$siteAddress = dirname(__FILE__);    
$siteAddress = substr($siteAddress,0,strlen(str_replace("MobileApi","",$siteAddress))-1);  

$url = $siteAddress.'/Library/common.inc.php';
require_once("$url"); //includes and evaluates the specified file during the execution of the script

//connection to database
$conn =mysqli_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect:' . mysqli_error($conn));
mysqli_select_db($conn,DB_NAME) or die(mysqli_error($conn));

if(isset($_REQUEST)){
		
		$postIndex=array_keys($_REQUEST);
		$post=$postIndex[0]."=".$_REQUEST[$postIndex[0]];
		for($i=1;$i<count($postIndex);$i++){
			
			$post.="!!~~!!".$postIndex[$i]."=".$_REQUEST[$postIndex[$i]];
		
		
		}
		$url=$_SERVER['HTTP_REFERER'];
		$query = "INSERT INTO smsResponse
							(responseUrl,responseData)
					VALUES 
					('".$url."','".$post."')";
		mysqli_query($conn,$query);
		mysqli_close($conn);
}

if(isset($_REQUEST['sSender'])&&$_REQUEST['sSender']!=""&&isset($_REQUEST['sMobileNo'])&&$_REQUEST['sMobileNo']!=""&&isset($_REQUEST['sStatus'])&&$_REQUEST['sStatus']!=""&&isset($_REQUEST['sMessageId'])&&$_REQUEST['sMessageId']!=""&&isset($_REQUEST['dtDone'])&&$_REQUEST['dtDone']!=""&&isset($_REQUEST['dtSubmit'])&&$_REQUEST['dtSubmit']!=""){
	
	$msgId=$_REQUEST['sMessageId'];
	$smsStatus="";
	
	if(trim($_REQUEST['sStatus'])=='DELIVRD'){
		$smsStatus=2; 
	}
	elseif(trim($_REQUEST['sStatus'])=='UNDELIV'){
		$smsStatus=3; 
	}
	elseif(preg_match("/stat:DND/i",$response)){
		$smsStatus=4;
	}
	else{
		$smsStatus=1;
	}
	
	if($smsStatus==1){
		$query = "update sms_messages set smsStatus='".$smsStatus."' where msgId='".$msgId."'";
	}
	else{
		$query = "update sms_messages set smsStatus='".$smsStatus."',updateBit=1 where msgId='".$msgId."'";
	}
	mysqli_query($conn,$query);
	mysqli_close($conn);
	echo "Status:200"



}
else{
		echo "<html>
					<head>
						<title>403 Forbidden</title>
					</head>
					<body>

						<p>Access is forbidden.</p>

					</body>
				</html>";
}








