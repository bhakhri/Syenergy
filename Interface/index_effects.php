<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>User welcome</title>
<link href="<?php print HTTP_PATH?>/CSS/adminCss.css" rel="stylesheet" type="text/css" media="all">
<?php print UtilityManager::includeJS('spryEffects.js');?>
</head>
<body>
<?php
require_once(TEMPLATES_PATH."/Users/header.php"); 
?>

<table width="80%" align="center" border="1" cellpadding="0" cellspacing="0">
<tr><td>
<div id="blah" style="display:none;position:relative;height:200px;background-color:#ffAABB;">
<table align="center" >
	<tr>
		<td align="center"><strong>W<strong>elcome<br>Please follow above links to proceed</td>
	</tr>
</table>
</div>
</td></tr>
<tr><td>
<table align="center">
	<tr>
		<td align="center"><strong>C<strong>lick <a onclick=" setTimeout('Spry.Effect.DoBlind(\'blah\', {duration: 1000, from:  \'0%\', to:  \'100%\', toggle: true})',1000);" href="#">>here</a> to see fade effect, <a onclick="Spry.Effect.DoBlind('blah', {duration: 1000, from:  '100%', to:  '0%', toggle: true}); return false;" href="#">Vertical hide</a>  <a onclick="Spry.Effect.DoGrow('blah',{duration:700, from:'100%', to:'0%',toggle: true}); return false;" href="#">Shrink</a> <a onclick="Spry.Effect.DoSquish('blah'); return false;" href="#">left disappear</a>   <a onclick="Spry.Effect.DoShake('blah'); return false;" href="#">Shake it!</a></td>
	</tr>
</table>
</td></tr></table>
<script type="text/javascript" language="javascript1.7">
//Spry.Effect.DoFade('blah', {duration: 1000, from:  100, to:  0, toggle: true});
function hideBlock(){
	Spry.Effect.DoBlind('blah', {duration: 1000, from:  '100%', to:  '0%', toggle: true});
}
var fadeElement = new Spry.Effect.Fade("blah", {duration:1000, from:100, to:0, toggle:true});
function hide(id){
	var el = document.getElementById(id);
	if(el){
		el.style.display = "";
	
	}

}
</script>
<?php
require_once(TEMPLATES_PATH."/Users/footer.php");
?>