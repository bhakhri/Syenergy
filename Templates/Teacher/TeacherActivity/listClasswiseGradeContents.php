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
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
			<?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
               <!-- <td valign="top">Marks & Attendance&nbsp;&raquo;&nbsp;Display Marks</td> -->
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
              <!--  <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Display Marks : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td> -->
             </tr>
             <tr>
             <td class="contenttab_border1" align="center" >
             <form action="" method="" name="searchForm"> 
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>    
                        <td width="5%" class="contenttab_internal_rows" align="left"><nobr><b>Class</b></nobr></td>
                        <td width="20%" class="padding" align="left"><nobr>:
                        <select size="1" class="selectfield" name="classes" id="classes" onchange="deleteRollNo();populateSubjects(this.value);" >
                        <option value="">Select Class</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTeacherClassData();
                        ?>
                      </select></nobr></td>
                        <td width="7%" class="contenttab_internal_rows" style="padding-left:15px"><nobr><b>Subject</b></nobr></td>
                        <td width="20%" class="padding"><nobr>:
                        <select size="1" class="selectfield" name="subject" id="subject" onchange="deleteRollNo();groupPopulate(this.value);" >
                        <option value="">Select Subject</option>
                          <?php
                           //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select></nobr>
                      </td>
                        <td width="7%" class="contenttab_internal_rows" style="padding-left:15px"><nobr><b>Group</b></nobr></td>
                        <td width="20%" class="padding" colspan="2" align="left"><nobr>:
                        <select size="1" class="selectfield" name="group" id="group" onchange="deleteRollNo();" >
                        <option value="">Select Group</option>
                        </select></nobr>
                      </td>
                    <!--  <td>&nbsp;</td>
                      <td  align="left" style="padding-left:5px" >
                        <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                      </td>-->
                    </tr>
                    
                    <tr>    
                        <td width="5%" class="contenttab_internal_rows"><nobr><b>Name</b></nobr></td>
                        <td width="20%" class="padding"  align="left"><nobr>: 
                        <input type="text" id="studentName" name="studentName" class="inputbox" style="width:182px"></nobr>
                        </td>
                        <td width="7%" class="contenttab_internal_rows" style="padding-left:15px"><nobr><b>Roll No / Univ. Roll No</b></nobr></td>
                        <td width="20%" class="padding"  align="left"><nobr>: 
                        <input type="text" id="studentRollNo" name="studentRollNo" autocomplete='off' class="inputbox" style="width:182px"></nobr>
                        </td>
                        <td class="contenttab_internal_rows" style="padding-left:15px"><b>Sort</b></td>
                        <td class="padding" align="left" nowrap>:
                            <select name="sorting"  class="selectfield" id="sorting" style="width:100px">
                                    <option value="1">C.RollNo</option>
                                    <option value="2">U.RollNo</option>
                                    <option value="3">Name</option>
                            </select>
                        <span class="contenttab_internal_rows" style="font-weight:normal;"><b>Order : </b>
                        <input type="radio" name="ordering" id="ordering1" checked="checked" value="1" />Asc&nbsp;
                        <input type="radio" name="ordering" id="ordering2" value="0" />Desc</span>
                       </td>
                        <td  align="left" style="padding-left:5px;">
                         <input type="image" style="margin-bottom:-5px;" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                       </td>
                    </tr>  
                    <tr><td colspan="8" height="5px"></td><td width="41%"></td>    
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                 <div id="results">
                </div>
             </td>
          </tr>
			<tr id="print" style="display:none">
				<td class="content_title" title="Print" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" />&nbsp;<input type="image"  name="printMarksSubmit" id='generateCSV' onClick="printCSV();return false;" value="printMarksSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></td>
			</tr>

          
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
<?php
// $History: listClasswiseGradeContents.php $
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 4/20/10    Time: 2:50p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//make print & export to excel for display marks
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//added code for autosuggest functionality
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected look and feel of teacher module logins
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 29/07/09   Time: 17:23
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done the enhancement: subjects are populated corresponding to the class
//selected
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 29/06/09   Time: 11:32
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added name & roll no wise search in display attendance and marks
//display in teacher login
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/18/09    Time: 10:37a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//modified to show group by selecting subject
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/18/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/07/08    Time: 4:52p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
?>