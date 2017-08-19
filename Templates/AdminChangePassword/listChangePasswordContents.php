<?php 
//This file creates Html Form output in ChangePassword Module 
//
// Author :Rajeev Aggarwal
// Created on : 22-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
               <!-- <td valign="top">Change Password</td>
                <td valign="top" align="right"> -->
                  <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?>    
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
<!--<table>
 <tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
			<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
    
    </table> -->
    
	
<?php floatingDiv_Start('ChangePassword','Change Password'); ?>
<form name="addPassword" action="" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

<tr>
    <td width="21%" class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Current Password</b></nobr></td>
	<td class="contenttab_internal_rows"><B>:</B></td>
    <td width="79%" class="padding"><input type="password" id="currentPassword" name="currentPassword" class="inputbox" maxlength="50"/></td>
</tr>
<tr>    
    <td width="21%" class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>New Password</b></nobr></td>
	<td class="contenttab_internal_rows"><B>:</B></td>
    <td width="79%" class="padding"><input type="password" id="newPassword" name="newPassword" class="inputbox" maxlength="50"/></td>
</tr>
<tr>    
    <td width="21%" class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Confirm Password</b></nobr></td>
	<td class="contenttab_internal_rows"><B>:</B></td>
    <td width="79%" class="padding"><input type="password" id="confirmPassword" name="confirmPassword" class="inputbox" maxlength="50"/></td>
</tr>

<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
                    <?php if(CURRENT_PROCESS_FOR=='sc'){ ?>
					<input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ChangePassword');location.href='scIndex.php';return false;" />
					<?php } if(CURRENT_PROCESS_FOR=='cc'){ ?>
					<input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ChangePassword');location.href='index.php';return false;" />
					<?php } ?>
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
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/AdminChangePassword
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Templates/AdminChangePassword
//Updated with Required field, centralized message, left align
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/22/08   Time: 5:39p
//Created in $/LeapCC/Templates/AdminChangePassword
//Intial Checkin
?>
