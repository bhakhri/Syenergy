<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Building LISTING 
//
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddBuilding',320,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddBuilding','Add Building'); ?>
<form name="AddBuilding" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Building  Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" id="buildingName" name="buildingName" class="inputbox" maxlength="50" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Abbr.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" id="abbreviation" name="abbreviation" class="inputbox" maxlength="10" /></td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
        <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddBuilding');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditBuilding','Edit Building'); ?>
<form name="EditBuilding" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="buildingId" id="buildingId" value="" />  
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Building  Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" id="buildingName" name="buildingName" class="inputbox" maxlength="50" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Abbr.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" id="abbreviation" name="abbreviation" class="inputbox" maxlength="10" /></td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditBuilding');return false;" />
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
// $History: listBuildingContents.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 28/08/09   Time: 13:14
//Updated in $/LeapCC/Templates/Building
//Done bug fixing.
//Bug ids---
//00001337,00001336,00001335,00001334,
//00001332,00001333,00001339,00001265,
//00001267,00001257,00001256,00001266,
//00001232,00001231
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 4/08/09    Time: 16:01
//Updated in $/LeapCC/Templates/Building
//Done bug fixing.
//bug ids--
//0000861 to 0000877
//
//*****************  Version 2  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Templates/Building
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Building
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 10/24/08   Time: 10:37a
//Updated in $/Leap/Source/Templates/Building
//Added functionality for building report print
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 10/22/08   Time: 5:38p
//Updated in $/Leap/Source/Templates/Building
//Corrected action button/images alignment for IE
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Building
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/Building
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:47p
//Updated in $/Leap/Source/Templates/Building
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:21p
//Updated in $/Leap/Source/Templates/Building
//corrected breadcrumb and reset button height and width
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/10/08    Time: 6:54p
//Updated in $/Leap/Source/Templates/Building
//Created Building Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 5:28p
//Created in $/Leap/Source/Templates/Building
//Initial Checkin
?>