<?php
//----------------------------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR class wise grade template
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
	 <?php //require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
           <td valign="top">Marks & Attendance&nbsp;&raquo;&nbsp;Display Attendance</td> 
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
                <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Display Attendance : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_border1" align="center" >
             <form action="" method="" name="searchForm"> 
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>    
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Subject: </b></nobr></td>
                        <td width="20%" class="padding" align="left">
                        <select size="1" class="selectfield" name="subject" id="subject" onchange="deleteRollNo();classPopulate();" >
                        <option value="">Select Subject</option>
                          <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select>
                      </td>
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Section: </b></nobr></td>
                        <td width="20%" class="padding" align="left" >
                        <select size="1" class="selectfield" name="section" id="section" onchange="deleteRollNo();classPopulate();">
                         <option value="">Select Section</option>
                          <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getTeacherSectionData();
                         ?>
                        </select>
                      </td>
                      <td width="10%" class="contenttab_internal_rows" align="left"><nobr><b>Class: </b></nobr></td>
                        <td width="20%" class="padding" align="left">
                        <select size="1" class="selectfield" name="classes" id="classes" onchange="deleteRollNo();">
                         <option value="">All</option>
                         </select>
                      </td>
                    </tr>
                   <!-- 
                    <tr>    
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Roll No: </b></nobr></td>
                        <td width="20%" class="padding"  align="left">
                        <input type="text" id="studentRollNo" name="studentRollNo" class="inputbox" style="width:185px">
                        </td>
                        <td  colspan="2" align="left" class="title1">(Select a Specific Student)</td>
                    </tr>
                   --> 
                    <tr>
                       <td width="10%" class="contenttab_internal_rows"><nobr><b>From Date: </b></nobr></td>
                       <td width="20%" class="padding"  align="left">
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            $toDate=date('Y')."-".date('m')."-".date('d');
                            echo HtmlFunctions::getInstance()->datePicker('fromDate',$toDate);
                         ?>
                        </td>
                       <td width="10%" class="contenttab_internal_rows"><nobr><b>To Date: </b></nobr></td>
                       <td width="20%" class="padding"  align="left">
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            $toDate=date('Y')."-".date('m')."-".date('d');
                            echo HtmlFunctions::getInstance()->datePicker('toDate',$toDate);
                         ?>
                        </td>
                       <td>&nbsp;</td> 
                       <td  align="left" style="padding-left:5px" >
                         <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                       </td>
 
                    </tr>  
                    <tr><td colspan="6" height="5px"></td><td width="41%"></td>    
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" style="padding-right:10px;" >&nbsp;
                 <div id="results">
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
<?php
// $History: scListClasswiseAttendanceContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/19/08    Time: 5:13p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//'Select Class' to 'All' according to Sachin Sir
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/18/08    Time: 5:06p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:37p
//Created in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:19p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/18/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/14/08    Time: 5:49p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/08/08    Time: 11:45a
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
?>