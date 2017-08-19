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
    
	
<?php floatingDiv_Start('ChangePassword','Change Password'); ?>
<form name="addPassword" action="" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

<tr>
    <td width="20%" class="contenttab_internal_rows"><nobr><b>Current Password</b></nobr></td>
    <td width="2%" class="contenttab_internal_rows"><nobr><b>&nbsp;:</b></nobr></td>
    <td width="77%" class="padding"><input type="password" id="currentPassword" name="currentPassword" class="inputbox" maxlength="50"/></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>New Password</b></nobr></td>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;:</b></nobr></td>
    <td class="padding"><input type="password" id="newPassword" name="newPassword" class="inputbox" maxlength="50"/></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>Confirm Password</b></nobr></td>
    <td width="2%" class="contenttab_internal_rows"><nobr><b>&nbsp;:</b></nobr></td>
    <td class="padding"><input type="password" id="confirmPassword" name="confirmPassword" class="inputbox" maxlength="50"/></td>
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
//*****************  Version 2  *****************
//User: Parveen      Date: 11/09/09   Time: 3:58p
//Updated in $/LeapCC/Templates/ChangePassword
//alignment updated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/ChangePassword
//
//*****************  Version 3  *****************
//User: Arvind       Date: 9/17/08    Time: 3:59p
//Updated in $/Leap/Source/Templates/ChangePassword
//added condition for sc module
//
//*****************  Version 2  *****************
//User: Arvind       Date: 9/15/08    Time: 5:49p
//Updated in $/Leap/Source/Templates/ChangePassword
//removed master from breadcrum
//
//*****************  Version 1  *****************
//User: Arvind       Date: 9/09/08    Time: 6:17p
//Created in $/Leap/Source/Templates/ChangePassword
//initial checkin
?>
