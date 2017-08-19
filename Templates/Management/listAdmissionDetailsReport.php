<?php 
//-------------------------------------------------------
//  This File contains html form for all details report
//
//
// Author :Rajeev Aggarwal
// Created on : 12-12-2008
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
						<td class="padding"><select size="1" name="searchStudent" id="searchStudent" class="inputbox1">
							<option value="">Select</option>
							<option value="Branch" <?php if($_REQUEST['searchStudent']=='Branch') echo "Selected";?>>Branch</option>
							<option value="Degree" <?php if($_REQUEST['searchStudent']=='Degree') echo "Selected";?>>Degree</option>
							<option value="Batch" <?php if($_REQUEST['searchStudent']=='Batch') echo "Selected";?>>Batch</option>
							<option value="Category" <?php if($_REQUEST['searchStudent']=='Category') echo "Selected";?>>Category</option>
							<option value="Hostel" <?php if($_REQUEST['searchStudent']=='Hostel') echo "Selected";?>>Hostel</option>
							<!--option value="City" <?php if($_REQUEST['searchStudent']=='City') echo "Selected";?>>City</option>
							<option value="State" <?php if($_REQUEST['searchStudent']=='State') echo "Selected";?>>State</option-->
							<option value="Gender" <?php if($_REQUEST['searchStudent']=='Gender') echo "Selected";?>>Gender</option>
						</select></td>
						<td align="left"  valign="middle">
                        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/display.gif"  onClick="return getSearch();"/>
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
//$History: listAdmissionDetailsReport.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/27/08   Time: 5:34p
//Created in $/LeapCC/Templates/Management
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:02p
//Created in $/LeapCC/Templates/Management
//Initial checkin
?>