<?php 
//this file contains the template of attendace
//
// Author :Jaineesh
// Created on : 22.07.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
          <tr>
                <?php   require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
        
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="contenttab_border2" valign="top" >
                <table width="100%" border="0" cellspacing="1" cellpadding="1">
                
                        <tr class="row0">
                            <td valign="top">
                                    <form name="attendance" id="attendance">
                                    <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                        <tr>
                                        <td>
                                           <table border="0" cellspacing="1" cellpadding="0" align="center">
                            <tr>
                                <td valign="middle" align="left" class="contenttab_internal_rows" style="padding-right:5px;">
                                 <div id="consolidatedDiv" title="Consolidated View" style="text-decoration:underline;cursor:pointer;" onclick="toggleAttendanceDataFormat();">
                                  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/consolidated.gif" />
                                  </div>
                                </td>
                                <td valign="middle" class="contenttab_internal_rows1"><b>Show Attendance Upto </b></td>
                                <td class="contenttab_internal_rows" valign="middle"><b>:</b></td>
                                <td>    
                                <?php
                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                   echo HtmlFunctions::getInstance()->datePicker('startDate2',date('Y-m-d'));
                                ?></td>
                                <td width="5"></td>
                                <td><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/showlist.gif" onClick="getAttendance();return false;"/></td>
                            </tr>
                            </table>
                            </td>
                                        </tr>
                                    </table>
                                    </form>
                            <td>
                        </tr>
                        <tr>
		                <td class="contenttab_internal_rows1">
		                	<font color="red"><b><u>Please Note:</u>&nbsp;</b></font><br>
							<font color="red">1. Medical Leaves are ONLY applicable in the Consolidated View.</font><br/>
							<font color="red">2. Medical Leaves are counted in the Aggregate ONLY if (Total Attendance + Duty Leaves) lie between <?php echo $sessionHandler->getSessionVariable('MEDICAL_LEAVE_CALCULATION_LIMIT'); ?>% and <?php echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');?>% </font>
		                </td>
                		</tr>
                        <tr>
                            <td valign="top"><div style="overflow:auto;HEIGHT:510px" id="results">
                            
                            </div>
                    </td></tr></table>          
             </td>
          </tr>
            <tr>
                <td class="content_title" title="Print" align="right" style="padding-right:20px"><input type="image" name="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" />&nbsp;<input type="image" name="printStudentAttendanceSubmit" id='generateCSV' onClick='printCSV();return false' src="<?php echo IMG_HTTP_PATH;?>/excel.gif" value="printStudentAttendanceSubmit" /></td>
            </tr>
          </table>
            
        </td>
    </tr>
    
    </table>
    
    <?php floatingDiv_Start('divDutyLeave','Duty Leaves','',''); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scrolLeave1" style="overflow:auto; width:550px; height:300px; vertical-align:top;">
                <div id="div_dutyLeave" style="width:98%; vertical-align:top;"></div>
            </div>
        </td>
    </tr>
</table>
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('divMedicalLeave','Medical Leaves','',''); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scrolLeave211" style="overflow:auto; width:550px; height:300px; vertical-align:top;">
                <div id="div_medicalLeave" style="width:98%; vertical-align:top;"></div>
            </div>
        </td>
    </tr>
</table>
<?php floatingDiv_End(); ?>
	
	
   <!--</form>-->
