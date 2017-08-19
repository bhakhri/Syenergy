<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR QUOTA LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddQuota',315,250);blankValues();return false;" />&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                            </tr>
             <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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

<?php floatingDiv_Start('AddQuota','Add Quota'); ?>
<form name="AddQuota" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
       <tr>
          <td width="21%" class="contenttab_internal_rows"><strong><nobr>Quota Name<?php echo REQUIRED_FIELD;?>:</nobr></strong></td>
          <td width="79%" class="padding"><input type="text" id="quotaName" name="quotaName"   class="inputbox" style="width:195px" class="inputbox" maxlength="50" /></td>
        </tr>
        <tr>
          <td class="contenttab_internal_rows"><strong>Quota Abbr.<?php echo REQUIRED_FIELD;?>:</strong></td>
          <td width="79%" class="padding">
          <input type="text" id="quotaAbbr" name="quotaAbbr"  class="inputbox" style="width:195px"  class="inputbox"  maxlength="10" />
         </td>
       </tr>
       <tr>
          <td class="contenttab_internal_rows"><strong>Parent Quota&nbsp;:</strong></td>
          <td width="79%" class="padding">
           <select name="parentQuota" id="parentQuota" class="selectfield" size="1">
           </select>
         </td>
       </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
        <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddQuota');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditQuota','Edit Quota '); ?>
<form name="EditQuota" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
 <input type="hidden" name="quotaId" id="quotaId" value="" /> 
      <tr>
          <td width="21%" class="contenttab_internal_rows"><strong><nobr>Quota Name<?php echo REQUIRED_FIELD;?>:</nobr></strong></td>
          <td width="79%" class="padding"><input type="text" id="quotaName"  class="inputbox" style="width:195px" class="inputbox"  maxlength="50" /></td>
        </tr>
        <tr>
          <td class="contenttab_internal_rows"><strong>Quota Abbr.<?php echo REQUIRED_FIELD;?>:</strong></td>
          <td width="79%" class="padding">
          <input type="text" id="quotaAbbr"  class="inputbox" style="width:195px"  class="inputbox"  maxlength="10" />
         </td>
       </tr>
      <tr>
          <td class="contenttab_internal_rows"><strong>Parent Quota&nbsp;:</strong></td>
          <td width="79%" class="padding">
           <select name="parentQuota" id="parentQuota" class="selectfield" size="1">
           </select>
         </td>
       </tr> 
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="editCancel"  src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditQuota');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->

<?php
// $History: listQuotaContents.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 21/10/09   Time: 11:42
//Updated in $/LeapCC/Templates/Quota
//Done bug fixing.
//bug ids---
//00001796,00001794,00001786,00001630
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 18/06/09   Time: 15:24
//Updated in $/LeapCC/Templates/Quota
//Done bug fixing.
//bug ids---00000113,00000114,00000115,00000141,00000142,
//00000143,00000144,00000146,00000147
//
//*****************  Version 2  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Templates/Quota
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Quota
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:11p
//Updated in $/Leap/Source/Templates/Quota
//Added functionality for quota report print
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Quota
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/Quota
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:48p
//Updated in $/Leap/Source/Templates/Quota
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/Quota
//corrected breadcrumb and reset button height and width
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/14/08    Time: 5:54p
//Updated in $/Leap/Source/Templates/Quota
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/08/08    Time: 1:14p
//Updated in $/Leap/Source/Templates/Quota
//Modified according to Task: 6 
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 7/17/08    Time: 4:56p
//Updated in $/Leap/Source/Templates/Quota
//Added parent quota field
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 7/02/08    Time: 11:44a
//Updated in $/Leap/Source/Templates/Quota
//Removed State Field from the quota master
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 6/30/08    Time: 7:43p
//Updated in $/Leap/Source/Templates/Quota
//Solved TabOrder Problem
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/28/08    Time: 2:41p
//Updated in $/Leap/Source/Templates/Quota
//Added AjaxListing Functionality
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/25/08    Time: 12:52p
//Updated in $/Leap/Source/Templates/Quota
//Added AjaxEnabled Delete Functionality
//Added Input Data Validation using Javascript
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/16/08    Time: 3:50p
//Updated in $/Leap/Source/Templates/Quota
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/16/08    Time: 10:01a
//Updated in $/Leap/Source/Templates/Quota
//Modifying functions Done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/13/08    Time: 11:01a
//Updated in $/Leap/Source/Templates/Quota
//Modifying Comments Complete
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:59p
//Created in $/Leap/Source/Templates/Quota
//Initial CheckIn
?>