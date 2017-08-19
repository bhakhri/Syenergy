<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Fine Category LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (02.07.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddFineCategory',330,250);blankValues();return false;" />&nbsp;</td>
							</tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
             				<tr>
								<td align="right" colspan="2">
                    				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    				<tr>
											<td class="content_title" valign="middle" align="right" width="20%">
												<input type="image" title="Print"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
												<input type="image" title="Export to Excel" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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
<?php floatingDiv_Start('AddFineCategory','Add Fine/Activity Category'); ?>
<form name="AddFineCategory" action="" method="post">  
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
     <td width="31%" class="contenttab_internal_rows"><nobr><strong>Category Name<?php echo REQUIRED_FIELD; ?>:</strong></nobr></td>
     <td width="69%" class="padding">
      <input type="text" id="categoryName" name="categoryName" class="inputbox" maxlength="150"/>
     </td>
   </tr>
   <tr>
    <td class="contenttab_internal_rows"><nobr><strong>Category Abbr.<?php echo REQUIRED_FIELD; ?>:</strong></nobr></td>
    <td width="69%" class="padding">
     <input type="text" id="categoryAbbr" name="categoryAbbr" class="inputbox" maxlength="150"/>
    </td>
   </tr>   
	<tr>
	    <td class="contenttab_internal_rows" nowrap="nowrap">  
	    	<nobr><b>Type<?php echo REQUIRED_FIELD; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b></nobr> 
	    </td>    
	    <td nowrap>
	    	<input name="fineType" id="fineType1" value="Fine" onclick="" checked="checked" type="radio">Fine&nbsp;&nbsp;&nbsp;
	        <input name="fineType" id="fineType2" value="Activity" onclick="" type="radio">Activity
	    </td>
	</tr>
	   
   
 <tr>
 	<td height="5px"></td>
 </tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFineCategory');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditFineCategory','Edit Fine/Activity Category '); ?>
<form name="EditFineCategory" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="fineCategoryId" id="fineCategoryId" value="" />  
   <tr>
     <td width="31%" class="contenttab_internal_rows"><nobr><strong>Category Name<?php echo REQUIRED_FIELD; ?>:</strong></nobr></td>
     <td width="69%" class="padding">
      <input type="text" id="categoryName" name="categoryName" class="inputbox" maxlength="150"/>
     </td>
   </tr>
   <tr>
   	<td class="contenttab_internal_rows"><nobr><strong>Category Abbr.<?php echo REQUIRED_FIELD; ?>:</strong></nobr></td>
    <td width="69%" class="padding">
     <input type="text" id="categoryAbbr" name="categoryAbbr" class="inputbox" maxlength="150"/>
    </td>
   </tr>
   <tr>
	    <td class="contenttab_internal_rows" nowrap="nowrap">  
	    	<nobr><b>Type<?php echo REQUIRED_FIELD; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b></nobr> 
	    </td>    
	    <td nowrap>
	    	<input name="fineType" id="editfineType1" value="Fine" onclick="" checked="checked" type="radio">Fine&nbsp;&nbsp;&nbsp;
	        <input name="fineType" id="editfineType2" value="Activity" onclick="" type="radio">Activity
	    </td>
	</tr>
	   
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditFineCategory');return false;" />
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
// $History: listFineCategoryContents.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 3/09/09    Time: 11:37
//Updated in $/LeapCC/Templates/Fine
//Done bug fixing.
//Bug ids---
//00001407,00001408,00001409,
//00001419,00001420
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 27/07/09   Time: 16:05
//Updated in $/LeapCC/Templates/Fine
//Done bug fixing.
//bug ids---0000697 to 0000702
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/07/09    Time: 15:31
//Created in $/LeapCC/Templates/Fine
//Added files for "fine_category" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/07/09    Time: 16:08
//Created in $/LeapCC/Templates/FineCategory
//Created "Fine Category Master" module
?>