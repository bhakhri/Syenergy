<?php 
//This file creates Html Form output in feefundallocation Module 
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;">
								<input type="image" title ="Add" src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddFeeFundAllocation',350,200);blankValues();return false;" />&nbsp;</td></tr>
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
	
<?php floatingDiv_Start('AddFeeFundAllocation','Add Fee Fund Allocation'); ?>
<form name="addFeeFundAllocation" action="" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

<tr>
    <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;&nbsp;<b>Allocation Entity<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="allocationEntity" name="allocationEntity" class="inputbox" maxlength="50"/></td>
</tr>
<tr>    
    <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;&nbsp;<b>Abbr.<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="entityType" name="entityType" class="inputbox" maxlength="5"/></td>
</tr>

<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
                    <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFeeFundAllocation');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
 
</table>
  </form>
<?php floatingDiv_End(); ?>



<?php floatingDiv_Start('EditFeeFundAllocation','Edit Fee Fund Allocation'); ?>
 <form name="editFeeFundAllocation" action="" method="post">      
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
  
   
    <input type="hidden" name="feeFundAllocationId" id="feeFundAllocationId" value="" />
<tr>
    <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;&nbsp;<b>Allocation Entity<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="allocationEntity" name="allocationEntity" class="inputbox" maxlength="50"/></td>
</tr>
<tr>    
    <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;&nbsp;<b>Abbr.<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="entityType" name="entityType" class="inputbox" maxlength="5"/></td>
</tr>

    <tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
        <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditFeeFundAllocation');return false;" />
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>

</table>
</form>
    <?php floatingDiv_End(); ?>
  
<?php 
//$History: listFeeFundAllocationContents.php $
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/FeeFundAllocation
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/13/09    Time: 10:49a
//Updated in $/LeapCC/Templates/FeeFundAllocation
//search condition & CSV format updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/08/09    Time: 5:30p
//Updated in $/LeapCC/Templates/FeeFundAllocation
//bug fix 505, 504, 503, 968, 961, 960, 959, 958, 957, 956, 955, 954,
//953, 952,
//951, 723, 722, 797, 798, 799, 916, 935, 936, 937, 938, 939, 940, 944
//(alignment, condition & formatting updated)
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Templates/FeeFundAllocation
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/30/09    Time: 5:07p
//Updated in $/LeapCC/Templates/FeeFundAllocation
//role permission and formatting updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/22/09    Time: 3:52p
//Updated in $/LeapCC/Templates/FeeFundAllocation
//condition & formatting, required parameter checks updated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/FeeFundAllocation
//
//*****************  Version 11  *****************
//User: Arvind       Date: 10/17/08   Time: 12:49p
//Updated in $/Leap/Source/Templates/FeeFundAllocation
//added print button
//
//*****************  Version 10  *****************
//User: Arvind       Date: 9/05/08    Time: 5:47p
//Updated in $/Leap/Source/Templates/FeeFundAllocation
//removed unsortable class
//
//*****************  Version 9  *****************
//User: Arvind       Date: 9/01/08    Time: 3:21p
//Updated in $/Leap/Source/Templates/FeeFundAllocation
//modified the field name from Entity Type to Abbr 
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/27/08    Time: 12:50p
//Updated in $/Leap/Source/Templates/FeeFundAllocation
//html validated
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/19/08    Time: 2:48p
//Updated in $/Leap/Source/Templates/FeeFundAllocation
//replaced search button
//
//*****************  Version 6  *****************
//User: Arvind       Date: 8/14/08    Time: 7:18p
//Updated in $/Leap/Source/Templates/FeeFundAllocation
//modified the bread crum
//
//*****************  Version 5  *****************
//User: Arvind       Date: 8/14/08    Time: 7:03p
//Updated in $/Leap/Source/Templates/FeeFundAllocation
//modified the bread crum
//
//*****************  Version 4  *****************
//User: Arvind       Date: 8/06/08    Time: 6:43p
//Updated in $/Leap/Source/Templates/FeeFundAllocation
//modify the width of fields in table
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/04/08    Time: 6:15p
//Updated in $/Leap/Source/Templates/FeeFundAllocation
//removed unneccesary code at bottom
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/12/08    Time: 11:04a
//Updated in $/Leap/Source/Templates/FeeFundAllocation
//modified the design
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:38a
//Created in $/Leap/Source/Templates/FeeFundAllocation
//Added a new content  file for " FeeFundAllocation " Module

?>
