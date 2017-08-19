<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
 
?>
	<tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
								<td class="contenttab_border" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH."/searchForm.php"); ?>
								</td>
							</tr>
							<tr>	
							 <td valign="top"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
            <td valign="top">
             <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
            
             <tr>
                <td class="contenttab_row" valign="top" align="left" valign="top" style="border-right-width:0px;">
                <table cellpadding="0" cellspacing="0" border="0" width="200px">
                <tr>
                 <td align="left" width="100%">
                 <?php
				  require_once(BL_PATH . "/Calendar/ajaxCreateCalendar.php"); // render calendar now
 				 ?>  
                </td>
               </tr>
              </table>         
             </td>
          
            <td valign="top"  class="contenttab_row" style="border-left-width:0px;">
 		   <div id="show_event_list" style="display:none">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td  height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="fontTitle"><div id="eventDate"></div></td>
                        <td class="fontTitle" title="Add" align="right"><a style="cursor:pointer" onClick="displayWindow('AddEvent',525,250);blankValues();return false;">Add Event</a>&nbsp;</td>
                        <td class="content_title1" title="Add" align="right" width="2%"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddEvent',525,250);blankValues();return false;"  />
						</td></table></tr>
						 <tr>
                <td  valign="top" >

		        <div id="results">
		    
               </div>   
		    </div>
		  </td>
		 </tr>
          </table>
        </td>
    </tr>
	 <tr><td height="10px" colspan="2"></td></tr>
    <tr>
       <td align="right" colspan="2">
             <input type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">
             &nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV()"/>
    </td></tr>    
 <!--   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
          
                <td valign="top" align="right">&nbsp;
                <form action="" method="" name="searchForm" onsubmit="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value ;sendReq(listURL,divResultName,searchFormName,'');return false;">
                   <input type="hidden" id="sdate" name="sdate" value="" style="display:none;" />
                   <input type="hidden" name="searchbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" />
                  
                </form>
                </td>
            </tr>
            </table>
        </td>
    </tr>
        </table>
		 </table>     -->
    </td>
    </tr>
    </table>
      <!--  <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" align="left" valign="top" style="border-right-width:0px;">
                <table cellpadding="0" cellspacing="0" border="0" width="200px">
                <tr>
                 <td align="left" width="100%">
                 <?php
				  require_once(BL_PATH . "/Calendar/ajaxCreateCalendar.php"); // render calendar now
 				 ?>  
                </td>
               </tr>
              </table>         
             </td>
          
            <td valign="top"  class="contenttab_row" style="border-left-width:0px;">
 		   <div id="show_event_list" style="display:none">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td  height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="fontTitle"><div id="eventDate"></div></td>
                        <td class="fontTitle" title="Add" align="right"><a style="cursor:pointer" onClick="displayWindow('AddEvent',525,250);blankValues();return false;">Add Event</a>&nbsp;</td>
                        <td class="content_title1" title="Add" align="right" width="2%"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddEvent',525,250);blankValues();return false;"  />
						</td> -->
                 <!--   </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td  valign="top" >

		        <div id="results">
		    
               </div>   
		    </div>
		  </td>
		 </tr> -->
   
     
    
   
    
   
    <!--Start Add Div-->

<?php floatingDiv_Start('AddEvent','Add Event');  ?>
<form name="AddEvent" action="" method="post">
    <table border="0" cellspacing="0" cellpadding="0" class="border">
	<tr>
	    <td class="contenttab_internal_rows"><nobr><b>Event Title<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
		 <input type="text" id="eventTitle" name="eventTitle" class="inputbox" maxlength="240" onkeydown="return sendKeys(1,'eventTitle',event);" />
		</td>
        <td class="contenttab_internal_rows"><nobr><b>Start Date</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
        <?php 
        $thisDate=date('Y')."-".date('m')."-".date('d');
        ?>
        <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('startDate1',$thisDate);
        ?>        
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Short Description<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
		 <input type="text" id="shortDescription" name="shortDescription" class="inputbox" maxlength="240" onkeydown="return sendKeys(1,'shortDescription',event);" />
		</td>
        <td class="contenttab_internal_rows"><nobr><b>End Date</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
         <?php
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->datePicker('endDate1',$thisDate);
         ?>
        </td>
    </tr>
	<tr>
        <td class="contenttab_internal_rows"><nobr><b>Long Description<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding" colspan="4">
		  <textarea id="longDescription" name="longDescription" cols="42" rows="5" class="inputBox" maxlength="2000" onkeyup="return ismaxlength(this)"></textarea>
		</td>
    </tr>
	<tr>
        <td class="contenttab_internal_rows"><nobr><b>Visible To<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="" colspan="4" style="padding-left:6px">
        <select size="2"  multiple="multiple" name="roles1" id="roles1" style="width:190px;">
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getRoleData($REQUEST_DATA['roles']==''? $eventRecordArray[0]['roleId'] : $REQUEST_DATA['roles'] );
              ?>
        </select>

    </td>
  </tr>
