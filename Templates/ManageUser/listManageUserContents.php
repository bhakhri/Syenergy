<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR USER LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (1.07.2008)
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
                    <!--            <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddUser',315,250);blankValues();return false;" />&nbsp;</td>--></tr>
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

<?php floatingDiv_Start('AddUser','Add User'); ?>
<form name="AddUser" action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Employee: </b></nobr></td>
        <td width="79%" class="padding">
            <select size="1" class="selectfield" name="employeeId" id="employeeId" >
            <option>Select</option>
            <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getEmployeeActive();
              ?>
            </select>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>User Name<?php echo REQUIRED_FIELD;?>:</b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="userName" name="userName" class="inputbox" maxlength="50" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>User Password<?php echo REQUIRED_FIELD;?>:</b></nobr></td>
        <td width="79%" class="padding"><input type="password" id="userPwd" name="userPwd" class="inputbox" maxlength="50"  /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Retype Password<?php echo REQUIRED_FIELD;?>:</b></nobr></td>
        <td width="79%" class="padding"><input type="password" id="userPwd2" name="userPwd2" class="inputbox" maxlength="50" /></td>
    </tr>
    <!--tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Default Role<?php echo REQUIRED_FIELD;?>:</b></nobr></td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="role" id="role" onChange="roleCheck(this.value)">
              <?php
                 // require_once(BL_PATH.'/HtmlFunctions.inc.php');
                 // echo HtmlFunctions::getInstance()->getRoleData($REQUEST_DATA['role']==''? $manageUserRecordArray[0]['roleId'] : $REQUEST_DATA['role']," WHERE roleId NOT IN (2,3,4,5) " );
              ?>
        </select>
        </td>
    </tr>
	<tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Role<?php echo REQUIRED_FIELD;?>:</b></nobr></td>
         
    </tr-->
	<tr>
         
        <td colspan="2" class="padding">
		<div id="scroll2" style="overflow:auto;HEIGHT:160px">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr class="field1_heading">
			<td height="25" class="padding">Role Name</td>	
			<td class="padding">Default Role</td>	
		</tr>
		<?php
		  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
		  //echo HtmlFunctions::getInstance()->getRoleCheckboxData(" WHERE roleId NOT IN(3,4)");
		?>
		 
		</table>
		</div>
        </td>
    </tr>
    
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Display Name: </b></nobr></td>
        <td width="79%" class="padding">
            <input type="text" id="displayName" name="displayName" class="inputbox" maxlength="255" />
        </td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddUser');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditUser','Edit User '); ?>
