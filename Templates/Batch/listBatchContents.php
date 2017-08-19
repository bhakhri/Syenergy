<?php 

//
//This file creates Html Form output in "Batch" Module 
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddBatch',315,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddBatch','Add Batch'); ?>
<form name="addBatch" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
 

                    <tr>
                      <td class="contenttab_internal_rows"><strong>&nbsp;&nbsp;&nbsp;Name<?php echo REQUIRED_FIELD ?></strong></td>
                      <td width="79%" class="padding">
                       :&nbsp;<input type="text" id="batchName" name="batchName" maxlength="20" style="width:142px" onkeydown="return sendKeys(1,'batchName',event);" />                      
					  </td>
                    </tr>
                    
                    <tr>
                      <td class="contenttab_internal_rows"><nobr><strong>&nbsp;&nbsp;&nbsp;Start Date</strong></nobr></td>
                      <td width="79%" class="padding">
                     :&nbsp;<?php
                             require_once(BL_PATH.'/HtmlFunctions.inc.php');
                             echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'));
                            ?>      
                      </td>
                    </tr>
                     <tr>
                      <td class="contenttab_internal_rows"><strong>&nbsp;&nbsp;&nbsp;End Date</strong></td>
                      <td width="79%" class="padding">
                      :&nbsp;<?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         echo HtmlFunctions::getInstance()->datePicker('endDate',date('Y-m-d'));
                        ?>      
                      </td>
</tr>
<?php 
/* 
<tr>
<td class="contenttab_internal_rows"><nobr><strong>&nbsp;&nbsp;&nbsp;Batch Year </strong></nobr></td>
<td width="79%" class="padding">:&nbsp;<select size="1" class="selectfield" name="batchYear" id="batchYear" style="width:146px;">
  <?php
  require_once(BL_PATH.'/HtmlFunctions.inc.php');
  echo HtmlFunctions::getInstance()->getSessionsList($REQUEST_DATA['batchYear']==''? $stateRecordArray[0]['batchYear'] : $REQUEST_DATA['batchYear'] );
  ?>
</select>                     
</td>
</tr>
*/
?>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
     <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddBatch');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditBatch','Edit Batch '); ?>
<form name="editBatch" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

<input type="hidden" name="batchId" id="batchId" value="" /> 
 
                    <tr>
                      <td class="contenttab_internal_rows"><strong>&nbsp;&nbsp;&nbsp;Name<?php echo REQUIRED_FIELD ?> </strong></td>
                      <td width="79%" class="padding">
                       :&nbsp;<input type="text" id="batchName" name="batchName" maxlength="20" style="width:142px" onkeydown="return sendKeys(2,'batchName',event);" />                      
                       </td>
                    </tr>
                    <tr>
                      <td class="contenttab_internal_rows"><nobr><strong>&nbsp;&nbsp;&nbsp;Start Date</strong></nobr></td>
                      <td width="79%" class="padding">
                         :&nbsp;<?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         echo HtmlFunctions::getInstance()->datePicker('startDate1',date('Y-m-d'));        
                        ?>
                       </td>
                    </tr>
                     <tr>
                      <td class="contenttab_internal_rows"><strong>&nbsp;&nbsp;&nbsp;End Date</strong></td>
                      <td width="79%" class="padding">
                             :&nbsp;<?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->datePicker('endDate1',date('Y-m-d'));        
                            ?>      
                      </td>
</tr>
<?php 
/* 
<tr>
<td class="contenttab_internal_rows"><nobr><strong>&nbsp;&nbsp;&nbsp;Batch Year </strong></nobr></td>
<td width="79%" class="padding">:&nbsp;<select size="1" class="selectfield" name="batchYear" id="batchYear" style="width:146px;">
  <?php
  require_once(BL_PATH.'/HtmlFunctions.inc.php');
  echo HtmlFunctions::getInstance()->getSessionsList($REQUEST_DATA['batchYear']==''? $stateRecordArray[0]['batchYear'] : $REQUEST_DATA['batchYear'] );
  ?>
</select>                     
</td>
</tr>
*/
?>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                    <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  
                    onclick="javascript:hiddenFloatingDiv('EditBatch');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>

