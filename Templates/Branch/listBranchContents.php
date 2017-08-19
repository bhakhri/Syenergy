<?php 

////This file creates Html Form output in Branch Module 
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddBranch',280,100);blankValues();return false;" />&nbsp;</td></tr>
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
   
<?php floatingDiv_Start('AddBranch','Add Branch'); ?>
<form name="addBranch" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
 
<tr>
    <td width="20%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Name<?php echo REQUIRED_FIELD ?> </b></nobr></td>
    <td width="5%" class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
    <td width="75%" style="width:180px" class="padding"><input type="text" id="branchName" name="branchName" class="inputbox" maxlength="50" /></td>
</tr>
<tr>    
    <td width="20%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Abbr.<?php echo REQUIRED_FIELD ?> </b></nobr></td>
    <td width="5%" class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
    <td width="75%" style="width:180px" class="padding"><input type="text" id="branchCode" name="branchCode" class="inputbox" maxlength="10" /></td>
</tr>
<tr>    
    <td width="20%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Misc.Receipt Prefix<?php echo REQUIRED_FIELD ?> </b></nobr></td>
    <td width="5%" class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
    <td width="75%" style="width:180px" class="padding"><input type="text" id="miscReceiptPrefix" name="miscReceiptPrefix" class="inputbox"  /></td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
        <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddBranch');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
 
</table>
  </form>
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('EditBranch','Edit Branch'); ?>
  <form name="editBranch" action="" method="post">   
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
  
      
    <input type="hidden" name="branchId" id="branchId" value="" />
    <tr>
        <td width="20%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Name<?php echo REQUIRED_FIELD ?> </b></nobr></td>
        <td width="5%" class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
        <td width="75%" class="padding"><input style="width:180px" type="text" id="branchName" name="branchName" class="inputbox"  maxlength="50"/></td>
    </tr>
    <tr>    
        <td width="20%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Abbr.<?php echo REQUIRED_FIELD ?> </b></nobr></td>
        <td width="5%" class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
        <td width="75%" class="padding"><input style="width:180px" type="text" id="branchCode" name="branchCode" class="inputbox" maxlength="10"/></td>
    </tr>
	<tr>    
    <td width="20%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Misc.Receipt Prefix<?php echo REQUIRED_FIELD ?> </b></nobr></td>
    <td width="5%" class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
    <td width="75%" style="width:180px" class="padding"><input type="text" id="miscReceiptPrefix" name="miscReceiptPrefix" class="inputbox" /></td>
</tr>
    <tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
        <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditBranch');return false;" />
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>

<?php floatingDiv_End(); ?>

<?php 
//$History: listBranchContents.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Templates/Branch
//search & conditions updated
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/04/09    Time: 7:07p
//Updated in $/LeapCC/Templates/Branch
//fixed bug nos.0000854, 0000853,0000860,0000859,0000858,0000857,0000856,
//0000824,0000822,0000811,0000823,0000809 0000810,
//0000808,0000807,0000806,0000805, 1395
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/02/09    Time: 12:35p
//Updated in $/LeapCC/Templates/Branch
//condition & required parameter updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/26/09    Time: 5:25p
//Updated in $/LeapCC/Templates/Branch
//issue fix
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Branch
//
//*****************  Version 17  *****************
//User: Arvind       Date: 10/15/08   Time: 5:57p
//Updated in $/Leap/Source/Templates/Branch
//added print button
//
//*****************  Version 16  *****************
//User: Arvind       Date: 9/10/08    Time: 5:41p
//Updated in $/Leap/Source/Templates/Branch
//table width modified
//
//*****************  Version 15  *****************
//User: Arvind       Date: 9/05/08    Time: 5:40p
//Updated in $/Leap/Source/Templates/Branch
//removed unsortable class
//
//*****************  Version 14  *****************
//User: Arvind       Date: 8/27/08    Time: 12:20p
//Updated in $/Leap/Source/Templates/Branch
//html validated
//
//*****************  Version 13  *****************
//User: Arvind       Date: 8/19/08    Time: 2:46p
//Updated in $/Leap/Source/Templates/Branch
//replaced search button
//
//*****************  Version 12  *****************
//User: Arvind       Date: 8/14/08    Time: 7:17p
//Updated in $/Leap/Source/Templates/Branch
//modified the bread crum
//
//*****************  Version 11  *****************
//User: Arvind       Date: 8/14/08    Time: 6:57p
//Updated in $/Leap/Source/Templates/Branch
//modified the bread crum
//
//*****************  Version 10  *****************
//User: Arvind       Date: 8/04/08    Time: 6:08p
//Updated in $/Leap/Source/Templates/Branch
//removed code
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/02/08    Time: 5:14p
//Updated in $/Leap/Source/Templates/Branch
//modified
//
//*****************  Version 8  *****************
//User: Arvind       Date: 7/23/08    Time: 6:08p
//Updated in $/Leap/Source/Templates/Branch
//added maxlength=50 in branchName and maxlength=6 in Abbr
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/11/08    Time: 3:05p
//Updated in $/Leap/Source/Templates/Branch
//remover javascript sorting class
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/11/08    Time: 2:04p
//Updated in $/Leap/Source/Templates/Branch
//removed  paging row
//
//*****************  Version 5  *****************
//User: Arvind       Date: 6/30/08    Time: 7:29p
//Updated in $/Leap/Source/Templates/Branch
//modify image button cancel to input type image button
//
//*****************  Version 4  *****************
//User: Arvind       Date: 6/14/08    Time: 7:19p
//Updated in $/Leap/Source/Templates/Branch
//modification
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:05p
//Updated in $/Leap/Source/Templates/Branch
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:22p
//Created in $/Leap/Source/Templates/Branch
//NEw File Added in Branch Folder

?>
