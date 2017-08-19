<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Student Info&nbsp;&raquo;&nbsp;Search Student</td>
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
                        <td class="content_title">Search Students : </td>
                        <td class="content_title" >&nbsp;</td>
                        <td align="right" valign="middle">
                         <div id="printDiv1" style="display:none">
                          <a href="javascript:void(0);" onClick="printReport()"><img src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0"></a>&nbsp;&nbsp;
                          <a id='generateCSV' href='javascript:void(0);' onClick='javascript:printStudentCSV();'><img src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0"></a>
                         </div> 
                        </td>
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
                        <select size="1" class="selectfield" name="subject" id="subject" onchange="classPopulate();" >
                        <option value="">Select Subject</option>
                          <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select>
                      </td>
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Section: </b></nobr></td>
                        <td width="20%" class="padding" align="left" >
                        <select size="1" class="selectfield" name="section" id="section" onchange="classPopulate();" >
                         <option value="">Select Section</option>
                          <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getTeacherSectionData();
                         ?>
                        </select>
                      </td>
                      <td width="10%" class="contenttab_internal_rows" align="left"><nobr><b>Class: </b></nobr></td>
                        <td width="20%" class="padding" align="left">
                        <select size="1" class="selectfield" name="classes" id="classes" >
                         <option value="">All</option>
                         </select>
                      </td>
                    </tr>
                    <tr> 
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Name: </b></nobr></td>
                        <td width="20%" class="padding"  align="left">
                        <input type="text" id="studentNameFilter" name="studentNameFilter" class="inputbox" style="width:180px">
                        </td>   
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Roll No: </b></nobr></td>
                        <td width="20%" class="padding"  align="left">
                        <input type="text" id="studentRollNo" name="studentRollNo" class="inputbox" style="width:180px">
                        </td>
                        <td>&nbsp;</td>
                        <td  align="left" style="padding-left:5px">
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
          <tr><td height="5px"></td></tr>
           <tr>
            <td valign="top" align="right">
            <div id="printDiv2" style="display:none">
             <a href="javascript:void(0);" onClick="printReport()"><img src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0"></a>&nbsp;&nbsp;
             <a id='generateCSV' href='javascript:void(0);' onClick='javascript:printStudentCSV();'><img src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0"></a>
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
// $History: scSearchStudentContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/ScStudentActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 10/20/08   Time: 4:11p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//Added facility for printing and export to excel in student list
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/19/08    Time: 5:13p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//'Select Class' to 'All' according to Sachin Sir
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/18/08    Time: 7:33p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/18/08    Time: 5:06p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:37p
//Created in $/Leap/Source/Templates/Teacher/ScStudentActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:19p
//Created in $/Leap/Source/Templates/Teacher/StudentActivity
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