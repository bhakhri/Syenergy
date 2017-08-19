<?php 
//This file creates Html Form output for Hostel Room
//
// Author :Jaineesh
// Created on : 16.07.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
      <td>
		<?php require_once(TEMPLATES_PATH."/breadCrumb.php");?>
	</td>
	<td valign="top" class="title">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td height="10"></td>
			</tr>
			<tr>
				<td valign="top" colspan="2"></td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
			<tr>
				<td valign="top" class="content">
				<form name="VehicleDetailForm" action="" method="post" onSubmit="return false;">
					<!-- form table starts -->
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
						<tr>
							<td valign="top" class="contenttab_row1">
								
								<table width="30%" align="center" border="0" >
						<tr>
							<td height="10"></td>
						</tr>
						<tr>	
							<td class="contenttab_internal_rows" nowrap><nobr><b>Report As On Date</b></nobr></td>
                            <td class="contenttab_internal_rows" nowrap><nobr><b>:&nbsp;</b></nobr></td>
							<td class="contenttab_internal_rows" nowrap>
                                <?php  
                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                    echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'));  
                                ?>    
                            </td>
							<td align="center" style="padding-left:20px" nowrap>
							    <input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
							</td>
						</tr>
									</table>
							</td>
						</tr>
					</table>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr id='nameRow' style='display:none;'>
							<td class="" height="20">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
						<tr>
                            <td colspan="1" class="content_title" align="left">
                            Hostel Details
                            </td>
							<td colspan="1" class="content_title" align="right">
                                <input style="display:none" type="image" name="cleaningHistoryPrint" value="cleaningHistoryPrint" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
						</tr>
						</table>
							</td>
						</tr>
						<tr id='resultRow' style='display:none;'>
							<td colspan='1' class='contenttab_row'>
								 
 						<div id="scroll2" style="overflow:auto; height:410px;width:1000px; vertical-align:top;"> 
                                       		<div id="resultsDiv"></div>
                                      		</div>  

							</td>
						</tr>
						
						
						<tr id='nameRow2' style='display:none;'>
							<td class="" height="20">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
						<tr>
							
							<td colspan="2" class="content_title" align="right">
                              <input style="display:none" type="image" name="cleaningHistoryPrint" value="cleaningHistoryPrint" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
						</tr>
							</table>
							</td>
						</tr>
					</table>
					</form>
					<!-- form table ends -->
					
				</td>
			</tr>
		</table>
	</table>


<?php 
//$History: listHostelRoomDetailContents.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/20/09    Time: 5:44p
//Updated in $/Leap/Source/Templates/Hostel
//put new fields Check In Date, Check Out Date
//Put new field vacant room in hostel room detail
//Give link for hostel room detail under reports->hostel
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/20/09    Time: 5:09p
//Created in $/Leap/Source/Templates/Hostel
//new template to show hostel room type detail
//
?>
