<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form action="" method="POST" name="listForm" id="listForm" onsubmit="return false;">
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
						<td class="contenttab_border2" colspan="2">
							<table width="350" border="0" cellspacing="0" cellpadding="0" align="center">
							<tr>
								<td height="10"></td>
							</tr>
							<tr>
								<td class="contenttab_internal_rows" ><nobr><b>Time Table<?php echo REQUIRED_FIELD?></b></nobr></td>
								<td class='contenttab_internal_rows' align='left' ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
								<td class='contenttab_internal_rows' align='left'><nobr>
									<select size="1" class="inputbox1" name="labelId" id="labelId" class="inputbox1" style="width:120px" onclick="hideResults(); return false;">
									<option value="">Select</option>
									<?php
									  require_once(BL_PATH.'/HtmlFunctions.inc.php');
									  echo HtmlFunctions::getInstance()->getAllTimeTableLabelData();
									?>
									</select></nobr>
								</td>
								<td class="contenttab_internal_rows" style='padding-left:10px;'><nobr><b>Bus Route<?php echo REQUIRED_FIELD?>: </b></nobr></td>
								<td class="padding"><select size="1" class="inputbox1" name="routeId" id="routeId"  style="width:250px" onclick="hideResults(); return false;">
								<option value="">Select</option>
								<?php
									require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                    echo HtmlFunctions::getInstance()->getBusRouteName();
								?>
								</select></td>
								<td  align="right" style="padding-left:15px">
								<input type="hidden" name="listRoute" value="1">
								<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="validateAddForm(); return false;"/></td>
							</tr>
							<tr>
								<td colspan="4" height="5px"></td>
							</tr>
							</table>
					    </td>
					</tr>
					<tr style="display:none" id="showTitle">
						<td class="contenttab_border" height="20" colspan="2">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
							<tr>
								<td class="content_title">Bus Route Stop Mapping : </td>
								<td align="right"   id = 'saveDiv2' style='display:none;'>
                                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onclick="return validateAddForm();return false;" />
                                </td>
							</tr>
							</table>
						</td>
					</tr>
					 <tr style="display:none" id="showData">
						<td class="contenttab_row" valign="top" colspan="2">
                           <div id="scroll2" style="overflow:auto; height:410px; vertical-align:top;">
                              <div id="resultsDiv"></div> 
                           </div>   
                        </td>
					 </tr>
					 <tr>
						<td height="10" colspan="2"></td>
					 </tr>
					 <tr  id = 'saveDiv1' style='display:none;'>
						<td align="right" width="55%">
						     <input type="hidden" name="submitRoute" value="1">
						     <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
                             <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCourseToClassCSV();return false;"/>
                              <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onclick="return validateAddForm();return false;" />
                         </td>
					</tr>
				</table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
</form>    
<?php
// $History: listSubjectToClassContents.php $
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/SubjectToClass
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/13/09    Time: 10:36a
//Updated in $/LeapCC/Templates/SubjectToClass
//Gurkeerat: resolved issue 1059
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 8/11/09    Time: 11:52a
//Updated in $/LeapCC/Templates/SubjectToClass
//0001009: Associate Subject to Class (Admin) > Print window Caption
//should be “Subject to Class Report Print” as clicked on “Print” button
//0001010: Associate Subject to Class (Admin) > Provide Save button on
//the right top of the grid.
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/10/09    Time: 11:13a
//Updated in $/LeapCC/Templates/SubjectToClass
//Fixed bug no 984,982
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/12/09    Time: 10:23a
//Updated in $/LeapCC/Templates/SubjectToClass
//added required field and centralized message
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/11/08   Time: 3:00p
//Updated in $/LeapCC/Templates/SubjectToClass
//Updated module as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/SubjectToClass
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 9/11/08    Time: 5:38p
//Updated in $/Leap/Source/Templates/SubjectToClass
//updated formatting and added comments
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 8/21/08    Time: 11:15a
//Updated in $/Leap/Source/Templates/SubjectToClass
//updated the formatting
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 8/18/08    Time: 7:34p
//Updated in $/Leap/Source/Templates/SubjectToClass
//updated formatting
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/18/08    Time: 3:31p
//Updated in $/Leap/Source/Templates/SubjectToClass
//updated with new buttons and print reports
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/14/08    Time: 2:10p
//Updated in $/Leap/Source/Templates/SubjectToClass
//added print report
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 8/11/08    Time: 10:59a
//Updated in $/Leap/Source/Templates/SubjectToClass
//updated the formatting and other issues
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/09/08    Time: 2:02p
//Updated in $/Leap/Source/Templates/SubjectToClass
//updated the functionality to map subject with class.
//made ajax based and removed study period and batch from search
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/05/08    Time: 3:39p
//Updated in $/Leap/Source/Templates/SubjectToClass
//placed submit button outside the result div
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/29/08    Time: 12:41p
//Updated in $/Leap/Source/Templates/SubjectToClass
//optimize the query
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/24/08    Time: 3:18p
//Updated in $/Leap/Source/Templates/SubjectToClass
//updated file
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/08    Time: 12:55p
//Created in $/Leap/Source/Templates/SubjectToClass
//intial checkin
?>
