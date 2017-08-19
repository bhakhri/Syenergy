<?php	
	// global $FE;
	// require_once($FE . "/Library/common.inc.php");
	// require_once(BL_PATH . "/UtilityManager.inc.php");	
	require_once($FE . "/Model/form_default.inc.php");
	// echo $FE." ".BL_PATH." ".SERVER_URL." ".$_POST["path"];
	$type=$_POST["type"];
	$path=$_POST["path"];
	if($type == 'update'){
		$data = $_POST["data"];
		$num_rows = form_default::getInstance()->num_of_rows($path);
		if($num_rows > 0){
			print_r(form_default::getInstance()->update($data,$path));
		}else{
			print_r(form_default::getInstance()->insert($data,$path));
		}
	}elseif($type == 'select'){
		print_r(form_default::getInstance()->disp_rec($path));
	}
	$data='';
	$path='';
	
?>