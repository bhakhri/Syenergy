<?php 

//
//This file creates DashBoard for Teacher Module 
//
// Author :Dipanjan Bhattacharjee
// Created on : 12.07.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php 
    require_once(BL_PATH . "/UtilityManager.inc.php");                    
    require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">  <strong>Welcome  <?php echo $sessionHandler->getSessionVariable('EmployeeName'); ?> ,</strong></td>
                 
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" height="400">
             <tr>
                <td class="contenttab_border" height="20">
                     <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                        <td valign="middle" width="50%" class="content_title">Dashboard: </td>
                        <td valign="middle" align="right" class="content_title">
                            <?php
                              $condition='';
                              $condition = " WHERE userId='".$sessionHandler->getSessionVariable('UserId')."'";
                              $totalRecord = CommonQueryManager::getInstance()->getUserLastLogin($condition);
                              if($totalRecord[0]['dateTimeIn']!='') {
                                echo "Last Login:&nbsp;".UtilityManager::formatDate($totalRecord[0]['dateTimeIn'],true);
                              }
                            ?>
                        &nbsp;&nbsp;</td>
                        </tr>
                    </table>
                </td>
             </tr>
             <tr>
              <td class="contenttab_row" valign="top" >
            
            <div id="div_Outer">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" >
          <tr><td class="padding_top" align="center"><b><?php echo $greetingMsg.'&nbsp;&nbsp;'.$timeTableAlert; ?></b> </td></tr> 
           <tr>
            <td valign="top"  align="center" >
            <table width="971" border="0" >
                <tr>
            <td width="209" scope="col" valign="top" align="center">
                <?php
                $limit=5; //numver of rows to be displayed
                
                 //*************Used For Creating*********
                 //floatingDiv_Create('div_Alerts','Attendance Last Taken On');
                 require_once(BL_PATH.'/HtmlFunctions.inc.php');
                 echo HtmlFunctions::getInstance()->tableBlueHeader('Attendance Last Taken On','width=300' ,'height=150','align=center');
                ?>                 
                <table width="100%" style="height:150px" border="0">
                <tr>
                 <td colspan="2" align="left" style="padding-left:10px" valign="top">
                     <?php
                     $recordCount = count($attendanceNotTakenRecordArray);
                     if($recordCount >0 && is_array($attendanceNotTakenRecordArray) ) { 
                     ?>
                     <table width="100%"  border="0" cellspacing="5">
                     <?php    
                     echo "<ul class=teacher_ul_class>";    
                     for($i=0; $i<$recordCount; $i++ ) {
                       //$bg = $bg =='row0' ? 'row1' : 'row0';
                     if($attendanceNotTakenRecordArray[$i]['attendanceId']!=-1){  
                     $title="Attendance Last Taken On  ".UtilityManager::formatDate(trim_output(strip_slashes($attendanceNotTakenRecordArray[$i]['dated']),20))." For  ".trim_output(strip_slashes($attendanceNotTakenRecordArray[$i]['subjectName'])." [ ".strip_slashes($attendanceNotTakenRecordArray[$i]['subjectCode'])." ]",50,2);   
                     ?>
                     <input type="hidden" id="toDate<?php echo $attendanceNotTakenRecordArray[$i]['attendanceId']; ?>" value="<?php echo strip_slashes($attendanceNotTakenRecordArray[$i]['dated']); ?>" >
                     <?php
                     echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top"><a href="" name="bubble" onclick="showAttendanceDetails('.$attendanceNotTakenRecordArray[$i]['attendanceId'].');return false;" title="'.$title.'" ><li>For  '
                         .trim_output(strip_slashes($attendanceNotTakenRecordArray[$i]['subjectCode']),50).
                         " On "
                         .UtilityManager::formatDate(trim_output(strip_slashes($attendanceNotTakenRecordArray[$i]['dated']),20)).
                        '</li></a></td> 
                        </tr>';
                      }
                     else{
                        $title="Attendance Not Taken For  ".trim_output(strip_slashes($attendanceNotTakenRecordArray[$i]['subjectName'])." [ ".strip_slashes($attendanceNotTakenRecordArray[$i]['subjectCode'])." ]",50,2);   
                     ?>
                     <?php
                     echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top"><a href="" onclick="return false;" name="bubble" title="'.$title.'" ><li>Attendance Not Taken For  '
                         .trim_output(strip_slashes($attendanceNotTakenRecordArray[$i]['subjectCode']),50).
                         '</li></a></td> 
                        </tr>';
                      }
                    
                     } 
                    echo "</ul>"; 
                    for($i=$recordCount; $i<$limit; $i++ ) {     
                     echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top"></td></tr>';    
                    } 
                    ?>
                    </table>
                    <?php
                    echo '<tr><td colspan="2" align="right" style="padding-right:10px">&nbsp;</td></tr>'; 
                   }
                   else {
                     echo '<tr><td colspan="2" align="center">No Alerts</td></tr>';
                   }
                    ?>
                  </table>
                <?php 
                 echo HtmlFunctions::getInstance()->tableBlueFooter();
                // floatingDiv_Close(); 
                  //floatingDiv_Close(); 
                 //*************End of Div*********
                ?>
                
              </td>

               
            <td width="209" scope="col" valign="top" align="center">
                <?php
                 //*************Used For Creating*********
                 //floatingDiv_Create('div_Notices','Notices');
                 require_once(BL_PATH.'/HtmlFunctions.inc.php');
                 echo HtmlFunctions::getInstance()->tableBlueHeader('Notices','width=300' ,'height=150','align=center');
                ?>                 
                <table width="100%" style="height:150px" border="0">
                <tr>
                 <td colspan="2" align="left" style="padding-left:10px" valign="top">
                     <?php
                     $recordCount = count($noticeRecordArray);
                     if($recordCount >0 && is_array($noticeRecordArray) ) { 
                     ?>
                     <table width="100%"  border="0" cellspacing="5">
                     <?php    
                     echo "<ul class=teacher_ul_class>";
                     for($i=0; $i<$recordCount; $i++ ) {
                       //$bg = $bg =='row0' ? 'row1' : 'row0';
                       $attactment=strip_slashes($noticeRecordArray[$i]['noticeAttachment']);
                       $pic=split('_-',strip_slashes($noticeRecordArray[$i]['noticeAttachment'])); 
                      $title="From : ".UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleFromDate']))." To : ".UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleToDate']))."     ".trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeText'])),500,1); 
                      echo '<tr class="'.$bg.'">';
                      echo '<td valign="top" class="padding_top" align="left"><a href="" name="bubble" onclick="showNoticeDetails('.$noticeRecordArray[$i]['noticeId'].');return false;" title="'.$title.'" >
                         <li>';
                     if(isset($pic[1])){     
                      echo   '<u><i>'.trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeSubject'])),35).'</i></u>';
                     }
                    else{
                        echo trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeSubject'])),35);
                    } 
                      echo '</li></a></td><td valign="top" align="right" height="3px">';
                       if(isset($pic[1])){   ?>
                        <img src="<?php echo IMG_HTTP_PATH ?>/download.gif" title="<?php echo $pic[1]; ?>" onclick="download('<?php echo $attactment?>')" /> 
                        <?php
                        }        
                        
                        echo ' </td></tr>';
                     }
                    echo "</ul>";
                    for($i=$recordCount; $i<$limit; $i++ ) {     
                     echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top">&nbsp;</td></tr>';    
                    } 
                    ?>
                    </table>
                    <?php
                    echo '<tr><td colspan="2" align="right" style="padding-right:10px"><a href="scListInstituteNotice.php"><u>All Notices</u></a></td></tr>'; 
                   }
                   else {
                     echo '<tr><td colspan="2" align="center">No Notice</td></tr>';
                   }
                    ?>
                  </table>
                <?php 
                 echo HtmlFunctions::getInstance()->tableBlueFooter();
                // floatingDiv_Close(); 
                  //floatingDiv_Close(); 
                 //*************End of Div*********
                ?>
                
              </td>
               <td width="209" scope="col" valign="top" align="center">
                <?php
                 //*************Used For Creating*********
                 //floatingDiv_Create('div_Notices','Notices');
                 require_once(BL_PATH.'/HtmlFunctions.inc.php');
                 echo HtmlFunctions::getInstance()->tableBlueHeader('Events','width=300' ,'height=150','align=center');
                ?>                 
                <table width="100%" style="height:150px" border="0">
                <tr>
                 <td colspan="2" align="left" style="padding-left:10px" valign="top">
                     <?php
                     $recordCount = count($eventRecordArray);
                     if($recordCount >0 && is_array($eventRecordArray) ) { 
                     ?>
                     <table width="100%"  border="0" cellspacing="5">
                     <?php    
                     echo "<ul class=teacher_ul_class>";
                     for($i=0; $i<$recordCount; $i++ ) {
                       //$bg = $bg =='row0' ? 'row1' : 'row0';
                     $title="From : ".UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['startDate']))." To : ".UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['endDate']))."     ".trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($eventRecordArray[$i]['shortDescription'])),100,1);   
                     echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" align="left"><a href="" name="bubble" onclick="showEventDetails('.$eventRecordArray[$i]['eventId'].');return false;" title="'.$title.'" >
                         <li>'
                         .trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($eventRecordArray[$i]['eventTitle'])),25)
                         .'</li></a></td>
                        </tr>';
                     }
                    echo "</ul>";
                    for($i=$recordCount; $i<$limit; $i++ ) {     
                     echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top">&nbsp;</td></tr>';    
                    } 
                    ?>
                    </table>
                    <?php
                    echo '<tr><td colspan="2" align="right" style="padding-right:10px"><a href="scListInstituteEvent.php"><u>All Events</u></a></td></tr>'; 
                   }
                   else {
                     echo '<tr><td colspan="2" align="center">No Events</td></tr>';
                   }
                    ?>
                 </table>
                <?php 
                 echo HtmlFunctions::getInstance()->tableBlueFooter();
                // floatingDiv_Close(); 
                  //floatingDiv_Close(); 
                 //*************End of Div*********
                ?>
                
              </td>
         </tr>
         <tr>
         <td height="300" colspan="3" align="center" valign="top" style="padding-top:10px;">
        
        <?php
         //*************Used For Creating*********
         // floatingDiv_Create('div_Messages','Messages');
         require_once(BL_PATH.'/HtmlFunctions.inc.php');
         echo HtmlFunctions::getInstance()->tableBlueHeader('Messages','width=950','height=150','align=center');
        ?>                    
         <table width="100%" height="100%" border="0" >
          <tr>
           <td height="114" valign="top" >
            <form name="searchForm" action="" method="post">
            <table border="0" cellpadding="0" cellspacing="0">
            <tr>
             <td align="right">
             <!--
              <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
               <input type="image"  name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search"   style="margin-bottom: -5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
              --> 
             </td>
            </tr>
            <tr><td height="3px"></td></tr>
            <tr>
            <td align="left" width="100%">
             <div id="results" style="width:900px">  
               <table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid">
               <tr class="rowheading">
                    <td width="3%" class="searchhead_text">&nbsp;&nbsp;<b>#</b></td>
                    <!--
                    <td width="100" class="searchhead_text"><b>Sender </b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="200" class="searchhead_text"><b>Subject</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=subject')" /></td>
                    <td width="400" class="searchhead_text"><b>Synopsis</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=message')" /></td>
                    <td width="100" class="searchhead_text"><b>Date</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=dated')" /></td>
                   --> 
                   <td width="100" class="searchhead_text"><b>Sender </b></td>
                    <td width="200" class="searchhead_text"><b>Subject</b></td>
                    <td width="400" class="searchhead_text"><b>Synopsis</b></td>
                    <td width="100" class="searchhead_text"><b>Date</b></td>
                 </tr>
                <?php
                $recordCount = count($msgRecordArray);
                if($recordCount >0 && is_array($msgRecordArray) ) { 
                  for($i=0; $i<$recordCount; $i++ ) {
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                     echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top"><a href="#"  onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].');return false;">'.($records+$i+1).'</a></td>
                        <td class="padding_top" valign="top"><a href="#"  onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].');return false;">'.strip_slashes($msgRecordArray[$i]['userName']).'</a></td>
                        <td class="padding_top" valign="top"><a href="#"  onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].');return false;">'.trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($msgRecordArray[$i]['subject'])),200).'</a></td>
                        <td class="padding_top" valign="top"><a href="#"  onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].');return false;">'.trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($msgRecordArray[$i]['message'])),700).'</a></td>
                        <td class="padding_top" valign="top"><a href="#"  onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].');return false;">'.UtilityManager::formatDate(strip_slashes($msgRecordArray[$i]['dated'])).'</a></td>
                        </tr>';
                    }
              
                   echo '<tr><td colspan="5" align="right" style="padding-right:10px"></td></tr>';                   
                   echo '<tr><td colspan="5" align="right" style="padding-right:10px"><a href="scListAdminMessages.php"><u>All Messages</u></a></td></tr>';                   
                }
                
                else {
                    echo '<tr><td colspan="5" align="center">No record found</td></tr>';
                }
                ?>                 
                 </table>
              </div>
              </td>
              </tr>
             </table> 
             </form>                  
           </td>
          </tr>
         </table>
            <?php 
            echo HtmlFunctions::getInstance()->tableBlueFooter();
              //floatingDiv_Close(); 
             //*************End of Div*********
            ?>       
                  
                  </td>
                </tr>
              </table>          
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

