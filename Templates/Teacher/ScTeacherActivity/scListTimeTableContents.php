<?php 
//it contain the template of time table 
//
// Author :Dipanjan Bhattacharjee
// Created on : 30-07-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
                <td valign="top">My Time Table </td>
                <td valign="top" align="right">   
               <form action="" method="" name="searchForm">
      
                  </form>
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
              <td align="left">
             <!-- 
              <table cellpadding="0" cellspacing="0" border="0" width="50%" >
              <tr>    
                <td width="10%" class="contenttab_internal_rows"><nobr><b>Subject: </b></nobr></td>
                <td width="20%" class="padding" align="left">
                <select size="1" class="selectfield" name="subject" id="subject" onchange="classPopulate();" >
                <option value="">Select Subject</option>
                  <?php
                   //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                   //echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                ?>
                </select>
              </td>
                <td width="10%" class="contenttab_internal_rows"><nobr><b>Section: </b></nobr></td>
                <td width="20%" class="padding" align="left" onchange="classPopulate();">
                <select size="1" class="selectfield" name="section" id="section" >
                 <option value="">Select Section</option>
                  <?php
                   //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                   //echo HtmlFunctions::getInstance()->getTeacherSectionData();
                 ?>
                </select>
              </td>
              <td width="10%" class="contenttab_internal_rows" align="left"><nobr><b>Class: </b></nobr></td>
                <td width="20%" class="padding" align="left">
                <select size="1" class="selectfield" name="classes" id="classes" >
                 <option value="">All Class</option>
                 </select>
              </td>
              <td  align="left" style="padding-left:5px" class="padding" >
                 <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
               </td>
               <td  align="right" style="padding-left:5px" class="contenttab_internal_rows" >
                 <a onClick="getTimeTableData(2);return false" style="cursor:pointer"><nobr><b>[ Display All ]</b></nobr></a>
               </td>
               
             </tr>
             </table>
            --> 
            </td>
            </tr>
            <tr><td height="3px"></td></tr> 
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title" width="200" align="left">Time Table :</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_row" valign="top" >
             <!--Time Table Data Template-->
              <div id="results">
               <table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid" style="border:2px solid #C7C7C7">
               <?php  
                $recordCount = count($teacherRecordArray);
                if($recordCount >0 && is_array($teacherRecordArray) ) { 
                   //  $j = $records;
                 echo '<tr class="rowheading">
                        <td width="5%" valign="middle" align="center" ><b>Period</b>
                        <td valign="middle" align="center" width="10%"><b>Monday</b></td>
                        <td valign="middle" align="center" width="10%" ><b>Tuesday</b></td>
                        <td valign="middle" align="center" width="10%"><b>Wednesday</b></td>
                        <td valign="middle" align="center" width="10%"><b>Thursday</b></td>
                        <td valign="middle" align="center" width="10%"><b>Friday</b></td>
                        <td valign="middle" align="center" width="10%"><b>Saturday</b></td>
                        <td valign="middle" align="center" width="10%"><b>Sunday</b></td>
                      </tr>';
                    $pno="";
                    $preMatch=1;   //check previous matched date
                    $fl=0;         //check whether createBlankTd() is called first time or not
                    $el=0;  
                    $str1="";$str2="";$str3="";
                    //$z=1;
                              //check number of times control goes to else part
                    //print_r($teacherRecordArray);
                    //exit(0);
                    for($i=0; $i<$recordCount; $i++ ) {
                      for ($j=1; $j<8 ;$j++) {
                         if($pno!=strip_slashes($teacherRecordArray[$i]['periodNumber']) and $pno==""){
                              $preMatch=1;
                              $fl=0;
                              $bg = $bg =='trow0' ? 'trow1' : 'trow0';      
                              echo '<tr class='.$bg.'>';        
                              echo  '<td align="center" class="timtd"><b>'.strip_slashes($teacherRecordArray[$i]['periodNumber']).'</b><br />'.strip_slashes($teacherRecordArray[$i]['pTime']).'</td>';
                              $pno=strip_slashes($teacherRecordArray[$i]['periodNumber']);   
                          }
                         elseif($pno!=strip_slashes($teacherRecordArray[$i]['periodNumber']) and $pno!=""){
                             $bg = $bg =='trow0' ? 'trow1' : 'trow0';
                             
                             echo '<td valign="top" valign="middle" align="center" class="timtd">';
                             echo $str1.'<br />'.$str3.$str4.'<br />'.$str2;
                             echo '</td>';
                             $str1="";$str2="";$str3="";$str4="";
                             
                             echo  createBlankTD(7-$preMatch);  
                             $preMatch=1;   
                             $fl=0;
                             $el=0;
                            
                             echo '</tr><tr><td height="3px" colspan="8"></td></tr><tr class='.$bg.'>';        
                             echo  '<td align="center" class="timtd"><b>'.strip_slashes($teacherRecordArray[$i]['periodNumber']).'</b><br />'.strip_slashes($teacherRecordArray[$i]['pTime']).'</td>';
                             $pno=strip_slashes($teacherRecordArray[$i]['periodNumber']);    
                         }
                        if (trim($teacherRecordArray[$i]['daysOfWeek'])==$j){
                            if($teacherRecordArray[$z]['periodNumber'] == $teacherRecordArray[$i]['periodNumber'] && $teacherRecordArray[$z]['daysOfWeek'] == $teacherRecordArray[$i]['daysOfWeek']){
                            }
                            else{
                                if($fl==0){
                                    echo createBlankTD($el);
                                    $fl=1;
                                 }
                                else{
                                   echo '<td valign="top" valign="middle" align="center" class="timtd">';
                                   echo $str1.'<br />'.$str3.$str4.'<br />'.$str2;
                                   echo '</td>';
                                   $str1="";$str2="";$str3="";$str4="";
                                   
                                   echo createBlankTD($j-$preMatch-1); 
                                }
                                
                                
                            }
                            //if($teacherRecordArray[$z]['daysOfWeek']!=$teacherRecordArray[$i]['daysOfWeek'] ){
                                //echo '<td valign="top" valign="middle" align="center" class="timtd">';
                                $str1=strip_slashes($teacherRecordArray[$i]['subjectCode']);
                                $str2=strip_slashes($teacherRecordArray[$i]['roomAbbreviation']);
                                if(strip_slashes($teacherRecordArray[$i]['activity'])==QUIZ)
                                 {
                                     $str4='<br /><b><font color="red">Quiz</font></b>';
                                 }
                               // echo strip_slashes($teacherRecordArray[$i]['subjectCode']).'<br>'.strip_slashes($teacherRecordArray[$i]['roomAbbreviation']).'<br />';
                            //}
                            
                           // echo strip_slashes($teacherRecordArray[$i]['abbr']).' '.strip_slashes($teacherRecordArray[$i]['sectionType']).'<br>';
                            if($str3==""){
                             $str3=strip_slashes($teacherRecordArray[$i]['abbr']).' '.strip_slashes($teacherRecordArray[$i]['sectionType']);   
                            }
                           else{
                               $str3 =$str3." , ".strip_slashes($teacherRecordArray[$i]['abbr']).' '.strip_slashes($teacherRecordArray[$i]['sectionType']);   
                           }  

                            $preMatch=$j;
                            $el=0;
                            $z=$i;
                         } 
                       else{
                                                      
                          $el++; 
                       }
                     }
                   }
                  if($pno==strip_slashes($teacherRecordArray[$i-1]['periodNumber']) and $pno!=""){
                    
                    echo '<td valign="top" valign="middle" align="center" class="timtd">';       
                    echo $str1.'<br />'.$str3.$str4.'<br />'.$str2;
                    echo '</td>';
                    $str1="";$str2="";$str3="";$str4="";
                         
                    echo  createBlankTD(7-$preMatch);  
                  }           
                }
               else{
                   echo '<tr><td colspan="8" align="center">No record found</td></tr>';
                }
                ?>                 
               </table>
              </div>
            <!--Time Table Data Template Ends-->           
             </td>
          </tr>
          
          </table>
        </td>
    </tr>
    
    </table>

	
<?php
//$History: scListTimeTableContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 11/20/08   Time: 3:55p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Corrected 'Quiz' display logic
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 11/19/08   Time: 12:41p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Chaned css for showing QUIZ
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 11/19/08   Time: 12:38p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Added Code for displaying activity as QUIZ in teacher timetable
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 10/03/08   Time: 3:28p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Modified table design to solve display problem
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 10/01/08   Time: 1:29p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Corrected the problem of more than two section in one period
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/22/08    Time: 2:53p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/17/08    Time: 12:43p
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
