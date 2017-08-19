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
<?php
    $roleId=$sessionHandler->getSessionVariable('RoleId');
   
    require_once(BL_PATH.'/helpMessage.inc.php');
?>
 <form name="allDetailsForm" id="allDetailsForm" method="post" onSubmit="return false;">      
    <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
        </td>
    </tr>
    <tr>
        <td valign="top">
       
        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
		<tr>
		 <td valign="top" class="content">
		 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
		 <tr>
			<td valign="top" class="contenttab_row1" align="center">
				<input type="hidden" id="searchAllClassId" name="searchAllClassId" value="">
				<table border='0' width='10%' cellspacing='0px' cellpadding='0px'> 
					<tr height='5px'></tr>
					<tr>
					   <td class="contenttab_internal_rows" colspan="9" align="center">
                         <table border='0' width='100%' cellspacing='0px' cellpadding='0px'> 
						   <tr>
							 <td width="11%" class="contenttab_internal_rows"><nobr><b>Institute<?php echo REQUIRED_FIELD; ?></b></nobr></td>
							 <td width="1%" class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
							 <td width="2%" class="contenttab_internal_rows"><nobr>
								<select size="1" class="selectfield" name="fineInstituteId" id="fineInstituteId" style="width:226px" onchange="getClass();">
                                  <option value="">All</option>
                                </select>
							 </td>
							 <td width="11%" class="contenttab_internal_rows" style="padding-left:10px;">
								<nobr><b>Class<?php echo REQUIRED_FIELD; ?></b></nobr>
							 </td>
							 <td width="1%" class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
							 <td width="2%" class="contenttab_internal_rows"><nobr>
								<select size="1" class="selectfield" name="fineClassId" id="fineClassId" style="width:380px">
                                  <option value="">All</option>
                                </select>
								<select size="1" class="selectfield" name="hiddenFineClassId" id="hiddenFineClassId" style="width:320px;display:none" >
                                  <option value="">All</option>
                                </select>
							 </td>
							  <td width="11%" class="contenttab_internal_rows" style="padding-left:20px;">
								<input type="image" name="fineSS" value="fineSS" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
							 </td>
						   </tr>
						 </table>
					   </td>
					</tr>
                    <tr height='10px'></tr>
                    <tr id='fineRow' style='display:<?php echo $showData?>'>
                       <td colspan ='9' align="left">
                         <table border='0' width='40%' cellspacing='2px' cellpadding='2px' align='center' class='content_table_border'> 
                           <tr>
                             <td valign="top" class="">
                                <table border='0' width='40%' cellspacing='0px' cellpadding='0px' > 
                                        <tr><td height='5' colspan='20'></td></tr>
                                        <tr>
                                            <td width="11%" class="contenttab_internal_rows"><nobr><b>Fine Category<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                                            <td width="1%" class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                            <td width="2%" class="contenttab_internal_rows"><nobr>
                                                 <select size="1" class="selectfield" name="fineCategoryId" id="fineCategoryId" style="width:226px">
                                                 <option value="">Select</option>
                                                      <?php
                                                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                          if($roleId==1) {
                                                             echo HtmlFunctions::getInstance()->getFineCategory();
                                                          }
                                                          else {
                                                             echo HtmlFunctions::getInstance()->getRoleFineCategory();    
                                                          }
                                                      ?>
                                                </select></nobr>
                                            </td>
                                            <td width="1%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Date</b></nobr></td>
                                            <td width="1%" class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                            <td width="2%" class="contenttab_internal_rows"><nobr>
                                                  <?php
                                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                      echo HtmlFunctions::getInstance()->datePicker('fineDate1',date('Y-m-d'));
                                                  ?></nobr>
                                          </td>
                                          <td width="2%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Amount<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                                          <td width="1%" class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                          <td width="2%" class="contenttab_internal_rows"><nobr>
                                             <input type="text" id="fineAmount" name="fineAmount" class="inputbox1" maxlength="8" size="9"/>
                                             </nobr>
                                          </td>
                                          <!--<td width="2%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Add to "No Dues"&nbsp;</b></nobr></td>
                                          <td width="1%" class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                          <td width="2%" class="contenttab_internal_rows" align=""><nobr>
                                                <select size="1" class="selectfield" name="dueStatus" id="dueStatus" style="width:70px">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                 </select></nobr>
                                          </td>-->
                                       </tr> 
                                       <tr height='2'>
                                       <tr>
                                            <td width="11%" class="contenttab_internal_rows"><nobr><b>Reason<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                                            <td width="1%" class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                            <td width="2%" class="contenttab_internal_rows" colspan="9">
                                                <nobr>
                                                    <textarea name="remarksTxt" id="remarksTxt" cols="10" rows="2" maxlength="400" onkeyup="return ismaxlength(this)" style="width:600px;" class="inputbox1"></textarea>
                                                </nobr>
                                            </td>
                                            <td width="2%" class="contenttab_internal_rows">
                                               <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm1();return false;" />
                                            </td>
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
			<tr id='nameRow' style='display:none'>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Student Details : </td>
						<td align="right" valign="middle">
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr id='resultRow' style='display:none'>
                <td class="contenttab_row1" valign="top" >
                <div id="results">  
                 </div>          
             </td>
          </tr>
          </table>
        </td>
    </tr>
	 <tr id='nameRow2' style='display:none'>
       <td colspan='1' align='right' height="35" valign="bottom">
         
       </td>
     </tr>
    </table>
    </td>
    </tr>
    </table>