<!--Start Attendance  Div-->
<?php floatingDiv_Start('divAttendance','Attendance '); ?>
<form name="AttendanceForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Subject : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="subject" name="subject" class="inputbox" style="border:0px" readonly="true" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Class: </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="tclass" name="tclass" class="inputbox" style="border:0px" readonly="true" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Date : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="date" name="date" class="inputbox" style="border:0px" readonly="true" /></td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
       <input type="image" name="editclose_icon" src="<?php echo IMG_HTTP_PATH;?>/close_icon.gif"  onclick="javascript:hiddenFloatingDiv('divAttendance');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form> 
<?php floatingDiv_End(); ?>


<!--Start Notice  Div-->
<?php floatingDiv_Start('divNotice','Notice '); ?>
<form name="NoticeForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows" ><nobr><b>Subject : </b></nobr></td>
        <td width="79%" class="padding" >
      <!--   <textarea id="noticeSubject" cols="20" rows="2" readonly="true"></textarea>  -->
         <div id="noticeSubject" style="overflow:auto; width:500px; height:15px" >  </div> 
        </td>
    </tr>
    <tr>
        <td width="21%" valign="top" align="right"><nobr><b>Notice : </b></nobr></td>
        <td width="79%" valign="top">
       <!--  <textarea id="noticeText" cols="20" rows="4" readonly="true"></textarea>     -->
        <div id="noticeText" style="overflow:auto; width:500px; height:400px" >  </div>  
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>From : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="visibleFromDate" name="visibleFromDate" class="inputbox" style="border:0px" readonly="true" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>To: </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="visibleToDate" name="visibleToDate" class="inputbox" style="border:0px" readonly="true" /></td>
    </tr>
    
