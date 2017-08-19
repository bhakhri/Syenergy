<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR BUSSTOP LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
						<!-- form table starts -->
						<form name="tyreList" action="" method="post" onSubmit="return false;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr>
								<td valign="top" class="contenttab_row1">
									<table width="20%" align="center" border="0" cellspacing="2" cellpadding="2">
									  <tr>
										<td width="10%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Tyre No.</b></nobr></td>
										<td class="padding">&nbsp;<b>:</b></td>
										<td class="padding">
											<input type="text" name="tyreNo" id="tyreNo" class="inputbox" maxlength="50" />   
										</nobr></td>
										<td valign="bottom" align="left" >
											<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getTyreExistance();return false;" />
										</td>
									 </tr>
									</table>
							 </tr>
						</table>
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
						  <tr id='nameRow' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										<tr>
											<td colspan="1" class="content_title">Tyre Retreading Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="tyreRetreadingPrint" value="tyreRetreadingPrint" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
									<div id = 'resultDiv'></div>
								</td>
							</tr>
							<tr id='showPrint' style='display:none;'>
								<td colspan="1" class="content_title" align="right"><input type="image" name="tyreRetreadingPrint" value="tyreRetreadingPrint" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
							</tr>
						</table> 
						</form>
						<!-- form table ends -->
						
					</td>
				</tr>
			</table>
		</table>
<?php floatingDiv_Start('ViewReason','Reason Description','',$wrapType=''); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<form name="viewReason" action="" method="post"> 
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Reason</b></td>
</tr>

<tr>
	<td width="100%" align="left" style="padding-left:5px">
	<br />
	<div id="innerReason" style="overflow:auto; width:380px;" ></div><br>
	</td>
</tr>

<tr>
    <td height="5px"></td>
</tr>

   </form>
</table>

<?php floatingDiv_End(); ?>
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