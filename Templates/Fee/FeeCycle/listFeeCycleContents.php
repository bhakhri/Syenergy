<?php
//-------------------------------------------------------
// Purpose: to design the layout for fee cycle.
//
// Author : Nishu Bindal
// Created on : (3.feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
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
                   <!-- <table width="100%" border="0" cellspacing="0" cellpadding="0" height="40">
                    <tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
                                            </td>
                    </tr>
                    </table>-->
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
<table width="100%" border="0" cellspacing="4" cellpadding="2" class="border">
<tr>
	<td class="contenttab_internal_rows" nowrap="nowrap" >   
	<b>Name<?php echo REQUIRED_FIELD ?></b> 
	 </td>    
	 <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
	 <td class="contenttab_internal_rows" colspan="3"  nowrap>
        <input type="text" id="cycleName" name="cycleName" class="inputbox" maxlength="100"  style="width:280px" onkeydown="return sendKeys(1,'cycleName',event);" /></td>
</tr>
<tr>  
    <td class="contenttab_internal_rows" nowrap="nowrap" >   
	<b>Abbr.<?php echo REQUIRED_FIELD ?></b> 
	 </td>    
	 <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
	 <td class="contenttab_internal_rows"  nowrap colspan="3"> 
    <input type="text" id="cycleAbbr" name="cycleAbbr" class="inputbox" maxlength="10" style="width:280px" onkeydown="return sendKeys(1,'cycleAbbr',event);"  /></td>
</tr>
<tr>
   <td class="contenttab_internal_rows" nowrap="nowrap" >   
	<b>Fee Cycle<?php echo REQUIRED_FIELD ?></b> 
	 </td>    
	 <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
	 
	<td class="contenttab_internal_rows"><nobr>From</nobr></td>
	<td class="contenttab_internal_rows" nowrap>
	    <?php
		     require_once(BL_PATH.'/HtmlFunctions.inc.php');
		    echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'));
	    ?>
	    &nbsp;&nbsp;To&nbsp;&nbsp;
        <?php
		    require_once(BL_PATH.'/HtmlFunctions.inc.php');
		    echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
	    ?>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows" nowrap="nowrap" >   
	<b>Active</b> 
	 </td>    
	 <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
	 <td class="contenttab_internal_rows"  nowrap colspan="4">  
        <input type='radio' name='activeRadio' id='active' value='1'>Yes &nbsp;&nbsp;
        <input type='radio' name='activeRadio' value='0' checked='true' id='inActive'>No
     </td> 
</tr>
<tr>
   <td class="contenttab_internal_rows" nowrap="nowrap" style="padding-top:15px" colspan="5"> 
	 <span  style="color: red;">   
	<b>Date Settings For Student-Login Alerts </b> </span>
	 </td>
</tr>

<tr>
	<td class="contenttab_internal_rows" nowrap="nowrap" >   
	<b>Academic<?php echo REQUIRED_FIELD ?></b> 
	 </td>    
	 <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
	
	<td class="contenttab_internal_rows"><nobr>From</nobr></td>
	<td class="contenttab_internal_rows" nowrap>
	<?php
		 require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('academicFromDate',date('Y-m-d'));
	?>
    &nbsp;&nbsp;To&nbsp;&nbsp;
	<?php
		require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('academicToDate',date('Y-m-d'));
	?>
	</td> 
</tr>
<tr>
	<td class="contenttab_internal_rows" nowrap="nowrap" >   
	<b>Hostel<?php echo REQUIRED_FIELD ?></b> 
	 </td>    
	 <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
	 <td class="contenttab_internal_rows"><nobr>From</nobr></td>
	 <td class="contenttab_internal_rows" nowrap>
	 <?php
		 require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('hostelFromDate',date('Y-m-d'));
	 ?>
	&nbsp;&nbsp;To&nbsp;&nbsp;
	<?php
		require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('hostelToDate',date('Y-m-d'));
	?>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows" nowrap="nowrap" >   
	  <b>Transport<?php echo REQUIRED_FIELD ?></b> 
	</td>    
	 <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
	 <td class="contenttab_internal_rows"><nobr>From</nobr></td>
	 <td class="contenttab_internal_rows" nowrap>
	    <?php
		     require_once(BL_PATH.'/HtmlFunctions.inc.php');
		    echo HtmlFunctions::getInstance()->datePicker('transportFromDate',date('Y-m-d'));
	    ?>
	    &nbsp;&nbsp;To&nbsp;&nbsp;
       <?php
		    require_once(BL_PATH.'/HtmlFunctions.inc.php');
		    echo HtmlFunctions::getInstance()->datePicker('transportToDate',date('Y-m-d'));
	    ?>
	</td> 
</tr>

