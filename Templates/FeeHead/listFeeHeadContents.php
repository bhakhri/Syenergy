<?php 
//This file creates Html Form output in Fee Head Module 
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddFeeHead',350,200);blankValues();return false;" />&nbsp;</td></tr>
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
    
<?php floatingDiv_Start('AddFeeHead','Add Fee Head'); ?>
<form name="addFeeHead" action="" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" name="headName" id="headName" class="inputbox" maxlength="100"  style="width:250px"/></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Abbr.<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" name="headAbbr" id="headAbbr" class="inputbox" maxlength="10"  style="width:250px" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Refundable Security </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="isRefundable" name="isRefundable" value="1" />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="isRefundable" name="isRefundable" value="0" checked="checked" />No
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Concessionable </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="isConsessionable" name="isConsessionable" value="1"  />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="isConsessionable" name="isConsessionable" checked="checked" value="0" />No
        </td>
    </tr>  
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Miscellaneous </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="isVariable" name="isVariable" value="1" />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="isVariable" name="isVariable" value="0" checked="checked" />No
        </td>
    </tr>
	<tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Display Order<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" name="sortOrder" id="sortOrder" class="inputbox" maxlength="2"  style="width:30px" /></td>
    </tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<tr>
		<td align="center" style="padding-right:10px" colspan="2"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" /><input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFeeHead');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" /></td>
	</tr>
	<tr>
		<td height="5px"></td></tr>
	<tr>
	</table>
    </form>
<?php floatingDiv_End(); ?>



<?php floatingDiv_Start('EditFeeHead','Edit Fee Head'); ?>
<form name="editFeeHead" action="" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="feeHeadId" id="feeHeadId" value="" />
     <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" name="headName" id="headName" class="inputbox" maxlength="100"  style="width:250px"/></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Abbr.<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" name="headAbbr" id="headAbbr" class="inputbox" maxlength="10"  style="width:250px" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Refundable Security </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="isRefundable" name="isRefundable" value="1" />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="isRefundable" name="isRefundable" value="0" checked="checked" />No
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Concessionable </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="isConsessionable" name="isConsessionable" value="1"  />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="isConsessionable" name="isConsessionable" checked="checked" value="0" />No
        </td>
    </tr>  
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Miscellaneous </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="isVariable" name="isVariable" value="1" />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="isVariable" name="isVariable" value="0" checked="checked" />No
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Display Order<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" name="sortOrder" id="sortOrder" class="inputbox" maxlength="2"  style="width:30px" /></td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                    <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  
                    onclick="javascript:hiddenFloatingDiv('EditFeeHead');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>

</table>
</form>
    <?php floatingDiv_End(); ?>