<tr><td height="5px" colspan="6"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="6">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddEvent');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>
<tr><td height="5px" colspan="6"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditEvent','Edit Event '); ?>
<form name="EditEvent" action="" method="post">
<input type="hidden" name="eventId" id="eventId" value="" />
<table border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Event Title<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
         <input type="text" id="eventTitle" name="eventTitle" class="inputbox" maxlength="240" onkeydown="return sendKeys(2,'eventTitle',event);" />
        </td>
        <td class="contenttab_internal_rows"><nobr><b>Start Date</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
        <?php 
        $thisDate=date('Y')."-".date('m')."-".date('d');
        ?>
        <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('startDate2','');
        ?>        
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Short Description<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
         <input type="text" id="shortDescription" name="shortDescription" class="inputbox" maxlength="240" onkeydown="return sendKeys(2,'shortDescription',event);" />
        </td>
        <td class="contenttab_internal_rows"><nobr><b>End Date</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
         <?php
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->datePicker('endDate2','');
         ?>
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Long Description<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding" colspan="4">
          <textarea id="longDescription" name="longDescription" cols="42" rows="5" class="inputBox" maxlength="2000" onkeyup="return ismaxlength(this)"></textarea>
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Visible To<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="contenttab_internal_rows" colspan="4" style="padding-left:6px">
        <select size="5" multiple="multiple" name="roles2" id="roles2" style="width:190px;">
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getRoleData($REQUEST_DATA['roles']==''? $eventRecordArray[0]['roleId'] : $REQUEST_DATA['roles'] );
              ?>
        </select>
        </td>
  </tr>
<tr><td height="5px" colspan="6"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="6">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditEvent');return false;" />
        </td>
</tr>
<tr><td height="5px" colspan="6"></td></tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->
    
    

<!--Start Display Event Div(Detailed)-->
<?php floatingDiv_Start('divEvent','Event Description','',' '); ?>
<form name="EventForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Event: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="eventTitle3" style="overflow:auto; width:630px; height:20px" ></div></td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Date: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td valign="middle" colspan="2">&nbsp;<B>From</B>: <span id="startDate3" style="height:20px"></span>&nbsp;&nbsp;<B>To</B>: <span id="endDate3" style="height:20px"></span></td>
</tr>
<tr>
    <td height="5px"></td>
</tr> 
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Short Description: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td valign="middle" colspan="2" style="padding-left:3px" ><div id="shortDescription3" style="overflow:auto; width:630px; height:20px" ></div></td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Long Description: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td valign="middle" colspan="2" style="padding-left:3px" >
     <div id="longDescription3" style="overflow:auto; width:630px;height:100px" ></div>
    </td>
</tr> 
 
<tr>
<td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--Start Display Event Div(Detailed)-->
    

<!--Divs for multiple selected dds-->
<div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d11"></div>
<div style="display:none;position:fixed;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d22" >
  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
       <tr>
          <td id="d33" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
          <td width="5%">
          <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('roles1','d11','containerDiv1','d33',true,true);" />
          </td>
        </tr>
     </table>
 </div>
 
<div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d111"></div>
<div style="display:none;position:fixed;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d222" >
  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
       <tr>
          <td id="d333" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
          <td width="5%">
          <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('roles2','d111','containerDiv11','d333',true,true);" />
          </td>
        </tr>
     </table>
 </div>
<?php
// $History: listCalendarContents.php $
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 15/02/10   Time: 17:43
//Updated in $/LeapCC/Templates/Calendar
//Modified javascript and html coding for "New Multiple Selected
//Dropdowns" as these are not working in IE6.
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 4/02/10    Time: 12:55
//Updated in $/LeapCC/Templates/Calendar
//Done bug fixing.
//Bug ids---
//0002528,0002303,0002193,0001928,
//0001922,0001863,0001763,0001238,
//0001229,0001894,0002143
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 5/01/10    Time: 11:58
//Updated in $/LeapCC/Templates/Calendar
//Made UI changes in Manage Notice Module
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 4/01/10    Time: 19:01
//Updated in $/LeapCC/Templates/Calendar
//Made UI changes
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Calendar
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 10/27/09   Time: 4:43p
//Updated in $/LeapCC/Templates/Calendar
//resolved issue 1895
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 26/10/09   Time: 11:40
//Updated in $/LeapCC/Templates/Calendar
//Corrected max length attribute of html elements
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 20/08/09   Time: 13:02
//Updated in $/LeapCC/Templates/Calendar
//Corrected javascrip code for searching in pressing "enter" key
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 19/08/09   Time: 15:26
//Updated in $/LeapCC/Templates/Calendar
//Done bug fixing.
//bug ids---00001141,00001142
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/08/09    Time: 10:37
//Updated in $/LeapCC/Templates/Calendar
//removed "close" button as suggested by QE
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 4/08/09    Time: 17:07
//Updated in $/LeapCC/Templates/Calendar
//Corrected "Event Masters" as pointed by Kanav Sir
//
//*****************  Version 2  *****************
//User: Administrator Date: 26/05/09   Time: 10:47
//Updated in $/LeapCC/Templates/Calendar
//Corrected display as reported by vimal sir.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Calendar
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 10/24/08   Time: 1:36p
//Updated in $/Leap/Source/Templates/Calendar
//Added functionality for calendar event report print
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 9/24/08    Time: 12:46p
//Updated in $/Leap/Source/Templates/Calendar
//Corrected javascript error thrown by Internet Explorer
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 9/09/08    Time: 4:36p
//Updated in $/Leap/Source/Templates/Calendar
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 9/03/08    Time: 5:22p
//Updated in $/Leap/Source/Templates/Calendar
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/02/08    Time: 2:38p
//Updated in $/Leap/Source/Templates/Calendar
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/02/08    Time: 2:01p
//Updated in $/Leap/Source/Templates/Calendar
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/Calendar
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/18/08    Time: 12:56p
//Updated in $/Leap/Source/Templates/Calendar
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/Calendar
//corrected breadcrumb and reset button height and width
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/11/08    Time: 5:27p
//Updated in $/Leap/Source/Templates/Calendar
//Modify calender functionility to have common function
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/05/08    Time: 12:29p
//Updated in $/Leap/Source/Templates/Calendar
//Added SessionId in the code 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/04/08    Time: 7:20p
//Updated in $/Leap/Source/Templates/Calendar
//Created Calendar(event) module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/03/08    Time: 12:35p
//Created in $/Leap/Source/Templates/Calendar
//Initial Checkin
?>