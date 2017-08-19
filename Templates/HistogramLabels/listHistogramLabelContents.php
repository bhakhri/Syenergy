<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR HISTOGRAM LABELS LISTING 
//
//
// Author :Jaineesh
// Created on : (22.10.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
					<td valign="top">Setup&nbsp;&raquo;&nbsp;Histogram Masters&nbsp;&raquo;&nbsp;Histogram Label Master</td>
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
											<td class="content_title">Histogram Label Detail : </td>
											<td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
											align="right" onClick="displayWindow('AddHistorgramLabel',320,250);blankValues();return false;" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="contenttab_row" valign="top" ><div id="results"> 
									<table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
										<tr class="rowheading">
											<td width="5%" class="unsortable"><b>#</b></td>
											<td width="20%" class="searchhead_text"><b>Histogram Label</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
											<td width="3%" class="unsortable" align="right"><b>Action</b></td>
										</tr>
										<?php
											$recordCount = count($histogramLabelRecordArray);
											if($recordCount >0 && is_array($histogramLabelRecordArray) ) {
											$j = $records;
											for($i=0; $i<$recordCount; $i++ ) {

											$bg = $bg =='row0' ? 'row1' : 'row0';

											echo '<tr class="'.$bg.'">
												<td valign="top" class="padding_top" >'.($i+1).'</td>
												<td class="padding_top" valign="top">'.strip_slashes($histogramLabelRecordArray[$i]['histogramLabel']).'</td>
												<td width="100" class="searchhead_text1" align="right"><a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif"  border="0" onClick="editWindow('.$histogramLabelRecordArray[$i]['histogramId'].',\'EditHistogramLabel\',320,250); return false;"/></a>&nbsp;&nbsp;<img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onClick="return deleteHistogramLabel('.$histogramLabelRecordArray[$i]['histogramId'].');" title="Delete"/></td>
											</tr>';
											}
											if($totalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
												$bg = $bg =='row0' ? 'row1' : 'row0';
												require_once(BL_PATH . "/Paging.php");
												$paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
												echo '<tr><td colspan="4" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';
												//echo '<tr class="'.$bg.'"><td class="paging" align="right">'.$paging->ajaxPrintLinks().'</td></tr>';                        
												}
											}
											else {
												echo '<tr><td colspan="5" align="center">No record found</td></tr>';
											}
										?>
									</table>
								   </div>
								   <tr><td class="content_title" title="Print" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" /></td></tr>
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

<?php floatingDiv_Start('AddHistorgramLabel','Add Histogram Label'); ?>
<form name="addHistogramLabel" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>Histogram Label : </b></nobr></td>
			<td width="70%" class="padding"><input type="text" id="histogramLabel" name="histogramLabel" class="inputbox" maxlength="20" tabindex="1" ></td>
		</tr>
		
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" tabindex="2" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" tabindex="3" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddHistorgramLabel');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditHistogramLabel','Edit Designation'); ?>
<form name="editHistogramLabel" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="histogramId" id="histogramId" value="" />
			<tr>
				<td width="30%" class="contenttab_internal_rows"><nobr><b>Histogram Label : </b></nobr></td>
				<td width="70%" class="padding"><input type="text" id="histogramLabel" name="histogramLabel" class="inputbox" maxlength="20" tabindex="1" ></td>
			</tr>
			
			<tr>
				<td height="5px"></td>
			</tr>
			<tr>
				<td align="center" style="padding-right:10px" colspan="2">
				<input type="image" name="imageField" tabindex="2" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<img src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" tabindex="3" onclick="javascript:hiddenFloatingDiv('EditHistogramLabel');" />
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
// $History: listHistogramLabelContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/HistogramLabels
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/24/08   Time: 4:28p
//Created in $/Leap/Source/Templates/HistogramLabels
//contain the template
//
//*****************  Version 20  *****************
//User: Jaineesh     Date: 10/13/08   Time: 5:46p
//Updated in $/Leap/Source/Templates/Designation
//embedded print option
//
//*****************  Version 19  *****************
//User: Jaineesh     Date: 10/13/08   Time: 3:52p
//Updated in $/Leap/Source/Templates/Designation
//embedded print option
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 9/25/08    Time: 4:42p
//Updated in $/Leap/Source/Templates/Designation
//fixed bug
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 8/29/08    Time: 3:35p
//Updated in $/Leap/Source/Templates/Designation
//modified in indentation
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:28p
//Updated in $/Leap/Source/Templates/Designation
//modified in indentation
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 8/27/08    Time: 11:23a
//Updated in $/Leap/Source/Templates/Designation
//modified in html
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 8/19/08    Time: 11:10a
//Updated in $/Leap/Source/Templates/Designation
//modified in search button
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 8/14/08    Time: 6:12p
//Updated in $/Leap/Source/Templates/Designation
//modified for cancel button remove height & width
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 8/12/08    Time: 10:25a
//Updated in $/Leap/Source/Templates/Designation
//modified in bread crump
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 7/18/08    Time: 3:53p
//Updated in $/Leap/Source/Templates/Designation
//change alert in messagebox
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 7/17/08    Time: 10:10a
//Updated in $/Leap/Source/Templates/Designation
//fixed the bug.
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 7/01/08    Time: 9:42a
//Updated in $/Leap/Source/Templates/Designation
//modification with cancel image button
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 6/30/08    Time: 9:43a
//Updated in $/Leap/Source/Templates/Designation
//Give the designation template 
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/25/08    Time: 5:27p
//Updated in $/Leap/Source/Templates/Designation
//giving title name delete
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/25/08    Time: 4:14p
//Updated in $/Leap/Source/Templates/Designation
//modified with some coding error
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/19/08    Time: 2:44p
//Updated in $/Leap/Source/Templates/Designation
?>