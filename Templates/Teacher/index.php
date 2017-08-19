<?php
// This file creates DashBoard for Teacher Module
// Author :Dipanjan Bhattacharjee
// Created on : 12.07.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
	require_once(BL_PATH.'/helpMessage.inc.php');
	/*
	echo '<pre>';
	print_r($_SESSION);
	echo '</pre>';
	*/
?>
<style>
.myUL {
	/*margin-left:0px;
	margin-left:0px !important;
	*/
	margin-top:0px !important;
	margin-top:5px;
	padding-left:14px !important;
	margin-left:0px;
	margin-bottom:0px;

}
</style>
<script>
		function testFn() {

		    guiders.hideAll();
		    addNewBread("menuLookup");
		}			


		function addNewBread(moduleName) {
		   url = '<?php echo HTTP_LIB_PATH;?>/ajaxGuiders.php';

		   new Ajax.Request(url,
		   {
		     method:'post',
		     parameters: { moduleName: moduleName
				   
				 },
		     onCreate: function(){
			 showWaitDialog(true);
		     },
		     onSuccess: function(transport){
		       hideWaitDialog(true);
		      
		     },
		     onFailure: function(){ }
		     });
		}
</script>

<table border='0' width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td align="right">
                
                <!-- Added for autosuggest -->
                <input type="text" name="menuLookup" class="fadeMenuText" style="width:200px" id="menuLookup" 
                onkeyup="getMenuLookup();" onclick="changeDefaultTextOnClick();" onblur="changeDefaultTextOnBlur();" 
                value="Menu Lookup.." autocomplete="off"/>&nbsp;
			<?php
			   $status=0;
			   require_once(MODEL_PATH . "/GuidersManager.inc.php");
			   $returnStatus=GuidersManager::getInstance()->checkGuidersEntry("menuLookup");
			   if(count($returnStatus)>0) {
			     $status=1;
			   }
			if($status==0){
			?>
			<script type="text/javascript">
				      guiders.createGuider({
				      attachTo: "#menuLookup",
				      buttons: [{name: "Close", onclick:testFn}],
				      description: "Menu lookup helps you find menu options easily and quickly. Just enter the keyword that matches your menu option and menu \
							lookup automatially guides you..",
				      id: "fourth",
				      next: "fifth",
				      position: 5,
				      title: "Find Menu Options Quickly!",
				      width: 400
				    }).show();
				</script>
			<?php }?>
					<div id="menuLookupContainer" style="position:absolute;z-index:100;padding:0px 0px 0px 0px; text-align:left; 
                display:none; border:1px solid #7F9DB9; margin-right:0px;"></div>
                <!-- Auto suggest ends -->
                
                </td>
                
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
           <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <?php
                    if(isset($REQUEST_DATA['z'])) {
                    ?>
                    <table width='100%' class='accessDenied'>
                        <tr>
                            <td><?php echo ACCESS_DENIED;?></td>
                        </tr>
                    </table><br>
             <?php } ?>
             <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" height="400">
             <tr>
                <td class="contenttab_border" height="20">
                     <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                        <td valign="middle" width="50%" class="content_title">Dashboard : &nbsp;
                        <a href="javascript:void(0);" onclick="showAvailableWidgets();" title="Available Widgets">
                        <!--<img src="<?php //echo IMG_HTTP_PATH;?>/blink.gif"  onclick="showAvailableWidgets();" />-->
                        <font color="#FFFFFF">Available Widgets</a>
                        &nbsp;&nbsp;
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          //echo HtmlFunctions::getInstance()->getHelpLink('','','','',1);
                        ?>
                        </td>
                        <td valign="middle" align="right" class="content_title">
                            <?php
                              $condition='';
                              $condition = " WHERE userId='".$sessionHandler->getSessionVariable('UserId')."'";
                              $totalRecord = CommonQueryManager::getInstance()->getUserLastLogin($condition);
                              if($totalRecord[0]['dateTimeIn']!='') {
                        echo "<span  class='redLink'>&nbsp; Last Login:&nbsp;".UtilityManager::formatDate($totalRecord[0]['dateTimeIn'],true)." </span>";
                              }
                            ?>
                        &nbsp;&nbsp;
                        </td>
                        </tr>
                    </table>
                </td>
             </tr>
             <tr>
              <td class="contenttab_row" valign="top"  >

            <!--<div id="div_Outer">-->
              <div id="columns">
                <ul id="column1" class="column">
                    <?php clumns(1);?>
                </ul>

                <ul id="column2" class="column">
                 <?php clumns(2);?>
                </ul>

               <ul id="column3" class="column">
                <?php clumns(3);?>
                </ul>  
				
             </div>
            <!--</div>-->
          </td>
        </tr>
        </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>

