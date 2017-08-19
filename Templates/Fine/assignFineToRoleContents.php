<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


require_once(TEMPLATES_PATH . "/breadCrumb.php");
$styleWidth = "style='width:360px'";
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
								
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddMappingDiv',350,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddMappingDiv','Add Role to Fines/Activities Mapping'); ?>
    <form name="AddMapping" action="" method="post">
	<strong>Note : Data will be Mapped and Overwritten</strong> <br>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Role<?php echo REQUIRED_FIELD; ?></b>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getHelpLink('Role',HELP_FINE_ROLE);
            ?></nobr>
        </td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
         <select name="roleName" id="roleName" class="inputbox1" class="selectfield" <?php echo $styleWidth; ?>>
           <option value="">Select</option>
           <?php
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->getRoleData('',' WHERE roleId NOT IN (3,4)');
           ?>
         </select><br>
	
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Fines/Activities to be Taken<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"  valign="top">:</td>
        <td width="79%" class="padding"  valign="top">
         <select size="5" multiple="multiple" class="inputbox1" name="fineName" id="fineName"  <?php echo $styleWidth; ?>  >
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getFineCategory();
              ?>
        </select><br>
Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("fineName","All","AddMapping");'>All</a> /
               <a class="allReportLink" href='javascript:makeSelection("fineName","None","AddMapping");'>None</a>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Institute Valid<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" valign="top">:</td>
        <td width="79%" class="padding" valign="top">
         <select size="5" multiple="multiple" class="inputbox1" name="validInstitute" id="validInstitute"  <?php echo $styleWidth; ?> onchange="getClass('A','S')" >
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getInstituteData();
              ?>
        </select><br>
        Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("validInstitute","All","AddMapping");' onClick="getClass('A','A');">All</a> /
               <a class="allReportLink" href='javascript:makeSelection("validInstitute","None","AddMapping");'  onClick="document.AddMapping.classId.length = null;">None</a>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"  valign="top"><nobr><b>Class Valid<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" valign="top">:</td>
        <td width="79%" class="padding" valign="top">
         <select size="5" multiple="multiple" class="inputbox1"  name="classId" id="classId"  <?php echo $styleWidth; ?> >
        </select><br>
	Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("classId","All","AddMapping");'>All</a> /
               <a class="allReportLink" href='javascript:makeSelection("classId","None","AddMapping");'>None</a>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"  valign="top"><nobr><b>Approver</b><?php echo REQUIRED_FIELD; ?><br/>(user names <br>seperated by commas)</b></nobr></td>
        <td class="padding"  valign="top">:</td>
        <td width="79%" class="padding"  valign="top" align="left">
         <textarea name="approver" id="approver" class="inputbox" cols="5" rows="3"  <?php echo $styleWidth; ?>></textarea>
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddMappingDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditMappingDiv','Edit Role to Fines/Activities Mapping '); ?>
<form name="EditMapping" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="roleFineId" id="roleFineId" value="" />
    <tr>    	
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Role<?php echo REQUIRED_FIELD; ?></b>
                    <?php
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                        echo HtmlFunctions::getInstance()->getHelpLink('Role',HELP_FINE_ROLE);
                    ?></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
         <select name="roleName" id="roleName" class="inputbox1" class="selectfield" disabled="disabled" <?php echo $styleWidth; ?> >
           <option value="">Select</option>
           <?php
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->getRoleData('',' WHERE roleId NOT IN (3,4)');
           ?>
         </select>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Fines/Activities to be Taken<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
         <select size="5" multiple="multiple" class="inputbox1" name="fineName" id="fineName" <?php echo $styleWidth; ?>  >
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getFineCategory();
              ?>
         </select><br>
	Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("fineName","All","EditMapping");'>All</a> /
               <a class="allReportLink" href='javascript:makeSelection("fineName","None","EditMapping");'>None</a>
        
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Institute Valid<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
         <select size="5" multiple="multiple" class="inputbox1" name="validInstitute" id="validInstitute" <?php echo $styleWidth; ?> onchange="getClass('E','S')" >
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getInstituteData();
              ?>
         </select><br>
	Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("validInstitute","All","EditMapping");' onClick="getClass('E','A');">All</a> /
               <a class="allReportLink" href='javascript:makeSelection("validInstitute","None","EditMapping");'  onClick="document.EditMapping.classId.length = null;">None</a>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"  valign="top"><nobr><b>Class Valid<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" valign="top">:</td>
        <td width="79%" class="padding" valign="top">
         <select size="5" multiple="multiple" class="inputbox1" name="classId" id="classId"  <?php echo $styleWidth; ?> >
        </select><br>
    Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("classId","All","EditMapping");'>All</a> /
               <a class="allReportLink" href='javascript:makeSelection("classId","None","EditMapping");'>None</a>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Approver</b><?php echo REQUIRED_FIELD; ?><br/>(user names <br>seperated by commas)</b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding" align="left">
         <textarea name="approver" id="approver" class="inputbox" cols="5" rows="3" <?php echo $styleWidth; ?>></textarea>
    </td>
</tr>
<tr><td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditMappingDiv');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->

<!--Daily Attendance Help  Details  Div-->
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>
            <td width="89%">
                <div id="helpInfo" style="vertical-align:top;" ></div>
            </td>
        </tr>
    </table>
</div>
<?php floatingDiv_End(); ?>
<!--Daily Attendance Help  Details  End -->

<?php
// $History: assignFineToRoleContents.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Fine
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 26/11/09   Time: 11:01
//Updated in $/LeapCC/Templates/Fine
//Done bug fixing.
//Bug ids---
//0002154,0002146
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 29/07/09   Time: 11:15
//Updated in $/LeapCC/Templates/Fine
//Done bug fixing.
//bug ids---
//0000739,0000740,0000746,0000747,0000748,0000752
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 27/07/09   Time: 16:05
//Updated in $/LeapCC/Templates/Fine
//Done bug fixing.
//bug ids---0000697 to 0000702
//
//*****************  Version 2  *****************
//User: Administrator Date: 3/07/09    Time: 18:30
//Updated in $/LeapCC/Templates/Fine
//Corrected html
//
//*****************  Version 1  *****************
//User: Administrator Date: 3/07/09    Time: 18:24
//Created in $/LeapCC/Templates/Fine
//Created "Assign Role to Fines" module
?>