<tr>
    <td height="5px"></td></tr>
    <!--
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
       <input type="image" name="editclose_icon" src="<?php echo IMG_HTTP_PATH;?>/close_icon.gif"  onclick="javascript:hiddenFloatingDiv('divNotice');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>     -->
</table>
</form> 
<?php floatingDiv_End(); ?>


<!--Start Event  Div-->
<?php floatingDiv_Start('divEvent','Event '); ?>
<form name="EventForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
    <tr>
        <td width="21%" align="right"><nobr><b>Event : </b></nobr></td>
        <td width="79%" >
         <!--<textarea id="eventTitle" cols="20" rows="1" readonly="true"></textarea>          -->
         <div id="eventTitle" style="overflow:auto; width:400px; height:15px" >  </div> 
        </td>
    </tr>
    <tr>
    <td colspan="2" valign="top" >
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
    <tr>
        <td  width="21%" class="contenttab_internal_rows"><nobr><b>From : </b></nobr></td>
        <td class="padding" align="left"><input type="text" id="startDate" name="startDate" class="inputbox" style="border:0px;width:85px" readonly="true"  /></td>
        <td  class="contenttab_internal_rows"><nobr><b>To: </b></nobr></td>
        <td  class="padding"><input type="text" id="endDate" name="endDate" class="inputbox" style="border:0px;width:85px" readonly="true" /></td>
      </tr>
     </table>
    </td>
    </tr>
    <tr>
        <td width="21%"  style="padding-left:5px" valign="top" align="right"><nobr><b>Description(S) : </b></nobr></td>
        <td width="79%" >
     <!--    <textarea id="shortDescription" cols="20" rows="1" readonly="true"></textarea>      -->
     <div id="shortDescription" style="overflow:auto; width:400px; height:100px" >  </div> 
        </td>
    </tr>
    <tr>
        <td width="21%"  style="padding-left:5px" valign="top" align="right"><nobr><b>Description(L) : </b></nobr></td>
        <td width="79%" valign="top">
         <!--<textarea id="longDescription" cols="20" rows="3" readonly="true"></textarea>       -->
        <div id="longDescription" style="overflow:auto; width:400px; height:300px" >  </div>
        </td>
    </tr>
    
    
