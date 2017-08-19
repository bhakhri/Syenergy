<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Page Not Found</title>

<?php require_once(TEMPLATES_PATH .'/jsCssHeader.php'); ?>
 
</head>
<body>
<table width="1000px" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="245" class="logo"></td>
        <td></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="5" bgcolor="#73AACC"></td>
  </tr>         
  <tr>
    <td align="center" background="<?php echo IMG_HTTP_PATH;?>/bg.gif" style="background-repeat:repeat-x; background-position:top; padding-top:40px;">&nbsp;</td>
  </tr> 
  <tr>
    <td align="left" valign="top" class="text_class">
<?php 
	echo '<div style="height:300px"><span class="error">Error:</span> '.PAGE_NOT_FOUND.'</div>';
?>
  </td>
</tr>
<tr>
     <td align="center" class="text_menu" valign="middle">&laquo; Powered By ChalkPad Technologies Pvt Ltd &raquo;</td>
</tr>  
</table>
</body>
</html>
