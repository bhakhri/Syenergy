<?php 
//it contain the template of time table 
//
// Author :Jaineesh
// Created on : 22-07-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title" >
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Parent Activities&nbsp;&raquo;&nbsp; Display Time Table </td>
                <td valign="top" align="right">   
               <form action="" method="" name="searchForm"> </form>
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
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Time Table :</td>
                         <td align='right'>
                            <span class="content_title">Study Period:</span>
                           <select size="1" class="selectfield" name="studyPeriod" id="studyPeriod" style="width:100px" onchange="refreshStudentData('<?php echo $studentDataArr[0]['studentId']?>',this.value);"> 
                            <option value="0">All</option>
                              <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStudyPeriodName($studentDataArr[0]['studentId'],$studentDataArr[0]['classId']);
                              ?>
                              </select>&nbsp;&nbsp;  
                          </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top">
                    <div id="scroll2" style="overflow:auto; height:510px; vertical-align:top;">
                        <div id="timeTableResultDiv" style="width:98%; vertical-align:top;"></div>
                    </div>
                 </td>
              </tr>
              <tr>
                  <td colspan='1' align='right'><div id = 'saveDiv3'></div></td>
               </tr> 
             </td>
              </tr>
           <tr>
           <td class="content_title" align="right" style="padding-right:10px">
              <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/print.gif"  onClick="printReport(); return false;" >&nbsp;
              <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif"  onClick="printCSV(); return false;" >&nbsp;
           </td>
           </tr>
          </table>
        </td>
    </tr>
    
    </table>

	
<?php
//$History: scTimeTableContents.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/04/09    Time: 3:39p
//Created in $/LeapCC/Templates/Parent
//file added
//
//*****************  Version 17  *****************
//User: Parveen      Date: 9/03/09    Time: 5:48p
//Updated in $/Leap/Source/Templates/ScParent
//condition & formating updated issue fix (1426, 1384, 1263, 1074)
//
//*****************  Version 16  *****************
//User: Parveen      Date: 8/17/09    Time: 7:02p
//Updated in $/Leap/Source/Templates/ScParent
//bug fix  (file attachement & format updated)
//1041, 1097, 1040, 1041, 1105, 1106, 1109 
//
//*****************  Version 15  *****************
//User: Parveen      Date: 8/14/09    Time: 10:09a
//Updated in $/Leap/Source/Templates/ScParent
//alignment, formatting updated
//
//*****************  Version 14  *****************
//User: Parveen      Date: 6/26/09    Time: 6:47p
//Updated in $/Leap/Source/Templates/ScParent
//format, condition, validation updated
//
//*****************  Version 13  *****************
//User: Parveen      Date: 12/19/08   Time: 5:23p
//Updated in $/Leap/Source/Templates/ScParent
//formatting settings
//
//*****************  Version 12  *****************
//User: Parveen      Date: 12/18/08   Time: 5:25p
//Updated in $/Leap/Source/Templates/ScParent
//code update
//
//*****************  Version 11  *****************
//User: Parveen      Date: 11/18/08   Time: 2:45p
//Updated in $/Leap/Source/Templates/ScParent
//study period align setting
//
//*****************  Version 10  *****************
//User: Parveen      Date: 11/18/08   Time: 2:10p
//Updated in $/Leap/Source/Templates/ScParent
//study period added
//
//*****************  Version 9  *****************
//User: Parveen      Date: 11/17/08   Time: 11:31a
//Updated in $/Leap/Source/Templates/ScParent
//study period added
//
//*****************  Version 8  *****************
//User: Arvind       Date: 10/22/08   Time: 4:34p
//Updated in $/Leap/Source/Templates/ScParent
//modify
//
//*****************  Version 7  *****************
//User: Arvind       Date: 10/20/08   Time: 6:57p
//Updated in $/Leap/Source/Templates/ScParent
//added section in display
//
//*****************  Version 6  *****************
//User: Arvind       Date: 10/06/08   Time: 6:27p
//Updated in $/Leap/Source/Templates/ScParent
//modified the time table display
//
//*****************  Version 5  *****************
//User: Arvind       Date: 10/06/08   Time: 4:14p
//Updated in $/Leap/Source/Templates/ScParent
//modified the display of the time table
//
//*****************  Version 4  *****************
//User: Arvind       Date: 9/22/08    Time: 4:12p
//Updated in $/Leap/Source/Templates/ScParent
//replaced subjectAbbreviation by subjectCode
//
//*****************  Version 3  *****************
//User: Arvind       Date: 9/17/08    Time: 6:26p
//Updated in $/Leap/Source/Templates/ScParent
//modifie dthe display of time table
//
//*****************  Version 1  *****************
//User: Arvind       Date: 9/17/08    Time: 11:29a
//Created in $/Leap/Source/Templates/ScParent
//initial checkin
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/23/08    Time: 11:53a
//Updated in $/Leap/Source/Templates/Parent
//added a field in display
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/18/08    Time: 4:53p
//Updated in $/Leap/Source/Templates/Parent
//added buttons at the bottom of the display
//
//*****************  Version 6  *****************
//User: Arvind       Date: 8/14/08    Time: 1:55p
//Updated in $/Leap/Source/Templates/Parent
//no change
//
//*****************  Version 4  *****************
//User: Arvind       Date: 8/02/08    Time: 2:06p
//Updated in $/Leap/Source/Templates/Parent
//modifed
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/01/08    Time: 4:28p
//Updated in $/Leap/Source/Templates/Parent
//reoved class from display table
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/31/08    Time: 3:45p
//Updated in $/Leap/Source/Templates/Parent
//modified the listing
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/25/08    Time: 6:06p
//Created in $/Leap/Source/Templates/Parent
//initial checkin
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/25/08    Time: 12:45p
//Created in $/Leap/Source/Templates/Student
//contain the template of student time table
//
?>
