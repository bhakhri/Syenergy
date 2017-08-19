<?php 
//
//----------------------------------------------------------------------
// THIS FILE CREATES HTML FORM OUTPUT IN "Periocidity" MODULE
//

// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------
//
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddPeriodicity',350,200);blankValues();return false;" />&nbsp;</td></tr>
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
<?php floatingDiv_Start('AddPeriodicity','Add Periodicity'); ?>
<form name="addPeriodicity" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
 
<tr>
    <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Name </b></nobr><?php echo REQUIRED_FIELD ?></td>
    <td width="79%" class="padding">:&nbsp;
        <input type="text" id="periodicityName" name="periodicityName" class="inputbox" maxlength="20" />
    </td>
</tr>
<tr>    
    <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Abbr.</b></nobr><?php echo REQUIRED_FIELD ?></td>
    <td width="79%" class="padding">:&nbsp;
        <input type="text" id="periodicityCode" name="periodicityCode" class="inputbox"  maxlength="10" />
    </td>
</tr>
<tr>    
    <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Annual Frequency </b></nobr><?php echo REQUIRED_FIELD ?></td>
    <td width="79%" class="padding">:&nbsp;
        <input type="text" id="periodicityFrequency" name="periodicityFrequency" class="inputbox"  maxlength="6" />
    </td>
</tr>

<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
                    <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" 
                    onclick="javascript:hiddenFloatingDiv('AddPeriodicity');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
  
</table>
    </form> 
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('EditPeriodicity','Edit Periodicity'); ?>
   <form name="editPeriodicity" action="" method="post">   
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="periodicityId" id="periodicityId" value="" />
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Name </b></nobr><?php echo REQUIRED_FIELD ?></td>
        <td width="79%" class="padding">:&nbsp;
            <input type="text" id="periodicityName" name="periodicityName" class="inputbox" maxlength="20" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Abbr.</b></nobr><?php echo REQUIRED_FIELD ?></td>
        <td width="79%" class="padding">:&nbsp;
            <input type="text" id="periodicityCode" name="periodicityCode" class="inputbox" maxlength="10" /></td>
    </tr>
    <tr>    
    <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Annual Frequency </b></nobr><?php echo REQUIRED_FIELD ?></td>
    <td width="79%" class="padding">:&nbsp;
        <input type="text" id="periodicityFrequency" name="periodicityFrequency" class="inputbox" maxlength="6" /></td>
</tr>
    <tr>
    <td height="5px"></td>
    </tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                   <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" 
                    onclick="javascript:hiddenFloatingDiv('EditPeriodicity');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>

</form> 
    <?php floatingDiv_End(); ?>
  
  
<?php 

//$History: listPeriodicityContents.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Templates/Periodicity
//search & conditions updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/06/09    Time: 3:48p
//Updated in $/LeapCC/Templates/Periodicity
//dot added (abbr. fields)
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/04/09    Time: 11:06a
//Updated in $/LeapCC/Templates/Periodicity
//make space between print & export to excel button and sr. no. make left
//align
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/03/09    Time: 5:41p
//Updated in $/LeapCC/Templates/Periodicity
//fixed bug nos.0000602, 0000832, 0000831, 0000830
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/21/09    Time: 10:25a
//Updated in $/LeapCC/Templates/Periodicity
//required field added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Periodicity
//
//*****************  Version 13  *****************
//User: Arvind       Date: 10/21/08   Time: 10:47a
//Updated in $/Leap/Source/Templates/Periodicity
//modified display
//
//*****************  Version 12  *****************
//User: Arvind       Date: 9/10/08    Time: 5:33p
//Updated in $/Leap/Source/Templates/Periodicity
//removed heigh and width from cancel button
//
//*****************  Version 11  *****************
//User: Arvind       Date: 9/05/08    Time: 5:42p
//Updated in $/Leap/Source/Templates/Periodicity
//removed unsortable class
//
//*****************  Version 10  *****************
//User: Arvind       Date: 9/04/08    Time: 11:24a
//Updated in $/Leap/Source/Templates/Periodicity
//html validated
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/19/08    Time: 2:52p
//Updated in $/Leap/Source/Templates/Periodicity
//replaced search button
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/14/08    Time: 6:58p
//Updated in $/Leap/Source/Templates/Periodicity
//modified the bread crum
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/04/08    Time: 6:11p
//Updated in $/Leap/Source/Templates/Periodicity
//modified
//
//*****************  Version 6  *****************
//User: Arvind       Date: 6/30/08    Time: 7:29p
//Updated in $/Leap/Source/Templates/Periodicity
//modify image button cancel to input type image button
//
//*****************  Version 5  *****************
//User: Arvind       Date: 6/17/08    Time: 3:17p
//Updated in $/Leap/Source/Templates/Periodicity
//modification
//
//*****************  Version 4  *****************
//User: Arvind       Date: 6/14/08    Time: 7:20p
//Updated in $/Leap/Source/Templates/Periodicity
//modification
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:05p
//Updated in $/Leap/Source/Templates/Periodicity
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:22p
//Created in $/Leap/Source/Templates/Periodicity
//NEw File Added in Periodicity Folder

?>