</form>
    
<!--Daily Attendance Help  Details  Div-->
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>    
            <td width="89%">
                <div id="helpInfo" style="vertical-align:top;" ></div> 
            </td>
        </tr>
    </table>
</div>       
<?php floatingDiv_End(); ?> 
<!--Daily Attendance Help  Details  End -->    


<!--Fine Already Exist Details  Div-->
<?php floatingDiv_Start('divNotFineAlreadyInfo','Fine already entered for mentioned students','',''); ?>
<div id="fineNotResultDiv" style="overflow:auto; vertical-align:top;"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>    
            <td width="89%">
                 <div id="scroll22" style="overflow:auto; width:920px; height:400px; vertical-align:top;">
                    <div id="fineResultDiv" style="width:98%; vertical-align:top;"></div>
                 </div>
            </td>
        </tr>
    </table>
 </div>   
<?php floatingDiv_End(); ?> 
<!--Fine Already Exist Details  Div-->

<?php 
// $History: listBulkFineStudentContents.php $
//
//*****************  Version 10  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Student
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 12/05/09   Time: 3:45p
//Updated in $/LeapCC/Templates/Student
//set the alignment of text
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 6/15/09    Time: 7:22p
//Updated in $/LeapCC/Templates/Student
//Enhanced "Admin Student" module as mailed by Puspender Sir.
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 6/02/09    Time: 11:39a
//Updated in $/LeapCC/Templates/Student
//Fixed bugs  1104-1110  and enhanced with student previous academics
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Templates/Student
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Templates/Student
//Updated with Required field, centralized message, left align
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/10/08   Time: 5:50p
//Updated in $/LeapCC/Templates/Student
//updated functionality as per CC
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 10:19a
//Updated in $/LeapCC/Templates/Student
//modified as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 16  *****************
//User: Rajeev       Date: 9/17/08    Time: 12:01p
//Updated in $/Leap/Source/Templates/Student
//updated back button with class
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 9/17/08    Time: 11:00a
//Updated in $/Leap/Source/Templates/Student
//updated formatting
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 9/03/08    Time: 3:10p
//Updated in $/Leap/Source/Templates/Student
//updated formatting and spacing
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 9/01/08    Time: 4:02p
//Updated in $/Leap/Source/Templates/Student
//updated with default display of student attendance, student print
//report
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 8/22/08    Time: 5:48p
//Updated in $/Leap/Source/Templates/Student
//updated print reports
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 8/21/08    Time: 2:03p
//Updated in $/Leap/Source/Templates/Student
//updated formatting and print reports
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 8/14/08    Time: 3:40p
//Updated in $/Leap/Source/Templates/Student
//added print report function
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 8/13/08    Time: 12:38p
//Updated in $/Leap/Source/Templates/Student
//updated the formatting of student list
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/12/08    Time: 12:40p
//Updated in $/Leap/Source/Templates/Student
//updated server side query
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/11/08    Time: 10:59a
//Updated in $/Leap/Source/Templates/Student
//updated the formatting and other issues
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 8/06/08    Time: 6:15p
//Updated in $/Leap/Source/Templates/Student
//updated javascript error
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/05/08    Time: 6:29p
//Updated in $/Leap/Source/Templates/Student
//remove all the demo issues
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/05/08    Time: 12:33p
//Updated in $/Leap/Source/Templates/Student
//updated the label
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/12/08    Time: 5:24p
//Updated in $/Leap/Source/Templates/Student
//updated ajax based
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/11/08    Time: 6:46p
//Updated in $/Leap/Source/Templates/Student
//updated student photo module
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/08/08    Time: 11:19a
//Created in $/Leap/Source/Templates/Student
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/08    Time: 12:55p
//Created in $/Leap/Source/Templates/SubjectToClass
//intial checkin
?>
 
    


