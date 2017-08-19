<?php
//-------------------------------------------------------
// Purpose: to design the layout for Block.
//
// Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008 )
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddBlock',315,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddBlock','Add Block'); ?>
<form name="AddBlock" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
 <tr>
    <td width="21%" class="contenttab_internal_rows"><nobr><b>Block Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
    <td class="padding" width="1%">:</td>
    <td width="78%" class="padding"><input type="text" id="blockName" name="blockName" class="inputbox" maxlength="50" /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>Abbr.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
    <td class="padding">:</td>
    <td class="padding"><input type="text" id="abbreviation" name="abbreviation" class="inputbox"  maxlength="10" /></td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>Building Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
    <td class="padding" width="1%">:</td>
    <td class="padding">
    <select size="1" class="inputbox" name="building" id="building" style="width:184px;">
    <option value="">SELECT BUILDING</option>
    
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getBuildingData($REQUEST_DATA['building']==''? $blockRecordArray[0]['buildingId'] : $REQUEST_DATA['building'] );
              ?>
        </select>
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddBlock');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form> 
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditBlock','Edit Block'); ?>
<form name="EditBlock" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<input type="hidden" name="blockId" id="blockId" value="" />
<tr>
    <td width="21%" class="contenttab_internal_rows"><nobr><b>Block Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
    <td class="padding" width="1%">:</td>
    <td width="75%" class="padding"><input type="text" id="blockName" name="blockName" class="inputbox" maxlength="50" /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>Abbr.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
    <td class="padding">:</td>
    <td class="padding"><input type="text" id="abbreviation" name="abbreviation" class="inputbox"  maxlength="10" /></td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>Building Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
    <td class="padding">:</td>
    <td class="padding">
    <select size="1" class="inputbox" name="building" id="building" style="width:184px;">
    <option value="">SELECT BUILDING</option>
    
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getBuildingData($REQUEST_DATA['building']==''? $blockRecordArray[0]['buildingId'] : $REQUEST_DATA['building'] );
              ?>
        </select>
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
        <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditBlock');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>  
<?php 
floatingDiv_End(); 

// $History: listBlockContents.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/28/09    Time: 11:41a
//Updated in $/LeapCC/Templates/Block
//search condition format updated
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 5/08/09    Time: 12:39
//Updated in $/LeapCC/Templates/Block
//Done bug fixing.
//bug ids---
//0000887 to 0000895,
//0000906 to 0000909
//
//*****************  Version 2  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Templates/Block
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Block
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 10/24/08   Time: 10:17a
//Updated in $/Leap/Source/Templates/Block
//Added functionality for block report print
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Block
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/Block
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:47p
//Updated in $/Leap/Source/Templates/Block
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:21p
//Updated in $/Leap/Source/Templates/Block
//corrected breadcrumb and reset button height and width
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/11/08    Time: 12:43p
//Updated in $/Leap/Source/Templates/Block
//Created "Block" Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 7:06p
//Created in $/Leap/Source/Templates/Block
//Initial Checkin
?>

    


