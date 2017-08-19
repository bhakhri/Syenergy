<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR HISTOGRAM LABELS LISTING 
//
//
// Author :Jaineesh
// Created on : (22.10.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 ?>
    
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td valign="top">Setup&nbsp;&raquo;&nbsp;Histogram Masters&nbsp;&raquo;&nbsp;Histogram Scale Master</td>
					<td valign="top" align="right">
					<form action="" method="" name="searchForm">
						<input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
						&nbsp;
						<input type="image" name="submit" align="absbottom" src="<?php echo IMG_HTTP_PATH;?>/search.gif" style="margin-right: 5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;" />
					</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td class="contenttab_border" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr>
											<td class="content_title">Histogram Scale Detail : </td>
											<td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
											align="right" onClick="displayWindow('AddHistorgramScale',320,250);blankValues();return false;" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="contenttab_row" valign="top" ><div id="results"> 
								   </div>
								   
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

<?php floatingDiv_Start('AddHistorgramScale','Add Histogram Scale'); ?>
<form name="addHistogramScale" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>Range From : </b></nobr></td>
			<td width="70%" class="padding"><input type="text" id="histogramRangeFrom" name="histogramRangeFrom" class="inputbox" maxlength="3" tabindex="1" ></td>
		</tr>

		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>Range To : </b></nobr></td>
			<td width="70%" class="padding"><input type="text" id="histogramRangeTo" name="histogramRangeTo" class="inputbox" maxlength="3" tabindex="2" ></td>
		</tr>

		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>Histogram Label : </b></nobr></td>
			<td class="padding"><select size="1" class="selectfield" name="histogramLabel" id="histogramLabel" tabindex="3">
			<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getHistogramLabels($REQUEST_DATA['histogramLabel']==''? $histogramScaleRecordArray[0]['histogramId'] : $REQUEST_DATA['histogramLabel']);
				?>
			</select></td>
		</tr>
		
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" tabindex="4" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" tabindex="5" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddHistorgramScale');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditHistogramScale','Edit Histogram Scale'); ?>
<form name="editHistogramScale" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="histogramScaleId" id="histogramScaleId" value="" />
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>Range From : </b></nobr></td>
			<td width="70%" class="padding"><input type="text" id="histogramRangeFrom" name="histogramRangeFrom" class="inputbox" maxlength="3" tabindex="1" ></td>
		</tr>

		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>Range To : </b></nobr></td>
			<td width="70%" class="padding"><input type="text" id="histogramRangeTo" name="histogramRangeTo" class="inputbox" maxlength="3" tabindex="2" ></td>
		</tr>

		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>Histogram Label : </b></nobr></td>
			<td class="padding"><select size="1" class="selectfield" name="histogramLabel" id="histogramLabel" tabindex="3">
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getHistogramLabels($REQUEST_DATA['histogramLabel']==''? $histogramScaleRecordArray[0]['histogramId'] : $REQUEST_DATA['histogramLabel']);
				?>
			</select></td>
		</tr>
			
			<tr>
				<td height="5px"></td>
			</tr>
			<tr>
				<td align="center" style="padding-right:10px" colspan="2">
				<input type="image" name="imageField" tabindex="4" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<img src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" tabindex="5" onclick="javascript:hiddenFloatingDiv('EditHistogramScale');" />
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
// $History: listHistogramScaleContents.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/11/09    Time: 5:26p
//Updated in $/LeapCC/Templates/HistogramScale
//show mandatory field histogram label
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/HistogramScale
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/25/08   Time: 11:15a
//Updated in $/Leap/Source/Templates/HistogramScale
//modified
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/24/08   Time: 4:28p
//Created in $/Leap/Source/Templates/HistogramScale
//contain the template
//
?>