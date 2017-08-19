<?php
//-------------------------------------------------------
// Purpose: to design the layout for Hostel.
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH . "/helpMessage.inc.php");
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
                                <td width="24%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;" title = "Add Item"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddPhoto',450,250);return false;" />&nbsp;</td></tr>
             <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
             </tr>
             <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" title="Print">&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" title="Export To Excel"> 
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
<?php floatingDiv_Start('AddItem','Add Item'); ?>
<form name="AddItem" action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr><td height="5px" colspan="3"></td></tr>
    <tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Institute <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<select size="1" class="selectfield" name="institute" id="institute" onChange="getCategory(this.value,'Add');">
			<option value="">Select</option>
				 
			</select>
        </td>
	</tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Institute Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<input name="instituteName" id="instituteName" class="inputbox" value="" maxlength="100" onkeydown="return sendKeys(1,'itemPrefix',event);" readonly="readonly"/></td>
	</tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Event Name <?php echo REQUIRED_FIELD ?></b>
		
	</nobr></td>
        <td class="padding">:&nbsp;<input name="eventName" id="eventName" class="inputbox" value="" maxlength="100" onkeydown="return sendKeys(1,'itemName',event);"/>  
        </td>
	</tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Data<?php echo REQUIRED_FIELD ?></b>
	
	</nobr></td>
        <td class="padding">:&nbsp;<input name="data" id="data" class="inputbox" value="" maxlength="20" onkeydown="return sendKeys(1,'itemCode',event);"/></td>
	</tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Photo<?php echo REQUIRED_FIELD ?></b>
		
	</nobr></td>
        <td class="padding">:&nbsp;<input name="photo" id="photo" class="inputbox" value="" maxlength="3" onkeydown="return sendKeys(1,'reorderLevel',event);"/></td>
	</tr>

	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Comments<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<input name="comments" id="comments"  value="" /></td>
			
		</td>
   </tr>

<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Download<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<input type="image" name="downloadImg" id ="downloadImg"src="<?php echo IMG_HTTP_PATH;?>/download2.gif"  />&nbsp;/></td>
			
		</td>
   </tr>
   <tr>
		<td height="5px" colspan="3"></td>
   </tr>
	<tr>
		<td align="center" style="padding-right:10px" colspan="3">
		<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
		<input type="image" name="addCamcel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddItem');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
      
		</td>

	</tr>
	<tr>
		<td height="5px" colspan="3"></td>
	</tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->



<!--Help  Details  Div-->
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
	   <tr>
	     <td height="5px"></td></tr>
	   <tr>
		<td width="89%">
		<div id="helpInfo" style="vertical-align:top;" ></div>
		</td>
		</tr>
	</table>
</div>
<?php floatingDiv_End(); ?>
<!--Help  Details  End -->


<!--Add Photos-->
<?php  floatingDiv_Start('AddPhoto','Add Event Photographs'); ?>
<form name="addPhoto" id="addPhoto" method="post" enctype="multipart/form-data" style="display:inline" onSubmit="return false;" >
<iframe id="uploadTargetAdd" name="uploadTargetAdd" style="width:0px;height:0px;border:0px solid #fff;"></iframe> 
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
 <tr><td height="5px" colspan ="2"></td></tr>
 <tr>
        <td class="contenttab_internal_rows" >&nbsp;&nbsp;&nbsp;<nobr><b>Date<?php echo REQUIRED_FIELD ?></b></nobr></td> 
        <td class="padding">:&nbsp;<input name="date" id="date" class="inputbox" value="" /></td>
	</tr>
<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Institute Name <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<select size="1" class="selectfield" name="institute" id="institute" >
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getInstituteData();
              ?>
        </select>
        </td>
	</tr>
 <tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Event Name<?php echo REQUIRED_FIELD ?></b>
	
	</nobr></td>
        <td class="padding">:&nbsp;<input name="eventName" id="eventName" class="inputbox" value="" /></td>
	</tr>
   <tr>
    <td width="100%" colspan="2" style="width:650px;" >
    <div id="tableDiv" style="height:350px;width:700px;overflow:auto;">
    <table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid">
        <tbody id="anyidBody">
            <tr class="rowheading">
                <td width="5%" class="contenttab_internal_rows"><b>#</b></td>
                 <td width="10%" class="contenttab_internal_rows"><b>Upload Photos</b>
                 <td width="10%" class="contenttab_internal_rows"><b>Comments</b></td>
                  <td width="10%" class="contenttab_internal_rows"><b>Delete</b></td>
            </tr>
        </tbody>
        </table>
    </div>    
    </td>
    </tr>
   
    <tr>
    <td colspan="2">
    <input type="hidden" name="deleteFlag" id="deleteFlag" value="" />
       <a href="javascript:addOneRow(1);" title="Add Row"><font class="textClass"><b><nobr><u>Add More</u></b></font></a>
    </td>
    </tr> 
  
  <tr>
    <td height="5px" colspan="2"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
      <!-- <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validatePhotoDescription();return false;" />-->
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddPhoto');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
       <input type="image" name="uploadImg" src="<?php echo IMG_HTTP_PATH;?>/upload.gif" onClick="uploadImages();" />&nbsp;
    </td>
 </tr>
<tr><td height="5px" colspan="2"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--Help  Details  End -->