<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="6">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />&nbsp;
    
	 <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFeeCycle');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditFeeCycle','Edit Fee Cycle'); ?>
<form name="editFeeCycle" action="" method="post">
<table width="100%" border="0" cellspacing="4" cellpadding="2" class="border">
<input type="hidden" name="feeCycleId" id="feeCycleId" value="" />

<tr>
	<td class="contenttab_internal_rows" nowrap="nowrap" >   
	<b>Name<?php echo REQUIRED_FIELD ?></b> 
	 </td>    
	 <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
	 <td class="contenttab_internal_rows"  nowrap colspan="4">
        <input type="text" id="cycleName" name="cycleName" class="inputbox" maxlength="100"  style="width:280px" onkeydown="return sendKeys(2,'cycleName',event);" /></td>
</tr>
<tr>  
    <td class="contenttab_internal_rows" nowrap="nowrap" >   
	<b>Abbr.<?php echo REQUIRED_FIELD ?></b> 
	 </td>    
	 <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
	 <td class="contenttab_internal_rows"  nowrap colspan="4">  
    <input type="text" id="cycleAbbr" name="cycleAbbr" class="inputbox" maxlength="10" style="width:280px" onkeydown="return sendKeys(2,'cycleAbbr',event);"  /></td>
</tr>
<tr>
   <td class="contenttab_internal_rows" nowrap="nowrap" >   
	<b>Fee Cycle<?php echo REQUIRED_FIELD ?></b> 
	 </td>    
	 <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
	
   <td class="contenttab_internal_rows"><nobr>From</nobr></td>
   <td class="contenttab_internal_rows" nowrap>
   <?php
	 require_once(BL_PATH.'/HtmlFunctions.inc.php');
	 echo HtmlFunctions::getInstance()->datePicker('fromDate1',date('Y-m-d'));
	?>
    &nbsp;&nbsp;To&nbsp;&nbsp;
    <?php
     require_once(BL_PATH.'/HtmlFunctions.inc.php');
     echo HtmlFunctions::getInstance()->datePicker('toDate1',date('Y-m-d'));
    ?>
    </td> 

</tr>
<tr>
	<td class="contenttab_internal_rows" nowrap="nowrap" >   
	<b>Active</b> 
	 </td>    
	 <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
	 <td class="contenttab_internal_rows"  nowrap colspan="4">
       <input type='radio' name='editActiveRadio' id='editActive' value='0'>Yes &nbsp;&nbsp;
       <input type='radio' name='editActiveRadio' value='0' checked='true' id='editInActive'>No
     </td> 
</tr>
<tr>
   <td class="contenttab_internal_rows" nowrap="nowrap" colspan="4" style="padding-top:15px"> 
	 <span  style=" color: red;">   
	<b>Date Settings For Student-Login Alerts </b> </span>
	 </td>
</tr>
<tr>
	<td class="contenttab_internal_rows" nowrap="nowrap" >   
	<b>Academic<?php echo REQUIRED_FIELD ?></b> 
	 </td>    
	 <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
	 
	<td class="contenttab_internal_rows"><nobr>From</nobr></td>
	<td class="contenttab_internal_rows" nowrap>
	<?php
		 require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('academicFromDate1',date('Y-m-d'));
	?>
	&nbsp;&nbsp;To&nbsp;&nbsp;
    <?php
		require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('academicToDate1',date('Y-m-d'));
	?>
	</td> 
</tr>
<tr>
	<td class="contenttab_internal_rows" nowrap="nowrap" >   
	<b>Hostel<?php echo REQUIRED_FIELD ?></b> 
	 </td>    
	 <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
	 
	<td class="contenttab_internal_rows"><nobr>From</nobr></td>
	<td class="contenttab_internal_rows" nowrap>
	<?php
		 require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('hostelFromDate1',date('Y-m-d'));
	?>
	&nbsp;&nbsp;To&nbsp;&nbsp;
    <?php
		require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('hostelToDate1',date('Y-m-d'));
	?>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows" nowrap="nowrap" >   
	<b>Transport<?php echo REQUIRED_FIELD ?></b> 
	 </td>    
	 <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
	 <td class="contenttab_internal_rows"><nobr>From</nobr></td>
	<td class="contenttab_internal_rows" nowrap>
	<?php
		 require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('transportFromDate1',date('Y-m-d'));
	?>
	&nbsp;&nbsp;To&nbsp;&nbsp;
    <?php
		require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('transportToDate1',date('Y-m-d'));
	?>
	</td> 
</tr>

</tr>

    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="6">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />&nbsp;
                
					 <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  
                    onclick="javascript:hiddenFloatingDiv('EditFeeCycle');return false;" />
        </td>
</tr>
</table></form>
<?php 
floatingDiv_End(); 
?>
    <!--End: Div To Edit The Table-->
    


