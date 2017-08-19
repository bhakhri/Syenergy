<?php 
//This file creates Html Form output in ChangePassword Module 
//
// Author :Jaineesh
// Created on : 09-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            
            <tr>
                <td valign="top">  <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?> </td>
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
    <td width="21%" class="contenttab_internal_rows"><nobr><b>Current Password : </b></nobr></td>
    <td width="79%" class="padding"><input type="password" id="currentPassword" name="currentPassword" class="inputbox" maxlength="50"/></td>
</tr>
<tr>    
    <td width="21%" class="contenttab_internal_rows"><nobr><b>New Password : </b></nobr></td>
    <td width="79%" class="padding"><input type="password" id="newPassword" name="newPassword" class="inputbox" maxlength="50"/></td>
</tr>
<tr>    
    <td width="21%" class="contenttab_internal_rows"><nobr><b>Confirm Password : </b></nobr></td>
    <td width="79%" class="padding"><input type="password" id="confirmPassword" name="confirmPassword" class="inputbox" maxlength="50"/></td>
</tr>

<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
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
//$History: studentChangePasswordContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/19/08    Time: 5:19p
//Updated in $/Leap/Source/Templates/Student
//fixed bugs
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/12/08    Time: 2:32p
//Created in $/Leap/Source/Templates/Student
//to get the template of student password
//

?>
