<?php 
//-------------------------------------------------------
//  This File contains html form for all details report
//
//
// Author :Rajeev Aggarwal
// Created on : 18-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
			
				<tr>
			  <?php  require_once(TEMPLATES_PATH . "/breadCrumb.php");?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="605">
				<tr>
             <td valign="top" class="content">
			
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border2" height="20">

                   <form action="" method="POST" name="searchForm" id="searchForm">
                    <table border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<td height="5"></td>
					</tr>
					<tr>	
						<td class="contenttab_internal_rows"><nobr><b>Please select the filter criteria: </b></nobr></td>
						<td class="padding"><select size="1" name="searchStudent" id="searchStudent"class="inputbox1">
						<option value="">Select</option>
						<option value="Branch" <?php if($_REQUEST['searchStudent']=='Branch') echo "Selected";?>>Branch</option>
						<option value="City" <?php if($_REQUEST['searchStudent']=='City') echo "Selected";?>>City</option>
						<option value="Designation" <?php if($_REQUEST['searchStudent']=='Designation') echo "Selected";?>>Designation</option>
						<option value="Gender" <?php if($_REQUEST['searchStudent']=='Gender') echo "Selected";?>>Gender</option>
						<option value="Marital" <?php if($_REQUEST['searchStudent']=='Marital') echo "Selected";?>>Marital Status</option>
						<option value="RoleWise" <?php if($_REQUEST['searchStudent']=='RoleWise') echo "Selected";?>>Role Wise</option>
						<option value="State" <?php if($_REQUEST['searchStudent']=='State') echo "Selected";?>>State</option>
						<option value="Teaching" <?php if($_REQUEST['searchStudent']=='Teaching') echo "Selected";?>>Teaching/Non-Teaching</option>
						</select></td>
						<td align="left"  valign="middle">
                        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/display.gif"   onClick="return getSearch();"/>
                        </td>
					</tr>
					<tr><td colspan="4" height="5px"></td></tr>

					</table>
					 </form>
                </td>
             </tr>
			 <tr>
				<td valign="top" class="content" align="center"><div id="resultsDiv"></div></td>
			 </tr>
			</table>
		</table>
<?php 
//$History: allEmployeeDetailsReport.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:02p
//Created in $/LeapCC/Templates/Management
//Initial checkin
?>