<form name="EditUser" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>User Name<?php echo REQUIRED_FIELD;?></b></nobr></td>
		<td class="contenttab_internal_rows"><b>:</b></nobr></td>
        <td width="79%" class="padding"><input type="hidden" name="userId" id="userId" value="" /><input type="hidden" name="defaultRoleId" id="defaultRoleId" value="" />  <input type="text" id="userName" name="userName" class="inputbox" maxlength="50" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>User Password<?php echo REQUIRED_FIELD;?></b></nobr></td>
		<td class="contenttab_internal_rows"><b>:</b></nobr></td>
        <td width="79%" class="padding"><input type="password" id="userPwd" name="userPwd" class="inputbox" maxlength="50" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Retype Password<?php echo REQUIRED_FIELD;?></b></nobr></td>
		<td class="contenttab_internal_rows"><b>:</b></nobr></td>
        <td width="79%" class="padding"><input type="password" id="userPwd2" name="userPwd2" class="inputbox" maxlength="50" /></td>
    </tr>
	<tr id="showDisplayName">    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Display Name</b></nobr></td>
		<td class="contenttab_internal_rows"><b>:</b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="displayName" name="displayName" class="inputbox" maxlength="255" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Active<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="contenttab_internal_rows"><nobr>:&nbsp;</td>
        <td width="79%" class="padding">
        <input type="radio" name="isActive" id="isActive3" value="1">Yes&nbsp; 
        <input type="radio" name="isActive" id="isActive4" value="0" >No
        </nobr>
        </td>
    </tr>
    <!--tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Role Name<?php echo REQUIRED_FIELD;?>:</b></nobr></td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="role" id="role">
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getRoleData($REQUEST_DATA['role']==''? $manageUserRecordArray[0]['roleId'] : $REQUEST_DATA['role'] ,"");
              ?>
        </select>
       </td>
    </tr-->
	<tr id="showRole">
        <td colspan="3" class="padding">
		<div id="scroll2" style="overflow:auto;HEIGHT:120px">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr class="field1_heading">
			<td height="25" class="padding">Role Name</td>	
			<td class="padding">Default Role</td>	
		</tr>
		<?php
		  require_once(BL_PATH.'/HtmlFunctions.inc.php');
		  echo HtmlFunctions::getInstance()->getRoleCheckboxData(" WHERE roleId NOT IN(1,3,4)");
		?>
		</table>
		</div>
        </td>
    </tr>
	<tr id="showOnlyRole" style='display:none'>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Role Name</b></nobr></td>
		<td class="contenttab_internal_rows"><b>:</b></nobr></td>
        <td width="79%" class="padding"><div id="showOtherRole"></div></td>
    </tr>
    <!--tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Employee : </b></nobr></td>
        <td width="79%" class="padding">
            <select size="1" class="selectfield" name="employeeId" id="employeeId">
            <option>Select</option>
            <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getEmployeeActive();
              ?>
            </select>
        </td>
    </tr-->

    <tr>
	    <td height="5px"></td>
	</tr>
	<tr>
		<td align="center" style="padding-right:10px" colspan="3"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" /><input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditUser');return false;" /></td>
	</tr>
	<tr>
		<td height="5px"></td></tr>
	<tr>
</table>
</form> 
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->


<?php
// $History: listManageUserContents.php $
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/ManageUser
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/03/09    Time: 12:30p
//Updated in $/LeapCC/Templates/ManageUser
//Gurkeerat: resolved issue 1395
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 09-08-21   Time: 12:50p
//Updated in $/LeapCC/Templates/ManageUser
//Added ACCESS right DEFINE in these modules
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 28/07/09   Time: 17:53
//Updated in $/LeapCC/Templates/ManageUser
//Added "userStatus" field in manage user module and added the check in
//login page that if a user is in active then he/she can not login
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/01/09    Time: 11:17a
//Updated in $/LeapCC/Templates/ManageUser
//Updated manage user module in which multiple role can be selected to
//single user
//
//*****************  Version 4  *****************
//User: Administrator Date: 13/06/09   Time: 18:59
//Updated in $/LeapCC/Templates/ManageUser
//Corredted issues which are detected during user documentation
//preparation
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/28/09    Time: 4:40p
//Updated in $/LeapCC/Templates/ManageUser
//New File Added in displayName
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/08/08   Time: 5:15p
//Updated in $/LeapCC/Templates/ManageUser
//employee Id code set
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/ManageUser
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 10/24/08   Time: 11:46a
//Updated in $/Leap/Source/Templates/ManageUser
//Added functionality for manage user report print
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 10/20/08   Time: 6:35p
//Updated in $/Leap/Source/Templates/ManageUser
//Added restriction for "management" user
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 10/01/08   Time: 2:38p
//Updated in $/Leap/Source/Templates/ManageUser
//Corrected Problem of User Editing
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/25/08    Time: 4:34p
//Updated in $/Leap/Source/Templates/ManageUser
//Corrected javascript validations
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/ManageUser
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/ManageUser
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:48p
//Updated in $/Leap/Source/Templates/ManageUser
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/16/08    Time: 3:53p
//Updated in $/Leap/Source/Templates/ManageUser
//Corrected colspan of paging row
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/ManageUser
//corrected breadcrumb and reset button height and width
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/24/08    Time: 12:00p
//Updated in $/Leap/Source/Templates/ManageUser
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/01/08    Time: 7:34p
//Updated in $/Leap/Source/Templates/ManageUser
//Created ManageUser Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/01/08    Time: 4:09p
//Created in $/Leap/Source/Templates/ManageUser
//Initial Checkin
?>