<!--Start Attendance  Div-->
<?php floatingDiv_Start('divAttendance','Attendance '); ?>
<form name="AttendanceForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Subject : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="subject" name="subject" class="inputbox" style="border:0px" readonly="true" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Class: </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="tclass" name="tclass" class="inputbox" style="border:0px" readonly="true" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Date : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="date" name="date" class="inputbox" style="border:0px" readonly="true" /></td>
</tr>
<tr>
    <td height="5px"></td></tr>
<!--<tr>
    <td align="center" style="padding-right:10px" colspan="2">
       <input type="image" name="editclose_icon" src="<?php echo IMG_HTTP_PATH;?>/close_icon.gif"  onclick="javascript:hiddenFloatingDiv('divAttendance');return false;" />
        </td>
</tr>-->
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>


<!--Start Notice  Div-->
<?php floatingDiv_Start('divNotice','Notice Description','',' '); ?>
<form name="NoticeForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Subject: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2" height='20'>
     <div id="noticeSubject" style="overflow:auto; width:630px; height:20px"></div>
</td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Department: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="noticeDepartment" style="width:600px; height:20px"></div></td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Date: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td valign="middle" colspan="2" height='20'><B>&nbsp;From</B>: <span id="visibleFromDateNotice" style="height:20px"></span>&nbsp;&nbsp;<B>To</B>: <span id="visibleToDateNotice" style="height:20px"></span></td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Description: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="noticeText" style="overflow:auto; width:630px; height:200px" ></div></td>
</tr>

<tr>
<td height="5px"></td>
</tr>
</table>
</form>  
<?php floatingDiv_End(); ?>


<!--Start Event  Div-->
<?php floatingDiv_Start('divEvent','Event Description','',' '); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <form name="EventForm" action="" method="post">
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Event: </b></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px">
    <div id="innerNotice" style="overflow:auto; width:580px; height:20px"></div>
    </td>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Date: </b></td>
</tr>

<tr>
    <td valign="middle" colspan="2" style="padding-left:3px"><B>From</B>: <span id="visibleFromDate" style="height:20px"></span>&nbsp;&nbsp;<B>To</B>: <span id="visibleToDate"></span></td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Short Description: </b></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px">
    <div id="innerDescription" style="overflow:auto; width:580px; height:50px" ></div>
    </td>
</tr>

<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Long Description: </b></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px">
    <div id="longDescription" style="overflow:auto; width:580px; height:150px" ></div>
    </td>
</tr>

<tr>
    <td height="5px"></td>
</tr>

   </form>
</table>

<?php floatingDiv_End(); ?>


<!--Start Message  Div-->
<?php floatingDiv_Start('divMessage','Message ','',' '); ?>
<form name="MessageForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
  <tr>
    <td height="5px"></td></tr>
<tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Subject: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2" height='20'>
     <div id="sub" style="overflow:auto; width:630px; height:20px"></div>
</td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Message: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="message" style="overflow:auto; width:630px; height:100px" ></div></td>
</tr> 
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Dated: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td valign="middle" colspan="2" height='20'><span id="dated" style="height:20px"></span></span></td>
</tr> 
<tr><td height="5px"></td></tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2" height='20'>
     <div id="downloadDiv" style="overflow:auto; width:630px;"></div>
</td>
</tr>