<!--<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
       <input type="image" name="editclose_icon" src="<?php echo IMG_HTTP_PATH;?>/close_icon.gif"  onclick="javascript:hiddenFloatingDiv('divEvent');return false;" />
        </td>
</tr>   -->  
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>


<!--Start Message  Div-->
<?php floatingDiv_Start('divMessage','Message '); ?>
<form name="MessageForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Subject : </b></nobr></td>
        <td width="79%" >
         <!--<textarea id="subject" name="subject" cols="20" rows="1" readonly="true"></textarea>  -->
         <div id="subject1" name="subject1" style="border:0px;height:15px;width:400px;overflow:auto"></div>   
        </td>
    </tr>
    <tr>
        <td width="21%" style="padding-left:5px" valign="top" align="right"><nobr><b>Message : </b></nobr></td>
        <td width="79%" >
         <!--<textarea id="message" name="message" cols="20" rows="3" readonly="true"></textarea>-->
         <div id="message" name="message" style="border:0px;height:400px;width:400px;overflow:auto"></div>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Dated : </b></nobr></td>
        <td width="79%" class="padding">
         <input type="text" id="dated" name="dated" class="inputbox" style="border:0px" readonly="true" />
        </td>
    </tr>
   
<tr>
    <td height="5px"></td></tr>
    <!--
    <tr>
    <td align="center" style="padding-right:10px" colspan="2">
       <input type="image" name="editclose_icon" src="<?php echo IMG_HTTP_PATH;?>/close_icon.gif"  onclick="javascript:hiddenFloatingDiv('divMessage');return false;" />
        </td>
