<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
				<td valign="top" class="content">
				
				 <table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="contenttab_border2" colspan="2">
							<form action="" method="POST" name="listForm" id="listForm">
							<table border="0" cellspacing="0" cellpadding="0" align="center">
							<tr>
								<td height="10"></td>
							</tr>
							<tr>	
								<td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Report Type: </b></nobr></td>
								<td class="padding"><select size="1" class="inputbox1" name="reportType" id="reportType" style="width:150px">
								<option value="">Select</option>
								<option value="1">Programme Wise</option>
								 
								</select></td>
								<td  align="right">
								<input type="hidden" name="listSubject" value="1">
								<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="showTestGraph(this.form); return false;"/></td> 
							</tr>
							<tr>
								<td colspan="4" height="5px"></td>
							</tr>	
							</table>
							</form>
					    </td>
					</tr>
					<tr id='showTitle' style='display:none;'>
						<td class="contenttab_border" height="20" colspan="2">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
							<tr>
								<td class="content_title">Hostel Detail Report: </td>
								 
							</tr>
							</table>
						</td>
					</tr>
					 <tr>
						<td class="contenttab_row" valign="top" colspan="2" align="center"><div id="resultsDiv1">  
						 </div></td>
					 </tr>
					  
					<tr id='nameRow3' style='display:none;'>
						<td class="" colspan="2" height="20">
							<table width="40%" border="0" cellspacing="0" cellpadding="0" height="20"  class="" align="right">
								<tr>
									<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" /></td>
								</tr>
							</table>
							<form method="POST" name="addForm"  action="<?php echo UI_HTTP_PATH;?>/imageSave.php" id="addForm" method="post" enctype="multipart/form-data" style="display:inline" target="imageDataFrame">
							<input type='hidden' name='imageData' value='' />
							<iframe id="imageDataFrame" name="imageDataFrame" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
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
// $History: hostelDetailContent.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/04/09    Time: 5:07p
//Created in $/LeapCC/Templates/StudentReports
//intial checkin for programme wise gender wise hostel detail
?>