</table>
</form> <?php floatingDiv_End(); ?>
<!--<form name="MessageForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Subject : </b></nobr></td>
        <td width="79%" class="padding">
         <textarea id="subject" name="subject" cols="20" rows="1" readonly="true"></textarea>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows" style="padding-left:5px"><nobr><b>Message : </b></nobr></td>
        <td width="79%" class="padding">
         <!--<textarea id="message" name="message" cols="20" rows="3" readonly="true"></textarea>-->
 <!--        <div id="message" name="message" style="border:1px solid black;height:100px;width:200px;overflow:auto"></div>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Dated : </b></nobr></td>
        <td width="79%" class="padding">
         <input type="text" id="dated" name="dated" class="inputbox" style="border:0px" readonly="true" />
        </td>
    </tr>

<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
       <input type="image" name="editclose_icon" src="<?php echo IMG_HTTP_PATH;?>/close_icon.gif"  onclick="javascript:hiddenFloatingDiv('divMessage');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form> -->


<?php

floatingDiv_Start('examStatisticsDiv','This Dialog shows the details of test taken by '. $sessionHandler->getSessionVariable('EmployeeName'));
?>
	<div id='examStatisticsTableOuter' style='height:455px;width:900px;overflow:auto;vertical-align:top;'>
	<div id='examStatisticsTable' style='height:400px;width:895px !important;width:99%;overflow:auto;'></div><br/>
	<div style="border:1px "> <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20" >
                                            <tr>
                                               <td colspan="1" align="right">
                                                    <input type="image" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="examStatisticsPrintReport()" />&nbsp;
                                                    <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="examStatisticsPrintReportCSV()"/>&nbsp;
                                                </td>
                                            </tr>
                                        </table></div>
	</div>
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('messageSendingDiv','Send Message',1); ?>
	<form name='smsSendingForm' method='post' action=''>

	<table border='0' cellspacing='2' cellpadding='0' width='900'>
		<tr>
			<td valign='top' colspan='2' class=''>
				<div id='classSubjectDiv' style='vertical-align:top;'></div>
			</td>
		</tr>
		<tr>
			<td valign='top' colspan='1' class='' width='50%'>
				<div style='height:400px;width:550px;overflow:auto;vertical-align:top;'>
				<div id='messageSendingTable' style='width:96%'></div>
				</div>
			</td>
			<td valign='top' colspan='1' class='' width='50%'>
				<table border='0' cellspacing='0' cellpadding='0'>
					<tr>
					  <td valign="top" width="100%" style="padding-left:5px;">
						  <span><b>Message Medium :</b></span>
						   <input type="checkbox" id="dashBoardCheck" name="dashBoardCheck" value="1" onclick="dateDivShow();">DashBoard &nbsp;
						   <input type="checkbox" id="emailCheck" name="emailCheck" value="1" onclick="subjectDivShow();">E-Mail &nbsp;
						   <?php
							if($sessionHandler->getSessionVariable('TEACHER_SMS_STUDENTS')==1){
						   ?>
							<input type="checkbox" id="smsCheck" name="smsCheck" value="1" onclick="smsDivShow();">SMS &nbsp;
						   <?php
							}
						   else{
						   ?>
						   <input type="checkbox" id="smsCheck" name="smsCheck" value="1" onclick="smsDivShow();" style="display:none">
						   <?php
						   }
						   ?>
					 </td>
					</tr>
					<tr>
						<td valign='top' colspan='1' class=''>
						  <div id="dateDiv" style="display:none">
							 <table border="0" cellpadding="0" cellspacing="0">
							 <?php $thisDate=date('Y')."-".date('m')."-".date('d'); ?>
							 <tr>
							 <td valign="top" style="padding-left:5px;"><b>Visible From :</b>
							  <?php
							   require_once(BL_PATH.'/HtmlFunctions.inc.php');
							   echo HtmlFunctions::getInstance()->datePicker('startDate',$thisDate);
							  ?>
							</td>
							 <td valign="top" style="padding-left:5px"><b>Visible To :</b>
							  <?php
							   require_once(BL_PATH.'/HtmlFunctions.inc.php');
							   echo HtmlFunctions::getInstance()->datePicker('endDate',$thisDate);
							  ?>
							</td>
							</tr>
							</table>
						  </div>
						</td>
					</tr>
					<tr>
						<td valign='top' colspan='1' class='' style="padding-left:5px;padding-top:5px;">
							<b>Subject : </b><input type="text" name="msgSubject" id="msgSubject" class="inputbox" style="width:325px" maxlength="100">
						</td>
					</tr>
					<tr>
						<td valign='top' colspan='1' class='' style="padding-left:5px;padding-top:5px;">
							<textarea id="elm1" name="elm1" rows="10" cols="40" style="width: 100%" ></textarea>
						</td>
					</tr>
					<tr>
						<td valign='top' colspan='1' class='' align='center' style="padding-top:5px;">
		                  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/send.gif" onClick="return validateForm();return false;" /><input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="hiddenFloatingDiv('messageSendingDiv');return false;" />

						</td>
					</tr>
				</table>

			</td>
		</tr>
	</table>
   <div id="smsDiv" class="field3_heading"  style="width:50%;display:none" >
	 SMS Length :<input type="text" id="sms_char" name="sms_char" class="small_txt" value="0" disabled="true" />
	 &nbsp;&nbsp;&nbsp;SMS(s) :     <input type="text" id="sms_no" name="sms_no" class="small_txt" value="1" disabled="true" />
	 </div>
