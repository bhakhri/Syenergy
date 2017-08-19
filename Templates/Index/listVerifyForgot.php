<?php
//--------------------------------------------------------  
// Purpose: This file contains HTMl Output for the Forgot Password Module
//
// Author:Parveen Sharma
// Created on : 15.12.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------      

require_once(BL_PATH . '/HtmlFunctions.inc.php');
?>
<table width="1000px" border="0" cellspacing="0" cellpadding="0" align="center" height="550">
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
    <td align="center" valign="top">
                <table width="406" border="0" cellspacing="0" cellpadding="0">
                   <tr>
                        <td colspan="4" align="center" class="text_loginpanel">
                        <?php if($errorVariable=='1'){
                            echo "<label class='redColor'>".TECHNICAL_PROBLEM."</label>";
                        } 
                        if($userCheckVariable=='1'){ 
                            echo "<label class='redColor'>".INVALID_VERIFICATION_CODE."</label>"; 
                        } if($mail=='1'){
                            echo "<label class='redColor'>Mail Sent Successfully to '".$mailAdd."' </label>";
                        }
                        ?>
                       </td>
                   </tr>
                   <tr>
                        <td align="center" colspan="4">
                        <?php
                        //require_once(BL_PATH."/HtmlFunctions.inc.php");
                        //echo HtmlFunctions::getInstance()->tableBlueHeader($title='Forgot Password',$width='width="406"');
                        
                        //if(UtilityManager::notEmpty($errorMessage)) {
                        //echo '<span class="error">'.$errorMessage.'<br/></span>';
                        //}
                        ?>            
                        <table width="100%" border="0" cellspacing="2" cellpadding="4">
                            <tr>
                                <td colspan="2" style="background-repeat:repeat-x;"></td>
                            </tr>
                            <tr height="25">
                                <td width="37%" align="left" class="text_loginpanel" ></td>
                                <td width="63%" align="left" class="text_loginpanel"><a href="index.php" class="redLink">Back to Home Page</a>
                                </td>
                            </tr>
                        </table>
                        <?php
                        //echo HtmlFunctions::getInstance()->tableBlueFooter();
                        ?>
                        </td>
                   </tr>
                   <tr>
                   <td height="125" colspan="3" align="center" style="background-repeat:repeat-x; background-position:top; padding-top:20px;"></td>
                   </tr>
                </table>
              </td>
</tr>
<tr>
     <td align="center" class="text_menu" valign="middle">&laquo; Powered By syenergy Technologies Pvt Ltd &raquo;</td>
</tr>  
</table>
<?php //$History: listVerifyForgot.php $ 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/02/09    Time: 12:14p
//Updated in $/LeapCC/Templates/Index
//message format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/23/08   Time: 12:46p
//Updated in $/LeapCC/Templates/Index
//html format settings
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/15/08   Time: 12:50p
//Created in $/LeapCC/Templates/Index
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/15/08   Time: 12:23p
//Created in $/Leap/Source/Templates/Index
//file added
//

?>