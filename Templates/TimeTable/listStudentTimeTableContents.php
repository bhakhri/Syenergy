<?php 
//it contain the template of time table for student
//
// Author :Rajeev Aggarwal
// Created on : 10-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
          <?php 
              require_once(TEMPLATES_PATH . "/breadCrumb.php"); 
          ?> 
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
            
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border2" height="20">
                    <form name="allDetailsForm" action="" method="post" onSubmit="return false;">     
                    <table width="30%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                         <td class="contenttab_internal_rows" ><nobr><b>Time Table Format</b></nobr></td>
                         <td class='contenttab_internal_rows' align='left' ><nobr><b>&nbsp;:</b></nobr></td>    
                         <td class='contenttab_internal_rows' align='left'>
                           <nobr><input type="radio" value="1"  onclick="clearText();"  name="timeFormat[]" id="timeFormat"></nobr>
                         </td>
                         <td class='contenttab_internal_rows' align='left'><nobr>Weekly&nbsp;</nobr></td>
                         <td class='contenttab_internal_rows' align='left'>
                           <nobr><input type="radio" value="2"  checked="checked" name="timeFormat[]" onclick="clearText();" id="timeFormat"></nobr>
                         </td>
                         <td class='contenttab_internal_rows' align='left'><nobr>Daily&nbsp;</nobr></td>
                         <td class="contenttab_internal_rows" id="timeTableCheck" style="display:none;"><nobr>
                            <?php 
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                                echo HtmlFunctions::getInstance()->makeTimeTableSearch(); 
                            ?>     
                         </nobr></td>
                         <td class="contenttab_internal_rows" ><nobr><b>&nbsp;&nbsp;Roll No./User Name</b></nobr></td>
                         <td class='contenttab_internal_rows' align='left' ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>    
                         <td class='contenttab_internal_rows' align='left'>
                            <nobr><input type='text' class='selectfield' name='rollNo' id='rollNo' autocomplete='off' style='width:150px'/></nobr>
                        </td>
                        <td class="contenttab_internal_rows" valign="middle" style="padding-left:20px">
                            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/show.gif" onClick="return getTimeTableData();return false;" />
                        </td>
                    </tr>
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
             <td class="contenttab_row" valign="top" >
             <!-- Time Table Data Template -->
                <div id="scroll2" style="overflow:auto; height:510px; vertical-align:top;">
                   <div id="results" style="width:98%; vertical-align:top;"></div>
                </div>
            <!-- Time Table Data Template Ends -->           
             </td>
          </tr>
          </table>
        </td>
    </tr>
    </table>
 <?php
//$History: listStudentTimeTableContents.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/TimeTable
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/TimeTable
//added code for autosuggest functionality
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/03/09    Time: 11:31a
//Updated in $/LeapCC/Templates/TimeTable
//Gurkeerat: resolved issue 1415
//
//*****************  Version 3  *****************
//User: Administrator Date: 26/05/09   Time: 11:31
//Updated in $/LeapCC/Templates/TimeTable
//Corrected table width
//
//*****************  Version 2  *****************
//User: Administrator Date: 26/05/09   Time: 11:28
//Updated in $/LeapCC/Templates/TimeTable
//Changed showlist button to show
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/10/08   Time: 5:53p
//Created in $/LeapCC/Templates/TimeTable
//intial checkin
?>
