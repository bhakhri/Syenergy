<?php 
//it contain the template of time table 
//
// Author :Dipanjan Bhattacharjee
// Created on : 30-07-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
 <?php

    require_once(BL_PATH.'/helpMessage.inc.php');
	require_once(BL_PATH.'/HtmlFunctions.inc.php');
?>
	    <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            
            <tr>
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
              <!-- <td valign="top">My Time Table </td> -->
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
        <form name="timeTableForm" action="" method="post" onSubmit="return false;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
              <td align="left" height=0>
            </td>
            </tr>
           
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title" width="20%" align="left">Time Table :</td>
						<td class="content_title" width="30%" align="center"><nobr><b>How to interpret Entries in cell<?php
                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                        echo HtmlFunctions::getInstance()->getHelpLink('How to interpret Entries in cell',HELP_INTERPRET_ENTRIES);
                                     ?></b></nobr></td>
                        <td class="content_title" width="30%" align="right">Time Table Name : 
                        <select size="1" class="selectfield" name="timeTableLabelId" id="timeTableLabelId" style="width:150px" 
                            onchange="getTimeTableData(1);">
                         <option value="0">All</option>
                        <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         echo HtmlFunctions::getInstance()->getTimeTableLabelDataForTeachers();
                        ?>
                        </select>   
                            &nbsp;&nbsp;
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_row" valign="top" >
             <!--Time Table Data Template-->
              <div id="resultRow">
               
              </div>
            <!--Time Table Data Template Ends-->           
             </td>
          </tr>
           
          </table>
          </form>
        </td>
    </tr>
    
    </table>
<!--Assignments Marks Help  Details  Div-->
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
//$History: listTimeTableContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 31/07/09   Time: 18:05
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added the check for teacher timetable in teacher login:
//Teachers can see only active and past classes timetable
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/05/09    Time: 10:35
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified files to show new time time format in teacher login
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 3/04/09    Time: 17:07
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Displays group in time table
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/03/09    Time: 13:03
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added the functionality of Time Table Print in Teacher Section
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/22/08    Time: 2:53p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/18/08    Time: 5:39p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/06/08    Time: 6:50p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//Done modifications as discussed in the demo session
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/02/08    Time: 1:03p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/01/08    Time: 11:46a
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//Created Teacher Time Table
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/31/08    Time: 7:27p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
?>
