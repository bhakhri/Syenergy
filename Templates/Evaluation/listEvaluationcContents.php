<?php
//-------------------------------------------------------
// Purpose: to design the layout for evaluation criteria.
//
// Author : Rajeev Aggarwal
// Created on : (05.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");?>    
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
							<td class="content_title">Evaluation Criteria Detail : </td>
						</tr>
						</table>
					</td>
	            </tr>
				<tr>
					<td class="contenttab_row" valign="top" ><div id="results">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
					<tr class="rowheading">
						<td width="3%" class="searchhead_text">&nbsp;&nbsp;<b>#</b></td>
						<td width="97%" class="searchhead_text"><b>Name </b></td>
					</tr>
					<?php
					$recordCount = count($evalutionCritieriaArray);
					if($recordCount >0 && is_array($evalutionCritieriaArray) ) { 
					   for($i=0; $i<$recordCount; $i++ ) {
							
							$bg = $bg =='row0' ? 'row1' : 'row0';
							
						echo '<tr class="'.$bg.'">
							<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
							<td class="padding_top" valign="top">'.strip_slashes($evalutionCritieriaArray[$i]['evaluationCriteriaName']).'</td>
							 
							</tr>';
						}
					}
					else {
						echo '<tr><td colspan="5" align="center">No record found</td></tr>';
					}
					?>                 
					</table></div>           
					</td>
				</tr>
				<tr>
						<td height="5"></td>
					</tr>
					<tr>
						<td height="20">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
							<tr>
								<td colspan='1' align='right' valign="middle"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport()"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV()"/></td>
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
<?php 
// $History: listEvaluationcContents.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/27/09    Time: 6:13p
//Updated in $/LeapCC/Templates/Evaluation
//Gurkeerat: resolved issue 1273,1275
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/10/09    Time: 12:27p
//Updated in $/LeapCC/Templates/Evaluation
//Added print and export to CSV functionality 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Evaluation
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/05/08    Time: 2:33p
//Updated in $/Leap/Source/Templates/Evaluation
//updated the code formatting
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/05/08    Time: 1:56p
//Updated in $/Leap/Source/Templates/Evaluation
//updated according to new pattern
?>