<?php 
//This file creates Html Form output in ChangePassword Module 
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
                <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
                <td valign="top" align="right">   
               </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
            
        </td>
    </tr>
    
    </table>
    
	
<?php floatingDiv_Start('ChangePassword','Change Password'); ?>
<form name="addPassword" action="" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

<tr>
    <td width="21%" class="contenttab_internal_rows"><nobr><b>Current Password<?php echo REQUIRED_FIELD;?></b></nobr></td>
    <td width="79%" class="padding"><nobr>: 
    <input type="password" id="currentPassword" name="currentPassword" class="inputbox" maxlength="50"/></td>
</tr>
<tr>    
    <td width="21%" class="contenttab_internal_rows"><nobr><b>New Password<?php echo REQUIRED_FIELD;?></b></nobr></td>
    <td width="79%" class="padding"><nobr>: 
    <input type="password" id="newPassword" name="newPassword" class="inputbox" maxlength="50"/></td>
</tr>
<tr>    
    <td width="21%" class="contenttab_internal_rows"><nobr><b>Confirm Password<?php echo REQUIRED_FIELD;?></b></nobr></td>
    <td width="79%" class="padding"><nobr>: 
    <input type="password" id="confirmPassword" name="confirmPassword" class="inputbox" maxlength="50"/></td>
</tr>

<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
                    <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ChangePassword');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td>
</tr>

  
</table>
</form>

<?php floatingDiv_End(); 
//$History: listChangePasswordContents.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher
//Corrected look and feel of teacher module logins
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/16/08    Time: 4:22p
//Created in $/Leap/Source/Templates/Teacher
?>