</tr>           -->
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form> 
<?php floatingDiv_End(); ?>
          

<?php
    // $History: scIndex.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/28/09    Time: 5:48p
//Updated in $/LeapCC/Templates/Teacher
//Display Last Login in dashBoard
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 11/19/08   Time: 6:26p
//Updated in $/Leap/Source/Templates/Teacher
//Changed trim_output parameter value in events div
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 11/07/08   Time: 12:42p
//Updated in $/Leap/Source/Templates/Teacher
//Corrected bubble tool tips error
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 10/21/08   Time: 11:48a
//Updated in $/Leap/Source/Templates/Teacher
//Added alert for time table changes
//
//*****************  Version 11  *****************
//User: Arvind       Date: 10/18/08   Time: 1:58p
//Updated in $/Leap/Source/Templates/Teacher
//modify the notice,events and messages  dispaly div
//
//*****************  Version 10  *****************
//User: Arvind       Date: 10/07/08   Time: 11:49a
//Updated in $/Leap/Source/Templates/Teacher
//added download functionality  for notice 
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/30/08    Time: 12:26p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/29/08    Time: 5:48p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/25/08    Time: 6:40p
//Updated in $/Leap/Source/Templates/Teacher
//Corrected date format problem
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/24/08    Time: 1:36p
//Updated in $/Leap/Source/Templates/Teacher
//Corrected date range in event showing criteria
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/19/08    Time: 1:32p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/17/08    Time: 4:14p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/16/08    Time: 5:09p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/16/08    Time: 12:58p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:37p
//Created in $/Leap/Source/Templates/Teacher
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 9/09/08    Time: 4:53p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Teacher
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/27/08    Time: 3:31p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/18/08    Time: 5:52p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/18/08    Time: 4:14p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/18/08    Time: 3:11p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/16/08    Time: 4:10p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/09/08    Time: 6:07p
//Updated in $/Leap/Source/Templates/Teacher
//Removed search option
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/06/08    Time: 6:53p
//Updated in $/Leap/Source/Templates/Teacher
//Done modifications as discussed in the demo session
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/31/08    Time: 7:27p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/30/08    Time: 3:36p
//Updated in $/Leap/Source/Templates/Teacher
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/22/08    Time: 10:47a
//Created in $/Leap/Source/Templates/Teacher
?>

