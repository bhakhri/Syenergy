   <?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Subject Category LISTING 
//
// Author :Parveen Sharma
// Created on : (06.07.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddGroup',300,200);getGroupName(); blankValues();return false;" />&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                            </tr>
								<tr>
                                <td align="right" colspan="2">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
											<tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image" title="print"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image" title="Export To Excel" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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

<?php floatingDiv_Start('AddGroup','Add Subject Category'); ?>
<form name="addGroup" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		
		<tr>
			<td width="25%" class="contenttab_internal_rows"><nobr><b>&nbsp;Category Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="2%" class="padding"><b>:</b></td>
            <td width="73%" class="padding">
                <input type="text" maxlength="50" id="categoryName"  name="categoryName" class="inputbox" style="width:170px" value="" />
            </td>
		</tr>
        <tr>
            <td width="25%" class="contenttab_internal_rows"><nobr><b>&nbsp;Abbr.</b></nobr></td>
            <td width="2%" class="padding"><b>:</b></td>
            <td width="73%" class="padding"><input type="text" maxlength="20" id="abbr" name="abbr" class="inputbox" style="width:170px" value="" />
            </td>
        </tr>
        <tr>
            <td width="25%" class="contenttab_internal_rows"><nobr><b>&nbsp;Parent Category</b></nobr></td>
            <td width="2%" class="padding"><b>:</b></td>
            <td width="73%" class="padding">
              <select size="1" class="selectfield" name="parentCategoryId" id="parentCategoryId" style="width:174px">
              <option value="">Select</option>
              <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                //echo HtmlFunctions::getInstance()->getParentSubjectCategoryData();
              ?>
            </select>
           </td>
        </tr>
		<tr>
			<td height="5px" colspan="3"></td>
        </tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="3">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;"  />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddGroup');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

 <!--Start subject Details Div-->
<!--Start subject details  Div-->
<?php floatingDiv_Start('divSubjectDetails','Subject Details','',''); ?>
<form name="divForm11" id="divForm11" action="" method="post">  
<input type="hidden" name="categoryId" id="categoryId" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td width="95%" align="center" valign="top">                          
          <div  style="overflow:auto; width:810px; height:400px; vertical-align:top;">
            <div id="resultInfo" style="width:810px; height:400px; vertical-align:top;"></div>
          </div>  
        </td>
    </tr>
<tr><td height="5px"></td></tr>
<tr>
 <td align="center">
 <input type="image" title="print"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport1(); return false;" >&nbsp;
  <input type="image" title="Export To Excel" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV1();  return false;" >
 </td>
</tr>
</table>
</form> 
<?php floatingDiv_End(); ?>
<!--End subject details Div-->




<!--Start Edit Div-->
<?php floatingDiv_Start('EditGroup','Edit Subject Category'); ?>
<form name="editGroup" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="subjectCategoryId" id="subjectCategoryId" value="" />
		<tr>
            <td width="25%" class="contenttab_internal_rows"><nobr><b>&nbsp;Category Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td width="2%" class="padding"><b>:</b></td>
            <td width="73%" class="padding">
            <input type="text" maxlength="50" id="categoryName" name="categoryName" class="inputbox" style="width:170px" value="" />
            </td>
        </tr>
        <tr>
            <td width="25%" class="contenttab_internal_rows"><nobr><b>&nbsp;Abbr.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td width="2%" class="padding"><b>:</b></td>
            <td width="73%" class="padding"><input type="text" maxlength="20" id="abbr" name="abbr" class="inputbox" style="width:170px" value="" />
            </td>
        </tr>
        <tr>
            <td width="25%" class="contenttab_internal_rows"><nobr><b>&nbsp;Parent Category</b></nobr></td>
            <td width="2%" class="padding"><b>:</b></td>
            <td width="73%" class="padding">
             <select size="1" class="selectfield" name="parentCategoryId" id="parentCategoryId" style="width:174px">
              <option value="">Select</option>
              <?php
              //  require_once(BL_PATH.'/HtmlFunctions.inc.php');
            //    echo HtmlFunctions::getInstance()->getParentSubjectCategoryData();
              ?>
            </select>
        </td>
        </tr>
	
        <tr>
            <td height="5px" colspan="3"></td>
        </tr>
     	<tr>
				<td align="center" style="padding-right:10px" colspan="3">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<input type="image" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditGroup');return false;" />
				</td>
			</tr>
			<tr>
				<td height="5px"></td>
			</tr>
    </table>
</form>
<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->
    
<?php 
// $History: listSubjectCategoryContents.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Templates/SubjectCategory
//search & conditions updated
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Templates/SubjectCategory
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/13/09    Time: 10:05a
//Updated in $/LeapCC/Templates/SubjectCategory
//New field added Abbr.
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/11/09    Time: 3:44p
//Updated in $/LeapCC/Templates/SubjectCategory
//abbr new filed added 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/07/09    Time: 2:16p
//Created in $/LeapCC/Templates/SubjectCategory
//initial checkin
//

?>