</form>

<?php floatingDiv_End(); ?>

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
			<input type="hidden" name="employeeId1" id="employeeId1" value="<?php echo $sessionHandler->getSessionVariable('EmployeeId')?>">
                    <input type="hidden" name="employeeName1" id="employeeName1" value="<?php echo $sessionHandler->getSessionVariable('EmployeeName')?>">
                    <input type="hidden" name="employeeCode1" id="employeeCode1" value="<?php echo $sessionHandler->getSessionVariable('EmployeeCode')?>">
        </tr>
    </table>
</div>
<?php floatingDiv_End(); ?>




<!--Start Widgets  Div-->
<?php floatingDiv_Start('divWidgets','Available Widgets '); ?>
<form name="widgetsForm" action="" method="post" onsubmit="return false;">
<table  border="0" cellspacing="1" cellpadding="0" class="border" width="300px;">
<tr><td colspan="3" height="5px"></td></tr>
    <tr class="rowheading">
     <td class="searchhead_text">#</td>
     <td class="searchhead_text">Widget</td>
     <td class="searchhead_text">Show</td>
     <td class="searchhead_text">Description</td>
     <!--<td class="searchhead_text">Remove</td>-->
    </tr>
    <?php
      echo $widgetOptionsString;
    ?>
<tr><td colspan="3" height="5px"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Widgets  Div-->




