<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING 
//
//
// Author :Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
require_once(BL_PATH.'/helpMessage.inc.php'); 
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();

?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
         <?php   require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
                        <td class="content_title">Send Message : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td valign="top" class="contenttab_row" style="border-bottom:none1" >
              <table cellpadding="0" cellspacing="0" border="0" width="100%" >
              <tr>
              <td colspan="2" style="padding:5px" valign="top" >
               <!--Add Student Filter-->
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                            <tr>
                                <td valign="top" align="center">
                                    <form name="allDetailsForm" action="" method="post" onSubmit="return false;">
                                        <table border='0' width='100%' cellspacing='0'>
                                            <tr>
                                             <td colspan="10" valign="top" align="left">
                                               <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tr>
                                                 <td class="contenttab_internal_rows"><nobr><b>Message Type</b></nobr></td>
                                                 <td class="padding">:</td>
                                                 <td class="padding">
                                                  <select name="msgType" id="msgType" class="inputbox" onchange="toggleAttendanceDateDisply(this.value);" style="width:120px;">
                                                   <option value="">Select</option>
                                                   <option value="1">Attendance</option>
                                                   <option value="2">Marks</option>
                                                  </select>
                                                 </td>
                                                 <td id="uptoTd1" class="contenttab_internal_rows" style="display:none;"><b>Upto</b></td>
                                                 <td id="uptoTd2" class="padding" style="display:none;">:</td>
                                                 <td id="uptoTd3" class="padding" style="display:none;">
                                                  <?php
                                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                    echo HtmlFunctions::getInstance()->datePicker('attendanceUpToDate',date('Y-m-d'));
                                                   ?>
                                                 </td>
                                                 <td id="uptoTd4" class="contenttab_internal_rows" style="display:none;">
                                                  <nobr><b>Duty leaves</b></nobr>
                                                 </td>
                                                 <td id="uptoTd5" class="padding" style="display:none;">:</td>
                                                 <td id="uptoTd6" class="contenttab_internal_rows" style="display:none;" colspan="4">
                                                 <input type="radio" name="dutyLeaves" id="dutyLeaves1" value="1">Yes&nbsp;
                                                 <input type="radio" name="dutyLeaves" id="dutyLeaves2" value="0" checked="checked">No
                                                </tr>
                                                <tr>
                                                  <td class="contenttab_internal_rows"><b>Time Table</b></td>
                                                  <td class="padding">:</td>
                                                  <td class="padding">
                                                   <select name="timeTableLabelId" id="timeTableLabelId" class="inputbox" style="width:120px;" onchange="getClasses(this.value)">
                                                    <option value="">Select</option>
                                                    <?php
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getTimeTableLabelData('-1');
                                                    ?>
                                                   </select>
                                                  </td>
                                                  <td class="contenttab_internal_rows"><b>Class</b></td>
                                                  <td class="padding">:</td>
                                                  <td class="padding">
                                                   <select name="classId" id="classId" class="inputbox" onchange="getGroups(document.allDetailsForm.timeTableLabelId.value,this.value);" >
                                                    <option value="">Select</option>
                                                   </select>
                                                  </td>
                                                  <td class="contenttab_internal_rows"><b>Group</b></td>
                                                  <td class="padding">:</td>
                                                  <td class="padding">
                                                   <select name="groupId" id="groupId" class="inputbox" style="width:120px;" onchange="getTests(document.allDetailsForm.timeTableLabelId.value,document.allDetailsForm.classId.value,this.value);" >
                                                    <option value="">Select</option>
                                                   </select>
                                                  </td>
                                                  <td class="contenttab_internal_rows"><b>Test</b></td>
                                                  <td class="padding">:</td>
                                                  <td class="padding">
                                                   <select name="testId" id="testId" class="inputbox" disabled="disabled" >
                                                    <option value="">Select</option>
                                                   </select>
                                                  </td>
                                                </tr> 
                                               </table>
                                             </td>
                                            </tr>
                                            <tr>
                                                <td valign='top' colspan='10' class='' align='center'>
                                                    <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
                                                </td>
                                            </tr>
										
                                        </table>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </td>
                  </tr>


              </table>

			<table border="0"  bgcolor="#FFCC99" width="1000px" >
            <tr> 		
            <td>Message Sent To parents <?php echo HtmlFunctions::getInstance()->getHelpLink('PerformanceHelp',PERFORMANCE_HELP);?> </td>
		    </tr>
			</table>
		


             <div id="showList" style="display:none">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                 <td>                    
                  <b></b><label id="totalRecordsId"></label></b> 
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="checkbox" id="sendToAllChk" value="sendToAllChk" style="display:none" />
                  <label for="sendToAllChk" style="display:none" ><b>Send to All</b></b><label>
                 </td>
                </tr> 
                <tr>
                 <td>
                <form name="listFrm" id="listFrm">
                <!--Do not delete-->
                 <input type="hidden" name="fathers" id="fathers" />
                 <input type="hidden" name="fathers" id="fathers" />  
                 <!--Do not delete-->
                 <div id="results">
                </div>
                </form>           
                </td>
               </tr>
               <tr><td height="5px"></td></tr>
               <tr> 
                <td align="center">
                <div id="divButton" style="display:none">
                  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/send.gif" onClick="return validateForm();return false;" />
                 <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="hide_div('showList',2);resetForm();return false;" />
				 </div> 
                 </td>
               </tr>
               <tr><td height="5px"></td></tr>
              </table> 
              </div>
             </td>
             </tr>
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>


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


<?php
// $History: listStudentPerformanceMessageContents.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 22/03/10   Time: 13:51
//Updated in $/LeapCC/Templates/AdminMessage
//Modified search filter in "Send student performance message to parents"
//module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 20/03/10   Time: 17:45
//Updated in $/LeapCC/Templates/AdminMessage
//Corrected title and breadcrumb of the page
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/03/10   Time: 17:36
//Created in $/LeapCC/Templates/AdminMessage
//Created "Sent Student Information Message To Parents" module
?>