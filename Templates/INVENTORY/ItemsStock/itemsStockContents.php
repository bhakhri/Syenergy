<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR BUSSTOP LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
								<span class="content_title">
								Openining Stock List :</span>
                                    <?php// require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
                </td>
                                <!--<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddGRN',360,250);blankValues();return false;"" />&nbsp;</td>--></tr>
             <tr>
                                <td class="contenttab_row" colspan="2" valign="top" >
								<form name="listForm" action="" method="post">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr>
								<td valign="top" class="contenttab_row1">
									<table width="88%" align="center" border="0" cellspacing="" cellpadding="">
									  <tr>
											<td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Cateogy Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
											<td class="padding"><nobr>:&nbsp;<select size="1" class="selectfield" name="categoryCode" id="categoryCode" onChange="getCategory(this.value);blankValues();">
											<option value="">Select</option>
											<?php
												require_once(BL_PATH.'/HtmlFunctions.inc.php');
												echo HtmlFunctions::getInstance()->getItemCategoryData();
											?>
											</select></nobr>
											</td>
											<td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Category Name</b></nobr></td>
											<td class="padding"><nobr>:&nbsp;<input name="categoryName" id="categoryName" class="inputbox" value="" maxlength="100" readonly="readonly"/></nobr></td>
											<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;As On Date</b></nobr></td>
											<td class="padding"><nobr>&nbsp;<b>:</b>
											<?php
												require_once(BL_PATH.'/HtmlFunctions.inc.php');
												echo HtmlFunctions::getInstance()->datePicker('stockDate',date('Y-m-d'));
											?>
											</nobr></td>
											<td valign="bottom" align="left" >
											<input type="image" name="imageField" title="Show List" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getOpeningStockData(); return false;" />
											</td>
									  </tr>
									  <tr><td colspan="10"><span id="title"><b>&nbsp;&nbsp;&nbsp;Note : Selected date will be considered for all calculations.</b></span></td></tr>
									</table>
								</td>
							 </tr>
						</table>
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
						  <tr id='nameRow' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										<tr>
											<td colspan="1" class="content_title">Openining Stock List :</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
									<div id = 'resultDiv' style="max:height;overflow:auto;"></div>
								</td>
							</tr>
							<tr id='saveData' style='display:none;'>
								<td colspan="1" class="content_title" align="right"><input type="image" name="save" value="save" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="addStock();return false;" />&nbsp;</td>
							</tr>
							<!--
							<tr id='showPrint' style='display:none;'>
								<td colspan="1" class="content_title" align="right"><input type="image" name="tyreRetreadingPrint" value="tyreRetreadingPrint" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
							</tr>-->
						</table> 
						</form>
								
								<div id="results"></div>
								</td>
		  </tr>
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>   
<?php
// $History: listTyreRetreadingReportContents.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 2/03/10    Time: 10:14a
//Updated in $/Leap/Source/Templates/TyreRetreading
//put new report tyre retreading
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/02/10    Time: 5:18p
//Created in $/Leap/Source/Templates/TyreRetreading
//new templates for tyre retreading
//
?>