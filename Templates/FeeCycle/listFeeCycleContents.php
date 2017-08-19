<?php
//-------------------------------------------------------
// Purpose: to design the layout for fee cycle.
//
// Author : Jaineesh
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
        <td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="505">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr height="30">
                                <td class="contenttab_border" height="20" style="border-right:0px;">
                                    <?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
                                </td>
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;">
                                <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddFeeCycle',315,250);blankValues();return false;" />&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                            </tr>
                                <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="40">
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

<?php floatingDiv_Start('AddFeeCycle','Add Fee Cycle'); ?>
 <form name="addFeeCycle" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

 
<tr>
    <td width="21%" class="contenttab_internal_rows"><nobr><b>Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;&nbsp;<input type="text" id="cycleName" name="cycleName" class="inputbox" maxlength="100" onkeydown="return sendKeys(1,'cycleName',event);" /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>Abbr.<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:&nbsp;&nbsp;<input type="text" id="cycleAbbr" name="cycleAbbr" class="inputbox" maxlength="10" onkeydown="return sendKeys(1,'cycleAbbr',event);"  /></td>
</tr>
<tr>
   <td class="contenttab_internal_rows"><nobr><b>From<?php echo REQUIRED_FIELD ?></b></nobr></td>
   <td class="padding">:&nbsp;
	<?php
		 require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'));
	?>
</td> 
</tr>
<tr>
   <td class="contenttab_internal_rows"><nobr><b>To<?php echo REQUIRED_FIELD ?></b></nobr></td>
   <td class="padding">:&nbsp;
	<?php
		require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
	?>
	</td> 
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
    
	 <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFeeCycle');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditFeeCycle','Edit Fee Cycle'); ?>
<form name="editFeeCycle" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

<input type="hidden" name="feeCycleId" id="feeCycleId" value="" />
    <tr>
    <td width="21%" class="contenttab_internal_rows"><nobr><b>Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="cycleName" name="cycleName" class="inputbox" value="" maxlength="100" onkeydown="return sendKeys(2,'cycleName',event);" /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>Abbr.<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:&nbsp;<input type="text" id="cycleAbbr" name="cycleAbbr" class="inputbox"  value="" maxlength="10" onkeydown="return sendKeys(2,'cycleAbbr',event);" /></td>
</tr>
<tr>
   <td class="contenttab_internal_rows"><nobr><b>From<?php echo REQUIRED_FIELD ?></b></nobr></td>
   <td class="padding">:
   <?php
	 require_once(BL_PATH.'/HtmlFunctions.inc.php');
	 echo HtmlFunctions::getInstance()->datePicker('fromDate1',date('Y-m-d'));
	?>
</td> 
</tr>
<tr>
   <td class="contenttab_internal_rows"><nobr><b>To<?php echo REQUIRED_FIELD ?></b></nobr></td>
   <td class="padding">:&nbsp;<?php
 require_once(BL_PATH.'/HtmlFunctions.inc.php');
 echo HtmlFunctions::getInstance()->datePicker('toDate1',date('Y-m-d'));
?></td> 
</tr>
    <tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                
					 <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  
                    onclick="javascript:hiddenFloatingDiv('EditFeeCycle');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>

</table></form>
<?php 
floatingDiv_End(); 

// $History: listFeeCycleContents.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/18/09    Time: 6:01p
//Updated in $/LeapCC/Templates/FeeCycle
//Gurkeerat: resolved issue 1111
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/17/09    Time: 1:27p
//Updated in $/LeapCC/Templates/FeeCycle
//Gurkeerat: resolved issue 927
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/14/09    Time: 3:33p
//Updated in $/LeapCC/Templates/FeeCycle
//Gurkeerat: resolved issues 931,930,929,926
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/13/09    Time: 4:55p
//Updated in $/LeapCC/Templates/FeeCycle
//fixed bug nos.0000932,0000544,0000550,0000549,0000949
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/12/09    Time: 7:27p
//Updated in $/LeapCC/Templates/FeeCycle
//fixed bug nos. 0000969, 0000965, 0000962, 0000963, 0000980, 0000950
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/FeeCycle
//
//*****************  Version 11  *****************
//User: Arvind       Date: 9/05/08    Time: 5:47p
//Updated in $/Leap/Source/Templates/FeeCycle
//removed unsortable class
//
//*****************  Version 10  *****************
//User: Arvind       Date: 9/01/08    Time: 3:05p
//Updated in $/Leap/Source/Templates/FeeCycle
//modified the date format
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/27/08    Time: 12:50p
//Updated in $/Leap/Source/Templates/FeeCycle
//html validated
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/19/08    Time: 2:47p
//Updated in $/Leap/Source/Templates/FeeCycle
//replaced search button
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/14/08    Time: 7:18p
//Updated in $/Leap/Source/Templates/FeeCycle
//modified the bread crum
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/24/08    Time: 7:37p
//Updated in $/Leap/Source/Templates/FeeCycle
//added the datepicker()  function in four date fields
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/19/08    Time: 5:31p
//Updated in $/Leap/Source/Templates/FeeCycle
//change max lengh of name & code
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/05/08    Time: 5:31p
//Updated in $/Leap/Source/Templates/FeeCycle
//modified for session
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/05/08    Time: 4:28p
//Updated in $/Leap/Source/Templates/FeeCycle
//modification for sessionid
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/01/08    Time: 9:57a
//Updated in $/Leap/Source/Templates/FeeCycle
//modification with cancel image button
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/28/08    Time: 1:46p
//Created in $/Leap/Source/Templates/FeeCycle
//used to show template of fee cycle
//
//*****************  Version 6  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:58p
//Updated in $/Leap/Source/Templates/States
//QA defects fixed and delete code function added
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 6/16/08    Time: 11:13a
//Updated in $/Leap/Source/Templates/States
//Added delete message
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 6/14/08    Time: 1:32p
//Updated in $/Leap/Source/Templates/States
//fixed the defects produced in QA testing and added comments header,
//footer

?>
    <!--End: Div To Edit The Table-->
    