</table>
</form>
    <?php floatingDiv_End();
//$History: listBatchContents.php $	
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 4/05/10    Time: 6:38p
//Updated in $/LeapCC/Templates/Batch
//bug fixing. FCNS No.1524
//
//*****************  Version 8  *****************
//User: Parveen      Date: 10/15/09   Time: 2:45p
//Updated in $/LeapCC/Templates/Batch
//search condition updated (bug No. 1784)
//
//*****************  Version 7  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Templates/Batch
//search & conditions updated
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/19/09    Time: 1:51p
//Updated in $/LeapCC/Templates/Batch
//fixed bug no.412
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/17/09    Time: 2:26p
//Updated in $/LeapCC/Templates/Batch
//Gurkeerat: issue resolved 918
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/08/09    Time: 5:30p
//Updated in $/LeapCC/Templates/Batch
//bug fix 505, 504, 503, 968, 961, 960, 959, 958, 957, 956, 955, 954,
//953, 952,
//951, 723, 722, 797, 798, 799, 916, 935, 936, 937, 938, 939, 940, 944
//(alignment, condition & formatting updated)
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/04/09    Time: 7:07p
//Updated in $/LeapCC/Templates/Batch
//fixed bug nos.0000854, 0000853,0000860,0000859,0000858,0000857,0000856,
//0000824,0000822,0000811,0000823,0000809 0000810,
//0000808,0000807,0000806,0000805, 1395
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/26/09    Time: 5:25p
//Updated in $/LeapCC/Templates/Batch
//issue fix
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Batch
//
//*****************  Version 17  *****************
//User: Arvind       Date: 10/15/08   Time: 11:09a
//Updated in $/Leap/Source/Templates/Batch
//added print button
//
//*****************  Version 16  *****************
//User: Arvind       Date: 9/20/08    Time: 2:48p
//Updated in $/Leap/Source/Templates/Batch
//added common date functions
//
//*****************  Version 15  *****************
//User: Arvind       Date: 9/10/08    Time: 5:47p
//Updated in $/Leap/Source/Templates/Batch
//table width modified 
//
//*****************  Version 14  *****************
//User: Arvind       Date: 9/05/08    Time: 5:42p
//Updated in $/Leap/Source/Templates/Batch
//removed unsortable class
//
//*****************  Version 13  *****************
//User: Arvind       Date: 8/27/08    Time: 12:23p
//Updated in $/Leap/Source/Templates/Batch
//html validated
//
//*****************  Version 12  *****************
//User: Arvind       Date: 8/19/08    Time: 2:45p
//Updated in $/Leap/Source/Templates/Batch
//replaced search button
//
//*****************  Version 11  *****************
//User: Arvind       Date: 8/14/08    Time: 7:17p
//Updated in $/Leap/Source/Templates/Batch
//modified the bread crum
//
//*****************  Version 10  *****************
//User: Arvind       Date: 8/14/08    Time: 6:57p
//Updated in $/Leap/Source/Templates/Batch
//modified the bread crum
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/07/08    Time: 2:18p
//Updated in $/Leap/Source/Templates/Batch
//modified the colspan of paging row
//
//*****************  Version 8  *****************
//User: Arvind       Date: 7/23/08    Time: 12:53p
//Updated in $/Leap/Source/Templates/Batch
//modified batch year dropdown 
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/21/08    Time: 5:02p
//Updated in $/Leap/Source/Templates/Batch
//added a new fields batchYear
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/18/08    Time: 11:40a
//Updated in $/Leap/Source/Templates/Batch
//Added return false in cancel button of ADD and EDIT div's
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/11/08    Time: 6:37p
//Updated in $/Leap/Source/Templates/Batch
//added datepicker()  common function for date fields
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/11/08    Time: 3:15p
//Updated in $/Leap/Source/Templates/Batch
//removed javascript sorting class from the table
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/10/08    Time: 12:55p
//Updated in $/Leap/Source/Templates/Batch
//added a tool tip in start date
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/30/08    Time: 7:29p
//Updated in $/Leap/Source/Templates/Batch
//modify image button cancel to input type image button
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:27p
//Created in $/Leap/Source/Templates/Batch
//new files added
	?>
    <!--End: Div To Edit The Table-->


