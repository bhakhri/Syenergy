<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR ROLE LISTING
//
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
        <td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr height="30">
                                <td class="contenttab_border" height="20" style="border-right:0px;">
                                    <?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
                                </td>
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddRole',320,250);blankValues();return false;" />&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                            </tr>
             <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
                                            </td>
                    </tr>
                    </table>
                </td>
             </tr>
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    <!--Start Add Div-->

<?php floatingDiv_Start('AddRole','Add Role'); ?>
<form name="AddRole" action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Role Name<?php echo REQUIRED_FIELD; ?>:</b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="roleName" name="roleName" class="inputbox" maxlength="50" /></td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddRole');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditRole','Edit Role'); ?>
<form name="EditRole" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="roleId" id="roleId" value="" />
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Role Name<?php echo REQUIRED_FIELD; ?>:</b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="roleName" name="roleName" class="inputbox" maxlength="50" /></td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
        <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditRole');return false;" />
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->

<!--Start User Details Div-->
<?php floatingDiv_Start('UserDetailDiv','','3'); ?>
<input type="hidden" id="roleId1" value="" />
<input type="hidden" id="roleName1" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr><td height="5px"></td></tr>
<tr>
 <td>
  <div id="userDetailsContentsDiv" style="max-width:700px;max-height:300px;overflow:auto;vertical-align:top;"></div>
 </td>
</tr>
<tr><td height="5px"></td></tr>
<tr>
 <td align="center">
  <input type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printUserReport()">&nbsp;
  <input type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="exportUserReport()">
 </td>
</tr>
</table>
<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->


<!--Start Role Permission Div-->
<?php floatingDiv_Start('UserPermissionDiv','','4'); ?>
<input type="hidden" id="roleId2" value="" />
<input type="hidden" id="roleName2" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr><td height="5px"></td></tr>
<tr>
 <td>
  <div id="userPermissionsContentsDiv" style="max-width:550px;max-height:300px;overflow:auto;vertical-align:top;"></div>
 </td>
</tr>
<tr><td height="5px"></td></tr>
<tr>
 <td align="center">
  <input type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printPermissionReport()">&nbsp;
  <input type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="exportPermissionReport()">
 </td>
</tr>
</table>
<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->

<!--Start Role Permission Div-->
<?php floatingDiv_Start('CopyPermissionDiv','Copy Permissions','4'); ?>
<?php
	require_once(BL_PATH . "/HtmlFunctions.inc.php");
	$htmlFunctions = HtmlFunctions::getInstance();
?>
<form name="copyPermissionForm" method="post" action="" onsubmit="return false">
<input type="hidden" name="roleId" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr>
		<td valign="top" colspan="2" class="" height="5">

		</td>
	</tr>
	<tr>
		<td valign="top" colspan="1" class="">From :</td>
		<td valign="top" colspan="1" class="">
			<select name='copyFrom' class='selectfield' style="width:150px;" onchange="changeCopyTo();">
			<?php echo $instituteArray = $htmlFunctions->getInstituteData(); ?>
			</select>
		</td>
		<td valign="top" colspan="1" class="">&nbsp;To :</td>
		<td valign="top" colspan="1" class="">
		<select name='copyTo' multiple size="5" class='selectfield' style="width:150px;">
		<?php echo $instituteArray = $htmlFunctions->getInstituteData(); ?>
		</select>
		</td>
	</tr>
	<tr>
		<td valign="top" colspan="4" class="" align="center">
			<input type="image" src="<?php echo IMG_HTTP_PATH ?>/save1.gif" border="0" onClick="copyRolePermission()">&nbsp;
		</td>
	</tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->


<?php
// $History: listRoleContents.php $
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Role
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 16/12/09   Time: 12:51
//Updated in $/LeapCC/Templates/Role
//Corrected DIV width
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 15/12/09   Time: 18:46
//Updated in $/LeapCC/Templates/Role
//Made UI changes in Role Master module
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 2/09/09    Time: 11:08
//Updated in $/LeapCC/Templates/Role
//Done bug fixing.
//Bug ids---
//00001398,00001399,00001400,00001401
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/12/09    Time: 6:35p
//Updated in $/LeapCC/Templates/Role
//change the breadcrumb
//
//*****************  Version 2  *****************
//User: Administrator Date: 13/06/09   Time: 18:59
//Updated in $/LeapCC/Templates/Role
//Corredted issues which are detected during user documentation
//preparation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Role
//
//*****************  Version 9  *****************
//User: Parveen      Date: 11/05/08   Time: 1:32p
//Updated in $/Leap/Source/Templates/Role
//add link is enable
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 10/24/08   Time: 11:31a
//Updated in $/Leap/Source/Templates/Role
//Added functionality for role report print
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Role
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/29/08    Time: 12:54p
//Updated in $/Leap/Source/Templates/Role
//Removed add link (new role creation is disabled)
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/Role
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:48p
//Updated in $/Leap/Source/Templates/Role
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/Role
//corrected breadcrumb and reset button height and width
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/10/08    Time: 5:23p
//Updated in $/Leap/Source/Templates/Role
//Created Role(Role Master) Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 2:59p
//Created in $/Leap/Source/Templates/Role
//Initial checkin
?>