<?php
// $History: index.php $
//
//*****************  Version 25  *****************
//User: Ajinder      Date: 2/12/10    Time: 12:25p
//Updated in $/LeapCC/Templates/Teacher
//done changes FCNS No. 1280
//
//*****************  Version 24  *****************
//User: Parveen      Date: 1/29/10    Time: 2:54p
//Updated in $/LeapCC/Templates/Teacher
//integer date check added (notices tdays)
//
//*****************  Version 23  *****************
//User: Parveen      Date: 1/29/10    Time: 11:46a
//Updated in $/LeapCC/Templates/Teacher
//integer field check add (days)
//
//*****************  Version 22  *****************
//User: Parveen      Date: 1/28/10    Time: 12:41p
//Updated in $/LeapCC/Templates/Teacher
//new flash image code updated
//
//*****************  Version 21  *****************
//User: Ajinder      Date: 1/12/10    Time: 4:12p
//Updated in $/LeapCC/Templates/Teacher
//added code to apply check if current teacher is taking any course or
//not.
//
//*****************  Version 20  *****************
//User: Ajinder      Date: 1/09/10    Time: 12:03p
//Updated in $/LeapCC/Templates/Teacher
//added help, changed table names to defines.
//
//*****************  Version 19  *****************
//User: Ajinder      Date: 1/08/10    Time: 3:06p
//Updated in $/LeapCC/Templates/Teacher
//file modified to make changes on Teacher Dashboard
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 22/12/09   Time: 10:25
//Updated in $/LeapCC/Templates/Teacher
//Corrected <a> tags style in "Attendance" Div for "Attendance Not Taken"
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 24/11/09   Time: 18:20
//Updated in $/LeapCC/Templates/Teacher
//Removed "underline" and "italics" from notice display when notics has
//attachments.
//
//*****************  Version 16  *****************
//User: Rahul.nagpal Date: 11/11/09   Time: 12:52p
//Updated in $/LeapCC/Templates/Teacher
//
//*****************  Version 15  *****************
//User: Rahul.nagpal Date: 11/11/09   Time: 12:14p
//Updated in $/LeapCC/Templates/Teacher
//teacher dashboard improvements
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 29/10/09   Time: 12:40
//Updated in $/LeapCC/Templates/Teacher
//Corrected "notice attachment" not visible issue
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 3/09/09    Time: 11:37
//Updated in $/LeapCC/Templates/Teacher
//Done bug fixing.
//Bug ids---
//00001407,00001408,00001409,
//00001419,00001420
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 1/09/09    Time: 11:21
//Updated in $/LeapCC/Templates/Teacher
//Done bug fixing.
//Bug ids---
//00001351,00001353,00001354,00001355,
//00001369,00001370,00001371
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/27/09    Time: 11:54a
//Updated in $/LeapCC/Templates/Teacher
//Gurkeerat: resolved issue regardind issue nos. 1226,1227
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 25/08/09   Time: 17:29
//Updated in $/LeapCC/Templates/Teacher
//Corrected msg display in teacher dashboard
//and discipline module
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/21/09    Time: 1:03p
//Updated in $/LeapCC/Templates/Teacher
//Gurkeerat: solved alignment issue
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/20/09    Time: 1:33p
//Updated in $/LeapCC/Templates/Teacher
//Gurkeerat: fixed same issue as in issue 1083
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 19/08/09   Time: 18:00
//Updated in $/LeapCC/Templates/Teacher
//Corrected  "Attendance Last Taken" divs design
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 12/08/09   Time: 12:33
//Updated in $/LeapCC/Templates/Teacher
//Modified teacher dashboard's design.Make "Notice" box longer to
//accomodate more notices.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:36
//Updated in $/LeapCC/Templates/Teacher
//Done bug fixing.
//bug ids---
//0000971 to 0000976,0000979
//
//*****************  Version 4  *****************
//User: Administrator Date: 13/06/09   Time: 11:19
//Updated in $/LeapCC/Templates/Teacher
//Made bulk attendance,duty leaves and grace marks in teacher end
//configurable
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/28/09    Time: 5:48p
//Updated in $/LeapCC/Templates/Teacher
//Display Last Login in dashBoard
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/08/08   Time: 4:41p
//Updated in $/LeapCC/Templates/Teacher
//Added "SC" enhancements
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher
//
//*****************  Version 24  *****************
//User: Dipanjan     Date: 11/07/08   Time: 12:42p
//Updated in $/Leap/Source/Templates/Teacher
//Corrected bubble tool tips error
//
//*****************  Version 23  *****************
//User: Dipanjan     Date: 10/21/08   Time: 12:05p
//Updated in $/Leap/Source/Templates/Teacher
//Added alert for time table changes in dashboard
//
//*****************  Version 22  *****************
//User: Dipanjan     Date: 9/30/08    Time: 12:26p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 21  *****************
//User: Dipanjan     Date: 9/29/08    Time: 5:48p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 20  *****************
//User: Dipanjan     Date: 9/25/08    Time: 6:40p
//Updated in $/Leap/Source/Templates/Teacher
//Corrected date format problem
//
//*****************  Version 19  *****************
//User: Dipanjan     Date: 9/24/08    Time: 1:36p
//Updated in $/Leap/Source/Templates/Teacher
//Corrected date range in event showing criteria
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 9/20/08    Time: 3:56p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 9/17/08    Time: 4:14p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 9/16/08    Time: 5:09p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 9/16/08    Time: 12:58p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 9/09/08    Time: 4:53p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Teacher
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/27/08    Time: 3:31p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/18/08    Time: 5:52p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/18/08    Time: 4:14p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/18/08    Time: 3:11p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/16/08    Time: 4:10p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/09/08    Time: 6:07p
//Updated in $/Leap/Source/Templates/Teacher
//Removed search option
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/06/08    Time: 6:53p
//Updated in $/Leap/Source/Templates/Teacher
//Done modifications as discussed in the demo session
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/31/08    Time: 7:27p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/30/08    Time: 3:36p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/22/08    Time: 10:47a
//Created in $/Leap/Source/Templates/Teacher
?>

