<?php 
//it contain the template of time table 
//
// Author :Jaineesh
// Created on : 22-07-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" >
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?> 
             <!--   <td valign="top">Show Time Table  </td> -->
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
                    <table border="0" cellspacing="1" cellpadding="1" align="right">
                        <tr>
                            <td width="100%" class="contenttab_internal_rows1" align="right"><nobr><b>Study Period : </b></nobr></td>
                            <td class="padding" width="30%"><select size="1" class="selectfield" name="semesterDetail" id="semesterDetail" onChange="getStudentTimeTable(this.value)">
                            <option value="0" selected="selected">All</option>
                                <?php
                                    $studentId = $sessionHandler->getSessionVariable('StudentId');
                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                    echo HtmlFunctions::getInstance()->getStudyPeriodName($studentId,$classId);
                                ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_row" valign="top" width="100%" >
             <!--Time Table Data Template-->
                <div id="scroll2" style="height:400px;vertical-align:top;">
                   <div id="results" style="height:350px;overflow:auto; vertical-align:top;"></div>
                </div>
            <!--Time Table Data Template Ends-->           
             </td>
          </tr>
         
          </table>
          
        </td>
    </tr>
    </table>
    
<?php
//$History: timeTableContents.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 4/22/10    Time: 11:51a
//Updated in $/LeapCC/Templates/Student
//validation & condition format updated 
//
//*****************  Version 5  *****************
//User: Parveen      Date: 4/21/10    Time: 5:13p
//Updated in $/LeapCC/Templates/Student
//format updated
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/31/09    Time: 1:25p
//Updated in $/LeapCC/Templates/Student
//fixed the bugs during self testing
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/13/09    Time: 6:28p
//Updated in $/LeapCC/Templates/Student
//modified for left alignment and giving cell padding, cell spacing 1
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:45p
//Updated in $/LeapCC/Templates/Student
//modification in code for cc
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 9/19/08    Time: 5:20p
//Updated in $/Leap/Source/Templates/Student
//modified in time table template for showing subject code
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 9/04/08    Time: 7:48p
//Updated in $/Leap/Source/Templates/Student
//fixed the bugs
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/26/08    Time: 12:15p
//Updated in $/Leap/Source/Templates/Student
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/22/08    Time: 3:18p
//Updated in $/Leap/Source/Templates/Student
//modified for print button
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/18/08    Time: 6:52p
//Updated in $/Leap/Source/Templates/Student
//change print button
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/14/08    Time: 7:43p
//Updated in $/Leap/Source/Templates/Student
//used to make print file of student time table
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/11/08    Time: 11:44a
//Updated in $/Leap/Source/Templates/Student
//modification in template
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/08/08    Time: 12:16p
//Updated in $/Leap/Source/Templates/Student
//modified in time table query
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/02/08    Time: 1:57p
//Updated in $/Leap/Source/Templates/Student
//modification in table table template
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/01/08    Time: 5:13p
//Updated in $/Leap/Source/Templates/Student
//modified for show data in table data
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/31/08    Time: 12:26p
//Updated in $/Leap/Source/Templates/Student
//modified in time table contents
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/25/08    Time: 12:45p
//Created in $/Leap/Source/Templates/Student
//contain the template of student time table
//
?>