<?php
//-------------------------------------------------------
// Purpose: to design the layout for Fee Collection.
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
			<form action="" method="POST" name="allDetailsForm" id="allDetailsForm">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
			 <tr>
				<td class="contenttab_border2">
                    <table width="98%" align="center" border="0" cellspacing="2" cellpadding="0" >
					<tr>
						<td class="contenttab_internal_rows"  valign="top"><nobr><b>University</b></nobr></td>
						<td class="contenttab_internal_rows" valign="top"><B>:</B></td>
						<td class="contenttab_internal_rows1" >
                            <select size="3" name="feeUniversity" id="feeUniversity" class="inputbox" multiple style="width:245px">
						<?php
						  require_once(BL_PATH.'/HtmlFunctions.inc.php');
						  echo HtmlFunctions::getInstance()->getUniversityAbbr();
						?>
						</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection("feeUniversity","All");'>All</a> / <a class='allReportLink' href='javascript:makeSelection("feeUniversity","None");'>None</a></td>
						<td class="contenttab_internal_rows" valign="top"><nobr><b>Institute</b></nobr></td>
						<td class="contenttab_internal_rows" valign="top"><B>:</B></td>
						<td class="contenttab_internal_rows1"><select size="3" style="width:245px" class="inputbox" name="feeInstitute" id="feeInstitute" multiple>
						<?php
						  require_once(BL_PATH.'/HtmlFunctions.inc.php');
						  echo HtmlFunctions::getInstance()->getInstituteData($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);
						?>
						</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection("feeInstitute","All");'>All</a> / <a class='allReportLink' href='javascript:makeSelection("feeInstitute","None");'>None</a></td>
						<td class="contenttab_internal_rows" valign="top"><nobr><b>Degree</b></nobr></td>
						<td class="contenttab_internal_rows" valign="top"><B>:</B></td>
						<td class="contenttab_internal_rows1" width="25%"><select size="3" style="width:245px" class="inputbox" name="feeDegree" id="feeDegree" multiple>
						<?php
						  require_once(BL_PATH.'/HtmlFunctions.inc.php');
						  echo HtmlFunctions::getInstance()->getDegreeAbbr($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);
						?>
						</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection("feeDegree","All");'>All</a> / <a class='allReportLink' href='javascript:makeSelection("feeDegree","None");'>None</a></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows" valign="top"><nobr><b>Branch</b></nobr></td>
						<td class="contenttab_internal_rows" valign="top"><B>:</B></td>
						<td class="contenttab_internal_rows1"> <select size="3" name="feeBranch" style="width:245px" id="feeBranch" class="inputbox" multiple>
						<?php
						  require_once(BL_PATH.'/HtmlFunctions.inc.php');
						  echo HtmlFunctions::getInstance()->getBranchData();
						?>
						</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection("feeBranch","All");'>All</a> / <a class='allReportLink' href='javascript:makeSelection("feeBranch","None");'>None</a></td>				 
						<td class="contenttab_internal_rows" valign="top"><nobr><b>Semesters</b></nobr></td>
						<td class="contenttab_internal_rows"  valign="top"><B>:</B></td>
						<td class="contenttab_internal_rows1"><select size="3" class="inputbox" style="width:245px" name="studyperiod" id="studyperiod" multiple>
						<?php
						  require_once(BL_PATH.'/HtmlFunctions.inc.php');
						  echo HtmlFunctions::getInstance()->getStudyPeriodData($REQUEST_DATA['periodName']==''? $REQUEST_DATA['studyperiod'] : $REQUEST_DATA['periodName'] );
						?>
						</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection("studyperiod","All");'>All</a> / <a class='allReportLink' href='javascript:makeSelection("studyperiod","None");'>None</a></td>
						<td class="contenttab_internal_rows" valign="top"><nobr><b>Fee Cycle</b></nobr></td>
						<td class="contenttab_internal_rows" valign="top"><B>:</B></td>
						<td class="contenttab_internal_rows1"> <select size="3" name="feeCycle" style="width:245px" id="feeCycle"  class="inputbox" multiple>
						<?php
							  require_once(BL_PATH.'/HtmlFunctions.inc.php');
							  echo HtmlFunctions::getInstance()->getFeeCycleData();
						?>
						</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection("feeCycle","All");'>All</a> / <a class='allReportLink' href='javascript:makeSelection("feeCycle","None");'>None</a></td> 
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><B>From Date</B> </td>
						<td class="contenttab_internal_rows" valign="top"><B>:</B></td>
						<td class="contenttab_internal_rows" style="text-align:left"><?php
							   require_once(BL_PATH.'/HtmlFunctions.inc.php');
							   echo HtmlFunctions::getInstance()->datePicker('fromDate','');
						?><B>To Date:</B><?php
							   require_once(BL_PATH.'/HtmlFunctions.inc.php');
							   echo HtmlFunctions::getInstance()->datePicker('toDate','');
						?></td>
						<td  align="left"  colspan="4">
						<input type="hidden" name="listStudent" value="1"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif"  onClick="return getData();return false"/>
						</td>
					</tr>
					</table>
				</td>
			</tr>
            <tr id="showTitle"  style="display:<?php echo $showTitle?>">
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Fee Collection: </td>
						<td colspan='1' align='right'><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printFeeCollectionCSV();return false;"/></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr id="showData"  style="display:<?php echo $showData?>">
                <td class="contenttab_row" valign="top" ><div id="results">  
                 </div>          
             </td>
          </tr>
		  <tr>
			<td height="10"></td>
		  </tr>
         <tr><td colspan='1' align='right'><div id='saveDiv' style="display:<?php echo $showPrint?>"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printFeeCollectionCSV();return false;"/></div></td> </tr>
			
          </table>
		 <input type="hidden" name="univId" value="">
		 <input type="hidden" name="instsId" value="">
		 <input type="hidden" name="degsId" value="">
		 <input type="hidden" name="bransId" value="">
		 <input type="hidden" name="semsId" value="">
		 <input type="hidden" name="feesId" value="">
        </td>
    </tr>
    
    </table>
	</form>
    </td>
    </tr>
    </table>
<?php 
// $History: feeCollectionContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/04/09    Time: 12:38p
//Updated in $/LeapCC/Templates/Student
//Gurkeerat: updated breadcrumb
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 09-09-03   Time: 12:40p
//Updated in $/LeapCC/Templates/Student
//fixed 0001421,0001422,0001428,0001430,0001434,0001435
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Templates/Student
//Updated with Required field, centralized message, left align
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/23/08   Time: 12:57p
//Updated in $/LeapCC/Templates/Student
//updated as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/16/08    Time: 11:29a
//Created in $/Leap/Source/Templates/Student
//intial checkin 
?>