<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
               <!-- <td valign="top">Student Info&nbsp;&raquo;&nbsp;Search Student</td> -->
			    <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
                <td valign="top" align="right">
                 <!-- 
                <form action="" method="" name="searchForm">
               
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="submit" value="Search" name="submit"  class="button" style="margin-bottom: 3px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
                  </form>
                   --> 
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
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
          <td height="1"> 
                
                  
		
                </td>
             </tr>
             <tr>
             <td class="contenttab_border1" align="center" nowrap="nowrap">
             <form action="" method="" name="searchForm"> 
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>    
                        <td width="5%" class="contenttab_internal_rows" align="left"><nobr><b>Class</b></nobr></td>
                        <td width="20%" class="padding" align="left"><nobr>: <select size="1" class="selectfield" name="class" id="class" onchange="populateSubjects(this.value);groupPopulate(this.form.subject.value);washoutData()" >
                        <option value="">Select Class</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTeacherClassData();
                        ?>
                      </select></nobr></td>
                        <td width="6%" class="contenttab_internal_rows"><nobr><b>Subject</b></nobr></td>
                        <td width="20%" class="padding" align="left"><nobr>: <select size="1" class="selectfield" name="subject" id="subject" onchange="groupPopulate(this.value);washoutData()">
                        <option value="">Select Subject</option>
                          <?php
                           //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select></nobr>
                      </td>
                        <td width="6%" class="contenttab_internal_rows" style="padding-left:15px"><nobr><b>Group</b></nobr></td>
                        <td width="20%" class="padding" align="left"><nobr>: <select size="1" class="selectfield" name="group" id="group" onchange="washoutData()">
                        <option value="">Select Group</option>
                          <?php
                           //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherGroupData();
                        ?>
                        </select></nobr>
                      </td>
                    </tr>
                    <tr> 
                        <td width="5%" class="contenttab_internal_rows"><nobr><b>Name</b></nobr></td>
                        <td class="padding"  align="left"><nobr>: 
                        <input type="text" id="studentNameFilter" name="studentNameFilter" class="inputbox" style="width:180px">&nbsp;&nbsp;<span class="contenttab_internal_rows"><b>( Or / And )</b></span></nobr>
                        </td>   
                        <td width="6%" class="contenttab_internal_rows"><nobr><b>Roll No.</b></nobr></td>
                        <td width="20%" class="padding"  align="left"><nobr>: 
                        <input type="text" id="studentRollNo" name="studentRollNo" class="inputbox" style="width:180px" autocomplete="off"></nobr>
                        </td>
                        <td>&nbsp;</td>
                        <td  align="left" style="padding-left:15px">
                        <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" /></td>
                    </tr>
                    <tr><td colspan="6" height="5px"></td><td width="41%"></td>    
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                 <form name="listFrm" id="listFrm">
                 <div id="results">
                <!-- 
                 <table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid">
                 <tr class="rowheading">
                    <td width="5%" class="unsortable">&nbsp;&nbsp;<b>#</b></td>
                    <td width="20%" class="searchhead_text"><b><nobr>Name</nobr></b> <img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="10%" class="searchhead_text"><b><nobr>Roll No</nobr></b> <img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=universityCode')" /></td>
                    <td width="15%" class="searchhead_text"><b><nobr>University No</nobr></b> <img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=universityAbbr')" /></td>
                    <td width="10%" class="searchhead_text"><b><nobr>Degree</nobr></b></td>
                    <td width="10%" class="searchhead_text"><b><nobr>Branch</nobr></b></td>
                    <td width="8%" class="searchhead_text"><b><nobr>Batch</nobr></b></td>
                    <td width="10%"  align="right" style="padding-right:8px;"><b>Details</b></td>
                 </tr>
                <?php
                /*
                $recordCount = count($studentRecordArray);
                if($recordCount >0 && is_array($studentRecordArray) ) { 
                     
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                    //<td class="padding_top" valign="top">'.strip_slashes($universityRecordArray[$i]['contactPerson']).'</td>    
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['studentName']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['rollNo']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['universityRollNo']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['degreeName']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['branchName']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['batchName']).'</td>
                        <td width="10%" class="searchhead_text1" align="right">
                         <a style=cursor:pointer onclick=openUrl('.$studentRecordArray[$i]['studentId'].');>Show</a>
                        </td>
                        </tr>';
                    }
                if($totalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                          $bg = $bg =='row0' ? 'row1' : 'row0';
                          require_once(BL_PATH . "/Paging.php");
                          $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
                          echo '<tr><td colspan="8" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';                   
                    }
                }
                else {
                    echo '<tr><td colspan="8" align="center">No record found</td></tr>';
                }
               */ 
                ?>                 
                 </table>
                 -->
                </div>
                </form>           
             </td>
          </tr>
          
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
<?php
// $History: searchStudentContents.php $
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//added code for autosuggest functionality
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 15/10/09   Time: 11:41
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Done enhancements in teacher login.
//1. Subject and groups are coming based upon selection of class
//,subjects in "Search Student" module in teacher login.Previously all
//values are coming.
//
//2.Before saving attendance data in daily attendance module,user have to
//confirm the attendance summary.Previously after saving data,this
//information is displayed.
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 6/10/09    Time: 13:09
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Done Bug fixing:
//Bug id:
//0001705: Student Info - Teacher > No Action performed as click on
//“Export to Excel” button. 
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Corrected look and feel of teacher module logins
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 19/08/09   Time: 15:28
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Done bug fixing
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/06/09   Time: 14:18
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Modifed look and feel as mailed by kabir sir.
//
//*****************  Version 3  *****************
//User: Administrator Date: 3/06/09    Time: 17:22
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Done these modifications :
//
//1. My Time Table in Teacher: Add a link in the cell of Period/Day in My
//Time Table of teacher module, that takes the teacher to Daily
//Attendance interface and sets the value in Class, Subject,  and group
//DDMs from the time table. however, teacher will need to select Date and
//Period manually.
//
//2. Student Info in Teacher: Please add just "And/Or" between Name and
//Roll No search text boxes.
//
//3. Department wise Employee Selection in send messages links in teacher
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 10/12/08   Time: 12:01
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Added print & csv functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/StudentActivity
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/20/08    Time: 3:56p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/22/08    Time: 5:36p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:51p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:37p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//corrected breadcrumb and reset button height and width
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/14/08    Time: 4:51p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/02/08    Time: 10:42a
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/31/08    Time: 7:27p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/15/08    Time: 5:37p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:20p
//Created in $/Leap/Source/Templates/Teacher/StudentActivity
//Initial Checkin
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:20p
//Created in $/Leap/Source/Templates/Teacher/StudentActivity
//Initial Checkin
?>