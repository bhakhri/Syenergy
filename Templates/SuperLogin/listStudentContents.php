<?php
//-------------------------------------------------------
// Purpose: to design the student detail for subject centric.
//
// Author : Rajeev Aggarwal
// Created on : (05.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");   
?>

    <tr>
        <td valign="top" colspan=2>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
		<tr>
		 <td valign="top" class="content">
		 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
		 <tr>
			<td valign="top" class="contenttab_row1" align="center">
			<form name="allDetailsForm" action="" method="post" onSubmit="return false;">
				<table border='0' width='100%' cellspacing='0'>
					<?php echo $htmlFunctions->makeStudentDefaultSearch(); ?>
                    <tr height='5'></tr>
                    <tr height='5'>
                    <td colspan ='9' align="left">Choose the following search criteria for advanced search options<?php 
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                        echo HtmlFunctions::getInstance()->getHelpLink('Subject',HELP_ADVANCED_FILTER);
                    ?></td></tr>
                    <tr height='5'></tr>
					<?php echo $htmlFunctions->makeStudentAcademicSearch(false); ?>
					<tr height='5'></tr>
					<?php echo $htmlFunctions->makeStudentAddressSearch(); ?>
					<tr height='5'></tr>
					<?php echo $htmlFunctions->makeStudentMiscSearch(); ?>
					<tr>
						<td valign='top' colspan='11' class='' align='center'>
						<input type="hidden" name="degs" value="">
						<input type="hidden" name="degsText" value="">
						<input type="hidden" name="brans" value="">
						<input type="hidden" name="branText" value="">
						
						<input type="hidden" name="subjectId" value="">
						<input type="hidden" name="periods" value="">
						<input type="hidden" name="periodsText" value="">

						<input type="hidden" name="course" value="">
						<input type="hidden" name="courseText" value="">

						<input type="hidden" name="grps" value="">
						<input type="hidden" name="grpsText" value="">
						
						<input type="hidden" name="univs" value="">
						<input type="hidden" name="univsText" value="">

						<input type="hidden" name="citys" value="">
						<input type="hidden" name="citysText" value="">

						<input type="hidden" name="states" value="">
						<input type="hidden" name="statesText" value="">
						
						<input type="hidden" name="cnts" value="">
						<input type="hidden" name="cntsText" value="">

						<input type="hidden" name="hostels" value="">
						<input type="hidden" name="hostelsText" value="">

						<input type="hidden" name="buss" value="">
						<input type="hidden" name="bussText" value="">

						<input type="hidden" name="routs" value="">
						<input type="hidden" name="routsText" value="">
						<input type="hidden" name="quotaText" value="">
						<input type="hidden" name="bloodGroupText" value="">

						
							<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
						</td>
					</tr>
				</table>
				</form> 
			</td>
			</tr>
			<tr id='nameRow' style='display:<?php echo $showData?>'>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Student / Parent  Detail: </td>
						<td align="right" valign="middle">
                         <!--<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport()"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printStudentCSV()"/>-->
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr id='resultRow' style='display:<?php echo $showTitle?>'>
                <td class="contenttab_row1" valign="top" ><div id="results">  
                 </div>          
             </td>
          </tr>
		 
          </table>
        </td>
    </tr>
	 <tr id='nameRow2' style='display:<?php echo $showPrint?>'><td colspan='1' align='right' height="35" valign="bottom">
      <!--<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport()"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printStudentCSV()"/>-->
      </td>
      </tr>
    </table>
    </td>
    </tr>
    </table>
<?php 
// $History: listStudentContents.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/04/10    Time: 10:07
//Updated in $/Leap/Source/Templates/SuperLogin
//corrected text and images
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/10    Time: 19:10
//Created in $/Leap/Source/Templates/SuperLogin
//Created module "Super Login"
//
//*****************  Version 18  *****************
//User: Rajeev       Date: 3/30/09    Time: 6:59p
//Updated in $/Leap/Source/Templates/ScStudent
//Fixed bugs and added new theme
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 3/27/09    Time: 11:25a
//Updated in $/Leap/Source/Templates/ScStudent
//changed image source to input type
//
//*****************  Version 16  *****************
//User: Rajeev       Date: 2/24/09    Time: 6:15p
//Updated in $/Leap/Source/Templates/ScStudent
//Fixed issues and added address verification
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 1/15/09    Time: 5:57p
//Updated in $/Leap/Source/Templates/ScStudent
//use student, dashboard, sms, email icons
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 1/13/09    Time: 2:38p
//Updated in $/Leap/Source/Templates/ScStudent
//updated colspan to show submit button
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 11/18/08   Time: 11:19a
//Updated in $/Leap/Source/Templates/ScStudent
//added "Course wise" search in student filter
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 11/13/08   Time: 11:46a
//Updated in $/Leap/Source/Templates/ScStudent
//Updated Student tab with complete Ajax functionality
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 11/06/08   Time: 11:07a
//Updated in $/Leap/Source/Templates/ScStudent
//Updated with "Access" rights parameters
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 10/22/08   Time: 6:04p
//Updated in $/Leap/Source/Templates/ScStudent
//modified to show search criteria on print report
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 9/24/08    Time: 4:08p
//Updated in $/Leap/Source/Templates/ScStudent
//added CSV report
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 9/23/08    Time: 5:00p
//Updated in $/Leap/Source/Templates/ScStudent
//updated with new student filter
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 9/19/08    Time: 4:07p
//Updated in $/Leap/Source/Templates/ScStudent
//updated files according to subject centric
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 9/17/08    Time: 12:01p
//Updated in $/Leap/Source/Templates/ScStudent
//updated back button with class
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 9/17/08    Time: 11:00a
//Updated in $/Leap/Source/Templates/ScStudent
//updated formatting
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/16/08    Time: 4:55p
//Updated in $/Leap/Source/Templates/ScStudent
//updated files according to subject centric
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/06/08    Time: 2:29p
//Created in $/Leap/Source/Templates/ScStudent
//intial checkin
?>
 
    