<?php 
//$History: listFeeHeadContents.php $
/* <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Parent Head </b></nobr></td>
        <td class="padding">
        :&nbsp;<select size="1" class="selectfield" name="parentHeadId" id="parentHeadId"  style="width:253px"><option value="">Select</option>
        </select>
        <!-- <select size="1" class="selectfield" name="parentHeadId" id="parentHeadId"><option value="">Select</option>
              <?php
              //   require_once(BL_PATH.'/HtmlFunctions.inc.php');
              //   echo HtmlFunctions::getInstance()->getFeeHeadNameData($REQUEST_DATA['parentHeadId']==''? $feeHeadRecordArray[0]['parentHeadId'] : $REQUEST_DATA['parentHeadId'] );
              ?>
        </select> 
    </td>
    </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top">
            <nobr><b>&nbsp;&nbsp;&nbsp;Applicable to All<br><span style="font-size:9px">&nbsp;&nbsp;(Categories i.e. Gen/SC/ST)<b></font></nobr>
        </td>
        <td width="79%" class="padding" align="left" valign="top">:&nbsp;<input type="checkbox" name="applicableToAll" id="applicableToAll" /></td>
    </tr
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Transport Head </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="transportHead" name="transportHead"  value="1" />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="transportHead" name="transportHead" checked="checked" value="0" />No
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Hostel Head </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="hostelHead" name="hostelHead" value="1" />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="hostelHead" name="hostelHead" value="0" checked="checked" />No
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Misc. Head </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="miscHead" name="miscHead" value="1" />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="miscHead" name="miscHead" value="0" checked="checked" />No
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Concessionable </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="isConsessionable" name="isConsessionable" value="1"  />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="isConsessionable" name="isConsessionable" checked="checked" value="0" />No
        </td>
    </tr>  
*/    
//*****************  Version 10  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Templates/FeeHead
//updated with all the fees enhancements
//
//*****************  Version 9  *****************
//User: Parveen      Date: 10/21/09   Time: 11:14a
//Updated in $/LeapCC/Templates/FeeHead
//print & csv functionality added
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/04/09    Time: 11:44a
//Updated in $/LeapCC/Templates/FeeHead
//Gurkeerat: respelled 'conssionable' as 'concessionable' on add window
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/20/09    Time: 7:17p
//Updated in $/LeapCC/Templates/FeeHead
//issue fix 13, 15, 10, 4 1129, 1118, 1134, 555, 224, 1177, 1176, 1175
//formating conditions updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 7/24/09    Time: 12:52p
//Updated in $/LeapCC/Templates/FeeHead
//spelling correct (Applicable to all)
//
//*****************  Version 5  *****************
//User: Parveen      Date: 7/22/09    Time: 5:38p
//Updated in $/LeapCC/Templates/FeeHead
//required field added 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/22/09    Time: 12:34p
//Updated in $/LeapCC/Templates/FeeHead
//alignment & drop down value updated (parent head name) search condition
//udpated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/13/09    Time: 10:06a
//Updated in $/LeapCC/Templates/FeeHead
//alignment,sorting order & conditions updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/22/08   Time: 5:13p
//Updated in $/LeapCC/Templates/FeeHead
//print sorting order set
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/FeeHead
//
//*****************  Version 19  *****************
//User: Arvind       Date: 9/05/08    Time: 5:47p
//Updated in $/Leap/Source/Templates/FeeHead
//removed unsortable class
//
//*****************  Version 18  *****************
//User: Arvind       Date: 8/27/08    Time: 12:47p
//Updated in $/Leap/Source/Templates/FeeHead
//html validated
//
//*****************  Version 17  *****************
//User: Arvind       Date: 8/26/08    Time: 5:14p
//Updated in $/Leap/Source/Templates/FeeHead
//modify
//
//*****************  Version 16  *****************
//User: Arvind       Date: 8/19/08    Time: 2:49p
//Updated in $/Leap/Source/Templates/FeeHead
//replaced search button
//
//*****************  Version 15  *****************
//User: Arvind       Date: 8/14/08    Time: 7:18p
//Updated in $/Leap/Source/Templates/FeeHead
//modified the bread crum
//
//*****************  Version 14  *****************
//User: Arvind       Date: 8/14/08    Time: 7:03p
//Updated in $/Leap/Source/Templates/FeeHead
//modified the bread crum
//
//*****************  Version 13  *****************
//User: Arvind       Date: 8/06/08    Time: 6:20p
//Updated in $/Leap/Source/Templates/FeeHead
//modify the width of the table
//
//*****************  Version 11  *****************
//User: Arvind       Date: 8/04/08    Time: 6:12p
//Updated in $/Leap/Source/Templates/FeeHead
//removed unneccesary code at bottom
//
//*****************  Version 10  *****************
//User: Arvind       Date: 7/29/08    Time: 3:58p
//Updated in $/Leap/Source/Templates/FeeHead
//added maxlength for name and abbr
//
//*****************  Version 9  *****************
//User: Arvind       Date: 7/29/08    Time: 3:53p
//Updated in $/Leap/Source/Templates/FeeHead
//added space between table fields and sorting icon and renamed childHead
//to headName
//
//*****************  Version 8  *****************
//User: Arvind       Date: 7/29/08    Time: 11:59a
//Updated in $/Leap/Source/Templates/FeeHead
//added space in header fields
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/19/08    Time: 12:49p
//Updated in $/Leap/Source/Templates/FeeHead
//no change
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/15/08    Time: 6:23p
//Updated in $/Leap/Source/Templates/FeeHead
//modifed the fields display
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/11/08    Time: 4:00p
//Updated in $/Leap/Source/Templates/FeeHead
//corrected the dropdown 
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/05/08    Time: 5:09p
//Updated in $/Leap/Source/Templates/FeeHead
//added column span inrow displaying " row not found "
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/05/08    Time: 3:49p
//Updated in $/Leap/Source/Templates/FeeHead
//added select option in parent head dropdown
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/03/08    Time: 12:32p
//Updated in $/Leap/Source/Templates/FeeHead
//changed display parentheadId  dropwon function from stateRecordsArray
//to feeheadRecordsArray
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:39a
//Created in $/Leap/Source/Templates/FeeHead
//Added a new content  file for " FeeHead " Module

?>
